jQuery.fn.extend({'replaceTemplate':function (value){
	return this.each(function() {
		if (this.name) this.name = this.name.replace('%value%', value);
		if (this.id) this.id = this.id.replace('%value%', value);
	});
}});

jQuery(document).ready(function($) {
	var nextId 		= ffto_social_accounts.next_extra_id;
	var prefix		= '#ffto_social_account_';
	var accounts	= $('#social_accounts');
	var popular		= $(prefix+'visible_accounts_all, '+prefix+'visible_accounts_popular');
	var uploads		= $('.upload');
	
	popular.on('change', popular_onChange).trigger('change');
		
	accounts.on('click', '.remove', extraRemove_onClick);
	accounts.on('click', '.upload', upload_onClick);
	accounts.on('click', '.remove_icon', removeIcon_onClick);
		
	accounts.find("tbody").sortable({update:updateOrder});

	$('#extra_upload').on('click', extraUpload_onClick);
	$('#extra_add').on('click', extraAdd_onClick);
		
	accounts.find('.upload').each(function(){
		var element = $(this);
		void updateCustomImage(element); 
    });
					
	function updateOrder (){
		var rows 	= $('#social_accounts tr:visible'),
			values	= rows.map(function (){ return $(this).data('account'); }).get().join();
		$('#ffto_social_account_order').val(values);	
	}
	
	function extraUpload_onClick (){
		var element	= $(this),
			field	= $('#extra_account_custom');

 		tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		
		window.send_to_editor = function (html) {
			imgurl = $('img', html).attr('src');
			field.val(imgurl);
			tb_remove();
		}
				
		return false;
	}
						
	function extraAdd_onClick (){
		var name		= $("#extra_account_name"),
			value		= $("#extra_account_value"),
			custom		= $('#extra_account_custom'),
			template	= $("#extra_template");
		
		if (!name.val() || !value.val()) return alert(ffto_social_accounts.error_extra);
			
		var clone		= template.clone(),
			label		= clone.find('th label'),
			inputValue	= clone.find('input.extra_value'),
			inputName	= clone.find('input.extra_name'),
			customBox	= clone.find('.upload');
		
		label.html(name.val());						
		inputValue.val(value.val()).replaceTemplate(nextId);												
		inputName.val(name.val()).replaceTemplate(nextId);
		customBox.replaceTemplate(nextId).find('.image_source').replaceTemplate(nextId).val(custom.val());
		
		
		template.parent().append(clone);
		clone.data('account', 'extra_'+nextId).attr('id','').show();
		updateCustomImage(customBox);
		
		name.val('');
		value.val('');
		custom.val('');
		
		nextId++;	
		
		updateOrder();
	}
	
	function extraRemove_onClick (){
		$(this).closest('tr').remove();
		updateOrder();
	}
	
	function upload_onClick (event){
		var element	= $(this),
			field	= element.find('.image_source');

 		tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		
		window.send_to_editor = function (html) {
			imgurl = $('img', html).attr('src');
			field.val(imgurl);
			updateCustomImage(element);
			tb_remove();
		}
				
		return false;		
	}
	
	function removeIcon_onClick (event){
		event.stopPropagation();
				
		var element = $(this).closest('.upload');
		element.find('.image_source').val('');
		void updateCustomImage(element);
	}
	
	function updateCustomImage (element){
		var src 		= element.find('.image_source').val(),
			oldImage	= element.find('.old'),
			newImage	= element.find('.new');
		
		if (src){ 
			oldImage.hide();
			newImage.show().find('img').attr('src', src); 
		}else{
			oldImage.show();
			newImage.hide();
		}
	}
	
	function popular_onChange (event){
		var all = $(prefix+'visible_accounts_all');
		if (all.is(':checked'))	accounts.find('tr.rare').show();
		else					accounts.find('tr.rare').hide();
	}
});
