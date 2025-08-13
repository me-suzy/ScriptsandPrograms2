<%include file="admin/admin.header.php"%>

<BR>
<%include file="admin/admin.menu.php"%>
<BR>

<script language="javascript">
	function order(col){
			document.forms['f'].orderby.value=col;
			document.forms['f'].orderdir.value = document.forms['f'].orderdir.value=="DESC"?"ASC":"DESC";			
			document.forms['f'].submit();
			//alert(document.forms['f'].submit);
			//foreach
			//return false;
			//return void(0);
	}
</script>

<form name="f" ACTION="<%$selfURL%>" method="post" >

<input type="hidden" name="mode" value="<%$mode%>">
<input type="hidden" name="cmd" value="">
<input type="hidden" name="itemID" value="0">
<input type="hidden" name="accountID" value="0">
<input type="hidden" name="page" value="">
<input type="hidden" name="balanceValue" value="0">
<input type="hidden" name="orderby" value="<%$orderby%>">
<input type="hidden" name="orderdir" value="<%$orderdir%>">

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="" background="">
<TR ALIGN="left" bgcolor="" >
        <TD Align="center">
                <b>Accounts.</b>
        </TD>
</TR>
</TABLE>

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="" background="">
<TR ALIGN="left" bgcolor="" >
        <TD Align="center">
                view 
                <SELECT name="accountType" onchange="document.forms['f'].submit();" style="width:200;">
                	<OPTION value="member" <%if $accountType == "member"%>SELECTED<%/if%>> member accounts
                	<OPTION value="affiliate" <%if $accountType == "affiliate"%>SELECTED<%/if%>> affiliate accounts
                </SELECT>
        </TD>
</TR>
</TABLE>

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="10" CELLSPACING="1" WIDTH="" background="">
<TR ALIGN="left" bgcolor="" >
        <TD Align="left">
           <a href="<%$selfURL%>?mode=options&optionCategory=account"><b>View and edit accounts options</b></a>
        </TD>
</TR>
</TABLE>


<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="100%" background="" bgcolor="#eeeeee">
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="CENTER">
        <TD align="left" colspan="8">
			   <b><%$msg%></b>	
        </TD>
</tr>
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="CENTER">
        <TD align="left" colspan="8">
				account is active if it is enough money on it, otherwise it is frozen until advertiser increases his balance.<br>
				to activate/deactivate links goto <a href="<%$selfURL%>?mode=links">links page</a>.<br>
				click on transactions to view past transactions of the advertiser<br>
				click on column heading to sort items<br>
				put value>0 (i.e. 30) or value<0 (ie. -10) and click update to update account's balance.<br>
        </TD>
</tr>

</table>

<!--                                                            
<%if $accountType == "affiliate"%>
<table width="100%" align="center" cellpadding="3">
 <tr>
  <td align="left" bgcolor="#cccccc">
    <input type="button" value="show affiliates that request money">
  </td>
 </tr>
</table>
<%/if%>
-->

<table width="100%" align="center" cellpadding="3">
 <tr>
  <td align="left" bgcolor="#cccccc">
   <%$nItems%> accounts. 
  </td>
  <td align="right" bgcolor="#cccccc" width="200">
   pages: 
   <%if $prev ne 0%> <a href="#" onclick="document.forms['f'].page.value=<%$prev%>; document.forms['f'].submit(); return false;"><<</a>   <%/if%>
   
   <%section name=Page loop=$pages%>
   	<a href="#" onclick="document.forms['f'].page.value=<%$pages[Page]%>; document.forms['f'].submit(); return false;"
		style="color:<%if $pages[Page] == $page%>red<%else%>black<%/if%>;"><%$pages[Page]%></a> | 
   <%sectionelse%>
     no pages
   <%/section%>
   
   <%if $next ne 0%> <a href="#" onclick="document.forms['f'].page.value=<%$next%>; document.forms['f'].submit(); return false;">>></a>   <%/if%>

  </td>
 </tr>
</table>


