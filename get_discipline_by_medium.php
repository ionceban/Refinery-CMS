<?php
	require('db_connect.php');
	
	$medium_id = $_POST['medium_id'];
	
	$query_statement = "SELECT disciplines.id FROM mediums, disciplines WHERE (disciplines.name=mediums.name AND ";
	$query_statement .= "mediums.id='" . $medium_id . "')";
	
	$query = mysql_query($query_statement, $db_conn);
	
	$row = mysql_fetch_row($query);
	
	if (!$row){
		echo "0";
	} else {
		echo $row[0];
	}
?>