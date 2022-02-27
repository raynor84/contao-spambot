<?php
declare(strict_types=1);

/*
 * sync*gw SpamBot Bundle
 *
 * @copyright  https://syncgw.com, 2013 - 2022
 * @author     Florian Daeumling, https://syncgw.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

namespace syncgw\SpamBotBundle\Module;

use Contao\FormTextField;

class SpamBotTextField extends FormTextField {

    /**
     * Set a parameter
     */
    public function __set($strKey, $varValue) {
        if ('rgxp' === $strKey && 'email' === $varValue)
            $varValue = 'rgxSpamBots';
        parent::__set($strKey, $varValue);
    }

}

?>