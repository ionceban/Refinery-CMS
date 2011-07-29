<?php
	require('db_connect.php');
	
	$medium_id = $_POST['medium_id'];
	
	$query_statement = "SELECT name FROM mediums WHERE id='" . $medium_id . "'";
	
	$query = mysql_query($query_statement, $db_conn);
	
	$row = mysql_fetch_row($query);
	
	echo $row[0];
?>