<?php
include_once('noca.php');
include_once('rcq.php');

$PName = $HTTP_POST_VARS['pname'];
$PEmail = $HTTP_POST_VARS['pemail'];
$PUserName = $HTTP_POST_VARS['pusername'];
$PMsg = $HTTP_POST_VARS['pmsg'];


$to  = $CONF['conf_GEmailAddress'];
$subject = $CONF['conf_GEmailSubject'];

$PMsg = str_replace(array(chr(92).chr(39),chr(92).chr(34)),array(chr(39),chr(34)),$PMsg);
$PMsg = nl2br(htmlspecialchars($PMsg));
$message = "<html><head><title>$subject</title></head><body><P>Name: $PName<BR>Email: $PEmail<br>------------------------------------------------------------</p><P>$PMsg</p><p>------------------------------------------------------------<br>Powered by phpOnline v".$phpOnlineVer."<br>Find out more at http://phponline.dayanahost.com/</p></body></html>";

$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";

$PEmail = str_replace(array("\n","\r","\n\r",";","<",">"),"",$PEmail);
$PName = str_replace(array("\n","\r","\n\r",";","<",">"),"",$PName);

$headers .= "From: $PName<$PEmail>\r\n";

$PMailStatus = 0;
if(mail($to, $subject, $message, $headers))
{
	$PMailStatus = 1;
}
else
{
	$PMailStatus = 2;
}


echo "test1=123&pmailstatus=$PMailStatus&test2=123";


?>