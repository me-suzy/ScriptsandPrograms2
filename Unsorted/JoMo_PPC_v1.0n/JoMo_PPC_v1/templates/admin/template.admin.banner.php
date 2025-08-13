<%include file="admin/admin.header.php"%>
<BR>
<%include file="admin/admin.menu.php"%>
<BR>

<DIV ALIGN="CENTER">

<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" width="100%">
<TR>
<TD VALIGN=TOP align="center">
	<h3>
                <%if $cmd eq 'edit'%> Edit banner.
                <%else%>        Create banner.
                <%/if%>
    </h3>        
	<div style="color:red"><%$msg%></div>
</TD>
</TR>
</TABLE>


<script>
 function onFormSubmit(){
          bannerForm.all["result[adminStatus]"].value = bannerForm.all["adminStatus"].checked?1:0;
          bannerForm.all["result[isCatchAll]"].value = bannerForm.all["isCatchAll"].checked?1:0;
		  bannerForm.all["result[isPerImpression]"].value = bannerForm.all["isPerImpression"].checked?1:0;
		  
          return true;
 }

</script>

<form name="bannerForm" action="<%$selfURL%>" method="post" onsubmit="return onFormSubmit();" ENCTYPE="multipart/form-data">

    <input type="hidden" name = "mode" value="banner">
        
	<%include file="member.bannerform.php"%>

</form>

</DIV>

<%include file="admin/admin.footer.php"%>