<?php
include("../includes/application_top2.php");
$u = new users();
$_SESSION["permission_dynamic_cmm"] = "no";
$_SESSION["users_name"] = "no";
$_SESSION["users_id"] = "";
$_SESSION["groups_id"] = "";
session_destroy();
//debug_r(FILENAME_DEFAULT);
header("Location: ".FILENAME_DEFAULT);
?>