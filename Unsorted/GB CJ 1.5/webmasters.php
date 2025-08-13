<?
include('config.php');
include('calculate.php');
include('skin.php');
?>
<html>
<head>
<title><?echo $V8ca05b67?></title>
</head>
<body>
<div align=center>
<table cellspacing=0 width=95%>
<tr>
<th width=200 align=left>
<?
if($displaystats)
{
	$V435ed7e9='datafiles/daily.dat';
if(file_exists($V435ed7e9))
	{
 $V3d801aa5='';
if($V633de4b0 = @fopen($V435ed7e9,'r'))
 {
 flock($V633de4b0,1);
$V3d801aa5 = fread($V633de4b0,filesize($V435ed7e9));
flock($V633de4b0,3);
fclose($V633de4b0);
}
$Vbea79186=array('');
$Vbea79186=split("\r\n",$V3d801aa5);
$V78078313=array('');
$V78078313=split('\|',$Vbea79186[sizeof($Vbea79186)-2]);
$V73af06a7=$Veb356d27=0;
for($V865c0c0b=0;$V865c0c0b<(sizeof($Vbea79186)-1);$V865c0c0b++)
 {
 $V4124bc0a=array('');
$V4124bc0a=split("\|",$Vbea79186[$V865c0c0b]);
$V73af06a7+=$V4124bc0a[1];
$Veb356d27+=$V4124bc0a[2];
}
echo ('<b>Stats for '.$V78078313[0].'<br>'.$V78078313[1].' Visits - '.$V78078313[2].' Out</b>');
}
}
?>
</th>
<td align=center>
<font size=4><a target=_blank href=./>Main page</a> - GB CJ <?echo $V2af72f10 ?> - <a target=_blank href=http://www.gbscript.com>Download</a></font>
</td>
<th width=200 align=right>
<?
if($displaystats)
{
	echo ('<b>Traffic Summary<br>'.$V73af06a7.' Visits - '.$Veb356d27.' Out</b>');
}
?>
</th></tr>
</table>
<br>
<?
 
