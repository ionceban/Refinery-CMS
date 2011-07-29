<?php
	require ('db_connect.php');
	
	$response = "<table ><tr><td style='vertical-align:top'><table><tr><td><table id='live-filter-mediums'>";
	
	$query_statement = "SELECT name FROM mediums";
	$query = mysql_query($query_statement, $db_conn);
	
	while ($row = mysql_fetch_row($query)){
		$response .= "<tr><td><img src='images/checkbox-0.png' filter_rel='" . $row[0] . "' /></td>";
		$response .= "<td>" . $row[0] . "</td></tr>";
	}
	
	$response .= "</table></td></tr><tr><td><br /><table id='live-filter-divisions'>";
	
	$query_statement = "SELECT name FROM divisions";
	$query = mysql_query($query_statement, $db_conn);
	
	while ($row = mysql_fetch_row($query)){
		$response .= "<tr><td><img src='images/checkbox-0.png' filter_rel='" . $row[0] . "' /></td>";
		$response .= "<td>" . $row[0] . "</td></tr>";
	}
	
	$response .= "</table></td></tr></table></td><td style='vertical-align:top'><table id='live-filter-deliverables'>";
	
	$query_statement = "SELECT name FROM deliverables ORDER BY name";
	$query = mysql_query($query_statement, $db_conn);
	
	while ($row = mysql_fetch_row($query)){
		$response .= "<tr><td><img src='images/checkbox-0.png' filter_rel='" . $row[0] . "' /></td>";
		$response .= "<td>" . $row[0] . "</td></tr>";
	}
	 
	$response .= "</table></td><td style='vertical-align:top'><table id='live-filter-keywords'>";
	
	$query_statement = "SELECT name FROM keywords ORDER BY name";
	$query = mysql_query($query_statement, $db_conn);
	
	while ($row = mysql_fetch_row($query)){
		$response .= "<tr><td><img src='images/checkbox-0.png' filter_rel='" . $row[0] . "' /></td>";
		$response .= "<td>" . $row[0] . "</td></tr>";
	}
	
	$response .= "</table></td></tr></table>";

	echo $response;
?>