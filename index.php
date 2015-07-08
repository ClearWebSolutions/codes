<?
require_once('header.php');

if($_REQUEST['action']=='updateSettings'){
	$u = new AdminUser(1);
	$u->updateSettings($_REQUEST);
	exit;
}

if($_REQUEST['action']=='addnewsite'){
	$site = new Site();
	if($id = $site->add($_REQUEST)){
		echo $id;
	}else{
		echo $site->error;
	}
	exit;
}

if($_REQUEST['action']=='deletesite'){
	$site = new Site($_REQUEST['id']);
	$site->delete();
}

$user = new AdminUser(1);
$smarty->assign_by_ref("user", $user);

$q = $db->query("select id, sitename from sites order by sitename asc");
foreach ($q->result() as $row){
	$sites[] = array('id'=>$row->id, 'name'=>$row->sitename);
}
$smarty->assign_by_ref("sites", $sites);

$smarty->display("websites.tpl");
?>