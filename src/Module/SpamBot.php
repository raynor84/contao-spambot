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

use Contao\Environment;
use Contao\System;

class SpamBot extends System {
    // modes
    const MOD_FIRST = 1;
    const MOD_SPAM  = 2;
    const MOD_HAM   = 3;

    // module type
    const TYP_IP    = 1;
    const TYP_MAIL  = 2;

    // spam types
    const NOTFOUND  = 0x01;
    const SPAM      = 0x02;
    const HAM       = 0x04;
    const WHITEL    = 0x08;
    const BLACKL    = 0x10;
    const LOADED    = 0x20;

    // text translation
    public static $Status = [
        self::NOTFOUND  => 'NotFound',
        self::SPAM      => 'Spam',
        self::HAM       => 'Ham',
        self::WHITEL    => 'WhiteList',
        self::BLACKL    => 'BlackList',
        self::LOADED    => 'Loaded',
    ];

    /*
     * module ID
     * @var int
     */
    public $modID;
    // extended information
    public $ExtInfo;
    // error message
    public $ErrMsg;
    // HTTP header received
    public $Header = [];
    // database pointer
    public $Db;

    /*
     * internal cache
     * @var array
     */
    protected $arrData = [];
    // use IP as is
    protected $Raw = FALSE;
    // HTTP buffer
    protected $Buffer = [];

    /**
     * Initialize class
     *
     * @param module id
     */
    public function __construct(int $modID = 0) {
        parent::__construct();
        $this->Db = \Contao\Database::getInstance();
        $this->modID = $modID;
    }

    /**
     * Load variables
     */
    public function __get($strKey) {

        if (!isset($this->arrData[$strKey])) {
            // record data already loaded?
            if (NULL === $this->Fields)
                return NULL;

            $rc = $this->Db->prepare('SELECT '.implode(',', array_keys($this->Fields)).' from tl_module WHERE id=?')->execute($this->modID);
            foreach ($this->Fields as $k => $v) {
                if ($v) {
                    $this->arrData[$k] = deserialize($rc->$k);
                    if (!is_array($this->arrData[$k]))
                        $this->arrData[$k] = [];
                } else
                    $this->arrData[$k] = $rc->$k;
            }
            $this->Fields = NULL;
        }

        return $this->arrData[$strKey];
    }

    /**
     * Save variable
     */
    public function __set($strKey, $varValue)  {
        $this->arrData[$strKey] = $varValue;
    }

    /**
     * Perform parallel checking
     *
     * @param function to call
     * @param IP address
     * @param mail address
     * @param call mod
     * @param 1=Execution time; 2=Additional info
     *
     * @return array (SpamBot::Status, status message, execution time)
     */
    public function callMods(int $func, string $ip, string $mail, int $mod = self::MOD_SPAM, int $info = 0): array {

        // get configured engines
        $conf = $this->Db->prepare('SELECT spambot_engines FROM tl_module WHERE id=?')->execute($this->modID);
        if (!is_array($conf = deserialize($conf->spambot_engines)))
            return ['Intern' => [self::NOTFOUND, 'No providwr selected', 0]];

        // start time
        $start = [];
        // handler ID
        $hd = [];
        // end time
        $end = [];
        // return array: name => array (SpamBot::Status, status message, execution time)
        $rc = [];
        // number of available active handlers
        $active = 0;
        // call all engines in parallel
        foreach ($conf as $name) {
            if ($info)
                $start[$name] = microtime(TRUE);
            $rc[$name] = NULL;
            // var_dump(Environment::get('httpHost').'/bundles/spambot/SpamBotCall.php'.'?Mod='.$this->modID.'&Class='.$name.'&Func='.$func.
            //        '&IP='.base64_encode($ip).'&Mail='.base64_encode($mail).'&ExtInfo='.(2 === $info ? '1' : '0'));
            if (!($hd[$name] = self::openHTTP(Environment::get('httpHost'),
                                              '/bundles/spambot/SpamBotCall.php'.
                                              '?Mod='.$this->modID.'&Class='.$name.'&Func='.$func.
                                              '&IP='.base64_encode($ip).'&Mail='.base64_encode($mail).'&ExtInfo='.(2 === $info ? '1' : '0')))) {
                $rc[$name] = [self::NOTFOUND, $name.': '.$this->ErrMsg];
            } else
                $active++;
        }

        // wait until all handler has terminated
        while ($active > 0) {
            foreach ($conf as $name) {
                // done?
                if (!$hd[$name])
                    continue;
                // get response
                $r = self::readHTTP($hd[$name]);
                if ($info)
                    $end[$name] = microtime(TRUE);
                // validate return value
                if ($r === FALSE) {
                    // simualte not found on error
                    $rc[$name] = [self::NOTFOUND, $name.': '.$this->ErrMsg, 0];
                    fclose($hd[$name]);
                    $hd[$name] = NULL;
                    $r = NULL;
                }
                // any valid response
                if (is_null($r) || !strlen($r))
                    continue;

                // check wait mode
                switch ($mod) {
                case self::MOD_FIRST:
                    $active = 1;

                    // no break
                case self::MOD_SPAM:
                case self::MOD_HAM:
                default:
                    $active--;
                    break;
                }

                // build return value
                if (FALSE === ($a = unserialize($r)))
                    $rc[$name] = [self::NOTFOUND, $name.': '.$r, 0];
                else {
                    $rc[$name] = $a;
                    // set no execution time
                    $rc[$name][2] = 0;
                }
            }
        }

        // cleanup
        foreach ($conf as $name) {
            if ($hd[$name])
                fclose($hd[$name]);
            if ($info)
                $rc[$name][2] = round(($end[$name] - $start[$name]) * 1000, 3);
        }

        return $rc;
    }

