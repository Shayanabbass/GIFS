<?php
class users extends db2
{
	function insert()
	{
		$users_id = $_REQUEST["users_id"];
		$users_name = $_REQUEST["users_name"];
		$users_password = $_REQUEST["users_password"];$users_password = md5($users_password);
		$users_email = $_REQUEST["users_email"];
		$groups_id = $_REQUEST["groups_id"];
		$users_full_name = $_REQUEST["users_full_name"];
		$currency_id = $_REQUEST["currency_id"];
		$company_id = $_REQUEST["company_id"];
		$sql = 'Insert into users (users_id, users_name, users_password, users_email, groups_id, users_full_name, currency_id, company_id) VALUES (\''.$users_id.'\', \''.$users_name.'\', \''.$users_password.'\', \''.$users_email.'\', \''.$groups_id.'\', \''.$users_full_name.'\', \''.$currency_id.'\', \''.$company_id.'\')';
		$result = $this->sqlq($sql);
		if($result)
		{
			alert("USERS Inserted Successfully");
			redirect("users.php?action=new");
		}
	}

	function delete()
	{
		$id = $_GET["id"];
		$sql = "DELETE FROM users WHERE users_id = ".$id." LIMIT 1;";
		$result = $this->sqlq($sql);
		if($result)
		{
			alert('USERS record deleted successfully');
			redirect('users.php');
		}
		else
		{
			alert('!!!USERS record delete failed');
		}
	}
	function update()
	{
		$id = $_GET["id"];
		$users_name = $_REQUEST["users_name"];
		$users_password = $_REQUEST["users_password"];
		$users_password = md5($users_password);
		$users_email = $_REQUEST["users_email"];
		$users_full_name = $_REQUEST["users_full_name"];
		$currency_id = $_REQUEST["currency_id"];
		$company_id = $_REQUEST["company_id"];

		if($_SESSION["groups_id"] == 5)
		{
			$id = $_SESSION["users_id"];
		}
		else
		{
			$usr = "users_name='".$users_name."',";
		}
		$value = $_FILES["users_picture_upload"];
		$path = upload($value, "users_images");
		$users_picture_upload = ", users_picture_upload='".$path."'";
		if(empty($path))
		{
			$users_picture_upload = '';
		}
		$date_of_birth = $_REQUEST["date_of_birth"];$date_of_birth = php_to_mysql_date($date_of_birth);
		$sql = "UPDATE users SET  ".$usr." users_password='".$users_password."', users_email='".$users_email."', users_full_name='".$users_full_name."', currency_id='".$currency_id."', company_id='".$company_id."' WHERE users_id = ".$id;
		//debug_r($sql);
		$result = $this->sqlq($sql);
		if($result)
		{
			alert("USERS Updated Successfully");
			back();
			//redirect("users.php");
		}
		else
		{
			alert("USERS NOT Updated Successfully");
		}
	}
	function edit()
	{
		$id = $_GET["id"];
		$edit_disabled = "";
		if($_SESSION["groups_id"] == 5)
		{
			$id = $_SESSION["users_id"];
			$edit_disabled = ' disabled readonly="readonly"';
		}
		if($id == "")
			$id = $_SESSION["users_id"];
		$sql = "select * from users where users_id = ".$id;
		$a = $this->result($sql, 1);
		if($a)
		{
			$output .= '
			<form id="form5" name="form5" method="post" action="?action=update&id='.$id.'" enctype="multipart/form-data" onsubmit="return check()">
			<table width="100%" border="0" cellspacing="0" cellpadding="5">';
			$users_name = $a["users_name"];
			$output .= '
				  <tr>
					<td width="30%">Users Name</td>
					<td width="70%"><input name="users_name" type="text" class="inputs" id="users_name" value="'.$users_name.'" '.$edit_disabled.'/>
					</td>
				  </tr>
				 ';
			$output .= '
				  <tr>
					<td width="30%">Users Password</td>
					<td width="70%"><input name="users_password" type="text" class="inputs" id="users_password"/> Enter new password
					</td>
				  </tr>
				 ';
			$output .= '
				  <tr>
					<td width="30%">Password Verify</td>
					<td width="70%"><input name="cpassword" type="text" class="inputs" id="cpassword"/> Verify new password
					</td>
				  </tr>
				 ';
			$date_of_birth = $a["date_of_birth"];
			$date_of_birth = mysql_to_php_date($date_of_birth);
			$output .= '
				  <tr>
					<td width="30%">Date of Birth</td>
					<td width="70%">
					<script language="JavaScript" src="trafficbuilder/calendar_us.js" type="text/javascript"></script>
                    <link rel="stylesheet" href="trafficbuilder/calendar.css" />
                  <label>
                        <input name="date_of_birth" type="text" id="date_of_birth" value="'.$date_of_birth.'" />
                      </label>
                        <img src="images/spacer.gif" width="3" height="1" alt="" />
                        <script language="JavaScript" type="text/javascript">
						new tcal ({
							// form name
							\'formname\': \'form5\',
							// input name
							\'controlname\': \'date_of_birth\'
						});
				  </script>
					</td>
				  </tr>
				 ';

			$users_email = $a["users_email"];
			$output .= '
				  <tr>
					<td width="30%">Users Email</td>
					<td width="70%"><input name="users_email" type="text" class="inputs" id="users_email" value="'.$users_email.'"/>
					</td>
				  </tr>
				 ';

				 $users_secret_question = $a["users_secret_question"];
			$output .= '
				  <tr>
					<td width="30%">Users Secret Question</td>
					<td width="70%"><input name="users_secret_question" type="text" class="inputs" id="users_secret_question" value="'.$users_secret_question.'"/>
					</td>
				  </tr>
				 ';
			$users_secret_answer = $a["users_secret_answer"];
			$output .= '
				  <tr>
					<td width="30%">Users Secret Answer</td>
					<td width="70%"><input name="users_secret_answer" type="text" class="inputs" id="users_secret_answer" value="'.$users_secret_answer.'"/>
					</td>
				  </tr>
				 ';

			$users_full_name = $a["users_full_name"];
			$output .= '
				  <tr>
					<td width="30%">Users First Name</td>
					<td width="70%"><input name="users_full_name" type="text" class="inputs" id="users_full_name" value="'.$users_full_name.'"/>
					</td>
				  </tr>
				 ';
			$currency_id = $a["currency_id"];
			$output .= '
				  <tr>
					<td width="30%">Currency</td>
					<td width="70%">'.dropdown('currency', $currency_id).'</td>
				  </tr>
				 ';
			//#=-)
			$company_id = $a["company_id"];
			$output .= '<tr>
						<td width="30%">Company</td>
						<td width="70%">'.dropdown('company', $company_id).'</td>
					  </tr>';

			$output .= '
		<tr><td colspan="2">
		<table cellpadding="5" cellspacing="0" border="0">
		<tr>
				<td><input type="submit" name="button2" id="button2" value="Update USERS" /></td>
				<td><input type="reset" name="Reset" id="button2" value="Reset USERS" /></td>
				<td>
				</td>
			  </tr>
		</table>
			</table>
		  </form>';
		}
	}
	function show()
	{
		if(($_SESSION["users_name"] != "admin"))
			$filter = " where users_name != 'admin'";
		$sql = "select * from users".$filter;
		$result = $this->result($sql);
		if(count($result)==0)
		{
			$output .= ' No Records Currently Present';
		}
		else
		{
			$output .= '';
		}
		foreach($result as $a)
		{
			$name = $a["users_name"];
			$id = $a["users_id"];
			$output .= '<tr>
					<td bgcolor="#ffffff" class="black">'.$name.'</td>
					<td bgcolor="#ffffff" class="black"><form name="form1" method="post" action="?action=edit&id='.$id.'">
					  <label>
						<input type="submit" name="button" id="button" value="Edit/Update">
					  </label>
					</form></td>
					<td bgcolor="#ffffff" class="black"><form name="form2" method="post" action="?action=delete_confirm&id='.$id.'">
					  <label>
						<input type="submit" name="button2" id="button2" value="Delete">
					  </label>
					</form>
					</td>
				  </tr>';
		}
		if(count($result)>0)
		{
			$output .= '';
		}
	}


