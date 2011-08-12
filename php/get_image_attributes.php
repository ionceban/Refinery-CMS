<?php
	require('../db_connect.php');
	require('../utils.php');
	
	if (!$_POST['image_id']){
		die("Please select an image");
	}

	$image_id = $_POST['image_id'];

	$query_statement = "SELECT * FROM images WHERE id='" . $image_id . "'";
	$query = mysql_query($query_statement, $db_conn);
	$row = mysql_fetch_array($query);

	$response = array();
	$response['id'] = $row['id'];
	$response['name'] = $row['name'];

	$file_attrs = preg_split('/\./', $row['name']);
	$response['thumber_src'] = $file_attrs[0] . "_t_thumber." . extension_checker('../projs/' . $file_attrs[0] . "_t_thumber");

	$response['medisc_id'] = $row['medisc_id'];
	$response['didisc_id'] = $row['didisc_id'];
	$response['project_id'] = $row['project_id'];
	$response['year_id'] = $row['year_id'];
	$response['thumb'] = $row['thumb'];
	$response['shadowbox'] = $row['shadowbox'];
	$response['queued'] = $row['queued'];
	$response['featured'] = $row['featured'];
	$response['date'] = $row['date'];
	$response['thumber_ext'] = extension_checker('../projs/' . $file_attrs[0] . "_t_thumber");
	
	echo json_encode($response);
?>