    /**
     * Default check data
     *
     * @param typ to check
     * @param IP address
     * @param mail address
     *
     * @return array (SpamBot::Status, status message) or received status codes (raw=TRUE)
     */
    public function check(int $typ, string $ip, string $mail): array {

        if (self::TYP_MAIL === $typ)
            return [self::NOTFOUND, sprintf($GLOBALS['TL_LANG']['SpamBot']['SpamBot']['notfound'], $this->Name)];

        // build query
        $chk = $this->Raw ? $ip : implode('.', array_reverse(explode('.', $ip))).$GLOBALS['SpamBot']['Engines'][$this->Name]['DNSBL'];
        $rc = [];
        $ext = FALSE;
        if (!$this->Raw)
            $this->ExtInfo = '<fieldset style="padding:3px"><div style="color:blue;">'.
                             'Checking <strong>'.(self::TYP_IP === $typ ? $ip : $mail).'</strong> <br />';

        if (version_compare(PHP_VERSION, '5.3', '>=')) {
            if ($r = @dns_get_record($chk, DNS_A + DNS_TXT)) {
                foreach ($r as $s) {
                    if ('A' === $s['type']) {
                        $rc[] = $s['ip'];
                        $this->ExtInfo .= 'Status received is <strong>'.$s['ip'].'</strong><br />';
                    } else {
                        $ext = TRUE;
                        if (isset($s['txt']))
                            $this->ExtInfo .= 'TXT record is <strong>'.$s['txt'].'</strong><br />';
                    }
                }
            }
            // hack for spamcop.net which does not return "A" record
            if ($ext && !count($rc))
                $rc[] = '127.0.0.2';
        } else {
            $rc[0] = gethostbyname($chk);
            $this->ExtInfo .= '<strong>Return code:</strong> '.$rc[0].'<br />';
        }
        $this->ExtInfo .= '</div></fieldset>';

        if (!count($rc))
            return [self::NOTFOUND, sprintf($GLOBALS['TL_LANG']['SpamBot']['generic']['notfound'], $this->Name)];

        if ('127' !== substr($rc[0], 0, 3))
            return [self::NOTFOUND, sprintf($GLOBALS['TL_LANG']['SpamBot']['generic']['err'], $this->Name, $rc[0])];

        // raw call?
        if ($this->Raw)
            return serialize($rc);

        // spam list
        $codes = is_array($GLOBALS['SpamBot']['Engines'][$this->Name]['Codes']) ?
                          $GLOBALS['SpamBot']['Engines'][$this->Name]['Codes'] : [];
        // module configuration array
        if (!is_array($conf = $this->{'spambot_'.strtolower($this->Name).'_mods'}))
            $conf = $GLOBALS['SpamBot']['Engines'][$this->Name]['Spam'];

        // check for Spam
        foreach ($rc as $stat) {
            foreach ($codes as $k => $v) {
                if (!in_array($k, $conf, TRUE))
                    continue;
                // wild card?
                if ($p = strpos($k, '*')) {
                    if (substr($k, 0, $p) === substr($stat, 0, $p))
                        return [self::SPAM, $this->Name.': '.$v];
                }
                if ($k === $stat)
                    return [self::SPAM, $this->Name.': '.$v];
            }
        }
        // check for Ham
        foreach ($rc as $stat) {
            foreach ($codes as $k => $v) {
                if (in_array($k, $conf, TRUE))
                    continue;
                // wild card?
                if ($p = strpos($k, '*')) {
                    if (substr($k, 0, $p) === substr($stat, 0, $p))
                    	return [self::HAM, $this->Name.': '.$v];
                }
                if ($k === $stat)
                    return [self::HAM, $this->Name.': '.$v];
            }
        }

        // not found
        return [self::NOTFOUND, sprintf($GLOBALS['TL_LANG']['SpamBot']['generic']['unsup'], $this->Name, $rc)];
    }


