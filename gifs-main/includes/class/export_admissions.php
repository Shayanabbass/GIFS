<?php
function export_admissions()
{
	// debug_r("EXCEL");
	// Create new PHPExcel object
	$mySpreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
	// delete the default active sheet
	$mySpreadsheet->removeSheetByIndex(0);
    // Create "Sheet 1" tab as the first worksheet.
	// https://phpspreadsheet.readthedocs.io/en/latest/topics/worksheets/adding-a-new-worksheet
	$worksheet1 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($mySpreadsheet, "Sheet 1");
	$mySpreadsheet->addSheet($worksheet1, 0);

	$db = new db2();
	$sql = "SELECT
`student_id`, `student_name`, `father_name`, `date_of_birth`, `gender_id`, `religion`, `admission_class`, `last_current_class`,
`last_current_school`, `father_contact_number`, `father_cnic`, `mother_contact_number`, `mother_cnic`, `email_address`, `address`,
 `siblings_in_gifs`, UNIX_TIMESTAMP(added_on) as added_on
	FROM student WHERE 1";
	$result = $db->sql_query($sql, true);
	$data = $result->fetchall(PDO::FETCH_ASSOC);
	$should_be = array(
		0 => 'S.No.',
		1 => 'Student Name',
		2 => 'Father\'s/Husband\'s Name',
		3 => 'Date of Birth',
		4 => 'Gender',
		5 => 'Relegion',
		6 => 'Admission Class',
		7 => 'Last Current Class',
		8 => 'Father\'s Contact Number',
		9 => 'Father\'s CNIC',
		10 => 'Mother\'s Contact Number',
		11 => 'Mother\'s CNIC',
		12 => 'Email Address',
		13 => 'Address',
		14 => 'Siblings',
		15 => 'Added On'
	);




	$column_names = array('', 'student_id', 'student_name', 'father_name', 'date_of_birth', 'gender_id', 'religion', 'admission_class', 'last_current_class',
	'last_current_school', 'father_contact_number', 'father_cnic', 'mother_contact_number', 'mother_cnic', 'email_address', 'address', 'siblings_in_gifs');
	$row_count = 1;
	$column = 'A';
	for($j=0; $j<count($should_be); $j++)
	{
		$worksheet1->setCellValue($column.$row_count, $should_be[$j]);
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
			$worksheet1->setCellValue($column.$row_count, $cell_value);
			$column++;
		}
		$row_count++;
	}
	foreach ($worksheet1->getColumnIterator() as $column)
	{
		$worksheet1->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
	}
	// Save to file.
	$writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($mySpreadsheet);
	// Rename worksheet

	// $output .=  '<table border=1>' . "\n";

	// foreach ($objWorksheet->getRowIterator() as $row) {

	//   $output .=  '<tr>' . "\n";

	//   $cellIterator = $row->getCellIterator();

	//   // This loops all cells, even if it is not set.
	//   // By default, only cells that are set will be iterated.
	//   $cellIterator->setIterateOnlyExistingCells(false);

	//   foreach ($cellIterator as $cell) {
	// 	$output .=  '<td>' . $cell->getValue() . '</td>' . "\n";
	//   }

	//   $output .=  '</tr>' . "\n";
	// }
	if(DEBUG)
	{
		echo $output;
		die;
	}
	// // Redirect output to a client’s web browser (Excel2007)
	// header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	// header('Content-Disposition: attachment;filename="Admissions.xlsx"');
	// header('Cache-Control: max-age=0');
	// // If you're serving to IE 9, then the following may be needed
	// header('Cache-Control: max-age=1');
	// // If you're serving to IE over SSL, then the following may be needed
	// header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	// header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	// header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	// header ('Pragma: public'); // HTTP/1.0
	// $writer->save('php://output');
	if(!file_exists('students_data'))
	{
		mkdir("students_data");
		chmod("students_data", 0755);
		// if(!file_exists('students_data/'.$project_id))
		// {
		//     debug_r("here");
		//     mkdir("students_data");
		//     chmod("students_data", 0755);
		// }
	}
	$filename = 'output_'.time().'.xlsx';
	$fn = 'students_data/'.$filename;
	$writer->save($fn);
	echo '<a href="'.$fn.'" target="_blank">Excel Generated</a>';

	exit;
}
