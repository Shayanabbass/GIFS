<?php
function export()
{
	// debug_r("EXCEL");
	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();
	// Set document properties
	$objPHPExcel->getProperties()->setCreator("iGAP")
								 ->setLastModifiedBy("Admin")
								 ->setTitle("Office 2007 XLSX Test Document")
								 ->setSubject("Office 2007 XLSX Test Document")
								 ->setDescription("Visa Details Full")
								 ->setKeywords("office 2007 openxml php")
								 ->setCategory("Participants");
								 
	//Traveller Name	Passport Name	Description	Nationality	Sale Type	Client Name	Debit	Credit	Vendor	Company	Sales Person	Typed In	Ticket Date	Air Line
	$db = new db2();
	$sql = "SELECT name, father_name, gender_name, profile_photo_upload, date_of_birth, group_name, date_proof_upload, mobile_number, email, city, country_name, school_maderssa, UNIX_TIMESTAMP(added_on) as added_on
	FROM participant WHERE 1";
	$result = $db->sql_query($sql, true);
	$data = $result->fetchall(PDO::FETCH_ASSOC);
	$should_be = array(
		0 => 'S.No.',
		1 => 'Participant Name',
		2 => 'Father\'s/Husband\'s Name',
		3 => 'Gender',
		4 => 'Profile Photo',
		5 => 'Date of Birth',
		6 => 'Group',
		7 => 'CNIC Number/Passport Number',
		8 => 'Mobile Number',
		9 => 'Email Address',
		10 => 'City',
		11 => 'Country',
		12 => 'School/Madressa/Institution',
		13 => 'Added On'
	);
	
	$column_names = array('', 'name', 'father_name', 'gender_name', 'profile_photo_upload', 'date_of_birth', 'group_name', 'date_proof_upload', 'mobile_number', 'email', 'city', 'country_name', 'school_maderssa', 'added_on');
	$row_count = 1;
	$column = 'A';
	for($j=0; $j<count($should_be); $j++)
	{
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue($column.$row_count, $should_be[$j]);
		$column++;
	}
	$row_count++;
	for($i=0;$i<count($data);$i++)
	{
		$column = 'A';
		for($j=0; $j<count($should_be); $j++)
		{
			$cell_value = $data[$i][$column_names[$j]];
			/*debug($i.'-'.$j.'=='.$column_names[$j].'='.date('d-m-Y', $data[$i][$column_names[$i]]));
			if($i>0)
			{
				echo $column_names[$j];
				debug($data[$i]);
				debug_r(date('d-m-Y', $data[$i][$column_names[$j]]));
			}*/
			if($column_names[$j] == 'added_on') 
			{
				/*if($column_names[$j] == 'ticket_date' && $cell_value)
				{
					debug_r($cell_value);
				}*/
				$cell_value = date('d-m-Y', $cell_value);
			}
			elseif($column_names[$j] == '') 
			{
				$cell_value = $i+1;
			}
			elseif($column_names[$j] == 'date_of_birth') 
			{
				$cell_value = date("d/m/Y", mysql_to_mktime($cell_value));
			}
			elseif($column_names[$j] == 'group_name') 
			{
				$date_of_birth = mysql_to_mktime($data[$i][$column_names[5]]);
				$date_today = time();
				// debug_r( ($date_today - $date_of_birth)/ (3600 * 24 * 365) );
				/**/
				// $junior_start = new Date(2007, 06, 31);
				$junior_end = mktime(0, 0, 0, 7, 1, 2011);
				// $middle_start = new Date(2003, 06, 31);
				$middle_end = mktime(0, 0, 0, 6, 31, 2007);
				$senior_end = mktime(0, 0, 0, 6, 31, 2003);
				
				$group_name = "";
				if($date_of_birth <= $senior_end)
				{
					$group_name = "Senior";
				}
				else if($date_of_birth <= $middle_end)//date_of_birth >= middle_start &&
				{
					$group_name = "Middle";
				}
				else if($date_of_birth <= $junior_end)//date_of_birth >= junior_start && 
				{
					// 31/07/2003
					// 1/08/2007
					$group_name = "Junior";
				}
		
				$cell_value = $group_name;
				/**/ 
			}
			if($cell_value == false)
			{
				$cell_value = '';
			}
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($column.$row_count, $cell_value);
			$column++;
		}
		$row_count++;
	}
	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('Participants');
	
	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	
	
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
	if(DEBUG)
	{
		echo $output;
		die;
	}
	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="01simple.xlsx"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');
	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
	exit;

}