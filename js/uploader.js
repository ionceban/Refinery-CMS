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
					    		}
		                	
		                	var t_thumber = file_body + "_t_thumber." + thumber_extension;
		                	var t_normal = file_body + "_t_normal." + thumber_extension;
		                	var t_featured = file_body + "_t_featured." + thumber_extension;
		                	var t_grid = file_body + "_t_grid." + thumber_extension;
		                	var t_list = file_body + "_t_list." + thumber_extension;
		                	
		                	var img_Preloader = new Image();
		                	img_Preloader.src = t_thumber;
		                	img_Preloader.onload = function(){
		                		
		                		GLOBAL_COUNTER = 0;
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