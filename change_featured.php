<?php
	require ('db_connect.php');
	$image_id = $_POST['image_id'];
	
	$query_statement = "SELECT featured FROM images WHERE id='" . $image_id . "'";
	$query = mysql_query($query_statement, $db_conn);
	$row = mysql_fetch_row($query);
	
	$star = 1 - intval($row[0]);
	
	$query_statement = "UPDATE images SET featured=" . $star . " WHERE id='" . $image_id . "'";
	$query = mysql_query($query_statement);

	if ($star == 1){
		$query_statement = "UPDATE images SET shadowbox=0 WHERE id='" . $image_id . "'";
		$query = mysql_query($query_statement, $db_conn);
	}

	if ($star == 1){
		$response['star'] = 'red';
	} else {
		$response['star'] = 'dark';
	}

	$query_statement = "SELECT shadowbox FROM images WHERE id='" . $image_id . "'";
	$query = mysql_query($query_statement, $db_conn);
	$row = mysql_fetch_row($query);
	
	$response['shadow'] = $row[0];

	echo json_encode($response);
?>
