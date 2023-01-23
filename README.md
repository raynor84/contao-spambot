# SpamBot

1. [Deutsch](#1)
2. [English](#2)

## <a name="1"></a>1. Deutsch
Blockieren sie den Zugriff auf Ihre Web-Seite (oder einzelne Seiten ihres Internetauftritts) für (halb-) automatische Spam-Roboter. Alle IP Adressen und/oder E-Mail Adressen werden bei der Prüfung entweder als **Spam** (unerwünschter Zugriff) oder als **Ham** (erlaubter Zugriff) identifiziert. Dabei können sie wahlweise die IP Adressen auch auf eine **BlackList** (Liste von unerwünschten Besuchern) oder eine **WhiteList** (Liste erwünschter Besuchern) eintragen.

Folgende Anbieter können genutzt werden:

* **Intern**
Internes caching der Überprüfungsergebnisse. Pflege von Einträgen auf der **BlackList** oder der **WhiteList**.
* **[Spamhaus](https://www.spamhaus.org/)**
Überprüfen der IP Adresse.
* **[Honeypot](https://www.projecthoneypot.org/)**
Überprüfen der IP Adresse.
* **[StopForumSpam](http://www.stopforumspam.com)**
Überprüfen der IP und der Mail Adresse.
* **[Spam and Open Relay Blocking System (SORBS)](http://www.sorbs.net)**
Überprüfen der IP Adresse.
* **[SpamCop](https://www.spamcop.net/)**
Überprüfen der IP Adresse.
* **[BlockList](http://www.blocklist.de)**
Überprüfen der IP Adresse.
* **[NixSpam](http://www.dnsbl.manitu.net)**
Überprüfen der IP Adresse.
* **[UCE Protect](http://www.uceprotect.net)**
Überprüfen der IP Adresse (Level 1+2+3).
* **[Abusive Host Blocking List](https://www.ahbl.org)**
Überprüfen der IP Adresse.
* **[Weighted Private Block List](http://www.wpbl.info)**
Überprüfen der IP Adresse.
* **[BotScout](http://www.botscout.com)**
Überprüfen der IP und der Mail Adresse.
* **[FSpamList](http://www.fspamlist.com)**
Überprüfen der IP und der Mail Adresse.
* **[IPStack](https://ipstack.com/)**
Zugriff generell für ausgewählte Länder erlauben.

**Installation**

* Installieren sie die Erweiterung.
* Erstellen sie ein neues **Frontend-Modul** vom Typ **SpamBot-IP** (für die Überprüfung der IP-Adressen) oder **SpamBot-Mail** (für die Überprüfung der Mail-Adressen). Konfigurieren Sie welche Anbieter genutzt werden sollen.
* Erstellen sie bei Bedarf eine neue Seite auf die alle als verdächtig identifizierten Besucher weiter geleitet werden oder passen sie das Template **mod_spambot** nach ihren Bedürfnissen an (das Template unterstützt die Ausgabe einer Meldung in Deutsch oder Englisch).
* Binden sie das Modul wahlweise im **Seitenlayout** oder auf einzelne Seiten als **Seitenelement** ein.

**Benutzung**

* Ab sofort wird entweder die IP Adresse der Besucher überprüft oder die E-Mail Adresse wird bei der Eingabe in irgend welchen Forumlarfeldern überprüft. Sollten die Daten als **Spam** eingestuft werden, so wird wahlweise eine Meldung ausgegeben oder die verdächtigen Besucher werden auf eine spezielle Seite weiter geleitet.
* Über einen neuen Menüpunkt im BackEnd (unterhalb der Benutzerverwaltung) können sie zusätzliche IP Adressen oder IP Bereiche oder E-Mail Adressen in eine **BlackList** oder **WhiteList** eintragen.
* Nach der Prüfung der IP Adresse stehen Ihnen in allen Templates folgende **InsertTags** zur Verfügung:
  * `{{SpamBot::clientIP}}` IP Adresse die geprüft wurde.
  * `{{SpamBot::Typ}}`´Spam Typ.
  * `{{SpamBot::Engine}}` Spam Provider Name (ggf. mit Link).
  * `{{SpamBot::Status}}` Statusmeldung.

**Besonderheiten**

* Die Suchmaschinen werden parallel abgefragt. Dies erhöht im Vergleich zu anderen Lösungen den Durchsatz ganz erheblich.
* Wir empfehlen ihnen die Module auf der Seitenebene einzubauen.
  * Je nachdem welche / wie viele Provider sie bei der Prüfung einbinden, kann sich die Latenzzeit der Seite verlängern. Durch das Einbinden eines Moduls z.B. nur auf der Registrierungsseite und/oder der Kontaktseite werden alle anderen Seiten schneller angezeigt.
  * Die Verwendung mehrerer Seitenmodule bietet ihnen die Möglichkeit in einem mehrsprachigen Internetauftritt auf eine an die Sprache angepasste Seite um zu lenken.
  * Sie können spezielle Seiten z.B. in einem Intranetauftritt durch die Verwendung von **BlackList** Einträgen nur für bestimmte Benutzer frei schalten (oder ausschließen).
* Sie können **SpamBot-Mail** für jedes beliebige Formular verwenden - denken Sie nur daran, das Modul am Anfang der Seite mit einzubauen und im Formular das Eingabefeld für die E-Mail auch die Eingabeprüfung E-Mail zuzuweisen.

**Testen**

* Wir empfehlen ihnen dringend die Tests mit dem Modul auf einer eigenen für andere Besucher nicht sichtbaren Seite oder auf einer lokalen Kopie ihrer Internetpräsenz durch zu führen (sonst sperren sie womöglich Kunden aus ;-).
* Erlauben sie in der Konfiguration das Speichern von **Ham** (erlaubten) IP Zugriffen.
* Rufen sie die präparierte Site im FrontEnd auf.
* Im BackEnd können sie nun sich den Eintrag ihrer IP / Mail Adresse anschauen.
* Überprüfen sie ihre IP /Mail Adresse bei den von ihnen konfigurierten Anbietern und lassen sich das Ergebnis anzeigen.
* Ändern Sie den Datensatz auf den Typ **Spam**.
* Rufen sie die Seite nochmals auf und überprüfen sie das veränderte Verhalten.

Viel Spaß bei der Benutzung!

<a href="https://www.paypal.com/donate?hosted_button_id=RQMP8CWD2Y2XC" target="_blank">
  <img src="https://www.paypalobjects.com/de_DE/DE/i/btn/btn_donateCC_LG.gif" alt="Donate with PayPal"/>
</a>
<a href="https://community.contao.org/de/showthread.php?36800-SpamBot" target="_blank">
  <img src="https://community.contao.org/de/images/layout/contao_community.svg" alt="Support" width="100" height="50"/>
</a>

## <a name="2"></a>2. English
Block access to your Web Site (singe pages or whole Internet presence) for spam robots. All IP or e-mail addresses are identified as **Spam** (suspicious access) or **Ham** (allowed access) based on **BlackList** or **WhiteList** or provider checks.

You may use one or more of the following providers

* **Intern**
Caching of testing results. Definition of **BlackList** or **WhiteList**.
* **[Spamhaus](https://www.spamhaus.org/)**
Testing IP address.
* **[Honeypot](https://www.projecthoneypot.org/)**
Testing IP address.
* **[StopForumSpam](http://www.stopforumspam.com)**
Testing IP and mail address.
* **[Spam and Open Relay Blocking System (SORBS)](http://www.sorbs.net)**
Testing IP address.
* **[SpamCop](https://www.spamcop.net/)**
Testing IP address.
* **[BlockList](http://www.blocklist.de)**
Testing IP address.
* **[NixSpam](http://www.dnsbl.manitu.net)**
Testing IP address.
* **[UCE Protect](http://www.uceprotect.net)**
Testing IP address (Level 1+2+3).
* **[Abusive Host Blocking List](https://www.ahbl.org)**
Testing IP address.
* **[Weighted Private Block List](http://www.wpbl.info)**
Testing IP address.
* **[BotScout](http://www.botscout.com)**
Testing IP and mail address.
* **[FSpamList](http://www.fspamlist.com)**
Testing IP and mail address.
* **[IPStack](https://ipstack.com/)**
Allow access for selected countries.

**Installation**

* Install Plugin
* Create a new **Frontend module** of typ **SpamBot-IP* *or **SpamBot-Mail**. Configure which provider should be used.
* Create a new page (optional) to which suspicious visitor should be redirected or check and update template **mod_spambot** according to your requirements (existing template supports German and English messages).
* Include module either in **Page layout** or on one or more pages as **Page element**.

**Usage**

* All visitor IP / mail addresses are checked and in case of suspicious visitor (**Spam**) either a message is displayed or visitor is redirected to a preselected page.
* Using a new menu option in BackEnd (in Account management) you can specify addition IP / mail addresses or regular expressions in **BlackList** or **WhiteList**.
* After checking the IP address, the following **InsertTags** are available in all templates:
  * `{{SpamBot::clientIP}}` IP address checked.
  * `{{SpamBot::Typ}}` Spam typ.
  * `{{SpamBot::Engine}}` Spam provider name (probably with link).
  * `{{SpamBot::Status}}` Status message.

**Specifics**

* Search engines were called in parallel. With this special solution operational capacity is enlarged dramatically.
* We recommend using modules only on page level.
  * Depending on which / how many provider you want to use latency time for displaying pages are extended. If you include module e.g. only on registration page and/or on contact page all other pages will be displayed faster.
  * If you use a Internet site with multi-language support you may define multiple Frontend modules with a a language specific redirection page.
  * With this plugin you may lock specific pages from being displayed (in your intranet) using the **BlackList** or allow only specific visitors to see these pages using **WhiteList**.
* You may use **SpamBot-Mail** in any form of your choice. Please don't forget to include modul at top of your page and in your Forms to configure the Inputfield E-Mail with the Configuration check for E-Mails.

**Testing**

* We highly recommend making any tests on a page not visible for other visitors or on a local copy of your Internet site (may bee you will lock or potential customers during testing :-).
* Allow logging of **Ham** IP / mail access.
* Open you prepared page in front end.
* Take a look at the IP / mail address in BackEnd.
* Check IP / mail address with configured provider and analyze result.
* Modify record and change type to **Spam**.
* Reload your modified page in browser and check results.

Please enjoy!

<a href="https://www.paypal.com/donate?hosted_button_id=RQMP8CWD2Y2XC" target="_blank">
  <img src="https://www.paypalobjects.com/en_US/DK/i/btn/btn_donateCC_LG.gif" alt="Donate with PayPal"/>
</a>
<a href="https://community.contao.org/de/showthread.php?36800-SpamBot" target="_blank">
  <img src="https://community.contao.org/de/images/layout/contao_community.svg" alt="Support" width="100" height="50"/>
</a>

