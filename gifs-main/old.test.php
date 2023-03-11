<?php
require('includes/application_top2.php');
if(1)
{
	$sql = "TRUNCATE `home_visit`";

	$db->sqlq($sql);
	$tables = array('postcard', 'warning', 'meeting', 'strike', 'wand', 'log', 'home_visit');
	foreach($tables as $tbl)
	{
		$sql = "update pupil set {$tbl}s = 0";
		$db->sqlq($sql);
		// debug($sql);
	}
	foreach($tables as $tbl)
	{
		$sql = "select s.pupil_id, count(s.id) as total, s.status
		from $tbl s
		left join pupil p
			on p.pupil_id = s.pupil_id
			AND status = 1
			GROUP BY pupil_id";
		// debug($sql);
		$result = $db->result($sql);
		// debug($result);
		foreach($result as $a)
		{
			$pupil_id = $a['pupil_id'];
			$total 		=	$a['total'];
			// debug_r($a);
			$sql = "update pupil set {$tbl}s = $total where pupil_id = $pupil_id;";
			$db->sqlq($sql);
			// debug($sql);
			// $a['added_on'] = strtotime($a['added_on']);
			// $a['type'] = $tbl;
			// $a['status'] = $current_status;
			// $data_all[$a['added_on']][] = $a;
		}
	}

	debug_r("Done");
}
$tbl = $_GET["tbl"];
if(empty($_GET["tbl"]) || !isset($_GET["tbl"]))
	redirect("?tbl=videos");
