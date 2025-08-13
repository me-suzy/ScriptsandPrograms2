<%include file="header.php"%>

<BR><BR>
<FORM ACTION="<%$selfURL%>" METHOD=POST>
<input type="hidden" name="tryLogin" value="1">

<TABLE WIDTH="100%" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#CCCCCC" background="">
        <TR>
        <TD>
                <TABLE WIDTH="100%" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="4" BGCOLOR="#FFFFFF" background="">
                        <TR>
                        <TD ALIGN="CENTER" CLASS="captionText">Member Login Page</TD>
                        </TR>
                        <TR>
                        <TD ALIGN="CENTER" CLASS="captionText"><b><%$msg%></b></TD>
                        </TR>

                </TABLE>
        </TD>
        </TR>
</TABLE>
<BR><center> For Demo Member View, use the following login:<br>
username: member<br>
password: member<br><BR>
<TABLE WIDTH="" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#CCCCCC">
        <TR>
        <TD>
        <TABLE WIDTH="" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#FFFFFF" background="">
                <TR>
                        <TD ALIGN="CENTER">
                        <TABLE WIDTH="200" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#CCCCCC" background="">
                                <TR BGCOLOR="#DDDDDD">
                                        <TD CLASS="NORMALTEXT" WIDTH="80">Login:</TD>
                                        <TD WIDTH="120"><INPUT TYPE="TEXT" NAME="memberLogin"></TD></TR>
                                <TR BGCOLOR="#DDDDDD">
                                        <TD CLASS="NORMALTEXT" WIDTH="80">Password:</TD>
                                        <TD WIDTH="120"><INPUT TYPE="PASSWORD" NAME="memberPassword"></TD></TR>
                                <TR BGCOLOR="#DDDDDD">
                                        <TD COLSPAN="2" ALIGN="CENTER">
                                                <INPUT TYPE="SUBMIT" CLASS="MENUBUTTON" NAME="LOGIN" VALUE="LOGIN">
                                        </TD>
                                </TR>
                                <TR BGCOLOR="#DDDDDD">
                                        <TD COLSPAN="2" ALIGN="CENTER">
                                                <A HREF="<%$selfURL%>?mode=members&memberMode=register" >register</a>
                                        </TD>
                                </TR>
                                <TR BGCOLOR="#DDDDDD">
                                        <TD COLSPAN="2" ALIGN="CENTER">
                                                <A HREF="<%$selfURL%>?mode=members&memberMode=forgot" >forgot password?</a>
                                        </TD>
                                </TR>

                        </TABLE>
                </TD>
                </TR>
        </TABLE>
        </TD>
        </TR>
</TABLE>
</FORM>
<TABLE WIDTH="100%" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#CCCCCC" background="">
        <TR>
        <TD>
                <TABLE WIDTH="100%" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="4" BGCOLOR="#FFFFFF">
                        <TR>
                        <TD ALIGN="CENTER" CLASS="normalText">Please enter your Login and Password to enter member area.</TD>
                        </TR>
                </TABLE>
        </TD>
        </TR>
</TABLE>
<TABLE WIDTH="100%" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="10" BGCOLOR="#FFFFFF" background="">
</TABLE>

<%include file="footer.php"%>