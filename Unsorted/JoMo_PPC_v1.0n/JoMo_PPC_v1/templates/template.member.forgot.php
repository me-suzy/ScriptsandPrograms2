<%include file="header.php"%>


<FORM ACTION="<%$selfURL%>" METHOD=POST>
<input type="hidden" name="action" value="remember">
<input type="hidden" name="memberType" value="<%$memberType%>">

<TABLE WIDTH="100%" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#CCCCCC" background="">
        <TR>
        <TD>
                <TABLE WIDTH="100%" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="4" BGCOLOR="#FFFFFF" background="">
                        <TR>
                        <TD ALIGN="CENTER" CLASS="captionText">Enter your email</TD>
                        </TR>
                        <TR>
                        <TD ALIGN="CENTER" CLASS="captionText"><b><%$msg%></b></TD>
                        </TR>

                </TABLE>
        </TD>
        </TR>
</TABLE>
<BR><BR>
<TABLE WIDTH="220" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#CCCCCC">
        <TR>
        <TD>
        <TABLE WIDTH="200" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#FFFFFF" background="">
                <TR>
                        <TD ALIGN="CENTER">
                        <TABLE WIDTH="200" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#CCCCCC" background="">
                                <TR BGCOLOR="#DDDDDD">
                                        <TD CLASS="NORMALTEXT" WIDTH="80">email:</TD>
                                        <TD WIDTH="120"><INPUT TYPE="TEXT" NAME="email"></TD></TR>
                        </TABLE>
                </TD>
                </TR>
        </TABLE>
        </TD>
        </TR>
</TABLE>

<TABLE WIDTH="220" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#CCCCCC" background="" align="center">
  <TR>
                                        <TD COLSPAN="1" ALIGN="CENTER">
                                                <INPUT TYPE="SUBMIT" CLASS="MENUBUTTON" VALUE="submit">
                                        </TD>
                                        <TD COLSPAN="1" ALIGN="CENTER">
                                                <INPUT TYPE="button" CLASS="MENUBUTTON" NAME="submit" VALUE="CANCEL"
                           onclick="location.href='<%$selfURL%>?mode=<%$memberType%>s&<%$memberType%>Mode=login'">

                                        </TD>
  
  </TR>
</TABLE>

</FORM>

<TABLE WIDTH="100%" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#CCCCCC" background="">
        <TR>
        <TD>
                <TABLE WIDTH="100%" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="4" BGCOLOR="#FFFFFF">
                        <TR>
                        <TD ALIGN="CENTER" CLASS="normalText">Your password will be sent to email.</TD>
                        </TR>
                </TABLE>
        </TD>
        </TR>
</TABLE>

<%include file="footer.php"%>