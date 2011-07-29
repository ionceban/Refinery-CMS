<?php
	require('db_connect.php');
	
	$featured_id = $_POST['featured_id'];
	$which_dialog = $_POST['which_dialog'];
	
	$response = "<select name='";
	
	if ($which_dialog == 'single'){
		$response .= "division-sel";
	} else {
		$response .= "m-division-sel";
	}
	
	$response .= "' id='";
	
	if ($which_dialog == 'single'){
		$response .= "division-sel";
	} else {
		$response .= "m-division-sel";
	}
	
	$response .= "'>";
	
	$query_statement = "SELECT id,name FROM divisions";
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