<?php
/**
 * This response shows all recently changed wiki pages.
 * Changes of uploaded files do not matter here.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Data.inc' );
require_once( BASE_PATH.'core/Upload.inc' );
require_once( BASE_PATH.'core/Lists.inc' );

class ViewRecentChanges extends Response {

	/** Offer this recent changes time span selection. */
	var $_mRecentChangesLinks = array(1,3,7,15,30,60,90,130,180,365);

	function ViewRecentChanges( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function verify( &$args ) {
		global $gConfig;

		//optional recent changes array from config for compatibility up to 1.4.3
		if (!empty($gConfig->mProp['RecentChangesLinks'])) {
			$this->_mRecentChangesLinks = $gConfig->mProp['RecentChangesLinks'];
		}

		if (empty($_REQUEST['iAge']) or $_REQUEST['iAge'] < 1) {
			$_REQUEST['iAge'] = $this->_mRecentChangesLinks[0];
		}

		if (empty($_REQUEST['iFileListPart']) || $_REQUEST['iFileListPart'] < 1) {
			$_REQUEST['iFileListPart'] = 1;
		}
		if (empty($_REQUEST['iPageListPart']) || $_REQUEST['iPageListPart'] < 1) {
			$_REQUEST['iPageListPart'] = 1;
		}
	}

	function service( &$buffer, &$args ) {
		global $gConfig;

		$gConfig->mProp['ListLength'] = (int)($gConfig->mProp['ListLength']/2);

		//build the list, not cacheable because of edit!
		echo <<<eoh
<p>
View changes of the last&nbsp;&nbsp;
eoh;
		foreach ($this->_mRecentChangesLinks as $value) {
			echo <<<eoh
<a href='{$_SERVER['PHP_SELF']}?iRequest=navigation/ViewRecentChanges&amp;iAge=$value'>[$value]</a>&nbsp;
eoh;
		}
		echo "&nbsp;&nbsp;day(s).</p>";

		$arrPages =& Data::getRecentChangesList( $_REQUEST['iAge'] );
		$arrFiles =& Upload::getRecentChangesList( $_REQUEST['iAge'] );

		echo "<h3>Wiki pages</h3>\n";
		if (sizeof($arrPages) == 0) {
			echo '<p>No matching Wiki pages found.</p>';
		} else {
			echo Lists::getIndex( $arrPages, LIST_PAGE, "iAge={$_REQUEST['iAge']}" );
		}
		echo "<hr size='1' noshade>\n<h3>Uploaded files</h3>\n";
		if (sizeof($arrFiles) == 0) {
			echo '<p>No matching uploaded files found.</p>';
		} else {
			echo Lists::getIndex( $arrFiles, LIST_UPLOAD, "iAge={$_REQUEST['iAge']}" );
		}

		$args['Heading'] = "Recent changes during the last {$_REQUEST['iAge']} day(s)";
		$this->changeState('out/Html');
	}
}

?>