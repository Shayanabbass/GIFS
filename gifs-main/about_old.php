<?php include('includes/application_top2.php');
   content_to_constant();
   $db = new db2();
    $data_n = get_tuple("html", "index", "html_name");
   ?>
<!DOCTYPE html>
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html lang="en-US" class="no-js">
   <!--<![endif]-->
   <!-- Mirrored from demo1.greenislandtrust.org/gifs/ by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 02 Jun 2021 10:29:31 GMT -->
   <!-- Added by HTTrack -->
   <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
   <!-- /Added by HTTrack -->
   <head>
      <?php include("template_parts/head.php"); ?>
      <?php echo $data["html_head"]; ?>
   </head>
   <body>
      <?php include("template_parts/header.php"); ?>

      <?php
      // echo respond($data_n["html_text"]);
      ?>
      <!-- Row Backgrounds -->
      <?php
      // include("template_parts/about.php");
      ?>
      <div class=" mt-4 " style="height: 2000px">
        <div class="image-text-work about_header">
          <div class="container">
            <div class="row text-center">
              <div class="col-lg-12">
                <div>
                  <h1>ABOUT US</h1>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div style="background-color: #00a651; width: 100%;  margin-bottom: 40px;">
          <div class="container ">
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




        <div style="margin-bottom: 30px;">
          <div class="container">
            <div class="row">
              <div class="col-lg-6 col-12">
                <img src="slider/image4-1.jpg" class="img-fluid" alt="Responsive image">
              </div>
              <div class="col-lg-6 col-12 my-4">
                <img src="gifs-icon.jpg" class="img-fluid" alt="Responsive image">
                <h1>Welcome to Green Island Foundaion School</h1>
                <p style="color: gray;">Lorem ipsum dolor sit amet consectetur adipisicing elit. Perferendis quidem quos in
                  fuga debitis fugiat temporibus consequatur porro, quas beatae dolorum tenetur odit aliquam suscipit
                  obcaecati id sit facere ipsa.
                  Lorem, ipsum dolor sit amet consectetur adipisicing elit. Velit, laboriosam architecto exercitationem quos
                  animi quo aspernatur earum incidunt eaque, iste ratione neque unde consequuntur ea ducimus commodi quia
                  nam. Odit.
                  Lorem ipsum dolor sit amet consectetur, adipisicing elit. Vitae incidunt odit enim. Quas exercitationem
                  quod praesentium nisi fugit asperiores, enim saepe alias qui beatae reiciendis repellat repudiandae cumque
                  cupiditate. Eum.
                </p>
              </div>


            </div>

            <div class="row my-5">

              <div class="col-lg-6 col-12 my-4">
                <h1>Our History</h1>
                <p style="color: gray;">Lorem ipsum dolor sit amet consectetur adipisicing elit. Perferendis quidem quos in
                  fuga debitis fugiat temporibus consequatur porro, quas beatae dolorum tenetur odit aliquam suscipit
                  obcaecati id sit facere ipsa.
                  Lorem, ipsum dolor sit amet consectetur adipisicing elit. Velit, laboriosam architecto exercitationem quos
                  animi quo aspernatur earum incidunt eaque, iste ratione neque unde consequuntur ea ducimus commodi quia
                  nam. Odit.
                  Lorem ipsum dolor sit amet consectetur, adipisicing elit. Vitae incidunt odit enim. Quas exercitationem
                  quod praesentium nisi fugit asperiores, enim saepe alias qui beatae reiciendis repellat repudiandae cumque
                  cupiditate. Eum.
                </p>
              </div>

              <div class="col-lg-6 col-12">
                <div class="row">
                  <div class="col-lg-6 col-6">
                    <img src="slider/1-2.jpg" alt="..." class="img-thumbnail">
                  </div>
                  <div class="col-lg-6 col-6">
                    <img src="slider/1-2.jpg" alt="..." class="img-thumbnail">
                  </div>
                  <div class="col-lg-6 col-6 my-3">
                    <img src="slider/1-2.jpg" alt="..." class="img-thumbnail">
                  </div>
                  <div class="col-lg-6 col-6 my-3">
                    <img src="slider/1-2.jpg" alt="..." class="img-thumbnail">
                  </div>
                </div>


              </div>


            </div>
          </div>

        </div>



      <?php include("template_parts/three_boxes.php"); ?>
      <?php //include("template_parts/gallery.php");?>


      <?php include("template_parts/footer.php"); ?>
   </body>
</html>
