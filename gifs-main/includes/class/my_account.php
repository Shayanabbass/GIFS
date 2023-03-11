<?php
function my_account()
{
	$users_id = $_SESSION["users_id"];
	if(!is_login())
	{
		return user_not_logged_in();
	}
	$temp = '  <div class="tourmaster-my-profile-wrapper">
                                <div class="tourmaster-my-profile-avatar"><img alt=""
                                                                               src="https://secure.gravatar.com/avatar/4dbc81e64e005304aaaf696e9b3941ce?s=85&amp;d=mm&amp;r=g"
                                                                               srcset="https://secure.gravatar.com/avatar/4dbc81e64e005304aaaf696e9b3941ce?s=170&amp;d=mm&amp;r=g 2x"
                                                                               class="avatar avatar-85 photo"
                                                                               height="85" width="85"></div>
                                <div class="tourmaster-my-profile-info-wrap clearfix">
                                    <div class="tourmaster-my-profile-info tourmaster-my-profile-info-full_name tourmaster-even clearfix">
                                        <span class="tourmaster-head">Name</span><span class="tourmaster-tail">Muhammad Hasnain (testing followup)</span>
                                    </div>
                                    <div class="tourmaster-my-profile-info tourmaster-my-profile-info-gender tourmaster-odd clearfix">
                                        <span class="tourmaster-head">Gender</span><span class="tourmaster-tail"></span>
                                    </div>
                                    <div class="tourmaster-my-profile-info tourmaster-my-profile-info-birth_date tourmaster-even clearfix">
                                        <span class="tourmaster-head">Birth Date</span><span
                                                class="tourmaster-tail">-</span></div>
                                    <div class="tourmaster-my-profile-info tourmaster-my-profile-info-country tourmaster-odd clearfix">
                                        <span class="tourmaster-head">Country</span><span
                                                class="tourmaster-tail">Iraq</span></div>
                                    <div class="tourmaster-my-profile-info tourmaster-my-profile-info-email tourmaster-even clearfix">
                                        <span class="tourmaster-head">Email</span><span class="tourmaster-tail">muhammad.hasnain@thebaronhotels.com</span>
                                    </div>
                                    <div class="tourmaster-my-profile-info tourmaster-my-profile-info-phone tourmaster-odd clearfix">
                                        <span class="tourmaster-head">Phone</span><span class="tourmaster-tail">012312311</span>
                                    </div>
                                    <div class="tourmaster-my-profile-info tourmaster-my-profile-info-contact_address tourmaster-even clearfix">
                                        <span class="tourmaster-head">Contact Address</span><span
                                                class="tourmaster-tail">-</span></div>
                                </div>
                            </div>';

	$data_n = array();
	$db = new db2();
	$action = $_GET["action"];
	if(!$action)$action = 'edit';
	$data_n["html_head"] = "";
	$data_n["html_title"] = "My Account";
    $data_n["html_heading2"] = '<a class="tourmaster-user-content-title-link"
                               href="?action=edit">Edit
                                Profile</a>';
	$data_n["html_heading"] = "My Account";
	$data_n["html_wrapper"] = true;


	$data_n['html_text'] = $temp;
	return $data_n;

	/*		Update/View Profile		*/
	if($action == "edit")
	{
		$data_n = register('edit');
		$data_n["html_title"] = "View / Update Profile";
		$data_n["html_heading"] = "View / Update Profile";
		return $data_n;
	}
	elseif($action == "update")
	{
		profile_update();
	}
	else
	{
		$user = get_user();
		$users_picture_upload = $user["users_picture_upload"];
		$temp = '<div id="account_options">
			<ul>
				<li><a href="?action=edit">Update / View Profile</a></li>
				<li><a href="messages.php">Bid Messages</a></li>
				<li><a href="add_products.php">Add Product</a></li>
			</ul>
			
		</div>
		<img src="'.$users_picture_upload.'" align="right" id="profile_image" />
		';
	}
//	debug_r($_SESSION);
	$data_n["html_text"] .= $temp;
	return $data_n;
}
function mark_as_used()
{
	$data_n["html_head"] = "";
	$data_n["html_title"] = "Mark as Used";
	$data_n["html_heading"] = "Mark as Used";
	if(!is_admin())
	{
		$data_n["html_text"] = '<p>You need to login <strong>as Site Administrator</strong> in orders to proceed
				<strong>&lt;&lt;</strong>
				<a href="index.php">Click here to go back</a>
			</p>';
		return $data_n;
	}
	$data_n = array();
	$id = $_GET["id"];
	if(!$id)
	{
		$data_n["html_text"] = '<p>Invalid URL
				<a href="index.php">Click here to go back</a>
			</p>';
		return $data_n;
	}
	$user = get_tuple("users", $id, "users_id");
	$db = new db2();
	$sql =  "update orders set status = 1, updated_on = ".time()." where users_id = $id";
	$headers = headers();
	$message = '
	Dear '.$user["users_first_name"].' '.$user["users_last_name"].'<br />
	You have successfully redeemed your points. 
	<p>&nbsp;</p>
	<p>Dated: '.date("r").'</p>
	';
	mail($user["users_email"], "Congradulations. You have redeemed your points", $message, $headers);
	mail(SITE_ADMIN_EMAIL, $user["users_first_name"].' '.$user["users_last_name"]." have redeemed his points", $message, $headers);
	$result = $db->sql_query($sql);
	redirect("loyalty.php?users_id=$id");
	die;
}
function print_certificate()
{
	if(is_admin() && $_GET["users_id"])
	{
		$users_id = $_GET["users_id"];
	}
	else
	{
		$users_id = $_SESSION["users_id"];
	}
	echo '<link href="reset.css" rel="stylesheet" type="text/css" media="all">
	<link href="ana_haji.css" rel="stylesheet" type="text/css" media="all">
	<div id="logo"> <img src="images/logo.png"> </div>
	<div id="wrapper">
  <div id="header">
    <div id="menu_up" style="color:#ffcc00; font:bolder 17px arial">Loyalty Certificate (Customer ID: '.$users_id.')</div>
    <!--menu_up-->
    <div id="toll"></div><!--toll--> 
   
    <div id="login_form"></div>
    <!--login_form-->
    <div class="clearfix"></div>
    <div class="style-1">
      <div id="menu"></div>
      <!--style-1--> 
      
    </div>
    <!--menu-->
    <div class="clearfix"></div>
  </div>
  <!--header-->    <!--<div id="banner" style="background:url(images/banner2.png)"></div>
    <div class="style-2"></div>
  -->
<div class="clearfix" style="height:32px;"></div>
  <div id="left_column">
  	<div class="col2">
   	  <h1>Loyalty Points</h1>
   '.get_total_loyalty($users_id).'
   <p>&nbsp;</p>
   <p>Printed on : <strong>'.date("r").'</strong></p>
   </div>
  </div>
  <!--left_column-->
<div class="clearfix" style="height:32px;"></div>
<div class="clearfix"></div>
	<!--footer-->
<div class="clearfix" style="height:32px;"></div>
    
    <div class="style-1"></div>
	
    <div id="footer">
	&copy; Copyright ANA HAJI. All Rights Reserved.</div></div>
	';
	die;
}
function show_all_customers()
{
	$users_id = $_SESSION["users_id"];
	if(!is_admin())
	{
		$data_n["html_text"] = '<p>You need to login <strong>as Site Administrator</strong> in orders to proceed
				<strong>&lt;&lt;</strong>
				<a href="index.php">Click here to go back</a>
			</p>';
		return $data_n;
	}
	$data_n = array();
	$action = $_GET["action"];
	$data_n["html_head"] = "";
	$data_n["html_title"] = "Loyalty Points";
	$data_n["html_heading"] = "Loyalty Points";
	$view = 1;
	if($id = $_GET["id"])
	{
		$user = get_tuple("users", $id, "users_id");
		if($user)
		{
			$view = 0;
			$temp .= get_total_loyalty($id);			
		}
		else
		{
			$temp .= '<div class="error">User does not exist</div>';
		}
	}
	
	if($view)
	{
		$sql = "select * from users where groups_id = 12";
		$db = new db2();
		$result = $db->sql_query($sql);
		$temp .= '
		<form action="" method="get" name="search_form">
		<p>Search By Customer ID: <input type="text" name="id" id="id"> <input type="submit" value="search"> </p>
		</form>
		<div class="clearfix" style="height:20px;"></div>
		<ul>';
		foreach($result as $a)
		{
			//debug_r($a);
			$temp .= '<li>'.$a["users_first_name"].' '.$a["users_last_name"] .' (ID : <strong>'.$a["users_id"].'</strong>)'.' <a href="?id='.$a["users_id"].'" target="_blank">View Details</a></li>';
		}
		$temp .= '</ul>';
	}
	$data_n["html_text"] .= $temp;
	return $data_n;
}