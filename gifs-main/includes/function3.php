<?php
function admin_top()
{
	//alert(page_name());
	$str = "
	 \$a = new db('".page_name()."');
	 
	 if(\$_GET[\"action\"] == \"insert\")
	 {
		 \$a->insert();
	 }
	 elseif(\$_GET[\"action\"] == \"delete\")
	 {
		\$a->delete(\$condition);
	 }
	 elseif(\$_GET[\"action\"] == \"update\")
	 {
		\$a->update($condition);
	 }
	 elseif(\$_GET[\"action\"] == \"delete_confirm\")
	 {
		 
			 echo '<script type=\"text/javascript\">
					<!--
					var answer = confirm (\"Are sure you want to delete '.\$a->class_name.'?\")
					if (answer)
					{
						location.href=\''.page_url().'?action=delete&id='.\$_GET[\"id\"].'\';
					}
					else
					{
						location.href=\''.page_url().'\';
					}	
					-->
					</script>';
	 };";
	return $str;
}
function admin_middle()
{
	return "
	 \$a = new db('".page_name()."');
	 if(\$_GET[\"action\"] == \"new\" || \$_GET[\"action\"] == \"edit\")
			  {
					\$a->new1();
			  }
			  else
			  {
				  \$a->show();
			  }";
}

function checkboxes($key, $id='')
{
	//alert($ids);
	//alert($filter);
	//$key1 = explode("_", $key);
	$key2 = str_replace("_id", "", $key);
	$key3 = $key2."_name";
	///+++++++++++++++++++++++++++++++++++++++++++++
	$sql = "select * from ".$key2;
	//
	$db = new db2();
	$result = $db->sql_query($sql);
	$f = $result;
	$total_column = $result->columnCount();
	for($counter = 0; $counter < $total_column; $counter ++)
	{
		$meta = $result->getColumnMeta($counter);
		$first_column_name = $meta['name'];
		$field_name = explode("_", $first_column_name);
		
		if($field_name[count($field_name)-1] == "name")
		{	
			break;
		}
	}

	if(if_users_id($key2))
	{
		$key3 = ' Where users_id = '.$_SESSION["users_id"].' ';
	//	alert(if_users_id($key2));
	}
	else
	{
		$key3 = '';
	}
	$key3 .= ' ORDER BY '.$first_column_name;
	$sql = "select * from ".$key2.$key3;
	//echo "<br />".$sql."<br />";
	$result = $db->sql_query($sql);
	if(count($result)>0)
	{
		//echo '<label><select name="'.$key1[0].'_id" id="'.$key1[0].'_id">';
		$s = '';
	}
	foreach($result as $a)
	{
		eval("\$id = \$a[\"".$key."\"];");
		eval("\$name = \$a[\"".$first_column_name."\"];");
		//eval("\$name=\$a['".$this->class_name."_name'];");
		//debug($id);
		if($ids == $id)
		{
			$s .= '
    <label>
      <input type="checkbox" name="'.$key.'[]" value="'.$id.'" checked="checked" />'.$name.'
	  </label><br />';
		}
		else
			$s .= '<label><input type="checkbox" name="'.$key.'[]" value="'.$id.'" />'.$name.'</label><br />';
	}
	if(count($result)>0)
		$s .= '		';
	if($s == "")
	{
		$s = 'No Data Currently Available';
	}
	return $s;
}
function checkboxes2($key, $keya)
{
	//alert($ids);
	//alert($filter);
	//$key1 = explode("_", $key);
	$key2 = str_replace("_id", "", $key);
	$key2a = str_replace("_id", "", $keya);
	$key3 = $key2."_name";
	$key3a = $key2a."_name";
	///+++++++++++++++++++++++++++++++++++++++++++++
	$sql = "select * from ".$key2.'_'.$key3;
	//
	$db = new db2();
	$result = $db->sql_query($sql);
	$f = $result;
	$total_column = $result->columnCount();
	for ($counter = 0; $counter < $total_column; $counter ++) {
		$meta = $result->getColumnMeta($counter);
		$first_column_name = $meta['name'];
		$field_name = explode("_", $first_column_name);
		
		if($field_name[count($field_name)-1] == "name")
		{	
			break;
		}
	}

	if(if_users_id($key2))
	{
		$key3 = ' Where users_id = '.$_SESSION["users_id"].' ';
	//	alert(if_users_id($key2));
	}
	else
	{
		$key3 = '';
	}
	$key3 .= ' ORDER BY '.$first_column_name;
	$sql = "select * from ".$key2.$key3;
	//echo "<br />".$sql."<br />";
	$result = $db->sql_query($sql);
	if(count($result)>0)
	{
		//echo '<label><select name="'.$key1[0].'_id" id="'.$key1[0].'_id">';
		$s = '';
	}
	foreach($result as $a)
	{
		eval("\$id = \$a[\"".$key."\"];");
		eval("\$name = \$a[\"".$first_column_name."\"];");
		//eval("\$name=\$a['".$this->class_name."_name'];");
		//debug($id);
		if($ids == $id)
		{
			$s .= '
    <label>
      <input type="checkbox" name="'.$key.'" value="'.$id.'" checked="checked" />'.$name.'
	  </label><br />';
		}
		else
			$s .= '<label><input type="checkbox" name="'.$key.'[]" value="'.$id.'" />'.$name.'</label><br />';
	}
	if(count($result)>0)
		$s .= '		';
	if($s == "")
	{
		$s = 'No Data Currently Available';
	}
	return $s;
}
function return_array($table, $key, $field, $id)
{
	if(if_users_id($table))
	{
		$filter = ' AND users_id = '.$_SESSION["users_id"].' ';
	//	alert(if_users_id($key2));
	}
	$sql = "select $key from $table WHERE $field = $id".$filter;
	//echo "<br />".$sql."<br />";return;
	$db = new db2();
	$result = $db->sql_query($sql);
	$count = 0;
	foreach($result as $a)
	{
		eval("\$name = \$a[\"".$key."\"];");
		//eval("\$name=\$a['".$this->class_name."_name'];");
		$res[$count] = $name;
		$count++;
	}
	if($s == "")
	{
		$s = 'No Data Currently Available';
	}
	return $res;
}
function validate($page_name = '')
{
	if($page_name == '')$page_name = page_name();
	$sql = "SELECT * FROM ".$page_name;
	$db = new db2();
	$a= $db->sql_query($sql);
	$r =  '<script>
			function check()
			{
				';
	$total_column = $result->columnCount();
	for ($counter = 0; $counter < $total_column; $counter ++)
	{
		$meta = $result->getColumnMeta($counter);
		$first_column_name = $meta['name'];			//echo mysql_field_flags($result, $i)."<br />";
		debug_r($meta);
		$len = strlen(field_name($result, $i));
		//
		$flags = mysql_field_flags($result, $i);
		$field_name_non_split = mysql_field_name($result, $i);
		$field_name = explode("_", mysql_field_name($result, $i));
		$first_column_name = ucwords(str_replace("_", " ", $field_name_non_split));
		if(strpos($flags, "auto_increment")=="")
		{
			if(mysql_field_type($result, $i) == "blob")
			{
				$r1 .=  'if(document.getElementById("'.$field_name_non_split.'").value == "")
						{
								alert(\''.$first_column_name.' is required.\');
								return false;
						}
						';
			}
			elseif($field_name[count($field_name)-1] == "upload")
			{
				//alert($field_name_non_split);
			}
			else
			{
				
				$r .=  'if(document.getElementById("'.$field_name_non_split.'").value == "")
						{
								alert(\''.$first_column_name.' is required.\');
								return false;
						}
						';					
			}
		}
	}
	$r .=  '
		return true;
		}
	</script>';
	echo $r;
	if(has_date($page_name))
	{
		require_once('trafficbuilder/cal.php');
		/*
		echo '<script language="JavaScript" src="calendar_us.js"></script>';
		echo '<link rel="stylesheet" href="calendar.css">';
		*/
	}
}
function print_field_by_type($field_name, $field_id, $field_type_id)
{
	$r = '';
	$db = new db2();
	$type = get_type($field_type_id, "field");
	if($type == 'Text Box')
	{
		$r = '<input type="text" name="'.revert($field_name).'" id="'.revert($field_name).'" />';
	}
	elseif($type == 'Radio')
	{
		$sql = "select * from field_options where field_id = ".$field_id;
		//echo $sql;
		$result = $db->sql_query($sql);
		foreach($result as $a)
		{
			if(!isset($count))
			{
				$count = 1;
			}
			else
			{
				$count++;
			}
			$r .= '   <label>
					<input type="radio" name="'.$field_name.'[]" value="'.$a["field_options_id"].'" id="'.$field_name.$count.'" />
					'.$a["field_options_name"].'</label>
				  <br />';
		}
	}
	elseif($type == 'Date')
	{
		require_once('trafficbuilder/cal.php');
		$r = '<label>
					<input name="'.$field_name.'" type="text" id="'.$field_name.'"/>
				</label>
				<img src="images/spacer.gif" width="3" height="1" alt="" />
				
				<script language="JavaScript">
					new tcal ({
						// form name
						\'formname\': \'form4\',
						// input name
						\'controlname\': \''.$field_name.'\'
					});
				</script>';
	}
	elseif($type == 'Security Code')
	{
		alert('!!!');
		$r = '<img src="CaptchaSecurityImages.php" />';
//		$field_name, $field_id
	}
	elseif($type == 'Auto Number')
	{
		//get next id into hidden
		//$r = 'asad';
	}
	elseif($type == '')
	{
		
	}
	if($r != '')
	$r = '<tr>
                            <td width="28%">'.$field_name.'</td>
                            <td width="72%"><label>'.$r.'
                              
                            </label></td>
                          </tr>';
	return $r;
}
function get_validation($field_name, $field_id, $validation_id, $field_hint)
{
	$r = '';
	$db = new db2();
	$type = get_name($field_id, "validation");
	//
	debug("get_validation");
	debug_r($type);
	
		for($i = 0; $i < mysql_num_fields($result); $i++)
		{
			//echo mysql_field_flags($result, $i)."<br />";
			$len = strlen(field_name($result, $i));
			//
			$flags = mysql_field_flags($result, $i);
			$field_name_non_split = mysql_field_name($result, $i);
			$field_name = explode("_", mysql_field_name($result, $i));
			$fn = ucwords(str_replace("_", " ", $field_name_non_split));
			if(strpos($flags, "auto_increment")=="")
			{
				if(mysql_field_type($result, $i) == "blob")
				{
					$r1 .=  'if(document.getElementById("'.$field_name_non_split.'").value == "")
							{
									alert(\''.$fn.' is required.\');
									return false;
							}
							';
				}
				elseif($field_name[count($field_name)-1] == "upload")
				{
					//alert($field_name_non_split);
				}
				else
				{

					$r .=  'if(document.getElementById("'.$field_name_non_split.'").value == "")
							{
									alert(\''.$fn.' is required.\');
									return false;
							}
							';					
				}
			}
		}
}
function print_field($class_name, $id)
{
	$db = new db2();
	$table_names = explode("_to_", $class_name);
	$sql = "select * from $class_name where ".$table_names[1]."_id = ".get("id")." AND ".$table_names[0]."_id = ".$id;
	//if _to_ is not present
	if(count($table_names) == 1)
	{
		$table_names[1] = $class_name;
		$table_names[0] = $class_name;
		$sql = "select * from $class_name limit 0,1";
		// echo $sql;

		$a = $db->result($sql, 1);
		//
		$r = '<table cellpadding="0" cellspacing="0" border="0">';
		$num_rows = mysql_num_fields($result);
		for($i=0; $i<$num_rows; $i++)
		{
			$field_name = mysql_field_name($result, $i);
			$field_type = mysql_field_type($result, $i);
			eval("\$field_value=\$a[\"".$field_name."\"];");
			if($field_type == "int" && mysql_field_len($result, $i) == 1)
				$field_type = "bool";
			if($field_name == $table_names[0]."_id" || $field_name == $table_names[1]."_id")
				continue;
			$r .= generate_field($field_name, $field_type, '', '');
			//return $r;
		}
		return $r.'</table>';
	}
	//end of if _to_ is not present
	$a= $db->result($sql);
	//
	$r = '<table cellpadding="0" cellspacing="0" border="0">';
	$first_column_name = '';
	for ($counter = 0; $counter < $total_column; $counter ++) {
		$meta = $result->getColumnMeta($counter);
		$first_column_name = $meta['name'];
/*	for($i=0; $i<$num_rows; $i++)
	{
		$field_name = mysql_field_name($result, $i);
		$field_type = mysql_field_type($result, $i);*/
		eval("\$field_value=\$a[\"".$first_column_name."\"];");
		
		//check for boolean
		if($field_type == "int" )//&& mysql_field_len($result, $i) == 1
			$field_type = "bool";
		if($field_name == $table_names[0]."_id" || $field_name == $table_names[1]."_id")
			continue;
		
		$r .= generate_field($field_name, $field_type, $field_value, $id);
	}
	return $r.'</table>';
}

function generate_field2($field)
{
	
	if(strpos($field["name"], "_added_on")>1)
	{
		return;
	}
	//_name, $field["type"], $field["value"] = '', $id = ''
	$action_e = false;
	if($_REQUEST["action"] == "edit")
		$action_e = true;
	
	if($action_e)
		$action_e = true;
	
	if($field["type"] == 'Radio')
		$field["type"] = 'bool';
		
	if($field["type"] == "blob")
		return generate_blob($field["name"], $field["value"]);
	elseif(ucwords($field["type"]) == ucwords("upload"))
	{
		return generate_upload($field["name"], $initial_value);
	}
	elseif(strpos($field["name"], "password")>1)
	{
		return generate_password($field);
	}
	elseif(strpos($field["name"], "_id") == (strlen($field["name"])-3))
	{
		if($field["name"] == "users_id" || strpos($field["name"], "added_by")>0)
		{
			return generate_hidden($field["name"], $_SESSION["users_id"]);
		}
		elseif($field["name"] == "parent_id")
		{
			$field["name"] = get_inherits_parent($field["class"])."_id";
			return generate_parent($field);
		}
		elseif($field["name"] == "groups_id" && has_inherits(page_name()) )
		{
			$current_group = ucwords( get_inherits_parent(  page_name() )  );
			$group = get_tuple("groups", $current_group, "groups_name");
			if(!$group)
			{
				$sql_g = "insert into groups (groups_id, groups_name) Values (null, '".$current_group."');";
				$db = new db2();
				$db->sqlq($sql_g);
				$group = get_tuple("groups", $current_group, "groups_name");
				//alert("Please add this group first");
			}
			return generate_hidden($field["name"], $group["groups_id"]);
		}
		else
		{
			return generate_foreign_key($field);
		}
		//debug($field);
	}
	elseif($field["type"] == "bool")
		generate_bool($field);
	elseif($field["type"] == 'Password')
		return generate_password($field["name"], $field["value"]);
	elseif($field["type"] == 'Security Code')
		return generate_security($field["name"], $field["value"]);
	elseif($field["type"] == "int")
		return generate_number($field);
	elseif(ucwords($field["type"]) == ucwords("date"))
	{
		if($action_e)
		{
			$initial_value =  mysql_to_php_date($temp_id);
		}
		if($initial_value == "00/00/0000")
			$initial_value = '';
		else
			$initial_value = ' value="'.$initial_value.'"';
		return generate_date($field);
	}
	elseif($field["type"] == 'Auto Number')
	{
		//echo '<input name="'.revert($field["name"]).'" type="hidden" value="'.return_value('max('..')', $from ,$cond).'" />';
	}
	else
		return generate_textbox($field);
}

function print_field_2($class_name, $id)
{
	debug_r("print_field_2");
	$db = new db2();
	$field["class"] = $class_name;
	$table_names = explode("_to_", $class_name);
	
	//	debug($sql);
	//if _to_ is not present
	if(count($table_names) == 1)
	{
		
		if($_GET["action"] == "new")
			$filter = " limit 0,1";
		if($id)
		{
			$filter = "  where ".$class_name."_id = ".$id;
			if(has_inherits($class_name))
				$filter = " where ".get_inherits_parent($class_name)."_id = ".$id;
		}
		
		$sql = "select * from $class_name".$filter;
		//echo $sql;
		$a= $db->result($sql);
		//
		$r = '<table cellpadding="0" cellspacing="0" border="0">';
		$num_rows = mysql_num_fields($result);
		for($i=0; $i<$num_rows; $i++)
		{
			$field["name"] = mysql_field_name($result, $i);
			$field["type"] = mysql_field_type($result, $i);
			$field["length"] = mysql_field_len($result, $i);
			$fieldd = mysql_fetch_field($result, $i);

			
			//$sql = "select * from $class_name where ".$table_names[0]."_id = ".$id;
			if($_GET["action"] == "edit")
				eval("\$field_value=\$a[\"".$field["name"]."\"];");
			
			$field["value"] = $field_value;
			//debug($field);
			//debug($a);
			
			//alert("asad");
			
			if($field["type"] == "int" && $field["length"] == 1)
				$field["type"] = "bool";
			//upload
			if($field["type"] == "string" && strpos($field["name"], "_upload") > 1)
				$field["type"] = "upload";
			
			if($field["name"] == $table_names[0]."_id" || $field["name"] == $table_names[1]."_id")
			{
				//remove autogenrated field of parent.....parent_id
				continue;
			}
			if(has_inherits($field["class"])  && ($field["name"] == (get_inherits_parent($field["class"])."_id" ) ))
			{
				//remove autogenrated field of inherit.....client_id
				continue;
			}
			
			$r .= generate_field2($field);
			//return $r;
		}
		$r.='</table>';
		return $r;
	}
	//end of if _to_ is not present
	$a= $db->result($sql);
	//
	$r = '<table cellpadding="0" cellspacing="0" border="0">';
	$num_rows = mysql_num_fields($result);
	for($i=0; $i<$num_rows; $i++)
	{
		$field_name = mysql_field_name($result, $i);
		$field_type = mysql_field_type($result, $i);
		eval("\$field_value=\$a[\"".$field_name."\"];");
		if($field_type == "int" && mysql_field_len($result, $i) == 1)
			$field_type = "bool";
		if($field_name == $table_names[0]."_id" || $field_name == $table_names[1]."_id")
			continue;
		$r .= generate_field($field_name, $field_type, $field_value, $id);
	}
	return $r.'</table>';
}

function generate_field($field_name, $field_type, $field_value = '', $id = '')
{
	$action_e = false;
	if($field_type == 'Radio')
		$field_type = 'bool';
	if($id == '') $id = 1;
	$fn = field_to_page($field_name);

	if($field_type == "blob")
	{
		//return generate_blob();
		return '
		<tr>
			<td colspan="2">'.$fn.'</td>
		  </tr>
		  <tr>
		  <td colspan="2">
		  		<textarea id="'.$field_name.'_'.$id.'" name="'.$field_name.'_'.$id.'" rows="15" cols="40" style="width: 80%">'.htmlspecialchars_decode1($field_value).'</textarea>
				<br /><a href="javascript:toggleEditor(\''.$field_name.'_'.$id.'\');" class="black2">Toggle HTML Editor</a>
			</td>
		  </tr>
		 ';
	}
	elseif(ucwords($field_type) == ucwords("date"))
	{
		if($_GET["action"] == "edit" && !$action_e)
		{
			$initial_value = $field_value;
			$date_1 = explode("-",$initial_value);
			$initial_value =  $date_1[1].'/'.$date_1[2].'/'.$date_1[0];
			if($initial_value == "00/00/0000" || $initial_value == "//")
			{
				$initial_value = '';
			}
			else
			{
				$initial_value = ' value="'.$initial_value.'"';							
			}
		}
		require_once('trafficbuilder/cal.php');
		$cal = '
		<label>
				<input name="'.revert($field_name).'_'.$id.'" type="text" id="'.revert($field_name).'_'.$id.'" '.$initial_value.' />
			  </label><img src="images/spacer.gif" width="3" height="1" alt="" />
			  
		<script language="JavaScript">
						new tcal ({
							// form name
							\'formname\': \'form5\',
							// input name
							\'controlname\': \''.revert($field_name).'_'.$id.'\'
						});
				</script>';
		return '
		<tr>
				<td width="30%">'.$fn.'</td>
				<td width="70%">'.$cal.'
				</td>
			  </tr>';
			  //alert(mysql_field_name($result, $i));
	}
	elseif($field_name[count($field_name)-1] == "upload")
	{
		$initial_value = '';
		if($_GET["action"] == "edit" && !$action_e)
		{
			$initial_value = $temp_id.'<img src="'.$temp_id.'" height=100 width=100>';
		}
		return '<tr>
				<td width="30%">'.field_name($result, $i).'</td>
				<td width="70%"><input name="'.$field_name_non_split.'" type="file" class="form-control" id="'.$field_name_non_split.'" />
				'.$initial_value.'
				</td>
			  </tr>';
			  //alert($field_name_non_split);
	}
	elseif($field_name[count($field_name)-1] == "id")
	{
		if($field_name_non_split == "users_id" && strpos(page_name(),"_to_users")<0 && $_GET["action"] == "edit" && !$action_e)
		{
			return '<input name="'.$fn.'" type="hidden" id="'.$fn.'" value="'.$_SESSION["users_id"].'" />';
		}
		elseif(strpos($field_name_non_split, "added_by")>0)
		{
			return '<input name="'.$fn.'" type="hidden" id="'.$fn.'" value="'.$_SESSION["users_id"].'" />';
			//alert("\$id=\$f[\"".$field_name_non_split."\"];")
		}
		elseif($field_name_non_split = "parent_id")
		{
			eval("\$id=\$f[\"".$field_name_non_split."\"];");
			return '
			  <tr>
				<td width="30%">Parent</td>
				<td width="70%">'.str_replace('<select name="'.page_name().'_id" id="'.page_name().'_id">', '<select name="'.page_name().'_id" id="'.page_name().'_id"><option value="0">Root</option>', dropdown(page_name()."_id") ).'</td>
			  </tr>';
		}
		else
		{
			//print_r($f)."<br /><br />aaaaaaaaaaaa<p>&nbsp;</p>";
			eval("\$id=\$f[\"".$field_name_non_split."\"];");
			$fn = str_replace(" Id", " Name", field_name($result, $i));
			//alert("\$id=\$f[\"".$field_name_non_split."\"];");
			return '
			  <tr>
				<td width="30%">'.$fn.'</td>
				<td width="70%">'.$this->dropdown($field_name_non_split, $id).'</td>
			  </tr>';
		}
	}
	elseif($field_type == "bool")
	{
		if($field_value)
		{
			$checked = 'checked="checked" ';
		}
		return '<tr>
			   <td width="30%">'.$fn.'</td>
			  <td width="70%">
			  <table border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td>
				 <label>
				   <input type="radio" name="'.$field_name.'_'.$id.'" value="1" id="'.$field_name.'_'.$id.'"'.$checked .' />
				   Yes</label>
				<br />
				<!--
				</td>
			    <td>
				-->
				 <label>
				   <input type="radio" name="'.$field_name.'_'.$id.'" value="0" id="'.$field_name.'_'.$id.'" />
				   No</label>
				</td>
				  </tr>
				</table>
				</td>
			 </tr>';
	}
	elseif($field_type == 'Auto Number')
	{
		//alert($field_name);
		//echo '<input name="'.revert($field_name).'" type="hidden" value="'.return_value('max('..')', $from ,$cond).'" />';
	}
	elseif($field_type == 'Password')
	{
		if($field_value != '')
		{
			$field_value = ' value="'.$field_value.'"';
		}
		//echo $fn;
		return '
		  <tr>
			<td width="30%">'.$fn.'</td>
			<td width="70%"><input name="'.$field_name.'" type="password" class="inputs" id="'.$field_name.'" '.$field_value.' autocomplete="off"/></td>
		  </tr>
		 ';
	}
	elseif($field_type == 'Security Code')
	{
		if($field_value != '')
		{
			$field_value = ' value="'.$field_value.'"';
		}
		//echo $fn;
		return '

		  <tr>
			<td width="30%">'.$fn.'</td>
			<td width="70%"><br />	Type the code shown <input name="'.$field_name.'" type="text" class="inputs" id="'.$field_name.'" '.$field_value.'/><br />
			<img src="'._levelw_r.'CaptchaSecurityImages.php">
			</td>
		  </tr>
		 ';
	}
	elseif($field_type == "int")
	{
		return '
		<tr>
			<td colspan="2">'.$fn.'</td>
		  </tr>
		  <tr>
		  <td colspan="2">
				<input name="'.$field_name.'_'.$id.'" type="text" class="inputs" id="'.$field_name.'_'.$id.'" value="'.$field_value.'" onkeypress="return onlyNumbers();"/>
			</td>
		  </tr>
		 ';
	}
	else
	{
	//	continue;
		if($field_value != '')
		{
			$field_value = ' value="'.$field_value.'"';
		}
		//echo $fn;
		return '
		  <tr>
			<td width="30%"><label for="'.$field_name.'_'.$id.'">'.$fn.'</label></td>
			<td width="70%"><input name="'.$field_name.'_'.$id.'" type="text" class="form-control" id="'.$field_name.'_'.$id.'"  value="'.$field_value.'"/></td>
		  </tr>
		 ';
		 //			<td width="70%"><input name="'.$field_name.'_'.$id.'" type="text" class="form-control" id="'.$field_name.'_'.$id.'" '.$field_value.'/></td>
	}
}

function dynamic_top()
{
	//alert(page_name());
	$str = "\$a = new dynamic(".page_name().");
	if(get(\"action\") == \"new\")
	{
	\$a->insert();
	}
";
	 //echo $str;
	return $str;
}
function dynamic_middle()
{
	echo "\$a->show();";
}
function id_to_name($key, $value)
{
	//$a["country_id"]
	
	$key = str_replace("_id", "", $key);
	$id = $key.'_id';
	$name = $key.'_name';
	$temp = get_tuple($key, $value, $id);
	return $temp[$name];
}
/*
function id_to_name($field_name, $field_value)
{
	$field_name_array = explode("_", $field_name);
	$db = new db2();
	$sql = "select ".$field_name_array[0]."_id from ".$field_name_array[0]." where ".$field_name_array[0]."_name = '".$field_value."'";

	$a= $db->result($sql);
	return $a[0];
}
*/
function group_logged_in($return = true)
{
	$groups = get_tuple("groups", $_SESSION["groups_id"], "groups_id");
//	debug_r($group);
	if($return)
		return $groups["groups_name"];
	else
		echo $groups["groups_name"];
}

function db_has_table($table_name)
{	
	//Our SQL statement, which will select a list of tables from the current MySQL database.
	$sql = "SHOW TABLES";
	$db = new db2();
	$tables = $db->result($sql);
	foreach($tables as $tabs)
	{
		if(strtolower(array_pop($tabs)) == strtolower($table_name))
			return true;
	}
	return false;
}
function save_in_sessions($user)
{
	$_SESSION["GMT"] = $user["GMT"];
}
