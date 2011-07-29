<?php
	$base_name = 'projs/' . $_POST['base_name'];
	
	$file_jpeg = $base_name . "jpg";
	$file_png = $base_name . "png";
	$file_gif = $base_name . "gif";

	if (file_exists($file_jpeg)){
		echo "jpg";
	} else if (file_exists($file_png)){
		echo "png";
	} else if (file_exists($file_gif)){
		echo "gif";
	} else {
		echo "failed";
	}
?>