<?php
/**
 * Meta Data Modifications for SilverStripe 3
 * ==========================================
 *
 * A SilverStripe extension to alter the layout of the
 * Meta data for CMS pages. It also introduces MetaKeywords
 * for those still wanting them.
 *
 * License: MIT-style license http://opensource.org/licenses/MIT
 * Authors: Techno Joy development team (www.technojoy.co.nz)
 */

class MetaTagsEditorExt extends SiteTreeExtension {

	private static $db = array(
		'MetaKeywords' => 'Varchar(250)'
	);

	public function updateCMSFields(FieldList $fields) {
		$fields->removeByName('Metadata');

		/* split keywords onto new lines for easy editing */
		$this->owner->setField('MetaKeywords', $this->keywordsOnNewLine());

		$advancedFields = array();
		$ExtraMetaFields = array();

		array_push($advancedFields, new HeaderField('SeoHdr', 'Search Engine Optimization', 2));

		if (!$this->owner->canCreate()) {
			$fields->removeByName('URLSegment');
			$fields->removeByName('MenuTitle');
			$fields->removeByName('Title');
			array_push($advancedFields, new LiteralField('TitleDescription', '<p class="metatitle" id="Form_EditForm_MetaTitleStats"></p>'));
			array_push($advancedFields, new TextField('MetaTitle', 'Meta Title', $this->owner->Title));
		}

		array_push($advancedFields, new LiteralField('StatsDescription', '<p class="metastats" id="Form_EditForm_MetaDescriptionStats"></p>'));
		array_push($advancedFields,
			$metaFieldDesc = new TextareaField('MetaDescription', 'Meta Description')
		);
		/* add class to prevent newlines */
		$metaFieldDesc->addExtraClass('noenter');
		$metaFieldDesc->setRightTitle(
			_t(
				'SiteTree.METADESCHELP',
				"Search engines use this content for displaying search results."
			)
		);

		array_push($ExtraMetaFields, $metaKeywordsDesc = new TextareaField('MetaKeywords', 'Meta Keywords'));
		$metaKeywordsDesc->setRightTitle(
			_t(
				'SiteTree.METAKEYWHELP',
				"Keywords are now ignored by most of the major search engines."
			)
		);

		array_push($ExtraMetaFields, $metaFieldExtra = new TextareaField("ExtraMeta",'Custom Meta Tags'));
		$metaFieldExtra->setRightTitle(
			_t(
				'SiteTree.METAEXTRAHELP',
				"HTML tags for additional meta information. For example &lt;meta name=\"customName\" content=\"your custom content here\" /&gt;"
			)
		);


		$MetaStuff = ToggleCompositeField::create('MetaStuff', 'Additional Metadata',
			$ExtraMetaFields
		)->setHeadingLevel(5);

		array_push($advancedFields, $MetaStuff);


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
		$this->owner->MetaDescription = $this->cleanData($this->owner->MetaDescription);
		$this->owner->MetaTitle = $this->cleanData($this->owner->MetaTitle);
		$this->owner->MetaKeywords = $this->tidyKeywords($this->owner->MetaKeywords);

		/* For users who may not alter the site structure */
		if ($this->owner->MetaTitle) {
			if ($this->owner->MetaTitle != $this->owner->Title) { // Meta title has changed
				$newMenuTitle = false;

				if ($this->owner->MenuTitle == $this->owner->Title) {
					$newMenuTitle = $this->owner->MenuTitle;
				}

				$this->owner->Title = $this->owner->MetaTitle;

				if ($newMenuTitle) {
					$this->owner->setMenuTitle($newMenuTitle);
				}
			} else {
				/* Reset to null if changed back */
				$this->owner->setMenuTitle($this->owner->MenuTitle);
			}
		}
	}

	/*
	 * Clean up data by removing double spaces and trim
	 * @param string
	 * @return string
	 */
	protected function cleanData($str) {
		$str = preg_replace('/\r?\n/', ' ', $str);
		return trim(preg_replace('/\s+/', ' ', $str));
	}

	/*
	 * Parse the MetaKeywords and return keywords on new lines
	 * @param null
	 * @return string
	 */
	protected function keywordsOnNewLine() {
		$str = $this->tidyKeywords($this->owner->getField('MetaKeywords'));
		$words = preg_split('/, /', $str, -1, PREG_SPLIT_NO_EMPTY);
		return implode("\n", $words);
	}

	/*
	 * Parse posted MetaKeywords, remove duplicates, and return
	 * comma-separated data
	 * @param string
	 * @return string
	 */
	protected function tidyKeywords($str) {
		$str = preg_replace('/\r?\n/', ',', $str);
		$words = preg_split('/,/', $str, -1, PREG_SPLIT_NO_EMPTY);
		$clean_words = array();
		$clean_words = array();
		foreach ($words as $word) {
			$new = trim($word);
			if ($new != '' && !in_array($new, $clean_words)) {
				array_push($clean_words, $new);
			}
		}
		return trim(implode(', ', $clean_words));
	}

}