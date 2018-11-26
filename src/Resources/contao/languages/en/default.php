<?php

/*
 * sync*gw SpamBot Bundle
 *
 * @copyright  http://syncgw.com, 2013 - 2018
 * @author     Florian Daeumling, http://syncgw.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

$GLOBALS['TL_LANG']['SpamBot']['SpamBot']['email'] = 'This e-mail address is known as being in use for for spam attacks and cannot be accepted!';

// SpamBot.php
$GLOBALS['TL_LANG']['SpamBot']['generic']['err'] = '%s: Error "%s"';
$GLOBALS['TL_LANG']['SpamBot']['generic']['unsup'] = '%s: Unsupported typ "%s"';
$GLOBALS['TL_LANG']['SpamBot']['generic']['notfound'] = '%s: Not found';
$GLOBALS['TL_LANG']['SpamBot']['generic']['found'] = '%s: Found';
$GLOBALS['TL_LANG']['SpamBot']['generic']['dbhit'] = '%s: %s matches in database';

// engine/Intern.php
$GLOBALS['TL_LANG']['SpamBot']['Intern']['ip'] = '%s: Network mask "%s" matched';
$GLOBALS['TL_LANG']['SpamBot']['Intern']['mail'] = '%s: E-mail pattern "%s" matched';

// engine/Honeypot.php
$GLOBALS['TL_LANG']['SpamBot']['Honeypot']['sus'] = 'Suspicious';
$GLOBALS['TL_LANG']['SpamBot']['Honeypot']['har'] = 'Harvester';
$GLOBALS['TL_LANG']['SpamBot']['Honeypot']['com'] = 'Comment Spammer';
$GLOBALS['TL_LANG']['SpamBot']['Honeypot']['ham'] = '%s: Score %s';
$GLOBALS['TL_LANG']['SpamBot']['Honeypot']['spam'] = '%s: %s (score %s)';

// engine/StopForumSpam.php
$GLOBALS['TL_LANG']['SpamBot']['StopForumSpam']['stat'] = '%s: Confidence level (score %s%%, frequence %s)';

// engines/SpamIP.php
$GLOBALS['TL_LANG']['SpamBot']['SpamIP']['cron'] = '%s: %d records added and %d records deleted';
$GLOBALS['TL_LANG']['SpamBot']['SpamIP']['notfound'] = '%s: No match in loaded records';

// SpamBotCron.php
$GLOBALS['TL_LANG']['SpamBot']['cron']['rec'] = '%d addresses deleted for module "%s"';
$GLOBALS['TL_LANG']['SpamBot']['cron']['mod'] = '%d unassigned entries deleted';
