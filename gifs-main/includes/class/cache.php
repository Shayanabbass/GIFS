<?php
function cache()
{
	//Default date = 1841 - 01 - 01
	
	$db = new db3();
	//select * from kotITM where KTM_KOT_PR=98890
	$date = date('Y-m-d', time()-86400);
	//debug_r($date);
	$sql="select * from kot where KOT_DATE = '$date' limit 0, 1";
	//KOTITM 
	$rs=odbc_exec(db3::$db, $sql);
	if (!$rs)
	{
	  exit("Error in SQL");
	}
	while ($a = odbc_fetch_array($rs))
	{
		$sql="select * from kotITM where KTM_KOT_PR=".$a['KOT_ROWID'];
		//KOTITM 
		$rs2=odbc_exec(db3::$db, $sql);
		while($b = odbc_fetch_array($rs2))
		{
			//debug($b);
			$sql="select * from MENU where MEN_SLNO=".$b['KTM_MENU_DR'];
			//KOTITM 
			$rs3=odbc_exec(db3::$db, $sql);
			$c = odbc_fetch_array($rs3);
			$text = ($c['MEN_DESC']);
			debug_r($text);
		}
	}
	
	$text = ($c['MEN_ROWID'].'=='.$c['MEN_DESC'].'=='.($c['MEN_DESC']).'---'.$c['strin']);
	$data_n["html_title"] = $title;
	$data_n["html_heading"] = $title;
	$data_n["html_text"] = $text;
	return $data_n;
}