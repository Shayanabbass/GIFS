<?php 
include('includes/application_top2.php');
$_SESSION["action"] = $_GET["action"];
$_SESSION["users_name_temp"] = $_REQUEST["users_name"];
$_SESSION["users_password_temp"] = $_REQUEST["users_password"];
//debug($_SESSION)
redirect($_GET["url"]);
?>