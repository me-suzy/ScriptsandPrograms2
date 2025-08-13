<%include file="admin/admin.header.php"%>
<BR>
<%include file="admin/admin.menu.php"%>
<BR>

<DIV ALIGN="CENTER">

<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" width="100%">
<TR><TD VALIGN=TOP>
        <H3 ALIGN=CENTER>
                <%if $cmd eq 'create'%>                Create new link.
                <%else%>        Edit link.
                <%/if%>
        </H3>
</TD>
</TR>
</TABLE>


<script>
 function onFormSubmit(){
	if (linkForm.all["result[title]"].value==""){
		alert("Please specify title.");
		return false;
	}
	if (linkForm.all["result[keywordName]"].value==""){
		alert("Please specify keyword.");
		return false;
	}
		
    linkForm.all["result[adminStatus]"].value = linkForm.all["adminStatus"].checked?1:0;
    linkForm.all["result[status]"].value = linkForm.all["status"].checked?1:0;
    
    return true;
 }

</script>

<script>
 function openKeywordBox(){
 	window.open('keywordbox.php?memberID=<%$memberID%>&keyword=<%$link.keywordName%>&listingType=link', 'keyword', 'width=300,height=310px,location=no,resizable=yes,directories=no,menubar=no,scrollbars=no,status=yes,titlebar=no,toolbar=no'); 
 }
</script>


<form name="linkForm" action="<%$selfURL%>" method="post" onsubmit="return onFormSubmit();">

    <input type="hidden" name = "mode" value="link">
        
	<%include file="member.linkform.php"%>

</form>

</DIV>

<%include file="admin/admin.footer.php"%>