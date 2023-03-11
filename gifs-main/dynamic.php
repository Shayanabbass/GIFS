<?php
include('includes/application_top.php'); 
eval(admin_top());
$data_n = array("html_title"=> page_comment());
$data = compile_top($data_n);
// debug_r($data_n);
$data_temp = explode("</head>", $data);
echo $data_temp[0];
$data = $data_temp[1];
// debug_r($data);
require_once('trafficbuilder/head.php'); 
echo '</head>';

	//ADD Body
$data_temp = explode("{__BODY__}", $data);
echo $data_temp[0];
$data = $data_temp[1];

eval(admin_middle());


echo $data;
?>