	function users_name()
	{
		$sql = "SELECT users_name FROM users WHERE users_id = ".$_REQUEST["id"];
		//alert($sql); return;
		$result = $this->result($sql);
		foreach($result as $a)
		{
			return $a["users_name"];
		}
	}
	function users_check($name)
	{

		$sql = "SELECT users_name FROM users WHERE users_name = '".$name."'";
		//alert($sql); return;
		$result = $this->result($sql);
		foreach($result as $db)
		{
			if($db["users_name"] == $name)
			{

				return 1;
			}
			else
			{
				return 0;
			}
		}
	}
	function users_check2($users_name_temp = '', $users_password_temp = '', $redirect = true)
	{
	    if($users_name_temp == '')
            $users_name_temp = addslashes($_SESSION["users_name_temp"]);
        if($users_password_temp == '')
            $users_password_temp = addslashes($_SESSION["users_password_temp"]);

		/*$_SESSION["users_name_temp"] = addslashes($_SESSION["users_name_temp"]);
		$_SESSION["users_password_temp"] = addslashes($_SESSION["users_password_temp"]);*/
		/*
		1) get the list of all group names
		2) gototheir tables and with id in temp check for login
		3) login
		*/
		$sql = "SELECT * FROM groups";
		$result = $this->result($sql);
		foreach($result as $a)
		{
			if($a["groups_name"] != "User" && db_has_table($a["groups_name"]))
			{
				$groups_name = strtolower($a["groups_name"]);
				$name_field = "_name";
				if($groups_name == "guest") $name_field = "_email";
				$sql2 = "SELECT * FROM ".$groups_name." where ".$groups_name.$name_field." = '".$users_name_temp."' AND ".$groups_name."_password = '".md5($users_password_temp)."';";
				$result2 = $this->result($sql2);
				//debug_r($sql2);
				foreach($result2 as $a2)
				{
					//$_SESSION["users_id"] = $a2["users_id"];
					eval('$id = $a2["'.$groups_name.'_id"];');
					$user = get_tuple($groups_name, $id, $groups_name."_id");
					$user_full_name = $user[$groups_name.'_first_name'];
					if($user_full_name != "")
					{
						$user_full_name .= ' '.$user[$groups_name.'_last_name'];
					}
					else
					{
						$user_full_name = $user[$groups_name.'_last_name'];
						if($user_full_name == "")
						{
							$user_full_name = $user[$groups_name.'_full_name'];
						}
						if($user_full_name == "")
						{
							$user_full_name = $user[$groups_name.'_name'];
						}
					}
					$_SESSION["users_full_name"] = $user_full_name;
					//die($_SESSION["user_full_name"]);
					$_SESSION["users_id"] = $id;
					$_SESSION["groups_id"] = $a["groups_id"];
					$_SESSION["currency_id"] = $user["currency_id"];
					$_SESSION["company_id"] = $user["company_id"];
					$_SESSION['profile_image'] = $user['profile_image'];
					$_SESSION["users_name"] = $users_name_temp;
					$_SESSION["permission_dynamic_cmm"] = 'Yes';
					$_SESSION['member_since'] = humanTiming($user['added_on']);
					//alert ("hasnian");
					$_SESSION["users_name_temp"] = '';
					$_SESSION["users_password_temp"] = '';
					unset($_SESSION["users_name_temp"]);
					unset($_SESSION["users_password_temp"]);
					save_in_sessions($user);
					reset_error();
					if($redirect)
					{
						if($_SESSION['redirect_url'] != '')
						{
							redirect($_SESSION['redirect_url']);
							unset($_SESSION['redirect_url']);
							die;
						}
    					redirect('index.php');
					}
					else
    					return true;
				}
			}
		}
		$sql = "SELECT * FROM users WHERE users_name = '".$users_name_temp."' AND users_password = '".md5($users_password_temp)."';";
		// debug($sql);

		$db = $this->result($sql, 1);
		// debug_r($db);
		if($db)
		{
			$users_full_name = $db["users_full_name"] ;
			$_SESSION["users_full_name"] = $users_full_name;
			$_SESSION["users_id"] = $db["users_id"];
			$_SESSION["groups_id"] = $db["groups_id"];
			$_SESSION['profile_image'] = $db['profile_image'];
			$_SESSION["currency_id"] = $db["currency_id"];
			$_SESSION["company_id"] = $db["company_id"];
			$_SESSION["users_name"] = $users_name_temp;
			$_SESSION["permission_dynamic_cmm"] = 'Yes';
			/*$_SESSION['profile_image'] = $user['profile_image'];
			$_SESSION["users_name"] = $_SESSION["users_name_temp"];
			$_SESSION["permission_dynamic_cmm"] = 'Yes';
			$_SESSION['member_since'] = humanTiming($user['added_on']);
			*/
			//alert ("hasnian");
			$_SESSION["users_name_temp"] = '';
			$_SESSION["users_password_temp"] = '';
			save_in_sessions($db);
			reset_error();
			if($redirect)
			{
				if($_SESSION['redirect_url'] != '')
				{
					redirect($_SESSION['redirect_url']);
					unset($_SESSION['redirect_url']);
					die;
				}
			}
			if($redirect && page_url() == "login.php")
				redirect(FILENAME_DEFAULT);
			else if ($redirect)
			    redirect(page_url());
			else
			    return true;
		}
		//add_to_block();
		// rajpootkhlid@gmail.com, contact@smmoin.com, info@smmoin.com, hasnainwasaya@gmail.com
		if($redirect) {
            set_error("Invalid username, email address or incorrect password.");
            redirect(page_url());
        }else
            return false;
	}

