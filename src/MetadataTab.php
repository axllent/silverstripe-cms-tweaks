<?php
/**
* Metadata Tab
* ============
*
* Moves the Metadata composite field contents info its own "Advanced" tab.
*
* License: MIT-style license http://opensource.org/licenses/MIT
* Authors: Techno Joy development team (www.technojoy.co.nz)
*/

namespace Axllent\CMSTweaks;

use SilverStripe\CMS\Model\SiteTreeExtension;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\Tab;
use SilverStripe\Forms\ToggleCompositeField;

class MetadataTab extends SiteTreeExtension
{
    /* @config */
    private static $use_tab = true;
    private static $tab_title = 'Advanced';
    private static $tab_to_right = true;

    public function updateCMSFields(\SilverStripe\Forms\FieldList $fields)
    {
        $config = Config::inst();

        $use_tab = $config->get('Axllent\CMSTweaks\MetadataTab', 'use_tab');
        $tab_title = $config->get('Axllent\CMSTweaks\MetadataTab', 'tab_title');
        $tab_to_right = $config->get('Axllent\CMSTweaks\MetadataTab', 'tab_to_right');

        $metadata_tab = $fields->fieldByName('Root.Main.Metadata');

        if (
            $use_tab &&
            $metadata_tab &&
            $metadata_fields = $metadata_tab->FieldList()
        ) {
            $tab = $fields->findOrMakeTab('Root.' . $tab_title);

            $tab->setTitle($tab_title);
            if ($tab_to_right) {
                $tab->addExtraClass('pull-right');
            }

            $dependent_tab = $fields->findOrMakeTab('Root.Dependent');
            $tab_fields = $dependent_tab->fields();
            if ($count = $this->owner->DependentPages()->count()) {
                $tab->setTitle($tab_title . ' (' . $count .')');
                $dependency_pages = ToggleCompositeField::create('Dependencies', 'Links to this page (' . $count . ')',
                    $tab_fields
                )->setHeadingLevel(5);
                $fields->addFieldToTab('Root.' . $tab_title, $dependency_pages);
            }
            $fields->removeByName('Dependent');

            $fields->removeFieldFromTab('Root.Main', 'Metadata');
            $fields->addFieldsToTab('Root.' . $tab_title, $metadata_fields);

        }
        return $fields;
    }
}
