<?php
/**
* @version     2.0 +
* @package       Open Source Excellence Security Suite
* @subpackage    Open Source Excellence WordPress Firewall
* @author        Open Source Excellence {@link http://www.opensource-excellence.com}
* @author        Created on 01-Jun-2013
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*
*
*  This program is free software: you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation, either version 3 of the License, or
*  (at your option) any later version.
*
*  This program is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  You should have received a copy of the GNU General Public License
*  along with this program.  If not, see <http://www.gnu.org/licenses/>.
*  @Copyright Copyright (C) 2008 - 2012- ... Open Source Excellence
*/
defined('OSEFWDIR') or die;
//Start here;
define('OSE_WORDPRESS_FIREWALL', 'OSE Firewall®');
define('OSE_WORDPRESS_FIREWALL_SETTING', 'OSE Firewall® Einstellungen');
define('OSE_WORDPRESS_FIREWALL_SETTING_DESC', 'OSE Firewall® ist eine Web Application Firewall für Wordpress - entwickelt von <a href="http://www.protect-website.com" target="_blank">Protect Website</a>. Sie schützt deine Webseite effektiv gegen Angriffe und Hacking-Versuche.');
define('NOTIFICATION_EMAIL_ATTACKS', 'E-Mail, die die Benachrichtigung über Angriffe empfängt');
define('EMAIL_ADDRESS', 'E-Mail-Adresse');
define('FIREWALL_SCANNING_OPTIONS', 'Firewall-Scan-Optionen');
define('BLOCKBL_METHOD', 'Sperr-Methoden auf der schwarzen Liste (Zurückführen / Löschen / Verfolgen)');
define('CHECK_MUA', 'Kontrolliere böswilligen User Agent');
define('checkDOS', 'Kontrolliere Basic DoS-Angriff');
define('checkDFI', 'Kontrolliere Basic Direct File Inclusion');
define('checkRFI', 'Kontrolliere Basic Remote File Inclusion');
define('checkJSInjection', 'Kontrolliere Basic Javascript Injection');
define('checkSQLInjection', 'Kontrolliere Basic Database SQL Injection');
define('checkTrasversal', 'Erkenne Directory Traversal');
define('ADVANCE_SETTING', 'Erweiterte Einstellungen');
define('OTHER_SETTING', 'Andere Einstellung');
define('BLOCK_QUERY_LONGER_THAN_255CHAR', 'Sperre Queries länger als 255 Zeichen');
define('BLOCK_PAGE', 'Sperrseite, die Angreifern gezeigt wird');
define('OSE_BAN_PAGE', 'Verwende OSE Sperrseite');
define('BLANK_PAGE', 'Zeige weiße Seite');
define('ERROR403_PAGE', 'Zeige eine 403 Fehlerseite');
define('TEST_CONFIGURATION', 'Konfiguration testen');
define('TEST_CONFIGURATION_NOW', 'Konfiguration jetzt testen!');
define('SAVE_CHANGES', 'Änderungen speichern');
define('WHITELIST_VARS', 'Whitelist-Variabeln (verwende bitte ein Komma "," um die Variablen zu trennen.)');
define('BLOCK_MESSAGE', 'Ihre Anfrage wurde gesperrt!');
define('FOUNDBL_METHOD', 'Blacklist-Methoden gefunden ((Zurückführen / Löschen / Verfolgen)');
define('FOUNDMUA', 'Malicious User Agent gefunden');
define('FOUNDDOS', 'Basic DoS Attacks gefunden');
define('FOUNDDFI', 'Basic Direct File Inclusion gefunden');
define('FOUNDRFI', 'Basic Remote File Inclusion gefunden');
define('FOUNDJSInjection', 'Basic Javascript Injection gefunden');
define('FOUNDSQLInjection', 'Basic Database SQL Injection gefunden');
define('FOUNDTrasversal', 'Directory Traversal gefunden');
define('FOUNDQUERY_LONGER_THAN_255CHAR', 'Queries länger als 255 Zeichen gefunden');
define('MAX_TOLERENCE', 'Maximale Toleranz für einen Angriff');
// Langauges for version 1.5 + start from here;
define('OSE_SCANNING_SETTING','Scan-Einstellung');
define('SERVERIP','Ihre Server-IP (zur Vermeidung von Fehlalarmen durch leeren User Agent)');
define('OSE_WORDPRESS_FIREWALL_CONFIG','OSE Firewall™ Konfiguration');
define('OSE_WORDPRESS_VIRUSSCAN_CONFIG','OSE Virus Scanner™ Konfiguration');
define('OSE_WORDPRESS_VIRUSSCAN_CONFIG_DESC','Bitte konfiguriere deine Virenschutz-Parameter hier.');
define('START_DB_INIT','Datenbank initialisieren');
define('STOP_DB_INIT','Aktion stoppen');
define('START_NEW_VIRUSSCAN','Neuen Scan starten');
define('CONT_VIRUSSCAN','Scan fortsetzten');
define('OSE_SCANNED','OSE Firewall hat gescannt');
define('OSE_FOLDERS','Ordner');
define('OSE_AND','und');
define('OSE_FILES','Dateien');
define('OSE_INFECTED_FILES','infizierte Dateien');
define('OSE_INTOTAL','insgesamt');
define('OSE_THERE_ARE','Es gibt');
define('OSE_IN_DB','in der Datenbank');
define('OSE_VIRUS_SCAN','OSE Virus Scanner™');
define('OSE_VIRUS_SCAN_DESC','OSE WordPress Virus Scanner™ scannt und säubert WordPress von schädlichem Code und überwacht deine Webseite rund um die Uhr.');
define('CUSTOM_BANNING_MESSAGE','Eigene Sperrmeldung');
define('FILEEXTSCANNED','Datei-Erweiterungen, die gescannt werden');
define('DONOTSCAN','Keine Dateien scannen, die größer sind als (Megabyte)');
define('PLEASE_CHOOSE_OPTION','Bitte wähle eine Option');
define('COMPATIBILITY','Kompatibilität');
define('OSE_PLEASE_CONFIG_FIREWALL','Bitte konfiguriere die Firewall Einstellungen hier.');	
define('OSE_FOLLOWUS','Folge uns auf:');
define('OSE_ID_INFO','OSE Kontoinformationen (bitte nur ausfüllen, wenn du ein kommerzieller Kunde bist).');	
define('OSE_ID','OSE ID (Benutzername für die OSE Security Webseite).');
define('OSE_PASS','OSE Passwort (Passwort für OSE Security Webseite).');
define('OSE_SCAN_SUMMARY','Scan-Zusammenfassung');
define('OSE_SCAN_ACTIVITY','Detaillierte Scan-Aktivität');
define('OSE_WEBSITE_PROTECTED_BY','Diese Webseite wird geschützt durch');
define('OSE_PROTECTION_MODE','Schutz-Modus');
define('OSE_FIREWALL_ONLY','Nur durch OSE Firewall geschützt');
define('OSE_SECSUITE_ONLY','Nur durch OSE Security Suite geschützt');
define('OSE_FWANDSUITE','Durch OSE Firewall & OSE Security Suite geschützt');
define('OSE_SUITE_PATH','Absolute Pfad zur OSE Security Suite.<br/>z. B. /home/youraccount/public_html/osesecurity/ <br/> (Bitte stelle sicher, dass du die <a href ="https://www.opensource-excellence.com/shop/ose-security-suite.html" target="_blank">OSE Security Suite</a> bereits installiert hast.)');
define('NEED_HELP_CLEANING','Benötigst du Hilfe bei der Säuberung?');
define('NEED_HELP_CLEANING_DESC','Viren verändern sich im Laufe der Zeit. Unsere Muster werden möglicherweise nicht aktualisiert, um bösartige Dateien im infizierten System zu erkennen. Wir empfehlen in diesem Fall unseren <a href="https://www.opensource-excellence.com/service/removal-of-malware.html" target="_blank" >Malware-Entfernungs-Dienst</a> zu nutzen. Sofern neue Muster in deiner Webseite gefunden werden, werden diese der Community zur Verfügung gestellt und helfen so auch anderen Nutzern.');
define('OSE_DEVELOPMENT','Entwicklungsmodus (vorübergehend Schutz deaktivieren)');
// Langauges for version 1.6 + start from here;
define('OSE_ENABLE_SFSPAM','Stopp Forum Spam-Erkennung aktivieren');
define('OSE_YES','Ja');
define('OSE_NO','Nein');
define('OSE_SFSPAM_API','Stopp Forum Spam API Key');
define('SFSPAMIP','Stopp Forum Spam IP');
define('OSE_SFS_CONFIDENCE','Vertrauensstufe (zwischen  1 und 100, je höher desto wahrscheinlicher ist es Spam))');
define('OSE_SHOW_BADGE','Zeige Schutz-Siegel auf Webseite <br/>(Bitte benutze zuvor den Virenscanner, um deine Webseite zuerst zu scannen)');
// Languages for version 2.0 start from here:
define('DBNOTREADY','Warnung: Die Datenbank ist nicht bereit! Bitte  klicke auf die Schaltfläche "Installieren", um die Datenbanktabelle zu erstellen.');
define('DASHBOARD_TITLE','<span>Übersicht</span>');
define('INSTALLDB','Installieren');
define('UPDATEVERSION', 'Aktualisieren');
define('SUBSCRIBE', 'Abonnieren');
define('READYTOGO','Alles klar, los geht`s!');
define('CREATE_BASETABLE_COMPLETED',' > Erstellen der Basistabelle abgeschlossen, weiter...');
define('INSERT_CONFIGCONTENT_COMPLETED',' > Einsetzten der Konfigurationsdaten abgeschlossen, weiter...');
define('INSERT_EMAILCONTENT_COMPLETED',' > Einsetzten der E-Mail-Inhalte  abgeschlossen, weiter...');
define('INSTALLATION_COMPLETED',' > Datenbankinstallation abgeschlossen.');
define('INSERT_ATTACKTYPE_COMPLETED',' > Installation der Angriffstyp-Informationen abgeschlossen, weiter...');
define('INSERT_BASICRULESET_COMPLETED',' > Installation der Basisregeln abgeschlossen, weiter...');
define('CREATE_IPVIEW_COMPLETED',' > Erstellen der Ansicht für die IP-ACL-Zuordnung abgeschlossen, weiter...');
define('CREATE_ADMINEMAILVIEW_COMPLETED',' > Erstellen der Ansicht für die Admin-E-Mail-Zuordnung abgeschlossen, weiter...');
define('CREATE_ATTACKMAPVIEW_COMPLETED',' > Erstellen der Ansicht für die ACL-Angriffs-Zuordnung abgeschlossen, weiter...');
define('CREATE_ATTACKTYPESUMEVIEW_COMPLETED',' > Erstellen der Ansicht für die Angriffstyp-Zuordnung abgeschlossen, weiter...');
define('INSERT_STAGE1_GEOIPDATA_COMPLETED',' > Installation der GeoIP-Daten Abschnitt 1 abgeschlossen, weiter...');
define('INSERT_STAGE2_GEOIPDATA_COMPLETED',' > Installation der GeoIP-Daten Abschnitt 2 abgeschlossen, weiter...');
define('INSERT_STAGE3_GEOIPDATA_COMPLETED',' > Installation der GeoIP-Daten Abschnitt 3 abgeschlossen, weiter...');
define('INSERT_STAGE4_GEOIPDATA_COMPLETED',' > Installation der GeoIP-Daten Abschnitt 4 abgeschlossen, weiter...');
define('INSERT_STAGE5_GEOIPDATA_COMPLETED',' > Installation der GeoIP-Daten Abschnitt 5 abgeschlossen, weiter...');
define('INSERT_STAGE6_GEOIPDATA_COMPLETED',' > Installation der GeoIP-Daten Abschnitt 6 abgeschlossen, weiter...');
define('INSERT_STAGE7_GEOIPDATA_COMPLETED',' > Installation der GeoIP-Daten Abschnitt 7 abgeschlossen, weiter...');
define('INSERT_VSPATTERNS_COMPLETED',' > Einsetzen der Virus-Muster abgeschlossen, weiter...');
define('MANAGEIPS_TITLE','IP-<span>Verwaltung</span>');
define('MANAGEIPS_DESC','Übersicht zur Verwaltung deiner IPs');
define('IP_EMPTY','IP ist leer');
define('IP_INVALID_PLEASE_CHECK','Die IP-Adresse ist ungültig, bitte prüfe ob eines der Oktette größer ist als 255 Zeichen.');
define('IP_RULE_EXISTS','Die Zugriffsregeln für diese IP-Adresse / diesen IP-Adressbereich sind bereits vorhanden.');
define('IP_RULE_ADDED_SUCCESS','Die Zugriffsregeln für diese IP-Adresse / diesen IP-Adressbereich wurden erfolgreich hinzugefügt.');
define('IP_RULE_ADDED_FAILED','Die Zugriffsregeln für diese IP-Adresse / diesen IP-Adressbereich konnten nicht erfolgreich hinzugefügt werden.');
define('IP_RULE_DELETE_SUCCESS','Die Zugriffsregeln für diese IP-Adresse / diesen IP-Adressbereich wurden erfolgreich entfernt.');
define('IP_RULE_DELETE_FAILED','Die Zugriffsregeln für diese IP-Adresse / diesen IP-Adressbereich konnten nicht erfolgreich entfernt werden.');
define('IP_RULE_CHANGED_SUCCESS','Die Zugriffsregeln für diese IP-Adresse / diesen IP-Adressbereich wurden erfolgreich geändert.');
define('IP_RULE_CHANGED_FAILED','Die Zugriffsregeln für diese IP-Adresse / diesen IP-Adressbereich konnten nicht erfolgreich geändert werden.');
define('MANAGE_IPS','IPs verwalten');
define('RULESETS','Regelsätze verwalten');
define('MANAGERULESETS_TITLE','Regelsatz verwalten');
define('MANAGERULESETS_DESC','Übersicht zur Verwaltung deiner Regelsätze');
define('ITEM_STATUS_CHANGED_SUCCESS','Der Status der Regel wurde erfolgreich geändert.');
define('ITEM_STATUS_CHANGED_FAILED','Der Status des Regel konnte nicht geändert werden.');
define('CONFIGURATION','Konfiguration');
define('CONFIGURATION_TITLE','Konfiguration-<span>Übersicht</span>');
define('CONFIGURATION_DESC','Konfigurations-Übersicht, um Einstellungen der Web Application Firewall zu ändern.');
define('SEO_CONFIGURATION','SEO-Konfiguration');
define('SEO_CONFIGURATION_TITLE','Suchmaschinen-<span>Konfigurations-Übersicht</span>');
define('SEO_CONFIGURATION_DESC','Die Konfigurations-Übersicht, um SEO-relevante Einstellungen zu ändern.');
define('CONFIG_SAVE_SUCCESS','Die Konfiguration wurde erfolgreich gespeichert.');
define('CONFIG_SAVE_FAILED','Die Konfiguration konnte nicht gespeichert werden.');
define('SCAN_CONFIGURATION','Scan-Konfiguration');
define('SCAN_CONFIGURATION_TITLE','Firewall-Scan-<span>Konfiguration</span>');
define('SCAN_CONFIGURATION_DESC','Die Konfigurations-Übersicht, um Firewall-relevante Einstellungen zu ändern.');
define('ANTISPAM_CONFIGURATION','OSE Anti-Spam™ Konfiguration');
define('ANTISPAM_CONFIGURATION_TITLE','OSE Anti-Spam™ <span>Konfiguration</span>');
define('ANTISPAM_CONFIGURATION_DESC','Die Konfiguration-Übersicht, um Anti-Spam-relevante Einstellungen zu ändern.');
define('EMAIL_CONFIGURATION','E-Mail-Konfiguration');
define('EMAIL_CONFIGURATION_TITLE','E-Mail-<span>Konfiguration</span>');
define('EMAIL_CONFIGURATION_DESC','Die Konfigurations-Übersicht, um The Konfiguration zum Hinzufügen / Bearbeiten von E-Mail-Vorlagen.');
define('EMAIL_TEMPLATE_UPDATED_SUCCESS','Die E-Mail-Vorlage wurde erfolgreich geändert.');
define('EMAIL_TEMPLATE_UPDATED_FAILED','Die E-Mail-Vorlage konnte nicht geändert werden.');
define('EMAIL_ADMIN','Admin-E-Mail-Zuordnung');
define('EMAIL_ADMIN_TITLE','Administrator-E-Mail <span>Zuordnung</span>');
define('EMAIL_ADMIN_DESC','Die Konfigurations-Übersicht, um zu konfigurieren, welcher Administrator welche E-Mails empfangen soll.');
define('LINKAGE_ADDED_SUCCESS','Die Verbindung wurde erfolgreich hinzugefügt.');
define('LINKAGE_ADDED_FAILED','Die Verbindung konnte nicht hinzugefügt werden.');
define('LINKAGE_DELETED_SUCCESS','Die Verbindung wurde erfolgreich gelöscht.');
define('LINKAGE_DELETED_FAILED','Die Verbindung konnte nicht gelöscht werden.');
define('ANTIVIRUS_CONFIGURATION','OSE Virus Scanner™ Konfiguration');
define('ANTIVIRUS_CONFIGURATION_TITLE','OSE Virus Scanner™ <span>Konfiguration</span>');
define('ANTIVIRUS_CONFIGURATION_DESC','Die Konfigurations-Übersicht, um Virus Scanner-relevante Einstellungen zu ändern.');
define('ANTIVIRUS','OSE Virus Scanner™');
define('ANTIVIRUS_TITLE','OSE Virus Scanner™ <span>Übersicht</span>');
define('ANTIVIRUS_DESC','Die Übersicht, um nach Viren / böswilligem Code in deiner Webseite zu suchen.');
define('LAST_SCANNED','Zuletzt gescannten Ordner: ');
define('LAST_SCANNED_FILE','Zuletzt gescannte Datei: ');
define('OSE_FOUND',OSE_WORDPRESS_FIREWALL.' gefunden');
define('OSE_ADDED',OSE_WORDPRESS_FIREWALL.' hinzugefügt');
define('IN_THE_LAST_SCANNED','in der letzten Überprüfung,');
define('O_CONTINUE','weiter...');
define('SCANNED_PATH_EMPTY','Bitte achte darauf, dass der zu scannende Pfad nicht leer ist.');
define('O_PLS', 'Bitte');
define('O_SHELL_CODES', 'Shell Codes');
define('O_BASE64_CODES', 'Base64 Encoded Codes');
define('O_JS_INJECTION_CODES', 'Javascript Injection Codes');
define('O_PHP_INJECTION_CODES', 'PHP Injection Codes');
define('O_IFRAME_INJECTION_CODES', 'IFrame Injection Codes');
define('O_SPAMMING_MAILER_CODES', 'Spamming Mailer Codes');
define('O_EXEC_MAILICIOUS_CODES','Executable Malicious Codes');
define('O_OTHER_MAILICIOUS_CODES','Anderer Miscellaneous Malicious Codes');
define('WEBSITE_CLEAN','Die Webseite ist sauber.');
define('COMPLETED','Abgeschlossen');
define('YOUR_SYSTEM_IS_CLEAN','Dein System ist sauber.');
define('VSREPORT','Scan-Bericht');
define('SCANREPORT_TITLE','OSE Virus Scan-Bericht');
define('SCANREPORT_DESC','Berichts-Übersicht, um gefundene infizierte Dateien anzuzeigen.');
define('VARIABLES','Variablen');
define('VARIABLES_TITLE','Variablen-Verwaltung');
define('VARIABLES_DESC','Übersicht, um die Variablen fürs Scannen zu aktivieren/deaktivieren.');
define('MANAGE_VARIABLES','Variablen verwalten');
define('VIRUS_SCAN_REPORT','Virus Scan-Bericht');
define('VERSION_UPDATE', 'Datenbank-Aktualisierung');
define('VERSIONUPDATE_DESC', 'Übersicht über die Versions-Aktualisierung.');
define('ANTI_VIRUS_DATABASE_UPDATE', 'Anti-Virus Datenbank-Aktualisierung');
define('VERSION_UPDATE_TITLE', 'OSE Versions-Aktualisierungs-Übersicht');
define('VERSION_UPDATE_DESC', 'Übersicht über die Anti-Virus-Datenbank-Aktualisierung.');
define('CHECK_UPDATE_VERSION', 'Verbinden mit Server und Überprüfen nach neuer Datenbank-Version...');
define('START_UPDATE_VERSION', 'Beginn mit Herunterladen von Aktualierungen...');
define('UPDATE_COMPLETED', 'Aktualisierung abgeschlossen!');
define('CHECK_UPDATE_RULE', 'Prüfe Regeln für Aktualisierung...');
define('ALREADY_UPDATED', 'Heute bereits aktualisiert.');
define('UPDATE_LOG', 'Aktualisiere Protokoll...');
?>