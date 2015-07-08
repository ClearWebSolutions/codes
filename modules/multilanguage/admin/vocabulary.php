<?php
require_once('header.php');

$smarty->assign("menu","vocabulary");

$vocabulary = new Vocabulary('en', $_REQUEST);
$smarty->assign("vocabulary", $vocabulary);
if($_REQUEST['search']&&sizeof($vocabulary->byLabel)==0){$smarty->assign("no_results",1);}
$smarty->assign_by_ref("request",$_REQUEST);

if($_REQUEST['action']=='add'&&$_REQUEST['sbm']==1){
	if($vocabulary->add($_REQUEST)){
		header("Location: vocabulary.php?added=true");
	}else{
		$smarty->assign("error",$vocabulary->error);
	}
}
if($_REQUEST['action']=='add'){
	$smarty->display("vocabulary_action.tpl");
	exit;
}
if($_REQUEST['action']=='edit'&&$_REQUEST['sbm']==1){
	if($vocabulary->update($_REQUEST)){
		$smarty->assign("success", "Updated successfully");
	}else{
		$smarty->assign("error", $vocabulary->error);
	}
}
if($_REQUEST['action']=='edit'){
	$smarty->display("vocabulary_action.tpl");
	exit;
}
if($_REQUEST['action']=='delete'){
	$vocabulary->delete();
	exit;
}


$smarty->display("vocabulary.tpl");
?>