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
	<title><?php echo $LANG['table_edittitle'] ?></title>
	<style type="text/css">
	body { background-color: buttonface; font-family: Tahoma; font-size: 8pt; }
	select { font-family: Tahoma; font-size: 8pt; }
	input { font-family: Tahoma; font-size: 8pt; }
	td { font-family: Tahoma; font-size: 8pt; }
	</style>
	<script language="JavaScript" for="window" event="onload">
		var tabelprops = window.dialogArguments;
		tabelprops["cellpadding"] = getNumber(tabelprops["cellpadding"]);
		tabelprops["cellspacing"] = getNumber(tabelprops["cellspacing"]);
		tabelprops["border"] = getNumber(tabelprops["border"]);
		var form = document.forms[0];
		form.cellpadding.value = tabelprops["cellpadding"];
		form.cellspacing.value = tabelprops["cellspacing"];
		form.border.value = tabelprops["border"];
		document.getElementById('BORDERCOLOR').style.backgroundColor = tabelprops["bordercolor"];
		document.getElementById('BGCOLOR').style.backgroundColor = tabelprops["bgcolor"];
	</script>
			
	</script>
	<SCRIPT LANGUAGE=JavaScript>
		function IsDigit() {
			return ((event.keyCode >= 48) && (event.keyCode <= 57))
		}
		
		function getNumber(value) {
			if (value.length > 0) {
				matches = value.match(/([0-9]+)/);
				if (matches && matches.length > 0) {
					return matches[0];	
				}
			}
			return 0;
		}
	</SCRIPT>

	
	<script language="JavaScript" for="OK" event="onclick">
		var arr = new Array();
		var form = document.forms[0];
		arr["cellpadding"] = getNumber(form.cellpadding.value)
		arr["cellspacing"] = getNumber(form.cellspacing.value)
		arr["border"] = getNumber(form.border.value);
		arr["bordercolor"] = document.getElementById('BORDERCOLOR').style.backgroundColor;
		arr["bgcolor"] = document.getElementById('BGCOLOR').style.backgroundColor;
		window.returnValue = arr;
		window.close();
	</script>

	<script language="JavaScript" for="CANCEL" event="onclick">
		window.close();
	</script>
	
	<script language="JavaScript" for="BORDERCOLORBUTTON" event="onclick">
		var arr = showModalDialog( "document_editor_selectcolor.php",
                             "",
                             "font-family:Verdana; font-size:12; dialogWidth:30em; dialogHeight:34em" );
		if (arr != null)
			document.getElementById('BORDERCOLOR').style.backgroundColor = arr;
	</script>
	
	<script language="JavaScript" for="BGCOLORBUTTON" event="onclick">
		var arr = showModalDialog( "document_editor_selectcolor.php",
                             "",
                             "font-family:Verdana; font-size:12; dialogWidth:30em; dialogHeight:34em" );
		if (arr != null)
			document.getElementById('BGCOLOR').style.backgroundColor = arr;
	</script>
	
	
</head>
<body>
<form>
<table style="margin: 10px">
<tr>
	<td>
		<table style="border: 1px solid #CCCCCC" cellpadding=4>
		<tr>
			<td><?php echo $LANG['table_borderwidth'] ?></td>
			<td>
				<input type="text" name="border" size="3" maxlength="2" value="" ONKEYPRESS="event.returnValue=IsDigit();">
				&nbsp; pixels
			</td>
		</tr>
		<tr>
			<td><?php echo $LANG['table_bordercolor'] ?></td>
			<td><span ID="BORDERCOLOR" style="background-color: #ff45a8; width: 23px; border: 1px solid black">&nbsp;</span>&nbsp;<input type="button" value="<?php echo $LANG['table_select'] ?>" ID="BORDERCOLORBUTTON"></td>
		</tr>
		<tr>
			<td><?php echo $LANG['table_cellpadding'] ?></td>
			<td><input type="text" name="cellpadding" size="3" maxlength="2" value="" ONKEYPRESS="event.returnValue=IsDigit();"></td>
		</tr>
		<tr>
			<td><?php echo $LANG['table_cellspacing'] ?></td>
			<td><input type="text" name="cellspacing" size="3" maxlength="2" value="" ONKEYPRESS="event.returnValue=IsDigit();"></td>
		</tr>
		<tr>
			<td><?php echo $LANG['table_bgcolor'] ?></td>
			<td><span ID="BGCOLOR" style="background-color: #ff45a8; width: 23px; border: 1px solid black">&nbsp;</span>&nbsp;<input type="button" value="<?php echo $LANG['table_select'] ?>" ID="BGCOLORBUTTON"></td>
		</tr>
		</table>
	</td>
	<td valign="top">
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
