<?php
class ModStore{

	function ModStore($id=0){
		global $db, $_SESSION;
		$this->site = new Site($_SESSION['siteid']);
		$this->hasOptions = false;
		if($id){
			$this->id = $id;
			$q = $db->query("select * from modules where id='".$this->id."'");
			$row = $q->next_row();
			$m = unserialize(base64_decode($row->serialized));
			if(gettype($m)!='object') $m = unserialize($row->serialized); //fix for websites already using old serialize
			$this->products_module = $m->products_module;
			$this->checkout = $m->checkout;
			$this->request = $m->request;
		}
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function add($request){
		global $db;
//		$request['products'] = "Products";
		if(!$this->check($request)){return false;}
		if(!$this->checkStockBase($request)){return false;}

		$this->title = "Store";
		//initializing page where the code would be added
		$this->page = new Page($request['page']);

		//create the db tables
		$db->query("use ".$this->site->db_name);
		executeSqlFromFile(BASEPATH."/modules/store/insert/db.sql", $this->site->db_prefix);
		//include the options to stock table
		$this->updateStockTable($request);

		//copy all the required files (bubbles, classes, styles, scripts, admin)
		dir_copy("modules/store/copy",$this->site->dir);

		//getting the complex object module selected
		for($i=0;$i<sizeof($this->site->modules);$i++){
			if($this->site->modules[$i]['module']=='complexObject'&&$this->site->modules[$i]['m']->tbl==$request['products']){
				$this->products_module = $this->site->modules[$i]['m'];
				break;
			}
		}

		//modify the code that's been copied
			//insert all the required stuff to templates and other files of FRONTEND

				//class/base/StockBase.php (options adjustments)
				$this->updateStockBase($request);

				//class/base/StockBase.php (options adjustments)
				$this->updateShoppingCartBase();

				//class/base/OrderBase.php (update of the products class name)
				$this->updateOrderBase();

				//store.api.php update
				$this->updateStoreAPI($request);

				//assets/css/codes/style.css add new code at the bottom
				$this->updateCSS();

				//assets/js/codes/scripts.js add code before last }); string
				$this->updateJS();

				//class/includes/classes.php add sc order paypal stock classes includes
				$this->add2classes();

				//class/Products.php add $this->stock to constructor. Products.php is not a real name of the class the real name could be obtained from $request['products'] complex object
				//IMPORTANT we are adding to Products.php not to ProductsBase.php to not mess the ComplexObject in case it would be updated in future
				$this->updateProducts($request);

				//templates/codes/store_add2cart.tpl options adjustment
				$this->updateAdd2CartTpl($request);

				//templates/codes/co_product.tpl and templates/codes/co_product_details.tpl need to have the include of the store_add2cart.tpl
				$this->updateProductsBubble($request);

				//templates/codes/store_review_order.tpl adjustment of the products_id param..., same for store_shopping_cart.tpl
				$this->updateProductIDs($request);

				//templates/checkout.tpl insertion of checkout bubble
				$this->insertCheckoutBubble($request);

				//templates/header.tpl insertion of the shopping cart bubble
				$this->updateHeaderTpl();

				//header.php ShoppingCart init
				$this->updateHeaderPhp();

				//templates/page_template.tpl reinsertion of the products CO bubble if it was removed
				$this->reinsertCObubble();

				//page.php if no CO code add CO code for bubble
				$this->reinsertCOcode();

		//insert all the required stuff to templates and other files of ADMIN AREA
				$this->updateAdminOrdersActionsTpl();
				//admin/assets/css/style.css add code to the end
				$this->updateAdminStyle();
				//admin/templates/header.tpl include of the store.js
				$this->includeAdminJS();
				//admin/templates/menu.tpl add Orders menu item
				$this->updateAdminMenu();
				//admin/templates/orders_action.tpl replace co.php?classname=Products with real products Complex Object class
				$this->updateAdminCOReference();
				//admin/templates/products_action.tpl add include stock_management.tpl
				$this->includeAdminStockManagement();
				//admin/templates/stock_management.tpl options!
				$this->updateAdminStockManagementOptions($request);
				//admin/header.php including the ShoppingCart object
				$this->updateAdminHeader();

		//add module to codes DB
		$this->request = $request;
		$db->query("use codes");
		if($db->query("insert into modules set pageid='".$this->page->id."', module='store', title='".addslashes($this->title)."', serialized='".base64_encode(serialize($this))."'")){
			$this->id = $db->insert_id();
		}else{
			$this->error = "Can not add module to codes DB.";return false;
		}

		return true;
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function check($request){
		global $db;
		//check that products complex object is selected
		if(!$request['products']){$this->error = "Please select the products from the list.";return false;}
		if(!$request['checkout']){$this->error = "Please select the checkout page template from the list.";return false;}

		//check if this module is already installed (happens in module.php)

		//check if there are already such tables: countries, order_details, order_shipping, orders, stock
		$db->query("use ".$this->site->db_name);
		$q = $db->query("show tables like '".$this->site->db_prefix."orders'");
		if($q->num_rows()>0){	$this->error = "'orders' DB table already exists!"; return false;}
		$q = $db->query("show tables like '".$this->site->db_prefix."order_details'");
		if($q->num_rows()>0){	$this->error = "'order_details' DB table already exists!"; return false;}
		$q = $db->query("show tables like '".$this->site->db_prefix."order_shipping'");
		if($q->num_rows()>0){	$this->error = "'order_shipping' DB table already exists!"; return false;}
		$q = $db->query("show tables like '".$this->site->db_prefix."stock'");
		if($q->num_rows()>0){	$this->error = "'stock' DB table already exists!"; return false;}
		$q = $db->query("show tables like '".$this->site->db_prefix."countries'");
		if($q->num_rows()>0){	$this->error = "'countries' DB table already exists!"; return false;}
		$q = $db->query("show tables like '".$this->site->db_prefix."states'");
		if($q->num_rows()>0){	$this->error = "'states' DB table already exists!"; return false;}
		return true;
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function checkStockBase($request){
		//looping through options entered if no title ignoring that option
		$this->hasOptions = false;
		for($i=1;$i<=$request['ttl'];$i++){
			if($request['title'.$i]){
				//we have an option for product
				$this->hasOptions = true;
				if(normalize($request['title'.$i])=='price'||normalize($request['title'.$i])=='qty'||normalize($request['title'.$i])=='id'||normalize($request['title'.$i])=='pid'){
					$this->error = "Option name can't be price or qty or id or pid!";return false;
				}
				if($request['type'.$i]=='select'){
					for($j=1;$j<=$request['optionsttl'.$i];$j++){
						if(strlen($request['optionvalue'.$i."_".$j])<1){$this->error = "All the ".$request['type'.$i]." options should have a value. You can select the text for such values as color because there are very many combinations of it.";return false;}
					}
				}
			}
		}
		return true;
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function updateStockTable($request){
		global $db;
		if($this->hasOptions){
			$db->query("use ".$this->site->db_name);
			$sql = " ADD (";
			for($i=1;$i<=$request['ttl'];$i++){
				if($request['title'.$i]){
					$ot = normalize($request['title'.$i]);//option title
					$sql .= $ot." VARCHAR(255),";
				}
			}
			$sql = substr($sql, 0, strlen($sql)-1);//removed the last coma
			$sql .= ")";
			$db->query("ALTER TABLE ".$this->site->db_prefix."stock ".$sql);
		}
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	function updateStockBase($request){
		if($this->hasOptions){
			$options_code = "";
			$options_code2 = "";
			for($i=1;$i<=$request['ttl'];$i++){
				if($request['title'.$i]){
					$ot = normalize($request['title'.$i]);//option title
					$options_code .= "\$this->unique_options['".$ot."'] = \$this->unique_options['".$ot."']?\$this->unique_options['".$ot."']:array();\n\t\t\t\t\t";
					$options_code .= "if(!in_array(\$row->".$ot.", \$this->unique_options['".$ot."'])&&\$row->".$ot."){\n\t\t\t\t\t\t";
					$options_code .= "	\$this->unique_options['".$ot."'][] = \$row->".$ot.";\n\t\t\t\t\t";
					$options_code .= "}\n\t\t\t\t\t";
					$options_code2 .= "\$this->entries[\$i]['".$ot."'] = \$row->".$ot.";\n\t\t\t\t";
				}
			}
			$search[0] = "\$this->unique_options = array();";
			$search[1] = "//options stuff here";
			$replace[0] = $options_code;
			$replace[1] = $options_code2;
			file_replace($this->site->dir."/class/base/StockBase.php", $search, $replace);
		}
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function updateShoppingCartBase(){
		$search[0] = "Products";
		$replace[0] = $this->products_module->classname;
		file_replace($this->site->dir."/class/base/ShoppingCartBase.php", $search, $replace);
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function updateOrderBase(){
		$search[0] = "Cars(";
		$replace[0] = $this->products_module->classname."(";
		file_replace($this->site->dir."/class/base/OrderBase.php", $search, $replace);
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function updateStoreAPI($request){
		$this->checkout = $request['checkout'];
		$search[0] = "checkout.tpl";
		$replace[0] = $request['checkout'];
		file_replace($this->site->dir."/store.api.php", $search, $replace);
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function updateCSS(){
		//adding new css to the bottom of existing
		$css = file_get_contents($this->site->dir.'/assets/css/codes/codes.css');
		$css .= file_get_contents("modules/store/insert/assets/css/codes/codes.css");
		file_put_contents($this->site->dir.'/assets/css/codes/codes.css', $css);
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function updateJS(){
		$js = file($this->site->dir.'/assets/js/codes/scripts.js');
		$new_code = file_get_contents("modules/store/insert/assets/js/codes/scripts.js");
		//finding the last });
		for($i=0;$i<sizeof($js);$i++){
			if(strstr($js[$i], '})')){
				$index = $i;
			}
		}
		//inserting before the last });
		$done = false;
		for($i=0;$i<sizeof($js);$i++){
			if($index==$i&&!$done){
				$new_js .= $new_code;
				$done = true;
			}
			$new_js .= $js[$i];
		}
		file_put_contents($this->site->dir.'/assets/js/codes/scripts.js', $new_js);
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function add2classes(){
		//update classes with new classes
		$classes = file($this->site->dir.'/class/includes/classes.php');
		$code =  "require_once(BASEPATH.'/class/base/ShoppingCartBase.php');\n";
		$code .= "require_once(BASEPATH.'/class/ShoppingCart.php');\n";
		$code .= "require_once(BASEPATH.'/class/base/PayPal.php');\n";
		$code .=  "require_once(BASEPATH.'/class/base/OrderBase.php');\n";
		$code .= "require_once(BASEPATH.'/class/Order.php');\n";
		$code .= "require_once(BASEPATH.'/class/base/StockBase.php');\n";
		$code .= "require_once(BASEPATH.'/class/Stock.php');\n";
		$updated_classes = "";
		for($i=0;$i<sizeof($classes);$i++){
			if($i==sizeof($classes)-2) $updated_classes .= $code;
			$updated_classes .= $classes[$i];
		}
		file_put_contents($this->site->dir."/class/includes/classes.php", $updated_classes);
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function updateProducts($request){
		$classname = $this->products_module->classname;
		$class = file_get_contents($this->site->dir."/class/".$classname.".php");
		$add = "\$this->stock = new Stock(\$this->id);\n\t";
		//find the contructor
		if(strstr($class, "function ".$classname."(")){
			//if exists add to the end of constructor
				$a = explode("function", $class);
				if(sizeof($a)>2){//if more than one method is overriden in Products.php
					$a = explode("function ".$classname."(", $class);
					//find next function after function Products
					$b = explode("function", $a[1]);
					$c = explode("}", $b[0]);
					$b[0] = "";//clearing the b[0] as we are adding our code to it below
					for($i=0;$i<sizeof($c);$i++){
						if($i==sizeof($c)-1){//last one doesn't have to be closed
							$b[0] .= $c[$i];
						}else{
							if($i==sizeof($c)-2){
								$b[0] .= $c[$i]."\t".$add."}";
							}else{
								$b[0] .= $c[$i]."}";
							}
						}
					}
					$a[1] = "";
					for($i=0;$i<sizeof($b);$i++){
						if($i==sizeof($b)-1){//last one doesn't have to be closed
							$a[1] .= $b[$i];
						}else{
							$a[1] .= $b[$i]."function";
						}
					}
					$new_class = $a[0]."function ".$classname."(".$a[1];
				}else{//if class has only constructor overriden
					//find second } from end and put the code before it
					$b = explode("}",$class);
					$new_class="";
					for($i=0;$i<sizeof($b);$i++){
						if($i==sizeof($b)-1){//last one doesn't have to be closed
							$new_class .= $b[$i];
						}else{
							if($i==sizeof($b)-3){
								$new_class .= $b[$i]."\t".$add."}";
							}else{
								$new_class .= $b[$i]."}";
							}
						}
					}
				}
		}else{
			//else create constructor which calls parent constructor and add code for stock
			$constructor  =  "\tfunction ".$classname."(\$id=0){\n\t\t";
			$constructor .= "parent::".$classname."Base(\$id);\n\t\t";
			$constructor .= $add;
			$constructor .= "}\n\n";
			//add contructor at the end of the class
			$new_class = "";
			$a = explode("}", $class);
			for($i=0;$i<sizeof($a);$i++){
				if($i==sizeof($a)-1) $new_class .= $constructor;
				if($i!=0){
					$new_class .= "}".$a[$i];
				}else{
					$new_class .= $a[$i];
				}
			}
		}
		file_put_contents($this->site->dir."/class/".$classname.".php", $new_class);
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function updateAdd2CartTpl($request){
		$options = "";
		for($i=1;$i<=$request['ttl'];$i++){
			if($request['title'.$i]){
				$ot = normalize($request['title'.$i]);//option title
				$options .= "{if \$stock->unique_options.".$ot."|@count}\n\t\t";
				$options .= $request['title'.$i]."\n\t\t";
				$options .= "<select option=\"".$ot."\" pid=\"{\$id}\">\n\t\t\t";
				$options .= "	{section name=option loop=\$stock->unique_options.".$ot."|@count}\n\t\t\t\t";
				$options .= "		<option {if \$stock->entries[0].".$ot."==\$stock->unique_options.".$ot."[option]}selected=\"selected\"{/if}>{\$stock->unique_options.".$ot."[option]}</option>\n\t\t\t";
				$options .= "	{/section}\n\t\t";
				$options .= "</select><br/>\n\t\t";
				$options .= "{/if}\n\n\t\t\t";
			}
		}
		$search[0] = "<!--store options-->";
		$replace[0] = $options;
		file_replace($this->site->dir."/templates/codes/store_add2cart.tpl", $search, $replace);
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function updateProductsBubble($request){
		$classname = $this->products_module->tbl;
		//insert code to co_product.tpl
		$search[0] = "{include file='codes/co_".$classname."_details.tpl'}";
		$search[1] = "<hr/>";
		$replace[0] = "{include file='codes/co_".$classname."_details.tpl'}\n\t\t\t\t";
		$replace[0] .= "{include file=\"codes/store_add2cart.tpl\" id=\$".$classname."->id}\n";
		$replace[1] = "{include file=\"codes/store_add2cart.tpl\" id=\$".$classname."_all[i].id}\n\t\t\t\t\t";
		$replace[1] .= "<hr/>\n\t\t\t\t";
		file_replace($this->site->dir."/templates/codes/co_".$classname.".tpl", $search, $replace);
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function updateProductIDs($request){
		$classname = $this->products_module->tbl;
		$search[0] = "index.php?products_id=";
		$replace[0] = $this->products_module->page->filename."?".$classname."_id=";
		file_replace($this->site->dir."/templates/codes/store_review_order.tpl", $search, $replace);
		file_replace($this->site->dir."/templates/codes/store_shopping_cart.tpl", $search, $replace);
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function insertCheckoutBubble($request){
//		$template_code = file($this->site->dir."/templates/".$request['checkout']);

		$code = "\t{include file='codes/store_checkout.tpl'}\n";
		$code_used = false;
		$code1 = "\n<div class=\"codes\">\n".$code."</div>\n\n";
		$new_template_code = "";
		for($i=0;$i<sizeof($template_code);$i++){
			//checking if the code already has any codes bubbles
			if(strstr($template_code[$i], 'class="codes"')){
				$new_template_code .= $template_code[$i];
				$new_template_code .= $code;
				$code_used = true;
			}else{
				if(($i==sizeof($template_code)-2)&&!$code_used) $new_template_code .= $code1;
				$new_template_code .= $template_code[$i];
			}
		}
		file_put_contents($this->site->dir."/templates/".$request['checkout'], $new_template_code);
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function updateHeaderTpl(){
		$header = file_get_contents($this->site->dir.'/templates/header.tpl');
		// search for codes_header and put shopping_cart include in there
		if(strstr($header, "codes_header")){
			$search[0] = "codes_header\">\n";
			$replace[0] = "codes_header\">\n\t\t{include file='codes/store_shopping_cart.tpl'}\n";
			file_replace($this->site->dir.'/templates/header.tpl', $search, $replace);
		}else{
		// if not found search for <body and put on the next row after it
			$header = file($this->site->dir.'/templates/header.tpl');
			$code =  "\t<div class=\"codes_header\">\n";
			$code .= "\t\t{include file='codes/store_shopping_cart.tpl'}\n";
			$code .= "\t</div>\n";
			$updated_header = "";
			for($i=0;$i<sizeof($header);$i++){
				if(strstr($header[$i],"<body")){ 
					$updated_header .= $header[$i];
					$updated_header .= $code;
				}else{
					$updated_header .= $header[$i];
				}
			}
			file_put_contents($this->site->dir."/templates/header.tpl", $updated_header);
		}
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function updateHeaderPhp(){
		$search[0] = "?>";
		$replace[0] = "\$cart = new ShoppingCart();\n\$smarty->assign_by_ref(\"cart\",\$cart);\n?>";
		file_replace($this->site->dir."/header.php", $search, $replace);
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function reinsertCObubble(){
		//remove previous include if exists
		$search[0] = "{if \$page=='".$this->page->name."'}{include file='codes/co_".$this->products_module->tbl.".tpl'}{/if}";
		$replace[0] = "";
		file_replace($this->site->dir."/templates/".$this->page->template, $search, $replace);

		//add new include
		$template_code = file($this->site->dir."/templates/".$this->page->template);
		$code = "\t{if \$page=='".$this->page->name."'}{include file='codes/co_".$this->products_module->tbl.".tpl'}{/if}\n";
		$code_used = false;
		$code1 = "\n<div class=\"codes\">\n\t{if \$page=='".$this->page->name."'}{include file='codes/co_".$this->products_module->tbl.".tpl'}{/if}\n</div>\n\n";
		$new_template_code = "";
		if(sizeof($template_code)>2){
			for($i=0;$i<sizeof($template_code);$i++){
				//checking if the code already has any codes bubbles
				if(strstr($template_code[$i], 'class="codes"')){
					$new_template_code .= $template_code[$i];
					$new_template_code .= $code;
					$code_used = true;
				}else{
					if(($i==sizeof($template_code)-2)&&!$code_used) $new_template_code .= $code1;
					$new_template_code .= $template_code[$i];
				}
			}
		}else{
			$new_template_code .= $code1;
			$new_template_code .= $template_code[0].$template_code[1];
		}
		file_put_contents($this->site->dir."/templates/".$this->page->template, $new_template_code);
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function reinsertCOcode(){
		$page = file_get_contents($this->site->dir."/".$this->page->filename);
		$tbl = $this->products_module->tbl;
		$classname = $this->products_module->classname;
		if(!strstr($page,"\$".$tbl." = new ".$classname."(\$_REQUEST['".$tbl."_id']);")){
			//insert code if not exists
			$code = "if(\$_REQUEST['".$tbl."_id']){\n\t";
			$code .= "\$".$tbl." = new ".$classname."(\$_REQUEST['".$tbl."_id']);\n\t";
			$code .= "\$smarty->assign_by_ref(\"".$tbl."\", \$".$tbl.");\n\t";
			$code .= "\$smarty->assign_by_ref(\"request\",\$_REQUEST);\n";
			$code .= "}else{\n\t";
			$code .= "\$".$tbl." = new ".$classname."();\n\t";
			$code .= "\$smarty->assign_by_ref(\"".$tbl."_all\", \$".$tbl."->getAll(\$_REQUEST));\n\t";
			$code .= "\$smarty->assign_by_ref(\"".$tbl."_pagination\", \$".$tbl."->pagination);\n";
			$code .= "}\n";
			if($this->products_module->request['categories']==1){
				for($i=1;$i<=$this->products_module->request['categoriesttl'];$i++){
					$code .= "\$".$this->products_module->request['category'.$i]." = new Category(\"".$this->products_module->request['category'.$i]."\");\n";
					$code .= "\$smarty->assign_by_ref(\"".$this->products_module->request['category'.$i]."\", \$".$this->products_module->request['category'.$i]."->getAll());\n\n";
				}
			}
			$page_code = file($this->site->dir.'/'.$this->page->filename);
			$updated_page = "";
			for($i=0;$i<sizeof($page_code);$i++){
				if(strstr($page_code[$i],"->display(\"".$this->page->template."\");")) $updated_page .= $code;
				$updated_page .= $page_code[$i];
			}
			file_put_contents($this->site->dir."/".$this->page->filename, $updated_page);
		}
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function updateAdminStyle(){
		$css = file_get_contents($this->site->dir.'/admin/assets/css/style.css');
		$css .= file_get_contents("modules/store/insert/admin/assets/css/style.css");
		file_put_contents($this->site->dir.'/admin/assets/css/style.css', $css);
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function includeAdminJS(){
		$header = file_get_contents($this->site->dir."/admin/templates/header.tpl");
		$new_header = str_replace("</head>", "\t<script type=\"text/javascript\" src=\"assets/js/store.js\"></script>\n</head>", $header);
		file_put_contents($this->site->dir."/admin/templates/header.tpl", $new_header);
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function updateAdminMenu(){
		$menu = file($this->site->dir.'/admin/templates/menu.tpl');
		$newitem = "\t\t<li {if \$menu=='orders'}class=\"selected1\"{/if}><a href=\"orders.php\">Orders<br/><div class=\"tri\"></div></a>\n\t\t\t";
		$newitem .= "<ul>\n\t\t\t\t";
		$newitem .= "<li><a href=\"orders.php\">Browse</a></li>\n\t\t\t\t";
//		$newitem .= "<li><a href=\"orders.php?action=add\">Add New</a></li>\n\t\t\t";
		$newitem .= "</ul>\n\t\t</li>\n";
		$done = false;
		for($i=0;$i<sizeof($menu);$i++){
			if(strstr($menu[$i], '<ul>')&&!$done){
				$menu[$i] .= $newitem;
				$done = true;
			}
			$new_menu .= $menu[$i];
		}
		file_put_contents($this->site->dir.'/admin/templates/menu.tpl', $new_menu);
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function updateAdminCOReference(){
		$classname = $this->products_module->classname;
		$tpl = file_get_contents($this->site->dir."/admin/templates/orders_action.tpl");
		$new_tpl = str_replace("co.php?classname=Products", "co.php?classname=".$classname, $tpl);
		file_put_contents($this->site->dir."/admin/templates/orders_action.tpl", $new_tpl);
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function includeAdminStockManagement(){
		$tbl = $this->products_module->tbl;
		$old = file_get_contents($this->site->dir."/admin/templates/".$tbl."_action.tpl");
		$new = str_replace("{include file=\"footer.tpl\"}", "\t{include file=\"stock_management.tpl\"}\n\n{include file=\"footer.tpl\"}", $old);
		file_put_contents($this->site->dir."/admin/templates/".$tbl."_action.tpl", $new);
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function updateAdminStockManagementOptions($request){
		$options = "";
		$stock_options = "";
		$options_default = "";
		for($i=1;$i<=$request['ttl'];$i++){
			if($request['title'.$i]){
				$ot = normalize($request['title'.$i]);
				$title = $request['title'.$i];
				$options .= "<th>".$title."</th>\n\t\t\t";
				if($request['type'.$i]=='select'){
					$stock_options .= "<td>\n\t\t\t\t\t\t<select option=\"".$ot."\">\n\t\t\t\t\t\t\t";
					$options_default .= "<td>\n\t\t\t\t\t\t<select option=\"".$ot."\">\n\t\t\t\t\t\t\t"; 
					for($j=1;$j<=$request['optionsttl'.$i];$j++){
						$stock_options .= "<option {if \$obj->stock->entries[s].".$ot."=='".$request['optionvalue'.$i.'_'.$j]."'}selected=\"selected\"{/if}>".$request['optionvalue'.$i.'_'.$j]."</option>\n\t\t\t\t\t\t\t";
						$options_default .= "<option>".$request['optionvalue'.$i.'_'.$j]."</option>";
					}
					$stock_options .= "</select>\n\t\t\t\t\t</td>\n\t\t\t\t\t";
					$options_default .= "</select>\n\t\t\t\t\t</td>\n\t\t\t\t\t";
				}else{
					$stock_options .= "<td><input type=\"text\" option=\"".$ot."\" value=\"{\$obj->stock->entries[s].".$ot."}\"/></td>\n\t\t\t\t\t";
					$options_default .= "<td><input type=\"text\" option=\"".$ot."\"</td>\n\t\t\t\t\t";
				}
			}
		}
		$search[0] = "<th>Color</th>";
		$search[1] = "<td>stock options</td>";
		$search[2] = "<td>options default</td>";
		$replace[0] = $options;
		$replace[1] = $stock_options;
		$replace[2] = $options_default;
		file_replace($this->site->dir."/admin/templates/stock_management.tpl", $search, $replace);
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function updateAdminHeader(){
		$search[0] = "?>";
		$replace[0] = "\$cart = new ShoppingCart();\n\$smarty->assign_by_ref(\"cart\",\$cart);\n\n?>";
		file_replace($this->site->dir."/admin/header.php", $search, $replace);
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function updateAdminOrdersActionsTpl(){
		$search[0] = "co.php?classname=Cars";
		$replace[0] = "co.php?classname=".$this->products_module->classname;
		file_replace($this->site->dir."/admin/templates/orders_action.tpl", $search, $replace);

	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function update($request){
		global $db;
		
	}

}
?>