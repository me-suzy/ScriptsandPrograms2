<?
if (!$id) exit;
include "./config.php";

$sql = "select id,subcat,title,homeurl,dlurl,demourl,descr,price,version,hitsout,added from $tablescripts where id='$id'";
$result = mysql_query($sql) or die("Failed: $sql");
$resrow = mysql_fetch_row($result);
$id = $resrow[0];
$subcat = $resrow[1];
$title = $resrow[2];
$homeurl = $resrow[3];
$dlurl = $resrow[4];
$demourl = $resrow[5];
$descr = $resrow[6];
$price = $resrow[7];
$version = $resrow[8];
$hitsout = $resrow[9];
$added = $resrow[10];
$aradded = explode(" ", $added);
$added = $aradded[0];

$sql = "select cat from $tablecats where id='$subcat'";
$result = mysql_query($sql);
$resrow = mysql_fetch_row($result);
$cat = $resrow[0];
$pagetitle = "$sitetitle: PHP Scripts: $cat: $title";

if ($headerfile) include $headerfile;
$numrows = mysql_num_rows($result);
print "<form name='form1' method='get' action='search.php'><table width='$table_width' border='$table_border' cellspacing='$cellspacing' cellpadding='3' bordercolor='$table_border_color' bgcolor='$table_head_color' align='center'><tr><td width='50%'><font face='$fontname' size='-1'><font color='$table_head_textcolor'><a href='$main_site_url'><font color='$table_head_textcolor'>$sitetitle</font></a>: <a href='index.php'><font color='$table_head_textcolor'>PHP Scripts</font></a>: <a href='cat.php?id=$subcat'><font color='$table_head_textcolor'>$cat</font></a>: $title</font></font></td><td width='50%' align='center'><div align='right'><font face='$fontname' size='-1'><font color='$table_head_textcolor'>Search:</font> <input type='text' name='search' size='10'><input type='submit' value='Go!' name='submit'></font><font face='$fontname' size='-1'>&nbsp;&nbsp;<a href='add.php?id=$subcat'><font color='$table_head_textcolor'>Add Script</font></a>&nbsp;<a href='modify.php'><font color='$table_head_textcolor'>Modify Script</font></a></font></div></td></tr></table></form>";

$title .= " ".$version;
if (($ckAdminPass) && ($ckAdminPass==$adminpass)) $adminurl = " &nbsp;&nbsp;<font color='$table_head_textcolor'>[<a href='cat.php?admindelete=$id'><font color='$table_head_textcolor'>X</a></a>]</font>";
if ($homeurl) $links .= "<font size='-1' face='$fontname'>[<a href='out.php?id=$id&home=1' target='$id'><font color='$table_head_textcolor'>Homepage</font></a>]</font> ";
if ($dlurl) $links .= "<font size='-1' face='$fontname'><font color='$table_head_textcolor'>[<a href='out.php?id=$id&dl=1'><font color='$table_head_textcolor'>Download</font></a>]</font> ";
if ($demourl) $links .= "<font size='-1' face='$fontname'><font color='$table_head_textcolor'>[<a href='out.php?id=$id&demo=1' target='$id'><font color='$table_head_textcolor'>Demo</font></a>]</font> ";
$descr .= "<br><br><i>Price: $price - Added: $added - Hits: $hitsout</i>";
print "<table width='$table_width' border='$table_border' cellspacing='$cellspacing' cellpadding='$cellpadding' align='center' bordercolor='$table_border_color'>
	  <tr> 
	    <td bgcolor='$table_head_color'><font size='-1' face='$fontname'>&nbsp;&nbsp;&nbsp;<b><font color='$table_head_textcolor'>$title</font>$adminurl</b></font></td>
	    <td bgcolor='$table_head_color' align='right'><font size='-1' face='$fontname'>&nbsp;$links</font></td>
	  </tr>
	  <tr> 
	    <td bgcolor='$table_bgcolor' colspan='2'> 
	      <blockquote> 
	        <p><font size='-1' face='$fontname' color='$table_textcolor'>$descr</font></p>
	      </blockquote>
	    </td>
	  </tr>
</table><br><center><font face='$fontname'>Powered by <a href='http://nukedweb.memebot.com/' target='_psi'>PHP Script Index</a></center>";
if ($footerfile) include $footerfile;
?>