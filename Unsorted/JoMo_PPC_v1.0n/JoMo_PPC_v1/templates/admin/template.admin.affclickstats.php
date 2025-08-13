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

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="" background="">

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="" background="">
<TR ALIGN="left" bgcolor="" >
        <TD Align="center">
                <b>Click statistics for affiliates.</b>
        </TD>
</TR>
</TABLE>


<form name="f" ACTION="<%$selfURL%>" method="post">

<input type="hidden" name="mode" value="affclickstats">
<input type="hidden" name="cmd" value="">

<TABLE ALIGN="CENTER" BORDER="1" CELLPADDING="5" CELLSPACING="1" width="100%" bgcolor="#cccccc" bordercolor="#999999">
<TR ALIGN="left" >
        <TD Align="left">
                <b>filter:</b><br>
                   affiliate:
            <SELECT name="affiliateID" STYLE="width:300">
            	<OPTION value="0">all affiliates
             	<%html_options values=$affiliateIDs output=$affiliateNames selected=$affiliateID%>
            </SELECT>
			<br>
			
			date:
			year:
			<SELECT name="month" STYLE="width:100">
             <%html_options values=$yearIDs output=$years selected=$year%>
            </SELECT>

			month:
			<SELECT name="month" STYLE="width:100">
             <%html_options values=$monthIDs output=$months selected=$month%>
            </SELECT>
            
            day:
			<SELECT name="day" STYLE="width:100">
             <option value=-1 SELECTED>current day
             <%html_options values=$days output=$days selected=$day%>
             
            </SELECT>
			
            <input type="submit" value="apply" name="submitSetfilter">
        </TD>

</TR>
</TABLE>

<TABLE ALIGN="CENTER" BORDER="1" CELLPADDING="5" CELLSPACING="1" width="100%" bgcolor="#cccccc" bordercolor="#999999">
<TR ALIGN="left" >
        <TD Align="left">
	        <b>group by:</b>
            <SELECT name="groupby" STYLE="width:150" onchange="f.submit();">
             <OPTION value="date" <%if $groupby == "date"%>SELECTED<%/if%>>date
             <OPTION value="link" <%if $groupby == "link"%>SELECTED<%/if%>>affiliate
            </SELECT>
            <br>
			show details:
			date:			
			<span style="visibility:;">
            <SELECT name="detailsDate" STYLE="width:150">
            	<OPTION value="0" <%if $detailsDate == 0%>SELECTED<%/if%>>totals
				<OPTION value="1" <%if $detailsDate == 1%>SELECTED<%/if%>>year
            	<OPTION value="2" <%if $detailsDate == 2%>SELECTED<%/if%>>month
            	<OPTION value="3" <%if $detailsDate == 3%>SELECTED<%/if%>>month, day
            </SELECT>
			</span>
			
			link: 
			<span style="visibility:;">
            <SELECT name="detailsLink" STYLE="width:150">
            	<OPTION value="0" <%if $detailsLink == 0%>SELECTED<%/if%>>totals
            	<OPTION value="1" <%if $detailsLink == 1%>SELECTED<%/if%>>affiliate
				<!--
            	<OPTION value="3" <%if $detailsLink == 3%>SELECTED<%/if%>>URL, link, keyword
				-->
            </SELECT>
			</span>
			
            <input type="submit" value="apply" name="submitSetfilter">
        </TD>

</TR>
</TABLE>

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" width="100%" background="">
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="CENTER" bgcolor="#eeeeee">
        <TD align="left" colspan="8">
                some help.<br>
				click on link to edit it.<br>
        </TD>
</tr>

<TR ALIGN="CENTER" bgcolor="lightgreen">
<%section name=Column loop=$columns%>
        <TD Align="center" ><%$columns[Column]%></TD>
<%/section%>        
<!--
		<TD Align="center" width="20">url</TD>
        <TD Align="center" width="200">link</TD>
        <TD Align="center" width="200">Keyword</TD>
        <TD Align="center">date</TD>
-->        
        <TD Align="center">#clicks</TD>
        <TD Align="center">$ cost (<%$affpercent%>%)</TD>
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
     
     
     <TD align="center">		<%$items[ItemLoop].logCount%>     </TD>
     <TD align="center">		<%$items[ItemLoop].cost%>     </TD>
	 
</TR>

<%/section%>


</FORM>



<%include file="member.footer.php"%>
