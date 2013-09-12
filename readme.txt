=== OSE Firewall™ ===
Contributors: Protect Website
Contributors: Open Source Excellence
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=PE4MSVEGBLLKE
Tags: security, admin, anti-spam, wordpress, anti-hack, anti-virus, wordpress security, anti-malware, firewall,security plugin, virus scanning, virus cleanning, clean malicious codes
Requires at least: 3.3
Tested up to: 3.6
Stable tag: 2.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

OSE Firewall™ - A WordPress Firewall created by Open Source Excellence, protects your WordPress-powered blog against hacking, virus and spam. 

== Description ==

<b>MOST EPIC WORDPRESS SECURITY PLUGIN</b>
OSE Firewall™ - A WordPress Security Plugin powered by <a href = "http://www.protect-website.com">ProWeb</a>. It protects your WordPress Website from attacks and hacking. The Built-in Scanner that identify any malicious codes, spam, virus, SQL injection attack, vulnerabilities. 

<b>New Features in v2.0</b>
*Manage IPs - blacklisting, whitelisting, and Monitoring IPs
*Manage Rulesets - you can change the rule sets to that best fits for your website's requirements
*Email Alert - choose different types of alert emails to receive when there�s an attack
*Variables whitelisting functions - you can whitelist some variables in your website to enhance firewall in order to avoid false alerts. 

<b>Enhanced Features</b>
*Anti-Spam - Utilizing Blacklisting IPs in Stop Forum Spam 
*Block blacklisted methods - Trace, Delete, Track
*Checks Malicious User Agent     
*Detect Directory Traversal 
*Virus Scanning    
*Checks DoS Attacks   
*Checks Javascript Injection      
*Checks Direct File Inclusion     
*Checks Remote File Inclusion     
*Checks Database SQL Injection

The plugin currently supports ENGLISH language ONLY, we�ll add new languages in the future.


== Installation ==

1. Go to WordPress Plugin Page --> Add New --> Choose Upload --> Upload the file ose_firewall.zip

2. Activate the plugin through the 'Plugins' menu in WordPress

3. Then choose which of the anti-hacking rules you would like the firewall to enable

4. The the configuration by clicking the 'Test Configuration' link, if you see the alert, that means the firewall has been enabled and protecting your wordpress blog.

== Frequently asked questions ==

* Please raise all questions / bug fixes in our support tickcet here: 
http://www.opensource-excellence.com/customers/general-enquries.html

* Does the plugin block User Agent attacks?

Yes, the plugin checks whether the user agent environment variables contains PHP command, linux system commands and sql commands. If these are detected, the firewall will stop the user by throwing a ban page. 

* What is DFI or LFI?

DFI or LFI refers to Direct (Local) File Inclusion, where it usually comes with the user agent attacks. Hackers first test if your server has the vulnerabilities in Direct File Inclusion before they start the User Agent Attacks. They test if including the local file can review your website's environment variables, e.g. adding the following into your URL to review linux username and passwords: ../../../../etc/passwd. If this is successfuly, they can use User Agent to start attacking your server by downloading shell codes into your website. 

More are coming up...


== Screenshots ==

1. Here's a screenshot of it in action


== Changelog ==
2.0.0  
	  - Improved frontend protect seal showing function
	  - Rewrite the whole pluguin to implement the MVC structure	  

1.6.4 
      - Improved frontend protect seal showing function
      - Improved frontend protect seal css style 
      
1.6.3 
      - Fixed the log table not created properly issues on some servers 
      
1.6.2 
      - Fixed a typo in the security seal 

1.6.1
	  - Enhancement: updated Chinese and Germany languages, credits to Mr Alexander Pfabel
	  - Bug fix: fixed the  Class 'osewpScanEngine' not found issue for some servers 
	  
1.6.0
	  - New feature: Added Stop Forum Spam Anti-spamming checking, keep your blog spam free
	  - New feature: Added Security Protection Badge, shows the confidence of your website security to your clients
	  - New feature: Added the logs of virus scanning to show the scanning records in the secuirty protection badge

1.5.4
	  - Enhancement: removed duplicatd menus as suggested by Lime Canvas (http://wordpress.org/support/profile/limecanvas)
	  - Bug fixed: fixed the issue where OSE Firewall Settings links are appended to all plugins links section (credits to Lime Canvas http://wordpress.org/support/profile/limecanvas)
      - Bug fixed: fixed the wpdb undefined issue when initializing file list into the database
      
1.5.3
	  - Enhancement: updated the codes to make it work with multiple websites (credits to scottnath, http://wordpress.org/support/profile/scottnath)
	  - Enhancement: improved function to check admin accounts
	  - Fixed PHP warning errors for undefined OSE Firewall setting variables 
	  
1.5.2
	  - Enhancement: updated Chinese and Germany languages, credits to Mr Alexander Pfabel 
	  
1.5.1 
	  - Fixed backend admin menu causing warning message issues (reported by mike http://www.graphicline.co.za/ and Alan http://wordpress.org/support/profile/alanpae, AlanP57 http://wordpress.org/support/profile/alanp57)
	  - Fixed language file loading error issue (credits to scottnath, http://wordpress.org/support/profile/scottnath)
	  - Fixed redirection function error issue reported by numzi http://wordpress.org/support/profile/nunzi
	  - Enhancement: avoid scanning backend blog post action to avoid false alerts with javascript codes inserted in to blog posts (thanks for the report by Alexander http://wordpress.org/support/profile/herzwacht and  
	  
1.5.0 

	  - Added four protection modes: OSE Firewall only, OSE Security Suite only, OSE Firewall plus OSE Security Suite and Development mode (protection temporarily turned off)
 	  - Added a server IP field to avoid false alerts due to empty user agent
 	  - Fixed the field 'Detect Directory Traversal' not being saved properly issue
 	  - Added custom banning message field and custom banning message function
 	  - Enhance OSE Banning page appearence
 	  - Enhance Javascript injection detection pattern to avoid false alerts
 	  - Added OSE Virus / Malicious codes scanning function

1.0.2 - Added Germany Translation language
		Fixed the email message not showing in full issue
		Added the maximum tolerance paramter, so the attacker will be blocked automatically after X times of attack
1.0.1 - Added French Translation language
1.0.0 - Initial release


== Upgrade notice ==
N/A