<?php
class Page{

	function Page($id=0){
		global $db;
		if($id){
			$this->id = $id;
			$this->init();
		}
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function init(){
		global $db;
		$db->query("use codes");
		$q = $db->query("select * from pages where id='".$this->id."'");
		$row = $q->next_row();
		$this->siteid = $row->siteid;
		$this->name = $row->name;
		$this->filename = $row->name.".php";
		$this->template = $row->template;
		$q = $db->query("select * from modules where pageid='".$this->id."' order by title asc");
		foreach($q->result() as $row){
			$this->modules[] = array("id"=>$row->id,"title"=>stripslashes($row->title), "module"=>$row->module);
		}
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function init_by_name($name){
		global $db;
		$name = substr($name, 0, -4);
		$q = $db->query("select * from pages where name='".$name."'");
		$row = $q->next_row();
		$this->id = $row->id;
		$this->init();
	}

}
?>