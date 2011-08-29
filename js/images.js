function resize_image(image_src, new_width, new_height, file_type){
	$.ajax({
		url: "php/fit_image.php",
		cache: false,
		type: "POST",
		data: {
			image_src: image_src,
			new_width: new_width,
			new_height: new_height,
			file_type: file_type
		}, success: function(data){
			if (data != 'success'){
				alert(data);
			}

			++FIT_COUNTER;
			if (FIT_COUNTER == NECESSARY_FITS){
				/*if (ImageToFit != '-1'){
					var dummy = new Array();
					dummy[0] = 1;
					dummy[1] = ImageToFit;

					App_refresh_images(dummy);

					ImageToFit = -1;
				}*/
				update_queue();
				filter_live_images();
			}
		}
	});
}
