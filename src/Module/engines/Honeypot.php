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

class Honeypot extends SpamBot {
    /*
     * @var string
     */
    protected $Name = 'Honeypot';
    /*
     * @var array
     */
    protected $Fields = [ 'spambot_honeypot_key' => 0, 'spambot_honeypot_score' => 0 ];

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
                         'Checking <strong>'.(SpamBot::TYP_IP === $typ ? $ip : $mail).'</strong> <br />'.
                         'Clipping level is <strong>'.$this->spambot_honeypot_score.'</strong><br />';

        // query the DNS Server
        $ip = $this->spambot_honeypot_key.'.'.implode('.', array_reverse(explode('.', $ip))).'.dnsbl.httpbl.org';
        // use raw key
        $this->Raw = TRUE;
        if (is_array($rc = parent::check($typ, $ip, '')))
            return $rc;

        // we assume one only return code!
        $rc = deserialize($rc);
        $rc = explode('.', $rc[0]);

        if ('127' !== $rc[0])
            return [SpamBot::NOTFOUND, sprintf($GLOBALS['TL_LANG']['SpamBot']['generic']['err'], $this->Name, $rc[0])];

        // rc[1] = number of days since last activity

        // check threat score (this is 0 for search engines)
        if ($rc[2] < $this->spambot_honeypot_score)
            return [SpamBot::HAM, sprintf($GLOBALS['TL_LANG']['SpamBot']['Honeypot']['ham'], $this->Name, $rc[2])];

        // format error message
        $msg = NULL;
        if ($rc[3] & 0x01)
            $msg .= $GLOBALS['TL_LANG']['SpamBot']['Honeypot']['sus'].', ';
        if ($rc[3] & 0x02)
            $msg .= $GLOBALS['TL_LANG']['SpamBot']['Honeypot']['har'].', ';
        if ($rc[3] & 0x04)
            $msg .= $GLOBALS['TL_LANG']['SpamBot']['Honeypot']['com'].', ';

        return [SpamBot::SPAM, sprintf($GLOBALS['TL_LANG']['SpamBot']['Honeypot']['spam'], $this->Name, substr($msg, 0, -2), $rc[2])];
    }

}

?>