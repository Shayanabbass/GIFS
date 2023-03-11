<?php 
function file_upload()
{
	$directory = 'event_images';
	$action = $_GET['action'];
	if($action == 'save')
	{
		debug($_REQUEST);
		die;
	}
	//
	if (!empty($_FILES) && $action == "upload") {
		$targetPath = upload($_FILES['file'], $directory);
		if($targetPath)
		{
			$targetPath = explode("/", $targetPath);
			$fn = $targetPath[1];
			
			$image = new SimpleImage();
			$image->load($directory.'/'. $fn);
			$image->resizeToWidth(295);
			$image->save($directory.'/small-'.$fn);
			
			$image = new SimpleImage();
			$image->load($directory.'/'. $fn);
			$image->square(120);
			$image->save($directory.'/thumb-'.$fn);
		}
		$array = array("image_big"	=> $targetPath[0].'/'.$targetPath[1],
						"image_small"	=> $directory.'/small-'.$fn,
						"image_thumb" => $directory.'/thumb-'.$fn);
		echo json_encode($array);
	}
	die;
}
