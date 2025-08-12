<?php
/**
 * This response renames an existing page
 * and forwards to view this page. Tt does take care
 * of the history entries.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Data.inc' );
require_once( BASE_PATH.'core/Trash.inc' );
require_once( BASE_PATH.'core/History.inc' );
require_once( BASE_PATH.'core/Cache.inc' );
require_once( BASE_PATH.'core/Lock.inc' );

class RenameResult extends Response {

	function RenameResult( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function verify( &$args ) {
		global $gError;

		//source must exist
		if (empty($_REQUEST['iPage'])) {
			$gError->add("Missing parameter.");
		} else if (!Data::fileExists($_REQUEST['iPage'])) {
			$gError->add("This page does NOT exists and cannot be renamed: {$_REQUEST['iPage']}");
		}

		//target has to obey the Wiki name rules and must not exist
		if (empty($_REQUEST['iNewPage'])) {
			$gError->add("Missing parameter.");
		} elseif (!Data::isValidFileName($_REQUEST['iNewPage'])) {
			$gError->add("The submitted page name is empty or contains invalid characters. Do not use &lt; &gt; * | etc.");
		} else if (Data::fileExists($_REQUEST['iNewPage'])) {
			$gError->add("This page does already exists and cannot be used as rename target: {$_REQUEST['iNewPage']}");
		} else if ( strpos(",{$_REQUEST['iNewPage']}", DATA_EXTENSION) ) {
			$gError->add("This page name contains an invalid substring: {$_REQUEST['iNewPage']}");
		}
	}

	function service( &$buffer, &$args ) {
		global $gError, $gConfig;

		if ($gConfig->mProp['LockForEdit'] ) { //can cope with not existing pages/locks
			$tstamp = 0;
			$user   = '';
			if (Lock::isLocked($_REQUEST['iPage'], $tstamp, $user)) {
				$args['TStamp'] = $tstamp;
				$args['User']   = $user;
				$args['Goto']   = $_REQUEST['iRequest'];
				$args['URLArgs'] = "iNewPage={$_REQUEST['iNewPage']}";
				$this->changeState('wiki/IsLocked');
				return FALSE;
			} else {
				Lock::create($_REQUEST['iPage']);
			}
		}

		//cannot copy a file that still exists in trash:
		if (!Trash::fileExists( $_REQUEST['iNewPage'].DATA_EXTENSION )) {

			if (Data::renamePage($_REQUEST['iPage'], $_REQUEST['iNewPage'])) {
				History::renamePage($_REQUEST['iPage'], $_REQUEST['iNewPage']);
				Lock::release($_REQUEST['iPage']); //would be a dead lock file otherwise...
				$_REQUEST['iPage'] = $_REQUEST['iNewPage']; //re-adjust iPage for edit
				Cache::clearNumerical( INDEX_PAGE_NAME );
				$this->changeState('wiki/ViewPage');
			} else {
				$gError->add("Could not rename Wiki page: {$_REQUEST['iPage']}");
			}

		} else {
			$gError->add("The page <strong>{$_REQUEST['iNewPage']}</strong> already exists was moved into the trash bin. Aborted rename operation.");
		}
	}

}

?>