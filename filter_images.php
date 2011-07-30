<?php
	require('db_connect.php');
	require('utils.php');
	
	$Mediums = $_POST['mediums'];
	$Divisions = $_POST['divisions'];
	$Deliverables = $_POST['deliverables'];
	$Keywords = $_POST['keywords'];
	$order = $_POST['order'];

	$query_statement = get_filter_query($Mediums, $Divisions, $Deliverables, $Keywords, $order);
	
	$query = mysql_query($query_statement, $db_conn);
	
	$map = array();
	
	while ($row = mysql_fetch_row($query)){
		$map[$row[0]]['image_id'] = $row[0];
		$map[$row[0]]['featured'] = $row[1];
		$map[$row[0]]['filename'] = $row[2];
		$map[$row[0]]['date'] = $row[3];
		$map[$row[0]]['project_name'] = $row[4];
		$map[$row[0]]['medium_name'] = $row[5];
		$map[$row[0]]['division_name'] = $row[6];
		$map[$row[0]]['deliverables'][0] = 0;
		$map[$row[0]]['keywords'][0] = 0;
	}
	
	$query_statement = "SELECT images.id, keywords.name FROM images, imgkeyws, keywords WHERE";
	$query_statement .= " (images.id=imgkeyws.image_id AND keywords.id=imgkeyws.keyword_id";
	$query_statement .= " AND images.queued=0)";
	$query_statement .= " ORDER BY keywords.name";
	
	$query = mysql_query($query_statement, $db_conn);
	
	while ($row = mysql_fetch_row($query)){
		if ($map[$row[0]]['image_id']){
			$map[$row[0]]['keywords'][0]++;
			array_push($map[$row[0]]['keywords'], $row[1]);
		}
	}
	
	$query_statement = "SELECT images.id, deliverables.name FROM images, imgdelivs, deliverables WHERE";
	$query_statement .= " (images.id=imgdelivs.image_id AND deliverables.id=imgdelivs.deliverable_id";
	$query_statement .= " AND images.queued=0)";
	$query_statement .= " ORDER BY deliverables.name";
	
	$query = mysql_query($query_statement, $db_conn);
	
	while ($row = mysql_fetch_row($query)){
		if ($map[$row[0]]['image_id']){
			$map[$row[0]]['deliverables'][0]++;
			array_push($map[$row[0]]['deliverables'], $row[1]);
		}
	}
	
	$response[0] = 0;
	
	foreach ($map as $single_image){
		$response[0]++;
		$response[$response[0]]['image_id'] = $single_image['image_id'];
		$response[$response[0]]['featured'] = $single_image['featured'];
		$response[$response[0]]['filename'] = $single_image['filename'];
		$response[$response[0]]['date'] = $single_image['date'];
		$response[$response[0]]['project_name'] = $single_image['project_name'];
		$response[$response[0]]['medium_name'] = $single_image['medium_name'];
		$response[$response[0]]['division_name'] = $single_image['division_name'];
		$response[$response[0]]['deliverables'] = $single_image['deliverables'];
		$response[$response[0]]['keywords'] = $single_image['keywords'];
	}
	
	echo json_encode($response);
?>