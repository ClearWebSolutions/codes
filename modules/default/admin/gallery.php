<?php
require_once('header.php');

$smarty->assign("menu","gallery");

if($_REQUEST['action']=='deleteImage'){
	$gid = $_REQUEST['gid'];
	$g2o = $_REQUEST['g2o'];
	$gallery = new Gallery($gid,$g2o);
	$list = $_REQUEST['list'];
	$gallery->deleteImgs($list);
	$gallery = new Gallery($gid,$g2o);
	$smarty->assign_by_ref("gallery",$gallery);
	$smarty->display("gallery_thumbs.tpl");
	exit;
}
if($_REQUEST['action']=='saveOrder'){
	$gid = $_REQUEST['gid'];
	$gallery = new Gallery($gid);
	$gallery->updateOrder($_REQUEST['order']);
	exit;
}
if($_REQUEST['action']=='editImage'){
	$gid = $_REQUEST['gid'];
	$g2o = $_REQUEST['g2o'];
	$gallery = new Gallery($gid,$g2o);
	$gallery->updateImg($_REQUEST);
	$gallery = new Gallery($gid,$g2o);
	$smarty->assign_by_ref("gallery", $gallery);
	for($i=0;$i<sizeof($gallery->imgs);$i++){
		if($gallery->imgs[$i]['id']==$_REQUEST['imgid']) break;
	}
	$smarty->assign("imgs",$i);
	$smarty->display("gallery_imagedata.tpl");
	exit;
}

if($_REQUEST['action']=='addGallery'){
	$gallery = new Gallery($_REQUEST['gid'], $_REQUEST['g2o']);
	if($gallery->addNew()){//now the gallery is new not the one initialized above
		$smarty->assign_by_ref("gallery", $gallery);
		$smarty->assign("oe",$_REQUEST['oe']);//odd/even decoration
		$smarty->display("gallery_inc.tpl");
	}else{
		echo "error";
	}
	exit;
}

if($_REQUEST['id']){
	$gallery = new Gallery($_REQUEST['id']);
	$smarty->assign_by_ref("gallery", $gallery);
}else{
	header("Location: ".ADMIN_START_PAGE);
}

$smarty->display("gallery.tpl");
?>