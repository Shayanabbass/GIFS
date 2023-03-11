<?php
include('includes/application_top2.php'); 
//$data_n = get_tuple("html", page_name(), "html_name");
//debug_r($data_n);
/*
html_id
html_name
html_text
*/

//-----------------------------DATA WITH ALL CONTENTS LOADEDS
$data = compile_top($data_n);

$data = str_replace("{__META_KEYWORDS__}", $data_n["html_meta_keywords"], $data);
$data = str_replace("{__META_DESCRIPTION__}", $data_n["html_meta_description"], $data);

//-----------------------------PRINT UNTILL </head>
$data_temp = explode("</head>", $data);
echo $data_temp[0];
$data = $data_temp[1];
require_once('trafficbuilder/head.php'); 
echo '</head>';

$data = compile_left($data, $data_n["html_text"]);
echo $data;
?>