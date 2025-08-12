<?

$GLOBALS['instructions'] = "Use the file browser below to select the file you'd like to import or type the name of the file.<br><br> Valid files end in: \".csv\", \".txt\" and \".dat\".";
$GLOBALS['tableWidth'] = "600";
$GLOBALS['stage'] = "preview";
$GLOBALS['submitValue'] = "Select file/directory";
$GLOBALS['validateFormArgs'] = ", this.fileName.value";

$fileList = FileList("select_file.php", "fileName", "25", $_POST['fileName']);

$GLOBALS['display_block'] = '
	<tr class="sectionHeader">
		<td>' . $fileList['browsing'] . '</td>
	</tr>
	<tr class="spacer">
		<td>&nbsp;</td>
	</tr>
	<tr align="center">
		<td>' . $fileList['options'] . '<br><a href="JavaScript:document.location.href(\'?\')">Default Directory</a></td>
	</tr>
		<input type="hidden" name="fileDirectory" value="' . $fileList['fileDirectory'] . '">
		<input type="hidden" name="previewLimit" value="10">
		<input type="hidden" name="useFRAH" value="">' . repost();

?>