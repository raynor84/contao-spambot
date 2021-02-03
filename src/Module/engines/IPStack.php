<?php
declare(strict_types=1);

/*
 * sync*gw SpamBot Bundle
 *
 * @copyright  https://syncgw.com, 2013 - 2021
 * @author     Florian Daeumling, https://syncgw.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

use syncgw\SpamBotBundle\Module\SpamBot;

class IPStack extends SpamBot {
    /*
     * @var string
     */
    protected $Name = 'IPStack';
    /*
     * @var array
     */
    protected $Fields = [ 'spambot_freegeoip_countries' => 1 ];

    /**
     * Check data
     *
     * @param type to check
     * @param IP address
     * @param mail address
     *
     * @return array (SpamBot::Status, status message)
     */
    public function check(int $typ, string $ip, string $mail): array  {

        $this->ExtInfo = '<fieldset style="padding:3px"><div style="color:blue;">'.
                         'Checking <strong>'.$ip.'</strong> <br />';

        if (!($fp = $this->openHTTP('freegeoip.net', '/json/'.$ip)))
            return [SpamBot::NOTFOUND, sprintf($GLOBALS['TL_LANG']['SpamBot']['generic']['err'], $this->Name, $this->ErrMsg)];

        $rc = json_decode($this->readHTTP($fp));
        fclose($fp);

        foreach (['country_code', 'country_name', 'region_code', 'city', 'zipcode',
        		   'latitude', 'longitude', 'metro_code', 'areacode'] as $k)
            $this->ExtInfo .= $k.' = <strong>'.$rc->$k.'</strong><br />';
        $this->ExtInfo .= '</div></fieldset>';

        // check for "hammed" countries
        foreach ($this->spambot_freegeoip_countries as $c) {
            if (strtoupper($c) === $rc->country_code)
                return [SpamBot::HAM, sprintf($GLOBALS['TL_LANG']['SpamBot']['generic']['found'], $this->Name)];
        }

        return [SpamBot::NOTFOUND, sprintf($GLOBALS['TL_LANG']['SpamBot']['generic']['notfound'], $this->Name)];
    }

}

?>