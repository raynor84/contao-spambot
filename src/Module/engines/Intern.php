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

class Intern extends SpamBot {

    /*
     * @var string
     */
    protected $Name = 'Intern';

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

        // check for BlackList and WhiteList entries
        // to test backlist, add netmask 127.0.0.1/32 with type blacklist
        if (SpamBot::TYP_IP === $typ) {
            $rc = $this->Db->prepare('SELECT ip,typ FROM tl_spambot WHERE module=? AND typ & 0x%x')->execute($this->modID, SpamBot::BLACKL | SpamBot::WHITEL);
            while ($rc->next()) {
                if (self::_matchNW($ip, $rc->ip))
                    return [$rc->typ, sprintf($GLOBALS['TL_LANG']['SpamBot']['Intern']['ip'], $this->Name, $rc->ip)];
            }
        } else {
            // check for BlackList and WhiteList entries
            $rc = $this->Db->prepare('SELECT mail,typ FROM tl_spambot WHERE module=? AND typ & 0x%x')->execute($this->modID, SpamBot::BLACKL | SpamBot::WHITEL);
            while ($rc->next()) {
                if (preg_match('/'.$rc->mail.'/', $mail))
                    return [$rc->typ, sprintf($GLOBALS['TL_LANG']['SpamBot']['Intern']['mail'], $this->Name, $rc->mail)];
            }
        }

        if (SpamBot::TYP_IP === $typ)
            $rc = $this->Db->prepare('SELECT typ,status FROM tl_spambot WHERE module=? AND ip=? AND typ<>?')->execute($this->modID, $ip, SpamBot::LOADED);
        else
            $rc = $this->Db->prepare('SELECT typ,status FROM tl_spambot WHERE module=? AND mail=? AND typ<>?')->execute($this->modID, $mail, SpamBot::LOADED);

        if ($rc->numRows)
            return [$rc->typ, $rc->status ? $rc->status : $this->Name.': Record found'];

        return [SpamBot::NOTFOUND, sprintf($GLOBALS['TL_LANG']['SpamBot']['generic']['notfound'], $this->Name)];
    }

    /**
     * Match IP to network.
     *
     * range can be formatted as:
     * ip-ip (192.168.111.100-192.168.1111.103)
     * ip/mask (192.168.1.0/24)
     *
     * @param client IP
     * @param network mask
     *
     * @return FALSE=no match; TRUE=matched
     */
    private function _matchNW(string $ip, string $network): bool {
        $ip = ip2long($ip);

        if (($p = strpos($network, '-')) === FALSE) {
            list($subnet, $mask) = explode('/', $network);
            if (($ip & ~((1 << (32 - $mask)) - 1) ) == ip2long($subnet))
                return TRUE;
        } else {
            $from = ip2long(substr($network, 0, $p));
            $to   = ip2long(substr($network, $p + 1));

            return $ip >= $from && $ip <= $to;
        }

        return FALSE;
    }

}

?>