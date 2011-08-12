<?php
	require('../db_connect.php');

	if (!$_POST['image_id']){
		die("Please select an image");
	}

	$image_id = $_POST['image_id'];

	$query_statement = "SELECT shadowbox FROM images WHERE id='" . $image_id . "'";
	$query = mysql_query($query_statement, $db_conn);
	$row = mysql_fetch_row($query);

	$shadowbox = 1 - intval($row[0]);

	$query_statement = "UPDATE images SET shadowbox='" . $shadowbox . "' WHERE id='" . $image_id . "'";
	$query = mysql_query($query_statement, $db_conn);

	echo $shadowbox;
?>
