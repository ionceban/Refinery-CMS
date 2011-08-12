<?php

	require('../db_connect.php');
	require('../utils.php');

	if (!$_POST['image_id']){
		die("Please select an image");
	}

	if (!$_POST['filename'] || $_POST['filename'] == ''){
		die("File name cannot be blank");
	}

	$image_id = $_POST['image_id'];
	$filename = $_POST['filename'];

	$query_statement = "SELECT name FROM images WHERE id='" . $image_id . "'";
	$query = mysql_query($query_statement, $db_conn);
	$row = mysql_fetch_row($query);

	$file_attrs = preg_split('/\./', $row[0]);

	$query_statement = "SELECT * FROM images WHERE (id!='" . $image_id . "') AND ";
	$query_statement .= "(name LIKE '%" . addslashes($filename) . "\\.%')";
	$query = mysql_query($query_statement, $db_conn);
	$row = mysql_fetch_row($query);

	if ($row){
		die("Filename already exists");
	}

	$query_statement = "UPDATE images SET images.name='" . $filename . "." . $file_attrs[1];
	$query_statement .= "' WHERE images.id='" . $image_id . "'";
	$query = mysql_query($query_statement, $db_conn);

	if ($query){
		$thumber_extension = extension_checker('../projs/' . $file_attrs[0] . '_t_thumber');

		$old_file = "../projs/" . $file_attrs[0] . "." . $file_attrs[1];
		$old_thumber = "../projs/" . $file_attrs[0] . "_t_thumber." . $thumber_extension;
		$old_normal = "../projs/" .  $file_attrs[0] . "_t_normal." . $thumber_extension;
		$old_featured = "../projs/" . $file_attrs[0] . "_t_featured." . $thumber_extension;
		$old_list = "../projs/" . $file_attrs[0] . "_t_list." . $thumber_extension;
		$old_grid = "../projs/" . $file_attrs[0] . "_t_grid." . $thumber_extension;

		$new_file = "../projs/" . $filename . "." . $file_attrs[1];
		$new_thumber = "../projs/" . $filename . "_t_thumber." . $thumber_extension;
		$new_normal = "../projs/" . $filename . "_t_normal." . $thumber_extension;
		$new_featured = "../projs/" . $filename . "_t_featured." . $thumber_extension;
		$new_list = "../projs/" . $filename . "_t_list." . $thumber_extension;
		$new_grid = "../projs/" . $filename . "_t_grid." . $thumber_extension;

		rename($old_file, $new_file);
		rename($old_thumber, $new_thumber);
		rename($old_normal, $new_normal);
		rename($old_featured, $new_featured);
		rename($old_list, $new_list);
		rename($old_grid, $new_grid);

		echo "success";
	} else {
		die("DB Error saving filename");
	}
?>
