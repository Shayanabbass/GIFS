<?php 
function phpmyadmin()
{
	//debug($_POST);
	$data_n = array();
	/*
	$data_n["html_title"] = "title ";
	$data_n["html_heading"] = "heading ";
	$data_n["html_text"] = "text ";
	*/
	
	
	$data_n["html_heading"] = '<a href="'.page_url().'">Manage Tables</a>';
	$data_n["html_title"] = "Manage Tables";
	$data_n["html_text"] = "";
	//simple_input
	//Step 4		------------		Execute SQL Input..and display result ..
	if($_POST["action"] == "new_sql")
	{
		$data_n["html_heading"] .= '<form action="" method="post" class="simple_input_white">
								<input type="submit" value=" &gt;&gt; SQL Query">
								<input type="hidden" name="action" value="sql">
							</form>';
		
		//exec
		$db = new db2();
		$sql = $_POST["query"];
		$result = $db->sql($sql);
		
		if(!$result)
		{
			$data_n["html_text"] = color("red").'Error '.mysql_errno().': '.mysql_error().color_end();
		}
		else
		{
			if(strpos($sql, " from ") > 0)
			{
				$flag = "true1";
				$color1 = 'green';
				$color2 = 'blue';
				foreach($result as $a)
				{
					//first time
					if($flag == "true1")
					{
						$data_n["html_text"] .= '<table width="400" borders="0" cellpadding="5" cellspacing="0" class="display" id="example" > 
											<thead> 
											  <tr>';
						for($i=0; $i< mysql_num_fields($result); $i++)
						{
							$data_n["html_text"] .= '<th>'.mysql_field_name($result, $i).'</th>';
						}
						$data_n["html_text"] .= '	</tr>
												</thead>
												<tbody>';
					}
					
					$data_n["html_text"] .= '<tr>';
					if($flag == "true")
					{
						$data_n["html_text"] .= color($color1);
						$flag = "nottrue";
					}
					else
					{
						$data_n["html_text"] .= color($color2);
						$flag = "true";
					}
					for($i=0; $i< mysql_num_fields($result); $i++)
					{
						$data_n["html_text"] .= '<td>';
						$data_n["html_text"] .= $a[$i];
						$data_n["html_text"] .= '</td>';
					}
					$data_n["html_text"] .= color_end().'</tr>';
				}
				$data_n["html_text"] .= '</table>';
			}
			else
				$data_n["html_text"] = color("blue").'Query Successful'.color_end();
		}

		$data_n["html_text"] .= '
							<form action="" method="post" class="simple_input">
								<input type="submit" value="Edit Query">
								<input type="hidden" name="query" value="'.$sql.'">
								<input type="hidden" name="action" value="sql">
							</form>
							<br />';
		
		return $data_n;
		
	}
	//Step 4		------------		Show SQL Input...
	if($_POST["action"] == "sql")
	{
		$data_n["html_heading"] .= ' &gt;&gt; SQL Query';
		
		$data_n["html_text"] = '<form action="" method="post" >
								<label>
								<textarea name="query" cols="50" rows="10" id="query">'.$_POST["query"].'</textarea>
								</label><br />
								<input type="submit" value="Submit">
								<input type="hidden" name="action" value="new_sql"></form>';
		return $data_n;
	}
	
	//Step 2.5		------------		Show all filed options...
	if($_POST["action"] == "field_edit")
	{
		$data_n["html_heading"] .= ' &gt;&gt; Edit Field';
		//recognize field that is sent
		$data_n["html_text"] .= $_POST["table"].' -&gt; '.$_POST["field"];
		/*
		$sql = "describe ".(revert($_POST["name"]));
		$db = new db2();
		$result = $db->sql_query($sql);
		*/
		$data_n["html_text"] .= '';
		return $data_n;
	}
	//Step 2		------------		Show all tables...
	if($_POST["action"] == "edit")
	{
		$data_n["html_heading"] .= ' &gt;&gt; Edit '.$_POST["name"];
		
		$sql = "describe ".(revert($_POST["name"]));
		$db = new db2();
		$result = $db->sql_query($sql);
		$data_n["html_text"] .= '<table width="400" borders="0" cellpadding="5" cellspacing="0" class="display" id="example" > 
					<thead> 
					  <tr> 
						<th>Field</th>
							<th class="black">Type</th>
							<th class="black">Null</th>
							<th class="black">Key</th>
							<th class="black">Default</th>
							<th class="black">Action</th>
						  </tr> 
					</thead> 
					<tbody>';
		foreach($result as $a)
		{
			$data_n["html_text"] .= '
						  <tr>
							<td>'.$a["Field"].'</td>
							<td>'.$a["Type"].'</td>
							<td>'.$a["Null"].'</td>
							<td>'.$a["Key"].'</td>
							<td>'.$a["Default"].'</td>
							<td>
								<form action="" method="post" class="simple_input">
								<input type="submit" value="Edit">
								<input type="hidden" name="name" value="Edit">
								<input type="hidden" name="field" value="'.$a["Field"].'">
								<input type="hidden" name="table" value="'.$_POST["name"].'">
								<input type="hidden" name="action" value="field_edit">
							</form>
							</td>
						  </tr>';
		}
		$data_n["html_text"] .= '</tbody>
						</table>';
		return $data_n;
	}
	//Step 1		------------		Show all tables...
	$data_n["html_text"] = '';
	$data_n["html_title"] = "Manage Tables";
	$data_n["html_heading"] = "Manage Tables";
	if(count($_POST) == 0)
	{
		$sql = "show tables";
		$db = new db2();
		$result = $db->sql_query($sql);
		$data_n["html_text"] .= '
			<form action="" method="post" class="simple_input">
			<input type="submit" value="&gt;&gt;Run SQL Query">
			<input type="hidden" name="action" value="sql">
			</form>
		<br /><br />	
			<table width="400" borders="0" cellpadding="5" cellspacing="0" class="display" id="example" > 
					<thead> 
					  <tr>
						<th><strong>Table </strong></th>
						<th><strong>Action</strong></th>
						<th><strong>Records</strong></th>
					  </tr>
					  </thead> 
					<tbody>
					  ';
		foreach($result as $a)
		{
			$sql2 = "Select count(*) as coun from ".($a[0]);
			$records = $db->result($sql2, 1);
			
			$data_n["html_text"] .= '
					  <tr>
						<td>'.field_to_page($a["0"]).'</td>
						<td><form action="" method="post" class="simple_input">
			<input type="submit" value="Edit">
			<input type="hidden" name="name" value="'.field_to_page($a["0"]).'">
			<input type="hidden" name="action" value="edit">
			</form></td>
						<td>'.$records.'</td>
					  </tr>
					  ';
		}
		$data_n["html_text"] .= '
					</tbody>
					</table><br />
<br />
';
	}
	return $data_n;
}
?>