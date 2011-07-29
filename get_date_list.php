<?php
	require('db_connect.php');

	$featured_day = $_POST['featured_day'];
	$featured_month = $_POST['featured_month'];
	$featured_year = $_POST['featured_year'];
	$which_dialog = $_POST['which_dialog'];
	
	$response = "<select id='";
	
	if ($which_dialog == 'single'){
		$response .= "date-day-sel";
	} else {
		$response .= "m-date-day-sel";
	}
	
	$response .= "' name='";
	
	if ($which_dialog == 'single'){
		$response .= "date-day-sel";
	} else {
		$response .= "m-date-day-sel";
	}
	
	$response .= "'>";
	
	for ($i = 1; $i <= 31; $i++){
		$response .= "<option value='" .$i . "'";
		if ($i == $featured_day){
			$response .= " selected='selected'";
		}
		$response .= "'>";
		
		if ($i < 10){
			$response .= "0";
		}
		
		$response .= $i . "</option>";
	}
	
	$response .= "</select>";
	
	if ($which_dialog == 'single'){
		$response .= "<span class='sep'></span>";
	} else {
		$response .= "<span class='date-sep'></span>";
	}
	
	$response .= "<select id='";
	
	if ($which_dialog == 'single'){
		$response .= "date-month-sel";
	} else {
		$response .= "m-date-month-sel";
	}
	
	$response .= "' name='";
	
	if ($which_dialog == 'single'){
		$response .= "date-month-sel";
	} else {
		$response .= "m-date-month-sel";
	}
	
	$response .= "'>";
	
	for ($i = 1; $i <= 12; $i++){
		$response .= "<option value='" . $i . "'";
		if ($i == $featured_month){
			$response .= " selected='selected'";
		}
		
		$response .= ">";
		
		if ($i < 10){
			$response .= "0";
		}
		
		$response .= $i . "</option>";
	}
	
	
	$response .= "</select>";
	
	if ($which_dialog == 'single'){
		$response .= "<span class='sep'></span>";
	} else {
		$response .= "<span class='date-sep'></span>";
	}
	
	$query_statement = "SELECT value FROM years ORDER BY value";
	$query = mysql_query($query_statement, $db_conn);
	
	$response .= "<select id='";
	
	if ($which_dialog == 'single'){
		$response .= "date-year-sel";
	} else {
		$response .= "m-date-year-sel";
	}
	
	$response .= "' name='";
	
	if ($which_dialog == 'single'){
		$response .= "date-year-sel";
	} else {
		$response .= "m-date-year-sel";
	}
	
	$response .= "'>";
	
	while ($row = mysql_fetch_row($query)){
		$response .= "<option value='" . $row[0] . "'";
		if ($row[0] == $featured_year){
			$response .= " selected='selected'";
		}
		$response .= ">" . $row[0] . "</option>";
	}
	
	$response .= "</select>";
	
	echo $response;
?>