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
define('OSE_WORDPRESS_FIREWALL_SETTING', 'OSE Firewall® Settings');
define('OSE_WORDPRESS_FIREWALL_SETTING_DESC', 'OSE Firewall® is a Web Application Firewall for Wordpress created by <a href="http://www.protect-website.com" target="_blank">Protect Website</a>. It protects your website against attacks and hacking attempts effectively.');
define('NOTIFICATION_EMAIL_ATTACKS', 'Email that receives the notification of attacks');
define('EMAIL_ADDRESS', 'Email Address');
define('FIREWALL_SCANNING_OPTIONS', 'Firewall Scanning Options');
define('BLOCKBL_METHOD', 'Block blacklisted methods (Trace / Delete / Track)');
define('CHECK_MUA', 'Checks Malicious User Agent');
define('checkDOS', 'Checks Basic DoS Attacks');
define('checkDFI', 'Checks Basic Direct File Inclusion');
define('checkRFI', 'Checks Basic Remote File Inclusion');
define('checkJSInjection', 'Checks Basic Javascript Injection');
define('checkSQLInjection', 'Checks Basic Database SQL Injection');
define('checkTrasversal', 'Detect Directory Traversal');
define('ADVANCE_SETTING', 'Advanced Setting');
define('OTHER_SETTING', 'Other Setting');
define('BLOCK_QUERY_LONGER_THAN_255CHAR', 'Block Queries longer than 255 characters');
define('BLOCK_PAGE', 'Block page shown to attackers');
define('OSE_BAN_PAGE', 'Use OSE ban page');
define('BLANK_PAGE', 'Show a blank page');
define('ERROR403_PAGE', 'Show a 403 error page');
define('TEST_CONFIGURATION', 'Test your configuration');
define('TEST_CONFIGURATION_NOW', 'Test your configuration now!');
define('SAVE_CHANGES', 'Save Changes');
define('WHITELIST_VARS', 'Whitelisted Variables (please use a comma "," to separate the variables.)');
define('BLOCK_MESSAGE', 'Your request has been blocked!');
define('FOUNDBL_METHOD', 'Found blacklisted methods (Trace / Delete / Track)');
define('FOUNDMUA', 'Found Malicious User Agent');
define('FOUNDDOS', 'Found Basic DoS Attacks');
define('FOUNDDFI', 'Found Basic Direct File Inclusion');
define('FOUNDRFI', 'Found Basic Remote File Inclusion');
define('FOUNDJSInjection', 'Found Basic Javascript Injection');
define('FOUNDSQLInjection', 'Found Basic Database SQL Injection');
define('FOUNDTrasversal', 'Found Directory Traversal');
define('FOUNDQUERY_LONGER_THAN_255CHAR', 'Found Queries longer than 255 characters');
define('MAX_TOLERENCE', 'Maximum tolerence for an attack');
// Langauges for version 1.5 + start from here;
define('OSE_SCANNING_SETTING','Scanning setting');
define('SERVERIP','Your server IP (to avoid false alerts due to empty user agent)');
define('OSE_WORDPRESS_FIREWALL_CONFIG','OSE Firewall™ Configuration');
define('OSE_WORDPRESS_VIRUSSCAN_CONFIG','OSE Virus Scanner™ Configuration');
define('OSE_WORDPRESS_VIRUSSCAN_CONFIG_DESC','Please configure your virus scanning parameters here.');
define('START_DB_INIT','Initialise Database');
define('STOP_DB_INIT','Stop Action');
define('START_NEW_VIRUSSCAN','Start New Scan');
define('CONT_VIRUSSCAN','Continue Previous Scan');
define('OSE_SCANNED','OSE Firewall has scanned');
define('OSE_FOLDERS','folders');
define('OSE_AND','and');
define('OSE_FILES','files');
define('OSE_INFECTED_FILES','infected files');
define('OSE_INTOTAL','in total of');
define('OSE_THERE_ARE','There are');
define('OSE_IN_DB','in the database');
define('OSE_VIRUS_SCAN','OSE Virus Scanner™');
define('OSE_VIRUS_SCAN_DESC','OSE WordPress Virus Scanner™ aims to scan and clean WordPress malicious codes and monitors your website on a 24/7 basis.');
define('CUSTOM_BANNING_MESSAGE','Custom banning message');
define('FILEEXTSCANNED','File extensions being scanned');
define('DONOTSCAN','Do not scan files greater than (unit: Megabytes)');
define('PLEASE_CHOOSE_OPTION','Please choose an option');
define('COMPATIBILITY','Compatibility');
define('OSE_PLEASE_CONFIG_FIREWALL','Please configure the firewall setting here.');	
define('OSE_FOLLOWUS','Follow us to keep updated.');
define('OSE_ID_INFO','OSE Account information (please ONLY fill in your account when you are an advanced / professional member).');	
define('OSE_ID','OSE ID (Username in OSE Security Website).');
define('OSE_PASS','OSE Password (Password in OSE Security Website).');
define('OSE_SCAN_SUMMARY','Scan Summary');
define('OSE_SCAN_ACTIVITY','Scan Detailed Activity');
define('OSE_WEBSITE_PROTECTED_BY','This website is protected by');
define('OSE_PROTECTION_MODE','Protection Mode');
define('OSE_FIREWALL_ONLY','Protected by OSE Firewall Only');
define('OSE_SECSUITE_ONLY','Protected by OSE Security Suite Only');
define('OSE_FWANDSUITE','Protected by OSE Firewall & OSE Security Suite');
define('OSE_SUITE_PATH','Absolute path of OSE Security Suite.<br/>e.g. /home/youraccount/public_html/osesecurity/ <br/> (Please ensure you have installed <a href ="https://www.opensource-excellence.com/shop/ose-security-suite.html" target="_blank">OSE Security Suite</a> already)');
define('NEED_HELP_CLEANING','Need help cleaning?');
define('NEED_HELP_CLEANING_DESC','Viruses are changing over time. Our patterns might not be updated to scan the latest malicious files in your infected system. In this case, please consider to hire our <a href="https://www.opensource-excellence.com/service/removal-of-malware.html" target="_blank" >malware removal service</a>. The new patterns found in your website will be contributed to the community to help other users.');
define('OSE_DEVELOPMENT','Development mode (temporarily turn off protection)');
// Langauges for version 1.6 + start from here;
define('OSE_ENABLE_SFSPAM','Enable Stop Forum Spam Scanning');
define('OSE_YES','Yes');
define('OSE_NO','No');
define('OSE_SFSPAM_API','Stop Forum Spam API key');
define('SFSPAMIP','Stop Forum Spam IP');
define('OSE_SFS_CONFIDENCE','Confidence Level (between 1 and 100, the higher the more likely a spam)');
define('OSE_SHOW_BADGE','Show Website Protection Seal <br/>(Please use Virus scanner to scan your website first)');
// Languages for version 2.0 start from here:
define('DBNOTREADY','Warning: the database is not ready, plesea click the install button to create the database table.');
define('DASHBOARD_TITLE','Dash<span>board</span>');
define('INSTALLDB','Install');
define('UPDATEVERSION', 'Update');
define('SUBSCRIBE', 'Subscribe');
define('READYTOGO','Everything is ready to go!');
define('CREATE_BASETABLE_COMPLETED',' > Create Base Table Completed, continue...');
define('INSERT_CONFIGCONTENT_COMPLETED',' > Inserting Configuration Data Completed, continue...');
define('INSERT_EMAILCONTENT_COMPLETED',' > Inserting Email Content Completed, continue...');
define('INSTALLATION_COMPLETED',' > Database Installation Completed.');
define('INSERT_ATTACKTYPE_COMPLETED',' > Attack Type Information Installation Completed, continue...');
define('INSERT_BASICRULESET_COMPLETED',' > Basic Ruleset Installation Completed, continue...');
define('CREATE_IPVIEW_COMPLETED',' > IP-ACL Mapping View Creation Completed, continue...');
define('CREATE_ADMINEMAILVIEW_COMPLETED',' > Admin-Email Mapping View Creation Completed, continue...');
define('CREATE_ATTACKMAPVIEW_COMPLETED',' > ACL-Attack Mapping View Creation Completed, continue...');
define('CREATE_ATTACKTYPESUMEVIEW_COMPLETED',' > Attack Type Mapping View Creation Completed, continue...');
define('INSERT_STAGE1_GEOIPDATA_COMPLETED',' > GeoIP Data Stage 1 Installation Completed, continue...');
define('INSERT_STAGE2_GEOIPDATA_COMPLETED',' > GeoIP Data Stage 2 Installation Completed, continue...');
define('INSERT_STAGE3_GEOIPDATA_COMPLETED',' > GeoIP Data Stage 3 Installation Completed, continue...');
define('INSERT_STAGE4_GEOIPDATA_COMPLETED',' > GeoIP Data Stage 4 Installation Completed, continue...');
define('INSERT_STAGE5_GEOIPDATA_COMPLETED',' > GeoIP Data Stage 5 Installation Completed, continue...');
define('INSERT_STAGE6_GEOIPDATA_COMPLETED',' > GeoIP Data Stage 6 Installation Completed, continue...');
define('INSERT_STAGE7_GEOIPDATA_COMPLETED',' > GeoIP Data Stage 7 Installation Completed, continue...');
define('INSERT_VSPATTERNS_COMPLETED',' > Virus Patterns Insertion Completed, continue...');
define('MANAGEIPS_TITLE','IP <span>Management</span>');
define('MANAGEIPS_DESC','The Panel to Manage your IPs');
define('IP_EMPTY','IP is empty');
define('IP_INVALID_PLEASE_CHECK','The IP is invalid, please check if your any of your octets is greater than 255');
define('IP_RULE_EXISTS','The Access Control Rules for this IP / IP Range already exists.');
define('IP_RULE_ADDED_SUCCESS','The Access Control Rules for this IP / IP Range was added successfully.');
define('IP_RULE_ADDED_FAILED','The Access Control Rules for this IP / IP Range was added unsuccessfully.');
define('IP_RULE_DELETE_SUCCESS','The Access Control Rules for this IP / IP Range was removed successfully.');
define('IP_RULE_DELETE_FAILED','The Access Control Rules for this IP / IP Range was removed unsuccessfully.');
define('IP_RULE_CHANGED_SUCCESS','The Access Control Rules for this IP / IP Range has been changed successfully.');
define('IP_RULE_CHANGED_FAILED','The Access Control Rules for this IP / IP Range has been changed unsuccessfully.');
define('MANAGE_IPS','Manage IPs');
define('RULESETS','Manage Rulesets');
define('MANAGERULESETS_TITLE','Manage Rulesets');
define('MANAGERULESETS_DESC','The Panel to Manage your Rules');
define('ITEM_STATUS_CHANGED_SUCCESS','The status of the item has been changed successfully');
define('ITEM_STATUS_CHANGED_FAILED','The status of the item was changed unsuccessfully');
define('CONFIGURATION','Configuration');
define('CONFIGURATION_TITLE','Configuration <span>Panel</span>');
define('CONFIGURATION_DESC','The Configuration Panel to Change Setting of The App');
define('SEO_CONFIGURATION','SEO Configuration');
define('SEO_CONFIGURATION_TITLE','Search Engine <span>Configuration Panel</span>');
define('SEO_CONFIGURATION_DESC','The Configuration Panel to Change SEO Related Setting');
define('CONFIG_SAVE_SUCCESS','The configuration was saved successfully.');
define('CONFIG_SAVE_FAILED','The configuration was saved successfully.');
define('SCAN_CONFIGURATION','Scanning Configuration');
define('SCAN_CONFIGURATION_TITLE','Firewall Scanning <span>Configuration</span>');
define('SCAN_CONFIGURATION_DESC','The Configuration Panel to Change Firewall Related Setting');
define('ANTISPAM_CONFIGURATION','OSE Anti-Spam™ Configuration');
define('ANTISPAM_CONFIGURATION_TITLE','OSE Anti-Spam™ <span>Configuration</span>');
define('ANTISPAM_CONFIGURATION_DESC','The Configuration Panel to Change Anti-Spamming Related Setting');
define('EMAIL_CONFIGURATION','Email Configuration');
define('EMAIL_CONFIGURATION_TITLE','Email <span>Configuration</span>');
define('EMAIL_CONFIGURATION_DESC','The Configuration Panel to Add / Edit Email Templates');
define('EMAIL_TEMPLATE_UPDATED_SUCCESS','The email template has been changed successfully.');
define('EMAIL_TEMPLATE_UPDATED_FAILED','The email template was changed unsuccessfully.');
define('EMAIL_ADMIN','Admin-Email Mapping');
define('EMAIL_ADMIN_TITLE','Administrator-Email <span>Mapping</span>');
define('EMAIL_ADMIN_DESC','The Configuration Panel to Configure Which Administrator to Receive Emails');
define('LINKAGE_ADDED_SUCCESS','The linkage has been added successfully.');
define('LINKAGE_ADDED_FAILED','The linkage was added unsuccessfully.');
define('LINKAGE_DELETED_SUCCESS','The linkage has been deleted successfully.');
define('LINKAGE_DELETED_FAILED','The linkage was deleted unsuccessfully.');
define('ANTIVIRUS_CONFIGURATION','OSE Virus Scanner™ Configuration');
define('ANTIVIRUS_CONFIGURATION_TITLE','OSE Virus Scanner™ <span>Configuration</span>');
define('ANTIVIRUS_CONFIGURATION_DESC','The Configuration Panel to Change Virus Scanner Related Setting');
define('ANTIVIRUS','OSE Virus Scanner™');
define('ANTIVIRUS_TITLE','OSE Virus Scanner™ <span>Panel</span>');
define('ANTIVIRUS_DESC','The Panel to Scan Virus / Malicious Codes In Your Website');
define('LAST_SCANNED','Last scanned folder: ');
define('LAST_SCANNED_FILE','Last scanned file: ');
define('OSE_FOUND',OSE_WORDPRESS_FIREWALL.' found');
define('OSE_ADDED',OSE_WORDPRESS_FIREWALL.' added');
define('IN_THE_LAST_SCANNED','in the last scan,');
define('O_CONTINUE','continue...');
define('SCANNED_PATH_EMPTY','Please make sure the scanned path is not empty.');
define('O_PLS', 'Please');
define('O_SHELL_CODES', 'Shell Codes');
define('O_BASE64_CODES', 'Base64 Encoded Codes');
define('O_JS_INJECTION_CODES', 'Javascript Injection Codes');
define('O_PHP_INJECTION_CODES', 'PHP Injection Codes');
define('O_IFRAME_INJECTION_CODES', 'iFrame Injection Codes');
define('O_SPAMMING_MAILER_CODES', 'Spamming Mailer Codes');
define('O_EXEC_MAILICIOUS_CODES','Executable Malicious Codes');
define('O_OTHER_MAILICIOUS_CODES','Other Miscellaneous Malicious Codes');
define('WEBSITE_CLEAN','Website is clean');
define('COMPLETED','Completed');
define('YOUR_SYSTEM_IS_CLEAN','Your system is clean.');
define('VSREPORT','Scan Report');
define('SCANREPORT_TITLE','OSE Virus Scan Report');
define('SCANREPORT_DESC','The Report Panel to View Detected Infected Files');
define('VARIABLES','Variables');
define('VARIABLES_TITLE','Variables Management');
define('VARIABLES_DESC','The Panel to Activate/Deactivate Variables in Scanning');
define('MANAGE_VARIABLES','Manage Variables');
define('VIRUS_SCAN_REPORT','Virus Scanning Report');
define('VERSION_UPDATE', 'Anti-Virus Database Update');
define('VERSIONUPDATE_DESC', 'The Panel is to Update Virus Database');
define('ANTI_VIRUS_DATABASE_UPDATE', 'Anti-Virus Database Update');
define('VERSION_UPDATE_TITLE', 'OSE Version Update Panel');
define('VERSION_UPDATE_DESC', 'The panel is to update your local anti-virus database');
define('CHECK_UPDATE_VERSION', 'Connecting with server and Checking update version...');
define('START_UPDATE_VERSION', 'Start downloading updates...');
define('UPDATE_COMPLETED', 'Update Completed!');
define('CHECK_UPDATE_RULE', 'Checking update rule...');
define('ALREADY_UPDATED', 'Already updated today');
define('UPDATE_LOG', 'Updating Log...');
?>