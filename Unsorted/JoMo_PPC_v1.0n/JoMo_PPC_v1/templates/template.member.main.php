<%include file="header.php"%>

<div height="20"> &nbsp</div>

<%include file="member.menu.php"%>

<TABLE WIDTH="" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#ffffff" background="">
        <TR>
          <TD ALIGN="CENTER" CLASS="clsText1"><H1><br>Welcome to the Member Area</H1></TD>
        </TR>
        
</TABLE>

<TABLE WIDTH="" ALIGN="CENTER" BORDER="0" CELLSPACING="0" CELLPADDING="1" BGCOLOR="#F3F3F3" background="">
        <TR>
          <TD ALIGN="CENTER" CLASS="clsText1"><h2><u>Member Information:</u></h2></TD>
        </TR>
		<TR>
          <TD ALIGN="left" CLASS="clsText1"><b>Name: <%$memberInfo.firstName%></TD>
        </TR>
        <TR>
          <TD ALIGN="left" CLASS="clsText1"><b>Account is <%if $memberAccount.isActive == 1%><font color=green>active</font><%else%><font color=red>not active</font><%/if%></font></TD>
        </TR>
		<TR>
          <TD ALIGN="left" CLASS="clsText1"><b>Account Balance: <font color=red>$<%$memberAccount.balance%></font></b></TD>
        </TR>
        
</TABLE>

<br>
<TABLE WIDTH="250" ALIGN="CENTER" BORDER="1" CELLSPACING="0" CELLPADDING="3" BGCOLOR="#FFFF99" background="" bordercolor="#FFCC00">
        <TR>
          <TD ALIGN="CENTER" ><font size="4">Member Administration:</font></TD>
        </TR>
		<TR>
          <TD ALIGN="left" >
          	<UL>
			   <LI><a href="<%$selfURL%>?mode=members&memberMode=urls">URL Management</a>
			   <LI><a href="<%$selfURL%>?mode=members&memberMode=links">Link Management</a>
			   <LI><a href="<%$selfURL%>?mode=members&memberMode=banners">Banner Management</a>
			   <LI><a href="<%$selfURL%>?mode=members&memberMode=info">Personal Info Management</a>  
			   <LI><a href="<%$selfURL%>?mode=members&memberMode=account">Add Funds & View Transactions</a>
			   <LI><a href="<%$selfURL%>?mode=members&memberMode=stats">Veiw Statistics</a>
			</UL>
			</TD>
        </TR>
        
</TABLE>


<%include file="member.footer.php"%>