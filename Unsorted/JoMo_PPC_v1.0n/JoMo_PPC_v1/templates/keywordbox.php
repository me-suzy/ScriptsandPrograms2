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
                some help.<br>
                view bids placed on keywords by other members				
        </TD>
</tr>

<TR ALIGN="CENTER" bgcolor="lightgreen">
		<TD Align="center" width="20">#</TD>
		<TD Align="center" width="100">keyword</TD>
        <TD Align="center" width="50">bid</TD>
</TR>

<%section name=ItemLoop loop=$items%>

<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="CENTER"
bgcolor="<%if $smarty.section.ItemLoop.index is even %>#c8c8c8<%else%>#dDdDdD<%/if%>"
>
	 <TD align="center">
             <%$items[ItemLoop].index%>
     </TD>
     <TD align="left">
             <a href="#"><%$items[ItemLoop].keywordName%></a>
     </TD>
     <TD align="left">
     	<%$items[ItemLoop].bid%>
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
