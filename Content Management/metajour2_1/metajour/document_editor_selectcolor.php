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


	function hexColor($color) {
		return sprintf("%02X%02X%02X", $color[0], $color[1], $color[2]);
	}
	
	function createGradient() {
	  for($r = 0; $r < 256; $r+=51) {
	    echo "<tr>\n";
	    for($g = 0; $g < 256; $g+=51) {
	      for($b = 0; $b < 256; $b+=51) {
		echo "<td width=16 height=16 bgcolor=\"" . hexColor(array($r, $g, $b)) . "\">&nbsp;</td>\n";
	      }
	    }
	    echo "</tr>\n";
	  }
	}

loadLangFile('basic_view');
loadLangFile('document_view_editor');
?>

<html>
<head>
<title><?php echo $LANG['color_title'] ?></title>
<style type="text/css">
BODY {margin: 10px; font-family: Tahoma; font-size: 12px; background: buttonface}
BUTTON {width: 5em}
TD { cursor: hand; }
P {text-align: center}
</style>

<script language="JavaScript" type="text/javascript" FOR="colorTable" event="onmouseover">
text = event.srcElement.title;
if (text != event.srcElement.bgColor)
     text += " (" + event.srcElement.bgColor + ")";
     RGB.innerText = text;
</script>

<script language="JavaScript" type="text/javascript" for="colorTable" event="onmouseout">
RGB.innerText = " ";
</script>

<script language="JavaScript" type="text/javascript" for="colorTable" event="onclick">
selColor.value = event.srcElement.bgColor;
</script>

<script language="JavaScript" type="text/javascript" for="OK" event="onclick">
window.returnValue = selColor.value;
window.close();
</script>
</head>
<body>
<table id="colorTable" cellspacing=0 cellpadding=0 align="center">
<?php createGradient(); ?>
</table>
<p>
<label for="selColor"><?php echo $LANG['color_label'] ?></label>
<input type="text" size=20 id="selColor">
<br>
<span id="RGB">&nbsp;</span>
<p>
<button ID="OK" type="submit"><?php echo $LANG['button_ok'] ?></button>
<button onclick="window.close();"><?php echo $LANG['button_cancel'] ?></button>
</body>
</html>
