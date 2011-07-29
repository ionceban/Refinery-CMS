<?php
	require('db_connect.php');
	require('utils.php');
	
	$image_array = list_to_array($_POST['id_list']);
	
	$query_statement = "SELECT medisc_id, didisc_id FROM images WHERE (1=0";
	
	foreach ($image_array as $single_image){
		$query_statement .= " OR id='" . $single_image . "'";
	}
	
	$query_statement .= ")";
	
	$query = mysql_query($query_statement, $db_conn);
	
	$row = mysql_fetch_row($query);
	
	$default_medisc = $row[0];
	$default_didisc = $row[1];
	
	while ($row = mysql_fetch_row($query)){
		if ($row[0] != $default_medisc || $row[1] != $default_didisc){
			$query_statement = "SELECT id FROM mediums WHERE name='print'";
			$query = mysql_query($query_statement, $db_conn);
			$row = mysql_fetch_row($query);
			
			$return_med = $row[0];
			
			$query_statement = "SELECT id FROM disciplines WHERE name='home entertainment'";
			$query = mysql_query($query_statement, $db_conn);
			$row = mysql_fetch_row($query);
			
			$return_med_disc = $row[0];
			
			$query_statement = "SELECT id FROM divisions WHERE name='home entertainment'";
			$query = mysql_query($query_statement, $db_conn);
			$row = mysql_fetch_row($query);
			
			$return_div = $row[0];
			
			$query_statement = "SELECT id FROM disciplines WHERE name='print'";
			$query = mysql_query($query_statement, $db_conn);
			$row = mysql_fetch_row($query);
			
			$return_div_disc = $row[0];
			
			$response = $return_med . "_" . $return_med_disc . "_" . $return_div . "_" . $return_div_disc;
			die($response);
		}
	}
	
	$query_statement = "SELECT medium_id, discipline_id FROM mediscs WHERE id='" . $default_medisc . "'";
	
	$query = mysql_query($query_statement, $db_conn);
	
	$row = mysql_fetch_row($query);
	
	$return_med = $row[0];
	$return_med_disc = $row[1];
	
	$query_statement = "SELECT division_id, discipline_id FROM didiscs WHERE id='" . $default_didisc . "'";
	
	$query = mysql_query($query_statement, $db_conn);
	
	$row = mysql_fetch_row($query);
	
	$return_div = $row[0];
	$return_div_disc = $row[1];
	
	echo $return_med . "_" . $return_med_disc . "_" . $return_div . "_" . $return_div_disc;
?>