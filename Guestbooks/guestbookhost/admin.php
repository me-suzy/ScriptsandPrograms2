<?
include "./config.php";

if ($pass!=$adminpass){
	echo "<center><form name='form1' method='get'>Enter Administrator's Password: <input type='text' name='pass'><input type=submit value='Submit'><br></form></center>";
	exit;
}

if ($update && $id){
	$sql = "update $table set email='$email', password='$password',sitetitle='$sitetitle', siteurl='$siteurl', headtext='$headtext', bgcolor='$bgcolor', textcolor='$textcolor', linkcolor='$linkcolor', entries='$entries', pageviews='$pageviews', uniquehits='$uniquehits', created='$created' where id='$id'";
	$result = mysql_query($sql) or die("Failed: $sql");
	Header("Location: admin.php?pass=$pass");
	exit;
}


if ($editid){
	$sql = "select * from $table where id='$editid'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$resrow = mysql_fetch_row($result);
	$id = $resrow[0];
	$email = $resrow[1];
	$password = $resrow[2];
	$sitetitle = $resrow[3];
	$siteurl = $resrow[4];
	$headtext = $resrow[5];
	$bgcolor = $resrow[6];
	$textcolor = $resrow[7];
	$linkcolor = $resrow[8];
	$uniquehits = $resrow[9];
	$pageviews = $resrow[10];
	$entries = $resrow[11];
	$created = $resrow[12];
	print "<form name='form1' method='post' action='admin.php'><table width='95%' border='1' cellspacing='2' cellpadding='2' align='center' bordercolor='#000000'><tr><td bgcolor='#eeeeee'>ID:</td><td>$id <input type='hidden' name='pass' value='$pass'><input type='hidden' name='update' value='1'><input type='hidden' name='id' value='$id'></td></tr><tr> <td bgcolor='#eeeeee'>Email:</td><td> <input type='text' name='email' value='$email'></td></tr><tr><td bgcolor='#eeeeee'>Password:</td><td><input type='text' name='password' value='$password'></td></tr><tr><td bgcolor='#eeeeee'>Site Title:</td><td><input type='text' name='sitetitle' value=\"$sitetitle\"></td></tr><tr><td bgcolor='#eeeeee'>Site URL:</td><td><input type='text' name='siteurl' value='$siteurl'></td></tr><tr> <td bgcolor='#eeeeee'>Head Text:</td><td> <textarea name='headtext' rows='3'>$headtext</textarea></td></tr><tr> <td bgcolor='#eeeeee'>Background Color</td><td> <input type='text' name='bgcolor' value='$bgcolor'></td></tr><tr> <td bgcolor='#eeeeee'>Text Color:</td><td><input type='text' name='textcolor' value='$textcolor'></td></tr><tr><td bgcolor='#eeeeee'>Link Color:</td><td> <input type='text' name='linkcolor' value='$linkcolor'></td> </tr> <tr> <td bgcolor='#eeeeee'>Number of Entries:</td><td><input type='text' name='entries' value='$entries'></td> </tr> <tr> <td bgcolor='#eeeeee'>Pageviews:</td><td><input type='text' name='pageviews' value='$pageviews'></td> </tr> <tr> <td bgcolor='#eeeeee'>Unique Hits:</td><td><input type='text' name='uniquehits' value='$uniquehits'></td> </tr> <tr> <td bgcolor='#eeeeee'>Created:</td><td>  <input type='text' name='created' value='$created'></td> </tr> <tr> <td bgcolor='#eeeeee'>&nbsp;</td><td>  <input type='submit' value='Save Changes'></td> </tr>  </table></form>";
	exit;
}

if ($deleteid){
	$sql = "delete from $table where id='$deleteid'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$sql = "delete from $msgstable where owner='$deleteid'";
	$result = mysql_query($sql) or die("Failed: $sql");
	Header("Location: admin.php?pass=$pass");
	exit;
}

$sql = "select id,email,sitetitle,siteurl,uniquehits,pageviews,entries,created from $table order by created desc";
$result = mysql_query($sql) or die("Failed: $sql");
$numrows = mysql_num_rows($result);
print "<font face='Verdana, Arial, Helvetica, sans-serif' size='-1'>Displaying $numrows users:</font><br>";
print "<table width='98%' border='1' cellspacing='2' cellpadding='2' bordercolor='#000000' align='center'><tr bgcolor='#eeeeee'><td width='25%' bgcolor='#eeeeee' align='center'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Actions</font></td><td width='30%' align='center'><font face='Verdana, Arial, Helvetica, sans-serif' size='-1'>Site Title </font></td><td width='7%' align='center'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Posts</font></td><td width='9%' align='center'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Pageviews</font></td><td width='9%' align='center'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Unique Hits</font></td><td width='20%' align='center'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Created</font></td></tr>";
for($x=0;$x<$numrows;$x++){
	$resrow = mysql_fetch_row($result);
	$id = $resrow[0];
	$email = $resrow[1];
	$sitetitle = $resrow[2];
	$siteurl = $resrow[3];
	$uniquehits = $resrow[4];
	$pageviews = $resrow[5];
	$entries = $resrow[6];
	$created = $resrow[7];
	print "<tr><td width='25%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'><center>[<a href='mailto:$email'>Email</a>] [<a href='admin.php?pass=$pass&editid=$id'>Edit</a>] [<a href='admin.php?pass=$pass&deleteid=$id'>Delete</a>] [<a href=\"#\" onClick=\"javascript:window.open('adminmsgs.php?pass=$pass&id=$id','pop_gb','height=".$gb_popwin_height.",width=".$gb_popwin_width.",top=0,left=0,resizable=no,scrollbars=yes');\">Msgs</a>]</center></font></td><td width='30%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>$sitetitle</font></td><td width='7%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>$entries</font></td><td width='9%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>$pageviews</font></td><td width='9%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>$uniquehits</font></td><td width='19%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>$created</font></td></tr>";
}
print "</table><center><a href='adminbanners.php?pass=$pass'>Add/Edit/Remove Banner Ads</a></center>";
?>
