<?php
/**
 * Plugin Name: __debug()
 * Description: Minimalistic debug functions for WordPress. Main course: __debug
 * Plugin URI: http://github.com/ginsterbusch/
 * Version: 1.6
 * Author: Fabian Wolf
 * Author URI: http://usability-idealist.de/
 * License: GNU GPL v3
 */

	// old function. uncomment and use at own risk ;)
	/*function __debug( $data, $title = 'Debug (visible for admins only):', $class = 'theme__debug' ) {
		$is_user_logged_in = ( function_exists('is_user_logged_in') && is_user_logged_in() );
		$current_user_can_manage_options = ( function_exists('current_user_can') && current_user_can('manage_options') );
		
		$is_allowed_user = ( $is_user_logged_in && $current_user_can_manage_options );
		
		if( $is_allowed_user || !empty($_COOKIE['___debug']) ) {
			echo '<div class="'.$class.'"><p class="debug-title">'.$title.'</p><pre>'.htmlentities2(print_r($data, true)).'</pre></div>';
		}
	}*/


if( !class_exists('__debug') ) {
	require_once( plugin_dir_path(__FILE__) . 'debug.class.php');	
}

// wrapper function
if( class_exists('__debug') && !function_exists('__debug') ) {
	function __debug( $data, $title, $class = 'theme__debug' ) {
		new __debug( $data, $title, array('class' => $class) );
	}
}

require_once( plugin_dir_path(__FILE__) . 'plugin.class.php' );
//require_once( plugin_dir_path(__FILE__ ) . 'admin.class.php' );
