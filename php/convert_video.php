<?php
	require('config.php');
	
	$filename = $_POST['filename'];
	$file_attrs = preg_split('/\./', $filename);
	
	$new_filename = $file_attrs[0] . ".ogg";
	set_time_limit(0);
	
	$old_target = $_SERVER['DOCUMENT_ROOT'] . "/refinery-cms/projs/" . $filename;
	$old_target = str_replace('//', '/', $old_target);
	
	$new_target = $_SERVER['DOCUMENT_ROOT'] . "/refinery-cms/projs/" . $new_filename;
	$new_target = str_replace('//', '/', $new_target);
	
	$converter_path = $_SERVER['DOCUMENT_ROOT'] . "/refinery-cms/apps/ffmpeg2theora.";
	if ($config_OS == 'Windows'){
		$converter_path .= "exe";
	} else {
		$converter_path .= "bin";
	}
	$converter_path = str_replace('//', '/', $converter_path);
	
	$command = $converter_path . " -o " . $new_target . " -v 10 --max_size 400x480 " . $old_target;
	
	shell_exec($command);
	unlink($old_target);
	
	echo "converted";
?>