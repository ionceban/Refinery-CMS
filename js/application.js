// CMS Application v2
var QueuedImages = new Array();
var LiveImages = new Array();
var ImagesToSave = new Array();
var ImageToFit;

function App_sort_az(a, b){
	if (a.filename < b.filename){
		return (-1);
	} else {
		return 1;
	}
}

function App_sort_za(a, b){
	if (a.filename > b.filename){
		return (-1);
	} else {
		return 1;
	}
}

function App_sort_date(a, b){
	var aYear = a.date.split('-')[0], aMonth = a.date.split('-')[1], aDay = a.date.split('-')[2];
	var bYear = b.date.split('-')[0], bMonth = b.date.split('-')[1], bDay = b.date.split('-')[2];
	
	if (aYear > bYear){
		return (-1);
	} else if (aYear == bYear){
		if (aMonth > bMonth){
			return (-1);
		} else if (aMonth == bMonth){
			if (aDay > bDay){
				return (-1);
			} else {
				return 1;
			}
		} else {
			return 1;
		}
	} else {
		return 1;
	}
}

function App_sort_images(Images, order){
	var CopyArray = new Array();

	for (var i = 1; i <= Images[0]; i += 1){
		CopyArray[i-1] = Images[i];		
	}

	if (order == 'az'){
		CopyArray.sort(App_sort_az);
	} else if (order == 'za'){
		CopyArray.sort(App_sort_za);
	} else if (order =='date'){
		CopyArray.sort(App_sort_date);
	}

	for (var i = 1; i <= Images[0]; i += 1){
		Images[i] = CopyArray[i-1];
	}

	return Images;
}

function App_find_index(target_array, image_id){
	for (var i = 0; i < target_array.length; i += 1){
		if (target_array[i]['image_id'] == image_id){
			return i;
		}
	}

	return (-1);
}

function App_detect_image_type(image_id){
	if (App_find_index(QueuedImages, image_id) != -1){
		return ('queued');
	} else if (App_find_index(LiveImages, image_id) != -1){
		return ('live');
	} else {
		return ('undefined');
	}
}

function App_remove_image(image_id){
	var image_type = App_detect_image_type(image_id);
	if (image_type == 'live'){
		var image_index = App_find_index(LiveImages, image_id);
		LiveImages.splice(image_index, 1);
		LiveImages[0] -= 1;
	} else if (image_type == 'queued'){
		var image_index = App_find_index(QueuedImages, image_id);
		QueuedImages.splice(image_index, 1);
		QueuedImages[0] -= 1;
	}
}

function App_update_image(image_id, Image){
	var image_type = App_detect_image_type(image_id);
	if (image_type == 'live'){
		var image_index = App_find_index(LiveImages, image_id);
		LiveImages[image_index] = Image;
	} else {
		var image_index = App_find_index(QueuedImages, image_id);
		QueuedImages[image_index] = Image;
	}
}

function App_delete_images(Images){
	for (var i = 1; i <= Images[0]; i += 1){
		$.ajax({
			url: "php/delete_single_image.php",
			cache: false,
			type: "POST",
			data: {image_id: Images[i]},
			success: function(data){
				if (data == "failed"){
					alert(data);
				} else {
					App_remove_image(data);
					App_remove_view(data);
				}
			}
		});
	}
}

function App_migrate_to_queue(Images){
	// migrating the images with no deliverable/keyword back to the queue
	
	for (var i = 1; i <= Images[0]; i += 1){
		if (App_detect_image_type(Images[i]) == 'live'){
			var current_index = App_find_index(LiveImages, Images[i]);
			var ToMigrate = LiveImages[current_index];
			
			App_remove_image(Images[i]);
			App_remove_view(Images[i]);

			QueuedImages[0] += 1;
			QueuedImages[QueuedImages[0]] = ToMigrate;
		} else {
			return false;
		}
	}

	QueuedImages = App_sort_images(QueuedImages, 'date');
	
	App_render_queue();
}

function App_migrate_to_queue_DB(Images){
	$.ajax({
		url: "php/migrate_to_queue.php",
		cache: false,
		type: "POST",
		data: { image_list: JSON.stringify(Images) },
		success: function(data){
			if (data == 'success'){
				App_migrate_to_queue(Images);
			}
		}
	});
}

