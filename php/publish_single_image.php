<?php
	require('../db_connect.php');

	$response = array();

	$query_statement = "SELECT id, name FROM images WHERE id='" . $_POST['image_id'] . "'";
	$query = mysql_query($query_statement, $db_conn);
	$row = mysql_fetch_row($query);

	$query_statement = "SELECT * FROM imgdelivs WHERE image_id='" . $_POST['image_id'] . "'";
	$query = mysql_query($query_statement, $db_conn);
	
	if (!mysql_fetch_row($query)){
		$response['message'] = "incomplete";
		$response['filename'] = $row[1];
		die(json_encode($response));
	}

	$query_statement = "SELECT * FROM imgkeyws WHERE image_id='" . $_POST['image_id'] . "'";
	$query = mysql_query($query_statement, $db_conn);
	
	if (!mysql_fetch_row($query)){
		$response['message'] = "incomplete";
		$response['filename'] = $row[1];
		die(json_encode($response));
	}

	$query_statement = "UPDATE images SET queued=0 WHERE id='" . $_POST['image_id'] . "'";
	$query = mysql_query($query_statement, $db_conn);

	$response['message'] = "success";
	$response['image_id'] = $row[0];
	
	echo json_encode($response);
?>	
