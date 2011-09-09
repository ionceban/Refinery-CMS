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
        <link href="uploadify/uploadify.css" type="text/css" rel="stylesheet" />
        <script type="text/javascript" src="js/jquery/jquery.js"></script>
		<script type="text/javascript" src="uploadify/swfobject.js"></script>
		<script type="text/javascript" src="uploadify/jquery.uploadify.v2.1.4.min.js"></script>
        <script type="text/javascript" src="js/jquery/jquery-ui.js"></script>
        <script type="text/javascript" src="js/jquery/jquery.dropkick.js" charset="utf-8"></script>
        <script type="text/javascript" src="js/jquery/jquery.megamenu.js"></script>
        <script type="text/javascript" src="js/jquery/jquery.jcrop.js"></script>
        <script type="text/javascript" src="js/jquery/jquery.form.js"></script>
        <script type="text/javascript" src="js/overlay.js"></script>
        <script type="text/javascript" src="js/results.js"></script>
		<script type="text/javascript" src="js/uploader.js"></script>
		<script type="text/javascript" src="js/images.js"></script>
		<script type="text/javascript" src="js/jcrop-interactions.js"></script>
		<script type="text/javascript" src="js/application.js"></script>
		<script type="text/javascript">
			var FIT_COUNTER = 0;
			var NECESSARY_FITS = 0;
			
			var SAVE_COUNTER = 0;
			var NECESSARY_SAVES = 0;
        	
        	
			function edit_dialog(image_id){
				CachedDialogType = 1;
				CachedDialogParams = image_id;

				ImagesToSave[0] = 1;
				ImagesToSave[1] = image_id;

        		var ajax_obj = new XMLHttpRequest();
        		ajax_obj.open("POST", "image_attributes.php", true);
        		ajax_obj.onreadystatechange = function(){
        			if (ajax_obj.readyState == 4 && ajax_obj.status == 200){
        				var obj = jQuery.parseJSON(ajax_obj.responseText);
        				
						var cYear = obj.date.split('-')[0];
						var cMonth = obj.date.split('-')[1];
						var cDay = obj.date.split('-')[2];

						refresh_overlay_date(cDay, cMonth, cYear, 'single');

        				$('#project').attr('value', obj['project_name']);
        				$('#file-name').attr('value', obj['filename'].split('.')[0]);
        				
        				refresh_overlay_mediums(obj['medium_id'], obj['medium_disc_id'], 'single');
        				refresh_overlay_divisions(obj['division_id'], obj['division_disc_id'], 'single');
        				refresh_deliverables_list(obj['deliverable'], 'single');
        				refresh_keywords_list(obj['keywords'], 'single');
						
						$('#overlay').attr('image_id', image_id);
        				$('#thumb_image_id').attr('value', $('#overlay').attr('image_id'));
						$('#overlay').dialog('open');
						jcrop_init(image_id, 0);
						
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
							update_queue();
							filter_live_images();
							$('#queue-select-all').attr('src', 'images/checkbox-0.png');
            				alert(data);
            			} else {
            				update_queue();
            				filter_live_images();
            				$('#queue-select-all').attr('src', 'images/checkbox-0.png');
            			}
            		}
            	});
            }
             
            
            function fit_image(image_src, width, height){
            	$.ajax({
            		url: "fit_image.php",
            		cache: false,
            		type: "POST",
            		data: {image_src: image_src, width: width, height: height},
            		success: function(data){
            			if (data != 'success'){
            				alert(data);
            			}
            			++FIT_COUNTER;
            			if (FIT_COUNTER == NECESSARY_FITS){
            				update_queue();
            			}
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
					dialogClass: "main-edit-dialog",
                	autoOpen: false,
                    width: "970",
                    draggable: false,
                    resizable: false,
                    position: "top",
                    modal: true,
                    zIndex: 3010,
                    buttons: {
                        Ok: function() {
							$( this ).dialog( "close" );
							CachedDialogType = 0;
                        }
                    }

				});

				$('.ui-dialog.main-edit-dialog').css('position', 'fixed');
                
                $('#edit-multiple-dialog').dialog({
                	autoOpen: false,
                	width: "600",
                	height: "600",
                	draggable: false,
                	resizable: false,
                	position: "top",
                	modal: true,
                	zIndex: 3010,
                	buttons: {
                		Ok: function(){
							$(this).dialog('close');
							CachedDialogType = 0;
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
                	zIndex: 3010,
                	buttons: {
                		Ok: function(){
							$(this).dialog('close');
							refresh_cached_dialog();
                		}
                	}
				});

				$('#edit-single-kd').dialog({
					autoOpen: false,
					width: "220",
					height: "80",
					draggable: false,
					resizable: false,
					position: "top",
					modal: true,
					zIndex: 3020
				});

				$('#save-edited-kd').click(function(){
					$.ajax({
						url: "php/save_kd_name.php",
						cache: false,
						type: "POST",
						data: {
							kd_type: $('#edit-single-kd').attr('kd_type'),
							kd_id: $('#edit-single-kd').attr('kd_id'),
							new_value: $('#edit-kd-input').val()
						}, success: function(data){
							if (data == 'failed'){
								alert('ERROR');
							} else {
								$('#edit-single-kd').dialog('close');
								$('#edit-kd-input').val('');
								refresh_kd_edit($('#edit-single-kd').attr('kd_type'));
							}
						}
					});
				});

				$('#cancel-edited-kd').click(function(){
					$('#edit-single-kd').dialog('close');
					$('#edit-kd-input').val('');
				});

                get_keywords_list();
            	get_deliverables_list();
            	
            	// Upload Form Events            		
               	
               	$('#new_thumb_form').css('position', 'absolute');
            	$('#new_thumb_form').css('left', '-10000px');
            	$('#thumb_form_mask').click(function(){
            		$('#thumb_file').click();
            		return false;
            	});
            	$('#thumb_file').change(function(){
					$('#new_thumb_form').ajaxSubmit({
						success: showThumbResponse	
					});
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
            	
            });
            
            
			
			$(document).ready(function(){
				
				
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

				$('#live-show-all').click(function(){
					$('#live-filter img').attr('src', 'images/checkbox-0.png');
					if ($('#live-images-wrapper').attr('f_active') == 'disabled'){
						$('#apply-filters-poster').hide();
						$('#live-images-wrapper').fadeIn('slow');
					}
					$('#live-images-wrapper').attr('f_active', 'filter');

					filter_live_images();
				});
			});
			
			
			
        </script>
    </head>
    <body onload="update_queue();">
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
                        <div id="multiple-upload-wrapper">
                        	<input type="file" id="file_upload" name="file_upload" />
                        </div>
                        <!-- really tweaked. don't touch. -->
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
                        <ul class="grid-block holdall holdall-queue" id="grid-block-image">
                        </ul>

                        <section id="list-block" class="holdall holdall-queue">
                            <table>
                                <thead>
                                <th></th>
                                <th></th>
                                <th style="cursor: pointer" onclick="App_sort_queue_project_name();">project title</th>
                                <th style="cursor: pointer" onclick="App_sort_queue_filename();">file name</th>
                                <th style="cursor: pointer" onclick="App_sort_queue_date();">date added</th>
                                <th style="cursor: pointer" onclick="App_sort_queue_medium_name();">medium</th>
                                <th style="cursor: pointer" onclick="App_sort_queue_division_name();">division</th>
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
							<li style="width:100px">
								<a id="live-show-all" style="width: 50px; overflow: visible" href="javascript: void(0)">
								show all
                            	</a>

							</li>
                            <li style="width:90px">
                                <a href="javascript: void(0)">
								search
                                    <span class="dropdown"></span>
                                </a>
                                <div class="search-dropdown">
                                    <span>
                                        <a class="reset-input" href="javascript: void(0)" onclick="$('#search-query-string').val('');">reset input</a>
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
                                		<li class='selected' order="az"><span>a - z</span></li>
                                		<li order="za"><span>z - a</span></li>
                                		<li order="date"><span>date</span></li>
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
	                    	<ul id="live-grid" class="grid-block holdall holdall-live">
	                    	</ul>
	                    	
	                        <section id="live-list" class="holdall holdall-live">
	                            <table>
	                                <thead>
	                                <th></th>
	                                <th></th>
	                                <th style="cursor: pointer" onclick="App_sort_live_project_name();">project title</th>
	                                <th style="cursor: pointer" onclick="App_sort_live_filename();">file name</th>
	                                <th style="cursor: pointer" onclick="App_sort_live_date();">date added</th>
	                                <th style="cursor: pointer" onclick="App_sort_live_medium_name();">medium</th>
	                                <th style="cursor: pointer" onclick="App_sort_live_division_name();">division</th>
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
			<div id="edit-single-kd">
				<input type="text" id="edit-kd-input" />
				<span id="cancel-edited-kd">cancel</span>
				<span id="save-edited-kd">save</span>
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
			                                       	<a class="edit-list edit-kd-deliverables" href="javascript: void(0)">edit list..</a>
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
			                                       	<a class="edit-list edit-kd-keywords" href="javascript: void(0)">edit list..</a>
			                                   	</div></div>
			                              	</td>
			                            </tr>
			                              
								</div>
							</td>
						</tr>
					</table>
					<div class="edit-buttons">
                                <a href="javascript: void(0)" onclick="save_multiple($('#edit-multiple-dialog').attr('id_list'));CachedDialogType=0;">save</a>
                                <a href="javascript: void(0)" onclick="$('#edit-multiple-dialog').dialog('close');CachedDialogType=0;">cancel</a>
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
						<li id="types-selector">
							<a rel="1" class='selected' href='#'>Portrait</a>
							<strong>|</strong>
							<a rel="2" href='#'>Landscape</a>
						</li>
                        <li class="load-thumbnail">
                            <a id="thumb_form_mask" href="#">load new thumbnail...</a>
                            <form enctype="multipart/form-data" action="php/new_thumb.php" id="new_thumb_form">
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
                                                <a class="edit-list edit-kd-deliverables" href="javascript: void(0)">edit list..</a>
                                            </div>
                                        </div><!-- end float-left -->

                                        <div class="float-left margin-keywords">
                                            <strong class="label-title">keywords:</strong>
                                            <div class="list-container">
                                                <div class="list-block">
                                                    <ul id="keywords-list">
                                                    </ul>
                                                </div>
                                                <a class="edit-list edit-kd-keywords" href="javascript: void(0)">edit list..</a>
                                            </div>
                                        </div><!-- end float-left -->


                                    </div> <!-- end form-row -->
                                </section><!-- details-content -->
                                <section class="thumbnail-wrapper" style="margin:auto">
                                    <img id="thumbnail-selector" src="images/madmen.jpg" />
                                </section>
                            </div><!-- end details-wrapper -->
                            <div class="edit-buttons">
                                <a href="javascript: void(0)" onclick="save_single();CachedDialogType=0;">save</a>
                                <a href="javascript: void(0)" onclick="$('#overlay').dialog('close');CachedDialogType=0;">cancel</a>
                            </div><!-- end edit-buttons -->
                        </form>
                    </section>
                </div><!-- end overlay-content -->
            </div><!-- end overlay -->
        </div><!-- end container -->
    </body>
</html>
