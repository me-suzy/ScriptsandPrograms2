<?

//

$csvFile = ($_POST['fileName']) ? $_POST['fileDirectory'] . $_POST['fileName'] :	$_POST['csvFile'];

//
$delimiter = (!$_POST['delimiter']) ? "," : $_POST['delimiter'];
$preview = GetPreview($csvFile, $delimiter, $_POST['previewLimit'], $_POST['chkUseFRAH']);


$GLOBALS['display_block'] = '
	<tr class="highlight">
		<td><input type="checkbox" name="chkUseFRAH"';

if (isset($_POST['chkUseFRAH'])) $GLOBALS['display_block'] .= " checked";

$GLOBALS['display_block'] .= ' onClick="ChangeHeaderClass(this)"> Use the first row in the .CSV file as the column headers.</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td align="center">
			' . $preview['previewData'] . '
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>
			<table cellspacing=1 cellpadding=0 border=0 width="500" align="center">
				<tr class="sectionHeader">
					<td colspan="3">Want to try again with different options?</td>
				</tr>
				<tr class="subForm">
					<td width="33%">Delimiter: <input type="text" name="delimiter" value="' . $delimiter . '" size="2" maxLength="1"></td>
					<td width="34%">Preview limit: <input type="text" name="previewLimit" value="' . $_POST['previewLimit'] . '" size="3" maxLength="3"></td>
					<td width="33%"><input type="submit" name="retry" value="Try again" class="submit" onClick="this.form.stage.value=\'preview\'"></td>
				</tr>
			</table>
		</td>
	</tr>
		<input type="hidden" name="fileDirectory" value="' . $_POST['fileDirectory'] . '">
		<input type="hidden" name="csvFile" value="' . $csvFile . '">' . Repost();
	

	
$tableWidth = $maxCols*80;
if ($tableWidth  < 480) {
	$GLOBALS['tableWidth'] = "480";
} else if ($tableWidth > 770) {
	$GLOBALS['tableWidth'] = "100%";
} else {
	$GLOBALS['tableWidth'] = $tableWidth;
}

$GLOBALS['instructions'] = 'Here\'s the preview of the file "<em>' . $csvFile . '</em>"';
$GLOBALS['stage'] = "db_connect";
?>