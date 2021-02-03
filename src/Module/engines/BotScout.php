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

class BotScout extends SpamBot {
    /*
     * @var string
     */
    protected $Name = 'BotScout';
    /*
     * @var array
     */
    protected $Fields = [ 'spambot_botscout_key' => 0, 'spambot_botscout_count' => 0 ];

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

?>