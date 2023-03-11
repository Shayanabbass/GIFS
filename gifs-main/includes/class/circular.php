<?php
function circular()
{
  // $a = new circular();
  // $id = $_GET["post_id"];
  // $category_id = $_GET["category_id"];
  $action = $_GET["action"];
  $title = 'Circular';
  $a = new announcement();
  
    $temp = '<div class="recent_activity">'.$a->recent('circular')."</div>";
    $data_n["html_title"] = $title;
    $data_n["html_heading"] = $title;
    $data_n["html_text"] = $temp;
    return $data_n;
  }
  ?>
  