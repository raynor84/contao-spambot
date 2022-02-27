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

class SORBS extends SpamBot {
    /*
     * @var string
     */
    protected $Name = 'SORBS';
    /*
     * @var array
     */
    protected $Fields = [ 'spambot_sorbs_mods' => 1 ];
}

?>