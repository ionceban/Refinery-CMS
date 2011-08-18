<?php
	require('db_connect.php');
	require('utils.php');
	
	$image_array = list_to_array($_POST['id_list']);
	
	$query_statement = "UPDATE images SET queued=0 WHERE (1=0";
	
	$warning = "no";

	foreach ($image_array as $image_id){
		$has_deliverables = "yes";
		$query_statement_2 = "SELECT * FROM imgdelivs WHERE image_id='" . $image_id . "'";
		$query_2 = mysql_query($query_statement_2, $db_conn);
		$row_2 = mysql_fetch_row($query_2);
		if (!$row_2){
			$has_deliverables = "no";
		}

		$has_keywords = "yes";
		$query_statement_2 = "SELECT * FROM imgkeyws WHERE image_id='" . $image_id . "'";
		$query_2 = mysql_query($query_statement_2, $db_conn);
		$row_2 = mysql_fetch_row($query_2);
		if (!$row_2){
			$has_keywords = "no";
		}

		if ($has_deliverables == 'no' || $has_keywords == 'no'){
			$warning = "yes";
		} else {
			$query_statement .= " OR id='" . $image_id . "'";
		}
	}
	
	$query_statement .= ")";
	
	mysql_query($query_statement, $db_conn);
	
	if ($warning == 'no'){
		echo "success";
	} else {
		echo "WARNING: files with no deliverable/keyword assigned will not go live";
	}
?>
