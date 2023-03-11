<?php
class ExchangeRate
{
	 /**
     * @var Singleton The reference to *Singleton* instance of this class
     */
		 private static $instance = NULL;
		 private static $exchange_rate;
		 private static $default_currency;
		 
    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return Singleton The *Singleton* instance.
     */
	 
	 //
	 
	 static public function getInstance()
	 {
		 if(NULL === self::$instance)
		 {
            self::$instance = new self();
			$db = new db2();
			$sql = "SELECT * FROM `currency`";
			$result = $db->sql_query($sql, true);
			$data = $result->fetchall(PDO::FETCH_ASSOC);
			$currencies = array();
			foreach($data as $curr)
			{
				$currencies[$curr['currency_id']] = $curr;
				if($curr['currency_rate'] == 1)
				{
					self::$default_currency = $curr;
				}
			 }
			 
			self::$exchange_rate = $currencies;

		 }
	
		 return self::$instance;
	 }
	 
	 public function getExchangeAll()
	 {
		 return self::$exchange_rate;
	 }
	 public function getDefaultCurrency()
	 {
		 return self::$default_currency;
	 }
	 public function getExchange($currency_id)
	 {
		 return self::$exchange_rate[$currency_id];
	 }
	 public function getExchangeRate($currency_id)
	 {
		 return self::$exchange_rate[$currency_id]['currency_rate'];
	 }
	 public function getExchangeName($currency_id)
	 {
		 return self::$exchange_rate[$currency_id]['currency_name'];
	 }

    /**
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     */
	 protected function __construct()
    {
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Private unserialize method to prevent unserializing of the *Singleton*
     * instance.
     *
     * @return void
     */
    private function __wakeup()
    {
    }
}
class currency extends db2
{
	function insert()
	{
		$currency_name = $_REQUEST["currency_name"];
		$currency_rate = $_REQUEST["currency_rate"];
		$sql = 'Insert into currency (currency_id, currency_name, currency_rate) VALUES (\''.$currency_id.'\', \''.$currency_name.'\', \''.$currency_rate.'\')';
		if(!db2::$db->inTransaction())
			db2::$db->beginTransaction();
		
		$result = $this->sqlq($sql);
		$currency_id = db2::$db->lastInsertId();
		//manage log as well
		if($currency_rate != 1)
		{
			$cr = new currency_rate();
			$cr->insert($currency_id, $currency_rate);
		}
		
		db2::$db->commit();
		if($result)
		{
			alert("Currency Inserted Successfully");
			redirect("currency.php?action=new");
		}
	}
	
	function delete()
	{
		$id = $_GET["id"];
		$sql = "DELETE FROM currency WHERE currency_id = ".$id." LIMIT 1;";
		$result = $this->sqlq($sql);
		if($result)
		{
			alert('Currency deleted successfully');
			redirect('currency.php');
		}
		else
		{
			alert('Error! Currency delete failed');
		}
	}
	function update()
	{
		$currency_name = $_REQUEST["currency_name"];
		$currency_rate = $_REQUEST["currency_rate"];
		$id = $_GET["id"];
		$sql = "UPDATE currency SET  
			currency_name='".$currency_name."', 
			currency_rate='".$currency_rate."' WHERE currency_id = ".$id;
		
		if(!db2::$db->inTransaction())
			db2::$db->beginTransaction();
		
		$result = $this->sqlq($sql);
		//manage log as well
		if($currency_rate != 1)
		{
			$cr = new currency_rate();
			$cr->insert($id, $currency_rate);
		}
		
		db2::$db->commit();
		if($result)
		{
			alert("Currency Updated Successfully");
			redirect("currency.php");
		}
		else
		{
			alert("Currency NOT Updated Successfully");
		}
	}
	function edit($id = "")
	{
		$action = "?action=insert";
		$action_label = "Insert ";
		if($id)
		{
			$action = "?action=update&id=".$id;
			$action_label = "Update ";
			$a = get_tuple(currency, $id, "currency_id");
		}
		$temp .= '
			<div class="col-md-12">
			<div class="box">
			<!--<div class="box-header with-border">
				<span class="icon-article"></span>
				<h3>Basic Information</h3>
			</div>  .box-header with-border -->
			<div class="box-body">
			<form class="form uniformForm validateForm" id="form5" name="form5" method="post" action="'.$action.'" enctype="multipart/form-data">';
			$currency_name = $a["currency_name"];
				$temp .= '
				<div class="form-group">
					<label for="services_accounts"> Name:</label>
					<div class="field">
						<input type="text" name="currency_name" id="currency_name" size="32" class="validate[required] form-control" value="'.$currency_name.'" />	
					</div>
				</div> <!-- .form-group -->';
			$currency_rate = $a["currency_rate"];
				$temp .= '
				<div class="form-group">
					<label for="services_accounts"> Rate:</label>
					<div class="field">
						<input type="text" name="currency_rate" id="currency_rate" size="32" class="validate[required] form-control" value="'.$currency_rate.'" />	
					</div>
				</div> <!-- .form-group -->';
		$temp .= '		
			<div class="actions">						
								<button type="submit" class="btn btn-primary">'.$action_label.'Currency</button>
								<a href="currency.php"><button type="button" class="btn">Cancel</button></a>
							</div> <!-- .actions -->
						</form>
					</div> <!-- .box-body -->
				</div>
				 <!-- .widget -->	
		</div>
		<!-- .grid -->';
		return $temp;
	}
	
