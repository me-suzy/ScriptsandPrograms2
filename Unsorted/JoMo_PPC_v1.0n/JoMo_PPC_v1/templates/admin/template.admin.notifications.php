<%include file="admin/admin.header.php"%>

<BR>
<%include file="admin/admin.menu.php"%>
<BR>

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="" background="">
<TR ALIGN="left" bgcolor="" >
        <TD Align="center">
                <b>Notifications.</b><br>
                manage notifications.<br>
			   	enable/disable
        </TD>
</TR>
</TABLE>

<form ACTION="<%$selfURL%>" method="post">
<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1">
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="CENTER">
        <TD align="left" colspan="8">
			   email all 
			   <SELECT name=recipient>
			   	<OPTION value=0> members and affiliates
			   	<OPTION value=1> members
			   	<OPTION value=2> affiliates
			   </SELECT>
			   <input type="hidden" name="mode" value="sendnotifications">
			   <input type="submit" value="email">
			   
        </TD>
</tr>
</table>
</form>


<form name="f" ACTION="<%$selfURL%>" method="post">


<input type="hidden" name="mode" value="notifications">
<input type="hidden" name="cmd" value="">
<input type="hidden" name="notifyName" value="">
<input type="hidden" name="isEnable" value="0">
<input type="hidden" name="freq" value="0">

<!--
<TABLE ALIGN="center" BORDER="0" CELLPADDING="5" CELLSPACING="1" background="" >
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="CENTER">
        <TD align="left" colspan="8">
			   <b><a href="<%$selfURL%>?mode=notifications&cmd=cron" >perform all notifications (only for debug)</a></b>	
        </TD>
</tr>
</table>
-->

<TABLE ALIGN="center" BORDER="0" CELLPADDING="5" CELLSPACING="1" background="" >
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="CENTER">
        <TD align="left" colspan="8">
			   <b><%$msg%></b>	
        </TD>
</tr>
</table>


<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="100%" background="">

<TR STYLE="font-size:14px; font-family:Verdana;" ALIGN="CENTER" bgcolor="lightgreen">
        <TD align="left">
            notify
        </TD>
        <TD align="center">
        	    enable
        </TD>
        <TD align="center">
        	    frequency (days)
        </TD>
        <TD align="center">
                commands                
        </TD>
</TR>

<%section name=ItemLoop loop=$items%>
<TR STYLE="font-size:14px; font-family:Verdana;" ALIGN="CENTER" bgcolor="<%if $smarty.section.ItemLoop.index is even %>#c8c8c8<%else%>#dDdDdD<%/if%>" valign="top">
        <TD align="left">
            <%$items[ItemLoop].description%>
        </TD>
        <TD align="center">
        	    <input type="checkbox" 
        	    	name="enable<%$items[ItemLoop].notifyName%>" 
        	    	value="" <%if $items[ItemLoop].isEnable == 1%> checked<%/if%>>
        </TD>
        <TD align="center">
        	    <input type="text" 
        	    name="freq<%$items[ItemLoop].notifyName%>" 
        	    value="<%$items[ItemLoop].freq%>" style="width:40;" 
        	    <%if $items[ItemLoop].notifyType == 'once'%> disabled<%/if%>
        	   >
        </TD>
        <TD align="center">
                <a 
                	href="#"
                	onclick="
                	document.forms['f'].cmd.value = 'update';
                	document.forms['f'].notifyName.value = '<%$items[ItemLoop].notifyName%>';
                	document.forms['f'].isEnable.value = document.forms['f'].enable<%$items[ItemLoop].notifyName%>.checked?1:0;
                	document.forms['f'].freq.value = document.forms['f'].freq<%$items[ItemLoop].notifyName%>.value;
                	document.forms['f'].submit();
                	return false;
                	"
                	>update</a>
                
        </TD>
</TR>
<%/section%>

</TABLE>

<TABLE ALIGN="CENTER" BORDER="1" CELLPADDING="5" CELLSPACING="0" WIDTH="100%" bgcolor="#cccccc" bordercolor="#999999">
<TR ALIGN="left" >
	<TD>
		<UL>
			<LI><a href="<%$selfURL%>?mode=notifications&cmd=enableall"
				onclick="if (!confirm('Are you sure to enable all?')) return false; "> 
				enable all notifications</a>
			<LI><a href="<%$selfURL%>?mode=notifications&cmd=disableall"
				onclick="if (!confirm('Are you sure to disable all?')) return false; "> 
				disable all notifications</a>		
		</UL>
    </TD>
</TR>
</TABLE>

</FORM>

<%include file="admin/admin.footer.php"%>
