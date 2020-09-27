<?php

/*
 * sync*gw SpamBot Bundle
 *
 * @copyright  http://syncgw.com, 2013 - 2020
 * @author     Florian Daeumling, http://syncgw.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

namespace syncgw\SpamBotBundle\Module;

use Psr\Log\LogLevel;
use Terminal42\ServiceAnnotationBundle\ServiceAnnotationInterface;
use Contao\System;
use Contao\CoreBundle\Monolog\ContaoContext;
use Doctrine\DBAL\Connection;

class SpamBotCron implements ServiceAnnotationInterface  {

    // database pointer
    private $db;

    /**
     * Initialize class
     */
    public function __construct(Connection $connection) {
		$this->db = $connection;
    }

    /**
     * Clear cached data.
     */
    public function clearCache() {

        System::loadLanguageFile('default');

        $rc = $this->db->prepare('SELECT id,name,spambot_engines,spambot_internal_exp from tl_module WHERE type LIKE ?')->execute('SpamBot-%');
        // allow loaded records to survive
        $mods = [0];

        // walk through all modules
        while ($rc && $rc->next()) {
            if (!in_array('Intern', deserialize($rc->spambot_engines), true))
                continue;
            $mods[] = $rc->id;
            $sql = sprintf('FROM tl_spambot WHERE typ & 0x%x AND tstamp < %s AND module=%s',
                           SpamBot::SPAM | SpamBot::HAM, time() - ($rc->spambot_internal_exp * 86400), $rc->id);
            $cnt = $this->db->execute('SELECT id '.$sql);
            $this->db->execute('DELETE '.$sql);
        	System::getContainer()->get('monolog.logger.contao')->log(LogLevel::INFO,
        			sprintf($GLOBALS['TL_LANG']['SpamBot']['cron']['rec'], $cnt->numRows, $rc->name),
      				[ 'contao' => new ContaoContext(__CLASS__.'::'.__FUNCTION__, TL_ERROR ) ] );
        }
        // delete all unassigned records
        $sql = sprintf('FROM tl_spambot WHERE INSTR(\'%s\',module) = 0', implode(',', $mods));
        $cnt = $this->db->execute('SELECT id '.$sql);
        $this->db->execute('DELETE '.$sql);
        	System::getContainer()->get('monolog.logger.contao')->log(LogLevel::INFO,
        			sprintf($GLOBALS['TL_LANG']['SpamBot']['cron']['mod'], $cnt->numRows),
      				[ 'contao' => new ContaoContext(__CLASS__.'::'.__FUNCTION__, TL_ERROR ) ] );
    }

    /**
     * Load data from external source.
     */
    public function loadData() {

    	System::loadLanguageFile('default');

        $rc = $this->db->prepare('SELECT id,name,type,spambot_engines from tl_module WHERE type LIKE ?')->execute('SpamBot-%');
        $mods = [];

        // walk through all modules
        while (!is_bool($rc) && $rc->next()) {
            foreach (deserialize($rc->spambot_engines) as $name) {
                if (isset($GLOBALS['SpamBot']['Engines'][$name]['CronJob'])) {
                    if (!isset($mods[$GLOBALS['SpamBot']['Engines'][$name]['CronJob'][0]])) {
                        require_once TL_ROOT.'/vendor/syncgw/contao-spambot/src/Module/Engine/'.$name.'.php';
                        $call = new $GLOBALS['SpamBot']['Engines'][$name]['CronJob'][0](null);
                        $mods[$GLOBALS['SpamBot']['Engines'][$name]['CronJob'][0]] = $call;
                        $call->$GLOBALS['SpamBot']['Engines'][$name]['CronJob'][1]($rc->id, $rc->name);
                    }
                }
            }
        }
    }

}
