<?php
require_once('header.php');

$smarty->assign("menu","categories");
$smarty->assign_by_ref("request",$_REQUEST);

//INIT
$category = new Category($_REQUEST['tbl']);
$smarty->assign_by_ref("category",$category);
$categories = $category->getAll(0);
$smarty->assign_by_ref("categories", $categories);

//ADD
if($_REQUEST['add']){
	$category->add($_REQUEST);
	echo json_encode($category);
	exit;
}

//ORDER
if($_REQUEST['action']=='saveOrder'){
	$category->updateOrder($_REQUEST['order']);
	exit;
}

//DELETE
if($_REQUEST['action']=='delete'){
	$category->delete($_REQUEST['cat_id']);
	exit;
}

//EDIT
if($_REQUEST['action']=='edit'){
	$category = new Category($_REQUEST['tbl'], $_REQUEST['cat_id']);
	$category->update($_REQUEST);
	echo json_encode($category);
	exit;
}

//GET ALL LANGUAGES TITLES
if($_REQUEST['action']=='getDetails'){
	$category = new Category($_REQUEST['tbl'], $_REQUEST['cat_id']);
	echo json_encode($category);
	exit;
}

//GET CHILDREN
if($_REQUEST['action']=='getChildren'){
	if(!$_REQUEST['cat_id']){echo json_encode(array());exit;}
	$categories = $category->getAll($_REQUEST['cat_id']);
	echo json_encode($categories);
	exit;
}

$smarty->display("categories.tpl");
?>