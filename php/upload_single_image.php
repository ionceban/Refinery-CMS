<?php
	
	$accepted_formats = array('jpg', 'png', 'gif', 'mov', 'avi', 'mp4', 'ogg');
	$image_formats = array('jpg', 'png', 'gif');

	require('../db_connect.php');

	if (!$_FILES['Filedata']['name']){
		die('missing file');
	}
	

	$filename = basename($_FILES['Filedata']['name']);
	
	$query_statement = "SELECT * FROM images WHERE name='" . $filename . "'";
	$query = mysql_query($query_statement, $db_conn);
	$row = mysql_fetch_row($query);
	
	if ($row){
		die("File already exists");
	}
	
	$target = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . $filename);
	
	if (move_uploaded_file($_FILES['Filedata']['tmp_name'], $target)){
		$file_attrs = preg_split('/\./', $target);
		if (!in_array($file_attrs[1], $accepted_formats)){
			unlink($target);
			die("File is neither image nor movie");
		}
		
		if (in_array($file_attrs[1], $image_formats)){
			list($image_width, $image_height, $image_src, $image_attrs) = getimagesize($target);
			if ($image_width < 222 || $image_height < 318){
				unlink($target);
				die("File must be at least 222x318.");
			}
			
			$thumber_path = $file_attrs[0] . "_t_thumber." . $file_attrs[1];
			$normal_path = $file_attrs[0] . "_t_normal." . $file_attrs[1];
			$featured_path = $file_attrs[0] . "_t_featured." . $file_attrs[1];
			$list_path = $file_attrs[0] . "_t_list." . $file_attrs[1];
			$grid_path = $file_attrs[0] . "_t_grid." . $file_attrs[1];
		} else {
			$dummy_thumb = "../images/dummy_thumb.jpg";
			$thumber_path = $file_attrs[0] . "_t_thumber.jpg";
			$normal_path = $file_attrs[0] . "_t_normal.jpg";
			$featured_path = $file_attrs[0] . "_t_featured.jpg";
			$list_path = $file_attrs[0] . "_t_list.jpg";
			$grid_path = $file_attrs[0] . "_t_grid.jpg";
		}
		
		$query_statement = "SELECT mediscs.id FROM mediscs,mediums,disciplines WHERE ";
		$query_statement .= "(mediums.id=mediscs.medium_id AND disciplines.id=mediscs.discipline_id AND ";
		$query_statement .= "mediums.name='print' AND disciplines.name='home entertainment')";
		
		$query = mysql_query($query_statement, $db_conn);
		$row = mysql_fetch_row($query);
		
		$dummy_medisc = $row[0];
		
		$query_statement = "SELECT didiscs.id FROM didiscs,divisions,disciplines WHERE ";
		$query_statement .= "(divisions.id=didiscs.division_id AND disciplines.id=didiscs.discipline_id AND ";
		$query_statement .= "divisions.name='home entertainment' AND disciplines.name='print')";
		
		$query = mysql_query($query_statement, $db_conn);
		$row = mysql_fetch_row($query);
		
		$dummy_didisc = $row[0];
		
		if (in_array($file_attrs[1], $image_formats)){
			$to_copy = $target;
		} else {
			$to_copy = $dummy_thumb;
		}
		
		$date = date('Y-m-d');
		
		$year_value = date('Y');
		$query_statement = "SELECT id FROM years WHERE value='" . $year_value . "'";
		$query = mysql_query($query_statement, $db_conn);
		$row = mysql_fetch_row($query);
		
		if (!$row){
			$query_statement = "INSERT INTO years(value) VALUES('" . $year_value . "')";
			$query = mysql_query($query_statement);
			if (!$query){
				unlink($target);
				die("Database error");
			}
			$query_statement = "SELECT id FROM years WHERE value='" . $year_value . "'";
			$row = mysql_fetch_row($query);
			
			$year_id = $row[0];
		} else {
			$year_id = $row[0];
		}
		
		$query_statement = "INSERT INTO images(name,medisc_id,didisc_id,project_id,year_id,date,queued,featured)";
		$query_statement .= " VALUES('" . $filename . "','" . $dummy_medisc . "','" . $dummy_didisc . "','6','" . $year_id . "','" . $date . "',true,false)";
		$query = mysql_query($query_statement, $db_conn);
		if (!$query){
			unlink($target);
			die("Database error");
		} else {
			copy($to_copy, $thumber_path);
			copy($to_copy, $normal_path);
			copy($to_copy, $featured_path);
			copy($to_copy, $list_path);
			copy($to_copy, $grid_path);
		}
	};
	
	
	echo "success";
?>