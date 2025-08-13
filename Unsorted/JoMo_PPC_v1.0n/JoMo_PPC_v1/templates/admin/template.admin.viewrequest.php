<%include file="admin/admin.header.php"%>

<BR>
<%include file="admin/admin.menu.php"%>
<BR>

<form name="f" ACTION="<%$selfURL%>" method="post" >

<input type="hidden" name="mode" value="<%$mode%>">
<input type="hidden" name="cmd" value="">

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="" background="">
<TR ALIGN="left" bgcolor="" >
        <TD Align="center">
                <b>Request of affiliate.</b>
        </TD>
</TR>
</TABLE>

<table border="0" align="center" cellpadding="5" cellspacing="0" bgcolor="#E0E0E0">
<tr valign="top">
  <td align="right">
    affiliate:    
  </td>
  <td align="left">
    <%$info.firstName%> <%$info.lastName%>
  </td>
</tr>

<tr valign="top">
  <td align="right">
    date:    
  </td>
  <td align="left">
    <%$request.lastRequestDate%>
  </td>
</tr>

<tr valign="top">
  <td align="right">
    payment:    
  </td>
  <td align="left">
    <%$request.paymentType%>
  </td>
</tr>

<tr valign="top">
  <td align="right">
    additional information<br> and comments:    
  </td>
  <td align="left">
    <textarea name="comments" style="width:250;"><%$request.comments%></textarea>
  </td>
  
</tr>

<tr valign="top">
  <td align="center" colspan="2">
        <input type="button" name="back" value="back" onclick="location.href='<%$selfURL%>?mode=accounts&accountType=affiliate';">
  </td>
</tr>

</table>

</FORM>

<%include file="admin/admin.footer.php"%>