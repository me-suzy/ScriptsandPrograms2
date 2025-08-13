<?php
require "variables.php";
if ($wsname == "") {
echo "You must fill out a site name.";
exit;
} 
else
if ($wsurl == "") {
echo "You must fill out a site URL.";
exit;
}
else
if ($description == "") {
echo "You must put a description.";
exit;
}
else
if ($email == "") {
echo "You must enter your email.";
exit;
}
else
$textfile = ("links.txt");
$textfile2 = ("emails.txt");
$fp = fopen($textfile, "a+");
fputs ($fp, "<center><table><tr><td bgcolor=666666 width=500 height=20><font size=2><img src=$imagedir/browser.gif><strong><a href=http://$wsurl target=_blank><font size=4>$wsname</font></a></strong></td></tr><tr><td bgcolor=cccccc width=400><font size=2><font color=000000>$description</font></td></tr></table></center><br><br>\n");
fclose($fp);
$fp = fopen($textfile2, "a+");
fputs ($fp, "$email\n");
fclose($fp);
echo "<html> <title>$title</title><body bgcolor=006699 text=FFFFFF link=FFFFFF vlink=FFFF00 alink=FF0000><font color=FFFFFF size=3 face=verdana><br><center><br> $text1<br>$text2 <br><a href=\"$url1 \" target=_blank\">  $urltext1</a><br><br>Your site has been successfully added. <a href=$viewpage>Click here</a> to view the Link List page.</center></html>";
?>