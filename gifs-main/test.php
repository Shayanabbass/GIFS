<?php
require('includes/application_top2.php');
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

show_generate($tbl);
edit_generate($tbl);
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
	$total_column = $result->columnCount();
	$f = $result->fetch(PDO::FETCH_ASSOC);
	echo '<pre>'.htmlspecialchars('
	function insert()
	{');


	$field_name_non_split = '';
	for ($counter = 0; $counter < $total_column; $counter ++) {
		$meta = $result->getColumnMeta($counter);
		$field_name_non_split = $meta['name'];
		$flags = $meta['flags'];
		$field_name = explode("_", $field_name_non_split);
		$key = $field_name_non_split;
		$type = $meta['native_type'];
		if("TIMESTAMP" == $field_type)
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
		elseif($field_type == "TIMESTAMP" && strpos($key, "added_on")>0)
		{
			echo htmlspecialchars('
		$'.$key.' = time();');
		}
		else
		{
			//alert($key ." = ".$fields[count($fields)-1]);
			echo htmlspecialchars('
		$'.$key.' = $_REQUEST["'.$key.'"];');
		}
		$field_type = $meta['native_type'];

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
			redirect(page_url()."?action=new");
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
	$total_column = $result->columnCount();
	$f = $result->fetch(PDO::FETCH_ASSOC);
	if($total_column>0)
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
			$a = get_tuple("'.$tbl.'", $id, "'.$tbl.'_id");
		}
		$temp .= \'
			<div class="col-md-12">
			<div class="box">
			<!--<div class="box-header">
				<span class="icon-article"></span>
				<h3>Basic Information</h3>
			</div>  .box-header -->
			<div class="box-body">
			<form class="form uniformForm validateForm" id="form5" name="form5" method="post" action="\'.$action.\'" enctype="multipart/form-data">\';');
	}
	for ($counter = 0; $counter < $total_column; $counter ++)
	{
		$meta = $result->getColumnMeta($counter);
		$field_name_non_split = $meta['name'];
		//debug_r($meta);
		$flags = $meta['flags'];
		$fn = $field_name_non_split;
		$field_name = explode("_", $field_name_non_split);
		$field_type = $meta['native_type'];
		$key = $field_name_non_split;


		#if(strpos($flags, "auto_increment")=="")
		//strpos($flags, "auto_increment")=="" &&
		if(array_search("primary_key", $flags ) === false  && "TIMESTAMP" != $field_type)
		{
			echo htmlspecialchars('
			$'.$key.' = $a["'.$field_name_non_split.'"];');
			if($field_type == "blob")
			{
				echo htmlspecialchars('
			$temp .= \'
			<div class="form-group">
				<label for="'.$field_name_non_split.'">'.field_to_page($field_name_non_split).':</label>
				<div class="field">
					<textarea name="'.$field_name_non_split.'" id="'.$field_name_non_split.'" rows="5" class="validate[required] form-control" >\'.htmlspecialchars_decode($'.$key.').\'</textarea>
				</div>
			</div> <!-- .form-group -->\';');
			}
			elseif($field_type == "date" || strpos($field_name_non_split, "_on") > 0)
			{
				echo htmlspecialchars('
				if($'.$key.' == "")
				{
					$'.$key.' = time();
				}');
				$key = 'date("d/m/Y", $'.$key.')';
				$j = 1;
				echo htmlspecialchars('
			$temp .= \'
				<script>
				$(function () {
					$( "#'.$field_name_non_split.'" ).datepicker({ format: "dd/mm/yyyy" });
				});
				</script>
				<div class="form-group">
					<label for="'.$field_name_non_split.'">'.str_replace(ucwords($tbl), "", field_to_page($field_name_non_split)).':</label>
					<div class="field">
						<input type="text" name="'.$field_name_non_split.'" id="'.$field_name_non_split.'" size="15" class="validate[required,custom[date] form-control" value="\'.'.$key.'.\'"/>
					</div>
				</div> <!-- .form-group -->\';');
			}
			elseif($field_name[count($field_name)-1] == "upload")
			{
				echo htmlspecialchars('
			$temp .= \'
				<div class="form-group inlineField">
					<label for="myfile">'.field_to_page($field_name_non_split).':</label>

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
					$fn = str_replace("Id", " Name", field_to_page($field_name_non_split));
					//alert("\$id=\$f[\"".$field_name_non_split."\"];");
					echo htmlspecialchars('
					$temp .= \'
					<div class="form-group">
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
				<div class="form-group">
					<label for="'.$field_name_non_split.'">'.str_replace(ucwords($tbl), "", field_to_page($field_name_non_split)).':</label>
					<div class="field">
						<input type="text" name="'.$field_name_non_split.'" id="'.$field_name_non_split.'" size="32" class="validate[required] form-control" value="\'.$'.$key.'.\'" />
					</div>
				</div> <!-- .form-group -->\';');
			}
		}
	}
	if($total_column>0)
	{
		echo htmlspecialchars( '
		$temp .= \'
			<div class="actions">
								<button type="submit" class="btn btn-primary">\'.$action_label.\''.$tbl2.'</button>
								<a href="\'.page_url().\'"><button type="button" class="btn">Cancel</button></a>
							</div> <!-- .actions -->
						</form>
					</div> <!-- .box-body -->
				</div>
				 <!-- .box -->
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
			redirect(page_url());
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
	$total_column = $result->columnCount();
	$f = $result->fetch(PDO::FETCH_ASSOC);
	$n = 5;

	$field_name_non_split = '';
	for ($counter = 0; $counter < $total_column; $counter ++) {
		$meta = $result->getColumnMeta($counter);
		$field_name_non_split = $meta['name'];
		$field["name"] = $field_name_non_split;
		$field["length"] = strlen($field_name_non_split);
		$field["flags"] = $meta['flags'];
		$type = $meta['native_type'];
		$field["type"] = $meta['native_type'];

		if(strpos($field["name"], "_password") > 0 || strpos($field["name"], "added_on") > 0 || strpos($field["name"], "added_by_id") > 0 || $field_name_non_split == "id"
			|| $field_name_non_split == $tbl.'_id')
		{
			$n++;
			continue;
		}
		//
		$r = 1;
		$temp_field_heads .= '<th>'.str_replace(ucwords($tbl).' ', '', field_to_page($field["name"]), $r).'</th>'."\n\t\t\t\t\t\t";
		$temp_field_body .= '<td>\'.$a["'.$field["name"].'"].\'</td>'."\n\t\t\t\t\t";


	}
	/* end of get first n cols*/
	echo '<pre>';
	echo htmlspecialchars('
		function show()
		{	');

	// if(checkField($tbl, $tbl."_added_by_id"))
	// {
	// 		echo '
	// 		if($_SESSION["groups_id"] == 3 || $_SESSION["groups_id"] == 6)
	// 		{
	// 			$filter = "";
	// 		}
	// 		else
	// 		{
	// 		';

	// 		echo '	$filter = " where '.$tbl.'_added_by_id = ".$_SESSION["users_id"];
	// 		}';
	// }
		$tbl2 = ucwords(field_to_page($tbl));
		echo htmlspecialchars('
		$sql = "select * from '.$tbl.' ".$filter;
		$result = $this->result($sql);
		if(count($result)==0)
		{
			$temp .= \'

			<div class="grid-24">
				<div class="box">

					<div class="box-header">
						<span class="icon-info"></span>
						<h3>'.$tbl2.'</h3>
					</div>

					<div class="box-body">
						No Records Currently present.
							<p align="center">
								<a class="btn bg-blue btn-flat margin" href="?action=new" class="text_news_date_11">New '.$tbl2.'</a>
							</p>
		            </div> <!-- .box-body -->

		        </div> <!-- .widget -->

				</div>
			\';
		}
		else
		{
			$temp .= \'
			<div class="grid-24"><!-- .widget -->
				<div class="box">

					<div class="box-header">
						<span class="icon-list"></span>
						<h3 class="icon chart">Manage '.$tbl2.'</h3>
					</div>

					<div class="box-body">

						<table class="table table-bordered table-striped data-table">
					<thead>
						<tr>
						'.$temp_field_heads.'<th>Actions</th>
						</tr>
					</thead>
					<tbody>
			\';
		}
		foreach($result as $a)
		{
			$id = $a["'.$tbl.'_id"];
			$temp .= \'
				<tr class="gradeA">
					'.$temp_field_body.'
					<td>
						<a href="?action=edit&id=\'.$id.\'" class="btn bg-blue btn-flat margin">
							<i class="fa fa-edit"></i> Edit
						</a>
						<a href="?action=delete_confirm&id=\'.$id.\'" class="btn bg-red btn-flat margin">
							<i class="fa fa-remove"></i> Delete
						</a>
					  </td>
					</tr>\';
		}
		if(count($result)>0)
		{
			$temp .= \'</tbody></table></div>
					<!--box-body-->
				</div>
				<!--box-->
			</div><!--box-->\';
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
	$total_column = $result->columnCount();
	$f = $result->fetch(PDO::FETCH_ASSOC);
	echo '<pre>'.htmlspecialchars('
	function update()
	{');
	$field_name_non_split = '';
	for ($counter = 0; $counter < $total_column; $counter ++) {
		$meta = $result->getColumnMeta($counter);
		$field_name_non_split = $meta['name'];
		$flags = $meta['flags'];
		$field_name = explode("_", $field_name_non_split);
		$key = $field_name_non_split;
		$type = $meta['native_type'];
		//debug($type);
		//type
		$field_type = $meta['native_type'];
		if("TIMESTAMP" == $field_type)
		{
			//continue;
		}
		if($key == $tbl."_id")
		{
			continue;
		}
		if($field_type != "TIMESTAMP" || strpos($key, "added_on") === false)
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
		elseif($field_type == "TIMESTAMP" && strpos($key, "added_on")>0)
		{
			echo htmlspecialchars('
		$'.$key.' = time();');
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
			redirect(page_url());
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
	$total_column = $result->columnCount();
	$f = $result->fetch(PDO::FETCH_ASSOC);
	if($total_column>0)
	{
		echo '<pre>'.htmlspecialchars('<form id="form5" name="form5" method="post" action="?action=insert" enctype="multipart/form-data" onsubmit="return check()">
			<table width="100%" border="0" cellspacing="0" cellpadding="5">');
	}

	$field_name_non_split = '';
	for ($counter = 0; $counter < $total_column; $counter ++) {
		$meta = $result->getColumnMeta($counter);
		$field_name_non_split = $meta['name'];
		$flags = $meta['flags'];
		$field_name = explode("_", $field_name_non_split);
		$key = $field_name_non_split;
		$type = $meta['native_type'];

		$fn = $field_name_non_split;
		$field_type = $type;

		if(strpos($flags, "auto_increment")=="" && "TIMESTAMP" != $field_type)
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
debug_r("CheckField");
if(mysql_field_to_page($tableFields, $i)==$columnName)
return 1;
} //end of loop
} //end of function
?>
