<%include file="header.php"%>

<div height="20"> &nbsp</div>

<%include file="affiliate.menu.php"%>


<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="" background="">
<TR ALIGN="left" bgcolor="" >
        <TD Align="center">
                <b>Search boxes.</b>
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
		//document.all[divID].innerHTML=document.all[areaID].innerHTML;
		
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


<%include file="affiliate.footer.php"%>