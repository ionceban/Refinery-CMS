<?php
	$image_name = basename($_POST['filename']);
	$original_height = $_POST['orig_height'];
	$original_width = $_POST['orig_width'];
	$scaled_height = $_POST['scaled_height'];
	$scaled_width = $_POST['scaled_width'];
	
	$aux_arr = preg_split('/_t_thumber\./', $image_name, 2);
	$file_base = $aux_arr[0];
	$file_ext = $aux_arr[1];
	
	$normal_name = 'projs/' . $file_base . '_t_normal.' . $file_ext;
	$featured_name = 'projs/' . $file_base . '_t_featured.' . $file_ext;
	
	$x1 = $_POST['x1'];
	$y1 = $_POST['y1'];
	$x2 = $_POST['x2'];
	$y2 = $_POST['y2'];
	
	if ($x1 == 0 && $x2 == 0 && $y1 == 0 && $y2 == 0) die("success");
	
	$scaled_x1 = intval(($original_width * $x1) / $scaled_width) + 1;
	$scaled_x2 = intval(($original_width * $x2) / $scaled_width) + 1;
	
	$scaled_y1 = intval(($original_height * $y1) / $scaled_height) + 1;
	$scaled_y2 = intval(($original_height * $y2) / $scaled_height) + 1;
	
	$targ_width = $scaled_x2 - $scaled_x1;
	$targ_height = $scaled_y2 - $scaled_y1;
	
	if ($file_ext == 'jpg'){
		$img_r = imagecreatefromjpeg($_POST['filename']);
	} else if ($file_ext == 'png'){
		$img_r = imagecreatefrompng($_POST['filename']);
	} else if ($file_ext == 'gif'){
		$img_r = imagecreatefromgif($_POST['filename']);
	} else {
		die("Wrong extension");
	}
	
	$dst_r = imagecreatetruecolor($targ_width, $targ_height);
	
	imagecopyresampled($dst_r, $img_r, 0, 0, $scaled_x1, $scaled_y1, $targ_width, $targ_height, $targ_width, $targ_height);
	
	if ($file_ext == 'jpg'){
		imagejpeg($dst_r, $normal_name);
		imagejpeg($dst_r, $featured_name);
	} else if ($file_ext == 'png'){
		imagepng($dst_r, $normal_name);
		imagepng($dst_r, $featured_name);
	} else if ($file_ext == 'gif'){
		imagegif($dst_r, $normal_name);
		imagegif($dst_r, $featured_name);
	}
	
	echo "success";
?>