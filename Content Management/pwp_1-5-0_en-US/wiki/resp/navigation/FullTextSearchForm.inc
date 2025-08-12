<?php
/**
 * This response shows a search form for the
 * full text search.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

class FullTextSearchForm extends Response {

	function FullTextSearchForm( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function service( &$buffer, &$args ) {
		global $gConfig;

		$args['Heading'] = 'Full text search';
		$this->changeState('out/Html');

		echo <<<eoh
<form name='searchform' action='{$_SERVER['PHP_SELF']}' method='post'>
<input type='hidden' name='iRequest' value='navigation/FullTextSearchResult'>
<table border='0' cellspacing='2' cellpadding='2' class='layout'>
<tr>
	<th colspan='2' align='left'>1) Search for:</th>
</tr>
<tr>
	<td class='layout' width="44%"><input type='text' name='iSearchText' size='20'></td>
	<td class='layout' valign='top'><small>Input a single word or a word fragment. Do not use wildcards.</small></td>
</tr><tr>
	<th colspan='2' align='left'>2) Search where:</th>
</tr>
<tr>
	<td class='layout'>
		<input type='checkbox' name='iSearchWiki'   value='1' checked > Wiki pages
		<br>
		<input type='checkbox' name='iSearchUpload' value='1' > Uploaded files
	</td>
	<td class='layout' valign='top'>
		<small>
			Define where to search. Will search in uploaded files of the
			types <strong>{$gConfig->mProp['TextFileTypes']}</strong>.
		</small>
	</td>
</tr><tr>
	<td class='layout'><input type='submit' value=' OK '></td>
	<td class='layout'>&nbsp;</td>
</tr>
</table>
</form>
eoh;

	}
}

?>