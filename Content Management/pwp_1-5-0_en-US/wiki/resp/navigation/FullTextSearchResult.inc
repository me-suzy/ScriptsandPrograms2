<?php
/**
 * This response shows a search result list of the
 * full text search.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Lists.inc' );
require_once( BASE_PATH.'core/Data.inc' );
require_once( BASE_PATH.'core/Upload.inc' );
require_once( BASE_PATH.'core/Cache.inc' );

class FullTextSearchResult extends Response {

	function FullTextSearchResult( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function verify( &$args ) {
		global $gError;

		if (!isset($_REQUEST['iSearchText']) or $_REQUEST['iSearchText'] == '') {
			$gError->add("Missing search string, cannot search for nothing.");
		}

		//if (empty($_REQUEST['iSearchLazy']) or $_REQUEST['iSearchLazy'] != 1) {
			$_REQUEST['iSearchLazy'] = 0;
		//}

		if (empty($_REQUEST['iSearchWiki']) or $_REQUEST['iSearchWiki'] != 1) {
			$_REQUEST['iSearchWiki'] = 0;
		}

		if (empty($_REQUEST['iSearchUpload']) or $_REQUEST['iSearchUpload'] != 1) {
			$_REQUEST['iSearchUpload'] = 0;
		}

		if ( $_REQUEST['iSearchUpload'] + $_REQUEST['iSearchWiki'] == 0 ) {
			$gError->add("Please check at least ONE of the search areas: Wiki pages or uploaded files.");
		}

		//use cache or force refresh
		if (empty($_REQUEST['iRefresh']) or $_REQUEST['iRefresh'] != 1) {
			$_REQUEST['iRefresh'] = 0;
		}
	}

	/**
	 * About the caching: Stores only full (not lazy) searches. Stores both, Wiki pages and
	 * uploaded files hits within one result. Thus, searching for Wiki pages and afterwards for both
	 * pages and files leads to different cached lists.
	 */
	function service( &$buffer, &$args ) {
		global $gConfig;

		if (empty($args['Heading'])) { //might already be set by backlinks
			$args['Heading'] = "Full text search result: \"{$_REQUEST['iSearchText']}\"";
		}
		$this->changeState('out/Html');

		$cacheName = SEARCH_TEXT.md5(strtolower($_REQUEST['iSearchText'])).'_'.$_REQUEST['iSearchWiki'].$_REQUEST['iSearchUpload'];

		if ($_REQUEST['iRefresh'] == 0) {
			$list = Cache::retrieve($cacheName);
			if ($list !== FALSE) {
				$age = Cache::getAge($cacheName);
				$url = (empty($_REQUEST['iRedoUrl']))?("navigation/FullTextSearchResult&amp;iSearchText={$_REQUEST['iSearchText']}&amp;iSearchWiki={$_REQUEST['iSearchWiki']}&amp;iSearchUpload={$_REQUEST['iSearchUpload']}&amp;iRefresh=1"):("{$_REQUEST['iRedoUrl']}&amp;iRefresh=1");
				echo <<<eoh
<p><small>
This search result was taken from the cache.
It is $age old. You might want to
<a href='{$_SERVER['PHP_SELF']}?iRequest=$url' title='Search again without using the cached result.'>search again</a>
without using the cached result.
</small></p>
$list
eoh;
				return TRUE;
			}
		} else {
			Cache::clear($cacheName);
		}


		$arrFiles = array();
		$arrPages = array();

		//text search in uploaded files
		if ($_REQUEST['iSearchUpload']) {
			$arrFiles =& Upload::getTextSearchList( $_REQUEST['iSearchText'], $_REQUEST['iSearchLazy']  );
		}

		// text search in wiki pages
		if ($_REQUEST['iSearchWiki']) {
			$arrPages =& Data::getTextSearchList( $_REQUEST['iSearchText'], $_REQUEST['iSearchLazy'] );
		}

		//forward if exactly one hit
		if (sizeof($arrPages) == 1 and sizeof($arrFiles) == 0 ) {
			$_REQUEST['iPage'] = key($arrPages);
			$this->changeState('wiki/ViewPage');
		} else {
			$list = '';
			if ($_REQUEST['iSearchWiki']) {
				$list .= Lists::getSearchList( $arrPages, 'Wiki pages', DATA_EXTENSION );
			}
			if ($_REQUEST['iSearchUpload']) {
				$list .= Lists::getSearchList( $arrFiles, 'Uploaded files', '' );
			}
			echo $list;
			//store in cache if there was one hit; but do not store lazy searches
			if (!$_REQUEST['iSearchLazy'] and (sizeof($arrFiles) > 0 or sizeof($arrPages) > 0)) {
				Cache::store( $cacheName, $list );
			}
		}
	}
}

?>