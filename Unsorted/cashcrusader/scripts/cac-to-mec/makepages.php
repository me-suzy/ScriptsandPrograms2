#!/usr/local/bin/php
<?
include("../conf.inc.php");
exec("mkdir ../../pages/cacpages");
$file=file("../../cgi-bin/pages.lib");
for ($i=0;$i<count($file);$i++){
$line=trim($file[$i]);
if (ereg("~;",$line)){      
$line=str_replace("~;","\n<? include('footer.php');?>\n",$line);
fwrite($fp,$line);
fclose($fp);
$pagename="";}
if (ereg("qq~",$line)){
list($pagename)=split("=",str_replace("$","",$line));
$pagename=trim($pagename);
$fp=fopen("../../pages/cacpages/".$pagename.".php","w");
if (ereg("rlinks",$pagename)){
$login="<? login();?>\n";}
fwrite($fp,"<? include('setup.php');?>\n$login<? include('header.php');?>\n");
$login='';
list($junk,$line)=split("qq~",$line);
}
if ($pagename){
$line=stripslashes(str_replace("\$getgold","/pages/gold.php",str_replace("\$terms","/pages/terms.php",str_replace("\$help","/pages/help.php",$line))));
$line=str_replace("\$index","/pages/index.php",$line);
$line=str_replace("\$privacy","/pages/privacy.php",str_replace("\$enter","/pages/enter.php",$line));
$line=str_replace("\$title",$domain,str_replace("\$siteaddr",$domain,str_replace("\$contactus","/pages/contact.php",$line)));
$line=str_replace("\$signup?r=\$u","/pages/index.php?refid=<? user('username');?>",str_replace("\$getad","/pages/advertise.php",$line));
$line=str_replace("\$signup","/pages/confirm.php",str_replace("cgi-bin/login.cgi","pages/enter.php",$line));
$line=str_replace("r=\$referer","",str_replace("<!--Insert_Rotating_Banner-->","<? getad(\"main\");?>",$line));
fwrite($fp,$line."\n");
}
}
fclose($fp);
$file=file("../../top.html");
$fp=fopen("../../pages/header.php","w");
for ($i=0;$i<count($file);$i++){
$line=trim($file[$i]);
$line=stripslashes(str_replace("\$getgold","/pages/gold.php",str_replace("\$terms","/pages/terms.php",str_replace("\$help","/pages/help.php",$line))));
$line=str_replace("\$index","/pages/index.php",$line);
$line=str_replace("\$privacy","/pages/privacy.php",str_replace("\$signup","/pages/confirm.php",str_replace("\$enter","/pages/enter.php",$line)));
$line=str_replace("\$title",$domain,str_replace("\$siteaddr",$domain,str_replace("\$contactus","/pages/contact.php",$line)));
$line=str_replace("\$signup?r=\$u","/pages/index.php?refid=<? user('username');?>",str_replace("\$getad","/pages/advertise.php",$line));
$line=str_replace("cgi-bin/login.cgi","pages/enter.php",$line);
$line=str_replace("r=\$referer","",str_replace("<!--Insert_Rotating_Banner-->","<? getad(\"main\");?>",$line));
fwrite($fp,$line."\n");
}
fclose($fp);
$file=file("../../bottom.html");
$fp=fopen("../../pages/footer.php","w");
for ($i=0;$i<count($file);$i++){
$line=trim($file[$i]);
$line=stripslashes(str_replace("\$getgold","/pages/gold.php",str_replace("\$terms","/pages/terms.php",str_replace("\$help","/pages/help.php",$line))));
$line=str_replace("\$index","/pages/index.php",$line);
$line=str_replace("\$privacy","/pages/privacy.php",str_replace("\$signup","/pages/confirm.php",str_replace("\$enter","/pages/enter.php",$line)));
$line=str_replace("\$title",$domain,str_replace("\$siteaddr",$domain,str_replace("\$contactus","/pages/contact.php",$line)));
$line=str_replace("\$signup?r=\$u","/pages/index.php?refid=<? user('username');?>",str_replace("\$getad","/pages/advertise.php",$line));
$line=str_replace("cgi-bin/login.cgi","pages/enter.php",$line);
$line=str_replace("r=\$referer","",str_replace("<!--Insert_Rotating_Banner-->","<? getad(\"main\");?>",$line));
fwrite($fp,$line."\n");
}
fclose($fp);
?>
