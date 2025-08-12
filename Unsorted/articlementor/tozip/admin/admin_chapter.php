<?php
require ("../incconfig.php");

    $oConn = Incdb_GetConnectionDedicated();

    if( $save=="yes" )
         {
         $name = addslashes($name);
         if ( $action == "new" )
               {
               mysql_query( "insert into am_chapter(name,orderingfield,faq_fldAuto) values('$name', 'ZZZZZZ', $faq_fldAuto)"  );
               }
         if ( $action == "edit")
               {
               mysql_query( "update am_chapter set name='$name' where fldAuto=$fldAuto");
               }
         if ( $action=="del" )
               {
               //För alla chapter, ta bort dess tillhörande quesitons
                mysql_query( "delete from am_question where chapter_fldAuto = $fldAuto" );

               //Delete from chapter
               mysql_query( "delete from am_chapter where fldAuto = $fldAuto" );
               }

        header("Location: admin_chapters.php?faq_fldAuto=$faq_fldAuto");
        exit;
        }



?>

<html>

<head>
<?


if( $action != "new")
     {
     $oRS = mysql_query( "select * from am_chapter where fldAuto = " . $fldAuto );
     $row=mysql_fetch_array($oRS);
     $name = $row["name"];
     }

$oRSFAQ = mysql_query( "select * from  am_faq where fldAuto =  $faq_fldAuto") ;
$row=mysql_fetch_array($oRSFAQ);
$sFAQName = $row["name"];
$nFaqId = $row["faq_fldAuto"];

?>


<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>PostcardMentor - Admin interface</title>
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
                            <td width="50%"><b><font color="#aa3333" face="verdana,arial,helvetica" size="4">ArticleMentor <%=Session("fullname")%></font></b>

                            </td>
                            <td width="50%">
                            </td>
                          </tr>
                        </table>
                        <hr color="#000066" noShade SIZE="1">
                        <p><font size="+1"><? echo($sFAQName);?></font></p>


                        <table border="0" width="100%">
                          <tr>
                            <td width="70%">

                        <p align="left"><b>Subcategory</b><br>
                        <?
                        $sURL = "admin_chapter.php?save=yes&action=$action&fldAuto=$fldAuto&faq_fldAuto=$faq_fldAuto";
                        ?>
                        <form method="POST" action="<? echo($sURL);?>">
                          <p align="left">Name: <input type="text" name="name" size="40" value="<? echo($name);?>"></p>
<?
if ( $action == "del" )
      $bval = "Delete";
else
          $bval = "Save";

?>
                          <p align="left"><input type="submit" value="<?echo($bval);?>" name="B1"></p>
                        </form>

                        <a href="admin_chapters.php?faq_fldAuto=<? echo($faq_fldAuto);?>">Back to current
                        category ( <? echo($sFAQName);?> )&nbsp;</a><br>

                        <a href="admin_default.php">Back to home&nbsp;</a><br>

                        <p><font face="helvetica, arial" size="2">ArticleMentor is
                        developed by <a href="http://www.aspcode.net">ASPCode.net</a>. Usage of it is totally free.</font></p>
                        <table border="0" width="100%">
                          <tr>
                            <td width="50%">
                            </td>
                            <td width="50%">
                            </td>
                          </tr>
                        </table>
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