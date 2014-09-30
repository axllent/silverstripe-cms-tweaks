<?php
/**
* SiteTree Modifications for SilverStripe 3
* ==========================================
*
* A series of css & JavaScript tweaks for SilverStripe.
*
* License: MIT-style license http://opensource.org/licenses/MIT
* Authors: Techno Joy development team (www.technojoy.co.nz)
*/
class LeftAndMainCMSTweaksExt extends LeftAndMainExtension {

	public function init() {

		parent::init();

		$this->setHtmlEditorConfig();

		Requirements::css($this->ModuleBase() . '/css/cms-tweaks.css');

		Requirements::javascript($this->ModuleBase() . '/javascript/cms-tweaks.js');

		/* Remove help link */
		CMSMenu::remove_menu_item('Help');

		/* Hide "Add new" page, page Settings tab */
		if (!Permission::check('SITETREE_REORGANISE')) {
			Requirements::javascript($this->ModuleBase() . '/javascript/sitetree-noedit.js');
		}

		/* Hide all error pages in SiteTree and Files (modeladmin) */
		if (!Permission::check('ADMIN')) {
			Requirements::javascript($this->ModuleBase() . '/javascript/hide-error-pages.js');
		}

	}

	/*
	 * Set default options for TinyMCE
	 */
	public function setHtmlEditorConfig() {
		HtmlEditorConfig::get('cms')->insertButtonsAfter('pasteword', 'removeformat');
		HtmlEditorConfig::get('cms')->setOptions(array(
			/* Strip out <div> tags */
			'invalid_elements' => 'div',
			/* The "span[!class]" is to address the issue where lists get inline css style.
			See and http://martinsikora.com/how-to-make-tinymce-to-output-clean-html */
			'extended_valid_elements' => 'span[!class|!style],p[class|style]'
		));
	}

	private function ModuleBase() {
		return basename(dirname(dirname(__FILE__)));
	}

}