function App_assign_list_events(Images){
	for (var i = 1; i <= Images[0]; i += 1){
		var item_id = '#list-image-item-' + Images[i];

		$(item_id + ' .select-toggle').click(function(){
			var new_checkbox = 1 - parseInt($(this).attr('src').split('checkbox-')[1].split('.png')[0]);
			$(this).attr('src', 'images/checkbox-' + new_checkbox + '.png');
			if ($(this).attr('src').split('checkbox-')[1].split('.png')[0] == '0'){
				if ($(this).parents('.holdall:first').hasClass('holdall-queue')){
					$('#queue-select-all').attr('src', 'images/checkbox-0.png');
				} else {
					$('#live-select-all').attr('src', 'images/checkbox-0.png');
				}
			}
		});

		$(item_id + ' .star-toggle').click(function(){
			var image_id = $(this).parents('.list-image-item:first').attr('image_id');
			$.ajax({
				url: "change_featured.php",
				cache: false,
				type: "POST",
				data: {image_id: image_id},
				success: function(data){
					var response = $.parseJSON(data);

					$('#list-image-item-' + image_id + ' .star-toggle').attr('src', 'images/' + response.star + '-star.png');
					$('#grid-image-item-' + image_id + ' .star-toggle').attr('src', 'images/' + response.star + '-star.png');

					$('#list-image-item-' + image_id + ' .shadow-toggle').attr('src', 'images/shadowbox-' + response.shadow + '.png');
					$('#grid-image-item-' + image_id + ' .shadow-toggle').attr('src', 'images/shadowbox-' + response.shadow + '.png');
				}
			});
		});

		$(item_id + ' .shadow-toggle').click(function(){
			var image_id = $(this).parents('.list-image-item:first').attr('image_id');
			$.ajax({
				url: "php/change_shadowbox.php",
				cache: false,
				type: "POST",
				data: {image_id: image_id},
				success: function(data){
					var response = $.parseJSON(data);

					$('#list-image-item-' + image_id + ' .star-toggle').attr('src', 'images/' + response.star + '-star.png');
					$('#grid-image-item-' + image_id + ' .star-toggle').attr('src', 'images/' + response.star + '-star.png');

					$('#list-image-item-' + image_id + ' .shadow-toggle').attr('src', 'images/shadowbox-' + response.shadow + '.png');
					$('#grid-image-item-' + image_id + ' .shadow-toggle').attr('src', 'images/shadowbox-' + response.shadow + '.png');
				}
			});
		});

		$(item_id + ' .edit-button').click(function(){
			edit_dialog($(this).parents('.list-image-item:first').attr('image_id'));	
		});

		$(item_id + ' .list-thumb').click(function(){
			edit_dialog($(this).parents('.list-image-item:first').attr('image_id'));
		});

		$(item_id + ' .delete-button').click(function(){
			var image_id = $(this).parents('.list-image-item:first').attr('image_id');
			var dummy = new Array();
			dummy[0] = 1; 
			dummy[1] = image_id;
			App_delete_images(dummy);
		});
	}
}

