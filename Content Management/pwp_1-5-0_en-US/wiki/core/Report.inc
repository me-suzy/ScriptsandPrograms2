<?php

/**
 * A report base class. Reports are Wiki pages which contain a list/enumeration
 * of other Wiki pages or uploaded files that fulfill a certain condition.
 * Condition might be the existence of a text string or a &#37;&#37; formatted report command.
 * Since PWP 1.5.0
 *
 * @status
 * @author Lars Ackermann
 * $Id: $
 */
require_once( BASE_PATH.'core/Lock.inc' );

class Report {

	/**
	 * Returns a menu with actions for all kinds of reports.
	 * Can be used as static method.
	 */
	function getMenu() {
		return <<<eoh
<ul>
<li> Create a report based on page/file names or full text search:
[<a href='{$_SERVER['PHP_SELF']}?iRequest=navigation/ReportSearchForm'>Simple report</a>]
</li>
<li> Create a table of contents:
[<a href='{$_SERVER['PHP_SELF']}?iRequest=navigation/ReportTOCForm'>TOC report</a>]
</li>
<li> Create a keyword index:
[<a href='{$_SERVER['PHP_SELF']}?iRequest=navigation/ReportIndexForm'>Keyword index</a>]
</li>
<li> Create a trailmap / teaser text report:
[<a href='{$_SERVER['PHP_SELF']}?iRequest=navigation/ReportTrailmapForm'>Trail map</a>]
</li>
</ul>
eoh;
	}

	/**
	 * Retuns the form part that provides an input field for the
	 * Wiki page name containing the report.
	 * Can be used as static method.
	 */
	function getFormPartWikiPage() {
		return <<<eoh
<tr>
	<td class='layout' valign='top'>Report: </td>
	<td class='layout'>
		<input type='text' name='iReport' size='20'>
		<br>
		<small>Enter the name of the Wiki page that should contain the report.</small>
		<br>
		<input type='radio' name='iOverwrite' value='0' checked> Cancel if Wiki page already exists.
		<br>
		<input type='radio' name='iOverwrite' value='1' > Overwrite existing Wiki page, save old revison.
		<br>
		<input type='radio' name='iOverwrite' value='2' > Append to existing Wiki page, save old revison.
	</td>
</tr>
eoh;
	}

	/**
	 * Verifies that a new report Wiki page is not locked. Call before prepare().
	 * Returns true on success.
	 * Can be used as static method.
	 */
	function checkLock( &$args, $report, $urlArgs ) {
		global $gConfig;

		if ($gConfig->mProp['LockForEdit'] ) { //can cope with not existing pages/locks
			$tstamp = 0;
			$user   = '';
			if (Lock::isLocked($report, $tstamp, $user)) {

				$args['TStamp'] = $tstamp;
				$args['User']   = $user;
				$args['Goto']   = $_REQUEST['iRequest'];
				$args['URLArgs'] = "iReport=$report&amp;$urlArgs";
				$this->changeState('wiki/IsLocked');
				$_REQUEST['iPage'] = $report; //required for heading; will cause submenu for $report to show up as well
				return FALSE;
			} else {
				Lock::create($report);
			}
		}
		return TRUE;
	}

	/**
	 * Prepares the report Wiki page or indicates an error.
	 * Returns true on success.
	 * Can be used as static method.
	 */
	function prepare( $report, $overwrite ) {
		global $gError;

		if (Trash::fileExists($report) ) {
			$gError->add("The Wiki page '$report' is currently contained in the trash bin. Please reactivate the page first.");
			return FALSE;
		} elseif (Data::fileExists($report) ) {
			if ($overwrite == 0) { //do not overwrite
				$gError->add("The Wiki page '$report' does already exist.");
				Lock::release($report);
				return FALSE;
			} else { //history and overwrite
				History::store($report);
			}
		} else {
			Data::make($report);
			Cache::clearNumerical( INDEX_PAGE_NAME );
		}
		return TRUE;
	}

