<?php
/**
 * This class displays the license and informs abou the authors.
 * The author's names are hard coded; who can edit who can change.
 * Requires the license file to be in the BASE_PATH directory.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

class License extends Response {

	function License( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function service( &$buffer, &$args ) {
		global $gError, $gConfig;

		$args['Heading'] = 'License';
		$this->changeState('out/Html');

		echo <<<eoh
<div id="textcolumn">
<h3>Authors and License</h3>
<pre>
eoh;
		if (file_exists(BASE_PATH.'LICENSE')) {
			$f = BASE_PATH.'LICENSE';
		} else if (file_exists(BASE_PATH.'license')) {
			$f = BASE_PATH.'license';
		} else if (file_exists(BASE_PATH.'license.txt')) {
			$f = BASE_PATH.'license.txt';
		} else if (file_exists(BASE_PATH.'License.txt')) {
			$f = BASE_PATH.'License.txt';
		} else if (file_exists(BASE_PATH.'LICENSE.TXT')) {
			$f = BASE_PATH.'LICENSE.TXT';
		} else {
			echo <<<eoh
<p>License file <em>not</em> found. Please visit
<a href='http://www.opensource.org'>Opensource.org</a> or
<a href='http://www.gnu.org'>GNU.org</a>
for details about the General Public License (GPL).
</p>
eoh;
		return;
		}

		$fp = fopen( $f, "r" );
		fpassthru( $fp );
		echo '</pre></div>';
		echo "<a href='{$_SERVER['PHP_SELF']}?iRequest=extra/Matrix'>_</a>";

	}
}

?>