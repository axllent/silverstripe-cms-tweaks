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
     * Hide the help links in the CMS footer
     *
     * @config boolean
     */
    private static $hide_help = true;

    /**
     * Invalid CMS editor elements
     *
     * @var string
     */
    private static $invalid_elements = '';

    /**
     * Extended CMS valid editor elements
     *
     * @var string
     */
    private static $extended_valid_elements = 'span[!class|!style],p[class|style],' .
        'img[class|src|alt|title|hspace|vspace|width|height|align|name|usemap|data*],' .
        'embed[width|height|name|flashvars|src|bgcolor|align|play|loop|quality|' .
        'allowscriptaccess|type|pluginspage|autoplay]';

    /**
     * Init function
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        $config = Config::inst();

        Requirements::css(
            'axllent/silverstripe-cms-tweaks: css/cms-tweaks.css'
        );

        Requirements::javascript(
            'axllent/silverstripe-cms-tweaks: javascript/cms-tweaks.js'
        );

        if ($config->get(self::class, 'hide_help')) {
            // backwards compatibility
            CMSMenu::remove_menu_item('Help');
            // SilverStripe 4.3
            LeftAndMain::config()->update(
                'help_links',
                [
                    'CMS User help'  => '',
                    'Developer docs' => '',
                    'Community'      => '',
                    'Feedback'       => '',
                ]
            );
        }

        /* Hide "Add new" page, page Settings tab */
        if (!Permission::check('SITETREE_REORGANISE')) {
            Requirements::javascript(
                'axllent/silverstripe-cms-tweaks: javascript/sitetree-noedit.js'
            );
        }

        /* Hide all error pages in SiteTree and Files (modeladmin) */
        if (!Permission::check('ADMIN')) {
            Requirements::javascript(
                'axllent/silverstripe-cms-tweaks: javascript/hide-error-pages.js'
            );
        }
    }

    /**
     * Run after init
     *
     * @return void
     */
    public function onAfterInit()
    {
        $this->setHtmlEditorConfig();
    }

    /**
     * Set default options for TinyMCE
     * Add timestamps to included css files
     *
     * @return void
     */
    public function setHtmlEditorConfig()
    {
        HtmlEditorConfig::get('cms')->removeButtons('paste');

        $editor_options = [];

        $config = Config::inst();

        if ($invalid_elements = $config->get(self::class, 'invalid_elements')) {
            $editor_options['invalid_elements'] = $invalid_elements;
        }

        // See and http://martinsikora.com/how-to-make-tinymce-to-output-clean-html
        if ($ext_valid_els = $config->get(self::class, 'extended_valid_elements')) {
            $editor_options['extended_valid_elements'] = $ext_valid_els;
        }

        // Set editor options
        if (count($editor_options)) {
            HtmlEditorConfig::get('cms')->setOptions($editor_options);
        }

        // Add file timestamps for TinyMCE's editor_css
        $css_config = HtmlEditorConfig::get('cms')->config()->get('editor_css');
        if (!empty($css_config)) {
            $timestamped_css = [];
            $base_folder     = Director::baseFolder();
            foreach ($css_config as $file) {
                $file = $this->resolvePath($file);
                if (is_file($base_folder . '/' . $file)) {
                    array_push($timestamped_css, $file . '?m=' . filemtime($base_folder . '/' . $file));
                } else {
                    array_push($timestamped_css, $file);
                }
            }
            HtmlEditorConfig::get('cms')->config()
                ->set('editor_css', $timestamped_css);
        }

        // Add file timestamps for TinyMCE's content_css
        $css = HtmlEditorConfig::get('cms')->getOption('content_css');
        if (!empty($css)) {
            $base_folder     = Director::baseFolder();
            $timestamped_css = [];
            $regular_css     = preg_split('/,/', $css, -1, PREG_SPLIT_NO_EMPTY);
            foreach ($regular_css as $file) {
                $file = $this->resolvePath($file);
                if (is_file($base_folder . '/' . $file)) {
                    array_push(
                        $timestamped_css,
                        $file . '?m=' . filemtime($base_folder . '/' . $file)
                    );
                } else {
                    array_push($timestamped_css, $file);
                }
            }
            if (count($timestamped_css) > 0) {
                HtmlEditorConfig::get('cms')
                    ->setOption('content_css', implode(',', $timestamped_css));
            }
        }
    }

    /**
     * Expand resource path to a relative filesystem path
     * Duplicated from TinyMCEConfig::resolvePath()
     *
     * @param string $path path
     *
     * @return string
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
