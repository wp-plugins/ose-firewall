=== OSE Firewall™ ===
Contributors: osexcel, ProHelix, ProKai, ProChase
Contributors: Open Source Excellence
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=PE4MSVEGBLLKE
Tags: security, admin, anti-spam, wordpress, anti-hack, anti-virus, wordpress security, anti-malware, firewall,security plugin, virus scanning, virus cleanning, clean malicious codes
Requires at least: 3.3
Tested up to: 3.6
Stable tag: 2.1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

OSE Firewall™ - A WordPress Firewall created by ProWeb (Protect Website), protects your WordPress-powered blog against hacking, virus and spam. 

== Description ==

= MOST EPIC WORDPRESS SECURITY PLUGIN =
OSE Firewall™ - A WordPress Security Plugin powered by <a href = "http://www.protect-website.com">ProWeb</a>. It protects your WordPress Website from attacks and hacking. The Built-in Scanner that identify any malicious codes, spam, virus, SQL injection attack, vulnerabilities. 

= Customer Support =
Need help? Save time by starting your support request online and we'll connect you to an expert.  <a href = "http://www.protect-website.com/support-center">Click here to get started.</a>

= New Features in v2.0 =
* Manage IPs - blacklisting, whitelisting, and monitoring IPs
* Manage Rulesets - you can change the rule sets to that best fits for your website's requirements
* Email Alert - choose different types of alert emails to receive when there's an attack
* Variables whitelisting functions - you can whitelist some variables in your website to enhance firewall in order to avoid false alerts. 

= Enhanced Features =
* Anti-Spam - Utilizing Blacklisting IPs in Stop Forum Spam 
* Block blacklisted methods - Trace, Delete, Track
* Checks Malicious User Agent     
* Detect Directory Traversal 
* Virus Scanning    
* Checks DoS Attacks  
* Checks Javascript Injection      
* Checks Direct File Inclusion     
* Checks Remote File Inclusion     
* Checks Database SQL Injection

The plugin currently supports ENGLISH language ONLY. 
You can contribute by translating OSE Firewall <a href = "https://www.protect-website.com/how-to-translate-ose-firewall/">here</a>.

= System Requirements =
* PHP 5.1.0 or above
* MySQL 5.0 or above
* Wordpress 3.5 or above
* PHP Data Objects enabled (it is activated by default as of PHP 5.1.0, please contact your hosting to enable it if it is disabled. <a href ="http://www.php.net/manual/en/pdo.installation.php">Reference</a> 


== Installation ==

To Install OSE Firewall Plugin:

1. Go to Plugins > Add New.
2. Under Search bar, search for 'OSE Firewall'.
3. Click 'Install Now' to install the Plugin.

For more information visit <a href = "https://www.protect-website.com/installing-ose-firewall/">here</a>.


== Frequently asked questions ==

= What to do if i have a problem? =
* You can always visit our <a href = "https://www.protect-website.com/support-center/">support center</a> to raise a ticket, and we will assign an expert to fix your problem immediately.

= How to raise a ticket in the Support Center? =
* Visit our <a href = "https://www.protect-website.com/support-center/">support center</a>, click 'Open a New Ticket', fill in all the details and click 'Create Ticket'. The system will automatically send you an email with a Ticket ID.

= How to use my Ticket ID? =
* Ticket ID is an identification number for you to track the status of your request/enquiry. Visit our <a href = "https://www.protect-website.com/support-center/">support center</a>, click 'Check Ticket Status', enter your registered email address and Ticket ID to track the status.

= Does the plugin block User Agent attacks? =
* Yes, the plugin checks whether the user agent environment variables contains PHP command, linux system commands and sql commands. If these are detected, the firewall will stop the user by throwing a ban page. 

= What is DFI or LFI? =
* DFI or LFI refers to Direct (Local) File Inclusion, where it usually comes with the user agent attacks. Hackers first test if your server has the vulnerabilities in Direct File Inclusion before they start the User Agent Attacks. They test if including the local file can review your website's environment variables, e.g. adding the following into your URL to review linux username and passwords: ../../../../etc/passwd. If this is successfuly, they can use User Agent to start attacking your server by downloading shell codes into your website. 

= How to resolve the issue "Fatal error: Class 'PDO' not found" =
* OSE Firewall is built on <a href ="http://php.net/manual/en/book.pdo.php">PHP Data Objects (PDO)</a>, which is an extension providing unified data access to many popular DBMS, such as MySQL, PostgreSQL. Therefore, to use OSE Firewall, the PDO extension and the specific PDO database driver (e.g. PDO_MYSQL) have to be installed 



== Screenshots ==

1. Admin Panel
2. IP Management Panel
3. Virus Scanner

== Changelog ==
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
