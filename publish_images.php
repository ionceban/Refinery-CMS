<?php
	require('db_connect.php');
	require('utils.php');
	
	$image_array = list_to_array($_POST['id_list']);
	
	$query_statement = "UPDATE images SET queued=0 WHERE (1=0";
	
	foreach ($image_array as $image_id){
		$query_statement .= " OR id='" . $image_id . "'";
	}
	
	$query_statement .= ")";
	
	mysql_query($query_statement, $db_conn);
	
	echo "success";
?>