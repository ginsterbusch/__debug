<?php
/**
 * Plugin loader / handling class
 *
 * @author Fabian Wolf
 * @package __debug
 * @since 1.6
 */

class __debug__plugin {
	var $pluginPrefix = '__debug_',
		$pluginVersion = '1.6',
		$pluginName = '__debug()';
		
	protected $arrSettings = array();
	
	/**
	 * Plugin instance.
	 *
	 * @see get_instance()
	 * @type object
	 */
	protected static $instance = NULL;

	

	/**
	 * Access this plugin’s working instance
	 *
	 * @wp-hook plugins_loaded
	 * @since   04/05/2013
	 * @return  object of this class
	 */
	public static function get_instance() {

		NULL === self::$instance and self::$instance = new self;

		return self::$instance;
	}

	
	function __construct() {
		$this->load_settings();
		
		add_action('init', array( $this, 'init_assets' ) );
		
		if( !empty( $this->arrSettings['load_assets'] ) ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'load_assets' ) );
			
			if( empty( $this->arrSettings['admin_only'] ) ) {
				add_action('wp_enqueue_scripts', array( $this, 'load_assets' ) );
			}
		}
		
	}
	
	function has_config_file() {
		$return = false;
		
		
		if( file_exists( plugin_dir_path( __FILE__ ) . 'debug-config.json' ) ) {
			$return = true;
		} else {
			$wp_upload_dir = wp_upload_dir();
			
			if( file_exists( trailingslashit( $wp_upload_dir['basepath'] ) . 'debug-config.json' ) ) {
				$return = true;
			}
		}
		
		return $return;
	}
	
	function load_config_file( $file = '' ) {
		$return = false;
		$strFile = 'debug-config.json';
		
		if( !empty( $file ) ) {
			$strFile = $file;
		}
		
		
		if( file_exists( plugin_dir_path( __FILE__ ) . $strFile ) ) {
			$strLoadPath = plugin_dir_path( __FILE__ ) . $strFile;
			
		} else {
			$wp_upload_dir = wp_upload_dir();
			
			if( file_exists( trailingslashit( $wp_upload_dir['basepath'] ) . $strFile ) ) {
				$strLoadPath = trailingslashit( $wp_upload_dir['basepath'] ) . $strFile;
			}
		}
		
		if( !empty( $strLoadPath ) ) {
			$config_data = file_get_contents( $strLoadPath );
			
			if( $this->is_json( $config_data ) != false ) {
				$return = json_decode( $config_data );
			}
			
			
		}
		
		return $return;
	}
	
	function is_json( $data = '', $doublequotes = false ) {
		$return = false;
	 
		/**
		 * Also see @link https://api.jquery.com/jQuery.parseJSON/
		* NOTE: Optional requirement of double quotes
		*/
	 
		if( !empty( $data ) ) {
			if( ( strpos( $data, '[' ) !== false && strpos( $data, ']' ) !== false ) ||
			( strpos( $data, '{' ) !== false && strpos( $data, '}' ) !== false ) ) {
				$return = true;
			}
	 
			if( !empty( $doublequotes ) ){
				$return = ( strpos( $data, '"' ) !== false ? true : false);
			}
		}
	 
		return $return;
	}
	
	function load_settings() {
		$this->arrSettings = array(
			'load_assets' => true,
			'admin_only' => false,
		);
		
		if( $this->has_config_file() != false ) {
			$settings = $this->load_config_file();
		} else {
			$settings = get_option( $this->pluginPrefix . 'settings', array() );
		}
		
		if( !empty( $settings ) ) {
			$this->arrSettings = wp_parse_args( $settings, $default_settings );
		}
	}
	
	function update_settings( $settings = array(), $suffix = 'settings' ) {
		$return = false;
		
		if( !empty( $settings ) ) {
			$return = update_option( $this->pluginPrefix . $suffix, $settings );
		}
		
		return $return;
		
	}
	
	function init_assets() {
		wp_register_style( $this->pluginPrefix . 'css', plugin_dir_url(__FILE__ ) . 'assets/debug.css' );
		wp_register_script( $this->pluginPrefix . 'js', plugin_dir_url(__FILE__ ) . 'assets/debug.js', array('jquery') );
	}
	
	function load_assets() {
		wp_enqueue_style( $this->pluginPrefix . 'css' );
		wp_enqueue_script( $this->pluginPrefix . 'js' );
	}
	
}

// load as soon as possible

add_action('plugins_loaded', array( '__debug__plugin', 'get_instance' ), 1 );
