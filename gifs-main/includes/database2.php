<?php
class db2
{
	public static $db;
	private static $instance = NULL;

	static public function getInstance()
	{
		if(NULL === self::$instance)
		{
			self::$instance = new self();
			try
			{
				self::$db = new PDO('mysql:host='.DB_SERVER.';dbname='.DB_NAME.';charset=utf8', DB_SERVER_USERNAME, DB_SERVER_PASSWORD);

			} catch (PDOException $e) {
			    echo 'Connection failed: ' . $e->getMessage();
			    exit;
			}

		}
		return self::$instance;
	}

	public static function fetchall($sql)
	{
		try {
			//connect as appropriate as above
			$stmt = self::$db->query($sql); //invalid query!
			if ($stmt === false){
				if(self::$db->inTransaction())
					self::$db->rollBack();
				$errorInfo = self::$db->errorInfo();
				echo $sql;
				debug($errorInfo);
				echo $errorInfo;die;
				//log the error or take some other smart action
			}
			$data = $stmt->fetchall(PDO::FETCH_ASSOC);
			return $data;
		} catch(PDOException $ex) {
			if(self::$db->inTransaction())
				self::$db->rollBack();
			echo "An Error occured!"; //user friendly message
			echo $ex->getMessage();
			die;
			//some_logging_function($ex->getMessage());
		}
	}
	function __construct()
	{
		if(db2::$db) return db2::$db;
    $query = 'mysql:host='.DB_SERVER.';dbname='.DB_NAME.';charset=utf8';
		// print_r(array($query, DB_SERVER_USERNAME, DB_SERVER_PASSWORD));die;
		try
			{
				self::$db = new PDO('mysql:host='.DB_SERVER.';dbname='.DB_NAME.';charset=utf8', DB_SERVER_USERNAME, DB_SERVER_PASSWORD);

			} catch (PDOException $e) {
			    echo 'Connection failed: ' . $e->getMessage();
			    exit;
			}
	}
	function c()
	{
		global $link;
		$link = 1;
		echo($link);
	}
	function result($sql, $is_single = false)
	{
		$result = db2::sql_query($sql, true);
		if($is_single)
		{
			return $result->fetch(PDO::FETCH_ASSOC);
		}
		else
			return $result->fetchall(PDO::FETCH_ASSOC);
	}

