<?php

namespace Axllent\CMSTweaks;

use SilverStripe\CMS\Model\SiteTreeExtension;
use SilverStripe\Forms\Tab;

class SEOTab extends SiteTreeExtension
{
    private $advanced_tab = true;
    private $advanced_tab_to_right = true;

    public function updateCMSFields(\SilverStripe\Forms\FieldList $fields)
    {
        if (
            $this->advanced_tab &&
            $metadataField = $fields->fieldByName('Root.Main.Metadata')->FieldList()
        ) {
            $fields->removeFieldFromTab('Root.Main', 'Metadata');
            $tab = $fields->findOrMakeTab('Root.SEO');
            $tab->setTitle('SEO');
            if ($this->advanced_tab_to_right) {
                $tab->addExtraClass('pull-right');
            }
            $fields->addFieldsToTab('Root.SEO', $metadataField);
        }
        return $fields;
    }
}
