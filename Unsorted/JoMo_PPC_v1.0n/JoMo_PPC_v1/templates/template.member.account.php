<%include file="header.php"%>
<BR>
<%include file="member.menu.php"%>
<BR>

<DIV ALIGN="CENTER">

<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" width="350">
<TR><TD VALIGN=TOP>
        <H3 ALIGN=CENTER>
                Member Account Management                
        </H3>
</TD>
</TR><tr><td><center>This is where deposits are made.  We offer Paypal and Credit Cards as payment options.</td></tr>
</TABLE>

<%if $cmd == "edit" %>
<FORM action="<%$selfURL%>" method="post">
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" width="100%">
<TR>
 <TD VALIGN=TOP bgcolor="" align="center">
  <UL>
      <LI><a href="#"><b>view statistics.</b></a>
  </UL>
 </TD>
</TR>
</TABLE>

</form>

<%/if%>


<script>
 function onFormSubmit(){
          //linkForm.all["result[status]"].value = linkForm.all["status"].checked?1:0;
          return true;
 }
</script>


<%include file="member.accountform.php"%>


</DIV>

<%include file="member.footer.php"%>