	function sql_query($sql, $is_PDO = true)
	{
		if($is_PDO)
		{
			try {
				//connect as appropriate as above
				$stmt = db2::$db->query($sql); //invalid query!
				if ($stmt === false){
					if(db2::$db->inTransaction())
						db2::$db->rollBack();
					$errorInfo = db2::$db->errorInfo();
					if(strpos($errorInfo[2], 'SELECT list is not in GROUP BY clause and contains nonaggregated column') !== false)
					{
						$this->sql_query("SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
						$this->sql_query($sql);
						return;
					}
					//forced elsesalam
					echo "SQL = " . $sql;
					debug($errorInfo);
					echo $errorInfo;
					die;
					//log the error or take some other smart action
				}
				return $stmt;
			} catch(PDOException $ex) {
				if(db2::$db->inTransaction())
					db2::$db->rollBack();
				echo "An Error occured!"; //user friendly message
				echo $ex->getMessage();
				die;
				//some_logging_function($ex->getMessage());
			}

		}
		echo 'database2.php - Line 94: ';
		echo 'sql_query("'.$sql.'")';
		die;
	}

	function sqlq($sql, $error_is_handled = false)
	{
		/**/
		$stmt = db2::$db->prepare($sql);
		if ($stmt === false){
			if(db2::$db->inTransaction())
				db2::$db->rollBack();
			echo "Prepare fail : ".$sql;
			$errorInfo = $stmt->errorInfo();
			echo $errorInfo;die;
			//log the error or take some other smart action
		}
		$status = $stmt->execute($param_values);
		if ($status === false){
			if(db2::$db->inTransaction())
				db2::$db->rollBack();
			$error_info =  $stmt->errorInfo();
			if($error_is_handled) throw new Exception($error_info[2]);
			debug("Execute fail : ".$sql);
			echo 'debugDumpParams: ';
			debug($stmt->debugDumpParams());
			echo 'errorInfo: ';
			$errorInfo = $stmt->errorInfo();
			debug_r($errorInfo);
			//log the error or take some other smart action
		}
		return $stmt;
		// }
		// $con = mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
		// mysql_set_charset('utf8',$con);
		// mysql_select_db(DB_NAME,$con);
		// if(!($result = mysql_query($sql, $con)))
		// {
		// 	die($sql.'<br /><br />'.'Error: '.mysql_error());
		// }
		// return 1;
	}

	function sql($sql)
	{
		debug_r($sql);
		$con = mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
		mysql_set_charset('utf8',$con);
		mysql_select_db(DB_NAME,$con);
		$result = mysql_query($sql, $con);
		return $result;
	}
	function insert_array($param_values, $table_name)
	{
		$columns = array_keys($param_values);
		$column_list = implode(', ', $columns);
		$values 	= array_map("concat", $columns);
		$param_list = implode(', ', $values);
		$sql = "INSERT INTO `$table_name` ($column_list) VALUES ($param_list)";
		//debug_r($sql);
		try {
			//connect as appropriate as above
			$stmt = db2::$db->prepare($sql);
			if ($stmt === false){
				echo "Prepare fail : ".$sql;
				$errorInfo = $stmt->errorInfo();
				echo $errorInfo;die;
				//log the error or take some other smart action
			}
			$status = $stmt->execute($param_values);
			if ($status === false){
				echo "Execute fail : ".$sql;
				echo $stmt->debugDumpParams();
				$errorInfo = $stmt->errorInfo();
				echo $errorInfo;die;
				//log the error or take some other smart action
			}
			return $status;
		} catch(PDOException $ex) {
			echo "An Error occured!"; //user friendly message
			echo $ex->getMessage();
			die;
			//some_logging_function($ex->getMessage());
		}
	}
	function get_closing_row($id, $year, $all = '=', $company_id)
	{
		if($company_id)
		{
			$filter = " AND company_id = $company_id";
		}
		$sql_closing = "select * from closing where account_heads_id = $id AND year $all $year $filter
			ORDER BY year desc limit 0, 1";
		$result_closing = $this->result($sql_closing);
		$amount_debit = 0;
		$amount_credit= 0;
		$balance = 0;
		if(count($result_closing) < 1) return;
		foreach($result_closing as $closing)
		{
			if($account_heads['account_head_type_id'] == 1)
			{
				$amount_debit += $closing['closing_balance'];
				$balance += $amount_debit - $amount_credit;
			}
			else
			{
				$amount_credit += $closing['closing_balance'];
				$balance += $amount_credit - $amount_debit;
			}
		}

		$temp = '<tr class="'.$grade.'_gradeA bottom_borders" onclick="(this.style.backgroundColor==\'rgb(255, 204, 204)\')?this.style.backgroundColor=\'#CCFFCC\':this.style.backgroundColor=\'#FFCCCC\';">
				<td>31/12/'.$year.'</td>
				<td>786110</td>
				<td><a href="closing.php?action=edit&id='.$closing["id"].'" target="_blank">Year End Closing</a>
					<small style="color:green">Added By : '.$closing["added_by"].'  on '.$closing['added_on'].' </small>
			 <!-- -->
				</td>
				<td></td>
				<td></td>
				<td></td>
				<td class="center">'.price($amount_credit?$amount_credit:"").'</td>
				<td class="center">'.price($amount_debit?$amount_debit:"").'</td>
				<td class="center">'.price($balance).'</td>
			</tr>';
		return array(
			'amount_debit' => $amount_debit,
			'amount_credit' => $amount_credit,
			'temp' => $temp,
			'balance'=>$balance
		);
	}
}
function concat($col){
	return ':'.$col;
}