<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="100%" background="" bgcolor="#eeeeee">
<TR ALIGN="CENTER" bgcolor="lightgreen" >
        <TD Align="center">                status        </TD>
        <TD Align="center" width="100"> <a href="#" onclick="order('firstName ');return false;">first name</a></TD>
        <TD Align="center" width="100"> <a href="#" onclick="order('lastName ');return false;">last name</a></TD>
        <TD Align="center" width="100">   email        </TD>
        <TD Align="center" colspan="2"> <a href="#" onclick="order('balance');return false;">balance</a> (min=$<%$minBalance%>)</TD>
        <%if $accountType == "affiliate"%>        
        <TD Align="center" colspan="1"> request</TD>
        <%/if%>        
        <TD Align="center" > Commands        </TD>

</TR>
<%section name=ItemLoop loop=$items%>
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="CENTER"
bgcolor="<%if $smarty.section.ItemLoop.index is even %>#c8c8c8<%else%>#dDdDdD<%/if%>"
>
        <TD align="center">
        	    <%$items[ItemLoop].activity%>
        </TD>
        <TD align="left">
                <a href="<%$selfURL%>?mode=accountinfo&itemID=<%$items[ItemLoop].itemID%>&accountType=<%$items[ItemLoop].accountType%>&cmd="><%$items[ItemLoop].firstName%> </a>
        </TD>        
		<TD align="left">
                <a href="<%$selfURL%>?mode=accountinfo&itemID=<%$items[ItemLoop].itemID%>&accountType=<%$items[ItemLoop].accountType%>&cmd="><%$items[ItemLoop].lastName%> </a>
        </TD>                
		<TD align="left">
                <a href="mailto:<%$items[ItemLoop].email%>"><%$items[ItemLoop].email%></a>
        </TD>                
        <TD align="center">
                <%$items[ItemLoop].balance%>
        </TD>
        <TD align="center">
        
                +/-&nbsp <input type="text" name="balanceValue<%$items[ItemLoop].accountID%>" value="0" style="width:40; height:20;font-size:13px;">
                <a href="#" onclick="
                document.forms['f'].balanceValue.value=document.forms['f'].balanceValue<%$items[ItemLoop].accountID%>.value;
                	document.forms['f'].accountID.value=<%$items[ItemLoop].accountID%>;
                	document.forms['f'].cmd.value='balance';
                	document.forms['f'].submit();
                	return false;" >update</a>
        </TD>
 
        <%if $accountType == "affiliate"%>  
            
        <TD align="center">
            <%if $items[ItemLoop].isRequest == 1%>                
                <%$items[ItemLoop].lastRequestDate%>
            <%else%>
                ----
            <%/if%>                                 
        </TD>
        <%/if%>        

        <TD Align="center" nowrap>
        <!--
                <A HREF="#"
                        onclick="document.forms['f'].cmd.value=<%if $items[ItemLoop].isActive == 1%>'deactivate'<%else%>'activate'<%/if%>; 
							document.forms['f'].itemID.value=<%$items[ItemLoop].accountID%>;
							document.forms['f'].submit();">
						<%if $items[ItemLoop].isActive == 1%>froze<%else%>activate<%/if%>
						</A> |
						-->               
                <%if $accountType == "affiliate"%>  
                    <%if $items[ItemLoop].isRequest == 1%>
                <A HREF="<%$selfURL%>?mode=accounts&cmd=delrequest&memberID=<%$items[ItemLoop].accountID%>&accountType=<%$items[ItemLoop].accountType%>" 
                	>del request</A> |                          
                <A HREF="<%$selfURL%>?mode=viewrequest&affiliateID=<%$items[ItemLoop].accountID%>&accountType=<%$items[ItemLoop].accountType%>" 
                	>view request</A> |             
                    <%/if%>
                <%/if%>
                
                <A HREF="<%$selfURL%>?mode=viewtrans&memberID=<%$items[ItemLoop].accountID%>&accountType=<%$items[ItemLoop].accountType%>" 
                	>transactions</A> |
				<A HREF="<%$selfURL%>?mode=accountinfo&itemID=<%$items[ItemLoop].itemID%>&accountType=<%$items[ItemLoop].accountType%>&cmd=">info</A> |
							
        </TD>
</TR>

<%/section%>

</TABLE>

</FORM>




<%include file="admin/admin.footer.php"%>