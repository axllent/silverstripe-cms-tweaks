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
use SilverStripe\View\Requirements;


class MetadataTab extends SiteTreeExtension
{
    /* @config */
    private static $use_tab = true;
    private static $tab_title = 'Advanced';
    private static $tab_to_right = true;
    private static $page_name_title = 'Meta Title';
    private static $meta_description = 'Meta Description';
    private static $show_meta_lengths = true;

    public function updateCMSFields(\SilverStripe\Forms\FieldList $fields)
    {
        $config = Config::inst();

        $use_tab = $config->get('Axllent\\CMSTweaks\\MetadataTab', 'use_tab');
        $tab_title = $config->get('Axllent\\CMSTweaks\\MetadataTab', 'tab_title');
        $tab_to_right = $config->get('Axllent\\CMSTweaks\\MetadataTab', 'tab_to_right');
        $page_name_title = $config->get('Axllent\\CMSTweaks\\MetadataTab', 'page_name_title');

        if ($config->get('Axllent\\CMSTweaks\\MetadataTab', 'show_meta_lengths')) {
            Requirements::javascript($this->ModuleBase() . '/javascript/meta-stats.js');
        }

        $metadata_tab = $fields->fieldByName('Root.Main.Metadata');

        if (
            $use_tab &&
            $metadata_tab &&
            $metadata_fields = $metadata_tab->FieldList()
        ) {
            $tab = $fields->findOrMakeTab('Root.' . $tab_title);

            $tab->setTitle($tab_title);
            if ($tab_to_right) {
                $tab->addExtraClass('tab-right');
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

            if ($page_name_title && $title = $fields->dataFieldByName('Title')) {
                $title->setTitle($page_name_title);
            }

        }
        return $fields;
    }

    private function ModuleBase()
    {
        return basename(dirname(dirname(__FILE__)));
    }
}
