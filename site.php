<?
require_once('header.php');

if($_REQUEST['id']){
	$_SESSION['siteid'] = $_REQUEST['id'];
	$siteid = $_SESSION['siteid'];
}elseif(isset($_SESSION['siteid'])){
	$siteid = $_SESSION['siteid'];
}else{
	header("Location:index.php");
}

if($_REQUEST['action']=='addPage'){
	$site = new Site($siteid);
	if($id = $site->addPage($_REQUEST)){
		echo $id;
	}else{
		echo $site->error;
	}
	exit;
}

if($_REQUEST['action']=='updateSettings'){
	$site = new Site($siteid);
	if($id = $site->updateSettings($_REQUEST)){
		echo $id;
	}else{
		echo $site->error;
	}
	exit;
}

/*if($_REQUEST['getAdminPages']){
	$site = new Site($siteid);
	$site->getAdminPages();
	exit;
}*/

if($_REQUEST['action']=='loadModules'){
	$page = new Page($_REQUEST['pageid']);
	for($i=0;$i<sizeof($page->modules);$i++){
		$module = preg_replace('/(?<!\ )[A-Z]/', ' $0', $page->modules[$i]['module']);
		if($page->modules[$i]['title']){
			$name = ucfirst($module)." (".$page->modules[$i]['title'].")";
		}else{
			$name = ucfirst($module);
		}
		if(strlen($name)>30){
			$name = substr($name, 0, 19)."...".substr($name, strlen($name)-8, 8);
		}
		echo "<a href='javascript:' moduleid='".$page->modules[$i]['id']."' modulename=".$page->modules[$i]['module'].">".$name."</a>";
	}
	exit;
}

$smarty->assign("page","site");

$user = new AdminUser(1);
$smarty->assign_by_ref("user", $user);

$site = new Site($siteid);
$smarty->assign_by_ref("site", $site);

$smarty->display("site.tpl");
?>