(function($){
	$.entwine('ss', function($){

		$('#tab-Root_Advanced').entwine({
			onmatch: function(){
				this.attr('title', 'Search engine optimization (SEO) etc');
			}
		});

		/* Prevent enter from submitting */
		$('.cms-edit-form .field.date input, .cms-edit-form .field.text input,' +
			' #Form_EditForm_MetaDescription').entwine({
			onkeydown: function(e){
				if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13))
					e.preventDefault();
			}
		});

		/* add auto-submit functionality */
		$('#Form_ItemEditForm_AutoSubmitForm').entwine({
			onmatch: function(){
				$('#Form_ItemEditForm_action_doSave').trigger('click');
				$('#Form_ItemEditForm_action_doSave').hide();
				$('<p class="message good">Creating record, please wait...</p>' ).prependTo( '#Root_Main');
				$('#Root_Main').find('div').hide();
			}
		});

		/* hide the delete button */
		$('#Form_ItemEditForm_HideDeleteButton').entwine({
			onmatch: function(){
				$('#Form_ItemEditForm_action_doDelete').hide();
			}
		});

		/* Provide word / character stats on MetaDescription & MetaTitle input fields */
		$('textarea#Form_EditForm_MetaDescription').entwine({
			onkeyup: function(){
				this.updateStats();
			},
			onmatch: function(){
				this.updateStats();
			},
			updateStats: function(){
				var wordCounts = {};
				var v = this.val().trim();
				var matches = v.match(/\b/g);
				wordCounts[this.id] = matches ? matches.length / 2 : 0;
				var words = 0;
				$.each(wordCounts, function(k, v){
					words += v;
				});
				var chars = v.replace(/\s+/g, ' ').length;
				$('#' + this.attr('id') + 'Stats').text(words + ' words | ' + chars +' chars');
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
	$('div.cms-logo a').entwine({
		onmatch: function(){
			this.attr('href', '');
			this.attr('title', 'Return to website');
			this.removeAttr('target');
		}
	});

})(jQuery);