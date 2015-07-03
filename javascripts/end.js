function print_elem(data) 
{
	$.get( "css/print.css").done(function(style){
		var mywindow = window.open('');
		mywindow.document.write('<html><head>');
		mywindow.document.write("<style>"+style+"</style>");
		mywindow.document.write('</head><body >');
		mywindow.document.write(data);
		mywindow.document.write('</body></html>');
		console.log(mywindow.document);
		mywindow.document.close(); // necessary for IE >= 10
		mywindow.focus(); // necessary for IE >= 10

		mywindow.print();
		mywindow.close();
	});

	
	return true;
}
function next_person(person,start_date,end_date){
	$.ajax({
		type:'post',
		data:{
			"send_id":person,
			"send_start_date":start_date,
			"send_end_date":end_date
		},
		url: "ajax.php?page=EndWeekCheck", 
		success: function(result){
			$("div#end_week_people_info").html(result);
		}
	});
}
$(document).ready(function(){
	create_datepicker('input.datepicker');
	$(document).on("click","#end_week_with_info",function(){
		$('div.back_js_show_page').show();
		$('div#end_week_people_info').show();
		$.ajax({
			type:'post',
			data:{
				"send_start_date":$("#start_date").val(),
				"send_end_date":$("#end_date").val()
			},
			url: "ajax.php?page=GetPersonsJson", 
			success: function(persons){
				var next = 0;
				$(document).on('click',"#previous_person",function(){
					next -= 1;
					if(next < 0){
						next = 0;
					}
					next_person(persons[next],$("#start_date").val(),$("#end_date").val());
				});
				$(document).on('click',"#next_person",function(){
					next += 1;
					if(next > persons.length-1){
						next = persons.length-1;
					}
					next_person(persons[next],$("#start_date").val(),$("#end_date").val());
				});
				next_person(persons[next],$("#start_date").val(),$("#end_date").val());
			},
			error:function(err){
				alert("Взъникна грешка. Моля рестартираите страницата или опитаите по-късно.");
				console.log(err);
			}
		});
	});
	$(document).on("click","#send_end_week",function(){
		if(confirm('Сигурни ли сте, че искате да завършите седмицата?')){
			$("input[name=end_week]").trigger("click");
		}
	});
	$(document).on("click","#print_person",function(){
		var clone = $("div#end_week_people_info").clone();
		clone.find('input[type=checkbox].bonus').each(function(){
			if($(this).is(":checked")){
				$(this).replaceWith("Удържан");
			}else{
				$(this).replaceWith("Не е удържан.");
			}
		});
		print_elem(clone.html());
	});
	$(document).on('change','input[type=checkbox].bonus',function(){
		var this_checbox = $(this);
		var new_val = 1;
		if(!this_checbox.is(':checked')){
			new_val = 0;
		}
		$.ajax({
			type:'post',
			data:{
				"send_id":this_checbox.attr('data-id'),
				"new_val":new_val
			},
			url: "ajax.php?page=ChangeBonus", 
			success: function(result){
				alert(result);
			},
			error:function(){
				alert("Възникна проблем. Моля свържете се с администратора.");
			},
			fail:function(){
				alert("Възникна проблем. Моля свържете се с администратора.");
			}
		});
		if(!this_checbox.is(':checked')){
			this_checbox.parents("li").addClass('not_used_bonus');
			$("#end_money_for_week>span").html((parseInt($("#end_money_for_week>span").html())+parseInt(this_checbox.attr('data-takemoney'))));
		}else{
			this_checbox.parents("li").removeClass('not_used_bonus');
			$("#end_money_for_week>span").html((parseInt($("#end_money_for_week>span").html())-parseInt(this_checbox.attr('data-takemoney'))));
		}
	});
});