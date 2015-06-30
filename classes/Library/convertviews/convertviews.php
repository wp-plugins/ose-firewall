<?php
/**
 * @version     2.0 +
 * @package       Open Source Excellence Security Suite
 * @subpackage    Centrora Security Firewall
 * @subpackage    Open Source Excellence WordPress Firewall
 * @author        Open Source Excellence {@link http://www.opensource-excellence.com}
 * @author        Created on 01-Jun-2013
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 *
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *  @Copyright Copyright (C) 2008 - 2012- ... Open Source Excellence
 */
if (! defined ( 'OSE_FRAMEWORK' ) && ! defined ( 'OSEFWDIR' ) && ! defined ( '_JEXEC' )) {
	die ( 'Direct Access Not Allowed' );
}
class convertViews {
    public static function convertAclipmapNoVar($attrArray)
    {
        $attrList = "";
        $attrList = convertViews::assembleAttributes($attrArray);
        $sql = "SELECT $attrList
				FROM (`#__osefirewall_acl` `acl`
				            LEFT JOIN `#__osefirewall_iptable` `ip` on `acl`.`id` = `ip`.`acl_id`)";
        return $sql;
    }
	public static function convertAclipmap($attrArray) {
		$attrList = "";
		$attrList = convertViews::assembleAttributes ( $attrArray );
		$sql = "SELECT $attrList
				FROM ((((`#__osefirewall_acl` `acl`
				            LEFT JOIN `#__osefirewall_iptable` `ip` on((`acl`.`id` = `ip`.`acl_id`)))
				           LEFT JOIN `#__osefirewall_detected` `detected` on((`acl`.`id` = `detected`.`acl_id`)))
				         LEFT JOIN `#__osefirewall_detcontdetail` `detcontdetail` on((`detected`.`detattacktype_id` = `detcontdetail`.`detattacktype_id`)))
				       LEFT JOIN `#__osefirewall_vars` `vars` on((`vars`.`id` = `detcontdetail`.`var_id`)))";
		return $sql;
	}
	public static function convertAdminEmail($attrArray) {
		$attrList = "";
		$attrList = convertViews::assembleAttributes ( $attrArray );
		$sql = "SELECT $attrList
				FROM `#__users` AS `users`
						INNER JOIN `#__ose_app_admin` `admin` ON `users`.`id` = `admin`.`user_id`
					LEFT JOIN `#__ose_app_adminrecemail` `adminemail` ON `admin`.`id` = `adminemail`.`admin_id`
				LEFT JOIN `#__ose_app_email` `email` ON `adminemail`.`email_id` = `email`.`id`";
		return $sql;
	}
	public static function convertAttackmap($attrArray) {
		$attrList = "";
		$attrList = convertViews::assembleAttributes ( $attrArray );
		$sql = "SELECT $attrList
				FROM ((((((`#__osefirewall_acl` `acl`
				           LEFT JOIN `#__osefirewall_detected` `detected` on((`acl`.`id` = `detected`.`acl_id`)))
				          LEFT JOIN `#__osefirewall_detattacktype` `detattacktype` on((`detected`.`detattacktype_id` = `detattacktype`.`id`)))
				         LEFT JOIN `#__osefirewall_detcontdetail` `detcontdetail` on((`detcontdetail`.`detattacktype_id` = `detattacktype`.`id`)))
				        LEFT JOIN `#__osefirewall_detcontent` `detcontent` on((`detcontdetail`.`detcontent_id` = `detcontent`.`id`)))
				       LEFT JOIN `#__osefirewall_vars` `vars` on((`vars`.`id` = `detcontdetail`.`var_id`)))
				      LEFT JOIN `#__osefirewall_attacktype` `attacktype` on((`attacktype`.`id` = `detattacktype`.`attacktypeid`)))";
		return $sql;
	}
	public static function convertAttackTypesum($attrArray) {
		$attrList = "";
		$attrList = convertViews::assembleAttributes ( $attrArray );
		$sql = "SELECT $attrList
				FROM (((`#__osefirewall_acl` `acl`
							LEFT JOIN `#__osefirewall_detected` `detected` on((`acl`.`id` = `detected`.`acl_id`)))
						LEFT JOIN `#__osefirewall_detattacktype` `detattacktype` on((`detected`.`detattacktype_id` = `detattacktype`.`id`)))
					LEFT JOIN `#__osefirewall_attacktype` `attacktype` on((`attacktype`.`id` = `detattacktype`.`attacktypeid`)))";
		return $sql;
	}
	public static function convertDetMalware($attrArray) {
		$attrList = "";
		$attrList = convertViews::assembleAttributes ( $attrArray );
		$sql = "SELECT $attrList
				FROM `#__osefirewall_malware` AS m
							LEFT JOIN `#__osefirewall_files` AS f ON f.id = m.file_id
						LEFT JOIN `#__osefirewall_vspatterns` AS v ON m.pattern_id = v.id
					LEFT JOIN `#__osefirewall_vstypes` AS vt ON v.type_id = vt.id";
		return $sql;
	}
	private static function assembleAttributes($attrArray) {
		$attrList = "";
		foreach ( $attrArray as $attr ) {
			if ($attrList != "") {
				$attrList = $attrList . ",";
			}
			$attrList = $attrList . $attr;
		}
		return $attrList;
	}
}
?>