<%include file="header.php"%>
<BR>
<%include file="member.menu.php"%>
<BR>

<DIV ALIGN="CENTER">

<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" width="100%">
<TR><TD VALIGN=TOP>
        <H3 ALIGN=CENTER>
                <%if $cmd eq 'create'%>                Create new url.
                <%else%>        Edit url.
                <%/if%>
        </H3>
</TD>
</TR>
</TABLE>

<%if $cmd == "edit" %>
<FORM action="<%$selfURL%>" method="post">
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" width="100%">
<TR>
 <TD VALIGN=TOP bgcolor="" align="center">
  <UL>
      <LI><a href="<%$selfURL%>?memberMode=links&urlID=<%$url.urlID%>&cmd="><b>view links of the url.</b></a>
      <LI><a href="<%$selfURL%>?memberMode=link&urlID=<%$url.urlID%>&cmd=create"><b>add link to the url.</b></a>
  </UL>
 </TD>
</TR>
</TABLE>

</form>

<%/if%>



<form action="<%$selfURL%>" method="post" >
        <input type="hidden" name = "mode" value="members">
        <input type="hidden" name = "memberMode" value="url">


<%include file="member.urlform.php"%>

</form>

</DIV>

<%include file="member.footer.php"%>