	function users_exist()
	{
		if($_SESSION["permission_admin_id"] == $_POST["users_name"])
		{
			return 0;
		}
		$sql = "SELECT * FROM users WHERE users_name = '".$_POST["users_name"]."';";
		//alert($sql); return;
		$result = $this->result($sql);
		foreach($result as $db)
			return 1;
		return 0;
	}
	function sub_form($form)
	{
		$forms_id = $form["forms_id"];
		$sql2 = "select * from forms where parent_id = $forms_id";
		$result2 = $this->result($sql2);
		foreach($result2 as $a)
		{
			if(page_url() == $a["forms_name"])
				return 1;
			elseif(page_url_complete() == $a["forms_name"])
				return 1;
		}
	}
	function permissions()
	{
		global $dont_show_forms;
		//$group_name = get_name($_SESSION["groups_id"], "groups");
		$output = '';
		$default_icon = '';
		if($_SESSION["users_name"] == "admin")
		{
			$sql_main = "select * from forms where parent_id = 0 order by sort, forms_comments";
			$result_main = $this->result($sql_main);
			$output .= ' <ul class="sidebar-menu"> ';
			foreach($result_main as $a)
			{
				// debug_r($a);
				//two level menu
				$sql_sub = "select * from forms where parent_id = ".$a["forms_id"]." order by sort, forms_comments";
				$result_sub = $this->result($sql_sub);
				$menu_link_active = '';
				foreach($result_sub as $b)
				{
					if($b['forms_name'] == page_url())
					{
						$menu_link_active = ' active';
					}
				}
				$output .= '<li class="treeview '.$menu_link_active.'"><a href="#">
					<i class="fa fa-'.$a['forms_icon'].'"></i> <span>'.$a['forms_comments'].'</span>
					<span class="pull-right-container">
					  <i class="fa fa-angle-left pull-right"></i>
					</span>
				  </a>';
				foreach($result_sub as $b)
				{
					if($b['forms_icon']) $default_icon = $b['forms_icon'];
					$sql2 = "select * from forms where parent_id = ".$b["forms_id"]." order by sort, forms_comments";
					$result3 = $this->result($sql2);
					$link = $b['forms_name'];
					if(count($result_sub) > 1)
						$output .= '<ul class="treeview-menu">';
					
					$menu_link_active = '';
					if($b['forms_name'] == page_url())
					{
						$menu_link_active = ' class="active"';
					}
					$output .= '
					<li '.$menu_link_active.'>
					  <a href="'.$link.'">
						<i class="fa fa-'.$default_icon.'"></i> <span>'.$b["forms_comments"].'</span> '.(count($result3)>0?'<i class="fa fa-angle-left pull-right"></i>':'').'
					  </a>';
					 //<li><a href="documentation/index.html"><i class="fa fa-book"></i> <span>Documentation</span></a></li>

					if(count($result3))
					{
						$output .= '
						  <ul class="treeview-menu">';
						foreach ($result3 as $c)
						{
							if(@array_search($c["forms_name"], $dont_show_admin_forms) != "")
							{
								continue;
							}
							//class="active"
							if($c['forms_type'] != 'hide')
							$output .= '
								<li><a href="'.$c['forms_name'].'"><i class="fa fa-circle-o"></i> '.$c["forms_comments"].'</a></li>';
						}
						$output .= '</ul>';

					}
					$output .= '
							</li>';
					if(count($result_sub) > 1)
						$output .= '</ul>';
				}
				$output .= '
						</li>';
			}
			$output .= '</ul>';
			$output .= '';
			return $output;
		}
		//debug("$group_name: Ok Slow and Easy");
		//auto else
		if($_SESSION["groups_id"] != "")
		{
			$sql_main = "select * from forms f, groups g, forms_to_groups fg where
			g.groups_id = fg.groups_id
			AND f.forms_id = fg.forms_id
			AND fg.groups_id = ".$_SESSION["groups_id"]."
			AND f.parent_id = 0
			Order by f.sort, f.forms_comments";
			$result_main = $this->result($sql_main);
			foreach($result_main as $a)
			{
				$output .= '
				<ul class="sidebar-menu">
			        <li class="header">'.$a['forms_comments'];

				$sql_sub = "select * from forms f, forms_to_groups fg where
						f.forms_id = fg.forms_id
						AND fg.groups_id = ".$_SESSION["groups_id"]."
						AND f.parent_id = ".$a["forms_id"]."
						Order by f.sort, f.forms_comments";
				//debug($sql_sub);
				$result_sub = $this->result($sql_sub);//active

				foreach($result_sub as $b)
				{
					$sql2 = "select * from forms f, forms_to_groups fg where
							f.forms_id = fg.forms_id
							AND fg.groups_id = ".$_SESSION["groups_id"]."
							AND f.parent_id = ".$b["forms_id"]."
							Order by f.sort, f.forms_comments";
					//debug($sql2);
					$result3 = $this->result($sql2);//active
					if($a['forms_icon']) $default_icon = $a['forms_icon'];
					if($b['forms_icon']) $default_icon = $b['forms_icon'];
					$link = $b['forms_name'];
					//debug_r(strpos($b[''], 'module_');
					$output .= '
					<li class="treeview">
					  <a href="'.$link.'">
						<i class="fa fa-'.$default_icon.'"></i> <span>'.$b["forms_comments"].'</span> '.(count($result3)>0?'<i class="fa fa-angle-left pull-right"></i>':'').'
					  </a>';
					if(count($result3))
					{
						$output .= '
						<ul class="treeview-menu">';
						foreach($result2 as $c)
						{
							//debug_r($c);
							if(array_search($c["forms_name"], $dont_show_forms) != "")
							{
								continue;
							}
							if($a["group_name"] == "")
							{

							}
							$output .= '<li><a href="'.$c["forms_name"].'"><i class="fa fa-circle-o"></i> '.$c["forms_comments"].'</a></li>';
						}
						$output .= '</ul>';
					}
				}

				$output .= '</ul>';
				$output .= '	</li>';
			}
		}
		//read tps groups and users discoussion
		return $output;
	}

