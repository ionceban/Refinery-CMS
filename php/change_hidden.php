<?php
	require('../db_connect.php');

	if (!$_POST['keyword_id']){
		die("Please provide a keyword id");
	}

	$keyword_id = $_POST['keyword_id'];

	$query_statement = "SELECT hidden FROM keywords WHERE id='" . $keyword_id . "'";
	$query = mysql_query($query_statement, $db_conn);
	$row = mysql_fetch_row($query);

	if (!$row){
		die("Invalid keyword id");
	}

	$new_hidden = 1 - intval($row[0]);

	$query_statement = "UPDATE keywords SET hidden=" . $new_hidden . " WHERE id='" . $keyword_id . "'";
	$query = mysql_query($query_statement, $db_conn);

	echo $new_hidden;
?>
