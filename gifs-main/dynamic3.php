<?php
include('includes/application_top2.php'); 
$function_name = $_GET["page_function"];
if(!$function_name)
{
	$e = ("\$data_n = ".$_GET["page_function"]."();");
}
else
{
	$e = ("\$data_n = ".$_GET["page_function"]."();");
	eval($e);
}
//debug_r($e);
$data =compile_top($data_n);
//-----------------------------PRINT UNTILL </head>
$data_temp = explode("</head>", $data);
echo $data_temp[0];
$data = $data_temp[1];

echo ''.$data_n["head"];
require_once('trafficbuilder/head.php');
echo '</head>';
//left column goes first
	

	//ADD Body
$data_temp = explode("{__BODY__}", $data);

echo $data_temp[0];
$data = $data_temp[1];


echo $data_n["html_text"];
echo $data;
?>