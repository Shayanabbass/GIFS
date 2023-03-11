<?php
function homework()
{
	$a = new homeworks();
	// $id = $_GET["post_id"];
	// $category_id = $_GET["category_id"];
	$action = $_GET["action"];
	if($action =="get_homework")
	{
		$a->get_homework();
	}
	$title = 'Homework';
	if($id)
	{
		// return $a->detail();
	}
	else {
		$temp = '<label for="section_id" style="text-align:left;margin: 10px 0;display: block;">Select Class(section) :</label>'.dropdown("section_id", "", " WHERE status = 1", ' onchange="update_homework()"').'
		<div id="homework_result_div"></div>';
	}
	$head = '
	<style>
	table.data-table td {
		border: thin black solid;
		padding: 5px;
	}
	</style>
	<script>
	function update_homework()
	{
		data = {section_id : $("#section_id").val()};
		jQuery.ajax({
			url: "?action=get_homework",
			method: "POST",
			data: data,
			dataType: "json"
		}).done(function( msg ) {
			if(msg.status == 1)
			{
				//alert(msg.message);
				$("#homework_result_div").html(msg.message);
			}
		});
		//alert($("#section_id").val());
	}
	</script>';
	$data_n["html_head"] = $head;
	$data_n["html_title"] = $title;
	$data_n["html_heading"] = ($heading ? $heading : $title);
	$data_n["html_text"] = $temp;
	return $data_n;
}
function homeworks()
{
	$a = new homeworks();
	$action = $_GET["action"];
	$title = "Manage  Homework";
	$heading = "";
	 switch($action)
	 {
		 case "insert":
		 	$a->insert();
			 break;
		 case "delete":
		 	$a->delete();
			 break;
		case "delete_confirm":
	 		$id = $_GET["id"];
	 		echo '<script>
	 			if(confirm("Are you sure you want to delete this event?"))
	 			{
	 				location.href="'.page_url().'?action=delete&id='.$id.'";
	 			}
	 			else {
	 				location.href = "'.page_url().'";
	 			}
	 		</script>';
			die;
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
		$data_n["html_head"] = '
		<script>
		$(function () {
			$("#confirm").live ("click", function (e) {
				e.preventDefault ();
				$.alert ({
					type: "confirm"
					, title: "Alert"
					, text: "<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do dolor sit amet, consectetur adipisicing elit, sed do.</p>"
					, callback: function () { location.href=\'test2.php?action=delete&id='.$_GET["id"].'\'; }
				});
			});
		});
		</script>';
	}
	$data_n["html_title"] = $title;
	$data_n["html_heading"] = $title;
	$data_n["html_text"] = $temp;
	return $data_n;
}
class homeworks extends db2
{
	function insert()
	{
		// debug_r($_POST);
		$name = $_REQUEST["name"];
		$dated = php_to_mysql_date($_REQUEST["dated"]);
		$content = htmlspecialchars($_REQUEST["content"], ENT_NOQUOTES);
		$section_id = $_REQUEST["section_id"];
		$section = get_tuple("section", $section_id, "id");

		$section_name = $section["name"];
		$section_full_name = $section["full_name"];
		$class_id = $section["class_id"];
		$class_name = $section["class_name"];
		$subject_id = $_REQUEST["subject_id"];
		$subject = get_tuple("subject", $subject_id, "id");
		$subject_name = $subject["name"];
		$added_by = $_SESSION["users_name"];
		$sql = '
		Insert into homework (
			name,  dated, content, section_id, section_name, section_full_name, class_id, class_name, subject_id, subject_name, added_by
		) VALUES (
			\'\', \''.$dated.'\', \''.$content.'\', \''.$section_id.'\', \''.$section_name.'\', \''.$section_full_name.'\', \''.$class_id.'\', \''.$class_name.'\', \''.$subject_id.'\', \''.$subject_name.'\', \''.$added_by.'\'
		)';
		// debug_r($sql);
		$result = $this->sqlq($sql);
		if($result)
		{
			alert("Homework Inserted Successfully");
			redirect(page_url()."?action=new");
		}
	}
	function delete()
	{
		$id = $_GET["id"];
		$sql = "DELETE FROM homework WHERE id = ".$id." LIMIT 1;";
		$result = $this->sqlq($sql);
		if($result)
		{
			alert('Homework deleted successfully');
			redirect(page_url());
		}
		else
		{
			alert('Error! Homework delete failed');
		}
	}
	function update()
	{
		$id = $_REQUEST["id"];
		$name = $_REQUEST["name"];
		$dated = php_to_mysql_date($_REQUEST["dated"]);
		$content = htmlspecialchars($_REQUEST["content"], ENT_NOQUOTES);
		$section_id = $_REQUEST["section_id"];
		$section = get_tuple("section", $section_id, "id");

		$section_name = $section["name"];
		$section_full_name = $section["full_name"];
		$class_id = $section["class_id"];
		$class_name = $section["class_name"];
		$subject_id = $_REQUEST["subject_id"];
		$subject = get_tuple("subject", $subject_id, "id");
		$subject_name = $subject["name"];
		$added_by = $_SESSION["users_name"];

		$sql = "UPDATE homework SET
			name='".$name."',
			dated='".$dated."',
			content='".$content."',
			section_id='".$section_id."',
			section_name='".$section_name."',
			section_full_name='".$section_full_name."',
			class_id='".$class_id."',
			class_name='".$class_name."',
			subject_id='".$subject_id."',
			subject_name='".$subject_name."',
			added_by='".$added_by."'
			WHERE id = ".$id;
		$result = $this->sqlq($sql);
		if($result)
		{
			alert("Homework Updated Successfully");
			redirect(page_url());
		}
		else
		{
			alert("Homework NOT Updated Successfully");
		}
	}
	function show()
	{
		$sql = "select * from homework ".$filter;
		$result = $this->result($sql);
		if(count($result)==0)
		{
			$temp .= '
			<div class="grid-24">
				<div class="box">

					<div class="box-header">
						<span class="icon-info"></span>
						<h3>Homework</h3>
					</div>

					<div class="box-body">
						No Records Currently present.
							<p align="center">
								<a class="btn bg-blue btn-flat margin" href="?action=new" class="text_news_date_11">New Homework</a>
							</p>
		            </div> <!-- .box-body -->

		        </div> <!-- .widget -->

				</div>
			';
		}
		else
		{
			$temp .= '
			<div class="grid-24"><!-- .widget -->
				<div class="box">

					<div class="box-header">
						<span class="icon-list"></span>
						<h3 class="icon chart"></h3>
						<p align="center">
							<a class="btn bg-blue btn-flat margin" href="?action=new" class="text_news_date_11">New Homework</a>
						</p>

					</div>

					<div class="box-body">

						<table class="table table-bordered table-striped data-table">
					<thead>
						<tr>
						<th>Dated</th>
						<th>Content</th>
						<th>Section Full Name</th>
						<th>Subject Name</th>
						<th>Added On</th>
						<th>Updated On</th>
						<th>Added By</th>
						<th>Actions</th>
						</tr>
					</thead>
					<tbody>
			';
		}
		foreach($result as $a)
		{
			$id = $a["id"];
			$temp .= '
				<tr class="gradeA">
					<td>'.$a["dated"].'</td>
					<td>'.$a["content"].'</td>
					<td>'.$a["section_full_name"].'</td>
					<td>'.$a["subject_name"].'</td>
					<td>'.$a["added_on"].'</td>
					<td>'.$a["updated_on"].'</td>
					<td>'.$a["added_by"].'</td>

					<td>
						<a href="?action=edit&id='.$id.'" class="btn bg-blue btn-flat margin">
							<i class="fa fa-edit"></i> Edit
						</a>
						<a href="?action=delete_confirm&id='.$id.'" class="btn bg-red btn-flat margin">
							<i class="fa fa-remove"></i> Delete
						</a>
					  </td>
					</tr>';
		}
		if(count($result)>0)
		{
			$temp .= '</tbody></table></div>
					<!--box-body-->
				</div>
				<!--box-->
			</div><!--box-->';
		}
		return $temp;
	}
	function edit($id = "")
	{
		$action = "?action=insert";
		$action_label = "Insert ";
		if($id)
		{
			$action = "?action=update&id=".$id;
			$action_label = "Update ";
			$a = get_tuple("homework", $id, "id");
		}
		$temp .= '
			<div class="col-md-12">
			<div class="box">
			<!--<div class="box-header">
				<span class="icon-article"></span>
				<h3>Basic Information</h3>
			</div>  .box-header -->
			<div class="box-body">
			<form class="form uniformForm validateForm" id="form5" name="form5" method="post" action="'.$action.'" enctype="multipart/form-data">';
			$dated = $a["dated"];
			if($dated) $dated = mysql_to_php_date($dated);
		$temp .= '
				<div class="form-group">
					<label for="dated">Dated:</label>
					<div class="field">
						<input type="text" name="dated" id="dated" size="32" class="validate[required] form-control" value="'.$dated.'" data-inputmask="\'alias\': \'dd/mm/yyyy\'" data-mask/>
					</div>
				</div> <!-- .form-group -->
				<script src="'.BASE_URL.TEMPLATE_DIR.'plugins/input-mask/jquery.inputmask.js"></script>
				<script src="'.BASE_URL.TEMPLATE_DIR.'/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
				<script src="'.BASE_URL.TEMPLATE_DIR.'/plugins/input-mask/jquery.inputmask.extensions.js"></script>
				<script>
					// $("[data-mask]")

					$(\'#dated\').datepicker({
						autoclose: true,
						format: "dd\mm\yyyy",
						setDate: new Date(),
						minDate: new Date()
					}).inputmask();
				</script>';
			$section_id = $a["section_id"];
					$temp .= '
					<div class="form-group">
					<label>Section  Name:</label>
						<div class="field">
							'.dropdown("section_id", $section_id, " WHERE status = 1").'
						</div>
					</div>';
			$subject_id = $a["subject_id"];
					$temp .= '
					<div class="form-group">
					<label>Subject  Name:</label>
						<div class="field">
							'.dropdown("subject_id", $subject_id).'
						</div>
					</div>';
			$content = $a["content"];
				$temp .= '
				<div class="form-group">
					<label for="content">Content:</label>
					<div class="field">
						<textarea name="content" id="content" size="32" class="validate[required] form-control">'.$content.'</textarea>
					</div>
				</div> <!-- .form-group -->';
		$temp .= '
			<div class="actions">
								<button type="submit" class="btn btn-primary">'.$action_label.'Homework</button>
								<a href="'.page_url().'"><button type="button" class="btn">Cancel</button></a>
							</div> <!-- .actions -->
						</form>
					</div> <!-- .box-body -->
				</div>
				 <!-- .box -->
		</div>
		<!-- .grid -->';
		return $temp;
	}
	function get_homework()
	{
		$section_id = $_POST["section_id"];
		$today = mktime(0, 0, 0, date("n"), date("d")-7, date("Y"));
		$last_date = mktime(0, 0, 0, date("n"), date("d")+1, date("Y"));
		//get 5 next days homework
		$message = '<table class="data-table">
		<thead>
			<th>Date</th>
			<th>Homework</th>
		</thead>
		<tbody>';
		$subjects = $_SESSION["data_subject"];
		$sql = "select * from homework where dated >= '".date("Y-m-d", $today)."' AND dated <= '".date("Y-m-d", $last_date)."' AND section_id = ".$section_id." ORDER BY id DESC";
		$homework = $this->result($sql);
		$sorted = [];
		foreach($homework as $a)
		{
			$sorted[mysql_to_mktime($a["dated"])][$a["subject_name"]] = $a;
		}
		$has_any_data = false;
		for($i=$last_date; $i>=$today; $i-=86400)
		{
			$main_data = '';
			$prefix_data = '<tr>
				<td>'.date("d/m/Y", $i).'</td><td>';
			foreach($subjects as $subject)
			{
				if(isset($sorted[$i]) && isset($sorted[$i][$subject["name"]]) && $sorted[$i][$subject["name"]])
				{
					$a = $sorted[$i][$subject["name"]];
					$main_data .= '<p><strong>'.$a["subject_name"].'</strong> : 	'.$a["content"]."</p>";
				}
			}
			$suffix_data = '</td>
			</tr>';

			if($main_data)
			{
				$has_any_data = true;
				$message .= $prefix_data.$main_data.$suffix_data;
			}
		}

		$message .= '</tbody>
		</table>';
		if(!$has_any_data)
		{
			$message = 'There is currently no homework for this class';
		}
		echo json_encode(array('status'=>1, 'message'=>$message));
		die;
	}
}
