/*
 * Do not display ability to add pages or Settings tab
 * loaded only when !Permission::check('SITETREE_REORGANISE')
 */
(function($) {
	$.entwine('ss', function($) {

		/* Hide "Add page" button */
		$('a[href="admin/pages/add/"]').entwine({
			onmatch: function() {
				this.hide();
			}
		});

		/* Hide "duplicate" content menu */
		$('#vakata-contextmenu a[rel="duplicate"]').entwine({
			onmatch: function() {
				this.parent().hide();
			}
		});

		/* Hide "Settings" tab */
		$('ul.ui-tabs-nav > li').entwine({
			onmatch: function() {
				var c = this.text().trim();
				if (c.match(/^Settings$/))
					this.hide();
			}
		});

		/* Hide page "drag" bars in SiteTree */
		$('div.cms-tree a ins.jstree-icon').entwine({
			onmatch: function() {
				if (!this.hasClass('jstree-checkbox')) { /* Fix cms inconsistencies when Pages loaded through ajax */
					this.hide();
					this.parent().css('padding-left', '0'); // Remove padding-left
				}
			}
		});

		/* Hide "Unpublish" menu */
		$('button[name="action_unpublish"]').entwine({
			onmatch: function() {
				this.hide();
				this.addClass('removed-no-edit')
				this.parent().children("button").not('.removed-no-edit').first().addClass('ui-corner-left');
			}
		});
	});
})(jQuery);
