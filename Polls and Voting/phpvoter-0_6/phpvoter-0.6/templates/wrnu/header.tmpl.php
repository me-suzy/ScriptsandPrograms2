<?php

$headerfile = getfile("../header.shtml");
$menufile = getfile("../menu.shtml");
$template_content = <<<ENDSTRING
{$headerfile}
<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0" WIDTH="760">
        <TR>
                <TD BACKGROUND="/images/litet_falt.gif" WIDTH="169" HEIGHT="30">
                <CENTER><IMG SRC="/images/text/meny.gif" BORDER="0" ALIGN="bottom"></CENTER>
                </TD>
                <TD BACKGROUND="/images/stort_falt2.gif" WIDTH="422" HEIGHT="30">
                <CENTER><IMG SRC="/images/text/rosta.gif" BORDER="0" ALIGN="bottom"></CENTER>
                </TD>
                <TD BACKGROUND="/images/litet_falt.gif" WIDTH="169" HEIGHT="30">
                <CENTER><IMG SRC="/images/text/tidigare.gif" BORDER="0" ALIGN="bottom"></CENTER>
                </TD>
        </TR>
   
        <TR>
   <TD VALIGN="TOP" WIDTH="169" HEIGHT="267">

        <!-- Beginning of left field -->
	<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0" WIDTH="169">
               <TR>
                  <TD ALIGN="left" VALIGN="top" WIDTH="169" HEIGHT="70">
		{$template['fontstring']}
{$menufile}
		</FONT>                   			  
		</TD>
		</TR>
	
		<TD BACKGROUND="/images/litet_falt.gif" WIDTH="169" HEIGHT="30">
                <CENTER><IMG SRC="/images/text/fraga.gif" BORDER="0" ALIGN="bottom"></CENTER>
                </TD>
	</TABLE>
	<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="4" WIDTH="169">
               <TR>
                  <TD ALIGN="left" VALIGN="top" WIDTH="169" HEIGHT="70" BGCOLOR="#CCCCCC">
		  {$template['fontstring']}
{$tmplarr['vote']}
		  </FONT>
		</TD>
               </TR>
            </TABLE>
           
        <!-- End of left field -->
    </TD>


    <TD ALIGN="center" VALIGN="top" WIDTH="422" HEIGHT="267">
                <TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0">
          <TR>
             <TD WIDTH=10>
                <IMG SRC="/images/trans.gif" BORDER="0" WIDTH="10" HEIGHT="2" ALT="">
             </TD>
             <TD VALIGN=top WIDTH=402>
		{$template['fontstring']}
        <!-- Beginning of middle field -->
ENDSTRING;

?>