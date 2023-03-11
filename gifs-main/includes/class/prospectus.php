<?php
function prospectus()
{
  // $a = new circular();
  // $id = $_GET["post_id"];
  // $category_id = $_GET["category_id"];
  $action = $_GET["action"];
  $title = 'Prospectus';
  $a = new announcement();
  $data = $a->recent('prospectus');
  if(!$data)
  {
    $data = 'Under Construction';
  }
  $temp = '<div class="recent_activity2">'.$data."</div>";
  $temp .= "";
  if($id)
  {
    // return $a->detail();
  }
  else {
    // $temp = $a->latest();
  }
  $temp .= '
  <style>
  .recent_activity2 div {
      padding: 0 10px 20px;
  }
  .recent_activity2
  {
      display: flex; 
      flex-wrap: wrap;
  }
  </style>';
  $data_n["html_title"] = $title;
  $data_n["html_heading"] = $title;
  $data_n["html_text"] = $temp;
  return $data_n;
}
?>
