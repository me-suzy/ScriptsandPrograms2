<?php
/**
 * This response shows a list of all files in the history
 * (i.e. older revisions) belonging to a give file name.
 * Works with both, Wiki pages and uploaded files.
 * This list is too special to be covered by the class core/Lists.
 * Requires a parameter containing a time stamp with the revision date.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Data.inc' );
require_once( BASE_PATH.'core/Upload.inc' );
require_once( BASE_PATH.'core/History.inc' );

class HistoryList extends Response {

	function HistoryList( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function verify( &$args ) {
		global $gError, $gConfig;

		if (!isset($_REQUEST['iPage']) or  !Data::isValidFileName($_REQUEST['iPage'])) {
			$gError->add("Missing or invalid file name!");
		}

		if (empty($gConfig->mIWiki['.'])) {
			$gConfig->mIWiki['.'] = '';
		}
	}

	function service( &$buffer, &$args ) {
		global $gConfig, $gError;

		$info 	 = '';
		$size    = 0;
		$href    = '';
		$isData = 1;

		//work with wiki page
		if (Data::fileExists($_REQUEST['iPage'])) {
			$size = Data::getFileSize( $_REQUEST['iPage'] );
			$href	 = <<<eoh
<a title='View page' href='{$_SERVER['PHP_SELF']}?iRequest=wiki/ViewPage&amp;iPage={$_REQUEST['iPage']}'>[v]</a>
<a title='Diff vs. previous version' href='{$_SERVER['PHP_SELF']}?iRequest=history/ViewDiff&amp;iPage={$_REQUEST['iPage']}'>[d]</a>
eoh;
			$isData  = 1;
		//work with uploaded file
		} else if (Upload::fileExists($_REQUEST['iPage'])) {
			$size = Upload::getFileSize( $_REQUEST['iPage'] );
			$href	 = "<a title='Get old version of an uploaded file' href='{$gConfig->mIWiki['.']}".UPLOAD_PATH."{$_REQUEST['iPage']}'>[g]</a>";
			$isData  = 0;
		} else {
			$gError->add("This file does not exist any longer: {$_REQUEST['iPage']} (Maybe deleted?)");
			return false;
		}

		//get an indexed array 0..n
		$arrFiles =& History::getList( $_REQUEST['iPage'] );

		if (sizeof($arrFiles) == 0) {
			$gError->add("The page '{$_REQUEST['iPage']}'  does not have any entries in the history, yet.");
			return FALSE;
		}

		$arrFiles = array_values($arrFiles);

		$growth = $size - $arrFiles[0]['size'];
		$growth  = ($growth < 0)?($growth):("+$growth");
		$size = (int)(($size + 511) / 1024);

		echo <<<eoh
<small>
<em>Note:</em> If you re-activate an older version, the current version
will automatically be stored in the revision history.
You cannot edit older page versions. A click on 'Edit'
will always load the currently active version.
<br>
<br>
[v]=View page [g]=Get uploaded file [d]=Diff [R]=Re-Activate
</small>
<table border='0' cellspacing='2' cellpadding='2'>
<tr>
	<th>Revision Created</th><th>Action</th><th>Age Today</th>
	<th>Size</th><th>Compared to Previous Version</th>
</tr>
<tr>
	<td><strong>currently active version</strong></td>
	<td>
		<small>
			$href
		</small>
	</td>
	<td align='center'>-</td>
	<td align='right'>$size KB</td>
	<td align='right'>$growth bytes</td>
</tr>
eoh;

		//add a "virtual" 0 bytes file for comparison operations of the first entry in the history
		array_push($arrFiles, array('time' => 0, 'size' => 0, 'name' => '' ));

		$now 	= time();
		$age 	= 0;
		$counter = sizeof( $arrFiles ) - 1;

		for ($i = 0;  $i < $counter; $i++) { //stop before "virtual" file is reached!
			$age  	 = (int)(($now - $arrFiles[$i]['time']) / 86400);
			$time    = date( $gConfig->mProp['DateFormat'], $arrFiles[$i]['time']);
			$growth  = $arrFiles[$i]['size'] - $arrFiles[$i+1]['size'];
			$growth  = ($growth < 0)?($growth):("+$growth");
			$size    = (int)(($arrFiles[$i]['size'] + 511) / 1024); //make readable
			if ( $isData ) {
				$href = <<<eoh
<a title='View old page version' href='{$_SERVER['PHP_SELF']}?iRequest=history/ViewHistory&amp;iPage={$_REQUEST['iPage']}&amp;iRevision={$arrFiles[$i]['name']}&amp;iIsData=1'>[v]</a>
<a title='Diff vs. previous version'href='{$_SERVER['PHP_SELF']}?iRequest=history/ViewDiff&amp;iPage={$_REQUEST['iPage']}&amp;iDiffNew={$arrFiles[$i]['name']}&amp;iDiffOld={$arrFiles[$i+1]['name']}'>[d]</a>
eoh;
			} else {
				$href = "<a title='Get old version of an uploaded file' href='{$gConfig->mIWiki['.']}".HISTORY_PATH."{$arrFiles[$i]['name']}'>[g]</a>";
			}
			echo <<<eoh
<tr>
	<td><strong>$time</strong></td>
	<td>
		<small>
			$href
			<a title='Re-activate this version' href='{$_SERVER['PHP_SELF']}?iRequest=history/ReActivate&amp;iPage={$_REQUEST['iPage']}&amp;iRevision={$arrFiles[$i]['name']}&amp;iIsData=$isData' onclick='return confirm("Re-activate?");'>[R]</a>
		</small>
	</td>
	<td align='right'>$age days</td>
	<td align='right'>$size KB</td>
	<td align='right'>$growth Bytes</td>
</tr>
eoh;
		}

		echo "</table><p>$counter file(s) listed.</p>\n";
		$args['Heading'] = "History: {$_REQUEST['iPage']}";
		if ( $isData == 0 ) { //prevent edit link, etc.
			$_REQUEST['iPage'] = NULL;
		}

		$this->changeState('out/Html');
	}

}

?>
