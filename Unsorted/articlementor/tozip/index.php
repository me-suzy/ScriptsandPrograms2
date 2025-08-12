<?php
require ("incconfig.php");
require ("inctemplate.php");

$conn = Incdb_GetConnectionDedicated();
$sContent = "";
$sHeader = "";
$sHeader2 = "";

function IsValidWebSite( $sString )
{
 $sStringTemp = substr($sString,0,4);
 $sStringTemp = trim(strtoupper( $sStringTemp ));
 return strcmp($sStringTemp, "HTTP") == 0;
}



$NavNames = array( $sSiteTitle );
$NavLinks = array( "index.php" );

$sHeader = "";
//$sHeader2 = "We are not only ASP programmers - we are webmasters as well!";


//Show the latest
$sContent = "";
$sContent = $sContent . "<table border=\"0\" width=\"100%\">";




$oRS = mysql_query( "select answer,question, am_question.fldAuto as qid, am_faq.fldAuto as faqid, am_faq.name as faqname, am_chapter.name as chaptername from am_faq ,am_chapter, am_question where am_question.chapter_fldAuto=am_chapter.fldAuto AND am_chapter.faq_fldAuto=am_faq.fldAuto order by am_question.fldAuto desc LIMIT 0,10" );
while ( $row=mysql_fetch_array($oRS) )
         {
         $ChapterName =$row["chaptername"];
         $FAQName =$row["faqname"];
         $Question =$row["question"];
         $Answer =$row["answer"];
         $Answer = strip_tags($Answer);
         if (IsValidWebsite($Answer))
             {
             $Answer = "";
             }
         else
             $Answer = substr($Answer, 0,255) . "...";
         $ArticleData = "<div class=TDRowText>$Answer</a><br>";
         $link = "showquestion.php?fldAuto=" . $row["qid"] . "&faq=". $row["faqid"];
         $ArticleData = $ArticleData . "<br><a class=TDRowText href=\"$link\">[Read more]</a><br><br>";

         $sContent = $sContent . "<tr>";
         $sContent = $sContent . "<td width=\"100%\" class=TDHeader><div class=TDHeaderText>$FAQName : $Question</td>";
         $sContent = $sContent . "</tr>";
         $sContent = $sContent . "<tr>";
         $sContent = $sContent . "<td class=TDRow width=\"100%\">$ArticleData</td>";
         $sContent = $sContent . "</tr>";
         }


$sContent = $sContent . "</table><br>";

WriteContent( $conn, $NavNames, $NavLinks, $sContent, $sHeader);
?>