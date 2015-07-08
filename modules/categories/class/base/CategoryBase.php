<?
class CategoryBase{

	function CategoryBase($tbl, $id=0){
		global $db, $settings;
		$this->tbl = $tbl;//the table of the categories
		if($id){
			$this->id = $id;
			$this->lang_parent = $id;
			$q = $db->query("select * from ".DBPREFIX.$this->tbl." where language='".$settings->language."' and lang_parent='".$this->id."'");
			$row = $q->next_row();
			$this->title = stripslashes($row->title);
			$this->ordr = $row->ordr;
			$this->parent_id = $row->parent_id;
			$q = $db->query("select * from ".DBPREFIX.$this->tbl." where lang_parent='".$this->id."'");
			foreach($q->result() as $row)
				$this->titles4admin[$row->language] = fix_quotes($row->title);
		}
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function getAll($parent_id=0){
		global $db, $settings;
		$q = $db->query("select lang_parent, title from ".DBPREFIX.$this->tbl." where language='".$settings->language."' and parent_id='".$parent_id."' order by ordr asc");
		if($q->num_rows()>0){
			foreach($q->result() as $row){
				$categories[] = array('id'=>$row->lang_parent, 'title'=>stripslashes($row->title), 'children'=>$this->getAll($row->lang_parent));
			}
			return $categories;
		}else{
			return array();
		}
	}

	function getAllParents($id){
		global $db,$settings;
		$q = $db->query("select parent_id, title from ".DBPREFIX.$this->tbl." where language='".$settings->language."' and lang_parent='".$id."'");
		if($q->num_rows()>0){
			$row = $q->next_row();
			$categories[] = array('id'=>$id, 'title'=>$row->title, 'parents'=>$this->getAllParents($row->parent_id));
			return $categories;
		}else{
			return array();
		}
	}
	
	function getPathArray($id){
		$i = 0;
		$k=true;
		$p = $this->getAllParents($id);
		if(sizeof($p)>0){
			while($k){
				$arr[$i]['id'] = $p[0]['id'];
				$arr[$i]['title'] = $p[0]['title'];
				if(sizeof($p[0]['parents'])){
					$p = $p[0]['parents'];
					$i++;
				}else{
					$k=false;
				}
			}
			return array_reverse($arr);
		}else{
			return array();
		}
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function add($request){
		global $db, $settings;
		if(!$request['title-'.$settings->language]){$this->error = "Please enter title!";return false;}
		$db->query("insert into ".DBPREFIX.$this->tbl." set language='".$settings->language."', parent_id='".$request['parent_id']."', title='".addslashes($request['title-'.$settings->language])."'");
		$this->id = $db->insert_id();
		$this->title = $request['title-'.$settings->language];
		$db->query("update ".DBPREFIX.$this->tbl." set lang_parent='".$this->id."' where id='".$this->id."'");
		foreach($settings->languages as $lang){
			if($settings->language!=$lang['id'])
				$db->query("insert into ".DBPREFIX.$this->tbl." set language='".$lang['id']."', lang_parent='".$this->id."', parent_id='".$request['parent_id']."', title='".addslashes($request['title-'.$lang['id']])."'");
		}
		return true;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function update($request){
		global $db, $settings;
		if(!$request['title-'.$settings->language]){$this->error = "Please enter title!";return false;}
		foreach($settings->languages as $lang){
			if($settings->language==$lang['id'])$this->title = $request['title-'.$lang['id']];
			$t = $db->query("select id from ".DBPREFIX.$this->tbl." where language='".$lang['id']."' and lang_parent='".$this->id."'");
			if($t->num_rows()>0){
				$db->query("update ".DBPREFIX.$this->tbl." set title='".addslashes($request['title-'.$lang['id']])."' where language='".$lang['id']."' and lang_parent='".$this->id."'");
			}else{
				$db->query("insert into ".DBPREFIX.$this->tbl." set title='".addslashes($request['title-'.$lang['id']])."', language='".$lang['id']."', lang_parent='".$this->id."', parent_id='".$this->parent_id."', ordr='".$this->ordr."'");
			}
		}
		return true;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function updateOrder($order){
		global $db;
		$arr = explode(",",$order);
		for($i=0;$i<sizeof($arr);$i++){
			$k = $i+1;
			if($arr[$i]) $db->query("update ".DBPREFIX.$this->tbl." set ordr='".$k."' where lang_parent='".$arr[$i]."'");
		}
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function delete($id){
		global $db;
		//delete children
		$q = $db->query("select lang_parent from ".DBPREFIX.$this->tbl." where parent_id='".$id."'");
		foreach($q->result() as $row)
			$this->delete($row->lang_parent);
		//delete the category itself
		$db->query("delete from ".DBPREFIX.$this->tbl." where lang_parent='".$id."'");
	}
}
?>