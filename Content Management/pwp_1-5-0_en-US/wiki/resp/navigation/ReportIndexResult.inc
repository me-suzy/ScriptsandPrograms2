<?php
/**
 * This response creates a keyword index report based on marker fields in Wiki pages.
 * It generates a report as Wiki page or appends the report to an existing page.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Trash.inc' );
require_once( BASE_PATH.'core/History.inc' );
require_once( BASE_PATH.'core/Data.inc' );
require_once( BASE_PATH.'core/Report.inc' );
require_once( BASE_PATH.'core/Cache.inc' );

class ReportIndexResult extends Response {

	function ReportIndexResult( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function verify( &$args ) {
		global $gError;

		if (empty($_REQUEST['iName'])) { //accept 0 as search string
			$gError->add("Name (ID) parameter missing.");
		}

		if (empty($_REQUEST['iReport']) or !Data::isValidFileName($_REQUEST['iReport'])) {
			$gError->add("Missing or invalid name for report. Cannot create such a report page.");
		}

		if (!isset($_REQUEST['iOverwrite']) or $_REQUEST['iOverwrite'] < 0 or $_REQUEST['iOverwrite'] > 2) {
			$gError->add("Invalid parameter value.");
		}

	}

	function service( &$buffer, &$args ) {
		global $gConfig, $gError;

		$args['Heading'] = "Keyword Index  Report for \"{$_REQUEST['iName']}\"";
		$this->changeState('out/Html');

		//check the report file depending on overwrite status
		if (!Report::checkLock($args, $_REQUEST['iReport'], "iOverwrite={$_REQUEST['iOverwrite']}&amp;iName={$_REQUEST['iName']}" )) {
			return FALSE;
		}
		if (!Report::prepare( $_REQUEST['iReport'], $_REQUEST['iOverwrite'])) {
			return FALSE; //abort here, error has been added
		}


		$arrPages =& Data::getReportList( $_REQUEST['iName'] );
		$sizePages = sizeof($arrPages);


		//do not create an empty report
		if (!Report::validate($sizePages , $_REQUEST['iReport'], $_REQUEST['iOverwrite'])) {
			return FALSE;
		}

		//build up the index; keyword => page1, page2, etc.
		$arrayReport = array();
		echo '<pre>';
		foreach( $arrPages as $page => $fields ) {
			if (sizeof($fields) < 2) {
				echo "Warning: '$page' contains no keywords within the index marker. Ignoring this page.\n";
			} else {
				array_shift($fields); //get rid of index name
				foreach ($fields as $keyword) {
					if ($keyword != '') { //double colons
						if (!isset($arrayReport[$keyword])) {
							$arrayReport[$keyword] = array();
						}
						$arrayReport[$keyword][] = $page;
					}
				}
			}
		}
		echo '</pre>';

		//free some resources
		unset($arrPages);

		//generate the report; sort the keywords and the pages
		ksort($arrayReport);
		$sizeReport = sizeof($arrayReport);
		$counter = 0;
		$report = "{{||--}}\n";
		$length = ceil($sizeReport/$gConfig->mProp['IndexType']);
		$length = ($length < 7)?(7):($length);
		$split = ($gConfig->mProp['IndexType'] > 1)?($length):(-1);
		foreach ($arrayReport as $keyword => $pages) {
			sort($pages);
			$report .= ";$keyword:";
			foreach ($pages as $page) {
				$report .= "[[$page]] . ";
			}
			$report .= "\n";
			if (++$counter == $split) {
				$report .= "{{||}}\n";
				$split += $length;
			}
		}
		$report .= "{{--||}}\n";

		//free some resources
		unset($arrayReport);

		if (!Report::store($report , $_REQUEST['iReport'], $_REQUEST['iOverwrite'])) {
			return FALSE;
		}

		//do the output
		echo Report::getInfoReportCommon();

	}

}

?>