		function show()
		{	
		$sql = "select * from currency ".$filter;
		$result = $this->result($sql);
		if(count($result)==0)
		{
			$temp .= ' 
			
			<div class="col-md-12">
				<div class="widget">
					
					<div class="box-header with-border">
						<span class="icon-info"></span>
						<h3>Currency</h3>
					</div>
					
					<div class="box-body">
						No Records Currently present.
						<form action="?action=new" target="_self" method="post" name="newForm">
							<p align="center">
								<button class="btn btn-quaternary btn-xlarge"><span class="icon-layers-alt"></span>New Currency</button>
							</p>
						</form>
		            </div> <!-- .box-body -->
		            
		        </div> <!-- .widget -->
					
				</div>
			';
		}
		else
		{
			$temp .= '
			<div class="col-md-12"><!-- .widget -->					
				<div class="box">
				
					<div class="box-header with-border">
						<span class="icon-list"></span>
						<h3 class="icon chart">Manage Currency</h3>		
					</div>
				
					<div class="box-body">
						
						<table class="table table-bordered table-striped data-table">
					<thead>
						<tr>
						<th>Id</th>
						<th>Name</th>
						<th>Rate</th>
						<th>Actions</th>
						</tr>
					</thead>
					<tbody>
			'; 
		}
		foreach($result as $a)
		{
			$id = $a["currency_id"];
			$temp .= '
				<tr class="gradeA">
					<td>'.$a["currency_id"].'</td>
					<td>'.$a["currency_name"].'</td>
					<td>'.$a["currency_rate"].'</td>
					
					<td><a href="?action=edit&id='.$id.'">
						<button class="btn btn-primary">Edit </button>
					  </a>
						</td>
					</tr>';
		}
		if(count($result)>0)
		{
			$temp .= '</tbody></table></div>
					<!--box-body-->
				</div>
				<!--widget-->
			</div><!--col-md-12-->';
		}
		return $temp;
	}
	//$exchange_rates = ExchangeRate::getInstance()->getExchangeAll();
}
//Currency Join Maker
//print_r(ExchangeRate::getInstance()->getExchange(1));
//echo ExchangeRate::getInstance()->getExchangeName(1);

