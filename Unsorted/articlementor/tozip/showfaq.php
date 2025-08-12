<?php
require ("incconfig.php");
require ("inctemplate.php");

if ( $fldAuto == "" )
      {
      IncNav_Redirect( "index.php" );
      exit();
      }


$conn = Incdb_GetConnectionDedicated();
$sContent = "";
$sHeader = "";
$sHeader2 = "";


$oRS = mysql_query( "select descr from am_faq where fldAuto=$fldAuto" );
$row=mysql_fetch_array($oRS);
$sChapterDescr = stripslashes($row["descr"]);
$sChapterDescr = str_replace("\n","<br>",$sChapterDescr);

$oRS = mysql_query( "select question, question.fldAuto as qid, faq.name as faqname, chapter.name as chaptername from am_question as question, am_faq as faq, am_chapter as chapter where question.chapter_fldAuto=chapter.fldAuto AND chapter.faq_fldAuto=faq.fldAuto AND faq.fldAuto = $fldAuto order by chapter_fldAuto, chapter.orderingfield, question.orderingfield" );
if (! $row=mysql_fetch_array($oRS) )
       {
      IncNav_Redirect( "index.php" );
      exit();
       }


$NavNames = array( $sSiteTitle, $row["faqname"] );
$NavLinks = array( "index.php", "showfaq.php?fldAuto=$fldAuto" );

$sHeader = $row["faqname"] . " articles";
//$sHeader2 = "We are not only ASP programmers - we are webmasters as well!";

$sContent = "";

$sContent = $sContent .$sChapterDescr . "<br><br>";


$sCurrentSubcat = "";
$sContent = $sContent .  "<table cellPadding=3 cellSpacing=0 width=\"100%\">";
while( true )
       {
       if ( $sCurrentSubcat != trim( $row["chaptername"] ))
             {
              //Start a new one...
              if( $sCurrentSubcat <> "" )
                   $sContent =  $sContent . "</UL></FONT></TD></TR>";
               $sContent =  $sContent .  "<TR><TD class=TDHeader vAlign=top><div class=TDHeaderText>" . $row["chaptername"] . "</div></TD></TR>";
               $sContent =  $sContent .  "<TR><TD class=TDRow vAlign=top><UL>";
              }
       $sContent =  $sContent . "<LI><A class=TDRowText href=\"showquestion.php?faq=$fldAuto&fldAuto=" . $row["qid"] . "\">";
       $sContent =  $sContent . trim($row["question"]) . "</a><br>";
       $sCurrentSubcat = $row["chaptername"];
//       $sContent = $sContent . "<br>";
       if (!$row=mysql_fetch_array($oRS) )
             break;
       }
if( $sCurrentSubcat != "" )
                $sContent = $sContent . "</UL></FONT></TD></TR>";
$sContent = $sContent . "</table><br>";
WriteContent( $conn, $NavNames, $NavLinks, $sContent, $sHeader);
?>