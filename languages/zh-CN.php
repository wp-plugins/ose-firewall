<?php
defined('OSEFWDIR') or die;
//Start here;
define('OSE_WORDPRESS_FIREWALL', '傲视防火墙™');
define('OSE_WORDPRESS_FIREWALL_SETTING', '傲视防火墙™设置');
define('OSE_WORDPRESS_FIREWALL_SETTING_DESC', '傲视防火墙™是一个<a href="http://www.opensource-excellence.com" target="_blank">傲视开源</a>针对Wordpress创建的网站应用防火墙. 它可以有效的保护你的网站免受黑客攻击.');
define('NOTIFICATION_EMAIL_ATTACKS', '接收攻击通知的电子邮件');
define('EMAIL_ADDRESS', '电邮地址');
define('FIREWALL_SCANNING_OPTIONS', '防火墙扫描选项');
define('BLOCKBL_METHOD', '阻止这些被列入黑名单的方法(追踪/删除/曲目)');
define('CHECK_MUA', '检查恶意用户代理');
define('checkDOS', '检查基本的DOS攻击');
define('checkDFI', '检查基本的直接文件共享攻击');
define('checkRFI', '检查基本的远程文件包含攻击');
define('checkJSInjection', '检查基本的JavaScript感染攻击');
define('checkSQLInjection', '检查基本的数据库SQL注入攻击');
define('checkTrasversal', '检查路径遍历攻击');
define('ADVANCE_SETTING', '高级设置');
define('OTHER_SETTING', '其他设置');
define('BLOCK_QUERY_LONGER_THAN_255CHAR', '阻止长于255个字符的网址');
define('BLOCK_PAGE', '显示给攻击者的禁令页');
define('OSE_BAN_PAGE', '使用傲视的禁令页');
define('BLANK_PAGE', '显示一个空白页');
define('ERROR403_PAGE', '显示403错误页面');
define('TEST_CONFIGURATION', '测试您的配置');
define('TEST_CONFIGURATION_NOW', '现在测试您的配置！');
define('SAVE_CHANGES', '保存更改');
define('WHITELIST_VARS', '忽略的变量');
define('WHITELIST_VARS', '(请使用可以逗号','分隔的变量)');
define('BLOCK_MESSAGE', '您的请求已被封锁！');
define('FOUNDBL_METHOD', '发现被列入黑名单的方法(追踪/删除/曲目)');
define('FOUNDMUA', '发现恶意用户代理');
define('FOUNDDOS', '发现基本的DOS攻击');
define('FOUNDDFI', '发现基本的直接文件共享攻击');
define('FOUNDRFI', '发现基本的远程文件包含攻击');
define('FOUNDJSInjection', '发现基本的JavaScript感染攻击');
define('FOUNDSQLInjection', '发现基本的数据库SQL注入攻击');
define('FOUNDTrasversal', '发现基本的数据库SQL注入攻击');
define('FOUNDQUERY_LONGER_THAN_255CHAR', '发现长于255个字符的网址');
define('MAX_TOLERENCE', '最大的攻击容忍');
// Langauges for version 2.0 + start from here;
define('OSE_SCANNING_SETTING','扫描设置');
define('服务器IP','你的服务器IP(由于空的用户代理,以避免假警报)');
define('OSE_WORDPRESS_FIREWALL_CONFIG','傲视防火墙™配置');
define('OSE_WORDPRESS_VIRUSSCAN_CONFIG','傲视病毒扫描™配置');
define('OSE_WORDPRESS_VIRUSSCAN_CONFIG_DESC','请设置您的病毒扫描参数。');
define('START_DB_INIT','初始化数据库');
define('STOP_DB_INIT','停止行动');
define('START_NEW_VIRUSSCAN','开始新的扫描');
define('CONT_VIRUSSCAN','继续上次的扫描');
define('OSE_SCANNED','傲视扫描到');
define('OSE_INIT','傲视初始化了');
define('OSE_FOLDERS','文件夹');
define('OSE_AND','和');
define('OSE_FILES','文件');
define('OSE_INFECTED_FILES','受感染的文件');
define('OSE_INTOTAL','总共');
define('OSE_THERE_ARE','有');
define('OSE_IN_DB','在数据库');
define('OSE_VIRUS_SCAN','傲视病毒扫描™');
define('OSE_VIRUS_SCAN_DESC','傲视 WordPress 病毒扫描™旨在扫描和清理WordPress的恶意代码和24小时监测您的网站。');
define('CUSTOM_BANNING_MESSAGE','自定义禁止消息');
define('FILEEXTSCANNED的','被扫描的文件扩展名');
define('DONOTSCAN','不扫描文件大于(单位：兆字节)');
define('PLEASE_CHOOSE_OPTION','请选择一个选项');
define('COMPATIBILITY','兼容性');
define('OSE_PLEASE_CONFIG_FIREWALL,请在这里配置防火墙设置。');
define('OSE_FOLLOWUS','跟随我们保持更新。');
define('OSE_ID_INFO','傲视的帐户信息(请务必填写您的帐户中,当你是一个高级/专业会员)。');
define('OSE_ID','傲视会员ID(傲视网站的用户名)。');
define('OSE_PASS',',傲视会员密码(傲视网站的密码)。');
define('OSE_SCAN_SUMMARY','扫描摘要');
define('OSE_SCAN_ACTIVITY','扫描详细的活动');
define('OSE_WEBSITE_PROTECTED_BY','这个网站是受');
define('OSE_PROTECTION_MODE','保护模式');
define('OSE_FIREWALL_ONLY','只由傲视防火墙保护');
define('OSE_SECSUITE_ONLY','只由傲视安全套件保护');
define('OSE_FWANDSUITE','由傲视防火墙与傲视安全套件同时间保护');
define('OSE_SUITE_PATH','傲视安全套件的绝对路径。<BR/> 例如 /home/youraccount/public_html/osesecurity,/ <BR/>(请确保你已经安装了的<a href ="htt​​ps://www.opensource-excellence.com/shop/ose-security-suite.html" target="_blank"> 傲视安全套件</a>)');
define('NEED_HELP_CLEANING','需要帮助清除病毒吗?');
define('NEED_HELP_CLEANING_DESC','病毒随着时间的推移正在发生变化,我们的病毒库可能不能在受感染的系统中扫描到最新的恶意文件。在这种情况下,请考虑聘请我们的<a href ="htt​​ps://www.opensource-excellence.com/service/removal-of-malware.html" target="_blank">恶意软件清除服务</a>。在您的网站扫描到的病毒将用以帮助其他用户。) ');
define('OSE_DEVELOPMENT','开发模式(暂时关闭保护)');