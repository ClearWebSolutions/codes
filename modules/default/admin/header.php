<?php
require_once('../class/includes/include.php');
is_admin();
$smarty->assign_by_ref("settings", $settings);
?>