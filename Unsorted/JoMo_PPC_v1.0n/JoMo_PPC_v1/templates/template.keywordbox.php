<form name="f" ACTION="<%$selfURL%>" method="post">

<input type="hidden" name="memberMode" value="bidstats">
<input type="hidden" name="memberID" value="<%$memberID%>">
<input type="hidden" name="cmd" value="">

<TABLE ALIGN="CENTER" BORDER="1" CELLPADDING="5" CELLSPACING="1" WIDTH="" bgcolor="#cccccc" bordercolor="#999999">
<TR ALIGN="left" >
        <TD Align="left">
			keyword:
            <INPUT name="keyword" value="<%$keyword%>" STYLE="width:200"><br>
            show bids for: 
            <SELECT name="listingType">
                <option value="link" <%if $listingType == "link"%>SELECTED<%/if%>>links
                <option value="banner" <%if $listingType == "banner"%>SELECTED<%/if%>>banners
            </SELECT>
            <br>
            select top <INPUT name="top" value="<%$top%>" STYLE="width:50"> items
            <br>
            <input type="submit" value="apply" >
        </TD>
</TR>
</TABLE>


<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="" background="">
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="CENTER" bgcolor="#eeeeee">
        <TD align="left" colspan="8">
                some help.<br>
                view top bids placed on keyword by other members				
        </TD>
</tr>

<TR ALIGN="CENTER" bgcolor="lightgreen">
		<TD Align="center" width="20">#</TD>
		<TD Align="center" width="100">keyword</TD>
		<TD Align="center" width="">type</TD>
        <TD Align="center" width="50">bid</TD>
<%if $loginMode == "admin"%>
        <TD Align="center" width="200">member</TD>        
<%/if%>
</TR>

<%section name=ItemLoop loop=$items%>

<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="CENTER"
bgcolor="<%if $smarty.section.ItemLoop.index is even %>#c8c8c8<%else%>#dDdDdD<%/if%>"
>
	 <TD align="center">
             <%$items[ItemLoop].index%>
     </TD>
     <TD align="left" >
             <%$items[ItemLoop].keywordName%>
     </TD>
     <TD align="left">
             <%$items[ItemLoop].listingType%>
     </TD>
     <TD align="left">
     	<%$items[ItemLoop].bid%>
     </TD>                 
<%if $loginMode == "admin"%>
      <TD Align="center" width="200"><%$items[ItemLoop].membername%></TD>        
<%/if%>     
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
