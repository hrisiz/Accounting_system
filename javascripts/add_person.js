 jQuery(document).ready(function($){
	$(document).on('click','button#edit_person',function(){
		$('div.back_js_show_page').show();
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
});