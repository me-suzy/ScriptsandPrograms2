<%include file="header.php"%>

<BR>
<%include file="member.menu.php"%>
<BR>

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="" background="">

<TR ALIGN="CENTER" >
        <TD Align="left">
        <UL>
        	<LI><a href="<%$selfURL%>?mode=members&memberMode=stats">click statistics</a><br>
			<LI><a href="<%$selfURL%>?mode=members&memberMode=bidstats">bid statistics</a>-view bids placed on keywords by other members<br>
			<LI><a href="<%$selfURL%>?mode=members&memberMode=keywordstats">keyword popularity</a><br>
		</UL>
        </TD>
</TR>
</TABLE>


<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="" background="">
<TR ALIGN="left" bgcolor="" >
        <TD Align="center">
                <b>Bid statistics.</b>
        </TD>
</TR>
</TABLE>


<form name="f" ACTION="<%$selfURL%>" method="post">

<input type="hidden" name="memberMode" value="bidstats">
<input type="hidden" name="cmd" value="">

<TABLE ALIGN="CENTER" BORDER="1" CELLPADDING="5" CELLSPACING="1" width="100%" bgcolor="#cccccc" bordercolor="#999999">
<TR ALIGN="left" >
        <TD Align="left">
			keyword:
            <INPUT name="keyword" value="<%$keyword%>" STYLE="width:250"><br>
            select top <INPUT name="top" value="<%$top%>" STYLE="width:50"> items
            <br>
            <input type="submit" value="apply" >
        </TD>
</TR>
</TABLE>


<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" width="100%" background="">
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="CENTER" bgcolor="#eeeeee">
        <TD align="left" colspan="8">
                <br>
                view bids placed on keywords by other members				
        </TD>
</tr>

<TR ALIGN="CENTER" bgcolor="#FFB13E">
		<TD Align="center" width="20">#</TD>
		<TD Align="center" width="200">keyword</TD>
        <TD Align="center" width="50">bid</TD>
        <TD Align="center" width="200">member</TD>
        <TD Align="center" width="150">modified</TD>
</TR>

<%section name=ItemLoop loop=$items%>

<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="CENTER"
bgcolor="<%if $smarty.section.ItemLoop.index is even %>#c8c8c8<%else%>#dDdDdD<%/if%>"
>
	 <TD align="center">
             <%$items[ItemLoop].index%>
     </TD>
     <TD align="left">
             <%$items[ItemLoop].keywordName%>
     </TD>
     <TD align="left">
     	<%$items[ItemLoop].bid%>
     </TD>
     <TD align="left">
     	<%$items[ItemLoop].membername%>
     </TD>
     <TD align="left">
     	<%$items[ItemLoop].modificationDate%>
     </TD>
</TR>

<%sectionelse%>
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="CENTER"
bgcolor="#c8c8c8"
>
	 <TD align="center" colspan="5">
             no items
     </TD>
</TR>

<%/section%>


</FORM>



<%include file="member.footer.php"%>