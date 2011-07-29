<?php
	require ('db_connect.php');
	$image_id = $_POST['image_id'];
	
	$query_statement = "SELECT featured FROM images WHERE id='" . $image_id . "'";
	$query = mysql_query($query_statement, $db_conn);
	$row = mysql_fetch_row($query);
	
	$type = 1 - intval($row[0]);
	
	$query_statement = "UPDATE images SET featured=" . $type . " WHERE id='" . $image_id . "'";
	$query = mysql_query($query_statement);
	
	echo $type;
?>