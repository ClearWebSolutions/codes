<?php
class ModContent{

	function ModContent($id=0){
		global $db, $_SESSION;
		$this->g2o_module_name = 'pages';//required for each module that has an optional gallery, used in gallery2object table
//		$this->site = new Site($_SESSION['siteid']);
		$site = new Site($_SESSION['siteid']);
		$this->site = new stdClass();
		$this->site->db_name = $site->db_name;
		$this->site->db_prefix = $site->db_prefix;
		$this->site->dir = $site->dir;
		if($id){
			$this->id = $id;
			$q = $db->query("select * from modules where id='".$id."'");
			$row = $q->next_row();
			$m = unserialize(base64_decode($row->serialized));
			if(gettype($m)!='object') $m = unserialize($row->serialized); //fix for websites already using old serialize
			$this->page = $m->page;
			$this->pages_id = $m->pages_id;
			$this->title = $m->title;
			$this->parent = $m->parent;
			$this->original_parent = $m->parent;//same as above, but different name for update check
			$this->original_parent_page = $m->parent_page;
			$this->content_areas = $m->content_areas;
			$this->content[1]['title'] = $m->content1_title;
			$this->content[2]['title'] = $m->content2_title;
			$this->content[3]['title'] = $m->content3_title;
			$this->content[4]['title'] = $m->content4_title;
			$this->content[5]['title'] = $m->content5_title;
			$this->gallery = $m->gallery;//just 0 or 1

			$this->is_template = 0;
			if($this->parent){
				$db->query("use ".$this->site->db_name);
				$q = $db->query("select child_page_template_id from ".$this->site->db_prefix."pages where id='".$this->original_parent_page->pages_id."'");
				$row = $q->next_row();
				if($this->original_parent_page->child_page_template_id==$row->child_page_template_id)
					$this->is_template = 1;
				//else it was replaced by the other template and so the is_template is still 0
			}
			
			//init the temp gallery module
			$gal = new ModGallery();

			//getAll will return the array of ModGallery modules associated with this Content module
			//@$object_table - passing the variable with object_table name used in client's database makes it unified for building into other modules
			//ModContent is a pages module and stores in pages table that's why we pass 'pages' and not 'content'
			//@$object_id - passing the object_id to find out which galleries are associated with particularly this object
			if($this->is_template){
				$this->galleries = $gal->getAll('pages', $this->original_parent_page->child_page_template_id);
			}else{
				$this->galleries = $gal->getAll('pages', $this->pages_id);
			}
			$this->galleries_multi = $gal->multi;
		}
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function add($request){
		global $db, $_SESSION;

		if(!$this->check($request)) return false;

		if($request['gallery']==1){
			//we have to do this check before we have started adding stuff to the db
			$this->mod_gallery = new ModGallery();
			if($this->mod_gallery->check($request)==false){
				$this->error  = $this->mod_gallery->error;
				return false;
			}
			$this->gallery = 1;
		}

		$this->title = $request['title'];
		$this->content_areas = $request['content_areas']?$request['content_areas']:0;
		$this->content1_title = $request['content1']?$request['content1']:'';
		$this->content2_title = $request['content2']?$request['content2']:'';
		$this->content3_title = $request['content3']?$request['content3']:'';
		$this->content4_title = $request['content4']?$request['content4']:'';
		$this->content5_title = $request['content5']?$request['content5']:'';

		//initializing page where the code would be added
		$this->page = new Page($request['page']);

		//switch to site's db
		$db->query("use ".$this->site->db_name);

		//if table pages exist don't do some steps
		$q = $db->query("show tables like '".$this->site->db_prefix."pages'");
		if($q->num_rows()<=0){
				//add pages DB table
				$query = '';
				$lines = file('modules/content/db.sql');
				//removing BOM if exists
				if(substr($lines[0],0,3) == pack("CCC",0xef,0xbb,0xbf)){ $lines[0]=substr($lines[0], 3); }
				foreach($lines as $line){
					// Skip it if it's a comment
					if(substr($line,0,1) == '#' || $line == '' || substr($line, 0, 2) == '/*'||substr($line,0,2)=='--')
						continue;
					// Add this line to the current segment
					$query .= $line;
					// If it has a semicolon at the end, it's the end of the query
					if (substr(trim($line), -1, 1) == ';'){
						$query = str_replace("DBPREFIX", $this->site->db_prefix, $query);
						$db->query($query);
						$query = "";
					}
				}
		
				//add page management files to admin and Page class
				dir_copy("modules/content",$this->site->dir);
		
				//delete db.sql
				@unlink($this->site->dir."/db.sql");
		
				//update admin/header.php
				$header = file($this->site->dir.'/admin/header.php');
				$code =  "\n\$p = new Page();\n";
				$code .= "\$allpages = \$p->getAll4Menu(0);\n";
				$code .= "\$smarty->assign_by_ref(\"allpages\",\$allpages);\n\n";
				$updated_header = "";
				for($i=0;$i<sizeof($header);$i++){
					if($i==sizeof($header)-1) $updated_header .= $code;
					$updated_header .= $header[$i];
				}
				file_put_contents($this->site->dir."/admin/header.php", $updated_header);
		
				//update classes.php
				$classes = file($this->site->dir.'/class/includes/classes.php');
				$code =  "require_once(BASEPATH.'/class/base/PageBase.php');\n";
				$code .= "require_once(BASEPATH.'/class/Page.php');\n";
				$updated_classes = "";
				for($i=0;$i<sizeof($classes);$i++){
					if($i==sizeof($classes)-2) $updated_classes .= $code;
					$updated_classes .= $classes[$i];
				}
				file_put_contents($this->site->dir."/class/includes/classes.php", $updated_classes);
		
				//update the admin menu with pages
				$menu = file($this->site->dir.'/admin/templates/menu.tpl');
				$newitem = "\t\t<li {if \$menu=='pages'}class=\"selected1\"{/if}><a href=\"pages.php\">Pages<br/><div class=\"tri\"></div></a>\n";
				$newitem .= "\t\t\t<ul>\n";
				$newitem .= "\t\t\t\t<li><a href=\"pages.php\">Browse</a></li>\n";
				$newitem .= "\t\t\t\t{foreach from=\$allpages item=element}\n";
				$newitem .= "\t\t\t\t<li><a href=\"pages.php?action=edit&id={\$element.id}\">{\$element.title}</a>\n";
				$newitem .= "\t\t\t\t\t{if \$element.children}\n";
				$newitem .= "\t\t\t\t\t\t{include file=\"menu_subpages.tpl\" element=\$element.children parent=\$element.id}\n";
				$newitem .= "\t\t\t\t\t{/if}\n";
				$newitem .= "\t\t\t\t</li>\n";
				$newitem .= "\t\t\t\t{/foreach}\n";
				$newitem .= "\t\t\t</ul>\n";
				$newitem .= "\t\t</li>\n";
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

		//check if we have the content.tpl in templates/codes already as developer might have deleted it
		if(!file_exists($this->site->dir."/templates/codes/content.tpl")){
			dir_copy("modules/content/templates/",$this->site->dir."/templates/");
		}

		//adding page to pages table
		$parent = $this->parent_page->pages_id?$this->parent_page->pages_id:0;
		$db->query("insert into ".$this->site->db_prefix."pages set language='en', parent_id='".$parent."', title='".$this->title."', content_areas='".$this->content_areas."', content1_title='".$this->content1_title."', content2_title='".$this->content2_title."', content3_title='".$this->content3_title."', content4_title='".$this->content4_title."', content5_title='".$this->content5_title."'");
		$id = $db->insert_id();
		$this->pages_id = $id;//pages table id to have a reference for future edits
		$db->query("update ".$this->site->db_prefix."pages set lang_parent='".$id."' where id='".$id."'");

		//insert Page code to page.php
		$page_code = file($this->site->dir.'/'.$this->page->filename);
		$code = "\n\$p = new Page(".$this->pages_id.");\n";
		$code .= "\$smarty->assign_by_ref(\"p\", \$p);\n\n";
		$updated_page = "";
		for($i=0;$i<sizeof($page_code);$i++){
			if(strstr($page_code[$i],"->display(\"".$this->page->template."\");")) $updated_page .= $code;
			$updated_page .= $page_code[$i];
		}
		file_put_contents($this->site->dir."/".$this->page->filename, $updated_page);

		//add code for the bubble to the header.tpl
//		$template_code = file($this->site->dir."/templates/".$this->page->template);
		$template_code = file($this->site->dir."/templates/header.tpl");
		$code = "\t{if \$page=='".$this->page->name."'}{include file='codes/content.tpl'}{/if}\n";
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
//			$new_template_code .= $code1;
			$new_template_code .= $template_code[0].$template_code[1].$code1;
		}
		file_put_contents($this->site->dir."/templates/header.tpl", $new_template_code);

		//creating the page template if parent page is selected
		if($request['parent']!=0&&$request['is_template']){
			//check if parent already has a child_page_template_id
			if($this->parent_page->child_page_template_id){
				//delete this template
				$db->query("delete from ".$this->site->db_prefix."pages where id='".$this->parent_page->child_page_template_id."'");//this will remove the previous template
				$db->query("delete from ".$this->site->db_prefix."gallery2object where object_id='".$this->parent_page->child_page_template_id."' and object_table='pages'");
			}
			//create template in pages
			$db->query("insert into ".$this->site->db_prefix."pages set new='1', locked='1', language='en', parent_id='".$this->parent_page->pages_id."', title='".$this->title."', content_areas='".$this->content_areas."', content1_title='".$this->content1_title."', content2_title='".$this->content2_title."', content3_title='".$this->content3_title."', content4_title='".$this->content4_title."', content5_title='".$this->content5_title."'");
			$this->parent_page->child_page_template_id = $db->insert_id();
			//update parent page with new child_page_template_id
			$db->query("update ".$this->site->db_prefix."pages set child_page_template_id='".$this->parent_page->child_page_template_id."' where lang_parent='".$this->parent_page->pages_id."'");
		}

		//create gallery2object records and galleries if needed
		if($request['gallery']==1){
			if($request['is_template']){$request['object_id'] = $this->parent_page->child_page_template_id;}
			$request['object_table'] = 'pages';
			$request['page_id'] = $this->pages_id;
			$this->mod_gallery->add($request);
		}

		//setting back to codes database
		$db->query("use codes");

		//add module and it's data to codes db
		$db->query("insert into modules set pageid='".$this->page->id."', module='content', title='".$this->title."', serialized='".base64_encode(serialize($this))."'");
		$this->id = $db->insert_id();

		return true;
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function check($request){
		global $db;
		if(!$request['page']&&$request['action']=='add'){	$this->error = "Please select the page first!";return false;}
		if(!$request['title']){	$this->error = "Please enter page title!";return false;}
//		if(!$request['content_areas']){ $this->error = "Please select the amount of editing zones to create.";return false; }
//		for($i=1;$i<=$request['content_areas'];$i++){
//			if(!$request['content'.$i]){$this->error = "Please enter the title for each of the content zones";return false;}
//		}
		if($request['parent']!=0){
			//check that the parent is not self
			if($request['action']=='edit'){$request['page'] = $this->page->id;}
			if($request['page']==$request['parent']){	$this->error = "Parent can't be self!";return false;}
			//check if the selected parent page has the content module
			$q = $db->query("select id from modules where pageid='".$request['parent']."' and module='content'");
			if($q->num_rows()<=0){
				$this->error = "The selected parent page doesn't have the Content module installed, please install the parent page Content module before adding it's subpage modules.";
				return false;
			}
			$row = $q->next_row();
			$this->parent = $request['parent'];
			$this->parent_page = new ModContent($row->id);
			//check if parent already has a child_page_template_id
			if($request['is_template']){
				$db->query("use ".$this->site->db_name);
				$q = $db->query("select child_page_template_id from ".$this->site->db_prefix."pages where id='".$this->parent_page->pages_id."'");
				$row = $q->next_row();
				if($row->child_page_template_id!=0){
					if($request['warning']!=1){
						$this->error = "Warning! There is already a child page template associated with this page, so you can add all the subpages via admin area of the website.<br/>However you can still submit anyway and the new page will overwrite the existing template.";
						$this->warning = 1;
						return false;
					}else{
						$this->parent_page->child_page_template_id = $row->child_page_template_id;
					}
				}
				$db->query("use codes");
			}
		}else{
			if($request['is_template']){
				$this->error = "Please select the parent page to create a template.";
				return false;
			}
		}
		return true;
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function update($request){
		global $db, $_SESSION;

		if(!$this->check($request)) return false;

		if($request['gallery']==1){
			//we have to do this check before we have started adding stuff to the db
			$this->mod_gallery = new ModGallery();
			if($this->mod_gallery->check($request)==false){
				$this->error  = $this->mod_gallery->error;
				return false;
			}
		}

		$this->title = $request['title'];
		$this->content_areas = $request['content_areas']?$request['content_areas']:0;
		$this->content1_title = $request['content1']?$request['content1']:'';
		$this->content2_title = $request['content2']?$request['content2']:'';
		$this->content3_title = $request['content3']?$request['content3']:'';
		$this->content4_title = $request['content4']?$request['content4']:'';
		$this->content5_title = $request['content5']?$request['content5']:'';

		//switch to site's db
		$db->query("use ".$this->site->db_name);

		//update page in pages table
		$parent = $this->parent_page->pages_id?$this->parent_page->pages_id:0;
		$db->query("update ".$this->site->db_prefix."pages set language='en', parent_id='".$parent."', title='".$this->title."', content_areas='".$this->content_areas."', content1_title='".$this->content1_title."', content2_title='".$this->content2_title."', content3_title='".$this->content3_title."', content4_title='".$this->content4_title."', content5_title='".$this->content5_title."' where lang_parent='".$this->pages_id."'");

		//if there was a previous parent and it is different from the one submited now during update we need to update the previous parent remove the template and clean up the g2o
		if($this->original_parent&&($this->parent!=$this->original_parent)&&$this->is_template){//means this was a template for original_parent else it was a regular page with parent which is updated above
			//set original_parent child_page_template_id to 0 and delete the old page template
			$db->query("update ".$this->site->db_prefix."pages set child_page_template_id='0' where lang_parent='".$this->original_parent_page->pages_id."'");
			$db->query("delete from ".$this->site->db_prefix."pages where id='".$this->original_parent_page->child_page_template_id."'");
			$db->query("delete from ".$this->site->db_prefix."gallery2object where object_id='".$this->original_parent_page->child_page_template_id."' and object_table='pages'");
		}

		//creating the page template if parent page is selected
		if($request['parent']!=0&&$request['is_template']){
			//check if parent already has a child_page_template_id
			if($this->parent_page->child_page_template_id){
				//delete this template
				$db->query("delete from ".$this->site->db_prefix."pages where id='".$this->parent_page->child_page_template_id."'");//this will remove the previous template
				$db->query("delete from ".$this->site->db_prefix."gallery2object where object_id='".$this->parent_page->child_page_template_id."' and object_table='pages'");
			}
			//create template in pages
			$db->query("insert into ".$this->site->db_prefix."pages set new='1', locked='1', language='en', parent_id='".$this->parent_page->pages_id."', title='".$this->title."', content_areas='".$this->content_areas."', content1_title='".$this->content1_title."', content2_title='".$this->content2_title."', content3_title='".$this->content3_title."', content4_title='".$this->content4_title."', content5_title='".$this->content5_title."'");
			$this->parent_page->child_page_template_id = $db->insert_id();
			//update parent page with new child_page_template_id
			$db->query("update ".$this->site->db_prefix."pages set child_page_template_id='".$this->parent_page->child_page_template_id."' where lang_parent='".$this->parent_page->pages_id."'");
		}

		//create/delete/update gallery2object records 
		//because pages is a different module it should be managed differently compared to ComplexObject
		$request['object_table'] = 'pages';
		if($request['gallery']){
			if($this->gallery){
				//we need to update here
				//first deleting all the g2o for the page itself
				$db->query("delete from ".$this->site->db_prefix."gallery2object where object_id='".$this->pages_id."' and object_table='pages'");
				//now in case we had a template before it was already cleaned on line 329, so we don't need to delete for template already
			}
			//as we have deleted the previous galleries associations from g2o it's just
			//same as adding
			$request['object_id'] = $this->parent_page->child_page_template_id;
			$request['page_id'] = $this->pages_id;
			$this->mod_gallery->add($request);
			$this->gallery=1;//the new value that would be serialized
		}else{
			if($this->gallery){
				//first deleting all the g2o for the page itself
				$db->query("delete from ".$this->site->db_prefix."gallery2object where object_id='".$this->pages_id."' and object_table='pages'");
				//remove previous galleries from template if is_template - already done on line 329
				//don't delete gallery table entries as they might be used by other already created pages
			}
			$this->gallery = 0;
		}

		//setting back to codes database
		$db->query("use codes");

		//update module and it's data to codes db
		$db->query("update modules set title='".$this->title."', serialized='".base64_encode(serialize($this))."' where id='".$this->id."'");
		$this->id = $db->insert_id();

		return true;
	}

}
?>