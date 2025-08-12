<?php
/**
 * This response shows a simple form where the user may
 * enter a new name for a rename target.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

class RenameForm extends Response {

	function RenameForm( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function verify( &$args ) {
		global $gError;

		if (empty($_REQUEST['iPage'])) {
			$gError->add("Missing parameter.");
		}
	}

	function service( &$buffer, &$args ) {

		$args['Heading'] = "Rename Page: {$_REQUEST['iPage']}";
		$this->changeState('out/Html');

			echo <<<eoh
$buffer
<form name='renameform' action='{$_SERVER['PHP_SELF']}' method='post'>
	<input type='hidden' name='iRequest' value='wiki/RenameResult'>
	<input type='hidden' name='iPage' value='{$_REQUEST['iPage']}'>
	<p>Rename the page '<strong>{$_REQUEST['iPage']}</strong>'.</p>
	<br>
	New name:
	<input type='text' name='iNewPage' size='30'>
	<input type='submit' value=' OK ' title='Rename the page {$_SERVER['PHP_SELF']}'>
	<br>
	<p><small>
	<em>Note:</em>
	All <strong>links</strong> pointing to the page '{$_REQUEST['iPage']}' (back links) will remain <strong>unchanged</strong>.
	<br>
	This action will take care of any older revisions contained in the history.
	</small></p>
</form>
eoh;
	}

}

?>