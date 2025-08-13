<%include file="header.php"%>

<div height="20"> &nbsp</div>

<%include file="affiliate.menu.php"%>

<DIV ALIGN="CENTER">

<table border="0" align="center" cellpadding="5" cellspacing="0" bgcolor="">
 <tr valign="top">
  <td align="center">
        <%$member.firstName%> <%$member.lastName%>.<br>
        Transfer money to advertiser's account.<br>
        Please specify login and password to this account.<br>
        balance: $<%$account.balance%>.<br>
  </td>
 </tr>
</TABLE>

<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" >
<TR><TD VALIGN=TOP>
        <H3 ALIGN=CENTER>Transfer money.</H3>
        <%$msg%>
</TD>
</TR>
</TABLE>



<script>
 function onFormSubmit(){
          return true;
 }
</script>

<form name="f" action="<%$selfURL%>" method="post" onsubmit="return onFormSubmit();">

        <input type="hidden" name = "mode" value="affiliates">
        <input type="hidden" name = "affMode" value="transfer">
        <input type="hidden" name = "cmd" value="transfer">
        <input type="hidden" name = "affiliateID" value="<%$member.affiliateID%>">


<table border="0" align="center" cellpadding="5" cellspacing="0" bgcolor="#E0E0E0">
<tr valign="top" bgcolor="#999999">
  <td align="right">
    login:    
  </td>
  <td align="left">
    <input type="text" name="login" value="">
  </td>
</tr>
<tr valign="top" bgcolor="#999999">
  <td align="right">
    password:
  </td>
  <td align="left">
    <input type="password" name="password" value="">
  </td>
</tr>
<tr valign="top">
  <td align="right">
    amount:
  </td>
  <td align="left">
    <input type="text" name="amount" value="">
  </td>
</tr>


<tr valign="top">
  <td align="center" colspan="2">
        <input type="button" name="transfer" value="transfer"
            onclick="document.forms['f'].submit();">
        <input type="button" name="back" value="back" onclick="location.href='<%$selfURL%>?mode=affiliates&affMode=account&cmd=';">
  </td>
</tr>

</table>


</form>

</DIV>

<%include file="affiliate.footer.php"%>