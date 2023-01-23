<?php
declare(strict_types=1);

/*
 * sync*gw SpamBot Bundle
 *
 * @copyright  https://syncgw.com, 2013 - 2023
 * @author     Florian Daeumling, https://syncgw.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

// back end modules
$GLOBALS['BE_MOD']['accounts']['SpamBot'] = [
    'tables'                                        => [ 'tl_spambot' ],
    // 'icon'                                       => 'bundles/spambot/images/spambot.png',
    'checkIP'                                       => [ 'tl_spambot', 'checkIP' ],
    'checkMail'                                     => [ 'tl_spambot', 'checkMail' ],
    'clearTab'                                      => [ 'tl_spambot', 'clearTab' ],
    'showLoad'                                      => [ 'tl_spambot', 'showLoad' ],
];

// front end modules
$GLOBALS['FE_MOD']['user']['SpamBot-IP']            = 'syncgw\SpamBotBundle\Module\SpamBotIP';
$GLOBALS['FE_MOD']['user']['SpamBot-Mail']          = 'syncgw\SpamBotBundle\Module\SpamBotMail';

if (TL_MODE === 'BE') {
    $GLOBALS['TL_CSS'][]                            = 'bundles/spambot/css/spambot_be.css';
} else {
    $GLOBALS['TL_CSS'][]                            = 'bundles/spambot/css/spambot_fe.css';
    // ensure only error message is display
    $GLOBALS['TL_HOOKS']['parseFrontendTemplate'][] = [ 'syncgw\SpamBotBundle\Module\SpamBotMod', 'clearTemplate' ];
    // insert tag replacement
    $GLOBALS['TL_HOOKS']['replaceInsertTags'][]     = [ 'syncgw\SpamBotBundle\Module\SpamBotMod', 'replaceInsertTag' ];
    // validate email text fields - need to do it this way, because else we wont catch comment e-mails
    $GLOBALS['TL_FFL']['text']                      = 'syncgw\SpamBotBundle\Module\SpamBotTextField';
    $GLOBALS['TL_HOOKS']['addCustomRegexp'][]       = [ 'syncgw\SpamBotBundle\Module\SpamBotMod', 'checkMail' ];
}

// Spam engines
$GLOBALS['SpamBot']['Engines'] = [
    'Intern' => [
        'HomePage' => 'Intern',
        'CheckIP' => 'Intern',
        'CheckMail' => 'Intern',
    ],
    'Spamhaus' => [
        'DNSBL' => '.zen.spamhaus.org',
        'HomePage' => '<a class="spambot_link" href="http://www.spamhaus.org" target="_blank">Spamhaus</a>',
        'CheckIP' => '<a class="spambot_link" href="http://www.spamhaus.org/query/bl?ip=%s" target="_blank">Spamhaus</a>',
        'Codes' => [
            '127.0.0.2' => 'Block List (<a class="spambot_link" href="http://www.spamhaus.org/sbl/" target="_blank">SBL</a>)',
            '127.0.0.3' => 'CSS Block List (<a class="spambot_link" href="http://www.spamhaus.org/css/" target="_blank">CSS</a>)',
            '127.0.0.4' => 'Exploits Block List (<a class="spambot_link" href="http://www.spamhaus.org/xbl/" target="_blank">XBL</a>)',
            '127.0.0.10' => 'ISP Policy Block List (<a class="spambot_link" href="http://www.spamhaus.org/pbl/" target="_blank">PBL</a>)',
            '127.0.0.11' => 'Policy Block List (<a class="spambot_link" href="http://www.spamhaus.org/pbl/" target="_blank">PBL</a>)',
        ],
        'Spam' => [
            '127.0.0.2', '127.0.0.4', '127.0.0.11', '127.0.1.*', '127.0.0.*',
        ],
    ],
    'Honeypot' => [
        'DNSBL' => '.dnsbl.httpbl.org',
        'HomePage' => '<a class="spambot_link" href="https://www.projecthoneypot.org" target="_blank">Honeypot</a>',
        'CheckIP' => '<a class="spambot_link" href="https://www.projecthoneypot.org/ip_%s" target="_blank">Honeypot</a>',
    ],
    'StopForumSpam' => [
        'HomePage' => '<a class="spambot_link" href="http://www.stopforumspam.com" target="_blank">StopForumSpam</a>',
        'CheckIP' => '<a class="spambot_link" href="http://www.stopforumspam.com/ipcheck/%s" target="_blank">StopForumSpam</a>',
        'CheckMail' => '<a class="spambot_link" href="http://www.stopforumspam.com/search/%s" target="_blank">StopForumSpam</a>',
    ],
    'SORBS' => [
        'DNSBL' => '.dnsbl.sorbs.net',
        'HomePage' => '<a class="spambot_link" href="http://www.sorbs.net/" target="_blank">Spam and Open Relay Blocking System</a>',
        'CheckIP' => '<a class="spambot_link" href="http://www.sorbs.net/lookup.shtml?%s" target="_blank">Spam and Open Relay Blocking System</a>',
        'Codes' => [
            '127.0.0.2' => 'Open SOCKS Proxy Servers',
            '127.0.0.3' => 'Open SOCKS Proxy Servers',
            '127.0.0.4' => 'Open Proxy Servers not listed in the SOCKS or HTTP lists',
            '127.0.0.5' => 'Open SMTP relay servers',
            '127.0.0.6' => 'Listed as Spam',
            '127.0.0.7' => 'Web servers which have spammer abusable vulnerabilities',
            '127.0.0.8' => 'Hosts demanding that they never be tested by SORBS',
            '127.0.0.9' => 'Networks hijacked from their original owners, some of which have already used for spamming',
            '127.0.0.10' => 'Dynamic IP Address ranges',
            '127.0.0.11' => 'Domain names where the "A" or "MX" records point to bad address space',
            '127.0.0.12' => 'Domain names where the owners have indicated no email should ever originate from these domains',
        ],
        'Spam' => [
            '127.0.0.6', '127.0.0.7', '127.0.0.9',
        ],
    ],
    'SpamCop' => [
        'DNSBL' => '.bl.spamcop.net',
        'HomePage' => '<a class="spambot_link" href="https://www.spamcop.net" target="_blank">SpamCop</a>',
        'CheckIP' => '<a class="spambot_link" href="https://www.spamcop.net/w3m?action=checkblock&ip=%s" target="_blank">SpamCop</a>',
        'Codes' => [
            '127.0.0.2' => 'Black listed',
        ],
        'Spam' => [
            '127.0.0.2',
        ],
    ],
    'Blocklist' => [
        'DNSBL' => '.all.bl.blocklist.de',
        'HomePage' => '<a class="spambot_link" href="http://www.blocklist.de/" target="_blank">BlockList</a>',
        'CheckIP' => '<a class="spambot_link" href="http://www.blocklist.de/en/view.html?ip=%s" target="_blank">BlockList</a>',
        'Codes' => [
            '127.0.0.2' => 'AmaVis',
            '127.0.0.3' => 'ApacheDDOS',
            '127.0.0.4' => 'Asterisk',
            '127.0.0.5' => 'BadBot',
            '127.0.0.6' => 'FTP',
            '127.0.0.7' => 'IMAP',
            '127.0.0.8' => 'IRCBot',
            '127.0.0.9' => 'Mail',
            '127.0.0.10' => 'POP3',
            '127.0.0.11' => 'RegBot',
            '127.0.0.12' => 'RFI Attack',
            '127.0.0.13' => 'SASL',
            '127.0.0.14' => 'SSH',
            '127.0.0.15' => 'W00TW00T',
            '127.0.0.16' => 'Port Flood',
            '127.0.0.18' => 'WebMin',
            '127.0.0.17' => 'SQL-Injection',
            '127.0.0.20' => 'Manuall',
        ],
        'Spam' => [
            '127.0.0.5', '127.0.0.8', '127.0.0.11',
        ],
    ],
    'NixSpam' => [
        'DNSBL' => '.ix.dnsbl.manitu.net',
        'HomePage' => '<a class="spambot_link" href="http://www.dnsbl.manitu.net/" target="_blank">NixSpam</a>',
        'CheckIP' => '<a class="spambot_link" href="http://www.dnsbl.manitu.net/lookup.php?value=%s" target="_blank">NixSpam</a>',
        'Codes' => [
            '127.0.0.2' => 'Black listed',
        ],
        'Spam' => [
            '127.0.0.2',
        ],
    ],
    'UCEProtect1' => [
        'DNSBL' => '.dnsbl-1.uceprotect.net',
        'HomePage' => '<a class="spambot_link" href="http://www.uceprotect.net/en/index.php?m=3&s=3" target="_blank">UCE Protect Level 1</a>',
        'CheckIP' => '<a class="spambot_link" href="http://www.uceprotect.net/rblcheck.php?ipr=%s" target="_blank">UCE Protect Level 1</a>',
        'Codes' => [
            '127.0.0.2' => 'Black listed',
        ],
        'Spam' => [
            '127.0.0.2',
        ],
    ],
    'UCEProtect2' => [
        'DNSBL' => '.dnsbl-2.uceprotect.net',
        'HomePage' => '<a class="spambot_link" href="http://www.uceprotect.net/en/index.php?m=3&s=4" target="_blank">UCE Protect Level 2</a>',
        'CheckIP' => '<a class="spambot_link" href="http://www.uceprotect.net/rblcheck.php?ipr=%s" target="_blank">UCE Protect Level 2</a>',
        'Codes' => [
            '127.0.0.2' => 'Black listed',
        ],
        'Spam' => [
            '127.0.0.2',
        ],
    ],
    'UCEProtect3' => [
        'DNSBL' => '.dnsbl-3.uceprotect.net',
        'HomePage' => '<a class="spambot_link" href="http://www.uceprotect.net/en/index.php?m=3&s=5" target="_blank">UCE Protect Level 3</a>',
        'CheckIP' => '<a class="spambot_link" href="http://www.uceprotect.net/rblcheck.php?ipr=%s" target="_blank">UCE Protect Level 3</a>',
        'Codes' => [
            '127.0.0.2' => 'Black listed',
        ],
        'Spam' => [
            '127.0.0.2',
        ],
    ],
    'AHBL' => [
        'DNSBL' => '.dnsbl.ahbl.org',
        'HomePage' => '<a class="spambot_link" href="https://www.ahbl.org/" target="_blank">Abusive Host Blocking List</a>',
        'CheckIP' => '<a class="spambot_link" href="https://www.ahbl.org/lktool?lookup=%s" target="_blank">Abusive Host Blocking List</a>',
        'Codes' => [
            '127.0.0.2' => 'Open Relay',
            '127.0.0.3' => 'Open Proxy',
            '127.0.0.4' => 'Spam Source',
            '127.0.0.5' => 'Provisional Spam Source Listing block (will be removed if spam stops)',
            '127.0.0.6' => 'Formmail Spam',
            '127.0.0.7' => 'Spam Supporter',
            '127.0.0.8' => 'Spam Supporter (indirect)',
            '127.0.0.9' => 'End User (non mail system)',
            '127.0.0.10' => 'Shoot On Sight',
            '127.0.0.11' => 'Non-RFC Compliant (missing postmaster or abuse)',
            '127.0.0.12' => 'Does not properly handle 5xx errors',
            '127.0.0.13' => 'Other Non-RFC Compliant',
            '127.0.0.14' => 'Compromised System - DDoS',
            '127.0.0.15' => 'Compromised System - Relay',
            '127.0.0.16' => 'Compromised System - Autorooter/Scanner',
            '127.0.0.17' => 'Compromised System - Worm or mass mailing virus',
            '127.0.0.18' => 'Compromised System - Other virus',
            '127.0.0.19' => 'Open Proxy',
            '127.0.0.20' => 'Blog/Wiki/Comment Spammer',
            '127.0.0.127' => 'Other',
        ],
        'Spam' => [
            '127.0.0.5', '127.0.0.6', '127.0.0.7', '127.0.0.8', '127.0.0.20',
        ],
    ],
    'WPBL' => [
        'DNSBL' => '.db.wpbl.info',
        'HomePage' => '<a class="spambot_link" href="http://www.wpbl.info/" target="_blank">Weighted Private Block List</a>',
        'CheckIP' => '<a class="spambot_link" href="http://wpbl.info/record?ip=%s" target="_blank">Weighted Private Block List</a>',
        'Codes' => [
            '127.0.0.2' => 'Black listed',
        ],
        'Spam' => [
            '127.0.0.2',
        ],
    ],
    'BotScout' => [
        'HomePage' => '<a class="spambot_link" href="http://www.botscout.com/" target="_blank">BotScout</a>',
        'CheckIP' => '<a class="spambot_link" href="http://www.botscout.com/search.htm?sterm=%s&stype=q" target="_blank">BotScout</a>',
        'CheckMail' => '<a class="spambot_link" href="http://www.botscout.com/search.htm?sterm=%s&stype=q" target="_blank">BotScout</a>',
    ],
    'FSpamList' => [
        'HomePage' => '<a class="spambot_link" href="http://www.fspamlist.com/" target="_blank">FSpamList</a>',
        'CheckIP' => '<a class="spambot_link" href="http://www.fspamlist.com/index.php?c=search" target="_blank">FSpamList</a>',
        'CheckMail' => '<a class="spambot_link" href="http://www.fspamlist.com/index.php?c=search" target="_blank">FSpamList</a>',
    ],
    'IPStack' => [
        'HomePage' => '<a class="spambot_link" href="https://ipstack.com/" target="_blank">IPStack</a>',
        'CheckIP' => '<a class="spambot_link" href="https://ipstack.com/" target="_blank">IPStack</a>',
    ],
];

?>