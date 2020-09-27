<?php

/*
 * sync*gw SpamBot Bundle
 *
 * @copyright  http://syncgw.com, 2013 - 2020
 * @author     Florian Daeumling, http://syncgw.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

use syncgw\SpamBotBundle\Module\SpamBot;

class SpamBotAHBL extends SpamBot {
    protected $Name = 'AHBL';
    protected $Fields = ['spambot_ahbl_mods' => 1];
}
