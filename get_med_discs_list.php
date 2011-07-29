<?php
	require('db_connect.php');
	
	$medium_id = $_POST['medium_id'];
	$featured_id = $_POST['featured_id'];
	$which_dialog = $_POST['which_dialog'];
	
	$response = "<select name='";
	
	if ($which_dialog == 'single'){
		$response .= "med-disc-sel";
	} else {
		$response .= "m-med-disc-sel";
	}
	
	$response .= "' id='";
	
	if ($which_dialog == 'single'){
		$response .= "med-disc-sel";
	} else {
		$response .= "m-med-disc-sel";
	}
	
	$response .= "'>";

	$query_statement = "SELECT disciplines.id, disciplines.name FROM mediscs,disciplines WHERE";
	$query_statement .= " (disciplines.id=mediscs.discipline_id AND mediscs.medium_id='" . $medium_id ."')";
	
	$query = mysql_query($query_statement, $db_conn);
	
	while ($row = mysql_fetch_row($query)){
		$response .= "<option value='" . $row[0] . "'";
		if ($row[0] == $featured_id){
			$response .= " selected='selected'";
		}
		$response .= ">" . $row[1] . "</option>";
	}
	
	$response .= "</select>";
	
	echo $response;
?>