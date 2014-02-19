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

		$advancedFields = array();

		array_push($advancedFields, new HeaderField('SeoHdr', 'Search Engine Optimization', 2));
		array_push($advancedFields, new LiteralField('StatsDescription', '<p class="metastats" id="Form_EditForm_MetaDescriptionStats"></p>'));
		array_push($advancedFields, new TextareaField('MetaDescription', 'Meta Description'));

		array_push($advancedFields,
			new LiteralField('ExtraWarning', '<p class="extrametawarning">Only edit this if you know
				exactly what you are doing as it can severely affect your webiste.</p>')
		);

		array_push($advancedFields, new TextareaField("ExtraMeta",'Custom Meta Tags'));

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
		$this->owner->MetaDescription = $this->cleadData($this->owner->MetaDescription);
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

}