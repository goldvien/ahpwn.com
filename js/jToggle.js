$(document).ready(function()
{
	
	$('.hidden').hide();
	
	
	$('.show').click(function() {
		var current_id = $(this).attr("id");
		$('#hidden' + current_id).fadeToggle('medium', function() {
	  //$('#hidden').fadeToggle('medium', function() {
	    
	  });
	});
	
	$('.show_odd').click(function() {
		var current_id = $(this).attr("id");
		$('#hidden' + current_id).fadeToggle('medium', function() {
	  //$('#hidden').fadeToggle('medium', function() {
	    
	  });
	});
  
});