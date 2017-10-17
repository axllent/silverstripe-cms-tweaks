/* Meta title / description counter */
(function($) {
	$.entwine('ss', function($) {
		$('textarea#Form_EditForm_MetaDescription, input#Form_EditForm_Title').entwine({
			onkeyup: function() {
				this.updateStats();
			},
			onkeydown: function(e) {
				if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
					e.preventDefault();
				}
			},
			onfocusin: function() {
				$(this).parent().addClass('counter-visible');
			},
			onfocusout: function() {
				$(this).parent().removeClass('counter-visible');
			},
			updateStats: function() {
				var counter = $(this).parent().find('.charcounter');
				if (!counter.length) {
					var c = $('<div class="charcounter"></div>');
					this.parent().append(c);
					counter = $(this).parent().find('.charcounter');
					this.parent().addClass('counter-container');
				}
				var wordCounts = {};
				var v = this.val().trim();
				var matches = v.match(/\b/g);
				wordCounts[this.id] = matches ? matches.length / 2 : 0;
				var words = 0;
				$.each(wordCounts, function(k, v) {
					words += v;
				});
				var chars = v.replace(/\s+/g, ' ').length;
				counter.text(words + ' words | ' + chars + ' chars');
			}
		});
	});
})(jQuery);
