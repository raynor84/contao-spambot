<?php
declare(strict_types=1);

/*
 * sync*gw SpamBot Bundle
 *
 * @copyright  https://syncgw.com, 2013 - 2023
 * @author     Florian Daeumling, https://syncgw.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

use syncgw\SpamBotBundle\Module\SpamBot;

class Blocklist extends SpamBot {
    /*
     * @var string
     */
    protected $Name = 'Blocklist';
    /*
     * @var array
     */
    protected $Fields = [ 'spambot_blocklist_mods' => 1 ];
}

?>