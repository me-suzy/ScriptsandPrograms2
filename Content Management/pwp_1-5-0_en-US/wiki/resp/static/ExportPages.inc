<?php
/**
 * This response generates static pages. Will break during creation of
 * static Wiki pages if there is not enough time left.
 * Refactored in 1.5.0
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/WikiEngine.inc' );
require_once( BASE_PATH.'core/StaticEngine.inc' );
require_once( BASE_PATH.'core/Statics.inc' );
require_once( BASE_PATH.'core/Data.inc' );
require_once( BASE_PATH.'conf/StaticHtml.inc' );

class ExportPages extends Response {

	function ExportPages( &$buffer, &$args ) {
		$this->Response( $buffer, $args, TRUE );
	}

	function verify( &$args ) {
		global $gError, $gConfig;

		if (!$gConfig->mProp['EnableStaticExport']) {
			$gError->add("The current configuration doesn't allow to export static pages.");
		}
	}

	function service( &$buffer, &$args ) {
		global $gError, $gConfig, $gEngine;

		$args['Heading'] = "Export Static Pages";
		$this->changeState('out/Html');

		//avoid info line, would require param iPage
		$gConfig->mProp['ShowInfoLine'] = FALSE;

		//process pages
		list($report, $exportedCount, $skippedCount) = $this->_processWikiPages();
		$report .= $this->_processDirIndex();

		$menu = Statics::getMenu();

		//do the output
		echo <<<eoh
$buffer
$menu
<h3>Exported Wiki Pages</h3>
<p><small>All exported pages, but without the skipped pages:</small></p>
<p>
$report
</p>
<pre>
Exported pages: $exportedCount
Skipped pages:  $skippedCount
</pre>
<br>
eoh;
	}


	/** Helper, returns report, numer of exported static pages / skipped pages. */
	function _processWikiPages() {
		global $gEngine;

		$res = '';
		$exp =  0;
		$skip = 0;
		$arrFiles =& Data::getIndexList();
		foreach ($arrFiles as $file) {

			if (!$this->canContinue()) {
				$res.= " Aborting due to script timeout. Please recall this function.\n";
				break;
			}

			if (!Statics::fileExists( $file['name'] )) {

				$text =& Data::retrieve( $file['name'] );
				if ($text === FALSE) {
					$res .= "{$file['name']}(ERROR), ";
					break;
				}

				//transform the Wiki mark-up
				$gEngine->transformHtml( $text );
				$text = "<div id='textcolumn'>\n$text\n</div>";

				//write the file
				$text = StaticHtml::getHeader( $file['name'] ).$text.StaticHtml::getFooter( $file['name'] );
				if (Statics::store( $file['name'], $text )) {
					$res .= "{$file['name']}(ok), ";
					$exp++;
				} else {
					$res .= "{$file['name']}(ERROR), ";
				}
			} else {
				++$skip;
			}
		}
		return array($res, $exp, $skip);
	}

	/** Helper, creates "other files" (lists). */
	function &_processDirIndex() {
		global $gConfig;

		$res = '';
		//write an index.html if not already there
		if (!Statics::fileExists( DIRECTORY_INDEX )) {
			$text =& StaticHtml::getDirIndex();
			if (Statics::store( DIRECTORY_INDEX, $text )) {
				$res .= "directory index '".DIRECTORY_INDEX."'(ok).";
			} else {
				$res .= "directory index '".DIRECTORY_INDEX."'(ERROR).";
			}
		} else {
			$res .= " Skipping: Directory index exists.\n";
		}
		return $res;
	}
}

?>