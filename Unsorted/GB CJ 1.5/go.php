<?
include('config.php');
$url=$_GET['url'];
$first=$_GET['first'];
$ref=$_GET['ref'];
$Vdad12808=array();
if($V69858820) { $Vfa75823c=apache_note('GEOIP_COUNTRY_CODE');$V599f46e3=$Vfa75823c; }
else
{
	$V599f46e3=$_SERVER['HTTP_ACCEPT_LANGUAGE'];
$Vdad12808=split('-',$V599f46e3);
$Vfa75823c=$Vdad12808[0];
}
$V3ec88920=$_COOKIE['page'];
$V7d320a89=$_COOKIE["clicks"];
$V4d2f3dfb=-10;
if($url)
{
	if($Vf03c5300)
	{
 if($Vf03c5300[$V7d320a89]!='T')
 {
 $V54f5210f='direct';
$V9b3df717=$url;
}
}
else
	{
 if (isset($_GET['s']))
 {
 $s=(int)$_GET['s'];
if ($s>100) $s=100;
}
else { $s=100; }
if ((rand(0,100)<$s) || (($first)&&($V7d320a89<$first)))
 {
 $V54f5210f='direct';
$V9b3df717=$url;
}
}
}
elseif($ref)
{
	$V435ed7e9='memberfiles/'.$ref.'.dat';
if($V633de4b0=@fopen($V435ed7e9,'r'))
	{
 flock($V633de4b0,1);
$V739b2f85=fgets($V633de4b0,filesize($V435ed7e9));
flock($V633de4b0,3);
fclose($V633de4b0);
$V3d801aa5=array();
$V3d801aa5=split('\|',$V739b2f85);
if($V3d801aa5[15])
 {
 $V54f5210f=$ref;
$V9b3df717=$V3d801aa5[15];
$V4d2f3dfb=$V3d801aa5[1];
}
}
}
if(!$V9b3df717)
{
	$V3d801aa5='';
$V435ed7e9='datafiles/outpick.dat';
if($V633de4b0=@fopen($V435ed7e9,'r'))
	{
 flock($V633de4b0,1);
$V3d801aa5=fread($V633de4b0,filesize($V435ed7e9));
flock($V633de4b0,3);
fclose($V633de4b0);
}
$outpick=array();
$outpick=split("\r\n",$V3d801aa5);
for($V865c0c0b=0;$V865c0c0b<sizeof($outpick);$V865c0c0b++)
	{
 $dat=array();
$dat=split('\|',$outpick[$V865c0c0b]);
if(($dat[1])&&(!$_COOKIE[$dat[1]])&&($dat[1]!=$_COOKIE["refer"]))
 {
 
 if((!$group)||(($group)&&($group==$dat[3])))
 {
 
 if((!$dat[4])||(strstr($dat[4],$Vfa75823c)))
 {
 $V9b3df717=$dat[2];
$V54f5210f=$dat[1];
$V4d2f3dfb=$dat[5];
break;
}
}
}
}
}
if(!$V9b3df717)
{
	$V435ed7e9='memberfiles/auxout.dat';
if($V633de4b0=@fopen($V435ed7e9,'r'))
	{
 flock($V633de4b0,1);
$V739b2f85=fgets($V633de4b0,filesize($V435ed7e9));
flock($V633de4b0,3);
fclose($V633de4b0);
$V3d801aa5=array('');
$V3d801aa5=split('\|',$V739b2f85);
if($V3d801aa5[15])
 {
 $V54f5210f='auxout';
$V9b3df717=$V3d801aa5[15];
}
}
}
setcookie("clicks",$V7d320a89+1,time() + $Vec77498a);
setcookie($V54f5210f,"visited",time() + $Vec77498a);
 
