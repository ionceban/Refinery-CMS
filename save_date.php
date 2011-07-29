<?php
	require('db_connect.php');
	require('utils.php');
	
	$image_array = list_to_array($_POST['id_list']);
	$date = $_POST['date'];
	
	$date_details = preg_split('/\-/', $date);
	$year_value = $date_details[0];
	
	$query_statement = "SELECT id FROM years WHERE value='" . $year_value . "'";
	$query = mysql_query($query_statement, $db_conn);
	
	$row = mysql_fetch_row($query);
	if (!$row){
		die("Error processing year");
	}
	
	$year_id = $row[0];
	
	$query_statement = "UPDATE images SET year_id='" . $year_id ."', date='" . $date . "' WHERE (1=0";
	
	foreach ($image_array as $single_image){
		$query_statement .= " OR id='" . $single_image . "'";
	}
	
	$query_statement .= ")";
	
	$query = mysql_query($query_statement, $db_conn);
	
	if (!$query){
		die("Error processing date");
	}
	
	echo "success";
?>