function App_assign_grid_events(Images){
	for (var i =1 ; i <= Images[0]; i += 1){
		var item_id = '#grid-image-item-' + Images[i];

		$(item_id + ' .select-toggle').click(function(){
			var new_checkbox = 1 - parseInt($(this).attr('src').split('checkbox-')[1].split('.png')[0]);
			$(this).attr('src', 'images/checkbox-' + new_checkbox + '.png');
			if ($(this).attr('src').split('checkbox-')[1].split('.png')[0] == '0'){
				if ($(this).parents('.holdall:first').hasClass('holdall-queue')){
					$('#queue-select-all').attr('src', 'images/checkbox-0.png');
				} else {
					$('#live-select-all').attr('src', 'images/checkbox-0.png');
				}
			}
		});

		$(item_id + ' .star-toggle').click(function(){
			var image_id = $(this).parents('.grid-image-item:first').attr('image_id');
			$.ajax({
				url: "change_featured.php",
				cache: false,
				type: "POST",
				data: {image_id: image_id},
				success: function(data){
					var response = $.parseJSON(data);

					$('#list-image-item-' + image_id + ' .star-toggle').attr('src', 'images/' + response.star + '-star.png');
					$('#grid-image-item-' + image_id + ' .star-toggle').attr('src', 'images/' + response.star + '-star.png');

					$('#list-image-item-' + image_id + ' .shadow-toggle').attr('src', 'images/shadowbox-' + response.shadow + '.png');
					$('#grid-image-item-' + image_id + ' .shadow-toggle').attr('src', 'images/shadowbox-' + response.shadow + '.png');
				}
			});
		});

		$(item_id + ' .shadow-toggle').click(function(){
			var image_id = $(this).parents('.grid-image-item:first').attr('image_id');
			$.ajax({
				url: "php/change_shadowbox.php",
				cache: false,
				type: "POST",
				data: {image_id: image_id},
				success: function(data){
					var response = $.parseJSON(data);

					$('#list-image-item-' + image_id + ' .star-toggle').attr('src', 'images/' + response.star + '-star.png');
					$('#grid-image-item-' + image_id + ' .star-toggle').attr('src', 'images/' + response.star + '-star.png');

					$('#list-image-item-' + image_id + ' .shadow-toggle').attr('src', 'images/shadowbox-' + response.shadow + '.png');
					$('#grid-image-item-' + image_id + ' .shadow-toggle').attr('src', 'images/shadowbox-' + response.shadow + '.png');
				}
			});
		});

		$(item_id + ' .edit-button').click(function(){
			edit_dialog($(this).parents('.grid-image-item:first').attr('image_id'));
		});

		$(item_id + ' .delete-button').click(function(){
			var image_id = $(this).parents('.grid-image-item:first').attr('image_id');
			var dummy = new Array();
			dummy[0] = 1;
			dummy[1] = image_id;
			App_delete_images(dummy);
		});
	}
}

function App_remove_view(image_id){
	$('#list-image-item-' + image_id).remove();
	$('#grid-image-item-' + image_id).remove();
}

function App_fetch_list_view(list, Images){
	$.ajax({
		url: "get_list_view.php",
		cache: false,
		type: "POST",
		data: {image_list: JSON.stringify(Images)},
		success: function(data){
			list.html(data);
			
			var ImageList = new Array();
			ImageList[0] = 0;
			for (var i = 1; i <= Images[0]; i += 1){
				ImageList[0] += 1;
				ImageList[ImageList[0]] = Images[i]['image_id'];
			}

			App_assign_list_events(ImageList);
		}
	});
}

function App_fetch_grid_view(grid, Images){
	$.ajax({
		url: "get_grid_view.php",
		cache: false,
		type: "POST",
		data: {image_list: JSON.stringify(Images)},
		success: function(data){
			grid.html(data);

			var ImageList = new Array();
			ImageList[0] = 0;
			for (var i = 1; i <= Images[0]; i += 1){
				ImageList[0] += 1;
				ImageList[ImageList[0]] = Images[i]['image_id'];
			}

			App_assign_grid_events(ImageList);
		}
	});
}

function App_render_queue(){
	App_fetch_list_view($('#list-block table tbody'), QueuedImages);
	App_fetch_grid_view($('#grid-block-image'), QueuedImages);
}

function App_fetch_queue(){
 	$.ajax({
		url: "queue_images.php",
		cache: false,
		success: function (data){
			QueuedImages = $.parseJSON(data);
			App_render_queue();
		}
	});
}

function App_render_live(){
	App_fetch_list_view($('#live-list table tbody'), LiveImages);
	App_fetch_grid_view($('#live-grid'), LiveImages);
}

function App_fetch_search(){
	if ($('#live-images-wrapper').attr('f_active') == 'disabled'){
		$('#apply-filters-poster').hide();	
		$('#live-images-wrapper').fadeIn('slow');
	}
	
	$('#live-images-wrapper').attr('f_active', 'search');

	var query_string = $('#search-query-string').val();

	$.ajax({
		url: "search_images.php",
		cache: false,
		type: "POST",
		data: { query_string: query_string},
		success: function(data){
			LiveImages = $.parseJSON(data);
			
			var order = $('#live-sort li.selected').attr('order');
			LiveImages = App_sort_images(LiveImages, order);

			App_render_live();
		}
	});

}

function App_fetch_filter(){
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

	$.ajax({
		url: "filter_images.php",
		cache: false,
		type: "POST",
		data: { mediums: mediums,
			divisions: divisions,
			deliverables: deliverables,
			keywords: keywords
		}, success: function(data){
			LiveImages = $.parseJSON(data);

			var order = $('#live-sort li.selected').attr('order');
			LiveImages = App_sort_images(LiveImages, order);

			App_render_live();
		}
	});
}

