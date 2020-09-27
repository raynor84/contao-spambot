<?php

/*
 * sync*gw SpamBot Bundle
 *
 * @copyright  http://syncgw.com, 2013 - 2020
 * @author     Florian Daeumling, http://syncgw.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

use syncgw\SpamBotBundle\Module\SpamBot;

class SpamBotFSpamList extends SpamBot {
    protected $Name = 'FSpamList';
    protected $Fields = ['spambot_fspamlist_key' => 0, 'spambot_fspamlist_count' => 0];

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
                         'Checking <strong>'.(SpamBot::TYP_IP === $typ ? $ip : $mail).'</strong> <br />'.
                         'Clipping level is <strong>'.$this->spambot_fspamlist_count.'</strong><br />';

        $qry = '/api.php?spammer='.(SpamBot::TYP_IP === $typ ? $ip : urlencode($mail)).'&serial';
        if ($this->spambot_fspamlist_key)
            $qry .= '&key='.$this->spambot_fspamlist_key;

        if (!($fp = $this->openHTTP('www.fspamlist.com', $qry)))
            return [SpamBot::NOTFOUND, sprintf($GLOBALS['TL_LANG']['SpamBot']['generic']['err'], $this->Name, $this->ErrMsg)];

        $rc = deserialize($this->readHTTP($fp));
        fclose($fp);

        if (!count($rc)) {
            $this->ExtInfo .= 'Status received is <strong>'.$rc.'</strong><br /></div></fieldset>';

            return [SpamBot::NOTFOUND, sprintf($GLOBALS['TL_LANG']['SpamBot']['generic']['err'], $this->Name, $rc)];
        }

        $this->ExtInfo .= 'Status received is <strong>'.
                          'isspammer = '.$rc[0]['isspammer'].'<br />'.
                          'lastseen = '.$rc[0]['lastseen'].'<br />'.
                          'timesreported = '.$rc[0]['timesreported'].'<br />'.
                          'threat = '.$rc[0]['threat'].'<br />'.
                          'notes = '.$rc[0]['notes'].'</strong><br /></div></fieldset>';

        // in data base?
        if ('true' !== $rc[0]['isspammer'])
            return [SpamBot::NOTFOUND, sprintf($GLOBALS['TL_LANG']['SpamBot']['generic']['notfound'], $this->Name)];

        // check threat score
        if ($rc[0]['threat'] < $this->spambot_fspamlist_count)
            return [SpamBot::HAM, sprintf($GLOBALS['TL_LANG']['SpamBot']['generic']['dbhit'], $this->Name, $rc[0]['threat'])];

        return [SpamBot::SPAM, sprintf($GLOBALS['TL_LANG']['SpamBot']['generic']['dbhit'], $this->Name, $rc[0]['threat'])];
    }

}
