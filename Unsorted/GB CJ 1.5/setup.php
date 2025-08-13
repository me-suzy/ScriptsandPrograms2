<?php
 
if($HTTP_POST_VARS["V0354d89c"])
{
	$V8ca05b67=strtolower($V8ca05b67);
$V0354d89c=strtolower($V0354d89c);
$V1a1dc91c=strtolower($V1a1dc91c);
$Vd5a27f82=strtolower($Vd5a27f82);
$V56bd7107='';
if(!ereg("^[[:alnum:]]+$", $V0354d89c)) $V56bd7107='Invalid Character In Login';
if(!ereg("^[[:alnum:]]+$", $V1a1dc91c)) $V56bd7107='Invalid Character In Password';
if(!$V8ca05b67) $V56bd7107='Blank Site Name';
if(!$V0354d89c) $V56bd7107='Blank Login';
if(!$V1a1dc91c) $V56bd7107='Blank Password';
if(!$Vd5a27f82) $V56bd7107='Blank E-mail';
if(!$V0cb9a618) $V56bd7107='Blank URL';
if(!$Vc4ef352f) $V56bd7107='Blank Category';
if(!$V098f6bcd) $V56bd7107='PHP must have setting register_globals=On. Contact your server admin or use another hosting.';
if($V56bd7107=='')
	{
 if(file_exists('uninstall.php')) { unlink('uninstall.php'); }
if(!is_dir('backupfiles')) { umask(0);mkdir('backupfiles',0777); }
if(!is_dir('datafiles')) { umask(0);mkdir('datafiles',0777); }
if(!is_dir('memberfiles')) { umask(0);mkdir('memberfiles',0777); }
$V5ad76bc3='AuthName "Protected Area"'."\r\n".'AuthType Basic'."\r\n".'AuthUserFile /dev/null'."\r\n".'AuthGroupFile /dev/null'."\r\n\r\n".'require valid-user';
F8d60ba21('backupfiles/.htaccess',$V5ad76bc3);
F8d60ba21('memberfiles/.htaccess',$V5ad76bc3);
F53ba727d('datafiles/blacklist.dat');
F53ba727d('datafiles/calctime.dat');
F53ba727d('datafiles/hitsin.dat');
F53ba727d('datafiles/hitsout.dat');
F53ba727d('datafiles/rules.dat');
F53ba727d('datafiles/rules2.dat');
F53ba727d('datafiles/outpick.dat');
F53ba727d('datafiles/linktrack.dat');
F53ba727d('datafiles/refurl.dat');
F53ba727d('datafiles/daily.dat');
F53ba727d('datafiles/lang.dat');
F53ba727d('datafiles/clickpage.dat');
F53ba727d('datafiles/ad.dat');
F53ba727d('datafiles/rotstat.dat');
F8d60ba21('memberfiles/auxout.dat','-|auxout||||0|0|0|0|0|0|0||no reset||http://google.com/||auxout|||||||||||||||||||||');
F8d60ba21('memberfiles/nocookie.dat','-|nocookie||||0|0|0|0|0|0|0||no reset|0|http://google.com/||nocookie|||||||||||||||||||||');
F8d60ba21('memberfiles/direct.dat','-|direct||||0|0|0|0|0|0|0||no reset|0|http://google.com/||direct|||||||||||||||||||||');
if($Vb92cbf2f=@fopen('config.php','r'))
 {
 $V1f620edc=fread($Vb92cbf2f,filesize('config.php'));
fclose($Vb92cbf2f);
$V1f620edc=str_replace('htmlurl',$V0cb9a618,$V1f620edc); 
 $V1f620edc=str_replace('SiteName',$V8ca05b67,$V1f620edc); 
 $V1f620edc=str_replace('adminemail',$Vd5a27f82,$V1f620edc); 
 $V1f620edc=str_replace('adminicq',$icq,$V1f620edc); 
 $V1f620edc=str_replace('adminlogin',$V0354d89c,$V1f620edc); 
 $V1f620edc=str_replace('adminpass',$V1a1dc91c,$V1f620edc); 
 $V1f620edc=str_replace('maingroup',$Vc4ef352f,$V1f620edc); 
 $V1f620edc=str_replace('sourcelang',$V7572559c,$V1f620edc); 
 if($Vb92cbf2f=@fopen('config.php','w'))
 {
 fwrite($Vb92cbf2f,$V1f620edc);
fclose($Vb92cbf2f);
}
else { echo 'Error! Cant write to file config.php<br>'; }
}
else { echo 'Error! Cant open file config.php<br>'; }
if($Vb92cbf2f=@fopen('main1.html','r'))
 {
 $V1f620edc=fread($Vb92cbf2f,filesize('main1.html'));
fclose($Vb92cbf2f);
$V1f620edc=str_replace('My Page',$V8ca05b67,$V1f620edc); 
 $V1f620edc=str_replace('Click Here',$Vc4ef352f,$V1f620edc); 
 if($Vb92cbf2f=@fopen('main1.html','w'))
 {
 fwrite($Vb92cbf2f,$V1f620edc);
fclose($Vb92cbf2f);
}
else { echo 'Error! Cant write to file main1.html<br>'; }
}
else { echo 'Error! Cant open file main1.html<br>'; }
if($Vb92cbf2f=@fopen('best.html','r'))
 {
 $V1f620edc=fread($Vb92cbf2f,filesize('best.html'));
fclose($Vb92cbf2f);
$V1f620edc=str_replace('Click Here',$Vc4ef352f,$V1f620edc); 
 if($Vb92cbf2f=@fopen('best.html','w'))
 {
 fwrite($Vb92cbf2f,$V1f620edc);
fclose($Vb92cbf2f);
}
else { echo 'Error! Cant write to file best.html<br>'; }
}
else { echo 'Error! Cant open file best.html<br>'; }
include('calculate.php');
F234f0381();
echo '<title>Your CJ is created successfully</title>';
echo '<font face=Arial><br><font size=+1>Your CJ is created</font><br><br>Main page: <a target=_blank href='.$V0cb9a618.'>'.$V0cb9a618.'</a><br>';
echo 'Administration: <a target=_blank href='.$V0cb9a618.'/admin.php>'.$V0cb9a618.'/admin.php</a><br>';
echo 'Login:' . $V0354d89c . '<br>';
echo 'Password:' . $V1a1dc91c . '<br><br>';
echo 'Write down this info</font>';
}
else
	{
 echo '<title>Error</title>';
echo '<center><font face=Arial size=+1><br><br>Wrong parameters - ' . $V56bd7107 . '<br></font></center>';
}
}
else
{
?>
<html>
<head>
<title>GB CG - Setup</title>
<STYLE type=text/css>
body {font-size:10pt;font-family:Arial;background-color:#ffffff;color:#000000;scrollbar-base-color:#7777CC}
a:link {color:#0000ff}
a:visited {color:#4444ff}
a:active {color:red}
a:hover {color:red;text-decoration:none}
tr {font-size:10pt;color:#000000;background-color:#dddddd}
table {background-color:#ffffff}
th {background-color:#3366CC;color:#ffffff}
</STYLE>
</head>
<body>
<FORM METHOD="POST">
<table align=center>
<tr>
<th colspan=2>
GB CJ Setup
</th>
</tr>
<tr>
<td>
URL
</td>
<td>
<INPUT TYPE="text" NAME="V0cb9a618" SIZE="40" value="<? $V8c82b05b='http://'.$SERVER_NAME.$PHP_SELF; echo (substr($V8c82b05b,0,strrpos($V8c82b05b,'/'))); ?>">
</td>
</tr>
<tr>
<td>
Your email
</td>
<td>
<INPUT TYPE="text" NAME="Vd5a27f82" SIZE="30">
</td>
</tr>
<tr>
<td>
Your ICQ
</td>
<td>
<INPUT TYPE="text" NAME="icq" SIZE="30">
</td>
</tr>
<tr>
<td>
Site name
</td>
<td>
<INPUT TYPE="text" NAME="V8ca05b67" SIZE="30">
</td>
</tr>
<td>
Category
</td>
<td>
<select name=Vc4ef352f>
<option selected></option>
<option>Music</option>
<option>Photo</option>
<option>Dating</option>
<option>Games</option>
<option>Betting_Gaming</option>
<option>Videos_Movies</option>
<option>Travel</option>
<option>Entertainment</option>
<option>Automotive</option>
<option>Business</option>
<option>Computers</option>
<option>Education</option>
<option>Family</option>
<option>Health</option>
<option>Sport</option>
<option>Web-Services</option>
<option>All</option>
<option></option>
<option>Adult</option>
<option>Teen</option>
<option>Amateur</option>
<option>Anal</option>
<option>Anime</option>
<option>Asian</option>
<option>Big_Tits</option>
<option>Black_Latina</option>
<option>Blowjobs</option>
<option>Bondage_Fetish</option>
<option>Celebrities</option>
<option>Cheerleader</option>
<option>Close_Ups</option>
<option>Cumshots</option>
<option>Dildos_Toys</option>
<option>Fat_Babes</option>
<option>Fisting</option>
<option>Gay</option>
<option>Groupsex</option>
<option>Hardcore</option>
<option>Interracial</option>
<option>Lesbians</option>
<option>Lingerie</option>
<option>Masturbating</option>
<option>Mature_Women</option>
<option>Adult_Movies</option>
<option>Panties_Upskirts</option>
<option>Pissing</option>
<option>Voyeur</option>
<option>Shemales</option>
<option>Small_Tits</option>
<option>Uniform</option>
</select>
</td>
<tr>
<td>
Login
</td>
<td>
<INPUT TYPE="text" NAME="V0354d89c" SIZE="15">
</td>
</tr>
<tr>
<td>
Password
</td>
<td>
<INPUT TYPE="text" NAME="V1a1dc91c" SIZE="15">
</td>
</tr>
<tr>
<th colspan=2>
<INPUT TYPE="hidden" NAME="V098f6bcd" value="test">
<INPUT TYPE="submit" VALUE="Create CJ">
</td>
</th>
</table>
</FORM>
</body>
</html>
<?
}
exit;
function F53ba727d($V435ed7e9)
{
	if(@$V633de4b0=fopen($V435ed7e9,'w'))
	{
 fclose($V633de4b0);
if(!@chmod('datafiles/blacklist.dat',0666)) { echo 'Error! Cant chmod file '.$V435ed7e9.'<br>'; }
}
else { echo 'Error! Cant create file '.$V435ed7e9.'<br>'; }
}
function F8d60ba21($V435ed7e9,$V8d777f38)
{
	if(@$V633de4b0=fopen($V435ed7e9,'w'))
	{
 fputs($V633de4b0,$V8d777f38);
fclose($V633de4b0);
if(!@chmod($V435ed7e9,0666)) { echo 'Error! Cant chmod file '.$V435ed7e9.'<br>'; }
}
else { echo 'Error! Cant create file '.$V435ed7e9.'<br>'; }
}
?>