/*
 * Do not display error pages in SiteTree or file selector
 * loaded only when !Permission::check('SITETREE_REORGANISE')
 */
(function($) {
	$.entwine('ss', function($) {
		/* Hide error pages in SiteTree */
		$('li[data-pagetype$="\ErrorPage"]').entwine({
			onmatch: function() {
				this.hide();
			}
		});
		/* Hide error pages in page link dropdowns */
		$('li.class-ErrorPage').entwine({
			onmatch: function() {
				this.hide();
			}
		});
	});
})(jQuery);
