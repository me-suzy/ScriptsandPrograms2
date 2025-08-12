<?
if (!$search) {
	Header("Location: index.php");
	exit;
}
include "./config.php";
$pagetitle = "$sitetitle: PHP Scripts: Search Results";
if ($headerfile) include $headerfile;
if ($max_search_results) $srchlimit = " limit 0, $max_search_results";
$sql = "select id,title,homeurl,dlurl,demourl,descr,price,version,hitsout,added from $tablescripts where (title rlike '$search' or descr rlike '$search') order by hitsin desc".$srchlimit;
$result = mysql_query($sql) or die("Failed: $sql");
$numrows = mysql_num_rows($result);
print "<form name='form1' method='get' action='search.php'><table width='$table_width' border='$table_border' cellspacing='$cellspacing' cellpadding='3' bordercolor='$table_border_color' bgcolor='$table_head_color' align='center'><tr><td width='60%'><font face='$fontname' size='-1'><font color='$table_head_textcolor'><a href='$main_site_url'><font color='$table_head_textcolor'>$sitetitle</font></a>: <a href='index.php'><font color='$table_head_textcolor'>PHP Scripts</font></a>: Found $numrows Matches for <i>$search</i>.</font></font></td><td width='40%' align='center'><font face='$fontname' size='-1'><font color='$table_head_textcolor'><div align='center'><font face='$fontname' size='-1'>Search: <font color='$table_head_textcolor'><input type='text' name='search' size='10'><input type='submit' value='Go!' name='submit'></font>&nbsp;<a href='modify.php'><font color='$table_head_textcolor'>Modify Script</font></a></font></div></font></font></td></tr></table></form>";

for($x=0;$x<$numrows;$x++){
	$resrow = mysql_fetch_row($result);
	$id = $resrow[0];
	$title = $resrow[1];
	$homeurl = $resrow[2];
	$dlurl = $resrow[3];
	$demourl = $resrow[4];
	$descr = $resrow[5];
	$price = $resrow[6];
	$version = $resrow[7];
	$hitsout = $resrow[8];
	$added = $resrow[9];
	$aradded = explode(" ", $added);
	$added = $aradded[0];
	$title .= " ".$version;
	if (($ckAdminPass) && ($ckAdminPass==$adminpass)) $adminurl = " &nbsp;&nbsp;<font color='$table_head_textcolor'>[<a href='cat.php?admindelete=$id'><font color='$table_head_textcolor'>X</a></a>]</font>";
	$links = "";
	if ($homeurl) $links .= "<font size='-1' face='$fontname'><font color='$table_head_textcolor'>[<a href='out.php?id=$id&home=1' target='$id'><font color='$table_head_textcolor'>Homepage</font></a>]</font> ";
	if ($dlurl) $links .= "<font size='-1' face='$fontname'><font color='$table_head_textcolor'>[<a href='out.php?id=$id&dl=1'><font color='$table_head_textcolor'>Download</font></a>]</font> ";
	if ($demourl) $links .= "<font size='-1' face='$fontname'><font color='$table_head_textcolor'>[<a href='out.php?id=$id&demo=1' target='$id'><font color='$table_head_textcolor'>Demo</font></a>]</font> ";
	$descr .= "<br><i>Price: $price - Added: $added - Hits: $hitsout</i>";
	print "<table width='$table_width' border='$table_border' cellspacing='$cellspacing' cellpadding='$cellpadding' align='center' bordercolor='$table_border_color'>
	  <tr> 
	    <td bgcolor='$table_head_color'><font size='-1' face='$fontname'>&nbsp;&nbsp;&nbsp;<b><a href='view.php?id=$id'><font color='$table_head_textcolor'>$title</font></a>$adminurl</b></font></td>
	    <td bgcolor='$table_head_color' align='right'><font size='-1' face='$fontname'>&nbsp;$links</font></td>
	  </tr>
	  <tr> 
	    <td bgcolor='$table_bgcolor' colspan='2'> 
	      <blockquote> 
	        <p><font size='-1' face='$fontname' color='$table_textcolor'>$descr</font></p>
	      </blockquote>
	    </td>
	  </tr>
	</table><br>";
}
print "<form name='form1' method='get' action='search.php'><table width='$table_width' border='$table_border' cellspacing='$cellspacing' cellpadding='3' bordercolor='$table_border_color' bgcolor='$table_head_color' align='center'><tr><td width='60%'><font face='$fontname' size='-1'><font color='$table_head_textcolor'><a href='$main_site_url'><font color='$table_head_textcolor'>$sitetitle</font></a>: <a href='index.php'><font color='$table_head_textcolor'>PHP Scripts</font></a>: Found $numrows Matches for <i>$search</i>.</font></font></td><td width='40%' align='center'><font face='$fontname' size='-1'><font color='$table_head_textcolor'><div align='center'><font face='$fontname' size='-1'>Search: <font color='$table_head_textcolor'><input type='text' name='search' size='10'><input type='submit' value='Go!' name='submit'></font>&nbsp;<a href='modify.php'><font color='$table_head_textcolor'>Modify Script</font></a></font></div></font></font></td></tr></table></form><center><font face='$fontname'>Powered by <a href='http://nukedweb.memebot.com/' target='_psi'>PHP Script Index</a></center>";
if ($footerfile) include $footerfile
?>