<?php
if (!defined('_JEXEC') && !defined('OSE_FRAMEWORK')) {
	die("Direct Access Not Allowed");
}
class oseJSON {
	public static function encode($arr) {
		if (version_compare(PHP_VERSION, "5.2", "<")) {
			if (file_exists(dirname(__FILE__) . DS . "ServicesJSON.php")) {
				require_once(dirname(__FILE__) . DS . "ServicesJSON.php"); //if php<5.2 need JSON class
			}
			$json = new Services_JSON(); //instantiate new json object
			$data = $json->encode($arr); //encode the data in json format
		} else {
			$data = json_encode($arr); //encode the data in json format
		}
		return $data;
	}
	public static function decode($json, $assoc = false) {
		if (version_compare(PHP_VERSION, "5.2", "<")) {
			if (file_exists(dirname(__FILE__) . DS . "ServicesJSON.php")) {
				require_once(dirname(__FILE__) . DS . "ServicesJSON.php"); //if php<5.2 need JSON class
			}
			$Services_json = new Services_JSON(); //instantiate new json object
			$data = $Services_json->decode($json, $assoc); //encode the data in json format
		} else {
			$data = json_decode($json, $assoc); //encode the data in json format
		}
		return $data;
	}
}
?>
