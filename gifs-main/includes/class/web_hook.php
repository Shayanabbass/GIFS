<?php
function web_hook()
{
	// debug_r($_REQUEST);
	try {
		$headers = email_header();
		$body = print_r(array(page_url(), $_REQUEST, $_SESSION), 1);
		mail("muhammad.hasnain@thebaronhotels.com", "All Hook s", $body, $headers);
	
    $json = file_get_contents("php://input");
    $order = json_decode($json);
	$a = new payment();
	if($order->eventName)
	{
		$sql = "INSERT INTO orders (  event, 
				  outlet, 
				  reference, 
				  email, 
				  currency, 
				  amount ) VALUES (  '".$order->eventName."',
									  '".$order->order->outletId."',
									  '".$order->order->reference."',
									  '".$order->order->emailAddress."',
									  '".$order->order->amount->currencyCode."',
									  '".number_format($order->order->amount->value / 100, 2)."'
								  );";      
		$a->sqlq($sql);
        $subject = 'Order HOOK Payment Gateway';
		$message = '
		<table width="800" border="0" cellspacing="0" cellpadding="5">
			<tr>
				<td width="101"><strong>Event</strong></td>
				<td width="3">:</td>
				<td width="666">'.$order->eventName.'</td>
			</tr>
			<tr>
				<td width="101"><strong>Outlet</strong></td>
				<td width="3">:</td>
				<td width="666">'.$order->order->outletId.'</td>
			</tr>
			<tr>
				<td width="101"><strong>Reference</strong></td>
				<td width="3">:</td>
				<td width="666">'.$order->order->reference.'</td>
			</tr>
			<tr>
				<td width="101"><strong>Email</strong></td>
				<td width="3">:</td>
				<td width="666">'.$order->order->emailAddress.'</td>
			</tr>
			<tr>
				<td width="101"><strong>Currency</strong></td>
				<td width="3">:</td>
				<td width="666">'.$order->order->amount->currencyCode.'</td>
			</tr>
			<tr>
				<td width="101"><strong>Amount</strong></td>
				<td width="3">:</td>
				<td width="666">'.number_format($order->order->amount->value / 100, 2).'</td>
			</tr>
		</table>';
		$message1 = '<br>'.email_footer();
        mail("muhammad.hasnain@thebaronhotels.com", $subject, $message1.$message, $headers);
	}
	//order_log
  }
  catch(PDOException $e) 
  {
    
    $sql = "INSERT INTO `order_log` (`id`, `event`, `outlet`, `reference`, `email`, `currency`, `amount`) VALUES (NULL, 'General Error', NULL, '".$e->getMessage()."', NULL, NULL, NULL);";

    //echo '{"error":{"text":'.  .'}}';
  
    $a = null;      
  }
	die;
}