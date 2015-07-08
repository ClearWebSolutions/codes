<?
class PageBase{

	function PageBase($id=0){
		global $db, $settings, $_SESSION;
		if($id){
			$this->id = $id;
			$q = $db->query("select * from ".DBPREFIX."pages where lang_parent='".$this->id."' and language='".$settings->language."'");
			$row = $q->next_row();
			$this->lang_id = $row->id;
			$this->parent_id = $row->parent_id;
			$this->title = stripslashes($row->title);
			$this->meta_title = stripslashes($row->meta_title);
			$this->meta_description = stripslashes($row->meta_description);
			$this->meta_keywords = stripslashes($row->meta_keywords);
			$this->content_areas = $row->content_areas;
			$this->content1 = stripslashes($row->content1);
			$this->content2 = stripslashes($row->content2);
			$this->content3 = stripslashes($row->content3);
			$this->content4 = stripslashes($row->content4);
			$this->content5 = stripslashes($row->content5);
			$this->content[1]['title'] = stripslashes($row->content1_title);
			$this->content[2]['title'] = stripslashes($row->content2_title);
			$this->content[3]['title'] = stripslashes($row->content3_title);
			$this->content[4]['title'] = stripslashes($row->content4_title);
			$this->content[5]['title'] = stripslashes($row->content5_title);
			$this->locked = $row->locked;
			$this->ordr = $row->ordr;

			$q = $db->query("select * from ".DBPREFIX."gallery2object where object_id='".$this->id."' and object_table='pages' and locked='0' order by ordr asc");
			foreach($q->result() as $row){
				$g = new Gallery($row->gallery_id, $row->id);
				$g->title = $row->gallery_title;
				$g->multi = $row->multi;
				$this->galleries[] = $g;
			}

			if(isset($_SESSION['admin'])){
				$_SESSION['login'] = 'adminIDHereToProtectCKFinder';//this is used in CKFinder/config.php if you'll store the adminID in another var just change it there
				require_once(BASEPATH.'/admin/editor/ckeditor.php');
				$e = new CKeditor();

//CKeditor::initComplete;

				for($i=0;$i<sizeof($settings->languages);$i++){
					$q = $db->query("select * from ".DBPREFIX."pages where lang_parent='".$this->id."' and language='".$settings->languages[$i]['id']."'");
					if($q->num_rows()){$row = $q->next_row();}else{$row = false;}
					$this->content[$settings->languages[$i]['id']]['id'] = $row?$row->id:$row;
					$this->content[$settings->languages[$i]['id']][1] = $e->editor('content1-'.$settings->languages[$i]['id'], stripslashes($row->content1));
					$this->content[$settings->languages[$i]['id']][2] = $e->editor('content2-'.$settings->languages[$i]['id'], stripslashes($row->content2)); 
					$this->content[$settings->languages[$i]['id']][3] = $e->editor('content3-'.$settings->languages[$i]['id'], stripslashes($row->content3));
					$this->content[$settings->languages[$i]['id']][4] = $e->editor('content4-'.$settings->languages[$i]['id'], stripslashes($row->content4));
					$this->content[$settings->languages[$i]['id']][5] = $e->editor('content5-'.$settings->languages[$i]['id'], stripslashes($row->content5));
					$this->meta[$settings->languages[$i]['id']]['title'] = fix_quotes($row->meta_title);
					$this->meta[$settings->languages[$i]['id']]['description'] = fix_quotes($row->meta_description);
					$this->meta[$settings->languages[$i]['id']]['keywords'] = fix_quotes($row->meta_keywords);
				}
			}
		}
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function getAll($request=array()){
		global $db, $settings;
		//if we enter the page from another page we need to clear the session
		if(!$request['order_by']&&!$request['order']&&!$request['page']&&!$request['search']) $_SESSION['admin_pages'] = '';
		
		if($request['order_by']){
			$this->order_by = $request['order_by'];
			if($_SESSION['admin_pages']['order_by']==$this->order_by&&$_SESSION['admin_pages']['order']=='asc'){	$request['order']='desc';}
			if($_SESSION['admin_pages']['order_by']==$this->order_by&&$_SESSION['admin_pages']['order']=='desc'){	$request['order']='asc';}
		}else{
			if($_SESSION['admin_pages']['order_by']){$this->order_by = $_SESSION['admin_pages']['order_by'];}else{$this->order_by = 'id';}
		}
		if($request['order']){$this->order = $request['order'];}else{
			if($_SESSION['admin_pages']['order']){$this->order = $_SESSION['admin_pages']['order'];}else{$this->order = 'asc';}
		}
		if($request['page']){$this->page = $request['page'];}else{
			if($_SESSION['admin_pages']['page']){$this->page = $_SESSION['admin_pages']['page'];}else{$this->page = 1;}
		}
		if($request['search']){$this->search = $request['search'];}else{
			if($_SESSION['admin_pages']['search']){$this->search = $_SESSION['admin_pages']['search'];}else{$this->search = '';}
		}
		$_SESSION['admin_pages']['order_by'] = $this->order_by;
		$_SESSION['admin_pages']['order'] = $this->order;
		$_SESSION['admin_pages']['page'] = $this->page;
		$_SESSION['admin_pages']['search'] = $this->search;
		
		if($this->search){
			$searchsql = " and title like '%".$this->search."%' ";
		}else{
			$searchsql='';
		}
		$sql = $searchsql." order by ".$this->order_by." ".$this->order." limit ".($this->page-1)*$settings->arpp.", ".$settings->arpp;
		$q = $db->query("select * from ".DBPREFIX."pages where title!='' and new!='1' and language='".$settings->language."'".$sql);
		$i=0;
		foreach($q->result() as $row){
			$pages[$i]['id'] = $row->id;
			$pages[$i]['title'] = $row->title;
			$pages[$i]['locked'] = $row->locked;
			$pages[$i]['ordr'] = $row->ordr;
			$i++;
		}

		$q = $db->query("select * from ".DBPREFIX."pages".$searchsql);
		$this->pagination = pagination($this->page, (int)$q->num_rows(), $settings->arpp);
		return $pages;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function getAll4Menu($id){
		global $db, $settings;
		$q = $db->query("select lang_parent, title, child_page_template_id from ".DBPREFIX."pages where language='".$settings->language."' and locked='0' and new='0' and parent_id='".$id."' order by ordr");
		if($q->num_rows()>0){
			foreach($q->result() as $row){
				$pages[] = array('id'=>$row->lang_parent, 'title'=>stripslashes($row->title), 'children'=>$this->getAll4Menu($row->lang_parent));
			}
			return $pages;
		}else{
			return array();
		}
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function add($parent_id){
		global $db,$settings;
		//get child template id
		$q = $db->query("select child_page_template_id from ".DBPREFIX."pages where id='".$parent_id."'");
		if($q->num_rows()<=0){$this->error = "Something went wrong"; return false;}
		$row = $q->next_row();
		//get child template info
		$templateid = $row->child_page_template_id;
		if($templateid==0){$this->error = "Can't add subpages to this page!";return false;}
		$q = $db->query("select * from ".DBPREFIX."pages where id='".$templateid."'");
		if($q->num_rows()<=0){$this->error = "Can't find subpage template!";return false;}
		$row = $q->next_row();
		//create a new page via insert with locked='1'
		$db->query("insert into ".DBPREFIX."pages set new='1', parent_id='".$parent_id."', language='".$settings->language."', content_areas='".$row->content_areas."', content1_title='".$row->content1_title."', content2_title='".$row->content2_title."', content3_title='".$row->content3_title."', content4='".$row->content4_title."', content5='".$row->content5_title."'");
		$id = $db->insert_id();
		$db->query("update ".DBPREFIX."pages set lang_parent='".$id."' where id='".$id."'");
		//create all the proper g2o with locked=1
		$q = $db->query("select * from ".DBPREFIX."gallery2object where object_table='pages' and object_id='".$templateid."' order by ordr asc");
		foreach($q->result() as $row){
			$db->query("insert into ".DBPREFIX."gallery2object set gallery_id='".$row->gallery_id."', object_id='".$id."', object_table='pages', ordr='".$row->ordr."', multi='".$row->multi."', locked='0', gallery_title='".$row->gallery_title."'");
		}
		return $id;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function update($request){
		global $db, $settings;
		$this->error = "";
		if(!$request['title']&&!$this->title){$this->error = "Title can't be empty!";return false;}
		foreach($settings->languages  as $lang){
			if($this->content[$lang['id']]['id']){
				if($request['title']){$this->title = $request['title'];}
				$db->query("update ".DBPREFIX."pages set new='0', title='".addslashes($this->title)."', meta_title='".addslashes($request['meta_title'.$lang['id']])."', meta_description='".addslashes($request['meta_description'.$lang['id']])."', meta_keywords='".addslashes($request['meta_keywords'.$lang['id']])."', content1='".addslashes($request['content1-'.$lang['id']])."', content2='".addslashes($request['content2-'.$lang['id']])."', content3='".addslashes($request['content3-'.$lang['id']])."', content4='".addslashes($request['content4-'.$lang['id']])."', content5='".addslashes($request['content5-'.$lang['id']])."' where id='".$this->content[$lang['id']]['id']."'");
			}else{
				$db->query("insert into ".DBPREFIX."pages set new='0', title='".addslashes($this->title)."', parent_id='".$this->parent_id."', lang_parent='".$this->id."', language='".$lang['id']."', ordr='".$this->ordr."', locked='".$this->locked."', meta_title='".addslashes($request['meta_title'.$lang['id']])."', meta_description='".addslashes($request['meta_description'.$lang['id']])."', meta_keywords='".addslashes($request['meta_keywords'.$lang['id']])."', content1='".addslashes($request['content1-'.$lang['id']])."', content2='".addslashes($request['content2-'.$lang['id']])."', content3='".addslashes($request['content3-'.$lang['id']])."', content4='".addslashes($request['content4-'.$lang['id']])."', content5='".addslashes($request['content5-'.$lang['id']])."'");
			}
		}
		return true;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function updateLocked($request){
		global $db;
		if($this->id){
			if($request['locked']==1){
				$db->query("update ".DBPREFIX."pages set locked='1' where lang_parent='".$this->id."'");
			}else{
				$db->query("update ".DBPREFIX."pages set locked='0' where lang_parent='".$this->id."'");
			}
		}
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function updateOrdr($request){
		global $db;
		if(($request['ordr']||$request['ordr']==0)&&$this->id){
			$db->query("update ".DBPREFIX."pages set ordr='".$request['ordr']."' where lang_parent='".$this->id."'");
		}
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function delete(){
		global $db;
		if(!$this->id){$this->error = "Item is not initialized.";return false;}
		$db->query("delete from ".DBPREFIX."pages where lang_parent='".$this->id."'");
		//for each gallery associated with a page delete it
		foreach($this->galleries as $gallery){
			//1: delete all the gallery images
			$db->query("delete from ".DBPREFIX."images where g2o_id='".$gallery->g2o."'");
			//2: delete the gallery folder
			@rrmdir($gallery->folder);
			//3: delete the gallery2object row associated with this gallery
			$db->query("delete from ".DBPREFIX."gallery2object where id='".$gallery->g2o."'");
		}

		return true;
	}

}
?>