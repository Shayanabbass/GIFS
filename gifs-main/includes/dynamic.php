<?php 
class dynamic extends db2
{
	var $class_name = "register";
	var $class_name_text = "register";
	//----------------------------function to insert data into login users
	function insert()
	{
		$u = new users();
		if($u->users_check(get("user_name")))
		{
			alert("!!!User Name already exists. Please try another");
			echo '<script>history.back();</script>';
			//redirect('register.php');
			return;
		}
		$initial_value = get("date_of_payment");
		$date_1 = split("/",$initial_value);
		$date_of_payment =  $date_1[2].'-'.$date_1[0].'-'.$date_1[1];
		//
		
		$initial_value = get("date_of_birth");
		$date_1 = split("/",$initial_value);
		$date_of_birth =  $date_1[2].'-'.$date_1[0].'-'.$date_1[1];
		
		$value = $_FILES["users_picture_upload"];
		
		$users_picture_upload = upload($value, "users_images");


		$users_secret_question = $_REQUEST["users_secret_question"];
		$users_secret_answer = $_REQUEST["users_secret_answer"];
		
		
		$users_sex = $_REQUEST["users_sex"];
		$users_home_town = $_REQUEST["users_home_town"];
		$users_relationship_status = $_REQUEST["users_relationship_status"];
		$users_interested_in = $_REQUEST["users_interested_in"];
		$users_activities = $_REQUEST["users_activities"];
		$users_favourite_music = $_REQUEST["users_favourite_music"];
		$users_favourite_tv_shows = $_REQUEST["users_favourite_tv_shows"];
		$users_favourite_movies = $_REQUEST["users_favourite_movies"];
		$users_about_me = $_REQUEST["users_about_me"];
									
							
		$sql = "INSERT INTO  users (
					users_id ,
					users_name ,
					users_password ,
					users_email ,
					users_added_on ,
					groups_id,
					users_first_name,
					users_last_name,
					country,
					users_zipcode,
					state_province,
					users_picture_upload,
					users_secret_question,
					users_secret_answer,
					date_of_birth,
					users_sex,
					users_home_town,
					users_relationship_status,
					users_interested_in,
					users_activities,
					users_favourite_music,
					users_favourite_tv_shows,
					users_favourite_movies,
					users_about_me
					)
					VALUES (
					NULL ,  '".get("user_name")."',  md5('".get("password")."'),  '".get("email_address")."',  NOW( ) ,  '5', '".get("first_name")."', '".get("last_name")."', '".get("country")."', '".get("zipcode")."', '".get("state_province")."', '".$users_picture_upload."', '".$users_secret_question."', '".$users_secret_answer."', '".$date_of_birth."', '".users_sex."', '".users_home_town."', '".users_relationship_status."', '".users_interested_in."', '".users_activities."', '".users_favourite_music."', '".users_favourite_tv_shows."', '".users_favourite_movies."', '".users_about_me."'
					);";
		debug_r($sql);
		$result = $this->sqlq($sql);
		alert("Account Created Successfully");
		redirect(FILENAME_DEFAULT);
	}
	//----------------------------END register
}
?>