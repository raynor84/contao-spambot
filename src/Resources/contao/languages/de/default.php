<?php

/*
 * sync*gw SpamBot Bundle
 *
 * @copyright  https://syncgw.com, 2013 - 2021
 * @author     Florian Daeumling, https://syncgw.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

$GLOBALS['TL_LANG']['SpamBot']['SpamBot']['email'] = 'Diese E-Mail Adresse ist bekannt f&uuml;r die Verwendung in Spam Attacken und kann nicht akzeptiert werden!';

// SpamBot.php
$GLOBALS['TL_LANG']['SpamBot']['generic']['err'] = '%s: Fehler "%s"';
$GLOBALS['TL_LANG']['SpamBot']['generic']['unsup'] = '%s: Unbekannter Typ "%s"';
$GLOBALS['TL_LANG']['SpamBot']['generic']['notfound'] = '%s: Nicht gefunden';
$GLOBALS['TL_LANG']['SpamBot']['generic']['found'] = '%s: Gefunden';
$GLOBALS['TL_LANG']['SpamBot']['generic']['dbhit'] = '%s: %s Treffer in Datenbank';

// engine/Intern.php
$GLOBALS['TL_LANG']['SpamBot']['Intern']['ip'] = '%s: Netzwerkmuster "%s" passt';
$GLOBALS['TL_LANG']['SpamBot']['Intern']['mail'] = '%s: E-Mail Muster "%s" passt';

// engine/Honeypot.php
$GLOBALS['TL_LANG']['SpamBot']['Honeypot']['sus'] = 'Suspicious';
$GLOBALS['TL_LANG']['SpamBot']['Honeypot']['har'] = 'Harvester';
$GLOBALS['TL_LANG']['SpamBot']['Honeypot']['com'] = 'Comment Spammer';
$GLOBALS['TL_LANG']['SpamBot']['Honeypot']['ham'] = '%s: %s Punkte';
$GLOBALS['TL_LANG']['SpamBot']['Honeypot']['spam'] = '%s: %s (%s Punkte)';

// engine/StopForumSpam.php
$GLOBALS['TL_LANG']['SpamBot']['StopForumSpam']['stat'] = '%s: Confidence level (%s%% Punkte, Frequenz %s)';

// engines/SpamIP.php
$GLOBALS['TL_LANG']['SpamBot']['SpamIP']['cron'] = '%s: %d Datens&auml;tze hinzugef&uuml;gt und %d Datens&auml;tze gel&ouml;scht';
$GLOBALS['TL_LANG']['SpamBot']['SpamIP']['notfound'] = '%s: Kein Treffer in geladenen Datens&auml;tzen';

// SpamBotCron.php
$GLOBALS['TL_LANG']['SpamBot']['cron']['rec'] = '%d Adressen f&uuml;r Modul "%s" gel&ouml;scht';
$GLOBALS['TL_LANG']['SpamBot']['cron']['mod'] = '%d nicht zugeordnete Eintr&auml;ge gel&ouml;scht';

?>