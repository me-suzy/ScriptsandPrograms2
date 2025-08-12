<?php
/**
 * This response moves a wiki page into the trash bin.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Trash.inc' );
require_once( BASE_PATH.'core/Data.inc' );
require_once( BASE_PATH.'core/Cache.inc' );
require_once( BASE_PATH.'core/Lock.inc' );

class DeletePage extends Response {

	function DeletePage( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function verify( &$args ) {
		global $gError;

		if (!(isset($_REQUEST['iPage']) and Data::isValidFileName($_REQUEST['iPage']))) {
			$gError->add("Missing or invalid file name, cannot delete page!");
		} else if (!Data::fileExists($_REQUEST['iPage'])) {
			$gError->add("This file does not exist and cannot be deleted: {$_REQUEST['iPage']} (Deleted by other one?)");
		}
	}

	function service( &$buffer, &$args ) {
		global $gError, $gConfig;

		if ($gConfig->mProp['LockForEdit'] ) {
			$tstamp = 0;
			$user   = '';
			if (Lock::isLocked($_REQUEST['iPage'], $tstamp, $user)) {
				$args['TStamp'] = $tstamp;
				$args['User']   = $user;
				$args['Goto']   = $_REQUEST['iRequest'];
				$this->changeState('wiki/IsLocked');
				return true;
			}
		}

		if (Trash::store($_REQUEST['iPage'])) {
			Cache::clearNumerical( INDEX_PAGE_NAME );
			Lock::clear($_REQUEST['iPage']);
		} else {
			$gError->add("Could not delete file: {$_REQUEST['iPage']} (Failed write to trash?)");
		}

		echo <<<eoh
$buffer
<div id='textcolumn' style='padding: 50,0,50,0;'>
<p>
The file <strong>{$_REQUEST['iPage']} has been moved into the trash bin</strong>.
You may undelete the file, restoring also all existing old revisions,
<a href='{$_SERVER['PHP_SELF']}?iRequest=trash/TrashList'>here</a>.
The file can be removed physically from the tash bin after {$gConfig->mProp['MinFileAge']} days.
<p>
</div>
eoh;

		$args['Heading'] = "Deleted: {$_REQUEST['iPage']}";
		$_REQUEST['iPage'] = ''; //prevents file edit, history links
		$this->changeState('out/Html');

	}

}

?>