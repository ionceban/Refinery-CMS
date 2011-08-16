			function assign_list_events(which_list){
				$(which_list + ' .list-image-item .select-toggle').click(function(){
					var checkbox_type = 1 - parseInt($(this).attr('src').split('checkbox-')[1].split('.')[0]);
					$(this).attr('src', 'images/checkbox-' + checkbox_type + '.png');
					if (checkbox_type == 0){
						if ($(this).parents('section:first').attr('id') == 'live-list'){
							$('#live-select-all').attr('src', 'images/checkbox-0.png');
						} else {
							$('#queue-select-all').attr('src', 'images/checkbox-0.png');
						}
					}
				});
				
				$(which_list + ' .list-image-item .star-toggle').click(function(){
					var image_id = $(this).parents('.list-image-item:first').attr('image_id');
					var starObj = $(this);
					var shadowObj = $(this).parents('.select-wrapper:first').find('.shadow-toggle');
					$.ajax({
						url: "change_featured.php",
						cache: false,
						type: "POST",
						data: {image_id: image_id},
						success: function(data){
							var response = $.parseJSON(data);
							var star_type = response.star;
							var shadow_type = response.shadow;
							starObj.attr('src', 'images/' + star_type + '-star.png');
							shadowObj.attr('src', 'images/shadowbox-' + shadow_type + '.png');
						}
					});
				});

				$(which_list + ' .list-image-item .shadow-toggle').click(function(){
					var image_id = $(this).parents('.list-image-item:first').attr('image_id');
					var shadowObj = $(this);
					var starObj = $(this).parents('.select-wrapper:first').find('.star-toggle');
					$.ajax({
						url: "php/change_shadowbox.php",
						cache: false,
						type: "POST",
						data: {image_id: image_id},
						success: function(data){
							var response = $.parseJSON(data);
							var star_type = response.star;
							var shadow_type = response.shadow;
							starObj.attr('src', 'images/' + star_type + '-star.png');
							shadowObj.attr('src', 'images/shadowbox-' + shadow_type + '.png');
						}
					});
				});
				
				$(which_list + ' .list-image-item .edit-button').click(function(){
					var image_id = $(this).parents('.list-image-item:first').attr('image_id');
					edit_dialog(image_id);
				});
				
				$(which_list + ' .list-thumb').click(function(){
					var image_id = $(this).parents('.list-image-item:first').attr('image_id');
					edit_dialog(image_id);
				});
				
				$(which_list + ' .list-image-item .delete-button').click(function(){
					var image_id = $(this).parents('.list-image-item:first').attr('image_id');
					if ($(this).parents('section:first').attr('id') == 'list-block'){
						delete_images(image_id, 0);
					} else {
						delete_images(image_id, 1);
					}
				});
			}
			
			function assign_grid_events(which_grid){
				$(which_grid + ' .select-item .select-toggle').click(function(){
					var checkbox_type = 1 - parseInt($(this).attr('src').split('checkbox-')[1].split('.')[0]);
					$(this).attr('src', 'images/checkbox-' + checkbox_type + '.png');
					if (checkbox_type == 0){
						if ($(this).parents('ul:first').attr('id') == 'live-grid'){
							$('#live-select-all').attr('src', 'images/checkbox-0.png');
						} else {
							$('#queue-select-all').attr('src', 'images/checkbox-0.png');
						}
					}
				});
				
				$(which_grid + ' .select-item .star-toggle').click(function(){
					var image_id = $(this).parents('li:first').attr('image_id');
					var starObj = $(this);
					var shadowObj = $(this).parents('.select-item:first').find('.shadow-toggle');
					$.ajax({
						url: "change_featured.php",
						cache: false,
						type: "POST",
						data: {image_id: image_id},
						success: function(data){
							var response = $.parseJSON(data);
							var star_type = response.star;
							var shadow_type = response.shadow;
							starObj.attr('src', 'images/' + star_type + '-star.png');
							shadowObj.attr('src', 'images/shadowbox-' + shadow_type + '.png');
						}
					});
				});

				$(which_grid + ' .select-item .shadow-toggle').click(function(){
					var image_id = $(this).parents('li:first').attr('image_id');
					var starObj = $(this).parents('.select-item:first').find('.star-toggle');
					var shadowObj = $(this);
					$.ajax({
						url: "php/change_shadowbox.php",
						cache: false,
						type: "POST",
						data: {image_id: image_id},
						success: function (data){
							var response = $.parseJSON(data);
							var star_type = response.star;
							var shadow_type = response.shadow;
							starObj.attr('src', 'images/' + star_type + '-star.png');
							shadowObj.attr('src', 'images/shadowbox-' + shadow_type + '.png');
						}
					});
				});
				
				$(which_grid + ' .grid-image-item .edit-button').click(function(){
					var image_id = $(this).parents('.grid-image-item:first').attr('image_id');
					edit_dialog(image_id);
				});
				
				$(which_grid + ' .grid-image-item .delete-button').click(function(){
					var image_id = $(this).parents('.grid-image-item:first').attr('image_id');
					if ($(this).parents('ul:first').attr('id') == 'grid-block-image'){
						delete_images(image_id, 0);
					} else {
						delete_images(image_id, 1);
					}
				});
			}
			
			function update_queue(){
            	
            	$.ajax({
            		url: "queue_images.php",
            		cache: false,
            		type: "POST",
            		success: function(data){
            			var encoded_image_list = data;
            			$.ajax({
            				url: "get_list_view.php",
            				cache: false,
            				type: "POST",
            				data: {image_list: encoded_image_list},
            				success: function(data){
            					if ($('#list-btn').hasClass('selected')){
            						$('#list-block').hide();
            					}
            					$('#list-block table tbody').html(data);
            					assign_list_events('#list-block');
            					if ($('#list-btn').hasClass('selected')){
            						$('#list-block').fadeIn('slow');
            					}
            				}
            			});
            			
            			$.ajax({
            				url: "get_grid_view.php",
            				cache: false,
            				type: "POST",
            				data: {image_list: encoded_image_list},
            				success: function(data){
            					if ($('#grid-btn').hasClass('selected')){
            						$('#grid-block-image').hide();
            					}
            					$('#grid-block-image').html(data);
            					assign_grid_events('#grid-block-image');
            					if ($('#grid-btn').hasClass('selected')){
            						$('#grid-block-image').fadeIn('slow');
            					}
            				}
            			})
            		}
            	})
            	
            }
            
			function filter_live_images(){
				
				if ($('#live-images-wrapper').attr('f_active') == 'disabled'){
					return false;
				}
				
				$('#live-images-wrapper').attr('f_active', 'filter');
				
				var mediums = "";
				$('#live-filter-mediums img').each(function(){
					var checkbox_type = parseInt($(this).attr('src').split('checkbox-')[1].split('.')[0]);
					if (checkbox_type == 1){
						if (mediums != '') mediums += "_";
						mediums += $(this).attr('filter_rel');
					}
				});
				
				var divisions = "";
				$('#live-filter-divisions img').each(function(){
					var checkbox_type = parseInt($(this).attr('src').split('checkbox-')[1].split('.')[0]);
					if (checkbox_type == 1){
						if (divisions != '') divisions += "_";
						divisions += $(this).attr('filter_rel');
					}
				});
				
				var deliverables = "";
				$('#live-filter-deliverables img').each(function(){
					var checkbox_type = parseInt($(this).attr('src').split('checkbox-')[1].split('.')[0]);
					if (checkbox_type == 1){
						if (deliverables != '') deliverables += "_";
						deliverables += $(this).attr('filter_rel');
					}
				});
				
				var keywords = "";
				$('#live-filter-keywords img').each(function(){
					var checkbox_type = parseInt($(this).attr('src').split('checkbox-')[1].split('.')[0]);
					if (checkbox_type == 1){
						if (keywords != '') keywords += "_";
						keywords += $(this).attr('filter_rel');
					}
				});
				
				var order = $('#live-sort li.selected').attr('order');
				
				$.ajax({
					url: "filter_images.php",
					cache: false,
					type: "POST",
					data: {mediums: mediums, 
						divisions: divisions, 
						deliverables: deliverables, 
						keywords: keywords,
						order: order
					}, success: function(data){
						var encoded_image_list = data;
						$.ajax({
							url: "get_list_view.php",
							cache: false,
							type: "POST",
							data: {image_list: encoded_image_list},
							success: function(data){
								if ($('#live-list-btn').hasClass('selected')){
									$('#live-list').hide();
								}
								$('#live-list table tbody').html(data);
								assign_list_events('#live-list');
								if ($('#live-list-btn').hasClass('selected')){
									$('#live-list').fadeIn('slow');
								}
							}
						});
						
						$.ajax({
							url: "get_grid_view.php",
							cache: false,
							type: "POST",
							data: {image_list: encoded_image_list},
							success: function(data){
								if ($('#live-grid-btn').hasClass('selected')){
									$('#live-grid').hide();
								}
								$('#live-grid').html(data);
								assign_grid_events('#live-grid');
								if ($('#live-grid-btn').hasClass('selected')){
									$('#live-grid').fadeIn();
								}
							}
						});
					}
				});
			}
			
			function search_live_images(){
				
				if ($('#live-images-wrapper').attr('f_active') == 'disabled'){
					$('#apply-filters-poster').hide();
					$('#live-images-wrapper').fadeIn('slow');
				}
				
				$('#live-images-wrapper').attr('f_active', 'search');
				
				var query_string = $('#search-query-string').val();
				var order = $('#live-sort li.selected').attr('order');
				
				$.ajax({
					url: "search_images.php",
					cache: false,
					type: "POST",
					data: {
						query_string: query_string,
						order: order
					}, success: function(data){
						var encoded_image_list = data;
						$.ajax({
							url: "get_list_view.php",
							cache: false,
							type: "POST",
							data: {image_list: encoded_image_list},
							success: function(data){
								if ($('#live-list-btn').hasClass('selected')){
									$('#live-list').hide();
								}
								$('#live-list table tbody').html(data);
								assign_list_events('#live-list');
								if ($('#live-list-btn').hasClass('selected')){
									$('#live-list').fadeIn('slow');
								}
							}
						});
						
						$.ajax({
							url: "get_grid_view.php",
							cache: false,
							type: "POST",
							data: {image_list: encoded_image_list},
							success: function(data){
								if ($('#live-grid-btn').hasClass('selected')){
									$('#live-grid').hide();
								}
								$('#live-grid').html(data);
								assign_grid_events('#live-grid');
								if ($('#live-grid-btn').hasClass('selected')){
									$('#live-grid').fadeIn();
								}
							}
						});
					}
				});
			}
			
			$(document).ready(function(){
				$('#live-sort ul li').click(function(){
					$('#live-sort ul li').each(function(){
						$(this).removeClass('selected');
					});
					
					$(this).addClass('selected');
					
					if ($('#live-images-wrapper').attr('f_active') == 'filter'){
						filter_live_images();
					} else if ($('#live-images-wrapper').attr('f_active') == 'search'){
						search_live_images();
					}
				});
			});
