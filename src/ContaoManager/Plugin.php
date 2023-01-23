<?php
declare(strict_types=1);

/*
 * sync*gw SpamBot Bundle
 *
 * @copyright  https://syncgw.com, 2013 - 2023
 * @author     Florian Daeumling, https://syncgw.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

namespace syncgw\SpamBotBundle\ContaoManager;

use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\CoreBundle\ContaoCoreBundle;
use syncgw\SpamBotBundle\SpamBotBundle;

class Plugin implements BundlePluginInterface {

    public function getBundles(ParserInterface $parser): array {
        return [
            BundleConfig::create(SpamBotBundle::class)
                ->setLoadAfter([ ContaoCoreBundle::class ]),
        ];
    }

}

?>