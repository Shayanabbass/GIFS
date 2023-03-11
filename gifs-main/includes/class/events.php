<?php
function events()
{
	$db = new event();
	$data_n = array();
	$heading = 'Events';
	$data_n = array();
	$action = $_GET['action'];
	if($action == 'insert')
	{
		$db->insert();
	}
	if($action == 'delete')
	{
		$db->delete();
	}
	elseif($action == "delete_confirm")
	{
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
	}
	elseif($action == 'update')
	{
		$db->update();
	}
	elseif($action == 'edit_event' || $action == 'new')
	{
		$head = '<script src="js/dropzone.js" type="text/javascript" language="javascript"></script>
			<link href="css/dropzone.css" type="text/css" rel="stylesheet" />
			<script>
				var is_loading = false;
				var uploaded_big 	= [];
				var uploaded_small 	= [];
				var uploaded_thumb 	= [];
				var uploaded_name 	= [];
				var myDropzone;
				function update_inputs()
				{
					$("#files_uploaded_big").val(uploaded_big.join(\',\'));
					$("#files_uploaded_small").val(uploaded_small.join(\',\'));
					$("#files_uploaded_thumb").val(uploaded_thumb.join(\',\'));
				}
				$(function(){
					myDropzone = new Dropzone("div#dropzone", {
					url: "file_upload.php?action=upload&id=2",
					init: function() {
							this.on("success", function(files, response) {
								try
								{
									response = $.parseJSON(response);
									uploaded_big.push(response.image_big);
									uploaded_small.push(response.image_small);
									uploaded_thumb.push(response.image_thumb);
									uploaded_name.push(files.name);
									update_inputs();
								}
								catch(ex)
								{
									alert(ex.message);
								}
								// Gets triggered when the files have successfully been sent.
								// Redirect user or notify of success.
							});
							this.on("errormultiple", function(files, response) {
							  // Gets triggered when there was an error sending the files.
							  // Maybe show form again, and notify user of error
							  console.log(response);
							});
						}//init ends
					});

				  myDropzone.on("addedfile", function(file) {
					  value = "";
					  if(is_loading)
						  value = file.size;
					  var sortInput = Dropzone.createElement("<input size=\"2\" type=\"text\" id=\"sort"+file.size+"\" value=\""+value+"\" name=\"sort[]\" placeholder=\"sort\">");
					  var removeButton = Dropzone.createElement("<button>Remove file</button>");

						// Capture the Dropzone instance as closure.
						var _this = this;

						// Listen to the click event
						removeButton.addEventListener("click", function (e) {
							// Make sure the button click doesn\'t submit the form:
							e.preventDefault();
							e.stopPropagation();
							// Remove the file preview.
							var index = -1;
							for(i=0;i<uploaded_name.length;i++)
							{
							   if(uploaded_name[i] == file.name)
							   {
								   index = i;
								   break;
							   }
							}
							if (index > -1) {
								uploaded_big.splice(index, 1);
								uploaded_small.splice(index, 1);
								uploaded_thumb.splice(index, 1);
								uploaded_name.splice(index, 1);
							}
							update_inputs()
							_this.removeFile(file);
							// If you want to the delete the file on the server as well,
							// you can do the AJAX request here.
						});

						// Add the button to the file preview element.
						file.previewElement.appendChild(sortInput);
						file.previewElement.appendChild(Dropzone.createElement("<p></p>"));
						file.previewElement.appendChild(removeButton);
					  //debugger;
					/* Maybe display some more file information on your page */
				  });
				});
		</script>';
		$temp .= $db->edit($_GET['event']);
	}
	else
	{
		$heading = 'Manage Events';
		$temp .= $db->showall();
	}
	$head .= '<style>
	.datatable
	{

	}
	.datatable tr:hover td
	{
		background:#ccc;
	}
	.datatable td
	{
		border:black thin solid;
		padding:5px;
	}
	.dropzone2.dz-clickable input
	{
		margin:5px 0 10px;
	}
	</style>';
	$data_n["head"] = $head;
	$data_n["html_title"] = $heading;
	$data_n["html_heading"] = $heading;
	$data_n["html_text"] = '<div class="col-md-12">'.$temp.'</div>';
	return $data_n;
}
class event extends db2
{
	function insert()
	{
		$sort = $_REQUEST['sort'];
		$files_uploaded_big 	= explode(",", $_POST['files_uploaded_big']);
		$files_uploaded_small 	= explode(",", $_POST['files_uploaded_small']);
		$files_uploaded_thumb 	= explode(",", $_POST['files_uploaded_thumb']);

		$topic 		= $_POST['title'];
		// $date 		= $_POST['date'];
		$date = php_to_mysql_date($_REQUEST["date"]);
		// if($date == "") $date = '2022-11-09';
 		// debug_r($_REQUEST);
		$directory 	= 'events/';
		$pic_large	= upload($_FILES['pic_large'], $directory.'largepics');
		if($pic_large)
		{
			$fn			= explode("/", $pic_large);
			$fn			= $fn[2];
			$image = new SimpleImage();
			$image->load($pic_large);
			$image->resizeToWidth(295);
			$image_small = $directory.'smallpics/small-'.$fn;
			$image->save($image_small);
		}
		//
		$events_id 	= next_id('events');
		//$sql = "INSERT INTO events VALUES ('".$topic."'., '".$sql1."' ,'".$sql2."', '".$description."', '".$date."');";
		$sql = "INSERT INTO events (events_id, event_name , image_big , image_small, event_date )
				VALUES (
					$events_id, '".$topic."', '".$pic_large."', '".$image_small."', '".$date."'
				);";
		// echo "<font color=black>".$sql."</font><br />";
		$this->sqlq($sql);
		$previous_sort = 1;
		for($i=0;$i<count($files_uploaded_big);$i++)
		{
			$event_image_big = $files_uploaded_big[$i];
			$event_image_small = $files_uploaded_small[$i];
			$event_image_thumb = $files_uploaded_thumb[$i];
			$temp_sort = $sort[$i];
			if(!$temp_sort) $temp_sort = $previous_sort;
			$previous_sort = $i+2;
			$sql = "insert into event_images (events_id, event_image_small, event_image_large, event_image_thumb, sort) VALUES
			($events_id, '$event_image_small', '$event_image_big', '$event_image_thumb', $temp_sort) ";
			$this->sqlq($sql);
			// debug($sql);
			//$file_uploaded
		}
		redirect("events.php");
		die;
	}
	function showall()
	{
		$sql = "SELECT *, UNIX_TIMESTAMP(event_date) as event_date FROM events ORDER BY event_date DESC";
		$result = $this->result($sql);
		$temp = '<div class="box box-solid">
    <div class="box-header with-border">
      <i class="fa fa-text-width"></i>

      <h3 class="box-title">Manage Events</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">';
		$temp .= ahref('Add New', '?action=new');
		$temp .= '
		<table cellpadding="0" cellspacing="0" border="0" class="datatable table table-bordered">
		<thead>
			<th>ID</th>
			<th>Event</th>
			<th>Date</th>
			<th>Action</th>
		</thead>
		<tbody>';
		foreach($result as $a)
		{
			$temp .= '<tr>
				<td>'.$a['events_id'].'</td>
				<td>'.$a['event_name'].'</td>
				<td>'.date('j F Y ', $a['event_date']).'</td>
				<td>
					<a class="btn bg-blue btn-flat margin" href="?action=edit_event&event='.$a['events_id'].'">
						<i class="fa fa-edit"></i> Edit</a>
					&nbsp;
					<a class="btn bg-red btn-flat margin" href="?action=delete_confirm&id='.$a['events_id'].'"><i class="fa fa-remove"></i> Delete</a>
				</td></tr>';
		}
		$temp .= '
			</tbody>
		</table>';
		$temp .= '
				</div>
				<!-- /.box-body -->
			</div>';
		return $temp;
	}
	function show()
	{
		$sql = "SELECT * FROM events ORDER BY event_date DESC";
		$result = $this->result($sql);
		$temp1 = '<div class="box box-solid">
    <div class="box-header with-border">
      <i class="fa fa-text-width"></i>

      <h3 class="box-title">Manage Events</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">';
		foreach($result as $a)
		{
			$temp .= '
			<table width="100%" border="0" cellspacing="0" cellpadding="5">
			  <tr>
				<td class="heading_white">'.$a['title'].'<br />
				  Dated : '.$a['date'].'</td>
			  </tr>
			  <tr>
				<td class="heading_white"><a href="'.$a['image_big'].'" target="_blank"><img src="'.$a['image_small'].'" alt="'.$a['title'].'" width="160" height="100" border="0" align="right" /></a>'.$a['description'].'</td>
			  </tr>

			  <tr>
				<td>&nbsp;</td>
			  </tr>
			</table>';
		}
		$temp1 .= '
				</div>
				<!-- /.box-body -->
			</div>';
			echo $temp;
	}
	function delete()
	{
		if(!db2::$db->inTransaction())
			db2::$db->beginTransaction();

		$id = $_GET["id"];
		$event = get_tuple("events", $id, "events_id");
		if($event["image_big"]) unlink($event["image_big"]);
		if($event["image_small"]) unlink($event["image_small"]);

		$sql = "SELECT *
			FROM event_images
			WHERE events_id = '".$id."' ";
		$result = $this->result($sql);

		foreach($result as $a)
		{
			if($event["event_image_small"]) unlink($a["event_image_small"]);
			if($event["event_image_large"]) unlink($a["event_image_large"]);
			if($event["event_image_thumb"]) unlink($a["event_image_thumb"]);
		}

		$sql = "DELETE FROM event_images  WHERE events_id = ".$id;
		$this->sqlq($sql);

		$sql = "DELETE FROM events WHERE events_id = ".$id." LIMIT 1;";
		$this->sqlq($sql);

		if(DEBUG)
		{
			debug($sql);
			db2::$db->rollBack();return;
		}
		else
		{
			alert("Event deleted successfully");
			db2::$db->commit();
			redirect(page_url());
		}
		die;
	}
	function edit($id = '')
	{
		$action_label = "New";
		if($id != '')
		{
			$action = 'update&id='.$id;
			$sql = "SELECT *
					FROM events
					WHERE events_id = '".$id."' ";
			$a = $this->result($sql, 1);


			$sql = "SELECT *
					FROM event_images
					WHERE events_id = '".$id."' ";
			$result = $this->result($sql);
			$head = '
			<style>
			.heading_white, .link_White_bold
			{
				padding:5px;
			}
			</style>
			<script>
			var temp_big 	= [];
			var temp_small	= [];
			var temp_thumb	= [];
			$(function(){
				is_loading = true;
			';

			foreach($result as $b)
			{
				$thumb = $b["event_image_thumb"];
				$head .= 'var mockFile = {
							name: \''.$b['event_image_large'].'\',
							size: '.$b['sort'].'
						};
						myDropzone.emit("addedfile", mockFile);
						myDropzone.emit("thumbnail", mockFile, \''.$thumb.'\');
						myDropzone.emit("complete", mockFile);
						myDropzone.files.push(mockFile);
						uploaded_name.push("'.$b['event_image_large'].'");
						uploaded_big.push("'.$b['event_image_large'].'");
						uploaded_small.push("'.$b['event_image_small'].'");
						uploaded_thumb.push("'.$b['event_image_thumb'].'");

					';
				$head .= " temp_big.push('".$b["event_image_large"]."');\n";
				$head .= " temp_small.push('".$b["event_image_small"]."');\n";
				$head .= " temp_thumb.push('".$b["event_image_thumb"]."');\n";
			}
			$head .= '
				update_inputs();
				is_loading = false;
			});</script>';

			$action_label = "Edit ".$a["event_name"];
		}
		else
		{
			$action = 'insert';
		}
		$head .= '<script>
		function check()
		{
			title = $("#title").val();
			if(title == "")
			{
				alert("Please enter the title");
				$("#title").focus();
				return false;
			}
			date = $("#date").val();
			if(date == "")
			{
				alert("Please select or enter the date");
				$("#date").focus();
				return false;
			}
			pic_large = $("#pic_large").val();
			if(pic_large == "")
			{
				alert("Please select event main image");
				$("#pic_large").focus();
				return false;
			}
			return true;
		}
		</script>';
		$temp = '<div class="box box-solid">
		<div class="box-header with-border">
		<i class="fa fa-text-width"></i>

		<h3 class="box-title">'.$action_label.'</h3>
		</div>
		<!-- /.box-header -->
		<div class="box-body">';
		$date = $a["event_date"];
		if($date) $date = mysql_to_php_date($date);
		$temp .= '
			<form action="?action='.$action.'" method="post" enctype="multipart/form-data" name="form1" id="form1" class="link_White_bold" onsubmit="return check()">
			<table width="100%" border="0" cellpadding="5" cellspacing="0" class="heading_white">
			  <tr>
				<td width="21%" class="heading_white">Event Title</td>
				<td width="79%" class="link_White_bold"><input type="text" class="form-control" name="title" id="title" value="'.$a['event_name'].'"></td>
			  </tr>
			  <tr>
				<td class="heading_white">Large Image</td>
				<td class="link_White_bold">
				<input type="file" name="pic_large" id="pic_large" class="form-control" /><br>
				<a href="'.$a['image_big'].'" target="_blank">Preview Image</a> </td>
		</tr>
		<!-- '.generate_upload('pic_large', $a['image_big']).'-->
		<tr>
			<td class="heading_white">Date</td>
			<td class="link_White_bold"><input name="date" type="textbox" class="form-control" id="date" value="'.$date.'" data-inputmask="\'alias\': \'dd/mm/yyyy\'" data-mask/></td>
		  </tr>
		  <script src="'.BASE_URL.TEMPLATE_DIR.'plugins/input-mask/jquery.inputmask.js"></script>
		  <script src="'.BASE_URL.TEMPLATE_DIR.'/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
		  <script src="'.BASE_URL.TEMPLATE_DIR.'/plugins/input-mask/jquery.inputmask.extensions.js"></script>
		  <script>
			  // $("[data-mask]")

			  $(\'#date\').datepicker({
				  autoclose: true,
				  format: "dd\mm\yyyy",
				  setDate: new Date(),
				  minDate: new Date()
			  }).inputmask();
		  </script>';

		  $temp .= '
		  <tr>
		  <td colspan="2">
		<div id="dropzone" class="dropzone2" style="text-align:center; vertical-align:middle">Drop Files Here</div>
		<input value="" type="hidden" name="files_uploaded_big" id="files_uploaded_big">
		<input value="" type="hidden" name="files_uploaded_small" id="files_uploaded_small">
		<input value="" type="hidden" name="files_uploaded_thumb" id="files_uploaded_thumb">

		  </td></tr>
		  <tr>
			<td class="heading_white">&nbsp;</td>
			<td>
			<br>
				<input type="submit" name="button" id="button" value="Submit" />
				&nbsp;
				<input type="button" onclick="location.href=\''.page_url().'\'" value="Cancel"></button>
			</td>
		  </tr>
		</table>
		</form>';
		$temp .= '
				</div>
				<!-- /.box-body -->
			</div>';


		return $head.$temp;
	}
	function update()
	{
		$events_id = $_GET["id"];
		//insert(
		$sort = $_REQUEST['sort'];
		$files_uploaded_big 	= explode(",", $_POST['files_uploaded_big']);
		$files_uploaded_small 	= explode(",", $_POST['files_uploaded_small']);
		$files_uploaded_thumb 	= explode(",", $_POST['files_uploaded_thumb']);

		$topic 		= $_POST['title'];
		$date = php_to_mysql_date($_REQUEST["date"]);
		$directory 	= 'events/';
		$pic_large	= upload($_FILES['pic_large'], $directory.'largepics');
		if($pic_large)
		{
			$fn			= explode("/", $pic_large);
			$fn			= $fn[2];
			$image = new SimpleImage();
			$image->load($pic_large);
			$image->resizeToWidth(295);
			$image_small = $directory.'smallpics/small-'.$fn;
			$image->save($image_small);

			$sql1 = " image_small='".$image_small."', ";
			$sql2 = " image_big='".$pic_large."', ";
		}

		$sql = "UPDATE events SET".$sql1.$sql2." event_name = '".$topic."', event_date = '".$date."'
		WHERE events_id=".$events_id."
		;";
		//debug($sql);
		//return;
		$result = $this->sqlq($sql);

		$sql = "DELETE FROM event_images  WHERE events_id = ".$events_id;
		$this->sqlq($sql);
		$previous_sort = 1;
		for($i=0;$i<count($files_uploaded_big);$i++)
		{
			$event_image_big = $files_uploaded_big[$i];
			$event_image_small = $files_uploaded_small[$i];
			$event_image_thumb = $files_uploaded_thumb[$i];
			$temp_sort = $sort[$i];
			if(!$temp_sort) $temp_sort = $previous_sort;
			$previous_sort = $i+2;
			$sql = "insert into event_images (events_id, event_image_small, event_image_large, event_image_thumb, sort) VALUES
			($events_id, '$event_image_small', '$event_image_big', '$event_image_thumb', $temp_sort) ";
			// debug_r($temp_sort);
			// debug_r($sql);
			$this->sqlq($sql);
			//debug($sql);
			//$file_uploaded
		}
		redirect('?action=edit_event&event='.$events_id);
	}
}

function ahref($page, $link)
{
    // $link = $link;
		return '<a class="btn bg-blue btn-flat margin" href="'.$link.'" class="text_news_date_11">'.$page.'</a>';
}
