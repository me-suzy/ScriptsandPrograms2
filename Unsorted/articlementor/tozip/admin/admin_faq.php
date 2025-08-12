<?php
require ("../incconfig.php");
    $oConn = Incdb_GetConnectionDedicated();
    if( $save=="yes" )
         {
         $name = addslashes($name);
         $descr = addslashes($descr);
         if ( $action == "new" )
               {
               mysql_query( "insert into am_faq(name,descr) values('$name', '$descr')"  );
               }
         if ( $action == "edit")
               {
               mysql_query( "update am_faq set name='$name', descr='$descr' where fldAuto=$fldAuto");
               }
         if ( $action=="del" )
               {
               //Ta bort ur FAQ
               mysql_query( "delete from am_faq where fldAuto = " . $fldAuto );

               //För alla chapter, ta bort dess tillhörande quesitons
               $oRS = mysql_query( "select * from am_chapter where faq_fldAuto = " . $fldAuto );
               while ( $row=mysql_fetch_array($oRS) )
                        mysql_query( "delete from am_question where chapter_fldAuto = " . $row["fldAuto"] );

               //Delete from chapter
               mysql_query( "delete from am_chapter where faq_fldAuto = " . $fldAuto );
               }

        header("Location: admin_default.php");
        exit;
        }
?>

<html>

<head>
<?



if( $action != "new")
     {
     $oRS = mysql_query( "select * from am_faq where fldAuto = " . $fldAuto );
     $row=mysql_fetch_array($oRS);
     $name = $row["name"];
     $descr = $row["descr"];
     }
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
      <b><a href="http://www.aspcode.net"><font face="verdana,arial,helvetica" size="1">http://www.aspcode.net</font></a></b>
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
                            </td>
                          </tr>
                        </table>
                        <font color="#aa3333" face="verdana,arial,helvetica" size="+2">
                        <hr color="#000066" noShade SIZE="1">

                        </font>

                        <table border="0" width="100%">
                          <tr>
                            <td width="70%">

                        <p align="left"><br>
                        <?
                        $sURL = "admin_faq.php?save=yes&action=$action";
                        if ( $action != "new")
                                $sURL = $sURL . "&fldAuto=$fldAuto";
                        ?>
                        <form method="POST" action="<? echo($sURL);?>">
                          <p align="left">Name: <input type="text" name="name" size="40" value="<? echo($name);?>"></p>
                          <p align="left">Description: <textarea rows="20" name="descr" cols="80"><? echo($descr);?></textarea><br>
</p>
<?
if ( $action == "del" )
      $bval = "Delete";
else
          $bval = "Save";
?>
                          <p align="left"><input type="submit" value="<?echo($bval);?>" name="B1"></p>
                        </form>

                        <p><font face="helvetica, arial" size="2">FAQMentor is
                        developed by <a href="http://www.aspcode.net">ASPCode.net</a>. Usage of it is totally free.</font>

                        <p>&nbsp;

                        <p>&nbsp;

                        <p>&nbsp;

                        <p>&nbsp;

                        <p>&nbsp;

                        <p>&nbsp;

                        <p>&nbsp;

                          </tr>
                          <tr>
                            <td width="70%">

                            </td>
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