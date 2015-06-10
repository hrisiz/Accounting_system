 jQuery(document).ready(function($){
	$(document).on('click','button#edit_person',function(){
		$('div#edit_person_field_out').show();
		$('div#edit_person_field_in').show();
		$.ajax({
			type:'post',
			data:{
				"send_id":$(this).attr("data-id")
			},
			url: "ajax.php?page=AddPersonEdit", 
			success: function(result){
				$("div#edit_person_field_in").html(result);
			}
		});
	});
	$(document).on('click','div#edit_person_field_out',function(){
		$('div#edit_person_field_out').hide();
		$('div#edit_person_field_in').hide();
	});
});