<?php

/*
 * sync*gw SpamBot Bundle
 *
 * @copyright  http://syncgw.com, 2013 - 2020
 * @author     Florian Daeumling, http://syncgw.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

define('TL_MODE', 'FE');
define('TL_SCRIPT', 'SpamBot');

require $_SERVER["DOCUMENT_ROOT"].'/../system/initialize.php';

use Contao\Frontend;

class SpamBotCall extends Frontend {

	/**
     * Initialize the object (do not remove).
     */
    public function __construct() {
        parent::__construct();
        define('BE_USER_LOGGED_IN', false);
        define('FE_USER_LOGGED_IN', false);
    }

    /**
     * Run the controller.
     *
     * @return array (SpamBot::Status, status message)
     */
    public function run() {
        $this->loadLanguageFile('default');

        // class/file to call
        $class = $_GET['Class'];
        // function to call
        $func = $_GET['Func'];
        // module ID
        $mod = $_GET['Mod'];
        // extended information
        $extinfo = $_GET['ExtInfo'];
        // ip address
        $ip = base64_decode($_GET['IP'], true);
        // mail address
        $mail = base64_decode($_GET['Mail'], true);

        if (!$class)
            return 'Not allowed';

        // include our engine class
        require TL_ROOT.'/vendor/syncgw/contao-spambot/src/Module/engines/'.$class.'.php';

        $class = 'syncgw\SpamBotBundle\Module\SpamBot'.$class;
        $obj = new $class($mod);
        list($stat, $msg) = $obj->check($func, $ip, $mail);
        // append extended information?
        if ($extinfo && $obj->ExtInfo)
            $msg .= $obj->ExtInfo;

        return serialize([$stat, $msg]);
    }

}

$obj = new SpamBotCall();
echo $obj->run();
