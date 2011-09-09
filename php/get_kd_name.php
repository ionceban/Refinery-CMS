<?php
	require('../db_connect.php');

	if (!$_POST['kd_type'] || ($_POST['kd_type'] != 'keywords' && $_POST['kd_type'] != 'deliverables')){
		die('name');
	}

	if (!$_POST['kd_id']){
		die('name');
	}

	$query_statement = "SELECT name FROM " . $_POST['kd_type'] . " WHERE id='" . $_POST['kd_id'] . "'";
	$query = mysql_query($query_statement, $db_conn);

	if ($row = mysql_fetch_row($query)){
		echo $row[0];
	} else {
		die('name');
	}
?>
