<?php
/**
 * This response generates static pages. Will break during creation of
 * static Wiki pages if there is not enough time left.
 * Refactored in 1.5.0
 * ToDo: There is no timeout check...
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Statics.inc' );
require_once( BASE_PATH.'core/Upload.inc' );

class ExportFiles extends Response {

	function ExportFiles( &$buffer, &$args ) {
		$this->Response( $buffer, $args, TRUE );
	}

	function verify( &$args ) {
		global $gError, $gConfig;

		if (!$gConfig->mProp['EnableStaticExport']) {
			$gError->add("The current configuration doesn't allow to export static pages.");
		}
	}

	function service( &$buffer, &$args ) {
		global $gError, $gConfig;

		$args['Heading'] = "Export Uploaded Files";
		$this->changeState('out/Html');

		//process pages
		$uploaded   =& Statics::storeAllUploaded();
		$report    = implode( ', ', $uploaded );
		$exportedCount = sizeof($uploaded);

		$menu = Statics::getMenu();

		//do the output
		echo <<<eoh
$buffer
$menu
<h3>Exported Files</h3>
<p><small>All exported files, but without the skipped files:</small></p>
<p>
$report
</p>
<pre>
Exported files: $exportedCount
</pre>
<br>
eoh;
	}
}

?>