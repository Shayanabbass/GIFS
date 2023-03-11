<?php
function announcement()
{
    $title = "Announcement";
    $heading = "";
    $a = new announcement();
    $action = $_GET["action"];
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
		default:
			$temp .= $a->show();
    }
	// debug_r($action);
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
    $data_n["html_heading"] = ($heading ? $heading : $title);
    $data_n["html_text"] = $temp;
    return $data_n;
}
class announcement extends db2
{
	function insert()
	{
		$name = $_REQUEST["name"];
		// debug_r($_REQUEST);
		$dated = php_to_mysql_date($_REQUEST["dated"]);



		$value = $_FILES["file_upload"];
		$newval_value = $_REQUEST["newval_file_upload"];
		$path = '';
		if(!$value["name"])
		{
			if($newval_value)
			{
				$path = $newval_value;
			}
		}
		else
		{
			//debug_r($_REQUEST);
			$targetpath = "announcement_images";
			$path = upload($value, "announcement_images");
		}
		$file_upload = $path;
		if(empty($path))
		{
			$file_upload = '';
		}

		$value = $_FILES["image_upload"];
		$newval_value = $_REQUEST["newval_image_upload"];
		$path = '';
		if(!$value["name"])
		{
			if($newval_value)
			{
				$path = $newval_value;
			}
		}
		else
		{
			//debug_r($_REQUEST);
			$targetpath = "announcement_images";
			$path = upload($value, "announcement_images");
		}
		$image_upload = $path;
		if(empty($path))
		{
			$image_upload = '';
		}




		// $value = $_FILES["file_upload"];
		// $file_upload = upload($value, "announcement_images");

		// $value = $_FILES["image_upload"];
		// $image_upload = upload($value, "announcement_images");

		$type = $_REQUEST["type"][0];
		$added_by = $_SESSION["users_name"];
		$sql = 'Insert into announcement (name, dated, file_upload, image_upload, type, added_by) VALUES 
		( \''.$name.'\', \''.$dated.'\', \''.$file_upload.'\', \''.$image_upload.'\', \''.$type.'\', \''.$added_by.'\')';
		// debug_r($sql);
		$result = $this->sqlq($sql);
		if($result)
		{
			alert("Announcement Inserted Successfully");
			redirect(page_url()."?action=new");
		}
	}
	function delete()
	{
		$id = $_GET["id"];
		$sql = "DELETE FROM announcement WHERE id = ".$id." LIMIT 1;";
		$result = $this->sqlq($sql);
		if($result)
		{
			alert('Announcement deleted successfully');
			redirect(page_url());
		}
		else
		{
			alert('Error! Announcement delete failed');
		}
	}
	function update()
	{
		$id = $_REQUEST["id"];
		$name = $_REQUEST["name"];
		$dated = php_to_mysql_date($_REQUEST["dated"]);

		$value = $_FILES["file_upload"];
		$newval_value = $_REQUEST["newval_file_upload"];
		$path = '';
		if(!$value["name"])
		{
			if($newval_value)
			{
				$path = $newval_value;
			}
		}
		else
		{
			//debug_r($_REQUEST);
			$targetpath = "announcement_images";
			$path = upload($value, "announcement_images");
		}
		$pics = ", file_upload='".$path."'";
		if(empty($path))
		{
			$pics = '';
		}

		$value = $_FILES["image_upload"];
		$newval_value = $_REQUEST["newval_image_upload"];
		$path = '';
		if(!$value["name"])
		{
			if($newval_value)
			{
				$path = $newval_value;
			}
		}
		else
		{
			//debug_r($_REQUEST);
			$targetpath = "announcement_images";
			$path = upload($value, "announcement_images");
		}
		$pics2 = ", image_upload='".$path."'";
		if(empty($path))
		{
			$pics2 = '';
		}

		$type = $_REQUEST["type"][0];
		$added_by = $_SESSION["users_name"];
		$sql = "UPDATE announcement SET  
			name='".$name."', 
			dated='".$dated."' ".$pics." ".$pics2.",
			type='".$type."', 
			added_by='".$added_by."'
			WHERE id = ".$id;
		// debug_r($sql);
		$result = $this->sqlq($sql);
		if($result)
		{
			alert("Announcement Updated Successfully");
			redirect(page_url());
		}
		else
		{
			alert("Announcement NOT Updated Successfully");
		}
	}
	function show()
	{
		$name = isset($_POST["name"]) ? $_POST["name"] : "";
		$filter = [];
		if ($_GET["action"] == "search" && $name) {
			$filter[] = " name like '%".$name."%'";
		}
		$sql = "select * from announcement ".($filter ? 'WHERE' : '').implode(' AND ', $filter)." ORDER BY id desc ";
		$result = $this->result($sql);
		$temp = '
		<div class="grid-24">
			<div class="box">
				<div class="box-body">
				<p align="center">
				<a class="btn bg-blue btn-flat margin" href="?action=new" class="text_news_date_11">New Announcement</a>
			</p>

					<form method="post" action="?action=search">
						<div class="form-group col-md-12">
							<p>Search:</p>
							<label for="t-text" class="sr-only">Title</label>
							<input id="t-text" type="text" name="name" placeholder="Title..." value="'.$name.'" class="form-control" required="">
							<input type="submit" name="txt" class="mt-4 btn btn-button-7">
							'.($name ? '<input value="Reset Search" type="button" onclick="location.href=\''.page_url().'\'" class="mt-4 btn btn-button-7">' : '').'
						</div>
					</form>
				</div>
			</div>
		</div>';
		if(count($result)==0)
		{
			$temp .= '

			<div class="grid-24">
				<div class="box">
					<div class="box-body">
						No Records Currently present.
		            </div> <!-- .box-body -->
		        </div> <!-- .widget -->
			</div>';
		}
		else
		{
			$temp .= '
			<div class="grid-24"><!-- .widget -->
				<div class="box">

					<div class="box-header">
						<span class="icon-list"></span>
						<h3 class="icon chart">Manage Announcement</h3>
					</div>

					<div class="box-body">

						<table class="table table-bordered table-striped data-table">
					<thead>
						<tr>
						<th>Name</th>
						<th>Dated</th>
						<th>File Upload</th>
						<th>Image Upload</th>
						<th>Type</th>
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
					<td>'.$a["name"].'</td>
					<td>'.$a["dated"].'</td>
					<td>'.$a["file_upload"].'</td>
					<td>'.$a["image_upload"].'</td>
					<td>'.$a["type"].'</td>
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
			$a = get_tuple("announcement", $id, "id");
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
			$file_upload = $a["file_upload"];
			$temp .= '
				<div class="form-group inlineField">
					<label for="myfile">File Upload:</label>

					<div class="field">
					'.generate_upload('file_upload', $file_upload).'

					</div>
				</div>
						<!--<img src="'.$file_upload.'" width="200" height="200" />-->';
			$image_upload = $a["image_upload"];
			$temp .= '
				<div class="form-group inlineField">
					<label for="myfile">Image Upload:</label>

					<div class="field">
						'.generate_upload('image_upload', $image_upload).'

					</div>
				</div>
						<!--<img src="'.$image_upload.'" width="200" height="200" />-->';
			$type = $a["type"];
            $options = ['monthly', 'yearly'];
            $options = ['circular', 'calendar', 'akkas', 'prospectus'];
            $temp .= '
            <div class="form-group">
                <label for="type">Type:</label>
                <div class="field">
                        ';
            foreach($options as $option)
            {
				$checked = '';
				if($option == $type) $checked = ' checked="checked"';
                $temp .= '<div class="radio"><label><input name="type[]" id="type_'.$option.'" type="radio" value="'.$option.'" '.$checked.'> '.ucwords($option).'</label></div>';
            }
            $temp .= '
                        </select>
					</div>
				</div> <!-- .form-group -->';
		$temp .= '
			<div class="actions">
								<button type="submit" class="btn btn-primary">'.$action_label.'Announcement</button>
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
	function recent($type = "calendar")
	{
		$max = 6;
		if($type == "calendar") $max = 4;
		$filter = "
			type = '$type'
			ORDER BY dated DESC, id DESC
			Limit 0, $max";
		if(page_name() == 'akkas')
		{
			$filter = "
			type = '$type'
			ORDER BY dated DESC, id DESC";
		}
		$sql1 = "SELECT *, UNIX_TIMESTAMP(added_on) as po
			FROM  announcement
			WHERE $filter";
		// debug_r($sql1);
		$data = $this->result($sql1);
		// debug_r($data);
		$temp = '';
		foreach($data as $a)
		{
			$temp .= '<div class="col-3 col-md-3 col-sm-12">
				<a href="'.$a["file_upload"].'" target="_blank">
				<img
					class="card-img-top"
					src="'.$a['image_upload'].'"
					width="252"
					height="168"
					alt="'.$a["name"].'"
				/>
				</a>
				<div class="card-body">
					<p class="card-text cText">'.$a["event_on"].'</p>
					<h5 class="card-title card-changes">
						'.$a["name"].'
					</h5>
					<a href="'.$a["file_upload"].'" target="_blank" class="btn btn-success hover mt-25">View</a>
				</div>
			</div>';
		}
		// $temp = '';
		return $temp;
	}
}