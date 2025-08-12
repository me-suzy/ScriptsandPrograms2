<?php
/**
 * This response shows a form for the
 * reports based on file name search or full text search.
 * Since 1.5.0
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Report.inc' );

class ReportSearchForm extends Response {

	function ReportSearchForm( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function service( &$buffer, &$args ) {
		global $gConfig;

		$args['Heading'] = 'Report Based on File Name / Full Text Search';
		$this->changeState('out/Html');
		$menu         = Report::getMenu();
		$pageSelector = Report::getFormPartWikiPage();
		$typeSelector = ReportSearchForm::getFormPartInclusions();

		echo <<<eoh
$buffer
$menu
<form name='reportform_name' action='{$_SERVER['PHP_SELF']}' method='post'>
<input type='hidden' name='iRequest' value='navigation/ReportNameResult'>
<table border='0' cellspacing='2' cellpadding='2' class='layout' width='100%'>
<tr>
	<th colspan='2' align='left'>1) Report Based on File Name Search</th>
</tr>
<tr>
	<td class='layout' width="15%" valign='top'>Include:</td>
	<td class='layout' valign='top'>
		<input type='text' name='iSearch' size='20'>
		<br>
		<small>Input a fragment of a file name. Do not use wildcards.
		Leave this field empty in order to include all pages or files.</small>
	</td>
</tr>
$pageSelector
$typeSelector
</tr><tr>
	<td class='layout'><input type='submit' value=' OK '></td>
	<td class='layout'>&nbsp;</td>
</tr>
</table>
</form>
<br>

<form name='reportform_text' action='{$_SERVER['PHP_SELF']}' method='post'>
<input type='hidden' name='iRequest' value='navigation/ReportTextResult'>
<table border='0' cellspacing='2' cellpadding='2' class='layout' width='100%'>
<tr>
	<th colspan='2' align='left'>2) Report Based on Full Text Search</th>
</tr>
<tr>
	<td class='layout' valign='top' width="15%" valign='top'>Search for:</td>
	<td class='layout' valign='top'>
		<input type='text' name='iSearch' size='20'>
		<br>
		<small>Input a text fragment to search for. Do not use wildcards.</small>
	</td>
</tr>
$pageSelector
$typeSelector
</tr><tr>
	<td class='layout'><input type='submit' value=' OK '></td>
	<td class='layout'>&nbsp;</td>
</tr>
</table>
</form>
<br>
eoh;

	}

	/**
	 * retuns checkboxes for Wiki pages and/or uploaded files.
	 */
	function getFormPartInclusions() {
		global $gConfig;
		return <<<eoh
<tr>
	<td class='layout' valign='top'>Search in:</td>
	<td class='layout' valign='top'>
		<input type='checkbox' name='iSearchWiki'   value='1' checked > Wiki pages
		<br>
		<input type='checkbox' name='iSearchUpload' value='1' > Uploaded files
		<br>
		<small>
			Define where to search. Will search in uploaded files of the
			types <strong>{$gConfig->mProp['TextFileTypes']}</strong>.
		</small>
	</td>
</tr>
eoh;
	}

}

?>