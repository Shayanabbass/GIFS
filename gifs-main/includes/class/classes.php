<?php
function classes()
{
	 $a = new classes();
	 $action = $_GET["action"];
	 $title = "Manage Classes";
	 $heading = '';
	 $temp = '';
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
		 case "manage_sections":
		 	list($title, $temp) = $a->manage_sections();
			//alert($title);
		 	break;
		case "delete_confirm_section":
			debug_r($_GET);
		 $id = $_GET["id"];
		 echo '<script>
			 if(confirm("Are you sure you want to delete this class?"))
			 {
				 location.href="'.page_url().'?action=delete&id='.$id.'";
			 }
			 else {
				 location.href = "'.page_url().'";
			 }
		 </script>';
		 die;
		 break;
		case "update_section":
		 $a->update_section();
			break;
 		case "insert_section":
 		 $a->insert_section();
 			break;
		case "delete_section":
		 $a->delete_section();
			break;
		case "edit_section":
		 list($title, $temp) = $a->edit_section($_GET["id"]);
			break;
		case "new_section":
		 list($title, $temp) = $a->edit_section();
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
	$data_n["html_heading"] = ($heading != "" ? $heading  : $title);
	$data_n["html_text"] = $temp;
	return $data_n;
}


class classes extends db2
{
	function insert()
	{
		$name = $_REQUEST["name"];
		$sort = (int)$_REQUEST["sort"];
		$status = (int)$_REQUEST["status"];
		$sql = 'Insert into class (name, sort, status) VALUES (\''.$name.'\', \''.$sort.'\', \''.$status.'\')';
		$result = $this->sqlq($sql);
		if($result)
		{
			alert("Class Inserted Successfully");
			redirect("?action=new");
		}
	}
	function delete()
	{
		$id = $_GET["id"];
		$sql = "update section set status = 1 where class_id = $id";
		$this->sqlq($sql);

		$sql = "update class set status = 0 WHERE id = ".$id." LIMIT 1;";
		$result = $this->sqlq($sql);
		if($result)
		{
			alert('Class deleted successfully');
			redirect(page_url());
		}
		else
		{
			alert('Error! Class delete failed');
		}
	}
	function update()
	{
		$id = $_REQUEST["id"];
		$name = $_REQUEST["name"];
		$sort = $_REQUEST["sort"];
		$status = $_REQUEST["status"];
		$id = $_GET["id"];
		$sql = "UPDATE class SET
			name='".$name."',
			sort='".$sort."',
			status='".$status."' WHERE id = ".$id;
		debug_r($sql);
		$result = $this->sqlq($sql);
		if($result)
		{
			alert("Class Updated Successfully");
			redirect(page_url());
		}
		else
		{
			alert("Class NOT Updated Successfully");
		}
	}
	function show()
	{
		$filter = " where status = 1";
		$sql = "select * from class ".$filter;
		$result = $this->sql_query($sql);
		if(count($result)==0)
		{
			$temp .= '

			<div class="grid-24">
				<div class="box">

					<div class="box-header">
						<span class="icon-info"></span>
						<h3>Class</h3>
					</div>

					<div class="box-body">
						No Records Currently present.
						<a class="btn bg-blue btn-flat margin" href="?action=new" class="text_news_date_11">New Class</a>
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
						<a class="btn bg-blue btn-flat margin" href="?action=new" class="text_news_date_11">New Class</a>
					</div>

					<div class="box-body">

						<table class="table table-bordered table-striped data-table">
					<thead>
						<tr>
						<th>Name</th>
						<th>Sort</th>
						<th>Sections</th>
						<th>Actions</th>
						</tr>
					</thead>
					<tbody>
			';
		}
		foreach($result as $a)
		{
			$id = $a["id"];
			$total = $this->result("select count(id) as total From section where class_id = $id", 1);
			$total = (int)$total["total"];
			$temp .= '
				<tr class="gradeA">
					<td>'.$a["name"].'</td>
					<td>'.$a["sort"].'</td>
					<td>'.$total.'</td>

					<td>
						<a href="?action=manage_sections&class_id='.$id.'" class="btn bg-blue btn-flat margin">
							<i class="fa fa-asterisk"></i> Manage Sections
						</a>
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
			$a = get_tuple("class", $id, "id");
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
			$name = $a["name"];
				$temp .= '
				<div class="form-group">
					<label for="name">Name:</label>
					<div class="field">
						<input type="text" name="name" id="name" size="32" class="validate[required] form-control" value="'.$name.'" />
					</div>
				</div> <!-- .form-group -->';
			$sort = $a["sort"];
				$temp .= '
				<div class="form-group">
					<label for="sort">Sort:</label>
					<div class="field">
						<input type="text" name="sort" id="sort" size="32" class="validate[required] form-control" value="'.$sort.'" />
					</div>
				</div> <!-- .form-group -->';
			$status = $a["status"];
				$temp .= '
				<div class="form-group">
					<label for="status">Status:</label>
					<div class="field">
						<input type="text" name="status" id="status" size="32" class="validate[required] form-control" value="'.$status.'" />
					</div>
				</div> <!-- .form-group -->';
		$temp .= '
			<div class="actions">
								<button type="submit" class="btn btn-primary">'.$action_label.'Class</button>
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
	function manage_sections()
	{
		$class_id = $_GET["class_id"];
		$a = get_tuple("class", $class_id, "id");
		$title = "Manage Sections for ".$a["name"];
		$filter = "where class_id = $class_id";
		$sql = "select * from section ".$filter;
		$result = $this->result($sql);
		$temp .= '
				<p align="center">
					<a href="?action=new_section&class_id='.$class_id.'" class="btn bg-blue btn-flat"><span class="icon-layers-alt"></span>New Section</a>
					<a href="'.page_url().'" class="btn bg-teal-active btn-flat"><span class="icon-layers-alt"></span>Manage Classes</a>
				</p>';
		// debug_r(count($result));
		if(count($result)==0)
		{
			$temp .= '
			<div class="grid-24">
				<div class="box">
					<div class="box-header">
						<span class="icon-info"></span>
						<h3>Manage Sections</h3>
					</div>
					<div class="box-body">
						No Records Currently present.
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
						<h3 class="icon chart">Manage Sections</h3>
					</div>
					<div class="box-body">
						<table class="table table-bordered table-striped data-table">
							<thead>
								<tr>
								<th>Full Name</th>
								<th>Section Name</th>
								<th>Class Name</th>
								<th>Added On</th>
								<th>Updated On</th>
								<th>Actions</th>
								</tr>
							</thead>
							<tbody>';
		}
		foreach($result as $a)
		{
			// debug_r($a);
			$id = $a["id"];
			$temp .= '
				<tr class="gradeA">
					<td>'.$a["full_name"].'</td>
					<td>'.$a["name"].'</td>
					<td>'.$a["class_name"].'</td>
					<td>'.$a["added_on"].'</td>
					<td>'.$a["updated_on"].'</td>

					<td>
						<a href="?action=edit_section&class_id='.$class_id.'&id='.$id.'" class="btn bg-blue btn-flat margin">
							<i class="fa fa-edit"></i> Edit
						</a>
						<a href="?action=delete_confirm_section&class_id='.$class_id.'&id='.$id.'" class="btn bg-red btn-flat margin">
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
		return array($title, $temp);
	}
	function edit_section($id = '')
	{
		$class_id = $_GET["class_id"];
		$class = get_tuple("class", $class_id, "id");
		// debug_r($a);
		$title = "New Section in ".$class["name"];

		$action = "?action=insert_section&class_id=$class_id";
		$action_label = "Insert ";
		if($id)
		{
			$title = "Edit Section in ".$class["name"];
			$action = "?action=update_section&class_id=$class_id&id=".$id;
			$action_label = "Update ";
			$a = get_tuple("section", $id, "id");
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
			$name = $a["name"];
				$temp .= '
				<div class="form-group">
					<label for="name">Name:</label>
					<div class="field">
						<input type="text" name="name" id="name" size="32" class="validate[required] form-control" value="'.$name.'" />
					</div>
				</div> <!-- .form-group -->';

		$temp .= '
			<div class="actions">
								<button type="submit" class="btn btn-primary">'.$action_label.'Section</button>
								<a href="'.page_url().'?action=manage_sections&class_id='.$class_id.'"><button type="button" class="btn">Cancel</button></a>
							</div> <!-- .actions -->
						</form>
					</div> <!-- .box-body -->
				</div>
				 <!-- .box -->
		</div>
		<!-- .grid -->

		<script>
		$(function(){
			focusAndCursor("#name");
		})
		function focusAndCursor(selector){
		  var input = $(selector);
		  setTimeout(function() {
		    // this focus on last character if input isn\'t empty
		    tmp = input.val(); input.focus().val("").blur().focus().val(tmp);
		  }, 200);
		}

		</script>';
		return array($title, $temp);
	}
	function insert_section()
	{
		$class_id = $_GET["class_id"];
		$class = get_tuple("class", $class_id, "id");
		$class_name = $class["name"];
		$section_name = $_POST["name"];
		$full_name = $class_name.' '.$section_name;
		$sql = 'Insert into section (name, class_id, class_name, full_name) VALUES (\''.$section_name.'\', \''.$class_id.'\', \''.$class_name.'\', \''.$full_name.'\')';
		$result = $this->sqlq($sql);
		if($result)
		{
			alert("Section Inserted Successfully");
			redirect(page_url()."?action=new_section&class_id=".$class_id);
		}
	}
	function update_section()
	{
		$id = $_GET["id"];
		$class_id = $_GET["class_id"];
		$class = get_tuple("class", $class_id, "id");
		$class_name = $class["name"];
		$section_name = $_POST["name"];
		$full_name = $class_name.' '.$section_name;
		$sql = 'update section
			set
				name = \''.$section_name.'\',
				full_name = \''.$full_name.'\'
			WHERE
				id = '.$id;
		// debug_r($sql);
		$result = $this->sqlq($sql);
		if($result)
		{
			alert("Section Updated Successfully");
			redirect(page_url()."?action=manage_sections&class_id=".$class_id);
		}
	}
}
