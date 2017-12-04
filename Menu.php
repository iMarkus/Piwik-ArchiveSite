<?php
/**
 *
 * @author iMarkus
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\ArchiveSite;

use Piwik\Menu\MenuAdmin;
use Piwik\Piwik;

class Menu extends \Piwik\Plugin\Menu
{
    public function configureAdminMenu(MenuAdmin $menu)
    {
        if (Piwik::hasUserSuperUserAccess()) {
            $menu->addSystemItem(
                'ArchiveSite_ArchiveSite',
                $this->urlForAction('index'),
                $order = 11
            );
        }
    }
}