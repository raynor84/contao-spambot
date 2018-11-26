<?php

/*
 * sync*gw SpamBot Bundle
 *
 * @copyright  http://syncgw.com, 2013 - 2018
 * @author     Florian Daeumling, http://syncgw.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

namespace syncgw\SpamBotBundle\Module;

class SpamBotSpamhaus extends SpamBot
{
    protected $Name = 'Spamhaus';
    protected $Fields = ['spambot_spamhaus_mods' => 1];
}
