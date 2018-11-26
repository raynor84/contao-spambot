<?php

/*
 * sync*gw SpamBot Bundle
 *
 * @copyright  http://syncgw.com, 2013 - 2018
 * @author     Florian Daeumling, http://syncgw.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

namespace syncgw\SpamBotBundle\Module;

use Contao\Controller;

class SpamBotCron extends Controller
{
    // k pointer
    public $Db;

    /**
     * Initialize class.
     */
    public function __construct()
    {
        parent::__construct();
        $this->Db = \Contao\Database::getInstance();
    }

    /**
     * Clear cached data.
     */
    public function clearCache()
    {
        $this->loadLanguageFile('default');
        $rc = $this->Db->prepare('SELECT id,name,spambot_engines,spambot_internal_exp from tl_module WHERE type LIKE ?')->execute('SpamBot-%');
        // allow loaded records to survive
        $mods = [0];
        // walk through all modules
        while ($rc->next()) {
            if (!in_array('Intern', deserialize($rc->spambot_engines), true)) {
                continue;
            }
            $mods[] = $rc->id;
            $sql = sprintf('FROM tl_spambot WHERE typ & 0x%x AND tstamp < %s AND module=%s',
                           SpamBot::SPAM | SpamBot::HAM, time() - ($rc->spambot_internal_exp * 86400), $rc->id);
            $cnt = $this->Db->execute('SELECT id '.$sql);
            $this->Db->execute('DELETE '.$sql);
            $this->log(sprintf($GLOBALS['TL_LANG']['SpamBot']['cron']['rec'], $cnt->numRows, $rc->name), 'SpamBot', TL_CRON);
        }
        // delete all unassigned records
        $sql = sprintf('FROM tl_spambot WHERE INSTR(\'%s\',module) = 0', implode(',', $mods));
        $cnt = $this->Db->execute('SELECT id '.$sql);
        $this->Db->execute('DELETE '.$sql);
        $this->log(sprintf($GLOBALS['TL_LANG']['SpamBot']['cron']['mod'], $cnt->numRows), 'SpamBot', TL_CRON);
    }

    /**
     * Load data from external source.
     */
    public function loadData()
    {
        $this->loadLanguageFile('default');
        $rc = $this->Db->prepare('SELECT id,name,type,spambot_engines from tl_module WHERE type LIKE ?')->execute('SpamBot-%');
        $mods = [];
        // walk through all modules
        while ($rc->next()) {
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
