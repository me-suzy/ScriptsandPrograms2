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

class ReportTrailmapForm extends Response {

	function ReportTrailmapForm( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function service( &$buffer, &$args ) {
		global $gConfig;

		$args['Heading'] = 'Trail Map / Teaser Text Report';
		$this->changeState('out/Html');
		$menu         = Report::getMenu();
		$pageSelector = Report::getFormPartWikiPage();

		echo <<<eoh
$buffer
$menu
<form name='reportform' action='{$_SERVER['PHP_SELF']}' method='post'>
<input type='hidden' name='iRequest' value='navigation/ReportTrailMapResult'>
<table border='0' cellspacing='2' cellpadding='2' class='layout' width='100%'>
<tr>
	<th colspan='2' align='left'>Trailmap / Teaser Text Report</th>
</tr>
<tr>
	<td class='layout' width="15%" valign='top'>Name:</td>
	<td class='layout' valign='top'>
		<input type='text' name='iName' size='20'>
		<br>
		<small>Input the name of the trail map / teaser text report, i.e. the first field.</small>
	</td>
</tr>
$pageSelector
<tr>
	<td class='layout' valign='top'>Sort order: </td>
	<td class='layout'>
		<input type='radio' name='iSort' value='0' checked> Ascending: 0-9, A-Z.
		<br>
		<input type='radio' name='iSort' value='1' > Descending: Z-A, 9-0 (suitable for dates, news, etc.)
	</td>
</tr>
<tr>
	<td class='layout'><input type='submit' value=' OK '></td>
	<td class='layout'>&nbsp;</td>
</tr>
</table>
</form>
<br>
<p>
A trail map / teaser text report is based on markers that have to be present in each
Wiki page to include. The format is:
</p>
<div align='center'>
	<strong>&#37;&#37;name:description:sort:teaser text&#37;&#37;</strong>
</div>
<dl>
	<dt>name</dt>
	<dd>An identifier for the report / trail map. One Wiki may contain more than one trail map.
	    One Wiki page may belong to different reports as well.</dd>
	<dt>description</dt>
	<dd>(Optional) If given, replaces the Wiki page name as heading.</dd>
	<dt>sort</dt>
	<dd>(Optional) A sort order hint for the report. For a chronological order, enter the
	    date in the YYYYmmdd format, e.g. 200507021. Any other sort order hint
	    will be fine, too. For example, one may create categories 'a', 'b', 'c'.
	    In the case several pages provide the same sort order hint, the Wiki
	    page name is applied to sort this group.</dd>
	<dt>teaser text</dt>
	<dd>The text that is supposed to appear beneath the heading. It may contain
	    Wiki markup, but it must not contain a colon (':').</dd>
</dl>
<p>
Sample: <strong>&#37;&#37;news:Software Shares Rise:200507291231:Today the markets openend with...&#37;&#37;</strong>
</p>
<p>
<em>Note:</em> PWP will create a heading followed by the teaser text.
<br>
This report is useful for tutorial trail maps, news pages, etc.
</p>
eoh;
	}

}

?>