<?php

/*
 * sync*gw SpamBot Bundle
 *
 * @copyright  http://syncgw.com, 2013 - 2018
 * @author     Florian Daeumling, http://syncgw.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

namespace syncgw\SpamBotBundle\Module;

use Contao\FormTextField;

class SpamBotTextField extends FormTextField
{
    /**
     * Set a parameter
     *
     * @param string
     * @param mixed
     * @param mixed $strKey
     * @param mixed $varValue
     */
    public function __set($strKey, $varValue)
    {
        if ('rgxp' === $strKey && 'email' === $varValue) {
            $varValue = 'rgxSpamBots';
        }
        parent::__set($strKey, $varValue);
    }
}
