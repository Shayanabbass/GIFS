<?php
// debug_r($_SESSION);
function strToHex($string){
    $hex = '';
    for ($i=0; $i<strlen($string); $i++){
        $ord = ord($string[$i]);
        $hexCode = dechex($ord);
		$temp = substr('0'.$hexCode, -2);
		$hex .= ('\\'.six($temp, 4));
    }
    return strToUpper($hex);
}
function date_gen($id = '', $date, $multi = '')
{
	$daydropdown_ = adodb_date("d", $date);
	$monthdropdown_ = adodb_date("m", $date);
	$yeardropdown_ = adodb_date("Y", $date);
	$temp = '
	<select id="monthdropdown_'.$id.'" name="monthdropdown_'.$id.$multi.'">';
	for($i=1;$i<=12;$i++)
	{
        $mon = adodb_date("F", adodb_mktime(0, 0, 0, $i+1, 0, 0, 0));
		//debug(adodb_mktime(0, 0, 0, $i+1, 0, 0, 0));
		$selected = '';
		if($i == $monthdropdown_)
			$selected = ' selected="selected"';
		$temp .= '<option value="'.($i).'" '.$selected.'>'.$mon.'</option>';
	}
	$temp .= '</select>
	<select id="daydropdown_'.$id.'" name="daydropdown_'.$id.$multi.'">';
	for($i=1;$i<=31;$i++)
	{
		$selected = '';
		if($i == $daydropdown_)
			$selected = ' selected="selected"';
		$temp .= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
	}
	$temp .= '</select>
	<select id="yeardropdown_'.$id.'" name="yeardropdown_'.$id.$multi.'">';
	for($i=2010;$i<2110;$i++)
	{
		$selected = '';
		if($i == $yeardropdown_)
			$selected = ' selected="selected"';
		$temp .= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
	}
	$temp .= '</select>';
	return $temp;
}
function get_final_test_marks($userid, $courseid, $sectionid)
{
	global $CFG;
	$sql = "SELECT
				sst.*, cm.section
			FROM
				{$CFG->prefix}course_modules cm,
				{$CFG->prefix}scorm_scoes_track sst,
				{$CFG->prefix}scorm s
			WHERE
				cm.module = 14
				AND cm.instance = s.id
				AND cm.instance = sst.scormid
				AND s.id = cm.instance
				AND s.id = sst.scormid
				AND s.scorm_type =  'Final Test'
				AND cm.section = $sectionid
				AND
				(
					sst.element = 'cmi.score.max'
					OR sst.element = 'cmi.score.raw'
				)
				AND userid = $userid
				AND cm.course =  '".$courseid."'
				";
	//echo "\n".($sql)."\n";
	//debug_r($sql);
		//debug_r($data);
	$data = get_recordset_sql($sql);
	return $data;
}
function six($max, $total = 4)
{
	for($i=strlen($max); $i<$total;$i++)
	{
		$max = "0".$max;
	}
	return $max;
}
function alert($s)
{
	$s = str_replace("'","\'", $s);
	echo "<script>alert('".$s."')</script>";
}
function alert2($s)
{
	echo "<script>alert(".$s.")</script>";
}
function upload2($product_image, $targetpath, $image)
{
		if($product_image['name'] != '')
		{
			upload($product_image, $targetpath);
			return;
		}
		else
		{
			return $image;
		}
}
function upload($file_name, $targetpath)
{
	if(!file_exists($targetpath))
	{
		//"/path/to/my/dir", 0700);
		mkdir(str_replace("\\", "/", getcwd().'/'.$targetpath), 0644);
	}

	$T = '';
	if($file_name['name'] != '')
	{
		$rnd = returnPassword();
		$targetpath = $targetpath.'/'.adodb_date("ymd").$rnd.basename($file_name['name']);
		$sql1 = ""; //Image 1
		if(move_uploaded_file($file_name['tmp_name'], $targetpath))
		{
			$T = $targetpath;
		}
	}
	return $T;
}
function redirect($str)
{
	echo
	'
		<script>
		location.href=\''.$str.'\'
		</script>
	';
	die;
}
function back()
{
	echo
	'
		<script>
		history.back();
		</script>
	';
}
function respond($string)
{
	return htmlspecialchars_decode1($string);
}
function htmlspecialchars_decode1($string,$style=ENT_COMPAT)
{
        $translation = array_flip(get_html_translation_table(HTML_SPECIALCHARS,$style));
        if($style === ENT_QUOTES){ $translation['&#039;'] = '\''; }
        return strtr($string,$translation);
}
function returnPassword ($length = 3)
{
  // start with a blank password
  $password = "";
  // define possible characters
  $possible = "0123456789bcdfghjkmnpqrstvwxyz";

  // set up a counter
  $i = 0;

  // add random characters to $password until $length is reached
  while ($i < $length) {
    // pick a random character from the possible ones
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);

    // we don't want this character if it's already in the password
    if (!strstr($password, $char)) {
      $password .= $char;
      $i++;
    }
  }
  // done!
  return ($password);
}
function generatePassword ($length = 8)
{
  // start with a blank password
  $password = returnPassword($length);
  return md5($password).":".$password;
}
  function tep_not_null($value) {
    if (is_array($value)) {
      if (sizeof($value) > 0) {
        return true;
      } else {
        return false;
      }
    } else {
      if (($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0)) {
        return true;
      } else {
        return false;
      }
    }
	return false;
  }
