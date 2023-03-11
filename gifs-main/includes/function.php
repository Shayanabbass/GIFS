<?php
function price($amount)
{
	if($amount)
	{
		if($amount<0)
		{
			$bracket_start = '(';
			$bracket_end = ')';
		}
		$amount = number_format(abs($amount), 2);
		return $bracket_start.' '.$amount.''.$bracket_end;
	}
	//return "0";
}
function humanTiming ($time)
{

    $time = time() - $time; // to get the time since that moment
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }

}
function change_from_price($amount)
{
	$amount = str_replace(',', '', $amount);
	if(strpos($amount, '(') === 0 )
	{
		$amount = str_replace(array('(', ')'), array('', ''), $amount);
		$amount *=-1;
	}
	return floatval($amount);
}
function compile_top_html($data_n, $template)
{
	$title = $data_n["html_title"];
	$heading = $data_n["html_heading"];
	$heading2 = $data_n["html_heading2"];
	//debug_r($data_n);
	if($title == "")
	{
		$title = page_name();
		if($_GET["action"] == "new")
			$title = "New ".$title;
	}

	if($heading == "")
		$heading = $title;
	//-----------------------------LOAD ALL CONTENT

	$data = file_get_contents($template);
	$data = load_content($data);
	//-----------------------------menu
	//echo((page_name()=="index")?" current":page_name());
	#-------------------#
	$data = str_replace('src="images/', 'src="'.TEMPLATE_DIR.'images/', $data);
	$data = str_replace('src="dist/', 'src="'.TEMPLATE_DIR.'dist/', $data);
	$data = str_replace('src="plugins/', 'src="'.TEMPLATE_DIR.'plugins/', $data);
	$data = str_replace('src="js/', 'src="'.TEMPLATE_DIR.'js/', $data);
	$data = str_replace('src="jquery/', 'src="'.TEMPLATE_DIR.'jquery/', $data);
	$data = str_replace('src="bootstrap/', 'src="'.TEMPLATE_DIR.'bootstrap/', $data);

	$data = str_replace('src="fullcalendar/', 'src="'.TEMPLATE_DIR.'fullcalendar/', $data);
	$data = str_replace('href="', 'href="'.TEMPLATE_DIR.'', $data);
	$data = str_replace('pref="', 'href="', $data);
	#-------------------#

	//
	$u = new users();
	if($_GET['page_type'] == 'minimal' && ($_GET['token'] != '1231231012980912asdsa' || $_GET['token'] != 'da81890afc76ab7ee6'))
	{
		$heading = '';
	}
	elseif(!is_login())
	{
		$data_temp = explode("{__LOGIN__}", $data);
		echo $data_temp[0];
		include(_level0.'trafficbuilder/login.php');
		$data = $data_temp[1];
		// debug_r($data_temp);
	}
	else
	{
		if(is_guest())
		{
			/*debug($_SESSION);
			debug_r($data);*/
			$data = str_replace("{__USERS_NAME__}", $_SESSION['guest_name'], $data);
			$data = str_replace("{__FULL_NAME__}", $_SESSION['guest_name'], $data);
		}
		else
		{
			$data = str_replace("{__USERS_NAME__}", $_SESSION['users_name'], $data);
			$data = str_replace("{__USERS_FULL_NAME__}", $_SESSION['users_full_name'], $data);
			$data = str_replace("{__FULL_NAME__}", $_SESSION['users_full_name'], $data);
		}
		$data = str_replace("{__PROFILE_IMAGE__}", $_SESSION['profile_image'], $data);
		$data = str_replace("{__MEMBER_SINCE__}", $_SESSION['member_since'], $data);
		//$data = str_replace("{__HEADING__}", $heading, $data);
	}
	//-----------------------------END OF LOAD ALL CONTENT

	//-----------------------------HEADING
	$data = str_replace("{__HEADING__}", $heading, $data);
	$data = str_replace("{__HEADING2__}", $heading2, $data);
	$data = str_replace("<a pref=\"", "<a href=\"", $data);

	#-------------------#
	$data = add_analytics($data);
	#---------Menu----------#
	$data = str_replace('{__menu__}', $u->permissions(), $data);
	#---------Profile----------#
	$group = get_group();
	//group_name.php
	//if group as page name don't exist
	$page = page_explode();
	$wwwroot = "http://".$_SERVER['HTTP_HOST'].'/'.$page[1].'/';
	if(is_array($group))
	{
		$file = $wwwroot.$group["group_name"].".php";
		$file_headers = @get_headers($file);
	}
	if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
		$exists = false;
		$file = $wwwroot."users.php";
	}
	else {
		$exists = true;
	}
	$data = str_replace('{__profile__}', $file.'?action=edit&id='.$_SESSION["users_id"], $data);
	#---------set pagination----------#
	// debug_r($_SESSION["groups_id"]);
	if($_SESSION["groups_id"])
	{
		$sql = "select number_of_pages from pagination where users_id = ".$_SESSION["users_id"]." AND groups_id = ".$_SESSION["groups_id"];
		if(is_guest())
		{
			$sql = "select number_of_pages from pagination where users_id = ".$_SESSION["guest_id"]." AND groups_id = ".$_SESSION["groups_id"];
		}
		$number_of_pages = $u->result($sql, 1);
		if(!$number_of_pages)
			define("number_of_pages", 10);	 //default number of pages
		else
			define("number_of_pages", $number_of_pages["number_of_pages"]);
	}
	return $data;
}

