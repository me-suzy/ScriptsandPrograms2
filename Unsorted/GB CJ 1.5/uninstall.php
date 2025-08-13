<?
include("config.php");
if(isset($PHP_AUTH_USER)&&($PHP_AUTH_USER == $V9b534ea5)&&($PHP_AUTH_PW == $V34819d7b))
{
	$V74c205ae = 1;
}
else
{
	header("WWW-Authenticate: Basic realm=\"Protected area\"");
header("HTTP/1.0 401 Unauthorized");
exit;
}
Fc64872cb('backupfiles');
Fc64872cb('datafiles');
Fc64872cb('memberfiles');
unlink('_main1.html');
unlink('_main2.html');
echo '<title>Database is deleted</title>';
echo '<center><font face=Arial size=+1><br><br>Database is deleted<br></font></center>';
function Fc64872cb($V5a445d71) 
{
	$V8277e091=dir($V5a445d71);
while($V1043bfc7=$V8277e091->read())
	{
 if($V1043bfc7!= '.' && $V1043bfc7!= '..')
 {
 unlink($V5a445d71.'/'.$V1043bfc7);
}
}
$V8277e091->close();
rmdir($V5a445d71);
}
?>