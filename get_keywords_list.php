<?php
	require('db_connect.php');
	
	$query_statement = "SELECT id,name,hidden FROM keywords ORDER BY hidden, name";
	$query = mysql_query($query_statement, $db_conn);
	
	$response = "";

	$already_hidden = 0;
	
	while ($row = mysql_fetch_row($query)){
		if ($row[2] == '1' && $already_hidden == 0){
			$response .= "<h3 style='color: #E20B3A'>Hidden Keywords</h3>";
		}

		$response .= "<li keyword_id='" . $row[0] . "'>";
		//$response .= "<img class='select-toggle' src='images/checkbox-". $row[2] . ".png' />";
		if ($row[2] == '1'){
			$already_hidden = 1;
		}
		$response .= $row[1];
		$response .= "</li>";
	}
	
	echo $response;
?>
