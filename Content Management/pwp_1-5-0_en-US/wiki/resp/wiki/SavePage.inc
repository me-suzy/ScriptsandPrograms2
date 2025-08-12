<?php
/**
 * This response saves changed wiki pages and detects conflicts.
 * Receives both, QuickEdit and Edit requests. Forwards either to ViewPage
 * or switches back to QuickEdit.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

define('INSERT_COMMENT', '!--(+)--!');

require_once( BASE_PATH.'core/Data.inc' );
require_once( BASE_PATH.'core/Trash.inc' );
require_once( BASE_PATH.'core/History.inc' );
require_once( BASE_PATH.'core/Lock.inc' );

class SavePage extends Response {

	function SavePage( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function verify( &$args ) {
		global $gConfig, $gError;

		//check file existence
		if (!(isset($_REQUEST['iPage']) and Data::isValidFileName($_REQUEST['iPage']))) {
			$gError->add("Missing or invalid page name, cannot save this page.");
		} else if (Trash::fileExists($_REQUEST['iPage'].DATA_EXTENSION)) {
			$gError->add("The page was moved to trash: {$_REQUEST['iPage']} Re-activate the page. 'Save' not possible.");
		} else if (!(Data::fileExists($_REQUEST['iPage']) )) {
			$gError->add("The page does neither exist nor was it deleted: {$_REQUEST['iPage']} Cannot save this.");
		}

		//check file modification time param
		if (!isset($_REQUEST['iFileModTime'])) {
			$gError->add("Missing parameter 'file modification time' used to prevent conflicts.");
		} else {
			$_REQUEST['iFileModTime'] = (int)$_REQUEST['iFileModTime']; //must be numerical
		}

		//check minor change param
		if (isset($gConfig->mProp['HistoryBehaviour'])) { //introduced in 1.5.0
			if ($gConfig->mProp['HistoryBehaviour'] == 1) {
				$_REQUEST['iIsMinorChange'] = 0; //record change in history
			}
			if ($gConfig->mProp['HistoryBehaviour'] == -1) {
				$_REQUEST['iIsMinorChange'] = 1; //ignore any changes
			}
		}
		if (!isset($_REQUEST['iIsMinorChange']) or $_REQUEST['iIsMinorChange'] != 1) {
			$_REQUEST['iIsMinorChange'] = 0;
		}


		//check text itself
		if (!isset($_REQUEST['iText'])) {
			$_REQUEST['iText'] = ''; //Does any browser supress this param if the text area was empty?
		} else if (strlen($_REQUEST['iText']) > ($gConfig->mProp['MaxPostSize'] * 1024)) {
			$gError->add("Your text is longer than the allowed {$gConfig->mProp['MaxPostSize']} KB."); //unlikely
		}

		//check type of operation; see QuickEditPage
		if (!isset($_REQUEST['iType']) or !strpos(',standard,append,prepend,insert', $_REQUEST['iType']) ) {
			$gError->add("Missing or invalid operation type parameter.");
		}
	}

	function service( &$buffer, &$args ) {
		global $gConfig, $gError;

		//prepare text corresponding to type of operation
		if (strpos(',append,prepend,insert', $_REQUEST['iType']) ) {
			$text =& Data::retrieve($_REQUEST['iPage']);
			switch($_REQUEST['iType']) {
				case 'append':
					$_REQUEST['iText'] = "$text\n{$_REQUEST['iText']}";
					break;
				case 'prepend':
					$_REQUEST['iText'] .= "\n$text";
					break;
				case 'insert':
					if (strpos($text, INSERT_COMMENT) !== FALSE) {
						$_REQUEST['iText'] = str_replace( INSERT_COMMENT, INSERT_COMMENT."{$_REQUEST['iText']}\n", $text );
					} else {
						$gError->add("Cannot insert text into page '{$_REQUEST['iPage']}'; there is no mark: ".INSERT_COMMENT);
						return false;
					}
					break;
			}
			$this->changeState('wiki/QuickEditPage');
		} else {
			$this->changeState('wiki/ViewPage');
		}

		//release the lock
		if ($gConfig->mProp['LockForEdit'] ) {
			Lock::release($_REQUEST['iPage']);
		}

		Data::preformatText($_REQUEST['iText']);

		//detect conflicts
		if ($_REQUEST['iFileModTime'] != Data::getFileModTime($_REQUEST['iPage'])) {
			//someone else changed the file, we have a conflict but that's ok
			$args['isConflict'] = TRUE;
			$this->changeState('wiki/Conflict');
			return true;
		}

		//preserve old revision
		if ($_REQUEST['iIsMinorChange'] == 0) {
			if (!History::store($_REQUEST['iPage'])) {
				$gError->add("Could not store old revison of: {$_REQUEST['iPage']}.");
				return false;
			}
		}


		//will create the file if it does not exist
		if (!Data::store($_REQUEST['iPage'], $_REQUEST['iText'])) {
			$gError->add("Could not store file: {$_REQUEST['iPage']}.");
			return false;
		}
	}

}

?>