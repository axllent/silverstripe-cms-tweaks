(function($) {
	$.entwine('ss', function($) {
		/* remove `target` from logo links */
		$('#cms-menu .cms-sitename a').removeAttr('target');

		/* Prevent enter key from submitting */
		$('.cms-edit-form .field.date input, .cms-edit-form .field.text input,' +
			'.cms-edit-form .noenter').entwine({
			onkeydown: function(e) {
				if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13))
					e.preventDefault();
			}
		});
	});

	/* Set the default layout to 'content' */
	$.entwine('ss.preview', function($) {
		$('.cms-preview').entwine({
			DefaultMode: 'content'
		});
	});
})(jQuery);
