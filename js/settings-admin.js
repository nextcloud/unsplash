$(function() {
	$('#headerbackground').on('change', function() {
		var status = 'no';
		if ($(this).is(':checked')) {
			status = 'yes';
		}
		OCP.AppConfig.setValue('unsplash', 'headerbackground', status);
	});

    $('#headerbackgroundlink').change(function(e) {
        var el = $(this);
        $.when(el.focusout()).then(function() {
            OCP.AppConfig.setValue('unsplash', 'headerbackgroundlink', $(this).val());
        });
        if (e.keyCode == 13) {
            OCP.AppConfig.setValue('unsplash', 'headerbackgroundlink', $(this).val());
        }
    });


});
