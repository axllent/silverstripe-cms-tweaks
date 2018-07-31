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
    private static $move_title_to_advanced = true;

    public function updateCMSFields(\SilverStripe\Forms\FieldList $fields)
    {
        $config = Config::inst();

        $use_tab = $config->get('Axllent\\CMSTweaks\\MetadataTab', 'use_tab');
        $tab_title = $config->get('Axllent\\CMSTweaks\\MetadataTab', 'tab_title');
        $tab_to_right = $config->get('Axllent\\CMSTweaks\\MetadataTab', 'tab_to_right');
        $page_name_title = $config->get('Axllent\\CMSTweaks\\MetadataTab', 'page_name_title');
        $move_title_to_advanced = $config->get('Axllent\\CMSTweaks\\MetadataTab', 'move_title_to_advanced');

        if ($config->get('Axllent\\CMSTweaks\\MetadataTab', 'show_meta_lengths')) {
            Requirements::javascript('axllent/silverstripe-cms-tweaks: javascript/meta-stats.js');
        }

        $metadata_tab = $fields->fieldByName('Root.Main.Metadata');

        if ($use_tab &&
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
                $dependency_pages = ToggleCompositeField::create(
                    'Dependencies',
                    'Links to this page (' . $count . ')',
                    $tab_fields
                )->setHeadingLevel(5);
                $fields->addFieldToTab('Root.' . $tab_title, $dependency_pages);
            }
            $fields->removeByName('Dependent');

            $fields->removeFieldFromTab('Root.Main', 'Metadata');
            $fields->addFieldsToTab('Root.' . $tab_title, $metadata_fields);

            $title_field = $fields->dataFieldByName('Title');

            if ($page_name_title && $title_field) {
                $title_field->setTitle($page_name_title);
            }

            // Detect whether a user is allowed to create pages in this section
            $parent = $this->owner->Parent();
            if (($parent->exists() && !$parent->canAddChildren()) ||
                (!$parent->exists() && !$this->owner->canCreate())
            ) {
                if ($title_field && $move_title_to_advanced) {
                    $meta_description = $fields->dataFieldByName('MetaDescription') ? 'MetaDescription' : false;
                    $fields->addFieldToTab('Root.' . $tab_title, $title_field, $meta_description);
                }
                $fields->removeByName('MenuTitle');
                $fields->removeByName('URLSegment');
            }
        }

        return $fields;
    }
}
