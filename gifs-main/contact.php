<?php
include( 'includes/application_top2.php' );
content_to_constant();
$db = new db2();
$action = $_GET["action"];
$thanks = false;
$alert = false;
if($action == "submit")
{
	list($name, $email, $subject, $message) = ($_POST['wpforms']['fields']);
	if($name && $email)
	{
		$text = '<table width="600" border="1" cellspacing="0" cellpadding="5">
				  <tbody>
					<tr>
					  <td width="131">Name</td>
					  <td width="4">:</td>
					  <td width="427">'.$name.'</td>
					</tr>
					<tr>
					  <td>Email</td>
					  <td>:</td>
					  <td>'.$email.'</td>
					</tr>
					<tr>
					  <td>Subject</td>
					  <td>:</td>
					  <td>'.$subject.'</td>
					</tr>
					<tr>
					  <td>Comments</td>
					  <td>:</td>
					  <td>'.$message.'</td>
					</tr>
				  </tbody>
				</table>';
		$emailTo = SITE_ADMIN_EMAIL;
		$emailSubject = "New Contact form from website";
		$emailBody = $text;
		email($emailTo, $emailSubject, $emailBody);
		redirect("contact.php?action=thanks");
	}
	else {
		$alert = "Required fields values are missing";
	}
}
elseif($action == 'thanks')
{
	$thanks = true;
}
$data_n = get_tuple( "html", "contact", "html_name" );
//    debug_r($data_n);
/*
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
phpinfo();*/
?>
<!DOCTYPE html>
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html lang="en-US" class="no-js">
<!--<![endif]-->
<!-- Mirrored from demo1.greenislandtrust.org/gifs/ by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 02 Jun 2021 10:29:31 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
<!-- /Added by HTTrack -->
<head>
    <?php include("template_parts/head.php"); ?>
    <?php echo $data["html_head"]; ?>
		<style>
		.margin
		{
			margin-left: 130px !important	;
		}
		</style>
</head>

<body>
    <?php include("template_parts/header.php"); ?>
	<div class="topbar">
            <div class="topbarBg"></div>
            <img src="./aboutBg.png" alt="">
            <h1 class="topbarText">CONTACT US</h1>
          </div>

		  <div class="image-text-work my-1 contact_banner">
		    <div class="container">
		      <div class="row text-center">
		        <div class="col-lg-12">
		          <div>
		            <!-- <h1></h1> -->
		          </div>
		        </div>
		      </div>
		    </div>
		  </div>


			<div class="container Wrp my-5">
				<div class="row">
					<div class="col-lg-6 col-12 ">
						<div class="my-4">
							<h2 class="py-4">Locations</h2>
							<h4 style="color: #00a651;" class="my-4">BOYS CAMPUS</h4>
							<span>144/E Hali Road P.E.C.H.S, Block 2,</span> <br>
							<span><i class="fa fa-phone mx-3" aria-hidden="true" style="transform: rotate(150deg);"></i>021-34300668 <i class="fa fa-phone mx-3" aria-hidden="true" style="transform: rotate(150deg);"></i>021-34543110</span>
							<hr class="mt-5" style="color: #00a651;width: 80%;background-color: #00a651;border: 1px solid; margin-left: 0px;">


						</div>

						<div class="my-5">

							<h4 style="color: #00a651;" class="my-4">GIRLS CAMPUS</h4>
							<span>74/F Ghazali Road P.E.C.H.S, Block 2,</span> <br>
							<span><i class="fa fa-phone mx-3" aria-hidden="true" style="transform: rotate(150deg);"></i> 021-34551962</span>
							<hr class="mt-5" style="color: #00a651;width: 80%;background-color: #00a651;border: 1px solid; margin-left: 0px;">

						</div>

						<div class="my-5">

							<h4 style="color: #00a651;" class="my-4">ECD CAMPUS</h4>
							<span>84/L Ghazali Road P.E.C.H.S, Block 2,</span> <br>
							<span><i class="fa fa-phone mx-3" aria-hidden="true" style="transform: rotate(150deg);"></i>021-34300669 <i class="fa fa-phone mx-3" aria-hidden="true" style="transform: rotate(150deg);"></i> 021-34543110</span>
							<hr class="mt-5" style="color: #00a651;width: 80%;background-color: #00a651;border: 1px solid; margin-left: 0px;">

						</div>

						<div class="icnWrapper">
									<a href="https://www.facebook.com/GIFSPAK" class="m-1"><i class="fab fa-facebook-f  border-success  icon" style="
											display: flex;
											align-items: center;
											justify-content: center; color: #00a651;
										" aria-hidden="true"></i>
									</a>
									<a href="mailto:greenislandschool@gmail.com"  class="m-1"><i
										class="fa fa-envelope icon border-success"
										style="
											display: flex;
											align-items: center;
											justify-content: center; color: #00a651;
										"
									></i>
									</a>
								</div>

					</div>

					<div class="col-lg-6 col-12 my-4 ">
						<h2 class="py-4">SEND A MESSAGE</h2>
						<?php if($thanks) alert("Your information has been received. Thank you for your message.");
						if($alert) alert($alert);?>
						<form method="post" enctype="multipart/form-data" action="contact.php?action=submit">
							<div class="form-group mb-3">


								<input type="text" class="form-control" id="formGroupExampleInput" placeholder="Name" name="wpforms[fields][0]">
							</div>
							<div class="form-group mb-3">

								<input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Email"  name="wpforms[fields][1]">
							</div>
							<div class="form-group mb-3">

								<input type="text" class="form-control" id="formGroupExampleInput2"  placeholder="Subject" name="wpforms[fields][2]">
							</div>

							<div class="form-group mb-3">

								<textarea class="form-control" id="exampleFormControlTextarea1" rows="6" placeholder="Message" name="wpforms[fields][3]"></textarea>
							</div>
							<button type="submit" style="height: 50px; width: 100px; " class="background-green border-0 bt btn btn-outline-secondary float-end text-light"> Submit</button>
						</form>


					</div>




				</div>
			</div>

    <!-- #main -->
		<?php include("template_parts/contact.php");?>
    <?php include("template_parts/footer.php"); ?>
	<?php include("template_parts/footer_scripts.php"); ?>
</body>
</html>