	function get_time_and_company()
	{
		$time1 = adodb_mktime(0, 0, 0, adodb_date("m")-3, 1, adodb_date("Y"));
		$time2 = adodb_mktime(0, 0, 0, adodb_date("m"), adodb_date("d")+1, adodb_date("Y"));
		$filter = '';
		$company_id = "";
		$currency_id = "";
		$visa_type_id = "";
		if($_POST)
		{
			/*foreach($_POST as $key=>$val)
			{
				//$key=>$val
				$_SESSION[$key] = $val;
				debug('
				$'.$key.' = "";
				if($_POST["'.$key.'"])
				{
					$'.$key.' = $_POST["'.$key.'"];
					$_SESSION["'.$key.'"] = $'.$key.';
				}');
			}*/
			
			if($_POST["monthdropdown_start_date"]) $monthdropdown_start_date = $_POST["monthdropdown_start_date"];
			if($_POST["daydropdown_start_date"]) $daydropdown_start_date = $_POST["daydropdown_start_date"];
			if($_POST["yeardropdown_start_date"]) $yeardropdown_start_date = $_POST["yeardropdown_start_date"];
			if($_POST["monthdropdown_end_date"]) $monthdropdown_end_date = $_POST["monthdropdown_end_date"];
			if($_POST["daydropdown_end_date"]) $daydropdown_end_date = $_POST["daydropdown_end_date"];
			if($_POST["yeardropdown_end_date"]) $yeardropdown_end_date = $_POST["yeardropdown_end_date"];
			if($_POST["company_id"]) $company_id = $_POST["company_id"];
			if($_POST["currency_id"]) $currency_id = $_POST["currency_id"];
			if($_POST["visa_type_id"]) $visa_type_id = $_POST["visa_type_id"];
			
				
			$_SESSION["company_id"] = $company_id;
			$_SESSION["currency_id"] = $currency_id;
			$_SESSION["visa_type_id"] = $visa_type_id;
			$time1 = adodb_mktime(0, 0, 0, $_POST["monthdropdown_start_date"], $_POST["daydropdown_start_date"], $_POST["yeardropdown_start_date"]);
			$temp_time2 = adodb_mktime(0, 0, 0, $_POST["monthdropdown_end_date"], $_POST["daydropdown_end_date"], $_POST["yeardropdown_end_date"]);
			if($temp_time2)
				$time2 = $temp_time2;
			else
				$time2 = $time1+86400*386*10;
		}
		else
		{
			if($_SESSION["monthdropdown_start_date"] && $_SESSION["daydropdown_start_date"] && $_SESSION["yeardropdown_start_date"])
				$time1 = adodb_mktime(0, 0, 0, $_SESSION["monthdropdown_start_date"], $_SESSION["daydropdown_start_date"], $_SESSION["yeardropdown_start_date"]);
			if($_SESSION["monthdropdown_end_date"] && $_SESSION["daydropdown_end_date"] && $_SESSION["yeardropdown_end_date"])
				$time2 = adodb_mktime(0, 0, 0, $_SESSION["monthdropdown_end_date"], $_SESSION["daydropdown_end_date"], $_SESSION["yeardropdown_end_date"]);
			if($_SESSION["company_id"])	$company_id = $_SESSION["company_id"];
			if($_SESSION["currency_id"]) $currency_id = $_SESSION["currency_id"];
			if($_SESSION["visa_type_id"]) $visa_type_id = $_SESSION["visa_type_id"];
		}
		$user = get_tuple("users", $_SESSION["users_id"]);
		$company_id2 = $user["company_id"];
		$groups_id2 = $user["groups_id"];
		if($groups_id2 == 3 && $company_id2) 
		{
			$company_id = $user["company_id"];
			$filter = " AND company_id = $company_id";
		}
		else if($company_id) $filter .= " AND company_id = $company_id";
		if($project_id) $filter .= " AND project_id = $project_id";
		
		return array('company_id'=>$company_id, 'time1'=>$time1, 'time2'=>$time2, 'currency_id'=>$currency_id, 'visa_type_id' => $visa_type_id, 'filter' =>$filter);
	}

function get_header_reports($id, $title, $line1, $time1 = '', $time2 = '')
{
	$db = new db2();
	extract(get_time_and_company());
	$temp = '
	<head>
		<title>
		'.COMPANY_NAME.' - '.$title.'
		</title>
			<style type="text/css" title="currentStyle">
				@import "media/css/demo_page.css";
				@import "media/css/demo_table.css";
				@import "extras/TableTools/media/css/TableTools.css";
				.even_gradeA 
				{
					background:#EEE;
				}
				.bottom_borders td
				{
					borders-bottom:black thin dotted;
				}
			</style>
			<style type="text/css" media="print">
				@page 
				{
					size: auto;   /* auto is the current printer page size */
					margin: 10mm;  /* this affects the margin in the printer settings */
				}
		
				body 
				{
					background-color:#FFFFFF; 
					border: solid 1px black ;
					margin: 0px;  /* the margin on the content before printing */
			   }
			</style>
			<style>
		.display_table td, .display_table th
		{
			padding: 5px;
		}
		.display_table th
		{
			border:black 1px solid; background:gray; color: white; font-weight:normal; font-size:16px; font-family:arial;
		}
		</style>

			
		</head>
		<body id="dt_example">
			<div class="" onClick="jQuery(\'.form_hidden\').show()" style="width:800px;">
				<div style="width:600px; float:left">
						<h2>'.COMPANY_NAME.' - '.$title.'</h2>
						Date: '.date('d F Y', $time1).' - '.date('d F Y', $time2).'<br>
						<strong>M/S '.$line1.' Report</strong>
					</div>
				</div>
				<div style="width:200px; float:left; margin-top:10px">
					<img src="images/logo.png" style="float:right" height="80px"/>
				</div>
						<div style="clear:both"></div>
			<div class="form_hidden" style="display:none">
			
			
				<form name="form_ledger" id="form_ledger" action="?action='.$_GET['action'].'&id='.$id.'" method="post">
					'.date_gen("start_date", $time1).' <strong>to</strong> '.date_gen("end_date", $time2).'
					Ledger of '.$line1.'
					 of '.dropdown("company", $company_id).'
					 in '.dropdown("currency", $currency_id).'
				  	 in Product Type '.dropdown_inventory($visa_type_id).'
				<input type="submit" name="Search" id="Search" />
				</form>
				
				</div>';
	return array('company_id'=>$company_id, 'time1'=>$time1, 'time2'=>$time2, 'currency_id'=>$currency_id, 'temp'=>$temp, 'visa_type_id' => $visa_type_id, 'filter' =>$filter);
}