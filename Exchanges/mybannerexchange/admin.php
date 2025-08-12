<?
include "./config.php";
if ($pass!=$adminpass){
	echo "<center><form name='form1' method='get' action='admin.php'>Enter Administrator's Password: <input type='text' name='pass'><input type=submit value='Submit'><br></form></center>";
	exit;
}

if ($makeexempt){
	$sql = "update $table set exempt='1' where id='$makeexempt'";
	$result = mysql_query($sql) or die("Failed: $sql");
}

if ($deleteid){
	$sql = "delete from $table where id='$deleteid'";
	$result = mysql_query($sql) or die("Failed: $sql");
}




if ($updateuser){
	if (!$email) $status = "An email address is required.<br>";
	if (!$password) $status .= "A password is required.<br>";
	if (!$status){
		$title = str_replace("\"", "%22", $title);
		$textad = str_replace("\"", "%22", $textad);
		$sql = "update $table set email='$email', password='$password', title='$title', url='$url', textad='$textad', tablebg='$tablebg', tablebdr='$tablebdr', tableclr='$tableclr', cat='$cat', exempt='$exempt' where id='$id'";
		$result = mysql_query($sql) or die("Failed: $sql");
	}
	#$editid = $id;
}


if ($editid){
	$sql = "select * from $table where id='$editid'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$resrow = mysql_fetch_row($result);
	$id = $resrow[0];
	$email = $resrow[1];
	$password = $resrow[2];
	$title = $resrow[3];
	$url = $resrow[4];
	$banner = $resrow[5];
	$textad = $resrow[6];
	$tablebg = $resrow[7];
	$tablebdr = $resrow[8];
	$tableclr = $resrow[9];
	$cat = $resrow[10];
	$myimpressions = $resrow[11];
	$myclicks = $resrow[12];
	$siteimpressions = $resrow[13];
	$siteclicks = $resrow[14];
	$created = $resrow[15];
	$lastclickin = $resrow[16];
	$exempt = $resrow[17];
	if ($exempt=="1") $chkexempt = " checked";
	if ($banner) $preview = "<a href='".$bx_url."out.php?id=$id' target='other'><img src='$banner' border='0'></a>";
	if ((!$banner) && ($textad)) $preview = "<table width='440' border='1' cellspacing='0' cellpadding='4' height='60' bordercolor='$tablebdr'><tr><td align='left' valign='top' bgcolor='$tablebg'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif' color='$tableclr'>$textad <a href='".$bx_url."out.php?id=$id'><font color='$tableclr'>$title</font></a></font></td></tr></table>";
	if (!$preview) $preview = "No banner is configured for this account.";
	print "<center>$preview</center>";
	print "<form name='form1' method='post' action='admin.php'> 
  <table width='95%' border='1' cellspacing='2' cellpadding='2' align='center' bordercolor='#000000'>
    <tr> 
      <td width='44%' align='left' bgcolor='#eeeeee'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>ID:</font></td>
      <td width='56%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>$id</font></td>
    </tr>
    <tr> 
      <td width='44%' align='left' bgcolor='#eeeeee'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Email:</font></td>
      <td width='56%'> <font size='-1' face='Verdana, Arial, Helvetica, sans-serif'> 
        <input type='text' name='email' value='$email'>
        </font></td>
    </tr>
    <tr> 
      <td width='44%' align='left' bgcolor='#eeeeee'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Password:</font></td>
      <td width='56%'><font face='Verdana, Arial, Helvetica, sans-serif' size='-1'> 
        <input type='text' name='password' value='$password'>
        </font></td>
    </tr>
    <tr> 
      <td width='44%' align='left' bgcolor='#eeeeee'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Title:</font></td>
      <td width='56%'> <font size='-1' face='Verdana, Arial, Helvetica, sans-serif'> 
        <input type='text' name='title' value=\"$title\">
        </font></td>
    </tr>
    <tr> 
      <td width='44%' align='left' bgcolor='#eeeeee'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Site 
        URL:</font></td>
      <td width='56%'> <font size='-1' face='Verdana, Arial, Helvetica, sans-serif'> 
        <input type='text' name='url' value='$url'>
        </font></td>
    </tr>
    <tr> 
      <td width='44%' align='left' bgcolor='#eeeeee'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Text 
        Ad:</font></td>
      <td width='56%'> <font size='-1' face='Verdana, Arial, Helvetica, sans-serif'> 
        <input type='text' name='textad' size='40' value=\"$textad\">
        </font></td>
    </tr>
    <tr> 
      <td width='44%' align='left' bgcolor='#eeeeee'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Table 
        BG Color:</font></td>
      <td width='56%'> <font size='-1' face='Verdana, Arial, Helvetica, sans-serif'> 
        <input type='text' name='tablebg' value='$tablebg'>
        </font></td>
    </tr>
    <tr> 
      <td width='44%' align='left' bgcolor='#eeeeee'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Table 
        Border Color:</font></td>
      <td width='56%'> <font size='-1' face='Verdana, Arial, Helvetica, sans-serif'> 
        <input type='text' name='tablebdr' value='$tablebdr'>
        </font></td>
    </tr>
    <tr> 
      <td width='44%' align='left' bgcolor='#eeeeee'><font face='Verdana, Arial, Helvetica, sans-serif' size='-1'>Table 
        Text Color:</font></td>
      <td width='56%'> <font size='-1' face='Verdana, Arial, Helvetica, sans-serif'> 
        <input type='text' name='tableclr' value='$tableclr'>
        </font></td>
    </tr>
    <tr> 
      <td width='44%' align='left' bgcolor='#eeeeee'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Category:</font></td>
      <td width='56%'><font face='Verdana, Arial, Helvetica, sans-serif' size='-1' color='<? print $tabletextcolor; ?>'> 
        <select name='cat'>".getcategoriesascombo($cat)."</select>
        </font></td>
    </tr>
    <tr> 
      <td width='44%' align='left' bgcolor='#eeeeee'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Impressions 
        On User's Site:</font></td>
      <td width='56%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>$siteimpressions</font></td>
    </tr>
    <tr> 
      <td width='44%' align='left' bgcolor='#eeeeee'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Clicks 
        From User's Site:</font></td>
      <td width='56%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>$siteclicks</font></td>
    </tr>
    <tr> 
      <td width='44%' align='left' bgcolor='#eeeeee'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Impressions 
        of User's Ad:</font></td>
      <td width='56%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>$myimpressions</font></td>
    </tr>
    <tr> 
      <td width='44%' align='left' bgcolor='#eeeeee'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Clicks 
        to User's Site:</font></td>
      <td width='56%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>$myclicks</font></td>
    </tr>
    <tr> 
      <td width='44%' align='left' bgcolor='#eeeeee'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Created:</font></td>
      <td width='56%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>$created</font></td>
    </tr>
    <tr> 
      <td width='44%' align='left' bgcolor='#eeeeee'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Last 
        Click In:</font></td>
      <td width='56%'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>$lastclickin</font></td>
    </tr>
    <tr> 
      <td width='44%' align='left' bgcolor='#eeeeee'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Make 
        Exempt from Time Limits: 
        <input type='checkbox' name='exempt' value='1' $chkexempt>
        </font></td>
      <td width='56%'> <font size='-1' face='Verdana, Arial, Helvetica, sans-serif'> 
        <input type='hidden' name='id' value='$id'>
        <input type='hidden' name='pass' value='$pass'>
        <input type='hidden' name='updateuser' value='1'>
        <input type='submit' value='Save Changes'>
        </font></td>
    </tr>
  </table>
	</form>";
	print "<center>[<a href='admin.php?pass=$pass'>Back to Users List</a>]</center>"; 
	exit;
}


