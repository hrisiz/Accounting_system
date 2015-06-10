 jQuery(document).ready(function($){
	$(document).on('click','button#show_person',function(){
		$('div.back_js_show_page').show();
		$('div#check_person').show();
		$.ajax({
			type:'post',
			data:{
				"send_id":$(this).attr("data-id")
			},
			url: "ajax.php?page=CheckPerson", 
			success: function(result){
				$("div#check_person").html(result);
			}
		});
	});
});