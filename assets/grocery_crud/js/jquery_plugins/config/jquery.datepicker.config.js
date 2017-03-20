$(function(){
	$('.datepicker-input').datepicker({
			dateFormat: js_date_format,
			showButtonPanel: true,
			changeMonth: true,
			changeYear: false,
      minDate: '2017-07-16',
      maxDate: '2017-08-06'
	});

  $('.datepicker-input').on('keydown keyup', function () {
    return false
  })

	$('.datepicker-input-clear').button();
	
	$('.datepicker-input-clear').click(function(){
		$(this).parent().find('.datepicker-input').val("");
		return false;
	});

});
