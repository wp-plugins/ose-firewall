=== Centrora Security™===
Contributors: osexcel, ProHelix, ProKai, ProChase
Contributors: Open Source Excellence
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=PE4MSVEGBLLKE
Tags: better wordpress security, admin, anti-spam, comments, anti-hack, anti-virus, firewall,plugin, virus cleaning, google, Google authenticator, country block
Requires at least: 3.7
Tested up to: 3.9.1
Stable tag: 3.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Protect your WordPress site with Centrora Security. Also recommended for multiple sites security management. 

== Description ==

= MOST POWERFUL WORDPRESS SECURITY PLUGIN =
Centrora Security is a new plugin that modified from OSE Firewall Security. A WordPress Firewall Security to protect your WordPress Sites from attacks and hacking. The built-in Malware and Security Scanner helps you identify any security risks, malicious codes, spam, virus, SQL injection, and security vulnerabilities. 

= Are you managing more than one websites? =
You can now manage all your WordPress sites with Centrora Security features in one place with our panel, Centrora Panel. Centrora Panel aims to provide you the ability to perform websites security management, without accessing your admin sites one by one.<a href="http://www.centrora.com/centrora-features/">Visit Centrora Panel</a>

= New features in v3.0: =
* Enhancement: Improved Backend User Interface
* Enhancement: Re-designed Virus Scanning Engine, virus scanner is now 20x faster
* Enhancement: Improved Backend User Interface
* New: Added Database Backup function
* New: Central Security Management Integration with Centrora Panel
* New: Added File Upload Scanning function
* New: Added Google Authenticator (2 step authentication) function

= Customer Support =
If you need help in using Centrora Security™ plugin, save time by starting your support request online and we'll connect you to a security analyst or even the senior security consultant.  <a href = "http://www.centrora.com/support-center/">Click here for help.</a>

= Security Firewall =
* AntiSpam: utilizing blacklisting IPs from Stop Forum Spam.
* AntiVirus: virus scanning that scans through your site for malware and variants that are known security threats, heuristics of backdoors, trojans, suspicious code and other security issues.
* IP Mangement: manage ip by allow, block and track IP that access to your site
* Security Check: malicious user agent blocks hundreds of bad bots while ensuring open-access for normal traffic.
* Security Check: detect directory traversal that consists in exploiting insufficient security validation/sanitization of user-supplied input file names.
* Security Check: javascript injection for any traffic including automated bots that constitutes security threats of injecting malicious javascript into your files.
* Security Check: direct file inclusion for any traffic including automated bots that constitutes security threats of including files on a server through the web browser.
* Security Check: remote file inclusion for any traffic including automated bots that constitutes security threats of exploiting "dynamic file include" mechanisms in web applications.
* Security Check: database SQL injection for any traffic traffic including automated bots that constitutes security threats of attacking data driven applications, in which malicious SQL statements are inserted into an entry field for execution.
* Security Check: DoS Attacks where automated bots constituting flooding attacks to your website.
* Report security threats to defined owner or security analysts

= Language =
* English 

You can contribute translation for Centrora Security plugin <a href="http://www.centrora.com/blog/translate-centrora-security-plugin/">here</a>. 

