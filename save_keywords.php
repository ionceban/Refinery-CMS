<?php
	require('db_connect.php');
	require('utils.php');
	
	$image_array = list_to_array($_POST['id_list']);
	$keywords_array = list_to_array($_POST['keywords_list']);
	
	$query_statement = "DELETE FROM imgkeyws WHERE (1=0";
	
	foreach ($image_array as $single_image){
		$query_statement .= " OR image_id='" . $single_image . "'";
	}
	
	$query_statement .= ")";
	
	$query = mysql_query($query_statement, $db_conn);
	if (!$query){
		die("Error processing keywords");
	}
	
	foreach ($image_array as $single_image){
		foreach ($keywords_array as $single_keyword){
			$query_statement = "INSERT INTO imgkeyws(image_id,keyword_id) VALUES('" . $single_image . "','" . $single_keyword . "')";
			$query = mysql_query($query_statement, $db_conn);
			if (!$query){
				die("Error processing keywords");
			}
		}
	}
	
	echo "success";
?>