<?php
	require('../db_connect.php');
	require('../utils.php');

	if (!$_POST['image_id']){
		die("Please select an image id");
	}

	if (!$_POST['jcrop_coords']){
		die("Please provide the coords");
	}

	if (!$_POST['scaled_width']){
		die("Please provide scaled width");
	}

	if (!$_POST['scaled_height']){
		die("Please provide scaled height");
	}	

	$image_id = $_POST['image_id'];

	$query_statement = "SELECT name FROM images WHERE id='" . $image_id . "'";
	$query = mysql_query($query_statement, $db_conn);
	$row = mysql_fetch_row($query);

	if (!$row){
		die("Couldn't find image in the database");
	}

	$filename = $row[0];
	$file_attrs = preg_split('/\./', $filename);

	$thumber_extension = extension_checker('../projs/' . $file_attrs[0] . '_t_thumber');
	$thumber_path = '../projs/' . $file_attrs[0] . '_t_thumber.' . $thumber_extension;
	$normal_path = '../projs/' . $file_attrs[0] . '_t_normal.' . $thumber_extension;
	$featured_path = '../projs/' . $file_attrs[0] . '_t_featured.' . $thumber_extension;

	$scaled_width = $_POST['scaled_width'];
	$scaled_height = $_POST['scaled_height'];

	list($original_width, $original_height, $dummy1, $dummy2) = getimagesize($thumber_path);

	$scale = $original_width / $scaled_width;

	$jcrop_coords = preg_split('/_/', $_POST['jcrop_coords']);

	$jcrop_x1 = intval($scale * intval($jcrop_coords[0]));
	$jcrop_y1 = intval($scale * intval($jcrop_coords[1]));
	$jcrop_x2 = intval($scale * intval($jcrop_coords[2]));
	$jcrop_y2 = intval($scale * intval($jcrop_coords[3]));

	if ($thumber_extension == 'jpg'){
		$source_image = imagecreatefromjpeg($thumber_path);
	} else if ($thumber_extension == 'png'){
		$source_image = imagecreatefrompng($thumber_path);
	} else {
		$source_image = imagecreatefromgif($thumber_path);
	}

	$dest_width = $jcrop_x2 - $jcrop_x1;
	$dest_height = $jcrop_y2 - $jcrop_y1;

	$dest_image = imagecreatetruecolor($dest_width, $dest_height);

	if (!imagecopyresampled($dest_image, $source_image, 0, 0, $jcrop_x1, $jcrop_y1, $dest_width, $dest_height, $dest_width, $dest_height)){
		die("Could not crop image");
	}

	if ($thumber_extension == 'jpg'){
		imagejpeg($dest_image, $normal_path);
		imagejpeg($dest_image, $featured_path);
	} else if ($thumber_extension == 'png'){
		imagepng($dest_image, $normal_path);
		imagepng($dest_image, $featured_path);
	} else {
		imagegif($dest_image, $normal_path);
		imagegif($dest_image, $featured_path);
	}

	echo "success"; 
?>
