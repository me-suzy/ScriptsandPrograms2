<?php
/**
 * This response contains the quick edit form for wiki pages.
 * It checks for a lock on the page before it forwards to
 * ViewPage.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Data.inc' );
require_once( BASE_PATH.'core/Lock.inc' );
require_once( BASE_PATH.'conf/Snippets.inc' );

class QuickEditPage extends Response {

	function QuickEditPage( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function verify( &$args ) {
		global $gError;

		if (!isset($_REQUEST['iPage']) or !Data::isValidFileName($_REQUEST['iPage'])) {
			$gError->add("Missing or invalid page name, cannot edit page!");
		} else if (!Data::fileExists($_REQUEST['iPage'])) {
			$gError->add("This file does not exist and cannot be edited: {$_REQUEST['iPage']} (Maybe deleted?)");
		}
	}

	function service( &$buffer, &$args ) {
		global $gConfig, $gError;

		if ($gConfig->mProp['LockForEdit'] ) {
			$tstamp = 0;
			$user   = '';
			if (Lock::isLocked($_REQUEST['iPage'], $tstamp, $user)) {
				$args['TStamp'] = $tstamp;
				$args['User']   = $user;
				$args['Goto']   = $_REQUEST['iRequest'];
				$this->changeState('wiki/IsLocked');
				return true;
			} else {
				Lock::create($_REQUEST['iPage']);
			}
		}

		$fileModTime = Data::getFileModTime($_REQUEST['iPage']); //used to check for conflicts
		$snippets	 = ($gConfig->mProp['UseSnippets'])?(Snippets::getSnippets('editform', 'iText')):('');
		$rows = (int)($gConfig->mProp["EditRows"]/4);

		$minorChangeCheckbox = '';
		if (!isset($gConfig->mProp['HistoryBehaviour']) or $gConfig->mProp['HistoryBehaviour'] == 0 ) {
			$minorChangeCheckbox = <<<eoh
<input type='checkbox' name='iIsMinorChange' value='1' checked>
<small>Minor change: Do not create a new revision, just correct the existing one.</small>
<br>
eoh;
		}

		//print the edit form
		echo <<<eoh
$buffer
<form name='editform' action='{$_SERVER['PHP_SELF']}' method='post'>
<input type='hidden' name='iRequest' value='wiki/SavePage'>
<input type='hidden' name='iPage' value='{$_REQUEST['iPage']}'>
<input type='hidden' name='iFileModTime' value='$fileModTime'>
<textarea name='iText' cols='{$gConfig->mProp["EditColumns"]}' rows='{$rows}' wrap='virtual'></textarea>
<br>
<input type='radio' name='iType' value='prepend'> prepend
<input type='radio' name='iType' value='insert'> insert
<input type='radio' name='iType' value='append' checked> append
<br>
$minorChangeCheckbox
<input type='submit' value=' OK '>
<input type='reset' value='Cancel'>
$snippets
</form>
eoh;
		$args['Heading'] = "{$_REQUEST['iPage']} (QuickEdit)";
		$this->changeState('wiki/ViewPage');
	}

}

?>