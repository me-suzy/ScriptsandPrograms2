<%include file="admin/admin.header.php"%>
<BR>
<%include file="admin/admin.menu.php"%>
<BR>

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


<form name="urlForm" action="<%$selfURL%>" method="post" >
   <input type="hidden" name = "mode" value="url">

    <%include file="member.urlform.php"%>

</form>

<%include file="admin/admin.footer.php"%>