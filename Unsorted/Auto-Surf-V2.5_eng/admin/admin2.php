<?
include("header.inc.php");

$result20 = mysql_query("SELECT za, zb, zc, zd  FROM `demo_a_zahl`");
$myrow20 = mysql_fetch_row($result20);

$result = mysql_query("SELECT * FROM `demo_a_admin`");
$myrow = mysql_fetch_row($result);
$url = $myrow[0];
$seitenname = $myrow[1];
$email = $myrow[2];
$loginpoints = $myrow[3];
$reportpoints = $myrow[4];
$bannerklick = $myrow[5];
$startcredits = $myrow[6];
$jackpot = $myrow[7];
$refjackpot = $myrow[8];
$ratio = $myrow[9];
$time = $myrow[10];
$defaultbanner = $myrow[11];
$defaultbannerurl = $myrow[12];
$defaulturl = $myrow[13];
$emailmodi = $myrow[14];
$logout = $myrow[15];
$registriert = $myrow[16];
$referview = $myrow[17];
$frequency = $myrow[18];
$start = $myrow[19];
$tausch = $myrow[20];
?>
<?
include("../templates/admin-header.txt");
?>
<form method="post" action="admin.php">
<center><TABLE bgcolor="#FFFFFF" bordercolor="#000008" border="0" width="95%">
<TR>
  <TD width="80%" bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">URL the script is installed http:// without / at the end:</TD>
  <TD bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="urlneu" value="<? echo "$url"; ?>"></TD>
<TR>
  <TD width="80%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Title of the program:</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="seitennameneu" value="<? echo "$seitenname"; ?>"></TD>
</TR>
<TR>
  <TD width="80%" bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Start date::</TD>
  <TD bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="startenneu" value="<? echo "$start"; ?>"></TD>
</TR>
<TR>
  <TD width="80%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Your e-mail:</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="emailneu" value="<? echo "$email"; ?>"></TD>
</TR>
<TR>
  <TD width="80%" bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Points for login ( activity bonus ) random no between:</TD>
  <TD bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" size="4" name="logina" value="<? echo "$myrow20[0]"; ?>">&nbsp;&nbsp;to&nbsp;&nbsp;<input type="text" size="4" name="loginb" value="<? echo "$myrow20[1]"; ?>"></TD>
</TR>
<TR>
  <TD width="80%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Points for telling the admin about cheaters ( only if it was a real cheater ):</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="reportpointsneu" value="<? echo "$reportpoints"; ?>"></TD>
</TR>
<TR>
  <TD width="80%" bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Ref-points ( e.g. 0.3 = 30% ):</TD>
  <TD bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="referviewneu" value="<? echo "$referview"; ?>"></TD>
</TR>
<TR>
  <TD width="80%><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Point for bannerclick in the surfbar: Random points between</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" size="4" name="klicka" value="<? echo "$myrow20[2]"; ?>">&nbsp;&nbsp;to&nbsp;&nbsp;<input type="text" size="4" name="klickb" value="<? echo "$myrow20[3]"; ?>"></TD>
</TR>
<TR>
  <TD width="80%" bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Startcredits:</TD>
  <TD bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="startcreditsneu" value="<? echo "$startcredits"; ?>"></TD>
</TR>
<TR>
  <TD width=80%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">When a user reaches x points...:</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="jackportneu" value="<? echo "$jackpot"; ?>"></TD>
</TR>
<TR>
  <TD width="80%" bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">...the referrer gets x points:</TD>
  <TD bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="refjackportneu" value="<? echo "$myrow[8]"; ?>"></TD>
</TR>
<TR>
  <TD width="80%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Ratio ( e.g. for 10:8 input 0.8 ):</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="rationeu" value="<? echo "$ratio"; ?>"></TD>
</TR>
<TR>
  <TD width="80%" bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">how many bannerviews per visit:</TD>
  <TD  bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="tauschen" value="<? echo "$tausch"; ?>"></TD>
</TR>
<TR>
  <TD width="80%" ><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Time a website is displayed in the surfbar ( e.g. 20 = 20 seconds ):</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="timeneu" value="<? echo "$time"; ?>"></TD>
</TR>
<TR>
  <TD width="80%" bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">minimum no. of websites till same website is shown again:</TD>
  <TD  bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="frequencyneu" value="<? echo "$frequency"; ?>"></TD>
</TR>
<TR>
  <TD width="80%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Standard banner with http:// without / at the end (is diplayed when no other banners are declared ):</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="defaultbannerneu" value="<? echo "$defaultbanner"; ?>"></TD>
</TR>
<TR>
  <TD width="80%" bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Standard URL with http:// without / at the end ( where the standard banner shall link to ) :</TD>
  <TD  bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="defaultbannerurlneu" value="<? echo "$defaultbannerurl"; ?>"></TD>
</TR>
<TR>
  <TD width="80%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Standard website with http:// without / at the end( displayed, when no other sites are active ):</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="defaulturlneu" value="<? echo "$defaulturl"; ?>"></TD>
</TR>
<TR>
  <TD width="80%" bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Site to display after e-mail was sent with http:// without / at the end:</TD>
  <TD bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="emailmodineu" value="<? echo "$emailmodi"; ?>"></TD>
</TR>
<TR>
  <TD width="80%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Site to display after logout with http:// without / at the end:</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="logoutneu" value="<? echo "$logout"; ?>"></TD>
</TR>
<TR>
  <TD width="80%" bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Site to display after registration with http:// without / at the end:</TD>
  <TD bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="registriertneu" value="<? echo "$registriert"; ?>"></TD>
</TR>
</TABLE></TR><TR><TD width="50%"><input type="submit" value="Update"></TD></form>
<?
include("../templates/admin-footer.txt");
?>