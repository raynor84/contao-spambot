<?php

/*
 * sync*gw SpamBot Bundle
 *
 * @copyright  https://syncgw.com, 2013 - 2022
 * @author     Florian Daeumling, https://syncgw.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

$GLOBALS['TL_LANG']['tl_spambot']['module'] = ['Modul Namen',
    'Dies ist der Name des Moduls.', ];
$GLOBALS['TL_LANG']['tl_spambot']['ip'] = ['IP Adresse oder Bereich', 'IP Adresse. '.
    'Wenn die IP Adresse "0.0.0.0" ist, dann verwendet der Besucher eine IPV6 Adresse (nicht unterst&uuml;tzt). '.
    'F&uuml;r &raquo;BlackList&laquo; und &raquo;WhiteList&laquo; k&ouml;nnen sie Netzwerkbereiche im '.
    'Format xxx.xxx.xxx.xxx-xxx.xxx.xxx.xxx oder Netzwerkmasken im Format xxx.xxx.xxx.xxx/xx angeben.', ];
$GLOBALS['TL_LANG']['tl_spambot']['mail'] = ['E-Mail Adresse',
    'F&uuml;r &raquo;BlackList&laquo; und &raquo;WhiteList&laquo; k&ouml;nnen sie alle '.
    '<a class="spambot_link" href="http://php.net/manual/de/reference.pcre.pattern.syntax.php" '.
    'target="_blank">PCRE Muster</a> verwenden.', ];
$GLOBALS['TL_LANG']['tl_spambot']['typ'] = ['Typ', '
	Typ des Datensatzes:<br />'.
    '&raquo;Spam&laquo;      - Als Spambot identifiziert <img src="bundles/spambot/images/spam.png" alt="Spam"><br />'.
    '&raquo;Ham&laquo;       - Als g&uuml;ltige Adresse identifiziert <img src="bundles/spambot/images/ham.png" alt="Ham"><br />'.
    '&raquo;WhiteList&laquo; - Zugriff immer erlauben <img src="bundles/spambot/images/whitelist.png" alt="WhiteList"><br />'.
    '&raquo;BlackList&laquo; - Zugriff immer verweigern <img src="bundles/spambot/images/blacklist.png" alt="BlackList">', ];
$GLOBALS['TL_LANG']['tl_spambot']['created'] = ['Erstellt',
    'Datum und Uhrzeit wann der Datensatz hinzugef&uuml;gt wurde.', ];
$GLOBALS['TL_LANG']['tl_spambot']['tstamp'] = ['Ge&auml;ndert',
    'Datum und Uhrzeit wann der Datensatz zuletzt benutzt wurde.', ];
$GLOBALS['TL_LANG']['tl_spambot']['browser'] = ['Browser Informationen',
    'Browser Informationen die beim HTTP Request mitgeliefert werden.', ];
$GLOBALS['TL_LANG']['tl_spambot']['status'] = ['Status', 'Ergebnis der Pr&uuml;fung.'];

// buttons
$GLOBALS['TL_LANG']['tl_spambot']['clear'] = ['L&ouml;sche Tabelle',
    'Klicken sie hier um die gesamte Tabelle zu leeren', ];
$GLOBALS['TL_LANG']['tl_spambot']['edit'] = ['Bearbeiten',
    'Bearbeiten des Datensatzes mit der ID %s', ];
$GLOBALS['TL_LANG']['tl_spambot']['copy'] = ['Duplizieren',
    'Duplizieren des Datensatzes mit der ID %s', ];
$GLOBALS['TL_LANG']['tl_spambot']['new'] = ['Neu',
    'Anlegen eines neuen Datensatzes', ];
$GLOBALS['TL_LANG']['tl_spambot']['delete'] = ['L&ouml;schen',
    'L&ouml;schen des Datensatzes mit der ID %s', ];
$GLOBALS['TL_LANG']['tl_spambot']['check'] = ['Pr&uuml;fen',
    'Pr&uuml;fen des Datensatzes mit der ID %s', ];
$GLOBALS['TL_LANG']['tl_spambot']['recheck'] = ['Erneut Pr&uuml;fen',
    'Erneutes Pr&uuml;fen des Datensatzes', ];
$GLOBALS['TL_LANG']['tl_spambot']['extinfo'] = ['Erweiterte Pr&uuml;fung',
    'Erneutes Pr&uuml;fen mit Ausgabe von Erweiterten Informationen (f&uuml;r Fehlersuche)', ];

// messages
$GLOBALS['TL_LANG']['tl_spambot']['confirm'] = 'Sind sie sicher, dass sie den Inhalt der Tabelle l&ouml;schen m&ouml;chten?';
$GLOBALS['TL_LANG']['tl_spambot']['typ_ip'] = 'Pr&uuml;fe IP Adresse "%s"';
$GLOBALS['TL_LANG']['tl_spambot']['typ_mail'] = 'Pr&uuml;fe E-Mail Adresse "%s"';
$GLOBALS['TL_LANG']['tl_spambot']['lab_time'] = 'Aktuelle Ausf&uuml;hrungszeit: %s msec.';
$GLOBALS['TL_LANG']['tl_spambot']['lab_avt'] = 'Durchschnittliche Ausf&uuml;hrungszeit: %s msec.';

?>