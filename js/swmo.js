jQuery( document ).ready(function() {

	jQuery(".mainmenu").bind("click",function(){
		var element_id = jQuery(this).attr('id');
		var is_checked = jQuery(this).is(':checked');

		jQuery("."+element_id).prop("checked",is_checked);
	
		console.log(element_id);
		console.log(is_checked);

	});

});
