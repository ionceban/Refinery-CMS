			$(document).ready(function(){
				$('#file_upload').uploadify({
					'uploader'  : 'uploadify/uploadify.swf',
				    'script'    : 'php/upload_single_image.php',
				    'cancelImg' : 'uploadify/cancel.png',
				    'folder'    : 'projs/',
				    'auto'      : true,
				    'multi'     : true,
				    'sizeLimit' : 30000000,
				    'buttonImg' : 'images/upload_button.png',
				    'onComplete': function(event, ID, fileObj, response, data){
				    	if (response == "success"){
					    	var file_body = 'projs/' + fileObj.name.split('.')[0];
					    	var original_extension = fileObj.name.split('.')[1];
					    	var thumber_extension;
					    	if (original_extension == 'jpg' || original_extension == 'png' ||
					    		original_extension == 'gif'){
					    			thumber_extension = original_extension;
					    		} else {
					    			thumber_extension = 'jpg';
					    			convert_video(fileObj.name);
					    		}
		                	
		                	var t_thumber = file_body + "_t_thumber." + thumber_extension;
		                	var t_normal = file_body + "_t_normal." + thumber_extension;
		                	var t_featured = file_body + "_t_featured." + thumber_extension;
		                	var t_grid = file_body + "_t_grid." + thumber_extension;
		                	var t_list = file_body + "_t_list." + thumber_extension;
		                	
		                	var img_Preloader = new Image();
		                	img_Preloader.src = t_thumber;
		                	img_Preloader.onload = function(){
		                		
		                		var old_width = img_Preloader.width;
		                		var old_height = img_Preloader.height;
		                		
		                		var list_height = 50;
		                		var list_width = parseInt((list_height * old_width) / old_height);
		                		if (list_width < 35) list_width = 35;
		                		if (list_width > 200) list_width = 200;
		                		
		                		resize_image(t_list, list_width, list_height);
		                		
		                		var grid_height = 110;
		                		var grid_width = parseInt((grid_height * old_width) / old_height);
		                		if (grid_width < 97) grid_width = 97;
		                		if (grid_width > 400) grid_width = 400;
		                		
		                		resize_image(t_grid, grid_width, grid_height);
		                		
		                		if (original_extension == 'jpg' || original_extension == 'png' || original_extension == 'gif'){
		                			resize_image(t_featured, 222, 318);
		                			resize_image(t_normal, 99, 147);
		                		} else {
		                			resize_image(t_featured, 468, 318);
		                			resize_image(t_normal, 222, 147);
		                		}
		                	
		                	}
		                } else {
		                	NECESSARY_FITS -= 4;
		                	if (FIT_COUNTER == NECESSARY_FITS){
		                		update_queue();
		                	}
		                	alert(response);
		                }
				    }, 'onSelectOnce': function(event, data){
				    	FIT_COUNTER = 0;
				    	NECESSARY_FITS = parseInt(data.fileCount) * 4;
				    	$('#file_upload').uploadifyUpload();
				    }, 'onCancel': function(event, ID, fileObj, data){
				    	NECESSARY_FITS -= 4;
				    	if (FIT_COUNTER == NECESSARY_FITS){
				    		update_queue();
				    	}
				    }, 'onError': function(event, ID, fileObj, errorObj){
				    	NECESSARY_FITS -= 4;
				    	if (FIT_COUNTER == NECESSARY_FITS){
				    		update_queue();
				    	}
				    }
				});
			});
			
			function convert_video(filename){
				$.ajax({
					url: "php/convert_video.php",
					cache: false,
					type: "POST",
					data: {filename: filename},
					success: function(data){
						alert(data);
					}
				});
			}

			function showThumbResponse(responseText, statusText, xhr, $form){
				if (responseText != 'failed'){
					var response = $.parseJSON(responseText);

					var thumber_path = 'projs/' + response.core_name + '_t_thumber.' + response.new_ext;
					var normal_path = thumber_path.replace('thumber', 'normal');
					var featured_path = thumber_path.replace('thumber', 'featured');
					var grid_path = thumber_path.replace('thumber', 'grid');
					var list_path = thumber_path.replace('thumber', 'list');
					
					NECESSARY_FITS = 4;
					FIT_COUNTER = 0;

					var old_width = response.new_width;
					var old_height = response.new_height;

					var list_height = 50;
					var list_width = parseInt((list_height * old_width) / old_height);

					if (list_width < 35) list_width = 35;
					if (list_width > 200) list_width = 200;

					resize_image(list_path, list_width, list_height);

					var grid_height = 110;
					var grid_width = parseInt((grid_height * old_width) / old_height);

					if (grid_width < 97) grid_width = 97;
					if (grid_width > 400) grid_width = 400;

					resize_image(grid_path, grid_width, grid_height);

					jcrop_init($('#overlay').attr('image_id'), 1);
				}
			}
