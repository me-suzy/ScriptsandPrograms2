<%include file="header.php"%>

<BR>
<%include file="member.menu.php"%>
<BR>

<form name="f" ACTION="<%$selfURL%>" method="post" enctype="multipart/form-data">

<input type="hidden" name="mode" value="members">
<input type="hidden" name="memberMode" value="bulksubm">
<input type="hidden" name="cmd" value="uplkeywords">
<input type="hidden" name="memberID" value="<%$memberID%>">

<TABLE ALIGN="CENTER" BORDER="1" CELLPADDING="5" CELLSPACING="1" WIDTH="100%" bgcolor="#cccccc" bordercolor="#999999">
<tr>
	<td colspan=2 align=center><b><%$msg%></b></td>
</tr>
<TR>
    <TD> <b>Url:</b></td>
    <td>
            <SELECT name="result[urlID]" STYLE="width:300;">
             	<%html_options values=$urlIDs output=$urlNames selected=$result.urlID%>
            </SELECT><br>
	</td>
</tr>
<TR>
    <TD> <b>Select File:</b></td>
    <td>
            <input type=file name="filename" value="">
	</td>
</tr>
<tr>
	<td><b>Delimiter:</b><br>(in your file)</td>
	<td>
	    <SELECT name="result[delimiter]" STYLE="width:150">
	    <%section name=UserLoop loop=$delimiters%>
	    	
			<option value=<%$delimiters[UserLoop].id%> <%if $result.delimiter == $delimiters[UserLoop].id%>selected<%/if%>  ><%$delimiters[UserLoop].name%>
		<%/section%>            
	    </SELECT>
    </TD>

</TR>
<tr>
	<td><b>Title <br>(for all these keywords):</b></td>
	<td>
	    <input type="text" name="result[title]" value="<%$result.title%>">
    </TD>

</TR>
<tr>
	<td><b>Bid:</b></td>
	<td>
        <input type="text" name="result[bid]" value="<%$result.bid%>" >
    </TD>

</TR>
<tr>
	<td colspan=2 align="center">
		<input type="submit" value="upload">
		<input type="button" value="cancel" onclick="document.forms['f'].cmd.value='cancel'; f.submit();">
	</td>
</tr>
</TABLE>

</FORM>

<%include file="member.footer.php"%>