$sql = "select id,email,title,url,cat,myimpressions,myclicks,lastclickin,exempt from $table";
$result = mysql_query($sql) or die("Failed: $sql");
$numrows = mysql_num_rows($result);
if ($status) print "<font color='red'>$status</font><br>";
print "Displaying $numrows users...<br>";

print "<table width='98%' border='1' cellspacing='2' cellpadding='2' bordercolor='#000000' align='center'>
  <tr bgcolor='#dddddd'> 
    <td width='21%' align='center'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Actions</font></td>
    <td width='20%' align='center'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Site 
      Title</font></td>
    <td width='9%' align='center'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Impressions</font></td>
    <td width='5%' align='center'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Clicks</font></td>
    <td width='24%' align='center'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Category</font></td>
    <td width='6%' align='center'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Exempt</font></td>
    <td width='15%' align='center'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Last 
      Click In</font></td>
  </tr>";


for($x=0;$x<$numrows;$x++){
	$resrow = mysql_fetch_row($result);
	$id = $resrow[0];
	$email = $resrow[1];
	$title = $resrow[2];
	$url = $resrow[3];
	$cat = $resrow[4];
	$impressions = $resrow[5];
	$clicks = $resrow[6];
	$lastclickin = $resrow[7];
	$exempt = "";
	$exempt = $resrow[8];
	if ($exempt=="1") $exempt = "Yes";
	if ($exempt!="Yes") $exempt = "";
	print "<tr bgcolor='#eeeeee'> 
    <td width='21%' bgcolor='#eeeeee' align='center'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>[<a href='$url' target='$url'>URL</a>] 
      [<a href='mailto:$email'>Email</a>] [<a href='admin.php?pass=$pass&editid=$id'>Edit</a>] 
      <br>[<a href='admin.php?pass=$pass&deleteid=$id'>Delete</a>] [<a href='admin.php?pass=$pass&makeexempt=$id'>Make 
      Exempt</a>]</font></td>
    <td width='20%' align='center'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>$title</font></td>
    <td width='9%' align='center'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>$impressions</font></td>
    <td width='5%' align='center'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>$clicks</font></td>
    <td width='24%' align='center'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>$cat</font></td>
    <td width='6%' align='center'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>$exempt</font></td>
    <td width='15%' align='center'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>$lastclickin</font></td>
  </tr>";
}
print "</table>";

?>