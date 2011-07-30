<?php
	require('db_connect.php');

	if ($_POST['type'] != 'deliverables' && $_POST['type'] != 'keywords'){
		die("failed");
	}
	if (!$_POST['id']){
		die("failed");
	}
	
	$table = $_POST['type'];
	if ($table == 'deliverables'){
		$cross_table = 'imgdelivs';
		$table_id = 'deliverable_id';
	} else {
		$cross_table = 'imgkeyws';
		$table_id = 'keyword_id';
	}
	
	$query_statement = "DELETE FROM " . $cross_table . " WHERE " . $table_id . "='" . $_POST['id'] . "'";
	$query = mysql_query($query_statement, $db_conn); 
	if (!$query){
		die('failed');
	}
	
	$query_statement = "DELETE FROM " . $table . " WHERE id='" . $_POST['id'] . "'";
	$query = mysql_query($query_statement, $db_conn);
	
	if (!$query){
		die('failed');
	}
	
	echo 'success';
?>