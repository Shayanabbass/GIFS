<?php
function display_inbound()
{
	$db = new db2();
	$data_n = array();
	$max_per_page = 20;
	$data_n["html_head"] = "";
	
	if(!$_GET["year"])
	{
		$title = "Select Year &raquo;";
	}
	else
	{
		$year = $_GET["year"];
		$title = '<a href="'.page_url().'">Year: '.$year.'</a> &raquo;';
	}
	if(!$_GET["month"])
	{
		$title .= " Select Month &raquo;";
    }
	else
	{
		$month = $_GET["month"];
		$stringmonth = date("F", mktime(0, 0, 0, ($date))); 
		$title .= ' <a href="?year='.$year.'">Month: '.$stringmonth.'</a> &raquo;';
	}
	
	if(!$_GET["Destination"])
	{
		$title .= " Select Destination &raquo;";
    }
	else
	{
		$Destination = $_GET["Destination"];
		$title .= ' <a href="?year='.$year.'&month='.$month.'">Destination: '.$Destination.'</a> &raquo;';
	}
	$data_n["html_title"] = $title;
	$data_n["html_heading"] = $title;
	
	$temp .= '<div id="typography_lists" class="col-md-12">
				<div class="widget">
					<div class="box-body">';	
	if(!$_GET["year"])
	{
		//display all the records of years
		$sql = "SELECT DISTINCT YEAR(FROM_UNIXTIME(Date)) as Date from data order by Date DESC";
		//echo $sql;
		$result = $db->sql_query($sql);
		$temp .= '<ul class="bullet bullet-purple">';
		foreach($result as $a)
		{
			if($date = $a["Date"])
			{
				$temp .= '<li>';
				$temp .= '<a href="?year='.$date.'">'.$date.'</a> ('.get_count_year($date).')'; 
				$temp .= '</li>';
			}
		}
		$temp .= '</ul>';
	}
	elseif(!$_GET["month"])
	{
		//step 2 now the year is selected
		$sql = "SELECT DISTINCT Month(FROM_UNIXTIME(Date)) as Date from data 
		WHERE YEAR(FROM_UNIXTIME(Date)) = '$year'
		order by Date DESC";
		//echo $sql;
		$result = $db->sql_query($sql);
		$temp = '<ul class="bullet bullet-purple">';
		foreach($result as $a)
		{
			if($date = $a["Date"])
			{
				$stringmonth = date("F", mktime(0, 0, 0, ($date))); 
				$temp .= '<li>';
				$temp .= '<a href="?year='.$year.'&month='.$date.'">'.$stringmonth.'</a>'; 
				$temp .= '</li>';
			}
		}
		$temp .= '</ul>';
	}
	elseif(!$_GET["Destination"])
	{
		//step 3 now the year is selected && month is selected
		$sql = "SELECT Destination, count(*) as total from data 
		group by Destination
		order by Destination ASC";
		//		Limit 0, $max_per_page
		//echo $sql;
		$result = $db->sql_query($sql);
		$temp = '<ul class="bullet bullet-purple">';
		foreach($result as $a)
		{
			$temp .= '<li>';
			$temp .= '<a href="?year='.$year.'&month='.$month.'&Destination='.$a["Destination"].'">'.$a["Destination"].' (Total Rate: '.get_destination_rate($a["Destination"]).' / Count: '.$a["total"].')</a>'; 
			$temp .= '</li>';
		
		}
		$temp .= '</ul>';
	}
	else
	{
		$page = $_GET["page"];
		if(!$page)
		{
			$page = '0';
		}
		//step 3 now the year is selected && month is selected
		$sql = "SELECT * from data 
		where Destination = '$Destination'
		order by Date DESC";
		$result = $db->sql_query($sql);
		$total_rows = count($result);
		
		$sql .= "
		Limit $page, $max_per_page";
		//echo $sql;
		$result = $db->sql_query($sql);
		$temp = '<table width="1000" borders="1" cellpadding="5" cellspacing="0">
		  <tr>
			<td width="50"></td>
			<td width="250">Date</td>
			<td width="100">Source</td>
			<td width="100">Destination</td>
			<td width="100">Seconds</td>
			<td width="100">CallerID</td>
			<td width="100">Disposition</td>
			<td width="100">Cost</td>
			<td width="100">Peer</td>
			<td width="100">Rate</td>
		  </tr>';
		$counter = ($page-1)*$max_per_page;
		foreach($result as $a)
		{
			$counter++;
			//debug($a);
			$rate = round_up(round_up($a["Seconds"]/60, 0)*0.032, 2);
			
			$temp .= '  <tr>
							<td>'.$counter.'</td>
							<td>'.date("m/d/Y H:i:s", $a["Date"]).'</td>
							<td>'.$a["Source"].'</td>
							<td>'.$a["Destination"].'</td>
							<td>'.$a["Seconds"].'</td>
							<td>'.$a["CallerID"].'</td>
							<td>'.$a["Disposition"].'</td>
							<td>'.$a["Cost"].'</td>
							<td>'.$a["Peer"].'</td>
							<td>'.$rate.'</td>
						  </tr>';
			
		
		}
		$temp .= '</table>';
		
		$link = "?year=".$year."&month=".$month."&Destination=".$Destination;
		$first = 0;
		$next = $page+1;
		$previous = $page - 1;
		$temp .= '<div class="pagination" style="text-align:right">
		
						<a href="'.$link.'&page='.$previous.'" class="prev disabled">« Previous</a>';
		for($i=0;$i<$total_rows/$max_per_page;$i++)
		{
			$selected = '';
			if($page==($i+1))
			{
				$selected = ' class="selected"';
			}
			$temp .= '<a href="'.$link.'&page='.($i+1).'"'.$selected.'>'.($i+1).'</a>';
		}
		$temp .= '<a href="'.$link.'&page='.$next.'" class="next">Next »</a>
		
					</div>';
	}
	$temp .='</div> <!-- .box-body -->
		       		</div> <!-- .widget -->
				</div>';
	$data_n["html_text"] = $temp;
	return $data_n;
}
function get_count_year($year)
{
	$db = new db2();
	$sql = "SELECT count(*) as total from data 
	WHERE YEAR(FROM_UNIXTIME(Date)) = '$year'
	";
	//echo $sql;
	$result = $db->sql_query($sql);
	$a = result;
	return($a["total"]);
}
function get_destination_rate($Destination)
{
	$db = new db2();
	//Formula on all seconds then SUM
	$sql = "SELECT * from data 
	where Destination = '$Destination'
	order by Date DESC";
	//echo $sql;
	$result = $db->sql_query($sql);
	$total = 0;
	foreach($result as $a)
	{
		$rate = round_up(round_up($a["Seconds"]/60, 0)*0.032, 2);
		$total += $rate;
		//debug('+ '.$rate .' = '.$total);
	}
	return ($total);
	//SUM of all seconds then formula
	$sql = "SELECT SUM(Seconds) as Seconds from data 
	where Destination = '$Destination'
	order by Date DESC";
	//echo $sql;
	$result = $db->sql_query($sql);
	$a = result;
	$rate = round_up(round_up($a["Seconds"]/60, 0)*0.032, 2);
	return ($rate);
			
}
 function round_up ($value, $places=0) {
  if ($places < 0) { $places = 0; }
  $mult = pow(10, $places);
  return ceil($value * $mult) / $mult;
 }
