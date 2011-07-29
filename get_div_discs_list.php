<?php
	require('db_connect.php');
	
	$division_id = $_POST['division_id'];
	$featured_id = $_POST['featured_id'];
	$which_dialog = $_POST['which_dialog'];
	
	$response = "<select name='";
	
	if ($which_dialog == 'single'){
		$response .= "div-disc-sel";
	} else {
		$response .= "m-div-disc-sel";
	}
	
	$response .= "' id='";
	
	if ($which_dialog == 'single'){
		$response .= "div-disc-sel";
	} else {
		$response .= "m-div-disc-sel";
	}
	
	$response .= "'>";

	$query_statement = "SELECT disciplines.id, disciplines.name FROM didiscs,disciplines WHERE";
	$query_statement .= " (disciplines.id=didiscs.discipline_id AND didiscs.division_id='" . $division_id ."')";
	
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