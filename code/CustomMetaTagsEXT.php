<?php
/**
 * Meta Data Modifications for SilverStripe 3
 * ==========================================
 *
 * A SilerStripe extension to alter the layout of the
 * Meta data for CMS pages.
 *
 * License: MIT-style license http://opensource.org/licenses/MIT
 * Authors: Techno Joy development team (www.technojoy.co.nz)
 */

class CustomMetaTagsEXT extends SiteTreeExtension {

	public function updateCMSFields(FieldList $fields) {
		$fields->removeByName('Metadata');

		$this->owner->setField('MetaKeywords', $this->keywordsOnNewLine());

		$advancedFields = array();

		array_push($advancedFields, new HeaderField('SeoHdr', 'Search Engine Optimization', 2));
		array_push($advancedFields, new LiteralField('StatsTitle', '<p class="metastats" id="Form_EditForm_MetaTitleStats"></p>'));
		array_push($advancedFields, new TextField('MetaTitle', 'Meta Title'));
		array_push($advancedFields, new LiteralField('StatsDescription', '<p class="metastats" id="Form_EditForm_MetaDescriptionStats"></p>'));
		array_push($advancedFields, new TextareaField('MetaDescription', 'Meta Description'));
		array_push($advancedFields, new TextareaField('MetaKeywords', 'Meta Keywords'));

		$extraMeta = ToggleCompositeField::create('Metadata', 'Custom Meta Data (Advanced)',
			array(
				new LiteralField('ExtraWarning', '<p class="extrametawarning">Only edit this if you know
					exactly what you are doing as it can severely affect your webiste.</p>'),
				new TextareaField("ExtraMeta",'Custom Meta Tags')
			)
		)->setHeadingLevel(5);

		array_push($advancedFields, $extraMeta);

		if ($depTab = $fields->fieldByName('Root.Dependent')) {
			$dependencyArr = array();
			foreach ($depTab as $item)
				array_push($dependencyArr, $item);

			$dependencyPages = ToggleCompositeField::create('Dependencies', $depTab->Title(),
				$dependencyArr
			)->setHeadingLevel(5);

			array_push($advancedFields, $dependencyPages);

			$fields->removeByName('Dependent');
		}

		$fields->addFieldsToTab('Root.Advanced', $advancedFields);
		$seoTab = $fields->findOrMakeTab('Root.Advanced');
		$seoTab->addExtraClass('tab-to-right');

		return $fields;
	}

	/*
	 * Clean / parse data before saving
	 * @param null
	 * @return null
	 */
	public function onBeforeWrite() {
		parent::onBeforeWrite();
		/* Clean up meta data */
		$this->owner->MetaTitle = $this->cleadData($this->owner->MetaTitle);
		$this->owner->MetaDescription = $this->cleadData($this->owner->MetaDescription);
		$this->owner->MetaKeywords = $this->tidyKeywords($this->owner->MetaKeywords);
	}

	/*
	 * Parse the MetaKeyWords and return keywords on new lines
	 * @param null
	 * @return string
	 */
	protected function keywordsOnNewLine() {
		$str = $this->tidyKeywords($this->owner->getField('MetaKeywords'));
		$words = preg_split('/, /', $str, -1, PREG_SPLIT_NO_EMPTY);
		return implode("\n", $words);
	}

	/*
	 * Clean up data, remove double spaces and trim
	 * @param string
	 * @return string
	 */
	protected function cleadData($str) {
		$str = preg_replace('/\r?\n/', ' ', $str);
		return trim(preg_replace('/\s+/', ' ', $str));
	}

	/*
	 * Parse posted keywords, remove duplicates, and save structured data
	 * @param Order
	 * @return FieldSet
	 */
	protected function tidyKeywords($str) {
		$str = preg_replace('/\r?\n/', ',', $str);
		$words = preg_split('/,/', $str, -1, PREG_SPLIT_NO_EMPTY);
		$clean_words = array();
		$clean_words = array();
		foreach ($words as $word) {
			$new = trim($word);
			if ($new != '' && !in_array($new, $clean_words))
				array_push($clean_words, $new);
		}
		return trim(implode(', ', $clean_words));
	}


	/*
	 * Customised $CustomMetaTags
	 * Checks page value, and if not set site config
	 * @param Include Page Title
	 * @return str
	 */
	public function CustomMetaTags($includeTitle = true) {
		$tags = "";
		$Page = $this->owner;
		$SiteConfig = SiteConfig::current_site_config();
		if($includeTitle === true || $includeTitle == 'true') {
			$tags .= "\t<title>" . Convert::raw2xml(($Page->MetaTitle)
				? $Page->MetaTitle
				: $Page->Title) . "</title>\n";
		}

		$tags .= "\t<meta name=\"generator\" content=\"SilverStripe - http://silverstripe.org\" />\n";

		$charset = ContentNegotiator::get_encoding();
		$tags .= "\t<meta http-equiv=\"Content-type\" content=\"text/html; charset=$charset\" />\n";
		if($Page->MetaDescription) {
			$tags .= "\t<meta name=\"description\" content=\"" . Convert::raw2att($Page->MetaDescription) . "\" />\n";
		}
		elseif($SiteConfig->MetaDescription) {
			$tags .= "\t<meta name=\"description\" content=\"" . Convert::raw2att($SiteConfig->MetaDescription) . "\" />\n";
		}
		if($Page->MetaKeywords) {
			$tags .= "\t<meta name=\"keywords\" content=\"" . Convert::raw2att($Page->MetaKeywords) . "\" />\n";
		}
		elseif($SiteConfig->MetaKeywords) {
			$tags .= "\t<meta name=\"keywords\" content=\"" . Convert::raw2att($SiteConfig->MetaKeywords) . "\" />\n";
		}
		if($Page->ExtraMeta) {
			$tags .= "\t" . $Page->ExtraMeta . "\n";
		}

		if(Permission::check('CMS_ACCESS_CMSMain') && in_array('CMSPreviewable', class_implements($Page))) {
			$tags .= "\t<meta name=\"x-page-id\" content=\"{$Page->ID}\" />\n";
			$tags .= "\t<meta name=\"x-cms-edit-link\" content=\"" . $Page->CMSEditLink() . "\" />\n";
		}

		return trim($tags);
	}

}