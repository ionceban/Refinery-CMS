<?php
	require('db_connect.php');
	
	$query_statement = "SELECT id,name,hidden FROM keywords ORDER BY hidden, name";
	$query = mysql_query($query_statement, $db_conn);
	
	$response = "";
	
	while ($row = mysql_fetch_row($query)){
		$response .= "<li keyword_id='" . $row[0] . "'>";
		//$response .= "<img class='select-toggle' src='images/checkbox-". $row[2] . ".png' />";
		if ($row[2] == '1'){
			$response .= "<b><i>";
		}
		$response .= $row[1];
		if ($row[2] == '1'){
			$response .= "</i></b>";
		}
		$response .= "</li>";
	}
	
	echo $response;
?>