if($QUERY_STRING=='newaccount')
{
	$mem=str_replace('.','',$V14c4b06b);
$mem=str_replace('/','',$mem);
$Vafbe94cd=$V14c4b06b;
$Vcb5e100e=0;
$V435ed7e9='memberfiles/'.$mem.'.dat';
if(file_exists($V435ed7e9)) { $V6e2baaf3.='That domain is allready in use'; $Vcb5e100e = 1; }
if(!$V14c4b06b) { $V6e2baaf3 .='You must enter a domain<br>'; $Vcb5e100e = 1; }
if((ereg(' ',$V14c4b06b))||(ereg(':',$V14c4b06b))) { $V6e2baaf3 .='Invalid character in domain'; $Vcb5e100e = 1; }
if(!ereg("\@",$V9b921424)) { $V6e2baaf3 .='You have entered an invalid e-mail address'; $Vcb5e100e = 1 ; }
if(!$Va722c63d) { $V6e2baaf3 .='You must enter a password'; $Vcb5e100e =1 ; }
if(!$Vc1572d05) { $V6e2baaf3 .='You must enter your password in BOTH fields'; $Vcb5e100e = 1 ; }
if($Va722c63d != $Vc1572d05) { $V6e2baaf3 .='The passwords you entered do not match'; $Vcb5e100e = 1 ; }
if((!ereg("http:\/\/",$Vdc85be65))&&(!ereg("php",$Vdc85be65))) { $V6e2baaf3 .='The URL to send traffic to MUST include http://'; $Vcb5e100e = 1; }
else
	{
 if ($V4b1a8625) { $Ve9831589=join('',@file($Vdc85be65)); }
}
$V435ed7e9='datafiles/blacklist.dat';
if($V633de4b0 = @fopen($V435ed7e9,'r'))
	{
 flock($V633de4b0,1);
fseek($V633de4b0,0);
$Va0bc9791 = fread($V633de4b0,filesize($V435ed7e9));
flock($V633de4b0,3);
fclose($V633de4b0);
}
$V59fbc8df = $REMOTE_ADDR;
$V14511f2f = array();
$V14511f2f = split("\r\n",$Va0bc9791);
for($V865c0c0b=0;$V865c0c0b<sizeof($V14511f2f);$V865c0c0b++)
	{
 if ($V14511f2f[$V865c0c0b])
 {
 if ($V59fbc8df == $V14511f2f[$V865c0c0b]) { $Vcb5e100e = 1; $V6e2baaf3 .="The IP address $V59fbc8df has been blacklisted from this site."; }
if ($Vdc85be65 == $V14511f2f[$V865c0c0b]) { $Vcb5e100e = 1; $V6e2baaf3 .="The URL $Vdc85be65 has been blacklisted from this site."; }
if ($V14c4b06b == $V14511f2f[$V865c0c0b]) { $Vcb5e100e = 1; $V6e2baaf3 .="The domain $V14c4b06b has been blacklisted from this site."; }
if ($V4b1a8625) { if (eregi($V14511f2f[$V865c0c0b],$Ve9831589)) { $Vcb5e100e = 1; $V6e2baaf3 .="Your page has the blacklisted word [$V14511f2f[$V865c0c0b]]."; } }
}
}
if ($Vcb5e100e == 1)
	{
?>
<table width=95%>
<tr><td align=center>
OOPS!<br>
<?echo $V6e2baaf3?><br><br>
Please hit your back button and fix these errors<br><br>
If you continue to have problems signing up please email me at <a href="mailto:<?echo $V0c83f57c?>?subject=Problem signing up to trade with <?echo $V8ca05b67?>"><?echo $V0c83f57c?></a> or icq me at <?echo $icq?>.
</td></tr>
</table>
<?
 exit;
}
$V99c2db49=ereg_replace('!','',strtolower($V99c2db49));
$Vc5e7dfaf=date("m.d.Y");
$V06cde2a7=date("g:i A");
if($V633de4b0=@fopen('memberfiles/'.$mem.'.dat','w'))
	{
 flock($V633de4b0,2);
fseek($V633de4b0,0);
fputs($V633de4b0,time().'|0|'.$V9b921424.'|'.$Ve02a798b.'|'.$Va722c63d.'|0|0|0|0|0|0|'.$V5ca52301.'|'.$V8dfc6579.'|no reset|'.$V94a7c36b.'|'.$Vdc85be65.'||'.$Vafbe94cd.'|new|0|0|0|'.$V99c2db49.'||'.$popups.'||||||||||||||');
flock($V633de4b0,3);
fclose($V633de4b0);
}
@chmod('memberfiles/'.$mem.'.dat',0666);
?>
<table width=95%>
<tr><td align=center>
Heres The Linking Information You Need</font>
</td></tr>
<tr><td align=center>
Thanks for signing up to trade quality traffic with <?echo $V8ca05b67?>.<br><br>
Username: <?echo $mem?><br>
Password: <?echo $Va722c63d?><br>
URL to send hits to:<br><a href="<?echo $V861ce498; if($V00dd8e4a) {echo ('?id='.$mem);}?>"><font size=+1><?echo $V861ce498; if($V00dd8e4a) {echo ('?id='.$mem);}?></font></a><br><br>
You may start sending and recieving hits immediately. If you have any questions just drop me a email at
<a href="mailto:<?echo $V0c83f57c?>?subject=Question about trade with <?echo $V8ca05b67?>"><?echo $V0c83f57c?></a> or icq me, my icq uin is <?echo $icq?>.
</td></tr>
</table>
<br>
<?
	include("datafiles/rules2.dat");
}
 
