<?php
/**
 * This response re-activates a file from the history
 * (i.e. an older revision) into the data dir or the upload dir.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Data.inc' );
require_once( BASE_PATH.'core/Upload.inc' );
require_once( BASE_PATH.'core/Cache.inc' );
require_once( BASE_PATH.'core/Trash.inc' );
require_once( BASE_PATH.'core/History.inc' );
require_once( BASE_PATH.'core/Lock.inc' );

class ReActivate extends Response {

	function ReActivate( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function verify( &$args ) {
		global $gConfig, $gError;

		if (empty($_REQUEST['iPage']) or !Data::isValidFileName($_REQUEST['iPage'])) {
			$gError->add("Missing or invalid file name, cannot re-activate old revision!");
		}

		if (!isset($_REQUEST['iIsData']) or ($_REQUEST['iIsData'] != 1 and $_REQUEST['iIsData'] != 0)) {
			$gError->add("Invalid parameter submitted!"); //somone tampered with iIsData
		}

		if (empty($_REQUEST['iRevision'])) {
			$gError->add("Missing revision parameter.");
		} else {
			//avoid bad path
			$_REQUEST['iRevision'] = str_replace( '..', '_', $_REQUEST['iRevision'] );
			$_REQUEST['iRevision'] = str_replace( '/', '_', $_REQUEST['iRevision'] );
		}

		//might have been deleted by clearAll()
		if (!History::fileExists( $_REQUEST['iRevision'] )) {
			$gError->add("The requested revision is not any longer available.");
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
				$args['Goto']   = "history/ReActivate&amp;iRevision={$_REQUEST['iRevision']}&amp;iIsData={$_REQUEST['iIsData']}";
				$this->changeState('wiki/IsLocked');
				return true;
			}
		}

		if ( $_REQUEST['iIsData'] == 1 ) {

			if (!Data::fileExists($_REQUEST['iPage'])  and !Trash::fileExists($_REQUEST['iPage'].DATA_EXTENSION)) {
				$gError->add("Sorry, the main revision does not exist any longer: {$_REQUEST['iPage']}. Cannot restore.");
			} else if (History::reStore( $_REQUEST['iPage'], $_REQUEST['iRevision'] )) {
				Cache::clearNumerical( INDEX_PAGE_NAME );
			} else {
				$gError->add("Could not re-activate page from history: {$_REQUEST['iPage']}. (Maybe deleted?)");
			}

			$this->changeState('wiki/ViewPage');

		} else {

			if (!Upload::fileExists($_REQUEST['iPage']) and !Trash::fileExists($_REQUEST['iPage'])) {
				$gError->add("Sorry, the main revision does not exist any longer: {$_REQUEST['iPage']} Cannot restore.");
			} else if (!History::reStoreUpload( $_REQUEST['iPage'], $_REQUEST['iRevision'] )) {
				$gError->add("Could not re-activate file from history: {$_REQUEST['iPage']} (Maybe deleted?)");
			}

			Cache::clearNumerical(INDEX_FILE_NAME);
			$this->changeState('upload/UploadList');
			$_REQUEST['iPage'] = NULL;
		}
	}
}

?>