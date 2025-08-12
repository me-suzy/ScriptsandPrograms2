<?php
/**
 * This response calculates a diff for two files. There are three cases:
 * a) Diff active version against history, name unknown: Fetch name from History.
 * b) Diff active version against history, name known.
 * c) Diff version from history agains previous versions; both names are known.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Data.inc' );
require_once( BASE_PATH.'core/History.inc' );
require_once( BASE_PATH.'core/Diff.inc' );

class ViewDiff extends Response {

	function ViewDiff( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function verify( &$args ) {
		global $gConfig, $gError;

		if (!empty($_REQUEST['iDiffNew'])) {
			//case c); both parameters have to be set; they must point to a history entry
			if (empty($_REQUEST['iDiffOld']) or !History::fileExists($_REQUEST['iDiffOld'])) {
				$gError->add("There is no older entry in the history to compare to. Cannot calculate a diff.");
			}
			if (!History::fileExists($_REQUEST['iDiffNew'])) {
			 	$gError->add("Invalid file for a diff.");
			}
		} else {
			//cases a), b)
			if (!Data::fileExists($_REQUEST['iPage'])) {
				//page might have been deleted, meanwhile
				$gError->add("The main revision does not exist any longer: {$_REQUEST['iPage']}");
			}
			if (empty($_REQUEST['iDiffOld'])) { //use the latest file from history
				//case a)
				$_REQUEST['iDiffOld'] = History::getNewestFileName($_REQUEST['iPage']);
				if (empty($_REQUEST['iDiffOld'])) {
					$gError->add("There is no previous version in the history. Cannot calculate a diff.");
				}
			}
		}
	}

	function service( &$buffer, &$args ) {
		global $gConfig, $gError, $gEngine;

		$args['Heading'] = "Diff for {$_REQUEST['iPage']}";
		$this->changeState('out/Html');

		$textNew = (empty($_REQUEST['iDiffNew']))?(Data::retrieve($_REQUEST['iPage'])):(History::retrieve( $_REQUEST['iDiffNew']));
		$textOld =& History::retrieve( $_REQUEST['iDiffOld'] );

		$text =& Diff::diffStrings($textNew, $textOld, FALSE); //not for edit mode

		echo $text;
	}

}

?>