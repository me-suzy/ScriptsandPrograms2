<?php
/*
                    ###########################                                 
                    #   DownloadCounter! v1.1 #                        
                    #   by Mohammed Jassim    #                     
                    #   email: lazy@mohd.biz  #                       
                    #   http://mohd.biz       #                 
                    #   http://mj-smile.com   #
                    ###########################

    Description:
	  This is a simple and powerful download counter which will log the details of anyone
	  who download the file specify, it will log their IP Address, Remote Address
	  Browser type and Operating system, you can show the number of downloads anywhere 
	  in your website through SSI. You can easily modify and add new downloads
	  through a protected admin panel, MySQL and SSI are required.

    Copyright (C) 2002  by Mohammed Jassim mj-smile.com 
 
    This program is free software; you can redistribute it and/or modify  
    it under the terms of the GNU General Public License as published by 
    the Free Software Foundation; either version 2 of the License, or 
    (at your option) any later version. 

    This program is distributed in the hope that it will be useful, 
    but WITHOUT ANY WARRANTY; without even the implied warranty of 
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
    GNU General Public License for more details. 

    You should have received a copy of the GNU General Public License 
    along with this program; if not, write to the Free Software 
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA 
*/   
include "config.php";
$auth = 0; 
if (($PHP_AUTH_USER == $user ) && ($PHP_AUTH_PW == $pass )) $auth = 1; 
if ( $auth != 1 ) { 
    header( "WWW-Authenticate: Basic realm=Authorization Required!" ); 
    header( "HTTP/1.0 401 Unauthorized" ); 
    echo 'Authorization Required!'; 
    exit; 
}
$con=mysql_connect($db_host,$db_user,$db_pass);
$result=mysql_select_db($db_name,$con) or die (mysql_error());
$sql="SELECT * FROM $table";
$result=mysql_query($sql) or die (mysql_error());

