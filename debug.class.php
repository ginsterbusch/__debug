<?php
/**
 * Simple Debug class for WordPress
 *
 * @author Fabian Wolf
 * @link http://usability-idealist.de/
 * @version 1.3
 * @license GNU GPL v3
 */
 
class __debug {
	protected $params, $title;
	
	function __construct( $data, $title = 'Debug:', $arrParams = false ) {
	
		
		$this->params = (object) array(
			'class' => ( isset( $arrParams['class'] ) ? $arrParams['class'] : 'theme__debug' ),
			'capability' => ( isset( $arrParams['capability'] ) && $arrParams['capability'] !== true ? $arrParams['capability'] : 'manage_options' ),
			'logged_in' => ( isset( $arrParams['logged_in'] ) ? $arrParams['logged_in'] : true ),
		);
		
		$this->debug( $data, $title );
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
 
