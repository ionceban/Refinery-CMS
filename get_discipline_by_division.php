<?php
	require('db_connect.php');
	
	$divison_id = $_POST['division_id'];
	
	$query_statement = "SELECT disciplines.id FROM divisions,disciplines WHERE (disciplines.name=divisions.name ";
	$query_statement .= "AND divisions.id='" . $divison_id . "')";
	
	$query = mysql_query($query_statement, $db_conn);
	
	$row = mysql_fetch_row($query);
	
	if (!$row){
		echo "0";
	} else {
		echo $row[0];
	}
?>