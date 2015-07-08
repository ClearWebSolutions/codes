<?php
require_once('header.php');

$smarty->assign("menu","metadata");
if($_REQUEST['sbm']){
	$user = new AdminUser();
	if($user->update_meta($_REQUEST)){
		$smarty->assign("success",1);
		$settings = new Settings();
		$smarty->assign_by_ref("settings",$settings);
	}else{
		$smarty->assign("error", $user->error);
	}
}

$smarty->display("metadata.tpl");
?>