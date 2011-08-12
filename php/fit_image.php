<?php
	if (!$_POST['image_src']){
		die("Please select an image");
	}

	if (!$_POST['new_width'] || !$_POST['new_height']){
		die("Please provide new dimensions");
	}

	$image_src = "../" . $_POST['image_src'];
	$new_width = $_POST['new_width'];
	$new_height = $_POST['new_height'];

	list($old_width, $old_height, $dummy1, $dummy2) = getimagesize($image_src);
	
	if ($new_width > $old_width){
		die("Size error width: " . $image_src);
	}

	if ($new_height > $old_height){
		die("Size error");
	}

	$scaled_new_width = $new_width;
	$scaled_new_height = $new_height;

	$scaled_new_height = intval(($old_width * $scaled_new_height) / $scaled_new_width);
	$scaled_new_width = $old_width;

	if ($scaled_new_height > $old_height){
		$scaled_new_width = intval(($old_height * $scaled_new_width) / $scaled_new_height);
		$scaled_new_height = $old_height;
	}

	$offset_x = intval(($old_width - $scaled_new_width) / 2);
	$offset_y = intval(($old_height - $scaled_new_height) /2);
	
	$file_attrs = preg_split('/projs/', $image_src);
	$file_attrs = preg_split('/\./', $file_attrs[1]);

	if ($file_attrs[1] == 'jpg'){
		$source_image = imagecreatefromjpeg($image_src);
	} else if ($file_attrs[1] == 'png'){
		$source_image = imagecreatefrompng($image_src);
	} else {
		$source_image = imagecreatefromgif($image_src);
	}

	$dest_image = imagecreatetruecolor($new_width, $new_height);

	if (!imagecopyresampled($dest_image, $source_image, 0, 0, $offset_x, $offset_y, $new_width, $new_height, $scaled_new_width, $scaled_new_height)){
		die("Could not fit image");
	}

	if ($file_attrs[1] == 'jpg'){
		imagejpeg($dest_image, $image_src);
	} else if ($file_attrs[1] == 'png'){
		imagepng($dest_image, $image_src);
	} else {
		imagegif($dest_image, $image_src);
	}
	
	echo "success";
?>
