<?php
	require('utils.php');
	
	$image_array = json_decode($_POST['image_list'], true);
	$response = "";
	
	for ($i = 1; $i <= $image_array[0]; $i++){
		$response .= "<li class='grid-image-item' image_id='" . $image_array[$i]['image_id'] . "'>";
		$response .= "<div class='img-wrapper'>";
		
		$file_attrs = preg_split('/\./', $image_array[$i]['filename']);
		$thumber_ext = extension_checker('projs/' . $file_attrs[0] . "_t_thumber");
		
		$response .= "<img src='projs/" . $file_attrs[0] . "_t_grid." . $thumber_ext . "' />";
		$response .= "<div class='select-item'>";
		$response .= "<img class='select-toggle' src='images/checkbox-0.png' />";
		$response .= "<img class='star-toggle' src='images/";
		
		if ($image_array[$i]['featured'] == '1'){
			$response .= "red";
		} else {
			$response .= "dark";
		}
		
		$response .= "-star.png' />";
		$response .= "</div>";
		$response .= "</div>";
		$response .= "<span>";
		$response .= "<a class='edit-button' href='javascript: void(0)'>edit</a>";
		$response .= "<a class='delete-button' href='javascript: void(0)'>delete</a>";
		$response .= "</span>";
		$response .= "</li>";
	}
	
	echo $response;
?>