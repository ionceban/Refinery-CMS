function resize_image(image_src, new_width, new_height){
	$.ajax({
		url: "php/fit_image.php",
		cache: false,
		type: "POST",
		data: {
			image_src: image_src,
			new_width: new_width,
			new_height: new_height
		}, success: function(data){
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
