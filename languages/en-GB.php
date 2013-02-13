<?php
defined('OSEFWDIR') or die;
//Start here;
define('OSE_WORDPRESS_FIREWALL', 'OSE Firewall™');
define('OSE_WORDPRESS_FIREWALL_SETTING', 'OSE Firewall™ Settings');
define('OSE_WORDPRESS_FIREWALL_SETTING_DESC', 'OSE Firewall™ is a Web Application Firewall for Wordpress created by <a href="http://www.opensource-excellence.com" target="_blank">Open Source Excellence</a>. It protects your website against attacks and hacking attempts effectively.');
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

// Langauges for version 2.0 + start from here;
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
define('OSE_INIT','OSE Firewall has initiated');
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
define('OSE_FIREWALL_ONLY','Procted by OSE Firewall Only');
define('OSE_SECSUITE_ONLY','Procted by OSE Security Suite Only');
define('OSE_FWANDSUITE','Procted by OSE Firewall & OSE Security Suite');
define('OSE_SUITE_PATH','Absolute path of OSE Security Suite.<br/>e.g. /home/youraccount/public_html/osesecurity/ <br/> (Please ensure you have installed <a href ="https://www.opensource-excellence.com/shop/ose-security-suite.html" target="_blank">OSE Security Suite</a> already)');
define('NEED_HELP_CLEANING','Need help cleaning?');
define('NEED_HELP_CLEANING_DESC','Viruses are changing over time. Our patterns might not be updated to scan the latest malicious files in your infected system. In this case, please consider to hire our <a href="https://www.opensource-excellence.com/service/removal-of-malware.html" target="_blank" >malware removal service</a>. The new patterns found in your website will be contributed to the community to help other users.');
define('OSE_DEVELOPMENT','Development mode (temporarily turn off protection)'); 