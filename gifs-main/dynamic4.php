<?php
include('includes/application_top.php'); 
include('vendor/autoload.php');
$function_name = $_GET["page_function"] ? $_GET["page_function"] : page_name();
// debug_r($function_name);
$e = ("\$data_n = ".$function_name."();");
eval($e);
$data =compile_top($data_n);
//-----------------------------PRINT UNTILL </head>
$data_temp = explode("</head>", $data);
echo $data_temp[0];
$data = $data_temp[1];

$head_currency = '<script>
var currency = [];';
$db = new db2();
$currencies = $db->result("select * From currency");
foreach($currencies as $curr)
{
	$head_currency .= '
		currency['.$curr['currency_id'].'] = '.$curr['currency_rate'].';';
}
$head_currency .= '</script>';
echo $head_currency.$data_n["head"];

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
