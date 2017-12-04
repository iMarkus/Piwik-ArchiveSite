<?php
/**
 *
 * @author iMarkus
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\ArchiveSite;

use Piwik\Common;
use Piwik\Container\StaticContainer;
use Psr\Log\LoggerInterface;
use Piwik\Plugin;
use Exception;

class ArchiveSite extends \Piwik\Plugin
{
    /**
     * @see Piwik_Plugin::getListHooksRegistered
     */
    public function getListHooksRegistered()
    {
        return array(
            'AssetManager.getJavaScriptFiles'        => 'getJsFiles',
            'AssetManager.getStylesheetFiles'        => 'getStylesheetFiles',
            'Translate.getClientSideTranslationKeys' => 'getClientSideTranslationKeys'
        );
    }
	
	/**
     * Plugin activate
     * 
     */
    public function activate()
    {
	    $logger = StaticContainer::get('Psr\Log\LoggerInterface');
	    $logger->info('Plugin ArchiveSite activated');
    }

	/**
     * Plugin deactivate
     * 
     */
    public function deactivate()
    {
        $logger = StaticContainer::get('Psr\Log\LoggerInterface');
        $logger->info('Plugin ArchiveSite deactivated.');
    }
	
	/**
     * Adds required JS files
     * @param $jsFiles
     */
    public function getJsFiles(&$jsFiles)
    {
        $jsFiles[] = "plugins/ArchiveSite/javascripts/archivesite.controller.js";
    }
	
	/**
     * Adds required CSS files
     * @param $stylesheets
     */
    public function getStylesheetFiles(&$stylesheets)
    {
        $stylesheets[] = "plugins/ArchiveSite/stylesheets/styles.less";
    }

    /**
     * Adds translation keys required in JS
     * @param $translationKeys
     */
    public function getClientSideTranslationKeys(&$translationKeys)
    {
        $translationKeys[] = "ArchiveSite_AllSegments";
        $translationKeys[] = "ArchiveSite_ArchiveSuccess";
        $translationKeys[] = "ArchiveSite_ArchiveAPIReturn";
    }
}
