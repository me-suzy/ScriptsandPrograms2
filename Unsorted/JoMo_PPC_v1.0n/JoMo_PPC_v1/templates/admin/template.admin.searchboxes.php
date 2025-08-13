<%include file="admin/admin.header.php"%>

<BR>
<%include file="admin/admin.menu.php"%>
<BR>

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="" background="">
<TR ALIGN="left" bgcolor="" >
        <TD Align="center">
                <b>Search boxes for affiliates.</b>
        </TD>
</TR>
</TABLE>

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="10" CELLSPACING="1" WIDTH="" background="">
<TR ALIGN="left" bgcolor="" >
        <TD Align="left">
           
        </TD>
</TR>
</TABLE>

<script language="javascript">
	function viewBox(divID,areaID){
		//document.all[divID].innerHTML="<input type='text'>";
		document.all[divID].innerHTML=document.all[areaID].innerText;
		
	}

	function viewBoxWindow(divID,areaID){
		t = document.all[areaID].innerHTML;
		/*
		alert(t);
		t.replace("/\"/","'");		
		alert(t);
		*/
		previewf.htmlcode.value=t;
		previewf.submit();		
	}
	
</script>

<%include file="template.searchboxes.php"%>

<%include file="admin/admin.footer.php"%>