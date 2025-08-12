<?php
/**
 * This response executes a full text search in Wiki pages and/or uploaded files.
 * It generates a report as Wiki page or appends the report to an existing page.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Trash.inc' );
require_once( BASE_PATH.'core/History.inc' );
require_once( BASE_PATH.'core/Data.inc' );
require_once( BASE_PATH.'core/Upload.inc' );
require_once( BASE_PATH.'core/Report.inc' );
require_once( BASE_PATH.'core/Cache.inc' );

class ReportTextResult extends Response {

	function ReportTextResult( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function verify( &$args ) {
		global $gError;

		if (empty($_REQUEST['iSearch'])) { //accept 0 as search string
			$gError->add("Missing search string, cannot search for nothing.");
		}

		if (empty($_REQUEST['iReport']) or !Data::isValidFileName($_REQUEST['iReport'])) {
			$gError->add("Missing or invalid name for report. Cannot create such a report page.");
		}

		if (!isset($_REQUEST['iOverwrite']) or $_REQUEST['iOverwrite'] < 0 or $_REQUEST['iOverwrite'] > 2) {
			$gError->add("Invalid parameter value.");
		}

		if (empty($_REQUEST['iSearchWiki']) or $_REQUEST['iSearchWiki'] != 1) {
			$_REQUEST['iSearchWiki'] = 0;
		}

		if (empty($_REQUEST['iSearchUpload']) or $_REQUEST['iSearchUpload'] != 1) {
			$_REQUEST['iSearchUpload'] = 0;
		}

		if ( $_REQUEST['iSearchUpload'] + $_REQUEST['iSearchWiki'] == 0 ) {
			$gError->add("Please check at least ONE of the search areas: Wiki pages or uploaded files.");
		}
	}

	function service( &$buffer, &$args ) {
		global $gConfig, $gError;

		$args['Heading'] = "Report Based on Full Text Search \"{$_REQUEST['iSearch']}\"";
		$this->changeState('out/Html');

		//check the report file depending on overwrite status
		if (!Report::checkLock($args, $_REQUEST['iReport'], "iOverwrite={$_REQUEST['iOverwrite']}&amp;iSearch={$_REQUEST['iSearch']}&amp;iSearchWiki={$_REQUEST['iSearchWiki']}&amp;iSearchUpload={$_REQUEST['iSearchUpload']}" )) {
			return FALSE;
		}
		if (!Report::prepare( $_REQUEST['iReport'], $_REQUEST['iOverwrite'])) {
			return FALSE; //abort here, error has been added
		}

		//execute the search
		if ($_REQUEST['iSearchWiki']) {
			$arrPages =& Data::getTextSearchList( $_REQUEST['iSearch'] );
		} else {
			$arrPages = array();
		}
		if ($_REQUEST['iSearchUpload']) {
			$arrFiles =& Upload::getTextSearchList( $_REQUEST['iSearch'] );
		} else {
			$arrFiles = array();
		}

		$sizePages = sizeof($arrPages);
		$sizeFiles = sizeof($arrFiles);

		//do not create an empty report
		if (!Report::validate($sizePages + $sizeFiles, $_REQUEST['iReport'], $_REQUEST['iOverwrite'])) {
			return FALSE;
		}

		$report =& Report::getListReportSearch($arrPages, $sizePages, $arrFiles, $sizeFiles);

		unset($arrFiles);
		unset($arrPages);

		if (!Report::store($report , $_REQUEST['iReport'], $_REQUEST['iOverwrite'])) {
			return FALSE;
		}

		//do the output
		echo Report::getInfoReportSearch($sizePages, $sizeFiles, TRUE); //is full text
	}
}

?>