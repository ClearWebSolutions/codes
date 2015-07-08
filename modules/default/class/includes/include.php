<?php

//absolute path on server no ending slash!
define('BASEPATH','');

//site URL no ending slash!
define('URL','');

//default admin page after login
define('ADMIN_START_PAGE', 'account.php');

//database config
define('DBPREFIX', '');
$db_setup['hostname'] = '';
$db_setup['username'] = '';
$db_setup['password'] = '';
$db_setup['database'] = '';
$db_setup['dbdriver'] = 'mysql';

//starting the session on each requested page
session_start();

//including database instance after which you can work with database via the $db object
require_once(BASEPATH.'/class/db/DB.php');

//including all the classes
require_once(BASEPATH.'/class/includes/classes.php');

//including smarty template engine
require_once(BASEPATH.'/class/smarty/Smarty.class.php');

//initializing template engine
$smarty = new Smarty();
$smarty->compile_check = true;
$smarty->debugging = false;

//////////////////////////////////////////////////////////////////////////////////////////////////
//global functions for user registration check
function is_admin(){
	global $_SESSION, $smarty;
	if(isset($_SESSION["admin"])){
		$smarty->assign_by_ref("admin",$_SESSION['admin']);
		return true;
	}else{
		header("Location:".URL);
	}
}

//////////////////////////////////////////////////////////////////////////////////////////////////

function is_email($email){
	// Test for the minimum length the email can be
	if ( strlen( $email ) < 3 ) return false;

	// Test for an @ character after the first position
	if ( strpos( $email, '@', 1 ) === false ) return false;

	// Split out the local and domain parts
	list( $local, $domain ) = explode( '@', $email, 2 );

	// LOCAL PART
	// Test for invalid characters
	if ( !preg_match( '/^[a-zA-Z0-9!#$%&\'*+\/=?^_`{|}~\.-]+$/', $local ) ) return false;

	// DOMAIN PART
	// Test for sequences of periods
	if ( preg_match( '/\.{2,}/', $domain ) ) return false;

	// Test for leading and trailing periods and whitespace
	if ( trim( $domain, " \t\n\r\0\x0B." ) !== $domain ) return false;

	// Split the domain into subs
	$subs = explode( '.', $domain );

	// Assume the domain will have at least two subs
	if ( 2 > count( $subs ) ) return false;

	// Loop through each sub
	foreach ( $subs as $sub ) {
		// Test for leading and trailing hyphens and whitespace
		if ( trim( $sub, " \t\n\r\0\x0B-" ) !== $sub ) return false;

		// Test for invalid characters
		if ( !preg_match('/^[a-z0-9-]+$/i', $sub ) ) return false;
	}

	// Congratulations your email made it!
	return true;
}

//////////////////////////////////////////////////////////////////////////////////////////////////
//recursive rmdir
function rrmdir($dir) {
	if (is_dir($dir)) {
		$objects = scandir($dir);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
				if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
			}
		}
		reset($objects);
		rmdir($dir);
	}
}

//////////////////////////////////////////////////////////////////////////////////////////////////
//fix quotes for inputs or attributes
function fix_quotes($str){
	return htmlentities(stripslashes($str), ENT_QUOTES, 'UTF-8');
}

//////////////////////////////////////////////////////////////////////////////////////////////////
function pagination($page, $ttl, $rpp, $classname=''){

	$a = explode($_SERVER['HTTP_HOST'], URL);
	if(strstr($_SERVER['REQUEST_URI'], $a[1].'/admin/')){
		$admin=true;
	}else{
		$admin=false;
	}

	if($classname&&$admin){
		$classname="classname=".$classname."&";
	}else{
		$classname="";
	}
	if(($ttl%$rpp)==0){
		$total_pages = $ttl/$rpp;
	}else{
		$total_pages = $ttl/$rpp + 1;
	}
	$total_pages = (int)$total_pages;
	$pagination = "";
	if($total_pages>1){
		$prev = $page-1;
		if($page!=1){ $pagination = "<div class=\"prev\"><a href=\"?".$classname."page=".$prev."\">&larr; Prev</a></div>"; }else{ $pagination = "<div class=\"prev\">&nbsp;</div>"; }
		$next = $page+1;
		if($page!=$total_pages){ $pagination .= "<div class=\"next\"><a href=\"?".$classname."page=".$next."\">Next &rarr;</a></div>";}else{ $pagination .= "<div class=\"next\">&nbsp;</div>"; }
		$pagination .= "<div class=\"pagination\">";

		if($page==1){$pagination .= "<a href=\"?page=1\" class=\"selected\">1</a>";}else{$pagination .= "<a href=\"?".$classname."page=1\">1</a>";}
		if(($page-4)>1) $left = $page-4; else $left = 2;
		if(($page+4)<$total_pages)$right=$page+5; else $right=$total_pages;
		for($i=$left;$i<$right;$i++){
			if($left!=2&&$i==$left){$pagination.="...";}
			if($i==$page){
				$pagination .= "<a href=\"?".$classname."page=".$i."\" class=\"selected\">".$i."</a>";
			}else{
				$pagination .= "<a href=\"?".$classname."page=".$i."\">".$i."</a>";
			}
			if($i==$right-1&&$right!=$total_pages){$pagination.="...";}
		}
		
		if($page==$total_pages){$pagination .=  "<a href=\"?".$classname."page=".$total_pages."\" class=\"selected\">".$total_pages."</a>";}else{$pagination .=  "<a href=\"?".$classname."page=".$total_pages."\">".$total_pages."</a>";}
		
		$pagination .= "</div>";
	}else{
		$pagination = "&nbsp;";
	}
	return $pagination;
}
?>