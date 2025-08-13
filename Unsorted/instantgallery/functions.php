<?
function getImgList( $dir )
{
	global $thumbflag;
	$handle = opendir($dir);

	while ($file = readdir($handle))
	{
		$ext = substr($file, strrpos($file, ".") + 1);		// grab file extension

		// add filename to output array if file has correct image extention and no thumbflag
		if (eregi('^(jpg|gif|png|jpeg|jpe)$', $ext) && !ereg("($thumbflag\.$ext)$", $file))
        	$imglist[] = $file;
	}

	closedir( $handle );

	if (!empty($imglist)) {
		ksort($imglist);
		return $imglist;
	} else {
		return False;
	}
}

function getScrollBar( $imgno )
{
	global $imgcnt, $gallery, $tmplt;
	echo $imgno . " of " . $imgcnt . ": ";

	if ($imgno != 1 && $imgno != $imgcnt) {
		echo "<A href=\"javascript:imgSubmit('index.php?gallery=" . $gallery . "&imgno=" . ($imgno - 1) . "&tmplt=" . $tmplt . "');\" class=\"scrolltxt\">Previous</A>\n";
		echo " | <A href=\"javascript:imgSubmit('index.php?gallery=" . $gallery . "&imgno=" . ($imgno + 1) . "&tmplt=" . $tmplt . "');\" class=\"scrolltxt\">Next</A>\n";
	} elseif ($imgno == 1) {
		echo "<A href=\"javascript:imgSubmit('index.php?gallery=" . $gallery . "&imgno=" . ($imgno + 1) . "&tmplt=" . $tmplt . "');\" class=\"scrolltxt\">Next</A>\n";
	} else {
		echo "<A href=\"javascript:imgSubmit('index.php?gallery=" . $gallery . "&imgno=" . ($imgno - 1) . "&tmplt=" . $tmplt . "');\" class=\"scrolltxt\">Previous</A>\n";
	}
	
	echo " || <A href=\"javascript:imgSubmit('index.php?gallery=" . $gallery . "&tmplt=" . $tmplt . "');\" class=\"scrolltxt\">Thumbs</A>\n";
}

function thumbTable( $thumbrowcnt, $imgwidth, $imgheight, $imgspacing, $borderwidth, $bordercolor )
{
	global $thumbflag, $galleryroot, $gallery, $imglist, $imgcnt, $tmplt;

	$imgw = $imgh = "";
	if (!empty($imgwidth)) $imgw = " width=\"" . $imgwidth . "\"";
	if (!empty($imgheight)) $imgh = " height=\"" . $imgheight . "\"";

	if (!empty($borderwidth)) {
		$bstr1 = "<table cellspacing=\"" . $imgspacing . "\" cellpadding=\"" . $borderwidth . "\" border=\"0\"><tr><td bgcolor=\"#" . $bordercolor . "\">";
		$bstr2 = "</td></tr></table>";
		$imgspacing = "0";
	} else {
		$bstr1 = ""; $bstr2 = "";
	}

	echo "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n";

	$i = 1;
	$count = 1;
	foreach ($imglist as $img) {

		$thumburl = "$galleryroot/$gallery/" . ereg_replace("\.", "$thumbflag.", $img);

		if ($i == 1) { echo "<tr>\n"; }

		echo "<td>" . $bstr1;
		echo "<a href=\"javascript:imgSubmit('index.php?gallery=" . $gallery . "&imgno=" . $count . "&tmplt=" . $tmplt . "');\">";
		echo "<img src=\"" . $thumburl . "\"" . $imgw . $imgh . " border=\"0\" vspace=\"" . $imgspacing . "\" hspace=\"" . $imgspacing . "\"></a>";
		echo $bstr2 . "</td>\n";

		if ($i == $thumbrowcnt) {
			echo "\n</tr>\n";
			$i = 1;
		} else {
			$i++;
		}
		$count++;
	}

	echo "</table>\n";
}
?>
