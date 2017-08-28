<?php
/**
* CMS Tweaks for SilverStripe 4
* =============================
*
* A series of css & JavaScript tweaks for SilverStripe.
*
* License: MIT-style license http://opensource.org/licenses/MIT
* Authors: Techno Joy development team (www.technojoy.co.nz)
*/

namespace Axllent\CMSTweaks;

use SilverStripe\Admin\CMSMenu;
use SilverStripe\Core\Config\Config;
use SilverStripe\Admin\LeftAndMainExtension;
use SilverStripe\Admin\LeftAndMain;
use SilverStripe\Control\Director;
use SilverStripe\View\Requirements;
use SilverStripe\Security\Permission;
use SilverStripe\Forms\HTMLEditor\HtmlEditorConfig;

class CMSTweaks extends LeftAndMainExtension
{

    /**
     * @config boolean
     */
    private static $hide_help = true;

    public function init()
    {
        parent::init();

        $config = Config::inst();

		LeftAndMain::config()->update('application_link', Director::baseURL());

		Requirements::css($this->ModuleBase() . '/css/cms-tweaks.css');

		Requirements::javascript($this->ModuleBase() . '/javascript/cms-tweaks.js');

        if ($config->get('Axllent\\CMSTweaks\\CMSTweaks', 'hide_help')) {
            CMSMenu::remove_menu_item('Help');
        }

		/* Hide "Add new" page, page Settings tab */
        if (!Permission::check('SITETREE_REORGANISE')) {
            Requirements::javascript($this->ModuleBase() . '/javascript/sitetree-noedit.js');
        }

		/* Hide all error pages in SiteTree and Files (modeladmin) */
        if (!Permission::check('ADMIN')) {
            Requirements::javascript($this->ModuleBase() . '/javascript/hide-error-pages.js');
        }

        // /* Add file timestamps for TinyMCE's content_css */
        // $css = HtmlEditorConfig::get('cms')->getOption('content_css');
        // if ($css) {
        //     $base_folder = Director::baseFolder();
        //     $timestamped_css = array();
        //     $regular_css = preg_split('/,/', $css, -1, PREG_SPLIT_NO_EMPTY);
        //     foreach ($regular_css as $file) {
        //         if (is_file($base_folder . '/' . $file)) {
        //             array_push($timestamped_css, $file . '?m=' . filemtime($base_folder . '/' . $file));
        //         }
        //     }
        //     if (count($timestamped_css > 0)) {
        //         HtmlEditorConfig::get('cms')->setOption('content_css', implode(',', $timestamped_css));
        //     }
        // }
    }

    /* needs to be onBeforeInit() to get called before cms load */
    public function onBeforeInit()
    {
        $this->setHtmlEditorConfig();
    }

    /*
     * Set default options for TinyMCE
     */
    public function setHtmlEditorConfig()
    {
        HtmlEditorConfig::get('cms')->insertButtonsAfter('paste', 'removeformat');
        HtmlEditorConfig::get('cms')->removeButtons('paste');
        HtmlEditorConfig::get('cms')->setOptions(array(
            /* Strip out <div> tags */
            'invalid_elements' => 'div',
            /* The "span[!class]" is to address the issue where lists get inline css style.
            See and http://martinsikora.com/how-to-make-tinymce-to-output-clean-html */
            'extended_valid_elements' => 'span[!class|!style],p[class|style]'
       ));
        // HtmlEditorConfig::get('cms')->
    }

    private function ModuleBase()
    {
        return basename(dirname(dirname(__FILE__)));
    }
}
