<?php
	require('db_connect.php');
	
	if ($_POST['type'] != 'deliverables' && $_POST['type'] != 'keywords'){
		die("ERROR: type");
	}
	
	if (!$_POST['new_name'] || $_POST['new_name'] == ''){
		die("ERROR: blank");
	}
	
	$query_statement = "SELECT * FROM " . $_POST['type'] . " WHERE name='" . $_POST['new_name'] . "'";
	$query = mysql_query($query_statement, $db_conn);
	$row = mysql_fetch_row($query);
	
	if ($row){
		die("ERROR: item already exists");
	}
	
	$query_statement = "INSERT INTO " . $_POST['type'] . "(name) VALUES('" . $_POST['new_name'] . "')";
	$query = mysql_query($query_statement, $db_conn);
	
	if (!$query){
		die("ERROR: DB");
	}
	
	echo "success";
?>