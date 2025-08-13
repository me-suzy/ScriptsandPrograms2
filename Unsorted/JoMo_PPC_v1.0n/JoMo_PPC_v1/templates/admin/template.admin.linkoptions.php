<%include file="admin/admin.header.php"%>

<BR>
<%include file="admin/admin.menu.php"%>
<BR>

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="" background="">
<TR ALIGN="left" bgcolor="" >
        <TD Align="center">
                <b>Links options.</b>
        </TD>
</TR>
</TABLE>


<form name="optionForm" ACTION="<%$selfURL%>" method="post">

<input type="hidden" name="mode" value="links">
<input type="hidden" name="cmd" value="">
<input type="hidden" name="option" value="">
<input type="hidden" name="value" value="">

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="" background="">
<TR ALIGN="CENTER" bgcolor="lightgreen" >
        <TD Align="center"> option</TD>
        <TD Align="center"> value </TD>
        <TD Align="center" width="200">  description</TD>
        <TD Align="center">  command</TD>
</TR>

<TR bgcolor="#cccccc">
        <TD align="left">
                approveListing
        </TD>
        <TD align="center">
        	    <input type="text" value="1">
        </TD>
        <TD align="left">
                it is option
        </TD>
        <TD align="center">
                <a href="#">update</a>
        </TD>
</TR>
</TABLE>

</FORM>


<TABLE ALIGN="CENTER" BORDER="1" CELLPADDING="5" CELLSPACING="1" width="100%" bgcolor="#cccccc" bordercolor="#999999">
<TR ALIGN="left" >
	<TD>
		View and edit other options:
		<UL>
			<LI> <a href="#">search options</a>
			<LI> <a href="#">notification options</a>
			<LI> <a href="#">account options</a>
		</UL>
    </TD>

</TR>
</TABLE>





<%include file="admin/admin.footer.php"%>
