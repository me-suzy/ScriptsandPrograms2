<?php
require ("../incconfig.php");
?>
<html>

<head>
<?
//  (C) Stefan Holmberg 2001
//  Free to use if these sourcecode lines is not deleted
//  Contact me at webmaster@aspcode.net
//  http://www.aspcode.net/php

    $oConn = Incdb_GetConnectionDedicated();


?>


<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>ArticleMentor - Admin interface</title>
<style type="text/css">
<!--
     body {  font-family: Arial, Geneva, Helvetica, Verdana; font-size: smaller; color: #000000}
     td {  font-family: Arial, Geneva, Helvetica, Verdana; font-size: smaller; color: #000000}
     th {  font-family: Arial, Geneva, Helvetica, Verdana; font-size: smaller; color: #000000}
     A:link {text-decoration: none;}
     A:visited {text-decoration: none;}
     A:hover {text-decoration: underline;}
-->
</style>
</head>

<body>

<table align="center" bgColor="#ECECD9" border="0" cellPadding="3" cellSpacing="0" height="100%" width="100%">
  <tbody>
    <tr>
      <td vAlign="top" width="50%" height="60">
      <b><a href="http://www.aspcode.net/"><font face="verdana,arial,helvetica" size="1">http://www.aspcode.net</font></a></b>
      </td>
      <td vAlign="top" width="468" height="60">
      <b><font face="verdana,arial,helvetica" size="+2">Admin
      interface</font></b>
      <table border="0" width="100%">
        <tr>
                            <td width="50%">
                            </td>
        </tr>
      </table>
      </td>
    </tr>
    <tr>
      <td height="100%" vAlign="top" width="100%" colspan="2">
        <table align="center" bgColor="#ffffff" border="0" cellPadding="0" cellSpacing="0" height="100%" width="100%">
          <tbody>
            <tr>
              <td height="100%" vAlign="top" width="85%">
                <table bgColor="#ffffff" border="0" cellPadding="10" cellSpacing="0" height="100%" width="100%">
                  <tbody>
                    <tr>
                      <td align="left" height="100%" vAlign="top" width="65%">
                        <table border="0" width="100%">
                          <tr>
                            <td width="50%"><b><font color="#aa3333" face="verdana,arial,helvetica" size="4">ArticleMentor</font></b>

                            </td>
                            <td width="50%">
                          </tr>
                        </table>
                        <font color="#aa3333" face="verdana,arial,helvetica" size="+2">
                        <hr color="#000066" noShade SIZE="1">

                        </font>

                        <table border="0" width="100%">
                          <tr>
                            <td width="70%">

                        <font face="helvetica, arial" size="2"><b><i>These
                        categories are available&nbsp;</i></b></font>
                        <table border="0" width="100%">
<?
$oRS = mysql_query( "select * from am_faq" );
$bgcolor = "#ECECD9";
while ( $row=mysql_fetch_array($oRS) )
         {
?>
                          <tr>
                            <td width="50%" bgcolor="<? echo($bgcolor);?>"><b><? echo( $row["name"]);?></b></td>
                            <td width="50%" bgcolor="<? echo($bgcolor);?>"><b><a href="admin_faq.php?fldAuto=<? echo( $row["fldAuto"]);?>&action=edit">Modify</a>
                              - <a href="admin_faq.php?fldAuto=<? echo( $row["fldAuto"]);?>&action=del">Delete</a>&nbsp;-
                              <a href="admin_chapters.php?faq_fldAuto=<? echo( $row["fldAuto"]);?>">Chapters/Articles</a></b></td>
                          </tr>
<?
        if ( $bgcolor=="#ECECD9")
                $bgcolor = "#FFFFFF";
        else
                $bgcolor="#ECECD9";
        }
?>
                          <tr>
                            <td width="50%"></td>
                            <td width="50%"></td>
                          </tr>
                        </table>
                        <p><font face="helvetica, arial" size="2"><a href="admin_faq.php?action=new">Add
                        new category</a></font></p>

                        <p><font face="helvetica, arial" size="2">ArticleMentor is
                        developed by <a href="http://www.aspcode.net">ASPCode.net</a>. Usage of it is totally free.</font></p>
                        <br>
                        <table border="0" width="100%">
                          <tr>
                            <td width="50%">
                            </td>
                            <td width="50%">
                            </td>
                          </tr>
                        </table>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
  </tbody>
</table>

</body>

</html>