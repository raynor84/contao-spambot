<?php
declare(strict_types=1);

/*
 * sync*gw SpamBot Bundle
 *
 * @copyright  https://syncgw.com, 2013 - 2022
 * @author     Florian Daeumling, https://syncgw.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

namespace syncgw\SpamBotBundle\Module;

use Psr\Log\LogLevel;
use Contao\System;
use Contao\CoreBundle\Monolog\ContaoContext;
use Doctrine\DBAL\Connection;

class SpamBotCron {

    /*
     * database pointer
     * @var Doctrine\DBAL\Connection
     */
    private $_db;

    /**
     * Initialize class
     */
    public function __construct(Connection $connection) {
		$this->_db = $connection;
    }

    /**
     * Clear cached data.
     */
    public function clearCache(): void {

        System::loadLanguageFile('default');

        $rc = $this->_db->prepare('SELECT id,name,spambot_engines,spambot_internal_exp from tl_module WHERE type LIKE ?')->execute([ 'SpamBot-%' ]);
        // allow loaded records to survive
        $mods = [0];

        // walk through all modules
        while ($rc && $rc->next()) {
            if (!in_array('Intern', deserialize($rc->spambot_engines), TRUE))
                continue;
            $mods[] = $rc->id;
            $sql = sprintf('FROM tl_spambot WHERE typ & 0x%x AND tstamp < %s AND module=%s',
                           SpamBot::SPAM | SpamBot::HAM, time() - ($rc->spambot_internal_exp * 86400), $rc->id);
            $cnt = $this->_db->execute('SELECT id '.$sql);
            $this->_db->execute('DELETE '.$sql);
        	System::getContainer()->get('monolog.logger.contao')->log(LogLevel::INFO,
        			sprintf($GLOBALS['TL_LANG']['SpamBot']['cron']['rec'], $cnt->numRows, $rc->name),
      				[ 'contao' => new ContaoContext(__CLASS__.'::'.__FUNCTION__, TL_ERROR ) ] );
        }
        // delete all unassigned records
        $sql = sprintf('FROM tl_spambot WHERE INSTR(\'%s\',module) = 0', implode(',', $mods));
        $cnt = $this->_db->execute('SELECT id '.$sql);
        $this->_db->execute('DELETE '.$sql);
        	System::getContainer()->get('monolog.logger.contao')->log(LogLevel::INFO,
        			sprintf($GLOBALS['TL_LANG']['SpamBot']['cron']['mod'], $cnt->numRows),
      				[ 'contao' => new ContaoContext(__CLASS__.'::'.__FUNCTION__, TL_ERROR ) ] );
    }

    /**
     * Load data from external source.
     */
    public function loadData(): void {

    	System::loadLanguageFile('default');

        $rc = $this->_db->prepare('SELECT id,name,type,spambot_engines from tl_module WHERE type LIKE ?')->execute([ 'SpamBot-%' ]);
        $mods = [];

        // walk through all modules
        while (!is_bool($rc) && $rc->next()) {
            foreach (deserialize($rc->spambot_engines) as $name) {
                if (isset($GLOBALS['SpamBot']['Engines'][$name]['CronJob'])) {
                    if (!isset($mods[$GLOBALS['SpamBot']['Engines'][$name]['CronJob'][0]])) {
                        require_once TL_ROOT.'/vendor/syncgw/contao-spambot/src/Module/Engine/'.$name.'.php';
                        $call = new $GLOBALS['SpamBot']['Engines'][$name]['CronJob'][0](NULL);
                        $mods[$GLOBALS['SpamBot']['Engines'][$name]['CronJob'][0]] = $call;
                        $call->$GLOBALS['SpamBot']['Engines'][$name]['CronJob'][1]($rc->id, $rc->name);
                    }
                }
            }
        }
    }

}

?>