	/**
	 * In case of an empty search result, this function will
	 * undo the creation of an empty Wiki page.
	 * Returns true on success.
	 * Can be used as static method.
	 */
	function validate( $resultSize, $report, $overwrite ) {
		global $gError;
		if ($resultSize == 0) {
			if ($overwrite == 0) { //create new page; now erase empty file
				Data::erasePage($report);
			}
			$gError->add("Did not find any matching pages/files for the report '$report'. Aborting the report creation.");
			Lock::release($report);
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/**
	 * Finally saves the report as Wiki page.
	 * Returns true on success.
	 * Can be used as static method.
	 */
	function store( $text,  $report, $overwrite ) {
		global $gError, $gConfig;

		$result = TRUE;
		if ($overwrite == 2) {
			$text = Data::retrieve($report)."\n$text";
		}
		if (!Data::store($report, $text)) {
			$gError->add("Failed to save the report.");
			$result = FALSE;
		}
		if ($gConfig->mProp['LockForEdit'] ) {
			Lock::release($report);
		}
		return $result;
	}

	/**
	 * Returns the Wiki markup for the report.
	 * This is for file name search and full text search reports.
	 */
	function &getListReportSearch($arrPages, $sizePages, $arrFiles, $sizeFiles) {
		global $gConfig;

		$report = '';

		$length = ceil($sizePages/$gConfig->mProp['IndexType']);
		$length = ($length < 10)?(10):($length);
		$split = ($gConfig->mProp['IndexType'] > 1)?($length):(-1);
		if ($sizePages > 0) {
			$counter = 0;
			$report .= "= Wiki Pages\n{{||--}}\n";
			foreach ($arrPages as $item) {
				$report .= '*[['.$item['name']."]]\n";
				if (++$counter == $split) {
					$report .= "{{||}}\n";
					$split += $length;
				}
			}
			$report .= "{{--||}}\n";
		}

		$length = ceil($sizeFiles/$gConfig->mProp['IndexType']);
		$length = ($length < 10)?(10):($length);
		$split = ($gConfig->mProp['IndexType'] > 1)?($length):(-1);
		if ($sizeFiles > 0) {
			$counter = 0;
			$report .= "= Uploaded Files\n{{||--}}\n";
			foreach ($arrFiles as $item) {
				if (empty($gConfig->mIWiki['.'])) {
					$report .= '*[-'.UPLOAD_PATH.$item['name'].'|'.$item['name']."]\n";
				} else {
					$report .= "*[-{. {$item['name']}}]\n";
				}
				if (++$counter == $split) {
					$report .= "{{||}}\n";
					$split += $length;
				}
			}
			$report .= "{{--||}}\n";
		}
		return $report;
	}

	/**
	 * Returns the final message after a report was created.
	 * This is for file name search and full text search reports.
	 */
	function getInfoReportSearch($pageCount, $uploadCount, $isFullText = true) {
		if ($pageCount == 0) {
			$pageCount = 'no';
		}
		if ($uploadCount == 0) {
			$uploadCount = 'no';
		}

		$type   = ($isFullText)?('Full text'):('File name');
		$search = (empty($_REQUEST['iSearch']))?('for all files/pages'):("'{$_REQUEST['iSearch']}'");

		return <<<eoh
<p>
The report <strong>'{$_REQUEST['iReport']}'</strong> has been created.
It contains <strong>$pageCount Wiki pages</strong> and <strong>$uploadCount uploaded files</strong>.
</p>

<p>
View the <a href='{$_SERVER['PHP_SELF']}?iRequest=wiki/ViewPage&amp;iPage={$_REQUEST['iReport']}'>report</a>.
</p>

<p>
Make a bookmark in order to overwrite/update this report:
<a href='{$_SERVER['PHP_SELF']}?iRequest={$_REQUEST['iRequest']}&amp;iSearch={$_REQUEST['iSearch']}&amp;iOverwrite=1&amp;iSearchWiki={$_REQUEST['iSearchWiki']}&amp;iSearchUpload={$_REQUEST['iSearchUpload']}&amp;iReport={$_REQUEST['iReport']}'>
Report: $type search $search
</a>
</p>
eoh;
	}

	/**
	 * Returns info about report creation.
	 * This is for all reports but search.
	 */
	function getInfoReportCommon( $urlParams = '') {
		echo <<<eoh

<p>
The report <strong>'{$_REQUEST['iReport']}'</strong> has been created.
</p>
<p>
View the <a href='{$_SERVER['PHP_SELF']}?iRequest=wiki/ViewPage&amp;iPage={$_REQUEST['iReport']}'>report</a>.
</p>
<p>
Make a bookmark in order to overwrite/update this report:
<a href='{$_SERVER['PHP_SELF']}?iRequest={$_REQUEST['iRequest']}&amp;iName={$_REQUEST['iName']}&amp;iOverwrite=1&amp;iReport={$_REQUEST['iReport']}$urlParams'>
Report: {$_REQUEST['iName']} / {$_REQUEST['iReport']}
</a>
</p>
eoh;
	}

}
?>