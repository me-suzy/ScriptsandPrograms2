<form name="previewf" ACTION="previewbox.php" method="post" target="_blank">
	<input type="hidden" name="htmlcode" value="">
</form>

<form name="sendf" ACTION="<%$selfURL%>" method="post" ENCTYPE="multipart/form-data">

<input type="hidden" name="mode" value="searchboxes">
<input type="hidden" name="cmd" value="">
<input type="hidden" name="itemID" value="0">
<input type="hidden" name="result[htmlCode]" value="">
<input type="hidden" name="result[searchBoxName]" value="">
<input type="hidden" name="page" value="">


</form>

<form name="f" ACTION="<%$selfURL%>" method="post" ENCTYPE="multipart/form-data">

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" width="100%" background="" bgcolor="">
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="CENTER">
        <TD align="center" colspan="8">
			   <b><%$msg%></b>	
        </TD>
</tr>
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="CENTER">
        <TD align="center" colspan="8">
				Search boxes. View code.<br>
        </TD>
</tr>
</table>


<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" width="100%" background="" bgcolor="#eeeeee">

<%if $smode == "admin"%>
<TR STYLE="font-size:14px; font-family:Verdana;" ALIGN="CENTER"
bgcolor="">	
        <TD align="center">
			<b>Create new search box.</b><br>
                <div id="div0" style="border-width:1; background-color:#eeeeee; border-color:black;">
                </div>
                name:<input type="text" name="boxname" value="new search box name" style="width:200;"> 
                <br>
    			<TEXTAREA name="area0" style="width:400;height:150;"><input type="text" name="str">
</TEXTAREA>
    			<br>
	    	<A HREF="#" onclick="viewBox('div0','area0');"
                        >view here</a><br>
                        
			<A HREF="#" onclick="viewBoxWindow('div0','area0');"
						>preview in new window</a><br>
						
				<A HREF="#"	onclick="
                            document.forms['sendf'].cmd.value='create'; 
                            document.forms['sendf'].all['result[htmlCode]'].value=document.all['area0'].innerText;
                            document.forms['sendf'].all['result[searchBoxName]'].value=document.forms['f'].boxname.value;
							document.forms['sendf'].itemID.value=0;
							document.forms['sendf'].submit();"
						>create search box</a> | 						

        </TD>
	</TR>
</TABLE>
<%/if%>

<%section name=ItemLoop loop=$items%>
	<TABLE ALIGN="CENTER" BORDER="1" CELLPADDING="5" CELLSPACING="1" width="100%" background="" bgcolor="#eeeeee">

<TR STYLE="font-size:14px; font-family:Verdana;" ALIGN="CENTER"
bgcolor="<%if $smarty.section.ItemLoop.index is even %>#c8c8c8<%else%>#dDdDdD<%/if%>"
>	
        <TD align="center">
                <b><%$items[ItemLoop].searchBoxName%></b><br>
                <div id="div<%$items[ItemLoop].searchBoxID%>" style="border-width:1; background-color:#eeeeee; border-color:black;">
                	<%$items[ItemLoop].htmlCode%>
                </div>
    			<TEXTAREA name="area<%$items[ItemLoop].searchBoxID%>" style="width:400;height:150;"><%$items[ItemLoop].htmlCode%></TEXTAREA>
    			<br>

<%if $smode == "admin"%>

	    	<A HREF="#"
                        onclick="viewBox('div<%$items[ItemLoop].searchBoxID%>','area<%$items[ItemLoop].searchBoxID%>');"
                        >view here</a><br>
<!--                        
			<A HREF="#"
							onclick="viewBoxWindow('div<%$items[ItemLoop].searchBoxID%>','area<%$items[ItemLoop].searchBoxID%>');"
						>preview in new window</a><br>
-->						
<!--						
                <A HREF="#"
                        onclick="
                            document.forms['f'].cmd.value='editor'; 
							document.forms['f'].itemID.value=<%$items[ItemLoop].searchBoxID%>;
							document.forms['f'].submit();"
						>edit code in editor</a><br>
-->						
				<A HREF="#"	onclick="
                            document.forms['sendf'].cmd.value='save'; 
                            document.forms['sendf'].all['result[htmlCode]'].value=document.all['area<%$items[ItemLoop].searchBoxID%>'].innerText;
                            document.forms['sendf'].all['result[searchBoxName]'].value='<%$items[ItemLoop].searchBoxName%>';
							document.forms['sendf'].itemID.value=<%$items[ItemLoop].searchBoxID%>;
							document.forms['sendf'].submit();"
						>save HTML</a> | 						
				<A HREF="#"	onclick="
                            document.forms['sendf'].cmd.value='delete'; 
							document.forms['sendf'].itemID.value=<%$items[ItemLoop].searchBoxID%>;
							document.forms['sendf'].submit();"
						>delete</a> | 

<!--						
				<A HREF="#"	onclick="
                            document.forms['f'].cmd.value='activate'; 
							document.forms['f'].itemID.value=<%$items[ItemLoop].searchBoxID%>;
							document.forms['f'].submit();"
						>activate/deactivate</a> | 
-->
<%/if%>

        </TD>
</TR>
	</TABLE>
<%/section%>


</form>