function compile_top($data_n)
{
	//-----------------------------LOAD ALL CONTENT
	if($_GET['page_type'] == 'minimal' && ($_GET['token'] != '1231231012980912asdsa' || $_GET['token'] != 'da81890afc76ab7ee6'))
	{
		$template = TEMPLATE_PRINT;
	}
	elseif(!is_login() )
	{
		$template = TEMPLATE_LOGIN;
	}
	else
	{
		$template = TEMPLATE;
	}
	return compile_top_html($data_n, $template);
}
function compile_left($data, $html_text)
{
	if(strpos($html_text, '<p>&nbsp;</p>') == (strlen($html_text)-13))
	{
		$html_text = substr($html_text, 0, (strlen($html_text)-13));
	}

	//ADD Body
	$data_temp = explode("{__BODY__}", $data);

	//-----------------------------ADD BODY
	$temp = "";
	//-----------------------------CHECK IF FITER IS AVAILBLE TO THE BODY
	if(function_exists(page_name()) && 0)
		eval("\$temp = ".page_name()."(".htmlspecialchars_decode1($html_text).");");
	else
		eval("\$temp = '".remove_appos(htmlspecialchars_decode1($html_text))."';");

	//eval("\$temp = '".remove_appos(htmlspecialchars_decode1($html_text))."';");
	$data_temp[0] .= $temp;
	$data = $data_temp[0].$data_temp[1];
	//-----------------------------END OF ADD BODY


	//remove state
	/*
	$states = "select * from state order by state_name";
	$db = new db2();
	$result = $db->sql_query($states);
	$temp = '<select id="state" name="state">';
	foreach($result as $a)
	{
		$temp .= '<option value="'.$a["state_name"].'">'.$a["state_name"].'</option>';
	}
	$temp .= '</select>';
	*/

	$data = str_replace('{__State__}', $temp, $data);
	return $data;
}
function compile_left2($data)
{
	return $data;
}

function get_group()
{
	$groups_id = $_SESSION["groups_id"];
	$groups_id = get_tuple("groups", $groups_id, "groups_id");
	//debug_r($groups_id);
	return $groups_id["groups_name"];
}

function color($c)
{
	return '<span style="color:'.$c.'">';
}

function color_end()
{
	return '</span>';
}

function has_inherits($str)
{
	if(strpos($str, '_inherits_'))
		return true;
	return false;
}

function remove_inherits($str)
{
	if(has_inherits($str))
	{
		$temp = explode('_inherits_', $str);
		$str = $temp[0];
	}
	if(strpos($str, ' inherits '))
	{
		alert("pls reconsider line 97 functions.php");
		$temp = explode(' inherits ', $str);
		$str = $temp[0];
	}
	return ucwords(str_replace("_", " ", $str));
}
function get_inherits($str)
{
	if(has_inherits($str))
	{
		$temp = explode('_inherits_', $str);
		$str = $temp[1];
	}
	if(strpos($str, ' inherits '))
	{
		alert("pls reconsider line 97 functions.php");
		$temp = explode(' inherits ', $str);
		$str = $temp[1];
	}
	return $str;
	return ucwords(str_replace("_", " ", $str));
}
function get_inherits_parent($str)
{
	if(has_inherits($str))
	{
		$temp = explode('_inherits_', $str);
		$str = $temp[0];
	}
	return $str;
}
//to get the classname_id in case of inherits or not
function get_class_id($str)
{
	if(has_inherits($str))
	{
		return get_inherits_parent($str)."_id";
	}
	else
	{
		if($str == "class") return "id";
		//if has id then don't add id
		if(strpos($str, "_id") != (strlen($str)-3))
			return	$str."_id";
		return $str;
//		alert('str = '.$str.'\nClass = '.$class_id);
	}

	return $class_id;
}
function remove_appos($str)
{
	$str = str_replace("'", "&rsquo;", $str);
	return $str;
}
function is_login()
{
	if(isset($_SESSION["users_id"]) && ($_SESSION["users_id"] != "0"))
		return true;
	if(is_guest())
		return true;
}
function is_guest()
{
	if(isset($_SESSION["guest_id"]) && $_SESSION["guest_id"] != "0")
		return true;
}
function add_analytics($data)
{
	$code = "";
	$data = explode("</body>", $data);
	$data = $data[0].$code.'</body>'.$data[1];
	return $data;
}
function getRealIpAddr()
{
	$ipaddress = '';
	if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	else if(isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	else if(isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
	else if(isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
	else
			$ipaddress = 'UNKNOWN';
	if($ipaddress == "::1") $ipaddress = "195.133.220.198";
	return $ipaddress;
}
function str_to_arr($str = '')
{
	if($str == "")
		$str = page_url_complete();
	//str = vehicle.php?action=delete&id=1
	$result = array();

	$arr = explode("?", $str);
	$arr = $arr[count($arr)-1];

	$arr = explode("&", $arr);
	for($i=0; $i<count($arr); $i++)
	{
		$temp = explode("=", $arr[$i]);
		$key = $temp[0];
		$value = $temp[1];
		$result[$key] = $value;
	}
	return $result;
}
?>
