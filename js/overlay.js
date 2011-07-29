			function get_keywords_list(){
            	
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
            			$('#keywords-list').html(data);
            			$('#keywords-list li').click(function(){
            				if ($(this).hasClass('selected')){
            					$(this).removeClass('selected');
            				} else {
            					$(this).addClass('selected');
            				}
            			})
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
        	
        	
        	function refresh_keywords_list(keyw_arr){
        		var keyword_state = new Array();
        		for (var i = 1; i <= keyw_arr[0]; i += 1){
        			keyword_state[parseInt(keyw_arr[i])] = 'selected';
        		}
        		$('#keywords-list li').removeClass('selected');
        		$('#keywords-list li').each(function(){
        			var keyword_id = parseInt($(this).attr('keyword_id'));
        			if (keyword_state[keyword_id] == 'selected'){
        				$(this).addClass('selected');
        			}
        		});
        	}
        	
			function edit_multiple_dialog(id_list){
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
					}
				});
			}
			
			function save_multiple(id_list){
				var project_confirm = parseInt($('#project-confirm').attr('src').split('checkbox-')[1].split('.')[0]);
				if (project_confirm == 1){
					save_project_name(id_list, $('#m-project-sel').attr('value'));
				}
				
				var date_confirm = parseInt($('#date-confirm').attr('src').split('checkbox-')[1].split('.')[0]);
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
				
				var medisc_didisc_confirm = parseInt($('#medisc-didisc-confirm').attr('src').split('checkbox-')[1].split('.')[0]);
				if (medisc_didisc_confirm == 1){
					save_medisc(id_list, $('#m-medium-sel').val(), $('#m-med-disc-sel').val());
					save_didisc(id_list, $('#m-division-sel').val(), $('#m-div-disc-sel').val());
				}
				
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
					
					save_deliverables(id_list, deliverables_list);
				}
				
				var keywords_confirm = parseInt($('#keywords-confirm').attr('src').split('checkbox-')[1].split('.')[0]);
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
					
					save_keywords(id_list, keywords_list);
				}
				$('#edit-multiple-dialog').dialog('close');
				update_queue();
				filter_live_images();
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
