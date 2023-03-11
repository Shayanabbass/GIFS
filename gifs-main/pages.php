<?php include('includes/application_top2.php');
content_to_constant();
$db = new db2();
$data_n = get_tuple("html", page_name(), "html_name");
if(function_exists(page_name()))
{
  eval("\$data = ".page_name()."();");
  $data_n = $data;
}
// debug_r($title);
?>
<!doctype html>
<html lang="en">

<head>
   <?php include("template_parts/head.php"); ?>
   <?php echo $data["html_head"]; ?>
</head>

<body>
  <?php include("template_parts/header.php"); ?>

  <!--CONTENT AREA-->
  <div class="topbar">
      <div class="topbarBg"></div>
        <img src="activityBg.png" alt="">
      <h1 class="topbarText"><?php echo $data['html_title']; ?></h1>
    </div>


    <div class="Wrp my-4">
        <p class="fs-6 fw-bold mb-5 mt-5 text-center">
          <?php echo $data['html_text']; ?>
        </p>



</div>

  <!--CONTENT AREA END-->

  <?php include("template_parts/contact.php"); ?>
  <?php include("template_parts/footer.php"); ?>
  <?php include("template_parts/footer_scripts.php"); ?>

   </body>
</html>
