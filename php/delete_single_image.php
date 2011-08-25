<?php
	require('../db_connect.php');
	require('../utils.php');
	
	if (!$_POST['image_id'] || !ctype_alnum($_POST['image_id'])){
		die("failed");
	}

	$query_statement = "SELECT name FROM images WHERE id='" . $_POST['image_id'] . "'";
	$query = mysql_query($query_statement, $db_conn);

	$row = mysql_fetch_row($query);

	if (!$row){
		die("failed");
	}

	$query_statement = "DELETE FROM imgdelivs WHERE image_id='" . $_POST['image_id'] . "'";
	$query = mysql_query($query_statement, $db_conn);

	if (!$query){
		die("failed");
	}

	$query_statement = "DELETE FROM imgkeyws WHERE image_id='" . $_POST['image_id'] . "'";
	$query = mysql_query($query_statement, $db_conn);

	if (!$query){
		die("failed");
	}

	$query_statement = "DELETE FROM images WHERE id='" . $_POST['image_id'] . "'";
	$query = mysql_query($query_statement, $db_conn);

	if (!$query){
		die("failed");
	}
	
	$file_attrs = preg_split('/\./', $row[0]);

	$thumber_body = $file_attrs[0] . "_t_thumber";
	$thumber_ext = extension_checker("../projs/" . $thumber_body);

	$old_file = "../projs/" . $file_attrs[0] . "." . $file_attrs[1];
	unlink($old_file);

	if ($file_attrs[1] == 'ogg'){
		$old_mp4 = "../projs/" . $file_attrs[0] . ".mp4";
		unlink($old_mp4);
	}

	$old_thumber = "../projs/" . $file_attrs[0] . "_t_thumber." . $thumber_ext;
	unlink($old_thumber);

	$old_normal = "../projs/" . $file_attrs[0] . "_t_normal." . $thumber_ext;
	unlink($old_normal);

	$old_featured = "../projs/" . $file_attrs[0] . "_t_featured." . $thumber_ext;
	unlink($old_featured);

	$old_list = "../projs/" . $file_attrs[0] . "_t_list." . $thumber_ext;
	unlink($old_list);

	$old_grid = "../projs/" . $file_attrs[0] . "_t_grid." . $thumber_ext;
	unlink($old_grid);

	echo $_POST['image_id'];
?>