function App_replace_list_view(Image){
	var dummy = new Array();
	dummy[0] = 1;
	dummy[1] = Image;
	$.ajax({
		url: "get_list_view.php",
		cache: false,
		type: "POST",
		data: {image_list: JSON.stringify(dummy)},
		success: function(data){
			var list_row = $('#list-image-item-' + dummy[1]['image_id']);
			list_row.replaceWith(data);
			
			var dummy_2 = new Array();
			dummy_2[0] =1;
			dummy_2[1] = dummy[1]['image_id'];

			App_assign_list_events(dummy_2);
			
			list_row = $('#list-image-item-' + dummy[1]['image_id']);
			list_row.hide();
			list_row.fadeIn('slow');
		}
	});
}

function App_replace_grid_view(Image){
	var dummy = new Array();
	dummy[0] = 1;
	dummy[1] = Image;
	$.ajax({
		url: "get_grid_view.php",
		cache: false,
		type: "POST",
		data: {image_list: JSON.stringify(dummy)},
		success: function(data){
			var grid_item = $('#grid-image-item-' + dummy[1]['image_id']);
			grid_item.replaceWith(data);

			var dummy_2 = new Array();
			dummy_2[0] = 1;
			dummy_2[1] = dummy[1]['image_id'];

			App_assign_grid_events(dummy_2);

			grid_item = $('#grid-image-item-' + dummy[1]['image_id']);
			grid_item.hide();
			grid_item.fadeIn('slow');
		}
	});
}

function App_refresh_images(Images){
	$.ajax({
		url: "php/get_selected_images.php",
		cache: false,
		type: "POST",
		data: {image_list: JSON.stringify(Images)},
		success: function(data){
			var ImageProps = $.parseJSON(data);
			for (var i = 1; i <= ImageProps[0]; i += 1){
				App_replace_list_view(ImageProps[i]);
				App_replace_grid_view(ImageProps[i]);
				App_update_image(ImageProps[i]['image_id'], ImageProps[i]);
			}
		}
	});
}

function App_publish_images(Images){
	for (var i = 1; i <= Images[0]; i += 1){
		$.ajax({
			url: "php/publish_single_image.php",
			cache: false,
			type: "POST",
			data: {image_id: Images[i]},
			success: function(data){
				var response = $.parseJSON(data);
				if (response.message == "incomplete"){
					alert("Can't publish " + response.filename + "; you must select at least one item in both the deliverable and keywords section");
				} else {
					App_remove_image(response.image_id);
					App_remove_view(response.image_id);
				}
			}
		});
	}
}

// Buttons

