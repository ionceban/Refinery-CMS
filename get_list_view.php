<?php
	require('utils.php');
	
	$image_array = json_decode($_POST['image_list'], true);
	$response = "";
	
	for ($i = 1; $i <= $image_array[0]; $i++){
		$response .= "<tr id='list-image-item-" . $image_array[$i]['image_id'] . "' class='list-image-item' image_id='" . $image_array[$i]['image_id'] . "'>";
		$response .= "<td>";
		$response .= "<span class='select-wrapper'>";
		$response .= "<img class='select-toggle' src='images/checkbox-0.png' style='margin-right:5px;' />";
		
		if ($image_array[$i]['featured'] == '1'){
			$response .= "<img class='star-toggle' src='images/red-star.png' />"; 
		} else {
			$response .= "<img class='star-toggle' src='images/dark-star.png' />";
		}

		$response .= "<img class='shadow-toggle' src='images/shadowbox-" . $image_array[$i]['shadowbox'] . ".png' style='margin-left: 5px;margin-top: 2px' />";
		
		$response .= "</span>";
		$response .= "</td>";
		$response .= "<td>";
		
		$file_attrs = preg_split('/\./', $image_array[$i]['filename']);
		$thumber_ext = extension_checker('projs/' . $file_attrs[0] . "_t_thumber");
		
		$rand_mod = rand(1, 10000);
		$response .= "<img class='list-thumb' src='projs/" . $file_attrs[0] . "_t_list." . $thumber_ext;
		$response .= "?modified=" . $rand_mod . "' />";
		$response .= "</td>";
		$response .= "<td>" . $image_array[$i]['project_name'] . "</td>";
		$response .= "<td>" . $image_array[$i]['filename'] . "</td>";
		$response .= "<td>" . $image_array[$i]['date'] . "</td>";
		$response .= "<td>" . $image_array[$i]['medium_name'] . "</td>";
		$response .= "<td>" . $image_array[$i]['division_name'] . "</td>";
		$response .= "<td style='width: 150px'>";
		
		for ($j = 1; $j <= $image_array[$i]['deliverables'][0]; $j++){
			if ($j > 1) $response .= ", ";
			$response .= $image_array[$i]['deliverables'][$j];
		}
		
		$response .= "</td>";
		$response .= "<td style='width: 150px'>";
		
		for ($j = 1; $j <= $image_array[$i]['keywords'][0]; $j++){
			if ($j > 1) $response .= ", ";
			$response .= $image_array[$i]['keywords'][$j];
		}
		
		$response .= "</td>";
		$response .= "<td>";
		$response .= "<a class='edit-button' href='javascript: void(0)'>edit</a>";
		$response .= "<a class='delete-button' href='javascript: void(0)'>delete</a>";
		$response .= "</td>";
		$response .= "</tr>";
	}
	
	echo $response; 
?>
