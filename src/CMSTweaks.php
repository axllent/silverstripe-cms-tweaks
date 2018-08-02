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
use SilverStripe\Admin\LeftAndMain;
use SilverStripe\Admin\LeftAndMainExtension;
use SilverStripe\Control\Director;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Manifest\ModuleLoader;
use SilverStripe\Forms\HTMLEditor\HtmlEditorConfig;
use SilverStripe\Security\Permission;
use SilverStripe\View\Requirements;

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

        Requirements::css('axllent/silverstripe-cms-tweaks: css/cms-tweaks.css');

        Requirements::javascript('axllent/silverstripe-cms-tweaks: javascript/cms-tweaks.js');

        if ($config->get('Axllent\\CMSTweaks\\CMSTweaks', 'hide_help')) {
            CMSMenu::remove_menu_item('Help');
        }

        /* Hide "Add new" page, page Settings tab */
        if (!Permission::check('SITETREE_REORGANISE')) {
            Requirements::javascript('axllent/silverstripe-cms-tweaks: javascript/sitetree-noedit.js');
        }

        /* Hide all error pages in SiteTree and Files (modeladmin) */
        if (!Permission::check('ADMIN')) {
            Requirements::javascript('axllent/silverstripe-cms-tweaks: javascript/hide-error-pages.js');
        }
    }

    public function onAfterInit()
    {
        $this->setHtmlEditorConfig();
    }

    /*
     * Set default options for TinyMCE
     * Add timestamps to included css files
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

        /* Add file timestamps for TinyMCE's editor_css */
        $css_config = HtmlEditorConfig::get('cms')->config()->get('editor_css');
        if (!empty($css_config)) {
            $timestamped_css = [];
            $base_folder = Director::baseFolder();
            foreach ($css_config as $file) {
                $file = $this->resolvePath($file);
                if (is_file($base_folder . '/' . $file)) {
                    array_push($timestamped_css, $file . '?m=' . filemtime($base_folder . '/' . $file));
                } else {
                    array_push($timestamped_css, $file);
                }
            }
            HtmlEditorConfig::get('cms')->config()->set('editor_css', $timestamped_css);
        }

        /* Add file timestamps for TinyMCE's content_css */
        $css = HtmlEditorConfig::get('cms')->getOption('content_css');
        if (!empty($css)) {
            $base_folder = Director::baseFolder();
            $timestamped_css = [];
            $regular_css = preg_split('/,/', $css, -1, PREG_SPLIT_NO_EMPTY);
            foreach ($regular_css as $file) {
                $file = $this->resolvePath($file);
                if (is_file($base_folder . '/' . $file)) {
                    array_push($timestamped_css, $file . '?m=' . filemtime($base_folder . '/' . $file));
                } else {
                    array_push($timestamped_css, $file);
                }
            }
            if (count($timestamped_css) > 0) {
                HtmlEditorConfig::get('cms')->setOption('content_css', implode(',', $timestamped_css));
            }
        }
    }

    /**
     * Expand resource path to a relative filesystem path
     *
     * @param string $path
     * @return string
     * Duplicated from TinyMCEConfig::resolvePath()
     */
    protected function resolvePath($path)
    {
        if (preg_match('#(?<module>[^/]+/[^/]+)\s*:\s*(?<path>[^:]+)#', $path, $results)) {
            $module = ModuleLoader::getModule($results['module']);
            if ($module) {
                return $module->getRelativeResourcePath($results['path']);
            }
        }
        return $path;
    }
}
