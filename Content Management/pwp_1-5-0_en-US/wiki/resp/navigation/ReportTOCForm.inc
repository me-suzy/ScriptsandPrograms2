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

class ReportTOCForm extends Response {

	function ReportTOCForm( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function service( &$buffer, &$args ) {
		global $gConfig;

		$args['Heading'] = 'Table of Contents Report';
		$this->changeState('out/Html');
		$menu         = Report::getMenu();
		$pageSelector = Report::getFormPartWikiPage();

		echo <<<eoh
$buffer
$menu
<form name='reportform' action='{$_SERVER['PHP_SELF']}' method='post'>
<input type='hidden' name='iRequest' value='navigation/ReportTOCResult'>
<table border='0' cellspacing='2' cellpadding='2' class='layout' width='100%'>
<tr>
	<th colspan='2' align='left'>TOC Report</th>
</tr>
<tr>
	<td class='layout' width="15%" valign='top'>Name:</td>
	<td class='layout' valign='top'>
		<input type='text' name='iName' size='20'>
		<br>
		<small>Input the name of the table of contents, i.e. the first field.</small>
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
A TOC report is based on markers that have to be present in each
Wiki page to include. The format is:
</p>
<div align='center'>
	<strong>&#37;&#37;name:predecessor:level:toc_entry&#37;&#37;</strong>
</div>
<dl>
	<dt>name</dt>
	<dd>An identifier for the TOC. One Wiki may contain several TOCs.
	    One Wiki page may belong to different TOCs as well.</dd>
	<dt>predecessor</dt>
	<dd>The name of the related page. Can be either on the same level or one level above.
	    <strong>Leave this field empty for the top chapters of the TOC.</strong></dd>
	<dt>level</dt>
	<dd>0|1: Zero indicates that the page should be at the same level as its predecessor.
	    One moves the page one step deeper; the current page becomes a subchapter of
	    the predecessor.</dd>
	<dt>toc_entry</dt>
	<dd>Optional: Text that should be displayed in the TOC instead of the Wiki page name.</dd>
</dl>
<p>
All colons are required; empty fields have a double colon, e.g. use
<strong>&#37;&#37;toc::0:&#37;&#37;</strong> for a top level chapter with no extra description.
</p>
<p>
Working with level and predecessor information makes it fairly simple to move a chapter with
a whole tree of subchapters to another location or level: Just change the predecessor of the
chapter; the sub chapters remain unchanged.
</p>
eoh;
	}

}

?>