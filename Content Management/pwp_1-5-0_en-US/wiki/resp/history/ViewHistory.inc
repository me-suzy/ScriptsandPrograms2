<?php
/**
 * This response shows a Wiki page from the history.
 * History pages are never cached. Uploaded files in the
 * history are linked directly.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Data.inc' );
require_once( BASE_PATH.'core/WikiEngine.inc' );
require_once( BASE_PATH.'core/History.inc' );

class ViewHistory extends Response {

	function ViewHistory( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function verify( &$args ) {
		global $gConfig, $gError;

		if (empty($_REQUEST['iPage']) or !Data::isValidFileName($_REQUEST['iPage'])) {
			$gError->add("Missing or invalid file name, cannot show old revision!");
		} else if (!Data::fileExists($_REQUEST['iPage'])) {
			$gError->add("The main revision does not exist any longer: {$_REQUEST['iPage']}");
		}

		if (empty($_REQUEST['iRevision'])) {
			$gError->add("Missing revision parameter.");
		} else {
			//avoid bad path
			$_REQUEST['iRevision'] = str_replace( '..', '_', $_REQUEST['iRevision'] );
			$_REQUEST['iRevision'] = str_replace( '/', '_', $_REQUEST['iRevision'] );
		}

		if (!History::fileExists( $_REQUEST['iRevision'] )) {
			$gError->add("The requested revison is not any longer available.");
		}
	}

	function service( &$buffer, &$args ) {
		global $gConfig, $gError, $gEngine;

		$args['Heading'] = "Old revision of {$_REQUEST['iPage']}";
		$this->changeState('out/Html');

		//process wiki code, no cache to be used or chache miss happened
		$text =& History::retrieve( $_REQUEST['iRevision'] );
		if ($text !== FALSE) { //type equal
			$gConfig->mProp['ShowInfoLine'] = FALSE; //supress info line, would inform about active revision
			$gEngine->transformHtml( $text );
			$text = "<div id='textcolumn'>\n$text\n</div>";
			echo <<<eoh
$text
<form action='{$_SERVER['PHP_SELF']}' method='post'>
<input type='hidden' name='iRequest' value='history/ReActivate'>
<input type='hidden' name='iPage' value='{$_REQUEST['iPage']}'>
<input type='hidden' name='iIsData' value='1'>
<input type='hidden' name='iRevision' value='{$_REQUEST['iRevision']}'>
<input type='submit' value='Re-Activate'>
<input type='button' value='Go Back' onclick='history.back()'>
<br>
<small>
Store the active revision in the history and make <em>this</em> old revison the current one.
<br>
Note that you cannot edit older revisions. A click on 'Edit' will always load the current active revision.
</small>
</form>
eoh;
		} else {
			$gError->add("Could not read history file: {$_REQUEST['iPage']} (Maybe deleted?)");
		}
	}

}

?>