			var CachedDialogParams, CachedDialogType = 0;

			function get_keywords_list(keyw_arr, which_dialog){
            	
            	$.ajax({
            		url: "get_keywords_list.php",
            		cache: false,
            		success: function(data){
            			$('#m-keywords-list').html(data);
            			$('#m-keywords-list li').click(function(){
            				if ($(this).hasClass('selected')){
            					$(this).removeClass('selected');
            				} else {
            					$(this).addClass('selected');
            				}
            			});
						/*$('#m-keywords-list .select-toggle').click(function(evt){
							evt.stopPropagation();
							var checkbox = $(this);
							var keyword_id = checkbox.parents('li:first').attr('keyword_id');
							$.ajax({
								url: "php/change_hidden.php",
								cache: false,
								type: "POST",
								data: {keyword_id: keyword_id},
								success: function(data){
									var keyw_arr = new Array();
									keyw_arr[0] = 0;
									$('#m-keywords-list li.selected').each(function(){
										keyw_arr[0] += 1;
										keyw_arr[keyw_arr[0]] = parseInt($(this).attr('keyword_id'));
									});
									get_keywords_list(keyw_arr, 'multiple');
								}
							});

						});*/

            			$('#keywords-list').html(data);
            			$('#keywords-list li').click(function(){
            				if ($(this).hasClass('selected')){
            					$(this).removeClass('selected');
            				} else {
            					$(this).addClass('selected');
            				}
            			});
						/*$('#keywords-list .select-toggle').click(function(evt){
							evt.stopPropagation();
							var checkbox = $(this);
							var keyword_id = checkbox.parents('li:first').attr('keyword_id');
							$.ajax({
								url: "php/change_hidden.php",
								cache: false,
								type: "POST",
								data: {keyword_id: keyword_id},
								success: function(data){
									var keyw_arr = new Array();
									keyw_arr[0] = 0;
									$('#keywords-list li.selected').each(function(){
										keyw_arr[0] += 1;
										keyw_arr[keyw_arr[0]] = parseInt($(this).attr('keyword_id'));
									});
									get_keywords_list(keyw_arr, 'single');
								}
							});
						});*/

						if (!(keyw_arr === undefined)){
							refresh_keywords_list(keyw_arr, which_dialog);
						}
            		}
            	});
            }
            
            
            function get_deliverables_list(){
         		
            	$.ajax({
            		url: "get_deliverables_list.php",
            		cache: false,
            		success: function(data){
            			$('#m-deliverables-list').html(data);
            			$('#m-deliverables-list li').click(function(){
            				if ($(this).hasClass('selected')){
            					$(this).removeClass('selected');
            				} else {
            					$(this).addClass('selected');
            				}
            			});
            			$('#deliverables-list').html(data);
            			$('#deliverables-list li').click(function(){
            				if ($(this).hasClass('selected')){
            					$(this).removeClass('selected');
            				} else {
            					$(this).addClass('selected');
            				}
            			});
            		}
            	});
            }
			
			function refresh_overlay_date(featured_day, featured_month, featured_year, which_dialog){
				$.ajax({
					url: "get_date_list.php",
					cache: false,
					type: "POST",
					data: {
						featured_day: featured_day,
						featured_month: featured_month,
						featured_year: featured_year,
						which_dialog: which_dialog
					}, success: function(data){
						if (which_dialog == 'single'){
							$('#date-wrapper').html(data);
							$('#date-day-sel').dropkick();
							$('#date-month-sel').dropkick();
							$('#date-year-sel').dropkick();
						} else {
							$('#m-date-wrapper').html(data);
							$('#m-date-day-sel').dropkick();
							$('#m-date-month-sel').dropkick();
							$('#m-date-year-sel').dropkick();
						}
					}
				});
			}
			
			function refresh_overlay_med_discs(medium_id, featured_id, which_dialog, to_dk){
				var med_disc_sel_id;
				if (which_dialog == "single"){
					med_disc_sel_id = 'med-disc-sel';
				} else {
					med_disc_sel_id = 'm-med-disc-sel';
				}
				
				$.ajax({
					url: "get_med_discs_list.php",
					cache: false,
					type: "POST",
					data: {medium_id: medium_id, featured_id: featured_id, which_dialog: which_dialog},
					success: function(data){
						$('#dk_container_' + med_disc_sel_id).remove();
						$('#' + med_disc_sel_id).replaceWith(data);
						if (to_dk == 1){
							$('#' + med_disc_sel_id).dropkick();
						}
					}
				});
			}

        
        	function refresh_overlay_mediums(featured_id, featured_disc_id, which_dialog){
        		
        		if (featured_id == 0){
        			alert('NULL medium');
        			return false;
        		}
        		
        		var medium_sel_id;
        		var division_sel_id;
        		var label_medisc_id;
        		
        		if (which_dialog == "single"){
        			medium_sel_id = "medium-sel";
        			division_sel_id = "division-sel";
        			label_medisc_id = "label-medisc";
        		} else {
        			medium_sel_id = "m-medium-sel";
        			division_sel_id = "m-division-sel";
        			label_medisc_id = "m-label-medisc";
        		}
        		
        		
        		$.ajax({
        			url: "get_mediums_list.php",
        			cache: false,
        			type: "POST",
        			data: {featured_id: featured_id, which_dialog: which_dialog},
        			success: function(data){
        				$('#dk_container_' + medium_sel_id).remove();
        				$('#' + medium_sel_id).replaceWith(data);
        				$('#' + medium_sel_id).dropkick({
        					change: function(value, label){
        						$.ajax({
        							url: "get_medium_by_id.php",
        							cache: false,
        							type: "POST",
        							data: {medium_id: value},
        							success: function(data){
        								if (data == 'av' || data == 'digital motion'){
        									$('#' + label_medisc_id).css('visibility', 'visible');
        									refresh_overlay_med_discs(value, 0, which_dialog, 1);
        								} else {
        									$('#' + label_medisc_id).css('visibility', 'hidden');
        									var current_division_id = $('#' + division_sel_id).val();
        									$.ajax({
        										url: "get_discipline_by_division.php",
        										cache: false,
        										type: "POST",
        										data: {division_id: current_division_id},
        										success: function(data){
        											refresh_overlay_med_discs(value, data, which_dialog, 0);
        										}
        									});
        								}
        							}
        						});
        						
        						$.ajax({
        							url: "get_discipline_by_medium.php",
        							cache: false,
        							type: "POST",
        							data: {medium_id: value},
        							success: function(data){
        								var current_division_id = $('#' + division_sel_id).val();
        								refresh_overlay_div_discs(current_division_id, data, which_dialog);
        							}
        						});
        					}
        				});
        				
        				$.ajax({
        					url: "get_medium_by_id.php",
        					cache: false,
        					type: "POST",
        					data: {medium_id: featured_id},
        					success: function(data){
        						if (data == 'av' || data == 'digital motion'){
        							$('#' + label_medisc_id).css('visibility', 'visible');
        							refresh_overlay_med_discs(featured_id, featured_disc_id, which_dialog, 1);
        						} else {
        							$('#' + label_medisc_id).css('visibility', 'hidden');
        							refresh_overlay_med_discs(featured_id, featured_disc_id, which_dialog, 0);
        						}
        					}
        				});
        			}
        		});
			}

			function refresh_overlay_div_discs(division_id, featured_id, which_dialog){
				var div_disc_sel_id;
				if (which_dialog == "single"){
					div_disc_sel_id = 'div-disc-sel';
				} else {
					div_disc_sel_id = 'm-div-disc-sel';
				}
				$.ajax({
					url: "get_div_discs_list.php",
					cache: false,
					type: "POST",
					data: {division_id: division_id, featured_id: featured_id, which_dialog: which_dialog},
					success: function(data){
						$('#' + div_disc_sel_id).replaceWith(data);
					}
				});
				
			}
        	
        	function refresh_overlay_divisions(featured_id, featured_disc_id, which_dialog){
        		var medium_sel_id;
        		var division_sel_id;
        		if (which_dialog == "single"){
        			medium_sel_id = 'medium-sel';
        			division_sel_id = 'division-sel';
        		} else {
        			medium_sel_id = 'm-medium-sel';
        			division_sel_id = 'm-division-sel';
        		}
        		$.ajax({
        			url: "get_divisions_list.php",
        			cache: false,
        			type: "POST",
        			data: {featured_id: featured_id, which_dialog: which_dialog},
        			success: function(data){
        				$('#dk_container_' + division_sel_id).remove();
        				$('#' + division_sel_id).replaceWith(data);
        				$('#' + division_sel_id).dropkick({
        					change: function(value,label){
        						var current_medium_id = $('#' + medium_sel_id).val();
        						$.ajax({
        							url: "get_discipline_by_medium.php",
        							cache: false,
        							type: "POST",
        							data: {medium_id: current_medium_id},
        							success: function(data){
        								refresh_overlay_div_discs(value, data, which_dialog);
        							}
        						});
        						
        						$.ajax({
        							url: "get_medium_by_id.php",
        							cache: false,
        							type: "POST",
        							data: {medium_id: current_medium_id},
        							success: function(data){
        								if (data != 'av' && data != 'digital motion'){
        									$.ajax({
        										url: "get_discipline_by_division.php",
        										cache: false,
        										type: "POST",
        										data: {division_id: value},
        										success: function(data){
        											refresh_overlay_med_discs(current_medium_id, data, which_dialog, 0);
        										}
        									});
        								}
        							}
        						});
        					}
        				});
        				
        				refresh_overlay_div_discs(featured_id, featured_disc_id, which_dialog);
        			}
        		});
        	}
        	
        	function refresh_deliverables_list(deliv_arr){
        		var deliverable_state = new Array();
        		for (var i = 1; i <= deliv_arr[0]; i += 1){
        			deliverable_state[parseInt(deliv_arr[i])] = 'selected';
        		}
        		$('#deliverables-list li').removeClass('selected');
        		$('#deliverables-list li').each(function(){
        			var deliverable_id = parseInt($(this).attr('deliverable_id'));
        			if (deliverable_state[deliverable_id] == 'selected'){
        				$(this).addClass('selected');
        			}
        		});
        	}
        	
        	
        	function refresh_keywords_list(keyw_arr, which_dialog){
        		var keyword_state = new Array();
        		for (var i = 1; i <= keyw_arr[0]; i += 1){
        			keyword_state[parseInt(keyw_arr[i])] = 'selected';
        		}

				if (which_dialog == 'single'){
        			$('#keywords-list li').removeClass('selected');
        			$('#keywords-list li').each(function(){
        				var keyword_id = parseInt($(this).attr('keyword_id'));
        				if (keyword_state[keyword_id] == 'selected'){
        					$(this).addClass('selected');
        				}
        			});
				} else {
					$('#m-keywords-list li').removeClass('selected');
					$('#m-keywords-list li').each(function(){
						var keyword_id = parseInt($(this).attr('keyword_id'));
						if (keyword_state[keyword_id] == 'selected'){
							$(this).addClass('selected');
						}
					});
				}
        	}
        	
			function edit_multiple_dialog(id_list){
				CachedDialogType = 2;
				CachedDialogParams = id_list;
				
				$('#edit-multiple-dialog').attr('id_list', id_list);
				
        		$.ajax({
        			url: "detect_project_name.php",
        			cache: false,
        			type: "POST",
        			data: {id_list: id_list},
        			success: function(data){
        				$('#m-project-sel').attr('value', data);
        			}
        		});
        		
        		$.ajax({
        			url: "detect_date_added.php",
        			cache: false,
        			type: "POST",
        			data: {id_list: id_list},
        			success: function(data){
        				if (data != "undefined"){
        					var fDay = data.split('-')[2];
        					var fMonth = data.split('-')[1];
        					var fYear = data.split('-')[0];
        					refresh_overlay_date(fDay, fMonth, fYear, 'multiple');
        				} else {
        					refresh_overlay_date(0, 0, 0, 'multiple');
        				}
        			}
        		});
        		
        		$.ajax({
        			url: "detect_medisc_didisc.php",
        			cache: false,
        			type: "POST",
        			data: {id_list: id_list},
        			success: function(data){
        				refresh_overlay_mediums(data.split('_')[0], data.split('_')[1], 'multiple');
        				refresh_overlay_divisions(data.split('_')[2], data.split('_')[3], 'multiple');
        			}
        		});
        		
        		
        		$('#project-confirm').attr('src', 'images/checkbox-0.png');
        		$('#date-confirm').attr('src', 'images/checkbox-0.png');
        		$('#medisc-didisc-confirm').attr('src', 'images/checkbox-0.png');
        		$('#deliverables-confirm').attr('src', 'images/checkbox-0.png');
        		$('#keywords-confirm').attr('src', 'images/checkbox-0.png');
        		
        		$('#edit-multiple-dialog').dialog('open');
        	}

			function save_plus(){
				++SAVE_COUNTER;
				if (SAVE_COUNTER == NECESSARY_SAVES){
					App_refresh_images(ImagesToSave);
				}
			}
        	
			function save_project_name(id_list, project_name){
				$.ajax({
					url: "save_project_name.php",
					cache: false,
					type: "POST",
					data: {id_list: id_list, project_name: project_name},
					success: function(data){
						if (data != "success"){
							alert(data);
						}
						save_plus();
					}
				});
			}

			function save_filename(image_id, filename){
				/* check if the name is valid */
				if (filename.length > 20){
					alert('Filname: no more than 20 characters allowed');
					save_plus();
					return false;
				}
				for (var i = 0; i < filename.length; i += 1){
					var chr = filename[i];
					if ((chr < 'a' || chr > 'z') && (chr < 'A' || chr >'Z') && (chr < '0' || chr > '9') && chr != '_'){
						alert('Filename: only letters, numbers and underlines allowed');
						save_plus();
						return false;
					}
				}
				$.ajax({
					url: "php/save_filename.php",
					cache: false,
					type: "POST",
					data: {image_id: image_id, filename: filename},
					success: function(data){
						if (data != "success"){
							alert(data);
						}
						save_plus();
					}
				});
			}
				
			
			function save_date(id_list, date){
				$.ajax({
					url: "save_date.php",
					cache: false,
					type: "POST",
					data: {id_list: id_list, date: date},
					success: function(data){
						if (data != "success"){
							alert(data);
						}
						save_plus();
					}
				});
			}
			
			function save_medisc(id_list, medium_id, discipline_id){
				$.ajax({
					url: "save_medisc.php",
					cache: false,
					type: "POST",
					data: {id_list: id_list, medium_id: medium_id, discipline_id: discipline_id},
					success: function(data){
						if (data != "success"){
							alert(data);
						}
						save_plus();
					}
				});
			}
			
			function save_didisc(id_list, division_id, discipline_id){
				$.ajax({
					url: "save_didisc.php",
					cache: false,
					type: "POST",
					data: {id_list: id_list, division_id: division_id, discipline_id: discipline_id},
					success: function(data){
						if (data != "success"){
							alert(data);
						}
						save_plus();
					}
				});
			}
			
			function save_deliverables(id_list, deliverables_list){
				$.ajax({
					url: "save_deliverables.php",
					cache: false,
					type: "POST",
					data: {id_list: id_list, deliverables_list: deliverables_list},
					success: function(data){
						if (data != "success"){
							alert(data);
						}
						save_plus();
					}
				});
			}
			
			function save_keywords(id_list, keywords_list){
				$.ajax({
					url: "save_keywords.php",
					cache: false,
					type: "POST",
					data: {id_list: id_list, keywords_list: keywords_list},
					success: function(data){
						if (data != "success"){
							alert(data);
						}
						save_plus();
					}
				});
			}

			function save_single(){
				image_id = $('#overlay').attr('image_id');

				NECESSARY_SAVES = 8;
				SAVE_COUNTER = 0;

				save_project_name(image_id, $('#project').val());
				save_filename(image_id, $('#file-name').val());

				var cDay = $('#date-day-sel').val();
				var cMonth = $('#date-month-sel').val();
				var cYear = $('#date-year-sel').val();
				var cDate = cYear + '-';
				if (cMonth < 10) cDate += '0';
				cDate += cMonth + '-';
				if (cDay < 10) cDate += '0';
				cDate += cDay;

				save_date(image_id, cDate);

				save_medisc(image_id, $('#medium-sel').val(), $('#med-disc-sel').val());
				save_didisc(image_id, $('#division-sel').val(), $('#div-disc-sel').val());
				
				var unsafe = 0;

				var deliverables_list = '';
				$('#deliverables-list li').each(function(){
					if ($(this).hasClass('selected')){
						if (deliverables_list != ''){
							deliverables_list += '_';
						}
						deliverables_list += $(this).attr('deliverable_id');
					}
				});

				if (deliverables_list == ''){
					unsafe = 1;
				}

				save_deliverables(image_id, deliverables_list);

				var keywords_list = '';
				$('#keywords-list li').each(function(){
					if ($(this).hasClass('selected')){
						if (keywords_list != ''){
							keywords_list += '_';
						}
						keywords_list += $(this).attr('keyword_id');
					}
				});

				if (keywords_list == ''){
					unsafe = 1;
				}

				save_keywords(image_id, keywords_list);

				if (unsafe == 1){
					alert("Warning: Live images with no keywords/deliverable will automatically migrate to the queue");
					App_migrate_to_queue_DB(ImagesToSave);
				}

				jcrop_get_thumbnail(image_id);

				$('#overlay').dialog('close');	
			}
			
			function save_multiple(id_list){
				$('#queue-select-all').attr('src', 'images/checkbox-0.png');

				var project_confirm = parseInt($('#project-confirm').attr('src').split('checkbox-')[1].split('.')[0]);
				var date_confirm = parseInt($('#date-confirm').attr('src').split('checkbox-')[1].split('.')[0]);
				var medisc_didisc_confirm = parseInt($('#medisc-didisc-confirm').attr('src').split('checkbox-')[1].split('.')[0]);
				var deliverables_confirm = parseInt($('#deliverables-confirm').attr('src').split('checkbox-')[1].split('.')[0]);
				var keywords_confirm = parseInt($('#keywords-confirm').attr('src').split('checkbox-')[1].split('.')[0]);
				
				NECESSARY_SAVES = project_confirm + date_confirm + medisc_didisc_confirm * 2
						 + deliverables_confirm + keywords_confirm;
				SAVE_COUNTER = 0;
				
				if (project_confirm == 1){
					save_project_name(id_list, $('#m-project-sel').attr('value'));
				}
				
				
				if (date_confirm == 1){
					var cDay = $('#m-date-day-sel').val();
					var cMonth = $('#m-date-month-sel').val();
					var cYear = $('#m-date-year-sel').val();
					var cDate = cYear + '-';
					if (cMonth < 10) cDate += '0';
					cDate += cMonth + '-';
					if (cDay < 10) cDate += '0';
					cDate += cDay;
					
					save_date(id_list, cDate);
				}
				
				
				if (medisc_didisc_confirm == 1){
					save_medisc(id_list, $('#m-medium-sel').val(), $('#m-med-disc-sel').val());
					save_didisc(id_list, $('#m-division-sel').val(), $('#m-div-disc-sel').val());
				}

				var unsafe = 0;
				
				var deliverables_confirm = parseInt($('#deliverables-confirm').attr('src').split('checkbox-')[1].split('.')[0]);
				if (deliverables_confirm == 1){
					var deliverables_list = '';
					$('#m-deliverables-list li').each(function(){
						if ($(this).hasClass('selected')){
							if (deliverables_list != ''){
								deliverables_list += '_';
							}
							deliverables_list += $(this).attr('deliverable_id');
						}
					});

					if (deliverables_list == ''){
						unsafe = 1;
					}
					
					save_deliverables(id_list, deliverables_list);
				}
				
				
				if (keywords_confirm == 1){
					var keywords_list = '';
					$('#m-keywords-list li').each(function(){
						if ($(this).hasClass('selected')){
							if (keywords_list != ''){
								keywords_list += '_';
							}
							keywords_list += $(this).attr('keyword_id');
						}
					});

					if (keywords_list == ''){
						unsafe = 1;
					}
					
					save_keywords(id_list, keywords_list);
				}

				if (unsafe == 1){
					alert('Warning: Live images with no keywords/deliverable will automatically migrate to the queue');
					App_migrate_to_queue_DB(ImagesToSave);
				}
				$('#edit-multiple-dialog').dialog('close');
			}
			
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
            					var ays = confirm("Are you sure? Live images with no deliverable/keyword assigned will automatically migrate back to the queue.");
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
            									update_queue();
            									var c_filter = $('#live-images-wrapper').attr('f_active');
            									if (c_filter == 'filter'){
            										filter_live_images();
            									} else if (c_filter == 'search'){
            										search_live_images();
            									}

												if (kd_type == 'keywords'){
													get_keywords_list();
												} else {
													get_deliverables_list();
												}
            								}
            							}
            						});
            					}
            				});
							
							if (kd_type == 'keywords'){
								$('#kd-wrapper .hide-toggle').click(function(){
									var keyword_id = $(this).parents('li:first').attr('kd_id');
									var selectObj = $(this);
									$.ajax({
										url: "php/change_hidden.php",
										cache: false,
										type: "POST",
										data: {keyword_id: keyword_id},
										success: function(data){
											selectObj.attr('src', 'images/checkbox-' + data + '.png');
											get_keywords_list();
										}
									});
								});
							}

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
            	$('#overlay').dialog('close');
            	$('#edit-multiple-dialog').dialog('close');
            	$('#add-kd').val('');
            	$('#edit-kd').dialog('open');
            	refresh_kd_edit(kd_type);
            }

			function refresh_cached_dialog(){
				if (CachedDialogType == 1){
					edit_dialog(CachedDialogParams);
				} else if (CachedDialogType == 2){
					edit_multiple_dialog(CachedDialogParams);
				}
			}
			
			$(document).ready(function(){
				$('#project-confirm').click(function(){
					var checkbox_type = 1 - parseInt($(this).attr('src').split('checkbox-')[1].split('.')[0]);
					$(this).attr('src', 'images/checkbox-' + checkbox_type + '.png');
				});
				
				$('#date-confirm').click(function(){
					var checkbox_type = 1 - parseInt($(this).attr('src').split('checkbox-')[1].split('.')[0]);
					$(this).attr('src', 'images/checkbox-' + checkbox_type + '.png');
				});
				
				$('#medisc-didisc-confirm').click(function(){
					var checkbox_type = 1 - parseInt($(this).attr('src').split('checkbox-')[1].split('.')[0]);
					$(this).attr('src', 'images/checkbox-' + checkbox_type + '.png');
				});
				
				
				$('#deliverables-confirm').click(function(){
					var checkbox_type = 1 - parseInt($(this).attr('src').split('checkbox-')[1].split('.')[0]);
					$(this).attr('src', 'images/checkbox-' + checkbox_type + '.png');
				});
				
				$('#keywords-confirm').click(function(){
					var checkbox_type = 1 - parseInt($(this).attr('src').split('checkbox-')[1].split('.')[0]);
					$(this).attr('src', 'images/checkbox-' + checkbox_type + '.png');
				});
			});
			
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
          			$('#edit-kd').dialog('close');
					refresh_cached_dialog();
          		});
          		
          		$('.edit-kd-keywords').click(function(){
          			edit_kd_list('keywords');
          		});
          		
          		$('.edit-kd-deliverables').click(function(){
          			edit_kd_list('deliverables');
          		});
			});


			$(document).ready(function(){
				$('#types-selector a').click(function(evt){
					evt.preventDefault();
					$anchor = $(this);
					if (!$anchor.hasClass('selected')){
						$.ajax({
							url: "php/change_thumbnail_type.php",
							cache: false,
							type: "POST",
							data: {
								image_id: $('#overlay').attr('image_id'),
								thumb_type: $anchor.attr('rel')
							}, success: function(data){
								if (data != 'success'){
									alert(data);
								} else {
									jcrop_init($('#overlay').attr('image_id'));
								}
							}
						});
					}
				});
			});
