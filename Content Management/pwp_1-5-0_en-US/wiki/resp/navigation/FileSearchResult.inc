<?php
/**
 * This response executes a search in Wiki page names and/or uploaded file's names.
 * Presents the results in a list or forwards directly if only one file is found.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Lists.inc' );
require_once( BASE_PATH.'core/Data.inc' );
require_once( BASE_PATH.'core/Upload.inc' );
require_once( BASE_PATH.'core/Cache.inc' );

class FileSearchResult extends Response {

	function FileSearchResult( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function verify( &$args ) {
		global $gError;

		if (empty($_REQUEST['iSearch']) or !Data::isValidFileName($_REQUEST['iSearch'])) {
			$gError->add("Missing or invalid search string, cannot search this! (Do NOT use wildcards or special chars.)");
		}

		//use cache or force refresh
		if (empty($_REQUEST['iRefresh']) or $_REQUEST['iRefresh'] != 1) {
			$_REQUEST['iRefresh'] = 0;
		}
	}

	function service( &$buffer, &$args ) {
		global $gConfig;

		$args['Heading'] = "File search result: \"{$_REQUEST['iSearch']}\"";
		$this->changeState('out/Html');

		$cacheName = SEARCH_PAGENAME.md5(strtolower($_REQUEST['iSearch']));

		//full name found -> forward immediately
		if (Data::fileExists($_REQUEST['iSearch'])) {
			$_REQUEST['iPage'] = $_REQUEST['iSearch'];
			$this->changeState('wiki/ViewPage');
			return TRUE;
		}

		if ($_REQUEST['iRefresh'] == 0) { //cach allowed, try to make a hit
			$list = Cache::retrieve($cacheName);
			if ($list !== FALSE) { //type equal!
				$age = Cache::getAge($cacheName);
				echo <<<eoh
<p><small>
This search result was taken from the cache.
It is $age old. You might want to
<a href='{$_SERVER['PHP_SELF']}?iRequest=navigation/FileSearchResult&amp;iSearch={$_REQUEST['iSearch']}&amp;iRefresh=1' title='Search again without using the cached result.'>search again</a>
without using the cached result.
</small></p>
$list
eoh;
				return TRUE;
			}
		} else {
			Cache::clear($cacheName);
		}

		$arrPages =& Data::getFileSearchList( $_REQUEST['iSearch'] );
		$arrFiles =& Upload::getFileSearchList( $_REQUEST['iSearch'] );

		if (sizeof($arrFiles) == 0 and sizeof($arrPages) == 1 ) {
			//forward if exactly one hit
			$_REQUEST['iPage'] = key($arrPages);
			$this->changeState('wiki/ViewPage');
		} else {
			$list =& Lists::getSearchList( $arrPages, 'Wiki pages', DATA_EXTENSION );
			$list .= Lists::getSearchList( $arrFiles, 'Uploaded files', '' ); //''=upload
			echo $list;
			if (sizeof($arrFiles) > 0 or sizeof($arrPages) > 0 ) {
				Cache::store( $cacheName, $list );
			}
		}
	}
}

?>