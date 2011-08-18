<?php
	require('db_connect.php');
	
	$query_statement = "SELECT id,name,hidden FROM keywords ORDER BY hidden, name";
	$query = mysql_query($query_statement, $db_conn);
	
	$response = "";
	
	while ($row = mysql_fetch_row($query)){
		$response .= "<li keyword_id='" . $row[0] . "'>";
		$response .= "<img class='select-toggle' src='images/checkbox-". $row[2] . ".png' />";
	   	$response .= $row[1] . "</li>";
	}
	
	echo $response;
?>
