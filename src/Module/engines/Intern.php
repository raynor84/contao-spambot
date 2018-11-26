<?php

/*
 * sync*gw SpamBot Bundle
 *
 * @copyright  http://syncgw.com, 2013 - 2018
 * @author     Florian Daeumling, http://syncgw.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

namespace syncgw\SpamBotBundle\Module;

class SpamBotIntern extends SpamBot
{
    protected $Name = 'Intern';

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
        // check for BlackList and WhiteList entries
        if (SpamBot::TYP_IP === $typ) {
            $rc = $this->Db->prepare('SELECT ip,typ FROM tl_spambot WHERE module=? AND typ & 0x%x')->execute($this->modID, SpamBot::BLACKL | SpamBot::WHITEL);
            while ($rc->next()) {
                if (self::_matchNW($ip, $rc->ip)) {
                    return [$rc->typ, sprintf($GLOBALS['TL_LANG']['SpamBot']['Intern']['ip'], $this->Name, $rc->ip)];
                }
            }
        } else {
            // check for BlackList and WhiteList entries
            $rc = $this->Db->prepare('SELECT mail,typ FROM tl_spambot WHERE module=? AND typ & 0x%x')->execute($this->modID, SpamBot::BLACKL | SpamBot::WHITEL);
            while ($rc->next()) {
                if (preg_match('/'.$rc->mail.'/', $mail)) {
                    return [$rc->typ, sprintf($GLOBALS['TL_LANG']['SpamBot']['Intern']['mail'], $this->Name, $rc->mail)];
                }
            }
        }

        if (SpamBot::TYP_IP === $typ) {
            $rc = $this->Db->prepare('SELECT typ,status FROM tl_spambot WHERE module=? AND ip=? AND typ<>?')->execute($this->modID, $ip, SpamBot::LOADED);
        } else {
            $rc = $this->Db->prepare('SELECT typ,status FROM tl_spambot WHERE module=? AND mail=? AND typ<>?')->execute($this->modID, $mail, SpamBot::LOADED);
        }

        if ($rc->numRows) {
            return [$rc->typ, $rc->status ? $rc->status : $this->Name.': Record found'];
        }

        return [SpamBot::NOTFOUND, sprintf($GLOBALS['TL_LANG']['SpamBot']['generic']['notfound'], $this->Name)];
    }

    /**
     * Match IP to network.
     *
     * range can be formatted as:
     * ip-ip (192.168.111.100-192.168.1111.103)
     * ip/mask (192.168.1.0/24)
     *
     * @param string client IP
     * @param string network mask
     * @param mixed $ip
     * @param mixed $network
     *
     * @return false=no match; true=matched
     */
    private function _matchNW($ip, $network)
    {
        $ip = ip2long($ip);
        if (false === ($p = strpos($network, '-'))) {
            $a = explode('/', $network);
            if (isset($a[1])) {
                $nwl = ip2long($a[0]);
                $x = ip2long($a[1]);
                $m = long2ip($x) === $a[1] ? $x : (0xffffffff << (32 - $a[1]));

                return ($ip & $a[0]) === ($nwl & $m);
            }
        } else {
            $from = ip2long(substr($network, 0, $p));
            $to = ip2long(substr($network, $p + 1));

            return $ip >= $from && $ip <= $to;
        }

        return false;
    }
}
