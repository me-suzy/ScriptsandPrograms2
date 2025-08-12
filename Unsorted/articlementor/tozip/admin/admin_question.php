<?php
require ("../incconfig.php");

    $oConn = Incdb_GetConnectionDedicated();

    if( $save=="yes" )
         {
         $question = addslashes($question);
         $answer = addslashes($answer);
         if ( $action == "new" )
               {
               mysql_query( "insert into am_question( question, answer,orderingfield, chapter_fldAuto ) values('$question', '$answer', 'ZZZZZZ', $chapter_fldAuto)"  );
               }
         if ( $action == "edit")
               {
               mysql_query( "update am_question set question = '$question', answer = '$answer' where fldAuto=$fldAuto"  );
               }
         if ( $action=="del" )
               {
               //Ta bort ur FAQ
               mysql_query( "delete from am_question where fldAuto = " . $fldAuto );

               }

        header("Location: admin_questions.php?chapter_fldAuto=$chapter_fldAuto");
        exit;
        }
?>




<html>

<head>
<?

if ( $action != "new" )
      {
     $oRS = mysql_query( "select * from am_question where fldAuto = " . $fldAuto );
     $row=mysql_fetch_array($oRS);
     $name = $row["question"];
     $descr = stripslashes($row["answer"]);
        }

$oRSChapter = mysql_query( "select * from  am_chapter where fldAuto =  $chapter_fldAuto") ;
$row=mysql_fetch_array($oRSChapter);
$sFAQName = $row["name"];
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
                        <p><font size="+1"><? echo($sFAQName); ?></font></p>


                        <table border="0" width="100%">
                          <tr>
                            <td width="70%">

                        <p align="left"><b>Question</b><br>
                        <?
                        $sURL = "admin_question.php?save=yes&action=$action&fldAuto=$fldAuto&chapter_fldAuto=$chapter_fldAuto";
                        ?>
                        <form method="POST" action="<?echo($sURL);?>">
                          <p align="left">Header: <input type="text" name="question" size="80" value="<?echo($name);?>"></p>
                          <p align="left">Articletext:<textarea rows="20" name="answer" cols="80"><?echo($descr);?></textarea></p>
                          <p align="left"><font size="1"><b>Note: you can create
                          multipage articles by inserting &lt;NEWPAGE&gt; tags
                          where you want a page break.</b></font></p>
                          <p align="left"><font size="1"><b>Note2:If you only
                          write an URL in the articletext then an Redirect will
                          be done to that page.</b></font></p>
                          <p align="left">&nbsp;</p>
                          <p align="left">&nbsp;</p>
                          <p align="left"><input type="submit" value="Submit" name="B1"></p>
                        </form>

<p><a href="admin_questions.php?chapter_fldAuto=<? echo($nChapterID); ?>">Back to current chapter ( <?echo($sFAQName);?>)&nbsp;</a>

<p><a href="admin_default.asp">Back to home&nbsp;</a>

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