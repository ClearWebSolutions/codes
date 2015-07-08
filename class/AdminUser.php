<?php
class AdminUser{

	function AdminUser($id=0){
		global $db;
		if($id){
			$this->id = $id;
			$q = $db->query("select * from admin where id='".$this->id."'");
			$row = $q->next_row();
			$this->client_email = $row->client_email;
			$this->client_username = $row->client_username;
			$this->client_password = $row->client_password;
			$this->db_prefix = $row->db_prefix;
			$this->db_user = $row->db_user;
			$this->db_password = $row->db_password;
			$this->db_host = $row->db_host;
			
		}
	}

/////////////////////////////////////////////////////////////////////////////////////////

	function updateSettings($request){
		global $db;
		$db->query("update admin set client_email='".$request['client_email']."', client_username='".$request['client_username']."', client_password='".$request['client_password']."', db_prefix='".$request['db_prefix']."', db_user='".$request['db_user']."', db_password='".$request['db_password']."', db_host='".$request['db_host']."' where id='".$this->id."'");
	}
}
?>