=== Centrora Security™===
Contributors: osexcel, ProHelix, ProKai, ProChase
Contributors: Open Source Excellence
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=PE4MSVEGBLLKE
Tags: antivirus, better wordpress security, better wp security, security, admin, anti-spam, wordpress, anti-hack, anti-virus, wordpress security, anti-malware, firewall,security plugin, virus scanning, virus cleanning, clean malicious codes,firewall security, front-end security, personal security, protection, rfi, secure, secure website, security, security log, security plugin, SQL Injection, web server security, website security, wordpress security, xss, malware, spam, hack, hacker
Requires at least: 3.7
Tested up to: 3.8.1
Stable tag: 3.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Centrora Security™ - part of Better WordPress Security™ Project, a WordPress Firewall plugin to protect your website against hacking, virus and spam. 

== Description ==

= MOST EPIC WORDPRESS SECURITY PLUGIN =
Centrora Security™ - Previously known as (OSE Firewall Security) A WordPress Security Plugin powered by <a href = "http://www.centrora.com" target="_blank">Centrora</a>. It protects your WordPress Website from attacks and hacking. The built-in Malware and Security Scanner helps you identify any security risks, malicious codes, spam, virus, SQL injection attack, and security vulnerabilities. By using Centrora Security™, you don't need any other antivirus product!

= Aim to build Better Wordpress Security - the more secure wordpress environment =  
The plugin is part of the Better WordPress Security™ Project. Better WordPress Security™ is a project that aims to create a safer WordPress environment for every WordPress users by guiding them to avoid infected plugins and themes. Centrora Security™ is the first plugin of the project, that tries to help all Wordpress users to enhance the security of their website. 

If you would like to know more about website security tips, e.g. installing web application firewall or a dedicated firewall in your server, please visit our blog to read articles about website security <a href = "http://www.centrora.com/category/blog/">here.</a>. We will regularly write up security articles so you can enhance your website security. If you would like to promote a better and more secure Wordpress environment, please feel free to Like us in Facebook <a href = "https://www.facebook.com/centrora" alt="Centrora" >here</a> or follow our <a href="https://twitter.com/loveCentrora">twitter</a> to ask any questions about website security. Our security analysts and security consultants will try to answer your questions as soon as possible.       

= Customer Support =
If you need help in using Centrora Security™ plugin, save time by starting your support request online and we'll connect you to a security analyst or even the senior security consultant.  <a href = "http://www.centrora.com/support-center/">Click here to get started.</a>

= New Security Features in Centrora Security™ v3.0 =
* Enhancement: Improved Backend User Interface
* Enhancement: Rewrite Virus Scanning Engine, virus scanning now becomes faster
* Enhancement: Improved Germany Translation
* Enhancement: Improved Backend User Interface
* New Feature: Added Database Backup function
* New Feature: Central Security Management Integration with Centrora Panel
* New Feature: Added File Upload Scanning function  
* New Feature: Added Advanced Firewall Protection (Premium only)
* New Feature: Added Advanced Virus Signatures (Premium only)
* New Feature: Added Country Blocking function
* New Feature: Added Google Authentication (2 step authentication) function

= Enhanced Security Features: Provides an industry level firewall to block common security threats =
* AntiSpam - Utilizing Blacklisting IPs in Stop Forum Spam 
* AntiVirus - prevent, detect and remove malware from the website, keep website secure.
* Blacklisted IP Handling methods - Trace, Delete, Track
* Security Check: Malicious User Agent blocks hundreds of the worst bots while ensuring open-access for normal traffic 
* Security Check: Detect Directory Traversal that consists in exploiting insufficient security validation / sanitization of user-supplied input file names.
* Malware Check: Virus Scanning that scans for malware and variants that are known security threats, and scans for heuristics of backdoors, trojans, suspicious code and other security issues, which is the first stgae of antivirus.
* Security Prevention: DoS Attacks where automated bots constituting flooding attacks to your website. 
* Security Check: Javascript Injection for any traffic including automated bots that constitutes security threats of injecting malicious javascript into your files.      
* Security Check: Direct File Inclusion for any traffic including automated bots that constitutes security threats of including files on a server through the web browser.
* Security Check: Remote File Inclusion for any traffic including automated bots that constitutes security threats of exploiting "dynamic file include" mechanisms in web applications.
* Security Check: Database SQL Injection for any traffic traffic including automated bots that constitutes security threats of attacking data driven applications, in which malicious SQL statements are inserted into an entry field for execution.
* Report security threats to defined owner or security analysts

The plugin currently supports English and Germany language. 
You can contribute by translating OSE Firewall Security <a href = "https://www.protect-website.com/how-to-translate-ose-firewall/">here</a>.

= System Requirements =
* PHP 5.1.0 or above
* MySQL 5.0 or above
* WordPress 3.5 or above
* PHP Data Objects enabled (it is activated by default as of PHP 5.1.0, please contact your hosting to enable it if it is disabled. <a href ="http://www.php.net/manual/en/pdo.installation.php">Reference</a> 


== Installation ==

To Install Centrora Security Plugin:

1. Go to Plugins > Add New.
2. Under Search bar, search for 'Centrora Security'.
3. Click 'Install Now' to install the Plugin. 

For more information visit <a href = "http://www.centrora.com/centrora-security-plugin/">here</a>.


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

1. Admin Panel of OSE Firewall Security
2. IP Management Panel of OSE Firewall Security
3. Virus Scanner of OSE Firewall Security
4. Website Security Seal 

== Changelog ==
= 3.0.0 =
* Enhancement: Improved Backend User Interface
* Enhancement: Rewrite Virus Scanning Engine, virus scanning now becomes faster
* Enhancement: Improved Germany Translation
* Enhancement: Improved Backend User Interface
* New Feature: Added Database Backup function
* New Feature: Central Security Management Integration with Centrora Panel
* New Feature: Added File Upload Scanning function  
* New Feature: Added Advanced Firewall Protection (Premium only)
* New Feature: Added Advanced Virus Signatures (Premium only)
* New Feature: Added Country Blocking function
* New Feature: Added Google Authentication (2 step authentication) function

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
