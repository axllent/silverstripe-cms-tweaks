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

        if (
            $use_tab &&
            $metadataField = $fields->fieldByName('Root.Main.Metadata')->FieldList()
        ) {
            $fields->removeFieldFromTab('Root.Main', 'Metadata');
            $tab = $fields->findOrMakeTab('Root.' . $tab_title);
            $tab->setTitle($tab_title);
            if ($tab_to_right) {
                $tab->addExtraClass('pull-right');
            }
            $fields->addFieldsToTab('Root.' . $tab_title, $metadataField);
        }
        return $fields;
    }
}
