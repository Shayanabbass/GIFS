<?php
function can_delete()
{
	$group = get_tuple("groups", $_SESSION["groups_id"], "groups_id");
	$group_name = $group["groups_name"];
	if($group_name == "Owner" || $group_name == "Administrator")
	{
		return true;
	}
		return false;
}
function can_edit()
{
	return can_delete();
}
function can_view_Bank()
{
	$group = get_tuple("groups", $_SESSION["groups_id"], "groups_id");
	$group_name = $group["groups_name"];
	if($group_name == "Bank")
	{
		return true;
	}
	return false;
}
?>