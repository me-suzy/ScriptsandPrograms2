<%include file="header.php"%>

<div height="20"> &nbsp</div>

<%include file="affiliate.menu.php"%>

<DIV ALIGN="CENTER">

<table border="0" align="center" cellpadding="5" cellspacing="0" bgcolor="">
 <tr valign="top">
  <td align="left">
        Welcome <%$member.firstName%> <%$member.lastName%>!<br>
        <%$msg%>
  </td>
 </tr>
</TABLE>

<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" >
<TR><TD VALIGN=TOP>
        <H3 ALIGN=CENTER>
                Affiliate account.                
        </H3>
</TD>
</TR>
</TABLE>



<script>
 function onFormSubmit(){
          return true;
 }
</script>

<form name="accountForm" action="<%$selfURL%>" method="post" onsubmit="return onFormSubmit();">

        <input type="hidden" name = "mode" value="affiliates">
        <input type="hidden" name = "affMode" value="account">

                <input type="hidden" name = "cmd" value="deposit">
        <input type="hidden" name = "affiliateID" value="<%$member.affiliateID%>">


<table border="0" align="center" cellpadding="5" cellspacing="0" bgcolor="#E0E0E0">
<tr valign="top">
  <td align="left">
        <b>Account info:</b><br>
        account is <%if $account.isActive == 1%>active<%else%>NOT active.<%/if%> <br>
        Current Balance: $<%$account.balance%>  <br>
		If you have an advertisers account, you can deposit the money to that account (press button "transfer"). 
		<br>Or you can send a request to withdraw the money (press button "request").<br>
        To request or transfer money you must have at least $<%$minAffBalance%> on your account.
  </td>
</tr>
<%if $account.balance >= $minAffBalance%>
<tr valign="top">
  <td align="center">            
        <input type="button" name="request" value="request" 
            onclick="location.href='<%$selfURL%>?mode=affiliates&affiliateID=<%$affiliateID%>&affMode=request&cmd='; ">
        <input type="button" name="transfer" value="transfer"
            onclick="location.href='<%$selfURL%>?mode=affiliates&affiliateID=<%$affiliateID%>&affMode=transfer&cmd='; ">
  </td>
</tr>
<%/if%>

<tr valign="top">
  <td align="left">
        <a href="<%$selfURL%>?mode=affiliates&affMode=clickstats">View statistics</a>
  </td>
</tr>

</table>


</form>

</DIV>

<%include file="affiliate.footer.php"%>