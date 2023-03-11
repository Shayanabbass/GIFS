<?php
function calendar()
{
  // $a = new circular();
  // $id = $_GET["post_id"];
  // $category_id = $_GET["category_id"];
  $action = $_GET["action"];
  $title = 'Calendar';
  $a = new announcement();

  $temp = '<div class="recent_activity">'.$a->recent()."</div>";
  $temp .= "";
  if($id)
  {
    // return $a->detail();
  }
  else {
    // $temp = $a->latest();
  }
  $data_n["html_title"] = $title;
  $data_n["html_heading"] = $title;
  $data_n["html_text"] = $temp;
  return $data_n;
}
?>