# Filter the input
function request($txt)
{
	$txt = $_REQUEST[$txt];
	return str_replace(array("'"), array("''"), $txt);
}
function page_explode()
{
	if(strpos($_SERVER['REQUEST_URI'], 'facebook_login.php'))
		return array('facebook_login.php');
	$temp = explode("/", $_SERVER['REQUEST_URI']);
	return $temp;
}
function page_url()
{
	$temp = page_explode();
	$temp3 = ($temp[count($temp)-1]);
	$temp2 = explode("?", $temp3);
	if($temp2[0] == "")
		$temp2[0] = FILENAME_DEFAULT;
//	debug_r($temp2[0]);
	return $temp2[0];
}
function page_link($page = '', $action = 'google_login', $keyword = 'action')
{
	$page = str_replace(".php", "", $page);
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$temp = explode("/", $_SERVER['PHP_SELF']);
	$temp3 = ($temp[1]);
	$host = $_SERVER['HTTP_HOST'].'/'.$temp3;
	$final_link = $protocol.$host.($page?'/'.$page.'.php':'').($action?'?'.$keyword.'='. $action:'');
	//debug_r($final_link);
	return($final_link);
	die( $_SERVER['PHP_SELF']);;
}
function page_url_complete()
{
	$temp = explode("/", $_SERVER['REQUEST_URI']);
	$temp3 = ($temp[count($temp)-1]);
	return($temp3);
}
function page_name($page_name = '')
{
	if($page_name == "")
	{
		if(isset($_GET['page_name']) && $_GET['page_name']) {
			return $_GET['page_name'];
		}
		$temp4 = explode(".", page_url());
	}
	else
	{
		$temp4 = explode(".", $page_name);
	}
	$temp3 = str_replace('-', '_', $temp4[0]);
	return $temp3;
}
function page_comment()
{
	$db = new db2();
	//remove multiple &
	$page_url_complete = page_url_complete();
	$page_url_complete_array = explode("&", $page_url_complete);
	if(count($page_url_complete_array) > 1)
	{
//		alert($page_url_complete);
		$page_url_array = explode("?", $page_url_complete);
		$page_url_complete = $page_url_array[0];
	}
	$sql = "select forms_comments from forms where forms_name = '".$page_url_complete."'";
	//echo $sql; exit();
	$result = $db->result($sql, true);
	return $result["forms_comments"];
}
function set_error($message)
{
	$_SESSION["error"] = "true";
	$_SESSION["error_message"] = $message;
}
function reset_error()
{
	$_SESSION["error"] = "false";
	$_SESSION["error_message"] = "";
}
function field_name($result, $index)
{
	debug($index);
	debug_r($result);
		return field_to_page(mysql_field_name($result, $index));
}
function field_to_page($str)
{
	$str = str_replace("_", " ", $str);
	return ucwords($str);
}
/*
echo '<script type="text/javascript">
<!--
function check()
{ //v4.0
	if (document.getElementById)
	{';
?>
		var error = 'The following errors have occured:';
		document.mmval = true;
		//_______________________________________________Validate Promotion Name
		if(document.getElementById("promotions_name").value == "")
		{
			error = error + "\nPromotion Name cannot be empty.";
			document.mmval = false;
		}

		if(document.mmval == false)
		{
			alert(error);
			error = '';
		}
	}
}
-->
</script>
  */
