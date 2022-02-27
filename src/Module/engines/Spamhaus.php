<?php
declare(strict_types=1);

/*
 * sync*gw SpamBot Bundle
 *
 * @copyright  https://syncgw.com, 2013 - 2022
 * @author     Florian Daeumling, https://syncgw.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

use syncgw\SpamBotBundle\Module\SpamBot;

class Spamhaus extends SpamBot {
    /*
     * @var string
     */
    protected $Name = 'Spamhaus';
    /*
     * @var array
     */
    protected $Fields = [ 'spambot_spamhaus_mods' => 1 ];
}

?>