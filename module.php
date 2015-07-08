<?
require_once('header.php');

if($_REQUEST['action']=='new'){
	if($_REQUEST['module']=='multilanguage'||$_REQUEST['module']=='content'||$_REQUEST['module']=='store'){
		if($_REQUEST['module']=='multilanguage'||$_REQUEST['module']=='store')	
			$q = $db->query("select m.id as id from pages as p, modules as m, sites as s where s.id=p.siteid and m.module='".$_REQUEST['module']."' and m.pageid=p.id and s.id='".$_SESSION['siteid']."'");
		if($_REQUEST['module']=='content')
			$q = $db->query("select m.id as id from pages as p, modules as m, sites as s where s.id=p.siteid and m.module='".$_REQUEST['module']."' and m.pageid=p.id and p.id='".$_REQUEST['pageid']."' and s.id='".$_SESSION['siteid']."'");
		if($q->num_rows()>0){
			$row = $q->next_row();
			eval("\$module = new Mod".ucfirst($_REQUEST['module'])."(\$row->id);");
			$module->installed = true;
			$smarty->assign_by_ref("module",$module);
		}
	}
	$site = new Site($_SESSION['siteid']);
	$smarty->assign_by_ref("site",$site);
	$smarty->display("modules/".$_REQUEST['module'].".tpl");
	exit;
}

if($_REQUEST['action']=='add'){
	eval("\$module = new Mod".ucfirst($_REQUEST['module'])."();");
	if($module->add($_REQUEST)){
		$module->success = true;
	}
	echo json_encode($module);
	exit;
}

if($_REQUEST['action']=='edit'){
	eval("\$module = new Mod".ucfirst($_REQUEST['module'])."(".$_REQUEST['moduleid'].");");
	if($module->update($_REQUEST)){
		$module->success = true;
	}
	echo json_encode($module);
	exit;
}

if($_REQUEST['action']=='loadModule'){
	$q = $db->query("select module from modules where id='".$_REQUEST['mid']."'");
	if($q->num_rows()>0){
		$row = $q->next_row();
		eval("\$module = new Mod".ucfirst($row->module)."(".$_REQUEST['mid'].");");
		$smarty->assign_by_ref("module",$module);
		$site = new Site($_SESSION['siteid']);
		$smarty->assign_by_ref("site",$site);
		$smarty->display("modules/".$row->module.".tpl");
	}else{
		echo "Module is missing in db!";
	}
	exit;
}

if($_REQUEST['action']=='getGalleryFrm'){
	for($i=$_REQUEST['from'];$i<=$_REQUEST['amt'];$i++){
		echo "<div class=\"separator\"></div><div class=\"gal\">";
		$smarty->assign("i",$i);
		$smarty->display("modules/gallery.frm.tpl");
		echo "</div>";
	}
}
?>