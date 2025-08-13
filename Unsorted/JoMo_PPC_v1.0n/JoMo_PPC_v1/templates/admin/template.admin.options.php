<%include file="admin/admin.header.php"%>

<BR>
<%include file="admin/admin.menu.php"%>
<BR>

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="100%" >
<TR><TD>

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="" background="">
<TR ALIGN="left" bgcolor="" >
        <TD Align="center">
                <a href="<%$selfURL%>?mode=options&optionCategory=all">all</a> | 
                <a href="<%$selfURL%>?mode=options&optionCategory=general">general</a> | 
                <a href="<%$selfURL%>?mode=options&optionCategory=search">search</a> | 
                <a href="<%$selfURL%>?mode=options&optionCategory=listing">listing</a> | 
                <a href="<%$selfURL%>?mode=options&optionCategory=account">account</a> | 
                <a href="<%$selfURL%>?mode=options&optionCategory=payment">payment</a> |
                <a href="<%$selfURL%>?mode=options&optionCategory=affiliate">affiliate</a> | 
        </TD>
</TR>
</TABLE>

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="" background="">
<TR ALIGN="left" bgcolor="" >
        <TD Align="center">
                <a name="top"><b>View and edit <%$optionCategory%> options.</b></a>
        </TD>
</TR>
</TABLE>


<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" background="" bgcolor="#eeeeee">
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="CENTER">
        <TD align="left" colspan="8">
			   <b><%$msg%></b>	
        </TD>
</tr>
</table>


<form name="f" ACTION="<%$selfURL%>" method="post">

<input type="hidden" name="mode" value="options">
<input type="hidden" name="optionCategory" value="<%$optionCategory%>">
<input type="hidden" name="cmd" value="">
<input type="hidden" name="option" value="">
<input type="hidden" name="optionValue" value="">
<input type="hidden" name="description" value="">

</FORM>

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="100%" background="">
<TR ALIGN="CENTER" bgcolor="lightgreen" >
        <TD Align="center" width="300"> option</TD>
        <TD Align="center"> value </TD>
        <TD Align="center">  command</TD>
</TR>

<%section name=OptionLoop loop=$options%>
<TR STYLE="font-size:14px; font-family:Verdana;" ALIGN="CENTER" bgcolor="<%if $smarty.section.OptionLoop.index is even %>#c8c8c8<%else%>#dDdDdD<%/if%>" valign="top">
        <TD align="left">
                <%$options[OptionLoop].description%>
        </TD>
        <TD align="center">
        	    <input type="text" name="optionValue<%$options[OptionLoop].optionName%>" value="<%$options[OptionLoop].value%>" style="width:200;">
        </TD>
        <TD align="center">
                <a href="#" onclick="
                	f.cmd.value='update';
                	f.option.value='<%$options[OptionLoop].optionName%>'; 
                	f.optionValue.value=optionValue<%$options[OptionLoop].optionName%>.value; 
                	f.submit();return false;">update</a>
        </TD>
</TR>
<%sectionelse%>
<TR STYLE="font-size:13px; font-family:Verdana;" ALIGN="left" " valign="top" bgcolor="#eeeeee">
	<td colspan="4">
		no options in this category
	</td>
</TR>
<%/section%>

<TR STYLE="font-size:13px; font-family:Verdana;" ALIGN="left" " valign="top">
	<td>
		<a href="#top">top</a>
	</td>
</TR>

</TABLE>

</TD>
</TR>
</TABLE>



<%include file="admin/admin.footer.php"%>

