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

use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Extension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Tab;
use SilverStripe\Forms\ToggleCompositeField;
use SilverStripe\View\Requirements;

class MetadataTab extends Extension
{
    // @config
    /**
     * Add an "Advanced" tab
     *
     * @var mixed
     */
    private static $use_tab = true;

    /**
     * Advanced tab title
     *
     * @var string
     */
    private static $tab_title = 'Advanced';

    /**
     * Right-align advanced tab
     *
     * @var mixed
     */
    private static $tab_to_right = true;

    /**
     * Update "Page Title" to "Meta Title"
     *
     * @var string
     */
    private static $page_name_title = 'Meta title';

    /**
     * Meta Description title
     *
     * @var string
     */
    private static $meta_description = 'Meta description';

    /**
     * Show meta title/description character count
     *
     * @var mixed
     */
    private static $show_meta_lengths = true;

    /**
     * Move Meta Title to Advances tab for non-admin users
     *
     * @var mixed
     */
    private static $move_title_to_advanced = true;

    /**
     * Update CMS fields
     *
     * @param FieldList $fields form fields
     *
     * @return void
     */
    public function updateCMSFields(FieldList $fields)
    {
        $config = Config::inst();

        $use_tab                = $config->get(self::class, 'use_tab');
        $tab_title              = $config->get(self::class, 'tab_title');
        $tab_to_right           = $config->get(self::class, 'tab_to_right');
        $page_name_title        = $config->get(self::class, 'page_name_title');
        $move_title_to_advanced = $config->get(self::class, 'move_title_to_advanced');

        if ($config->get(self::class, 'show_meta_lengths')) {
            Requirements::javascript(
                'axllent/silverstripe-cms-tweaks: javascript/meta-stats.js'
            );
        }

        $metadata_tab = $fields->fieldByName('Root.Main.Metadata');

        if ($use_tab
            && $metadata_tab
            && $metadata_fields = $metadata_tab->FieldList()
        ) {
            $tab = $fields->findOrMakeTab('Root.' . $tab_title);

            $tab->setTitle($tab_title);
            if ($tab_to_right) {
                $tab->addExtraClass('tab-right');
            }

            $dependent_tab = $fields->findOrMakeTab('Root.Dependent');
            $tab_fields    = $dependent_tab->fields();
            if ($count = $this->owner->DependentPages()->count()) {
                $dependency_pages = ToggleCompositeField::create(
                    'Dependencies',
                    'Links to this page (' . $count . ')',
                    $tab_fields
                )->setHeadingLevel(5);
                $fields->addFieldToTab('Root.' . $tab_title, $dependency_pages);
            }
            $fields->removeByName('Dependent');

            $fields->removeFieldFromTab('Root.Main', 'Metadata');

            foreach ($metadata_fields as $f) {
                $fields->addFieldToTab('Root.' . $tab_title, $f);
            }

            $title_field = $fields->dataFieldByName('Title');

            if ($page_name_title && $title_field) {
                $title_field->setTitle($page_name_title);
            }

            // Detect whether a user is allowed to create pages in this section
            $parent = $this->owner->Parent();
            if (($parent->exists() && !$parent->canAddChildren())
                || (!$parent->exists() && !$this->owner->canCreate())
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
