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

use Contao\Module;

class SpamBotMail extends Module {

    public function generate(): string {
        if (TL_MODE === 'BE') {
            $obj            = new \Contao\BackendTemplate('be_wildcard');
            $obj->wildcard  = '### SPAMBOT MAIL PROTECTION ###';
            $obj->title     = $this->headline;
            $obj->id        = $this->id;
            $obj->link      = $this->name;
            return $obj->parse();
        }

        return parent::generate();
    }

    public function compile(): void {
    }

}

?>