<?php 
require('includes/application_top2.php');
if(empty($_GET["tbl"]) || !isset($_GET["tbl"]))
	redirect("?tbl=users");
	$tbl = $_GET["tbl"];
echo '<pre>';
///echo top_generate($tbl);
//echo validate_generate($tbl);
$temp_pag = '';
echo htmlspecialchars($temp_pag);
echo body_generate($tbl);
//echo new_generate($tbl);
echo '</pre>';
function htmlspecialchars_decode2($str)
{
	$str = htmlspecialchars_decode1($str);
	return $str;
	//
	$str = str_replace("<pre>", "", $str);
	$str = str_replace("</pre>", "", $str);
	$str = htmlspecialchars_decode1($str);
	return $str;
}
function generate_page()
{
	echo htmlspecialchars_decode2(top_generate($tbl));
	return;
}
function new_generate($tbl)
{
	$name = "Insert ";
	$tbl2 = strtoupper(field_to_page($tbl));
	$sql = "select * from ".$tbl." limit 1";
	$db = new db();
	$result = $db->sql_query($sql);
	$f = mysql_fetch_array($result);
	if(mysql_num_fields($result)>0)
	{
		$r = htmlspecialchars('<div class="grid-16">
		<div class="widget">
		<!--<div class="widget-header">
			<span class="icon-article"></span>
			<h3>Basic Information</h3>
		</div>  .widget-header -->
		<div class="widget-content">
		<form class="form uniformForm validateForm" id="form5" name="form5" method="post" action="?action=insert" enctype="multipart/form-data">');
	}
	for($i = 0; $i < mysql_num_fields($result); $i++)
	{
		$len = strlen(field_name($result, $i));
		$flags = mysql_field_flags($result, $i);
		$field_name_non_split = mysql_field_name($result, $i);
		$fn = mysql_field_name($result, $i);
		$field_name = explode("_", $field_name_non_split);
		$field_type = mysql_field_type($result, $i);
		if("timestamp" != $field_type)
		{
			if($field_type == "blob")
			{
				$r .= htmlspecialchars('');
				$r .= htmlspecialchars('
			<div class="field-group">
				<label for="services_id">'.field_name($result, $i).':</label>
				<div class="field">
					<textarea name="'.$field_name_non_split.'" id="'.$field_name_non_split.'" rows="5" cols="50" class="validate[required]" ></textarea>
				</div>
			</div> <!-- .field-group -->');
			}
			elseif($field_name[count($field_name)-1] == "id" && $field_type == "int")
			{
				if($field_name_non_split == "parent_id")
				{
					$r .= htmlspecialchars('$id = $f["'.$field_name_non_split.'"];"');
					//debug(page_name());
					$r .= htmlspecialchars('
					  <tr>
						<td width="30%">Parent</td>
						<td width="70%">'.str_replace('<select name="'.$tbl.'_id" id="'.$tbl.'_id">', '<select name="parent_id" id="parent_id"><option value="0">Root</option>', dropdown($tbl."_id") ).'</td>
					  </tr>');
				}
				elseif(strpos($field_name_non_split, "added_by")>0)
				{
					//alert(strpos($field_name_non_split, "added_by"));
					$r .= htmlspecialchars('<input name="'.$fn.'" type="hidden" id="'.$fn.'" value="'.$_SESSION["users_id"].'" />');
					//alert("\$id=\$f[\"".$field_name_non_split."\"];")
				}
				elseif($field_name_non_split == $tbl.'_id' || $field_name_non_split == "id")
				{
					//has $tbl_prefix
					$next_id_func = 'next_id';
					if($field_name_non_split == "id")
					{
						$next_id_func = 'next_id2';
					}
					$r .= htmlspecialchars('
			<div class="field-group">
				<label for="services_id">ID:</label>
				<div class="field">
					<input type="text" disabled="disabled" name="'.$field_name_non_split.'" id="'.$field_name_non_split.'" size="20" value="\'.'.$next_id_func.'("'.$tbl.'").\'"/>	
				</div>
			</div> <!-- .field-group -->');
					//if($field_type == "int")
				}
				else
				{
					//alert($);
					//print_r($f)."<br /><br />aaaaaaaaaaaa<p>&nbsp;</p>";
					//$r .= htmlspecialchars('$id=$f["'.$field_name_non_split.'"];');
					eval("\$id=\$f[\"".$field_name_non_split."\"];");

					$fn = str_replace("Id", " Name", field_name($result, $i));
					
					//debug_r($field_name);
					//alert("\$id=\$f[\"".$field_name_non_split."\"];");
					$r .= htmlspecialchars('
					  <tr>
						<td width="30%">'.$fn.'</td>
						<td width="70%">\'.dropdown("'.$field_name_non_split.'").\'</td>
					  </tr>');
				}
			}
			elseif($field_type == "int")
			{
				$j = 1;
				$r .= htmlspecialchars( '
				<div class="field-group">
					<label for="services_accounts">'.str_replace(ucwords($tbl), "", field_name($result, $i), $j).':</label>
					<div class="field">
						<input type="text" name="'.$field_name_non_split.'" id="'.$field_name_non_split.'" size="32" class="validate[required]" />	
					</div>
				</div> <!-- .field-group -->');
				$removed .= htmlspecialchars( '
				  <tr>
					<td width="30%">'.field_name($result, $i).'</td>
					<td width="70%"><input name="'.$field_name_non_split.'" type="text" class="inputs" id="'.$field_name_non_split.'" onkeypress="return onlyNumbers();" />							
					</td>
				  </tr>
				 ');
			}
			elseif($field_name[count($field_name)-1] == "upload")
			{
				
				$r .=  htmlspecialchars('<tr>
						<td width="30%">'.field_name($result, $i).'</td>
						<td width="70%"><input name="'.$field_name_non_split.'" type="file" class="inputs" id="'.$field_name_non_split.'" />
						

						</td>
					  </tr>');
					  //alert($field_name_non_split);
			}
			elseif($field_type == "date")
			{
				$j = 1;
				$r .= htmlspecialchars('
				<div class="field-group">
					<label for="'.$field_name_non_split.'">'.str_replace(ucwords($tbl), "", field_name($result, $i), $j).':</label>
					<div class="field">
						<input type="text" name="'.$field_name_non_split.'" id="'.$field_name_non_split.'" size="15" class="validate[required,custom[date]" />
						<label for="date">\'.date("Y-m-d").\'</label>	
					</div>
				</div> <!-- .field-group -->');
			  //alert(mysql_field_name($result, $i));
			}
			else
			{
				$j = 1;
				$r .= htmlspecialchars( '
				<div class="field-group">
					<label for="services_accounts">'.str_replace(ucwords($tbl), "", field_name($result, $i), $j).':</label>
					<div class="field">
						<input type="text" name="'.$field_name_non_split.'" id="'.$field_name_non_split.'" size="32" class="validate[required]" />	
					</div>
				</div> <!-- .field-group -->');
			}
		}
	}
	
	if(mysql_num_fields($result)>0)
	{
		$r .= htmlspecialchars( '				
				<div class="actions">						
									<button type="submit" class="btn">'.$name.ucwords($tbl).'</button>
								</div> <!-- .actions -->
							</form>
						</div> <!-- .widget-content -->
					</div>
					 <!-- .widget -->	
			</div>
			<!-- .grid -->
		');
	}
	return $r;
}
	//($tbl);

function body_generate($tbl)
{
	$tbl2 = strtoupper(field_to_page($tbl));
	$r = htmlspecialchars('<?php
	function '.$tbl.'()
	{
		 $a = new '.$tbl.'();
		 $action = $_GET["action"];
		 switch($action)
		 {
			 case "insert":
			 	$a->insert();
				 break;
			 case "delete":
			 	$a->delete();
				 break;
			 case "update":
			 	$a->update();
				 break;
			 case "delete":
			 	$a->delete();
				 break;
			 case "edit":
			 	$temp .= $a->edit($_GET["id"]);
				 break;
			 case "new":
			 	$temp .= $a->edit();
				 break;
			 case "default":
	 			$a->show();
		}
		if(!$action)
			$temp = $a->show();
		$data_n = array();
		if($action == "edit" || $action == "new")
		{
			$data_n["html_head"] = \'
			<script>
			$(function () {	
				$("#confirm").live ("click", function (e) {
					e.preventDefault ();
					$.alert ({ 
						type: "confirm"
						, title: "Alert"
						, text: "<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do dolor sit amet, consectetur adipisicing elit, sed do.</p>"
						, callback: function () { location.href=\\\''.page_url().'?action=delete&id=\'.$_GET["id"].\'\\\'; }	
					});		
				});
			});
			</script>\';
		}
		$data_n["html_title"] = "'.ucwords($tbl).'";
		$data_n["html_heading"] = "'.ucwords($tbl).'";
		$data_n["html_text"] = $temp;
		return $data_n;
	}');
	return $r;
}
//$temp = \''.htmlspecialchars_decode2(new_generate($tbl)).'\';