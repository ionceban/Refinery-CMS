<?php
	require('db_connect.php');
	require('utils.php');
	
	$id_list = $_POST['id_list'];
	
	$id_array = list_to_array($id_list);
	
	foreach ($id_array as $single_image_id){
		$response = delete_single_image($single_image_id, $db_conn);
		if ($response != 'success'){
			die($response);
		}
	}
	
	echo "success";
?>
