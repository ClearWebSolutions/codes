<?php
require_once('header.php');

$smarty->assign("menu","account");

if($_REQUEST['sbm']){
	$user = new AdminUser($_SESSION['admin']['id']);
	if($user->update($_REQUEST)){
		$smarty->assign("success",1);
		//in case email was updated this is needed
		$settings = new Settings();
		$smarty->assign_by_ref("settings",$settings);
	}else{
		$smarty->assign("error", $user->error);
	}
}

$smarty->display("account.tpl");
?>