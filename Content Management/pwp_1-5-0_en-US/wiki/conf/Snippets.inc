<?php
/**
 * Are you going to edit this file without having made a backup?
 * To be used as static class (Snippet::).
 * Provides text snippets for the edit form.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

class Snippets {

	/**
	 * Define your snippets here.
	 * Returns an array containing 'name' => 'text'.
	 * Snippets must not contain ' or ". If required, use \'.
	 * Use \\n for a line break in "enclosed" strings.
	 * Use \n  for a line break in 'enclosed' strings.
	 */
	function &_defineSnippets() {
		global $gConfig;
		return array(

		'DAT'    => date( $gConfig->mProp['DateFormat'], time() ),
		'Sales1' => '{{SalesContact}}', //click inserts a 'call' to a variable, not the content
		'Sales2' => $gConfig->mVars['SalesContact'], //click inserts the content of the variable
		'Addr.'	 => "\\n First Name: \\n Last Name:  \\n Phone:      \\n E-Mail:     \\n Street:     \\n City:       \\n",

		); //array
	}

	/** Retuns the HTML required for the edit form. (Do not touch!) */
	function &getSnippets( $formName, $boxName ) {
		global $gConfig;
		$res = <<<eoh
<script language='JavaScript1.2' type='text/javascript'>
function addSnippet( pText ) {
	document.$formName.$boxName.value += pText;
	document.$formName.$boxName.focus();
}
</script>
<hr size='1' noshade>
<p>Click to add a snippet:&nbsp;&nbsp;
eoh;
		$arrSnippets =& Snippets::_defineSnippets();
		foreach ( $arrSnippets as $name => $snippet ) {
			$res .= <<<eoh
<a	href='javascript:addSnippet("$snippet")'>[$name]</a>&nbsp;
eoh;
		}
		return $res.'</p>';
	}
}

?>