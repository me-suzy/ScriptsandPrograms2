<?php
/**
 * This response clears all static files.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Statics.inc' );
require_once( BASE_PATH.'core/Data.inc' );
require_once( BASE_PATH.'core/Upload.inc' ); //statics requires this...

class ClearStatic extends Response {

	function ClearStatic( &$buffer, &$args ) {
		$this->Response( $buffer, $args, TRUE );
	}

	function verify( &$args ) {
		global $gError, $gConfig;

		if (!$gConfig->mProp['EnableStaticExport']) {
			$gError->add("The current configuration doesn't allow to work with static pages.");
		}

		if (empty($_REQUEST['iMode']) or $_REQUEST['iMode'] != 'outdated') {
			$_REQUEST['iMode'] = 'all';
		}

		if (empty($_REQUEST['iType']) or $_REQUEST['iType'] != 'files') {
			$_REQUEST['iType'] = 'pages';
		}
	}

	function service( &$buffer, &$args ) {

		$this->changeState('out/Html');
		$args['Heading'] = "Clear Exported Pages or Files";
		$menu = Statics::getMenu();

		if ($_REQUEST['iType'] == 'files') {
			if ($_REQUEST['iMode'] == 'outdated') {
				$heading = 'Clear outdated exported files';
				$res =& Statics::clearOldUploaded();
				if (sizeof($res) == 0) {
					$report = 'There are no outdated files to delete.';
				} else {
					$report = implode(', ', $res);
				}
			} else {
				$heading = 'Clear all exported files';
				if (Statics::clearAllUploaded()) {
					$report .= "OK, removed all exported copies of the uploaded files.";
				} else {
					$report .= "Error while removing exported copies of uploaded files. Reason unknown. Retry the action.";
				}
			}
		} else {
			if ($_REQUEST['iMode'] == 'outdated') {
				$heading = 'Clear outdated exported Wiki pages';
				$res =& Statics::clearOld();
				if (sizeof($res) == 0) {
					$report = 'There are no outdated Wiki pages to delete.';
				} else {
					$report = implode(', ', $res);
				}
			} else {
				$heading = 'Clear all exported Wiki pages';
				if (Statics::clearAll()) {
					$report = "OK, removed all exported pages.";
				} else {
					$report = "Error while removing exported pages. Reason unknown. Retry the action.";
				}
			}
		}

		//do the output
		echo <<<eoh
$buffer
$menu
<h3>$heading</h3>
<p>
$report
</p>
<br>
eoh;
	}
}

?>