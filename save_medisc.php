<?php
	require('db_connect.php');
	require('utils.php');
	
	$image_array = list_to_array($_POST['id_list']);
	$medium_id = $_POST['medium_id'];
	$discipline_id = $_POST['discipline_id'];
	
	$query_statement = "SELECT id FROM mediscs WHERE (medium_id='" . $medium_id . "' AND discipline_id='" . $discipline_id . "')";
	$query = mysql_query($query_statement, $db_conn);
	
	$row = mysql_fetch_row($query);
	
	$medisc_id = $row[0];
	
	$query_statement = "UPDATE images SET medisc_id='" . $medisc_id . "' WHERE (1=0";
	
	foreach ($image_array as $single_image){
		$query_statement .= " OR id='" . $single_image . "'";
	}
	
	$query_statement .= ")";
	
	$query = mysql_query($query_statement, $db_conn);
	
	if (!$query){
		die("Error processing medisc");
	}
	
	echo "success";
?>