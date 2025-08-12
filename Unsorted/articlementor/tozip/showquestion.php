<?php
require ("incconfig.php");
require ("inctemplate.php");


function IsValidWebSite( $sString )
{
 $sStringTemp = substr($sString,0,4);
 $sStringTemp = trim(strtoupper( $sStringTemp ));
 return strcmp($sStringTemp, "HTTP") == 0;
}

function FixText( $sText )
{
        $sText = str_replace("\n","<br>",$sText);
         return $sText;
}

function WritePrev()
{
 global $nCurPage;
 global $nfaq_fldAuto;
 global $nQuestionId;

 $nTemp =(int)$nCurPage;
 $sTemp2 = $nTemp - 1;
 $sTemp2 = strval($sTemp2);

        if( $nTemp != 1 )
                return "<a href=\"showquestion.php?faq=$nfaq_fldAuto&fldAuto=$nQuestionId&page=" . $sTemp2  . "\">" . "<img src=\"prev.gif\">" . "</a>";
        return "";
}

function WriteNext()
{
 global $nCurPage;
 global $nfaq_fldAuto;
 global $nQuestionId;
 global $nTotalPages;
        if( (int)$nCurPage < (int)$nTotalPages )
                return "<a href='showquestion.php?faq=" . $nfaq_fldAuto . "&fldAuto=" . $nQuestionId . "&page=" . strval($nCurPage+1)  . "'>" . "<img src='next.gif'>" . "</a>";
        return "";
}

$nQuestionId = $fldAuto;
if ($nQuestionId == "" )
     {
      IncNav_Redirect( "index.php" );
      exit();
     }
$nfaq_fldAuto = $faq;
if ($nfaq_fldAuto == "" )
     {
      IncNav_Redirect( "index.php" );
      exit();
     }

$conn = Incdb_GetConnectionDedicated();
$sContent = "";
$sHeader = "";
$sHeader2 = "";


$oRS = mysql_query( "select question, answer from am_question where fldAuto=$nQuestionId" );
if (! $row=mysql_fetch_array($oRS) )
       {
      IncNav_Redirect( "index.php" );
      exit();
       }

$sQuestion = $row["question"];
$sAnswer = $row["answer"];

///
$vPages = explode( "<NEWPAGE>", $sAnswer );
$nTotalPages = sizeof( $vPages );


$fIsValidWebsite = IsValidWebsite($vPages[0]);
if ($nTotalPages== 1 && IsValidWebsite($vPages[0]))
     {
      header("Location: " . $vPages[0]  );
      exit();
     }


$nCurPage = $page;
if ($nCurPage == "" || $nCurPage > $nTotalPages || $nCurPage< 1 )
        $nCurPage = 1;
$sTextToShow = stripslashes(FixText($vPages[$nCurPage-1]));
///

$oRS2 = mysql_query( "select name from am_faq where fldAuto=$nfaq_fldAuto" );


if (( $row2=mysql_fetch_array($oRS2)) == false)
       {
      IncNav_Redirect( "index.php" );
      exit();
       }
$sFaqName = $row2["name"];


$NavNames = array( $sSiteTitle, $sFaqName, $sQuestion );
$NavLinks = array( "index.php", "showfaq.php?fldAuto=$faq","showfaq.php?fldAuto=$fldAuto"  );

$sHeader = "Content";
//$sHeader2 = "We are not only ASP programmers - we are webmasters as well!";


$sContent = "";
$sCurrentSubcat = "";
$sContent = $sContent . "<div class=TDRowText>$sTextToShow</div><br>";

$sContent = $sContent . "<table border=\"0\" width=\"100%\">";
$sContent = $sContent . "<tr>";
$sContent = $sContent . "<td width=\"50%\">";
$sContent = $sContent . WritePrev() . "</td>";
$sContent = $sContent . "<td width=\"50%\">";
$sContent = $sContent . "<p align=\"right\">" . WriteNext() . "</td>";
$sContent = $sContent . "</tr>";
$sContent = $sContent . "</table>";
$sContent = $sContent . "<p> </p>";

$sHeader = $sQuestion;
WriteContent( $conn, $NavNames, $NavLinks, $sContent, $sHeader);
?>