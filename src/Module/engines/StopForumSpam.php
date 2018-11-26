<?php

/*
 * sync*gw SpamBot Bundle
 *
 * @copyright  http://syncgw.com, 2013 - 2018
 * @author     Florian Daeumling, http://syncgw.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

namespace syncgw\SpamBotBundle\Module;

class SpamBotStopForumSpam extends SpamBot
{
    protected $Name = 'StopForumSpam';
    protected $Fields = ['spambot_stopforumspam_score' => 0];

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
     **/
    public function check($typ, $ip, $mail)
    {
        $this->ExtInfo = '<fieldset style="padding:3px"><div style="color:blue;">'.
                         'Checking <strong>'.(SpamBot::TYP_IP === $typ ? $ip : $mail).'</strong> <br />'.
                         'Clipping level is <strong>'.$this->spambot_stopforumspam_score.'</strong><br />';

        $qry = '/api?'.(SpamBot::TYP_IP === $typ ? 'ip='.$ip : 'email='.urlencode($mail)).'&f=serial&confidence';

        if (!($fp = $this->openHTTP('www.stopforumspam.com', $qry))) {
            $this->ExtInfo .= '</div></fieldset>';

            return [SpamBot::NOTFOUND, sprintf($GLOBALS['TL_LANG']['SpamBot']['generic']['err'], $this->Name, $this->ErrMsg)];
        }

        $rc = deserialize($this->readHTTP($fp));
        fclose($fp);

        // in data base?
        $key = SpamBot::TYP_IP === $typ ? 'ip' : 'email';

        $this->ExtInfo .= 'Status received is <strong>'.
                          'success = '.$rc['success'].'<br />'.
                          'lastseen = '.$rc[$key]['lastseen'].'<br />'.
                          'frequency = '.$rc[$key]['frequency'].'<br />'.
                          'appears = '.$rc[$key]['appears'].'<br />'.
                          'confidence = '.$rc[$key]['confidence'].'<br /></strong></div></fieldset>';

        if (!$rc['success']) {
            return [SpamBot::NOTFOUND, sprintf($GLOBALS['TL_LANG']['SpamBot']['generic']['err'], $this->Name, $rc['error'])];
        }

        if (!$rc[$key]['appears']) {
            return [SpamBot::NOTFOUND, sprintf($GLOBALS['TL_LANG']['SpamBot']['generic']['notfound'], $this->Name)];
        }

        // check confidence level
        if ($rc[$key]['confidence'] < $this->spambot_stopforumspam_score) {
            $typ = SpamBot::HAM;
        } else {
            $typ = SpamBot::SPAM;
        }

        return [$typ, sprintf($GLOBALS['TL_LANG']['SpamBot']['StopForumSpam']['stat'], $this->Name,
                                                $rc[$key]['confidence'], $rc[$key]['frequency'])];
    }
}
