<?php
declare(strict_types=1);

/*
 * sync*gw SpamBot Bundle
 *
 * @copyright  https://syncgw.com, 2013 - 2023
 * @author     Florian Daeumling, https://syncgw.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

namespace syncgw\SpamBotBundle\Module;

use Contao\Validator;
use Contao\Widget;

class SpamBotMod extends SpamBot {

    /*
     * @array
     */
    protected $Fields = ['spambot_page' => 0, 'spambot_msg' => 0, 'spambot_mod' => 0,
                          'spambot_internal_logspam' => 0, 'spambot_internal_logham' => 0, ];

    /**
     * Wipe everything from template except our error message - called during FE template processing
     *
     * @param  content
     * @param  template name
     *
     * @return string content
     */
    public function clearTemplate(string $strContent, $strTemplate): string {

        $str = [];
        if ($GLOBALS['SpamBot']['Catch'] & (SpamBot::SPAM | SpamBot::BLACKL) && 'mod_article' === $strTemplate) {
            preg_match('/(?<=<!-- SpamBot::start -->).*(?=<!-- SpamBot::end -->)/s', $strContent, $str);
            // clear flag
            unset($GLOBALS['SpamBot']['Catch']);
            return $str[0];
        }

        return $strContent;
    }

    /**
     * Replace insert tags - called during processing of insert tags
     *
     * @param  insert tag
     * @param  $strTag
     *
     * @return string replacement
     */
    public function replaceInsertTag(string $strTag) {

        $parm = explode('::', $strTag);
        // are we responsible?
        if ('spambot' !== strtolower($parm[0]))
            return FALSE;

        // get information
        if (!is_array($arr = deserialize(base64_decode($this->Input->cookie('SpamBot'), TRUE))))
            $arr = [];

        return isset($arr[$parm[1]]) ? $arr[$parm[1]] : '';
    }

    /**
     * Check special regular expression - called during processing of textfields in any forms
     *
     * @param rgxp name
     * @param e-mail
     * @param $obj
     *
     * @return bool
     */
    public function checkMail(string $strRegexp, string $email, Widget $obj): bool {
        global $objPage;

        // are we responsible?
        if ('rgxSpamBots' !== $strRegexp)
            return FALSE;

        // standard e-mail validation
        if (!Validator::isEmail($email))
            $obj->addError($GLOBALS['TL_LANG']['ERR']['email']);

        // check for active module included in this page
        $rc = $this->Db->prepare(
                    'SELECT c.id FROM tl_article a LEFT JOIN tl_content b ON (b.pid=a.id) '.
                    'LEFT JOIN tl_module c ON (c.id=b.module) WHERE '.
                    'a.pid=? AND b.invisible<>1 AND c.type=?')
                    ->execute($objPage->id, 'SpamBot-Mail');

        // if not on page check layout
        if (!$rc->numRows) {
            // get list of SpamBot-Mail modules
            $mods = $this->Db->prepare('SELECT id FROM tl_module WHERE type=?')->execute('SpamBot-Mail');
            $mods = is_array($mods->id) ? $mods->id : [$mods->id];
            // clear module ID
            $this->modID = 0;
            // check for modules included in page layout
            $rc = $this->Db->prepare('SELECT modules FROM tl_layout WHERE id=?')->execute($objPage->layout);
            if ($rc->modules) {
                foreach (deserialize($rc->modules) as $v) {
                    foreach ($mods as $mid) {
                        if ($mid === $v['mod']) {
                            $this->modID = $mid;
                            break;
                        }
                    }
                    if ($this->modID)
                        break;
                }
            }
            // not in page and not in layout :-(
            if (!$this->modID)
                return TRUE;
        } else
            $this->modID = $rc->id;

        list($ptyp, , ) = self::_doCheck(SpamBot::TYP_MAIL, self::getIP(), $email);

        // display user error message
        if ($ptyp & (SpamBot::SPAM | SpamBot::BLACKL))
            $obj->addError($GLOBALS['TL_LANG']['SpamBot']['SpamBot']['email']);

        return TRUE;
    }

    /**
     * Check IP address - called by ModuleSpamBotIP
     *
     * @param client IP
     *
     * @return array (SpamBot::Status, status message, execution time)
     */
    public function checkIP(string $ip): array {
        return self::_doCheck(SpamBot::TYP_IP, $ip, '');
    }

    /**
     * Get IP address
     *
     * @return string
     */
    public function getIP(): string {

        $ip = [];
        foreach (['HTTP_CF_CONNECTING_IP', 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED',
                        'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR', ] as $key) {
            if (TRUE === array_key_exists($key, $_SERVER)) {
                foreach (explode(',', $_SERVER[$key]) as $vip)
                    $ip[] = str_replace(' ', '', $vip);
            }
        }
        // if for some strange reason we don't get an IP we return imemdiately with 0.0.0.0
        if (empty($ip))
            $ip = '0.0.0.0';
        else {
            $ip = array_values(array_unique($ip));
            foreach ($ip as $v) {
                if (filter_var($v, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                    $ip = $v;
                    break;
                }
            }
        }

        return is_array($ip) ? $ip[0] : $ip;
    }

    /**
     * Perform check
     *
     * @param function to call
     * @param IP address
     * @param email address
     *
     * @return array (SpamBot::Status, status message, execution time)
     */
    private function _doCheck(int $func, string $ip, string $mail): array {

        // return value is undefined
        $rc = NULL;

        // check all engines
        $en = deserialize(parent::callMods($func, $ip, $mail, intval($this->spambot_mod)));

        // check for WHITE and BLACK list?
        if (SpamBot::MOD_FIRST !== $this->spambot_mod) {
            foreach ($en as $v) {
                if (SpamBot::NOTFOUND === $v[0])
                    continue;

                // check type
                if ($v[0] & (SpamBot::BLACKL | SpamBot::WHITEL)) {
                    $rc = $v;
                    break;
                }
            }
        }

        // check all other returned values
        if (!$rc) {
            foreach ($en as $v) {
                if (SpamBot::NOTFOUND === $v[0])
                    continue;

                // check modus
                switch ($this->spambot_mod) {
                case SpamBot::MOD_FIRST:
                    if (!($v[0] & SpamBot::NOTFOUND))
                        $rc = $v;
                    break;

                case SpamBot::MOD_SPAM:
                    if ($v[0] & SpamBot::SPAM)
                        $rc = $v;
                    break;

                case SpamBot::MOD_HAM:
                default:
                    if ($v[0] & SpamBot::HAM)
                        $rc = $v;
                    break;
                }

                // anything found
                if ($rc)
                    break;
            }
        }

        // default to "Ham" (if not found)
        if (!$rc || $rc[0] & SpamBot::NOTFOUND) {
            $rc[0] = SpamBot::HAM;
            $rc[1] = 'Default: Ham';
        }

        // convert LOADED
        if ($rc[0] & SpamBot::LOADED)
            $rc[0] = SpamBot::SPAM;

        // special Intern check
        if (($rc[0] & (SpamBot::WHITEL | SpamBot::BLACKL)) ||
            (($rc[0] & SpamBot::HAM) && !$this->spambot_internal_logham) ||
            (($rc[0] & SpamBot::SPAM) && !$this->spambot_internal_logspam))
            return $rc;

        // clean status message
        $rc[1] = strip_tags($rc[1]);

        // update internal data base
        $upd = time();
        if (SpamBot::TYP_IP === $func) {
            $rec = $this->Db->prepare('SELECT id FROM tl_spambot WHERE module=? AND ip=? AND typ<>?')
                                  ->execute($this->modID, $ip, SpamBot::LOADED);
            if ($rec->numRows)
                $this->Db->prepare('UPDATE tl_spambot SET module=?, tstamp=?, browser=?, typ=?, status=? WHERE id=?')
                               ->execute($this->modID, $upd, \Environment::get('httpUserAgent'), $rc[0], $rc[1], $rec->id);
            else
                $this->Db->prepare('INSERT tl_spambot SET module=?, ip=?, created=?, tstamp=?, browser=?, typ=?, status=?')
                               ->execute($this->modID, $ip, $upd, $upd, \Environment::get('httpUserAgent'), $rc[0], $rc[1]);
        } else {
            $rec = $this->Db->prepare('SELECT id FROM tl_spambot WHERE module=? AND mail=? AND typ<>?')
                                  ->execute($this->modID, $mail, SpamBot::LOADED);
            if ($rec->numRows)
                $this->Db->prepare('UPDATE tl_spambot SET module=?, tstamp=?, ip=?, browser=?, typ=?, status=? WHERE id=?')
                               ->execute($this->modID, $upd, self::getIP(), \Environment::get('httpUserAgent'), $rc[0], $rc[1], $rec->id);
            else
                $this->Db->prepare('INSERT tl_spambot SET module=?, ip=?, mail=?, created=?, tstamp=?, browser=?, typ=?, status=?')
                               ->execute($this->modID, self::getIP(), $mail, $upd, $upd, \Environment::get('httpUserAgent'), $rc[0], $rc[1]);
        }

        return $rc;
    }

}

?>
