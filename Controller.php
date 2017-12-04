<?php
/**
 *
 * @author iMarkus
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\ArchiveSite;

use Piwik\API\Request;
use Piwik\Common;
use Piwik\Period\Range;
use Piwik\Piwik;
use Piwik\Site;
use Piwik\View;

/**
 *
 */
class Controller extends \Piwik\Plugin\ControllerAdmin
{
    public function index()
    {
        Piwik::checkUserHasSuperUserAccess();

        $view = new View('@ArchiveSite/admin');
        $this->setBasicVariablesView($view);

        return $view->render();
    }

    public function archiveSite()
    {
        Piwik::checkUserHasSuperUserAccess();

        $siteIds                  = Common::getRequestVar('idSites', '', 'string');
        $segmentIds               = Common::getRequestVar('idSegments', '', 'string');
        $dateRange                = Common::getRequestVar('dateRange', '', 'string');
        $disableScheduledTasks    = Common::getRequestVar('disableScheduledTasks', '', 'int');
        $disableSegmentsArchiving = Common::getRequestVar('disableSegmentsArchiving', '', 'int');
		
        return Request::processRequest('ArchiveSite.runArchiving', [
			'format'                   => 'json',
			'idSites'                  => $siteIds,
			'idSegments'               => $segmentIds,
			'dateRange'                => $dateRange,
			'disableScheduledTasks'    => $disableScheduledTasks,
			'disableSegmentsArchiving' => $disableSegmentsArchiving
        ]);
    }
}
