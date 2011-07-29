<?php
	require('db_connect.php');
	require('utils.php');
	
	$image_array = list_to_array($_POST['id_list']);
	
	$query_statement = "SELECT project_id FROM images WHERE (1=0";
	
	foreach ($image_array as $single_image){
		$query_statement .= " OR id='" . $single_image . "'";
	}
	
	$query_statement .= ")";
	
	$query = mysql_query($query_statement, $db_conn);
	
	$row = mysql_fetch_row($query);
	
	$default_id = $row[0];
	
	while ($row = mysql_fetch_row($query)){
		if ($row[0] != $default_id){
			die("");
		}
	}
	
	$query_statement = "SELECT name FROM projects WHERE id='" . $default_id . "'";
	$query = mysql_query($query_statement, $db_conn);
	$row = mysql_fetch_row($query);
	
	echo $row[0];
?>