echo '<pre>'.htmlspecialchars('
<?php
class '.$tbl.' extends db2
{');
echo '</pre>';
//echo '$temp = \''.htmlspecialchars_decode(new_generate($tbl)).'\';';
insert_generate($tbl);
delete_generate($tbl);
update_generate($tbl);
edit_generate($tbl);
show_generate($tbl);
echo '<pre>}</pre>';
function insert_generate($tbl)
{
	$tbl2 = ucwords(field_to_page($tbl));
	$s1 = '';
	$s2 = '';
	$db = new db();
	$sql = "select * from ".$tbl.' limit 0,1';
	//echo $sql; return;
	$result = $db->sql_query($sql);
	$f = mysql_fetch_array($result);
	echo '<pre>'.htmlspecialchars('
	function insert()
	{');
	for($i = 0; $i < mysql_num_fields($result); $i++)
	{
		//
		$flags = mysql_field_flags($result, $i);
		//$key
		$key = mysql_field_name($result, $i);
		$field_type = mysql_field_type($result, $i);
		if("timestamp" == $field_type)
		{
			//continue;
		}
		$fields = explode("_", $key);
		//eval("\$value = \$_REQUEST[\"$key\"];");
		//echo '<pre>'.htmlspecialchars('$value = $_REQUEST["'.$key.'"];');

		if(strpos($key, "added_by")>0)
		{
			echo htmlspecialchars('$'.$key.'  = $_SESSION["users_id"];');
		}
		elseif($fields[count($fields)-1] == "upload" || strpos($flags, "auto_increment")!="")
		{

		}
		else
		{
			//alert($key ." = ".$fields[count($fields)-1]);
			echo htmlspecialchars('
		$'.$key.' = $_REQUEST["'.$key.'"];');
		}
		$field_type = mysql_field_type($result, $i);

		if($field_type == "blob")
		{
			echo htmlspecialchars('
		if( (substr($'.$key.', 0, 3) == "<p>" ))
		{
			$temp = substr(substr($'.$key.', 3, strlen($'.$key.')), 0, strlen($'.$key.')-3);
			$'.$key.' = $temp;
			$'.$key.' = substr($'.$key.', 0, strlen($'.$key.')-4);
			$'.$key.' = htmlspecialchars($'.$key.');
		}'
		);
		}
		//check field type and act accordingly
		//users_id end
		//$s .= "$key=\"$sValue\", ";
		//
		//check if key is password
		if($fields[count($fields)-1] == "password")
		{
			echo htmlspecialchars('$'.$key.' = md5($'.$key.');');
			//alert($sValue);
		}
		//end check password
		if($field_type == "date" || strpos($key, "_on")>0)
		{
			echo htmlspecialchars('
		$'.$key.' = mysql_to_mktime($'.$key.');');/*
			echo '
			$date_1 = explode("/", $'.$key.');
			$'.$key.' = $date_1[2].\'-\'.$date_1[0].\'-\'.$date_1[1];';*/
		}
		if($fields[count($fields)-1] == "upload")
		{
			echo htmlspecialchars('
		$value = $_FILES["'.$key.'"];
		$'.$key.' = upload($value, "'.$tbl.'_images");');
		}
		$s1 .= "$key, ";
		$s2 .= "\''.\$$key.'\', ";
	}
	$s1 = substr($s1, 0, strlen($s1)-2);
	$s2 = substr($s2, 0, strlen($s2)-2);
	//continue;	return;

	echo htmlspecialchars('
		$sql = \'Insert into '.$tbl.' ('.$s1.') VALUES ('.$s2.')\';
		$result = $this->sqlq($sql);
		if($result)
		{
			alert("'.$tbl2.' Inserted Successfully");
			redirect("'.$tbl.'.php?action=new");
		}
	}
').'</pre>';
}
function edit_generate($tbl)
{
	//alert(":)");
	$db = new db();
	$tbl2 = ucwords(field_to_page($tbl));
	$name = "Update ";
	$action = "?action=up\&id='.\$id.'";
	$sql = "select * from ".$tbl.' limit 1';
	$result = $db->sql_query($sql);
	$f = mysql_fetch_array($result);
	if(mysql_num_fields($result)>0)
	{
		echo '<pre>'.htmlspecialchars('
	function edit($id = "")
	{
		$action = "?action=insert";
		$action_label = "Insert ";
		if($id)
		{
			$action = "?action=update&id=".$id;
			$action_label = "Update ";
			$a = get_tuple('.$tbl.', $id, "'.$tbl.'_id");
		}
		$temp .= \'
			<div class="grid-16">
			<div class="widget">
			<!--<div class="widget-header">
				<span class="icon-article"></span>
				<h3>Basic Information</h3>
			</div>  .widget-header -->
			<div class="widget-content">
			<form class="form uniformForm validateForm" id="form5" name="form5" method="post" action="\'.$action.\'" enctype="multipart/form-data">\';');
	}
	for($i = 0; $i < mysql_num_fields($result); $i++)
	{
		$len = strlen(field_name($result, $i));
		$flags = mysql_field_flags($result, $i);
		$field_name_non_split = mysql_field_name($result, $i);
		$fn = mysql_field_name($result, $i);
		$field_name = explode("_", $field_name_non_split);
		$field_type = mysql_field_type($result, $i);
		$key = $field_name_non_split;
		#if(strpos($flags, "auto_increment")=="")
		if(strpos($flags, "auto_increment")=="" && "timestamp" != $field_type)
		{
			echo htmlspecialchars('
			$'.$key.' = $a["'.$field_name_non_split.'"];');
			if($field_type == "blob")
			{
				echo htmlspecialchars('
			$temp .= \'
			<div class="field-group">
				<label for="services_id">'.field_name($result, $i).':</label>
				<div class="field">
					<textarea name="'.$field_name_non_split.'" id="'.$field_name_non_split.'" rows="5" cols="50" class="validate[required]" >\'.htmlspecialchars_decode($'.$key.').\'</textarea>
				</div>
			</div> <!-- .field-group -->\';');
			}
			elseif($field_type == "date" || strpos($field_name_non_split, "_on") > 0)
			{
				echo htmlspecialchars('
				if($'.$key.' == "")
				{
					$'.$key.' = time();
				}');
				$key = 'date("Y-m-d", $'.$key.')';
				$j = 1;
				echo htmlspecialchars('
			$temp .= \'
				<script>
				$(function () {
					$( "#'.$field_name_non_split.'" ).datepicker({ dateFormat: "yy-mm-dd" });
				});
				</script>
				<div class="field-group">
					<label for="'.$field_name_non_split.'">'.str_replace(ucwords($tbl), "", field_name($result, $i), $j).':</label>
					<div class="field">
						<input type="text" name="'.$field_name_non_split.'" id="'.$field_name_non_split.'" size="15" class="validate[required,custom[date]" value="\'.'.$key.'.\'"/>
						<label for="date">\'.date("Y-m-d").\'</label>
					</div>
				</div> <!-- .field-group -->\';');
			}
			elseif($field_name[count($field_name)-1] == "upload")
			{
				echo htmlspecialchars('
			$temp .= \'
				<div class="field-group inlineField">
					<label for="myfile">'.field_name($result, $i).':</label>

					<div class="field">
						<input type="file" name="'.$field_name_non_split.'" id="'.$field_name_non_split.'" />

					</div>
				</div>
						<!--<img src="\'.$'.$key.'.\'" width="200" height="200" />-->\';');
					  //alert($field_name_non_split);
			}
			elseif($field_name[count($field_name)-1] == "id")
			{
				if($field_name_non_split == "parent_id")
				{
					echo htmlspecialchars('$id = $f["'.$field_name_non_split.'"];"');
					//debug(page_name());
					echo htmlspecialchars('
					$temp .= \'
					  <tr>
						<td width="30%">Parent</td>
						<td width="70%">\'.str_replace(\'<select name="'.$tbl.'_id" id="'.$tbl.'_id">\', \'<select name="parent_id" id="parent_id"><option value="0">Root</option>\', dropdown("'.$tbl.'_id") ).\'</td>
					  </tr>');
				}
				elseif(strpos($field_name_non_split, "added_by")>0)
				{
					//alert(strpos($field_name_non_split, "added_by"));
					echo htmlspecialchars('
						$temp .= \'<input name="'.$fn.'" type="hidden" id="'.$fn.'" value="\'.$_SESSION["users_id"].\'" />\';');
					//alert("\$id=\$f[\"".$field_name_non_split."\"];")
				}
				else
				{
					//alert($);
					//print_r($f)."<br /><br />aaaaaaaaaaaa<p>&nbsp;</p>";
					//echo htmlspecialchars('$id=$f["'.$field_name_non_split.'"];');
					eval("\$id=\$f[\"".$field_name_non_split."\"];");
					$fn = str_replace("Id", " Name", field_name($result, $i));
					//alert("\$id=\$f[\"".$field_name_non_split."\"];");
					echo htmlspecialchars('
					$temp .= \'
					<div class="field-group">
					<label>'.$fn.':</label>
						<div class="field">
							\'.dropdown("'.$field_name_non_split.'", $'.$field_name_non_split.').\'
						</div>
					</div>\';');
				}
			}
			else
			{
				$j = 1;
				echo htmlspecialchars( '
				$temp .= \'
				<div class="field-group">
					<label for="services_accounts">'.str_replace(ucwords($tbl), "", field_name($result, $i), $j).':</label>
					<div class="field">
						<input type="text" name="'.$field_name_non_split.'" id="'.$field_name_non_split.'" size="32" class="validate[required]" value="\'.$'.$key.'.\'" />
					</div>
				</div> <!-- .field-group -->\';');
			}
		}
	}
	if(mysql_num_fields($result)>0)
	{
		echo htmlspecialchars( '
		$temp .= \'
			<div class="actions">
								<button type="submit" class="btn btn-primary">\'.$action_label.\''.$tbl2.'</button>
								<a href="'.$tbl.'.php"><button type="button" class="btn">Cancel</button></a>
							</div> <!-- .actions -->
						</form>
					</div> <!-- .widget-content -->
				</div>
				 <!-- .widget -->
		</div>
		<!-- .grid -->\';
		return $temp;
	}
	');
	}
	echo '</pre>';
}
function delete_generate($tbl)
{
	$tbl2 = ucwords(field_to_page($tbl));
	echo '<pre>'.htmlspecialchars('
	function delete()
	{
		$id = $_GET["id"];
		$sql = "DELETE FROM '.$tbl.' WHERE '.$tbl.'_id = ".$id." LIMIT 1;";
		$result = $this->sqlq($sql);
		if($result)
		{
			alert(\''.$tbl2.' deleted successfully\');
			redirect(\''.$tbl.'.php\');
		}
		else
		{
			alert(\'Error! '.$tbl2.' delete failed\');
		}
	}').'</pre>';
}
function show_generate($tbl)
{
	/* Get First n columns for display*/
		$db = new db();
	$sql = "select * from ".$tbl.' limit 1';
	$s = '';
	//echo $sql; return;
	$result = $db->sql_query($sql);
	$f = mysql_fetch_array($result);
	$n = 5;
	for($i = 0; $i < mysql_num_fields($result) && $i < $n; $i++)
	{
		//echo mysql_field_flags($result, $i)."<br />";
		$field["name"] = mysql_field_name($result, $i);
		$field["length"] = strlen(field_name($result, $i));
		$field["flags"] = mysql_field_flags($result, $i);
		$type = mysql_field_type($result, $i);
		$field["type"] = mysql_field_type($result, $i);

		if(strpos($field["name"], "_password") > 0 || strpos($field["name"], "added_on") > 0 || strpos($field["name"], "added_by_id") > 0)
		{
			$n++;
			continue;
		}
		//
		$r = 1;
		$temp_field_heads .= '<th>'.str_replace(ucwords($tbl).' ', '', field_name($result, $i), $r).'</th>'."\n\t\t\t\t\t\t";
		$temp_field_body .= '<td>\'.$a["'.$field["name"].'"].\'</td>'."\n\t\t\t\t\t";


	}
	/* end of get first n cols*/
	echo '<pre>';
	echo htmlspecialchars('
		function show()
		{	');

	if(checkField($tbl, $tbl."_added_by_id"))
	{
			echo '
			if($_SESSION["groups_id"] == 3 || $_SESSION["groups_id"] == 6)
			{
				$filter = "";
			}
			else
			{
			';

			echo '	$filter = " where '.$tbl.'_added_by_id = ".$_SESSION["users_id"];
			}';
	}
		$tbl2 = ucwords(field_to_page($tbl));
		echo htmlspecialchars('
		$sql = "select * from '.$tbl.' ".$filter;
		$result = $this->sql_query($sql);
		if(mysql_num_rows($result)==0)
		{
			$temp .= \'

			<div class="grid-24">
				<div class="widget">

					<div class="widget-header">
						<span class="icon-info"></span>
						<h3>'.$tbl2.'</h3>
					</div>

					<div class="widget-content">
						No Records Currently present.
						<form action="?action=new" target="_self" method="post" name="newForm">
							<p align="center">
								<button class="btn btn-quaternary btn-xlarge"><span class="icon-layers-alt"></span>New '.$tbl2.'</button>
							</p>
						</form>
		            </div> <!-- .widget-content -->

		        </div> <!-- .widget -->

				</div>
			\';
		}
		else
		{
			$temp .= \'
			<div class="grid-24"><!-- .widget -->
				<div class="widget widget-table">

					<div class="widget-header">
						<span class="icon-list"></span>
						<h3 class="icon chart">Manage '.$tbl2.'</h3>
					</div>

					<div class="widget-content">

						<table class="table table-bordered table-striped data-table">
					<thead>
						<tr>
						'.$temp_field_heads.'<th>Actions</th>
						</tr>
					</thead>
					<tbody>
			\';
		}
		while($a= mysql_fetch_array($result))
		{
			$id = $a["'.$tbl.'_id"];
			$temp .= \'
				<tr class="gradeA">
					'.$temp_field_body.'
					<td><a href="?action=edit&id=\'.$id.\'">
						<button class="btn btn-primary">Edit </button>
					  </a>
					  <script>
						$(function () {
							$("#deleteConfirm_\'.$id.\'").live ("click", function (e) {
								e.preventDefault ();
								$.alert ({
									type: "confirm"
									, title: "Delete Accounts"
									, text: "<p>Are you sure you want to delete the Accounts record?</p>"
									, callback: function () { location.href="?action=delete&id=\'.$id.\'"; }
								});
							});
						});
					 </script>
						  <a href="?action=edit&id=\'.$id.\'">
							<a href="?action=delete_confirm&id=\'.$id.\'" id="deleteConfirm_\'.$id.\'"><button class="btn btn-error">Delete</button></a>
						  </a>
						</td>
					</tr>\';
		}
		if(mysql_num_rows($result)>0)
		{
			$temp .= \'</tbody></table></div>
					<!--widget-content-->
				</div>
				<!--widget-->
			</div><!--grid-24-->\';
		}
		return $temp;
	}').'</pre>';
	}

function update_generate($tbl)
{
	$tbl2 = ucwords(field_to_page($tbl));
	$db = new db();
	$sql = "select * from ".$tbl.' limit 1';
	$s = '';
	//echo $sql; return;
	$result = $db->sql_query($sql);
	$f = mysql_fetch_array($result);

		echo '<pre>'.htmlspecialchars('
	function update()
	{');
		for($i = 0; $i < mysql_num_fields($result); $i++)
		{
		//echo mysql_field_flags($result, $i)."<br />";
		$len = strlen(field_name($result, $i));
		//
		$flags = mysql_field_flags($result, $i);
		//$key
		$key = mysql_field_name($result, $i);
		//$value
		$type = mysql_field_type($result, $i);
		//type
		$field_type = mysql_field_type($result, $i);
		if("timestamp" == $field_type)
		{
			//continue;
		}
		if($key == $tbl."_id")
		{
			continue;
		}
		echo htmlspecialchars('
		$'.$key.' = $_REQUEST["'.$key.'"];');
		//users_id end
		if($type == 'blob')
		{
		echo htmlspecialchars('
		$allowedTags=\'<p><strong><em><u><h1><h2><h3><h4><h5><h6><img>\';
		$allowedTags.=\'<li><ol><ul><span><div><br><ins><del>\';
		$'.$key.' = str_replace("�", "&rsquo;", $'.$key.');
		$'.$key.' = htmlspecialchars($'.$key.');');
		}
		elseif($type == 'date' || strpos($key, "_on")>0)
		{
			echo htmlspecialchars('
		$'.$key.' = mysql_to_mktime($'.$key.');');
				//return;
		}
		//check if key is password
		$fields = explode("_", $key);
		if($fields[count($fields)-1] == "password")
		{
			echo htmlspecialchars('$'.$key.' = md5($'.$key.');');
			//alert($sValue);
		}
		//end check password
		//check if field is name
		if($fields[count($fields)-1] == "upload")
		{
			$s = substr($s, 0, strlen($s)-2);
			echo htmlspecialchars('
		$value = $_FILES["'.$key.'"];');
					echo htmlspecialchars('
		$targetpath = "'.$tbl.'_images";
		$path = upload($value, "'.$tbl.'_images");
		$pics = ", '.$key.'=\'".$path."\'";
		if(empty($path))
		{
			$pics = \'\';
		}
				');
			$s .= "\".\$pics.\" ";
		}
		//check field type and act accordingly
		if(strpos($key, "added_by_id")>0)
		{
			echo htmlspecialchars('$'.$key.' = $_SESSION["users_id"];');
		}
		if($fields[count($fields)-1] != "upload")
			$s .= "\n\t\t\t"."$key='\".$".$key.".\"', ";
			//$kv[] = "$key=$value";
		}
		$s = substr($s, 0, strlen($s)-2);
		//debug($s);
		echo htmlspecialchars('
		$id = $_GET["id"];');

		//prevent any other updates
		if($tbl.'_id' == "users_id" && $_SESSION["users_name"] != "admin")
		{
			$id = $_SESSION["users_id"];
		}
		echo htmlspecialchars('
		$sql = "UPDATE '.$tbl.' SET  '.$s.' WHERE '.$tbl.'_id = ".$id;
		$result = $this->sqlq($sql);
		if($result)
		{
			alert("'.$tbl2.' Updated Successfully");
			redirect("'.$tbl.'.php");
		}
		else
		{
			alert("'.$tbl2.' NOT Updated Successfully");
		}
	}').'</pre>';
	return;
}

function added_by($tbl)
{
	$name = "Insert ";
	$tbl2 = ucwords(field_to_page($tbl));
	$sql = "select * from ".$tbl." limit 1";
	$db = new db();
	$result = $db->sql_query($sql);
	$f = mysql_fetch_array($result);
	if(mysql_num_fields($result)>0)
	{
		echo '<pre>'.htmlspecialchars('<form id="form5" name="form5" method="post" action="?action=insert" enctype="multipart/form-data" onsubmit="return check()">
			<table width="100%" border="0" cellspacing="0" cellpadding="5">');
	}
	for($i = 0; $i < mysql_num_fields($result); $i++)
	{
		$len = strlen(field_name($result, $i));
		$flags = mysql_field_flags($result, $i);
		$field_name_non_split = mysql_field_name($result, $i);
		$fn = mysql_field_name($result, $i);
		$field_name = explode("_", $field_name_non_split);
		$field_type = mysql_field_type($result, $i);

		if(strpos($flags, "auto_increment")=="" && "timestamp" != $field_type)
		{
		}
	}
}

function checkField($tableName,$columnName)
{
//Global variable, $db for database name, these variable are common in whole application so stored in a global variable, alternatively you can pass this as parameters of function.

//Getting table fields through mysql built in function, passing db name and table name
$tableFields = mysql_list_fields(DB_NAME, $tableName);

//loop to traverse tableFields result set
for($i=0;$i<mysql_num_fields($tableFields);$i++){

//Using mysql_field_name function to compare with column name passed. If they are same function returns 1

if(mysql_field_name($tableFields, $i)==$columnName)
return 1;
} //end of loop
} //end of function
?>
