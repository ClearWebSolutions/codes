<?php
class AdminUserBase{

	function AdminUserBase($id=0){
		if($id){
			$this->id = $id;
		}
	}

////////////////////////////////////////////////////////////////////////////////////////

	function login($request){
		global $db, $_SESSION;
		if(!$request['username']){	$this->error = "Please enter username!";return false;	}
		if(!$request['password']){	$this->error = "Please enter password!";return false;	}
		$q = $db->query("select id, email from ".DBPREFIX."admin where username='".addslashes($request['username'])."' and password='".addslashes($request['password'])."'");
		if($q->num_rows()>0){
			$row = $q->next_row();
			$this->id = $row->id;
			$this->username = $request['username'];
			$this->email = $row->email;
			if($request['rememberme']){
				setcookie("admin", $this->username, time()+3600*24*365);
				setcookie("adminid", $this->id, time()+3600*24*365);
				setcookie("adminemail", $this->email, time()+3600*24*365);
			}
			$this->setSessionVars();
		}else{
			$this->error = "Wrong username/password!";
			return false;
		}
		return true;
	}

////////////////////////////////////////////////////////////////////////////////////////

	function loginByCookie(){
		global $_COOKIE;
		$this->id = $_COOKIE["adminid"];
		$this->username = $_COOKIE["admin"];
		$this->email = $_COOKIE["adminemail"];
		$this->setSessionVars();
		return true;
	}

////////////////////////////////////////////////////////////////////////////////////////

	function setSessionVars(){
		global $_SESSION;
		$_SESSION['admin']['id'] = $this->id;
		$_SESSION['admin']['username'] = $this->username;
		$_SESSION['admin']['email'] = $this->email;
	}

/////////////////////////////////////////////////////////////////////////////////////////

	function validateEmail($email){
		global $db;
		if(!$email){	$this->error = "No email provided.";return false;}
		$q = $db->query("select username, password from ".DBPREFIX."admin where email='".addslashes($email)."'");
		if($q->num_rows()>0){
			$row = $q->next_row();
			$a = array("username"=>$row->username, "password"=>$row->password);
			return $a;
		}
		return false;
	}

/////////////////////////////////////////////////////////////////////////////////////////

	function update($request){
		global $db, $_SESSION;
		if($this->id){
			$q = $db->query("select username, password from ".DBPREFIX."admin where username='".addslashes($request['username'])."' and password='".addslashes($request['password'])."'");
			if($q->num_rows()<=0){
				$this->error = "Wrong username/password!"; return false;
			}
			$row = $q->next_row();
			$this->username = $row->username;
			if($request['new_username']){$qry = ", username='".addslashes($request['new_username'])."'";}
			if($request['new_password']&&$request['new_password']!=$request['new_password1']){$this->error = "Passwords don't match";return false;}
			if($request['new_password']){$qry.=", password='".addslashes($request['new_password'])."'";}
			$db->query("update ".DBPREFIX."admin set email='".addslashes($request['email'])."' ".$qry." where id='".$this->id."'");
			//in case we had a cookie login on the update of the username and or password it should be removed, so next time it will ask for login
			if($request['new_username']||$request['new_password']){
				if(isset($_COOKIE["admin"])){
					setcookie("admin", "", time()-3600);
					setcookie("adminid", "", time()-3600);
					setcookie("adminemail", "", time()-3600);
				}
				if($request['new_username']) $this->username=$request['new_username'];
			}
			//in case email was updated update it in the cookie
			if($this->email!=$request['email']){
				if(isset($_COOKIE["admin"])){
					setcookie("adminemail", $request['email'], time()+3600*24*365);
				}
				$this->email = $request['email'];
			}
			$this->setSessionVars();
			return true;
		}else{
			$this->error = "Something went wrong. Log in again and try once again.";
			return false;
		}
	}

/////////////////////////////////////////////////////////////////////////////////////////

	function update_meta($request){
		global $db;
		$db->query("update ".DBPREFIX."admin set meta_title='".addslashes($request['meta_title'])."', meta_description='".addslashes($request['meta_description'])."', meta_keywords='".addslashes($request['meta_keywords'])."' where id='1'");
		return true;
	}

}
?>