	function generate_perm()
	{
		//$group_name = get_name($_SESSION["groups_id"], "groups");
		$s = '';
		if($_SESSION["users_name"] == "admin")
		{
			$sql2 = "select * from forms order by forms_comments";
			$result2 = $this->result($sql2);
			foreach($result2 as $b)
				$s .= '<li><a href="'.$b["forms_name"].'">'.$b["forms_comments"].'</a></li>';
			return $s;
		}
		//debug("$group_name: Ok Slow and Easy");
		//auto else
		$sql = "select * from groups where groups_id = '".$_SESSION["groups_id"]."';";
		$result = $this->result($sql);
		foreach($result as $a)
		{
			$sql2 = "select * from forms_to_groups where groups_id = ".$a["groups_id"]. " order by sort";
			$result2 = $this->result($sql2);
			foreach($result2 as $b)
			{
				$sql3 = "select * from forms where forms_id = ".$b["forms_id"];
				$result3 = $this->result($sql3);
				foreach($result3 as $c)
				{
					if($b["forms_id"] == 83 || $b["forms_id"] == 84 || $b["forms_id"] == 86)
						continue;

					//$output .= $b["forms_id"];
					$s .= '<li><a href="'.$c["forms_name"].'">'.$c["forms_comments"].'</a></li>';
				}
			}
		}
		//read tps groups and users discoussion
		return $s;
	}



