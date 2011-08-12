<?php
	require('../db_connect.php');

	if (!$_POST['image_id']){
		die("Please provide image id");
	}

	if (!$_POST['thumb_type']){
		die("Please provide thumb type");
	}

	$image_id = $_POST['image_id'];
	$thumb_type = $_POST['thumb_type'];

	if ($thumb_type != '1' && $thumb_type != '2'){
		die("Thumb type is incorrect");
	}

	$query_statement = "UPDATE images SET images.thumb='" . $thumb_type . "' WHERE images.id='" . $image_id . "'";
	$query = mysql_query($query_statement, $db_conn);

	if (!$query){
		die("Database error");
	} else {
		echo "success";
	}
?>
