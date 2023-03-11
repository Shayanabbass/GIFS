<?php include('includes/application_top2.php');
   content_to_constant();
   $db = new db2();
    $data_n = get_tuple("html", "index", "html_name");
//    debug_r($data_n);
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




  <button type="button" class="btn btn-lg btn-back-to-top" id="btn-back-to-top">
    <i class="fas fa-arrow-up"></i>
  </button>

  <div class=" mb-1">

    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">

    <?php
    //   session_destroy();
    $banners = $_SESSION["data_banner"];
    foreach($banners as $banner)
    {
       echo '

       <div class="carousel-item">
         <img class="d-block w-100" id="banner_'.$banner['banner_id'].'" src="'.$banner['banner_image_upload'].'" alt="'.$banner['banner_name'].'">
       </div>';
    }
    ?>
  </div>
  <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
    <div style="background-color: #00a651; width: 100%;  margin-bottom: 30px;">
      <div class="container">
        <div class="text-center py-5 text-white ">
          <div class="p-3 vc_column-inner">
            <h5 id="visionheading">OUR VISION</h5>
          </div>

          <h6 class="p-2" style="font-size: 18px;">To foster a stimulating learning environment which actualizes
            individual <br> potential, caters academic, creative, personal, physical, moral & most <br> importantly
            spiritual development & ensures that all students are nurtured <br> to meet the challenges of the world and
            the hereafter.</h6>
          <h6 class="p-2"><i>Under the Vision and Supervision of Agha Ghulam Raza Roohani.</i></h6>
        </div>
      </div>

    </div>


    <?php include("template_parts/three_boxes.php");?>

    <?php include("template_parts/recent_activities.php"); ?>


    <?php include("template_parts/gallery.php");?>
    <?php //include("template_parts/contact.php");?>


    <div style="margin-bottom: 15px;">
      <div class="container-fluid">

        <div id="carousel" class="carousel slide" data-ride="carousel">
          <ol class="carousel-indicators">
            <li data-target="#carousel" data-slide-to="0" class="active"></li>
            <li data-target="#carousel" data-slide-to="1"></li>
          </ol>
          <div class="carousel-inner">
            <div class="carousel-item active">
              <div class="d-none d-lg-block">
                <div class="slide-box ">


                  <img src="images.jpg" alt="..." class="img-thumbnail">
                  <img src="images.jpg" alt="..." class="img-thumbnail">
                  <img src="images.jpg" alt="..." class="img-thumbnail">
                  <img src="images.jpg" alt="..." class="img-thumbnail">

                  <!-- <img class="p-1" src="./slider/1-3.jpg" alt="First slide"> -->


                </div>
              </div>
              <div class="d-none d-md-block d-lg-none">
                <div class="slide-box">
                  <img src="images.jpg" alt="..." class="img-thumbnail">
                  <img src="images.jpg" alt="..." class="img-thumbnail">
                  <img src="images.jpg" alt="..." class="img-thumbnail">
                  <img src="images.jpg" alt="..." class="img-thumbnail">

                </div>
              </div>
              <div class="d-none d-sm-block d-md-none">
                <div class="slide-box">
                  <img class="p-1" src="./slider/1-4.jpg" alt="First slide">
                  <img class="p-1" src="./slider/image4-1.jpg" alt="First slide">
                </div>
              </div>
              <div class="d-block d-sm-none">
                <img class="d-block w-100" src="images.jpg" alt="..." class="img-thumbnail">
              </div>
            </div>
            <div class="carousel-item">
              <div class="d-none d-lg-block">
                <div class="slide-box">
                  <img src="post-3-252x168.jpg" alt="..." class="img-thumbnail">
                  <img src="images.jpg" alt="..." class="img-thumbnail">
                  <img src="images.jpg" alt="..." class="img-thumbnail">
                  <img src="images.jpg" alt="..." class="img-thumbnail">
                </div>
              </div>
              <div class="d-none d-md-block d-lg-none">
                <div class="slide-box">
                  <img src="images.jpg" alt="..." class="img-thumbnail">
                  <img src="images.jpg" alt="..." class="img-thumbnail">
                  <img src="images.jpg" alt="..." class="img-thumbnail">
                  <img src="images.jpg" alt="..." class="img-thumbnail">
                </div>
              </div>
              <div class="d-none d-sm-block d-md-none">
                <div class="slide-box">
                  <img src="images.jpg" alt="..." class="img-thumbnail">
                  <img src="images.jpg" alt="..." class="img-thumbnail">


                </div>
              </div>
              <div class="d-block d-sm-none">
                <img class="d-block w-100" src="images.jpg" alt="..." class="img-thumbnail">

              </div>
            </div>
          </div>
          <a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>

      </div>
    </div>

    <?php include("template_parts/contact.php"); ?>
    <?php include("template_parts/footer.php"); ?>
  </div>
  <?php include("template_parts/footer_scripts.php"); ?>

</body>

</html>
