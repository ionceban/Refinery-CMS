function jcrop_update(coords){
	var jcrop_coords = coords.x + "_" + coords.y + "_" + coords.x2 + "_" + coords.y2;
	$('#thumbnail-selector').attr('jcrop_coords', jcrop_coords);
}

function jcrop_init(image_id, create_initial){
	$.ajax({
		url: "php/get_image_attributes.php",
		cache: false,
		type: "POST",
		data: {image_id: image_id},
		success: function (data){
			var CurrentImage = $.parseJSON(data);
			var image_src = 'projs/' + CurrentImage.thumber_src;
			var minSelWidth, minSelHeight;

			if (parseInt(CurrentImage.thumb) == 1){
				minSelWidth = 222;
				minSelHeight = 318;
			} else {
				minSelWidth = 468;
				minSelHeight = 318;
			}

			$('#types-selector a').each(function(){
				$(this).removeClass('selected');
				if ($(this).attr('rel') == CurrentImage.thumb){
					$(this).addClass('selected');
				}
			});

			$('.jcrop-holder').remove();

			var thumberPreloader = new Image();
			thumberPreloader.src = image_src + '?uid=' + Math.floor(Math.random() * 10000);
			thumberPreloader.onload = function(){
				var PictureWidth = thumberPreloader.width;
				var PictureHeight = thumberPreloader.height;

				var ContainerWidth = 383;
				var ContainerHeight = 465;

				if (PictureWidth > ContainerWidth){
					PictureHeight = parseInt((ContainerWidth * PictureHeight) / PictureWidth);
					PictureWidth = ContainerWidth;
				}

				if (PictureHeight > ContainerHeight){
					PictureWidth = parseInt((ContainerHeight * PictureWidth) / PictureHeight);
					PictureHeight = ContainerHeight;
				}

				var scale = PictureWidth / thumberPreloader.width;

				var scaledSelWidth = parseInt(scale * minSelWidth) + 1;
				var scaledSelHeight = parseInt(scale * minSelHeight) + 1;
		
				var image_tag = "<img src='" + thumberPreloader.src + "' style='width:" + PictureWidth + "px;height:" 
					+ PictureHeight + "px' id='thumbnail-selector' \/>";

				$('#thumbnail-selector').replaceWith(image_tag);
		
				$('#thumbnail-selector').Jcrop({
					onSelect: jcrop_update,
					aspectRatio: scaledSelWidth / scaledSelHeight,
					minSize: [scaledSelWidth, scaledSelHeight]
				});

				if (create_initial == 1){
					$('#thumbnail-selector').attr('jcrop_coords', '0_0_' + PictureWidth + '_' + PictureHeight);
					jcrop_get_thumbnail(image_id);
				}


				$('#thumbnail-selector').attr('jcrop_coords', '0_0_0_0');
			}
		}
	});
}

function jcrop_get_thumbnail(image_id){
	var jcrop_coords = $('#thumbnail-selector').attr('jcrop_coords');
	if (jcrop_coords == '0_0_0_0'){
		save_plus();
		return false;
	}

	$.ajax({
		url: "php/jcrop_get_thumbnail.php",
		cache: false,
		type: "POST",
		data: {
			image_id: image_id,
			jcrop_coords: jcrop_coords,
			scaled_width: $('#thumbnail-selector').css('width').split('px')[0],
			scaled_height: $('#thumbnail-selector').css('height').split('px')[0]
		}, success: function(data){
			if (data != 'success'){
				alert(data);
			} else {
				$.ajax({
					url: "php/get_image_attributes.php",
					cache: false,
					type: "POST",
					data: {image_id: image_id},
					success: function(data){
						var CurrentImage = $.parseJSON(data);

						var file_attrs = CurrentImage.name.split('.');
						var normal_path = 'projs/' + file_attrs[0] + "_t_normal." + CurrentImage.thumber_ext;
						var featured_path = 'projs/' + file_attrs[0] + "_t_featured." + CurrentImage.thumber_ext;
						
						if (parseInt(CurrentImage.thumb) == 1){
							resize_image(normal_path, 99, 147, file_attrs[1]);
							resize_image(featured_path, 222, 318, file_attrs[1]);
						} else {
							resize_image(normal_path, 222, 147, file_attrs[1]);
							resize_image(featured_path, 468, 318, file_attrs[1]);
						}
					}
				});
			}

			save_plus();
		}
	});
}
