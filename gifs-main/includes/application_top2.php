<?php
// ini_set('zlib.output_compression_level', 1);
// if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
// {
// 	header('Content-Encoding: gzip');
// 	ob_start('ob_gzhandler');
// }
header('Content-Encoding: gzip');
date_default_timezone_set('UTC');
if(1)
{
  error_reporting(E_ERROR);
}
else {
  error_reporting(E_ALL);
  ini_set('display_errors', TRUE);
  ini_set('display_startup_errors', TRUE);/**/
}
if (extension_loaded('zlib') && !ini_get('zlib.output_compression')){
    header('Content-Encoding: gzip');
    ob_start('ob_gzhandler');
}
ob_end_clean();
ob_start('ob_gzhandler');
//get the last-modified-date of this very file
$lastModified=filemtime(__FILE__);
//get a unique hash of this file (etag)
$etagFile = md5_file(__FILE__);
//get the HTTP_IF_MODIFIED_SINCE header if set
$ifModifiedSince=(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false);
//get the HTTP_IF_NONE_MATCH header if set (etag: unique file hash)
$etagHeader=(isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);

//set last-modified header
header("Last-Modified: ".gmdate("D, d M Y H:i:s", $lastModified)." GMT");
//set etag-header
header("Etag: $etagFile");
//make sure caching is turned on
header('Cache-Control: public');
session_start();
#--------------------------------------------------| for directory access |--------------------------------------------------
$file_path = $_SERVER['SCRIPT_FILENAME'];
if(strpos($file_path, '/') !== false)
{
	$file = explode("/", $file_path);
}
else
{
	$file = explode("\\", $file_path);
}
if($_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1')
{
  // print_r($file);die;
  foreach($file as $val)
	{
		$level0 .= $val.'/';
		// if($val == "beta" || $val == '||')break;
		if($val == "public_html" || $val == 'gifs')break;
	}
	// $level0 = $file[0].'/'.$file[1].'/'.$file[2].'/'.$file[3].'/';
}
else
{
	// define("BASE_URL", "https://thebaronhotels.com/");
	foreach($file as $val)
	{
		$level0 .= $val.'/';
		// if($val == "beta" || $val == '||')break;
		if($val == "public_html" || $val == 'gifs')break;
	}
	if(strpos($_SERVER["PHP_SELF"], "action.php") == 0)
	{
		//require_once $level0.'/Zend/Loader/Autoloader.php';
	}
}
// echo($level0);die;
$level1 = $level0.''.$file[4].'';
define("_level0", $level0);
include(_level0.'includes/function3.php');
include(_level0.'includes/function.php');
include(_level0.'includes/function2.php');
define("_leveli", $level0.'includes/');
define("_levelc", $level0.'includes/class/');
define("_levelw", $level0.'trafficbuilder/');
//relative level
define("_levelw_r", 'trafficbuilder/');
define("_level1", $level1);
#--------------------------------------------------|  |--------------------------------------------------
include(_level0.'includes/configure_pk.php');
include(_level0.'includes/file_names.php');
include(_level0.'includes/class.php');
include(_level0.'includes/design.php');
$u = new users("users");
if(isset($_SESSION["is_pakistan"]))
{
}
else
{
	$ip = getRealIpAddr();
	$sql = "SELECT * FROM `ips` WHERE '".$ip."' BETWEEN (`start`) AND (`end`)";
	$ip_found = $u->result($sql, 1);
	if(!$ip_found)
	{
		try
		{
			//API For getting the ip of person
			$access_key = '2165a024fffc64d3c9683188c684011f';

			// Initialize CURL:
			$ch = curl_init('http://api.ipapi.com/'.$ip.'?access_key='.$access_key.'');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			// Store the data:
			$json = curl_exec($ch);
			curl_close($ch);

			// Decode JSON response:
			$api_result = json_decode($json, true);
			$code = $api_result["country_code"];
			$lip = ip2long($ip);
			$u->sqlq("insert into ips (start, end, country_code) VALUES ('$lip', '$lip', '$code');");
			// Output the "calling_code" object inside "location"

			$ip_found['country_code'] = $api_result['country_code'];

		}
		catch (Exception $e)
		{
			//nothing
    	}
	}
	$country_code = $ip_found['country_code'];
	$_SESSION['ip_info'] = $ip_found;
	$_SESSION["is_pakistan"] = 0;
	if($country_code == "PK")
	{
		$_SESSION["is_pakistan"] = 1;
	}
	$_SESSION['phone_code'] = $country_code;
	$country = get_tuple("country2", $country_code, "iso");
	$_SESSION['phone_code'] = $country['phonecode'];
}

//debug_r($_SESSION["spiritual_guide"]);

if (!$_SESSION["source_name"]) {
	//forced else
	$source_name = 'Website';
	$source_id = 5;
	if(isset($_REQUEST["source"]) && $_REQUEST["source"])
	{
		$source = get_tuple("source", $_REQUEST["source"], "source_name");
		// debug_r($_REQUEST["source"]);
		if($source)
		{
			$source_id = $source["source_id"];
			$source_name = $source["source_name"];
		}
	}
	// echo $source_name;
	// debug_r($_SESSION["source_name"]);
    $_SESSION["source_id"] = $source_id;
	$_SESSION["source_name"] = $source_name;
}
set_session_for("users", "users_id");
//set_session_for("lead_status");
// set_session_for("facebook_replies");
set_session_for("country", "country_id");
set_session_for("guest", "guest_id");
// set_session_for("rate_plan", "id", "status = 1");
set_session_for("banner", "banner_id");
set_session_for("source", "source_id");
// debug_r($_REQUEST);
set_session_for("section", "id", "status = 1");
set_session_for("subject", "id", "status = 1");
