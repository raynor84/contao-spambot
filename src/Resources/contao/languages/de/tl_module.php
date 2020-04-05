<?php

/*
 * sync*gw SpamBot Bundle
 *
 * @copyright  http://syncgw.com, 2013 - 2020
 * @author     Florian Daeumling, http://syncgw.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

$GLOBALS['TL_LANG']['tl_module']['spambot'] = 'Anbieter';
$GLOBALS['TL_LANG']['tl_module']['spambot_engines'] = [' ',
         'W&auml;hlen Sie den Abieter aus und legen die Reihenfolge in der die Abfragen gestartet werden sollen fest. '.
         'Bitte beachten Sie, dass die R&uuml;ckmeldungen parallel abgefragt werden - sobald ein Anbieter meldet, dass '.
         'die IP Adresse oder die Mail Adresse als "Ham" oder "Spam" eingestuft wurde, wird die Abfrage bei den '.
         'anderen Anbietern abgebrochen.', ];

// check modes
$GLOBALS['TL_LANG']['tl_module']['spambot_mod'] = ['Optimierung',
         'SpamBot fragt die Anbieter parallel ab. Wenn Sie mehr als einen Anbieter ausge&auml;hlt haben, dann k&ouml;nnen '.
         'Sie hier angeben, wie die Abfrage optimiert wird:<br>'.
         '<strong>Geschwindigkeit</strong> - Erstes Ergebnis von irgend einem Anbieter z&auml;hlt.<br>'.
         '<strong>Schlechteste</strong> - Erste "Spam" oderr "BlackList" von irgend einem Anbietern in der Liste.<br>'.
         '<strong>Beste</strong>  - Erste "Ham" oder "WhiteList" von irgend einem Anbietern in der Liste.', ];
$GLOBALS['TL_LANG']['tl_module']['spambot_mod_first'] = 'Geschwindigkeit';
$GLOBALS['TL_LANG']['tl_module']['spambot_mod_spam'] = 'Schlechteste';
$GLOBALS['TL_LANG']['tl_module']['spambot_mod_ham'] = 'Beste';

// output
$GLOBALS['TL_LANG']['tl_module']['spambot_output'] = 'SpamBot Ausgabe';
$GLOBALS['TL_LANG']['tl_module']['spambot_page'] = ['Umleitungsseite',
         'Legen Sie fest auf welche Seite der Besucher umgeleitet wird wenn der Besucher '.
         'als Spam Roboter identifiziert wurde. Wenn Sie das Feld leer lassen, dann wird nur eine '.
         'Fehlermeldung ausgegeben.', ];
$GLOBALS['TL_LANG']['tl_module']['spambot_msg'] = ['Meldung zus&auml;tzlich auf Seite ausgeben',
         'Wenn Sie keine Umleitungsseite angegeben haben, dann gibt SpamBot eine Meldung aus. '.
         'Wenn diese Option nicht aktiv ist, dann wird die komplette Seite bis auf die SpamBot Meldung gel&ouml;scht; '.
         'wenn diese Option aktiv ist, dann wird die SpamBot Meldung nur '.
         '<strong>zus&auml;tzlich</strong> ausgegeben.', ];

// Intern
$GLOBALS['TL_LANG']['tl_module']['spambot_internal'] = 'Interne Konfiguration';
$GLOBALS['TL_LANG']['tl_module']['spambot_internal_exp'] = ['Ablauftage des Zwischenspeichers',
         'Bitte geben Sie die Anzahl der Tage an, die die Adressen zwischen gespeichert bleiben sollen. '.
         'Bitte ber&uuml;cksichtigen Sie bei der Auswahl eines sehr hohen Wertes (z.B. 356 Tage), '.
         'dass Sie damit u.U. Adressen aussperren k&ouml;nnten, die zwischenzeitlich von dem Anbieter als '.
         'harmlos identifiziert wurden / bzw. dass die Adressen vielleicht durch erh&ouml;hte Aktivit&auml;ten '.
         'als "Spam" identifiziert wurden. Wenn Sie einen zu kleinen Wert angeben, dann wird im Ergebnis '.
         'ein h&ouml;herer Internetverkehr durch die &uuml;berpr&uuml;fung bei den Anbieter entstehen.', ];
$GLOBALS['TL_LANG']['tl_module']['spambot_val0'] = 'Ein Wert < 0 ist nicht erlaubt.';
$GLOBALS['TL_LANG']['tl_module']['spambot_val365'] = 'Ein Wert > 365 ist nicht erlaubt.';
$GLOBALS['TL_LANG']['tl_module']['spambot_internal_logham'] = ['Zwischenspeichern von "Ham" (zul&auml;ssige) Zugriffe',
         'Wird eine Adresse bei keinem Anbieter gefunden, so wird Sie als "Ham" gespeichert. '.
         'Bitte beachten Sie, dass Treffer in der "WhiteList" nicht zwischen gespeichert werden.', ];
$GLOBALS['TL_LANG']['tl_module']['spambot_internal_logspam'] = ['Zwischenspeichern von "Spam" (verd&auml;chtige) Zugiffe',
         'Bitte beachten Sie, dass Treffer in der "BlackList" nicht zwischen gespeichert werden.', ];

// Spamhaus
$GLOBALS['TL_LANG']['tl_module']['spambot_spamhaus'] = 'Spamhaus Konfiguration';
$GLOBALS['TL_LANG']['tl_module']['spambot_spamhaus_mods'] = ['Auswahl welche Status als "Spam" '.
         'identifiziert werden sollen. Dies ',
         'schlie&szlig;t die Pr&uuml;fung bei http://cbl.abuseat.org/ und http://www.njabl.org/ mit ein.', ];

// Honeypot
$GLOBALS['TL_LANG']['tl_module']['spambot_honeypot'] = 'HoneyPot Konfiguration';
$GLOBALS['TL_LANG']['tl_module']['spambot_honeypot_key'] = ['API Schl&uuml;ssel',
         'Bitte geben Sie ihren "http:BL Access Key" ein. Sie k&ouml;nnen auf der Seite '.
         '<a class="spambot_link" href="http://www.projecthoneypot.org/httpbl_configure.php" '.
         'target="_blank">www.projecthoneypot.org</a> diesen kostenlos beantragen.', ];
$GLOBALS['TL_LANG']['tl_module']['spambot_honeypot_score'] = ['Gef&auml;hrungswert',
         'Geben Sie den Gef&auml;hrdungswert ein (empfohlen ist 25). F&uuml;r mehr Informationen besuchen Sie die Seite '.
         '<a class="spambot_link" href="http://www.projecthoneypot.org/threat_info.php" '.
         'target="_blank">www.projecthoneypot.org</a>. IP Adressen mit einem h&ouml;heren Gef&auml;hrdungswert '.
         'werden als "Spam" identifiziert.', ];

// StopForumSpam
$GLOBALS['TL_LANG']['tl_module']['spambot_stopforumspam'] = 'StopForumSpam Konfiguration';
$GLOBALS['TL_LANG']['tl_module']['spambot_stopforumspam_score'] = ['Gef&auml;hrdungswert (in %)',
         'Geben Sie den Gef&auml;hrdungswert ein. '.
         'IP Adressen mit einem h&ouml;heren Gef&auml;hrdungswert werden als "Spam" identifiziert.', ];

// SORBS
$GLOBALS['TL_LANG']['tl_module']['spambot_sorbs'] = 'Spam and Open Relay Blocking System Konfiguration';
$GLOBALS['TL_LANG']['tl_module']['spambot_sorbs_mods'] = ['Auswahl welche Status als "Spam" '.
         'identifiziert werden sollen.', ];

// Blocklist
$GLOBALS['TL_LANG']['tl_module']['spambot_blocklist'] = 'Blocklist Konfiguration';
$GLOBALS['TL_LANG']['tl_module']['spambot_blocklist_mods'] = ['Auswahl welche Status als "Spam" '.
         'identifiziert werden sollen. Dies ',
         'schlie&szlig;t die Pr&uuml;fung bei http://www.dnswl.org/, http://www.spamhauswhitelist.com '.
         'und https://www.torproject.org/ mit ein.', ];

// AHBL
$GLOBALS['TL_LANG']['tl_module']['spambot_ahbl'] = 'Abusive Host Blocking List Konfiguration';
$GLOBALS['TL_LANG']['tl_module']['spambot_ahbl_mods'] = ['Auswahl welche Status als "Spam" '.
         'identifiziert werden sollen.', ];

// BotScout
$GLOBALS['TL_LANG']['tl_module']['spambot_botscout'] = 'BotScout Konfiguration';
$GLOBALS['TL_LANG']['tl_module']['spambot_botscout_key'] = ['API Schl&uuml;ssel',
         'Bitte geben Sie ihren "API Key" ein. Wenn Sie mehr als 20 Abfragen am Tag durchf&uuml;hren, '.
         'dann k&ouml;nnen Sie auf der Seite '.
         '<a class="spambot_link" href="http://www.botscout.com/getkey.htm" '.
         'target="_blank">www.botscout.com</a> diesen kostenlos beantragen.', ];
$GLOBALS['TL_LANG']['tl_module']['spambot_botscout_count'] = ['Schwelle',
         'Geben Sie die Schwelle an (Auflistung in der Datenbank), ab der die IP oder Mail Adresse als "Spam" '.
         'gekennzeichnet wird (empfohlen ist 5).', ];

// FSpamList
$GLOBALS['TL_LANG']['tl_module']['spambot_fspamlist'] = 'FSpamList Konfiguration';
$GLOBALS['TL_LANG']['tl_module']['spambot_fspamlist_key'] = ['API Schl&uuml;ssel',
         'Bitte geben Sie ihren "API Key" ein. Sie k&ouml;nnen diesen auf der Seite '.
         '<a class="spambot_link" href="http://www.fspamlist.com/index.php?c=register" '.
         'target="_blank">www.fspamlist.com</a> nach der Registrierung beantragen kostenlos beantragen.', ];
$GLOBALS['TL_LANG']['tl_module']['spambot_fspamlist_count'] = ['Schwelle',
         'Geben Sie die Schwelle an (Auflistung in der Datenbank), ab der die IP oder Mail Adresse als "Spam" '.
         'gekennzeichnet wird.', ];

// IPStack
$GLOBALS['TL_LANG']['tl_module']['spambot_ipstack'] = 'IPStack Konfiguration';
$GLOBALS['TL_LANG']['tl_module']['spambot_ipstack_countries'] = ['WhiteList L&auml;nder',
         'W&auml;hlen Sie die L&auml;nder aus, bei denen die IP-Adresse des Besuchers immer als "Ham" eingestuft wird.', ];
