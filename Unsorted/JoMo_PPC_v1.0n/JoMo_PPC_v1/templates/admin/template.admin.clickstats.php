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
<TR ALIGN="left" bgcolor="" >
        <TD Align="center">
                <b>Members' click statistics.</b>
        </TD>
</TR>
</TABLE>



<form name="f" ACTION="<%$selfURL%>" method="post">

<input type="hidden" name="memberMode" value="stats">
<input type="hidden" name="linkID" value="">
<input type="hidden" name="cmd" value="">

<TABLE ALIGN="CENTER" BORDER="1" CELLPADDING="5" CELLSPACING="1" width="100%" bgcolor="#cccccc" bordercolor="#999999">
<TR ALIGN="left" >
        <TD Align="center">
			view statistics for:
            <SELECT name="logType" STYLE="width:120" onchange="f.submit();">
			<!--
             <OPTION value="all" <%if $logType == "all"%>SELECTED<%/if%> >banners and links
			 -->
             <OPTION value="link" <%if $logType == "link"%>SELECTED<%/if%> >links
             <OPTION value="banner" <%if $logType == "banner"%>SELECTED<%/if%> >banners
         
           </SELECT>
        </TD>
</TR>

<TR ALIGN="left" >
        <TD Align="left">
                <b>filter:</b>
                member:
            <SELECT name="memberID" STYLE="width:100">
            	<OPTION value="0">all members
             	<%html_options values=$memberIDs output=$memberNames selected=$memberID%>
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
             <option value=-1 SELECTED>current
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
             <OPTION value="link" <%if $groupby == "link"%>SELECTED<%/if%>>link
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
            	<OPTION value="1" <%if $detailsLink == 1%>SELECTED<%/if%>>member
            	<OPTION value="2" <%if $detailsLink == 2%>SELECTED<%/if%>>member,URL
            	<OPTION value="3" <%if $detailsLink == 3%>SELECTED<%/if%>>member,URL, link
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
                place help here.<br>
        </TD>
</tr>

<TR ALIGN="CENTER" bgcolor="lightgreen">
<%section name=Column loop=$columns%>
        <TD Align="center" ><%$columns[Column]%></TD>
<%/section%>        
        <TD Align="center">#clicks</TD>
        <TD Align="center">$ cost</TD>
        <TD Align="center">top<br>position</TD>
        <TD Align="center">avg<br>position</TD>		

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
     <TD align="center">		<%$items[ItemLoop].maxpos%>     </TD>
     <TD align="center">		<%$items[ItemLoop].avgpos%>     </TD>

	 
</TR>

<%/section%>


</FORM>

<%include file="admin/admin.footer.php"%>