else if($QUERY_STRING == 'displaystats')
{
	if(!file_exists('memberfiles/'.$V53d670af.'.dat'))
	{
?>
<table width=95%>
<tr><td align=center>
OOPS!<br>
That user name does not exist in the database.<br><br>
Please hit your back button and try again<br><br>
If you continue to have problems email me at <a href="mailto:<?echo $V0c83f57c?>?subject=Problem checking stats on <?echo $V8ca05b67?>"><?echo $V0c83f57c?></a> or icq me at <?echo $icq?> and be sure to include the name of your site.
</td></tr>
</table>
<?
 exit;
}
$V435ed7e9 = 'memberfiles/'.$V53d670af.'.dat';
if($V633de4b0 = @fopen($V435ed7e9,'r'))
	{
 flock($V633de4b0,1);
fseek($V633de4b0,0);
$V32762e5d = fread($V633de4b0,filesize($V435ed7e9));
fclose($V633de4b0);
}
$V078171f8 = array();
$V078171f8 = split("\|",$V32762e5d);
$Vf8bff26e = $V53d670af;
$Ve449f731=$Vc040bf1d=$V013dff02='';
if($V078171f8[26]>0) { $Ve449f731=round($V078171f8[20]*100/$V078171f8[26]); }
if($V078171f8[20]>0){ $Vc040bf1d=round(100*$V078171f8[28]/$V078171f8[20]); }
$V41f302d0=($V078171f8[7]+$V078171f8[20])-($V078171f8[27]+$V078171f8[28]);
if($V41f302d0>0) { $V013dff02=round(100*($V078171f8[36]+$V078171f8[37])/$V41f302d0); }
if($V078171f8[4]!=$V5f4dcc3b)
	{
?>
<table width=95%>
<tr><td align=center>
OOPS!<br>
The Password you entered is incorrect.<br><br>
Please hit your back button and try again<br><br>
If you continue to have problems email me at <a href="mailto:<?echo $V0c83f57c?>?subject=Problem checking stats on <?echo $V8ca05b67?>"><?echo $V0c83f57c?></a> or icq me at <?echo $icq?> and be sure to include the name of your site.
</td></tr>
</table>
<?
 exit;
}
if($V078171f8[4] == '')
	{
?>
<table width=95%>
<tr><td align=center>
OOPS!<br>
That function has been disabled, sorry<br><br>
You can email me at <a href='mailto:<?echo $V0c83f57c?>?subject=Problem checking stats on <?echo $V8ca05b67?>'><?echo $V0c83f57c?></a> or icq me at <?echo $icq?> and be sure to include the name of your site and I'll get back to you with that information.
</td></tr>
</table>
<?
 exit;
}
?>
<table cellspacing=0 width=95%>
<tr><td colspan=2 align=center>
<font size=+1><b>Member Information For '<?echo $V53d670af?>'</b></font>
</td></tr>
<tr><th valign=top>
<table cellspacing=1 width=100%>
<tr><th colspan=2>Member Information</th></tr>
<tr><td>Username</td><td><?echo $V53d670af?></td></tr>
<tr><td>Password</td><td><?echo $V078171f8[4]?></td></tr>
<tr><td>Name for Top</td><td><?echo $V078171f8[22]?></td></tr>
<tr><td>Pop-ups</td><td><?echo $V078171f8[24]?></td></tr>
<tr><td>Email</td><td><?echo $V078171f8[2]?></td></tr>
<tr><td>ICQ</td><td><?echo $V078171f8[3]?></td></tr>
<tr><td>URL</td><td><?echo $V078171f8[15]?></td></tr>
<tr><td>Last Hit In</td><td><?echo date('d M - H:i',$V078171f8[0])?></td></tr>
</table>
<table cellspacing=1 width=100%>
<tr><th colspan=2>Last Hour Stats</th></tr>
<tr><td>Hits In</td><td><?echo $V078171f8[5]?></td></tr>
<tr><td>Raw In</td><td><?echo $V078171f8[25]?></td></tr>
<tr><td>Hits Out</td><td><?echo $V078171f8[9]?></td></tr>
<tr><td>Clicks</td><td><?echo $V078171f8[7]?></td></tr>
<tr><th colspan=2>Stats for 24 Hours</th></tr>
<tr><td>Hits In</td><td><?echo $V078171f8[19]?></td></tr>
<tr><td>Raw In</td><td><?echo $V078171f8[26]?></td></tr>
<tr><td>Hits Out</td><td><?echo $V078171f8[21]?></td></tr>
<tr><td>Clicks</td><td><?echo $V078171f8[20]?></td></tr>
<tr><td>Bad Clicks</td><td><?echo $Vc040bf1d?>%</td></tr>
<tr><td>Productivity</small></td><td><?echo $Ve449f731?>%</td></tr>
<tr><td>Bad Languages</small></td><td><?echo $V013dff02?>%</td></tr>
<tr><th colspan=2>Total Stats</th></tr>
<tr><td>Hits In</td><td><?echo $V078171f8[6]?></td></tr>
<tr><td>Hits Out</td><td><?echo $V078171f8[10]?></td></tr>
<tr><td>Clicks</td><td><?echo $V078171f8[8]?></td></tr>
</table>
</th><th align=center valign=top>
<table cellspacing=0 cellpadding=2>
<?
	F68a59adb($V53d670af);
?>
</table>
</th></tr>
<tr><th colspan=2>
If you need any of these info's changed just email me at <a href='mailto:<?echo $V0c83f57c?>?subject=Change the infos for <?echo $V53d670af?> on <?echo $V8ca05b67?>'><?echo $V0c83f57c?></a> or icq me, my icq uin is <?echo $icq?>
</th></tr>
</table>
<br>
<?
}
 