if ($action == "" && $show == "" && $mod == ""){
echo"<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html>
<head>
<META content=no-cache http-equiv=Pragma>
<title>Admin Panel</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
<style type=\"text/css\">
<!--
table {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: xx-small;
	font-style: normal;
	line-height: normal;
	font-weight: normal;
	font-variant: normal;
	text-transform: none;
	color: #000099;
}
-->
</style>
</head>

<body>
<div align=\"center\">  <p><a href=\"admin.php?action=add\"><font size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"><strong>Add 
    new entry!</strong></font></a></p>
  <table width=\"632\" height=\"16\" border=\"0\" cellpadding=\"2\" cellspacing=\"2\"  style=\"border-collapse: collapse; border: 1px dotted #CCCCCC\"
>
    <tr>"; 
	while ($data=mysql_fetch_assoc($result)){
     echo" <td width=\"210\" style=\"border: 1px dotted #CCCCCC;\"><div align=\"center\"><font color=\"#FF0000\"><strong><font face=\"Geneva, Arial, Helvetica, sans-serif\">$data[title]</font></strong></font></div></td>
      <td width=\"243\" style=\"border: 1px dotted #CCCCCC;\"><div align=\"center\"><font color=\"#FF0000\" face=\"Geneva, Arial, Helvetica, sans-serif\"><strong>$data[URL]</strong></font></div></td>
      <td width=\"86\" style=\"border: 1px dotted #CCCCCC;\"><div align=\"center\"><font color=\"#FF0000\"><strong><font color=\"#9933FF\" face=\"Geneva, Arial, Helvetica, sans-serif\"><a href=\"admin.php?mod=$data[title]\">Modify</a></font></strong></font></div></td>
      <td width=\"65\" style=\"border: 1px dotted #CCCCCC;\"><div align=\"center\"><font color=\"#FF0000\"><strong><font color=\"#999999\" face=\"Geneva, Arial, Helvetica, sans-serif\"><a href=\"admin.php?show=$data[title]\">Browse</a></font></strong></font></div>
        <div align=\"center\"><font color=\"#FF0000\"><strong></strong></font></div></td> </tr>";
		 } 
		 echo"   
  </table>
</div>
<div align=\"center\"> <font size=\"-7\" face=\"Verdana, Arial, Helvetica, sans-serif\">By 
  Mohammed Jassim 2002, <a href=\"http://mj-smile.com\">mj-smile.com</a><br>
  All rights are reserved!</font> </div>
</body>
</html>
";}
if ($action == "add"){
echo"<html>

<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1252\">
<title>Add new entry</title>
</head>

<body>

<form method=\"POST\" action=\"admin.php?action=addnew\">
<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" id=\"AutoNumber1\">
    <tr>
      <td width=\"50%\">
      <p align=\"center\">
      <input type=\"text\" name=\"dtitle\" size=\"20\" value=\"Title\" style=\"font-family: Verdana; font-size: 8pt; color: #0033CC; border-style: solid; border-width: 1\"></td>
      <td width=\"50%\">
      <p align=\"center\">
      <input type=\"text\" name=\"durl\" size=\"20\" value=\"http://\" style=\"border-style: solid; border-width: 1; color:#FF0000; font-family:Verdana; font-size:8pt\"></td>
    </tr>
  </table>
  <p align=\"center\">
  <input type=\"submit\" value=\"Add\" name=\"B1\" style=\"color: #33CC33; font-family: Verdana; font-size: 8pt; font-weight: bold; border: 3px double #CCCCCC; background-color: #FFFFFF\" size=\"20\"></p>
</form>
<p align=\"center\">&nbsp;</p>
<p><div align=\"center\"><a href=\"admin.php\"><font color=\"#999999\" size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"><strong>Back!</strong></font></a></div>
</p><div align=\"center\"> <font size=\"-7\" face=\"Verdana, Arial, Helvetica, sans-serif\">By 
  Mohammed Jassim 2002, <a href=\"http://mj-smile.com\">mj-smile.com</a><br>
  All rights are reserved!</font> </div>
</body>

</html>";
}
elseif ($action == "addnew"){
$title=$_POST[dtitle];
$url=$_POST[durl];
$insert = "INSERT INTO $table (title,URL) 
    VALUES ('$title','$url')"; 
    mysql_query($insert) or die (mysql_error());  
	$sql=mysql_query("
CREATE TABLE download_$title (
  id int(11) NOT NULL auto_increment,
  IP varchar(30) NOT NULL default '',
  RemoteAddr varchar(30) NOT NULL default '',
  agent varchar(50) NOT NULL default '',
  ref varchar(50) NOT NULL default '',
  PRIMARY KEY  (id)
)");
echo" <!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html>
<head>
<title>Success!</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
</head>

<body>
<div align=\"center\">
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p><font size=\"+7\" face=\"Courier New, Courier, mono\"><strong>The entry has been 
    added sucessfully!</strong></font></p>
</div>
<p align=\"center\">&nbsp;</p>
<p><div align=\"center\"><a href=\"admin.php\"><font color=\"#999999\" size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"><strong>Back!</strong></font></a></div>
</p><div align=\"center\"> <font size=\"-7\" face=\"Verdana, Arial, Helvetica, sans-serif\">By 
  Mohammed Jassim 2002, <a href=\"http://mj-smile.com\">mj-smile.com</a><br>
  All rights are reserved!</font> </div>
</body>

</html>";
	}
	elseif ($action == "mod")
	{
$title=$_POST[dtitle];
$url=$_POST[durl];
$mod=$_POST[mod];
$query = "UPDATE $table SET title='$title' WHERE title='$mod'";  
$result = mysql_query($query) or die (mysql_error()); 
$query = "UPDATE $table SET URL='$url' WHERE title='$mod'";  
$result = mysql_query($query) or die (mysql_error()); 
$query="ALTER TABLE `download_$mod` RENAME `download_$title`";
$result = mysql_query($query) or die (mysql_error()); 
echo"success";

}
if ($show != "")
{
echo"<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html>
<head>
<META content=no-cache http-equiv=Pragma>
<title>Admin Panel</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
<style type=\"text/css\">
<!--
table {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: xx-small;
	font-style: normal;
	line-height: normal;
	font-weight: normal;
	font-variant: normal;
	text-transform: none;
	color: #000099;
}
-->
</style>
</head>

<body>
<div align=\"center\">
  <table width=\"632\" height=\"16\" border=\"0\" cellpadding=\"2\" cellspacing=\"2\"  style=\"border-collapse: collapse; border: 1px dotted #CCCCCC\"
>
    <tr> 
      <td width=\"103\" style=\"border: 1px dotted #CCCCCC;\"><div align=\"center\"><font color=\"#FF0000\"><strong><font face=\"Geneva, Arial, Helvetica, sans-serif\">IP</font></strong></font></div></td>
      <td width=\"114\" style=\"border: 1px dotted #CCCCCC;\"><div align=\"center\"><font color=\"#FF0000\"><strong><font face=\"Geneva, Arial, Helvetica, sans-serif\">Remote 
          Addr </font></strong></font></div></td>
      <td width=\"112\" style=\"border: 1px dotted #CCCCCC;\"><div align=\"center\"><font color=\"#FF0000\"><strong><font face=\"Geneva, Arial, Helvetica, sans-serif\">Agent</font></strong></font></div></td>
      <td width=\"75\" style=\"border: 1px dotted #CCCCCC;\"><div align=\"center\"><font color=\"#FF0000\"><strong><font face=\"Geneva, Arial, Helvetica, sans-serif\">Referrer</font></strong></font></div></td>
      <td width=\"110\" style=\"border: 1px dotted #CCCCCC;\"><div align=\"center\"><font color=\"#FF0000\"><strong><font face=\"Geneva, Arial, Helvetica, sans-serif\">Hit 
          number </font></strong></font></div></td>";
		  $sql="SELECT * FROM download_$show";
$result=mysql_query($sql) or die ("mysql_error() four");
while ($data=mysql_fetch_assoc($result)){
echo "     <tr> <td width=\"103\" style=\"border: 1px dotted #CCCCCC;\"><div align=\"center\">$data[IP]</div></td>
      <td width=\"114\" style=\"border: 1px dotted #CCCCCC;\"><div align=\"center\">$data[RemoteAddr]</div></td>
      <td width=\"112\" style=\"border: 1px dotted #CCCCCC;\"><div align=\"center\">$data[agent]</div></td>
      <td width=\"75\" style=\"border: 1px dotted #CCCCCC;\"><div align=\"center\">$data[ref]</div></td>
      <td width=\"110\" style=\"border: 1px dotted #CCCCCC;\"><div align=\"center\">$data[id]</div></td></tr>";
}
echo"    </tr>
  </table>
</div>
<p><div align=\"center\"><a href=\"admin.php\"><font color=\"#999999\" size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"><strong>Back!</strong></font></a></div>
</P>
<div align=\"center\"> <font size=\"-7\" face=\"Verdana, Arial, Helvetica, sans-serif\">By 
  Mohammed Jassim 2002, <a href=\"http://mj-smile.com\">mj-smile.com</a><br>
  All rights are reserved!</font> </div>
</body>
</html>
";}
if ($mod != "")
echo"<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1252\">
<title>Modify $mod entry</title>
</head>

<body>

<form method=\"POST\" action=\"admin.php?action=mod\">
<input type=\"hidden\" name=\"mod\" value=\"$mod\">
<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" id=\"AutoNumber1\">
    <tr>
      <td width=\"50%\">
      <p align=\"center\">
      <input type=\"text\" name=\"dtitle\" size=\"20\" value=\"$mod\" style=\"font-family: Verdana; font-size: 8pt; color: #0033CC; border-style: solid; border-width: 1\"></td>
      <td width=\"50%\">
      <p align=\"center\">
      <input type=\"text\" name=\"durl\" size=\"20\" value=\"http://\" style=\"border-style: solid; border-width: 1; color:#FF0000; font-family:Verdana; font-size:8pt\"></td>
    </tr>
  </table>
  <p align=\"center\">
  <input type=\"submit\" value=\"Modify\" name=\"B1\" style=\"color: #33CC33; font-family: Verdana; font-size: 8pt; font-weight: bold; border: 3px double #CCCCCC; background-color: #FFFFFF\"></p>
</form>
<p align=\"center\">&nbsp;</p>
<p><div align=\"center\"><a href=\"admin.php\"><font color=\"#999999\" size=\"2\" face=\"Verdana, Arial, Helvetica, sans-serif\"><strong>Back!</strong></font></a></div>
</P>
<div align=\"center\"> <font size=\"-7\" face=\"Verdana, Arial, Helvetica, sans-serif\">By 
  Mohammed Jassim 2002, <a href=\"http://mj-smile.com\">mj-smile.com</a><br>
  All rights are reserved!</font> </div>
</body>
</html>";

?>