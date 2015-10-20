/*
* Do not display ability to add pages or Settings tab
* loaded only when !Permission::check('SITETREE_REORGANISE')
*/
(function($){
	$.entwine('ss', function($){

		/* Hide Add Page button */
		$('a.cms-page-add-button').entwine({
			onmatch: function(){
				this.hide();
			}
		});

		/* Hide Settings Tab */
		$('ul.ui-tabs-nav > li').entwine({
			onmatch: function(){
				var c = this.text().trim();
				if (c.match(/^Settings$/))
					this.hide();
			}
		});

		/* Hide page "drag" bars in SiteTree */
		$('div.cms-tree a ins.jstree-icon').entwine({
			onmatch: function(){
				if (!this.hasClass('jstree-checkbox')){ /* Fix cms inconsistencies when Pages loaded through ajax */
					this.hide();
					this.parent().css('padding-left', '0'); // Remove padding-left
				}
			}
		});

		/* Hide Settings Tab */
		$('button[name="action_unpublish"]').entwine({
			onmatch: function(){
				this.hide();
				this.addClass('removed-no-edit')
				this.parent().children("button").not('.removed-no-edit').first().addClass('ui-corner-left');
			}
		});

	});
})(jQuery);