<?php
	require('../db_connect.php');

	$image_array = json_decode($_POST['image_list'], true);

	$query_statement = "SELECT images.id, images.featured, projects.name, images.name, images.date, mediums.name, divisions.name, images.shadowbox ";
	$query_statement .= " FROM images, projects, mediscs, mediums, didiscs, divisions WHERE ";
	$query_statement .= "(projects.id=images.project_id AND images.medisc_id=mediscs.id AND images.didisc_id=didiscs.id AND ";
	$query_statement .= "mediscs.medium_id=mediums.id AND didiscs.division_id=divisions.id AND (1=0";

	for ($i = 1; $i <= $image_array[0]; $i++){
		$query_statement .= " OR images.id=" . $image_array[$i];
	}

	$query_statement .= "))";
	$query = mysql_query($query_statement, $db_conn);

	$map = array();

	while ($row = mysql_fetch_row($query)){
		$map[$row[0]]['image_id'] = $row[0];
		$map[$row[0]]['featured'] = $row[1];
		$map[$row[0]]['filename'] = $row[3];
		$map[$row[0]]['date'] = $row[4];
		$map[$row[0]]['project_name'] = $row[2];
		$map[$row[0]]['medium_name'] = $row[5];
		$map[$row[0]]['division_name'] = $row[6];
		$map[$row[0]]['shadowbox'] = $row[7];
		$map[$row[0]]['keywords'][0] = 0;
		$map[$row[0]]['deliverables'][0] = 0;
	}

	$query_statement = "SELECT images.id, deliverables.name FROM images, imgdelivs, deliverables WHERE ";
	$query_statement .= "(images.id=imgdelivs.image_id AND deliverables.id=imgdelivs.deliverable_id AND (1=0";

	for ($i = 1; $i <= $image_array[0]; $i++){
		$query_statement .= " OR images.id=" . $image_array[$i];
	}

	$query_statement .= ")) ORDER BY deliverables.name";
	$query = mysql_query($query_statement, $db_conn);

	while ($row = mysql_fetch_row($query)){
		$map[$row[0]]['deliverables'][0]++;
		array_push($map[$row[0]]['deliverables'], $row[1]);
	}

	$query_statement = "SELECT images.id, keywords.name FROM images, imgkeyws, keywords WHERE ";
	$query_statement .= "(images.id=imgkeyws.image_id AND keywords.id=imgkeyws.keyword_id AND (1=0";

	for ($i = 1; $i <= $image_array[0]; $i++){
		$query_statement .= " OR images.id=" . $image_array[$i];
	}

	$query_statement .= ")) ORDER BY keywords.name";
	$query = mysql_query($query_statement, $db_conn);
	
	while ($row = mysql_fetch_row($query)){
		$map[$row[0]]['keywords'][0]++;
		array_push($map[$row[0]]['keywords'], $row[1]);
	}
	
	$response[0] = 0;

	foreach ($map as $single_image){
		$response[0]++;
		$response[$response[0]]['image_id'] = $single_image['image_id'];
		$response[$response[0]]['featured'] = $single_image['featured'];
		$response[$response[0]]['date'] = $single_image['date'];
		$response[$response[0]]['filename'] = $single_image['filename'];
		$response[$response[0]]['project_name'] = $single_image['project_name'];
		$response[$response[0]]['medium_name'] = $single_image['medium_name'];
		$response[$response[0]]['division_name'] = $single_image['division_name'];
		$response[$response[0]]['shadowbox'] = $single_image['shadowbox'];
		$response[$response[0]]['keywords'] = $single_image['keywords'];
		$response[$response[0]]['deliverables'] = $single_image['deliverables'];
	}

	echo json_encode($response);
?>
