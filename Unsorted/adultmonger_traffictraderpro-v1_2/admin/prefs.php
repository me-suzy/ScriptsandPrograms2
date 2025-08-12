<?php
require_once("admin_max_settings.php"); require_once("db.php");

if (check_user2()) { if (isset($save)){ $furl = urlencode($furl); db_query("update ttp_settings set furl='$furl', nemail='$nemail', dntf=$dntf, mhpd=$mhpd, duniq=$duniq, dprox=$dprox, dprod=$dprod, emailw=$emailw, fctg=$fctg where 1");
}

?>

<html><head><title>Preferences</title>
<STYLE type=text/css>.title {FONT: 10pt Verdana, Helvetica, sans-serif}</STYLE>
<STYLE type=text/css>.main {FONT: 8pt Verdana, Helvetica, sans-serif; color:white}</STYLE>
<STYLE type=text/css>.small {FONT: 7pt Verdana, Helvetica, sans-serif; color:white}</STYLE>
<STYLE type=text/css>
A:link {COLOR: #000000; TEXT-DECORATION: underline}
A:visited {COLOR: #000000; TEXT-DECORATION: underline}
A:active {COLOR: #000000; TEXT-DECORATION: underline}
A:hover {COLOR: #0000FF; TEXT-DECORATION: none}
</STYLE></head>

<body bgcolor=#000000 background=../assets/am-interfacev1_r6_c4.jpg leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>
<table width=726 height=280 border=0 cellpadding=0 cellspacing=0 background=../assets/am-interfacev1_r6_c4.jpg>
  <tr>
    <td valign=middle align=center class=main>

   <form method=POST>
   <table border="0" cellpadding="3" cellspacing="1">
   <tr><td class=main align=left bgcolor="#425B7E" colspan=2><b>Default TGP Preferences</b></td></tr>
<?php
$stat_q = db_query("select * from ttp_settings limit 1");
while ($myrow = mysql_fetch_array($stat_q)){
$a1 = $myrow["duniq"];
$a2 = $myrow["dprox"];
$a3 = $myrow["dprod"];
$a4 = $myrow["emailw"];
$a5 = $myrow["fctg"];

echo"    <tr><td class=main align=right bgcolor=\"#425B7E\">Front Page URL:&nbsp;</td><td class=main align=left bgcolor=\"#557AB1\"><input type=text name=furl size=60 value=\"".urldecode($myrow["furl"])."\"><br>your TGP's front page</td></tr>\n";
echo"    <tr><td class=main align=right bgcolor=\"#425B7E\">Notify on New Trades (your email):&nbsp;</td><td class=main align=left bgcolor=\"#557AB1\"><input type=text name=nemail size=20 value=\"".$myrow["nemail"]."\"> leave blank to turn off</td></tr>\n";
echo"    <tr><td class=main align=right bgcolor=\"#425B7E\">Default New Trade Force:&nbsp;</td><td class=main align=left bgcolor=\"#557AB1\"><input type=text name=dntf size=20 value=\"".$myrow["dntf"]."\"> default daily force for new trades</td></tr>\n";
echo"    <tr><td class=main align=right bgcolor=\"#425B7E\">Minimum Hits per Day:&nbsp;</td><td class=main align=left bgcolor=\"#557AB1\"><input type=text name=mhpd size=20 value=\"".$myrow["mhpd"]."\"> site will be deleted if less raw hits</td></tr>\n";
echo"    <tr><td class=main align=right bgcolor=\"#425B7E\">Delete if Uniques are:&nbsp;</td><td class=main align=left bgcolor=\"#557AB1\"><select name=duniq><option value=90"; if($a1==90) echo " selected"; echo ">< 90%<option value=80";
if($a1==80) echo " selected"; echo ">< 80%<option value=70"; if($a1==70) echo " selected"; echo ">< 70%<option value=60";
if($a1==60) echo " selected"; echo ">< 60%<option value=50"; if($a1==50) echo " selected"; echo ">< 50%<option value=40";
if($a1==40) echo " selected"; echo ">< 40%<option value=30"; if($a1==30) echo " selected"; echo ">< 30%<option value=20";
if($a1==20) echo " selected"; echo ">< 20%<option value=10"; if($a1==10) echo " selected"; echo ">< 10%<option value=5"; if($a1==5) echo " selected"; echo ">< 5%</select></td></tr>\n";
echo"    <tr><td class=main align=right bgcolor=\"#425B7E\">Delete if Proxies are:&nbsp;</td><td class=main align=left bgcolor=\"#557AB1\"><select name=dprox><option value=90"; if($a1==90) echo " selected"; echo ">> 90%<option value=80";
if($a2==80) echo " selected"; echo ">> 80%<option value=70"; if($a2==70) echo " selected"; echo ">> 70%<option value=60";
if($a2==60) echo " selected"; echo ">> 60%<option value=50"; if($a2==50) echo " selected"; echo ">> 50%<option value=40";
if($a2==40) echo " selected"; echo ">> 40%<option value=30"; if($a2==30) echo " selected"; echo ">> 30%<option value=20";
if($a2==20) echo " selected"; echo ">> 20%<option value=10"; if($a2==10) echo " selected"; echo ">> 10%<option value=5"; if($a2==5) echo " selected"; echo ">> 5%</select></td></tr>\n";
echo"    <tr><td class=main align=right bgcolor=\"#425B7E\">Delete if Productivity is:&nbsp;</td><td class=main align=left bgcolor=\"#557AB1\"><select name=dprod><<option value=90"; if($a1==90) echo " selected"; echo ">< 90%<option value=80";
if($a3==80) echo " selected"; echo ">< 80%<option value=70"; if($a3==70) echo " selected"; echo ">< 70%<option value=60";
if($a3==60) echo " selected"; echo ">< 60%<option value=50"; if($a3==50) echo " selected"; echo ">< 50%<option value=40";
if($a3==40) echo " selected"; echo ">< 40%<option value=30"; if($a3==30) echo " selected"; echo ">< 30%<option value=20";
if($a3==20) echo " selected"; echo ">< 20%<option value=10"; if($a3==10) echo " selected"; echo ">< 10%<option value=5"; if($a3==5) echo " selected"; echo ">< 5%</select></td></tr>\n";
echo"    <tr><td class=main align=right bgcolor=\"#425B7E\">Email Webmaster if Deleted:&nbsp;</td><td class=main align=left bgcolor=\"#557AB1\"><select name=emailw><option value=1"; if($a4==1) echo " selected"; echo ">Yes<option value=0"; if($a4==0) echo " selected"; echo ">No</select></td></tr>\n";
echo"    <tr><td class=main align=right bgcolor=\"#425B7E\">First Click to Gallery:&nbsp;</td><td class=main align=left bgcolor=\"#557AB1\"><select name=fctg><option value=1"; if($a5==1) echo " selected"; echo ">Yes<option value=0"; if($a5==0) echo " selected"; echo ">No</select></td></tr>\n";
echo"    <tr><td class=main align=right bgcolor=\"#425B7E\" colspan=2><input type=Submit value=\"Save Settings\" name=save></td></tr>\n";
}
?>

    </table>
</form>
    </td>
  </tr></table>
</body>
</html>

<?php
db_close();
exit();
} else { db_close();
header("Location: login.htm\n\n");
exit();
}
?>
