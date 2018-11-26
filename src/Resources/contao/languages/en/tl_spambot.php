<?php

/*
 * sync*gw SpamBot Bundle
 *
 * @copyright  http://syncgw.com, 2013 - 2018
 * @author     Florian Daeumling, http://syncgw.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

$GLOBALS['TL_LANG']['tl_spambot']['module'] = ['Module name',
    'This is the name of the module.', ];
$GLOBALS['TL_LANG']['tl_spambot']['ip'] = ['IP address',
    'A IP address of "0.0.0.0" indicates visitors IP address is a IPV6 address (not supported). '.
    'For <strong>BlackList</strong> and <strong>WhiteList</strong> you may use network mask in '.
    'range format xxx.xxx.xxx.xxx-xxx.xxx.xxx.xxx or mask format xxx.xxx.xxx.xxx/xx.', ];
$GLOBALS['TL_LANG']['tl_spambot']['mail'] = ['Mail address',
    'For <strong>BlackList</strong> and <strong>WhiteList</strong> you may use any '.
    '<a class="spamot_link" href="http://php.net/manual/en/reference.pcre.pattern.syntax.php" '.
    'target="_blank">PCRE Pattern</a>.', ];
$GLOBALS['TL_LANG']['tl_spambot']['typ'] = ['Typ', 'Type of record:<br />'.
    '<strong>Spam</strong> - Identified as a spam bot <img src="bundles/spambot/imagesspam.png" alt="Spam"><br />'.
    '<strong>Ham</strong> - Identified as a valid address <img src="bundles/spambot/images/ham.png" alt="Ham"><br />'.
    '<strong>WhiteList</strong> - Access always allowed <img src="bundles/spambot/images/whitelist.png" alt="WhiteList"><br />'.
    '<strong>BlackList</Strong> - Access always denied <img src="bundles/spambot/images/blacklist.png" alt="BlackList">', ];
$GLOBALS['TL_LANG']['tl_spambot']['created'] = ['Created', 'Date and time record was created.'];
$GLOBALS['TL_LANG']['tl_spambot']['tstamp'] = ['Update', 'Date and time record was last modified.'];
$GLOBALS['TL_LANG']['tl_spambot']['browser'] = ['Browser info', 'Browser information. '.
    'Browser information extracted from HTTP request.', ];
$GLOBALS['TL_LANG']['tl_spambot']['status'] = ['Status', 'Status of check.'];

// buttons
$GLOBALS['TL_LANG']['tl_spambot']['clear'] = ['Clear table', 'Click to clear entire table'];
$GLOBALS['TL_LANG']['tl_spambot']['edit'] = ['Edit', 'Edit record with ID %s'];
$GLOBALS['TL_LANG']['tl_spambot']['copy'] = ['Duplicate', 'Duplicate record with ID %s'];
$GLOBALS['TL_LANG']['tl_spambot']['new'] = ['New', 'Create a new record'];
$GLOBALS['TL_LANG']['tl_spambot']['delete'] = ['Delete', 'Delete record with ID %s'];
$GLOBALS['TL_LANG']['tl_spambot']['check'] = ['Check', 'Check record with ID %s'];
$GLOBALS['TL_LANG']['tl_spambot']['recheck'] = ['Re Check', 'Check record again'];
$GLOBALS['TL_LANG']['tl_spambot']['extinfo'] = ['Extended check',
    'Check record again and include extended information (for debugging puroses)', ];

// messages
$GLOBALS['TL_LANG']['tl_spambot']['confirm'] = 'Are you sure you want to clear entire table?';
$GLOBALS['TL_LANG']['tl_spambot']['typ_ip'] = 'Checking IP address "%s"';
$GLOBALS['TL_LANG']['tl_spambot']['typ_mail'] = 'Checking mail address "%s"';
$GLOBALS['TL_LANG']['tl_spambot']['lab_time'] = 'Current execution time: %s msec.';
$GLOBALS['TL_LANG']['tl_spambot']['lab_avt'] = 'Average execution time: %s msec.';
