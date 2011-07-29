<?php
	require('db_connect.php');
	require('utils.php');

	$image_array = list_to_array($_POST['id_list']);
	$project_name = $_POST['project_name'];
	
	$query_statement = "SELECT id FROM projects WHERE name='" . $project_name . "'";
	$query = mysql_query($query_statement, $db_conn);
	
	if ($row = mysql_fetch_row($query)){
		$project_id = $row[0];
	} else {
		$query_statement = "INSERT INTO projects(name) VALUES('" . $project_name . "')";
		mysql_query($query_statement, $db_conn);
		$query_statement = "SELECT id FROM projects WHERE name='" . $project_name . "'";
		$query = mysql_query($query_statement, $db_conn);
		$row = mysql_fetch_row($query);
		
		$project_id = $row[0];
	}
	
	$query_statement = "UPDATE images SET project_id='" . $project_id . "' WHERE (1=0";
	
	foreach ($image_array as $single_image){
		$query_statement .= " OR id='" . $single_image ."'";
	}
	
	$query_statement .= ")";
	$query = mysql_query($query_statement);
	if (!$query){
		die("Failed editing project name for the current set");
	}
	
	echo "success";
?>