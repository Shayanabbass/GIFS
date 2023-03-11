<?php 
function generic()
{
	$get = str_to_arr();
	$db = new db2();
	$_GET = $get;
	/*$data_n = array();
	$data_n["html_head"] = "";
	$data_n["html_title"] = "title ";
	$data_n["html_heading"] = "heading ";
	$data_n["html_text"] = "text ";
	return $data_n;*/
	if($get["action"]=="")
	{
		redirect();
		//eval($get["action"].'();');
	}
	if($_GET["action"] == "update_pagination")
	{
		if($_GET["id"] == "") redirect("index.php");
		$sql = "delete from pagination where users_id = ".$_SESSION["users_id"]." AND groups_id = ".$_SESSION["groups_id"];
		$db->sqlq($sql);
		$sql = "insert into pagination (users_id, groups_id, number_of_pages) values (".$_SESSION["users_id"].", ".$_SESSION["groups_id"].", ".$_GET["id"].");";
		$db->sqlq($sql);
		redirect($_SERVER['HTTP_REFERER']);
		die;
	}
}
function subscription()
{
	$sql = "insert into subscriptions
	(subscriptions_name, subscriptions_last_name, subscriptions_email)
	VALUES
	('".$_POST["first_name"]."', '".$_POST["last_name"]."', '".$_POST["email"]."')";
	debug_r($sql);
	$db = new db2();
	$db->sqlq($sql);
	
	$subject = 'New Subscription From ForexSchool';
	$message = 'eMAIL : '.$_POST["email"].'<br />
				First Name: '.$_POST["first_name"].'<br />
				Last Name: '.$_POST["last_name"].'';
	$headers = 'From: webmaster@example.com' . "\r\n" .
		'Reply-To: webmaster@example.com' . "\r\n" .
		'Content-type: text/html; charset=iso-8859-1' . "\r\n".
		'X-Mailer: PHP/' . phpversion();
	
	mail(SITE_ADMIN_EMAIL, $subject, $message, $headers);
	alert("You have successfully subscribed.");
	redirect($_SERVER["HTTP_REFERER"]);
}
function content_to_constant()
{
    $db = new db2();
    $sql = "SELECT * FROM  `content` ";
    $data = $db->result($sql);
    foreach($data as $f)
    {
        $content = get_tuple("content", $f["content_name"], "content_name");
        $name = $f["content_name"];
        $name = str_replace("{", '', $name);
        $name = str_replace("}", '', $name);
        define($name, $content["content_description"]);
    }
    return $data;
}
function dropdown_incident_category()
{
	$temp = '<select id="closure_category_id" name="closure_category_id" class="form-control">';
	$sql = "select * from incident_category where parent_id = 0 ORDER BY incident_category_name ASC";
	$db = new db2();
	$data = $db->result($sql);
	foreach($data as $a)
	{
		$temp .= '<optgroup label="'.$a['incident_category_name'].'">';
		$sql2 = "select * from incident_category where parent_id = ".$a['incident_category_id']." ORDER BY incident_category_name ASC";
		$data2 = $db->result($sql2);
		foreach($data2 as $b)
		{
			$temp .= '<option value="'.$b['incident_category_id'].'">'.$b['incident_category_name'].'</option>';
		}
		$temp .= '</optgroup>';
	}
	$temp .= '
	</select>
	<script>
	$(function(){
		$("#incident_category_id").select2();
	})
	</script>
	';
	
	return $temp;
}