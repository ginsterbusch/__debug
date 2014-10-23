<?php
/**
 * Simple Debug class for WordPress
 *
 * @author Fabian Wolf
 * @link http://usability-idealist.de/
 * @version 1.4-full
 * @license GNU GPL v3
 * 
 * Features:
 * - class acts as a namespace
 * - debug output is displayed only to logged in users with the manage_options capability (may be optionally changed or disabled)
 * - optionally writes data into a remote-read protected logfile (default location: wp_upload_dir)
 */

if( !class_exists('__debug' ) ) :

class __debug {
	protected $params, $title;
	
	public static $version = 1.4;
	
	function __construct( $data, $title = 'Debug:', $arrParams = false ) {
	
		
		$this->params = (object) array(
			'class' => ( isset( $arrParams['class'] ) ? $arrParams['class'] : 'theme__debug' ),
			'capability' => ( isset( $arrParams['capability'] ) && $arrParams['capability'] !== true ? $arrParams['capability'] : 'manage_options' ),
			'logged_in' => ( isset( $arrParams['logged_in'] ) ? $arrParams['logged_in'] : true ),
			'log' => ( !empty( $arrParams['log'] ) ? true : false ),
		);
		
		// read upload_dir ONLY if logging is ENABLED
		if( !empty($this->params->log_data) ) {
		
			if( !empty( $arrParams['log_file'] ) ) {
				$this->params->log_file = $arrParams['log_file'];
			} else {
				$upload_dir = wp_upload_dir();
				$this->params->log_file = trailingslashit( $upload_dir['basedir'] ) . '__debug.log.php';
			}
		}
		
		
		if( !empty($this->params->log ) ) {
			$this->_log_data( $data, $title );
		} else {
			$this->debug( $data, $title );
		}
	}

	protected function debug( $data, $title = 'Debug:' ) {
		
		// default both to true
		$is_user_logged_in = true;
		$current_user_can_use_capability = true; 
		
		if( !empty( $this->params->logged_in )  ) {
			$is_user_logged_in = ( function_exists('is_user_logged_in') && is_user_logged_in() );
		}
		
		if( !empty($this->params->capability ) ) {
			$current_user_can_use_capability = ( function_exists('current_user_can') && current_user_can( $this->params->capability ) );
		}
		
		$is_allowed_user = ( $is_user_logged_in && $current_user_can_use_capability );
		
		if( $is_allowed_user ) {
			echo '<div class="'.$this->params->class.'"><p class="debug-title">'.$title.'</p><pre class="debug-content">'.$this->htmlentities2( print_r($data, true) ).'</pre></div>';

		}
	}
	
	public static function log( $data, $title = false, $arrParams = array() ) {
		$arrParams['log'] = true;
		
		new self( $data, $title, $arrParams );
	}
	
	protected function _log_data( $data, $title = false ) {
		if( !empty( $this->params->log_file ) ) {
			
			
			$arrLogEntry = array( 'title' => date('Y-m-d H:i:s') );
			
			if( !empty( $title ) ) {
				$arrLogEntry['title'] .= ' - ' . $title ;
			}
			$arrLogEntry['title'] . ':';
			
			
			$arrLogEntry['data'] = print_r( $data, true );
			
			$strLogEntry = '<logentry>' . "\n" . implode( "\n", $arrLogEntry ) . '</logentry>\n';
							
			
			file_put_contents( $this->params->log_file, $strLogEntry, FILE_APPEND );
		}
	}
	
	/**
	 * NOTE: Taken straight from wp-includes/formatting.php (and slighty adapted; but that's just for show)
	 * 
	 * Convert entities, while preserving already-encoded entities.
	 *
	 * @link http://www.php.net/htmlentities Borrowed from the PHP Manual user notes.
	 *
	 * @since 1.2.2
	 *
	 * @param string $content The text to be converted.
	 * @return string Converted text.
	 */
	protected function htmlentities2( $content ) {
		$return = $content;
		
		$translation_table = get_html_translation_table( HTML_ENTITIES, ENT_QUOTES );
		$translation_table[chr(38)] = '&';
		
		$return = preg_replace( "/&(?![A-Za-z]{0,4}\w{2,3};|#[0-9]{2,3};)/", '&amp;', strtr( $return, $translation_table ) );
		
		return $return;
	}
}

endif;
 
