<?
$tabcount = count($tabnames)+1;
echo <<<EOT
<td colspan="$tabcount" height="60">
EOT;
echo ( strstr($SCRIPT_NAME,"adm/") ? str_replace("images/","../images/",$shop_header) : $shop_header);
echo <<<EOT
</td>
</tr>
<tr>
EOT;
for ($i=0; $i<$tabcount-1; $i++) {
	echo "<td align=\"center\" bgcolor=\"";
	if (strstr($taburls[$i],$SCRIPT_NAME)) echo $cl_tab_top; else echo $cl_tab_back;
	echo "\"><img src=\"images/null.gif\" width=\"1\" height=\"1\"></td>";
	if ($i==$tabcount-2) {
		echo "<td align=\"center\" bgcolor=\"";
		if (strstr($taburls[$i],$SCRIPT_NAME)) echo $cl_tab_top; else echo $cl_tab_back;
		echo "\"><img src=\"images/null.gif\" width=\"1\" height=\"1\"></td>";
	}
}
echo "</tr><tr>";
for ($i=0; $i<$tabcount-1; $i++) {
	echo "<td align=\"center\" bgcolor=\"";
	if (strstr($taburls[$i],$SCRIPT_NAME)) echo $cl_tab_top; else echo $cl_tab_back;
	echo "\"><img src=\"images/null.gif\" width=\"1\" height=\"1\"></td>";
	if ($i==$tabcount-2) {
		echo "<td align=\"center\" bgcolor=\"";
		if (strstr($taburls[$i],$SCRIPT_NAME)) echo $cl_tab_top; else echo $cl_tab_back;
		echo "\"><img src=\"images/null.gif\" width=\"1\" height=\"1\"></td>";
	}
}
echo "</tr><tr>";
for ($i=0; $i<$tabcount-1; $i++) {
	echo "<td align=\"center\" bgcolor=\"";
	if (strstr($taburls[$i],$SCRIPT_NAME)) echo $cl_tab_top; else echo $cl_tab_back;
	echo "\" height=\"17\" nowrap";
	if (!strstr($taburls[$i],$SCRIPT_NAME))
		echo " onClick='window.location=\"$taburls[$i]".(strstr($taburls[$i],"?") ? "&" : "?")."first=$first&sortby=$sortby&category=".urlencode($category)."\"'";
	echo "><b><font size=\"-1\">&nbsp;&nbsp;";
	if (!strstr($taburls[$i],$SCRIPT_NAME))
		echo "<a href=\"$taburls[$i]".(strstr($taburls[$i],"?") ? "&" : "?")."first=$first&sortby=$sortby&category=".urlencode($category)."\">";
	echo "$tabnames[$i]";
	if (!empty($tabimages[$i]))
		echo "&nbsp;<img src=\"$tabimages[$i]\" border=\"0\" width=\"17\" height=\"16\" align=\"top\">";
	if (!strstr($taburls[$i],$SCRIPT_NAME))
		echo "</a>";
	echo "&nbsp;&nbsp;</font></b></td>";
	if ($i==$tabcount-2) {
		echo "<td align=\"center\" bgcolor=\"";
		if (strstr($taburls[$i],$SCRIPT_NAME)) echo $cl_tab_top; else echo $cl_tab_back;
		echo "\" height=\"17\" width=\"1%\"><img src=\"images/null.gif\" width=\"1\" height=\"1\"></td>";
	}
}
echo "</tr>";
?>
