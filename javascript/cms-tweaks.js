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
            onmatch: function() {
                var parent = $(this).parent();
                parent.addClass('counter-container');
                var counterdiv = $('<div class="charcounter"></div>');
                parent.append(counterdiv);
                this.updateStats();
            },
            updateStats: function() {
                var wordCounts = {};
                var v = this.val().trim();
                var matches = v.match(/\b/g);
                wordCounts[this.id] = matches ? matches.length / 2 : 0;
                var words = 0;
                $.each(wordCounts, function(k, v) {
                    words += v;
                });
                var chars = v.replace(/\s+/g, ' ').length;
                this.parent().find('.charcounter').text(words + ' words | ' + chars + ' chars');
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
