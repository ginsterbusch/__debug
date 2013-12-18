<?php
/**
 * Plugin Name: __debug()
 * Description: Minimalistic debug functions for WordPress. Main course: __debug
 * Plugin URI: http://usability-idealist.de/
 * Version: 1.2
 * Author: Fabian Wolf
 * Author URI: http://usability-idealist.de/
 * License: GNU GPL v3
 */

 
if( !function_exists('__debug') ) {
	function __debug( $data, $title = 'Debug (visible for admins only):', $class = 'theme__debug' ) {
		$is_user_logged_in = ( function_exists('is_user_logged_in') && is_user_logged_in() );
		$current_user_can_manage_options = ( function_exists('current_user_can') && current_user_can('manage_options') );
		
		$is_allowed_user = ( $is_user_logged_in && $current_user_can_manage_options );
		
		if( $is_allowed_user || !empty($_COOKIE['___debug']) ) {
			echo '<div class="'.$class.'"><p class="debug-title">'.$title.'</p><pre>'.htmlentities2(print_r($data, true)).'</pre></div>';
		}
	}
}

