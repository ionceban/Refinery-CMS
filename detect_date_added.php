<?php
	require('db_connect.php');
	require('utils.php');
	
	$image_array = list_to_array($_POST['id_list']);
	
	$query_statement = "SELECT date FROM images WHERE (1=0";
	
	foreach ($image_array as $single_image){
		$query_statement .= " OR id='" . $single_image . "'";
	}
	
	$query_statement .= ")";
	
	$query = mysql_query($query_statement, $db_conn);
	$row = mysql_fetch_row($query);
	
	$default_date = $row[0];
	
	while ($row = mysql_fetch_row($query)){
		if ($row[0] != $default_date){
			die("undefined");
		}
	}
	
	echo $default_date;
?>