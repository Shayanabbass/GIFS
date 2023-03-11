<?php
class post extends db2
{
	function insert()
	{
		$post_id = $_REQUEST["post_id"];
		$post_name = str_replace("'", "''", $_REQUEST["post_name"]);
		$event_timing = str_replace("'", "''", $_REQUEST["event_timing"]);
		$event_on = php_to_mysql_date($_REQUEST["event_on"]);

		$post_type_id = $_REQUEST["post_type_id"];
		$post_type = get_tuple("post_type", $post_type_id, "id");
		$post_type_name = $post_type["name"];
		$value = $_FILES["post_image_upload"];
		$post_image_upload = upload($value, "post_images");
		$post_description = $_REQUEST["post_description"];
		if( (substr($post_description, 0, 3) == "<p>" ))
		{
			$temp = substr(substr($post_description, 3, strlen($post_description)), 0, strlen($post_description)-3);
			$post_description = $temp;
			$post_description = substr($post_description, 0, strlen($post_description)-4);
			$post_description = htmlspecialchars($post_description);
		}
        $categories = $_REQUEST['categories'];
        $post_category = json_encode($categories);
        $post_added_by_id  = $_SESSION["users_id"];
		$gallery_link = $_REQUEST["gallery_link"];
		if( (substr($gallery_link, 0, 3) == "<p>" ))
		{
			$temp = substr(substr($gallery_link, 3, strlen($gallery_link)), 0, strlen($gallery_link)-3);
			$gallery_link = $temp;
			$gallery_link = substr($gallery_link, 0, strlen($gallery_link)-4);
			$gallery_link = htmlspecialchars($gallery_link);
		}
        $sql = 'Insert into post
            (post_name, event_timing, event_on, post_type_name, post_image_upload, post_description, post_category, post_added_by_id, gallery_link)
        VALUES
			(\''.$post_name.'\', \''.$event_timing.'\', \''.$event_on.'\', \''.$post_type_name.'\', \''.$post_image_upload.'\', \''.$post_description.'\', \''.$post_category.'\', \''.$post_added_by_id.'\', \''.$gallery_link.'\')';
		if (!db2::$db->inTransaction())
			db2::$db->beginTransaction();
		if (DEBUG) {
			debug($sql);
			$id = 1;
		} else {
			$this->sqlq($sql, true);
			$id = db2::$db->lastInsertId();
		}
        $this->insert_details($id, $categories);
		db2::$db->commit();
		alert("Post Inserted Successfully");
		redirect("post.php?action=new");
	}

	function delete()
	{
		$id = $_GET["id"];
		$sql = "DELETE FROM post WHERE post_id = ".$id." LIMIT 1;";
		$result = $this->sqlq($sql);
		if($result)
		{
			alert('Post deleted successfully');
			redirect('post.php');
		}
		else
		{
			alert('Error! Post delete failed');
		}
	}
	function update()
	{
		$post_name = $_REQUEST["post_name"];
		$event_timing = $_REQUEST["event_timing"];
		$event_on = php_to_mysql_date($_REQUEST["event_on"]);
		$post_type_id = $_REQUEST["post_type_id"];
		$post_type = get_tuple("post_type", $post_type_id, "id");
		$post_type_name = $post_type["name"];
		$post_image_upload = $_REQUEST["post_image_upload"];
		$value = $_FILES["post_image_upload"];
		$targetpath = "post_images";
		$path = upload($value, "post_images");
		$pics = ", post_image_upload='".$path."'";
		if(empty($path))
		{
			$pics = '';
		}
		$post_description = request("post_description");
        $categories = $_REQUEST['categories'];
        $post_added_by_id = $_SESSION["users_id"];
		$gallery_link = $_REQUEST["gallery_link"];
		$id = $_GET["id"];
		// post_type_id='".$post_type_id."',
		$sql = "UPDATE post SET
			post_name='".$post_name."',
			event_timing='".$event_timing."',
			event_on='".$event_on."',
			post_type_name='".$post_type_name."'".$pics.",
			post_description='".$post_description."',
			post_category='".json_encode($categories)."',
			post_added_by_id='".$post_added_by_id."',
            gallery_link='".$gallery_link."' WHERE post_id = ".$id;
		// debug_r($sql);
        db2::$db->beginTransaction();
        if(DEBUG)
        {
            debug($sql);
        }
        else
        {
            $this->sqlq($sql, true);
        }
        $this->insert_details($id, $categories);
        if(!DEBUG)
        {
            db2::$db->commit();
            alert("Post Updated Successfully");
            redirect("post.php");
        }
        else
        {
            debug($sql);
            db2::$db->rollBack();
            die;
        }
    }
    function insert_details($id, $categories)
    {
        $category_array = array();
        foreach($categories as $category)
        {
            $temp_cat = explode("___", $category);
            $category_array[] = "(".$id.", '".$temp_cat[0]."')";
        }
        $sql_post_to_category = "insert into post_to_category (post_id, category_id) VALUES ".implode(",", $category_array);

        if(DEBUG)
        {
            debug($sql_post_to_category);
        }
        else {
			$this->sqlq("delete from post_to_category where post_id = $id", true);
            if(count($category_array))
				$this->sqlq($sql_post_to_category, true);
        }

    }
	function edit($id = "")
	{
		$action = "?action=insert";
		$action_label = "Insert ";
		if($id)
		{
			$action = "?action=update&id=".$id;
			$action_label = "Update ";
			$a = get_tuple("post", $id);
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
			$post_name = $a["post_name"];
				$temp .= '
				<div class="form-group">
					<label for="services_accounts"> Name:</label>
					<div class="field">
						<input type="text" name="post_name" id="post_name" size="32" class="validate[required] form-control" value="'.$post_name.'" />
					</div>
				</div> <!-- .form-group -->';
			// $post_type_id = $a["post_type_id"];
			// 		$temp .= '
			// 		<div class="form-group">
			// 		<label>Post Type</label>
			// 			<div class="field">
			// 				'.dropdown("post_type_id", $post_type_id).'
			// 			</div>
			// 		</div>';
			$event_on = $a["event_on"];
			if($event_on) $event_on = mysql_to_php_date($event_on);
			$temp .= '
				<div class="form-group">
					<label for="event_on">Event Posted On:</label>
					<div class="field">
						<input type="text" name="event_on" id="event_on" size="32" class="validate[required] form-control" value="'.$event_on.'" data-inputmask="\'alias\': \'dd/mm/yyyy\'" data-mask/>
					</div>
				</div> <!-- .form-group -->
				<script src="'.BASE_URL.TEMPLATE_DIR.'plugins/input-mask/jquery.inputmask.js"></script>
				<script src="'.BASE_URL.TEMPLATE_DIR.'/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
				<script src="'.BASE_URL.TEMPLATE_DIR.'/plugins/input-mask/jquery.inputmask.extensions.js"></script>
				<script>
					// $("[data-mask]")

					$(\'#event_on\').datepicker({
						autoclose: true,
						format: "dd\mm\yyyy",
						setDate: new Date(),
						minDate: new Date()
					}).inputmask();
				</script>';
			$event_timing = $a["event_timing"];
				$temp .= '
				<div class="form-group">
					<label for="services_accounts">Author Name:</label>
					<div class="field">
						<input type="text" name="event_timing" id="event_timing" size="32" class="validate[required] form-control" value="'.$event_timing.'" />
					</div>
				</div> <!-- .form-group -->';
			$post_image_upload = $a["post_image_upload"];
			$temp .= '
				<div class="form-group inlineField">
					<label for="myfile">Post Image Upload:</label>

					<div class="field">
						<input type="file" name="post_image_upload" id="post_image_upload" />

					</div>
				</div>
						<img src="'.$post_image_upload.'" width="200" height="200" />';
			$post_description = $a["post_description"];
				$temp .= '
				<div class="form-group">
					<label for="services_accounts"> Description:</label>
					<div class="field">
						<textarea name="post_description" id="post_description" class="validate[required] form-control" rows="4" cols="50">'.$post_description.'</textarea>
					</div>
                </div> <!-- .form-group -->
                <script>
                   jQuery("#post_description").wysihtml5();
                </script>';
                //Post Categories
        $categories = $this->result("select c.category_id, c.category_name from category c order by category_name ASC");
        if($id)
            $categories = $this->result("select c.category_id, c.category_name, ptc.post_id from category c left join post_to_category ptc on ptc.category_id = c.category_id AND post_id = $id  order by category_name ASC");
        $temp .= '<div class="form-group">
					<label for="categories">Categories:</label>
                    <div class="container">
                    <div class="row">';
        foreach ($categories as $category) {
            $post_id = $category['post_id'];
            $temp .= '<div class="col-md-3">
                        <label><input name="categories[]" type="checkbox" '.($post_id ? 'checked="checked"' : '').' value="' . $category["category_id"].'___'.$category["category_name"] .'"> ' . $category["category_name"] . '</label>
                </div>';
        }
        $temp .= '
                    </div>
                 </div>';
			// $post_category = $a["post_category"];
			// 	$temp .= '
			// 	<div class="form-group">
			// 		<label for="services_accounts"> Category:</label>
			// 		<div class="field">
			// 			<input type="text" name="post_category" id="post_category" size="32" class="validate[required] form-control" value="'.$post_category.'" />
			// 		</div>
			// 	</div> <!-- .form-group -->';
			$gallery_link = $a["gallery_link"];
				$temp .= '
				<div class="form-group">
					<label for="services_accounts"> Gallery Link:</label>
                    <div class="field">
                        <input type="text" name="gallery_link" id="gallery_link" size="32" class="validate[required] form-control" value="'.$gallery_link.'">					</div>
                </div> <!-- .form-group -->';
        $temp .= '
			<div class="actions">
								<button type="submit" class="btn btn-primary">'.$action_label.'Post</button>
								<a href="post.php"><button type="button" class="btn">Cancel</button></a>
							</div> <!-- .actions -->
						</form>
					</div> <!-- .box-body -->
				</div>
				 <!-- .box -->
		</div>
		<!-- .grid -->';
		// debug_r($temp);
		return $temp;
	}

	function show()
	{
		$filter = " ORDER By event_on DESC";
		$sql = "select *, UNIX_TIMESTAMP(event_on) as event_on from post ".$filter;
		$result = $this->result($sql);
		if(count($result)==0)
		{
			$temp .= '

			<div class="grid-24">
				<div class="box">

					<div class="box-header">
						<span class="icon-info"></span>
						<h3>Post</h3>
					</div>

					<div class="box-body">
						No Records Currently present.
						<p align="center">
							<a class="btn bg-blue btn-flat margin" href="?action=new" class="text_news_date_11">New</a>
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
						<h3 class="icon chart">Manage Post</h3>
						<p align="center">
							<a class="btn bg-blue btn-flat margin" href="?action=new" class="text_news_date_11">New</a>
						</p>
					</div>


					<div class="box-body">

						<table class="table table-bordered table-striped data-table">
					<thead>
						<tr>
						<th>Id</th>
						<th>Name</th>
						<th>Author</th>
						<th>Event On</th>
						<th>Description</th>
						<th>Actions</th>
						</tr>
					</thead>
					<tbody>
			';
		}
		foreach($result as $a)
		{
			$id = $a["post_id"];
			$temp .= '
				<tr class="gradeA">
					<td>'.$a["post_id"].'</td>
					<td>'.$a["post_name"].'</td>
					<td>'.$a["event_timing"].'</td>
					<td>'.date('j F Y ', $a["event_on"]).'</td>
					<td>'.substr(strip_tags ($a["post_description"], ''), 0, 100).'</td>

					<td><a href="?action=edit&id='.$id.'">
						<button class="btn btn-primary">Edit </button>
					  </a>
					  <a href="?action=delete_confirm&id='.$id.'" id="deleteConfirm_'.$id.'"><button class="btn btn-error">Delete</button></a>
					  <script>
						$(function () {
							$("#deleteConfirm_'.$id.'").on ("click", function (e) {
								e.preventDefault ();
								$.confirm({
									title: "Delete",
									content: "Are you sure you want to delete the record?",
									buttons: {
										confirm: function () {
											location.href="?action=delete&id='.$id.'";
										},
										cancel: function () {
										}
									}
								});
							});
						});
					 </script>
						</td>
					</tr>';
		}
		if(count($result) > 0)
		{
			$temp .= '</tbody></table></div>
					<!--box-body-->
				</div>
				<!--box-->
			</div><!--box-->
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
			<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
			';
		}
		return $temp;
	}
}
