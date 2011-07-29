<?php
	require('db_connect.php');
	require('utils.php');
	
	$image_array = list_to_array($_POST['id_list']);
	$deliverables_array = list_to_array($_POST['deliverables_list']);
	
	$query_statement = "DELETE FROM imgdelivs WHERE (1=0";
	
	foreach ($image_array as $single_image){
		$query_statement .= " OR image_id='" . $single_image . "'";
	}
	
	$query_statement .= ")";
	
	$query = mysql_query($query_statement, $db_conn);
	if (!$query){
		die("Error processing deliverables");
	}
	
	foreach ($image_array as $single_image){
		foreach ($deliverables_array as $single_deliverable){
			$query_statement = "INSERT INTO imgdelivs(image_id,deliverable_id) VALUES('" . $single_image . "','" . $single_deliverable . "')";
			$query = mysql_query($query_statement, $db_conn);
			if (!$query){
				die("Error processing deliverables");
			}
		}
	}
	
	echo "success";
?>