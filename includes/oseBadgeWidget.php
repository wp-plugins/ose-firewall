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
				'description' => 'Show the Centrora Security Badget' 
		);
		
		$control_ops = array (
				'width' => 200,
				'height' => 250 
		);
		
		$this->WP_Widget ( 'ose_Badge_Widget', 'Centrora Security Badge Widget', $widget_ops, $control_ops );
	}
	public function __construct() {
		parent::__construct ( 'ose_Badge_Widget', 'Centrora Security Badge Widget', array (
				'description' => __ ( 'Show the Centrora Security Badget' ) 
		) );
	}
	public function form( $instance ) {
		$style = ! empty( $instance['style'] ) ? $instance['style'] : __( '1', 'text_domain' );
		$styleArray = array(1=>'Blue', 3=>'Green', 5=>'Red', 11=>'Grey');
		$html= '<p>
			<label for="'.$this->get_field_id( 'style' ).'">'._e( 'Style:' ).'</label> 
			<select class="widefat" id="'.$this->get_field_id( 'style' ).'" name="'.$this->get_field_name( 'style' ) .'" >';
				foreach ($styleArray as $key => $value)
				{
					$html .= '<option value="'.$key.'" ';
					if ($key == $style)
					{
						$html .= 'selected';
					}
					$html .= '>'.$value.'</option>';
				}
		$html .= '</select></p>';
		echo $html; 
	}
		
	public function widget($args, $instance) {
		$style = $instance['style'];
		if (oseFirewall::isDBReady())
		{
			oseFirewall::callLibClass ( 'vsscanner', 'vsscanner' );
			$scanner = new virusScanner ();
			$log = $scanner->getScanninglog ();
			if (empty ( $log )) {
				$status = 'Protected: '. date ( 'Y-m-d' );
			} else {
				$status = $log->status.': '. date("Y-m-d", $log->date);
			}
			$this->register_plugin_styles ($style);
			$trackingCode = $this->getTrackingCode();
			if (!empty($trackingCode))
			{
				$url = 'http://www.centrora.com/store/centrora-subscriptions?tracking='.$trackingCode;
			}
			else
			{
				$url ='http://www.centrora.com/store/centrora-subscriptions';
			}
			echo '<div id ="osebadge"><a href="'.$url.'" target="_blank"><div id="osebadge-content"><div class="osestatus">' . $status . '</div></div><div id="osebadge-footer"></div></a></div>';
		}
	}
	protected function getTrackingCode () {
		global $wpdb;
		$results = $wpdb->get_results( 'SELECT * FROM `'.$wpdb->prefix.'ose_secConfig` WHERE `key` = "trackingCode"', OBJECT );
		if (count($results)==1)
		{
			$code = $results[0]->value;
		}
		else 
		{
			$code ='';
		}
		return $code;
	}
	public function register_plugin_styles($style) {
		wp_register_style ( 'ose-badge-style', plugins_url ( 'ose-firewall/public/css/badge.css' ) );
		wp_register_style ( 'ose-badge-style-'.$style, plugins_url ( 'ose-firewall/public/css/badges/style'.$style.'.css' ) );
		wp_enqueue_style ( 'ose-badge-style' );
		wp_enqueue_style ( 'ose-badge-style-'.$style );
	}
}
add_action ( 'widgets_init', create_function ( '', 'register_widget( "oseBadgeWidget" );' ) );
?>