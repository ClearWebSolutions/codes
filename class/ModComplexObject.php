<?php
class ModComplexObject{

	function ModComplexObject($id=0){
		global $db, $_SESSION;
//$this->site = new Site($_SESSION['siteid']);
$site = new Site($_SESSION['siteid']);
$this->db_name = $site->db_name;
$this->db_prefix = $site->db_prefix;
$this->categories = $site->categories;
$this->dir = $site->dir;
		if($id){
			$this->id = $id;
			$db->query("use codes");
			$q = $db->query("select * from modules where id='".$this->id."'");
			$row = $q->next_row();
			$this->title = $row->title;

			$m = unserialize(base64_decode($row->serialized));
			if(gettype($m)!='object') $m = unserialize($row->serialized); //fix for websites already using old serialize
			$this->page = $m->page;
			$this->tbl = $m->tbl;
			$this->classname = $m->classname;
			$this->multilanguage = $m->multilanguage;
			$this->request = $m->request;
			$this->prev_mod_gallery = $m->mod_gallery;

			//init the temp gallery module
			$gal = new ModGallery();
			$this->galleries = $gal->getAll($this->tbl, 0);
		}
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function add($request){
		global $db, $_SESSION, $reserved_words;
		$this->tbl = normalize($request['db_tbl']);
		$this->classname = ucfirst($this->tbl);
		$this->multilanguage = $request['multilanguage'];
		$this->request = $request;
		if(!$this->check($request)) return false;
		if($request['gallery']==1){
			//we have to do this check before we have started adding stuff to the db
			$this->mod_gallery = new ModGallery();
			if($this->mod_gallery->check($request)==false){
				$this->error  = $this->mod_gallery->error;
				return false;
			}
			//create gallery2object records and galleries if needed
			//this goes before "use this->db_name" as it ends with "use codes"...
			$request['object_id']=0;//it's a template for all the complex objects with table this->tbl
			$request['object_table'] = $this->tbl;
			$this->mod_gallery->add($request);
		}

		//initializing page where the code would be added
		$this->page = new Page($request['page']);

		//switch to site's db
		$db->query("use ".$this->db_name);

		//create the db table for future objects
		$sql = $this->_createTableSQL($request);
		$db->query($sql);
//somehow below returns false even if the query was successful
//		if(!$db->query($sql)){
//			$this->error = "Something is wrong, can not create the database table. Please check the fields entered once again.";return false;
//		}

		//create the class for objects
		file_put_contents($this->dir."/class/".$this->classname.".php", "<?\nclass ".$this->classname." extends ".$this->classname."Base{\n\n}\n?>");
		file_put_contents($this->dir."/class/base/".$this->classname."Base.php", $this->_createClass($request));

		//update classes with new class
		$classes = file($this->dir.'/class/includes/classes.php');
		$code =  "require_once(BASEPATH.'/class/base/".$this->classname."Base.php');\n";
		$code .= "require_once(BASEPATH.'/class/".$this->classname.".php');\n";
		$updated_classes = "";
		for($i=0;$i<sizeof($classes);$i++){
			if($i==sizeof($classes)-2) $updated_classes .= $code;
			$updated_classes .= $classes[$i];
		}
		file_put_contents($this->dir."/class/includes/classes.php", $updated_classes);

		//update the admin menu
		$menu = file($this->dir.'/admin/templates/menu.tpl');
		$newitem = "\t\t<li {if \$menu=='".$this->classname."'}class=\"selected1\"{/if}><a href=\"co.php?classname=".$this->classname."\">".$request['title']."<br/><div class=\"tri\"></div></a>\n\t\t\t";
		$newitem .= "<ul>\n\t\t\t\t";
		$newitem .= "<li><a href=\"co.php?classname=".$this->classname."\">Browse</a></li>\n\t\t\t\t";
		$newitem .= "<li><a href=\"co.php?classname=".$this->classname."&action=add\">Add New</a></li>\n\t\t\t";
		$newitem .= "</ul>\n\t\t</li>\n";
		$done = false;
		for($i=0;$i<sizeof($menu);$i++){
			if(strstr($menu[$i], '<ul>')&&!$done){
				$menu[$i] .= $newitem;
				$done = true;
			}
			$new_menu .= $menu[$i];
		}
		file_put_contents($this->dir.'/admin/templates/menu.tpl', $new_menu);

		//copy the admin co.php script if it doesn't exist
		if(!file_exists($this->dir."/admin/co.php")){
			dir_copy("modules/complexObject/admin/",$this->dir."/admin/");
		}
		if($request['categories']==1){
			dir_copy("modules/complexObject/templates/",$this->dir."/templates/");
		}

		//create the admin add/edit .tpl
		$this->_createAdminAddTpl($request);

		//create the admin list .tpl
		$this->_createAdminListTpl($request);

		//create and add the bubble to the page
		$this->_addBubbleCode($request);

		//setting back to codes database
		$db->query("use codes");

		$this->title = $request['title'];

		//add module and it's data to codes db
		if($db->query("insert into modules set pageid='".$this->page->id."', module='complexObject', title='".addslashes($this->title)."', serialized='".base64_encode(serialize($this))."'")){
		$this->id = $db->insert_id();
		}else{
			$this->error = "can't add module";return false;
		}

		return true;
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function update($request){
		global $db, $_SESSION;
		if(!$this->id){$this->error = "Module is not initialized.";return false;}
		$tbl = normalize($request['db_tbl']);
		$classname = ucfirst($tbl);

		if(!$this->check($request)) return false;

		if($request['gallery']==1){
			//we have to do this check before we have started adding stuff to the db
			$this->mod_gallery = new ModGallery();
			if($this->mod_gallery->check($request)==false){
				$this->error  = $this->mod_gallery->error;
				return false;
			}

			//create gallery2object records and galleries if needed
			//this goes before "use this->db_name" as it ends with "use codes"...
			$request['object_id']=0;//it's a template for all the complex objects with table this->tbl
			$request['object_table'] = $tbl;
			$this->mod_gallery->add($request);

			//if was a gallery before
			if($this->request['gallery']==1){
				$db->query("use ".$this->db_name);
				for($j=1;$j<=sizeof($this->prev_mod_gallery->gid);$j++){
					//breaking the linkage for the co and gallery
					$db->query("delete from ".$this->db_prefix."gallery2object where gallery_id='".$this->prev_mod_gallery->gid[$j]."' and object_table='".$this->tbl."'");
					//delete the old gallery from galleries
					$db->query("delete from ".$this->db_prefix."galleries where id='".$this->prev_mod_gallery->gid[$j]."'");
					//delete the old images
					$db->query("delete from ".$this->db_prefix."images where id='".$this->prev_mod_gallery->gid[$j]."'");
				}
			}
		}

		//switch to site's db
		$db->query("use ".$this->db_name);

		//create the db table for future objects
		$db->query("DROP TABLE IF EXISTS ".$this->db_prefix.$this->tbl);
		$sql = $this->_createTableSQL($request);
		$db->query($sql);

		//create the class for objects
		if($this->classname!=$classname){
			@unlink($this->dir."/class/".$this->classname.".php");
			@unlink($this->dir."/class/base/".$this->classname."Base.php");
			file_put_contents($this->dir."/class/".$classname.".php", "<?\nclass ".$classname." extends ".$classname."Base{\n\n}\n?>");
		}
		file_put_contents($this->dir."/class/base/".$classname."Base.php", $this->_createClass($request));

		//update classes with new class
		if($this->classname!=$classname){
			$search[0] = "require_once(BASEPATH.'/class/base/".$this->classname."Base.php');";
			$search[1] = "require_once(BASEPATH.'/class/".$this->classname.".php');";
			$replace[0] = "";
			$replace[1] = "";
			file_replace($this->dir."/class/includes/classes.php", $search, $replace);
			$classes = file($this->dir.'/class/includes/classes.php');
			$code =  "require_once(BASEPATH.'/class/base/".$classname."Base.php');\n";
			$code .= "require_once(BASEPATH.'/class/".$classname.".php');\n";
			$updated_classes = "";
			for($i=0;$i<sizeof($classes);$i++){
				if($i==sizeof($classes)-2) $updated_classes .= $code;
				$updated_classes .= $classes[$i];
			}
			file_put_contents($this->dir."/class/includes/classes.php", $updated_classes);
		}

		//update the admin menu
		if($request['title']!=$this->title){
			unset($search);unset($replace);
			$search[0] = ">".$this->title."<br/>";
			$replace[0] = ">".$request['title'];
			file_replace($this->dir."/admin/templates/menu.tpl", $search, $replace);
		}
		if($this->classname!=$classname){
			unset($search);unset($replace);
			$search[0] = "'".$this->classname."'";
			$search[1] = "classname=".$this->classname;
			$replace[0] = "'".$classname."'";
			$replace[1] = "classname=".$classname;
			file_replace($this->dir."/admin/templates/menu.tpl", $search, $replace);
		}

		if($tbl!=$this->tbl){
			@unlink($this->dir."/admin/templates/".$this->tbl."_action.tpl");
			@unlink($this->dir."/admin/templates/".$this->tbl.".tpl");
		}
		//create the admin add/edit .tpl
		$this->_createAdminAddTpl($request);

		//create the admin list .tpl
		$this->_createAdminListTpl($request);

		//create and add the bubble to the page
		$this->_addBubbleCode($request);

		//setting back to codes database
		$db->query("use codes");

		$this->title = $request['title'];
		$this->tbl = normalize($request['db_tbl']);
		$this->classname = ucfirst(normalize($request['db_tbl']));
		$this->multilanguage = $request['multilanguage'];
		$this->request = $request;

		//add module and it's data to codes db
		$db->query("update modules set module='complexObject', title='".addslashes($this->title)."', serialized='".base64_encode(serialize($this))."' where id='".$this->id."'");

		return true;
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function check($request){
		global $db, $reserved_words;
		$tbl = normalize($request['db_tbl']);
		$classname = ucfirst($tbl);
		if(!$request['page']&&$request['action']=='add'){		$this->error = "Please select the page first!";return false;}
		if(!$request['title']){		$this->error = "Please enter the title!";return false;}
		if(!$request['db_tbl']){	$this->error = "Please enter the DB table name!";return false;}
		if(($this->tbl!=$tbl&&$request['action']=='edit')||($request['action']=='add')){
			$db->query("use ".$this->db_name);
			$q = $db->query("show tables like '".$this->db_prefix.$tbl."'");
			if($q->num_rows()>0){	$this->error = "Such DB table already exists please select another name!"; return false;}
			$db->query("use codes");
		}

		//check of the admin area templates and classes as they might have been created by developer
		if($this->classname!=$classname){
			if(file_exists($this->dir."/class/".$classname.".php")){$this->error = "File /class/".$classname.".php already exists, please choose another DB table name as it's also used as a name for the class."; return false;}
			if(file_exists($this->dir."/class/base/".$classname."Base.php")){$this->error = "File /class/base/".$classname."Base.php already exists, please choose another DB table name as it's also used as a name for the class."; return false;}
		}
		if($this->tbl!=$tbl){
			if(file_exists($this->dir."/admin/templates/".$tbl.".tpl")){$this->error = "File /admin/templates/".$tbl.".tpl already exists, please choose another DB table name as it's also used as a name for the template."; return false;}
			if(file_exists($this->dir."/admin/templates/".$tbl."_action.tpl")){$this->error = "File /admin/templates/".$tbl."_action.tpl already exists, please choose another DB table name as it's also used as a name for the template."; return false;}
		}

		//check each field title to be not empty
		for($i=1;$i<=$request['ttl'];$i++){
				//check the field title
				if(!$request['title'.$i]){$this->error = "Please enter all the field titles or delete the unneeded ones.";return false;}
				//check the db field title
				if(!$request['dbfield'.$i]){$this->error = "Please enter all the DB fields or delete the unneeded ones.";return false;}
				//reserved words check				
				if(in_array(strtolower($request['dbfield'.$i]), $reserved_words)){					$this->error = "'".$request['dbfield'.$i]."' is a reserved word and can't be used as a DB field. Please change";return false;}
				//check each DB Field name so it's a good name for db field and object property
				if(!preg_match("/^[A-Za-z][A-Za-z0-9_]*$/",$request['dbfield'.$i])){$this->error = "DB field name is too complex or doesn't match the allowed database column names. Same field would be used as a variable name!";return false;}
				//check the radio and select options
				$len = 0;
				if($request['type'.$i]=='radio'){
					for($j=1;$j<=$request['optionsttl'.$i];$j++){
						if(!$request['optionname'.$i."_".$j]){$this->error = "All the ".$request['type'.$i]." options should have a name.";return false;}
						if(strlen($request['optionvalue'.$i."_".$j])<1){$this->error = "All the ".$request['type'.$i]." options should have a value.";return false;}
						$len = strlen($request['optionvalue'.$i.'_'.$j])>$len?strlen($request['optionvalue'.$i.'_'.$j]):$len;
					}
					if($request['dblength'.$i]&&$request['dblength'.$i]<$len){
						$this->error = "Your database length is less than your option values length for field <b>".$request["title".$i]."</b>";
						return false;
					}
				}
		}

		//categories check
		if($request['categories']==1){
			$ok = false;
			for($i=1;$i<=$request['categoriesttl'];$i++){
				if($request['category'.$i]){$ok = true;}
			}
			if(!$ok){$this->error = "Please choose the categories table!"; return false;}
		}

		return true;
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function _createTableSQL($request){
		$tbl = normalize($request['db_tbl']);
		$sql = "CREATE TABLE ".$this->db_prefix.$tbl." (";
		$sql .= "id int(11) NOT NULL AUTO_INCREMENT,";
		$sql .= "lang_parent int(11) DEFAULT NULL,";
		$sql .= "language varchar(2) DEFAULT NULL,";
		$sql .= "ordr int(11) DEFAULT '0',";
		$sql .= "locked tinyint(1) DEFAULT '0',";
		$sql .= "new tinyint(1) DEFAULT '0',";
		for($i=1;$i<=$request['ttl'];$i++){
				$sql  .= $request['dbfield'.$i]." ".$request['dbtype'.$i];
				if($request['dblength'.$i]) $sql .= "(".$request['dblength'.$i].")";
				$sql .= " DEFAULT ";
				if($request['dbdefault'.$i]){ $sql .= "'".$request['dbdefault'.$i]."'";}else{$sql .= "NULL";}
				$sql .= ",";
		}
		if($request['categories']==1){
			for($i=1;$i<=$request['categoriesttl'];$i++){
				if($request['category'.$i]){
					$sql .= $request['category'.$i]." INT(11) DEFAULT NULL,";
				}
			}
		}
		$sql .= "PRIMARY KEY (id)";
		$sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		return $sql;
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function _createClass($request){
		$tbl = normalize($request['db_tbl']);
		$classname = ucfirst($tbl);
		$code = "<?\n";
		$code .= "class ".$classname."Base{\n\n\t";

		//constructor
		$code .= $this->_createClass_constructor($request);

		//getAll
		$code .= $this->_createClass_getAll($request);

		//add
		$code .= $this->_createClass_add($request);

		//check
		$code .= $this->_createClass_check($request);

		//update
		$code .= $this->_createClass_update($request);

		//update locked
		$code .= $this->_createClass_updateLocked($request);

		//update order
		$code .= $this->_createClass_updateOrdr($request);

		//delete
		$code .= $this->_createClass_delete($request);

		//end of class
		$code .= "}\n";
		$code .= "?>";
		return $code;
	}
	
	function _createClass_constructor($request){
		$tbl = normalize($request['db_tbl']);
		$classname = ucfirst($tbl);
		$code = "function ".$classname."Base(\$id=0){\n\t\t";
		$code .= "global \$db, \$settings, \$_SESSION;\n\t\t";
		$code .= "if(\$id){\n\t\t\t";
		$code .= "\$this->lang_parent = \$id;\n\t\t\t";
		$code .= "\$q = \$db->query(\"select * from \".DBPREFIX.\"".$tbl." where lang_parent='\".\$this->lang_parent.\"' and language='\".\$settings->language.\"'\");\n\t\t\t";
		$code .= "\$row = \$q->next_row();\n\t\t\t";
		$code .= "\$this->id = \$row->id;\n\t\t\t";
		$code .= "\$this->locked = \$row->locked;\n\t\t\t";
		$code .= "\$this->ordr = \$row->ordr;\n\t\t\t";
			//create all the dbfields here use stripslashes for strings
			$cke = false;
			for($i=1;$i<=$request['ttl'];$i++){
				if($request['type'.$i]=='html'){$cke=true;}
				if($request['dbtype'.$i]=="blob"||$request['dbtype'.$i]=="varchar"){
					$vars .= "\$this->".$request['dbfield'.$i]." = stripslashes(\$row->".$request['dbfield'.$i].");\n\t\t\t";
				}else if($request['type'.$i]=='date'){
					$vars .= "\$date = explode(\"-\",\$row->".$request['dbfield'.$i].");\n\t\t\t";
					$vars .= "if(\$date[1]){\$this->".$request['dbfield'.$i]." = \$date[1].\"/\".\$date[2].\"/\".\$date[0];}else{\$this->".$request['dbfield'.$i]." = \"\";}\n\t\t\t";
				}else{
					$vars .= "\$this->".$request['dbfield'.$i]." = \$row->".$request['dbfield'.$i].";\n\t\t\t";
				}
			}
		$code .= $vars."\n\t\t\t\n\t\t\t";
		
		if($request['categories']==1){
			for($i=1;$i<=$request['categoriesttl'];$i++){
				if($request['category'.$i]){
					$code .= "\$this->".$request['category'.$i]." = new Category('".$request['category'.$i]."', \$row->".$request['category'.$i].");\n\t\t\t";
					$code .= "\$this->".$request['category'.$i]."_all = \$this->".$request['category'.$i]."->getAll(0);\n\t\t\t";
					$code .= "\$this->".$request['category'.$i]."_parents = \$this->".$request['category'.$i]."->getPathArray(\$row->".$request['category'.$i].");\n\t\t\t";
				}
			}
		}
		
		if($request['gallery']==1){
			$code .= "\$q = \$db->query(\"select * from \".DBPREFIX.\"gallery2object where object_id='\".\$this->id.\"' and object_table='".$tbl."' and locked='0' order by ordr asc\");\n\t\t\t";
			$code .= "foreach(\$q->result() as \$row){\n\t\t\t\t";
			$code .= "\$g = new Gallery(\$row->gallery_id, \$row->id);\n\t\t\t\t";
			$code .= "\$g->title = \$row->gallery_title;\n\t\t\t\t";
			$code .= "\$g->multi = \$row->multi;\n\t\t\t\t";
			$code .= "\$this->galleries[] = \$g;\n\t\t\t";
			$code .= "}\n\n\t\t\t";
		}
		
		
		$code .= "//for admin\n\t\t\t";
		$code .="if(isset(\$_SESSION['admin'])){\n\t\t\t\t";
		//in case we have the html input
		if($cke){
			$code .= "\$_SESSION['login'] = 'adminIDHereToProtectCKFinder';//this is used in CKFinder/config.php if you'll store the adminID in another var just change it there\n\t\t\t\t";
			$code .= "require_once(BASEPATH.'/admin/editor/ckeditor.php');\n\t\t\t\t";
			$code .= "\$e = new CKeditor();\n\n\t\t\t\t";
		}
			$code .= "for(\$i=0;\$i<sizeof(\$settings->languages);\$i++){\n\t\t\t\t\t";
			$code .= "\$lang = \$settings->languages[\$i]['id'];\n\t\t\t\t\t";
			$code .= "\$q = \$db->query(\"select * from \".DBPREFIX.\"".$tbl." where lang_parent='\".\$this->id.\"' and language='\".\$lang.\"'\");\n\t\t\t\t\t";
			$code .= "if(\$q->num_rows()){\$row = \$q->next_row();}else{\$row = false;}\n\t\t\t\t\t";
			$code .= "\$this->admin[\$lang]['id'] = \$row?\$row->id:\$row;\n\t\t\t\t\t";
			for($i=1;$i<=$request['ttl'];$i++){
				if($request['type'.$i]=='text'||$request['type'.$i]=='password'){
					$code .= "\$this->admin[\$lang]['".$request['dbfield'.$i]."'] = fix_quotes(\$row->".$request['dbfield'.$i].");\n\t\t\t\t\t";
				}else if($request['type'.$i]=='html'){
					$code .= "\$this->admin[\$lang]['".$request['dbfield'.$i]."'] = \$e->editor(\"".$request['dbfield'.$i]."-\".\$lang, stripslashes(\$row->".$request['dbfield'.$i]."));\n\t\t\t\t\t";
				}else if($request['type'.$i]=='date'){
					$code .= "\$date = explode(\"-\",\$row->".$request['dbfield'.$i].");\n\t\t\t\t\t";
					$code .= "if(\$date[1]){\$this->admin[\$lang]['".$request['dbfield'.$i]."'] = \$date[1].\"/\".\$date[2].\"/\".\$date[0];}else{\$this->admin[\$lang]['".$request['dbfield'.$i]."'] = \"\";}\n\t\t\t\t\t";
				}else{
					$code .= "\$this->admin[\$lang]['".$request['dbfield'.$i]."'] = stripslashes(\$row->".$request['dbfield'.$i].");\n\t\t\t\t\t";
				}
			}
			$code = substr($code, 0, strlen($code)-1);
			$code .= "}\n\t\t\t";
			$code .= "}\n\t\t";
		$code .= "}\n\t";
		$code .= "}\n\n";

		return $code;
	}
	
	function _createClass_getAll($request){
		$tbl = normalize($request['db_tbl']);
		$classname = ucfirst($tbl);

		$code = "///////////////////////////////////////////////////////////////////////////////////////////////////////////////\n\n\t";
		$code .= "function getAll(\$request=array()){\n\t\t";
		$code .= "global \$db, \$settings;\n\t\t";
		$code .= "\$a = explode(\$_SERVER['HTTP_HOST'], URL);\n\t\t";
		$code .= "if(strstr(\$_SERVER['REQUEST_URI'], \$a[1].'/admin/')||strstr(\$_SERVER['PHP_SELF'], '/admin/')){\n\t\t\t";
		$code .= "\$admin=true;\n\t\t\t";
		$code .= "\$environment = 'admin';\n\t\t";
		$code .= "}else{\n\t\t\t";
		$code .= "\$admin=false;\n\t\t\t";
		$code .= "\$environment = 'frontend';\n\t\t";
		$code .= "}\n\t\t";
		$code .= "//if we enter the page from another page we need to clear the session\n\t\t";
		$code .= "if(!\$request['order_by']&&!\$request['order']&&!\$request['page']&&!\$request['search']) \$_SESSION[\$environment.'_".$tbl."'] = '';\n\n\t\t";
		$code .= "if(\$request['order_by']){\n\t\t\t";
		$code .= "\$this->order_by = \$request['order_by'];\n\t\t\t";
		$code .= "if(\$_SESSION[\$environment.'_".$tbl."']['order_by']==\$this->order_by&&\$_SESSION[\$environment.'_".$tbl."']['order']=='asc'&&!\$request['order']){	\$request['order']='desc';}\n\t\t\t";
		$code .= "if(\$_SESSION[\$environment.'_".$tbl."']['order_by']==\$this->order_by&&\$_SESSION[\$environment.'_".$tbl."']['order']=='desc'&&!\$request['order']){	\$request['order']='asc';}\n\t\t";
		$code .= "}else{\n\t\t\t";
		$code .= "if(\$_SESSION[\$environment.'_".$tbl."']['order_by']){\$this->order_by = \$_SESSION[\$environment.'_".$tbl."']['order_by'];}else{\$this->order_by = 'id';}\n\t\t";
		$code .= "}\n\t\t";
		$code .= "if(\$request['order']){\n\t\t\t";
		$code .= "\$this->order = \$request['order'];\n\t\t";
		$code .= "}else{\n\t\t\t";
		$code .= "if(\$_SESSION[\$environment.'_".$tbl."']['order']){\$this->order = \$_SESSION[\$environment.'_".$tbl."']['order'];}else{\$this->order = 'asc';}\n\t\t";
		$code .= "}\n\t\t";
		$code .= "if(\$request['page']){\n\t\t\t";
		$code .= "\$this->page = \$request['page'];\n\t\t";
		$code .= "}else{\n\t\t\t";
		$code .= "if(\$_SESSION[\$environment.'_".$tbl."']['page']){\$this->page = \$_SESSION[\$environment.'_".$tbl."']['page'];}else{\$this->page = 1;}\n\t\t";
		$code .= "}\n\t\t";
		$code .= "if(\$request['search']){\n\t\t\t";
		$code .= "\$this->search = \$request['search'];\n\t\t";
		$code .= "}else{\n\t\t\t";
		$code .= "if(\$_SESSION[\$environment.'_".$tbl."']['search']){\$this->search = \$_SESSION[\$environment.'_".$tbl."']['search'];}else{\$this->search = '';}\n\t\t";
		$code .= "}\n\t\t";
		for($i=1;$i<=$request['ttl'];$i++){
			if($request['type'.$i]=='date'&&$request['searchable'.$i]){
				$code .= "if(\$request['".$request['dbfield'.$i]."_from']){\$this->".$request['dbfield'.$i]."_from = \$request['".$request['dbfield'.$i]."_from'];}else{\n\t\t\t";
				$code .= "if(\$_SESSION[\$environment.'_".$tbl."']['".$request['dbfield'.$i]."_from']){\$this->".$request['dbfield'.$i]."_from = \$_SESSION[\$environment.'_".$tbl."']['".$request['dbfield'.$i]."_from'];}\n\t\t";
				$code .= "}\n\t\t";
				$code .= "if(\$request['".$request['dbfield'.$i]."_to']){\$this->".$request['dbfield'.$i]."_to = \$request['".$request['dbfield'.$i]."_to'];}else{\n\t\t\t";
				$code .= "if(\$_SESSION[\$environment.'_".$tbl."']['".$request['dbfield'.$i]."_to']){\$this->".$request['dbfield'.$i]."_to = \$_SESSION[\$environment.'_".$tbl."']['".$request['dbfield'.$i]."_to'];}\n\t\t";
				$code .= "}\n\t\t";
			}
			if(($request['type'.$i]=='radio'||$request['type'.$i]=='select')&&$request['searchable'.$i]){
				$code .= "if(\$request['".$request['dbfield'.$i]."_search']){\$this->".$request['dbfield'.$i]."_search = \$request['".$request['dbfield'.$i]."_search'];}else{\n\t\t\t";
				$code .= "if(\$_SESSION[\$environment.'_".$tbl."']['".$request['dbfield'.$i]."_search']){\$this->".$request['dbfield'.$i]."_search = \$_SESSION[\$environment.'_".$tbl."']['".$request['dbfield'.$i]."_search'];}else{\$this->".$request['dbfield'.$i]."_search = '';}\n\t\t";
				$code .= "}\n\t\t";
			}
		}
		if($request['categories']==1){
			for($i=1;$i<=$request['categoriesttl'];$i++){
					$code .= "if(\$request['".$request['category'.$i]."']){\n\t\t\t";
					$code .= "\$this->".$request['category'.$i]." = \$request['".$request['category'.$i]."'];\n\t\t";
					$code .= "}else{\n\t\t\t";
					$code .= "if(\$_SESSION[\$environment.'_".$tbl."']['".$request['category'.$i]."']){\$this->".$request['category'.$i]." = \$_SESSION[\$environment.'_".$tbl."']['".$request['category'.$i]."'];}else{\$this->".$request['category'.$i]." = '';}\n\t\t";
					$code .= "}\n\t\t";
			}
		}



		$code .= "\$_SESSION[\$environment.'_".$tbl."']['order_by'] = \$this->order_by;\n\t\t";
		$code .= "\$_SESSION[\$environment.'_".$tbl."']['order'] = \$this->order;\n\t\t";
		$code .= "\$_SESSION[\$environment.'_".$tbl."']['page'] = \$this->page;\n\t\t";
		$code .= "\$_SESSION[\$environment.'_".$tbl."']['search'] = \$this->search;\n\t\t";
		if($request['categories']==1){
			for($i=1;$i<=$request['categoriesttl'];$i++){
				$code .= "\$_SESSION[\$environment.'_".$tbl."']['".$request['category'.$i]."'] = \$this->".$request['category'.$i].";\n\t\t";
			}
		}
		$code .= "\n\t\t";
		for($i=1;$i<=$request['ttl'];$i++){
			if($request['type'.$i]=='date'&&$request['searchable'.$i]){
				$code .= "\$_SESSION[\$environment.'_".$tbl."']['".$request['dbfield'.$i]."_from'] = \$this->".$request['dbfield'.$i]."_from;\n\t\t";
				$code .= "\$_SESSION[\$environment.'_".$tbl."']['".$request['dbfield'.$i]."_to'] = \$this->".$request['dbfield'.$i]."_to;\n\t\t";
			}
			if(($request['type'.$i]=='radio'||$request['type'.$i]=='select')&&$request['searchable'.$i]){
				$code .= "\$_SESSION[\$environment.'_".$tbl."']['".$request['dbfield'.$i]."_search'] = \$this->".$request['dbfield'.$i]."_search;\n\t\t";
			}
		}
		$code .= "if(\$this->search){\n\t\t\t";
			$search = "";
			for($i=1;$i<=$request['ttl'];$i++){
					if($request['searchable'.$i]&&$request['type'.$i]!='date'){
						if($search){
							$search .= " or ".$request['dbfield'.$i]." like '%\".\$this->search.\"%'";
						}else{
							$search .= "\$searchsql = \" and ".$request['dbfield'.$i]." like '%\".\$this->search.\"%' ";
						}
					}
			}
			if($search){
				$search .= "\";\n\t\t";
			}
		$code .= substr($search, 0, strlen($search)-1);
		$code .= "}else{\n\t\t\t";
		$code .= "\$searchsql='';\n\t\t";
		$code .= "}\n\t\t";

		if($request['categories']==1){
			for($i=1;$i<=$request['categoriesttl'];$i++){
				$code .= "if(\$this->".$request['category'.$i]."){\n\t\t\t";
				$code .= "\$searchsql .= \" and ".$request['category'.$i]."='\".\$this->".$request['category'.$i].".\"' \";\n\t\t";
				$code .= "}\n\t\t";
			}
		}

		for($i=1;$i<=$request['ttl'];$i++){
				if($request['searchable'.$i]&&$request['type'.$i]=='date'){
					$code .= "if(\$this->".$request['dbfield'.$i]."_from){\n\t\t\t";
					$code .= "\$date = explode(\"/\",\$this->".$request['dbfield'.$i]."_from); \$date = \$date[2].\"-\".\$date[0].\"-\".\$date[1];\n\t\t\t";
					$code .= "\$searchsql .= \" and ".$request['dbfield'.$i].">='\".\$date.\"' \";\n\t\t";
					$code .= "}\n\t\t";
					$code .= "if(\$this->".$request['dbfield'.$i]."_to){\n\t\t\t";
					$code .= "\$date = explode(\"/\",\$this->".$request['dbfield'.$i]."_to); \$date = \$date[2].\"-\".\$date[0].\"-\".\$date[1];\n\t\t\t";
					$code .= "\$searchsql .= \" and ".$request['dbfield'.$i]."<='\".\$date.\"' \";\n\t\t";
					$code .= "}\n\t\t";
				}
			if(($request['type'.$i]=='radio'||$request['type'.$i]=='select')&&$request['searchable'.$i]){
				$code .= "if(\$this->".$request['dbfield'.$i]."_search){\n\t\t\t";
				$code .= "\$searchsql .= \" and ".$request['dbfield'.$i]."='\".\$this->".$request['dbfield'.$i]."_search.\"' \";\n\t\t";
				$code .= "}\n\t\t";
			}
		}
		$code .= "if(\$admin){\n\t\t\t";
		$code .= "\$sql = \$searchsql.\" order by \".\$this->order_by.\" \".\$this->order.\" limit \".(\$this->page-1)*\$settings->arpp.\", \".\$settings->arpp;\n\t\t";
		$code .= "}else{\n\t\t\t";
		$code .= "\$sql = \$searchsql.\" and locked!='1' order by \".\$this->order_by.\" \".\$this->order.\" limit \".(\$this->page-1)*\$settings->rpp.\", \".\$settings->rpp;\n\t\t";
		$code .= "}\n\t\t";
		$code .= "\$q = \$db->query(\"select * from \".DBPREFIX.\"".$tbl." where new!='1' and language='\".\$settings->language.\"'\".\$sql);\n\t\t";
		$code .= "\$i=0;\n\t\t";
		$code .= "foreach(\$q->result() as \$row){\n\t\t\t";
		$code .= "\$objects[\$i]['id'] = \$row->lang_parent;\n\t\t\t";
		$code .= "\$objects[\$i]['locked'] = \$row->locked;\n\t\t\t";
		$code .= "\$objects[\$i]['ordr'] = \$row->ordr;\n\t\t\t";
			for($i=1;$i<=$request['ttl'];$i++){
				if($request['type'.$i]=='date'){
					$code .= "\$date = explode(\"-\",\$row->".$request['dbfield'.$i].");\n\t\t\t";
					$code .= "if(\$date[1]){\$objects[\$i]['".$request['dbfield'.$i]."'] = \$date[1].\"/\".\$date[2].\"/\".\$date[0];}else{\$objects[\$i]['".$request['dbfield'.$i]."'] = \"\";}\n\t\t\t";
				}else{
					$code .= "\$objects[\$i]['".$request['dbfield'.$i]."'] = stripslashes(\$row->".$request['dbfield'.$i].");\n\t\t\t";
				}
			}

			if($request['categories']==1){
				for($i=1;$i<=$request['categoriesttl'];$i++){
					if($request['category'.$i]&&$request['category'.$i.'_admindisplay']){
						$code .= "\$objects[\$i]['".$request['category'.$i]."'] = new Category('".$request['category'.$i]."',\$row->".$request['category'.$i].");\n\t\t\t";
					}
				}
			}

			if($request['gallery']==1&&$request['gallery_admindisplay']==1){
				$code .= "\$q1 = \$db->query(\"select * from \".DBPREFIX.\"gallery2object where object_id='\".\$row->lang_parent.\"' and object_table='".$tbl."' and locked='0' order by ordr asc\");\n\t\t\t";
				$code .= "\$row1 = \$q1->result();\n\t\t\t";
				$code .= "\$row1 = \$row1[0];\n\t\t\t";
				$code .= "//this is used in admin list display so we actually need only one first image from the gallery, that's why we don't save all the galleries\n\t\t\t";
				$code .= "\$objects[\$i]['gallery'] = new Gallery(\$row1->gallery_id, \$row1->id);\n\t\t\t";
			}
			
		$code .= "\$i++;\n\t\t";
		$code .= "}\n\n\t\t";

		$code .= "\$q = \$db->query(\"select * from \".DBPREFIX.\"".$tbl."  where new!='1' and language='\".\$settings->language.\"' \".\$searchsql);\n\t\t";
		$code .= "\$this->pagination = pagination(\$this->page, (int)\$q->num_rows(), \$admin==true?\$settings->arpp:\$settings->rpp,\"".$classname."\");\n\t\t";
		$code .= "return \$objects;\n\t";
		$code .= "}\n\n";
		return $code;
	}

	function _createClass_check($request){
		$tbl = normalize($request['db_tbl']);
		$classname = ucfirst($tbl);

		$code = "///////////////////////////////////////////////////////////////////////////////////////////////////////////////\n\n\t";
		$code .= "function check(\$request){\n\t\t";
		$code .= "global \$settings;\n\t\t";
		
		if($request['categories']==1){
			for($i=1;$i<=$request['categoriesttl'];$i++){
				if($request['category'.$i]&&$request['category'.$i.'_required']==1){
					foreach($this->categories as $c){
						if($request['category'.$i]==$c['table']){
							$title = $c['title'];
						}
					}
					$code .= "\$".$request['category'.$i]." = '';\n\t\t";
					$code .= "for(\$i=\$request['".$request['category'.$i]."_level'];\$i>=0;\$i--){\n\t\t\t";
					$code .= "if(\$request['".$request['category'.$i]."_level'.\$i]){\n\t\t\t\t";
					$code .= "\$".$request['category'.$i]." = \$request['".$request['category'.$i]."_level'.\$i];\n\t\t\t\t";
					$code .= "break;\n\t\t\t";
					$code .= "}\n\t\t";
					$code .= "}\n\t\t";
					$code .= "if(!\$".$request['category'.$i]."){\$this->error = \"Please select the ".strtolower($title)."!\";return false;}\n\t\t";
				}
			}
		}
		
		$code .= "foreach(\$settings->languages as \$lang){\n\t\t\t";
		$code .= "if(sizeof(\$settings->languages)>1){\n\t\t\t\t";
			for($i=1;$i<=$request['ttl'];$i++){
				if($request['required'.$i]){
					if($request['multilanguage'.$i]==1){
						if($request['type'.$i]=='radio'||$request['type'.$i]=='select'){
							$code .= "if(strlen(\$request['".$request['dbfield'.$i]."-'.\$lang['id']])<1){\$this->error = \$lang['title'].\" ".strtolower($request['title'.$i])." is required!\";return false;}\n\t\t\t\t";
						}else{
							$code .= "if(!\$request['".$request['dbfield'.$i]."-'.\$lang['id']]){\$this->error = \$lang['title'].\" ".strtolower($request['title'.$i])." is required!\";return false;}\n\t\t\t\t";
						}
					}else{
						if($request['type'.$i]=='radio'||$request['type'.$i]=='select'){
							$code .= "if(strlen(\$request['".$request['dbfield'.$i]."'])<1){\$this->error = \"".ucfirst(strtolower($request['title'.$i]))." is required!\";return false;}\n\t\t\t\t";
						}else{
							$code .= "if(!\$request['".$request['dbfield'.$i]."']){\$this->error = \"".ucfirst(strtolower($request['title'.$i]))." is required!\";return false;}\n\t\t\t\t";
						}
					}
				}
			}
		$code = substr($code, 0, strlen($code)-1);
		$code .= "}else{\n\t\t\t\t";
			for($i=1;$i<=$request['ttl'];$i++){
				if($request['required'.$i]){
					if($request['multilanguage'.$i]==1){
						if($request['type'.$i]=='radio'||$request['type'.$i]=='select'){
							$code .= "if(strlen(\$request['".$request['dbfield'.$i]."-'.\$lang['id']])<1){\$this->error = \"".ucfirst($request['title'.$i])." is required!\";return false;}\n\t\t\t\t";
						}else{
							$code .= "if(!\$request['".$request['dbfield'.$i]."-'.\$lang['id']]){\$this->error = \"".ucfirst($request['title'.$i])." is required!\";return false;}\n\t\t\t\t";
						}
					}else{
						if($request['type'.$i]=='radio'||$request['type'.$i]=='select'){
							$code .= "if(strlen(\$request['".$request['dbfield'.$i]."'])<1){\$this->error = \"".ucfirst($request['title'.$i])." is required!\";return false;}\n\t\t\t\t";
						}else{
							$code .= "if(!\$request['".$request['dbfield'.$i]."']){\$this->error = \"".ucfirst($request['title'.$i])." is required!\";return false;}\n\t\t\t\t";
						}
					}
				}
			}
		$code = substr($code, 0, strlen($code)-1);
		$code .= "}\n\t\t";
		$code .= "}\n\t\t";
		$code .= "return true;\n\t";
		$code .= "}\n\n";

		return $code;
	}
	
	function _createClass_updateLocked($request){
		$tbl = normalize($request['db_tbl']);
		$classname = ucfirst($tbl);

		$code = "///////////////////////////////////////////////////////////////////////////////////////////////////////////////\n\n\t";
		$code .= "function updateLocked(\$request){\n\t\t";
		$code .= "global \$db;\n\t\t";
		$code .= "if(\$this->id){\n\t\t\t";
		$code .= "if(\$request['locked']==1){\n\t\t\t\t";
		$code .= "\$db->query(\"update \".DBPREFIX.\"".$tbl." set locked='1' where lang_parent='\".\$this->id.\"'\");\n\t\t\t";
		$code .= "}else{\n\t\t\t\t";
		$code .= "\$db->query(\"update \".DBPREFIX.\"".$tbl." set locked='0' where lang_parent='\".\$this->id.\"'\");\n\t\t\t";
		$code .= "}\n\t\t";
		$code .= "}\n\t";
		$code .= "}\n\n";

		return $code;
	}
	
	function _createClass_updateOrdr($request){
		$tbl = normalize($request['db_tbl']);
		$classname = ucfirst($tbl);

		$code = "///////////////////////////////////////////////////////////////////////////////////////////////////////////////\n\n\t";
		$code .= "function updateOrdr(\$request){\n\t\t";
		$code .= "global \$db;\n\t\t";
		$code .= "if((\$request['ordr']||\$request['ordr']==0)&&\$this->id){\n\t\t\t";
		$code .= "\$db->query(\"update \".DBPREFIX.\"".$tbl." set ordr='\".\$request['ordr'].\"' where lang_parent='\".\$this->id.\"'\");\n\t\t";
		$code .= "}\n\t";
		$code .= "}\n\n";

		return $code;
	}
	
	function _createClass_add($request){
		$tbl = normalize($request['db_tbl']);
		$classname = ucfirst($tbl);

		$code = "///////////////////////////////////////////////////////////////////////////////////////////////////////////////\n\n\t";
		$code .= "function add(\$request){\n\t\t";
		$code .= "global \$db, \$settings;\n\t\t";
		$code .= "\$db->query(\"insert into \".DBPREFIX.\"".$tbl." set new='1', language='\".\$settings->language.\"'\");\n\t\t";
		$code .= "\$this->id = \$db->insert_id();\n\t\t";
		$code .= "\$this->lang_parent = \$this->id;\n\t\t";
		$code .= "\$db->query(\"update \".DBPREFIX.\"".$tbl." set lang_parent='\".\$this->id.\"' where id='\".\$this->id.\"'\");\n\t\t";
		if($request['gallery']==1){
			$code .= "//create all the proper g2o with locked=1\n\t\t";
			$code .= "\$q = \$db->query(\"select * from \".DBPREFIX.\"gallery2object where object_table='".$tbl."' and object_id='0' order by ordr asc\");\n\t\t";
			$code .= "foreach(\$q->result() as \$row){\n\t\t\t";
			$code .= "\$db->query(\"insert into \".DBPREFIX.\"gallery2object set gallery_id='\".\$row->gallery_id.\"', object_id='\".\$this->id.\"', object_table='".$tbl."', ordr='\".\$row->ordr.\"', multi='\".\$row->multi.\"', locked='0', gallery_title='\".\$row->gallery_title.\"'\");\n\t\t";
			$code .= "}\n\t\t";
		}
		$code .= "return \$this->id;\n\t";
		$code .= "}\n\n";

		return $code;
	}

	function _createClass_update($request){
		$tbl = normalize($request['db_tbl']);
		$classname = ucfirst($tbl);

		$sql = "";
		for($i=1;$i<=$request['ttl'];$i++){
			if($request['multilanguage'.$i]==1){
				$sql .= ", ".$request['dbfield'.$i]."='\".addslashes(\$request['".$request['dbfield'.$i]."-'.\$lang]).\"'";
			}else{
				$sql .= ", ".$request['dbfield'.$i]."='\".addslashes(\$request['".$request['dbfield'.$i]."']).\"'";
			}
		}
		if($request['categories']==1){
			for($i=1;$i<=$request['categoriesttl'];$i++){
				if($request['category'.$i]){
					$sql .= ", ".$request['category'.$i]."='\".\$".$request['category'.$i].".\"'";
				}
			}
		}
		
		$code = "///////////////////////////////////////////////////////////////////////////////////////////////////////////////\n\n\t";
		$code .= "function update(\$request){\n\t\t";
		$code .= "global \$db, \$settings;\n\t\t";

		$code .= "if((\$request['action']=='add'||\$request['action']=='edit')&&\$request['sbm']==1){\n\t\t\t";
		$code .= "if(isset(\$_SESSION['admin'])){\n\t\t\t\t";
		$code .= "\$_SESSION['login'] = 'adminIDHereToProtectCKFinder';//this is used in CKFinder/config.php if you'll store the adminID in another var just change it there\n\t\t\t\t";
		$code .= "require_once(BASEPATH.'/admin/editor/ckeditor.php');\n\t\t\t\t";
		$code .= "\$e = new CKeditor();\n\t\t\t\t";
		$code .= "foreach(\$settings->languages as \$lang){\n\t\t\t\t\t";
		$code .= "\$lang = \$lang['id'];\n\t\t\t\t\t";
		for($i=1;$i<=$request['ttl'];$i++){
			if($request['type'.$i]=='html'){
				$code .= "\$this->admin[\$lang]['".$request['dbfield'.$i]."'] = \$e->editor(\"".$request['dbfield'.$i]."-\".\$lang, \$request['".$request['dbfield'.$i]."-'.\$lang]);\n\t\t\t\t";
			}
		}
		$code .= "}\n\t\t\t";
		$code .= "}\n\t\t";
		$code .= "}\n\t\t";

		$code .= "if(!\$this->check(\$request)) return false;\n\t\t";

		if($request['categories']==1){
			for($i=1;$i<=$request['categoriesttl'];$i++){
				if($request['category'.$i]){
					$code .= "\$".$request['category'.$i]." = '';\n\t\t";
					$code .= "for(\$i=\$request['".$request['category'.$i]."_level'];\$i>=0;\$i--){\n\t\t\t";
					$code .= "if(\$request['".$request['category'.$i]."_level'.\$i]){\n\t\t\t\t";
					$code .= "\$".$request['category'.$i]." = \$request['".$request['category'.$i]."_level'.\$i];\n\t\t\t\t";
					$code .= "break;\n\t\t\t";
					$code .= "}\n\t\t";
					$code .= "}\n\t\t";
				}
			}
		}

		$code .= "foreach(\$settings->languages  as \$lang){\n\t\t\t";
		$code .= "\$lang = \$lang['id'];\n\t\t\t";
		for($i=1;$i<=$request['ttl'];$i++){
			if($request['dbtype'.$i]=='date'){
				$code .= "\$date = explode('/',\$request['".$request['dbfield'.$i]."-'.\$lang]);\n\t\t\t";
				$code .= "if(\$date[1]){\$request['".$request['dbfield'.$i]."-'.\$lang] = \$date[2].\"-\".\$date[0].\"-\".\$date[1];}\n\t\t\t";
			}
		}
		$code .= "if(\$this->admin[\$lang]['id']){\n\t\t\t\t";
		$code .= "\$db->query(\"update \".DBPREFIX.\"".$tbl." set new='0'".$sql."  where lang_parent='\".\$this->id.\"' and language='\".\$lang.\"'\");\n\t\t\t";
		$code .= "}else{\n\t\t\t\t";
		$code .= "\$db->query(\"insert into \".DBPREFIX.\"".$tbl." set new='0'".$sql.", lang_parent='\".\$this->id.\"', language='\".\$lang.\"'\");\n\t\t\t";
		$code .= "}\n\t\t";
		$code .= "}\n\t\t";
		$code .= "return true;\n\t";
		$code .= "}\n\n";

		return $code;
	}

	function _createClass_delete($request){
		$tbl = normalize($request['db_tbl']);
		$classname = ucfirst($tbl);
		$code = "///////////////////////////////////////////////////////////////////////////////////////////////////////////////\n\n\t";
		$code .= "function delete(){\n\t\t";
		$code .= "global \$db;\n\t\t";
		$code .= "if(!\$this->id){\$this->error = \"Item is not initialized.\";return false;}\n\t\t";
		$code .= "\$db->query(\"delete from \".DBPREFIX.\"".$tbl." where lang_parent='\".\$this->id.\"'\");\n\t\t";
		if($request['gallery']==1){
			$code .= "//for each gallery associated with an object delete it\n\t\t";
			$code .= "foreach(\$this->galleries as \$gallery){\n\t\t\t";
			$code .= "//1: delete all the gallery images\n\t\t\t";
			$code .= "\$db->query(\"delete from \".DBPREFIX.\"images where g2o_id='\".\$gallery->g2o.\"'\");\n\t\t\t";
			$code .= "//2: delete the gallery folder\n\t\t\t";
			$code .= "@rrmdir(\$gallery->folder);\n\t\t\t";
			$code .= "//3: delete the gallery2object row associated with this gallery\n\t\t\t";
			$code .= "\$db->query(\"delete from \".DBPREFIX.\"gallery2object where id='\".\$gallery->g2o.\"'\");\n\t\t\t";
			$code .= "}\n\t\t";
		}
		$code .= "return true;\n\t";
		$code .= "}\n\n";

		return $code;
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function _createAdminAddTpl($request){
		$tbl = normalize($request['db_tbl']);
		$classname = ucfirst($tbl);

		$code = "{include file=\"header.tpl\"}\n\n\t";
		$code .= "<h1>{\$request.action|ucfirst} ".$request['title']."</h1>\n\n\t";
		$code .= "<form action=\"co.php\" method=\"post\" id=\"actionfrm\">\n\t\t";
		$code .= "<input type=\"hidden\" name=\"classname\" value=\"".$classname."\"/>\n\t\t";
		$code .= "<input type=\"hidden\" name=\"sbm\" value=\"1\"/>\n\t\t";
		$code .= "<input type=\"hidden\" name=\"id\" value=\"{\$obj->id}\"/>\n\t\t";
		$code .= "<input type=\"hidden\" name=\"action\" value=\"{\$request.action}\"/>\n\n\t\t";
		
		$code .= "<div class=\"cnt\" id=\"tabs\">\n\t\t\t";
		if($request['multilanguage']==1){
			$code .= "{if \$settings->languages|@count>1}\n\t\t\t";
			$code .= "<ul id=\"tabs_menu\">\n\t\t\t\t";
			$code .= "{section name=i loop=\$settings->languages|@count}\n\t\t\t\t";
			$code .= "<li><a href=\"#tabs-{\$settings->languages[i].id}\">{\$settings->languages[i].title}</a></li>\n\t\t\t\t";
			$code .= "{/section}\n\t\t\t";
			$code .= "</ul>\n\t\t\t";
			$code .= "{/if}\n\n\t\t\t";
		}
		
		$code .= "<div class=\"outerRow {if !\$error&&!\$success}hidden{/if}\">\n\t\t\t\t";
		$code .= "<div class=\"success-box {if !\$success}hidden{/if}\">\n\t\t\t\t\t";
		$code .= "<div class=\"success\">{\$success}</div>\n\t\t\t\t";
		$code .= "</div>\n\t\t\t\t";
		$code .= "<div class=\"error-box {if !\$error}hidden{/if}\">\n\t\t\t\t\t";
		$code .= "<div class=\"error\">{\$error}</div>\n\t\t\t\t";
		$code .= "</div>\n\t\t\t";
		$code .= "</div>\n\n\t\t\t";


		for($i=1;$i<=$request['ttl'];$i++){
			if($i%2==0){$oe='even';}else{$oe='odd';}

			if($request['multilanguage'.$i]!=1||$request['multilanguage']==0){
				$code .= "{assign var=lang value=\$settings->language}\n\t\t\t";
				$code .= "<div class=\"frmRow ".$oe."\">\n\t\t\t\t\t";
				$code .= "<label>".$request['title'.$i]."</label>\n\t\t\t\t\t";
				$code .= "<div class=\"input\">\n\t\t\t\t\t\t";
				$code .= $this->_createInputField($request, $i)."\n\t\t\t\t\t";
				$code .= "</div>\n\t\t\t";
				$code .= "</div>\n\n\t\t\t";
			}else{
				$code .= "{section name=i loop=\$settings->languages|@count}\n\t\t\t";
				$code .= "{assign var=lang value=\$settings->languages[i].id}\n\t\t\t";
				$code .= "{assign var=vn value='".$request['dbfield'.$i]."-'|cat:\$lang}\n\t\t\t";
				$code .= "<div id=\"tabs-{\$lang}\">\n\t\t\t\t";
					$code .= "<div class=\"frmRow ".$oe."\">\n\t\t\t\t\t";
					$code .= "<label>".$request['title'.$i]."</label>\n\t\t\t\t\t";
					$code .= "<div class=\"input\">\n\t\t\t\t\t\t";
					$code .= $this->_createInputField($request, $i)."\n\t\t\t\t\t";
					$code .= "</div>\n\t\t\t\t";
					$code .= "</div>\n\t\t\t\t";
				$code .= "</div>\n\t\t\t";
				$code .= "{/section}\n\n\t\t\t";
			}
		}
		
		
		if($request['categories']==1){
			for($i=1;$i<=$request['categoriesttl'];$i++){
				if($request['category'.$i]){
					if($oe=='odd'){$oe='even';}else{$oe='odd';}
					foreach($this->categories as $c){
						if($request['category'.$i]==$c['table']){
							$title = $c['title'];
						}
					}
					$code .= "<div class=\"frmRow ".$oe."\">\n\t\t\t\t";
					$code .= "<label>".$title."</label>\n\t\t\t\t";
					$code .= "<div class=\"input category\">\n\t\t\t\t";
					$code .= "{assign var=next value=\$obj->".$request['category'.$i]."_all}\n\t\t\t\t\t";
					$code .= "{assign var=level value=\$obj->".$request['category'.$i]."_parents|@count}\n\t\t\t\t\t";
					$code .= "<input type=\"hidden\" name=\"".$request['category'.$i]."_level\" value=\"{\$level}\"/>\n\t\t\t\t\t";
					$code .= "<input type=\"hidden\" name=\"".$request['category'.$i]."_tbl\" value=\"".$request['category'.$i]."\"/>\n\t\t\t\t\t";
					$code .= "{section name=i loop=\$level+1}\n\t\t\t\t\t\t";
					$code .= "{if \$next|@count>0}\n\t\t\t\t\t\t";
					$code .= "<select name=\"".$request['category'.$i]."_level{\$smarty.section.i.index}\">\n\t\t\t\t\t\t\t";
					$code .= "<option value=\"\">&nbsp;</option>\n\t\t\t\t\t\t\t";
					$code .= "{assign var=n value=0}\n\t\t\t\t\t\t\t";
					$code .= "{section name=j loop=\$next}\n\t\t\t\t\t\t\t\t";
					$code .= "<option value=\"{\$next[j].id}\" {if \$next[j].id==\$obj->".$request['category'.$i]."_parents[i].id}{assign var=nnext value=\$next[j].children}{assign var=n value=1}selected=\"selected\"{/if}>{\$next[j].title}</option>\n\t\t\t\t\t\t\t";
					$code .= "{/section}\n\t\t\t\t\t\t\t";
					$code .= "{if \$n}{assign var=next value=\$nnext}{/if}\n\t\t\t\t\t\t";
					$code .= "</select>\n\t\t\t\t\t\t";
					$code .= "{/if}\n\t\t\t\t\t";
					$code .= "{/section}\n\t\t\t\t";
					$code .= "</div>\n\t\t\t";
					$code .= "</div>\n\n\t\t\t";
				}
			}
		}



		if($request['gallery']){
			if($oe=='odd'){$oe='even';}else{$oe='odd';}
			$code .= "{assign var=oe value='".$oe."'}\n\t\t\t";
			$code .= "{section name=j loop=\$obj->galleries|@count}\n\t\t\t";
			$code .= "{assign var=gallery value=\$obj->galleries[j]}\n\t\t\t\t";
			$code .= "{include file=\"gallery_inc.tpl\"}\n\t\t\t\t";
			$code .= "{if \$gallery->multi}\n\t\t\t\t\t";
			$code .= "{assign var=addonemore value=\"true\"}\n\t\t\t\t\t";
			$code .= "{assign var=gid value=\$gallery->id}\n\t\t\t\t\t";
			$code .= "{assign var=g2o value=\$gallery->g2o}\n\t\t\t\t";
			$code .= "{/if}\n\t\t\t";
			$code .= "{/section}\n\t\t\t";
			$code .= "{if \$addonemore==\"true\"}\n\t\t\t\t";
			$code .= "<div class=\"frmRow\"><a href=\"javascript:\" class=\"btnGrey addGallery\" gid=\"{\$gid}\" g2o=\"{\$g2o}\">Add One More Gallery</a></div>\n\t\t\t";
			$code .= "{/if}\n\n\t\t";
		}

		$code .= "</div>\n\t";
		$code .= "</form>\n\n\t";
		
		$code .= "<div class=\"frmRow\">\n\t\t";
		$code .= "<label class=\"error\">&nbsp;</label>\n\t\t";
		$code .= "<div class=\"input\">\n\t\t\t";
		$code .= "<div class=\"submitLine\">\n\t\t\t\t";
		$code .= "<a href=\"javascript:submitFrm('actionfrm')\" class=\"btnGreen\">{if \$request.action=='edit'}Update{else}Add{/if}</a>\n\t\t\t";
		$code .= "</div>\n\t\t";
		$code .= "</div>\n\t";
		$code .= "</div>\n\n";
		
		$code .= "{include file=\"footer.tpl\"}";

		file_put_contents($this->dir."/admin/templates/".$tbl."_action.tpl", $code);
	}

	function _createInputField($request, $i){
		if($request['type'.$i]=='text') return $this->_createTextInput($request, $i);
		if($request['type'.$i]=='date') return $this->_createDateInput($request, $i);
		if($request['type'.$i]=='password') return $this->_createPasswordInput($request, $i);
		if($request['type'.$i]=='radio') return $this->_createRadioInput($request, $i);
		if($request['type'.$i]=='select') return $this->_createSelectInput($request, $i);
		if($request['type'.$i]=='textarea') return $this->_createTextareaInput($request, $i);
		if($request['type'.$i]=='html') return $this->_createHTMLInput($request, $i);
	}

	function _createTextInput($request, $i){
		if($request['multilanguage'.$i]==1){
			return "<input type=\"text\" name=\"".$request['dbfield'.$i]."-{\$lang}\" value=\"{if \$request.sbm||\$request[\$vn]}{\$request[\$vn]}{else}{\$obj->admin[\$lang].".$request['dbfield'.$i]."}{/if}\"/>";
		}else{
			return "<input type=\"text\" name=\"".$request['dbfield'.$i]."\" value=\"{if \$request.sbm||\$request.".$request['dbfield'.$i]."}{\$request.".$request['dbfield'.$i]."}{else}{\$obj->admin[\$lang].".$request['dbfield'.$i]."}{/if}\"/>";
		}
	}

	function _createDateInput($request, $i){
		if($request['multilanguage'.$i]==1){
			return "<input type=\"text\" class=\"date\" name=\"".$request['dbfield'.$i]."-{\$lang}\" value=\"{if \$request.sbm||\$request[\$vn]}{\$request[\$vn]}{else}{\$obj->admin[\$lang].".$request['dbfield'.$i]."}{/if}\"/>";
		}else{
			return "<input type=\"text\" class=\"date\" name=\"".$request['dbfield'.$i]."\" value=\"{if \$request.sbm||\$request.".$request['dbfield'.$i]."}{\$request.".$request['dbfield'.$i]."}{else}{\$obj->admin[\$lang].".$request['dbfield'.$i]."}{/if}\"/>";
		}
	}

	function _createPasswordInput($request, $i){
		if($request['multilanguage'.$i]==1){
			return "<input type=\"password\" name=\"".$request['dbfield'.$i]."-{\$lang}\" value=\"\"/>";
		}else{
			return "<input type=\"password\" name=\"".$request['dbfield'.$i]."\" value=\"\"/>";
		}
	}

	function _createRadioInput($request, $i){
		$code = "";
		for($j=1;$j<=$request['optionsttl'.$i];$j++){
			if($request['optiondefault'.$i."_".$j]){
				if($request['multilanguage'.$i]==1){
					$checked = "{if \$request.sbm&&\$request[\$vn]=='".str_replace("'","\'",stripslashes($request['optionvalue'.$i.'_'.$j]))."'}checked=\"checked\"{else}{if \$request.action=='edit'}{if \$obj->admin[\$lang].".$request['dbfield'.$i]."=='".str_replace("'","\'",stripslashes($request['optionvalue'.$i.'_'.$j]))."'}checked=\"checked\"{/if}{else}checked=\"checked\"{/if}{/if}";
				}else{
					$checked = "{if \$request.sbm&&\$request.".$request['dbfield'.$i]."=='".str_replace("'","\'",stripslashes($request['optionvalue'.$i.'_'.$j]))."'}checked=\"checked\"{else}{if \$request.action=='edit'}{if \$obj->admin[\$lang].".$request['dbfield'.$i]."=='".str_replace("'","\'",stripslashes($request['optionvalue'.$i.'_'.$j]))."'}checked=\"checked\"{/if}{else}checked=\"checked\"{/if}{/if}";
				}
			}else{
				if($request['multilanguage'.$i]==1){
					$checked = "{if \$request.sbm&&\$request[\$vn]=='".str_replace("'","\'",stripslashes($request['optionvalue'.$i.'_'.$j]))."'}checked=\"checked\"{else}{if \$request.action=='edit'}{if \$obj->admin[\$lang].".$request['dbfield'.$i]."=='".str_replace("'","\'",stripslashes($request['optionvalue'.$i.'_'.$j]))."'}checked=\"checked\"{/if}{/if}{/if}";
				}else{
					$checked = "{if \$request.sbm&&\$request.".$request['dbfield'.$i]."=='".str_replace("'","\'",stripslashes($request['optionvalue'.$i.'_'.$j]))."'}checked=\"checked\"{else}{if \$request.action=='edit'}{if \$obj->admin[\$lang].".$request['dbfield'.$i]."=='".str_replace("'","\'",stripslashes($request['optionvalue'.$i.'_'.$j]))."'}checked=\"checked\"{/if}{/if}{/if}";
				}
//				$checked = "{if \$request.action=='edit'}{if \$obj->admin[\$lang].".$request['dbfield'.$i]."=='".str_replace("'","\'",stripslashes($request['optionvalue'.$i.'_'.$j]))."'}checked=\"checked\"{/if}{/if}";
			}
			if($request['multilanguage'.$i]==1){
				$code .= "<input type=\"radio\" name=\"".$request['dbfield'.$i]."-{\$lang}\" value=\"".str_replace('"','&quot;',stripslashes($request['optionvalue'.$i.'_'.$j]))."\" ".$checked."/> ".stripslashes($request['optionname'.$i.'_'.$j])."\n\t\t\t\t\t\t";
			}else{
				$code .= "<input type=\"radio\" name=\"".$request['dbfield'.$i]."\" value=\"".str_replace('"','&quot;',stripslashes($request['optionvalue'.$i.'_'.$j]))."\" ".$checked."/> ".stripslashes($request['optionname'.$i.'_'.$j])."\n\t\t\t\t\t\t";
			}
		}
		$code = substr($code, 0, strlen($code)-6);
		return $code;
	}

	function _createSelectInput($request, $i){
		if($request['multilanguage'.$i]==1){
			$code = "<select name=\"".$request['dbfield'.$i]."-{\$lang}\">\n\t\t\t\t\t\t\t";
		}else{
			$code = "<select name=\"".$request['dbfield'.$i]."\">\n\t\t\t\t\t\t\t";
		}
		for($j=1;$j<=$request['optionsttl'.$i];$j++){
			if(trim($request['optionname'.$i.'_'.$j])==""){$request['optionname'.$i.'_'.$j]="&nbsp;";}


if($request['multilanguage'.$i]==1){
			if($request['optiondefault'.$i."_".$j]){
				if(strlen(trim($request['optionvalue'.$i.'_'.$j]))>0){
					$selected = "{if \$request.sbm&&\$request[\$vn]=='".str_replace("'","\'",stripslashes($request['optionvalue'.$i.'_'.$j]))."'}selected=\"selected\"{else}{if \$request.action=='edit'}{if \$obj->admin[\$lang].".$request['dbfield'.$i]."=='".$request['optionvalue'.$i.'_'.$j]."'}selected=\"selected\"{/if}{else}selected=\"selected\"{/if}{/if}";
				}else{
					$selected = "{if \$request.sbm&&\$request[\$vn]=='".str_replace("'","\'",stripslashes($request['optionname'.$i.'_'.$j]))."'}selected=\"selected\"{else}{if \$request.action=='edit'}{if \$obj->admin[\$lang].".$request['dbfield'.$i]."=='".$request['optionname'.$i.'_'.$j]."'}selected=\"selected\"{/if}{else}selected=\"selected\"{/if}{/if}";
				}
			}else{
				if(strlen(trim($request['optionvalue'.$i.'_'.$j]))>0){
					$selected = "{if \$request.sbm&&\$request[\$vn]=='".str_replace("'","\'",stripslashes($request['optionvalue'.$i.'_'.$j]))."'}selected=\"selected\"{else}{if \$request.action=='edit'}{if \$obj->admin[\$lang].".$request['dbfield'.$i]."=='".$request['optionvalue'.$i.'_'.$j]."'}selected=\"selected\"{/if}{/if}{/if}";
				}else{
					$selected = "{if \$request.sbm&&\$request[\$vn]=='".str_replace("'","\'",stripslashes($request['optionname'.$i.'_'.$j]))."'}selected=\"selected\"{else}{if \$request.action=='edit'}{if \$obj->admin[\$lang].".$request['dbfield'.$i]."=='".$request['optionname'.$i.'_'.$j]."'}selected=\"selected\"{/if}{/if}{/if}";
				}
			}
}else{
			if($request['optiondefault'.$i."_".$j]){
				if(strlen(trim($request['optionvalue'.$i.'_'.$j]))>0){
					$selected = "{if \$request.sbm&&\$request.".$request['dbfield'.$i]."=='".str_replace("'","\'",stripslashes($request['optionvalue'.$i.'_'.$j]))."'}selected=\"selected\"{else}{if \$request.action=='edit'}{if \$obj->admin[\$lang].".$request['dbfield'.$i]."=='".$request['optionvalue'.$i.'_'.$j]."'}selected=\"selected\"{/if}{else}selected=\"selected\"{/if}{/if}";
				}else{
					$selected = "{if \$request.sbm&&\$request.".$request['dbfield'.$i]."=='".str_replace("'","\'",stripslashes($request['optionname'.$i.'_'.$j]))."'}selected=\"selected\"{else}{if \$request.action=='edit'}{if \$obj->admin[\$lang].".$request['dbfield'.$i]."=='".$request['optionname'.$i.'_'.$j]."'}selected=\"selected\"{/if}{else}selected=\"selected\"{/if}{/if}";
				}
			}else{
				if(strlen(trim($request['optionvalue'.$i.'_'.$j]))>0){
					$selected = "{if \$request.sbm&&\$request.".$request['dbfield'.$i]."=='".str_replace("'","\'",stripslashes($request['optionvalue'.$i.'_'.$j]))."'}selected=\"selected\"{else}{if \$request.action=='edit'}{if \$obj->admin[\$lang].".$request['dbfield'.$i]."=='".$request['optionvalue'.$i.'_'.$j]."'}selected=\"selected\"{/if}{/if}{/if}";
				}else{
					$selected = "{if \$request.sbm&&\$request.".$request['dbfield'.$i]."=='".str_replace("'","\'",stripslashes($request['optionname'.$i.'_'.$j]))."'}selected=\"selected\"{else}{if \$request.action=='edit'}{if \$obj->admin[\$lang].".$request['dbfield'.$i]."=='".$request['optionname'.$i.'_'.$j]."'}selected=\"selected\"{/if}{/if}{/if}";
				}
			}
}

			if(strlen(trim($request['optionvalue'.$i.'_'.$j]))>0){
				$code .= "<option value=\"".$request['optionvalue'.$i.'_'.$j]."\" ".$selected.">".stripslashes($request['optionname'.$i.'_'.$j])."</option>\n\t\t\t\t\t\t\t";
			}else{
				$code .= "<option ".$selected.">".stripslashes($request['optionname'.$i.'_'.$j])."</option>\n\t\t\t\t\t\t\t";
			}
		}
		$code = substr($code,0,strlen($code)-1);
		$code .= "</select>";
		return $code;
	}

	function _createTextareaInput($request, $i){
		if($request['multilanguage'.$i]==1){
			return "<textarea name=\"".$request['dbfield'.$i]."-{\$lang}\">{if \$request.sbm||\$request[\$vn]}{\$request[\$vn]}{else}{\$obj->admin[\$lang].".$request['dbfield'.$i]."}{/if}</textarea>";
		}else{
			return "<textarea name=\"".$request['dbfield'.$i]."\">{if \$request.sbm||\$request.".$request['dbfield'.$i]."}{\$request.".$request['dbfield'.$i]."}{else}{\$obj->admin[\$lang].".$request['dbfield'.$i]."}{/if}</textarea>";
		}
	}

	function _createHTMLInput($request, $i){
		return "{\$obj->admin[\$lang].".$request['dbfield'.$i]."}";
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function _createAdminListTpl($request){
		$tbl = normalize($request['db_tbl']);
		$classname = ucfirst($tbl);

		$code .= "{include file=\"header.tpl\"}\n\n\t\t";
		
		$code .= "<div id=\"deletePopup\" class=\"box hidden\">\n\t\t\t";
		$code .= "<div class=\"tri\"></div>\n\t\t\t";
		$code .= "<h2>Delete ".$request['title']."?</h2>\n\t\t\t";
		$code .= "<form action=\"co.php\" method=\"post\" id=\"deletefrm\">\n\t\t\t\t";
		$code .= "<input type=\"hidden\" name=\"classname\" value=\"".$classname."\"/>\n\t\t\t\t";
		$code .= "<input type=\"hidden\" name=\"id\" id=\"id\" value=\"\"/>\n\t\t\t\t";
		$code .= "<input type=\"hidden\" name=\"action\" value=\"delete\"/>\n\t\t\t";
		$code .= "<div class=\"submitLine\">\n\t\t\t\t";
		$code .= "<a href=\"javascript:\" class=\"btnRed\" id=\"btnRed\">Delete</a>\n\t\t\t\t";
		$code .= "<a href=\"javascript:\" class=\"btnCancel\">Cancel</a>\n\t\t\t";
		$code .= "</div>\n\t\t\t";
		$code .= "</form>\n\t\t\t";
		$code .= "</div>\n\n\n\t\t";
		
		
		$code .= "<div class=\"hat\">\n\t\t\t";
		$code .= "<div class=\"hatshdw\"></div>\n\t\t";
		$code .= "</div>\n\t\t";
		$code .= "<div class=\"lshdw\"></div>\n\t\t";
		$code .= "<div class=\"rshdw\"></div>\n\t\t";
		$code .= "<h1>".$request['title']."</h1>\n\n\t\t";
		
		$code .= "<div class=\"outerRow {if !\$request.added}hidden{/if} pb0\">\n\t\t\t";
		$code .= "<div class=\"success-box {if !\$request.added}hidden{/if}\">\n\t\t\t";
		$code .= "<div class=\"success\">".$request['title']." item added successfully.</div>\n\t\t\t";
		$code .= "</div>\n\t\t";
		$code .= "</div>\n\n\n\t\t";
		
		
		$code .= "{if \$objects|@count||\$no_results}\n\t\t\t";
		
		$sfrm="";
		for($i=1;$i<=$request['ttl'];$i++){
			if($request['type'.$i]=='date'&&$request['searchable'.$i]){
				$sfrm .= "<div class=\"leftSearch\"><label>".$request['title'.$i]." From</label><input type=\"text\" class=\"date\" name=\"".$request['dbfield'.$i]."_from\" value=\"{\$obj->".$request['dbfield'.$i]."_from}\"/></div>\n\t\t\t\t\t";
				$sfrm .= "<div class=\"leftSearch\"><label>".$request['title'.$i]." To</label><input type=\"text\" class=\"date\" name=\"".$request['dbfield'.$i]."_to\" value=\"{\$obj->".$request['dbfield'.$i]."_to}\"/></div>\n\t\t\t\t\t";
				$mt10 = true;
			}
			if(($request['type'.$i]=='select'||$request['type'.$i]=='radio')&&$request['searchable'.$i]){
				$sfrm .= "<div class=\"leftSearch\">\n\t\t\t\t\t\t";
				$sfrm .= "<select name=\"".$request['dbfield'.$i]."_search\">\n\t\t\t\t\t\t\t";
				$sfrm .= "<option value=\"\">".$request['title'.$i]."...</option>\n\t\t\t\t\t\t\t";
				for($j=1;$j<=$request['optionsttl'.$i];$j++){
					if(strlen($request['optionvalue'.$i.'_'.$j])>0){
						$selected = "{if \$obj->".$request['dbfield'.$i]."_search=='".str_replace("'","\'",$request['optionvalue'.$i.'_'.$j])."'}selected=\"selected\"{/if}";
						$sfrm .= "<option value=\"".$request['optionvalue'.$i.'_'.$j]."\" ".$selected.">".$request['optionname'.$i.'_'.$j]."</option>\n\t\t\t\t\t\t\t";
					}else{
						$selected = "{if \$obj->".$request['dbfield'.$i]."_search=='".str_replace("'","\'",$request['optionname'.$i.'_'.$j])."'}selected=\"selected\"{/if}";
						$sfrm .= "<option ".$selected.">".$request['optionname'.$i.'_'.$j]."</option>\n\t\t\t\t\t\t\t";
					}
				}
				$sfrm = substr($sfrm, 0, strlen($sfrm)-1);
				$sfrm .= "</select>\n\t\t\t\t\t";
				$sfrm .= "</div>\n\t\t\t\t\t";
			}
		}
		$sfrm .= "<input type=\"text\" name=\"search\" value=\"{\$obj->search}\" class=\"search ml20\" placeholder=\"Keyword\"/>\n\t\t\t\t\t";
//TODO do the proper search for categories

		if($mt10){$code .= "<div class=\"searchRow mt10\">\n\t\t\t\t";}else{$code .= "<div class=\"searchRow\">\n\t\t\t\t";}
		$code .= "<form action=\"co.php\" method=\"post\" id=\"search\">\n\t\t\t\t\t";
		$code .= "<input type=\"hidden\" name=\"classname\" value=\"".$classname."\"/>\n\t\t\t\t\t";
		$code .= "<input type=\"hidden\" name=\"searchsbm\" value=\"1\"/>\n\t\t\t\t\t";
		$code .= $sfrm;
		
		$code .= "<a href=\"javascript:submitFrm('search')\" class=\"btnGrey\">Search</a>\n\t\t\t\t";
		$code .= "</form>\n\t\t\t";
		$code .= "</div>\n\n\t\t\t";
		
		$code .= "<table class=\"datatable\" cellspacing=\"0\" cellpadding=\"0\">\n\t\t\t\t";
		$code .= "<thead>\n\t\t\t\t\t";
		$code .= "<tr>\n\t\t\t\t\t\t";
		$code .= "<th class=\"sortable {if \$obj->order_by=='id'}{\$obj->order}{/if}\" order_by=\"id\">ID<div class=\"sort\"></div></th>\n\t\t\t\t\t\t";

		if($request['gallery']&&$request['gallery_admindisplay']){
			$code .= "<th width=\"127\"></th>\n\t\t\t\t\t\t";
		}

		for($i=1;$i<=$request['ttl'];$i++){
//echo "<admindisplay".$i."=".$request['admindisplay'.$i].">";
			if($request['admindisplay'.$i]){
				$code .= "<th class=\"sortable {if \$obj->order_by=='".$request['dbfield'.$i]."'}{\$obj->order}{/if}\" order_by=\"".$request['dbfield'.$i]."\">".$request['title'.$i]."<div class=\"sort\"></div></th>\n\t\t\t\t\t\t";
			}
		}
		
		if($request['categories']==1){
			for($i=1;$i<=$request['categoriesttl'];$i++){
				if($request['category'.$i]&&$request['category'.$i.'_admindisplay']==1){
					foreach($this->categories as $c){
						if($request['category'.$i]==$c['table']){
							$title = $c['title'];
						}
					}
					$code .= "<th>".$title."</th>\n\t\t\t\t\t\t";
				}
			}
		}
		
		$code .= "<th class=\"sortable {if \$obj->order_by=='locked'}{\$obj->order}{/if}\" width=\"60\" order_by=\"locked\">Locked<div class=\"sort\"></div></th>\n\t\t\t\t\t\t";
		$code .= "<th class=\"sortable {if \$obj->order_by=='ordr'}{\$obj->order}{/if}\" width=\"60\" order_by=\"ordr\">Order<div class=\"sort\"></div></th>\n\t\t\t\t\t\t";
		$code .= "<th class=\"center\">Actions</th>\n\t\t\t\t\t";
		$code .= "</tr>\n\t\t\t\t";
		$code .= "</thead>\n\t\t\t\t";
		$code .= "<tbody>\n\t\t\t\t\t";
		$code .= "{if \$no_results}<tr><td colspan=\"5\" align=\"center\">No ".$request['title']." match your criteria.</td></tr>{/if}\n\t\t\t\t\t";
		$code .= "{section name=i loop=\$objects|@count}\n\t\t\t\t\t";
		$code .= "<tr>\n\t\t\t\t\t\t";
		$code .= "<td>{\$objects[i].id}</td>\n\t\t\t\t\t\t";

		if($request['gallery']&&$request['gallery_admindisplay']){
			$code .= "<td><img src=\"{\$objects[i].gallery->imgs[0].url.admin}\" border=\"0\"/></td>\n\t\t\t\t\t\t";
		}

		for($i=1;$i<=$request['ttl'];$i++){
			if($request['admindisplay'.$i]){
				if($request['type'.$i]=='radio'||$request['type'.$i]=='select'){
					$code .= "<td>\n\t\t\t\t\t\t\t";
					for($j=1;$j<=$request['optionsttl'.$i];$j++){
						if($request['optionvalue'.$i.'_'.$j]){
							$code .= "{if \$objects[i].".$request['dbfield'.$i]."=='".str_replace("'","\'",stripslashes($request['optionvalue'.$i.'_'.$j]))."'}".stripslashes($request['optionname'.$i."_".$j])."{/if}\n\t\t\t\t\t\t\t";
						}else{
							$code .= "{if \$objects[i].".$request['dbfield'.$i]."=='".str_replace("'","\'",stripslashes($request['optionname'.$i.'_'.$j]))."'}".stripslashes($request['optionname'.$i."_".$j])."{/if}\n\t\t\t\t\t\t\t";
						}
					}
					$code = substr($code, 0, strlen($code)-1);
					$code .= "</td>\n\t\t\t\t\t\t";
				}else if($request['type'.$i]=='textarea'){
					$code .= "<td>{\$objects[i].".$request['dbfield'.$i]."|nl2br}</td>\n\t\t\t\t\t\t";
				}else{
					$code .= "<td>{\$objects[i].".$request['dbfield'.$i]."}</td>\n\t\t\t\t\t\t";
				}
			}
		}
		
		if($request['categories']==1){
			for($i=1;$i<=$request['categoriesttl'];$i++){
				if($request['category'.$i]&&$request['category'.$i.'_admindisplay']==1){
					$code .= "<td>{\$objects[i].".$request['category'.$i]."->title}</td>\n\t\t\t\t\t\t";
				}
			}
		}
		
		$code .= "<td align=\"center\"><input type=\"checkbox\" class=\"locked\" oid=\"{\$objects[i].id}\" ajaxurl=\"co.php\" ajaxaction=\"updateLocked\" {if \$objects[i].locked}checked=\"checked\"{/if}/></td>\n\t\t\t\t\t\t";
		$code .= "<td align=\"center\"><input type=\"text\" class=\"ordr\" oid=\"{\$objects[i].id}\" ajaxurl=\"co.php\" ajaxaction=\"updateOrdr\" value=\"{\$objects[i].ordr}\" class=\"ordr\"/></td>\n\t\t\t\t\t\t";
		$code .= "<td class=\"center\"><a href=\"?classname=".$classname."&action=edit&id={\$objects[i].id}\" class=\"edit\"></a><a href=\"javascript:\" class=\"delete\" objectid=\"{\$objects[i].id}\"></a></td>\n\t\t\t\t\t\t";
		$code .= "</tr>\n\t\t\t\t\t";
		$code .= "{/section}\n\t\t\t\t";
		$code .= "</tbody>\n\t\t\t\t";
		$code .= "<tfoot>\n\t\t\t\t\t";
		$code .= "<tr>\n\t\t\t\t\t\t";
		$code .= "<td colspan=\"20\">{\$obj->pagination}</td>\n\t\t\t\t\t";
		$code .= "</tr>\n\t\t\t\t";
		$code .= "</tfoot>\n\t\t\t";
		$code .= "</table>\n\t\t";
		$code .= "{else}\n\t\t\t";
		$code .= "<div class=\"row1\">No ".$tbl." yet.</div>\n\t\t";
		$code .= "{/if}\n\n";
		
		$code .= "{include file=\"footer.tpl\"}";

		file_put_contents($this->dir."/admin/templates/".$tbl.".tpl", $code);
	}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function _addBubbleCode($request){

		//create the code for page .php
		$this->_createBubbleCode4Page($request);

		//create the bubble for list and react on click to show, have pagination
		$this->_createBubbleCodeTpl($request);
		$this->_createBubbleCodeDetailsTpl($request);

		//add code for the bubble to the header.tpl
		$this->_addBubbleCode2PageTpl($request);

	}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function _createBubbleCode4Page($request){
		$tbl = normalize($request['db_tbl']);
		$classname = ucfirst($tbl);
		//delete previous code and create new code

		if($request['action']=='edit'){
			//remove old code
			$old_code[0] = "if(\$_REQUEST['".$this->tbl."_id']){";
			$old_code[1] = "\$".$this->tbl." = new ".$this->classname."(\$_REQUEST['".$this->tbl."_id']);";
			$old_code[2] = "\$smarty->assign_by_ref(\"".$this->tbl."\", \$".$this->tbl.");";
			$old_code[3] = "\$smarty->assign_by_ref(\"request\",\$_REQUEST);";
			$old_code[4] = "\$".$this->tbl." = new ".$this->classname."();";
			$old_code[5] = "\$smarty->assign_by_ref(\"".$this->tbl."_all\", \$".$this->tbl."->getAll(\$_REQUEST));";
			$old_code[6] = "\$smarty->assign_by_ref(\"".$this->tbl."_pagination\", \$".$this->tbl."->pagination);";
			if($this->request['categories']==1){
				for($i=1;$i<=$this->request['categoriesttl'];$i++){
					$old_code[7] .= "\$".$this->request['category'.$i]." = new Category(\"".$this->request['category'.$i]."\");";
					$old_code[8] .= "\$smarty->assign_by_ref(\"".$this->request['category'.$i]."\", \$".$this->request['category'.$i]."->getAll());";
				}
			}
	
			$replace[0] = "if(\$_REQUEST['".$tbl."_id']){";
			$replace[1] = "\$".$tbl." = new ".$classname."(\$_REQUEST['".$tbl."_id']);";
			$replace[2] = "\$smarty->assign_by_ref(\"".$tbl."\", \$".$tbl.");";
			$replace[3] = "\$smarty->assign_by_ref(\"request\",\$_REQUEST);";
			$replace[4] = "\$".$tbl." = new ".$classname."();\n\t";
			$replace[5] = "\$smarty->assign_by_ref(\"".$tbl."_all\", \$".$tbl."->getAll(\$_REQUEST));\n\t";
			$replace[6] = "\$smarty->assign_by_ref(\"".$tbl."_pagination\", \$".$tbl."->pagination);\n";
			if($request['categories']==1){
				for($i=1;$i<=$request['categoriesttl'];$i++){
					$replace[7] .= "\$".$request['category'.$i]." = new Category(\"".$request['category'.$i]."\");\n";
					$replace[8] .= "\$smarty->assign_by_ref(\"".$request['category'.$i]."\", \$".$request['category'.$i]."->getAll());\n\n";
				}
			}else{
				$replace[7] = "";
				$replace[8] = "";
			}
			file_replace($this->dir."/".$this->page->filename, $old_code, $replace);
		}else{
			//new code
			$code = "if(\$_REQUEST['".$tbl."_id']){\n\t";
			$code .= "\$".$tbl." = new ".$classname."(\$_REQUEST['".$tbl."_id']);\n\t";
			$code .= "\$smarty->assign_by_ref(\"".$tbl."\", \$".$tbl.");\n\t";
			$code .= "\$smarty->assign_by_ref(\"request\",\$_REQUEST);\n";
			$code .= "}else{\n\t";
			$code .= "\$".$tbl." = new ".$classname."();\n\t";
			$code .= "\$smarty->assign_by_ref(\"".$tbl."_all\", \$".$tbl."->getAll(\$_REQUEST));\n\t";
			$code .= "\$smarty->assign_by_ref(\"".$tbl."_pagination\", \$".$tbl."->pagination);\n";
			$code .= "}\n";
			if($request['categories']==1){
				for($i=1;$i<=$request['categoriesttl'];$i++){
					$code .= "\$".$request['category'.$i]." = new Category(\"".$request['category'.$i]."\");\n";
					$code .= "\$smarty->assign_by_ref(\"".$request['category'.$i]."\", \$".$request['category'.$i]."->getAll());\n\n";
				}
			}
			$page_code = file($this->dir.'/'.$this->page->filename);
			$updated_page = "";
			for($i=0;$i<sizeof($page_code);$i++){
				if(strstr($page_code[$i],"->display(\"".$this->page->template."\");")) $updated_page .= $code;
				$updated_page .= $page_code[$i];
			}
			file_put_contents($this->dir."/".$this->page->filename, $updated_page);
		}
	}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function _createBubbleCodeTpl($request){
		$tbl = normalize($request['db_tbl']);
		$classname = ucfirst($tbl);

		if($tbl!=$this->tbl){
			//delete the old bubble template
			if(file_exists($this->dir."/templates/codes/co_".$this->tbl.".tpl")){
				@unlink($this->dir."/templates/codes/co_".$this->tbl.".tpl");
			}
		}

		$bubblecode = "<div class=\"codes_bubble\">\n\t";
		$bubblecode .= "<div class=\"codes_tri\"></div>\n\t";
		$bubblecode .= "<div class=\"codes_cnt\">\n\t\t";
		if($request['categories']==1){
			$bubblecode .= "<div class=\"codes_categories\">\n\t\t\t";
			for($i=1;$i<=$request['categoriesttl'];$i++){
				foreach($this->categories as $c){if($request['category'.$i]==$c['table']){$title = $c['title'];}}
				$bubblecode .= "{include file='codes/categories.tpl' category_name=\"".$request['category'.$i]."\" category_title=\"".$title."\" categories=\$".$request['category'.$i]."}\n\t\t";
			}
			$bubblecode .= "</div>\n\t\t";
		}
		$bubblecode .= "<div class=\"codes_co\">\n\t\t";
		$bubblecode .= "<h2>Module Complex Object (".$request['title'].")</h2>\n\t\t\t";
		$bubblecode .= "<p>Location: <i>templates/codes/co_".$tbl.".tpl</i></p>\n\n\t\t\t";

$bubblecode .= "{if \$request.".$tbl."_id}\n\t\t\t\t";
$bubblecode .=	"{include file='codes/co_".$tbl."_details.tpl'}\n\t\t\t";
$bubblecode .= "{else}\n\t\t\t\t";
		$bubblecode .= "{section name=i loop=\$".$tbl."_all}\n\t\t\t\t\t";
//		$first = true;
		for($i=1;$i<=$request['ttl'];$i++){
			if($request['admindisplay'.$i]==1){
				if($i==1){
					if($request['gallery']==1&&$request['gallery_admindisplay']==1){
						$bubblecode .= "<a href=\"?".$tbl."_id={\$".$tbl."_all[i].id}\"><img src=\"{\$".$tbl."_all[i].gallery->imgs.0.url.admin}\" border=\"0\"/></a>\n\t\t\t\t\t";
					}
					$bubblecode .= "<a href=\"?".$tbl."_id={\$".$tbl."_all[i].id}\">{\$".$tbl."_all[i].".$request['dbfield'.$i]."}</a><br/>\n\t\t\t\t\t";
//					$first=false;
				}else{
					$bubblecode .= "{\$".$tbl."_all[i].".$request['dbfield'.$i]."}<br/>\n\t\t\t\t\t";
				}
			}
		}
		$bubblecode .= "<hr/>\n\t\t\t\t{/section}\n\t\t\t";
		$bubblecode .=	"{\$".$tbl."_pagination}\n\t\t";

$bubblecode .= "{/if}\n\n\t\t";

		$bubblecode .= "</div>\n\t\t";
		$bubblecode .= "<div class=\"codes_clear\"></div>\n\t";
		$bubblecode .= "</div>\n";
		$bubblecode .= "</div>";
		file_put_contents($this->dir."/templates/codes/co_".$tbl.".tpl", $bubblecode);
	}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function _createBubbleCodeDetailsTpl($request){
		$tbl = normalize($request['db_tbl']);
		$classname = ucfirst($tbl);

		if($tbl!=$this->tbl){
			//delete the old bubble template
			if(file_exists($this->dir."/templates/codes/co_".$this->tbl."_details.tpl")){
				@unlink($this->dir."/templates/codes/co_".$this->tbl."_details.tpl");
			}
		}

		//create all the dbfields here use stripslashes for strings
		$code = "";
		for($i=1;$i<=$request['ttl'];$i++){
			$code .= "{\$".$tbl."->".$request['dbfield'.$i]."}<br/>\n";
		}
		if($request['gallery']==1){
			$code .= "<p><b>Galleries:</b></p>\n";
			$code .= "{section name=j loop=\$".$tbl."->galleries|@count}\n\t";
			$code .= "{section name=i loop=\$".$tbl."->galleries[j]->imgs|@count}\n\t\t";
			$code .= "<a href=\"{\$".$tbl."->galleries[j]->imgs[i].url.full}\" rel=\"gallery\" class=\"fancybox\" title=\"{\$".$tbl."->galleries[j]->imgs[i].title}\"><img src=\"{\$".$tbl."->galleries[j]->imgs[i].url.admin}\" border=\"0\"/></a>\n\t";
			$code .= "{/section}\n\t";
			$code .= "<hr/>\n";
			$code .= "{/section}";
		}
		file_put_contents($this->dir."/templates/codes/co_".$tbl."_details.tpl", $code);
	}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function _addBubbleCode2PageTpl($request){
		$tbl = normalize($request['db_tbl']);
		$classname = ucfirst($tbl);

		//remove previous include
		$search[0] = "{if \$page=='".$this->page->name."'}{include file='codes/co_".$this->tbl.".tpl'}{/if}";
		$replace[0] = "";
		file_replace($this->dir."/templates/header.tpl", $search, $replace);

		//add new include
		$template_code = file($this->dir."/templates/header.tpl");
		$code = "\t{if \$page=='".$this->page->name."'}{include file='codes/co_".$tbl.".tpl'}{/if}\n";
		$code_used = false;
		$code1 = "\n<div class=\"codes\">\n".$code."</div>\n\n";
		$new_template_code = "";
		if(sizeof($template_code)>2){
			for($i=0;$i<sizeof($template_code);$i++){
				//checking if the code already has any codes bubbles
				if(strstr($template_code[$i], 'class="codes"')){
					$new_template_code .= $template_code[$i];
					$new_template_code .= $code;
					$code_used = true;
				}else{
					if(($i==sizeof($template_code)-1)&&!$code_used){
						$new_template_code .= $template_code[$i].$code1;
					}else{
						$new_template_code .= $template_code[$i];
					}
				}

			}
		}else{
			$new_template_code .= $template_code[0].$template_code[1].$code1;
		}
		file_put_contents($this->dir."/templates/header.tpl", $new_template_code);
	}

}
?>