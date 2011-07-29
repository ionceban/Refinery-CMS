<?php
	require('db_connect.php');
	require('db_utils.php');
	
	if (!$_FILES['uploaded']['name']) die("failed");
	$name = basename($_FILES['uploaded']['name']);
	$current_year = date('Y');
	$year_id = get_year_by_value($current_year, $db_conn);
	if (!$year_id){
		$query_statement = "INSERT INTO years(value) VALUES('" . $current_year . "')";
		mysql_query($query_statement);
		$year_id = get_year_by_value($current_year, $db_conn);
	}
	$date = date('Y-m-d');
	
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
	
	$query_statement = "INSERT INTO images(name,medisc_id,didisc_id,project_id,year_id,date,queued,featured)";
	$query_statement .= " VALUES('" . $name . "','" . $dummy_medisc . "','" . $dummy_didisc . "','6','" . $year_id . "','" . $date . "',true,false)";
	
	$target = 'projs/' . $name;
	if (move_uploaded_file($_FILES['uploaded']['tmp_name'], $target)){
		$file_attrs = preg_split('/\./', $target);
		if ($file_attrs[1] == 'jpg' || $file_attrs[1] == 'png' || $file_attrs[1] == 'gif'){
			list($up_width, $up_height, $up_src, $up_attr) = getimagesize($target);
			if ($up_width < 222 || $up_height < 318){
				unlink($target);
				die("failed");
			}
		} 
		
		if (mysql_query($query_statement)){
			$file_attrs = preg_split('/\./', $target);
			if ($file_attrs[1] == 'jpg' || $file_attrs[1] == 'png' || $file_attrs[1] == 'gif'){
				$t_thumber = $file_attrs[0] . "_t_thumber." . $file_attrs[1];
				$t_normal = $file_attrs[0] . "_t_normal." . $file_attrs[1];
				$t_featured = $file_attrs[0] . "_t_featured." . $file_attrs[1];
				$t_list = $file_attrs[0] . "_t_list." . $file_attrs[1];
				$t_grid = $file_attrs[0] . "_t_grid." . $file_attrs[1];
				copy($target, $t_thumber);
				copy($target, $t_normal);
				copy($target, $t_featured);
				copy($target, $t_list);
				copy($target, $t_grid);
			} else {
				$dummy_thumb_path = 'images/dummy_thumb.jpg';
				$t_thumber = $file_attrs[0] . "_t_thumber." . "jpg";
				$t_normal = $file_attrs[0] . "_t_normal." . "jpg";
				$t_featured = $file_attrs[0] . "_t_featured." . "jpg";
				$t_list = $file_attrs[0] . "_t_list." . "jpg";
				$t_grid = $file_attrs[0] . "_t_grid." . "jpg";
				copy($dummy_thumb_path, $t_thumber);
				copy($dummy_thumb_path, $t_normal);
				copy($dummy_thumb_path, $t_featured);
				copy($dummy_thumb_path, $t_list);
				copy($dummy_thumb_path, $t_grid);
			}
			
			echo $target;
		} else {
			unlink($target);
			die("failed");
		}
	} else {
			die("failed");
	}
?>