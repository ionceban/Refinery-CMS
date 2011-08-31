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

	$query_statement = "SELECT DISTINCT image_id FROM " . $cross_table . " WHERE " . $table_id . "='" . $_POST['id'] . "'";
	$query = mysql_query($query_statement, $db_conn);
	
	$image_array = array();
	$image_array[0] = 0;

	while ($row = mysql_fetch_row($query)){
		$image_array[0]++;
		$image_array[$image_array[0]] = $row[0];
	}

	$query_statement = "SELECT id FROM images WHERE queued=0 AND (1=0";

	for ($i = 1; $i <= $image_array[0]; $i++){
		$query_statement .= " OR id='" . $image_array[$i] . "'";
	}

	$query_statement .= ")";
	$query = mysql_query($query_statement, $db_conn);

	$image_array[0] = 0;

	while ($row = mysql_fetch_row($query)){
		$image_array[0]++;
		$image_array[$image_array[0]] = $row[0];
	}
	
	$query_statement = "DELETE FROM " . $cross_table . " WHERE " . $table_id . "='" . $_POST['id'] . "'";
	$query = mysql_query($query_statement, $db_conn); 
	if (!$query){
		die('failed');
	}

	$big_statement = "UPDATE images SET images.queued=1 WHERE (1=0";

	for ($i = 1; $i <= $image_array[0]; $i++){
		$big_statement .= " OR images.id='" . $image_array[$i] . "'";
	}

	$big_statement .= ") AND (1=1";

	$query_statement = "SELECT DISTINCT image_id FROM " . $cross_table . " WHERE (1=0";

	for ($i = 1; $i <= $image_array[0]; $i++){
		$query_statement .= " OR image_id='" . $image_array[$i] . "'";
	}

	$query_statement .= ")";
	$query = mysql_query($query_statement, $db_conn);

	$image_array[0] = 0;

	while ($row = mysql_fetch_row($query)){
		$image_array[0]++;
		$image_array[$image_array[0]] = $row[0];
	}

	for ($i = 1; $i <= $image_array[0]; $i++){
		$big_statement .= " AND images.id!='" . $image_array[$i] . "'";
	}

	$big_statement .= ")";
	$query = mysql_query($big_statement, $db_conn);

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
