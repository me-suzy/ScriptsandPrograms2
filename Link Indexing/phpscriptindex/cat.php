<?
include "./config.php";
if (($ckAdminPass) && ($ckAdminPass==$adminpass) && ($admindelete)){
	$sql = "select subcat from $tablescripts where id='$admindelete'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$numrows = mysql_num_rows($result);
	if ($numrows!=0){
		$resrow = mysql_fetch_row($result);
		$catid = $resrow[0];
		$sql = "delete from $tablescripts where id='$admindelete'";
		$result = mysql_query($sql) or die("Failed: $sql");
		$sql = "select ct from $tablecats where id='$catid'";
		$result = mysql_query($sql) or die("Failed: $sql");
		$resrow = mysql_fetch_row($result);
		$ct = $resrow[0];
		$ct--;
		$sql = "update $tablecats set ct='$ct' where id='$catid'";
		$result = mysql_query($sql) or die("Failed: $sql");
		Header("Location: index.php");
		exit;
	}
}

$sql = "select cat from $tablecats where id='$id'";
$result = mysql_query($sql);
$resrow = mysql_fetch_row($result);
$cat = $resrow[0];
$pagetitle = "$sitetitle: PHP Scripts: $cat";
if ($headerfile) include $headerfile;
$sql = "select id,title,homeurl,dlurl,demourl,descr,price,version,hitsout,added from $tablescripts where subcat='$id' order by hitsin desc";
$result = mysql_query($sql) or die("Failed: $sql");
$numrows = mysql_num_rows($result);
$catid = $id;
print "<form name='form1' method='get' action='search.php'><table width='$table_width' border='$table_border' cellspacing='$cellspacing' cellpadding='3' bordercolor='$table_border_color' bgcolor='$table_head_color' align='center'><tr><td width='60%'><font face='$fontname' size='-1'><font color='$table_head_textcolor'><a href='$main_site_url'><font color='$table_head_textcolor'>$sitetitle</font></a>: <a href='index.php'><font color='$table_head_textcolor'>PHP Scripts</font></a>: $cat</font></font></td><td width='40%' align='right'><font face='$fontname' size='-1'><font color='$table_head_textcolor'><font face='$fontname' size='-1'>Search: <font color='$table_head_textcolor'><input type='text' name='search' size='10'><input type='submit' value='Go!' name='submit'></font></font>&nbsp;<a href='add.php?id=$catid'><font color='$table_head_textcolor'>Add Script</font></a>&nbsp;<a href='modify.php'><font color='$table_head_textcolor'>Modify Script</font></a></font></font></td></tr></table></form>";
If ($numrows==0) print "<blockquote><font face='$fontname' color='$table_textcolor'>No scripts have been submitted to this category.</font></blockquote>";
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
	if (($ckAdminPass) && ($ckAdminPass==$adminpass)) $adminurl = " &nbsp;&nbsp;<font color='$table_head_textcolor'>[</font><a href='cat.php?admindelete=$id'><font color='$table_head_textcolor'>X</a></a>]";
	$links = "";
	if ($homeurl) $links .= "<font size='-1' face='$fontname'><font color='$table_head_textcolor'>[<a href='out.php?id=$id&home=1' target='$id'><font color='$table_head_textcolor'>Homepage</font></a>]</font> ";
	if ($dlurl) $links .= "<font size='-1' face='$fontname'><font color='$table_head_textcolor'>[<a href='out.php?id=$id&dl=1'><font color='$table_head_textcolor'>Download</font></a>]</font> ";
	if ($demourl) $links .= "<font size='-1' face='$fontname'><font color='$table_head_textcolor'>[<a href='out.php?id=$id&demo=1' target='$id'><font color='white'>Demo</font></a>]</font> ";
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
print "<form name='form1' method='get' action='search.php'><table width='$table_width' border='$table_border' cellspacing='$cellspacing' cellpadding='3' bordercolor='$table_border_color' bgcolor='$table_head_color' align='center'><tr><td width='60%'><font face='$fontname' size='-1'><font color='$table_head_textcolor'><a href='$main_site_url'><font color='$table_head_textcolor'>$sitetitle</font></a>: <a href='index.php'><font color='$table_head_textcolor'>PHP Scripts</font></a>: $cat</font></font></td><td width='40%' align='right'><font face='$fontname' size='-1'><font color='$table_head_textcolor'><font face='$fontname' size='-1'>Search: <font color='$table_head_textcolor'><input type='text' name='search' size='10'><input type='submit' value='Go!' name='submit'></font></font>&nbsp;<a href='add.php?id=$catid'><font color='$table_head_textcolor'>Add Script</font></a>&nbsp;<a href='modify.php'><font color='$table_head_textcolor'>Modify Script</font></a></font></font></td></tr></table></form>";;
print "<center>Powered by <a href='http://nukedweb.memebot.com/' target='_nukedweb'>PHP Script Index</a></center>";
if ($footerfile) include $footerfile;
?>