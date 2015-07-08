<?php
session_start();
session_destroy();
if(isset($_COOKIE["admin"])){
	setcookie("admin", "", time()-3600);
	setcookie("adminid", "", time()-3600);
	setcookie("adminemail", "", time()-3600);
}
header("Location: ../");
?>