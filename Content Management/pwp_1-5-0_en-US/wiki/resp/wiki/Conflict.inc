<?php
/**
 * This response handles an edit conflict detcted by SavePage.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Data.inc' );
require_once( BASE_PATH.'core/Diff.inc' );

class Conflict extends Response {

	function Conflict( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function verify( &$args ) {
		global $gError;

		/*
		Conflict is called by SavePage, never directly. We can be sure that all params
		are defined and are of the correct type.
		*/
		if (!(isset($args['isConflict']) or $args['isConflict'])) {
			$gError->add('It is not allowed to call Conflict directly.');
		}

		if (!isset($_REQUEST['iText']) ) {
			$gError->add('Missing parameter!');
		} else {
			$_REQUEST['iText'] = stripslashes( $_REQUEST['iText'] );
		}
	}

	function service( &$buffer, &$args ) {
		global $gConfig, $gError, $gEngine;

		/*
		OK, this case is slightly different form a combination of view and edit;
		implement it once again.
		*/

		$args['Heading'] = "Conflict: {$_REQUEST['iPage']}";
		$this->changeState('out/Html');

		$text =& Data::retrieve( $_REQUEST['iPage'] );
		if ($text === FALSE) { //type equal
			$gError->add("Could not read Wiki page: {$_REQUEST['iPage']} (Maybe deleted?)");
			return false;
		}
		Data::preformatText($_REQUEST['iText']);

		$text =& Diff::diffStrings($_REQUEST['iText'], $text, TRUE);

		//by using current file time SavePage can be applied, no SaveConflict is required
		//and we will spot further conflicts
		$fileModTime = Data::getFileModTime($_REQUEST['iPage']);

echo <<<eoh
$buffer
<p><small>
Another user has <strong>changed the page while you where editing</strong> it.
You see an edit box below providing a combination of the two text versions.
Sections marked as <strong>removed</strong> are missing in your version compared to the conflicting
version. Likewise, sections marked as <strong>new</strong> are available in your version
but not in the conflicting version.
In the case you save the page, the former state of it will be preserved as an old revision.
</small></p>

<p>
<a href='{$_SERVER['PHP_SELF']}?iRequest=wiki/ViewPage&amp;iPage={$_REQUEST['iPage']}'
   title='Ignore my changes, accept the other version.'>Ignore my changes</a>
and accept the other version.
</p>


<form action='{$_SERVER['PHP_SELF']}' method='post'>
<input type='hidden' name='iRequest' value='wiki/SavePage'>
<input type='hidden' name='iPage' value='{$_REQUEST['iPage']}'>
<input type='hidden' name='iType' value='standard'>
<input type='hidden' name='iFileModTime' value='$fileModTime'>
<textarea name='iText' cols='{$gConfig->mProp["EditColumns"]}' rows='{$gConfig->mProp["EditRows"]}' wrap='virtual'>$text</textarea>
<br>
<input type='submit' value=' OK '>
<input type='reset' value='Cancel'>
<input type='button' value='Go Back' onclick='history.back()'>
<br>
<small>
<em>OK</em> will save the contents of the form as the current revision.
<br>
<em>Cancel</em> will undo all changes done in the form above, i.e. reset the form.
</small>
</form>

eoh;
	}
}

?>