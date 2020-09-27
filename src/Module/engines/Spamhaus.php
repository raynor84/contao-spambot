<?php

/*
 * sync*gw SpamBot Bundle
 *
 * @copyright  http://syncgw.com, 2013 - 2020
 * @author     Florian Daeumling, http://syncgw.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

use syncgw\SpamBotBundle\Module\SpamBot;

class SpamBotSpamhaus extends SpamBot {
    protected $Name = 'Spamhaus';
    protected $Fields = ['spambot_spamhaus_mods' => 1];
}
