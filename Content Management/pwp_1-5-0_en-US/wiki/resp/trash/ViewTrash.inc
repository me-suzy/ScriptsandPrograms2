<?php
/**
 * This response shows a wiki page out of the trash.
 * Provides a re-activation link.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Data.inc' );
require_once( BASE_PATH.'core/WikiEngine.inc' );
require_once( BASE_PATH.'core/Trash.inc' );

class ViewTrash extends Response {

	function ViewTrash( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function verify( &$args ) {
		global $gConfig, $gError;

		if (!isset($_REQUEST['iTrashPage']) or !Data::isValidFileName($_REQUEST['iTrashPage'])) {
			$gError->add("Missing or invalid file name, cannot show file!");
		}

		if (!Trash::fileExists( $_REQUEST['iTrashPage'] )) {
			$gError->add("The requested file is not any longer in the trash bin.");
		}
	}

	function service( &$buffer, &$args ) {
		global $gConfig, $gError, $gEngine;

		//process wiki code, no cache to be used or chache miss happened
		$tempName = str_replace(DATA_EXTENSION, '',  $_REQUEST['iTrashPage']);
		$text =& Trash::retrieve($tempName);
		if ($text !== FALSE) { //type equal
			$gConfig->mProp['ShowInfoLine'] = FALSE; //supress info line
			$gEngine->transformHtml( $text );
			$text = "<div id='textcolumn'>\n$text\n</div>";
			echo <<<eoh
$text
<form action='{$_SERVER['PHP_SELF']}' method='post'>
<input type='hidden' name='iRequest' value='trash/ReActivate'>
<input type='hidden' name='iTrashPage' value='{$_REQUEST['iTrashPage']}'>
<input type='submit' value='Unerase'>
<input type='button' value='Go Back' onclick='history.back()'>
<br>
</form>
eoh;
		} else {
			$gError->add("Could not read page from trash: {$_REQUEST['iTrashPage']} (Maybe deleted?)");
		}

		$_REQUEST['iTrashPage'] = str_replace( DATA_EXTENSION, '', $_REQUEST['iTrashPage']);
		$args['Heading'] = "View erased page: {$_REQUEST['iTrashPage']}";
		$this->changeState('out/Html');
	}

}

?>