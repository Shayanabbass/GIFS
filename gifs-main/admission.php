<?php
include( 'includes/application_top2.php' );
content_to_constant();
$db = new db2();
$action = $_GET["action"];
$thanks = false;
if($action == "submit_application")
{
	$student_name = request('student_name');
	$father_name = request('father_name');
	$date_of_birth = php_to_mysql_date($_POST['date_of_birth']);
	$gender_id = request('gender_id');
	$religion = request('religion');
	$admission_class = request('admission_class');
	$last_current_class = request('last_current_class');
	$last_current_school = request('last_current_school');
	$father_contact_number = request('father_contact_number');
	$father_cnic = request('father_cnic');
	$mother_contact_number = request('mother_contact_number');
	$mother_cnic = request('mother_cnic');
	$email_address = request('email_address');
	$address = request('address');
	$siblings_in_gifs = request('siblings_in_gifs');

	$body = '<table width="600" border="1" cellspacing="0" cellpadding="5">
		  <tbody>
			<tr>
			  <td>Student\'s Name</td>
			  <td>'.$student_name.'</td>
			</tr>
			<tr>
			  <td>Father\'s Name</td>
			  <td>'.$father_name.'</td>
			</tr>
			<tr>
			  <td>Date of Birth</td>
			  <td>'.$date_of_birth.'</td>
			</tr>
			<tr>
			  <td>Gender</td>
			  <td>'.$gender_id.'</td>
			</tr>
			<tr>
			  <td>Religion</td>
			  <td>'.$religion.'</td>
			</tr>
			<tr>
			  <td>Admission for Class</td>
			  <td>'.$admission_class.'</td>
			</tr>
			<tr>
			  <td>Last / Current Class (if any)</td>
			  <td>'.$last_current_class.'</td>
			</tr>
			<tr>
			  <td>Last / Current School (if any)</td>
			  <td>'.$last_current_school.'</td>
			</tr>
			<tr>
			  <td>Father\'s Contact Number</td>
			  <td>'.$father_contact_number.'</td>
			</tr>
			<tr>
			  <td>Father\'s CNIC Number</td>
			  <td>'.$father_cnic.'</td>
			</tr>
			<tr>
			  <td>Mother\'s Contact Number</td>
			  <td>'.$mother_contact_number.'</td>
			</tr>
			<tr>
			  <td>Mother\'s CNIC</td>
			  <td>'.$mother_cnic.'</td>
			</tr>
			<tr>
			  <td>Email Address</td>
			  <td>'.$email_address.'</td>
			</tr>
			<tr>
			  <td>Residential Address</td>
			  <td>'.$address.'</td>
			</tr>
			<tr>
			  <td>Siblings (if any in GIFS)</td>
			  <td>'.$siblings_in_gifs.'</td>
			</tr>
		  </tbody>
		</table>';
	$emailTo = SITE_ADMISSION_EMAIL;
	$emailSubject = "New Admission form from website";
	$emailBody = 'Dear Admin, <br> Please find the below request<p></p>'.$body;
	email($emailTo, $emailSubject, $emailBody);
	$sql = "INSERT INTO `student` (`student_name`, `father_name`, `date_of_birth`, `gender_id`, `religion`, `admission_class`, `last_current_class`, `last_current_school`, `father_contact_number`, `father_cnic`, `mother_contact_number`, `mother_cnic`, `email_address`, `address`, `siblings_in_gifs`) VALUES ( '$student_name', '$father_name', '$date_of_birth', '$gender_id', '$religion', '$admission_class', '$last_current_class', '$last_current_school', '$father_contact_number', '$father_cnic', '$mother_contact_number', '$mother_cnic', '$email_address', '$address', '$siblings_in_gifs')";
	$db = new db2();
	$db->sqlq($sql);

//	debug_r($sql);


	echo json_encode(array('status'=>1, 'message'=>'We have received your query thank you.', 'sql' =>$sql));
	die;
}
elseif($action == 'thanks')
{
	$thanks = true;
}
$data_n = get_tuple( "html", "admission", "html_name" );
//    debug_r($data_n);
/*
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
phpinfo();*/
?>
<!doctype html>
<html lang="en">

<head>
    <?php include("template_parts/head.php"); ?>
		<link rel="stylesheet" href="css/wpforms-full1f22.css">
    <?php echo $data["html_head"]; ?>
</head>

<body>
    <?php include("template_parts/header.php"); ?>
	<div class="topbar">
            <div class="topbarBg"></div>
            <img src="./aboutBg.png" alt="">
            <h1 class="topbarText">ADMISSION</h1>
          </div>
    <div class="image-text-work my-1 admission_banner mb-5">
      <div class="container">
        <div class="row text-center">
          <div class="col-lg-12">
            <div>
              <!-- <h1>ADMISSION FORM</h1> -->
            </div>
          </div>
        </div>
      </div>
    </div>

    <div id="main" class="sidebar-none sidebar-divider-off">
        <div class="main-gradient"></div>
        <div class="wf-wrap">
            <div class="wf-container-main">
                <div id="content" class="content" role="main">
                    <div class="vc_row wpb_row vc_row-fluid nopadding vc_custom_1612559337733 wpforms-container-full">
                        <div class="wpb_column vc_column_container vc_col-sm-12">
                            <div class="vc_column-inner">
                                <div class="wpb_wrapper">
                                    <div class="wpb_text_column wpb_content_element ">
                                        <div class="wpb_wrapper Wrp container">
										<?php if($thanks)
											echo '<div class="success" style="text-align: left;color: green;">
												<p>
													Assalam-o-Alaikum<br>
													Thank you for submitting the Admission Request Form.<br>
													We are grateful for showing interest in Green Island Foundation School. The data provided will be checked as per the available seats and you will be contacted accordingly for the new session 2023-24.<br>
													Have a great day!
												</p>
												</div>';
										?>
											<h2 class="py-4">Request for Admission</h2>
                                            <p style="text-align: justify; color: black;">Please fill in the below form </p>
											<?php if($thanks)
											{
												// alert("Your request for admission has been received. Thank you for.");
											}?>
											 <?php $a = new admission();
											list($head, $temp) = $a->admission_form_fields();
											echo $temp;
											?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
               </div>
                <!-- #content -->

            </div>
            <!-- .wf-container -->
        </div>
        <!-- .wf-wrap -->
    </div>
    <!-- #main -->
    <?php include("template_parts/footer.php"); ?>
	<?php include("template_parts/footer_scripts.php"); 
	echo $head;
	?>
</body>
<!-- Mirrored from demo1.greenislandtrust.org/gifs/ by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 02 Jun 2021 10:32:25 GMT -->
</html>
