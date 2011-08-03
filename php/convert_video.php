<?php
	$filename = $_POST['filename'];
	$file_attrs = preg_split('/\./', $filename);
	
	$new_filename = $file_attrs[0] . ".ogg";
	set_time_limit(0);
	
	$old_target = $_SERVER['DOCUMENT_ROOT'] . "refinery-cms/projs/" . $filename;
	$new_target = $_SERVER['DOCUMENT_ROOT'] . "refinery-cms/projs/" . $new_filename;
	$ffmpeg2theora_path = $_SERVER['DOCUMENT_ROOT'] . "refinery-cms/php/ffmpeg2theora.exe";
	
	$command = $ffmpeg2theora_path . " -o " . $new_target . " " . $old_target;
	
	shell_exec($command);
	
	echo "converted";
?>