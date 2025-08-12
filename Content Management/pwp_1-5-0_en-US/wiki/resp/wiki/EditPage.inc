<?php
/**
 * This response contains the edit form for wiki pages.
 * It checks for a lock on the page before delivering it.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Data.inc' );
require_once( BASE_PATH.'core/Lock.inc' );
require_once( BASE_PATH.'conf/Snippets.inc' );

class EditPage extends Response {

	function EditPage( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function verify( &$args ) {
		global $gError;

		if (!isset($_REQUEST['iPage']) or !Data::isValidFileName($_REQUEST['iPage'])) {
			$gError->add("Missing or invalid page name, cannot edit page!");
		} else if (!Data::fileExists($_REQUEST['iPage'])) {
			$gError->add("This file does not exist and cannot be edited: {$_REQUEST['iPage']}");
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

		$text 		 = stripslashes( Data::retrieve($_REQUEST['iPage']) );
		//$text        = str_replace( array('&', '>', '<', '"'), array('&amp;', '&gt;', '&lt;', '&quot;'), $text);
		$fileModTime = Data::getFileModTime($_REQUEST['iPage']); //used to check for conflicts
		$snippets	 = ($gConfig->mProp['UseSnippets'])?(Snippets::getSnippets('editform', 'iText')):('');

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
<input type='hidden' name='iType' value='standard'>
<input type='hidden' name='iPage' value='{$_REQUEST['iPage']}'>
<input type='hidden' name='iFileModTime' value='$fileModTime'>
<textarea name='iText' cols='{$gConfig->mProp["EditColumns"]}' rows='{$gConfig->mProp["EditRows"]}' wrap='virtual'>$text</textarea>
<br>
$minorChangeCheckbox
<input type='submit' value=' OK '>
<input type='reset' value='Cancel'>
<input type='button' value='Go Back' onclick='history.back()'>
$snippets
</form>
eoh;
		$args['Heading'] = "Edit: {$_REQUEST['iPage']}";
		$this->changeState('out/Html');
	}

}

?>