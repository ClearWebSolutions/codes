<?php
require_once('../class/includes/include.php');

$smarty->assign("menu","index");

$user = new AdminUser();

if(isset($_COOKIE["admin"])){
	$user->loginByCookie();
	header("Location: ".ADMIN_START_PAGE);
}

//log in
if($_REQUEST["sbm"]==1){
	if($user->login($_REQUEST)){
		header("Location: ".ADMIN_START_PAGE);
	}else{
		$smarty->assign("error",$user->error);
	}
}

//forgot pass requested via XHR
if($_REQUEST["email"]){
	$to = $_REQUEST['email'];
	$e = new Email();
	if($e->validate($to)){
		if($access = $user->validateEmail($to)){
			$from = $settings->email;
			$subject = "Your ".$settings->url." admin access request";
			$msg = "You've requested the password to your admin panel for ".$settings->url."<br/><br/>";
			$msg .= "Your username is: <b>".$access['username']."</b><br/>";
			$msg .= "Your password is: <b>".$access['password']."</b><br/>";
			$e->send($to, $from, $subject, $msg);
		}else{
			echo "No such email in our system.";
		}
	}else{
		echo "Please enter a valid email address.";
	}
	exit;
}


$smarty->display("login.tpl");
?>