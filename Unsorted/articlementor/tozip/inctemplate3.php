<?
function WriteContent( $conn, $NavNames, $NavLinks, $sContent, $sHeader )
{
?>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<LINK href="style.css" rel=stylesheet type=text/css>
<title><? echo($NavNames[0]);?></title>
</head>
<body>
<table align="center" bgColor="#A9C0D8" border="0" cellPadding="3" cellSpacing="0"
height="100%" width="100%">
  <tr>
    <td vAlign="top"><font color="#660000" face="Tahoma,Verdana,Arial" size="+3"><b>Stefans PHP</b></font><br>
    <b><font size="4">This is ArticleMentor</font></b></td>
    <td vAlign="middle" width="468">    </td>
  </tr>
  <tr>
    <td colspan="2">
<TABLE bgColor=black border=0 cellPadding=1 cellSpacing=0 width="100%">
  <TR>
    <TD>
      <TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
        <TBODY>
        <TR bgColor=#eeeeaa>
          <TD><B><FONT face="Arial,Helvetica,sans serif" size=2> </FONT></B><b><font face="Arial,Helvetica" size="2"><? IncTemplate_WriteCurrentNavigationPath( $NavNames, $NavLinks );?></font></b></TD>
          <TD align=right vAlign=baseline>

          </TD></TR></TBODY></TABLE></TD>
          </TR></TABLE>
    </td>
  </tr>
  <tr>
    <td height="100%" vAlign="top" width="100%" colspan="2"><table align="center"
    bgColor="#ffffff" border="0" cellSpacing="1" height="100%" width="100%">
<tbody>
      <tr>
        <td bgColor="#eeeeaa" height="100%" vAlign="top">
<? IncTemplate_WriteWholeMenu( $oConn ); ?>
</td>
        <td height="100%" vAlign="top"><table bgColor="#ffffff" border="0"
        cellPadding="10" cellSpacing="0" height="100%" width="100%">
<tbody>
          <tr>
            <td align="left" vAlign="top">
              <H1><? echo($sHeader);?></H1>

              <? echo($sContent);?><br><br><br>
            </td>
      </tr>
</tbody>
    </table>
    </td>
  </tr>
  <tr>
    <td height="100%" vAlign="top" width="100%" colspan="2">
    </td>
  </tr>
</table>
<br><br><br>

</body>

</html>
<?php
}

function IncTemplate_WriteCurrentNavigationPath( $NavNames, $NavLinks )
{
        $num = count( $NavNames );
        if ( $num > 0 )
                {
                for ( $n=0; $n < $num; ++$n )
                        {
            if ( $n!=0 )
                            echo( " : " );
                        If ( $n != $num-1 )
                                {
                                echo(  "<a href=\"$NavLinks[$n]\">" );
                                }
                          echo( $NavNames[$n] );
                        if ( $n != $num-1 )
                                {
                                echo(  "</a>" );
                                }
                        }
                }
}


function IncTemplate_WriteWholeMenu( $oConn )
{
        $sMenu = "";
        $sMenu = $sMenu .  "<br>" . IncTemplate_WriteRubrik( "Articles", "");
        $oRS = mysql_query( "select max(am_faq.fldAuto) as faqid, max(am_faq.name) as name, count(*) as antal from am_faq ,am_chapter, am_question where am_question.chapter_fldAuto=am_chapter.fldAuto AND am_chapter.faq_fldAuto=am_faq.fldAuto group by am_faq.fldAuto " );

        while ( $row=mysql_fetch_array($oRS) )
                 {
                 $sURL = "showfaq.php?fldAuto=" . $row["faqid"];
                 $sMenu = $sMenu . IncTemplate_WriteMenuItem( $sURL, $row["name"] . " (" . $row["antal"] . ")" );
                 }

        $sMenu = $sMenu .  "<br>";

        echo( $sMenu );
};



function IncTemplate_WriteMenuItem(  $sRef, $sText )
{
        return "<div><A class=MenyItem href=\"$sRef\">$sText</A></div>";
}

function IncTemplate_WriteMenuItemTargetBlank( $sRef, $sText )
{
        return  "<div><A class=MenyItem target=_blank href=\"$sRef\">$sText</A></div>";
}



function IncTemplate_WriteRubrik( $sText, $sRef )
{
        $sRet = "<div class=MenyRubrik><IMG alt=\"\" border=0 height=8 src=\"box.gif\" width=8>  ";
        if( $sRef != "" )
                {
                $sRet = $sRet . "<a href = \"$sRef\">" ;
                }
        $sRet = $sRet . "<b>$sText</b>";
        if( $sRef != "" )
                {
                $sRet = $sRet . "</a>" ;
                }
        $sRet = $sRet . "</div>";
        return $sRet;
};




?>