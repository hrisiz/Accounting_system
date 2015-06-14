 jQuery(document).ready(function($){
	create_datepicker('datepicker');
	$(document).on('click','input#persons_input',function(){
		$('div#persons').toggle();
	});
	$(document).on('click','div#persons>ul>li',function(){
		var li_elem = $(this);
		if(li_elem.hasClass('selected')){
			li_elem.removeClass('selected');
			li_elem.next().prop('checked', false);
		}else{
			li_elem.addClass('selected');
			li_elem.next().prop('checked', true);
		}
	});
	$(document).on('change','div#input > form input#datepicker',function(){
		$.ajax({
			type:'post',
			data:{
				"date":$("input#datepicker").val()
			},
			url: "ajax.php?page=AddPersonTimeForm", 
			success: function(result){
				$("div#ajax").html(result);
			}
		});
	});
});