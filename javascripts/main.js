 jQuery(document).ready(function($){
	$(document).on('click','div.back_js_show_page',function(){
		$('div.back_js_show_page').hide();
		$('div.front_js_show_page').hide();
	});
	// if($('p.error,p.success').length > 0){
		// var top_nav = $('header').html();
		// $('header').html($('p.error,p.success').clone().addClass('show'));
		// $('.show').show();
		// setTimeout(function(){
			// $('p.error,p.success').fadeOut(500);
			// setTimeout(function(){
					// $('header').html(top_nav);
			// },500)
		// },1000);
	// }
	setTimeout(function(){
		$('p.error,p.success').addClass('hide');
	},1000);
	
 });
 function create_datepicker(selector){
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
	$( selector ).datepicker({
		dateFormat: "yy-mm-dd"
	});
	$.datepicker.setDefaults($.datepicker.regional['bg']);

 }