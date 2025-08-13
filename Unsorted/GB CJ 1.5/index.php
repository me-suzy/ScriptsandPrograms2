<?
include('config.php');
$Ved85f522=0;
if(file_exists('datafiles/rotator1.dat'))
{
	include('rotator.php');
$Ved85f522=1;
}
$V5adf599e=$_SERVER["HTTP_REFERER"];
if(!$V5adf599e) { $V5adf599e='Unknown Address'; }
$id=$_GET['id'];
if(($V00dd8e4a)&&($id))
{
	$member=str_replace('.','',$id);
}
else
{
	$url=parse_url(strtolower($_SERVER["HTTP_REFERER"]));
$member=str_replace('www.','',$url['host']);
$member=addslashes($member);
if(!$member) $member='noref';
$member=str_replace('.','',$member);
}
$Vee66a504=$V14ca2a15;
$V435ed7e9='memberfiles/'.$member.'.dat';
if($V633de4b0=@fopen($V435ed7e9,'r'))
{
	flock($V633de4b0,1);
$V739b2f85=fgets($V633de4b0,filesize($V435ed7e9));
flock($V633de4b0,3);
fclose($V633de4b0);
$V3d801aa5=array('');
$V3d801aa5=split("\|",$V739b2f85);
$Vee66a504=$V3d801aa5[24];
if(strstr($V3d801aa5[18],'.')) { $face=$V3d801aa5[18]; }
}
setcookie("refer",$member,time()+$Vec77498a);
setcookie($member,"visited",time()+$Vec77498a);
$V85387580='popups';
setcookie($V85387580,$Vee66a504,time()+$Vec77498a);
 
$Vcff34c13=0;
$gbv=$_GET['gbv'];
if(($Vf3778551)&&($gbv))
{
	$gbv=str_rot13($gbv);
$V4e68c29f=array();
$V4e68c29f=split(';',$gbv);
for($V865c0c0b=0;$V865c0c0b<count($V4e68c29f);$V865c0c0b++)
	{
 if($V4e68c29f[$V865c0c0b]) { setcookie($V4e68c29f[$V865c0c0b],'visited',time()+$Vec77498a); }
}
$Vcff34c13=2;
}
$V435ed7e9='datafiles/Vf17ca2c8.dat';
if($V633de4b0=@fopen($V435ed7e9,'r'))
{
	flock($V633de4b0,1);
$V3d801aa5=fread($V633de4b0,filesize($V435ed7e9));
flock($V633de4b0,3);
fclose($V633de4b0);
$V092073c3=array('');
$V092073c3=split("\r\n",$V3d801aa5);
$V202620a9=sizeof($V092073c3);
for($V865c0c0b=0;$V865c0c0b<sizeof($V092073c3);$V865c0c0b++)
	{
 $V4124bc0a=array('');
$V4124bc0a=split("\|",$V092073c3[$V865c0c0b]);
if ($V4124bc0a[0]==$_SERVER["HTTP_ACCEPT_LANGUAGE"])
 {
 if (ereg('http://',$V4124bc0a[1])) { $V4d738b60='go.php?link=Vf17ca2c8&url='.$V4124bc0a[1]; }
else { $V4d738b60='go.php?link=Vf17ca2c8&ref='.$V4124bc0a[1]; }
break;
}
}
}
if (!$V4d738b60)
{
	if($_GET['face']) { $face=$_GET['face']; }
if(!$face || !file_exists($face))
	{
 if ($_COOKIE['page'])
 {
 $V3ec88920=$_COOKIE['page'];
if ($V3ec88920>2) { $V3ec88920=1; }
}
else
 {
 if (rand(0,1)<0.5) { $V3ec88920=1; }
else { $V3ec88920=2; }
}
if (filesize('datafiles/_main'.$V3ec88920.'.html')==0) { $V3ec88920=1; }
setcookie("page",$V3ec88920+1,time() + 86400);
$face='datafiles/_main'.$V3ec88920.'.html';
if($V6b72e9b9 && !$Ved85f522 && ereg('gzip, deflate',$HTTP_ACCEPT_ENCODING))
 {
 header("Content-Encoding: gzip");
$face='datafiles/gzip_main'.$V3ec88920.'.html';
if($V633de4b0=@fopen($face,'rb'))
 {
 flock($V633de4b0,1);
$Vfc35fdc7=fread($V633de4b0,filesize($face));
flock($V633de4b0,3);
fclose($V633de4b0);
}
echo($Vfc35fdc7);
}
else { include($face); }
}
else { include('./'.$face); }
}
$V673eb027=0;
$V3d801aa5='';
$V435ed7e9='datafiles/ips.txt';
if($V633de4b0=@fopen($V435ed7e9,'r'))
{
	flock($V633de4b0,1);
$V3d801aa5=fread($V633de4b0,filesize($V435ed7e9));
flock($V633de4b0,3);
fclose($V633de4b0);
}
if(!ereg($_SERVER["REMOTE_ADDR"]."\r\n",$V3d801aa5))
{
	if($V633de4b0=@fopen($V435ed7e9,'a'))
	{
 flock($V633de4b0,2);
fputs($V633de4b0,';'.$_SERVER["REMOTE_ADDR"]."\r\n");
flock($V633de4b0,3);
fclose($V633de4b0);
$V673eb027=1;
}
}
if($V633de4b0=@fopen('datafiles/hitsin.dat','a'))
{
	flock($V633de4b0,2);
fputs($V633de4b0,$member.'|'.$V5adf599e.'|'.$V673eb027.'||'.$Vcff34c13."|\r\n");
flock($V633de4b0,3);
fclose($V633de4b0);
}
$Vfd418b58=0;
if($V633de4b0=@fopen('datafiles/calctime.dat','r+'))
{
	flock($V633de4b0,2);
$V945d616f=array('');
$V945d616f=split("\|",fgets($V633de4b0,1024));
$V3e067559=time();
if($V3e067559-$V945d616f[0]>$V3e04dc2a)
	{
 $V0f295760=$V4ac4d98c=0;
if(date('H',$V3e067559)!=date('H',$V945d616f[0]))
 {
 $V0f295760=1;
if(date('j',$V3e067559)!=date('j',$V945d616f[0])) { $V4ac4d98c=1; }
}
$V945d616f[0]=$V3e067559;
if($V3e067559-$V945d616f[1]>$Vec77498a)
 {
 if($Ve05fe307=@fopen('datafiles/ips.txt','w')) { fclose($Ve05fe307); }
if ($V742913f8)
 {
 if($Ve05fe307=@fopen('datafiles/outips.txt','w')) { fclose($Ve05fe307); }
}
$V945d616f[1]=$V3e067559;
}
fseek($V633de4b0,0);
fputs($V633de4b0,$V945d616f[0].'|'.$V945d616f[1]);
$Vfd418b58=1;
}
flock($V633de4b0,3);
fclose($V633de4b0);
if($Vfd418b58)
	{
 include('calculate.php');
Ffd418b58();
}
}
if ($V4d738b60) { Header("Location: $V4d738b60"); }
?>
