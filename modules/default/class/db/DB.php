<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

$active_group = 'default';
$active_record = TRUE;
$db_setup['dbprefix'] = '';
$db_setup['pconnect'] = TRUE;
$db_setup['db_debug'] = TRUE;
$db_setup['cache_on'] = FALSE;
$db_setup['cachedir'] = '';
$db_setup['char_set'] = 'utf8';
$db_setup['dbcollat'] = 'utf8_general_ci';
$db_setup['swap_pre'] = '';
$db_setup['autoinit'] = TRUE;
$db_setup['stricton'] = FALSE;

require_once(BASEPATH.'/class/db/DB_driver.php');

if( !isset($active_record) OR $active_record == TRUE){
	require_once(BASEPATH.'/class/db/DB_active_rec.php');
	if( ! class_exists('CI_DB')){
		eval('class CI_DB extends CI_DB_active_record { }');
	}
}else{
	if( ! class_exists('CI_DB')){
			eval('class CI_DB extends CI_DB_driver { }');
	}
}

require_once(BASEPATH.'/class/db/drivers/'.$db_setup['dbdriver'].'/'.$db_setup['dbdriver'].'_driver.php');

// Instantiate the DB adapter
$driver = 'CI_DB_'.$db_setup['dbdriver'].'_driver';
$db = new $driver($db_setup);
if($db->autoinit == TRUE){
	$db->initialize();
}
if (isset($db_setup['stricton']) && $db_setup['stricton'] == TRUE){
	$db->query('SET SESSION sql_mode="STRICT_ALL_TABLES"');
}