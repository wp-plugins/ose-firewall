<?php
defined('OSEFWDIR') or die;
require_once(dirname(__FILE__).ODS.'uri.php');
if (class_exists('JConfig') || class_exists('SConfig'))
{
	require_once(dirname(__FILE__).ODS.'joomla.php');
}
else
{
	require_once(dirname(__FILE__).ODS.'wordpress.php');
}
?>