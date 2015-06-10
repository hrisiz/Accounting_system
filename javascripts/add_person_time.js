 jQuery(document).ready(function($){
	$(function() {
		$.datepicker.regional['bg'] = {
			closeText: 'затвори',
			prevText: '&#x3c;назад',
			nextText: 'напред&#x3e;',
			nextBigText: '&#x3e;&#x3e;',
			currentText: 'днес',
			monthNames: ['Януари','Февруари','Март','Април','Май','Юни','Юли','Август','Септември','Октомври','Ноември','Декември'],
			monthNamesShort: ['Яну','Фев','Мар','Апр','Май','Юни','Юли','Авг','Сеп','Окт','Нов','Дек'],
			dayNames: ['Неделя','Понеделник','Вторник','Сряда','Четвъртък','Петък','Събота'],
			dayNamesShort: ['Нед','Пон','Вто','Сря','Чет','Пет','Съб'],
			dayNamesMin: ['Не','По','Вт','Ср','Че','Пе','Съ'],
			weekHeader: 'Wk',
			dateFormat: 'dd.mm.yy',
			firstDay: 1,
			isRTL: false,
			showMonthAfterYear: false,
			yearSuffix: ''
		};
		$( "#datepicker" ).datepicker({
			dateFormat: "yy-mm-dd"
		});
		$.datepicker.setDefaults($.datepicker.regional['bg']);
	});
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