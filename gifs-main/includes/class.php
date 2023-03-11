<?php
//include('.php');
include_once(_leveli.'database.php');
include_once(_leveli.'database2.php');
include(_leveli.'class_singleton.php');
include_once(_leveli.'class_users.php');
include_once(_leveli.'dynamic.php');
//include_once(_leveli.'classes.php');
#---------------------------------------------- load all class files
//echo _levelc;
$handle = opendir(_levelc);
$files = (glob(_levelc."*.php"));
foreach ($files as $file)
{
	// debug($file);
	include($file);
}
?>
