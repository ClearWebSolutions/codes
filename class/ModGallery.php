<?php
class ModGallery{

	function ModGallery($id=0){
		global $db, $_SESSION;
//		$this->site = new Site($_SESSION['siteid']);
		$site = new Site($_SESSION['siteid']);
		$this->site = new stdClass();
		$this->site->db_name = $site->db_name;
		$this->site->db_prefix = $site->db_prefix;
		$this->site->dir = $site->dir;
		if($id){
			$this->id = $id;
			$q = $db->query("select * from modules where id='".$this->id."'");
			$row = $q->next_row();
			$m = unserialize(base64_decode($row->serialized));
			if(gettype($m)!='object') $m = unserialize($row->serialized); //fix for websites already using old serialize
			$this->gid = $m->gid;
			$this->fancybox = $m->fancybox;
			$this->amount = $m->amount;
			$this->page = $m->page;
			$this->getGalleryProperties($this->gid);
		}
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function getGalleryProperties($gallery_id){
		global $db, $db_setup;
		//now we connect to the site's db and grab the gallery properties from there
		$db->query("use ".$this->site->db_name);
		$q = $db->query("select * from ".$this->site->db_prefix."galleries where id='".$gallery_id."'");
		$row = $q->next_row();
		$this->title = $row->title;
		$this->code_title = normalize($this->title);
		$this->folder = $row->folder;
		$arr = explode(";",$row->sizes);
		//clear the sizes array in case it was already initialized before
		unset($this->sizes);
		$k = 1;
		for($i=0;$i<sizeof($arr);$i++){
			$a = explode(":",$arr[$i]);
			if($a[0]!='admin'&&$a[0]!='full'){
				$this->sizes[$k] = array('suffix'=>$a[0], 'width'=>$a[1], 'height'=>$a[2], 'cut'=>$a[3]);
				$k++;
			}
		}
		$db->query("use ".$db_setup['database']);
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//@param if obejct_id is not passed we are having a 1 to n type of relations where we manage the way the new complex_objects are added
	//if it's passed we are having a situation like with pages, which is 1 to 1 relation as each page could have a different setup for the page
	function getAll($object_table, $object_id=0){
		global $db, $db_setup;
			$db->query("use ".$this->site->db_name);
			//find out from g2o if multi
			$q = $db->query("select id from ".$this->site->db_prefix."gallery2object where object_table='".$object_table."' and object_id='".$object_id."' and multi='1' and locked='0'");
			if($q->num_rows()>0){ $this->multi = 1; }else{ $this->multi = 0; }
			//take the exact amount of galleries from g2o
			$q = $db->query("select gallery_id, multi from ".$this->site->db_prefix."gallery2object where object_table='".$object_table."' and object_id='".$object_id."' and locked='0' order by ordr");
			//for each gallery get title, folder, amnt of images, sizes with suffixes
			$i=1;
			foreach($q->result() as $row){
				//we are creating the empty Gallery module and initializing it with the info
				//comparing to the single Gallery module the info about the gallery is not stored in the codes database
				$galleries[$i] = new ModGallery();
				$galleries[$i]->getGalleryProperties($row->gallery_id);
				//if($row->multi==1){break;}//we only need the template galleries and we don't need the ones user have created using Add One More Gallery button
				$i++;
			}
			$db->query("use ".$db_setup['database']);
		return $galleries;
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//@param $request['gallery']==1 tells this gallery is getting added as part of another module, all below params are coming in that case
	//@param $request['galleries_amnt']
	//@param $request['multi_galleries'] if set to 1 the last gallery is used as multi in gallery2object table
	function add($request){
		global $db, $_SESSION, $db_setup;

		if($request['gallery']==1){
			$this->addAsPartOfOtherModule($request);
			return true;
		}

		if(!$this->check($request)) return false;

		$this->title = $request['name'];
		$this->folder = $request['folder'];
		$this->code_title = normalize($this->title);

		//initializing page where the code would be added
		$this->page = new Page($request['page']);

		//switch to site's db
		$db->query("use ".$this->site->db_name);

		//create the DB entries
		$sizes = "admin:127:95:1;";
//		if($request['fancybox']=='1'){$sizes.="full:1280:800:0;";$this->fancybox=1;}else{$this->fancybox=0;}
		$sizes.="full:1280:800:0;";$this->fancybox=1;
		$this->amount = $request['amount'];
		for($i=1;$i<=$request['amount'];$i++){
			$sizes .= $request['suffix_'.$i].":".$request['width_'.$i].":".$request['height_'.$i].":".$request['cut_'.$i].";";
		}
		$db->query("insert into ".$this->site->db_prefix."galleries set title='".$this->title."', folder='".$this->folder."', sizes='".$sizes."'");
		$this->gid = $db->insert_id();

		//update the admin menu with new gallery link
		$menu = file($this->site->dir.'/admin/templates/menu.tpl');
		$newitem = "\n\t\t<li {if \$menu=='gallery'&&\$gallery->id==".$this->gid."}class=\"selected1\"{/if}><a href=\"gallery.php?id=".$this->gid."\">".ucfirst($this->title)."<br/><div class=\"tri\"></div></a></li>\n";
		$done = false;
		for($i=0;$i<sizeof($menu);$i++){
			if(strstr($menu[$i], '<ul>')&&!$done){
				$menu[$i] .= $newitem;
				$done = true;
			}
			$new_menu .= $menu[$i];
		}
		file_put_contents($this->site->dir.'/admin/templates/menu.tpl', $new_menu);

		//add code for the gallery to the page.php
		$page_code = file($this->site->dir.'/'.$this->page->filename);
		$code = "\$".$this->code_title." = new Gallery(".$this->gid.");\n";
		$code .= "\$smarty->assign_by_ref(\"".$this->code_title."\", \$".$this->code_title.");\n\n";
		$new_page_code = "";
		for($i=0;$i<sizeof($page_code);$i++){
			if($i==sizeof($page_code)-2) $new_page_code .= $code;
			$new_page_code .= $page_code[$i];
		}
		file_put_contents($this->site->dir."/".$this->page->filename, $new_page_code);

		//add code for the bubble to the page.tpl
		$template_code = file($this->site->dir."/templates/header.tpl");
		$code = "{if \$page=='".$this->page->name."'}{include file='codes/".$this->code_title.".tpl'}{/if}\n";
		$code_used = false;
		$code1 = "\n<div class=\"codes\">\n\t".$code."</div>\n\n";
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
		file_put_contents($this->site->dir."/templates/header.tpl", $new_template_code);


		// creating the bubble code in codes/ dir of templates
		copy("modules/gallery/templates/codes/gallery.tpl", $this->site->dir."/templates/codes/".$this->code_title.".tpl");
		$search[0] = "templates/codes/gallery.tpl";
		$search[1] = "\$gallery->";
		$search[2] = "<h2>Module Gallery</h2>";
/*		if($request['fancybox']=='0'){
			$search[2] = "rel=\"gallery\" class=\"fancybox\"";
		}*/
		$replace[0] = "templates/codes/".$this->code_title.".tpl";
		$replace[1] = "$".$this->code_title."->";
		$replace[2] = "<h2>Module Gallery ".$this->title."</h2>";
/*		if($request['fancybox']=='0'){
			$replace[2] = "";
		}*/
		file_replace($this->site->dir."/templates/codes/".$this->code_title.".tpl", $search, $replace);

		//setting back to codes database
		$db->query("use ".$db_setup['database']);

		//add module and it's data to codes db
		$db->query("insert into modules set pageid='".$this->page->id."', module='gallery', title='".$this->title."', serialized='".base64_encode(serialize($this))."'");
		$this->id = $db->insert_id();

		return true;
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function addAsPartOfOtherModule($request){
		global $db, $_SESSION, $db_setup;

		//check been done inside the module, and it should be done this way because it needs to show the errors before the module starts installation!

		//switch to site's db
		$db->query("use ".$this->site->db_name);

		//create the gallery DB entries
		for($j=1;$j<=$request['galleries_amnt'];$j++){
			$sizes = "admin:127:95:1;";
//			if($request['fancybox'.$j]=='1'){$sizes.="full:1280:800:0;";$this->fancybox[$j]=1;}else{$this->fancybox[$j]=0;}
			$sizes.="full:1280:800:0;";$this->fancybox[$j]=1;
			$this->amount[$j] = $request['amount'.$j];
			for($i=1;$i<=$request['amount'.$j];$i++){
				$sizes .= $request['suffix'.$j.'_'.$i].":".$request['width'.$j.'_'.$i].":".$request['height'.$j.'_'.$i].":".$request['cut'.$j.'_'.$i].";";
			}
			$db->query("insert into ".$this->site->db_prefix."galleries set title='".$request['name'.$j]."', folder='".$request['folder'.$j]."', sizes='".$sizes."'");
			$this->gid[$j] = $db->insert_id();
		}

		//create the gallery2object DB entries
		for($j=1;$j<=$request['galleries_amnt'];$j++){
			if(($j==$request['galleries_amnt'])&&($request['multi_galleries']==1)){$multi=1;}else{$multi=0;}
				//here we create the template for the subpages(or just for complex objects) in case the parent page is selected
				if(strlen($request['object_id'])>0){
				$db->query("insert into ".$this->site->db_prefix."gallery2object set gallery_id='".$this->gid[$j]."', object_id='".$request['object_id']."', object_table='".$request['object_table']."', ordr='".$j."', multi='".$multi."', gallery_title='".$request['name'.$j]."'");
				}
				//however in pages module we want to be able to create the page and template at the same time so we need to do the same for newly added page
				//later this could be defined as part of the 1-1 module relation, compare to 1-n
				//in 1-1 the module could be installed many times and represents the 1 page 1 module relation e.g. Content module
				//in 1-n the module could be installed only once for some page, but it has the ability to have multiple items creation e.g. News 
				if($request['page_id']){
					$db->query("insert into ".$this->site->db_prefix."gallery2object set gallery_id='".$this->gid[$j]."', object_id='".$request['page_id']."', object_table='".$request['object_table']."', ordr='".$j."', multi='".$multi."', gallery_title='".$request['name'.$j]."'");
				}
		}

		//setting back to codes database
		$db->query("use ".$db_setup['database']);

	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//@param $request['gallery']==1 means we are adding a gallery as submodule of some other module
	function check($request){
		global $db;
		if(!$request['page']&&$request['action']=='add'){		$this->error = "Please select the page first!";return false;}
		if($request['gallery']==1){
			for($i=1;$i<=$request['galleries_amnt'];$i++){
				if(!$request['name'.$i]){	$this->error = "Please enter each gallery name!";return false;}
				if(!$request['folder'.$i]){	$this->error = "Please enter each gallery folder!";return false;}
				if(strlen($request['folder'.$i])<3){	$this->error = "Folder name should be at least 3 characters long!";return false;}
				if(!$request['amount'.$i]){	$this->error = "Please choose amount of images to create!";return false;}
			}
		}else{
			if(!$request['name']){	$this->error = "Please enter gallery name!";return false;}
			if(!$request['folder']){	$this->error = "Please enter gallery folder!";return false;}
			if(strlen($request['folder'])<3){	$this->error = "Folder name should be at least 3 characters long!";return false;}
			if(!$request['amount']){	$this->error = "Please choose amount of images to create!";return false;}
		}
		if(!preg_match("/^[a-zA-Z][a-zA-Z0-9_]+$/",normalize($request['name']))&&$request['gallery']!=1){
			$this->error = "Please select another name, coz it would be used as a variable name!"; return false;
		}
		//check if folder exists
		if($request['gallery']==1&&$request['action']=='add'){
			for($i=1;$i<=$request['galleries_amnt'];$i++){
				if(is_dir($this->site->dir."/".$request['folder'.$i])){	$this->error = "Warning! This gallery folder already exists. Pick another folder name or remove the one that exists to proceed!";return false;}
			}
		}else{
			if(is_dir($this->site->dir."/".$request['folder'])&&$request['action']=='add'){	$this->error = "Warning! This gallery folder already exists. Pick another folder name or remove the one that exists to proceed!";return false;}
			if(($this->folder!=$request['folder'])&&$request['action']=='edit'){
				//checking the edit folder only if it differs from the original folder
				if(is_dir($this->site->dir."/".$request['folder'])){	$this->error = "Warning! This gallery folder already exists. Pick another folder name or remove the one that exists to proceed!";return false;}
			}
		}

		if($request['gallery']!=1){
			$q = $db->query("select id from modules where title='".normalize($request['name'])."' and pageid in (select id from pages where siteid='".$this->siteid."')");
			if($q->num_rows()>0){	$this->error = "There is already a module with such name! Please choose another name because different classes can't have same name!"; return false;}
		}

		//check in a cycle if all the values been entered for the images (suffix, width, height)
		if($request['gallery']==1){
			for($j=1;$j<=$request['galleries_amnt'];$j++){
				for($i=1;$i<=$request['amount'.$j];$i++){
					if(!$request['suffix'.$j.'_'.$i]){			$this->error = "Please enter all the suffixes or remove the unwanted rows by updating the amount!";return false;}
					if($request['suffix'.$j.'_'.$i]=='admin'||$request['suffix'.$j.'_'.$i]=='full'){$this->error = "'full' and 'admin' are reserved suffixes! Try using 'big' and 'admn' instead.";return false;}
					if(!$request['width'.$j.'_'.$i]){			$this->error = "Please enter all the widths or remove the unwanted rows by updating the amount!";return false;}
					if(!$request['height'.$j.'_'.$i]){			$this->error = "Please enter all the heights or remove the unwanted rows by updating the amount!";return false;}
					 if(!is_numeric($request['width'.$j.'_'.$i])){		$this->error = "Width must be an integer!";return false;}
					 if(!is_numeric($request['height'.$j.'_'.$i])){	$this->error = "Height must be an integer!";return false;}
				}
			}
		}else{
			for($i=1;$i<=$request['amount'];$i++){
				if(!$request['suffix_'.$i]){			$this->error = "Please enter all the suffixes or remove the unwanted rows by updating the amount!";return false;}
				if($request['suffix_'.$i]=='admin'||$request['suffix_'.$i]=='full'){$this->error = "'full' and 'admin' are reserved suffixes! Try using 'big' and 'admn' instead.";return false;}
				if(!$request['width_'.$i]){			$this->error = "Please enter all the widths or remove the unwanted rows by updating the amount!";return false;}
				if(!$request['height_'.$i]){			$this->error = "Please enter all the heights or remove the unwanted rows by updating the amount!";return false;}
				 if(!is_numeric($request['width_'.$i])){		$this->error = "Width must be an integer!";return false;}
				 if(!is_numeric($request['height_'.$i])){	$this->error = "Height must be an integer!";return false;}
			}
		}
		return true;
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function update($request, $module=null){
		global $db, $db_setup;

/*		if($module){
			$this->updateAsPartOfOtherModule($request, $module);
		}*/

		if(!$this->check($request)) return false;

		//switch to site's db
		$db->query("use ".$this->site->db_name);

		//update the DB entries
		$sizes = "admin:127:95:1;";
//		if($request['fancybox']=='1'){$sizes.="full:1280:800:0;";$this->fancybox=1;}else{$this->fancybox=0;}
		$sizes.="full:1280:800:0;";$this->fancybox=1;
		$this->amount = $request['amount'];
		for($i=1;$i<=$request['amount'];$i++){
			$sizes .= $request['suffix_'.$i].":".$request['width_'.$i].":".$request['height_'.$i].":".$request['cut_'.$i].";";
		}
		$db->query("update ".$this->site->db_prefix."galleries set title='".$request['name']."', folder='".$request['folder']."', sizes='".$sizes."' where id='".$this->gid."'");

		//add code for the gallery to the page.php
		$search[0] = "\$".$this->code_title." = new Gallery(".$this->gid.");";
		$search[1] = "\$smarty->assign_by_ref(\"".$this->code_title."\", \$".$this->code_title.");";
		$replace[0] = "\$".normalize($request['name'])." = new Gallery(".$this->gid.");";
		$replace[1] = "\$smarty->assign_by_ref(\"".normalize($request['name'])."\", \$".normalize($request['name']).");";
		file_replace($this->site->dir.'/'.$this->page->filename, $search, $replace);

		//update code for the bubble in the header.tpl
		$search[0] = "{include file='codes/".$this->code_title.".tpl'}";
		$search[1] = "$".$this->code_title."->";
		$replace[0] = "{include file='codes/".normalize($request['name']).".tpl'}";
		$replace[1] = "$".normalize($request['name'])."->"; 
		file_replace($this->site->dir."/templates/header.tpl", $search, $replace);

		//update the admin menu title
		$search[0] = ">".ucfirst($this->title)."<";
		$replace[0] = ">".ucfirst($request['name'])."<";
		file_replace($this->site->dir.'/admin/templates/menu.tpl', $search, $replace);

		//update the bubble title
		$search[0] = "<h2>Module Gallery ".$this->title."</h2>";
		$search[1] = "templates/codes/".$this->code_title.".tpl";
		$search[2] = "$".$this->code_title."->";
		$replace[0] = "<h2>Module Gallery ".$request['name']."</h2>";
		$replace[1] = "templates/codes/".normalize($request['name']).".tpl";
		$replace[2] = "$".normalize($request['name'])."->";
/*		if($this->fancybox!=$request['fancybox']){
			if($request['fancybox']==0){
				$search[3] = "rel=\"gallery\" class=\"fancybox\"";
				$replace[3] = "";
			}else{
				$bubble_code = file_get_contents($this->site->dir."/templates/codes/".$this->code_title.".tpl");
				if(!strstr($bubble_code, "rel=\"gallery\"")){
					$search[3] = "><img src=";
					$replace[3] = " rel=\"gallery\" class=\"fancybox\"><img src=";
				}
			}
		}*/
		
		file_replace($this->site->dir."/templates/codes/".$this->code_title.".tpl", $search, $replace);

		//rename the bubble tpl
		@rename($this->site->dir."/templates/codes/".$this->code_title.".tpl", $this->site->dir."/templates/codes/".normalize($request['name']).".tpl");

		//setting back to codes database
		$db->query("use ".$db_setup['database']);

		$this->title = $request['name'];
		$this->folder = $request['folder'];
		$this->code_title = normalize($this->title);
//		if($request['fancybox']=='1'){$this->fancybox=1;}else{$this->fancybox=0;}
		$this->fancybox=1;

		//add module and it's data to codes db
		$db->query("update modules set title='".$this->title."', serialized='".base64_encode(serialize($this))."' where id='".$this->id."'");

		return true;
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*	function updateAsPartOfOtherModule($request, $module){
		global $db;
		if($request['gallery']){
			if($module->gallery){
				//we need to update here
				
			}else{
				//same as adding
				$this->mod_gallery->add($request);
			}
		}else{
			if($module->gallery){
				//delete all the g2o of the previous galleries
				$db->query("delete from gallery2object where object_id='".$request['object_id']."' and table='".$request['object_table']."'");
			}
		}
	}*/
	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	function delete($tbl){
		global $db;
		$db->query("use ".$this->site->db_name);
		$q = $db->query("select distinct gallery_id from ".$this->site->db_prefix."gallery2object where object_table='".$tbl."'");
		foreach($q->result() as $row){
			//1 select folder of the gallery and delete it
			$q1 = $db->query("select folder from ".$this->site->db_prefix."galleries where id='".$row->gallery_id."'");
			$row1 = $q1->next_row();
			advanced_rmdir($this->site->dir."/".$row1->folder);
			//2 delete the gallery
			$db->query("delete from ".$this->site->db_prefix."galleries where id='".$row->gallery_id."'");
			//3 delete the images from the gallery
			$db->query("delete from ".$this->site->db_prefix."images where gallery_id='".$row->gallery_id."'");
		}
	}

}
?>