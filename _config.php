<?php
if (!DEFINED('CMS_TWEAKS_BASE'))
	DEFINE('CMS_TWEAKS_BASE', basename(dirname(__FILE__)));

/* Custom CMS tweaks */
Object::add_extension('LeftAndMain', 'LeftAndMainCMSTweaksExt');

Object::add_extension('SiteTree', 'CustomMetaTagsEXT');

/* Add "Remove formatting" button to TinyMCE to clean code */
HtmlEditorConfig::get('cms')->insertButtonsAfter('pasteword', 'removeformat');

HtmlEditorConfig::get('cms')->setOptions(array(
	/* Strip out <div> tags */
	'invalid_elements' => 'div',
	/* The "span[!class]" is to address the issue where lists get inline css style.
	   See and http://martinsikora.com/how-to-make-tinymce-to-output-clean-html */
	'extended_valid_elements' => 'span[!class|!style],p[class|style]'
));