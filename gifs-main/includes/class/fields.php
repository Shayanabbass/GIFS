<?php

/*
initial value -- edit option not working for
*/
function generate_upload($field_name_non_split, $initial_value = '')
{
	$temp = $initial_value;
	if($initial_value)
	{
		$initial_value = $initial_value.'<img src="'.$initial_value.'" height=100 width=100>';
	}
	return '<tr>
			<td width="30%"><label for="'.$field_name_non_split.'">'.field_to_page($field_name_non_split).'</label></td>
			<td width="70%"><input name="'.$field_name_non_split.'" type="file" class="inputs" id="'.$field_name_non_split.'" />
			<div style="height:5px"></div>
			<input placeholder="Image path on server" name="newval_'.$field_name_non_split.'" type="text" class="inputs" id="'.$field_name_non_split.'" value="'.$temp.'" />

			</td>
		  </tr>';
}

function generate_foreign_key($field)
{
	//eval("\$initial_value=\$f[\"".$field["name"]."\"];");
	//alert("\$id=\$f[\"".$field_name_non_split."\"];");
	$fn = str_replace(" Id", " Name", field_to_page($field["name"]));
	return '
	  <tr>
		<td width="30%">'.$fn.'</td>
		<td width="70%">'.dropdown($field["name"], $field["value"]).'</td>
	  </tr>';
}


function generate_blob($field_name_non_split, $initial_value = '')
{
	$temp =  '
		<tr>
			<td colspan="2">'.field_to_page($field_name_non_split).'</td>
		  </tr>
		  <tr>
		  <td colspan="2">
		  <textarea id="'.$field_name_non_split.'" name="'.$field_name_non_split.'" rows="3" cols="20" style="width: 90%;">'.htmlspecialchars_decode1($initial_value).'</textarea>';

	if($field_name_non_split == "html_text" || 1)
	{
		$temp .= '	  <br />
					<a href="javascript:toggleEditor(\''.$field_name_non_split.'\');" class="black2">Toggle HTML Editor</a>
		';
	}
	$temp .= '
			</td>
		  </tr>';
	return $temp;
}

function generate_blob2($field_name_non_split, $initial_value = '')
{
	return '
		<tr>
			<td colspan="2">'.field_to_page($field_name_non_split).'</td>
		  </tr>
		  <tr>
		  <td colspan="2">
		  <textarea id="'.$field_name_non_split.'" name="'.$field_name_non_split.'" rows="15" cols="40" style="width: 80%">'.htmlspecialchars_decode1($initial_value).'</textarea>
		  <br />
				<script>once = true; toggleEditor(\''.$field_name_non_split.'\');</script>
				<a href="javascript:toggleEditor(\''.$field_name_non_split.'\');" class="black2">Toggle HTML Editor</a>
			</td>
		  </tr>';
}

function generate_date($field)
{
	if($field["value"] != '')
	{
		$field["value"] = str_replace(" value=", '', $field["value"]);
		$field["value"] = str_replace('"', '', $field["value"]);

		if(strpos($field["value"], "/")<1)
			$field["value"] = mysql_to_php_date($field["value"]);
		$field["value"] = ' value="'.$field["value"].'"';
	}
	$cal = '
		<label>
			'.field_to_page($field["name"]).'
		</label>
		<input name="'.$field["name"].'" class="form-control inp-form" type="text" id="'.$field["name"].'" '.$field["value"].' placeholder="dd/mm/yyyy" />
		
		<script language="JavaScript">
					jQuery(\'#'.$field["name"].'\').datepicker({
						format: "d/mm/yyyy",
						weekStart: 1,
						//endDate: "infinity",
						todayBtn: "linked",
						clearBtn: true,
						autoclose: true,
						todayHighlight: true,
						toggleActive: true,
						//daysOfWeekDisabled: "0,6"
					}).on("changeDate", function(en) {
						//check_class_date();
						//if(en.date != undefined) $("#centre-class-menu").show();
					});
		</script>';
	return '
	<div class="form-group">
		'.$cal.'
	</div>';
}

function generate_hidden($field_name_non_split, $initial_value = '')
{
	return '<input name="'.$field_name_non_split.'" type="hidden" id="'.$field_name_non_split.'" value="'.$initial_value.'" />';
}


function generate_parent($field)
{
	$selected = '';
	return '<div class="form-group">
                  <label>Parent </label>
                  '.str_replace('<select name="'.$field["name"].'" id="'.$field["name"].'" class="form-control validate[required]"><option value="">Please Select</option>', '<select name="parent_id" id="parent_id" class="form-control validate[required]"><option value="0"'.$selected.'>Root</option>', dropdown($field["name"], $field["value"]) ).'                </div>';
}

function generate_dropdown($field_name_non_split, $initial_value = '')
{
		//alert("\$id=\$f[\"".$field_name_non_split."\"];");
		$fn = str_replace(" Id", " Name", field_to_page($field_name_non_split));
		$fn = str_replace(array("Gender Name", "State Name"), array("Gender", "State"), $fn);
		return '<div class="form-group">
			<label>'.$fn.'</label>
			'.dropdown($field_name_non_split, $initial_value).'
		  </div>';
}