= System Requirements =
* PHP 5.1.0 or above
* MySQL 5.0 or above
* WordPress 3.5 or above
* PHP Data Objects enabled (it is activated by default as of PHP 5.1.0, please contact your hosting to enable it if it is disabled. <a href ="http://www.php.net/manual/en/pdo.installation.php">reference</a> 


== Installation ==

To Install Centrora Security Plugin:

1. Go to Plugins > Add New.
2. Under Search bar, search for 'Centrora Security'.
3. Click 'Install Now' to install the Plugin. 

Visit the Full Tutorial <a href = "http://www.centrora.com/plugin-tutorial/plugin-ip-management/">here</a>.


== Frequently asked questions ==

= What to do if i have a problem? =
* You can always visit our <a href = "https://www.centrora.com/support-center/">support center</a> to raise a ticket, and we will assign an expert to fix your problem immediately.

= I get a Fatal Error: 'Class PDO not found'? =
* If you encounter the error 'Fatal error: Class 'PDO' not found' when the Centrora Security is activated, this indicates the PHP Data Objects is not loaded in your PHP environment. PDO is activated by default as of PHP 5.1.0, so please contact your hosting company to enable it if it is disabled. If you know how to customize the php configuration, add these codes into... <a href = "http://www.centrora.com/user-manual/application-runtime-path-valid/">Learn more</a>.

= Application runtime path not valid? =
* If you are getting this error, you have to change the permission for both of these folders and set them writable. <a href = "http://www.centrora.com/user-manual/application-runtime-path-valid/">Learn more</a>.

= Does the plugin block User Agent attacks? =
* Yes, the plugin checks whether the user agent environment variables contains PHP command, linux system commands and sql commands. If these are detected, the firewall will stop the user by throwing a ban page. 

= What is DFI or LFI? =
* DFI or LFI refers to Direct (Local) File Inclusion, where it usually comes with the user agent attacks. Hackers first test if your server has the vulnerabilities in Direct File Inclusion before they start the User Agent Attacks. They test if including the local file can review your website's environment variables, e.g. adding the following into your URL to review linux username and passwords: ../../../../etc/passwd. If this is successfuly, they can use User Agent to start attacking your server by downloading shell codes into your website. 


== Screenshots ==
1. Dashboard of the Centrora Security Plugin
2. IP Management Panel in Centrora Security Plugin 
3. Variables Management Panel in Centrora Security Plugin
4. Virus Scanning Panel in Centrora Security Plugin
5. Configuration Panel in Centrora Security Plugin
6. Dashboard Panel in Centrora Control Panel
7. Country Blocking Management Panel in Centrora Control Panel
8. Google 2-step authentication activation in Centrora Control Panel
9. Firewall Configuration Setting in Centrora Security Plugin
9. ClamAV Open Source Free Antivirus Integration

== Changelog ==

= 3.2.0 =
* Removed: Advanced Firewall setting panel
* Removed: Advanced Firewall checking in Dashboard Panel
* Fixed: Google Authenticator function keeps showing disabled even it is enabled in Dashboard
* Added: Country Blocking Panel and Download function
* Added: ClamAV integration into the Virus Scanning Function

= 3.1.3 =
* Fixed: IP cannot be deleted in the IP Management Panel

= 3.1.2 =
* Removed: Removed the installation of views in the database
* Fixed: Fixed the configuration cannot be saved in windows server
* Fixed: Fixed virus scanner cannot work on Windows server
* Added: Change username for the 'admin' account in Dashboard

= 3.1.1 =
* Enhancement: Change some wording in the dashboard to clarify the meaning of the menus
* Enhancement: Add ‘fix it’ button at the end of every warning bar.

= 3.1.0 =
* Enhancement: Enhance dashboard layout
* Enhancement: Removed unnecessary database connections
* Added: About page to show all short links to the pages in the plugin
* Enhancement: Change the remote login function to fit Centrora Panel 1.0.7

= 3.0.7 =
* Enhancement: Use the default Wordpress Contact email address in the ban page instead of the default value created in the Centrora SQL file
* Removed: removed the duplicated createTable.sql file in the data folder

= 3.0.6 =
* Fixed: On some servers, the auto loader function cause blank screen. 
* Fixed: On some servers, the PDO connection exceeds the maximum number of connection configured in MySQL setting. Adding datanbase connection closing codes to resolve it.   

= 3.0.5 =
* Enhancement: Added the version number in the dashboard
* Enhancement: Updated the remoteLogin class to work with Centrora Panel 1.0.5 
* Fixed: On some websites, the adminsitrator's email cannot show up in the Admin-Email Mapping Panel

= 3.0.4 =
* Fixed: On some websites, the checking of Development mode causes a blank screen
* Fixed: Missing closing tag for the warning message for development checking
* Enhancement: Warning message style improved
* Enhancement: Clarified warning message for the advance firewall setting

= 3.0.3 =
* Fixed: On some websites, the adminsitrator's email cannot show up in the Admin-Email Mapping Panel
* Fixed: Ajax class missed the ORequest Class when Centrora Panel calls the functions in the class
* Enhancement: Added a function to check if allow_url_fopen is turned on for a website
* Enhancement: Added a function to check if Developement mode is turned on for the website
* Enhancement: Added a function to check if the advanced firewall setting is turned on for the website 
* Enhancement: Removed duplicated 'Advanced Firewall' field in the scanning configuration panel

= 3.0.2 =
* Enhancement: Improved Dashboard Layout to have more user friendly navigation
* Enhancement: Improved Configuration Layout to have clearer navigation for functions like advanced firewall setting, country block and Google Authenticator
* Enhancement: Checked if the user has used other Google Authenticator plugin than Centrora Google Authenticator before loading the Google Authenticator plugin
* Enhancement: Remove the permission denied message for Country Block Page

= 3.0.1 =
* Enhancement: Removed the secret word wording from scanning configuration page
* New: Added Advance Firewall Setting function

= 3.0.0 =
* Enhancement: Improved Backend User Interface
* Enhancement: Re-designed Virus Scanning Engine, virus scanner is now 20x faster
* Enhancement: Improved Backend User Interface
* New: Added Database Backup function
* New: Central Security Management Integration with Centrora Panel
* New: Added File Upload Scanning function
* New: Added Google Authenticator (2 step authentication) function

= 2.2.6 =
* Fixed: temporarily fix the admin-email mapping not being able to fix in Google Chrome browser
* Fixed: fixed the 'Constant OSEAPPDIR already defined' error
* Enhancement: Enhance the YiiBase library to avoid open_basedir restriction for the library autoload function 

= 2.2.5 =
* Fixed: further fix for some websites the administrator lists cannot be shown in the Admin-Email Mapping section. 

= 2.2.4 =
* Fixed: admin-email mapping delete function not working in some servers because the JSON encoded ID value is escaped
* Fixed: admin-email mapping add linkage function showing incorrect return message even the linkage was added successfully

= 2.2.3 =
* Fixed the admin-email mapping controller for the incorrect return messages for the Ajax message box. 

= 2.2.2 =
* Fixed some websites the administrator lists cannot be shown in the Admin-Email Mapping section. 

= 2.2.1 =
* Enhancement: Remove the HTML Purifier auro register function in order to solve the 500 error issue in some server. 

= 2.2.0 =
* Enhancement: Added menu bar into the control panel for easy navigation
* Enhancement: Improved firewall statistic library to reduce PHP warning errors
* Enhancement: Improved virus scanner library to reduce PHP warning errors
* Enhancement: Improved oseAjax class to support Joomla CMS
* Enhancement: Improved oseDatabase class to support Joomla CMS
* Enhancement: Improved oseEmail class to support Joomla CMS
* Enhancement: Improved oseInstaller class to support Joomla CMS
* Enhancement: Improved oseRequest class to support Joomla CMS

= 2.1.4 =
* Enhancement: Improved Germany Language Translation. Credits to <a href="http://alexander.pfabel.de">Alexander Pfabel</a>
* Enhancement: Added the debug mode option in the configuration panel to turn off error displaying function in the frontend. Credits to <a href="http://wordpress.org/support/profile/lewismedia">Wombat</a> 

= 2.1.3 =
* Enhancement: Added the function to check if the curl_exec is enabled for a hosting account, if so, the Stop Forum Spam function will be disabled. 
* Enhancement: Improve the backend css file to adjust the font-size to match default wordpress font-size. Credits to <a href="http://alexander.pfabel.de">Alexander Pfabel</a>
* Enhancement: Improve the badge seal layout and background images

= 2.1.2 =
* Enhancement: Added Germany Support - credits to: German translation by Alexander Pfabel (http://alexander.pfabel.de)
* Fixed no data issue in Admin Email Mapping config page, Credits to <a href = "http://wordpress.org/support/profile/shadowood">shadowood</a>, and <a href ="http://wordpress.org/support/profile/itpixie">itpixie</a>
* Enhancement: make the Admin Email Mapping Editing window closable

= 2.1.1 =
* Add back i18n multiple language solution library, some environment requires this. Credits to <a href = "http://wordpress.org/support/profile/joedeagnon">joedeagnon</a> 

= 2.1.0 =
* Significantly reduce package size
* Fixed Class 'CHtmlPurifier' not found error during database creation section. Credits to <a href = "http://wordpress.org/support/profile/mikeotgaar">mikeotgaar</a> 
* Fixed wrong warning message shown in Variables management. Credits to <a href = "http://wordpress.org/support/profile/shadowood">shadowood</a>, and <a href ="http://wordpress.org/support/profile/kamiill">kamill</a>
* Fixed Virus Scanner Panel: no progression bar during scan. Credits to <a href = "http://wordpress.org/support/profile/shadowood">shadowood</a>
* Fixed Virus Scanner Panel: no progression bar during scan. Credits to <a href = "http://wordpress.org/support/profile/shadowood">shadowood</a>
* Fixed incorrect format for option 'File Extensions' in the virus scan config page. Credits to <a href = "http://wordpress.org/support/profile/shadowood">shadowood</a>
* Fixed incorrect sizing for scan file size box. Credits to <a href = "http://wordpress.org/support/profile/shadowood">shadowood</a>
* Enhancement: remove GeoIP database tables requirements, significantly reducing Database size. . Credits to <a href = "http://wordpress.org/support/profile/shadowood">shadowood</a>
 
= 2.0.2 =
* Remove Secret Word Descriptions
* Fixed non-English website not able to load javascript language files issues

= 2.0.1 =
* Fixed Badge update issue 
* Fixed Virus database update issue 
* Fixed Database keeps display not ready issue

= 2.0.0 = 
* Improved front-end protect seal showing function
* Rewrite the whole plugin to implement the MVC structure	  

= 1.6.4 =
* Improved front-end protect seal showing function
* Improved front-end protect seal CSS style 
      
= 1.6.3 =
* Fixed the log table not created properly issues on some servers 
      
= 1.6.2 =
* Fixed a typo in the security seal 

= 1.6.1 =
* Updated Chinese and Germany languages, credits to Mr Alexander Pfabel
* Fixed the  Class 'osewpScanEngine' not found issue for some servers 
	  
= 1.6.0 =
* Added Stop Forum Spam Anti-spamming checking, keep your blog spam free
* Added Security Protection Badge, shows the confidence of your website security to your clients
* Added the logs of virus scanning to show the scanning records in the security protection badge

= 1.5.4 =
* Removed duplicated menus as suggested by Lime Canvas (http://wordpress.org/support/profile/limecanvas)
* Fixed the issue where OSE Firewall Settings links are appended to all plugins links section (credits to Lime Canvas http://wordpress.org/support/profile/limecanvas)
* Fixed the wpdb undefined issue when initializing file list into the database
      
= 1.5.3 =
* Updated the codes to make it work with multiple websites (credits to scottnath, http://wordpress.org/support/profile/scottnath)
* Improved function to check admin accounts
* Fixed PHP warning errors for undefined OSE Firewall setting variables 
	  
= 1.5.2 =
* Updated Chinese and Germany languages, credits to Mr Alexander Pfabel 
	  
= 1.5.1 =
* Fixed back-end admin menu causing warning message issues (reported by mike http://www.graphicline.co.za/ and Alan http://wordpress.org/support/profile/alanpae, AlanP57 http://wordpress.org/support/profile/alanp57)
* Fixed language file loading error issue (credits to scottnath, http://wordpress.org/support/profile/scottnath)
* Fixed redirection function error issue reported by numzi http://wordpress.org/support/profile/nunzi
* Avoid scanning back-end blog post action to avoid false alerts with javascript codes inserted in to blog posts (thanks for the report by Alexander http://wordpress.org/support/profile/herzwacht and  
	  
= 1.5.0 =
* Added four protection modes: OSE Firewall only, OSE Security Suite only, OSE Firewall plus OSE Security Suite and Development mode (protection temporarily turned off)
* Added a server IP field to avoid false alerts due to empty user agent
* Fixed the field 'Detect Directory Traversal' not being saved properly issue
* Added custom banning message field and custom banning message function
* Enhance OSE Banning page appearance
* Enhance Javascript injection detection pattern to avoid false alerts
* Added OSE Virus / Malicious codes scanning function

= 1.0.2 =
* Added Germany Translation language
* Added the maximum tolerance parameter, so the attacker will be blocked automatically after X times of attack

= 1.0.1 =
* Added French Translation language

= 1.0.0 =
* Initial release


== Upgrade notice ==
N/A
