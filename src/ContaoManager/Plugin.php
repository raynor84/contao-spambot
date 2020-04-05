<?php

/*
 * sync*gw SpamBot Bundle
 *
 * @copyright  http://syncgw.com, 2013 - 2020
 * @author     Florian Daeumling, http://syncgw.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

namespace syncgw\SpamBotBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use syncgw\SpamBotBundle\SpamBotBundle;

class Plugin implements BundlePluginInterface {
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser) {
        return [
            BundleConfig::create(SpamBotBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }

}
