/*
 * Do not display error pages in SiteTree or file selector
 * loaded only when !Permission::check('SITETREE_REORGANISE')
 */
(function($){
	$.entwine('ss', function($){

		/* Hide error pages in SiteTree */
		$('li[data-pagetype="ErrorPage"]').entwine({
			onmatch: function(){
				this.hide();
			}
		});

        /* Hide error pages in page link dropdowns */
        $('li.class-ErrorPage').entwine({
            onmatch: function(){
                this.hide();
            }
        });

		/* Hide error-xxx.html from file selector dropdowns */
		$('li.class-File').entwine({
			onmatch: function(){
				var c = this.text().trim();
				if (c.match(/^error-(\d+){3}\.html$/))
					this.hide();
			}
		});

		/* Hide error-xxx.html from File gridfields */
		$('td.col-Title').entwine({
			onmatch: function(){
				var c = this.text().trim();
				if (c.match(/^error-(\d+){3}\.html$/))
					this.parent().hide();
			}
		});

	});
})(jQuery);