function generate_security($field)
{
	if($field["value"] != '')
	{
		$field["value"] = ' value="'.$field["value"].'"';
	}
	return '

		  <tr>
			<td width="30%">'.field_to_page($field["name"]).'</td>
			<td width="70%"><br />	Type the code shown <input name="'.$field["name"].'" type="text" class="inputs" id="'.$field["name"].'" '.$field["value"].'/><br />
			<img src="'._levelw_r.'CaptchaSecurityImages.php">
			</td>
		  </tr>
		 ';
}

function generate_password($field)
{
	//debug($field);
	if($field["value"] != '')
		$field["value"] = ' value="'.$field["value"].'"';
	//echo $fn;
	return '<div class="form-group">
		<label>'.field_to_page($field["name"]).'</label>
		<input name="'.$field["name"].'" type="password" class="form-control inp-form" id="'.$field["name"].'" '.$field["value"].' autocomplete="off">
	</div>';
	return '
	  <tr>
		<td width="30%">'.field_to_page($field["name"]).'</td>
		<td width="70%"><input name="'.$field["name"].'" type="password" class="inp-form" id="'.$field["name"].'" '.$field["value"].' autocomplete="off"/></td>
	  </tr>
	 ';/*
	  <tr>
		<td width="30%">Repeat '.field_to_page($field["name"]).'</td>
		<td width="70%"><input name="'.$field["name"].'" type="password" class="inputs" id="'.$field["name"].'" '.$field["value"].' autocomplete="off"/></td>
	  </tr> */
}

function generate_number($field)
{
	return '
		<tr>
			<td colspan="2">'.field_to_page($field["name"]).'</td>
		  </tr>
		  <tr>
		  <td colspan="2">
				<input name="'.$field["name"].'" type="text" class="inputs" id="'.$field["name"].'" value="'.$field["value"].'" onkeypress="return onlyNumbers();"/>
			</td>
		  </tr>
		 ';
}

function generate_textbox($field)
{
	if($field["value"] != '')
	{
		$field["value"] = ' value="'.$field["value"].'"';
	}
	return '<div class="form-group">
	  <label>'.field_to_page($field["name"]).'</label>
	  <input name="'.$field["name"].'" type="text" class="form-control" placeholder="Enter ..." id="'.$field["name"].'"  '.$field["value"].'>
    </div>';
}
function generate_bool($field)
{
	if($field["value"])
	{
		$checked = 'checked="checked" ';
	}
	else
	{
		$not_checked = 'checked="checked" ';
	}
	return '<tr>
		   <td width="30%">'.field_to_page($field["name"]).'</td>
		  <td width="70%">
		  <table border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td>
			 <label>
			   <input type="radio" name="'.$field["name"].'" value="1" id="'.$field["name"].'1"'.$checked .' />
			   Yes</label>
			<br />
			<!--
			</td>
			<td>
			-->
			 <label>
			   <input type="radio" name="'.$field["name"].'" value="0" id="'.$field["name"].'2"'.$not_checked .' />
			   No</label>
			</td>
			  </tr>
			</table>
			</td>
		 </tr>';
}

function generate_yesno($title, $field_name, $field_value)
{
	$field = array(
		"name" => $field_name,
		"value" => $field_value
	);
	if($field["value"])
	{
		$checked = 'checked="checked" ';
	}
	else
	{
		$not_checked = 'checked="checked" ';
	}
	return '
	<div class="form-group">
                <label for="pupil_flag">'.$title.'</label>
                <div class="row">
                  <div class="col-lg-5">
                    <div class="radio">
                      <label>
                        <input type="radio" name="'.$field["name"].'" id="'.$field["name"].'1" value="1"'.$checked .'>
                        Yes </label>
                    </div>
                  </div>
                  <div class="col-lg-5">
                    <div class="radio">
                      <label>
                        <input type="radio" name="'.$field["name"].'" id="'.$field["name"].'2" value="0"'.$not_checked .'>
                        No </label>
                    </div>
                  </div>
                </div>
              </div>';
}
function generate_multi($table_name, $selected_ids, $field_label = '')
{
	$db = new db2();
	if($field_label == '') $field_label = field_to_page($table_name);
	$temp = '
	<label for="'.$table_name.'">'.$field_label.'</label>
	<select name="'.$table_name.'_id[]" id="'.$table_name.'_id" multiple="multiple" class="form-control validate[required]">';

	$sql = "select * from ".$table_name;
	$data_rows = $db->result($sql);

	//debug_r($data_rows);
	$selected_ids = explode(",", $selected_ids);
	foreach($data_rows as $data_row)
	{
		$id = $data_row["id"];
		$name = $data_row["name"];
		$selected = '';
		if(array_search($id, $selected_ids) !== false)
			$selected = ' selected="selected"';
		$temp .= '<option value="'.$id.'"'.$selected.'>'.$name.'</option>';
	}
	$temp .= '</select>';
	// debug_r($selected_ids);
	//echo str_replace('', '', dropdown('medical_condition', $id));
	$temp .= '
	<script>
	$("#'.$table_name.'_id").select2({
	  allowClear: false,
	  closeOnSelect: false
	});
	</script>';

	return $temp;
}
function get_multi($table_name)
{
	$table_name_ids = $_REQUEST[$table_name."_id"];
	$output = array();
	foreach($table_name_ids as $table_name_id)
	{
		$name = get_tuple_name($table_name, $table_name_id);
		$output[] = trim($name);
	}
	return array($table_name."_id"=> implode(', ', $table_name_ids), $table_name."_name" => implode(', ', $output));
}
/*

function generate_($field_name_non_split, $initial_value = '')
{
}
*/
