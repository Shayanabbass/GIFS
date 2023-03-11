<?php
$column_names = array('s.no', 'application_date', 'traveller_name', 'passport_number', 'description', 'nationality_id', 'visa_type_id', 'client_id', 'debit_', 'debit_currency_id', 'debit_currency_rate_', 'credit_', 'credit_currency_id', 'credit_currency_rate_', 'vendor_id', 'company_id', 'sales_person_id', 'typing_location_id', 'ticket_date', 'air_line');

$should_be = array(
	0 => 'S.No.',
	1 => 'Application Date',
	2 => 'Traveller Name',
	3 => 'Passport Name',
	4 => 'Description',
	5 => 'Nationality',
	6 => 'Sale Type',
	7 => 'Client Name',
	8 => 'Debit',
	9 => 'Debit Currency',
	10 => 'Debit Rate',
	11 => 'Credit',
	12 => 'Credit Currency',
	13 => 'Credit Rate',
	14 => 'Vendor',
	15 => 'Company',
	16 => 'Sales Person',
	17 => 'Typed In',
	18 => 'Ticket Date',
	19 => 'Air Line'
);

$match_column = array(
	0 => '',
	1 => '',
	2 => '',
	3 => '',
	4 => '',
	5 => 'nationality',
	6 => 'visa_type',
	7 => 'client',
	8 => '',
	9 => 'currency',
	10 => '',
	11 => '',
	12 => 'currency',
	13 => '',
	14 => 'vendor',
	15 => 'company',
	16 => 'sales_person',
	17 => 'typing_location',
	18 => '',
	19 => '',
);
function import()
{
	global $column_names, $should_be;
/*	$column_names = array('application_date', 'traveller_name', 'passport_number', 'nationality_id', 'visa_type_id', 'client_id', 'typing_location_id', 'vendor_id', 'debit_', 'credit_', 'company_id');
	
	$column_names = array('', 'application_date', 'traveller_name', 'passport_number', 'description', 'nationality_id', 'visa_type_id', 'client_id', 'debit_', 'credit_', 'company_id', 'vendor_id', 'typing_location_id', 'sales_person_id', 'typing_location_id', 'ticket_date', 'air_line');
*/	
		
	$db = new visa();
	$action = $_GET['action'];
	if($action == 'import_confirm')
	{
		$data = $_SESSION['data'];
		foreach($data as $row)
		{
			extract($row);
			if(!$passport_number)
			{
				/*if(!$traveller_name)*/ //continue;
			}
			else
			{
				/*$sql_valid = "select * from visa where application_date = ".mysql_to_mktime($application_date)." AND passport_number = '$passport_number' AND nationality_id = (select nationality_id from nationality where nationality_name like '$nationality_id')";
				$result_valid = $db->sql_query($sql_valid, true);
				$data_valid = $result_valid->fetch(PDO::FETCH_ASSOC);
				if($data_valid)
				{
					debug_r($data_valid);
					continue;
				}*/
			}
			
			for($i=0; $i<count($column_names); $i++)
			{
				
				$temp_val = update_column($$column_names[$i] ,  $i, true);
				if(!$passport_number && ($column_names[$i] == 'nationality_id' || $column_names[$i] == 'typing_location_id'))
				{
					$temp_val[0] = $$column_names[$i];
				}
				//debug($column_names[$i], $column_names[$i]);
				$$column_names[$i] = $temp_val[0];
				//debug('-----------------'.$column_names[$i] .' == '.$$column_names[$i]);
			}
			//echo '<hr />';
			$application_date = mysql_to_mktime($row["application_date"]);
			if($row["ticket_date"])
				$ticket_date = mysql_to_mktime($row["ticket_date"]);
			else
				$ticket_date = '';
			//check for validity
			/*$sql_valid = "select * from visa where application_date = $application_date AND passport_number = '$passport_number' AND nationality_id = $nationality_id";
			$result_valid = $db->sql_query($sql_valid, true);
			$data_valid = $result_valid->fetch(PDO::FETCH_ASSOC);
			if($data_valid)
			{
				continue;
				debug_r($data_valid);
			}*/
			/*debug(array($debit_, $credit_));
			debug_r($row);
			die;*/
			//debug_r(array($application_date, $traveller_name, $passport_number, $description, $nationality_id, $visa_type_id, $client_id, $typing_location_id, $vendor_id, $debit_, $credit_, $company_id, $ticket_date, $air_line));
			$db->insert_r($application_date, $traveller_name, $passport_number, $description, $nationality_id, $visa_type_id, $client_id, $typing_location_id, $vendor_id, $sales_person_id, $debit_, $credit_, $debit_currency_id, $credit_currency_id, $debit_currency_rate_, $credit_currency_rate_, $company_id, $ticket_date, $air_line);
		}
		if(!DEBUG)
		{
			if(db2::$db->inTransaction())
				db2::$db->commit();
			alert("Visas Imported Successfully");
			redirect(page_url());
		}
		else
		{
			debug("Visas Imported");
			db2::$db->rollBack();
			die;
		}
		$output = 'Data Inserted Sucessfully';
	}
	else if($action == "import")
	{
		$_SESSION['data'] = '';
		if($_FILES['import'] && $_FILES['import']['size'] > 0 || 1)
		{
			//$file_uploaded = upload($_FILES['import'], '/uploaded');
			$objPHPExcel = PHPExcel_IOFactory::load($_FILES['import']['tmp_name']);
			//debug($_FILES);
			//debug_r($file_uploaded);
			//$file_uploaded = "160107qb7import.xlsx";
			//$objPHPExcel = PHPExcel_IOFactory::load($file_uploaded);
			$objWorksheet = $objPHPExcel->getActiveSheet();
			
			
			$output .=  '<table border=1>' . "\n";

			foreach ($objWorksheet->getRowIterator() as $row) {
			
			  $output .=  '<tr>' . "\n";
			
			  $cellIterator = $row->getCellIterator();
			
			  // This loops all cells, even if it is not set.
			  // By default, only cells that are set will be iterated.
			  $cellIterator->setIterateOnlyExistingCells(false);
			
			  foreach ($cellIterator as $cell) {
				$output .=  '<td>' . $cell->getValue() . '</td>' . "\n";
			  }
			
			  $output .=  '</tr>' . "\n";
			}


			$first = true;
			$header = array();
			$data = array();
			$i = -1;
			$data_new = array();
			foreach ($objWorksheet->getRowIterator() as $row)
			{
				$i++;
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false); // This loops all cells,
																 // even if it is not set.
																 // By default, only cells
																 // that are set will be
																 // iterated.
				$j = -1;
				foreach ($cellIterator as $cell)
				{
					$j++;
					if($first)
					{
						if($j >= 0)
						{
							$header[$j] = $cell->getValue();
							if($header[$j] != $should_be[$j])
							{
								alert("The $j Column ".$header[$j].' should be '.$should_be[$j]);
							}
						}
					}
					else
					{
						//search for $match_column
						$temp_val = update_column($cell->getValue(), $j);
						if($temp_val[1])
						{
							//$temp_match_column = $match_column[$j];
							$data_new[$should_be[$j]][] = $temp_val[1];
						}
						//if($temp_match_column == "vendor" || $temp_match_column == "client" || $temp_match_column == "sales_person")
		
						if($j >= 0)
						{/*
						echo '<hr>';
						echo $temp_val[0];
						debug($header);
						debug($data);*/
							$data[$i][$column_names[$j]] = $temp_val[0];/*
						debug($data);
						echo '<hr>';
						if($i>10)die;*/
							/*if($column_names[$j-1] == "debit_currency_id")
							{
								debug($temp_val);
								debug($data[$i][$column_names[$j-1]]);
								debug($data[$i]);
								debug_r($data);
							}*/
						}
					}
					//insert record into db
				}
				if($first)
				{
					//set cell headers
					$first = false;
					$i=-1;
				}
				else
				{
					extract($data[$i]);
				/*	if(!$passport_number)
					{
						if(!$traveller_name)
						{
							$data_valid['ddd'] = $i;
							$rows_skipped[] = $data_valid;
						}
					}
					else
					{
						$sql_valid = "select * from visa where application_date = ".mysql_to_mktime($application_date)." AND passport_number = '$passport_number' AND nationality_id = (select nationality_id from nationality where nationality_name like '$nationality_id')";
						$result_valid = $db->sql_query($sql_valid, true);
						$data_valid = $result_valid->fetch(PDO::FETCH_ASSOC);
						if($data_valid)
						{
							$data_valid['ddd'] = $i;
							$rows_skipped[] = $data_valid;
						}
					}*/
				}
			}
			for($i=count($data)-1; $i>=0; $i--)
			{
				$row = $data[$i];
				if($row['debit_'] == "")
				{
					unset($data[$i]);
				}
			}
			//debug_r($data);
			$_SESSION['data'] = $data;
			if($data)
			{
				$output .= 'Data ready for import. <a href="?action=import_confirm">Click here to continue</a> <br>
				Total Records to be imported = '.count($data).'<br>';
				//$output .= '<pre>'.print_r($data, 1).'</pre>';
				if($rows_skipped)
				{
					$output .= 'Total Records that will be skipped : '.count($rows_skipped).'<br>';
					$output .= implode(', ', $should_be).'<br>';
					$i = 0;
					foreach($rows_skipped as $curr_row)
					{
						$output .= ++$i.') '.implode(', ', $curr_row).'<br>';
					}
				}
			}
			else
				$output = 'No Data to be imported';
			if($data_new)
			{
				foreach($data_new as $key=>$value)
				{
					$output .= '<p>'.$key.' to be added <br><ul><li>'.implode('</li><li>', array_unique($value)).'</li></ul></p>';
					
				}
			}
			$output .= '<br><br>';
			//unlink($file_uploaded);
			//$data = new Spreadsheet_Excel_Reader($file_uploaded);
			//echo $data->dump(true,true);
		}
		else
		{
			alert('Please upload an .xlsx file');
			redirect(page_url());
		}
	}
	//debug_r($_SESSION["temp_removed_months"]);
	//unset($_SESSION["temp_removed_months"]);
	//debug_r($_SESSION);
	/*
		Now to optimize the runtime of script we will only run it for 10 seconds and then refresh the page 
		or
		we will use some sort of ajax to do this
	*/
	
	/*
		1. Display List of files from uploaded directory
		2. Get the selected file and start importing it 
		3. Remove the extra entry that has already been done 
		3.1. for removal go month and year wise. That is to remove the entire month and year whose data is to be inserted (this is done by $temp_removed_months array
	*/
	$data_n["html_head"] = '';
	
	// Open a known directory, and proceed to read its contents
	$temp .= '<div id="typography_lists" class="col-md-12">
					<div class="widget"><div class="box-body">';
	if($action && $output)
	{
		$temp .= $output;
	}
	$temp .= '	<form enctype="multipart/form-data" method="post" action="?action=import">
								<input type="file" name="import">
								<input type="submit" name="submit" id="submit">
							</form>';
	
	$temp .= '
						</div> <!-- .box-body -->
		       		</div> <!-- .widget -->
				</div> <!-- .grid -->';
				
	$data_n["html_text"] = $temp;
	$data_n["html_title"] = "Import CSV";
	$data_n["html_heading"] = "Import CSV";
	return $data_n;
}
function generate_csv()
{
	
}
function load_to_database()
{
	$mtime = microtime();
	$mtime = explode(" ",$mtime); 
	$mtime = $mtime[1] + $mtime[0]; 
	$starttime = $mtime;
	//if started then just save that time
	if(!$_SESSION["script_start"])
	{
		$_SESSION["script_start"] = $starttime;
	}
	//
	$start = $_POST["line_number"];
	if(!$start || $start == "undefined" || $start == "")
	{
		$start = 1;
	}
	$lines_per_session = LINES_PER_SESSION;
	$file = $_POST["file"];
	if(!$file)die("File not Passed");
	///debug_r($start);
	$lines = file(DIR."/".$file); 
	$db = new db2();
	for($i=$start;$i<count($lines) && $i<($start+$lines_per_session);$i++)
	{
		//END OF GET COLUMN NAMES
		//we have the column names
		// 8 == since we have only 8 columns in the databse
		$cols = explode(",", $lines[0], 8);
		//remove extra ",,,," from 8th column, i.e. column 7th Index of data
		$cols[7] = str_replace(",", '', $cols[7]);
		//just in case there are empty columns | This line is not necessary and probabbly should be removed
		$cols = array_filter($cols, 'strlen');
	//END OF GET COLUMN NAMES
		//we are traversing the data
		$data = explode(",", $lines[$i]);
		//there will be columns > 8 and index >7 but we will use only till 7th index
		//		since 8 cols only 
		//$data[7] = str_replace(",", '', $data[7]);
		
		//to make a dynamic query in case of swapping of columns
		$sql = "Insert into data (
		";
		$values = '';
		//now a new var $flag_data to check if there is data or not	| for every row
		$flag_data = false;
		for($j=0;$j<8;$j++)
		{
			if($cols[$j] == "Date")
			{
				$temp_dt = explode(' ', $data[$j]);
				//$temp_d == 12/1/2010 in mm/dd/YYYY format
				$temp_d = explode('/', $temp_dt[0]);
				//temp_r = 12:23 in HH:MM 24 hour format
				$temp_r = explode(':', $temp_dt[1]);
				//now to save date in mktime
				//debug($data[$j]);
				$final_date = mktime($temp_r[0], $temp_r[1], 0, $temp_d[0], $temp_d[1], $temp_d[2]);
				//get month range to delete
				$month_start_date = mktime(0, 0, 0, $temp_d[0], 1, $temp_d[2]);
				//Since 0 is the last day of previous month
				//for end date of current month we will need the previous date of the next month
				$month_end_date = mktime(0, 0, 0, $temp_d[0]+1, 0, $temp_d[2]);
				//check if the month has already been removed or not
				if($_SESSION["temp_removed_months"][$temp_d[2]] != $temp_d[0])
				{
					$_SESSION["temp_removed_months"][$temp_d[2]] = $temp_d[0];
					//debug($temp_removed_months);
					$sql_delete = "delete from data where Date >= $month_start_date AND Date <= $month_end_date";
					$db->sql_query($sql_delete);
					//debug_r("range = ". date("r", $month_start_date) .' - '.date("r", $month_end_date));
					$sql_delete = '';
				}
				$data[$j] = $final_date;
				//debug(date("r", $data[$j]));
				//echo '<hr>';
				$flag_data = true;
			}
			if($j > 0)
			{
				$sql .= ', ';
				$values .= ', ';
			}
			$sql .= $cols[$j];
			$values .= "'".$data[$j]."'";
		}//end of for
		$sql .= ') VALUES ('.$values.')';
		if($flag_data)
			$db->sql_query($sql);
		else
			echo $sql;
		//for(
	}//end of lines loop
	
	$mtime = microtime(); 
	$mtime = explode(" ",$mtime); 
	$mtime = $mtime[1] + $mtime[0]; 
	$endtime = $mtime; 
	$totaltime = ($endtime - $starttime); 
	//$percent_done
	$total_lines = count($lines);
	$done = $start+$lines_per_session;
	if($done > $total_lines)
	{
		$done = $total_lines;
	}
	$percent_done = round(($done)/$total_lines*100, 2);
	if($lines_per_session > $done)
		$lines_per_session = $done;
	echo '
	
	
	Total Script runtime is '.$totaltime.' seconds, to import '.$lines_per_session.' lines from '.$start.' to '.$done.' of total '.$total_lines.'
	<div class="progress-bar primary">
		<div class="bar" style="width: '.$percent_done.'%;">'.$percent_done.'%</div>
	</div>'; 
	if($percent_done < 100)
	{
		echo '<script>
			load_file_start("'.$file.'", '.($start+$lines_per_session).');
		</script>';
	}
	else
	{
		//unset $_SESSION["temp_removed_months"]
		unset($_SESSION["temp_removed_months"]);
		$starttime = $_SESSION["script_start"];
		unset($_SESSION["script_start"]);
		$endtime = $mtime; 
		$totaltime = ($endtime - $starttime);
		echo '<div class="progress-bar secondary">
					<div class="bar" style="width: 100%;">Total Time: '.$totaltime.' seconds</div>
				</div>';
	}
	
	//alert("Data Imported Successfully");
	//redirect("index.php");
	die;
}
function update_column($temp_val, $j, $do_replace = false)
{
	if(strtolower(trim($temp_val)) == "") return array('', '');
	global $temp_match_column;
	//debug($temp_val);
	$db = new db2();
	global $match_column;

	$temp_match_column = $match_column[$j];
	
	$temp_match_column_id = $temp_match_column.'_id';
	$temp_match_column_name = $temp_match_column.'_name';
	if($temp_match_column)
	{
		if($temp_match_column_name == "company_name" && $temp_val == '')
		{
			$temp_val = 'Karachi Branch';
		}
		//visa_type_id
		/*echo $j.'='.$temp_match_column;
			debug($temp_val);*/
		$filter = '';
		if($temp_match_column_name == "visa_type_name")
		{
			$temp_match_column = 'account_heads';
			$temp_match_column_id = "id";
			$temp_match_column_name = "name";
			$filter = " AND LOWER(".$temp_match_column_name.") like '%inventory%'";
		}
		/*echo $temp_match_column;
			debug_r($temp_val);*/
		$search_match_sql = "select * From ".$temp_match_column." where LOWER(".$temp_match_column_name.") like '%".strtolower(trim($temp_val))."%' $filter";
		
//			if($column_names[$j-1] == "debit_currency_id")

		//if($temp_match_column == "sales_person") debug($search_match_sql.'<hr>');
		//debug($search_match_sql);
		$result_match = $db->sql_query($search_match_sql, true);
		$data_match = $result_match->fetch(PDO::FETCH_ASSOC);
		/*if($temp_match_column_name != 'nationality_name' && $temp_match_column_name != 'vendor_name' && $temp_match_column_name != 'company_name' && $temp_match_column_name != 'client_name' && $temp_match_column_name != 'typing_location_name' && strtolower($temp_val) != '')
		{
			echo $temp_val;
			
			debug($data_match);
			debug_r($search_match_sql);
		}*/
		//$dta = get_tuple($temp_match_column, $temp_val, $temp_match_column.'_name');
		if($data_match)
		{
			//found correctly
			if($do_replace)
				$temp_val = $data_match[$temp_match_column_id];
		}
		else
		{
			//can add typing_location
			if($temp_match_column == "vendor" || $temp_match_column == "client" || $temp_match_column == "sales_person")
			{
				if($temp_val)
				{
					$sql_insert = "insert into $temp_match_column ($temp_match_column_name) values ('$temp_val')";
					$vendors_new = $temp_val;
					if($do_replace)
					{
						$db->sqlq($sql_insert, true);
						$temp_val = db2::$db->lastInsertId();
					}
				}
			}	
			/*
			//rest cannot be added
			elseif($temp_match_column_name == "typing_location")
			{
				
			}*/
			/*debug($temp_match_column);
			echo $search_match_sql;
			debug_r($temp_val.' missmatch');*/
		}
	}
	
	return array($temp_val, $vendors_new);
}