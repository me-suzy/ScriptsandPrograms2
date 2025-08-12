<?php
/**
 * This response creates a TOC based on marker fields in Wiki pages.
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

class ReportTOCResult extends Response {

	/** TOC related member. */
	var $_mToc = array();

	/** TOC related member. */
	var $_mLink = array();

	function ReportTOCResult( &$buffer, &$args ) {
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

		$args['Heading'] = "TOC Report for \"{$_REQUEST['iName']}\"";
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

		//build up the TOC
		echo '<pre>';
		foreach( $arrPages as $page => $fields ) {
			if (sizeof($fields) != 4) {
				echo "Error: '$page' contains an invalid TOC marker. Ignoring this page.\n";
			} else {
				//add predecessor, level, wiki page, link text
				if (empty($fields[3])) {
					$fields[3] = $page;
				}
				$this->addToToc($fields[1], $fields[2], $page, $fields[3]);
			}
		}
		echo '</pre>';

		unset($arrPages); //free some resources

		$report = '';
		$this->getToc($report, '', 0);

		if (!Report::store($report , $_REQUEST['iReport'], $_REQUEST['iOverwrite'])) {
			return FALSE;
		}

		//do the output
		echo $this->_listOrphans();
		echo Report::getInfoReportCommon();

	}

	/**
	 * Stores one new entry in the TOC data structure.
	 */
	function addToToc( $predecessor, $level, $file, $linkText ) {
		$level = ($level != 0)?(1):(0);
		if (!isset($this->_mToc[$predecessor])) {
			$this->_mToc[$predecessor] = array(0 => array(), 1 => array());
		}
		$this->_mToc[$predecessor][$level][] = $file;
		$this->_mLink[$file] = $linkText;
	}


	/**
	 * Builds up the TOC.
	 */
	function getToc( &$result, $predecessor, $currentLevel ) {
		for ($i=0; $i<2; $i++) { //first level 0 successors, then level 1
			if (isset($this->_mToc[$predecessor][$i])) {
				sort($this->_mToc[$predecessor][$i]); //sort many successors of one toc entry
				$currentLevel += (2*$i);      //adjust indentation, go deeper for level=1
				$isFirst = TRUE;
				$separator = '';
				foreach($this->_mToc[$predecessor][$i] as $item) {
					$this->_mLink[$item] = (!empty($this->_mLink[$item]))?("|{$this->_mLink[$item]}"):('');
					if ($currentLevel == 0) { //level 1 = heading
						$result .= "$separator,+[[$item{$this->_mLink[$item]}]]+\n";
						if ($isFirst) {
							$separator = "----\n";
							$isFirst = FALSE;
						}
					} elseif ($currentLevel == 2) { //level 2 with space above
						$result .= ", \n, ".str_pad('', $currentLevel, ' ', STR_PAD_LEFT)."&middot; [[$item{$this->_mLink[$item]}]]\n";
					} elseif ($currentLevel == 4) { //level 3 italic for better readability
						$result .= ', '.str_pad('', $currentLevel, ' ', STR_PAD_LEFT)."&middot; /[[$item{$this->_mLink[$item]}]]/\n";
					} else {
						$result .= ', '.str_pad('', $currentLevel, ' ', STR_PAD_LEFT)."&middot; [[$item{$this->_mLink[$item]}]]\n";
					}
					$this->getToc( $result, $item, $currentLevel );
				}
			}
		}
		unset($this->_mToc[$predecessor]); //just keep the orphans (if any)
	}

	/**
	 * Enumerates the oprphans, i.e. pages wit a matching TOC name but
	 * a non-existent predecessor or things like that.
	 */
	function &_listOrphans() {
		$res = '';
		foreach( $this->_mToc as $item ) {
			for ($i=0; $i<2; $i++) {
				if (sizeof($item[$i]) > 0) {
					$res .= implode("\n", $item[$i]);
					$res .= ', ';
				}
			}
		}
		if (!empty($res)) {
			$res = <<<eoh
<pre>
Warning: These pages are not included in the TOC:

$res

Possible reasons are misspelled or non-existing predecessor pages.
</pre>
eoh;
		}
		return $res;
	}

}

?>