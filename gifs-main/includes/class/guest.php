<?php
	function guest()
	{
		 $a = new guest();
         $action = $_REQUEST["action"];
         if($action == "update" && !$_REQUEST["id"]) $action = "insert";
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
			 	$temp .= $a->edit($_REQUEST["id"]);
				 break;
			 case "new":
			 	$temp .= $a->edit();
				 break;
			 case "default":
	 			$a->show();
        }
        if ($_REQUEST['type'] == 'ajax' && $action == 'edit') {
            echo $temp;
            die;
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
		$data_n["html_title"] = "Guest";
		$data_n["html_heading"] = "Guest";
		$data_n["html_text"] = $temp;
		return $data_n;
	}