function replace_with($str, $rep, $with)
{
  $str = str_replace("<".$rep.">", $with, $str);
  $str = str_replace("</".$rep.">", $with, $str);
  return $str;
}
function replace($str, $rep)
{
	return replace_with($str, $rep, "");
}
function text($name)
{
	$db = new db2();
	$a = $db->result("select * from html where html_name = '".$_GET["id"]."'");
	eval("\$r = \$a[\"".$name."\"];");
	return $r;
}
function dropdown_country($country_id = '', $type = 1)
{
    $db = new db2();
    $output = '
    <select id="country_id" name="country_id" data-required="" '.$select_details.'>
                                <option value="">Please Select</option>';
    $countries = $db->result("select * from country");
    foreach ($countries as $country) {
		$selected = '';
		$value = $country['country_id'];
		if($type == 2) $value = $country['code'];
        if ($value == $country_id) $selected = ' selected="selected"';
        $output .= '<option value="' . $value . '"' . $selected . '>' . $country['country_name'] . '</option>';
    }
    $output .= '</select>';
    return $output;
}
function dropdown_lead_status($initial_value)
{
    $db = new db2();
    $output = '
    <select id="lead_status_id" name="lead_status_id" data-required="" '.$select_details.' class="form-control">
		<option value="">Please Select</option>';
    $lead_statuses = $db->result("select * from lead_status");
    foreach ($lead_statuses as $lead_status) {
		$selected = '';
		$lead_status_id = $lead_status['id'];
        if ($lead_status_id == $initial_value) $selected = ' selected="selected"';
        $output .= '<option value="' . $lead_status_id . '"' . $selected . '>' . $lead_status['name']. ($lead_status['description'] ? '(' . $lead_status['description'] . ')' : '' ). '</option>';
    }
    $output .= '</select>';
    return $output;

}
function dropdown_users($field_name = 'users_id', $users_id = 0, $id='', $filter = '', $options = '', $class = ' class="form-control select2 validate[required]"')
{
    $db = new db2();
    $sql = "select users_id, users_full_name, designation from users $filter";
    $result = $db->result($sql);
    if($result)
    {
        //echo '<label><select name="'.$key1[0].'_id" id="'.$key1[0].'_id">';
        $s = '<select name="'.$field_name.'" id="'.$field_name.'"'.($is_required ? $is_required : ' class="form-control select2"').'>
				<option value="">Please Select</option>';
        foreach($result as $a)
        {
			$selected = '';
			if($a['users_id'] == $users_id) $selected = ' selected="selected"';
            $s .= '<option value="'.$a["users_id"].'"'.$selected.'>'.$a["users_full_name"].'('.$a['designation'].')</option>';
        }
        $s .= '</select>';
    }
    return $s;
}
function dropdown($key, $id='', $filter = '', $select_options = '', $is_required = ' class="form-control select2 validate[required]"')
{
	//alert($filter);
	//$key1 = explode("_", $key);
	//default id for the dropdown = selected
	$init_id = $id;
	$_name = $table_name = str_replace("_id", "", $key);
	//in case of multiselect dropdown
	$table_name = str_replace("[]", "", $table_name);
	$_name = str_replace("$table_name", "{$table_name}_id", $_name);

	$key = $table_name."_id";
	//$key2 = str_replace("_id", "", $key);
	$key3 = $table_name."_name";
	///+++++++++++++++++++++++++++++++++++++++++++++
	$sql = "select * from ".$table_name.$filter;
	// debug_r($sql);
	$db = new db2();
	$result = $db->sql_query($sql);
	//$f = $result;
	/*for($i = 0; $i < mysql_num_fields($result); $i++)
	{
	*/
	$total_column = $result->columnCount();
	//var_dump($total_column);
	$first_column_name = '';
	for ($counter = 0; $counter < $total_column; $counter ++) {
		$meta = $result->getColumnMeta($counter);
		$first_column_name = $meta['name'];
		//$first_column_name = mysql_field_name($result, $i);
		$field_name = explode("_", $first_column_name);
		if($field_name[count($field_name)-1] == "name")
		{
			break;
		}
	}

	if($first_column_name == "forms_name" && page_name() == "forms"){$first_column_name = "forms_comments";}

	if($key == "section_id" && $first_column_name == "name"){$first_column_name = "full_name";}
	if($key != 'orders_id' && if_users_id($table_name) && !is_admin())
	{
		$key3 = ' Where users_id = '.$_SESSION["users_id"].' ';
		//	alert(if_users_id($table_name));
	}
	else
	{
		$key3 = '';
	}
	if($key == "company_id")
	{
		$user = get_tuple("users", $_SESSION["users_id"]);
		$company_id = $user["company_id"];
		$groups_id = $user["groups_id"];
		if($groups_id == 3 && $company_id)
		{
			$key3 = " WHERE company_id = $company_id";
		}
	}
	if($key == "credit")
	{
		$key3 .= ' ORDER BY account_code';
	}
	else
	{
		$key3 .= ' ORDER BY '.$first_column_name;
	}
	$sql = "select * from ".$table_name.$filter.$key3;
	// debug_r($sql);
	$result = $db->result($sql);
	if(count($result)>0)
	{
		//echo '<label><select name="'.$key1[0].'_id" id="'.$key1[0].'_id">';
		$s = '<select'.$select_options.' name="'.$_name.'" id="'.$key.'"'.($is_required ? $is_required : ' class="form-control select2"').'>';
	}
	$s .= '<option value="">Please Select</option>';
	foreach($result as $a)
	{
		eval("\$id = \$a[\"".$key."\"];");
		if(!$id) eval("\$id = \$a[\"id\"];");
		eval("\$name = \$a[\"".$first_column_name."\"];");
		if($key == "account_heads_id 00")
			$name = ($a["account_code"]).' '.$name;
		if($init_id == $id)
		{
			$s .= '<option value="'.$id.'" selected="selected">'.$name.'</option>';
		}
		else
			$s .= '<option value="'.$id.'">'.$name.'</option>';
	}
	if(count($result)>0)
		$s .= '</select>';
	if($s == "")
	{
		$s = 'No Data Currently Available';
	}
	return $s;
}
function dropdown3($key, $id='', $filter = '', $options = '', $is_required = ' class="form-control select2 validate[required]"')
{
	//alert($ids);
	//alert($filter);
	//$key1 = explode("_", $key);
	$init_id = $id;
	$_name = $key = str_replace("_id", "", $key);
	$key = str_replace("[]", "", $key);
	$_name = str_replace("$key", "{$key}_id", $_name);
	$key = $key."_id";
	$key2 = str_replace("_id", "", $key);
	$key3 = $key2."_name";
	///+++++++++++++++++++++++++++++++++++++++++++++
	$sql = "select * from ".$key2.$filter.$options;

	$db = new db2();
	debug_r("dropdown3");
	$result = $db->sql_query($sql);
	$f = $result;
	for($i = 0; $i < mysql_num_fields($result); $i++)
	{
		$fn = mysql_field_name($result, $i);
		$field_name = explode("_", $fn);

		if($field_name[count($field_name)-1] == "name")
		{
			break;
		}
	}

	if($fn == "forms_name" && page_name() == "forms"){$fn = "forms_comments";}
	if($filter) $key3 = $filter;

	if($key != 'orders_id' && if_users_id($key2))
	{
		if(!$key3) $key3 = ' WHERE ';
		$key3 .= ' users_id = '.$_SESSION["users_id"].' ';
		//	alert(if_users_id($key2));
	}
	if($key == "credit")
	{
		$key3 .= ' ORDER BY account_code';
	}
	else
	{
		$key3 .= ' ORDER BY '.$fn;
	}
	$sql = "select * from ".$key2.$key3;
	$result = $db->sql_query($sql);
	if(count($result)>0)
	{
		//echo '<label><select name="'.$key1[0].'_id" id="'.$key1[0].'_id">';
		$s = '<select name="'.$_name.'" id="'.$key.'"'.$is_required.'>';
	}
	$s .= '<option value="">Please Select</option>';
	foreach($result as $a)
	{
		eval("\$id = \$a[\"".$key."\"];");
		if(!$id) eval("\$id = \$a[\"id\"];");
		eval("\$name = \$a[\"".$fn."\"];");
		if($key == "account_heads_id 00")
			$name = ($a["account_code"]).' '.$name;
		if($init_id == $id)
		{
			$s .= '<option value="'.$id.'" selected="selected">'.$name.'</option>';
		}
		else
			$s .= '<option value="'.$id.'">'.$name.'</option>';
	}
	if(count($result)>0)
		$s .= '</select>';
	if($s == "")
	{
		$s = 'No Data Currently Available';
	}
	return $s;
}
function dropdown_counter($name, $start = 0, $end = 10, $options = '', $placeholder = 'Please Select')
{
	$s = '<select name="'.$name.'" id="'.$name.'" '.$options.'>
		<option value="0">'.$placeholder.'</option>';
	for($i=$start ; $i < $end; $i++)
		$s .= '<option value="'.$i.'">'.$i.'</option>';
	$s .= '</select>';
	return $s;

}
function next_id($key)
{
	//alert($ids);
	//alert($filter);
	//$key1 = explode("_", $key);
	$key2 = str_replace("_id", "", $key);
	$key = $key2."_id";
	///+++++++++++++++++++++++++++++++++++++++++++++
	$sql = "select max(".$key.")+1 as MAX from ".$key2;
	//echo $sql;
	$db = new db2();
	$f = $db->result($sql, 1);
	$id = $f["MAX"];
	if(!$id)
		$id = 1;
	return $id;
}
function next_id2($key)
{
	//alert($ids);
	//alert($filter);
	//$key1 = explode("_", $key);
	$key2 = str_replace("_id", "", $key);
	$key = "id";
	///+++++++++++++++++++++++++++++++++++++++++++++
	$sql = "select max(".$key.")+1 as MAX from ".$key2;
//	echo $sql;
	$db = new db2();
	$f = $db->result($sql);
	$id = $f["MAX"];
	if(!$id)
		$id = 1;
	return $id;
}
function if_users_id($class_name)
{
	$db = new db2();
	$sql = "select * from ".$class_name;
	$result = $db->sql_query($sql);
	$total_column = $result->columnCount();
	//$f = $result->fetch(PDO::FETCH_ASSOC);
	for ($counter = 0; $counter < $total_column; $counter ++) {
		$meta = $result->getColumnMeta($counter);

		$field_name_non_split = $meta['name'];
		//debug_r($meta);
		$flags = $meta['flags'];
		//
		//echo $flags'."<br />";
		$field_name = explode("_", $field_name_non_split);

		if($field_name_non_split == "users_id")
		{
			return true;
		}
	}
	return false;
}
function get_tuple($table, $id = '', $key = '')
{
	if($id == 0)
	{
		if(gettype($id) == 'integer')
			$id = '';
	}
	if($key == '' && $id != '')
		$key = $table.'_id';
	$sql = "select * from $table WHERE $key = '".$id."'";
	// echo $sql;return;
	$db = new db2();
	$a = $db->result($sql, 1);
	//print_r($a);
	#Temporarily remove users_id from returning
	//$a = remove_users($a);
	return $a;
}
function remove_users($arr)
{
	unset($arr["users_id"]);
	for($i = 0; $i < count($arr); $i++)
		if($arr[$i] == $_SESSION["users_id"])
		{
			unset($arr[$i]);
			break;
		}
	return $arr;
}
function has_date($page_name = '')
{
	if($page_name == '')
		$page_name = page_name();

	$sql = "select * from ".$page_name;
	//echo $sql; return;
	$db = new db2();
	debug_r("has_date");
	$result = $db->sql_query($sql);
	$f = $result;
	if(mysql_num_fields($result)>0)
	{
		for($i = 0; $i < mysql_num_fields($result); $i++)
		{
			//echo mysql_field_flags($result, $i)."<br />";
			$len = strlen(field_name($result, $i));
			//
			$flags = mysql_field_flags($result, $i);
			$field_name_non_split = mysql_field_name($result, $i);
			$field_name = explode("_", mysql_field_name($result, $i));

			if(mysql_field_type($result, $i) == "date")
				return true;
		}
	}
	return false;
}
function debug($msg)
{
	echo '<span style="font-size:12px">';
	echo '<pre>';
	print_r($msg);
	echo '</pre></span>';
}
function debug_r($msg)
{
	debug($msg);exit();
}
function debug_d($msg)
{
	debug($msg);die("");
}
function get($txt)
{
	eval("\$r=\$_REQUEST[\"$txt\"];");
	return $r;
}
function filter_inputs($txt)
{
//	filter_input(
	$txt = str_replace("'", "\'", $txt);
	return $txt;
}
//return all tables with key in them
function return_tables($key)
{
	$sql = "show TABLES from ".DB_NAME." like '%_to_".$key."%'";
	//debug($sql); exit();
	$db = new db2();
	$result = $db->result($sql);
	$r = array();
	if(count($result) > 0)
	{
		return array((str_replace('.php', '', page_url())));
	}
	foreach($result as $a)
		array_push($r, $a[0]);
	//debug($r); exit();
	return $r;
}
function colors($i = '')
{
	$clr = array('AFD8F8', 'F6BD0F', '8BBA00', 'FF8E46', '008E8E', 'D64646', '8E468E', '588526', 'B3AA00', '008ED6', '9D080D', 'A186BE');
	//debug($clr);
	//debug($i);
	if($i != '' || $i == 0)
	{
		return $clr[$i];
	}
	return $clr;
}
function revert($name)
{
	$name = str_replace(' ', '_', $name);
	$name = strtolower($name);
	return $name;
}
function get_value($field, $from ,$id = '', $where='')
{
	$db = new db2();
	if(($id != "") == "")
		$id = request("id");
	if($where == '')
		 $where = $from."_id";
	$class_name = $db->class_name;
	$sql = "select $field from $from where $where = '$id';";
//	debug($sql);
	$result = $db->result($sql, true);
	return $result[$field];
}
function get_name($id = '', $field)
{
	if($field == "forms")
	{
		return get_value($field."_comments", $field, $id);
	}
	else
		return get_value($field."_name", $field, $id);
}
function get_type($id, $field)
{
	return get_value($field."_type", $field."_type", revert($b["field_name"]));
}
function return_value($field, $from ,$cond)
{
	//alert($key);return;
	$db = new db2();
	if($cond != "")
		 $where = " where $cond";
//if forms_to_id
//	$table_names = explode("_to_", $this->class_name);
//	$class_name_id = ' WHERE '.$this->class_name."_id".'  = '.$id;
/*	then do read all rows and generate id from both*/
	$sql = "select $field from $from $where;";
	//echo $sql;
	$a = $db->result($sql);
	return $a[0];
}
function box_start($msg)
{
	echo '
	';
}
function box_end()
{
	echo '';
}
function php_to_mysql_date($initial_value)
{
	//29/05/2011 --> 2009-05-29
	$date_1 = explode("/",$initial_value);
	// debug_r($initial_value.'=='.$date_1[2].'-'.$date_1[1].'-'.$date_1[0]);
	return $date_1[2].'-'.$date_1[1].'-'.$date_1[0];
}
function timestamp_to_mysql($initial_value)
{
	return date("Y-m-d H:i:s", $initial_value);
}
function php_to_timestamp($initial_value)
{
	//31/03/2019 to mktime
	$date_1 = explode("/", $initial_value);
	return adodb_mktime(0,0,0,$date_1[1], $date_1[0], $date_1[2]);
}
function mysql_to_mktime($initial_value)
{
	//2009-05-29 --> 29/05/2011
	if(strpos($initial_value, '-') === false)
	{
		if(strpos($initial_value, '/') === false)
		{
			return ($initial_value - 25569) * 86400;
		}
		$date_1 = explode("/",$initial_value);
		//05/03/2017
		return adodb_mktime(0,0,0,$date_1[0], $date_1[1], $date_1[2]);
	}
	else
	{

		$date_1 = explode("-",$initial_value);
		if(2 == strlen($date_1[0]))
		{
			return adodb_mktime(0,0,0, $date_1[1], $date_1[0], $date_1[2]);
		}
	}
	$temp = $date_1[2].'/'.$date_1[1].'/'.$date_1[0];
	if($temp == "00/00/0000")
		return "";
	return adodb_mktime(0,0,0, $date_1[1], $date_1[2], $date_1[0]);
}
function mysql_to_php_date($initial_value)
{
	//21/01/2010 <-- 2010-01-21
	//Y/M/D => d/m/y
	$date_1 = explode("-",$initial_value);
	$temp = $date_1[2].'/'.$date_1[1].'/'.$date_1[0];
	if($temp == "00/00/0000")
		return "";
	return $temp;
}
function mysql_to_php_datetime($initial_value)
{
	$date_distributed = explode(' ', $initial_value);
	//11/22/2009 <-- 2010-01-21
	$date_1 = explode("-",$date_distributed[0]);
	$temp = $date_1[2].'/'.$date_1[1].'/'.$date_1[0];
	$date_2 = explode(":",$date_distributed[1]);
	$temp .= ' '.$date_distributed[1];
	if($temp == "00/00/0000")
		return "";
	return $temp;
}
function toarray($arr, $imgs, $names)
{
	echo '<script>';
	for($i=0; $i<count($arr); $i++)
	{
		echo "purchased2.push({id:".$arr[$i].", img:'".$imgs[$i]."', name:'".$names[$i]."'});";
	}
	echo '</script>';
}
function get_count_update()
{
	$db = new db2();
	$db->sqlq("update recipe SET counter = (counter+1) where recipe_id = ".$_GET["id"]);
}
function find_element($arr, $element)
{
	for($i=0; $i<count($arr); $i++)
	{
		if($arr[$i] == $element)
			return true;
	}
	return false;
	//
}
function dropdown2($key, $ids='')
{
	//alert($ids);
	//alert($filter);
	//$key1 = explode("_", $key);
	$key = str_replace("_id", "", $key);
	$key = $key."_id";
	$key2 = str_replace("_id", "", $key);
	$key3 = $key2."_name";
	///+++++++++++++++++++++++++++++++++++++++++++++
	$sql = "select * from ".$key2;
	//echo $sql;
	$db = new db2();
	debug_r("dropdown2");
	$result = $db->sql_query($sql);
	$f = $result;
	for($i = 0; $i < mysql_num_fields($result); $i++)
	{
		$fn = mysql_field_name($result, $i);
		$field_name = explode("_", $fn);

		if($field_name[count($field_name)-1] == "name")
		{
			break;
		}
	}
	$key3 .= ' ORDER BY '.$fn." ASC";
	$sql = "select * from ".$key2.' '.$key3;
	//echo "<br />".$sql."<br />";
	$result = $db->sql_query($sql);
	if(count($result)>0)
	{
		//echo '<label><select name="'.$key1[0].'_id" id="'.$key1[0].'_id">';
		$s = '<label><select name="'.$key.'" id="'.$key.'">';
	}
	foreach($result as $a)
	{
		eval("\$id = \$a[\"".$key."_id\"];");
		eval("\$name = \$a[\"".$fn."\"];");
		//eval("\$name=\$a['".$this->class_name."_name'];");
		///debug('id = '.$id.' ids = '.$ids);
		if($ids == $id)
		{
			$s .= '<option value="'.$id.'" selected="selected">'.$name.'</option>';
		}
		else
			$s .= '<option value="'.$id.'">'.$name.'</option>';
	}
	if(count($result)>0)
		$s .= '</select></label>';
	if($s == "")
	{
		$s = 'No Data Currently Available';
	}
	return $s;
}
function utube_thumb($id = '')
{
	$id1 = explode("?", $id);
	//print_r($id1);
	parse_str($id1[1]);
	//echo $v;return;
	return '<table id="Table_01" width="134" height="100" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="3" bgcolor="#000000">
			<img src="images/fr_01.jpg" width="134" height="7" alt=""></td>
	</tr>
	<tr>
		<td rowspan="2" bgcolor="#000000">
			<img src="images/fr_02.jpg" width="7" height="93" alt=""></td>
		<td width="120" height="86" bgcolor="#000000">		<a href="javascript: void(0)" onclick="MM_openBrWindow(\'video.php?id='.$id.'\',\'Cartoons\',\'width=450,height=360\');return false;">
		<img src="http://img.youtube.com/vi/'.$v.'/default.jpg" width="120" height="86" alt="" border="0" />
		</a>
	  </td>
		<td rowspan="2" bgcolor="#000000">
			<img src="images/fr_04.jpg" width="7" height="93" alt=""></td>
	</tr>
	<tr>
		<td bgcolor="#000000">
			<img src="images/fr_05.jpg" width="120" height="7" alt=""></td>
	</tr>
</table>';
}
function utube($id = '', $width="425", $height="344")
{
	$id1 = explode("?", $id);
	//print_r($id1);
	parse_str($id1[1]);
	//echo $v;return;
	//return;
	return '<object width="'.$width.'" height="'.$height.'"><param name="movie" value="http://www.youtube.com/v/'.$v.'&hl=en&fs=1&[b]start=20[/b]"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/'.$v.'&hl=en&fs=1&[b]start=20[/b]" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="344"></embed></object> ';
}
function email($emailTo, $emailSubject, $emailBody, $headers = '')
{
	// echo $emailTo.'----------------------------------';
	// use PHPMailer\PHPMailer\PHPMailer;
	// use PHPMailer\PHPMailer\Exception;
// echo _level0.'mailer/Exception.php';
// echo '0';
	require_once(_level0.'mailer/Exception.php');
// echo '1';
	require_once(_level0.'mailer/PHPMailer.php');
// echo '2';
	require_once(_level0.'mailer/SMTP.php');
	// echo '3';die;
	$mail = new PHPMailer\PHPMailer\PHPMailer();

	try {
		//Server settings
		// $mail->SMTPDebug = PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;                      // Enable verbose debug output
		$mail->isSMTP();                                            // Send using SMTP
		$mail->Host       = 'mail.school.greenislandtrust.org';                    // Set the SMTP server to send through
		$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
		$mail->Username   = EMAIL_LOGIN_ID;                     // SMTP username
		$mail->Password   = EMAIL_PASSWORD;                               // SMTP password
		$mail->SMTPSecure = 'ssl';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
		$mail->Port       = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
		//Recipients
		$mail->setFrom(EMAIL_LOGIN_ID, "Green Island School");
		if(strpos($emailTo, ",") === false)
		{
			$mail->addAddress($emailTo);     // Add a recipient
		}
		else
		{
			//split all adress and add one by one
			$email_tos = explode(",", $emailTo);
			foreach($email_tos as $email_to)
				$mail->addAddress($email_to);     // Add a recipient
		}
		// $mail->addAddress('muhammad.hasnain@thebaronhotels.com');               // Name is optional
		// $mail->addReplyTo('info@example.com', 'Information');
		// $mail->addCC('cc@example.com');
		// $mail->addBCC('hasnainwasaya@gmail.com');

		// Attachments
		// $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

		// Content
		$mail->isHTML(true);                                  // Set email format to HTML
		$mail->Subject = $emailSubject;
		$mail->Body    = $emailBody;
		// $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		$mail->send();
//		 echo 'Message has been sent';
	} catch (Exception $e) {
		 echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	}

	//'Cc: asad <info@symbios.pk>' . "\n"
	// $emailHeader = email_header();
	// return mail($emailTo, $emailSubject, $emailBody, $emailHeader);
}
//XPM XpertMailer
function email2($emailTo, $emailSubject, $emailBody, $emailPriority, $emailFrom, $emailReplyTo='')
{
	/*
	integer 1 = string High
	integer 3 = string Normal
	integer 5 = string Low
 	*/
	// manage errors
	//error_reporting(E_ALL); // php errors
	//define('DISPLAY_XPM4_ERRORS', true); // display XPM4 errors
	if($emailPriority != 1 && $emailPriority != 3 && $emailPriority != 5)
	{
		$emailPriority = 3;
	}
	// path to 'MAIL.php' file from XPM4 package
	require_once("MAIL.php");
$headers = array(
    'From' => $emailFrom,
    'To' => $emailTo,
    'Subject' => $emailSubject
);
$smtp = Mail::factory('smtp', array(
        'host' => 'mail.school.greenislandtrust.org',
        'port' => '465',
        'auth' => true,
				'username' => EMAIL_LOGIN_ID,
				'password' => EMAIL_PASSWORD
		));
$mail = $smtp->send($emailTo, $headers, $emailBody);
//alert(PEAR::isError($mail));
if (PEAR::isError($mail)) {
    echo('<p>Error! ' . $mail->getMessage() . '</p>');
} else {
    echo('<p>Message successfully sent!</p>');
}
die;


	// //EMAIL_LOGIN_ID EMAIL_PASSWORD
	// // initialize MAIL class
	// $m = new MAIL;
	// // set from address
	// $m->From($emailFrom);
	// // add to address
	// $m->AddTo($emailTo);
	// // set subject
	// $m->Subject($emailSubject);
	// // set HTML message
	// $m->Html($emailBody);
	// //set Priority
	// $m->Priority($emailPriority);
	// //set Reply-to
	// if($emailReplyTo != '')
	// {
	// 	$m->AddHeader('Reply-To', $emailReplyTo);
	// }
	// // connect to MTA server 'smtp.gmail.com' port '465' via SSL ('tls' encryption) with authentication: 'username@gmail.com'/'password'
	// // set the connection timeout to 10 seconds, the name of your host 'localhost' and the authentication method to 'plain'
	// // make sure you have OpenSSL module (extension) enable on your php configuration
	// $c = $m->Connect('smtp.gmail.com', 465, EMAIL_LOGIN_ID, EMAIL_PASSWORD, 'tls', 10, 'localhost', null, 'plain') or die(print_r($m->Result));

	// // send mail relay using the '$c' resource connection
	// $r = $m->Send($c) ? $emailTo : 'Error';
	// // disconnect from server
	// $m->Disconnect();
	/*
	// optional for debugging ----------------
	// print History
	print_r($m->History);
	// calculate time
	list($tm1, $ar1) = each($m->History[0]);
	list($tm2, $ar2) = each($m->History[count($m->History)-1]);
	echo 'The process took: '.(floatval($tm2)-floatval($tm1)).' seconds.</pre>';
	*/
	return $r;
}
function convert_datetime($str)
{
list($date, $time) = explode(' ', $str);
list($year, $month, $day) = explode('-', $date);
list($hour, $minute, $second) = explode(':', $time);
$timestamp = adodb_mktime($hour, $minute, $second, $month, $day, $year);
return $timestamp;
}
function load_content($data)
{
	$db = new db2();
	$sql = "SELECT * FROM  `content` ";
	$result = $db->result($sql);
	foreach($result as $f)
	{
		$content = get_tuple("content", $f["content_name"], "content_name");
		$name = $f["content_name"];
		$name = str_replace("{", '', $name);
		$name = str_replace("}", '', $name);
		define($name, $content["content_description"]);
		if($f["content_description"] == "")
		{
		}
		else
		{
			$data = str_replace($f["content_name"], htmlspecialchars_decode1($content["content_description"]), $data);
		}
	}
	$u = new users();
	if(	($_SESSION["users_id"] != "0") && isset($_SESSION["users_id"])	)
	{
		$u = new users();
		$data = str_replace("{__content_list__}", $u->generate_perm(), $data);
	}
	else
	{	$data = str_replace("{__content_list__}", "", $data);
	}
	return $data;
}
function upload3($name , $tmp, $targetpath)
{
	$file_name['name'] = $name;
	$file_name['tmp_name'] = $tmp;
	if(!file_exists($targetpath))
	{
		//"/path/to/my/dir", 0700);
		mkdir(str_replace("\\", "/", getcwd().'/'.$targetpath), 0700);
	}

	$T = '';
	if($file_name['name'] != '')
	{
		$rnd = returnPassword();
		$targetpath = $targetpath.'/'.adodb_date("ymd").$rnd.basename($file_name['name']);
		$sql1 = ""; //Image 1
		if(move_uploaded_file($file_name['tmp_name'], $targetpath))
		{
			$T = $targetpath;
		}
	}
	return $T;
}
function limit($str, $max = 100)
{
	if($max > strlen($str))
	{
		//alert(strpos($str, ' ', 50));
		return $str;
	}
	return substr($str, 0, strpos($str, ' ', 100));
}
function get_next_code($table_name, $id_column = '')
{
	$db = new db2();
	if(!$id_column) $id_column = $table_name."_id";
	$sql = "select max($id_column) as last from $table_name
	WHERE $id_column != '0073' AND  $id_column != '0042'
	";
	$data = $db->result($sql, true);
	$max_voucher_number = $data['last'];
	$max_voucher_number++;
	return $max_voucher_number;
}
function get_latest_currency_rate($currency_id)
{

	define("CURR", json_encode($currencies));
	$currencies = (array)json_decode(CURR);
	$data = array_search($currency_id, $currencies);
	debug($data);
	return $currencies[$currency_id];
}
function get_curl_output($url, $header, $post_fields, $put = 'POST')
{
	$curl = curl_init();
	$options = array(
		CURLOPT_URL => $url,
		CURLOPT_HTTPHEADER => $header,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1
	);
	if($put == 'POST')
	{
		$options[CURLOPT_POSTFIELDS] = $post_fields;
		$options[CURLOPT_POST] = 1;
	}
	if($put == 'PUT')
	{
		$options[CURLOPT_CUSTOMREQUEST] = "PUT";
	}
	else if($put == 'GET')
	{
		//$options[CURLOPT_CUSTOMREQUEST] = "GET";
	}
	//debug($post_fields);
	//debug($options);
	curl_setopt_array($curl, $options);
	$output = (array)json_decode(curl_exec($curl));
	if (curl_errno($curl)){
		debug_r('<br>Retreive Base Page Error: ' . curl_error($curl));
	}
	//debug($json);
	curl_close ($curl);
	return $output;
}

function set_session_for($table, $col_id = 'id', $filter = '')
{
	$reset_session = false;
	$reset_session = true;
	if(!isset($_SESSION["data_".$table]) || $reset_session)
	{
		$db = new db2();
		if($filter)
		{
			$filter = ' where '.$filter;
			if(is_array($filter)) $filter = ' where '.implode('AND', $filter);
		}
		$rows = $db->result("select * from $table $filter");
		$last_pax_max = 0;
		unset($_SESSION["data_".$table]);
		$_SESSION["data_".$table] = array();
		foreach($rows as $row)
		{
			$_SESSION["data_".$table][$row[$col_id]] = $row;
		}
	}
}
function get_session_value($table, $ids)
{
	$ids = explode(",", $ids);
	$data = $_SESSION["data_".$table];
	// debug($ids);
	// debug_r($table);
	$rows = array();
	foreach($ids as $id)
	{
		$rows[] = $data[trim($id)];
	}
	return $rows;
}
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
