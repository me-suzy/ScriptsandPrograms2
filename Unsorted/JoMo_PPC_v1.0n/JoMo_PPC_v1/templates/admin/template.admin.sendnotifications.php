<%include file="admin/admin.header.php"%>

<BR>
<%include file="admin/admin.menu.php"%>
<BR>

<form name="f" ACTION="<%$selfURL%>" method="post">
	<input type="hidden" name="mode" value="sendnotifications">
	<input type="hidden" name="cmd" value="sendmail">
	
	
<TABLE ALIGN="CENTER" BORDER="1" CELLPADDING="5" CELLSPACING="1" bgcolor=#cccccc bordercolor=#999999>
<tr>
		<td colspan=2 align=center><p style="color: #ff0000"><%$msg%></p></td>
</tr>
<TR>
        <td>Email All</td>
        <TD> 
			   <SELECT name=recipient>
			   	<OPTION value=0 <%if $recipient == 0%>selected<%/if%>> members and affiliates
			   	<OPTION value=1 <%if $recipient == 1%>selected<%/if%>> members
			   	<OPTION value=2 <%if $recipient == 2%>selected<%/if%>> affiliates
			   </SELECT>
		</TD>
</tr>
<TR>
		<td>subject:</td>
        <TD><input type=text name="result[subject]" value="<%$subject%>" ></TD>
</tr>
<TR>
		<td>from:</td>
        <TD>name:<input type=text name="result[fromName]" value="<%$fromName%>" ><br>
        email:<input type=text name="result[fromEmail]" value="<%$fromEmail%>" >
        </TD>
</tr>
<TR>
		<td colspan=2 align=center>
		Body of Your Message:<br>
        <textarea name=message cols=30 rows=4><%$message%></textarea>
        </TD>
</tr>
<tr>
	<td align=center colspan=2>
	
	<input type="submit" value="email">
	<input type="button" value="cancel" onclick="f.cmd.value='cancel'; f.submit(); return true;">
	</td>
</tr>
</table>
</form>


<%include file="admin/admin.footer.php"%>
