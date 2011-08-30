<?php
	require('../db_connect.php');

	if (!$_POST['image_list']){
		die("failed");
	}

	$image_array = json_decode($_POST['image_list'], true);
	
	$query_statement = "UPDATE images SET images.queued=1 WHERE (1=0";

	for ($i = 1; $i <= $image_array[0]; $i++){
		$query_statement .= " OR images.id='" . $image_array[$i] . "'";
	}

	$query_statement .= ")";

	$query = mysql_query($query_statement, $db_conn);

	if (!$query){
		die("failed");
	} else {
		die("success");
	}	
?>
