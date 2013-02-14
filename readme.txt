=== OSE Firewall™ ===
Contributors: Open Source Excellence
Donate link: 
Tags: security, admin, anti-spam, wordpress, anti-hack, anti-virus, wordpress security, anti-malware, firewall,security plugin, virus scanning, virus cleanning, clean malicious codes
Requires at least: 3.3
Tested up to: 3.5.1
Stable tag: 1.5.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

OSE Firewall™ - A WordPress Firewall created by Open Source Excellence. It protects your WordPress-powered blog against attacks and hacking. 

== Description ==

OSE Firewall™ - A WordPress Firewall created by <a href = "http://www.opensource-excellence.com">Open Source Excellence</a>. It protects your WordPress-powered blog against attacks and hacking. Detailed description can be found in our main website <a href ="http://www.opensource-excellence.com/shop/ose-wordpress-firewall.html">here</a>.

Since 1.0.0, The software has the following functions

    Block blacklisted methods (Trace / Delete / Track)     
    Checks Malicious User Agent     
    Detect Directory Traversal     
    Checks Basic DoS Attacks     
    Checks Basic Direct File Inclusion     
    Checks Basic Remote File Inclusion     
    Checks Basic Javascript Injection     
    Checks Basic Database SQL Injection

Since 1.5.0, 

    The software supports the direction activation of OSE Security Suite to enhance the protection of your WordPress websites. 
    The newly added Development mode also allows you to turn off the protection temporarily. 
    A virus / malicious code scanner has been added to the plugin to help detect virus in your websites.

Languages

The plugin currently supports the following languages:

    English
    Chinese
	French - Credits to Valérie CREPIN (contact@geromweb.com)
	Germany - Credits to Alexander Pfabel (homepage@pfabel.de)

The language files can be found in this folder:

YOUR_WORDPRESS_FOLDER/wp-content/plugins/ose_firewall/languages/

Please feel free to share your translation by sending the language file to: partners@opensource-excellence.com, we will add it into our next release. Thank you!

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

Screenshots are located in this page:

http://www.opensource-excellence.com/shop/ose-wordpress-firewall.html

== Changelog ==
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