<html>
<title>Banner preview</title>
<body topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>
<table border=0 width=100% height=100% cellspasing=0 cellpadding=0>
<tr><td align=center valign=middle>
<?
	switch($type){
	 	case "Image":
			echo "<img src=$url width=$w height=$h alt=\"Banner preview: $url\">";
			break;
	 	case "Flash":
			echo "<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0\" width=\"$w\" height=\"$h\">\n";
			echo "<param name=movie value=\"$url\">\n";
			echo "<param name=quality value=high><param name=\"SCALE\" value=\"noborder\">;\n";
			echo "<embed src=\"$url\" quality=high pluginspage=\"http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash\" type=\"application/x-shockwave-flash\" width=\"$w\" height=\"$h\" scale=\"noborder\">\n";
			echo "</embed>\n";
			echo "</object>\n";
			break;
	}
?>
</td></tr>
</table>
</body>
</html>