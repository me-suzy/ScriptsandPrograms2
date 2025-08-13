<%include file="admin/admin.header.php"%>

<BR><BR>
<FORM ACTION="<%$selfURL%>" METHOD=POST>
<input type="hidden" name="tryLogin" value="1">

<TABLE ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#CCCCCC" background="">
        <TR>
        <TD>
                <TABLE WIDTH="380" ALIGN="CENTER" BORDER="1" CELLSPACING="0" CELLPADDING="4" BGCOLOR="#cccccc" background="" bordercolor="black">
                        <TR>
                        <TD ALIGN="CENTER" CLASS="captionText">Admin area</TD>
                        </TR>
                        <TR>
                        <TD ALIGN="CENTER" CLASS="captionText"><b><%$msg%></b></TD>
                        </TR>

                </TABLE>
        </TD>
        </TR>
</TABLE>
<BR><center>To view Admin Demo use the following login...<br>
<center>username: admin<br>
<center>password: admin
<BR><br>
<TABLE WIDTH="" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#CCCCCC">
        <TR>
        <TD>
        <TABLE WIDTH="" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#FFFFFF" background="" bordercolor="black">
                <TR>
                        <TD ALIGN="CENTER">
                        <TABLE WIDTH="" BORDER="0" CELLSPACING="1" CELLPADDING="4" BGCOLOR="#CCCCCC" background="">
                                <TR BGCOLOR="#DDDDDD">
                                        <TD CLASS="NORMALTEXT" WIDTH="80">Login:</TD>
                                        <TD WIDTH="120"><INPUT TYPE="TEXT" NAME="login"></TD></TR>
                                <TR BGCOLOR="#DDDDDD">
                                        <TD CLASS="NORMALTEXT" WIDTH="80">Password:</TD>
                                        <TD WIDTH="120"><INPUT TYPE="PASSWORD" NAME="password"></TD></TR>
                                <TR BGCOLOR="#DDDDDD">
                                        <TD COLSPAN="2" ALIGN="CENTER">
                                                <INPUT TYPE="SUBMIT" CLASS="MENUBUTTON" NAME="LOGIN" VALUE="LOGIN">
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
<TABLE WIDTH="380" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#CCCCCC" background="">
        <TR>
        <TD>
                <TABLE WIDTH="380" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="4" BGCOLOR="#FFFFFF">
                        <TR>
                        <TD ALIGN="CENTER" CLASS="normalText">Please enter your Login and Password to enter admin area.</TD>
                        </TR>
                </TABLE>
        </TD>
        </TR>
</TABLE>
<TABLE WIDTH="380" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="1" background="">
        <TR>
        <TD align="center">
                <a href="<%$selfURL%>?mode=gotosite">go to site</a>
        </TD>
        </TR>
</TABLE>

<%include file="admin/admin.footer.php"%>