<?php
	require('db_connect.php');
	
	if ($_POST['type'] != 'deliverables' && $_POST['type'] != 'keywords'){
		die("failed");
	}
	
	$query_statement = "SELECT id, name";
	
	if ($_POST['type'] == 'keywords'){
		$query_statement .= ", hidden";
	}

   	$query_statement .= " FROM " . $_POST['type'] . " ORDER BY name";
	$query = mysql_query($query_statement, $db_conn);
	
	$response = "";
	while ($row = mysql_fetch_row($query)){
		$response .= "<li kd_id='" . $row[0] . "'>";
		if ($_POST['type'] == 'keywords'){
			$response .= "<img class='hide-toggle' src='images/checkbox-" . $row[2] . ".png' />";
		}
	   	$response .= $row[1] . "<img class='delete-kd-button'";
		$response .= " src='images/reset-input.png' /></li>";
	}
	
	echo $response;
?>
