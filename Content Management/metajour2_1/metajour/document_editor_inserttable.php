<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */

session_start();
require_once('config.php');
require_once('ow.php');

	function loadLangFile($file) {
		global $LANG;
		$langfiles = locateLangFiles($file);
		foreach ($langfiles as $langfile) {
			include($langfile);
		}
	}

loadLangFile('basic_view');
loadLangFile('document_view_editor');
?>

<html>
<head>
	<title><?php echo $LANG['table_inserttitle'] ?></title>
	<style type="text/css">
	body { background-color: buttonface; font-family: Tahoma; font-size: 8pt; }
	select { font-family: Tahoma; font-size: 8pt; }
	input { font-family: Tahoma; font-size: 8pt; }
	td { font-family: Tahoma; font-size: 8pt; }
	</style>
	<script language="JavaScript" for="window" event="onload">
		document.getElementById('BORDERCOLOR').style.backgroundColor = '000000';
		document.getElementById('BGCOLOR').style.backgroundColor = 'FFFFFF';
		// Nothing (yet!)
	</script>
			
	</script>
	<SCRIPT LANGUAGE=JavaScript>
		function IsDigit() {
			return ((event.keyCode >= 48) && (event.keyCode <= 57))
		}
		
		function getNumber(value) {
			if (value.length > 0) {
				matches = value.match(/([0-9]+)/);
				if (matches.length > 0) {
					return matches[0];	
				}
			}
			return 0;
		}
	</SCRIPT>

	
	<script language="JavaScript" for="OK" event="onclick">
		var arr = new Array();
		var form = document.forms[0];
		
		arr["TableAttrs"] = 'style="border-collapse: collapse; background-color: ' + document.getElementById('BGCOLOR').style.backgroundColor + '"';
		
		if (form.width.value.length > 0)
			arr["TableAttrs"] = arr["TableAttrs"] + ' width=' + getNumber(form.width.value);

		//arr["CellAttrs"] = 'bgcolor="' + document.getElementById('BGCOLOR').style.backgroundColor + '"';
		
		arr["CellAttrs"] = 'valign="top" style="margin: ' + getNumber(form.cellpadding.value) + 'px; padding: ' + getNumber(form.cellspacing.value) + '"';

		if (getNumber(form.border.value) > 0)
			arr["CellAttrs"] = arr["CellAttrs"] + ' style="border: ' + getNumber(form.border.value) + 'px solid ' + document.getElementById('BORDERCOLOR').style.backgroundColor + '"';
		else
			arr["CellAttrs"] = arr["CellAttrs"] + ' style="border: medium none"';

		arr["NumRows"] = getNumber(form.rows.value);
		arr["NumCols"] = getNumber(form.cols.value);

		window.returnValue = arr;
		window.close();
	</script>

	<script language="JavaScript" for="CANCEL" event="onclick">
		window.close();
	</script>
	
	<script language="JavaScript" for="BORDERCOLORBUTTON" event="onclick">
		var arr = showModalDialog( "document_editor_selectcolor.php",
                             "",
                             "font-family:Verdana; font-size:12; dialogWidth:29em; dialogHeight:24em" );
		if (arr != null)
			document.getElementById('BORDERCOLOR').style.backgroundColor = arr;
	</script>
	
	<script language="JavaScript" for="BGCOLORBUTTON" event="onclick">
		var arr = showModalDialog( "document_editor_selectcolor.php",
                             "",
                             "font-family:Verdana; font-size:12; dialogWidth:29em; dialogHeight:24em" );
		if (arr != null)
			document.getElementById('BGCOLOR').style.backgroundColor = arr;
	</script>
	
	
</head>
<body>
<form>
<table style="margin: 10px">
<tr>
	<td>
			<fieldset>
				<legend><?php echo $LANG['table_dimension'] ?></legend>
				<table border=0 cellpadding=4>
				<tr>
					<td><?php echo $LANG['table_rows'] ?></td>
					<td><input type="text" style="text-align: right;" name="rows" size="3" maxlength="2" value="2" ONKEYPRESS="event.returnValue=IsDigit();"></td>
					<td><?php echo $LANG['table_width'] ?></td>
					<td><input type="text" style="text-align: right;" name="width" size="5" maxlength="5" value="" ONKEYPRESS="event.returnValue=IsDigit();"></td>
				</tr>
				<tr>
					<td><?php echo $LANG['table_columns'] ?></td>
					<td><input type="text" style="text-align: right;" name="cols" size="3" maxlength="2" value="2" ONKEYPRESS="event.returnValue=IsDigit();"></td>
					<td><?php echo $LANG['table_borderwidth'] ?></td>
					<td><input type="text" style="text-align: right" name="border" size="5" maxlength="2" value="0" ONKEYPRESS="event.returnValue=IsDigit();"></td>
				</tr>
				</table>
			</fieldset>
			<fieldset>
				<legend><?php echo $LANG['table_colors'] ?></legend>
				<table border=0 cellpadding=4>
				<tr>
					<td><?php echo $LANG['table_bordercolor'] ?></td>
					<td><span ID="BORDERCOLOR" style="background-color: #ff45a8; width: 23px; border: 1px solid black">&nbsp;</span>&nbsp;<input type="button" value="<?php echo $LANG['table_select'] ?>" ID="BORDERCOLORBUTTON"></td>
				</tr>
				<tr>
					<td><?php echo $LANG['table_bgcolor'] ?></td>
					<td><span ID="BGCOLOR" style="background-color: #ff45a8; width: 23px; border: 1px solid black">&nbsp;</span>&nbsp;<input type="button" value="<?php echo $LANG['table_select'] ?>" ID="BGCOLORBUTTON"></td>
				</tr>
				</table>
			</fieldset>
			<fieldset>
				<legend><?php echo $LANG['table_spacing'] ?></legend>
				<table border=0 cellpadding=4>
				<tr>
					<td><?php echo $LANG['table_cellpadding'] ?></td>
					<td><input type="text" name="cellpadding" size="3" maxlength="2" value="2" ONKEYPRESS="event.returnValue=IsDigit();"></td>
				</tr>
				<tr>
					<td><?php echo $LANG['table_cellspacing'] ?></td>
					<td><input type="text" name="cellspacing" size="3" maxlength="2" value="2" ONKEYPRESS="event.returnValue=IsDigit();"></td>
				</tr>
				</table>
			</fieldset>
	</td>
	<td valign="bottom">
		<table cellpadding=2>
			<tr>
				<td><input type="button" id="OK" value="<?php echo $LANG['button_ok'] ?>" style="width: 80px; height: 22px" default></td>
			</tr>
			<tr>
				<td><input type="button" id="CANCEL" value="<?php echo $LANG['button_cancel'] ?>" style="width: 80px; height: 22px"></td>
			</tr>
		</table>
	</td>
</tr>
</table>

</form>
</body>
</html>
