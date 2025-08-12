<?

$httpref=$referer;
$IPADDRESS = $HTTP_SERVER_VARS["REMOTE_ADDR"]; 

$today=date("Y-m-d");
$thismonth=date("Y-m");

if(empty($_COOKIE["userentered"]) )  {
setcookie ("userentered", 'entered',time()+86400);

$filename="logs/".$today."_unique";
$filenameall="logs/alltotal_unique";
$filenamereferers="logs/".$today."_referers_unique";
$filenameReferersAllForThisMonth="logs/".$thismonth."_allreferers_unique";
}
else  {
$filename="logs/".$today."_raw";
$filenameall="logs/alltotal_raw";
$filenamereferers="logs/".$today."_referers_raw";
$filenameReferersAllForThisMonth="logs/".$thismonth."_allreferers_raw";
}

if(!empty($httpref))  {

$fp=fopen($filenamereferers,"a");
fwrite($fp,$httpref."\n");
fclose($fp);

$fp=fopen($filenameReferersAllForThisMonth,"a");
fwrite($fp,$httpref."\n");
fclose($fp);
}


if(file_exists($filenameall))    {
$countinthefileall=ParseFile($filenameall);
$countsofarall=$countinthefileall+1;
 }
else  {
$countsofarall=1;
}

$fp=fopen($filenameall,"w");
fwrite($fp,$countsofarall);
fclose($fp);




if(file_exists($filename))    {
$countinthefile=ParseFile($filename);
$countsofar=$countinthefile+1;
 }
else  {
$countsofar=1;
}

$fp=fopen($filename,"w");
fwrite($fp,$countsofar);
fclose($fp);






##This function opens a file and returns its contents to the caller
function ParseFile ($filename)
{

if(!file_exists($filename))   {
print "<center><font size=1 face=verdana><b>$filename</b> does not exist in the templates directory.Please re-upload or check permissions of the file</center></font>";
exit;
}
else  {    

if(is_readable($filename))  {
$handle=fopen($filename,"r");
$contents = fread ($handle, filesize ($filename));
fclose ($handle);

return $contents;
}
else  {
print "<center><font size=1 face=verdana>Unable to open the file: <b>$filename</b>. <br>File is found in the specified directory but it has not been possible to open it. <br>This may be a permission problem. Please set permission of this file to : <b>644</b>";
exit;
     }

   }
}





?>