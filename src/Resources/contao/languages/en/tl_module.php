<?php

/*
 * sync*gw SpamBot Bundle
 *
 * @copyright  https://syncgw.com, 2013 - 2021
 * @author     Florian Daeumling, https://syncgw.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

$GLOBALS['TL_LANG']['tl_module']['spambot'] = 'Provider';
$GLOBALS['TL_LANG']['tl_module']['spambot_engines'] = [' ',
         'Select provider and order to start check requests. Please note feed back is checked in parallel - '.
         'as soon as SpamBot receives a identification as "Ham" or "Spam" waiting for all other providers '.
         'is stopped.', ];

// check modes
$GLOBALS['TL_LANG']['tl_module']['spambot_mod'] = ['Optimization',
         'SpamBot use parallel checking for all selected provider above. If there are more than one provider selected '.
         'you may specify how checking is opimized:<br>'.
         '&raquo;Speed&laquo; - First result from any provider is selected.<br>'.
         '&raquo;Worst&laquo; - First "Spam" or "BlackList" from any provider in list.<br>'.
         '&raquo;Best&laquo;  - First "Ham" or "WhiteList" from any provider in list.', ];
$GLOBALS['TL_LANG']['tl_module']['spambot_mod_first'] = 'Speed';
$GLOBALS['TL_LANG']['tl_module']['spambot_mod_spam'] = 'Worst';
$GLOBALS['TL_LANG']['tl_module']['spambot_mod_ham'] = 'Best';

// output
$GLOBALS['TL_LANG']['tl_module']['spambot_output'] = 'SpamBot output';
$GLOBALS['TL_LANG']['tl_module']['spambot_page'] = ['Redirection page',
         'Defines to which page visitor will be redirected when user is being '.
         'identified as a spam bot. If this field is left empty only a error message will be displayed.', ];
$GLOBALS['TL_LANG']['tl_module']['spambot_msg'] = ['Put message additionally on page',
         'If you do not specify a redirection page, then SpamBot shows a error message. '.
         'If this option is not active, then page content except SpamBot message is wiped out; '.
         'if this option is active, then SpamBot message is put on page &raquo;additionally&laquo;.', ];

// Intern
$GLOBALS['TL_LANG']['tl_module']['spambot_internal'] = 'Internal configuration';
$GLOBALS['TL_LANG']['tl_module']['spambot_internal_exp'] = ['Cache expiration days',
         'Please enter the number of days addresses should stay in cache. '.
         'Please consider setting this value high (e.g. 356 days) may result in locking out '.
         'addresses which in meantime has been identified by provider as harmless or which '.
         'has been identified as "Spam" due to high actvitiy. If you enter a too small value this '.
         'results in more internet traffic for communication with provider.', ];
$GLOBALS['TL_LANG']['tl_module']['spambot_val0'] = 'A value < 0 is not allowed.';
$GLOBALS['TL_LANG']['tl_module']['spambot_val365'] = 'A value > 365 is not allowed.';
$GLOBALS['TL_LANG']['tl_module']['spambot_internal_logham'] = ['Cache "Ham" (allowed) access',
         'If address is not found by any provider it will be stored as "Ham". '.
         'Please note matches in "WhiteList" will not be cached.', ];
$GLOBALS['TL_LANG']['tl_module']['spambot_internal_logspam'] = ['Cache "Spam" (suspicious) access',
         'Please note matches in "BlackList" will not be cached.', ];

// Spamhaus
$GLOBALS['TL_LANG']['tl_module']['spambot_spamhaus'] = 'Spamhaus configuration';
$GLOBALS['TL_LANG']['tl_module']['spambot_spamhaus_mods'] = ['Select which status should '.
         'be considered as "Spam". Includes checling at http://cbl.abuseat.org/ and http://www.njabl.org/', ];

// Honeypot
$GLOBALS['TL_LANG']['tl_module']['spambot_honeypot'] = 'HoneyPot configuration';
$GLOBALS['TL_LANG']['tl_module']['spambot_honeypot_key'] = ['API key',
         'Please enter your "http:BL Access Key". You can request key at '.
         '<a class="spambot_link" href="http://www.projecthoneypot.org/httpbl_configure.php" '.
         'target="_blank">www.projecthoneypot.org</a> for free.', ];
$GLOBALS['TL_LANG']['tl_module']['spambot_honeypot_score'] = ['Threat score',
         'Enter the threat score (recommended is 25). For more information visit '.
         '<a class="spambot_link" href="http://www.projecthoneypot.org/threat_info.php" '.
         'target="_blank">www.projecthoneypot.org</a>. '.
         'IP addresses with a threat score higher than specified value will identified as "Spam".', ];

// StopForumSpam
$GLOBALS['TL_LANG']['tl_module']['spambot_stopforumspam'] = 'StopForumSpam configuration';
$GLOBALS['TL_LANG']['tl_module']['spambot_stopforumspam_score'] = ['Confidence level (in %)',
         'Enter the confidence level. '.
         'IP addresses returning a confidence level higher than specified value will identified as "Spam".', ];

// SORBS
$GLOBALS['TL_LANG']['tl_module']['spambot_sorbs'] = 'Spam and Open Relay Blocking System configuration';
$GLOBALS['TL_LANG']['tl_module']['spambot_sorbs_mods'] = ['Select which status should '.
         'be considered as "Spam".', ];

// Blocklist
$GLOBALS['TL_LANG']['tl_module']['spambot_blocklist'] = 'Blocklist configuration';
$GLOBALS['TL_LANG']['tl_module']['spambot_blocklist_mods'] = ['Select which status should '.
         'be considered as "Spam". Includes checking at http://www.dnswl.org/, http://www.spamhauswhitelist.com '.
         'and https://www.torproject.org/', ];

// AHBL
$GLOBALS['TL_LANG']['tl_module']['spambot_ahbl'] = 'Abusive Host Blocking List configuration';
$GLOBALS['TL_LANG']['tl_module']['spambot_ahbl_mods'] = ['Select which status should '.
         'be considered as "Spam".', ];

// BotScout
$GLOBALS['TL_LANG']['tl_module']['spambot_botscout'] = 'BotScout configuration';
$GLOBALS['TL_LANG']['tl_module']['spambot_botscout_key'] = ['API key',
         'Please enter your "API key". IF you expect more than 20 checks per day, '.
         'you may request a key for free at <a class="spambot_link" href="http://www.botscout.com/getkey.htm" '.
         'target="_blank">www.botscout.com</a>.', ];
$GLOBALS['TL_LANG']['tl_module']['spambot_botscout_count'] = ['Threshold',
         'Enter threshold (matches in database) treating IP or mail address as "Spam" '.
         '(recommended is 5).', ];

// FSpamList
$GLOBALS['TL_LANG']['tl_module']['spambot_fspamlist'] = 'FSpamList configuration';
$GLOBALS['TL_LANG']['tl_module']['spambot_fspamlist_key'] = ['API key',
         'Please enter your "API key". You may request a key for free (after registering) at <a class="spambot_link" '.
         'href="http://www.fspamlist.com/index.php?c=register" target="_blank">www.fspamlist.com</a>.', ];
$GLOBALS['TL_LANG']['tl_module']['spambot_fspamlist_count'] = ['Threshold',
         'Enter threshold (matches in database) treating IP or mail address as "Spam".', ];

// IPStack
$GLOBALS['TL_LANG']['tl_module']['spambot_ipstack'] = 'IPStack configuration';
$GLOBALS['TL_LANG']['tl_module']['spambot_ipstack_countries'] = ['Whitelist countries',
         'Select countries from which visitors IP address is auto.flagged as "Ham".', ];

?>