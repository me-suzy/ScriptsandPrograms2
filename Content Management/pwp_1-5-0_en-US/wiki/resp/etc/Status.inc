<?php
/**
 * This response shows the config settings and some other status infos.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Data.inc' );
require_once( BASE_PATH.'core/Trash.inc' );
require_once( BASE_PATH.'core/Upload.inc' );

class Status extends Response {

	function Status( &$buffer, &$args ) {
		$this->Response( $buffer, $args, TRUE );
	}

	function service( &$buffer, &$args ) {
		global $gError, $gConfig;

		$dataFiles   = sizeof( Data::getIndexList() );
		$trashFiles  = sizeof( Trash::getList() );
		$uploadFiles = sizeof( Upload::getIndexList() );

		echo <<<eoh
<h3>File count</h3>
<pre>
Data files:     $dataFiles
Uploaded files: $uploadFiles
Files in trash: $trashFiles
</pre>

<h3>InterWiki link targets</h3>
<a href='{$_SERVER['PHP_SELF']}?iRequest=extra/EditConfig&amp;iSection=interwikilinks'>[Edit]</a>
<table border='0' cellspacing='2' cellpadding='2' width='100%'>
<tr>
	<th width='25%'>Name</th><th>Target</th>
</tr>
eoh;

	foreach ($gConfig->mIWiki as $key => $value) {

		echo <<<eoh
		<tr>
			<td>$key</td><td>$value</td>
		</tr>
eoh;

	}

echo <<<eoh
</table>
<h3>Variables</h3>
<a href='{$_SERVER['PHP_SELF']}?iRequest=extra/EditConfig&amp;iSection=variables'>[Edit]</a>
<table border='0' cellspacing='2' cellpadding='2' width='100%'>
<tr>
	<th width='25%'>Name</th><th>Value</th>
</tr>
eoh;

	foreach ($gConfig->mVars as $key => $value) {

		$value = wordwrap(htmlentities($value), 60, "\n", 1);
		echo <<<eoh
		<tr>
			<td>$key</td><td>$value</td>
		</tr>
eoh;

	}

	echo <<<eoh
</table>
<h3>Settings</h3>
<table border='0' cellspacing='2' cellpadding='2' width='100%'>
<tr>
	<th width='25%'>Setting</th><th>Value</th><th>Type</th>
</tr>
eoh;

	foreach ($gConfig->mProp as $key => $value) {

		$type = gettype($value);
		if ($type == 'array') {
			$value = implode(', ', $value);
		} else if ($type == 'boolean') {
			$value = ($value)?('TRUE'):('FALSE');
		} else {
			$value = wordwrap(htmlentities($value), 60, "\n", 1);
		}
		echo <<<eoh
		<tr>
			<td>$key</td><td>$value</td><td>$type</td>
		</tr>
eoh;

	}

	echo <<<eoh
</table>
<h3>Environment</h3>
<table border='0' cellspacing='2' cellpadding='2' width='100%'>
<tr>
	<th width='25%'>Variable</th><th>Value</th>
</tr>
eoh;

		foreach ($_SERVER as $key => $value) {
			if (strpos(",$key", 'HTTP') or strpos(",$key", 'SERVER') or strpos(",$key", 'REMOTE') ) {
				$value = wordwrap(htmlentities($value), 60, "\n", 1);
				echo <<<eoh
<tr>
	<td>$key</td><td>$value</td>
</tr>
eoh;
			}
		}
		foreach ($_ENV as $key => $value) {
			if (strpos(",$key", 'PROCESSOR') or $key == 'OS' ) {
				$value = wordwrap(htmlentities($value), 60, "\n", 1);
				echo <<<eoh
<tr>
	<td>$key</td><td>$value</td>
</tr>
eoh;
			}
		}

	echo "</table>\n";
		$args['Heading'] = 'Status';
		$this->changeState('out/Html');
	}
}

?>