    /**
     * Open connection to host
     *
     * @param host name
     * @param URI
     * @param timeout
     *
     * @return mixed $pointer
     */
    public function openHTTP(string $host, string $uri, int $timeout = 10) {

        // prepare request (no fopen() usage because "allow_url_fopen=FALSE" may be set in PHP.INI)
        $req = 'GET '.$uri." HTTP/1.0\r\nHost: ".$host."\r\n\r\n";
        $sock = 80 != $_SERVER['SERVER_PORT'] ? 'ssl://' : NULL;
        $err = NULL;

        if (($fp = fsockopen($sock.$host, intval($_SERVER['SERVER_PORT']), $err, $this->ErrMsg, 10))) {
            fwrite($fp, $req);
            stream_set_timeout($fp, $timeout);
            $this->Header[(int) ($fp)] = [];
            $this->Buffer[(int) ($fp)] = NULL;
            if (($this->Buffer[(int) ($fp)] = self::readHTTP($fp)) === FALSE) {
                fclose($fp);
                return FALSE;
            }
        } else
            $this->ErrMsg = '['.$err.'] '.$this->ErrMsg;

        return $fp;
    }


    /**
     * Read data
     *
     * @param file pointer
     *
     * @return FALSE on error; else string
     */
    public function readHTTP($fp) {

        if (!is_resource($fp) || !$fp) {
            $this->ErrMsg = 'Connection to "'.$this->Name.'" is not a resource';
            return FALSE;
        }
        if ($this->Buffer[(int) ($fp)]) {
            $wrk = $this->Buffer[(int) ($fp)];
            $this->Buffer[(int) ($fp)] = NULL;
            return $wrk;
        }
        if (FALSE === ($wrk = fread($fp, 8192))) {
            $info = stream_get_meta_data($fp);
            if ($info['timed_out'])
                $this->ErrMsg = 'Connection to "'.$this->Name.'" timed out ('.$info['timed_out'].' sec.)';
            else
                $this->ErrMsg = 'Unspecific connection error to "'.$this->Name.'"';
            return FALSE;
        }

        if (is_array($this->Header[(int) ($fp)]))
            $do = !count($this->Header[(int) ($fp)]) ? 1 : 0;
        else
            $do = 0;

        if ($do && $wrk) {
            $this->Header[(int) ($fp)] = explode("\r\n", substr($wrk, 0, $pos = strpos($wrk, "\r\n\r\n")));
            $wrk = substr($wrk, $pos + 4);
            if (!$wrk)
                $wrk = NULL;
            // we use this approach to get "HTTP/1.0 200 OK" as well as "HTTP/1.1 200 OK"
            if (count($this->Header[(int) ($fp)]) && FALSE === strpos($this->Header[(int) ($fp)][0], '200 OK')) {
                $this->ErrMsg = $this->Header[(int) ($fp)][0];
                return FALSE;
            }
        }

        return $wrk;
    }

}

?>