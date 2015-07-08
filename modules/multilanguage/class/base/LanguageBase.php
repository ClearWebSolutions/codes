<?
class LanguageBase{

	function LanguageBase($request=array()){
		global $db, $settings;
		if($request['id']){
			$this->id = $request['id'];
			$q = $db->query("select * from ".DBPREFIX."languages where id='".$this->id."'");
			$row = $q->next_row();
			$this->language = fix_quotes($row->language);
			$this->locked = $row->locked;
			$this->ordr = $row->ordr;
		}
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function getAll($request=array()){
		global $db, $settings;
		//if we enter the page from another page we need to clear the session
		if(!$request['order_by']&&!$request['order']&&!$request['page']&&!$request['search']) $_SESSION['admin_languages'] = '';
		
		if($request['order_by']){
			$this->order_by = $request['order_by'];
			if($_SESSION['admin_languages']['order_by']==$this->order_by&&$_SESSION['admin_languages']['order']=='asc'){	$request['order']='desc';}
			if($_SESSION['admin_languages']['order_by']==$this->order_by&&$_SESSION['admin_languages']['order']=='desc'){	$request['order']='asc';}
		}else{
			if($_SESSION['admin_languages']['order_by']){$this->order_by = $_SESSION['admin_languages']['order_by'];}else{$this->order_by = 'language';}
		}
		if($request['order']){$this->order = $request['order'];}else{
			if($_SESSION['admin_languages']['order']){$this->order = $_SESSION['admin_languages']['order'];}else{$this->order = 'asc';}
		}
		if($request['page']){$this->page = $request['page'];}else{
			if($_SESSION['admin_languages']['page']){$this->page = $_SESSION['admin_languages']['page'];}else{$this->page = 1;}
		}
		if($request['search']){$this->search = $request['search'];}else{
			if($_SESSION['admin_languages']['search']){$this->search = $_SESSION['admin_languages']['search'];}else{$this->search = '';}
		}
		$_SESSION['admin_languages']['order_by'] = $this->order_by;
		$_SESSION['admin_languages']['order'] = $this->order;
		$_SESSION['admin_languages']['page'] = $this->page;
		$_SESSION['admin_languages']['search'] = $this->search;
		
		if($this->search){
			$searchsql = " where language like '%".$this->search."%' ";
		}else{
			$searchsql='';
		}
		$sql = $searchsql." order by ".$this->order_by." ".$this->order." limit ".($this->page-1)*$settings->arpp.", ".$settings->arpp;
		$q = $db->query("select * from ".DBPREFIX."languages".$sql);
		$i=0;
		foreach($q->result() as $row){
			$languages[$i]['id'] = $row->id;
			$languages[$i]['language'] = $row->language;
			$languages[$i]['locked'] = $row->locked;
			$languages[$i]['ordr'] = $row->ordr;
			$i++;
		}

		$q = $db->query("select * from ".DBPREFIX."languages".$searchsql);
		$this->pagination = pagination($this->page, (int)$q->num_rows(), $settings->arpp);
		return $languages;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function updateLocked($request){
		global $db;
		if($this->id){
			if($request['locked']==1){
				$db->query("update ".DBPREFIX."languages set locked='1' where id='".$this->id."'");
			}else{
				$db->query("update ".DBPREFIX."languages set locked='0' where id='".$this->id."'");
			}
		}
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function updateOrdr($request){
		global $db;
		if(($request['ordr']||$request['ordr']==0)&&$this->id){
			$db->query("update ".DBPREFIX."languages set ordr='".$request['ordr']."' where id='".$this->id."'");
		}
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function add($request){
		global $db, $settings;

		$this->id = $request['id'];
		$this->language = $request['language'];
		//check if label is not empty
		if($request['id']==''){$this->error = "ID can't be empty!";return false;}
		if(strlen($request['id'])>2){$this->error = "ID can't be more than 2 characters!";return false;}
		if(!preg_match("/^[a-z][a-z]$/", $request['id'])){ $this->error = "ID should be 2 lowercase letters!"; return false;}
		if($request['language']==''){ $this->error = "Language can't be empty!"; return false;}

		//check if language already exists
		$q = $db->query("select id from ".DBPREFIX."languages where language='".addslashes($request['language'])."' or id='".$request['id']."'");
		if($q->num_rows()>0){
			$row = $q->next_row();
			if($row->id==$request['id']){
				$this->error = "There is already a language with such ID!"; return false;
			}else{
				$this->error = "There is already a language with such name!"; return false;
			}
		}

		//add label to DB
		$q = $db->query("select max(ordr) as ordr from ".DBPREFIX."languages");
		$row = $q->next_row();
		$ordr = $row->ordr+1;
		$db->query("insert into ".DBPREFIX."languages set language='".addslashes($request['language'])."', id='".$request['id']."', ordr='".$ordr."'");
		$this->id = $db->insert_id();
		return true;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function update($request){
		global $db, $settings;

		if(!$this->id){$this->error = "Item is not initialized";return false;}
		
		//check if label is not empty
		if($request['language']==''){ $this->error = "Language can't be empty!"; return false;}

		//check if language already exists
		if($this->language!=$request['language']){
			$q = $db->query("select id from ".DBPREFIX."languages where language='".addslashes($request['language'])."'");
			if($q->num_rows()>0){
				$this->error = "There is already a language with such name!".htmlspecialchars($this->language); return false;
			}
		}

		$db->query("update ".DBPREFIX."languages set language='".addslashes($request['language'])."' where id='".$request['id']."'");
		$this->id = $request['id'];
		$this->language = fix_quotes($request['language']);
		return true;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function delete(){
		global $db;
		if(!$this->id){$this->error = "Item is not initialized.";return false;}
		$db->query("delete from ".DBPREFIX."languages where id='".$this->id."'");
		return true;
	}

}
?>