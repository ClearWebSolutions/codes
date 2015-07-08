<?php
require_once('header.php');

$stock = new Stock();

if($_REQUEST['action']=='update'){
	if($id=$stock->update($_REQUEST)){
		$json['success'] = '1';
		$json['stockid'] = $id;
	}else{
		$json['error'] = '1';
		$json['errorMsg'] = $stock->error;
	}
	echo json_encode($json);
	exit;
}

?>