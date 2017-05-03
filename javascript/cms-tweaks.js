(function($){
	$.entwine('ss', function($){

		/* Prevent enter key from submitting */
		$('.cms-edit-form .field.date input, .cms-edit-form .field.text input,' +
			'.cms-edit-form .noenter').entwine({
			onkeydown: function(e){
				if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13))
					e.preventDefault();
			}
		});

		/* Provide word / character stats on MetaDescription & MetaTitle input fields */
		// $('input#Form_EditForm_MetaTitle').entwine({
		// 	onkeyup: function(){
		// 		this.updateStats();
		// 	},
		// 	onmatch: function(){
		// 		this.updateStats();
		// 	},
		// 	updateStats: function(){
		// 		var wordCounts = {};
		// 		var v = this.val().trim();
		// 		var matches = v.match(/\b/g);
		// 		wordCounts[this.id] = matches ? matches.length / 2 : 0;
		// 		var words = 0;
		// 		$.each(wordCounts, function(k, v){
		// 			words += v;
		// 		});
		// 		var chars = v.replace(/\s+/g, ' ').length;
		// 		$('#' + this.attr('id') + 'Stats').text(words + ' words | ' + chars +' chars');
		// 	}
		// });

		// $('textarea#Form_EditForm_MetaDescription').entwine({
		// 	onkeyup: function(){
		// 		this.updateStats();
		// 	},
		// 	onmatch: function(){
		// 		this.updateStats();
		// 	},
		// 	updateStats: function(){
		// 		var wordCounts = {};
		// 		var v = this.val().trim();
		// 		var matches = v.match(/\b/g);
		// 		wordCounts[this.id] = matches ? matches.length / 2 : 0;
		// 		var words = 0;
		// 		$.each(wordCounts, function(k, v){
		// 			words += v;
		// 		});
		// 		var chars = v.replace(/\s+/g, ' ').length;
		// 		$('#' + this.attr('id') + 'Stats').text(words + ' words | ' + chars +' chars');
		// 	}
		// });

		/* move any notices to above the tabs */
		$('#Root .noticefield').entwine({
			onmatch: function(){
				if ($('#Form_EditForm_error').length != 0)
					jQuery(this).detach().insertAfter('#Form_EditForm_error');
				else if ($('#Form_ItemEditForm_error').length != 0)
					jQuery(this).detach().insertAfter('#Form_ItemEditForm_error');
			}
		});

	});

	/* Set the default layout to 'content' */
	$.entwine('ss.preview', function($){
		$('.cms-preview').entwine({
			DefaultMode: 'content'
		});
	});

	/* Set website logo to return to website */
	// $('div.cms-logo a').entwine({
	// 	onmatch: function(){
	// 		this.attr('href', '');
	// 		this.attr('title', 'Return to website');
	// 		this.removeAttr('target');
	// 	}
	// });

})(jQuery);
