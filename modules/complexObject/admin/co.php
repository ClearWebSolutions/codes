<?php
require_once('header.php');

$smarty->assign("menu",$_REQUEST['classname']);
$smarty->assign_by_ref("request",$_REQUEST);

//INIT
eval("\$obj = new ".$_REQUEST['classname']."(\$_REQUEST['id']);");
$objects = $obj->getAll($_REQUEST);
$smarty->assign_by_ref("obj", $obj);
$smarty->assign_by_ref("objects", $objects);
if($_REQUEST['searchsbm']&&sizeof($objects)==0){$smarty->assign("no_results",1);}

//ADD NEW
if($_REQUEST['action']=='add'&&!$_REQUEST['sbm']){
	$new_id  = $obj->add($_REQUEST);//this will create the new object, and lock it with new flag
	if($new_id){
		eval("\$obj = new ".$_REQUEST['classname']."(\$new_id);");
		$smarty->assign_by_ref("obj", $obj);
	}else{
		$smarty->assign("error",$obj->error);
	}
}

//ADD (form submit)
if($_REQUEST['action']=='add'&&$_REQUEST['sbm']==1){
	if($obj->update($_REQUEST)){
		header("Location: co.php?classname=".$_REQUEST['classname']."&added=true");
		exit;
	}else{
		$smarty->assign("error",$obj->error);
	}
}

//EDIT
if($_REQUEST['action']=='edit'&&$_REQUEST['sbm']==1){
	if($obj->update($_REQUEST)){
		eval("\$obj = new ".$_REQUEST['classname']."(\$obj->id);");
		$smarty->assign_by_ref("obj",$obj);
		$smarty->assign("success", "Updated successfully");
	}else{
		$smarty->assign("error", $obj->error);
	}
}

//UPDATELOCKED
if($_REQUEST['action']=='updateLocked'&&$_REQUEST['id']){
	$obj->updateLocked($_REQUEST);
	exit;
}

//UPDATE ORDR
if($_REQUEST['action']=='updateOrdr'&&$_REQUEST['id']){
	$obj->updateOrdr($_REQUEST);
	exit;
}

//DELETE
if($_REQUEST['action']=='delete'){
	$obj->delete();
	exit;
}

if($_REQUEST['action']=='add'||$_REQUEST['action']=='edit'||$_REQUEST['action']=='add'){
	$smarty->display(strtolower($_REQUEST['classname'])."_action.tpl");
	exit;
}
$smarty->display(strtolower($_REQUEST['classname']).".tpl");
?>