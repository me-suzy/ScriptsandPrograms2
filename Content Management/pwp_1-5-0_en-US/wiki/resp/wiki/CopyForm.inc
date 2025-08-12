<?php
/**
 * This response shows a simple form where the user may
 * enter a new name for a copy target.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

class CopyForm extends Response {

	function CopyForm( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function verify( &$args ) {
		global $gError;

		if (empty($_REQUEST['iPage'])) {
			$gError->add("Missing parameter.");
		}
	}

	function service( &$buffer, &$args ) {

		$args['Heading'] = "Copy page: {$_REQUEST['iPage']}";
		$this->changeState('out/Html');

			echo <<<eoh
$buffer
<form name='copyform' action='{$_SERVER['PHP_SELF']}' method='post'>
	<input type='hidden' name='iRequest' value='wiki/CopyResult'>
	<input type='hidden' name='iPage' value='{$_REQUEST['iPage']}'>
	<p>Create a copy  of '<strong>{$_REQUEST['iPage']}</strong>'.</p>
	<br>
	New name:
	<input type='text' name='iNewPage' size='30'>
	<input type='submit' value=' OK ' title='Create a copy of {$_SERVER['PHP_SELF']}'>
	<p><small>
	<em>Note:</em>
	The action will NOT copy older revisions contained in the history of the Wiki page.
	</small></p>
</form>
eoh;
	}

}

?>