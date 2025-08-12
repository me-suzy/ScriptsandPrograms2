<?php
/**
 * This response shows a form for the
 * table of contents report (TOC).
 * Since 1.5.0
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Report.inc' );

class ReportIndexForm extends Response {

	function ReportIndexForm( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function service( &$buffer, &$args ) {
		global $gConfig;

		$args['Heading'] = 'Keyword Index Report';
		$this->changeState('out/Html');
		$menu         = Report::getMenu();
		$pageSelector = Report::getFormPartWikiPage();

		echo <<<eoh
$buffer
$menu
<form name='reportform' action='{$_SERVER['PHP_SELF']}' method='post'>
<input type='hidden' name='iRequest' value='navigation/ReportIndexResult'>
<table border='0' cellspacing='2' cellpadding='2' class='layout' width='100%'>
<tr>
	<th colspan='2' align='left'>Keyword Index Report</th>
</tr>
<tr>
	<td class='layout' width="15%" valign='top'>Name:</td>
	<td class='layout' valign='top'>
		<input type='text' name='iName' size='20'>
		<br>
		<small>Input the name of the keyword index, i.e. the first field.</small>
	</td>
</tr>
$pageSelector
<tr>
	<td class='layout'><input type='submit' value=' OK '></td>
	<td class='layout'>&nbsp;</td>
</tr>
</table>
</form>
<br>
<p>
A keyword index report is based on markers that have to be present in each
Wiki page to include. The format is:
</p>
<div align='center'>
	<strong>&#37;&#37;name:key1:key2:...:keyN&#37;&#37;</strong>
</div>
<dl>
	<dt>name</dt>
	<dd>An identifier for the index. One Wiki may contain more than one index.
	    One Wiki page may belong to different index pages as well.</dd>
	<dt>key</dt>
	<dd>A case sensitive keyword. Expressions or groups of words are allowed.</dd>
</dl>
<p>
Sample: <strong>&#37;&#37;index:fast:red:dangerous&#37;&#37;</strong>
</p>
<p>
<em>Note:</em> PWP will create a definition list containing the keyword or expression
as definition term and the corresponding Wiki pages as definition text. For each definition
term, there is a similar named anchor available.
<br>
In order to provide a "see also" link within your text, enter
<strong>[[IndexPageName#keyword (see also keyword)]]</strong>.
</p>
eoh;
	}

}

?>