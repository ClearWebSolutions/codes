<?
class VocabularyBase{

	function VocabularyBase($lang, $request){
		global $db, $settings;
		
		if($request['id']){
			$this->id = $request['id'];
			$q = $db->query("select * from ".DBPREFIX."vocabulary where lang_parent='".$this->id."'");
			foreach($q->result() as $row){
				$this->label = fix_quotes($row->label);
				$this->phrase[$row->language] = fix_quotes($row->phrase);
			}
		}
		
		if($lang&&!$request['id']){
			//if we enter the page from another page we need to clear the session
			if(!$request['order_by']&&!$request['order']&&!$request['page']&&!$request['search']) $_SESSION['admin_vocabulary'] = '';
			
			if($request['order_by']){$this->order_by = $request['order_by'];
									if($_SESSION['admin_vocabulary']['order_by']==$this->order_by&&$_SESSION['admin_vocabulary']['order']=='asc'){	$request['order']='desc';}
									if($_SESSION['admin_vocabulary']['order_by']==$this->order_by&&$_SESSION['admin_vocabulary']['order']=='desc'){	$request['order']='asc';}
			}else{
				if($_SESSION['admin_vocabulary']['order_by']){$this->order_by = $_SESSION['admin_vocabulary']['order_by'];}else{$this->order_by = 'lang_parent';}
			}
			if($request['order']){$this->order = $request['order'];}else{
				if($_SESSION['admin_vocabulary']['order']){$this->order = $_SESSION['admin_vocabulary']['order'];}else{$this->order = 'asc';}
			}
			if($request['page']){$this->page = $request['page'];}else{
				if($_SESSION['admin_vocabulary']['page']){$this->page = $_SESSION['admin_vocabulary']['page'];}else{$this->page = 1;}
			}
			if($request['search']){$this->search = $request['search'];}else{
				if($_SESSION['admin_vocabulary']['search']){$this->search = $_SESSION['admin_vocabulary']['search'];}else{$this->search = '';}
			}
			$_SESSION['admin_vocabulary']['order_by'] = $this->order_by;
			$_SESSION['admin_vocabulary']['order'] = $this->order;
			$_SESSION['admin_vocabulary']['page'] = $this->page;
			$_SESSION['admin_vocabulary']['search'] = $this->search;
			
			if($this->search){
				$searchsql = " and (label like '%".$this->search."%' or phrase like '%".$this->search."%')";
			}else{
				$searchsql='';
			}
			$sql = $searchsql." order by ".$this->order_by." ".$this->order." limit ".($this->page-1)*$settings->arpp.", ".$settings->arpp;
			$q = $db->query("select * from ".DBPREFIX."vocabulary where language='".$lang."'".$sql);
			$i=0;
			foreach($q->result() as $row){
				$this->byLabel[$row->label] = $row->phrase;
				$this->byId[$row->lang_parent] = $row->phrase;
				$this->labels[$i] = array('id'=>$row->lang_parent, 'label'=>stripslashes($row->label), 'phrase'=>stripslashes($row->phrase));
				$i++;
			}

			$q = $db->query("select * from ".DBPREFIX."vocabulary where language='".$lang."'".$searchsql);
			$this->pagination = pagination($this->page, (int)$q->num_rows(), $settings->arpp);
		}
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function add($request){
		global $db, $settings;

		//check if label is not empty
		if($request['label']==''){$this->error = "Label can't be empty!";return false;}
		if(!preg_match("/^[a-zA-Z][a-zA-Z0-9_]+$/", $request['label'])){ $this->error = "Please select another label. No spaces, no starting with numbers, no special chars!"; return false;}

		//check if label already exists
		$q = $db->query("select id from ".DBPREFIX."vocabulary where label='".addslashes($request['label'])."'");
		if($q->num_rows()>0){$this->error = "There is already an item with such label in the vocabulary!"; return false;}

		//add label to DB
		$db->query("insert into ".DBPREFIX."vocabulary set label='".addslashes($request['label'])."', language='en', phrase='".addslashes($request['phrase-en'])."'");
		$this->id = $db->insert_id();
		$db->query("update ".DBPREFIX."vocabulary set lang_parent='".$this->id."' where id='".$this->id."'");

		for($i=0;$i<sizeof($settings->languages);$i++){
			$lang = $settings->languages[$i]['id'];
			if($lang!='en'){
				$db->query("insert into ".DBPREFIX."vocabulary set label='".addslashes($request['label'])."', language='".$lang."', lang_parent='".$this->id."', phrase='".addslashes($request['phrase-'.$lang])."'");
			}
		}
		return true;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function update($request){
		global $db, $settings;
		
		if(!$this->id){$this->error = "Item is not initialized.";return false;}
		
		//check if label is not empty
		if($request['label']==''){$this->error = "Label can't be empty!";return false;}
		if(!preg_match("/^[a-zA-Z][a-zA-Z0-9_]+$/", $request['label'])){ $this->error = "Please select another label. No spaces, no starting with numbers, no special chars!"; return false;}


		//check if label already exists
		if($this->label!=$request['label']){
			$q = $db->query("select id from ".DBPREFIX."vocabulary where label='".addslashes($request['label'])."'");
			if($q->num_rows()>0){$this->error = "There is already an item with such label in the vocabulary!"; return false;}
		}

		//update the db
		$this->label = fix_quotes($request['label']);
		for($i=0;$i<sizeof($settings->languages);$i++){
			$lang = $settings->languages[$i]['id'];
			$this->phrase[$lang] = fix_quotes($request['phrase-'.$lang]);
			$q = $db->query("select id from ".DBPREFIX."vocabulary where language='".$lang."' and lang_parent='".$this->id."'");
			if($q->num_rows()>0){
				$db->query("update ".DBPREFIX."vocabulary set label='".addslashes($request['label'])."', phrase='".addslashes($request['phrase-'.$lang])."' where lang_parent='".$this->id."' and language='".$lang."'");
			}else{
				$db->query("insert into ".DBPREFIX."vocabulary set label='".addslashes($request['label'])."', language='".$lang."', lang_parent='".$this->id."', phrase='".addslashes($request['phrase-'.$lang])."'");
			}
		}
		return true;
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function delete(){
		global $db;
		if(!$this->id){$this->error = "Item is not initialized.";return false;}
		$db->query("delete from ".DBPREFIX."vocabulary where lang_parent='".$this->id."'");
		return true;
	}

}
?>