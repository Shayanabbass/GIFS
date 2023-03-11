<?php
include('includes/application_top2.php');
content_to_constant();
$db = new db2();
$data_n = get_tuple("html", "index", "html_name");
//  debug_r($data_n);
/*
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
phpinfo();*/
// debug_r("as");
?>
<!doctype html>
<html lang="en">

<head>
     <?php include("template_parts/head.php"); ?>
     <?php echo $data["html_head"]; ?>
</head>

<body>
  <?php include("template_parts/header.php"); ?>

    <div class="banner-image">
      <?php

    $banners = $_SESSION["data_banner"];
    foreach($banners as $banner)
    {
       echo '<img  src="'.$banner['banner_image_upload'].'" id="banner_'.$banner['banner_id'].'"  alt="'.$banner['banner_name'].'" />';
    }
    ?>
	</div>
  <div style="background-color: #00a651; width: 100%; margin-bottom: 30px">
				<div class="Wrp">
					<div class="text-center py-5 text-white img">
						<img src="./tree.png" class="tree" alt="Tree Background Image" />
						<div class="p-3 vc_column-inner">
							<h5 id="visionheading" class="visionheading">OUR VISION</h5>
						</div>

						<p class="txt">
							To foster a stimulating learning environment which actualizes
							individual potential, caters academic, creative, personal,<br />
							physical, moral & most importantly spiritual development & ensures
							that all students are nurtured to meest the challenges of the<br />
							world and the hereafter.
						</p>
						<h6 class="italic">
							Under the Vision and Supervision of Agha Ghulam Raza Roohani.
						</h6>
					</div>
				</div>
			</div>

      <?php include("template_parts/three_boxes.php");?>
      <div>
        <div class="Wrp">
          <div class="text-center text-success" id="img">
            <img
              src="./tree.png"
              class="tree"
              id="tree"
              alt="Tree Background Image"
            />
            <div class="py-5 vc_column-inner">
              <h5 id="visionheading">RECENT ACTIVITIES</h5>
            </div>
          </div>
          <div class="recent_activity">

          <?php
            $a = new latest_stories();
            echo $a->recent_activities();
          ?>

            </div>
            <div style="text-align:center" class="mb-5">
              <a class="btn btn-success hover btn-small mt-25" href="activity.php">Show More</a>
            </div>
		
        </div>
      </div>
      <?php
      //include("template_parts/recent_activities.php");
       ?>
      <?php include("template_parts/gallery.php");?>


    <?php include("template_parts/contact.php"); ?>
    <?php include("template_parts/footer.php"); ?>
  </div>
  <?php include("template_parts/footer_scripts.php"); ?>

</body>

</html>
