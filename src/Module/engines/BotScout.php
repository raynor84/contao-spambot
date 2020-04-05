<?php

/*
 * sync*gw SpamBot Bundle
 *
 * @copyright  http://syncgw.com, 2013 - 2020
 * @author     Florian Daeumling, http://syncgw.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

namespace syncgw\SpamBotBundle\Module;

class SpamBotBotScout extends SpamBot {
    protected $Name = 'BotScout';
    protected $Fields = ['spambot_botscout_key' => 0, 'spambot_botscout_count' => 0];

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
    public function check($typ, $ip, $mail) {
        $this->ExtInfo = '<fieldset style="padding:3px"><div style="color:blue;">'.
                         'Checking <strong>'.(SpamBot::TYP_IP === $typ ? $ip : $mail).'</strong> <br />';

        $qry = '/test/?'.(SpamBot::TYP_IP === $typ ? 'ip='.$ip : 'mail='.urlencode($mail));
        if ($this->spambot_botscout_key)
            $qry .= '&key='.$this->spambot_botscout_key;

        if (!($fp = $this->openHTTP('botscout.com', $qry)))
            return [SpamBot::NOTFOUND, sprintf($GLOBALS['TL_LANG']['SpamBot']['generic']['err'], $this->Name, $this->ErrMsg)];

        $rc = $this->readHTTP($fp);
        $this->ExtInfo .= 'Clipping level is <strong>'.$this->spambot_botscout_count.'</strong><br />'.
                          'Status received is <strong>'.$rc.'</strong><br /></div></fieldset>';
        $rc = explode('|', $rc);
        fclose($fp);

        if (1 === count($rc))
            return [SpamBot::NOTFOUND, sprintf($GLOBALS['TL_LANG']['SpamBot']['generic']['err'], $this->Name, strip_tags($rc[0]))];

        // in data base?
        if (SpamBot::TYP_IP === $typ) {
            if ('Y' !== $rc[0] || 'IP' !== $rc[1])
                return [SpamBot::NOTFOUND, sprintf($GLOBALS['TL_LANG']['SpamBot']['generic']['notfound'], $this->Name)];
        } else {
            if ('Y' !== $rc[0] || 'MAIL' !== $rc[1])
                return [SpamBot::NOTFOUND, sprintf($GLOBALS['TL_LANG']['SpamBot']['generic']['notfound'], $this->Name)];
        }

        // check threat score
        if ($rc[2] < $this->spambot_botscout_count)
            return [SpamBot::HAM, sprintf($GLOBALS['TL_LANG']['SpamBot']['generic']['dbhit'], $this->Name, $rc[2])];

        return [SpamBot::SPAM, sprintf($GLOBALS['TL_LANG']['SpamBot']['generic']['dbhit'], $this->Name, $rc[2])];
    }

}