else
{
	$V435ed7e9='datafiles/ad.dat';
include($V435ed7e9);
$V90b47bf4=filemtime($V435ed7e9);
if (time()-$V90b47bf4>604800)
	{
 $V4f2afc9c=@join('',@file('http://gbscript.com/go/ad.php?cat='.$Vf1cb89ef));
if(!ereg('gb cj',$V4f2afc9c))
 {
 $V4f2afc9c='';
}
if($V633de4b0=@fopen($V435ed7e9,'w'))
 {
 flock($V633de4b0,2);
fputs($V633de4b0,$V4f2afc9c);
flock($V633de4b0,3);
fclose($V633de4b0);
}
else { exit; }
}
if($Vf7d5225e)
	{
?>
<table cellspacing=1 cellpadding=3 width=95%>
<tr><th>
Trading traffic with <?echo $V8ca05b67?> - General GB CJ rules & recommendations
</th></tr>
<tr><td>
<center><font size=+1>How you can get a lot of <?echo $Vf1cb89ef?> traffic from me</font></center><br>
GB CJ script rate traders by effectivity.
You will get so much outs, as much clicks will generate your traffic.
For maximum trade I recommend to use no more 1 exit pop-up without any tricks, dialers etc.
Then you will get much more and much better traffic from me.
<br><br>
Script trades in real time. You will get the traffic back immediately, all unique so you wont get the same surfer twice.
<br><br>
<center>
If you have any questions, just email me at <a href='mailto:<?echo $V0c83f57c?>?subject=Trade with <?echo $V8ca05b67?>'><?echo $V0c83f57c?></a> or icq me at <?echo $icq?>.<br><br>
URL to send hits to: <a href='<?echo $V861ce498?>'><font size=+1><?echo $V861ce498?></font></a>
</center>
</td></tr>
</table>
<br>
<?
	}
include('datafiles/rules.dat');
?>
</center>
<form method=POST action=webmasters.php?newaccount>
<table cellspacing=1 width=450>
<tr>
	<th colspan=2>Submit New Site</th>
</tr><tr>
	<td align=right>Your domain (without http://www):</td>
	<td><input type=text name=V14c4b06b size=30 maxlength=30></td>
</tr><tr>
	<td align=right>URL of your page to send traffic to:</td>
	<td><input type=text name=Vdc85be65 size=30 value='http://'></td>
</tr><tr>
	<td align=right>Site name for toplist:</td>
	<td><input type=text name=V99c2db49 size=15 maxlength=15></td>
</tr>
<?
	if($Vd519d889)
	{
?>
<tr>
	<td align=right>Pop-ups:</td>
	<td>
 <select name=popups>
 <option value=0>0</option>
 <option value=1 selected>1</option>
 <option value=2>2</option>
 </select>
	</td>
</tr>
<?
	}
?>
<tr>
	<td align=right>Your Email:</td>
	<td><input type=text name=V9b921424 size=30></td>
</tr><tr>
	<td align=right>Your ICQ #:</td>
	<td><input type=text name=Ve02a798b size=12 maxlength=12></td>
</tr><tr>
	<td align=right>Enter a password:</td>
	<td><input type=password name=Va722c63d size=12 maxlength=12></td>
</tr><tr>
	<td align=right>Re-type your password:</td>
	<td><input type=password name=Vc1572d05 size=12 maxlength=12></td>
</tr><tr>
	<td colspan=2 align=center><input type=submit name=newaccount value='Start Trading'></td>
</tr>
</table>
</form>
<form method=POST action=webmasters.php?displaystats>
<table cellspacing=1 width=300>
<tr>
	<th colspan=2>Check Your Stats</th>
</tr><tr>
	<td align=right>Username:</td>
	<td><input type=text name=V53d670af></td>
</tr>
<tr>
	<td align=right>Enter Your Password:</td>
	<td><input type=password name=V5f4dcc3b></td>
</tr>
<tr>
	<td colspan=2 align=center><input type=submit name=displaystats value='Check Stats'></td>
</tr>
</table>
</form>
<a target=_blank href=http://www.gbscript.com>Powered by GBScript - Download Free GB CJ</a>
<?
}
?>
</div>
</body>
</html>
<?
exit;
function F68a59adb($Vf8bff26e)
{
 
	$V745fd0ea=array();
$V15e05c6d=array();
if($Vf8bff26e) { $V15e05c6d[]=$Vf8bff26e; }
else
	{
 $V8277e091=dir('memberfiles');
while($V1043bfc7=$V8277e091->read())
 {
 if(ereg("\.dat",$V1043bfc7)) { $V15e05c6d[]=ereg_replace("\.dat",'',$V1043bfc7); }
}
$V8277e091->close();
}
for($mem=0;$mem<count($V15e05c6d);$mem++)
	{
 $V3659ce8e=array();
Fb0b3b672('memberfiles/'.$V15e05c6d[$mem].'.csv',"\r\n",$V3659ce8e);
$V078171f8=array();
Fb0b3b672('memberfiles/'.$V15e05c6d[$mem].'.dat',"\|",$V078171f8);
for($V865c0c0b=0;$V865c0c0b<8;$V865c0c0b++)
 {
 $V982a278a=array();
$V982a278a[$V865c0c0b]=split(';',$V3659ce8e[$V865c0c0b]);
for($V363b122c=0;$V363b122c<24;$V363b122c++)
 {
 $V745fd0ea[$V865c0c0b][$V363b122c]+=$V982a278a[$V865c0c0b][$V363b122c];
}
}
$V745fd0ea[0][24]+=$V078171f8[5];
$V745fd0ea[1][24]+=$V078171f8[7];
$V745fd0ea[2][24]+=$V078171f8[9];
$V745fd0ea[3][24]+=$V078171f8[25];
}
if($V633de4b0=@fopen('datafiles/calctime.dat','r'))
	{
 flock($V633de4b0,1);
$V945d616f=array();
$V945d616f=split("\|",fgets($V633de4b0,1024));
flock($V633de4b0,3);
fclose($V633de4b0);
}
$V289a681c=date('H',$V945d616f[0])+0;
$V118e4cf2=0;
for($V865c0c0b=0;$V865c0c0b<=24;$V865c0c0b++)
	{
 for($V363b122c=0;$V363b122c<=3;$V363b122c++)
 {
 if($V745fd0ea[$V363b122c][$V865c0c0b]>$V118e4cf2) { $V118e4cf2=$V745fd0ea[$V363b122c][$V865c0c0b]; }
}
}
?>
<th>Time</th>
<th>Hits In</th>
<th>Raw In</th>
<th>Graph In</th>
<th colspan=2>Clicks</th>
<th colspan=2>Hits Out</th>
<?
	for($V865c0c0b=0;$V865c0c0b<=24;$V865c0c0b++)
	{
 echo ('<tr onmouseover=con(this) onmouseout=coff(this)><td align=right>'.$V289a681c.':00</td>');
echo('<td align=right>');if($V745fd0ea[0][$V865c0c0b]) echo($V745fd0ea[0][$V865c0c0b]);echo('</td>');
echo('<td align=right>');if($V745fd0ea[3][$V865c0c0b]) echo($V745fd0ea[3][$V865c0c0b]);echo('</td><td>');
if($V118e4cf2)
 {
 $V52041a85=round($V745fd0ea[0][$V865c0c0b]*150/$V118e4cf2);
$Vdb44fea5=round($V745fd0ea[3][$V865c0c0b]*150/$V118e4cf2)-$V52041a85;
$V45c976d6=round($V745fd0ea[1][$V865c0c0b]*150/$V118e4cf2);
$V9a3a65a6=round($V745fd0ea[2][$V865c0c0b]*150/$V118e4cf2);
}
if($V52041a85) { echo ('<img src=stat2.gif width='.$V52041a85.' height=7 border=0>'); }
if($Vdb44fea5) { echo ('<img src=stat3.gif width='.$Vdb44fea5.' height=7 border=0><br>'); }
echo('</td><td align=right>');if($V745fd0ea[1][$V865c0c0b]) echo($V745fd0ea[1][$V865c0c0b]);echo('</td><td>');
if($V45c976d6) { echo('<img src=stat4.gif width='.$V45c976d6.' height=7 border=0>'); }
echo('</td><td align=right>');if($V745fd0ea[2][$V865c0c0b]) echo($V745fd0ea[2][$V865c0c0b]);echo('</td><td>');
if($V9a3a65a6) { echo('<img src=stat1.gif width='.$V9a3a65a6.' height=7 border=0>'); }
echo ("</td></tr>\r\n");
if(++$V289a681c>23){ $V289a681c=0; }
}
}
?>