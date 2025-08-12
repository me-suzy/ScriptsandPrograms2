<?php

/**
 * The class Lists is to be applied as static class (:: syntax).
 * It knows how to print an index list; used by navigation/*
 * and by static/*. Can also handle partial lists since 1.4.1.
 * Prints also lists with search results.
 * The history list is not contained; it
 * differs from the generic list treatment implemented here.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

define('LIST_PAGE', 1);
define('LIST_UPLOAD', 2);
define('LIST_TRASH_PAGE', 3);
define('LIST_TRASH_UPLOAD', 4);

class Lists {

	/**
	 * Returns a list in HTML format.
	 * Expects an array with all data and the information
	 * if the array contais Wiki pages or uploaded files.
	 * The last parameter $linkExtension is used by the partial
	 * list feature and may contain additional link parameter, like
	 * the max. age in a recent changes list.
	 * Must return a string instead of using "echo" because
	 * the HTML will be cached.
	 */
	function getIndex( &$arrFiles, $listAndPageType, $linkExtension = '' ) {
		global $gConfig, $gError;

		if (empty($gConfig->mIWiki['.'])) { //important for local links - uploaded files
			$gConfig->mIWiki['.'] = '';
		}

		switch ($listAndPageType) {
			case LIST_PAGE:
				$linkFunction = '_getLinksForWikiPages';
				break;
			 case LIST_UPLOAD:
			 	$linkFunction = '_getLinksForUploadedFiles';
			 	break;
			 case LIST_TRASH_PAGE:
			 	$linkFunction = '_getLinksForTrashPages';
			 	break;
			 case LIST_TRASH_UPLOAD:
			 	$linkFunction = '_getlinksForTrashUploads';
			 	break;
			 default:
			 	$gError->add('BUG: invalid parameter.');
			 	return;
		}

		if ($gConfig->mProp['IndexType'] > 1 ) {
			list($listPart, $navBar) = Lists::getPartOfList( $arrFiles, $linkExtension, $listAndPageType );
			return Lists::_getShortIndex( $listPart, $navBar, $linkFunction, $gConfig->mProp['IndexType'] );
		} else if ($gConfig->mProp['IndexType'] == 1) {
			list($listPart, $navBar) = Lists::getPartOfList( $arrFiles, $linkExtension, $listAndPageType );
			return Lists::_getLongIndex( $listPart, $navBar, $linkFunction );
		} else { //is smaller than 1
			$gError->add("Configuration error. Please adjust the configuration 'IndexType' to 1 or bigger.");
		}
	}

	/**
	 * Expects a sorted array of files. Each entry is another
	 * array containing the elements:
	 *  name (string)
	 *  size (int, in bytes)
	 *  time (int, seconds)
	 *  type (string, DATA_EXTENSION or '')
	 * Returns a list with edit, delete, view and history links.
	 * This is the long version with time infos.
	 */
	function _getLongIndex( &$arrFiles, $navBar, $linkFunction ) {
		global $gConfig;
		$res = '';
		$age = 0;
		$now = time();
		$counter   = sizeof( $arrFiles );
		$totalSize = 0;
		$arrLinks  = NULL;
		foreach ($arrFiles as $row) {
			$age  		 = (int)(($now - $row['time']) / 86400);
			$row['time'] = date( $gConfig->mProp['DateFormat'], $row['time']);
			$totalSize  += $row['size'];
			$row['size'] = (int)($row['size']/1024);
			$arrLinks =& Lists::$linkFunction( $row['name'] );
			$res .= <<<eoh
<tr>
	<td>{$arrLinks[0]}</td>
	<td align='right' nowrap>{$arrLinks[1]}</td>
	<td align='right'>{$row['size']} KB</td>
	<td>{$row['time']}</td>
	<td align='right'>$age days</td>
</tr>
eoh;
		}
		$totalSize = number_format( ($totalSize + 511) / 1024, 0, ',', ' ');
		return <<<eoh
$navBar
<small>Click to view. [e]=Edit, [d]=Diff, [h]=History, [b]=BackLinks, [r]=Rename, [R]=Re-Activate, [X]=Move to Trash</small>
<table border='0' cellspacing='2' cellpadding='2'>
<tr>
	<th>Page/File</th><th>Action</th><th>Size</th><th>Last Modified</th><th>Age</th>
</tr>
$res
</table><p>$counter file(s) listed. Total size: $totalSize KB</p>
$navBar
eoh;
	}

	/**
	 * Expects a sorted array of files. Each entry is another
	 * array containing the elements:
	 *  name (string)
	 *  size (int, in bytes)
	 *  type (string, DATA_EXTENSION or '')
	 * Returns a 2..N column list with edit, delete, view and history links.
	 * This is the version without time infos.
	 */
	function _getShortIndex( &$arrFiles, $navBar, $linkFunction, $columns ) {
		global $gConfig;

		$res = '';
		for ($i=0; $i<$columns; $i++) {
			$res .= '<th>Page/File</th><th>Action</th><th>KB</th>';
		}
		$res .= '</tr><tr>';

		$totalSize  = 0;
		$counter    = sizeof($arrFiles);
		$i          = 1;
		$arrayLinks = NULL;
		foreach ($arrFiles as $row) {
			$totalSize  += $row['size'];
			$row['size'] = (int)(($row['size'] + 511) / 1024);
			$arrayLinks = Lists::$linkFunction( $row['name'] );
			$res .= "<td>{$arrayLinks[0]}</td><td align='right' nowrap>{$arrayLinks[1]}</td><td align='right'>{$row['size']}</td>\n";
			$res .= ( ($i++ % $columns) == 0 )?('</tr><tr>'):('');
		}
		$totalSize = number_format( ($totalSize + 511) / 1024, 0, ',', ' ');
		return <<<eoh
$navBar
<small>Click to view. [e]=Edit, [q]=QuickEdit, [d] = Diff, [h]=History, [b]=BackLinks, [R]=Re-Activate, [X]=Move to Trash</small>
<table border='0' cellspacing='2' cellpadding='2'>
<tr>
$res
</table><p>$counter file(s) listed. Total size: <b>$totalSize</b> KB</p>
$navBar
eoh;
	}

	/**
	 * WikiPages: Helper for getIndex functions. Returns an array with a view link
	 * and action links in string format.
	 */
	function _getLinksForWikiPages( $name ) {
		$url        = "{$_SERVER['PHP_SELF']}?iPage={$name}&amp;iRequest=";
		$viewLink   = "<strong><a href='{$url}wiki/ViewPage' title='View page'>{$name}</a></strong>";
		$actionLink = <<<eoh
<a title='Edit' href='{$url}wiki/EditPage'>[e]</a>
<a title='Diff vs. previous version' href='{$url}history/ViewDiff'>[d]</a>
<a title='History' href='{$url}history/HistoryList'>[h]</a>
<a title='BackLinks' href='{$url}navigation/BackLinks'>[b]</a>
<a title='Rename' href='{$url}wiki/RenameForm'>[r]</a>
<a title='Move to trash bin' href='{$url}wiki/DeletePage' onclick='return confirm(\"Move to trash bin?\");'>[X]</a>
eoh;
		return array($viewLink, $actionLink);
	}

	/**
	 * UploadedFiles: Helper for getIndex functions. Returns an array with a view link
	 * and action links in string format.
	 */
	function _getLinksForUploadedFiles( $name ) {
		global $gConfig;
		$url        = "{$_SERVER['PHP_SELF']}?iPage={$name}&amp;iRequest=";
		$viewLink   = "<strong><a href='{$gConfig->mIWiki['.']}".UPLOAD_PATH."{$name}' title='Open file'><em>{$name}</em></a><strong>";
		$actionLink = <<<eoh
<a title='History'  href='{$url}history/HistoryList'>[h]</a>
<a title='BackLinks' href='{$url}navigation/FileLinks'>[b]</a>
<a title='Rename' href='{$url}upload/RenameForm'>[r]</a>
<a title='Move to trash bin' href='{$url}upload/DeleteUpload' onclick='return confirm(\"Move to trash bin?\");'>[X]</a>
eoh;
		return array($viewLink, $actionLink);
	}

	/**
	 * Uploaded files. Helper for index functions. returns an array with view link
	 * and action links for files in the trash bin.
	 */
	function _getlinksForTrashPages( $name ) {
		$viewLink   = "<a title='View page' href='{$_SERVER['PHP_SELF']}?iRequest=trash/ViewTrash&amp;iTrashPage=$name'>".str_replace( DATA_EXTENSION, '', $name)."</a>";
		$actionLink = <<<eoh
<a href='{$_SERVER['PHP_SELF']}?iRequest=trash/ReActivate&amp;iTrashPage=$name' title='Re-activate page' onclick='return confirm("Re-activate file?");'>[R]</a>
eoh;
		return array($viewLink, $actionLink);
	}

	/**
	 * Uploaded files. Helper for index functions. returns an array with view link
	 * and action links for files in the trash bin.
	 */
	function _getlinksForTrashUploads( $name ) {
		global $gConfig;
		$viewLink   = "<strong><a href='{$gConfig->mIWiki['.']}".TRASH_PATH."{$name}' title='Open file'><em>{$name}</em></a><strong>";
		$actionLink = <<<eoh
<a href='{$_SERVER['PHP_SELF']}?iRequest=trash/ReActivate&amp;iTrashPage=$name' title='Re-activate file' onclick='return confirm("Re-activate file?");'>[R]</a>
eoh;
		return array($viewLink, $actionLink);
	}

	/**
	 * Returns an array which contains two entries: The first is a
	 * part of the submitted dataArray. The second one is a navigation bar as string.
	 * Calculates a "page" based on the page size (taken from config)
	 * and the requested page number. A page is a section of the
	 * dataArray.
	 *
	 * -dataArry: all data; may be empty but must be an array
	 * -link: iParam=value; extend the link by further params
	 * -isWikiPage: page or file
	 *
	 * Externally used by the trash implementation.
	 */
	function getPartOfList( &$dataArray, $link, $listAndPageType ) {
		global $gConfig;

		if (!empty($link)) {
			$link = "&amp;$link";
		}
		if ($listAndPageType == LIST_PAGE or $listAndPageType == LIST_TRASH_PAGE) {
			$number = $_REQUEST['iPageListPart'];
		} else {
			$number = $_REQUEST['iFileListPart'];
		}

		$pageSize = ($gConfig->mProp['ListLength'] > 0)?($gConfig->mProp['ListLength']):(10);
		$dataSize = sizeof( $dataArray );

		//prepare the results
		$res = array();

		if ( $dataSize == 0) {
			return array($res, ''); //empty array, nothing to list
		}
		if ($dataSize <= $pageSize) {
			return array($dataArray, ''); //no pages to display
		}

		$navBar = '';
		//we might lack of a straight numerical index, let's create one
		$indexedArray = array_values( $dataArray );
		//avoid blank page if last items were deleted; show last page instead
		$number = (($number * $pageSize) > $dataSize)?(ceil( $dataSize / $pageSize )):($number);
		//index 0..N but number:= pages 1..N
		$start = ($number - 1) * $pageSize;
		//select either last data item or item at calculated data index
		$end   = ( ($number * $pageSize) > $dataSize )?($dataSize):($number * $pageSize);

		//fill in the result array
		for ($i=$start; $i<$end; $i++) {
			$res[] = $indexedArray[$i];
		}

		//generate the navbar
		$start = 1;
		$end   = ceil( $dataSize / $pageSize );
		for ($i=$start; $i<=$end; $i++) {
			if ($listAndPageType == LIST_PAGE or $listAndPageType == LIST_TRASH_PAGE) {
				$p = $i;
				$f = $_REQUEST['iFileListPart'];
			} else {
				$f = $i;
				$p = $_REQUEST['iPageListPart'];
			}
			$navBar .= ($i==$number)?("[$i] \n"):("<a href='{$_SERVER['PHP_SELF']}?iRequest={$_REQUEST['iRequest']}&amp;iPageListPart=$p&amp;iFileListPart=$f$link'>[$i]</a> \n");
		}
		$navBar = "<div align='right'><p>\nGoto part $navBar\n</p></div>";
		return array($res, $navBar);
	}

	/**
	 * Prints a list of search results out of a supplied array.
	 * Expects a sorted array of files. Each entry is another
	 * array containing the elements:
	 *  name (string)
	 *  size (int, in bytes)
	 *  time (int, seconds)
	 */
	function &getSearchList( &$arrFiles, $heading = '', $type = '' ) {
		global $gConfig;

		if (empty($gConfig->mIWiki['.'])) { //important for local links - uploaded files
			$gConfig->mIWiki['.'] = '';
		}

		$res = "<h3>$heading</h3>\n";

		if (sizeof($arrFiles) == 0) {
			$res .=  "<p>No matches found.</p>\n";
			return $res;
		}

		$res .= "<ul>\n";

		$counter   = sizeof($arrFiles);
		$path = UPLOAD_PATH;
		foreach ($arrFiles as $row) {
			$row['size'] = (int)(($row['size'] + 511) / 1024);
			$row['time'] = date( $gConfig->mProp['DateFormat'], $row['time']);
			if ( $type=='') { //we have an uploaded file
				$res .= <<<eoh
<li>
	<a href='{$gConfig->mIWiki['.']}{$path}{$row['name']}'><em>{$row['name']}</em></a>
	<br><small class='gray'>({$row['size']} KByte, last modified: {$row['time']})</small>
</li>
eoh;
			} else {
				$res .= <<<eoh
<li>
	<a href='{$_SERVER['PHP_SELF']}?iRequest=wiki/ViewPage&amp;iPage={$row['name']}'>{$row['name']}</a>
	<br><small class='gray'>({$row['size']} KByte, last modified: {$row['time']})</small>
</li>
eoh;
			}
		}
		$res .= "</ul>\n<p>$counter item(s) found.</p>\n";
		return $res;
	}
}

?>