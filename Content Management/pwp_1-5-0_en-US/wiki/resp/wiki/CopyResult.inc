<?php
/**
 * This response creates a copy of an existing page
 * and forwards to edit this page. The copy action does
 * not take care of the history entries - the new file
 * will have an empty history.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Data.inc' );
require_once( BASE_PATH.'core/Trash.inc' );
require_once( BASE_PATH.'core/Cache.inc' );

class CopyResult extends Response {

	function CopyResult( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function verify( &$args ) {
		global $gError;

		//source must exist
		if (empty($_REQUEST['iPage'])) {
			$gError->add("Missing parameter.");
		} else if (!Data::fileExists($_REQUEST['iPage'])) {
			$gError->add("This page does NOT exists and cannot be copied: {$_REQUEST['iPage']}");
		}

		//target has to obey the Wiki name rules and must not exist
		if (empty($_REQUEST['iNewPage'])) {
			$gError->add("Missing parameter.");
		} elseif (!Data::isValidFileName($_REQUEST['iNewPage'])) {
			$gError->add("The submitted page name is empty or contains invalid characters. Do not use &lt; &gt; * | etc.");
		} else if (Data::fileExists($_REQUEST['iNewPage'])) {
			$gError->add("This page does already exists and cannot be used as copy target: {$_REQUEST['iNewPage']}");
		} else if ( strpos(",{$_REQUEST['iNewPage']}", DATA_EXTENSION) ) {
			$gError->add("This page name contains an invalid substring: {$_REQUEST['iNewPage']}");
		}
	}

	function service( &$buffer, &$args ) {
		global $gError;

		//cannot copy a file that still exists in trash:
		if (!Trash::fileExists( $_REQUEST['iNewPage'].DATA_EXTENSION )) {

			if (Data::copyPage($_REQUEST['iPage'], $_REQUEST['iNewPage'])) {
				$_REQUEST['iPage'] = $_REQUEST['iNewPage']; //re-adjust iPage for edit
				Cache::clearNumerical( INDEX_PAGE_NAME );
				$this->changeState('wiki/EditPage');
			} else {
				$gError->add("Could not copy Wiki page: {$_REQUEST['iPage']}");
			}

		} else {
			$gError->add("The page <strong>{$_REQUEST['iNewPage']}</strong> already exists was moved into the trash bin. Aborted copy operation.");
		}
	}

}

?>