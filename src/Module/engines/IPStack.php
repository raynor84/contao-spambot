<?php

/*
 * sync*gw SpamBot Bundle
 *
 * @copyright  http://syncgw.com, 2013 - 2018
 * @author     Florian Daeumling, http://syncgw.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

namespace syncgw\SpamBotBundle\Module;

class SpamBotIPStack extends SpamBot
{
    protected $Name = 'IPStack';
    protected $Fields = ['spambot_freegeoip_countries' => 1];

    /**
     * Check data.
     *
     * @param int type to check
     * @param string IP address
     * @param string mail address
     * @param mixed $typ
     * @param mixed $ip
     * @param mixed $mail
     *
     * @return array (SpamBot::Status, status message)
     */
    public function check($typ, $ip, $mail)
    {
        $this->ExtInfo = '<fieldset style="padding:3px"><div style="color:blue;">'.
                         'Checking <strong>'.$ip.'</strong> <br />';

        if (!($fp = $this->openHTTP('freegeoip.net', '/json/'.$ip))) {
            return [SpamBot::NOTFOUND, sprintf($GLOBALS['TL_LANG']['SpamBot']['generic']['err'], $this->Name, $this->ErrMsg)];
        }

        $rc = json_decode($this->readHTTP($fp));
        fclose($fp);

        foreach (['country_code', 'country_name', 'region_code', 'city', 'zipcode', 'latitude', 'longitude', 'metro_code', 'areacode'] as $k) {
            $this->ExtInfo .= $k.' = <strong>'.$rc->$k.'</strong><br />';
        }
        $this->ExtInfo .= '</div></fieldset>';

        // check for "hammed" countries
        foreach ($this->spambot_freegeoip_countries as $c) {
            if (strtoupper($c) === $rc->country_code) {
                return [SpamBot::HAM, sprintf($GLOBALS['TL_LANG']['SpamBot']['generic']['found'], $this->Name)];
            }
        }

        return [SpamBot::NOTFOUND, sprintf($GLOBALS['TL_LANG']['SpamBot']['generic']['notfound'], $this->Name)];
    }
}
