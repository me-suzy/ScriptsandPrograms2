<%include file="header.php"%>
<BR>
<%include file="member.menu.php"%>
<BR>

<%include file="member.accountform.php"%>



<DIV ALIGN="CENTER">

<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" width="100%">
<TR><TD VALIGN=TOP>
        <H3 ALIGN=CENTER>
                View Past Transactions.               
        </H3>
</TD>
</TR>
</TABLE>

<FORM action="<%$selfURL%>" method="post">
<TABLE BORDER="1" CELLPADDING="0" CELLSPACING="0" WIDTH="100%" bgcolor="#cccccc">
<TR>
 <TD VALIGN=TOP align="center">
  Transaction Type:
  <select name='tr_Type' style="width:120;">
  <option value="all" <%if $type=="all"%>selected<%/if%>>all
  <option value='admin' <%if $type=='admin'%>selected<%/if%>>admin
  <option value='deposit' <%if $type=='deposit'%>selected<%/if%>>deposit
  </select><br><br>
 
	date:
	year:
	<SELECT name="year" STYLE="width:70">
     <%html_options values=$yearIDs output=$years selected=$year%>
    </SELECT>
	
	month:
	<SELECT name="month" STYLE="width:70">
     <%html_options values=$monthIDs output=$months selected=$month%>
    </SELECT>
    
    day:
	<SELECT name="day" STYLE="width:100">
     <option value=-1 SELECTED>current
     <%html_options values=$days output=$days selected=$day%>
    </SELECT>

  <br>
 
  <input type=hidden name=cmd value='query'>
  <input type=hidden name=mode value=members>
  <input type=hidden name=memberMode value=viewtrans >
  <input type=submit value=' View ' style="width:150;">
  <br>
  <%$msg%> 
  <br>
 
  
 </TD>
</TR>
</TABLE>
</form>

<TABLE ALIGN="CENTER" BORDER="1" CELLPADDING="5" CELLSPACING="0" WIDTH="100%" bgcolor="#cccccc" bordercolor="#999999">
<TR STYLE="font-size:13px; font-weight:bold;font-family:Verdana;background-color:'#ccffcc'">
<TD>Date</TD>
<TD>Amount</TD>
<TD>Transaction type</TD>
</TR>
<%section name=UserLoop loop=$data_tr%>
<tr bgcolor="<%if $smarty.section.UserLoop.index is even %>#c8c8c8<%else%>#dDdDdD<%/if%>">
	<td><%$data_tr[UserLoop]%></td>
	<td><%$ammount_tr[UserLoop]%></td>
	<td><%$type_tr[UserLoop]%></td>
</tr>
<%sectionelse%>
<tr bgcolor="#c8c8c8">
	<td colspan="3" align="center">no items</td>
</tr>
<%/section%>

</table>

</DIV>

<%include file="member.footer.php"%>