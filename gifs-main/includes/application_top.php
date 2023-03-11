<?php
include('application_top2.php');
//alert(!$u->check_permissions() == 1);
//echo NOT_LOGGED_IN;
if(!is_login())
	redirect(NOT_LOGGED_IN);
if($_SESSION["users_name"] != "admin" && !$u->check_permissions())
{
	alert("You don't have rights to the page");
	redirect(NOT_LOGGED_IN);
}
//check permission and redirect
?>