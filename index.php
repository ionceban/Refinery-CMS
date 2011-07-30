<?php
	$db_conn = mysql_connect('localhost', 'root', 'okapistudio');
	if (!$db_conn){
		die("Could not connect to DB");
	}
	mysql_select_db('ref_users') or die("Could not select DB");
	
	if ($_POST['username'] && $_POST['password']){
		$query_statement = "SELECT password FROM users WHERE username='" . $_POST['username'] . "'";
		$query_statement .= " AND password='" . md5($_POST['password']) . "'";
		$query = mysql_query($query_statement, $db_conn);
		$row = mysql_fetch_row($query);
		
		if (!$row){
			header('Location: login.php');
		} else {
			setcookie("username", $_POST['username'], time() + 3600);
			setcookie("password", $_POST['password'], time() + 3600);
		}
	} else {
		if (!isset($_COOKIE['username']) || !isset($_COOKIE['password'])){
			header('Location: login.php');
		} else {
			$query_statement = "SELECT password FROM users WHERE username='" . $_COOKIE['username'] . "'";
			$query_statement .= " AND password='" . md5($_COOKIE['password']) . "'";
			$query = mysql_query($query_statement, $db_conn);
			$row = mysql_fetch_row($query);
			
			if (!$row){
				setcookie("username", "", time() - 1000000);
				setcookie("password", "", time() - 1000000);
				header('Location: login.php');
			}
		}
	}
