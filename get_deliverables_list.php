<?php
	require ('db_connect.php');

	$query_statement = "SELECT id,name FROM deliverables ORDER BY name";
	$query = mysql_query($query_statement, $db_conn);
	
	$response = "";
	
	while ($row = mysql_fetch_row($query)){
		$response .= "<li deliverable_id='" . $row[0] . "'>" . $row[1] . "</li>";
	}
	
	echo $response;
?>
	
