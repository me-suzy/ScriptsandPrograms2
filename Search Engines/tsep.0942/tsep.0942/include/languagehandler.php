<?php
  
/**
 * @package The Search Engine Project
 * @version 1.0
 * @copyright (C) 2005 by TSEP Development Team
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @since TSEP 0942
 * @author Toon Goedhart
 *
 * following will be filled automatically by SubVersion!
 * Do not change by hand!
 *  $LastChangedDate: 2005-06-11 18:55:44 +0200 (Sa, 11 Jun 2005) $
 *  @lastedited $LastChangedBy: toon $
 *  $LastChangedRevision: 134 $
 *
*/



/** 
 * Handles language releated issues
 **/
class languageHandler {
	/**
     * Constructor
     * @access protected
     */
	function languageHandler() {
	}
	
	/**
	 * Deletes CR/LF characters from all language strings
	 * to avoid conflicts with JavaScript code.
	 * 
	 * @access public
	 * @return void 
	 **/
	function cleanLanguageStrings() {
		global $tsep_lng;
		
		while ( list( $key, $val ) = each( $tsep_lng ) ) {
			$val = str_replace( "\r\n", "", $val );
			$val = str_replace( "\n\r", "", $val );
			$val = str_replace( "\r", "", $val );
			$val = str_replace( "\n", "", $val );
			$tsep_lng[$key] = $val;
		} // while		
	}
}


/***  MAIN CODE  ***************************************************************************/ 
$myLangHandler = new languageHandler();
$myLangHandler->cleanLanguageStrings();
?>
