<?php
	function app()
	{
        $a = new app();
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = explode( '/', $uri );
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        // debug($uri);
        // debug($requestMethod);
		//  $action = $_GET["action"];
        $action = $uri[3];
		 switch($action)
		 {
			 case "login":
                /*Req data : Username, Password
                Res data : Success message*/
			 	$a->login($_REQUEST["users_name"], $_REQUEST["users_password"]);
				 break;
			 case "search_member":
                /*Req data : Card no OR phone no
                Res data : Success message, Return list of customers along with id or
                 loyalty card no and current points*/
			 	$a->search_member($_REQUEST["number"]);
				 break;
			 case "add_points":
                /*Req data : Bill no, bill amount, points
                (if points will be calculated by the system then ignore this param)
                Res data : success message*/
			 	$a->add_points(request("guest_id"), $_REQUEST["bill_number"], $_REQUEST["bill_amount"]);
				 break;
			 case "redeem_points":
                /*Req data : bill no, bill amount, points to redeem
                Res data : success msg, redemption code, new amount after deduction
                (if amount will be calculated by the system)*/
			 	$a->redeem_points(request("guest_id"), $_REQUEST["bill_number"], $_REQUEST["bill_amount"], (int)$_REQUEST['points_to_redeem']);
				 break;
			 case "view_points":
                /*Res data : Customer details along with current points, last added points & date, 
                last redeemed points & date*/
			 	$a->view_points($_GET["id"]);
				 break;
			case "msg_reply":
				$a->msg_reply();
				break;
			case "status_call":
				$a->status_call();
				break;
			 case "default":
	 			// $a->show();
		}
        die;
	}