<?
$pagetitle = "$sitetitle PHP Scripts";
include "./config.php";
$sql = "select id,cat,ct from $tablecats";
$result = mysql_query($sql) or die("Failed: $sql");
$numrows = mysql_num_rows($result);
$half = intval($numrows / 2);
if (($half+$half)!=$numrows) $half = $half + 1;

if ($headerfile) include $headerfile;
print "<form name='form1' method='post' action='search.php'><table width='$table_width' border='$table_border' cellspacing='$cellspacing' cellpadding='3' bordercolor='$table_border_color' bgcolor='$table_head_color' align='center'><tr><td width='48%' height='19'><font face='$fontname' size='-1'><font color='$table_head_textcolor'><a href='$main_site_url'><font color='$table_head_textcolor'>$sitetitle</font></a>: PHP Scripts</font></font></td><td width='52%' align='right' height='19'><font face='$fontname' size='-1'><font color='$table_head_textcolor'>Search: <input type='text' name='search' size='10'><input type='submit' value='Go!'></font></font></td></tr></table></form>\n";
print "<table bgcolor='$table_bgcolor' width='$table_width' border='0' cellspacing='$cellspacing' cellpadding='$cellpadding' align='center'><tr><td width='50%' valign='top'> <table width='100%' border='0' cellspacing='$cellspacing' cellpadding='3' align='center'>\n";
for($x=0;$x<$numrows;$x++){
	$resrow = mysql_fetch_row($result);
	$id = $resrow[0];
	$cat = $resrow[1];
	$cnt = $resrow[2];
	$ctfolder = str_replace(" ", "_", $cat);
	if ($cnt!=0) $lnkclr = $cat_link_color;
	if ($cnt==0) $lnkclr = $empty_cat_link_color;
	if ($cnt!=0) $foldericon = "foldericon.enabled.gif";
	if ($cnt==0) $foldericon = "foldericon.disabled.gif";

	if ($x==$half) print "</table></td><td width='50%' valign='top'><table width='100%' border='0' cellspacing='$cellspacing' cellpadding='3' align='center'>";
	print "<tr><td width='7%'><img src='$foldericon' width='22' height='19'></td><td width='93%'><font face='$fontname'><a href='cat.php?id=$id'><font color='$lnkclr'>$cat</font></a> <i>($cnt)</i></font></td></tr>\n";
}
print "</table></td></tr></table>\n";
print "<form name='form1' method='post' action='search.php'><table width='$table_width' border='$table_border' cellspacing='$cellspacing' cellpadding='3' bordercolor='$table_border_color' bgcolor='$table_head_color' align='center'><tr><td width='48%' height='19'><font face='$fontname' size='-1'><font color='$table_head_textcolor'><a href='$main_site_url'><font color='$table_head_textcolor'>$sitetitle</font></a>: PHP Scripts</font></font></td><td width='52%' align='right' height='19'><font face='$fontname' size='-1'><font color='$table_head_textcolor'>Search: <input type='text' name='search' size='10'><input type='submit' value='Go!'></font></font></td></tr></table></form><center><font face='$fontname'>Powered by <a href='http://nukedweb.memebot.com/' target='_psi'>PHP Script Index</a></center>\n";

if ($footerfile) include $footerfile;
?>