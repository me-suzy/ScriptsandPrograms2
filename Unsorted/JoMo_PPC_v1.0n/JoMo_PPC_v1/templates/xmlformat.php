<form name="f" ACTION="<%$selfURL%>" method="post" target="_blank" 
onsubmit="return false;">

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="100%" background="" bgcolor="">
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="CENTER">
        <TD align="center" colspan="8">
			   <b><%$msg%></b>	
        </TD>
</tr>
</table>


<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="100%" background="" bgcolor="#eeeeee">
<TR STYLE="font-size:14px; font-family:Verdana;" ALIGN="CENTER" bgcolor="">	
        <TD align="center">
                <b>Use the following URL to obtain search results in XML format:</b><br>
    			<input type="text" name="url" style="width:600;" value="<%$xmlurl%>"><br>
    			parameters:
    			<TABLE ALIGN="CENTER" BORDER="1" CELLPADDING="5" CELLSPACING="0" WIDTH="500" background="" bgcolor="#eeeeee" bordercolor="#999999">
    				<tr bgcolor="#cccccc">
    					<td width="150">parameter<td width="300">description
    				</tr>
    				<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="left">
    					<td><b>str</b>
    					<td>searching string
    				</tr>
    				<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="left">
    					<td><b>page</b>
    					<td>number of page. (start from 1).
    				</tr>
    				<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="left">
    					<td><b>affiliateID</b>
    					<td>your affiliate ID. Do not change this parameter.
    				</tr>
    				<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="left">
    					<td><b>format</b>
    					<td>must be = "XML".
    				</tr>
    				<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="left">
    					<td><b>count</b>
    					<td>number of links to return. If you want to get links from 6 to 10 you must specify: page=2, count=5.
    				</tr>
    				
    			</table>
        </TD>
</TR>

<TR STYLE="font-size:14px; font-family:Verdana;" ALIGN="CENTER" bgcolor="">	
        <TD align="center">
			<b>format:</b><br>
    			<TEXTAREA name="area0" style="width:600;height:150;"><%$xmlformat%></TEXTAREA>
        </TD>
</TR>
<%if $loginMode == "affiliate"%>
<TR STYLE="font-size:14px; font-family:Verdana;" ALIGN="CENTER" bgcolor="">	
        <TD align="center">
			<input type="button" value="test" 
			 onclick="
	window.open(document.all['url'].value, 'test', 'width=800,height=600px,location=no,resizable=yes,directories=no,menubar=yes,scrollbars=yes,status=yes,titlebar=yes,toolbar=yes');" >
        </TD>
</TR>
<%/if%>
	
</TABLE>

</form>
