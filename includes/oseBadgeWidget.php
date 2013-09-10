<?php
/**
 * @version     2.0 +
 * @package       Open Source Excellence Security Suite
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
class oseBadgeWidget extends WP_Widget {
	public function oseBadgeWidget() {
		$widget_ops = array (
				'classname' => 'ose-badge-widget',
				'description' => 'Show the OSE Firewall Badget' 
		);
		
		$control_ops = array (
				'width' => 200,
				'height' => 250 
		);
		
		$this->WP_Widget ( 'ose_Badge_Widget', 'OSE Badge Widget', $widget_ops, $control_ops );
	}
	public function __construct() {
		parent::__construct ( 'ose_Badge_Widget', 'OSE Badge Widget', array (
				'description' => __ ( 'Show the OSE Firewall Badget' ) 
		) );
	}
	public function widget($args, $instance) {
		oseFirewall::callLibClass ( 'vsscanner', 'vsscanner' );
		$scanner = new virusScanner ();
		$log = $scanner->getScanninglog ();
		
		if (empty ( $log )) {
			
			$status = 'Protected';
			$date = date ( 'Y-m-d h:i:s' );
			$text = '';
		} else {
			
			$status = $log->status;
			$date = $log->date;
			$text = 'Last scanned on:';
		}
		$this->register_plugin_styles ();
		echo '<div id ="osebadge-bottom-right"><div id="osebadge-content"><div class="osestatus">' . $status . '</div>' . $text . '<br/>' . $date . '</div><div id="osebadge-footer"><a href="https://www.protect-website.com" target="_blank">Protected by OSE Firewall™</a></div></div>';
	}
	public function register_plugin_styles() {
		wp_register_style ( 'ose-badge-style', plugins_url ( 'ose-firewall/public/css/badge.css' ) );
		wp_enqueue_style ( 'ose-badge-style' );
	}
}
add_action ( 'widgets_init', create_function ( '', 'register_widget( "oseBadgeWidget" );' ) );
?>