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

     <div>
          <div class="topbar">
            <div class="topbarBg"></div>
            <img src="./aboutBg.png" alt="">
            <h1 class="topbarText">ABOUT US</h1>
          </div>


          <div style="background-color: #00a651; width: 100%;" class="mb-5">
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


    <div class="mb-5">
      <div class="Wrp">
        <div class="aboutSec mb-5">
          <div class="aboutImage">
            <img src="slider/image4-1.jpg" alt="Responsive image">
          </div>
          <div class="col-lg-6 col-12">
            <h1 class="fw-bold mb-4">Welcome to Green Island Foundation School</h1>
            <p>Our journey as a school began in 2005. Initially though, it was small scale, but that changed as the years progressed and now Green Island Foundation School has become quite large scale, owing to the number of enrolments of students. Not just that, but also in terms of fixtures, overall infrastructure and academic curriculum. We are registered with the Board of Secondary Education as well.</p>
            <p>We take pride in acknowledging that our students are capable individuals and majority of them have managed to secure excellent academic records coupled by A and A-1 grades. We offer free books and copies and provide students with the best facilities to make their educational experience very practical and interesting to learn, which includes:</p>
            <li>
              Field Trips
            </li>
            <li>
              Computer Labs
            </li>
            <li>
              Science Labs
            </li>
            <li>
              Libraries
            </li>
            <li>
              High-Tech Classrooms, as well as school activities.
            </li>
          </div>
        </div>

        <div class="flex flex-wrap mb-5">

          <div class="col-lg-6 col-12 mb-5" style="display: flex;justify-content: center;flex-direction: column;">
            <h1 class="fw-bold mb-4">Our History</h1>
            <p>Behind this educational institutions' concept was a noble organization itself. Green Island Trust is an organization that believes in serving the society and giving back, especially in terms of education, because of which Green Island Foundation School was created. It is a registered trust under the government of Pakistan and the Trustees include:</p>
            <li>
              Mr. Musa Raza Naqvi
            </li>
            <li>
              Mr. Ghulam Raza Roohani
            </li>
            <li>
              Mr. Ejaz Hussain
            </li>
            <p></p>
              <p>We started off with a small, two bedroom rented apartment in Solider Bazar’s vicinity as a Montessori School. Started from only five students to now having many was a rigorous journey indeed. However, our growth accelerated soon after 2005. The five students turned into 25 after a year. Moreover, by the <?php echo date("Y")-2006; ?>th academic year, we are able to see a significant strength in enrolments, crossing 700. The school’s <?php echo date("Y")-2006; ?> years journey sure was not an easy one, but our students made it through by maintaining excellent academic records.</p>
          </div>

          <div class="col-lg-6 col-12 ">
            <div class="imgWrap">
              <div class="cardImage">
                <img src="slider/1-2.jpg" alt="..." >
              </div>
              <div class="cardImage">
                <img src="slider/1-3.jpg" alt="..." >
              </div>
              <div class=" cardImage">
                <img src="slider/1-4.jpg" alt="..." >
              </div>
              <div class=" cardImage">
                <img src="slider/1-5.jpg" alt="..." >
              </div>
            </div>


          </div>


        </div>
      </div>

    </div>


      <?php include("template_parts/three_boxes.php"); ?>
      <?php include("template_parts/contact.php"); ?>

      <?php //include("template_parts/gallery.php");?>
      <div class="mb-5"></div>

      <?php include("template_parts/footer.php"); ?>
      <?php include("template_parts/footer_scripts.php"); ?>

   </body>
</html>
