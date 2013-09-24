<?php
defined('OSEFWDIR') or die;
if (class_exists('JFactory'))
{
	require_once(dirname(__FILE__).DS.'joomla.php');
}
else
{
	require_once(dirname(__FILE__).DS.'wordpress.php'); 
}
?>