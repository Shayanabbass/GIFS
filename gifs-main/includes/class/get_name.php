<?php
function get_ajax()
{
	$key = $_POST["key"];
	$id = $_POST["id"];
	if(!$id) die();
	$sql = "select * from $key where {$key}_id = $id";
	$db = new db2();
	$result = $db->sql_query($sql, true);
	$data = $result->fetch(PDO::FETCH_ASSOC);
	echo json_encode($data);
	die;
}
function set_ajax()
{
	$key = $_POST["key"];
	$value = rawurldecode($_POST["value"]);
	$db = new db2();
	$sql = "insert into $key ({$key}_name) VALUES ('$value')";
	$result = $db->sqlq($sql);
	$id = db2::$db->lastInsertId();
	$data = array("name"=>$value, "id"=>$id);
	echo json_encode($data);
	die;
}
function get_description()
{
	//header('Content-Type: application/json');
	$value = $_GET['s'];
	$c = $_GET['c'];
	if($c == 'null')
	{
		$filter = '';
	}
	else
		$filter = " AND product_id = $c";
	$sql = "select distinct(description) from orders_details where description like '%$value%' $filter";
	//echo $sql;die;
	$db = new db2();
	$result = $db->sql_query($sql, true);
	$data = $result->fetchall(PDO::FETCH_ASSOC);
	/*$output = array();
	foreach($data as $d)
	{
		$desc = $d['description'];
		$output[] = $desc;
	}
	//$data = array_values($data);
	//print_r($data);*/
	echo $_GET['callback'] . '(' . json_encode($data) .')';
	die;
}
function get_vendor_description()
{
	//header('Content-Type: application/json');
	$value = $_GET['s'];
	$c = $_GET['c'];
	if($c == 'null')
	{
		$filter = '';
	}
	else
		$filter = " AND vendor_id = $c";
	$sql = "select distinct(vendor_description) as vendor_description from orders where vendor_description like '%$value%' $filter";
	//echo $sql;die;
	$db = new db2();
	$result = $db->sql_query($sql, true);
	$data = $result->fetchall(PDO::FETCH_ASSOC);
	/*$output = array();
	foreach($data as $d)
	{
		$desc = $d['description'];
		$output[] = $desc;
	}
	//$data = array_values($data);
	//print_r($data);*/
	echo $_GET['callback'] . '(' . json_encode($data) .')';
	die;
}

function get_ajax_data()
{
	$table = $_POST["table"];
	$column = $_POST["column"];
	$value = $_POST["value"];
	$sql = "select * from $table where $column = $value";
	if($table == 'inventory')
	{
		$sql = "select * from $table i 
			INNER JOIN orders_details o ON o.`orders_details_id` = i.orders_details_id
		where $column = $value AND sales_details_id = 0";
	}
	$db = new db2();
	$result = $db->sql_query($sql, true);
	$data = $result->fetchall(PDO::FETCH_ASSOC);
	echo json_encode($data);
	die;
}