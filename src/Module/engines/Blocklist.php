<?php

/*
 * sync*gw SpamBot Bundle
 *
 * @copyright  http://syncgw.com, 2013 - 2020
 * @author     Florian Daeumling, http://syncgw.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

use syncgw\SpamBotBundle\Module\SpamBot;

class SpamBotBlocklist extends SpamBot {
    protected $Name = 'Blocklist';
    protected $Fields = ['spambot_blocklist_mods' => 1];
}
