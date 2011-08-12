<?php
	require('../db_connect.php');
	require('../utils.php');

	$accepted_formats = array('jpg', 'png', 'gif');

	if (!$_POST['image_id']) die("failed");

	$file_attrs = preg_split('/\./', $_FILES['uploaded_file']['name']);

	if (!in_array($file_attrs[1], $accepted_formats)){
		die("failed");
	}
	
	list($up_width, $up_height, $dummy1, $dummy2) = getimagesize($_FILES['uploaded_file']['tmp_name']);

	if ($up_width < 500 || $up_height < 400){
		die("failed");
	}
	
	$query_statement = "SELECT name FROM images WHERE id='" . $_POST['image_id'] . "'";
	$query = mysql_query($query_statement, $db_conn);
	$row = mysql_fetch_row($query);
	
	if (!$row){
		die("failed");
	}

	$file_attrs = preg_split('/\./', $row[0]);
	$core_name = $file_attrs[0];

	$old_thumber_extension = extension_checker('../projs/' . $core_name . '_t_thumber');
	
	$old_thumber = '../projs/' . $core_name . "_t_thumber." . $old_thumber_extension;
	$old_normal = str_replace('thumber', 'normal', $old_thumber);
	$old_featured = str_replace('thumber', 'featured', $old_thumber);
	$old_grid = str_replace('thumber', 'grid', $old_thumber);
	$old_list = str_replace('thumber', 'list', $old_thumber);
	
	$file_attrs = preg_split('/\./', $_FILES['uploaded_file']['name']);
	$new_ext = $file_attrs[1];
	
	$new_thumber = '../projs/' . $core_name . "_t_thumber." . $new_ext;
	$new_normal = str_replace('thumber', 'normal', $new_thumber);
	$new_featured = str_replace('thumber', 'featured', $new_thumber);
	$new_grid = str_replace('thumber', 'grid', $new_thumber);
	$new_list = str_replace('thumber', 'list', $new_thumber);

	$thumber_backup = $old_thumber . ".bak";
	copy($old_thumber, $thumber_backup);
	unlink($old_thumber);
	
	if (!move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $new_thumber)){
		copy($thumber_backup, $old_thumber);
		unlink($thumber_backup);
		
		die("failed");
	} else {
		unlink($thumber_backup);
		unlink($old_normal);
		unlink($old_featured);
		unlink($old_grid);
		unlink($old_list);

		copy($new_thumber, $new_normal);
		copy($new_thumber, $new_featured);
		copy($new_thumber, $new_list);
		copy($new_thumber, $new_grid);
	}

	list($new_width, $new_height, $dummy1, $dummy2) = getimagesize($new_thumber);

	$response = array();
	$response['core_name'] = $core_name;
	$response['new_ext'] = $new_ext;
	$response['new_width'] = $new_width;
	$response['new_height'] = $new_height;

	echo json_encode($response);
?>
