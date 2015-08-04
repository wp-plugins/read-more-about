jQuery(document).ready(function( $ ){
	$( '#add-row' ).on('click', function() {
		var row = $( '.empty-row.screen-reader-text' ).clone(true);
		row.addClass('new-row link-fields');
		row.removeClass( 'empty-row screen-reader-text' );
		row.insertAfter( '.link-fields:last' );
		$('.new-row .new-field').attr("disabled",false);
		return false;
	});
  	
	$( '.remove-row' ).on('click', function() {
		$(this).parents('section').remove();
		return false;
	});

	$(".read_more_about_in_ex").change(function() { 
        if ($(this).val() == "internal") {
            $(this).parents('section').find(".internal-link").show();
            $(this).parents('section').find(".external-link").hide();
            $(this).parents('section').find(".external-title").hide();
        } else {
        	$(this).parents('section').find(".internal-link").hide();
            $(this).parents('section').find(".external-link").show();
            $(this).parents('section').find(".external-title").show();
        }
    });
});