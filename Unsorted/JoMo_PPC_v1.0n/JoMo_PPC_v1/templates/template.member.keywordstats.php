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
			<LI><a href="<%$selfURL%>?mode=members&memberMode=keywordstats">keyword popularity</a><br>		</UL>
        </TD>
</TR>
</TABLE>


<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="" background="">
<TR ALIGN="left" bgcolor="" >
        <TD Align="center">
                <b>Searched keywords.</b>
        </TD>
</TR>
</TABLE>


<form name="f" ACTION="<%$selfURL%>" method="post">

<input type="hidden" name="memberMode" value="keywordstats">
<input type="hidden" name="linkID" value="">
<input type="hidden" name="cmd" value="">

<TABLE ALIGN="CENTER" BORDER="1" CELLPADDING="5" CELLSPACING="1" width="100%" bgcolor="#cccccc" bordercolor="#999999">
<TR ALIGN="left" >
        <TD Align="left">
                <b>filter:</b>
			date:
			year:
			<SELECT name="year" STYLE="width:100">
             <%html_options values=$yearIDs output=$years selected=$year%>
            </SELECT>
			
			month:
			<SELECT name="month" STYLE="width:100">
             <%html_options values=$monthIDs output=$months selected=$month%>
            </SELECT>
            
            day:
			<SELECT name="day" STYLE="width:100">
             <option value=-1 SELECTED>current
             <%html_options values=$days output=$days selected=$day%>
            </SELECT>
            <br>
			
			keyword:
            <input type="text" name="keyword" value="<%$keyword%>" STYLE="width:250" >

        </TD>

</TR>
<TR ALIGN="left" >
        <TD Align="left">
                <b>show :</b>
            top 
            <input type="text" name="top" value="<%$top%>" STYLE="width:50"> keywords &nbsp<br>

            <input type="submit" value="apply" name="submitSetfilter">
        </TD>

</TR>

</TABLE>

<TABLE ALIGN="CENTER" BORDER="1" CELLPADDING="5" CELLSPACING="1" width="100%" bgcolor="#cccccc" bordercolor="#999999">
<TR ALIGN="left" >
        <TD Align="left">
			show details:
			date:			
			<span style="visibility:;">
            <SELECT name="detailsDate" STYLE="width:150">
            	<OPTION value="1" <%if $detailsDate == 1%>SELECTED<%/if%>>totals
				<OPTION value="2" <%if $detailsDate == 2%>SELECTED<%/if%>>totals by month
            	<OPTION value="3" <%if $detailsDate == 3%>SELECTED<%/if%>>month, day
            </SELECT>
			</span>
			
            <input type="submit" value="apply" name="submitSetfilter">
        </TD>

</TR>
</TABLE>

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" width="100%" background="">
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="CENTER" bgcolor="#eeeeee">
        <TD align="left" colspan="8">
                Enter the variables above to view statistics in the following table.<br>
        </TD>
</tr>

<TR ALIGN="CENTER" bgcolor="#FFB13E">
<%section name=Column loop=$columns%>
        <TD Align="center" ><%$columns[Column]%></TD>
<%/section%>        
        <TD Align="center">#searches</TD>

</TR>

<%section name=ItemLoop loop=$items%>
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="CENTER"
bgcolor="<%if $items[ItemLoop].level == 0%>#9999ff<%elseif $items[ItemLoop].level == 1%>#9999dd<%elseif $items[ItemLoop].level == 2%>#ccccff<%else%>#dddddd<%/if%>"
>

	<%section name=ColumnLoop loop=$ss[ItemLoop]%>
	
     <TD align="left">
             <%$ss[ItemLoop][ColumnLoop]%> 
     </TD>
     <%/section%>
     
     
     <TD align="center">		<%$items[ItemLoop].nSearches%>     </TD>
	 
</TR>

<%/section%>


</FORM>



<%include file="member.footer.php"%>