$(document).ready(function(){

	// Queue Delete Multiple
	
	$('#queue-delete-multiple').click(function(evt){
		evt.preventDefault();
		if ($('#list-btn').hasClass('selected')){
			var Images = new Array();
			Images[0] = 0;
			
			$('#list-block .list-image-item .select-toggle').each(function(){
				if ($(this).attr('src').split('checkbox-')[1].split('.png')[0] == '1'){
					Images[0] += 1;
					Images[Images[0]] = $(this).parents('.list-image-item:first').attr('image_id');
				}
			});

			App_delete_images(Images);

			$('#queue-select-all').attr('src', 'images/checkbox-0.png');
		} else {
			var Images = new Array();
			Images[0] = 0;
			
			$('#grid-block-image .grid-image-item .select-toggle').each(function(){
				if ($(this).attr('src').split('checkbox-')[1].split('.png')[0] == '1'){
					Images[0] += 1;
					Images[Images[0]] = $(this).parents('.grid-image-item:first').attr('image_id');
				}
			});

			App_delete_images(Images);
		}
	});

	// Live Delete Multiple
	
	$('#live-delete-multiple').click(function(evt){
		evt.preventDefault();
		if ($('#live-list-btn').hasClass('selected')){
			var Images = new Array();
			Images[0] = 0;

			$('#live-list .list-image-item .select-toggle').each(function(){
				if ($(this).attr('src').split('checkbox-')[1].split('.png')[0] == '1'){
					Images[0] += 1;
					Images[Images[0]] = $(this).parents('.list-image-item:first').attr('image_id');
				}
			});

			App_delete_images(Images);
		} else {
			var Images = new Array();
			Images[0] = 0;

			$('#live-grid .grid-image-item .select-toggle').each(function(){
				 if ($(this).attr('src').split('checkbox-')[1].split('.png')[0] == '1'){
					Images[0] += 1;
					Images[Images[0]] = $(this).parents('.grid-image-item:first').attr('image_id');
				 }
			});

			App_delete_images(Images);
		}
	});

	// Queue Edit Multiple
	
	$('#queue-edit-multiple').click(function(evt){
		evt.preventDefault();
		if ($('#list-btn').hasClass('selected')){
			var query_string = '';
			ImagesToSave[0] = 0;

			$('#list-block .list-image-item .select-toggle').each(function(){
				if ($(this).attr('src').split('checkbox-')[1].split('.png')[0] == '1'){
					var image_id = $(this).parents('.list-image-item:first').attr('image_id');
					
					ImagesToSave[0] += 1;
					ImagesToSave[ImagesToSave[0]] = image_id;

					if (query_string != '') { query_string += '_'; }
					query_string += image_id;
				}
			});

			if (query_string != ''){
				edit_multiple_dialog(query_string);
			}
		} else{
			var query_string = '';
			ImagesToSave[0] = 0;

			$('#grid-block-image .grid-image-item .select-toggle').each(function(){
				if ($(this).attr('src').split('checkbox-')[1].split('.png')[0] == '1'){
					var image_id = $(this).parents('.grid-image-item:first').attr('image_id');

					ImagesToSave[0] += 1;
					ImagesToSave[ImagesToSave[0]] = image_id;

					if (query_string != '') { query_string += '_'; }
					query_string += image_id;
				}
			});

			if (query_string != ''){
				edit_multiple_dialog(query_string);
			}
		}
	});

	// Live Edit Multiple
	
	$('#live-edit-multiple').click(function(evt){
		evt.preventDefault();
		if ($('#live-list-btn').hasClass('selected')){
			var query_string = "";
			ImagesToSave[0] = 0;
			
			$('#live-list .list-image-item .select-toggle').each(function(){
				if ($(this).attr('src').split('checkbox-')[1].split('.png')[0] == '1'){
					var image_id = $(this).parents('.list-image-item:first').attr('image_id');

					ImagesToSave[0] += 1;
					ImagesToSave[ImagesToSave[0]] = image_id;

					if (query_string != ''){ query_string += '_'; }
					query_string += image_id;
				}
			});

			if (query_string != ''){
				edit_multiple_dialog(query_string);
			}
		} else {
			var query_string = "";
			ImagesToSave[0] = 0;

			$('#live-grid .grid-image-item .select-toggle').each(function(){
				if ($(this).attr('src').split('checkbox-')[1].split('.png')[0] == '1'){
					var image_id = $(this).parents('.grid-image-item:first').attr('image_id');

					ImagesToSave[0] += 1;
					ImagesToSave[ImagesToSave[0]] = image_id;

					if (query_string != ''){ query_string += '_'; }
					query_string += image_id;
				}
			});

			if (query_string != ''){
				edit_multiple_dialog(query_string);
			}
		}
	});

	// Queue publish multiple
	
	$('#queue-publish-multiple').click(function(evt){
		evt.preventDefault();
		if ($('#list-btn').hasClass('selected')){
			var Images = new Array();
			Images[0] = 0;
			
			$('#list-block .list-image-item .select-toggle').each(function(){
				if ($(this).attr('src').split('checkbox-')[1].split('.png')[0] == '1'){
					Images[0] += 1;
					Images[Images[0]] = $(this).parents('.list-image-item:first').attr('image_id');
				}
			});
			
			App_publish_images(Images);
		} else {
			var Images = new Array();
			Images[0] = 0;

			$('#grid-block-image .grid-image-item .select-toggle').each(function(){
				if ($(this).attr('src').split('checkbox-')[1].split('.png')[0] == '1'){
					Images[0] += 1;
					Images[Images[0]] = $(this).parents('.grid-image-item:first').attr('image_id');
				}
			});

			App_publish_images(Images);
		}
	});

	// Live sort images
	
	$('#live-sort li').click(function(evt){
		evt.preventDefault();
		$('#live-sort li').removeClass('selected');
		$(this).addClass('selected');
		
		LiveImages = App_sort_images(LiveImages, $(this).attr('order'));
		App_render_live();
	});
});
