<?php
	require('db_connect.php');
	require('db_utils.php');
	
	if (!$_POST['image_id']) die("Please select an image");
	if (!$_POST['project_name'] || $_POST['project_name'] == "") die("Please type a project name");
	if (!$_POST['day'] || !$_POST['month'] || !$_POST['year']) die("Please select date");
	if (!$_POST['d_id'] || !$_POST['m_id'] || !$_POST['dd_id'] || !$_POST['md_id']) die("Please select medium, division and discipline");
	if (!$_POST['file_name']) die("Please type a file name");
	if (!$_POST['file_ext']) die("Please select a file extension");
	if (strlen($_POST['file_ext']) != 3) die("File extension is inappropriate");
	
	$image_id = $_POST['image_id'];
	$medium_id = $_POST['m_id'];
	$discipline_id = $_POST['md_id'];
	
	$query_statement = "SELECT id FROM mediscs WHERE (medium_id='" . $medium_id . "' AND discipline_id='" . $discipline_id . "')";
	$query = mysql_query($query_statement, $db_conn);
	$row = mysql_fetch_array($query);
	if (!$row) die('Wrong medium or discipline');
	
	$medisc_id = $row['id'];
	
	$division_id = $_POST['d_id'];
	$discipline_id = $_POST['dd_id'];
	
	$query_statement = "SELECT id FROM didiscs WHERE (division_id='" . $division_id . "' AND discipline_id='" . $discipline_id . "')";
	$query = mysql_query($query_statement, $db_conn);
	$row = mysql_fetch_array($query);
	if (!$row) die('Wrong division or discipline.');
	
	$didisc_id = $row['id'];
	
	$name = $_POST['file_name'] . "." . $_POST['file_ext'];
	$query_statement = "SELECT id FROM images WHERE name='" . $name . "'";
	$query = mysql_query($query_statement, $db_conn);
	$row = mysql_fetch_array($query);
	
	if ($row && $row['id'] != $image_id) die('File name already exists');
	
	$year_value = $_POST['year'];
	
	$query_statement = "SELECT id FROM years WHERE value='" . $year_value . "'";
	$query = mysql_query($query_statement, $db_conn);
	$row = mysql_fetch_array($query);
	if (!$row) die('Year is not valid');
	
	$year_id = $row['id'];
	
	$month = $_POST['month'];
	$day = $_POST['day'];
	$date = $year_value . "-" . $month . "-" . $day;
	
	$project_name = $_POST['project_name'];
	$query_statement = "SELECT id FROM projects WHERE name='" . addslashes($project_name) . "'";
	$query = mysql_query($query_statement, $db_conn);
	$row = mysql_fetch_array($query);
	
	if (!$row){
		$query_statement = "INSERT INTO projects(name) VALUES('" . addslashes($project_name) . "')";
		$query = mysql_query($query_statement);
		if (!$query) die("Could not add project " . $project_name);
		
		$query_statement_2 = "SELECT id FROM projects WHERE name='" . $project_name . "'";
		$query_2 = mysql_query($query_statement_2, $db_conn);
		$row_2 = mysql_fetch_array($query_2);
		
		$project_id = $row_2['id'];
	} else {
		$project_id = $row['id'];
	}
	
	$query_statement = "SELECT name FROM images WHERE id='" . $image_id . "'";
	$query = mysql_query($query_statement, $db_conn);
	$row = mysql_fetch_array($query);
	
	$old_name = "projs/" . $row['name'];
	$new_name = "projs/" . $name;
	
	$old_arr = preg_split('/\./', $row['name']);
	$new_arr = preg_split('/\./', $name);
	$old_thumber = "projs/" . $old_arr[0] . "_thumber." . $old_arr[1];
	$new_thumber = "projs/" . $new_arr[0] . "_thumber." . $new_arr[1];
	
	$old_thumb = "projs/" . $old_arr[0] . "_thumb." . $old_arr[1];
	$new_thumb = "projs/" . $new_arr[0] . "_thumb." . $new_arr[1];
	
	$old_resized = "projs/" . $old_arr[0] . "_resized." . $old_arr[1];
	$new_resized = "projs/" . $new_arr[0] . "_resized." . $new_arr[1];
	

	if (!rename($old_name, $new_name)) die("Could not rename file");
	rename($old_thumb, $new_thumb);
	rename($old_thumber, $new_thumber);
	rename($old_resized, $new_resized);
	
	$query_statement = "UPDATE images SET name='" . $name . "', project_id='" . $project_id . "'";
	$query_statement .= ", date='" . $date . "', medisc_id='" . $medisc_id . "', didisc_id='" . $didisc_id ."'";
	$query_statement .= ", year_id='" . $year_id . "' WHERE id='" . $image_id . "'";
	$query = mysql_query($query_statement, $db_conn);
	if (!$query){
		rename($new_name, $old_name);
		die($query_statement);
	};
	
	$query_statement = "DELETE FROM imgdelivs WHERE image_id='" . $image_id . "'";
	$query = mysql_query($query_statement, $db_conn);
	if (!$query) die("Could not set deliverable. Please try again later");
	
	$deliverables_array = parse_string($_POST['deliverables']);
	foreach ($deliverables_array as $elem){
		$query_statement = "INSERT INTO imgdelivs(image_id,deliverable_id) VALUES('" . $image_id . "','" . $elem ."')";
		$query = mysql_query($query_statement);
		if (!$query) die("Could not set deliverable. Please try again later");
	}
	
	$query_statement = "DELETE FROM imgkeyws WHERE image_id='" . $image_id . "'";
	$query = mysql_query($query_statement, $db_conn);
	if (!$query) die("Could not set keywords. Please try again later");
	
	$keywords_array = parse_string($_POST['keywords']);
	foreach ($keywords_array as $elem){
		$query_statement = "INSERT INTO imgkeyws(image_id,keyword_id) VALUES('" . $image_id . "','" . $elem . "')";
		$query = mysql_query($query_statement);
		if (!$query) die("Could not set keywords. Please try again later");
	}
	
	echo "success";
?>