$(function() {
	$('#headerbackground').on('change', function() {
		var status = 'no';
		if ($(this).is(':checked')) {
			status = 'yes';
		}
		OCP.AppConfig.setValue('unsplash', 'headerbackground', status);
	});
});
