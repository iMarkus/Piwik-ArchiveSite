<?php
/**
*
* @author iMarkus
* @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
*
*/
namespace Piwik\Plugins\ArchiveSite;

use Exception;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Piwik\ArchiveProcessor\Rules;
use Piwik\Config;
use Piwik\Container\StaticContainer;
use Piwik\CronArchive;
use Piwik\Date;
use Piwik\Db;
use Piwik\Piwik;
use Piwik\Segment;
use Piwik\Scheduler\Scheduler;
use Piwik\Site;
use Piwik\Url;

class API extends \Piwik\Plugin\API
{
    /**
     * Get Plugin Info
     * @return array
     */
    public function getPluginInfo()
    {
        $json = file_get_contents(__DIR__.'/plugin.json');
        $json_a = json_decode($json, true);
        return $json_a;
    }
	
	/**
     * Initiates archiving process via web request.
     *
	 * @param string $idSites Comma separated list of site IDs to archive.
	 * @param string $idSegments Comma separated list of segment IDs to archive.
	 * @param string $dateRange Comma separated list of dates to archive.
	 * @param bool $disableScheduledTasks If true, scheduled tasks will be disabled.
	 * @param bool $disableSegmentsArchiving If true, segments will not be archived.
	 * 
	 * @hideExceptForSuperUser
     */
    public function runArchiving($idSites, $idSegments = '', $dateRange = '', $disableScheduledTasks = true, $disableSegmentsArchiving = false)
    {
        Piwik::checkUserHasSuperUserAccess();        
        $logger = StaticContainer::get('Psr\Log\LoggerInterface');		
        $handler = new StreamHandler('php://output', Logger::INFO);
        $handler->setFormatter(StaticContainer::get('Piwik\Plugins\Monolog\Formatter\LineMessageFormatter'));		
        $logger->pushHandler($handler);

        $archiver = new CronArchive();
        
        if (!empty($idSites) && $idSites != 'all') {
          $idSites = Site::getIdSitesFromIdSitesString($idSites);		  
          $archiver->shouldArchiveSpecifiedSites = $idSites;
        }
		
        if (!$disableSegmentsArchiving && !empty($idSegments)) {
          $idSegments = explode(',', $idSegments);
          $idSegments = array_map('trim', $idSegments);
          $archiver->setSegmentsToForceFromSegmentIds($idSegments);
        }
		
        if (!empty($dateRange)) {		  
          $archiver->restrictToDateRange = $dateRange;
        }
		
        if ($disableScheduledTasks) {		  
          $archiver->disableScheduledTasks = $disableScheduledTasks;
        }
		
        if ($disableSegmentsArchiving) {		  
          $archiver->disableSegmentsArchiving = $disableSegmentsArchiving;
        }      
		
        ob_start();		
        $archiver->main();	
        $consoleOutput = ob_get_contents();
        ob_end_clean();		
		
        return "<pre>$consoleOutput</pre>";
    }    
}
