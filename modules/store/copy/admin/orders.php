<?php
require_once('header.php');

$smarty->assign("menu","orders");



$obj = new Order($_REQUEST['id']);
$smarty->assign_by_ref("obj", $obj);
$objects = $obj->getAll($_REQUEST);
$smarty->assign_by_ref("objects", $objects);
if(sizeof($objects)==0){$smarty->assign("no_results",1);}

//UPDATE STATUS
if($_REQUEST['action']=='updateStatus'){
	$obj->updateStatus($_REQUEST);
	$obj = new Order($_REQUEST['id']);
	$smarty->assign_by_ref("obj", $obj);
	$objects = $obj->getAll($_REQUEST);
	$smarty->assign_by_ref("objects", $objects);
}


$sc = new ShoppingCart();
$smarty->assign_by_ref("sc", $sc);

//ADD NEW
if($_REQUEST['action']=='add'&&!$_REQUEST['sbm']){
	$new_id  = $obj->add($_REQUEST);//this will create the new order, and lock it with new flag
	if($new_id){
		$obj = new Order($new_id);;
		$smarty->assign_by_ref("obj", $obj);
	}else{
		$smarty->assign("error",$obj->error);
	}
}

//ADD (form submit)
if($_REQUEST['action']=='add'&&$_REQUEST['sbm']==1){
	if($obj->update($_REQUEST)){
		header("Location: orders.php?added=true");
		exit;
	}else{
		$smarty->assign("error",$obj->error);
	}
}

//EDIT
if($_REQUEST['action']=='edit'&&$_REQUEST['sbm']==1){
	if($obj->update($_REQUEST)){
		$obj = new Order($obj->id);
		$smarty->assign_by_ref("obj",$obj);
		$smarty->assign("success", "Updated successfully");
	}else{
		$smarty->assign("error", $obj->error);
	}
}

//DELETE
if($_REQUEST['action']=='delete'){
	$obj->delete();
	exit;
}

if($_REQUEST['action']=='add'||$_REQUEST['action']=='edit'||$_REQUEST['action']=='add'){
	$smarty->display("orders_action.tpl");
	exit;
}


$smarty->assign_by_ref("request",$_REQUEST);

$smarty->display("orders.tpl");
?>