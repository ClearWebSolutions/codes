<?php
require_once('header.php');

$smarty->assign("menu","languages");
$smarty->assign_by_ref("request",$_REQUEST);

//INIT
$language = new Language($_REQUEST);
$languages = $language->getAll($_REQUEST);
$smarty->assign("language", $language);
$smarty->assign("languages", $languages);
if($_REQUEST['search']&&sizeof($languages)==0){$smarty->assign("no_results",1);}

//ADD
if($_REQUEST['action']=='add'&&$_REQUEST['sbm']==1){
	if($language->add($_REQUEST)){
		header("Location: languages.php?added=true");
	}else{
		$smarty->assign("error",$language->error);
	}
}

//EDIT
if($_REQUEST['action']=='edit'&&$_REQUEST['sbm']==1){
	if($language->update($_REQUEST)){
		$smarty->assign("success", "Updated successfully");
	}else{
		$smarty->assign("error", $language->error);
	}
}

//UPDATELOCKED
if($_REQUEST['action']=='updateLocked'&&$_REQUEST['id']){
	$language->updateLocked($_REQUEST);
	exit;
}

//UPDATE ORDR
if($_REQUEST['action']=='updateOrdr'&&$_REQUEST['id']){
	$language->updateOrdr($_REQUEST);
	exit;
}


//DELETE
if($_REQUEST['action']=='delete'){
	$language->delete();
	exit;
}

if($_REQUEST['action']=='add'||$_REQUEST['action']=='edit'){$smarty->display("language_action.tpl");exit;}
$smarty->display("languages.tpl");
?>