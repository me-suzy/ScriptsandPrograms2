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

class ReportTrailMapResult extends Response {

	function ReportTrailMapResult( &$buffer, &$args ) {
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

		if (!isset($_REQUEST['iSort']) or $_REQUEST['iSort'] != 0) {
			$_REQUEST['iSort'] = 1;
		}

	}

	function service( &$buffer, &$args ) {
		global $gConfig, $gError;

		$args['Heading'] = "Trail Map / Teaser Text Report for \"{$_REQUEST['iName']}\"";
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
		if (!Report::validate($sizePages, $_REQUEST['iReport'], $_REQUEST['iOverwrite'])) {
			return FALSE;
		}

		//build up the report: sort => page, desc|page, txt
		$arrayReport = array();
		echo '<pre>';
		foreach( $arrPages as $page => $fields ) {
			if (sizeof($fields) != 4) {
				echo "Warning: '$page' contains an invalid marker. Ignoring this page.\n";
			} else {
				$desc = (empty($fields[1]))?($page):($fields[1]);
				$arrayReport[$fields[2].$page] = array($page, $desc, $fields[3]);
			}
		}
		echo '</pre>';

		//free some resources
		unset($arrPages);

		//sort the array asc or desc
		if ($_REQUEST['iSort'] == 0) {
			ksort($arrayReport);
		} else {
			krsort($arrayReport);
		}

		//generate the report; sort the keywords and the pages
		$report = '';
		foreach ($arrayReport as $id => $pages) {
			$report .= <<<eoh
=={$pages[1]}
$pages[2] -_
([[{$pages[0]}|{$pages[0]}]])

eoh;
		}

		//free some resources
		unset($arrayReport);

		if (!Report::store($report , $_REQUEST['iReport'], $_REQUEST['iOverwrite'])) {
			return FALSE;
		}

		//do the output
		echo Report::getInfoReportCommon( "&amp;iSort={$_REQUEST['iSort']}" );
	}
}

?>