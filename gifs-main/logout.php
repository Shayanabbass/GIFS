<?php
include("includes/application_top2.php");
if($_SESSION['login_source'] == 'google')
{
	require_once 'library_google/Google_Client.php';
	require_once 'library_google/contrib/Google_Oauth2Service.php';
	$client = new Google_Client();
	$client->revokeToken();
	$token = $_SESSION['token'];
	$link = "https://accounts.google.com/o/oauth2/revoke?token=".$token;
	file_get_contents($link);
}
elseif($_SESSION['login_source'] == 'facebook')
{
	try
	{
		// $fb = new \Facebook\Facebook([
		// 	'app_id' => FB_APP_ID,
		// 	'app_secret' => FB_APP_SECRET,
		// 	'default_graph_version' => 'v2.10',
		// 	'default_access_token' => $_SESSION['fb_access_token'], // optional
		// ]);
		// $response = $fb->delete('/'.$_SESSION['facebook_user_id'].'/permissions');
	}
	catch(Exception $e)
	{
		
	}
}
// 	$url = 'https://www.facebook.com/logout.php?next=' . YOUR_SITE_URL .
// 	'&access_token='.$_SESSION['fb_access_token'];
// 	debug($url);
// try
// {
// //testing logged in Facebook
// $fb = new \Facebook\Facebook([
// 	'app_id' => FB_APP_ID,
// 	'app_secret' => FB_APP_SECRET,
// 	'default_graph_version' => 'v2.10',
// 	'default_access_token' => $_SESSION['fb_access_token'], // optional
// ]);
// $response = $fb->get('/me?fields=id,name');
// $user = $response->getGraphUser();
// echo 'Name: ' . $user['name'];
// }
// catch(Exception $ex)
// {
//   echo 'Message: ' .$ex->getMessage();
// }
// debug_r($_SESSION);
//$u = new users();
unset($_SESSION['login_source']);
unset($_SESSION['token']);
$_SESSION["permission_dynamic_cmm"] = "no";
$_SESSION["users_name"] = "no";
$_SESSION["users_id"] = "";
$_SESSION["groups_id"] = "";
session_destroy();
//debug_r(FILENAME_DEFAULT);
header("Location: ".FILENAME_DEFAULT);
?>