?>
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link rel="shortcut icon" href="/favicon.ico">
        <!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
        <!--[if IE 8]>
		<link type="text/css" rel="stylesheet" href="css/ie8.css"/>
		<![endif]-->
        <!--[if IE 7]>
		<link type="text/css" rel="stylesheet" href="css/ie7.css"/>
		<![endif]-->
        <title>Refinery CMS</title>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.14.custom.css" />
        <link rel="stylesheet" type="text/css" href="css/jquery.megamenu.css" />
        <link rel="stylesheet" type="text/css" href="css/dropkick.css" />
        <link rel="stylesheet" type="text/css" href="css/Jcrop.css" />
        <script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
        <script src="js/jquery-ui.min.js" type="text/javascript"></script>
        <script src="js/jquery.dropkick-1.0.0.js" type="text/javascript" charset="utf-8"></script>
        <script src="js/jquery.megamenu.js" type="text/javascript"></script>
        <script type="text/javascript" src="js/jquery.Jcrop.min.js"></script>
        <script type="text/javascript" src="js/jquery.form.js"></script>
        <script type="text/javascript" src="js/overlay.js"></script>
        <script type="text/javascript" src="js/results.js"></script>
		<script type="text/javascript">
		
		
			function save_image_details(){
				var scaled_height = $('#thumbnail-selector').css('height').split('px')[0];
        		var scaled_width = $('#thumbnail-selector').css('width').split('px')[0];
        		var imgPreloader = new Image();
        		imgPreloader.src= $('#thumbnail-selector').attr('src');
        		imgPreloader.onload = function(){
        			var ajax_thumb = new XMLHttpRequest();
        			ajax_thumb.open("POST", "get_thumbnail.php", true);
        			ajax_thumb.onreadystatechange = function(){
        				if (ajax_thumb.readyState == 4 && ajax_thumb.status == 200){
        					if (ajax_thumb.responseText == "success"){
        						var file_attrs = $('#thumbnail-selector').attr('src').split('_t_thumber.');
        						var t_normal = file_attrs[0] + "_t_normal." + file_attrs[1];
        						var t_featured = file_attrs[0] + "_t_featured." + file_attrs[1];
        						var big_file_ext = extension_detect(file_attrs[0].split('projs/')[1] + '.');
        						
        						if (big_file_ext == 'jpg' || big_file_ext == 'png' || big_file_ext == 'gif'){
	        						fit_image(t_featured.split('?')[0], 222, 318);
	        						fit_image(t_normal.split('?')[0], 99, 147);
	        					} else {
	        						fit_image(t_featured.split('?')[0], 468, 318);
	        						fit_image(t_normal.split('?')[0], 222, 147);
	        					}
        						var image_id = parseInt($('#overlay').attr('image_id'));
								var keywords_string = "";
								var keywords_list = $('#keywords-list li');
								var Nkeywords = 0;
								keywords_list.each(function(){
									if ($(this).hasClass('selected')){
										Nkeywords += 1;
										if (Nkeywords > 1) keywords_string += "_";
										keywords_string += $(this).attr('keyword_id');
									}
								});
								
								var deliverables_string = "";
								var deliverables_list = $('#deliverables-list li');
								var Ndeliverables = 0;
								deliverables_list.each(function(){
									if ($(this).hasClass('selected')){
										Ndeliverables += 1;
										if (Ndeliverables > 1) deliverables_string += "_";
										deliverables_string += $(this).attr('deliverable_id');
									}
								});
								
								var division_id = $('#division-sel').val();
								var medium_id = $('#medium-sel').val();
								var div_disc_id = $('#div-disc-sel').val();
								var med_disc_id = $('#med-disc-sel').val();
								var project_name = $('#project').val();
								var file_name = $('#file-name').val();
								var file_ext = $('#overlay').attr('file_ext');
								
								var day = $('#date-day-sel').val();
								var month = $('#date-month-sel').val();
								var year = $('#date-year-sel').val();
								
								ajax_save = new XMLHttpRequest();
								ajax_save.open("POST", "image_save.php", true);
								ajax_save.onreadystatechange = function(){
									if (ajax_save.readyState == 4 && ajax_save.status == 200){
										if (ajax_save.responseText == 'success'){
											$('#overlay').dialog('close');
											update_queue();
											filter_live_images();
										} else {
											alert(ajax_save.responseText);
										}
									}
								}
								ajax_save.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
								var query_string = 'project_name=' + project_name + '&file_name=' + file_name + '&file_ext=' + file_ext;
								query_string += '&keywords=' + keywords_string + '&deliverables=' + deliverables_string;
								query_string += '&d_id=' + division_id + '&m_id=' + medium_id + '&md_id=' + med_disc_id;
								query_string += '&dd_id=' + div_disc_id + '&day=' + day + '&month=' + month;
								query_string += '&year=' + year + '&image_id=' + image_id;
								ajax_save.send(query_string);
        					} else {
        						alert(ajax_thumb.responseText);
        					}
        				}
        			}
        			ajax_thumb.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        			var x1 = parseInt($('#thumbnail-selector').attr('vars').split('_')[0]);
        			var y1 = parseInt($('#thumbnail-selector').attr('vars').split('_')[1]);
        			var x2 = parseInt($('#thumbnail-selector').attr('vars').split('_')[2]);
        			var y2 = parseInt($('#thumbnail-selector').attr('vars').split('_')[3]);
        			
        			var query_string = "orig_height=" + imgPreloader.height + "&orig_width=" + imgPreloader.width;
        			query_string += "&scaled_height=" + scaled_height + "&scaled_width=" + scaled_width;
        			query_string += "&x1=" + x1 + "&y1=" + y1 + "&x2=" + x2 + "&y2=" + y2;
        			query_string += "&filename=" + $('#thumbnail-selector').attr('src').split('?')[0];
        			ajax_thumb.send(query_string);
        		}
				
			}

			
        	
        	function showCoords(c){
        		$('#thumbnail-selector').attr('vars', c.x + '_' + c.y + '_' + c.x2 + '_' + c.y2);
        	}
        	
        	
        	
        	function edit_dialog(image_id){
        		/*$.ajax{
        			url: "image_attributes.php",
        			cache: false,
        			type: "POST",
        			data: {id_list: image_id},
        			success: function(data){
        				var image_array = $.parseJSON(data);
        			}
        		}*/
        		var ajax_obj = new XMLHttpRequest();
        		ajax_obj.open("POST", "image_attributes.php", true);
        		ajax_obj.onreadystatechange = function(){
        			if (ajax_obj.readyState == 4 && ajax_obj.status == 200){
        				var obj = jQuery.parseJSON(ajax_obj.responseText);
        				var cYear = parseInt(obj.date.split('-')[0]);
        				var cMonth = parseInt(obj.date.split('-')[1]);
        				var cDay = parseInt(obj.date.split('-')[2]);
        				var DaySelect = "<select class='custom' name='date-day-sel' id='date-day-sel' style='display:none'>";
        				for (var i = 1; i <= 31; i = i + 1){
        					DaySelect += "<option value='" + i + "'";
        					if (i == cDay) DaySelect += " selected='selected'";
        					DaySelect += ">";
        					if (i < 10) DaySelect += "0";
        					DaySelect += i;
        					DaySelect += "<\/option>";
        				}
        				DaySelect += "<\/select>";
        				
        				var MonthSelect = "<select class='custom' name='date-month-sel' id='date-month-sel' style='display:none'>";
        				for (var i = 1; i <= 12; i = i + 1){
        					MonthSelect += "<option value='" + i + "'";
        					if (i == cMonth) MonthSelect += " selected='selected'";
        					MonthSelect += ">";
        					if (i < 10) MonthSelect += "0";
        					MonthSelect += i;
        					MonthSelect += "<\/option>";
        				}
        				MonthSelect += "<\/select>";
        				
        				var YearSelect = "<select class='custom' name='date-year-sel' id='date-year-sel' style='display:none'>";
        				for (var i = 1990 ; i <= 2020; i = i + 1){
        					YearSelect += "<option value='" + i + "'";
        					if (i == cYear) YearSelect += " selected='selected'";
        					YearSelect += ">";
        					YearSelect += i;
        					YearSelect += "<\/option>";
        				}
        				YearSelect += "<\/select>";
        				
        				$('#project').attr('value', obj['project_name']);
        				$('#file-name').attr('value', obj['filename'].split('.')[0]);
        				$('#overlay').attr('file_ext', obj['filename'].split('.')[1]);
        				
        				$('#dk_container_date-day-sel').remove();
        				$('#date-day-sel').replaceWith(DaySelect);
        				$('#date-day-sel').dropkick();
        				$('#dk_container_date-day-sel .dk_options').css("width", "40px");
        				
        				$('#dk_container_date-month-sel').remove();
        				$('#date-month-sel').replaceWith(MonthSelect);
        				$('#date-month-sel').dropkick();
        				$('#dk_container_date-month-sel .dk_options').css("width", "40px");
        				
        				$('#dk_container_date-year-sel').remove();
        				$('#date-year-sel').replaceWith(YearSelect);
        				$('#date-year-sel').dropkick();
        				$('#dk_container_date-year-sel .dk_options').css("width", "56px");
        				
        				if (navigator.appName == "Microsoft Internet Explorer"){
        					$('#dk_container_date-day-sel').css('margin-right', '15px');
        					$('#dk_container_date-month-sel').css('margin-right', '15px');
        					$('#dk_container_date-day-sel .dk_options').css('right', '-15px');
        					$('#dk_container_date-month-sel .dk_options').css('right', '-15px');
        					$('#dk_container_date-year-sel .dk_options').css('right', '-30px');
        				}
        				
        				refresh_overlay_mediums(obj['medium_id'], obj['medium_disc_id'], 'single');
        				refresh_overlay_divisions(obj['division_id'], obj['division_disc_id'], 'single');
        				refresh_deliverables_list(obj['deliverable']);
        				refresh_keywords_list(obj['keywords']);
        				$('#overlay').attr('image_id', image_id);
        				$('#thumb_image_id').attr('value', $('#overlay').attr('image_id'));
        				$('#overlay').dialog('open');
        				$('.jcrop-holder').remove();
        				var file_extension = obj['filename'].split('.')[1];
        				var thumber_extension = extension_detect(obj['filename'].split('.')[0] + '_t_thumber.');
        				var thumber_filename = obj['filename'].split('.')[0] + '_t_thumber.' + thumber_extension;
        				var thumber_preloader = new Image();
        				thumber_preloader.src = 'projs/' + thumber_filename + '?modified=' + Math.floor(Math.random() * 10000);
        				thumber_preloader.onload = function(){
        					var original_width = thumber_preloader.width;
        					var original_height = thumber_preloader.height;
        					var thumber_width = thumber_preloader.width;
        					var thumber_height = thumber_preloader.height;
        					var maxwidth = 383;
        					var maxheight = 465;
        					if (thumber_width > maxwidth){
        						var old_width = thumber_width;
        						var old_height = thumber_height;
        						thumber_width = maxwidth;
        						thumber_height = parseInt((thumber_width * old_height) / old_width) + 1;
        					}
        					
        					if (thumber_height > maxheight){
        						var old_width = thumber_width;
        						var old_height = thumber_height;
        						thumber_height = maxheight;
        						thumber_width = parseInt((thumber_height * old_width) / old_height) + 1;
        					}
        					
        					var scale = thumber_height / original_height;
        					var minSelWidth = 0;
        					var minSelHeight = 0;
        					
        					if (file_extension == 'jpg' || file_extension == 'png' || file_extension == 'gif'){
        						minSelWidth = 222;
        						minSelHeight = 318;
        					} else {
        						minSelWidth = 468;
        						minSelHeight = 318;
        					}
        					
        					minSelWidth = parseInt(scale * minSelWidth) + 1;
        					minSelHeight = parseInt(scale * minSelHeight) + 1;
        					
	        				$('#thumbnail-selector').replaceWith('<img style="width:' + thumber_width + 'px; height:' + thumber_height + 'px;" src="projs/' + thumber_filename + '?modified=' + Math.floor(Math.random()*10000) + '" id="thumbnail-selector" \/>');
	        				$('#thumbnail-selector').Jcrop({
	        					onSelect: showCoords,
	        					aspectRatio: minSelWidth / minSelHeight,
	        					minSize: [minSelWidth, minSelHeight]
	        				});
	        				$('#thumbnail-selector').attr('vars', '0_0_0_0'); 
        				}
        			}
        		}
        		ajax_obj.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        		ajax_obj.send("id=" + image_id);
        	}
            
            
            
            
            function delete_images(id_list, image_type){
            	$.ajax({
            		url: 'delete_images.php',
            		cache: false,
            		type: 'POST',
            		data: {id_list: id_list},
            		success: function(data){
            			if (data != "success"){
            				alert("ERROR: Could not delete file(s)");
            			} else {
            				if (image_type == 0){
            					$('#queue-select-all').attr('src', 'images/checkbox-0.png');
            					update_queue();
            				} else {
            					$('#live-select-all').attr('src', 'images/checkbox-0.png');
            					filter_live_images();
            				}
            			}
            		}
            	});
            }
            
            function publish_images(id_list){
            	$.ajax({
            		url: 'publish_images.php',
            		cache: false,
            		type: 'POST',
            		data: {id_list: id_list},
            		success: function(data){
            			if (data != "success"){
            				alert("ERROR: Could not publish image(s)");
            			} else {
            				update_queue();
            				filter_live_images();
            				$('#queue-select-all').attr('src', 'images/checkbox-0.png');
            			}
            		}
            	});
            }
             
           
            function showRequest(formData, jqForm, options) {
                return true;
            }
            
            function fit_image(image_src, width, height){
            	$.ajax({
            		url: "fit_image.php",
            		cache: false,
            		type: "POST",
            		data: {image_src: image_src, width: width, height: height},
            		success: function(data){
            			if (data != 'success') alert(data);
            		}
            	});
            }
            
            function extension_detect(base_name){
            	var ajax_ext = new XMLHttpRequest();
            	ajax_ext.open("POST", "thumber_ext.php", false);
            	ajax_ext.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            	ajax_ext.send('base_name=' + base_name);
            	return ajax_ext.responseText;
            }
            
			
            function showResponse(responseText, statusText, xhr, $form)  {
                if (responseText != 'failed') {
                	var original_extension = responseText.split('.')[1];
                	var file_body = responseText.split('.')[0];
                	var file_extension = extension_detect(file_body.split('projs/')[1] + "_t_thumber.");
                	
                	var t_thumber = file_body + "_t_thumber." + file_extension;
                	var t_normal = file_body + "_t_normal." + file_extension;
                	var t_featured = file_body + "_t_featured." + file_extension;
                	var t_grid = file_body + "_t_grid." + file_extension;
                	var t_list = file_body + "_t_list." + file_extension;
                	
                	var img_Preloader = new Image();
                	img_Preloader.src = t_thumber;
                	img_Preloader.onload = function(){
                		var old_width = img_Preloader.width;
                		var old_height = img_Preloader.height;
                		
                		var list_height = 50;
                		var list_width = parseInt((list_height * old_width) / old_height);
                		if (list_width < 35) list_width = 35;
                		if (list_width > 200) list_width = 200;
                		
                		fit_image(t_list.split('?')[0], list_width, list_height);
                		
                		var grid_height = 110;
                		var grid_width = parseInt((grid_height * old_width) / old_height);
                		if (grid_width < 97) grid_width = 97;
                		if (grid_width > 400) grid_width = 400;
                		
                		fit_image(t_grid.split('?')[0], grid_width, grid_height);
                		
                		if (original_extension == 'jpg' || original_extension == 'png' || original_extension == 'gif'){
                			fit_image(t_featured.split('?')[0], 222, 318);
                			fit_image(t_normal.split('?')[0], 99, 147);
                		} else {
                			fit_image(t_featured.split('?')[0], 468, 318);
                			fit_image(t_normal.split('?')[0], 222, 147);
                		}
                		update_queue();
                	}
    				
                	
                } else {
                	alert('Failed upload!');
                }
            }
            
            function showResponse_IE(success){
            	if (success == 1) update_queue(); else alert('Failed upload!');
            }
            
            
            
            
           
            function showThumbRequest(formData, jqForm, options) {
                return true;
            }
            
            function showThumbResponse(responseText, statusText, xhr, $form)  {
            	if (responseText != 'failed'){
            		
            		var original_extension = responseText.split('.')[1];
                	var file_body = responseText.split('.')[0];
                	var file_extension = extension_detect(file_body.split('projs/')[1] + "_t_thumber.");
                	
                	var t_thumber = file_body + "_t_thumber." + file_extension;
                	var t_normal = file_body + "_t_normal." + file_extension;
                	var t_featured = file_body + "_t_featured." + file_extension;
                	var t_grid = file_body + "_t_grid." + file_extension;
                	var t_list = file_body + "_t_list." + file_extension;
                	
                	var img_Preloader = new Image();
                	img_Preloader.src = t_thumber + '?modified=' + Math.floor(Math.random() * 10000);
                	img_Preloader.onload = function(){
                		
                		var old_width = img_Preloader.width;
                		var old_height = img_Preloader.height;
                		
                		var list_height = 50;
                		var list_width = parseInt((list_height * old_width) / old_height);
                		if (list_width < 35) list_width = 35;
                		if (list_width > 200) list_width = 200;
                		
                		fit_image(t_list.split('?')[0], list_width, list_height);
                		
                		var grid_height = 110;
                		var grid_width = parseInt((grid_height * old_width) / old_height);
                		if (grid_width < 97) grid_width = 97;
                		if (grid_width > 400) grid_width = 400;
                		
                		fit_image(t_grid.split('?')[0], grid_width, grid_height);
                		
                		if (original_extension == 'jpg' || original_extension == 'png' || original_extension == 'gif'){
                			fit_image(t_featured.split('?')[0], 222, 318);
                			fit_image(t_normal.split('?')[0], 99, 147);
                		} else {
                			fit_image(t_featured.split('?')[0],468, 318);
                			fit_image(t_normal.split('?')[0], 222, 147);
                		}
                		update_queue();
                		
                		
                		
                		var n_original_width = img_Preloader.width;
        				var n_original_height = img_Preloader.height;
        				var n_thumber_width = img_Preloader.width;
        				var n_thumber_height = img_Preloader.height;
        				var n_maxwidth = 383;
        				var n_maxheight = 465;
        				if (n_thumber_width > n_maxwidth){
        					var n_old_width = n_thumber_width;
        					var n_old_height = n_thumber_height;
        					n_thumber_width = n_maxwidth;
        					n_thumber_height = parseInt((n_thumber_width * n_old_height) / n_old_width) + 1;
        				}
        				if (n_thumber_height > n_maxheight){
        					var n_old_width = n_thumber_width;
        					var n_old_height = n_thumber_height;
        					n_thumber_height = n_maxheight;
        					n_thumber_width = parseInt((n_thumber_height * n_old_width) / n_old_height) + 1;
        				}
        				
        				var n_scale = n_thumber_height / n_original_height;
        				var n_minSelWidth = 0;
        				var n_minSelHeight = 0;
        				
        				if (original_extension == 'jpg' || original_extension == 'png' || original_extension == 'gif'){
        					n_minSelWidth = 222;
        					n_minSelHeight = 318;
        				} else {
        					n_minSelWidth = 468;
        					n_minSelHeight = 318;
        				}
       					
       					n_minSelWidth = parseInt(n_scale * n_minSelWidth) + 1;
       					n_minSelHeight = parseInt(n_scale * n_minSelHeight) + 1;
       					
                		$('.jcrop-holder').remove();
                		var new_src = 'projs/' + img_Preloader.src.split('projs/')[1];
                		$('#thumbnail-selector').replaceWith('<img style="width:' + n_thumber_width + 'px; height:'+ n_thumber_height +'px;" src="'+ new_src + '?modified=' + Math.floor(Math.random()*10000) + '" id="thumbnail-selector" \/>');

                		$('#thumbnail-selector').Jcrop({
	        					onSelect: showCoords,
	        					aspectRatio: n_minSelWidth / n_minSelHeight,
	        					minSize: [n_minSelWidth, n_minSelHeight]
	        				});
	        			$('#thumbnail-selector').attr('vars', '0_0_0_0');
                	}
            	} else {
            		alert(responseText);
            	}
			}
			
			
			
			
		
			
			$(document).ready(function(){
				
				// View Toggle
				$('#live-images-wrapper').hide();
				$('#grid-block-image').hide();
                $('#live-grid').hide();

                $('#grid-btn').click(function() {
                	if (!$(this).hasClass('selected')){
                		$('#grid-block-image .select-toggle').attr('src', 'images/checkbox-0.png');
                    	$('#queue-select-all').attr('src', 'images/checkbox-0.png');
                    	$(this).parent().find('a').removeClass('selected');
                    	$(this).addClass('selected');
                    	$('#grid-block-image').fadeIn('slow');
                    	$("#list-block").hide();
                  	}
                });
            	
            	$('#live-grid-btn').click(function(){
            		if (!$(this).hasClass('selected')){
            			$('#live-grid .select-toggle').attr('src', 'images/checkbox-0.png');
            			$('#live-select-all').attr('src', 'images/checkbox-0.png');
            			$(this).parent().find('a').removeClass('selected');
            			$(this).addClass('selected');
            			$('#live-grid').fadeIn('slow');
            			$('#live-list').hide();
            		}
            	});

            	$('#list-btn').click(function() {
                	if (!$(this).hasClass('selected')){
                		$('#list-block .select-toggle').attr('src', 'images/checkbox-0.png');
                    	$('#queue-select-all').attr('src', 'images/checkbox-0.png');
                    	$(this).parent().find('a').removeClass('selected');
                    	$(this).addClass('selected');
                    	$('#grid-block-image').hide();
                    	$("#list-block").fadeIn('slow');
                  	}
                });
            	
            	$('#live-list-btn').click(function(){
            		if (!$(this).hasClass('selected')){
            			$('#live-list .select-toggle').attr('src', 'images/checkbox-0.png');
            			$('#live-select-all').attr('src', 'images/checkbox-0.png');
            			$(this).parent().find('a').removeClass('selected');
            			$(this).addClass('selected');
            			$('#live-grid').hide();
            			$('#live-list').fadeIn('slow');
            		}
            	});
				
				// Overlay Init
				$( "#overlay" ).dialog({
                	autoOpen: false,
                    width: "970",
                    draggable: false,
                    resizable: false,
                    position: "top",
                    modal: true,
                    buttons: {
                        Ok: function() {
                            $( this ).dialog( "close" );
                        }
                    }

                });
                
                $('#edit-multiple-dialog').dialog({
                	autoOpen: false,
                	width: "600",
                	height: "600",
                	draggable: false,
                	resizable: false,
                	position: "top",
                	modal: true,
                	buttons: {
                		Ok: function(){
                			$(this).dialog('close');
                		}
                	}
                });
                
                $('#edit-kd').dialog({
                	autoOpen: false,
                	width: "200",
                	height: "500",
                	draggable: false,
                	resizable: false,
                	position: "top",
                	modal: true,
                	buttons: {
                		Ok: function(){
                			$(this).dialog('close');
                		}
                	}
                });
                
                get_keywords_list();
            	get_deliverables_list();
            	
            	// Upload Form Events            		
           		$('#upload-img-form').css('left', '-10000px');
           		$('#addimage').click( function() {
                   	$('#upload').click();
                   	return false;
	       		});
           		$('#upload').change(function(){
          			var options = {
                   		beforeSubmit: showRequest,
                   		success: showResponse
               		};
               		$('#upload_form').ajaxSubmit(options);
               	});
               	
               	$('#new_thumb_form').css('position', 'absolute');
            	$('#new_thumb_form').css('left', '-10000px');
            	$('#thumb_form_mask').click(function(){
            		$('#thumb_file').click();
            		return false;
            	});
            	$('#thumb_file').change(function(){
            		var options = {
            			beforeSubmit: showThumbRequest,
            			success: showThumbResponse
            		};
            		$('#new_thumb_form').ajaxSubmit(options);
            	});
            		
            	// Select All/None Events
            	$('#queue-select-all').click(function(){
            		var checkbox_type = 1 - parseInt($(this).attr('src').split('checkbox-')[1].split('.')[0]);
            		$(this).attr('src', 'images/checkbox-' + checkbox_type + '.png');
					if ($('#list-btn').hasClass('selected')){
            			$('#list-block .select-toggle').attr('src', 'images/checkbox-' + checkbox_type + '.png');
            		}
            		if ($('#grid-btn').hasClass('selected')){
            			$('#grid-block-image .select-toggle').attr('src', 'images/checkbox-' + checkbox_type + '.png');
            		}
            	});
            	
            	$('#live-select-all').click(function(){
            		var checkbox_type = 1 - parseInt($(this).attr('src').split('checkbox-')[1].split('.')[0]);
            		$(this).attr('src', 'images/checkbox-' + checkbox_type + '.png');
            		if ($('#live-list-btn').hasClass('selected')){
            			$('#live-list .select-toggle').attr('src', 'images/checkbox-' + checkbox_type + '.png');
            		} else {
            			$('#live-grid .select-toggle').attr('src', 'images/checkbox-' + checkbox_type + '.png');
            		}
            	})
            	
            	// Delete Multiple
            	
            	$('#queue-delete-multiple').click(function(){
            		if ($('#list-btn').hasClass('selected')){
            			var id_list = "";
            			$('#list-block .select-toggle').each(function(){
            				var current_id = $(this).parents('.list-image-item:first').attr('image_id');
            				var select_type = $(this).attr('src').split('checkbox-')[1].split('.png')[0];
            				if (select_type == '1'){
            					if (id_list != "") id_list += "_";
            					id_list += current_id;
            				}
            			});
            			if (id_list != "") delete_images(id_list, 0);
            		} else {
            			var id_list = "";
            			$('#grid-block-image .select-toggle').each(function(){
            				var current_id = $(this).parents('.grid-image-item:first').attr('image_id');
            				var select_type = $(this).attr('src').split('checkbox-')[1].split('.png')[0];
            				if (select_type == '1'){
            					if (id_list != "") id_list += "_";
            					id_list += current_id;
            				}
            			});
            			if (id_list != "") delete_images(id_list, 0);
            		}
            	});
            	
            	$('#live-delete-multiple').click(function(){
            		if ($('#live-list-btn').hasClass('selected')){
            			var id_list = "";
            			$('#live-list .select-toggle').each(function(){
            				var current_id = $(this).parents('.list-image-item:first').attr('image_id');
            				var select_type = $(this).attr('src').split('checkbox-')[1].split('.png')[0];
            				if (select_type == '1'){
            					if (id_list != "") id_list += "_";
            					id_list += current_id;
            				}
            			});
            			if (id_list != "") delete_images(id_list, 1);
            		} else {
            			var id_list = "";
            			$('#live-grid .select-toggle').each(function(){
            				var current_id = $(this).parents('.grid-image-item:first').attr('image_id');
            				var select_type = $(this).attr('src').split('checkbox-')[1].split('.png')[0];
            				if (select_type == '1'){
            					if (id_list != "") id_list += "_";
            					id_list += current_id;
            				}
            			});
            			if (id_list != "") delete_images(id_list, 1);
            		}
            	});
            	
            	// Publish multiple
            	
            	$('#queue-publish-multiple').click(function(){
            		if ($('#list-btn').hasClass('selected')){
            			var id_list = "";
            			$('#list-block .select-toggle').each(function(){
            				var current_id = $(this).parents('.list-image-item:first').attr('image_id');
            				var checkbox_type = parseInt($(this).attr('src').split('checkbox-')[1].split('.')[0]);
            				if (checkbox_type == 1){
            					if (id_list != "") id_list += "_";
            					id_list += current_id; 
            				}
            			});
            			if (id_list != "") publish_images(id_list);
            		} else {
            			var id_list = "";
            			$('#grid-block-image .select-toggle').each(function(){
            				var current_id = $(this).parents('.grid-image-item:first').attr('image_id');
            				var checkbox_type = parseInt($(this).attr('src').split('checkbox-')[1].split('.')[0]);
            				if (checkbox_type == 1){
            					if (id_list != "") id_list += "_";
            					id_list += current_id;
            				}
            			});
            			if (id_list != "") publish_images(id_list);
            		}
            	});
            	
            	// Edit multiple
            	
            	$('#live-edit-multiple').click(function(){
            		if ($('#live-list-btn').hasClass('selected')){
            			var id_list = "";
            			$('#live-list .select-toggle').each(function(){
            				var current_id = $(this).parents('.list-image-item:first').attr('image_id');
            				var select_type = $(this).attr('src').split('checkbox-')[1].split('.png')[0];
            				if (select_type == '1'){
            					if (id_list != "") id_list += "_";
            					id_list += current_id;
            				}
            			});
            			if (id_list != "") edit_multiple_dialog(id_list);
            		} else {
            			var id_list = "";
            			$('#live-grid .select-toggle').each(function(){
            				var current_id = $(this).parents('.grid-image-item:first').attr('image_id');
            				var select_type = $(this).attr('src').split('checkbox-')[1].split('.png')[0];
            				if (select_type == '1'){
            					if (id_list != "") id_list += "_";
            					id_list += current_id;
            				}
            			});
            			if (id_list != "") edit_multiple_dialog(id_list);
            		}
            	});
            	
            	$('#queue-edit-multiple').click(function(){
            		if ($('#list-btn').hasClass('selected')){
            			var id_list = "";
            			$('#list-block .select-toggle').each(function(){
            				var current_id = $(this).parents('.list-image-item:first').attr('image_id');
            				var checkbox_type = parseInt($(this).attr('src').split('checkbox-')[1].split('.')[0]);
            				if (checkbox_type == 1){
            					if (id_list != "") id_list += "_";
            					id_list += current_id; 
            				}
            			});
            			if (id_list != "") edit_multiple_dialog(id_list);
            		} else {
            			var id_list = "";
            			$('#grid-block-image .select-toggle').each(function(){
            				var current_id = $(this).parents('.grid-image-item:first').attr('image_id');
            				var checkbox_type = parseInt($(this).attr('src').split('checkbox-')[1].split('.')[0]);
            				if (checkbox_type == 1){
            					if (id_list != "") id_list += "_";
            					id_list += current_id;
            				}
            			});
            			if (id_list != "") edit_multiple_dialog(id_list);
            		}
            	});
            });
            
            function refresh_kd_edit(kd_type){
            	$('#edit-kd').attr('kd_type', kd_type);
            	$.ajax({
            		url: "get_kd_content.php",
            		cache: false,
            		type: "POST",
            		data: {type: kd_type},
            		success: function (data){
            			if (data != 'failed'){
            				$('#kd-wrapper ul').html(data);
            				$('.delete-kd-button').click(function(){
            					var ays = confirm("Are you sure?");
            					if (ays == true){
            						var kd_id = $(this).parents('li').attr('kd_id');
            						var kd_type = $('#edit-kd').attr('kd_type');
            						$.ajax({
            							url: "delete_kd.php",
            							cache: false,
            							type: "POST",
            							data: {type: kd_type, id: kd_id},
            							success: function(data){
            								if (data == 'success'){
            									refresh_kd_edit(kd_type);
            								}
            							}
            						});
            					}
            				});
            				if (kd_type == 'keywords'){
            					get_keywords_list();
            				} else {
            					get_deliverables_list();
            				}
            			}
            		}
            	});
            }
            
            function edit_kd_list(kd_type){
            	$('#edit-kd').dialog('open');
            	$('#overlay').dialog('close');
            	$('#edit-multiple-dialog').dialog('close');
            	refresh_kd_edit(kd_type);
            }
			
			$(document).ready(function(){
				$('#add-kd-button').click(function(){
           			var new_name = $('#add-kd').val();
            		var kd_type = $('#edit-kd').attr('kd_type');
           			$.ajax({
            			url: "add_kd.php",
            			cache: false,
            			type: "POST",
            			data: {type: kd_type, new_name: new_name},
            			success: function(data){
            				if (data == 'success'){
            					refresh_kd_edit(kd_type);
            				} else {
            					alert(data);
            				}
            			}
        			});
          		});
          		$('#done-kd').click(function(){
          			$('#add-kd').val('');
          			$('#edit-kd').dialog('close');
          		});
				
				$('.dropdown-nav').megamenu();
				
				$.ajax({
					url: "get_filter_dropdown.php",
					cache: false,
					data: {},
					success: function(data){
						$('#live-filter').html(data);
						$('#live-filter img').click(function(){
							var checkbox_type = 1 - parseInt($(this).attr('src').split('checkbox-')[1].split('.')[0]);
							$(this).attr('src', 'images/checkbox-' + checkbox_type + '.png');
							
							if ($('#live-images-wrapper').attr('f_active') == 'disabled'){
								$('#apply-filters-poster').hide();
								$('#live-images-wrapper').fadeIn('slow');
							}
							$('#live-images-wrapper').attr('f_active', 'filter');
							
							
							filter_live_images();
						})
					}
				});
			});
			
        </script>
    </head>
    <body onload="update_queue(); ">
        <div id="container">
            <header id="header">
                <h1 id="logo">
                    <a href="#">the refinery</a>
                </h1>
                <section id="top-bar">
                    <nav>
                        <ul>
                            <li>
                                <a href="http://therefinerycreative.com/pipeline/">pipeline</a>
                            </li>
                            <li>
                                <a class="active" href="#">portfolio admin</a>
                            </li>
                            <li>
                                <a href="javascript: void(0)">client site admin</a>
                            </li>
                            <li>
                                <a href="http://mail.therefinerycreative.com/" target="_blank">email</a>
                            </li>
                            <li>
                                <a href="javascript: void(0)">ftp</a>
                            </li>
                            <li>
                                <a href="logout.php">logout</a>
                            </li>
                        </ul>
                    </nav><!-- main-navigation -->
                </section><!-- end top-bar -->
            </header><!-- end header -->
            <section id="main">
                <section id="image-queue" class="main-block">
                    <header>
                        <h1>image queue</h1>
                        <a class="add-image" id="addimage" href="#">add an image to the queue</a>
                        <!-- really tweaked. don't touch. -->
                        <div id="upload-img-form">
                            <form id="upload_form" enctype="multipart/form-data" method="POST" action="upload_image.php">
                                <input type="file" id="upload" name="uploaded" />
                                <input type="submit" id="submit_button" value="Upload"/>
                            </form>	
                        </div><!-- end upload-form -->
                    </header>
                    <nav>
                        <ul class="left-nav">
                            <li>
                            	<img id='queue-select-all' src='images/checkbox-0.png' style="border: 1px solid black;"/>
                                <!-- <input type="checkbox" name="select_all" /> -->
								select all/none
                            </li>
                            <li>
                                <a id="queue-edit-multiple" href="javascript: void(0)">edit selected</a>
                            </li>
                            <li>
                                <a id="queue-delete-multiple" href="javascript: void(0)">delete selected</a>
                            </li>
                            <li>
                                <a id="queue-publish-multiple" href="javascript: void(0)">publish selected</a>
                            </li>
                        </ul>
                        <ul class="right-nav">
                            <li class="last">
                                <strong>views:</strong>
                                <a id="grid-btn" class="grid-view" href="javascript: void(0)">grid</a>
                                <a id="list-btn" class="list-view selected" href="javascript: void(0)">list</a>
                            </li>
                        </ul>
                        <div class="clear">
                        </div>
                    </nav>
                    <section class="view-container">
                        <ul class="grid-block" id="grid-block-image">
                        </ul>

                        <section id="list-block">
                            <table>
                                <thead>
                                <th></th>
                                <th></th>
                                <th>project title</th>
                                <th>file name</th>
                                <th>date added</th>
                                <th>medium</th>
                                <th>division</th>
                                <th>deliverable</th>
                                <th>keywords</th>
                                <th></th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>

                        </section><!-- end list-block -->
                    </section><!-- end view-container -->
                </section><!-- end main-block -->

                <section id="live-images" class="main-block">
                    <header>
                        <h1>live images</h1>
                    </header>
                    <nav>
                        <ul class="left-nav">
                            <li>
                                <img id='live-select-all' src="images/checkbox-0.png" style="border: 1px solid black"/>
								select all/none
                            </li>
                            <li>
                                <a id="live-edit-multiple" href="#">edit selected</a>
                            </li>
                            <li>
                                <a id="live-delete-multiple" href="javascript: void(0)">delete selected</a>
                            </li>
                        </ul>
                        <ul class="right-nav">

                            <li class="last">
                                <strong>views:</strong>
                                <a id="live-grid-btn" class="grid-view" href="javascript: void(0)">grid</a>
                                <a id="live-list-btn" class="list-view selected" href="javascript: void(0)">list</a>
                            </li>
                        </ul>
                        <ul class="right-nav dropdown-nav">
                            <li style="width:90px">
                                <a href="javascript: void(0)">
								search
                                    <span class="dropdown"></span>
                                </a>
                                <div class="search-dropdown">
                                    <span>
                                        <a class="reset-input" href="#">reset input</a>
                                        <input id="search-query-string" type="text" name="search" value="" />
                                    </span>
                                    <input type="submit" value="search" onclick="search_live_images();"/>

                                </div>
                            </li>
                            <li style="width: 94px">
                                <a  href="javascript: void(0)">
								filter by
                                    <span class="dropdown"></span>
                                </a>
                                <div id="live-filter" class="filter-dropdown">
                                	
                                </div>
                            </li>
                            <li>
                                <a href="#">
								sort by
                                    <span class="dropdown"></span>
                                </a>
                                <div id="live-sort" class="filter-dropdown">
                                	<ul>
                                		<li class='selected' order="images.name"><span>a - z</span></li>
                                		<li order="images.name DESC"><span>z - a</span></li>
                                		<li order="images.date DESC, images.id DESC"><span>date</span></li>
                                	</ul>
                                </div>
                            </li>
                      	</ul>
                      	
                        <div class="clear">
                        </div>
                    </nav>
                    <section class="view-container">
                    	<span id="apply-filters-poster" class="apply-filters"></span>
                    	<section id="live-images-wrapper" f_active="disabled">
	                    	<ul id="live-grid" class="grid-block">
	                    	</ul>
	                    	
	                        <section id="live-list">
	                            <table>
	                                <thead>
	                                <th></th>
	                                <th></th>
	                                <th>project title</th>
	                                <th>file name</th>
	                                <th>date added</th>
	                                <th>medium</th>
	                                <th>division</th>
	                                <th>deliverable</th>
	                                <th>keywords</th>
	                                <th></th>
	                                </thead>
	                                <tbody>
	                                </tbody>
	                            </table>
	                    	</section><!-- end view-container -->
	                    </section>
                	</section><!-- end main-block -->
            </section><!-- end main -->
            <div id="edit-kd">
            	<div id="kd-wrapper">
					<ul>
						
					</ul>
				</div>
				<div id="add-kd-wrapper">
					<input type="text" id="add-kd" />
					<span id="add-kd-button"><span>add</span></span>
				</div>
				<span id="done-kd"><span>done</span></span>
			</div>
			<div id="edit-multiple-dialog">
				<header>
					<h2>edit images</h2>
				</header>
				
				<section id="edit-multiple-details">
					<table>
						<tr>
							
								<td>
									<div class="edit-multiple-row" style="margin-left:2px">
										<img id="project-confirm" src="images/checkbox-0.png"  style='float:left; margin-top: 4px; margin-right:5px'/>
										<label>
											<strong>project:</strong>
										</label>
										<div class="multiple-text-input">
											<input id="m-project-sel" type="text" />
											<a href="javascript: void(0)">close</a>
										</div>
									</div>
								</td>
							
						</tr>
						<tr>
							<td>
								<div class="edit-multiple-row" style="margin-left:2px">
									<img id="date-confirm" src="images/checkbox-0.png"  style='float:left; margin-top: 4px; margin-right:5px'/>
									<label>
										<strong>date:</strong>
									</label>
									<div id="m-date-wrapper" class="m-date-wrapper">
										<select id="m-date-day-sel" class="to_dk">
											
										</select>
										<span class="date-sep"></span> 
										<select id="m-date-month-sel" class="to_dk">
											
										</select>
										<span class="date-sep"></span>
										<select id="m-date-year-sel" class="to_dk">
											
										</select>
									</div>
								</div>
							</td>
						</tr>
						<tr>
							
							<td>
								<div class='edit-multiple-row'>
									<table border="0">
										<tr>
											<td>
												<img id="medisc-didisc-confirm" src='images/checkbox-0.png' style="float:left;position: relative; bottom: 10px; margin-right:1px" />
											</td>
												
											<td>
												<div class='category-discipline-wrapper'>
													
													<table>
														<tr>
															<td>
																<label>
																	<strong>medium:</strong>
																</label>
																<select id="m-medium-sel" class="to_dk">
																	<option>home entertainment</option>
																	<option>broadcast</option>
																</select>
															</td>
														</tr>
														<tr>
															<td>
																<label id="m-label-medisc">
																	<strong>sub-section:</strong>
																</label>
																<select id="m-med-disc-sel" class="to_dk">
																	<option>home entertainment</option>
																	<option>theatrical</option>
																</select>
															</td>
														</tr>
													</table>
												</div>
											</td>
											<td>
												<div class='category-discipline-wrapper'>
													<table>
														<tr>
															<td>
																<label>
																	<strong>division:</strong>
																</label>
																<select id="m-division-sel" class="to_dk">
																	<option>home entertainment</option>
																	<option>broadcast</option>
																</select>
															</td>
														</tr>
														<tr>
															<td>
																
																<select id="m-div-disc-sel" class="to_dk">
																	<option>home entertainment</option>
																	<option>theatrical</option>
																</select>
															</td>
														</tr>
													</table>
												</div>
											</td>
										</tr>
									</table>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="edit-multiple-row">
									<table>
										<tr>
											<td>
												<img id="deliverables-confirm" src='images/checkbox-0.png' style="float:left;margin-right:5px" />
												<strong class="label-title">
													deliverable:
												</strong>
			                                    <div class="list-container">
			                                   		<div class="list-block">
			                                        	<ul id="m-deliverables-list">
			                                        		
			                                          	</ul>
			                                  		</div>
			                                       	<a class="edit-list" href="javascript: void(0)" onclick="edit_kd_list('deliverables')">edit list..</a>
			                                   	</div>
			                              	</td>
			                              	<td><div style="margin-left: 40px">
			                              		<img id="keywords-confirm" src='images/checkbox-0.png' style="float:left;margin-right:5px" />
												<strong class="label-title">
													keywords:
												</strong>
			                                    <div class="list-container">
			                                   		<div class="list-block">
			                                        	<ul id="m-keywords-list">
			                                        		
			                                          	</ul>
			                                  		</div>
			                                       	<a class="edit-list" href="javascript: void(0)" onclick="edit_kd_list('keywords')">edit list..</a>
			                                   	</div></div>
			                              	</td>
			                            </tr>
			                              
								</div>
							</td>
						</tr>
					</table>
					<div class="edit-buttons">
                                <a href="javascript: void(0)" onclick="save_multiple($('#edit-multiple-dialog').attr('id_list'));">save</a>
                                <a href="javascript: void(0)" onclick="$('#edit-multiple-dialog').dialog('close');">cancel</a>
                            </div>
				</section>
			</div>
            <div id="overlay">
                <div id="overlay-content">
                    <header>
                        <h2>edit image</h2>
                    </header>
                    <ul class="heading">
                        <li>
                            <strong>information</strong>
                        </li>
                        <li>
                            <strong>thumbnail</strong>
                        </li>
                        <li class="load-thumbnail">
                            <a id="thumb_form_mask" href="#">load new thumbnail...</a>
                            <form enctype="multipart/form-data" action="new_thumb.php" id="new_thumb_form">
                            	<input id="thumb_image_id" type="hidden" name="image_id" value="" />
                            	<input id="thumb_file" type="file" name="uploaded_file" />
                            </form>
                        </li>
                    </ul>
                    <section class="image-details">
                        <form>
                            <div class="details-wrapper">
                                <section class="details-content">

                                    <div class="form-row">
                                        <label for="project">
										project:
                                        </label>
                                        <div class="input-container">
                                            <input name="project" id="project" type="text" value="mad men" />
                                            <a class="clear-input" onclick="$('#project').attr('value', '');" href="javascript: void(0)">close</a>
                                        </div>
                                        <div class="clear">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <label for="file-name">
										file name:
                                        </label>
                                        <div class="input-container">
                                            <input name="file_name" id="file-name" type="text" value="(optional)" />
                                            <a class="clear-input" onclick="$('#file-name').attr('value', '');" href="javascript: void(0)">close</a>
                                        </div>
                                        <div class="clear">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <label for="date">
										date added:
                                        </label>
                                        <div id="date-wrapper" class="date-wrapper">
                                            <select id='date-day-sel' class="custom" name="select_day">
                                                
                                            </select>
                                            <span class="sep"></span>
                                            <select id='date-month-sel' class="custom" name="select_month">
                                                
                                            </select>
                                            <span class="sep"></span>
                                            <select id='date-year-sel' class="custom" name="select_year">
                                                
                                            </select>
                                        </div>
                                        <div class="clear">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="float-left">
                                            <label for="medium">
											medium:
                                            </label>
                                            <div class="select-medium">
                                                <select class="custom" name="medium-sel" id="medium-sel">
                                                    
                                                </select>
                                            </div>
                                        </div><!-- end float-left -->

                                        <div class="float-right right-margin">
                                            <label for="division">
											division:
                                            </label>
                                            <div class="select-division">
                                                <select name="division-sel" class="custom" id="division-sel">
                                                    
                                                </select>
                                            </div>
                                        </div><!-- end float-right -->
                                        <div class="clear">
                                        </div>
                                    </div> <!-- end form-row -->

                                    <div class="form-row">
                                        <div class="float-left">
                                            <label for="discipline-medium" id='label-medisc'>
											sub-section:
                                            </label>
                                            <div class="select-discipline-medium">
                                                <select class="custom" name="med-disc-sel" id="med-disc-sel">
                                                    
                                                </select>
                                            </div>
                                        </div><!-- end float-left -->

                                        <div class="float-right right-margin">
                                            
                                            <div class="select-discipline-division">
                                                <select id="div-disc-sel" name="div-disc-sel" class="custom">
                                                    
                                                </select>
                                            </div>
                                        </div><!-- end float-right -->
                                        <div class="clear">
                                        </div>
                                    </div> <!-- end form-row -->

                                    <div class="form-row last-row">
                                        <div class="float-left">
                                            <strong class="label-title">deliverable:</strong>
                                            <div class="list-container">
                                                <div class="list-block">
                                                    <ul id="deliverables-list">
                                                    </ul>
                                                </div>
                                                <a class="edit-list" href="javascript: void(0)" onclick="edit_kd_list('deliverables')">edit list..</a>
                                            </div>
                                        </div><!-- end float-left -->

                                        <div class="float-left margin-keywords">
                                            <strong class="label-title">keywords:</strong>
                                            <div class="list-container">
                                                <div class="list-block">
                                                    <ul id="keywords-list">
                                                    </ul>
                                                </div>
                                                <a class="edit-list" href="javascript: void(0)" onclick="edit_kd_list('keywords')">edit list..</a>
                                            </div>
                                        </div><!-- end float-left -->


                                    </div> <!-- end form-row -->
                                </section><!-- details-content -->
                                <section class="thumbnail-wrapper" style="margin:auto">
                                    <img id="thumbnail-selector" src="images/madmen.jpg" />
                                </section>
                            </div><!-- end details-wrapper -->
                            <div class="edit-buttons">
                                <a href="javascript: void(0)" onclick="save_image_details();">save</a>
                                <a href="javascript: void(0)" onclick="$('#overlay').dialog('close');">cancel</a>
                            </div><!-- end edit-buttons -->
                        </form>
                    </section>
                </div><!-- end overlay-content -->
            </div><!-- end overlay -->
        </div><!-- end container -->
    </body>
</html>
