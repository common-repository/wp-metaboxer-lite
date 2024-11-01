jQuery(document).ready(function($){
	$('.mtbxr-admin-sortables').sortable({
		handle:'.mtbxr-admin-header',
		placeholder: "mtbxr-admin-placeholder",
		forcePlaceholderSize:true,
		update: function(event, ui) {
			$('.mtbxr-admin-sortables .mtbxr-admin-box').each(function(i){
				$(this).find('.update-my-index').trigger('updateindex', [i]); /*** Calls any element in this box with this class and update its attributes. Can be input, label, select, etc. ***/
				//$(this).trigger('updatecookie', [i]);/*** Update cookie of this box ***/
			});
		}
	});
	
	/*** Close the right boxes based on cookie. Open more settings content based on cookie ***/
	$('.mtbxr-admin-sortables .mtbxr-admin-box').each(function(index){
		var body = $(this).find('.mtbxr-admin-body');
		var more = body.find('.mtbxr-admin-more-content');
		if($.cookie!=undefined){
			if($.cookie('mtbxr_box_'+index)=='closed'){
				body.hide();
			}
			if($.cookie('mtbxr_more_'+index)=='open'){
				more.show();
			}
		}
	});
		
	/*** Open or Close Box ***/
	$('.mtbxr-admin-sortables').on('click',  '.mtbxr-admin-header', function(e) {
		var box = $(this).parents('.mtbxr-admin-box');
		var body = box.find('.mtbxr-admin-body');
		var index = parseInt( $('.mtbxr-admin-box').index(box) );
		if(body.is(':visible')){
			body.slideUp(100, function(){
				box.trigger('updatecookie', [index]);/*** Update cookie of this box ***/
			});
		} else {
			body.slideDown(100, function(){
				box.trigger('updatecookie', [index]);/*** Update cookie of this box ***/
			});
		}
		e.preventDefault();
	});
	
	/*** Add Box ***/
	$('.mtbxr-admin-toolbar').on('click', 'input[type="button"]', function(e){
		var index = $('.mtbxr-admin-sortables .mtbxr-admin-box').length;
		var type = $(this).data('type'); /*** textbox, textarea, etc. ***/
		var html = $('#mtbxr-builder-skeletons .'+type).html();
		if(html==null){
			alert('Error. Could not add field.');
		}
		html = html.replace(/{index}/g, index);/*** replace all occurences of {id} to real id ***/
		var sortable = $('.mtbxr-admin-sortables');
		var box = $(html);
		box.appendTo(sortable).hide().fadeIn('fast');
	});
	
	/*** Delete Box ***/
	$('.mtbxr-admin-sortables').on('click', '.mtbxr-admin-delete', function(e){
		e.preventDefault();
		e.stopPropagation();
		var box = $(this).parents('.mtbxr-admin-box');

		box.fadeOut('fast', function(){
			box.remove();
			$('.mtbxr-admin-sortables .mtbxr-admin-box').each(function(i){
				$(this).find('.update-my-index').trigger('updateindex', [i]);
				//$(this).trigger('updatecookie', [i]);/*** Update cookie of this box ***/
			});
		})
	});
	
	/*** More Settings ***/
	$('.mtbxr-admin-sortables').on('click', '.mtbxr-admin-more a', function(e){
		e.preventDefault();
		var box = $(this).parents('.mtbxr-admin-box');
		var more = box.find('.mtbxr-admin-more-content');
		var index = parseInt( $('.mtbxr-admin-box').index(box) );
		if(more.is(':visible')){
			more.slideUp(100);
			if($.cookie!=undefined){
				$.cookie('mtbxr_more_'+index, null);/*** Delete cookie ***/
			}
		} else {
			more.slideDown(100);
			if($.cookie!=undefined){
				$.cookie('mtbxr_more_'+index, 'open', { expires: 7});/*** Remember open section ***/
			}
		}
	});
	
	/*** Add Select Option ***/
	$('.mtbxr-admin-sortables').on('click', '.mtbxr-option-add', function(e){
		e.preventDefault();
		var box = $(this).parents('.mtbxr-admin-box');
		var table = $(this).prev();
		var index = box.data('index');
		var optionIndex = table.find('tr.row').length;

		var html = '<tr class="row">';
		html += '<td><input class="mtbxr-option-value update-my-index" type="text" name="mtbxr[fields]['+index+'][options]['+optionIndex+'][value]"></td>';
		html += '<td><input class="mtbxr-option-text update-my-index" type="text" name="mtbxr[fields]['+index+'][options]['+optionIndex+'][text]"></td>';
		html += '<td><a class="mtbxr-option-delete" href="#">Delete</a></td>';
		html += '</tr>';
		table.append(html);
	});
	
	
	/*** Delete Select Option ***/
	$('.mtbxr-admin-sortables').on('click', '.mtbxr-option-delete', function(e){
		e.preventDefault();
		
		var box = $(this).parents('.mtbxr-admin-box');
		var table = $(this).parents('table');
		var index = box.data('index');
		$(this).parents('tr').remove();
		var optionIndex = table.find('tr.row').length;

		table.find('tr.row').each(function(i){
			$(this).children().eq(0).find('input').attr('name', 'mtbxr[fields]['+index+'][options]['+i+'][value]');
			$(this).children().eq(1).find('input').attr('name', 'mtbxr[fields]['+index+'][options]['+i+'][text]');
		});

	});
	
	/*** Event Hook when index is updated ***/
	$('.mtbxr-admin-sortables').on('updateindex', '.update-my-index', function(e, index){
		e.stopPropagation();
		var name = $(this).attr('name');
		var attr_for = $(this).attr('for');
		var id = $(this).attr('id');
		
		if(name){ /*** Is there a name attr in this element? then update the index ***/
			name = name.replace(/[\d]+/, index);
			$(this).attr('name', name);
		}
		if(attr_for){ /*** Is there a for attr in this element? then update the index  ***/
			attr_for = attr_for.replace(/[\d]+/, index);
			$(this).attr('for', attr_for);
		}
		if(id){ /*** Is there an id attr in this element? then update the index  ***/
			id = id.replace(/[\d]+/, index);
			$(this).attr('id', id);
		}
		
	});
	
	/*** Event hook to update cookie ***/
	$('.mtbxr-admin-sortables').on('updatecookie', '.mtbxr-admin-box', function(e, index){
		e.stopPropagation();
		var body = $(this).find('.mtbxr-admin-body');
		if(body.is(':visible')){
			if($.cookie!=undefined){
				$.cookie('mtbxr_box_'+index, null);/*** Delete cookie ***/
			}
		} else {
			if($.cookie!=undefined){
				$.cookie('mtbxr_box_'+index, 'closed', { expires: 7});/*** Remember closed section in cookie ***/
			}
		}
	});
	
	/*** On blur, fill uid field with value from label ***/		
	$('.mtbxr-admin-sortables').on('blur', '.mtbxr-label', function(e){
		e.stopPropagation();
		var target = $(this).nextAll('.mtbxr-uid');
		if(target.val()==''){
			target.val(string_to_key($(this).val()));
		}
	});
	
	/*** Format string to be a good uid ***/
	function string_to_key(string){
		string = string+'';
		string = string.toLowerCase();
		string = string.replace(/[^a-z0-9]+/g, '_').replace(/[_]+$/g, '');/*** Replace non alphanumeric with underscores. Remove last underscore ***/
		return string;
	}
	
	$('.inline-edit-group').hide();
});