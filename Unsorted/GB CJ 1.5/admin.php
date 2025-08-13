<?
include('config.php');
include('calculate.php');
if($V69858820) { include('countries.php'); }
$V6adf3cbd=array('','Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
$V7572559c=array('','en de fr pt it es nl ja da','en','de','zh','ja','fr','ru','nl','es');
$V39bb4215[0]='Disabled';
$V39bb4215[1]='Daily Weekly Monthly';
$V39bb4215[2]='Hourly';
$V39bb4215[3]='Right Now';
$V39bb4215[4]='Auto';
$V39bb4215[5]='Soft';
$V21c8bfa1=0;
if($_POST['logout'])
{
	setcookie('log');
setcookie('V1a1dc91c');
}
 
elseif(($V8f96e4f5==$V9b534ea5)&&($Ve3274be5==$V34819d7b))
{
	setcookie('log',$V8f96e4f5,time()+31104000);
setcookie('V1a1dc91c',$Ve3274be5,time()+31104000);
$V21c8bfa1=1;
}
elseif(($HTTP_COOKIE_VARS['log']==$V9b534ea5)&&($HTTP_COOKIE_VARS['V1a1dc91c']==$V34819d7b))
{
	$V21c8bfa1=1;
}
include('skin.php');
if(!$V21c8bfa1)
{
?>
<title>GB CJ Control Panel - <?echo $V8ca05b67?></title>
<center>
<br><br><br><br>
<table>
<form method=POST>
<tr>
<td>Login</td>
<td><input type=text name=V8f96e4f5></td>
</tr>
<tr>
<td>Password</td>
<td><input type=password name=Ve3274be5></td>
</tr>
<tr>
<td colspan=2 align=center><input type=submit name=checkpass value=Submit></td>
</tr>
</form>
</table>
</center>
<?
	exit;
}
?>
<html>
<head>
<title><?echo $V8ca05b67?></title>
</head>
<body>
<div align=center>
<table cellspacing=0 width=95%>
<tr>
<th width=180 align=left>
<?
$V435ed7e9='datafiles/daily.dat';
if(file_exists($V435ed7e9))
{
	$Vbea79186=array('');
Fb0b3b672($V435ed7e9,"\n",$Vbea79186);
$V78078313=array('');
$V78078313=split('\|',$Vbea79186[sizeof($Vbea79186)-2]);
echo ('<b>Stats for '.$V78078313[0].'<br>'.$V78078313[1].' Visits - '.$V78078313[2].' Out</b>');
}
?>
</th>
<td align=center>
<font size=4><a target=_blank href=./>Main Page</a> - GB CJ <?echo $V2af72f10 ?> - <a target=_blank href=http://www.gbscript.com>Docs</a><? if($HTTP_ACCEPT_LANGUAGE=='ru') echo ' <a target=_blank href=http://forum.mavpa.com>Board</a></font>'; ?>
</td>
<th width=180 align=right>
<b><?echo date("H:i - d M Y").'&nbsp;<br>'.date("l");;?></b>&nbsp;
</th></tr>
</table>
<br>
<?
if(file_exists('setup.php'))
{
	@unlink('setup.php');
?>
<table cellspacing=0 width=90%>
<tr>
<td align=center>
<font size=+2 color=red>Thank You !</font><br>
<font size=+1>
Welcome to our community of experienced webmasters<br>
Bookmark this page and make a first step - read <a target=_blank href=http://www.gbscript.com>docs and FAQ</a><br>
</font>
</td>
</tr>
</table>
<br>
<?
}
 
if($_POST["viewlogs"])
{
	F546519e0();
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr><td align=center>
<b>Select Log To View</b>
</td></tr>
</table>
<?
}
 
else if($_POST["viewhourly"])
{
	F546519e0();
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<td align=center>
<b>Hourly Log</b>
<br><br>
<table cellspacing=1 cellpadding=2>
<?
	F68a59adb('');
?>
</table>
</td>
</tr>
</table>
<br>
<?
}
 
else if($_POST["viewrefurl"])
{
	F546519e0();
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<td align=center>
<b>Referring URL Log</b>
<br><br>
<table cellspacing=1 cellpadding=2>
<tr>
	<th>RawIn</th>
	<th>Url</th>
	<th>ID</th>
	<th colspan=2>Percentage</th>
</tr>
<?
 
	$V7de45eee=array();
Fb0b3b672('datafiles/refurl.dat',"\n",$V7de45eee);
rsort($V7de45eee,SORT_NUMERIC);
$Vbdc99d8c=array();
$Vfbb44b44=0;
for($V865c0c0b=0;$V865c0c0b<count($V7de45eee);$V865c0c0b++)
	{
 if($V7de45eee[$V865c0c0b])
 {
 $V4124bc0a=array();
$V4124bc0a=explode('|',$V7de45eee[$V865c0c0b]);
$Vfbb44b44+=$V4124bc0a[0];
$V6cfe6169=0;
for($V363b122c=0;$V363b122c<count($Vbdc99d8c);$V363b122c++)
 {
 $V21ad0bd8=array();
$V21ad0bd8=explode('|',$Vbdc99d8c[$V363b122c]);
if($V4124bc0a[2]==$V21ad0bd8[2])
 {
 $Vbdc99d8c[$V363b122c]=($V21ad0bd8[0]+$V4124bc0a[0]).'|'.$V21ad0bd8[1].'|'.$V21ad0bd8[2];
$V6cfe6169=1;
break;
}
}
if(!$V6cfe6169)
 {
 $Vbdc99d8c[]=$V7de45eee[$V865c0c0b];
}
}
}
rsort($Vbdc99d8c,SORT_NUMERIC);
$Vce384c69=array();
for($V865c0c0b=0;$V865c0c0b<sizeof($Vbdc99d8c);$V865c0c0b++)
	{
 if($Vbdc99d8c[$V865c0c0b])
 {
 $Vce384c69[$V865c0c0b]=explode('|',$Vbdc99d8c[$V865c0c0b]);
}
}
for($V865c0c0b=0;$V865c0c0b<count($Vce384c69);$V865c0c0b++)
	{
 $Vd1647af5=0;
if($Vfbb44b44>0) { $Vd1647af5=round($Vce384c69[$V865c0c0b][0]*100/$Vfbb44b44); }
if($Vce384c69[$V865c0c0b][0]>1)
 {
?>
<tr onmouseover=con(this) onmouseout=coff(this)><td align=center><?echo $Vce384c69[$V865c0c0b][0]?></td>
<td>
<?
 $V3d0ad017=$Vce384c69[$V865c0c0b][1];
$Vee683fca=$V23668a6e=' ';
if(strstr($V3d0ad017,'http://'))
 {
 $V23668a6e="<a target=_blank href='$V3d0ad017'>";
$Vee683fca='</a>';
}
if (strlen($V3d0ad017)>50) { $V3d0ad017=substr($V3d0ad017,0,50).'..'; }
echo $V23668a6e.$V3d0ad017.$Vee683fca;
?>
<td>
	<form name=mem<?echo $V865c0c0b?> method=POST>
	<input type=hidden name=memberstats value=yes>
	<input type=hidden name=V53d670af value=<?echo $Vce384c69[$V865c0c0b][2]?>>
	<?echo('<a href=# onclick="document.all.mem'.$V865c0c0b.'.submit();return false">'.$Vce384c69[$V865c0c0b][2].'</a>')?>
</td></form>
<td align=center><?echo $Vd1647af5?>%</td>
<td>
<?
	if($Vd1647af5) { echo ('<img src=stat2.gif width='.($Vd1647af5*2).' height=7 border=0>'); }
echo('</td></tr>');
}
}
?>
</table>
</td>
</tr>
</table>
<br>
<?
}
 
else if($_POST["viewpages"])
{
	F546519e0();
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<td align=center>
<b>Popular Pages</b>
<br><br>
<table cellspacing=1 cellpadding=2>
<tr>
	<th>Clicks</th>
	<th>Page</th>
	<th colspan=2>Percentage</th>
</tr>
<?
 
	$V38d4765b=array();
Fb0b3b672('datafiles/clickpage.dat',"\n",$V38d4765b);
rsort($V38d4765b,SORT_NUMERIC);
$Vfbb44b44=0;
$Ved32a252=array('');
for($V865c0c0b=0;$V865c0c0b<sizeof($V38d4765b);$V865c0c0b++)
	{
 $Ved32a252[$V865c0c0b]=split("\|",$V38d4765b[$V865c0c0b]);
$Vfbb44b44+=$Ved32a252[$V865c0c0b][0];
}
for($V865c0c0b=0;$V865c0c0b<sizeof($Ved32a252);$V865c0c0b++)
	{
 $Vd1647af5=0;
if($Vfbb44b44>0) { $Vd1647af5=round($Ved32a252[$V865c0c0b][0]*100/$Vfbb44b44); }
if($Ved32a252[$V865c0c0b][0]>1)
 {
?>
<tr onmouseover=con(this) onmouseout=coff(this)><td align=center><?echo $Ved32a252[$V865c0c0b][0]?></td>
<td>
<?
 $V7de45eee=$Ved32a252[$V865c0c0b][1];
$Vee683fca=$V23668a6e=' ';
if(strstr($V7de45eee,'http://'))
 {
 $V23668a6e="<a target=_blank href='$V7de45eee'>";
$Vee683fca='</a>';
}
if (strlen($V7de45eee)>50) { $V3d0ad017=substr($V7de45eee,0,50).'..'; }
echo $V23668a6e.$V7de45eee.$Vee683fca;
?>
</form>
<td align=center><?echo $Vd1647af5?>%</td>
<td>
<?
	if($Vd1647af5) { echo ('<img src=stat2.gif width='.($Vd1647af5*2).' height=7 border=0>'); }
echo('</td></tr>');
}
}
?>
</table>
</td>
</tr>
</table>
<br>
<?
}
 
else if($_POST["viewlinktrack"])
{
	F546519e0();
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<td align=center>
<b>Link Tracking Log</b>
<br><br>
<table cellspacing=1 cellpadding=2>
<tr>
	<th>Clicks</th>
	<th>Link ID</th>
	<th colspan=2>Percentage</th>
</tr>
<?
	$V33052e94=array('');
Fb0b3b672('datafiles/linktrack.dat',"\n",$V33052e94);
rsort($V33052e94,SORT_NUMERIC);
$Vfbb44b44=0;
$V8a61dcdc=array('');
for($V865c0c0b=0;$V865c0c0b<sizeof($V33052e94);$V865c0c0b++)
	{
 $V8a61dcdc[$V865c0c0b]=split("\|",$V33052e94[$V865c0c0b]);
$Vfbb44b44+=$V8a61dcdc[$V865c0c0b][0];
}
for($V865c0c0b=0;$V865c0c0b<sizeof($V8a61dcdc);$V865c0c0b++)
	{
 $Vd1647af5=0;
if($Vfbb44b44>0) { $Vd1647af5=round($V8a61dcdc[$V865c0c0b][0]*100/$Vfbb44b44); }
if($V8a61dcdc[$V865c0c0b][0])
 {
?>
<tr onmouseover=con(this) onmouseout=coff(this)>
<td align=center><?echo $V8a61dcdc[$V865c0c0b][0]?></td>
<td align=center><?echo $V8a61dcdc[$V865c0c0b][1]?></td>
<td align=right><?echo $Vd1647af5?>%</td>
<td>
<?
 if($Vd1647af5) { echo ('<img src=stat1.gif width='.($Vd1647af5*2).' height=7 border=0>'); }
echo('</td></tr>');
}
}
?>
</table>
</td>
</tr>
</table>
<br>
<?
}
 
else if($_POST["viewlanguages"])
{
	F546519e0();
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<td align=center>
<b><?if($V69858820){echo'Countries';}else{echo'Browser Languages';}?> Log</b>
<br><br>
<table cellspacing=1 cellpadding=2>
<tr>
	<th>Clicks</th>
	<th><?if($V69858820){echo'Country';}else{echo'Language';}?></th>
	<th colspan=2>Percentage</th>
</tr>
<?
	$Vaf799ebc=array('');
Fb0b3b672('datafiles/lang.dat',"\n",$Vaf799ebc);
rsort($Vaf799ebc,SORT_NUMERIC);
$Vfbb44b44=0;
$V60fec096=$V145a2791=array();
for($V865c0c0b=0;$V865c0c0b<sizeof($Vaf799ebc);$V865c0c0b++)
	{
 $V145a2791=split("\|",$Vaf799ebc[$V865c0c0b]);
$V6cfe6169=0;
for($V363b122c=0;$V363b122c<sizeof($V60fec096);$V363b122c++)
 {
 if($V145a2791[1]==$V60fec096[$V363b122c][1])
 {
 $V60fec096[$V363b122c][0]+=$V145a2791[0];
$V6cfe6169=1;
break;
}
}
if(!$V6cfe6169){ $V60fec096[]=$V145a2791; }
$Vfbb44b44+=$V145a2791[0];
}
for($V865c0c0b=0;$V865c0c0b<sizeof($V60fec096);$V865c0c0b++)
	{
 $Vd1647af5=0;
if($Vfbb44b44>0) { $Vd1647af5=round($V60fec096[$V865c0c0b][0]*100/$Vfbb44b44); }
if($V60fec096[$V865c0c0b][0]>1)
 {
 $V21bcaf2c=$V60fec096[$V865c0c0b][1];
if (strlen($V21bcaf2c)>50) { $V21bcaf2c=substr($V21bcaf2c,0,50).'..'; }
?>
<tr onmouseover=con(this) onmouseout=coff(this)>
<td><?echo $V60fec096[$V865c0c0b][0]?></td>
<td><?echo $V21bcaf2c?></td>
<td align=right><?echo $Vd1647af5?>%</td>
<td>
<?
 if($Vd1647af5) { echo ('<img src=stat3.gif width='.($Vd1647af5*2).' height=7 border=0>'); }
echo('</td></tr>');
}
}
?>
</table>
</td>
</tr>
</table>
<br>
<?
}
 
else if($_POST["viewdaily"])
{
	F546519e0();
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<td align=center>
<b>Daily In/Out Log</b>
<br><br>
<table cellspacing=1 cellpadding=2>
<tr>
	<th>Date</th>
	<th>Hits In</th>
	<th>Raw In</th>
	<th>Graph In</th>
	<th>Clicks</th>
	<th>Hits Out</th>
	<th>Graph Out</th>
</tr>
<?
	$Vbea79186=array();
Fb0b3b672('datafiles/daily.dat',"\n",$Vbea79186);
$V118e4cf2=0;
$V1d30cde2=array('');
for($V865c0c0b=0;$V865c0c0b<count($Vbea79186);$V865c0c0b++)
	{
 $V1d30cde2[$V865c0c0b]=explode('|',trim($Vbea79186[$V865c0c0b]));
for($V363b122c=1;$V363b122c<5;$V363b122c++)
 {
 if($V1d30cde2[$V865c0c0b][$V363b122c]>$V118e4cf2) { $V118e4cf2=$V1d30cde2[$V865c0c0b][$V363b122c]; }
}
}
for($V865c0c0b=0;$V865c0c0b<sizeof($V1d30cde2);$V865c0c0b++)
	{
 if($V1d30cde2[$V865c0c0b][0])
 {
 $V52041a85=round($V1d30cde2[$V865c0c0b][1]*200/$V118e4cf2);
$Vdb44fea5=round($V1d30cde2[$V865c0c0b][3]*200/$V118e4cf2)-$V52041a85;
$V45c976d6=round($V1d30cde2[$V865c0c0b][4]*200/$V118e4cf2);
$V9a3a65a6=round($V1d30cde2[$V865c0c0b][2]*200/$V118e4cf2)-$V45c976d6;
?>
<tr onmouseover=con(this) onmouseout=coff(this)>
<td width=80><?echo $V1d30cde2[$V865c0c0b][0]?></td>
<td align=right><?if($V1d30cde2[$V865c0c0b][1]) echo $V1d30cde2[$V865c0c0b][1]?></td>
<td align=right><?if($V1d30cde2[$V865c0c0b][3]) echo $V1d30cde2[$V865c0c0b][3]?></td>
<td>
<?
 if($V52041a85) { echo ('<img src=stat2.gif width='.$V52041a85.' height=7 border=0>'); }
if($Vdb44fea5) { echo ('<img src=stat3.gif width='.$Vdb44fea5.' height=7 border=0>'); }
?>
</td>
<td align=right><?if($V1d30cde2[$V865c0c0b][4]) echo $V1d30cde2[$V865c0c0b][4]?></td>
<td align=right><?if($V1d30cde2[$V865c0c0b][2]) echo $V1d30cde2[$V865c0c0b][2]?></td>
<td>
<?
 if($V45c976d6) { echo ('<img src=stat4.gif width='.$V45c976d6.' height=7 border=0>'); }
if($V9a3a65a6) { echo ('<img src=stat1.gif width='.$V9a3a65a6.' height=7 border=0>'); }
}
}
?>
</td></tr>
</table>
</td>
</tr>
</table>
<br>
<?
}
 
else if($_POST["delete"])
{
	unlink('datafiles/'.$_POST["V53d670af"]);
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr><td align=center>
<b>Data File Has Been Reset</b>
</td></tr>
</table>
<br>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<form method=POST>
<td align=center><input type=submit name=main value='Main Menu'></td>
</tr>
</table>
<?
}
 
else if($_POST["edittemplates"])
{
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<td align=center colspan=2>
<b>Edit Templates</b>
</td>
</tr>
<tr>
<form method="POST">
<td align=center colspan=2>
<b>Main page 1</b><br>
<textarea name=Vecdc7656 cols=80 rows=25 wrap=OFF>
<?
	if(F43605c4d('main1.html',$Vfe9e80c8))
	{
 echo htmlspecialchars($Vfe9e80c8);
}
?>
</textarea>
<br><br>
<b>Main page 2</b><br>
<textarea name=V57b6a6e0 cols=80 rows=25 wrap=OFF>
<?
	if(F43605c4d('main2.html',$V740f9b67))
	{
 echo htmlspecialchars($V740f9b67);
}
?>
</textarea>
<br><br>
<b>Pop-up</b><br>
<textarea name=V36496b27 cols=80 rows=20 wrap=OFF>
<?
	if(F43605c4d('best.html',$V0d4c14a2))
	{
 echo htmlspecialchars($V0d4c14a2);
}
?>
</textarea>
</td>
</tr>
<tr>
<td align=center><input type=submit name=savetemplates value='Save Templates'></td>
<td align=center><input type=submit name=main value='Main Menu'></td>
</tr>
</table>
<?
}
 
else if($_POST["savetemplates"])
{
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<td align=center>
<?
$Vfe9e80c8=str_replace("\r",'',stripslashes($Vecdc7656));
F93adb31d('main1.html',$Vfe9e80c8);
$V740f9b67=str_replace("\r",'',stripslashes($V57b6a6e0));
F93adb31d('main2.html',$V740f9b67);
$V0d4c14a2=str_replace("\r",'',stripslashes($V36496b27));
F93adb31d('best.html',$V0d4c14a2);
?>
<b>Templates Saved</b>
</td>
</tr>
<tr>
<td align=center colspan=2>
</textarea>
</td>
</tr>
<tr>
<form method=POST>
<td align=center><input type=submit name=main value='Main Menu'></td>
</tr>
</table>
<?
F234f0381();
}
 
else if($_POST["editrules"])
{
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<td align=center colspan=2>
<b>Edit Rules</b>
</td>
</tr>
<tr>
<form method=POST>
<td align=center colspan=2>
<b>Main webmasters page</b><br>
<textarea name=Vbc87880c cols=80 rows=10 wrap=OFF>
<?
	if(F43605c4d('datafiles/rules.dat',$V1f4da964))
	{
 echo htmlspecialchars($V1f4da964);
}
?>
</textarea>
<br><br>
<b>Site added page</b><br>
<textarea name=V2b4a63b7 cols=80 rows=10 wrap=OFF>
<?
	if(F43605c4d('datafiles/rules2.dat',$V229c1995))
	{
 echo htmlspecialchars($V229c1995);
}
?>
</textarea>
</td>
</tr>
<tr>
<td align=center><input type=submit name=saverules value='Save Rules'></td>
<td align=center><input type=submit name=main value='Main Menu'></td>
</tr>
</table>
<?
}
 
else if($_POST["saverules"])
{
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<td align=center>
<?
	$V1f4da964=str_replace("\r",'',stripslashes($Vbc87880c));
F93adb31d('datafiles/rules.dat',$V1f4da964);
$V229c1995=str_replace("\r",'',stripslashes($V2b4a63b7));
F93adb31d('datafiles/rules2.dat',$V229c1995);
?>
<b>Rules Saved</b>
</td>
</tr>
<tr>
<td align=center colspan=2>
</textarea>
</td>
</tr>
<tr>
<form method=POST>
<td align=center><input type=submit name=main value='Main Menu'></td>
</tr>
</table>
<?
}
 
else if($_POST["editblacklist"])
{
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<td align=center colspan=2>
<b>Edit BlackList</b>
<br>
One domain, URL or IP per line
</td>
</tr>
<tr>
<form method=POST>
<td align=center colspan=2>
<textarea name=V99f2a1a4 cols=60 rows=10 wrap=OFF>
<?
	if(F43605c4d('datafiles/blacklist.dat',$V1cdc4983))
	{
 echo $V1cdc4983;
}
?>
</textarea>
</td>
</tr>
<tr>
<td align=center><input type=submit name=saveblacklist value='Save BlackList'></td>
<td align=center><input type=submit name=main value='Main Menu'></td>
</tr>
</table>
<?
}
 
else if($_POST["saveblacklist"])
{
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<td align=center>
<?
	F93adb31d('datafiles/blacklist.dat',$V99f2a1a4);
?>
<b>BlackList Updated</b>
</td>
</tr>
<tr>
<td align=center colspan=2>
</textarea>
</td>
</tr>
<tr>
<form method=POST>
<td align=center><input type=submit name=main value='Main Menu'></td>
</tr>
</table>
<?
}
 
else if($_POST["updatesettings"])
{
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr><td align=center>
<?
if(!F93adb31d('datafiles/adminconfig.dat',serialize($adminconfig))) { echo '<b>Error! Cant write to file adminconfig.dat</b>'; }
$V11dbe7e0="<?\r\n";
$V11dbe7e0.='$V861ce498="'.$V861ce498.'";'."\r\n".'$V8ca05b67="'.$Vc70074c2.'";'."\r\n".'$V0c83f57c="'.$V7f4e946d.'";'."\r\n".'$icq="'.$Vf790995a.'";'."\r\n".'$V94a7c36b="'.$V0168e55b.'";'."\r\n".'$V14ca2a15="'.$V84b0e073.'";'."\r\n".'$Vf1cb89ef="'.$V66b9a507.'";'."\r\n".'$V8dfc6579="'.$V2f9bc40b.'";'."\r\n".'$V5ca52301="'.$Vc1c1bddd.'";'."\r\n".'$V9b534ea5="'.$V565c0027.'";'."\r\n".'$V34819d7b="'.$Vaa8f5042.'";'."\r\n".'$V0d50889a="'.$V0d50889a.'";'."\r\n".'$Vb512b9b2="'.$Vb512b9b2.'";'."\r\n";
$V11dbe7e0.='$V0ca2ee97="'.$V631772af.'";'."\r\n".'$V742913f8="'.$V4821278f.'";'."\r\n".'$V3e04dc2a="'.$V3e04dc2a.'";'."\r\n".'$Vec77498a="'.$Vec77498a.'";'."\r\n".'$Vd4bf1469="'.$Vd4bf1469.'";'."\r\n".'$V4e807dca="'.$acceptedlanguagenew.'";'."\r\n".'$V00dd8e4a="'.$Vfd7467f4.'";'."\r\n".'$Vc39f66b1="'.$V32e9bbc1.'";'."\r\n".'$V4b1a8625="'.$Vd2b6e296.'";'."\r\n".'$V2af72f10="'.$V2af72f10.'";'."\r\n".'$V2914baf9="'.$V1ada0c93.'";'."\r\n".'$displaystats="'.$V18c11919.'";'."\r\n";
$V11dbe7e0.='$V98c4bb2d="'.$Vcee7ca45.'";'."\r\n".'$V82fb14b8="'.$V75351123.'";'."\r\n".'$Vf3778551="'.$Vc85ea988.'";'."\r\n".'$V183d79af="'.$V65018e5b.'";'."\r\n".'$V6f458d5c="'.$V4034b6a9.'";'."\r\n".'$Vf7d5225e="'.$Vdcfdc85e.'";'."\r\n".'$Vd519d889="'.$V5151215a.'";'."\r\n".'$V6b72e9b9="'.$Vc2e0fba1.'";'."\r\n".'$Vf03c5300="'.$V6f49fa55.'";'."\r\n".'$V5cf1c824="'.$V14dca78d.'";'."\r\n".'$affiliateid="'.$Vb1bae187.'";'."\r\n".'$Vd3a4258b="'.$V52abca0d.'";'."\r\n".'$V69858820="'.$V98c4466d.'";'."\r\n?>";
if(F93adb31d('config.php',$V11dbe7e0))
{
	echo '<b>Settings Updated</b>';
}
else { echo '<b>Error! Cant write to file config.php</b>'; }
?>
</td></tr>
</table>
<br>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<form method=POST>
<td align=center><input type=submit name=main value='Main Menu'></td>
<td align=center><input type=submit name=settings value=Settings></td>
</tr>
</table>
<?
}
 
else if($_POST["settings"])
{
	$adminconfig=@unserialize(@implode('',file('datafiles/adminconfig.dat')));
?>
<script>
function Fa4a65097(WarMessage)
{
	WarWin=open('','WarPopUp','width=350,height=150,toolbar=0,location=0,menubar=0,scrollbars=1,resizable=1');
with(WarWin.document)
	{
 open();
write('<html><head><title>Warning!</title><base target=_blank></head><body><center><font size=4 face=Arial color=#ff0000>Warning!<br><br>'+WarMessage+'</body>');
close();
}
}
</script>
<table cellspacing=0 cellpadding=3 width=90%>
<tr><td align=center>
<b>Settings</b>
</td></tr>
</table>
<br>
<form method=POST>
<table cellspacing=0 width=90%>
<tr>
<th>
<br>
<table cellspacing=1>
<tr>
	<td>Site Name</td>
	<td><input type=text name=Vc70074c2 value='<?echo $V8ca05b67?>'></td>
	<td>Aphorisms on admin page</td>
	<td><input type=checkbox name=V14dca78d value=1 <? if($V5cf1c824) echo 'checked'; ?>></td>
</tr><tr>
	<td>Webmaster e-mail</td>
	<td><input type=text name=V7f4e946d value='<?echo $V0c83f57c?>'></td>
	<td>Trader is Dead after Days</td>
	<td><input type=text size=4 name=Vcee7ca45 value='<?echo $V98c4bb2d?>'></td>
</tr><tr>
	<td>Webmaster icq</td>
	<td><input type=text name=Vf790995a value='<?echo $icq?>'></td>
	<td>Stop Forces for Dead Traders</td>
	<td><input type=checkbox name=V75351123 value=1 <? if($V82fb14b8) echo 'checked'; ?>></td>
</tr><tr>
	<td>Default Limit Out/Click</td>
	<td><input type=text size=4 name=V0168e55b value='<?echo $V94a7c36b?>'></td>
	<td>GB Protocol</td>
	<td><input type=checkbox name=Vc85ea988 value=1 <? if($Vf3778551) echo 'checked'; ?>></td>
</tr><tr>
	<td>Category/Default Group</td>
	<td><input type=text name=V66b9a507 value='<?echo $Vf1cb89ef?>'></td>
	<td>Max Counted Clicks for unique visitor</td>
	<td><input type=text size=4 name=V631772af value='<?echo $V0ca2ee97?>'></td>
</tr><tr>
	<td>Default Force Amount</td>
	<td><input type=text size=4 name=V2f9bc40b value='<?echo $V8dfc6579?>'></td>
	<td>Show ICQ Status</td>
	<td><input type=checkbox name=V52abca0d value=1 <? if($Vd3a4258b) echo 'checked'; ?>></td>
</tr><tr>
	<td>Default Force Time</td>
	<td><input type=text size=4 name=Vc1c1bddd value='<?echo $V5ca52301?>'></td>
	<td>Trace IP</td>
	<td><input type=checkbox name=V4821278f value=1 <? if($V742913f8) echo 'checked'; ?>></td>
</tr><tr>
	<td>Login</td>
	<td><input type=text name=V565c0027 value='<?echo $V9b534ea5?>'></td>
	<td>Use Trade ID</td>
	<td><input type=checkbox name=Vfd7467f4 value=1 <? if($V00dd8e4a) echo 'checked'; ?>></td>
</tr><tr>
	<td>Password</td>
	<td><input type=text name=Vaa8f5042 value='<?echo $V34819d7b?>'></td>
	<td>Scan of banned words on the traders pages</td>
	<td><input type=checkbox name=Vd2b6e296 value=1 <? if($V4b1a8625) echo 'checked'; ?>></td>
</tr><tr>
	<td>Ratio for Bad Languages or Countries</td>
	<td><input type=text size=4 name=V32e9bbc1 value='<?echo $Vc39f66b1?>'></td>
	<td>Stats on submit page</td>
	<td><input type=checkbox name=V18c11919 value=1 <? if($displaystats) echo 'checked'; ?>></td>
</tr><tr>
	<td>Bad Clicks Limit (proxy & robots)</td>
	<td><input type=text size=4 name=V1ada0c93 value='<?echo $V2914baf9?>'></td>
	<td>GB Rules on submit page</td>
	<td><input type=checkbox name=Vdcfdc85e value=1 <? if($Vf7d5225e) echo 'checked'; ?>></td>
</tr><tr>
	<td>Pop-ups Amount for Unknown Referers</td>
	<td><input type=text size=4 name=V84b0e073 value='<?echo $V14ca2a15?>'></td>
	<td>Pop-ups amount in submit form</td>
	<td><input type=checkbox name=V5151215a value=1 <? if($Vd519d889) echo 'checked'; ?>></td>
</tr><tr>
	<td>Fixed deviation(anticheat) link name</td>
	<td><input type=text name=V4034b6a9 value='<?echo $V6f458d5c?>'></td>
	<td>Compress Face HTML
	<a href=# onclick='Fa4a65097("Your server should have setting zlib.output_compression=On for using of this function or script will not working");return false'><small>Warning!</small></a>
	</td>
	<td><input type=checkbox name=Vc2e0fba1 value=1 <? if($V6b72e9b9) echo 'checked'; ?>></td>
</tr><tr>
	<td>Trade Scheme (sample: CCTCTCT)</td>
	<td><input type=text name=V6f49fa55 value='<?echo $Vf03c5300?>'></td>
	<td>Deviation Limit for New Traders</td>
	<td><input type=text size=4 name=V65018e5b value='<?echo $V183d79af?>'></td>
</tr><tr>
	<td><a target=_blank href=http://gbscript.com/affiliate.html>Affiliate ID</a></td>
	<td><input type=text name=Vb1bae187 value='<?echo $affiliateid?>'></td>
	<td>Country Tracking
	<a href=# onclick='Fa4a65097("Your server should have GeoIP module for using of this function or script will not working");return false'><small>Warning!</small></a>
	</td>
	<td><input type=checkbox name=V98c4466d value=1 <? if($V69858820) echo 'checked'; ?>></td>
</tr><tr>
	<td>Accepted
<?
if($V69858820) { echo('Countries'); } else { echo('Languages'); }
?>
</td>
	<td><input type=text name=acceptedlanguagenew value='<?echo $V4e807dca?>'></td>
	<td colspan=2><=
	<select onchange="langlist=document.all.acceptedlanguagenew.value; if (langlist.length>0) langlist+=' '; if (this.value!='') document.all.acceptedlanguagenew.value=langlist+this.value">
<?
	if($V69858820)
	{
 for($V865c0c0b=0;$V865c0c0b<sizeof($Va88be001);$V865c0c0b++)
 {
 echo'<option value="'.$Va88be001[$V865c0c0b].'">';
if($V865c0c0b!=1) echo($Va88be001[$V865c0c0b].' ');
echo"$V5a0f9379[$V865c0c0b]</option>\n";
}
}
else
	{
 for($V865c0c0b=0;$V865c0c0b<sizeof($V7572559c);$V865c0c0b++)
 {
 echo'<option value="'.$V7572559c[$V865c0c0b].'">';
echo"$V7572559c[$V865c0c0b]</option>\n";
}
}
?>
	</select>
	</td>
</tr><tr>
<td colspan=4>
<center>Visible Columns in Admin Table</center>
Checkbox<input type=checkbox name=adminconfig[0] value=1<?if($adminconfig[0]) echo ' checked';?>>
Edit<input type=checkbox name=adminconfig[1] value=1<?if($adminconfig[1]) echo ' checked';?>>
Stats<input type=checkbox name=adminconfig[2] value=1<?if($adminconfig[2]) echo ' checked';?>>
Mail ICQ<input type=checkbox name=adminconfig[4] value=1<?if($adminconfig[4]) echo ' checked';?>>
Current Hour In<input type=checkbox name=adminconfig[5] value=1<?if($adminconfig[5]) echo ' checked';?>>
Out<input type=checkbox name=adminconfig[6] value=1<?if($adminconfig[6]) echo ' checked';?>>
Click<input type=checkbox name=adminconfig[7] value=1<?if($adminconfig[7]) echo ' checked';?>>
24 Hours In<input type=checkbox name=adminconfig[8] value=1<?if($adminconfig[8]) echo ' checked';?>>
Out<input type=checkbox name=adminconfig[9] value=1<?if($adminconfig[9]) echo ' checked';?>>
Click<input type=checkbox name=adminconfig[10] value=1<?if($adminconfig[10]) echo ' checked';?>>
Dir<input type=checkbox name=adminconfig[11] value=1<?if($adminconfig[11]) echo ' checked';?>>
RIn<input type=checkbox name=adminconfig[12] value=1<?if($adminconfig[12]) echo ' checked';?>>
Bad<input type=checkbox name=adminconfig[13] value=1<?if($adminconfig[13]) echo ' checked';?>>
BadL<input type=checkbox name=adminconfig[14] value=1<?if($adminconfig[14]) echo ' checked';?>>
Prod<input type=checkbox name=adminconfig[15] value=1<?if($adminconfig[15]) echo ' checked';?>>
Ratio<input type=checkbox name=adminconfig[16] value=1<?if($adminconfig[16]) echo ' checked';?>>
Dev<input type=checkbox name=adminconfig[17] value=1<?if($adminconfig[17]) echo ' checked';?>>
Eff<input type=checkbox name=adminconfig[18] value=1<?if($adminconfig[18]) echo ' checked';?>>
Fin<input type=checkbox name=adminconfig[19] value=1<?if($adminconfig[19]) echo ' checked';?>>
Limit<input type=checkbox name=adminconfig[20] value=1<?if($adminconfig[20]) echo ' checked';?>>
Group<input type=checkbox name=adminconfig[21] value=1<?if($adminconfig[21]) echo ' checked';?>>
Pop<input type=checkbox name=adminconfig[22] value=1<?if($adminconfig[22]) echo ' checked';?>>
Coun<input type=checkbox name=adminconfig[23] value=1<?if($adminconfig[23]) echo ' checked';?>>
Force Time<input type=checkbox name=adminconfig[24] value=1<?if($adminconfig[24]) echo ' checked';?>>
Force<input type=checkbox name=adminconfig[25] value=1<?if($adminconfig[25]) echo ' checked';?>>
</td>
</tr>
</table>
</th>
</tr>
<tr>
<th><input type=submit name=updatesettings value='Update Settings'>
<input type=submit name=main value='Main Menu'></th>
</tr>
</table>
</form>
<?
}
 
else if($_POST["resettopchecker"])
{
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr><td align=center>
<?
$Vdb06ca29=array('');
if(Fb0b3b672('datafiles/toplog.dat',"\n",$Vdb06ca29))
{
	for($V865c0c0b=0;$V865c0c0b<(sizeof($Vdb06ca29)-1);$V865c0c0b++)
	{
 $V4124bc0a=array('');
$V4124bc0a=split("\|",$Vdb06ca29[$V865c0c0b]);
unlink('datafiles/grab_'.$V4124bc0a[0].'.html');
}
}
if($V633de4b0=@fopen('datafiles/topcheck.dat','w')) {	fclose($V633de4b0); }
else { echo '<b>Error! Cant write to file topcheck.dat</b>'; }
if($V633de4b0=@fopen('datafiles/toplog.dat','w')) { fclose($V633de4b0); }
else { echo '<b>Error! Cant write to file toplog.dat</b>'; }
?>
<b>Top Checker Has Been Stopped</b>
</td></tr>
</table>
<br>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<form method=POST>
<td align=center><input type=submit name=main value='Main Menu'></td>
<td align=center><input type=submit name=topchecker value='Top Checker'></td>
</tr>
</table>
<?
}
 
else if($_POST["updatetopchecker"])
{
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr><td align=center>
<?
	$V8d777f38=$V0e95faa2.'|'.$V14f99b15.'|'.$Va3305e7b.'|'.$V74318e89.'|'.$Vadb6c96f.'|'.$Vf12087e8.'|'.$V3793ea52.'|';
if(F93adb31d('datafiles/topcheck.dat',$V8d777f38))
	{
 echo '<b>Top Checker Updated</b>';
}
else { echo '<b>Error! Cant write to file topcheck.dat</b>'; }
?>
</td></tr>
</table>
<br>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<form method=POST>
<td align=center><input type=submit name=main value='Main Menu'></td>
<td align=center><input type=submit name=topchecker value='Top Checker'></td>
</tr>
</table>
<?
}
 
else if($_POST["topchecker"])
{
	$V9fa21bcb=array('');
Fb0b3b672('datafiles/topcheck.dat',"\|",$V9fa21bcb);
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr><td align=center>
<b>TopLists Reset Checker</b>
</td></tr>
</table>
<br>
<form method=POST>
<table cellspacing=0 width=90%>
<tr>
<th>
Status -
<?
	if ($V9fa21bcb[0])
	{
 if ($V9fa21bcb[2]==2)
 {
 $V2b38e269=date('H:i - d M Y - D',$V9fa21bcb[5]);
echo('reset is found - '.$V2b38e269);
}
elseif ($V9fa21bcb[2]==1)
 {
 $V2b38e269=date('H:i - d M Y - D',round(($V9fa21bcb[4]+$V9fa21bcb[5])/2));
echo('search in progress, current result - '.$V2b38e269);
}
else { echo 'search in progress'; }
}
else { echo 'inactive'; }
?>
<br>
<?
	if ($V9fa21bcb[0])
	{
 echo '<br><a target=_blank href="'.$V9fa21bcb[3].'">'.$V9fa21bcb[3].'</a><br>';
echo '<input type=hidden name=V74318e89 value="'.$V9fa21bcb[3].'">';
echo '<input type=hidden name=V0e95faa2 value=$V9fa21bcb[0]>';
}
else
	{
 echo '<table cellspacing=1><tr><td>URL of TopList main page (must be html, no cgi) <input type=text name=V74318e89 value="'.$V9fa21bcb[3].'" size=30></a></td></tr></table>';
echo '<input type=hidden name=V0e95faa2 value=3600>';
}
?>
	<input type=hidden name=V14f99b15 value='<?echo $V9fa21bcb[1]?>'>
	<input type=hidden name=Va3305e7b value='<?echo $V9fa21bcb[2]?>'>
	<input type=hidden name=Vadb6c96f value='<?echo $V9fa21bcb[4]?>'>
	<input type=hidden name=Vf12087e8 value='<?echo $V9fa21bcb[5]?>'>
	<input type=hidden name=V3793ea52 value='<?echo $V9fa21bcb[6]?>'>
<br>
<table cellspacing=1 width=500>
<tr>
<?
	if ($V9fa21bcb[0])
	{
 echo '<th><input type=submit name=resettopchecker value="Reset Log / New Top"></th>';
}
else
	{
 echo '<th><input type=submit name=updatetopchecker value="Start Checker"></th>';
}
?>
<th><input type=submit name=main value='Main Menu'></th>
</tr>
</table>
</th>
</tr>
</table>
</form>
<?
	if ($V9fa21bcb[0])
	{
?>
<br>
<table cellspacing=0>
<tr>
<th>
TopList Log
</th>
</tr>
<tr>
<td>
<?
 $Vdb06ca29=array('');
if(Fb0b3b672('datafiles/toplog.dat',"\n",$Vdb06ca29))
 {
 for($V865c0c0b=0;$V865c0c0b<(sizeof($Vdb06ca29)-1);$V865c0c0b++)
 {
 $V4124bc0a=array('');
$V4124bc0a=split("\|",$Vdb06ca29[$V865c0c0b]);
if ($Vb55ca7bf>($V4124bc0a[1]*1.05))
 {
 $Vb6803ae1='<font color=red>';
$Vc64c380a='</font>';
}
else { $Vb6803ae1=$Vc64c380a=''; }
$Vb55ca7bf=$V4124bc0a[1];
$Vbdcfee9f=date('H:i - d M Y - D',$V4124bc0a[0]);
echo ('<a target=_blank href='.$V861ce498.'/datafiles/grab_'.$V4124bc0a[0].'.html>'.$Vbdcfee9f.'</a> - '.$Vb6803ae1.$V4124bc0a[1].$Vc64c380a.'<br>');
}
}
?>
</td>
</tr>
</table>
<?
	}
}
 
else if($_POST["updatemember"])
{
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr><td align=center>
<?
	if((strstr($url,'http://'))||(strstr($url,'php')))
	{
 $V078171f8=array('');
if(Fb0b3b672('memberfiles/'.$V53d670af.'.dat',"\|",$V078171f8))
 {
 $V8d777f38=$V078171f8[0].'|'.$V078171f8[1].'|'.$V4dd836b7.'|'.$Ve02a798b.'|'.$Vb3dd50c9.'|'.$V10093ccc.'|'.$V5edb6e7b.'|'.$V9ad7a686.'|'.$Ve69fe0da.'|'.$Vb214e109.'|'.$V5eba7f09.'|'.$V1c5fa1f5.'|'.$Vb5e02752.'|'.$V078171f8[13].'|'.$V8283014e.'|'.$url.'|'.$V22d017f4.'|'.$Vafbe94cd.'|'.$group.'|'.$Ve6b1a341.'|'.$V55274f89.'|'.$Veb6c5ba7.'|'.$V99c2db49.'|'.$Vf3c5de72.'|'.$V8eff3617.'|'.$V8a3a5fae.'|'.$V1ee80869.'|'.$V237e6b0b.'|'.$Vc040bf1d.'|'.$browserlanguage.'|'.$V402a3274.'|'.$V11dc6249.'|'.$V078171f8[32].'|'.$V078171f8[33].'|'.$V078171f8[34].'|'.$V078171f8[35].'|'.$V078171f8[36].'|'.$V078171f8[37].'|';
if(F93adb31d('memberfiles/'.$V53d670af.'.dat',$V8d777f38))
 {
 echo('<b>Member '.$V53d670af.' Updated</b>');
}
else { echo('<b>Error! Cant write to file '.$V53d670af.'.dat</b>'); }
}
else { echo('<b>Error! Cant open file '.$V53d670af.'.dat</b>'); }
}
else { echo '<b>Error! The URL to send traffic to MUST include http://</b>'; }
?>
</td></tr>
</table>
<br>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<form method=POST>
<input type=hidden name=V53d670af value='<?echo $V53d670af?>'>
<td align=center><input type=submit name=main value='Main Menu'></td>
<td align=center><input type=submit name=editmember value='Edit Member <?echo $V53d670af?>'></td>
</tr>
</table>
<?
}
 
else if($_POST["editmember"])
{
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr><td align=center>
<?
	$V078171f8=array('');
if(Fb0b3b672('memberfiles/'.$V53d670af.'.dat',"\|",$V078171f8))
	{
 $Vf8bff26e=str_replace('.dat','',$V15913c10);
if ($V078171f8[18]=='new')
 {
 $V078171f8[18]=$Vf1cb89ef;
$V8d777f38=$V078171f8[0].'|'.$V078171f8[1].'|'.$V078171f8[2].'|'.$V078171f8[3].'|'.$V078171f8[4].'|'.$V078171f8[5].'|'.$V078171f8[6].'|'.$V078171f8[7].'|'.$V078171f8[8].'|'.$V078171f8[9].'|'.$V078171f8[10].'|'.$V078171f8[11].'|'.$V078171f8[12].'|'.$V078171f8[13].'|'.$V078171f8[14].'|'.$V078171f8[15].'|'.$V078171f8[16].'|'.$V078171f8[17].'|'.$V078171f8[18].'|'.$V078171f8[19].'|'.$V078171f8[20].'|'.$V078171f8[21].'|'.$V078171f8[22].'|'.$V078171f8[23].'|'.$V078171f8[24].'|'.$V078171f8[25].'|'.$V078171f8[26].'|'.$V078171f8[27].'|'.$V078171f8[28].'|'.$V078171f8[29].'|'.$V078171f8[30].'|'.$V078171f8[31].'|'.$V078171f8[32].'|'.$V078171f8[33].'|'.$V078171f8[34].'|'.$V078171f8[35].'|'.$V078171f8[36].'|'.$V078171f8[37].'|';
F93adb31d('memberfiles/'.$V53d670af.'.dat',$V8d777f38);
}
$V78f2923e=$Ve4feead0=$Vecd511d2='';
if($V078171f8[10]>0) { $V78f2923e=round($V078171f8[6]*100/$V078171f8[10]); }
if($V078171f8[9]>0) { $Ve4feead0=round($V078171f8[5]*100/$V078171f8[9]); }
if($V078171f8[21]>0) { $Vecd511d2=round($V078171f8[19]*100/$V078171f8[21]); }
$V752cc430=($V078171f8[7]+$V078171f8[20])*$V078171f8[14]-($V078171f8[9]+$V078171f8[21]);
$V752cc430=round($V752cc430);
?>
<b>Member Information For <?echo $V53d670af?></b>
</td></tr>
</table>
<br>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<form method=POST>
<input type=hidden name=V53d670af value=<?echo $V53d670af?>>
<td align=center><input type=submit name=updatemember value='Update Member'></td>
<td align=center><input type=submit name=memberstats value=Stats></td>
<?
if($V53d670af!='auxout')
{
?>
	<td align=center><input type=submit name=deletemember value='Delete Member'></td>
<?
}
?>
<td align=center><input type=submit name=main value='Main Menu'></td>
</tr>
</table>
<br>
<table cellspacing=0 width=90%>
<tr>
<th>
Member Information<br><br>
<table cellspacing=1 width=100%>
<tr>
	<td>Domain</td>
	<td><input type=text name=Vafbe94cd value='<?echo $V078171f8[17]?>' size=40></td>
</tr><tr>
	<td>Site ID</td>
	<td><?echo $V53d670af?></td>
</tr><tr>
	<td>Password</td>
	<td><input type=text name=Vb3dd50c9 value='<?echo $V078171f8[4]?>'></td>
</tr><tr>
	<td>Name for Toplist</td>
	<td><input type=text name=V99c2db49 value='<?echo $V078171f8[22]?>'></td>
</tr><tr>
	<td>Pop-ups</td>
	<td>
	<select name=V8eff3617>
<?
	for($V865c0c0b=0;$V865c0c0b<=4;$V865c0c0b++)
	{
 if ($V865c0c0b==$V078171f8[24])
 {
 $V2e85ee7c='selected';
}
else
 {
 $V2e85ee7c='';
}
echo "<option $V2e85ee7c>$V865c0c0b</option>\n";
}
?>
	</select>
</tr><tr>
	<td>Webmaster Email</td>
	<td><input type=text name=V4dd836b7 value='<?echo $V078171f8[2]?>'></td>
</tr><tr>
	<td>Webmaster ICQ</td>
	<td><input type=text name=Ve02a798b value='<?echo $V078171f8[3]?>'></td>
</tr><tr>
	<td>URL</td>
	<td><input type=text name=url value='<?echo $V078171f8[15]?>' size=40></td>
</tr><tr>
	<td>Last Hit In</td>
	<td><?echo date('d M - H:i',$V078171f8[0])?></td>
</tr><tr>
	<td>GB Protocol</td>
	<td><?if(($Vf3778551)&&($V078171f8[1]>0)){ echo 'active '.$V078171f8[1].' hits'; } else { echo 'inactive'; }?></td>
</tr><tr>
	<td>Force Hit Time</td>
	<td><input type=text name=V1c5fa1f5 value='<?echo $V078171f8[11]?>'></td>
</tr><tr>
	<td>Weekly Force Hit</td>
	<td>
	<select name=Vf3c5de72>
<?
	for($V865c0c0b=0;$V865c0c0b<sizeof($V6adf3cbd);$V865c0c0b++)
	{
 if ($V6adf3cbd[$V865c0c0b]==$V078171f8[23])
 {
 $V2e85ee7c='selected';
}
else
 {
 $V2e85ee7c='';
}
echo "<option value='$V6adf3cbd[$V865c0c0b]' $V2e85ee7c>$V6adf3cbd[$V865c0c0b]</option>\n";
}
?>
	</select>
	</td>
</tr><tr>
	<td>Monthly Force Hit</td>
	<td>
	<select name=V402a3274>
<?
	for($V865c0c0b=0;$V865c0c0b<=31;$V865c0c0b++)
	{
 $V9a2a88fd='';
if ($V865c0c0b) { $V9a2a88fd=$V865c0c0b; }
if ($V9a2a88fd==$V078171f8[30])
 {
 $V2e85ee7c='selected';
}
else
 {
 $V2e85ee7c='';
}
echo "<option value='$V9a2a88fd' $V2e85ee7c>$V9a2a88fd</option>\n";
}
?>
	</select>
	</td>
</tr><tr>
	<td>Number Of Hits To Force</td>
	<td><input type=text name=Vb5e02752 value='<?echo $V078171f8[12]?>'></td>
</tr><tr>
	<td>Hits Forced</td>
	<td><input type=text name=V11dc6249 value='<?echo $V078171f8[31]?>'></td>
</tr><tr>
	<td>Force Type</td>
	<td>
	<select name=V22d017f4>
<?
	for($V865c0c0b=0;$V865c0c0b<sizeof($V39bb4215);$V865c0c0b++)
	{
 if ($V865c0c0b==$V078171f8[16]) { $V2e85ee7c='selected'; } else { $V2e85ee7c='';	}
echo "<option value=$V865c0c0b $V2e85ee7c>$V39bb4215[$V865c0c0b]</option>\n";
}
?>
	</select>
	</td>
</tr><tr>
	<td>Effectivity Limit (left blank for no limit)</td>
	<td><input type=text name=V8283014e value='<?echo $V078171f8[14]?>'></td>
</tr><tr>
	<td>Group</td>
	<td><input type=text name=group value='<?echo $V078171f8[18]?>'></td>
</tr><tr>
	<td>Hits owed</td>
	<td><?echo $V752cc430?></td>
</tr><tr>
	<td>Out Hits
<?
if($V69858820) { echo('Countries'); } else { echo('Languages'); }
?>
</td>
	<td><input type=text name=browserlanguage value='<?echo $V078171f8[29]?>'>
<=
	<select onchange="langlist=document.all.browserlanguage.value; if (langlist.length>0) langlist+=' '; if (this.value!='') document.all.browserlanguage.value=langlist+this.value">
<?
	if($V69858820)
	{
 for($V865c0c0b=0;$V865c0c0b<sizeof($Va88be001);$V865c0c0b++)
 {
 echo'<option value="'.$Va88be001[$V865c0c0b].'">';
if($V865c0c0b!=1) echo($Va88be001[$V865c0c0b].' ');
echo"$V5a0f9379[$V865c0c0b]</option>\n";
}
}
else
	{
 for($V865c0c0b=0;$V865c0c0b<sizeof($V7572559c);$V865c0c0b++)
 {
 echo'<option value="'.$V7572559c[$V865c0c0b].'">';
echo"$V7572559c[$V865c0c0b]</option>\n";
}
}
?>
	</select>
	</td>
</tr>
</table>
</th><th valign=top>
<br>
Last Hour Stats<br>
<table cellspacing=1 width=100%>
<tr>
	<td>Unique Hits In</td>
	<td><input type=text name=V10093ccc value='<?echo $V078171f8[5]?>'></td>
</tr><tr>
	<td>Raw Hits In</td>
	<td><input type=text name=V8a3a5fae value='<?echo $V078171f8[25]?>'></td>
</tr><tr>
	<td>Hits Out</td>
	<td><input type=text name=Vb214e109 value='<?echo $V078171f8[9]?>'></td>
</tr><tr>
	<td>Clicks</td>
	<td><input type=text name=V9ad7a686 value='<?echo $V078171f8[7]?>'></td>
</tr><tr>
	<td>Bad Clicks</td>
	<td><input type=text name=V237e6b0b value='<?echo $V078171f8[27]?>'></td>
</tr><tr>
	<td>Return Ratio</td>
	<td><?echo $Ve4feead0?></td>
</tr>
</table>
<br>
24 Hours Stats<br>
<table cellspacing=1 width=100%>
<tr>
	<td>Unique Hits In</td>
	<td><input type=text name=Ve6b1a341 value='<?echo $V078171f8[19]?>'></td>
</tr><tr>
	<td>Raw Hits In</td>
	<td><input type=text name=V1ee80869 value='<?echo $V078171f8[26]?>'></td>
</tr><tr>
	<td>Hits Out</td>
	<td><input type=text name=Veb6c5ba7 value='<?echo $V078171f8[21]?>'></td>
</tr><tr>
	<td>Clicks</td>
	<td><input type=text name=V55274f89 value='<?echo $V078171f8[20]?>'></td>
</tr><tr>
	<td>Bad Clicks</td>
	<td><input type=text name=Vc040bf1d value='<?echo $V078171f8[28]?>'></td>
</tr><tr>
	<td>Return Ratio</td>
	<td><?echo $Vecd511d2?></td>
</tr>
</table>
<br>
Total Stats
<br>
<table cellspacing=1 width=100%>
<tr>
	<td>Total Hits In</td>
	<td><input type=text name=V5edb6e7b value='<?echo $V078171f8[6]?>'></td>
</tr><tr>
	<td>Total Hits Out</td>
	<td><input type=text name=V5eba7f09 value='<?echo $V078171f8[10]?>'></td>
</tr><tr>
	<td>Total Clicks</td>
	<td><input type=text name=Ve69fe0da value='<?echo $V078171f8[8]?>'></td>
</tr><tr>
	<td>Total Return Ratio</td>
	<td><?echo $V78f2923e?></td>
</tr>
</table>
</th></tr>
</table>
</form>
<?
	}
else { echo '<b>Member '.$V53d670af.' does not exist</b></td></tr></table>'; }
?>
<br>
<?
}
 
else if($_POST["memberstats"])
{
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<form method=POST>
<input type=hidden name=V53d670af value=<?echo $V53d670af?>>
<? if(($V53d670af!='direct')&&($V53d670af!='nocookie')) { echo('<td align=center><input type=submit name=editmember value="Edit Member"></td>'); }?>
<td align=center><input type=submit name=main value='Main Menu'></td>
</tr>
</table>
<br>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<td align=center>
<b>Hourly Stats for <?echo $V53d670af?></b>
<br>
<table cellspacing=1 cellpadding=1>
<?
	F68a59adb($V53d670af);
?>
<tr><th colspan=9></th></tr>
</table>
<br><br>
<b>Referring URL Log</b>
<br>
<table cellspacing=1 cellpadding=2>
<tr>
	<th>RawIn</th>
	<th>Url</th>
	<th colspan=2>Percentage</th>
</tr>
<?
 
	$V7de45eee=array('');
Fb0b3b672('datafiles/refurl.dat',"\n",$V7de45eee);
rsort($V7de45eee,SORT_NUMERIC);
 
	$V4216fb0d='';
if ($V53d670af=='auxout')
	{
 $V53d670af='noref';
$V8277e091=dir('memberfiles');
while($V1043bfc7=$V8277e091->read())
 {
 if(strstr($V1043bfc7,'.dat')) { $V4216fb0d.=str_replace('.dat',' ',$V1043bfc7); }
}
$V8277e091->close();
}
$Vfbb44b44=0;
$Vce384c69=array('');
for($V865c0c0b=0;$V865c0c0b<sizeof($V7de45eee);$V865c0c0b++)
	{
 $V0cc175b9=array('');
$V0cc175b9=split("\|",$V7de45eee[$V865c0c0b]);
if(($V0cc175b9[2]==$V53d670af)||($V4216fb0d && !strstr($V4216fb0d,$V0cc175b9[2])))
 {
 $Vce384c69[]=$V0cc175b9;
$Vfbb44b44+=$V0cc175b9[0];
}
}
for($V865c0c0b=0;$V865c0c0b<sizeof($Vce384c69);$V865c0c0b++)
	{
 $Vd1647af5=0;
if($Vfbb44b44>0) { $Vd1647af5=round($Vce384c69[$V865c0c0b][0]*100/$Vfbb44b44); }
if($Vce384c69[$V865c0c0b][0])
 {
?>
	<tr onmouseover=con(this) onmouseout=coff(this)><td align=center><?echo $Vce384c69[$V865c0c0b][0]?></td>
	<td>
<?
 $V3d0ad017=$Vce384c69[$V865c0c0b][1];
$Vee683fca=$V23668a6e=' ';
if(strstr($V3d0ad017,'http://'))
 {
 $V23668a6e="<a target=_blank href='$V3d0ad017'>";
$Vee683fca='</a>';
}
if (strlen($V3d0ad017)>50) { $V3d0ad017=substr($V3d0ad017,0,50).'..'; }
echo $V23668a6e.$V3d0ad017.$Vee683fca;
?>
	</td>
	<td align=center><?echo $Vd1647af5?>%</td>
	<td>
<?
	if($Vd1647af5) { echo ('<img src=stat2.gif width='.($Vd1647af5*2).' height=7 border=0>'); }
echo('</td></tr>');
}
}
?>
</table>
<br><br>
<b><?if($V69858820){echo'Countries';}else{echo'Languages';}?> Log</b>
<br>
<table cellspacing=1 cellpadding=2>
<tr>
	<th>Clicks</th>
	<th><?if($V69858820){echo'Country';}else{echo'Language';}?></th>
	<th colspan=2>Percentage</th>
</tr>
<?
 
	$Vaf799ebc=array();
Fb0b3b672('datafiles/lang.dat',"\n",$Vaf799ebc);
rsort($Vaf799ebc,SORT_NUMERIC);
$Vfbb44b44=0;
$V60fec096=array();
for($V865c0c0b=0;$V865c0c0b<sizeof($Vaf799ebc);$V865c0c0b++)
	{
 $V0cc175b9=array();
$V0cc175b9=split("\|",$Vaf799ebc[$V865c0c0b]);
if($V0cc175b9[2]==$V53d670af)
 {
 $V60fec096[]=$V0cc175b9;
$Vfbb44b44+=$V0cc175b9[0];
}
}
for($V865c0c0b=0;$V865c0c0b<sizeof($V60fec096);$V865c0c0b++)
	{
 $Vd1647af5=0;
if($Vfbb44b44>0) { $Vd1647af5=round($V60fec096[$V865c0c0b][0]*100/$Vfbb44b44); }
if($V60fec096[$V865c0c0b][0])
 {
?>
	<tr onmouseover=con(this) onmouseout=coff(this)><td align=center><?echo $V60fec096[$V865c0c0b][0];?></td>
	<td align=center>
<?
	echo $V60fec096[$V865c0c0b][1];
if($V69858820)
	{
 for($V363b122c=0;$V363b122c<count($Va88be001);$V363b122c++)
 {
 if($Va88be001[$V363b122c]==$V60fec096[$V865c0c0b][0])
 {
 echo(' '.$V5a0f9379[$V363b122c]);
break;
}
} 
	}
?>
	</td>
	<td align=center><?echo $Vd1647af5?>%</td>
	<td>
<?
	if($Vd1647af5) { echo ('<img src=stat3.gif width='.($Vd1647af5*2).' height=7 border=0>'); }
echo('</td></tr>');
}
}
?>
</table>
</td>
</tr>
</table>
<br>
<?
}
 
else if($_POST["deletememberdoit"])
{
	unlink('memberfiles/'.$V53d670af.'.dat');
unlink('memberfiles/'.$V53d670af.'.csv');
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr><td align=center>
<b>Member '<?echo $V53d670af?>' Has Been Deleted</b>
</td></tr>
</table>
<br>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<form method=POST>
<td align=center><input type=submit name=main value='Main Menu'></td>
</tr>
</table>
<?
}
 
else if($_POST["deletemember"])
{
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr><td align=center>
<b>Are You Sure You Want To Delete Member <?echo $V53d670af?>?</b>
</td></tr>
</table>
<br>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<form method=POST>
<input type=hidden name=V53d670af value='<?echo $V53d670af?>'>
<td align=center><input type=submit name=deletememberdoit value='Just Do It'></td>
<td align=center><input type=submit name=V15913c10 value='Main Menu'></td>
</tr>
</table>
<?
}
 
else if($_POST["forceupdate"])
{
	if($V633de4b0=@fopen('datafiles/calctime.dat','r+'))
	{
 flock($V633de4b0,2);
$V945d616f=array('');
$V945d616f=split("\|",fgets($V633de4b0,1024));
$V3e067559=time();
$V0f295760=$V4ac4d98c=0;
if(date('H',$V3e067559)!=date('H',$V945d616f[0]))
 {
 $V0f295760=1;
if(date('j',$V3e067559)!=date('j',$V945d616f[0])) { $V4ac4d98c=1; }
}
$V945d616f[0]=$V3e067559;
fseek($V633de4b0,0);
fputs($V633de4b0,$V945d616f[0].'|'.$V945d616f[1]);
flock($V633de4b0,3);
fclose($V633de4b0);
Ffd418b58();
F234f0381();
}
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr><td align=center>
<b>Update Forced!</b>
</td></tr>
</table>
<br>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<form method=POST>
<td align=center><input type=submit name=main value='Main Menu'></td>
</tr>
</table>
<?
}
 
else if($_POST["massedit"])
{
?>
<form method=POST>
<table cellspacing=0 cellpadding=3 width=90%>
<tr><td align=center>
<b>Mass Edit Trades</b>
<br>
<?
	if($_POST["memlist"])
	{
 $V15e05c6d=array('');
$V15e05c6d=split('\|',$memlist);
for($V865c0c0b=0;$V865c0c0b<(sizeof($V15e05c6d)-1);$V865c0c0b++)
 {
 echo $V15e05c6d[$V865c0c0b].' ';
}
?>
<br><br>
<table cellspacing=1 cellpadding=1>
<tr>
<th>
<table cellspacing=1 cellpadding=1>
<tr>
<th>Edit</th>
<th></th>
<th>New Value</th>
</tr>
<tr>
<td><input type=checkbox name=Limit value=1></td>
<td>Limit Out/Click</td>
<td><input type=input name=Vfbedf566></td>
</tr>
<tr>
<td><input type=checkbox name=Group value=1></td>
<td>Group</td>
<td><input type=input name=Vcaff3ae3 value='<?echo $Vf1cb89ef;?>'></td>
</tr>
<tr>
<td><input type=checkbox name=Popups value=1></td>
<td>Pop-ups</td>
<td><input type=input name=V49bfffef value='<?echo $V14ca2a15;?>'></td>
</tr>
<tr>
<td><input type=checkbox name=Lang value=1></td>
<td>Out Browser Language</td>
<td>
<select name=V53205c55>
<?
	for($V865c0c0b=0;$V865c0c0b<sizeof($V7572559c);$V865c0c0b++)
	{
 echo "<option value='$V7572559c[$V865c0c0b]'>$V7572559c[$V865c0c0b]</option>\n";
}
?>
</select>
</td>
</tr>
<tr>
<td><input type=checkbox name=ForceTime value=1></td>
<td>Force Time</td>
<td><input type=input name=V9a384c58 value='00:00'></td>
</tr>
<tr>
<td><input type=checkbox name=ForceAmount value=1></td>
<td>Force Amount</td>
<td><input type=input name=V3d436fd6></td>
</tr>
<input type=hidden name=memlist value=<?echo $memlist?>>
<td colspan=3 align=center><input type=submit name="masssave" value="Edit Values"></td>
</table>
</th>
</tr>
</table>
<?
	}
else
	{
 echo '<b>You have not selected any trader</b>';
}
?>
</td></tr>
</table>
<br>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<td align=center><input type=submit name=main value='Main Menu'></td>
</tr>
</table>
</form>
<?
}
 
else if($_POST["masssave"])
{
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr><td align=center>
<b>Traders Edited</b>
<br>
<?
	if($_POST["memlist"])
	{
 $V15e05c6d=array('');
$V15e05c6d=split('\|',$memlist);
for($V865c0c0b=0;$V865c0c0b<(sizeof($V15e05c6d)-1);$V865c0c0b++)
 {
 echo ($V15e05c6d[$V865c0c0b].' - ');
$V435ed7e9='memberfiles/'.$V15e05c6d[$V865c0c0b].'.dat';
if($V633de4b0 = @fopen($V435ed7e9,'r'))
 {
 flock($V633de4b0,1);
$V32762e5d = str_replace("\r\n",'',fgets($V633de4b0,filesize($V435ed7e9)));
flock($V633de4b0,3);
fclose($V633de4b0);
$V078171f8 = array('');
$V078171f8 = split("\|",$V32762e5d);
if($_POST["Limit"]) { $V078171f8[14]=$Vfbedf566; echo 'Limit - '; }
if($_POST["Group"]) { $V078171f8[18]=$Vcaff3ae3; echo 'Group - '; }
if($_POST["Popups"]) { $V078171f8[24]=$V49bfffef; echo 'Pop-ups - '; }
if($_POST["Lang"]) { $V078171f8[29]=$V53205c55; echo 'Language - '; }
if($_POST["ForceTime"]) { $V078171f8[11]=$V9a384c58; echo 'Force Time - '; }
if($_POST["ForceAmount"]) { $V078171f8[12]=$V3d436fd6; echo 'Force Amount - '; }
if($V633de4b0 = @fopen($V435ed7e9,'w'))
 {
 flock($V633de4b0,2);
fputs($V633de4b0,$V078171f8[0].'|'.$V078171f8[1].'|'.$V078171f8[2].'|'.$V078171f8[3].'|'.$V078171f8[4].'|'.$V078171f8[5].'|'.$V078171f8[6].'|'.$V078171f8[7].'|'.$V078171f8[8].'|'.$V078171f8[9].'|'.$V078171f8[10].'|'.$V078171f8[11].'|'.$V078171f8[12].'|'.$V078171f8[13].'|'.$V078171f8[14].'|'.$V078171f8[15].'|'.$V078171f8[16].'|'.$V078171f8[17].'|'.$V078171f8[18].'|'.$V078171f8[19].'|'.$V078171f8[20].'|'.$V078171f8[21].'|'.$V078171f8[22].'|'.$V078171f8[23].'|'.$V078171f8[24].'|'.$V078171f8[25].'|'.$V078171f8[26].'|'.$V078171f8[27].'|'.$V078171f8[28].'|'.$V078171f8[29].'|'.$V078171f8[30].'|'.$V078171f8[31].'|'.$V078171f8[32].'|'.$V078171f8[33].'|'.$V078171f8[34].'|'.$V078171f8[35].'|'.$V078171f8[36].'|'.$V078171f8[37].'|');
flock($V633de4b0,3);
fclose($V633de4b0);
echo '<b>Updated</b><br>';
}
else { echo '<b>Error - Cant write to '.$V15e05c6d[$V865c0c0b].'.dat</b><br>'; }
}
else { echo '<b>Error - Cant open '.$V15e05c6d[$V865c0c0b].'.dat</b><br>'; }
}
}
?>
</td></tr>
</table>
<br>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<form method=POST>
<td align=center><input type=submit name=main value='Main Menu'></td>
</tr>
</table>
<?
}
 
else if($_POST["massdelete"])
{
?>
<form method=POST>
<table cellspacing=0 cellpadding=3 width=90%>
<tr><td align=center>
<b>Mass Delete Trades</b>
<br>
<?
	if($_POST["memlist"])
	{
 $V15e05c6d=array('');
$V15e05c6d=split('\|',$memlist);
for($V865c0c0b=0;$V865c0c0b<(sizeof($V15e05c6d)-1);$V865c0c0b++)
 {
 echo $V15e05c6d[$V865c0c0b].' ';
}
?>
<br><br>
<b><font color=red>Are You Sure You Want To Delete Selected Members?</font><b>
<br>
<input type=hidden name=memlist value=<?echo $memlist?>>
<input type=submit name=massdeletedoit value='Delete Selected Trades'>
</th>
</tr>
</table>
<?
	}
else
	{
 echo '<b>You have not selected any trader</b>';
}
?>
</td></tr>
</table>
<br>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<td align=center><input type=submit name=main value='Main Menu'></td>
</tr>
</table>
</form>
<?
}
 
else if($_POST["massdeletedoit"])
{
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr><td align=center>
<b>Traders Deleted</b>
<br>
<?
	if($_POST["memlist"])
	{
 $V15e05c6d=array('');
$V15e05c6d=split('\|',$memlist);
for($V865c0c0b=0;$V865c0c0b<(sizeof($V15e05c6d)-1);$V865c0c0b++)
 {
 echo ($V15e05c6d[$V865c0c0b].' - ');
@unlink('memberfiles/'.$V15e05c6d[$V865c0c0b].'.csv');
if(unlink('memberfiles/'.$V15e05c6d[$V865c0c0b].'.dat'))
 {
 echo '<b>Deleted</b><br>';
}
else { echo '<b>Error - Cant delete '.$V15e05c6d[$V865c0c0b].'.dat</b><br>'; }
}
}
?>
</td></tr>
</table>
<br>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<form method=POST>
<td align=center><input type=submit name=main value='Main Menu'></td>
</tr>
</table>
<?
}
 
else if($_POST["unmarknew"])
{
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr><td align=center>
<b>New Traders Unmarked</b>
<br>
<?
	$V2b75de9a=array();
$V8277e091=dir('memberfiles');
while($V1043bfc7=$V8277e091->read())
	{
 if(strstr($V1043bfc7,'.dat')) { $V2b75de9a[]=$V1043bfc7; }
}
$V8277e091->close();
for($V865c0c0b=0;$V865c0c0b<sizeof($V2b75de9a);$V865c0c0b++)
	{
 $V435ed7e9='memberfiles/'.$V2b75de9a[$V865c0c0b];
$V078171f8=array('');
if(Fb0b3b672($V435ed7e9,"\|",$V078171f8))
 {
 if ($V078171f8[18]=='new')
 {
 echo(str_replace('.dat','',$V2b75de9a[$V865c0c0b]).' - ');
$V078171f8[18]=$Vf1cb89ef;
if(F93adb31d($V435ed7e9,implode('|',$V078171f8)))
 {
 echo '<b>Unmarked</b><br>';
}
else { echo '<b>Error - Cant write to '.$V15e05c6d[$V865c0c0b].'.dat</b><br>'; }
}
}
else { echo '<b>Error - Cant open '.$V15e05c6d[$V865c0c0b].'.dat</b><br>'; }
}
?>
</td></tr>
</table>
<br>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<form method=POST>
<td align=center><input type=submit name=main value='Main Menu'></td>
</tr>
</table>
<?
}
 
else if($_POST["Vf17ca2c8"])
{
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<td align=center colspan=2>
<b>Redirect of Incoming Traffic by Browser Language</b>
</td>
</tr>
<tr>
<form method=POST>
<td align=center colspan=2>
<table cellspacing=1>
<th>Browser Language</th>
<th>Trader Name</th>
<th>or Direct URL</th>
<?
	$V15e05c6d=array();
$V8277e091=dir('memberfiles');
while($V1043bfc7=$V8277e091->read())
	{
 if((strstr($V1043bfc7,'.dat'))&&($V1043bfc7!='sponsor.dat')) { $V15e05c6d[]=str_replace('.dat','',$V1043bfc7); }
}
$V8277e091->close();
sort($V15e05c6d);
$Vf17ca2c8=array('');
Fb0b3b672('datafiles/redirect.dat',"\r\n",$Vf17ca2c8);
$V202620a9=sizeof($Vf17ca2c8);
if (!$V202620a9) { $V202620a9=1; }
for($V865c0c0b=0;$V865c0c0b<$V202620a9;$V865c0c0b++)
	{
 $V4124bc0a=array('');
$V4124bc0a=split("\|",$Vf17ca2c8[$V865c0c0b]);
echo('<tr><td><input type=text name=redirlang['.$V865c0c0b.'] value="'.$V4124bc0a[0].'"></td>');
echo('<td><select name=redirtrader['.$V865c0c0b.']><option></option>\n');
for($V363b122c=0;$V363b122c<sizeof($V15e05c6d);$V363b122c++)
 {
 if ($V15e05c6d[$V363b122c]==$V4124bc0a[1])
 {
 $V2e85ee7c='selected';
}
else
 {
 $V2e85ee7c='';
}
echo "<option $V2e85ee7c>$V15e05c6d[$V363b122c]</option>\n";
}
echo('</select></td>');
if (strstr($V4124bc0a[1],'http://')) { $V5ed40f0f=$V4124bc0a[1]; }
else { $V5ed40f0f=''; }
echo('<td><input type=text name=redirurl['.$V865c0c0b.'] value="'.$V5ed40f0f.'"></td></tr>');
}
?>
</table>
</td>
</tr>
<tr>
<td align=right><input type=submit name=updateredirect value=Update></td>
<td align=left><input type=submit name=main value=Main Menu></td>
</form>
</tr>
</table>
<?
}
 
else if($_POST["updateredirect"])
{
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr><td align=center>
<?
	$V06320e28='';
for($V865c0c0b=0;$V865c0c0b<sizeof($redirlang);$V865c0c0b++)
	{
 if ($redirtrader[$V865c0c0b]) { $Vf17ca2c8=$redirtrader[$V865c0c0b]; }
else { $Vf17ca2c8=$redirurl[$V865c0c0b]; }
if ($redirlang[$V865c0c0b] && $Vf17ca2c8)
 {
 $V06320e28.=$redirlang[$V865c0c0b].'|'.$Vf17ca2c8."\r\n";
}
}
$V435ed7e9='datafiles/redirect.dat';
if(F93adb31d($V435ed7e9,&$V8d777f38))
	{
 if(!$V06320e28) { unlink($V435ed7e9); }
echo '<b>Redirect is Updated</b>';
}
else { echo '<b>Error! Cant open file redirect.dat</b>'; }
?>
</td></tr>
</table>
<br>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<form method=POST>
<input type=hidden name=V53d670af value='<?echo $V53d670af?>'>
<td align=center><input type=submit name=main value='Main Menu'></td>
<td align=center><input type=submit name=Vf17ca2c8 value=Redirect></td>
</tr>
</table>
<?
}
 
else if($_POST["addtrader"])
{
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<form method=POST>
<td align=center colspan=2>
<b>Add New Trader</b>
<br><br>
<table cellspacing=1 cellpadding=1>
<tr>
<th>
<table cellspacing=1 cellpadding=1>
<tr>
	<td align=right>Domain: </td>
	<td><input type=text name=V14c4b06b></td>
</tr><tr>
	<td align=right>URL: </td>
	<td><input type=text name=Vdc85be65 value=http://></td>
</tr><tr>
	<td align=right>Site name for toplist: </td>
	<td><input type=text name=V99c2db49></td>
</tr><tr>
	<td align=right>Pop-ups: </td>
	<td><input type=text name=popups value=<? echo $V14ca2a15 ?>></td>
</tr><tr>
	<td align=right>Email: </td>
	<td><input type=text name=V9b921424></td>
</tr><tr>
	<td align=right>ICQ #: </td>
	<td><input type=text name=Ve02a798b></td>
</tr><tr>
	<td align=right>Force Hit Time: </td>
	<td><input type=text name=V1c5fa1f5 value='<?echo $V5ca52301?>'></td>
</tr><tr>
	<td align=right>Number Of Hits To Force: </td>
	<td><input type=text name=Vb5e02752 value='<?echo $V8dfc6579?>'></td>
</tr><tr>
	<td align=right>Force Type: </td>
	<td>
	<select name=V22d017f4>
<?
	for($V865c0c0b=0;$V865c0c0b<sizeof($V39bb4215);$V865c0c0b++)
	{
 echo "<option value=$V865c0c0b>$V39bb4215[$V865c0c0b]</option>\n";
}
?>
	</select>
	</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td align=center><input type=submit name=main value='Main Menu'></td>
<td align=center><input type=submit name=savenewtrader value='Add Trader'></td>
</form>
</tr>
</table>
<?
}
 
else if($_POST["savenewtrader"])
{
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr><td align=center>
<?
	$mem=str_replace('.','',$V14c4b06b);
$mem=str_replace('/','',$mem);
$Vafbe94cd=$V14c4b06b;
$Vcb5e100e=0;
$V435ed7e9='memberfiles/'.$mem.'.dat';
if(file_exists($V435ed7e9)) { $V6e2baaf3.='That domain is allready in use'; $Vcb5e100e = 1; }
if(!$V14c4b06b) { $V6e2baaf3 .='You must enter a domain<br>'; $Vcb5e100e = 1; }
if((strstr($V14c4b06b,' '))||(strstr($V14c4b06b,':'))) { $V6e2baaf3 .='Invalid character in domain'; $Vcb5e100e = 1; }
if((!strstr($Vdc85be65,'http://'))&&(!strstr($Vdc85be65,'php'))) { $V6e2baaf3 .='The URL to send traffic to MUST include http://'; $Vcb5e100e = 1; }
if ($Vcb5e100e == 1)
	{
 echo('<b>Oops!</b><br>'.$V6e2baaf3.'<br>');
}
else
	{
 $Vc5e7dfaf=date("m.d.Y");
$V06cde2a7=date("g:i A");
if($V633de4b0=@fopen('memberfiles/'.$mem.'.dat','w'))
 {
 flock($V633de4b0,2);
fseek($V633de4b0,0);
fputs($V633de4b0,time().'|0|'.$V9b921424.'|'.$Ve02a798b.'||0|0|0|0|0|0|'.$V1c5fa1f5.'|'.$Vb5e02752.'|no reset|'.$V94a7c36b.'|'.$Vdc85be65.'|'.$V22d017f4.'|'.$Vafbe94cd.'|new|0|0|0|'.$V99c2db49.'||'.$popups.'||||||||||||||');
flock($V633de4b0,3);
fclose($V633de4b0);
}
@chmod('memberfiles/'.$mem.'.dat',0666);
?>
Trader <?echo $mem?> was added successfully<br>
URL to send hits to:<br><a href="<?echo $V861ce498; if($V00dd8e4a) {echo ('?id='.$mem);}?>"><font size=+1><?echo $V861ce498; if($V00dd8e4a) {echo ('?id='.$mem);}?></font></a><br><br>
<?
	}
?>
</td></tr>
</table>
<br>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<form method=POST>
<input type=hidden name=V53d670af value='<?echo $V53d670af?>'>
<td align=center><input type=submit name=main value='Main Menu'></td>
<td align=center><input type=submit name=addtrader value='Add Trader'></td>
</tr>
</table>
<?
}
 
else if($_POST["Vcf2861e0"])
{
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<form name=rotchange method=POST>
<th colspan=2>
<b>Content Rotator</b>
<select name=V26315627 onchange=document.all.rotchange.submit()>
<?
	$Vc9c882ff=0;
for($V865c0c0b=1;$V865c0c0b<=10;$V865c0c0b++)
	{
 if(!file_exists('datafiles/rotator'.$V865c0c0b.'.dat')) { break; }
$Vc9c882ff++;
}
if(!$V26315627){$V26315627=1;}
for($V865c0c0b=1;$V865c0c0b<=($Vc9c882ff+1);$V865c0c0b++)
	{
 $V462d7dc9='';
if($V865c0c0b==$V26315627){$V462d7dc9=' selected';}
echo('<option'.$V462d7dc9.'>'.$V865c0c0b.'</option>');
}
?>
</select>
<input type=hidden name=Vcf2861e0 value=1>
</th>
</form>
</tr>
<?
	$V637f5a12=$Vea3cc89e=array();
if ($Vbd75f4d2)
	{
 $V6d2c5a78=$V3e229d62=0;
$V8277e091=dir($Vbd75f4d2);
while($V1043bfc7=$V8277e091->read())
 {
 if((!is_dir($V1043bfc7)))
 {
 if((!$V851f5ac9)||(strstr($V1043bfc7,$V851f5ac9))) { $V637f5a12[$V6d2c5a78++]=$Vbd75f4d2.'/'.$V1043bfc7; }
else { $Vea3cc89e[$V3e229d62++]=$Vbd75f4d2.'/'.$V1043bfc7; }
}
}
$V8277e091->close();
sort($V637f5a12);
sort($Vea3cc89e);
}
else
	{
 $Vc53c53ad=array();
Fb0b3b672('datafiles/rotator'.$V26315627.'.dat',"\r\n",$Vc53c53ad);
$Vcf2861e0=array();
for($V865c0c0b=0;$V865c0c0b<count($Vc53c53ad);$V865c0c0b++)
 {
 $Vcf2861e0=split(';',$Vc53c53ad[$V865c0c0b]);
$Vea3cc89e[$V865c0c0b]=$Vcf2861e0[0];
$V637f5a12[$V865c0c0b]=$Vcf2861e0[1];
}
}
?>
<form method=POST>
<tr>
<td align=center>
<b>Text or Thumbs</b><br>
<textarea name=Vd8009d31 cols=40 rows=20 wrap=OFF>
<?
	for($V865c0c0b=0;$V865c0c0b<count($V637f5a12);$V865c0c0b++)
	{
 echo $V637f5a12[$V865c0c0b]."\r\n";
}
?>
</textarea>
</td>
<td align=center>
<b>Content - html pages, pics, etc (optional)</b><br>
<textarea name=Vcfd435da cols=40 rows=20 wrap=OFF>
<?
	for($V865c0c0b=0;$V865c0c0b<count($Vea3cc89e);$V865c0c0b++)
	{
 echo $Vea3cc89e[$V865c0c0b]."\r\n";
}
?>
</textarea>
</td>
</tr>
<tr>
<td align=center><input type=submit name=main value='Main Menu'></td>
<td align=center><input type=submit name=updaterotator value='Update Rotator'></td>
<input type=hidden name=V26315627 value=<?echo $V26315627;?>>
</tr>
<tr>
<th colspan=2>
<b>Import Content/Thumbs</b>
</th>
</tr>
<tr>
<td align=center colspan=2>
Content directory
<select name=Vbd75f4d2>
<?
	$V8277e091=dir('.');
while($V1043bfc7=$V8277e091->read())
	{
 if(is_dir($V1043bfc7)&&($V1043bfc7!= '..')&&($V1043bfc7!= 'datafiles')&&($V1043bfc7!= 'memberfiles')&&($V1043bfc7!= 'backupfiles'))
 {
 if($Vbd75f4d2==$V1043bfc7) { $V8be74552='selected'; }
else { $V8be74552=''; }
echo "<option value='$V1043bfc7' $V8be74552>$V1043bfc7</option>\n";
}
}
$V8277e091->close();
?>
</select>
Thumbs prefix or extension
<input type=text name=V851f5ac9 size=10 value='<?echo $V851f5ac9?>'>
<input type=hidden name=rotatorimport>
<input type=submit name=Vcf2861e0 value='Import'>
</td>
</tr>
</table>
<br>
<?
}
 
else if($_POST["updaterotator"])
{
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr>
<td align=center colspan=2>
<?
	$Va10e01c7=$V65e3ce90=array();
$Va10e01c7=split("\r\n",$Vd8009d31);
$V65e3ce90=split("\r\n",$Vcfd435da);
$Vd414262e='';
$Vb96c4ec8=0;
for($V865c0c0b=0;$V865c0c0b<count($Va10e01c7);$V865c0c0b++)
	{
 if($Va10e01c7[$V865c0c0b])
 {
 $Vd414262e.=$V65e3ce90[$V865c0c0b].';'.$Va10e01c7[$V865c0c0b]."\r\n";
$Vb96c4ec8=1;
}
}
if(F93adb31d('datafiles/rotator'.$V26315627.'.dat',$Vd414262e))
	{
 echo '<b>Rotator is Updated</b>';
}
else { echo '<b>Error! Cant write to rotator.dat</b>'; }
@unlink('datafiles/rotstat'.$V26315627.'.dat');
if(!$Vb96c4ec8) { unlink('datafiles/rotator'.$V26315627.'.dat'); }
?>
</td>
</tr>
<tr>
<td align=center colspan=2>
</textarea>
</td>
</tr>
<tr>
<form method=POST>
<td align=center><input type=submit name=main value='Main Menu'></td>
<td align=center><input type=submit name=Vcf2861e0 value=Rotator></td>
<input type=hidden name=V26315627 value=<?echo $V26315627;?>>
</tr>
</table>
<?
}
 
else if($_POST["upload"])
{
?>
<table cellspacing=0 cellpadding=3 width=90%>
<tr><td align=center>
<b>File Manager</b><br>
Do not use file manager for uploading of main page<br><br>
<?
	if(is_uploaded_file($_FILES['userfile']['tmp_name']))
	{
 $V6b88677a='';
if($_FILES['userfile']['size']>100000) { $V6b88677a.='File cant be over 100KB. '; }
  
 if(strstr($_FILES['userfile']['name'],'.php')) { $V6b88677a.='You cant upload php-files. '; }
if(!$V6b88677a)
 {
 copy($_FILES['userfile']['tmp_name'],$_FILES['userfile']['name']);
echo 'File '.$_FILES['userfile']['name'].' uploaded successfully';
}
else { echo 'Error! '.$V6b88677a; }
}
if($V9e2ba431)
	{
 if(@unlink($_POST["V9e2ba431"])) { echo 'File '.$V9e2ba431.' deleted successfully'; }
else { echo 'Error! Cant delete '.$V9e2ba431; }
}
?>
</td></tr>
<tr>
<td align=center>
<table>
<tr>
<form enctype='multipart/form-data' method=post>
<th>
<input type=hidden name=MAX_FILE_SIZE value=100000>
Upload File: <input name=userfile type=file>
<input type=submit name=upload value='Upload File'>
</th>
</form>
</tr>
</table>
<br>
<table>
<tr><th colspan=2>Files</th></tr>
<?
	$V8277e091=dir('.');
while($V1043bfc7=$V8277e091->read())
	{
 if((!is_dir($V1043bfc7)))
 {
 if((!strstr($V1043bfc7,'.php'))&&(!strstr($V1043bfc7,'stat')))
 {
 echo('<tr><th align=left><a target=_blank href="'.$Vf1d185c4.'/'.$V1043bfc7.'">'.$V1043bfc7.'</a></th><form method=post><th><input type=hidden name=V9e2ba431 value="'.$Vf1d185c4.'/'.$V1043bfc7.'"><input type=submit name=upload value=Delete></th></form></tr>'."\n");
}
}
}
$V8277e091->close();
?>
</table>
</td>
</tr>
<tr>
<form method=POST>
<td align=center><input type=submit name=main value='Main Menu'></td>
</form>
</tr>
</table>
<br>
<?
}
 
else
{
	$adminconfig=@unserialize(@implode('',file('datafiles/adminconfig.dat')));
if(!$adminconfig) {$adminconfig=array_fill(0,26,1);}
$V5fa3852b=array('');
Fb0b3b672('datafiles/cheat.dat',"\|",$V5fa3852b);
?>
<script>
function F55f1a468(box)
{
	MassList=document.all.memlist.value;
ChangedText=box.name+'|';
if (box.checked)
	{
 MassList+=ChangedText;
}
else
	{
 ind=MassList.indexOf(ChangedText);
if (ind>=0)
 {
 MassList=MassList.substr(0,ind)+MassList.substr(ind+ChangedText.length);
}
}
document.all.memlist.value=MassList;
}
function F25f97bdb()
{
	for (i=0;i<document.all.length;i++)
	{
 if (document.all[i].type=='checkbox')
 {
 if (document.all[i].name!='checkall')
 {
 if ((document.all.setall.checked)^(document.all[i].checked))
 {
 document.all[i].click();
}
}
}
}
}
</script>
<table cellspacing=0>
<tr align=center>
<form method=POST>
<td>
<input type=submit name=main value=Reload title='Reload Admin Page'>
<input type=submit name=forceupdate value=Update title='Stats Recalculate'>
<input type=submit name=addtrader value=Add title='Add New Trader'>
<input type=submit name=viewlogs value=Logs title='View Logs'>
<input type=submit name=massedit value=Edit title='Mass Edit Traders'>
<input type=submit name=settings value=Settings title='Edit Global Settings'>
<input type=submit name=upload value=Upload title='File Manager'>
<input type=submit name=Vcf2861e0 value=Rotator title='Content Rotator'>
<script>
if(screen.availWidth<1024){ document.write('</td></tr><tr><td>');}
</script>
<input type=submit name=edittemplates value=Face title='Edit Templates'>
<input type=submit name=editrules value=Rules title='Edit Webmaster Rules'>
<input type=submit name=editblacklist value=Black title='Edit BlackList'>
<input type=submit name=massdelete value=Delete title='Mass Delete Traders'>
<input type=submit name=Vf17ca2c8 value=Redirect title='Redirect of Incoming Traffic'>
<input type=submit name=topchecker value=Top title='Find TopLists Reset Time'>
<input type=submit name=unmarknew value=Unmark title='Unmark New Traders'>
<input type=submit name=logout value=Logout>
</td>
<input type=hidden name=memlist>
</tr>
</table>
</form>
<?
	$V2b75de9a=array();
$V8277e091=dir('memberfiles');
while($V1043bfc7=$V8277e091->read())
	{
 if(strstr($V1043bfc7,'.dat')) { $V2b75de9a[]=$V1043bfc7; }
}
$V8277e091->close();
$Vf9e1fb3d=array('');
$V32762e5d=array('');
for($V865c0c0b=0;$V865c0c0b<sizeof($V2b75de9a);$V865c0c0b++)
	{
 $V435ed7e9='memberfiles/'.$V2b75de9a[$V865c0c0b];
F43605c4d($V435ed7e9,$V32762e5d[$V865c0c0b]);
$V078171f8=array('');
$V078171f8=split("\|",$V32762e5d[$V865c0c0b]);
if ($V2b75de9a[$V865c0c0b] == 'auxout.dat') { $Vf9e1fb3d[$V865c0c0b]='100000|'.$V865c0c0b; }
elseif ($V2b75de9a[$V865c0c0b] == 'nocookie.dat') { $Vf9e1fb3d[$V865c0c0b]='100001|'.$V865c0c0b; }
elseif ($V2b75de9a[$V865c0c0b] == 'direct.dat') { $Vf9e1fb3d[$V865c0c0b]='100002|'.$V865c0c0b; }
elseif ($V2b75de9a[$V865c0c0b] == 'sponsor.dat') { $Vf9e1fb3d[$V865c0c0b]='100003|'.$V865c0c0b; }
else { $Vf9e1fb3d[$V865c0c0b]=$V078171f8[7]+$V078171f8[20].'|'.$V865c0c0b; }
}
rsort($Vf9e1fb3d,SORT_NUMERIC);
$V67afdedd=$V8f7e4c2e=$V7d893251=$Ved186dde=$V8c445acc=$V293e5def=$Vb1bd460b=0;
for($V865c0c0b=0;$V865c0c0b<sizeof($V2b75de9a);$V865c0c0b++)
	{
 $Ve8059791=$V078171f8=array('');
$Ve8059791=split("\|",$Vf9e1fb3d[$V865c0c0b]);
$V078171f8=split("\|",$V32762e5d[$Ve8059791[1]]);
$Vf8bff26e=str_replace('\.dat','',$V2b75de9a[$Ve8059791[1]]);
if($Vf8bff26e!='sponsor')
 {
 $V67afdedd+=$V078171f8[19];
$V8f7e4c2e+=$V078171f8[26];
$V7d893251+=$V078171f8[21];
$Ved186dde+=$V078171f8[20];
$V8c445acc+=$V078171f8[33];
}
}
if($V8f7e4c2e){$V293e5def=round($Ved186dde*100/$V8f7e4c2e);$V30c19e96=round($V8c445acc*100/$V8f7e4c2e);}
$outpick='';
$V435ed7e9='datafiles/outpick.dat';
F43605c4d($V435ed7e9,$outpick);
?>
<table cellspacing=1 cellpadding=3>
<tr>
	<th>24 Hours</th>
	<td>In - <?echo $V67afdedd?></td>
	<td>RIn - <?echo $V8f7e4c2e?></td>
	<td>Out - <?echo $V7d893251?></td>
	<td>Click - <?echo $Ved186dde?></td>
	<td<?if($V30c19e96){echo(' title="direct='.$V30c19e96.'% summary='.($V293e5def+$V30c19e96).'%"');}?>>Prod - <?echo $V293e5def?>%</td>
<?
	$Vdad12808=array();
$Vdad12808=split('-',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
$V5291cd1d=array();
if($V5cf1c824)
	{
 if((Fb0b3b672('ap'.$Vdad12808[0].'.txt',"\n",$V5291cd1d)) || (Fb0b3b672('apen.txt',"\n",$V5291cd1d)))
 {
 srand ((float)microtime()*10000000);
echo('<td>'.$V5291cd1d[array_rand($V5291cd1d) ].'</td>');
}
}
?>
</tr>
</table>
<br>
<table cellspacing=1 cellpadding=1 width=95%>
<?
	$Vf921bd18=$V88d67324=$Vd5159755=$V60edd3c9=$V67afdedd=$Vdbc3017a=$V8f7e4c2e=$V312cf491=$V7d893251=$Ved186dde=$V8c445acc=$V89654807=$Vf96e37e7=$V781e08dd=$V9b2e4cef=0;
for($V865c0c0b=0;$V865c0c0b<sizeof($V2b75de9a);$V865c0c0b++)
	{
 if(!($V865c0c0b%20)) { F9bca221a(); }
$Ve8059791=array('');
$Ve8059791=split("\|",$Vf9e1fb3d[$V865c0c0b]);
$Vf8bff26e=str_replace('.dat','',$V2b75de9a[$Ve8059791[1]]);
if($Vf8bff26e!='sponsor')
 {
 $V078171f8=array('');
$V078171f8=split("\|",$V32762e5d[$Ve8059791[1]]);
$Ve449f731=$V7e305609=$V23d2f70e=$V25e299ff=$V871aed25='';
if(($V078171f8[25]+$V078171f8[26])>0)
 {
 $Ve449f731=round(($V078171f8[7]+$V078171f8[20])*100/($V078171f8[25]+$V078171f8[26]));
$V7e305609=round(($V078171f8[32]+$V078171f8[33])*100/($V078171f8[25]+$V078171f8[26]));
}
if(($V078171f8[9]+$V078171f8[21])>0)
 {
 $V23d2f70e=round(($V078171f8[5]+$V078171f8[19])*100/($V078171f8[9]+$V078171f8[21]));
if(($Vf8bff26e!='auxout')&&($Vf8bff26e!='nocookie')) { $V25e299ff=round(($V078171f8[7]+$V078171f8[20])*100/($V078171f8[9]+$V078171f8[21])); }
$V7d46fd43=round(($V078171f8[34]+$V078171f8[35])*100/($V078171f8[9]+$V078171f8[21]));
}
$V0fee87ad=$V078171f8[7]+$V078171f8[20];
$Vef6b342b=$V078171f8[36]+$V078171f8[37];
$Vf401eb74=$V078171f8[27]+$V078171f8[28];
$V670a9d8f=$Vf401eb74-$V0fee87ad*$V2914baf9;
if($V670a9d8f<0) { $V670a9d8f=0; }
$V41f302d0=$V0fee87ad-$V670a9d8f;
if($V0fee87ad) { $V41f302d0-=$Vef6b342b*(1-$Vc39f66b1)*$V41f302d0/$V0fee87ad; }
if(($Vf8bff26e!='auxout')&&($Vf8bff26e!='nocookie')&& $V41f302d0)
 {
 if ($V078171f8[9]+$V078171f8[21]) { $V871aed25=round($V41f302d0*100/($V078171f8[9]+$V078171f8[21])); }
else { $V871aed25='&infin;'; }
}
$V62d4c3bc='';
if (($V5fa3852b[1])&&($V078171f8[7]+$V078171f8[20]))
 {
 $V7d46fd43=($V078171f8[34]+$V078171f8[35]);
$V3953f98b=$V7d46fd43/($V078171f8[7]+$V078171f8[20]);
if (!$V3953f98b)
 {
 $V62d4c3bc='&infin;';
}
elseif ($V3953f98b>$V5fa3852b[1])
 {
 $V62d4c3bc=round($V3953f98b*100/$V5fa3852b[1])-100;
}
else
 {
 $V62d4c3bc=round($V5fa3852b[1]*100/$V3953f98b)-100;
}
}
if(($Vf8bff26e!='auxout')&&($Vf8bff26e!='nocookie'))
 {
 $V752cc430=0;
if($V078171f8[14]) { $V752cc430=($V078171f8[7]+$V078171f8[20])*$V078171f8[14]-($V078171f8[9]+$V078171f8[21]); }
$V752cc430 = round($V752cc430);
}
$V431387eb=$V013dff02='';
if($V0fee87ad>0)
 {
 $V431387eb=round(100*$Vf401eb74/$V0fee87ad);
$V013dff02=round(100*$Vef6b342b/$V0fee87ad);
}
$Vf921bd18+=$V078171f8[5];
$Vdbc3017a+=$V078171f8[25];
$V88d67324+=$V078171f8[9];
$Vd5159755+=$V078171f8[7];
$V60edd3c9+=$V078171f8[32];
$V67afdedd+=$V078171f8[19];
$V8f7e4c2e+=$V078171f8[26];
$V312cf491+=$V078171f8[27]+$V078171f8[28];
$V9b2e4cef+=$V078171f8[36]+$V078171f8[37];
$V7d893251+=$V078171f8[21];
$Ved186dde+=$V078171f8[20];
$V8c445acc+=$V078171f8[33];
$V89654807+=$V752cc430;
if((!$V078171f8[23])&&(!$V078171f8[30]))
 {
 $Vf96e37e7+=$V078171f8[12];
$V781e08dd+=$V078171f8[31];
}
?>
<tr onmouseover=con(this) onmouseout=coff(this) align=center>
<?
	if($adminconfig[0])
	{
 if (($Vf8bff26e=='auxout')||($Vf8bff26e=='nocookie')||($Vf8bff26e=='direct')||($Vf8bff26e=='sponsor')) { echo ('<td></td>'); }
else
 {
 echo('<td');
if((time()-$V078171f8[0])>($V98c4bb2d*86400)) { echo ' bgcolor=#FFCC00 title=Dead';} echo('>');
echo ('<input type=checkbox name="'.$Vf8bff26e.'" onclick=F55f1a468(this)>');
echo('</td>');
}
}
if($adminconfig[1])
	{
?>
<td <?if ($V078171f8[18]=='new') { echo 'bgcolor=#FF0000 title=New';} elseif(strstr($outpick,$Vf8bff26e)) { echo 'bgcolor=#99CC99 title=Active';} else { echo 'title=Inactive';}?>>
<form name=mems<?echo $V865c0c0b?> method=POST>
<input type=hidden name=editmember value=yes>
<input type=hidden name=V53d670af value=<?echo $Vf8bff26e?>>
<?if(($Vf8bff26e!='direct')&&($Vf8bff26e!='nocookie')) { echo('<a href=# onclick="document.all.mems'.$V865c0c0b.'.submit();return false">Edit</a>'); }?>
</td></form>
<?
	}
if($adminconfig[2])
	{
?>
<td>
<form name=mem<?echo $V865c0c0b?> method=POST>
<input type=hidden name=memberstats value=yes>
<input type=hidden name=V53d670af value=<?echo $Vf8bff26e?>>
<a href=# onclick='document.all.mem<?echo $V865c0c0b?>.submit();return false'>Stats</a>
</td></form>
<?
	}
?>
<td align=left>
<?
	if(($Vf8bff26e!='direct')&&($Vf8bff26e!='nocookie'))
	{
 $V5a27bcc3='';
$V3c430307=$V078171f8[17];
if(strlen($V3c430307)>20)
 {
 $V5a27bcc3=$V3c430307;
$V3c430307=substr($V3c430307,0,19).'..';
}
echo('<a href="'.$V078171f8[15].'" target=_blank title="'.$V5a27bcc3.'">'.$V3c430307.'</a>');
if(($Vf3778551)&&($V078171f8[1]>0))
 {
 echo ' <small>GB</small>';
}
}
else { echo $V078171f8[17]; }
?>
</td>
<?
	if($adminconfig[4])
	{
?>
<td>
<small>
<?
	if($V078171f8[2]){echo('<a target=_blank href="mailto:'.$V078171f8[2].'">m</a>');}
if($V078171f8[3])
	{
 echo('<a target=_blank href="http://wwp.icq.com/scripts/contact.dll?msgto='.$V078171f8[3].'">');
if($Vd3a4258b){echo('<img src="http://web.icq.com/whitepages/online?img=5&icq='.$V078171f8[3].'" width=18 height=18 border=0>');}
else{echo('i');}
echo('</a>');}
?>
</small>
</td>
<?
	}
if($adminconfig[5]){echo'<td id=1>';if ($V078171f8[5]) echo ($V078171f8[5]);echo('</td>');}
if($adminconfig[6]){echo'<td id=2>';if ($V078171f8[9]) echo ($V078171f8[9]);echo('</td>');}
if($adminconfig[7])
	{
?>
<td id=3<?if($V078171f8[32]){echo(' title="content='.$V078171f8[32].' summary='.($V078171f8[7]+$V078171f8[32]).'"');}?>><?if ($V078171f8[7]) echo ($V078171f8[7])?></td>
<?
	}
if($adminconfig[5]+$adminconfig[6]+$adminconfig[7]){echo'<th></th>';}
if($adminconfig[8]){echo'<td id=4>';if ($V078171f8[19]) echo ($V078171f8[19]);echo('</td>');}
if($adminconfig[9]){echo'<td id=5>';if ($V078171f8[21]) echo ($V078171f8[21]);echo('</td>');}
if($adminconfig[10]){echo'<td id=6>';if ($V078171f8[20]) echo ($V078171f8[20]);echo('</td>');}
if($adminconfig[11]){echo'<td id=7>';if ($V078171f8[33]) echo ($V078171f8[33]);echo('</td>');}
if($adminconfig[12]){echo'<td id=8>';if ($V078171f8[26]) echo ($V078171f8[26]);echo('</td>');}
if($adminconfig[8]+$adminconfig[9]+$adminconfig[10]+$adminconfig[11]+$adminconfig[12]){echo'<th></th>';}
?>
<?if($adminconfig[13]){?>
<td id=9><?if ($V431387eb) echo($V431387eb)?></td>
<?}if($adminconfig[14]){?>
<td id=10><?if ($V013dff02) echo($V013dff02)?></td>
<?}if($adminconfig[15]){?>
<td id=11<?if($V7e305609){echo(' title="content='.$V7e305609.' summary='.($Ve449f731+$V7e305609).'"');}?>><?if ($Ve449f731) echo($Ve449f731)?></td>
<?}if($adminconfig[16]){?>
<td id=12><?if ($V23d2f70e) echo($V23d2f70e)?></td>
<?}if($adminconfig[17]){?>
<td id=13<?if(($V078171f8[20]>50)&&($Vf8bff26e!='auxout')){if($V62d4c3bc>200){ echo' bgcolor=#ffcc00'; } elseif($V62d4c3bc>100){ echo' bgcolor=#99CC99'; }} if ($V7d46fd43) echo (' title="test clicks = '.$V7d46fd43.' / '.round($V3953f98b*100).'%"')?>><?if ($V62d4c3bc!='') echo($V62d4c3bc)?></td>
<?}if($adminconfig[18]){?>
<td id=14><?if ($V25e299ff) echo($V25e299ff)?></td>
<?}if($adminconfig[19]){?>
<td id=15><?if ($V871aed25) echo('<b>'.$V871aed25.'</b>')?></td>
<?
	}
if($adminconfig[13]+$adminconfig[14]+$adminconfig[15]+$adminconfig[16]+$adminconfig[17]+$adminconfig[18]+$adminconfig[19]){echo'<th></th>';}
if($adminconfig[20])
	{
?>
<td id=16 title=limit/owed><?echo $V078171f8[14]; if ($V752cc430) echo '<br><small>'.$V752cc430.'</small>'?></td>
<?
	}
if($adminconfig[21])
	{
 $V40e03bf8='';
$V7c13c7fc=$V078171f8[18];
if(strlen($V7c13c7fc)>8)
 {
 $V40e03bf8=$V7c13c7fc;
$V7c13c7fc=substr($V7c13c7fc,0,7).'..';
}
?>
<td id=17 title='<?echo $V40e03bf8?>'><?echo $V7c13c7fc?></td>
<?}if($adminconfig[22]){?>
<td id=18><?echo $V078171f8[24]?></td>
<?
	}
if($adminconfig[23])
	{
 $V71348b6f='';
$Ved635826=$V078171f8[29];
if(strlen($Ved635826)>2)
 {
 $V71348b6f=$Ved635826;
$Ved635826=substr($Ved635826,0,2).'..';
}
?>
<td id=19 title='<?echo $V71348b6f?>'><?echo $Ved635826?></td>
<?}if($adminconfig[24]){?>
<td id=20><?if ($V078171f8[16]==2) { echo 'Hourly'; } elseif ($V078171f8[16]==3) { echo 'Now'; } elseif (($V078171f8[16]==1)||($V078171f8[16]==4)||($V078171f8[16]==5)) { { echo $V078171f8[11]; } if ($V078171f8[30]) { echo "<br><small>$V078171f8[30]</small>"; } elseif ($V078171f8[23]) { echo '<br><small>'.$V078171f8[23].'</small>'; } }?></td>
<?}if($adminconfig[25]){?>
<td id=21><?if ($V078171f8[16] && $V078171f8[12]) echo $V078171f8[12]; if ($V078171f8[16]==4) { echo '<small>a</small>'; } elseif ($V078171f8[16]==5) { echo '<small>s</small>'; } if ($V078171f8[31]) echo '<br><small>'.$V078171f8[31].'</small>'?></td>
<?}?>
</tr>
<?
 }
}
$Vdf93c995=$Ve0d88a0e=$Vb50108e8=$V293e5def=$V707888c6=$Vcb370252='';
if(($V8f7e4c2e+$Vdbc3017a)>0)
	{
 $V293e5def=round(($Ved186dde+$Vd5159755)*100/($V8f7e4c2e+$Vdbc3017a));
}
if(($V7d893251+$V88d67324)>0)
	{
 $Vb50108e8=round($V3b521893*100/($V7d893251+$V88d67324));
$V707888c6=round(($V8f7e4c2e+$Vdbc3017a)*100/($V7d893251+$V88d67324));
$Vcb370252=round(($Ved186dde+$Vd5159755)*100/($V7d893251+$V88d67324));
}
if(($Vd5159755+$Ved186dde)>0)
	{
 $Vdf93c995=round($V312cf491*100/($Vd5159755+$Ved186dde));
$Ve0d88a0e=round($V9b2e4cef*100/($Vd5159755+$Ved186dde));
}
?>
<tr>
<?if($adminconfig[0]){?>
<th><input type=checkbox name=setall onclick=F25f97bdb()></V5b79c40f>
<?}if($adminconfig[1]){?>
<th></th>
<?}if($adminconfig[2]){?>
<th>
<form name=allstats method=POST>
<input type=hidden name=viewhourly value=yes>
<a href=# onclick='document.all.allstats.submit();return false'>Stats</a>
</th></form>
<?}?>
<th>Summary</th>
<?if($adminconfig[4]){?>
<th></th>
<?}if($adminconfig[5]){?>
<th title=in><?echo $Vf921bd18?></th>
<?}if($adminconfig[6]){?>
<th title=out><?echo $V88d67324?></th>
<?}if($adminconfig[7]){?>
<th title=clicks><?echo $Vd5159755?></th>
<?}if($adminconfig[5]+$adminconfig[6]+$adminconfig[7]){?>
<th></th>
<?}if($adminconfig[8]){?>
<th title=in><?echo $V67afdedd?></th>
<?}if($adminconfig[9]){?>
<th title=out><?echo $V7d893251?></th>
<?}if($adminconfig[10]){?>
<th title=clicks><?echo $Ved186dde?></th>
<?}if($adminconfig[11]){?>
<th title='direct clicks'><?echo $V8c445acc?></th>
<?}if($adminconfig[12]){?>
<th title='raw in'><?echo $V8f7e4c2e?></th>
<?}if($adminconfig[8]+$adminconfig[9]+$adminconfig[10]+$adminconfig[11]+$adminconfig[12]){?>
<th></th>
<?}if($adminconfig[13]){?>
<th title=bad><?if ($Vdf93c995) echo $Vdf93c995?></th>
<?}if($adminconfig[14]){?>
<th title='bad lang'><?if ($Ve0d88a0e) echo $Ve0d88a0e?></th>
<?}if($adminconfig[15]){?>
<th title=prod><?if ($V293e5def) echo $V293e5def?></th>
<?}if($adminconfig[16]){?>
<th title=ratio><?if ($V707888c6) echo $V707888c6?></th>
<?}if($adminconfig[17]){?>
<th></th>
<?}if($adminconfig[18]){?>
<th title=eff><?if ($Vcb370252) echo $Vcb370252?></th>
<?}if($adminconfig[19]){?>
<th></th>
<?}if($adminconfig[13]+$adminconfig[14]+$adminconfig[15]+$adminconfig[16]+$adminconfig[17]+$adminconfig[18]+$adminconfig[19]){?>
<th></th>
<?}if($adminconfig[20]){?>
<th title=owed><?if ($V89654807) echo '<small>'.$V89654807.'</small>'?></th>
<?}if($adminconfig[21]){?>
<th></th>
<?}if($adminconfig[22]){?>
<th></th>
<?}if($adminconfig[23]){?>
<th></th>
<?}if($adminconfig[24]){?>
<th></th>
<?}if($adminconfig[25]){?>
<th title=force><?if ($Vf96e37e7) echo $Vf96e37e7?></th>
<?}?>
</tr>
</table>
<br>
<?
}
?>
</div>
</body>
</html>
<?
exit;
function F546519e0()
{
	global $V69858820;
?>
<table cellspacing=0 width=90%>
<tr>
<form method=POST>
<td align=center><input type=submit name=viewhourly value=Hourly></td>
<td align=center><input type=submit name=viewdaily value=Daily></td>
<td align=center><input type=submit name=viewlinktrack value='Link Tracking'></td>
<td align=center><input type=submit name=viewpages value='Pages'></td>
<td align=center><input type=submit name=viewrefurl value='Referring URL'></td>
<td align=center><input type=submit name=viewlanguages value='<?if($V69858820){echo'Countries';}else{echo'Languages';}?>'></td>
<td align=center><input type=submit name=main value='Main Menu'></td>
</form>
</tr>
</table>
<br>
<?
}
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
 if(strstr($V1043bfc7,'.dat')) { $V15e05c6d[]=str_replace('.dat','',$V1043bfc7); }
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
$V745fd0ea[4][24]+=$V078171f8[34];
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
 for($V363b122c=0;$V363b122c<=4;$V363b122c++)
 {
 if($V745fd0ea[$V363b122c][$V865c0c0b]>$V118e4cf2) { $V118e4cf2=$V745fd0ea[$V363b122c][$V865c0c0b]; }
}
}
?>
<th>Time</th>
<th>Hits In</th>
<th>Raw In</th>
<th>Graph In</th>
<th colspan=3>Clicks/Anticheat Clicks</th>
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
$V8436ff3f=round($V745fd0ea[4][$V865c0c0b]*150/$V118e4cf2);
$V45c976d6=round($V745fd0ea[1][$V865c0c0b]*150/$V118e4cf2)-$V8436ff3f;
$V9a3a65a6=round($V745fd0ea[2][$V865c0c0b]*150/$V118e4cf2);
}
if($V52041a85) { echo ('<img src=stat2.gif width='.$V52041a85.' height=7 border=0>'); }
if($Vdb44fea5) { echo ('<img src=stat3.gif width='.$Vdb44fea5.' height=7 border=0>'); }
echo('</td><td align=right>');if($V745fd0ea[1][$V865c0c0b]) echo($V745fd0ea[1][$V865c0c0b]);
echo('</td><td align=right>');if($V745fd0ea[4][$V865c0c0b]) echo($V745fd0ea[4][$V865c0c0b]);echo('</td><td>');
if($V8436ff3f) { echo('<img src=stat2.gif width='.$V8436ff3f.' height=7 border=0>'); }
if($V45c976d6) { echo('<img src=stat4.gif width='.$V45c976d6.' height=7 border=0>'); }
echo('</td><td align=right>');if($V745fd0ea[2][$V865c0c0b]) echo($V745fd0ea[2][$V865c0c0b]);echo('</td><td>');
if($V9a3a65a6) { echo('<img src=stat1.gif width='.$V9a3a65a6.' height=7 border=0>'); }
echo ("</td></tr>\r\n");
if(++$V289a681c>23){ $V289a681c=0; }
}
}
function F9bca221a()
{
	global $V69858820,$adminconfig;
echo('<tr>');
if($adminconfig[0]){echo('<th rowspan=2></th>');}
if($adminconfig[1]){echo('<th rowspan=2></th>');}
if($adminconfig[2]){echo('<th rowspan=2></th>');}
echo('<th rowspan=2>Site Name</th>');
if($adminconfig[4]){echo('<th rowspan=2></th>');}
if($adminconfig[5]+$adminconfig[6]+$adminconfig[7])
	{
 echo('<th colspan='.($adminconfig[5]+$adminconfig[6]+$adminconfig[7]).' align=center>Current Hour</th>');
echo('<th rowspan=2></th>');
}
if($adminconfig[8]+$adminconfig[9]+$adminconfig[10]+$adminconfig[11]+$adminconfig[12])
	{
 echo('<th colspan='.($adminconfig[8]+$adminconfig[9]+$adminconfig[10]+$adminconfig[11]+$adminconfig[12]).' align=center>24 Hours</th>');
echo('<th rowspan=2></th>');
}
if($adminconfig[13]+$adminconfig[14]+$adminconfig[15]+$adminconfig[16]+$adminconfig[17]+$adminconfig[18]+$adminconfig[19])
	{
 echo('<th colspan='.($adminconfig[13]+$adminconfig[14]+$adminconfig[15]+$adminconfig[16]+$adminconfig[17]+$adminconfig[18]+$adminconfig[19]).' align=center>Current Hour + 24 Hours</th>');
echo('<th rowspan=2></th>');
}
if($adminconfig[20]){echo('<th rowspan=2 title="Limit Out/Click; How much Hits You Owed">Limit<br><small>Owed</small></th>');}
if($adminconfig[21]){echo('<th rowspan=2>Group</th>');}
if($adminconfig[22]){echo('<th rowspan=2 title="Pop-ups amount">Pop</th>');}
if($adminconfig[23])
	{
 echo('<th rowspan=2 title="Out ');if($V69858820){echo'Countries';}else{echo'Browser Languages';}
echo('">');
if($V69858820){echo'Coun';}else{echo'Lang';}
echo('</th>');
}
if($adminconfig[24]){echo('<th rowspan=2>Force<br>Time</th>');}
if($adminconfig[25]){echo('<th rowspan=2 title="Force amount; Forced hits">Force<br><small>Forced</small></th>');}
echo('</tr><tr>');
if($adminconfig[5]){echo('<th title="Unique In">In</th>');}
if($adminconfig[6]){echo('<th title="Unique Out">Out</th>');}
if($adminconfig[7]){echo('<th title="Generated Clicks">Click</th>');}
if($adminconfig[8]){echo('<th title="Unique In">In</th>');}
if($adminconfig[9]){echo('<th title="Unique Out">Out</th>');}
if($adminconfig[10]){echo('<th title="Generated Clicks">Click</th>');}
if($adminconfig[11]){echo('<th title="Direct Clicks">Dir</th>');}
if($adminconfig[12]){echo('<th title="Raw In">RIn</th>');}
if($adminconfig[13]){echo('<th title="Bad Clicks=Proxy+Robots">Bad<br><small>%</small></th>');}
if($adminconfig[14]){echo('<th title="Bad Language">BadL<br><small>%</small></th>');}
if($adminconfig[15]){echo('<th title="Productivity=Click/RIn">Prod<br><small>%</small></th>');}
if($adminconfig[16]){echo('<th title="Return Ratio=In/Out">Ratio<br><small>%</small></th>');}
if($adminconfig[17]){echo('<th title="Statistical Deviation (Cheat Probability)">Dev<br><small>%</small></th>');}
if($adminconfig[18]){echo('<th title="Effectivity=Click/Out">Eff<br><small>%</small></th>');}
if($adminconfig[19]){echo('<th title="Finally=Effect/Bad">Fin</th>');}
echo('</tr>');
}
?>