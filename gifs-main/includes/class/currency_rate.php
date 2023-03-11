<?php
function currency_rate()
{
	$a = new currency_rate();
	$action = $_GET["action"];
	switch($action)
	{
		 case "insert":
			$a->insert();
			 break;
		 case "delete":
			$a->delete();
			 break;
		 case "update":
			$a->update();
			 break;
		 case "delete":
			$a->delete();
			 break;
		 case "edit":
			$temp .= $a->edit($_GET["id"]);
			 break;
		 case "new":
			$temp .= $a->edit();
			 break;
		 case "default":
			$a->show();
	}
	if(!$action)
		$temp = $a->show();
	$data_n = array();
	if($action == "edit" || $action == "new")
	{
		$data_n["html_head"] = '
		<script>
		$(function () {	
			$("#confirm").live ("click", function (e) {
				e.preventDefault ();
				$.alert ({ 
					type: "confirm"
					, title: "Alert"
					, text: "<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do dolor sit amet, consectetur adipisicing elit, sed do.</p>"
					, callback: function () { location.href=\'test2.php?action=delete&id='.$_GET["id"].'\'; }	
				});		
			});
		});
		</script>';
	}
	$data_n["html_title"] = "Currency_rate";
	$data_n["html_heading"] = "Currency_rate";
	$data_n["html_text"] = $temp;
	return $data_n;
}