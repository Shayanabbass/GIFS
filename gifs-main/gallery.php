<?php
  include('includes/application_top2.php');
  content_to_constant();
  $db = new db2();
  $data_n = get_tuple("html", "gallery", "html_name");
?>
<!doctype html>
<html lang="en">

<head>
  
   <?php include("template_parts/head.php"); ?>
   <?php echo $data["html_head"]; ?>

   <link rel="stylesheet" href="accordian.css" />


</head>

<body class="gallery-page">
  <?php include("template_parts/header.php"); ?>

  <!--CONTENT AREA-->
  <div class="topbar">
      <div class="topbarBg"></div>
        <img src="activityBg.png" alt="">
      <h1 class="topbarText" id="main_heading">GALLERY</h1>
    </div>


    <div class="Wrp ">
    <!-- <div class="txtWrap">
				<div class="title">
					<p class="galleryText">
						
					</p>
				</div>
			</div> -->


        <div class="container page-top">



        <div class="row mt-5">

        <div class="col-md-3">
            <h1>GALLERY</h1>

            <div class="panel-group" id="accordion">

         <?php
         $db = new db2();
         $events = $db->result("select * from events ORDER BY events_id DESC");
         $data = [];
         $id = isset($_GET["id"]) ? $_GET["id"] : '';
         if($id != "")
         {

         }
         // $data_images = [];
         $event_data = [];
         $flag_first = true;
         foreach($events as $a)
         {
            $event_date = mysql_to_mktime($a["event_date"]);
            //For only selected or first
            if($flag_first || $id == $a["events_id"])
            {
              $images = $db->result("select * from event_images where events_id = ".$a['events_id']);
              $a['images'] = $images;
              $flag_first = false;
              $image_count = count($images);
            }
            else
            {
              $images = $db->result("select count(*) as total from event_images where events_id = ".$a['events_id'], 1);
              $image_count = $images["total"];
            }
            $event_title = $a["event_name"].'('.$image_count.')';
            //  debug_r();
            $d = mysql_to_mktime($a["event_date"]);
            $data[date("Y", $d)][date("F", $d)][$event_title] = $a;
            $event_data[$a['events_id']] = $a;
// $data_images[] = $images;
         }
        //  debug_r($data);
         $counter = 0;

        $flag_first = false;
        if($id == "") $flag_first = true;
        foreach($data as $year => $events_information)
        {
          //Events Information
          $collapse_year_link = ' data-toggle="collapse" class="collapsed" aria-expanded="false"';
          $collapse_year_div = ' class="nonveg collapse panel-collapse" aria-expanded="false"';
          foreach($events_information as $month => $events)
          {
            // debug(count($month));
            foreach($events as $title => $event)
            {
              // debug_r($event);
              if($id == $event['events_id'])
              {
                // $flag_first = true;
                $collapse_year_link = ' data-toggle="collapse" aria-expanded="true"';
                $collapse_year_div = ' class="nonveg collapse show panel-collapse" aria-expanded="false"';
              }
            }
          }

          // debug_r($events_information);
          echo '<div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title title">
                <a '.$collapse_year_link.'" data-parent="#accordion" href="#nonveg_'.$year.'">
                  '.$year.'
                </a>
              </h4>
            </div><!--/.panel-heading -->
            <div id="nonveg_'.$year.'" '.$collapse_year_div.'>
                <div class="panel-body nested-menu">';

            $yid = 'y'.strtolower($year);
            $temp = '';
            foreach($events_information as $month => $events)
            {
              // debug(count($month));
              $id_slide = $yid.strtolower($month);
              // debug($month.''.count($events));
              
              $temp = '';
              foreach($events as $title => $event)
              {
                // debug_r($event);
                if($id == $event['events_id'])
                {
                  $flag_first = true;
                }
                // debug_r($events);
                $temp .= '<li><a href="?id='.$event['events_id'].'">'.$title.'</a></li>';
              }
              echo '<div class="accordion" id="'.$yid.'">
                      <div class="accordion-item">
                          <h4 class="accordion-header" id="h_'.$id_slide.'">
                            <button class="accordion-button '.($flag_first ? 'collapsed' : '').'" type="button" data-toggle="collapse" data-target="#'.$id_slide.'" aria-expanded="false" aria-controls="'.$id_slide.'">
                            '.$month.'
                            </button>
                          </h4>
                          <div id="'.$id_slide.'" class="accordion-collapse collapse '.($flag_first ? 'show' : '').'" aria-labelledby="h_'.$id_slide.'" data-bs-parent="#'.$yid.'">
                            <div class="accordion-body">'.'
                            <ul>
                              '.$temp.'
                            </ul>
                          </div><!--accordion-body-->
                        </div>
                      </div>
                   </div>';
              $flag_first = false;
              $counter++;
            }
            echo '</div><!--/.panel-body -->
            </div>
            <!--/.panel-collapse -->
        </div>';
         }
         ?>

         <style>
          #nonveg ul, .nonveg ul {
              margin: 0;
              padding: 0;
          }
          div#accordion .element_first{
              list-style:none;
              margin-top:5px !important
          }
          div#accordion .element_first a{
              color: #04a651;
              text-decoration:underline;;
          }
          .accordion-item {
              border: none;
          }
          .accordion-header button.accordion-button {
              padding: 5px 10px;
          }
          .accordion-button:not(.collapsed)
          {
              color:green;
              text-decoration:underline;
              background:none;
          }
          #accordion .panel-body {
              padding: 0;
          }

          .accordion-body {
              padding: 10px 0 10px 15px;
          }
         </style>


      </div>


        </div>

        <div class="col-md-9 row p-0">
        <?php
        $main_heading = '';
        if($id)
        {
          $event_selected = $event_data[$id];
          // debug_r($id);
          $main_heading = '
          <script>
            $("#main_heading").html("'.($event_selected["event_name"]).'");
          </script>';
        }
        else {
          $year_selected = array_values($data)[0];
          $month_selected = array_values($year_selected)[0];
          $event_selected = array_values($month_selected)[0];
          // debug_r($event_selected);
        }
        // debug_r($event_selected);
        foreach($event_selected['images'] as $image)
        {
          // debug_r($image);
          echo '<div class="col-lg-4 col-md-4 col-xs-6 thumb mb-5 ">
                <a href="'.$image['event_image_large'].'" class="fancybox  overflow-hidden d-block" rel="ligthbox ">
                    <img src="'.$image['event_image_small'].'" class="zoom img-fluid img-thumbnail gallery-img-thumbnail"  alt="">
                </a>
            </div>';
          }
          ?>

          </div>
       </div>




    </div>




<style>
.panel-body.nested-menu li a {
    color: gray;
    text-decoration: none;
}.panel-body.nested-menu li {
    list-style-type: '- ';
}
</style>
</div>

  <!--CONTENT AREA END-->

  <?php include("template_parts/contact.php"); ?>
  <?php include("template_parts/footer.php"); ?>
  <?php include("template_parts/footer_scripts.php"); ?>
  <?php include("template_parts/gallery-page-script.php"); ?>
<?php
echo $main_heading;
?>

<!-- https://bootsnipp.com/snippets/aMGnk#google_vignette -->
   </body>
</html>