	function check_permissions()
	{
		if(!$_SESSION["users_name"] || !$_SESSION["groups_id"])
			return false;
		if($_SESSION["users_name"] == "admin")
			return true;
		$url = $_SERVER['SCRIPT_NAME'];
		//$url = $_SERVER['REQUEST_URI'];
		$url = explode('/', $url);
		$url = ($url[count($url)-1]);
		$id = ($_SESSION["users_id"]!="")?$_SESSION["users_id"]:0;
		$sql = "SELECT f.forms_name FROM forms_to_groups fg, forms f where fg.forms_id = f.forms_id and fg.groups_id = ".$_SESSION["groups_id"]." and f.forms_name = '".page_url()."'";
		// debug_r($sql);
		//$output .= $sql; return true;
		$a = $this->result($sql);
		if($a)
			return true;
		/**/
		/*for only insert right*/
		$page_url = page_url_complete();
		$page_url = str_replace("?action=insert", "?action=new", page_url_complete());
		//action=insert
		$sql = "SELECT f.forms_name FROM forms_to_groups fg, forms f where fg.forms_id = f.forms_id and fg.groups_id = ".$_SESSION["groups_id"]." and
		(
		f.forms_name = '".$page_url."'";
		/*
		if(strpos(page_url_complete(), "new_order")>1)
			$sql .= "OR f.forms_name = '".page_url()."?action=new'
		";
		*/
		$sql .= ")";
		$a = $this->result($sql);
		if($a)
			return true;

			//debug_r($sql);

		//$output .= $sql;die("") ;
		return false;
	}
}
function is_referrer()
{
	return ($_SESSION['groups_id'] == 15 ? $_SESSION['users_id'] : '');
}
function is_tutor()
{
	return ($_SESSION['groups_id'] == 13 ? $_SESSION['users_id'] : '');
}
function is_admin()
{
	return ($_SESSION['groups_id'] == 3 ? $_SESSION['users_id'] : '');
}