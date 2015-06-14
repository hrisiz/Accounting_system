 jQuery(document).ready(function($){
	$(document).on('click','.show_person',function(){
		$('div.back_js_show_page').show();
		$('div#person_info').show();
		$.ajax({
			type:'post',
			data:{
				"send_id":$(this).attr("data-id")
			},
			url: "ajax.php?page=CheckPerson", 
			success: function(result){
				$("div#person_info").html(result);
			}
		});
	});
	$(document).on('change','input.compare_person',function(){
		var send_data = {};
		var checked_checkboxes = $('input.compare_person:checked');
		if(checked_checkboxes.length > 0){
			checked_checkboxes.each(function(key){
				send_data["id"+key] = $(this).attr('data-id');
			});
			console.log(send_data);
			$.ajax({
				type:'post',
				data:{
					"send_ids": send_data
				},
				url: "ajax.php?page=ComparePersons", 
				success: function(result){
					$("div#compare").html(result);
				}
			});
		}else{
			$("div#compare").html("Не са избрани хора за сравнение.");
		}
	});
	$(document).on('click','button.edit_person_day',function(){
		$.ajax({
			type:'post',
			data:{
				"send_id": $(this).attr('data-id')
			},
			url: "ajax.php?page=EditPersonTime", 
			success: function(result){
				$("div#person_info").html(result);
				create_datepicker('#datepicker');
			}
		});
		
	});
});