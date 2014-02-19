<?php

class LeftAndMainCMSTweaksExt extends LeftAndMainExtension {

	public function init() {

		parent::init();

		Requirements::css(CMS_TWEAKS_BASE . '/css/cms-tweaks.css');

		Requirements::javascript(CMS_TWEAKS_BASE . '/javascript/cms-tweaks.js');

		/* Remove help link */
		CMSMenu::remove_menu_item('Help');

		/* Hide "Add new" page, page Settings tab */
		if (!Permission::check('SITETREE_REORGANISE')) {
			Requirements::javascript(CMS_TWEAKS_BASE . '/javascript/sitetree-noedit.js');
		}

		/* Hide all error pages in SiteTree and Files (modeladmin) */
		if (!Permission::check('ADMIN')) {
			Requirements::javascript(CMS_TWEAKS_BASE . '/javascript/hide-error-pages.js');
		}

	}

}