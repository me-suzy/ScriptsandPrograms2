<%include file="admin/admin.header.php"%>

<BR>
<%include file="admin/admin.menu.php"%>
<BR>

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="" background="">

<TR ALIGN="CENTER" >
        <TD Align="left">
        <UL>
        	<LI><a href="<%$selfURL%>?mode=clickstats">members' click statistics</a><br>
        	<LI><a href="<%$selfURL%>?mode=affclickstats">affiliates' click statistics</a><br>
			<LI><a href="<%$selfURL%>?mode=keywordstats">keyword popularity</a>-view keywords, searched by visitors.<br>
		</UL>
        </TD>
</TR>
</TABLE>





<%include file="admin/admin.footer.php"%>