<?php
	if (!$_POST['image_src']) die('Insufficient params');
	if (!$_POST['width']) die('Insufficient params');
	if (!$_POST['height']) die('Insufficient params');

	$image_src = $_POST['image_src'];
	$new_width = $_POST['width'];
	$new_height = $_POST['height']; 
	
	list($original_width, $original_height, $src, $attr) = getimagesize($image_src);
	
	if ($new_width > $original_width) die("Image too small: " . $_POST['image_src']);
	if ($new_height > $original_height) die("Image too small: " . $_POST['image_src']);
	
	$cropped_height = $original_height;
	$cropped_width = intval(($new_width * $cropped_height) / $new_height);
	
	if ($cropped_width > $original_width){
		$cropped_width = $original_width;
		$cropped_height = intval(($new_height * $cropped_width) / $new_width);
	}
	
	$x1 = intval(($original_width - $cropped_width) / 2);
	$y1 = intval(($original_height - $cropped_height) / 2);
	
	$image_details = preg_split('/\./', $image_src);
	$image_extension = $image_details[1];
	
	if ($image_extension == 'jpg'){
		$original_image = imagecreatefromjpeg($image_src);
	} else if ($image_extension == 'png'){
		$original_image = imagecreatefrompng($image_src);
	} else if ($image_extension == 'gif'){
		$original_image = imagecreatefromgif($image_src);
	}
	
	$new_image = imagecreatetruecolor($new_width, $new_height);
	
	imagecopyresampled($new_image, $original_image, 0, 0, $x1, $y1, $new_width, $new_height, $cropped_width, $cropped_height);
	
	if ($image_extension == 'jpg'){
		if (!imagejpeg($new_image, $image_src)) die("failed");
	} else if ($image_extension == 'png'){
		if (!imagepng($new_image, $image_src)) die("failed");
	} else if ($image_extension == 'gif'){
		if (!imagegif($new_image, $image_src)) die("failed");
	}
	
	echo "success";
?>