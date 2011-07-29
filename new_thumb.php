<?php
	require('db_connect.php');
	
	if (!$_POST['image_id']) die("failed");
	$file_attrs = preg_split('/\./', $_FILES['uploaded_file']['name']);
	if ($file_attrs[1] != 'jpg' && $file_attrs[1] != 'png' && $file_attrs[1] != 'gif'){
		die("failed");
	}
	
	list($up_width, $up_height, $up_src, $up_attr) = getimagesize($_FILES['uploaded_file']['tmp_name']);

	
	$query_statement = "SELECT name FROM images WHERE id='" . $_POST['image_id'] . "'";
	$query = mysql_query($query_statement, $db_conn);
	$row = mysql_fetch_array($query);
	
	$aux_splitter = preg_split('/\./', $row['name']);
	$core_name = $aux_splitter[0];
	$core_ext = $aux_splitter[1];
	
	if ($core_ext == 'jpg' || $core_ext == 'png' || $core_ext == 'gif'){
		if ($up_width < 222 || $up_height < 318) die("failed");
	} else {
		if ($up_width < 468 || $up_height < 318) die("failed");
	}
	
	$base_name = "projs/" . $core_name . "_t_thumber.";
	$ext_jpeg = $base_name . "jpg";
	$ext_png = $base_name . "png";
	$ext_gif = $base_name . "gif";
	
	if (file_exists($ext_gif)){
		$file_path = $ext_gif;
	} else if (file_exists($ext_png)){
		$file_path = $ext_png;
	} else if (file_exists($ext_jpeg)){
		$file_path = $ext_jpeg;
	}
	
	
	$file_attrs = preg_split('/_t_thumber\./', $file_path);
	
	$old_thumber = $file_attrs[0] . "_t_thumber." . $file_attrs[1];
	$old_normal = $file_attrs[0] . "_t_normal." . $file_attrs[1];
	$old_featured = $file_attrs[0] . "_t_featured." . $file_attrs[1];
	$old_list = $file_attrs[0] . "_t_list." . $file_attrs[1];
	$old_grid = $file_attrs[0] . "_t_grid." . $file_attrs[1];
	
	unlink($old_thumber);
	unlink($old_normal);
	unlink($old_featured);
	unlink($old_grid);
	unlink($old_list);
	
	$file_attrs = preg_split('/\./', $_FILES['uploaded_file']['name']);
	$new_ext = $file_attrs[1];
	
	$new_thumber = 'projs/' . $core_name . '_t_thumber.' . $new_ext;
	$new_normal = 'projs/' . $core_name . '_t_normal.' . $new_ext;
	$new_featured = 'projs/' . $core_name . '_t_featured.' . $new_ext;
	$new_list = 'projs/' . $core_name . '_t_list.' . $new_ext;
	$new_grid = 'projs/' . $core_name . '_t_grid.' . $new_ext;
	
	if (!move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $new_thumber)){
		die("failed");
	} else {
		copy($new_thumber, $new_normal);
		copy($new_thumber, $new_featured);
		copy($new_thumber, $new_list);
		copy($new_thumber, $new_grid);
	}
	
	echo "projs/" . $core_name . "." . $core_ext;
?>