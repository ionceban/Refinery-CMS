<?php
	if (!$_POST['kd_type'] || ($_POST['kd_type'] != 'keywords' && $_POST['kd_type'] != 'deliverables')){
		die("failed");
	}

	if (!$_POST['kd_id']){
		die("failed");
	}

	if (!$_POST['new_value'] || $_POST['new_value'] == ''){
		die("failed");
	}

	require('../db_connect.php');

	$query_statement = "UPDATE " . $_POST['kd_type'] . " SET name='" . addslashes($_POST['new_value']) . "' WHERE id='" . $_POST['kd_id'] . "'";
	$query = mysql_query($query_statement, $db_conn);

	if (!$query){
		die("failed");
	}

	echo "success";
?>
