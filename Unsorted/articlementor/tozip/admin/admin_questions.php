<?php
require ("../incconfig.php");

    $oConn = Incdb_GetConnectionDedicated();

      if ( $save=="yes" )
            {
           //Get all fldAutos into array
           while(list($var, $val) = each($HTTP_POST_VARS))
                 {
                 if ( substr( $var,0,5) == "order" )
                      {
                      $nTheId = substr($var,5);
                      $theval = ${$var};
//                      echo("update am_chapter set orderingfield='$theval' where fldAuto=$nTheId");
                      mysql_query( "update am_question set orderingfield='$theval' where fldAuto=$nTheId" );
                      }
                  //echo "$var = " . ${$var} . "<BR>\n";
                  }
            }

?>

<html>

<head>
<?

$oRSChapter = mysql_query( "select * from  am_chapter where fldAuto =  $chapter_fldAuto") ;
$row=mysql_fetch_array($oRSChapter);
$sFAQName = $row["name"];
$nFaqID = $row["faq_fldAuto"];


$oRSQuestions = mysql_query("select * from am_question where chapter_fldAuto=$chapter_fldAuto order by orderingfield" )
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
<?
$sURL = "admin_questions.php?chapter_fldAuto=$chapter_fldAuto&save=yes";
?>
                        <form method="POST" action="<? echo($sURL);?>">
                        <table border="0" width="100%">
                          <tr>
                            <td width="34%" bgcolor="#ECECD9"><b>Article</b></td>
                            <td width="16%" bgcolor="#ECECD9"><b>Ordernumber</b></td>
                            <td width="34%" bgcolor="#ECECD9"><b>Actions</b></td>
                          </tr>
<?
$nVal = 1;
while ( $row=mysql_fetch_array($oRSQuestions) )
         {
         $sName = "order" . $row["fldAuto"];
?>
                          <tr>
                            <td width="34%"><? echo($row["question"]);?></td>
                            <td width="16%">
                            <input type="text" name="<? echo($sName); ?>" size="7" value="<? echo($nVal); ?>"></td>
                            <td width="34%"><a href="admin_question.php?action=edit&fldAuto=<? echo($row["fldAuto"]);?>&chapter_fldAuto=<? echo($row["chapter_fldAuto"]);?>">Modify</a> -<a href="admin_question.php?action=del&fldAuto=<? echo($row["fldAuto"]);?>&chapter_fldAuto=<? echo($row["chapter_fldAuto"]);?>"> Delete</a></td>
                          </tr>
<?
  $nVal = $nVal + 1;
}
?>
                        </table>

                        <p align="left">&nbsp;
                          <p align="left"><input type="submit" value="Change article ordering" name="B1">&nbsp;&nbsp;&nbsp;&nbsp;
                          <a href="admin_question.php?action=new&chapter_fldAuto=<? echo($chapter_fldAuto);?>">Add new article</a></p>
                        </form>

<?
$oRSFAQ = mysql_query ("select * from am_faq where fldAuto = $nFaqID" );
$row=mysql_fetch_array($oRSFAQ);
$sFAQName = $row["name"];
?>
<p><a href="admin_chapters.php?faq_fldAuto=<? echo($nFaqID); ?>">Back to current category
( <? echo($sFAQName); ?> )&nbsp;</a>

<p><a href="admin_default.php">Back
to home&nbsp;</a></p><p><font face="helvetica, arial" size="2">ArticleMentor is
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