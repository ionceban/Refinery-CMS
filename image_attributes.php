<?php
	require('db_connect.php');

	$id = $_POST['id'];
	$query_statement = "SELECT * FROM images WHERE id='" . $id . "'";
	$query = mysql_query($query_statement, $db_conn);
	$row = mysql_fetch_array($query);
	
	$result = array();
	$result['id'] = $row['id'];
	$result['filename'] = $row['name'];
	
	$query_statement_2 = "SELECT name FROM projects WHERE id='" . $row['project_id'] . "'";
	$query_2 = mysql_query($query_statement_2, $db_conn);
	$row_2 = mysql_fetch_array($query_2);
	
	$result['project_name'] = $row_2['name'];
	
	$query_statement_2 = "SELECT * FROM mediscs WHERE id='" . $row['medisc_id'] . "'";
	$query_2 = mysql_query($query_statement_2, $db_conn);
	$row_2 = mysql_fetch_array($query_2);
	
	$medium_id = $row_2['medium_id'];
	$result['medium_id'] = $medium_id;
	$medium_disc_id = $row_2['discipline_id'];
	$result['medium_disc_id'] = $medium_disc_id;
	
	$query_statement_2 = "SELECT * FROM didiscs WHERE id='" . $row['didisc_id'] . "'";
	$query_2 = mysql_query($query_statement_2, $db_conn);
	$row_2 = mysql_fetch_array($query_2);
	
	$division_id = $row_2['division_id'];
	$result['division_id'] = $division_id;
	$division_disc_id = $row_2['discipline_id'];
	$result['division_disc_id'] = $division_disc_id;
	
	$query_statement_2 = "SELECT name FROM mediums WHERE id='" . $medium_id . "'";
	$query_2 = mysql_query($query_statement_2, $db_conn);
	$row_2 = mysql_fetch_array($query_2);
	
	$result['medium'] = $row_2['name'];
	
	$query_statement_2 = "SELECT name FROM divisions WHERE id='" . $division_id . "'";
	$query_2 = mysql_query($query_statement_2, $db_conn);
	$row_2 = mysql_fetch_array($query_2);
	
	$result['division'] = $row_2['name'];
	
	$query_statement_2 = "SELECT name FROM disciplines WHERE id='" . $medium_disc_id . "'";
	$query_2 = mysql_query($query_statement_2, $db_conn);
	$row_2 = mysql_fetch_array($query_2);
	
	$result['medium_disc'] = $row_2['name'];
	
	$query_statement_2 = "SELECT name FROM disciplines WHERE id='" . $division_disc_id . "'";
	$query_2 = mysql_query($query_statement_2, $db_conn);
	$row_2 = mysql_fetch_array($query_2);
	
	$result['division_disc'] = $row_2['name'];
	$result['date'] = $row['date'];
	
	$result['deliverable'][0] = 0;
	$query_statement_2 = "SELECT DISTINCT deliverable_id FROM imgdelivs WHERE image_id='" . $id . "'";
	$query_2 = mysql_query($query_statement_2, $db_conn);
	while ($row_2 = mysql_fetch_array($query_2)){
		$result['deliverable'][0]++;
		$result['deliverable'][$result['deliverable'][0]] = $row_2['deliverable_id'];
	}
	
	$result['keywords'][0] = 0;
	$query_statement_2 = "SELECT DISTINCT keyword_id FROM imgkeyws WHERE image_id='" . $id . "'";
	$query_2 = mysql_query($query_statement_2, $db_conn);
	while ($row_2 = mysql_fetch_array($query_2)){
		$result['keywords'][0]++;
		$result['keywords'][$result['keywords'][0]] = $row_2['keyword_id'];
	}
	
	echo json_encode($result);
?>