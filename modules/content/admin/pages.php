<?php
require_once('header.php');

$smarty->assign("menu","pages");
$smarty->assign_by_ref("request",$_REQUEST);

//INIT
$page = new Page($_REQUEST['id']);
$pages = $page->getAll($_REQUEST);
$smarty->assign_by_ref("page", $page);
$smarty->assign_by_ref("pages", $pages);
if($_REQUEST['search']&&sizeof($pages)==0){$smarty->assign("no_results",1);}

//ADD NEW
if($_REQUEST['action']=='add'&&!$_REQUEST['sbm']){
	$new_id  = $page->add($_REQUEST['parent_id']);//this will create the new page, and lock it with new flag
	if($new_id){
		$page = new Page($new_id);
		$smarty->assign_by_ref("page", $page);
	}else{
		$smarty->assign("error",$page->error);
	}
}

//ADD (form submit)
if($_REQUEST['action']=='add'&&$_REQUEST['sbm']==1){
	if($page->update($_REQUEST)){
		header("Location: pages.php?added=true");
		exit;
	}else{
		$smarty->assign("error",$page->error);
	}
}

//EDIT
if($_REQUEST['action']=='edit'&&$_REQUEST['sbm']==1){
	if($page->update($_REQUEST)){
		$page = new Page($page->id);
		$smarty->assign_by_ref("page",$page);
		$smarty->assign("success", "Updated successfully");
	}else{
		$smarty->assign("error", $page->error);
	}
}

//UPDATELOCKED
if($_REQUEST['action']=='updateLocked'&&$_REQUEST['id']){
	$page->updateLocked($_REQUEST);
	exit;
}

//UPDATE ORDR
if($_REQUEST['action']=='updateOrdr'&&$_REQUEST['id']){
	$page->updateOrdr($_REQUEST);
	exit;
}

//DELETE
if($_REQUEST['action']=='delete'){
	$page->delete();
	exit;
}

if($_REQUEST['action']=='add'||$_REQUEST['action']=='edit'||$_REQUEST['action']=='add'){
	$smarty->display("page_action.tpl");
	exit;
}
$smarty->display("pages.tpl");
?>