$Vcff34c13=0;
if(($Vf3778551)&&($V54f5210f!='auxout')&&($V54f5210f!='sponsor'))
{
	if(($V4d2f3dfb>0)||(($V4d2f3dfb>-10)&&(!rand(0,9))))
	{
 $Vd51c3d0d='';
array_walk($HTTP_COOKIE_VARS,'visiteddata');
if($Vd51c3d0d)
 {
 $Vd51c3d0d=str_rot13($Vd51c3d0d);
if(ereg("\?",$V9b3df717)) { $Vd51c3d0d='&gbv='.$Vd51c3d0d; }
else { $Vd51c3d0d='?gbv='.$Vd51c3d0d; }
}
$Vcff34c13=-1;
}
}
header("Location: ".$V9b3df717.$Vd51c3d0d."\n");
$V38d4765b=$_SERVER['HTTP_REFERER'];
$V724b1be8=1;
if(($link=='redirect') || (strstr(str_replace('www.','',$_SERVER['HTTP_REFERER']), str_replace('www.','',$V861ce498))))
{
	if( !$HTTP_VIA && !$HTTP_X_FORWARDED_FOR && $_SERVER['HTTP_ACCEPT'] && $_SERVER['HTTP_USER_AGENT'] && ((!$V0ca2ee97)||($V7d320a89<$V0ca2ee97)))
	{
 $V724b1be8=0;
if ($V742913f8)
 {
 $V724b1be8=1;
$V3d801aa5='';
$V435ed7e9='datafiles/ips.txt';
if($V633de4b0=@fopen($V435ed7e9,'r'))
 {
 flock($V633de4b0,1);
$V3d801aa5=fread($V633de4b0,filesize($V435ed7e9));
flock($V633de4b0,3);
fclose($V633de4b0);
}
if(ereg($V7d320a89.';'.$_SERVER["REMOTE_ADDR"]."\r\n",$V3d801aa5))
 {
 if($V633de4b0=@fopen($V435ed7e9,'a'))
 {
 flock($V633de4b0,2);
fputs($V633de4b0,($V7d320a89+1).';'.$_SERVER["REMOTE_ADDR"]."\r\n");
flock($V633de4b0,3);
fclose($V633de4b0);
$V724b1be8=0;
}
}
}
}
 
	$V546b4439=strpos($V38d4765b,'?');
if($V546b4439) { $V38d4765b=substr($V38d4765b,0,$V546b4439); }
if((($V38d4765b==$V861ce498)||($V38d4765b==$V861ce498.'/'))&&($V3ec88920))
	{
 $V3ec88920--;
if ($V3ec88920==0) { $V3ec88920=2; }
$V38d4765b=$V861ce498.'/main'.$V3ec88920.'.html';
}
}
$V013dff02=0;
if($V4e807dca)
{
	if(!strstr($V4e807dca,$Vfa75823c))
	{
 $V013dff02=1;
}
}
if($V633de4b0=@fopen('datafiles/hitsout.dat','a'))
{
	flock($V633de4b0,2);
fputs($V633de4b0,$V54f5210f.'|'.$link.'|'.$_COOKIE["refer"].'|'.$V599f46e3.'|'.$V724b1be8.'|'.$V013dff02.'|'.$Vcff34c13.'|'.$V38d4765b."\r\n");
flock($V633de4b0,3);
fclose($V633de4b0);
}
 
if ($_GET['thumb'])
{
	$thumb=$_GET['thumb'];
$V0d3d238b=1;
if($_GET['V0d3d238b']){$V0d3d238b=$_GET['V0d3d238b'];}
$stats=$V5a423d9e=array();
Fbd5fc730('datafiles/rotstat'.$V0d3d238b.'.dat',$stats);
Fbd5fc730('datafiles/rotator'.$V0d3d238b.'.dat',$V5a423d9e);
$V46bc758a=0;
foreach($V5a423d9e as $V8ce4b16b=>$V9e3669d1)
	{
 if($thumb==$V8ce4b16b)
 {
 $V46bc758a=1;
break;
}
}
if($V46bc758a)
	{
 $stats[$thumb]++;
$V14447879='';
foreach($stats as $V8ce4b16b=>$V9e3669d1) { $V14447879.=$V9e3669d1.';'.$V8ce4b16b."\r\n"; }
if($V633de4b0=@fopen('datafiles/rotstat'.$V0d3d238b.'.dat','w'))
 {
 flock($V633de4b0,2);
fputs($V633de4b0,$V14447879);
flock($V633de4b0,3);
fclose($V633de4b0);
}
}
}
exit;
function visiteddata($V447b7147,$V3c6e0b8a)
{
	global $Vd51c3d0d;
if($V447b7147=='visited') { $Vd51c3d0d.=$V3c6e0b8a.';'; }
}
function Fbd5fc730($V435ed7e9,&$V8d777f38)
{
	if($V633de4b0=@fopen($V435ed7e9,'r'))
	{
 flock($V633de4b0,1);
while($V5b4d9906=fgets($V633de4b0,1024))
 {
 $Vde695463=array();
$Vde695463=split(';',$V5b4d9906);
$V8d777f38[trim($Vde695463[1])]=$Vde695463[0];
}
flock($V633de4b0,3);
fclose($V633de4b0);
}
}
?>