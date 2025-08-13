<%include file="header.php"%>

<div height="20"> &nbsp</div>

<%include file="affiliate.menu.php"%>


<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" WIDTH="100%" background="">
<TR ALIGN="CENTER" colspan="1" STYLE="font-size:13px; font-weight:bold;font-family:Verdana;background-color:'white'"   >
        <td>
        Customize search results.<br>
        </td>
</TR>
</table>

<script>
 function onFormSubmit(){
          f.all["result[showURL]"].value = f.all["showURL"].checked?1:0;
		  f.all["result[showBid]"].value = f.all["showBid"].checked?1:0;
		  f.all["result[openLinkInNewWindow]"].value = f.all["openlink"].checked?1:0;		  
		  //alert(f.all["result[showBid]"].value);
          return true;
 }

</script>

<FORM name="f" ACTION="<%$selfURL%>" method="post" onsubmit="return onFormSubmit();">

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" width="100%" background="" bgcolor="#eeeeee">
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="CENTER">
        <TD align="left" colspan="8">
			   <b><%$msg%></b>	
        </TD>
</tr>
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="CENTER">
        <TD align="left" colspan="8">
			When someone does a search through your affiliate search box, You can customize the way search results are brought up by using the following variables.
        </TD>
</tr>
</table>


<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" width="100%" background="" bgcolor="">

<TR ALIGN="CENTER" STYLE="font-size:13px; font-weight:bold;font-family:Verdana;background-color:#FFAD5B"   >
        <TD Align="center">Show</TD>
</TR>

<TR ALIGN="CENTER" >
        <TD Align="left">
			<input type="checkbox" name="showURL" <%if $customs.showURL == 1%>CHECKED<%/if%>>URLs
		</TD>
</TR>		
<TR ALIGN="LEFt" >		
		<TD>
			<input type="checkbox" name="showBid" <%if $customs.showBid == 1%>CHECKED<%/if%>>bids
		</TD>
</TR>
<TR ALIGN="CENTER" >
        <TD Align="left">
			<input type="checkbox" name="openlink" <%if $customs.openLinkInNewWindow == 1%>CHECKED<%/if%>>open link in new window
		</TD>
</TR>		

</TABLE>


<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" width="100%" background="" bgcolor="">

<TR ALIGN="CENTER" STYLE="font-size:13px; font-weight:bold;font-family:Verdana;background-color:#FFAD5B"   >
        <TD Align="center" colspan="2">Colors</TD>
</TR>

<%section name=ColorLoop loop=$colors%>

<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="left">
	<TD>
		<%$colors[ColorLoop].colorName%>:
	</TD>
	<TD>
		<input type="text" name="result[<%$colors[ColorLoop].colorID%>]" value="<%$colors[ColorLoop].value%>">
	</TD>
</TR>
<%sectionelse%>
<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="left">
	<TD colspan="2">
		no colors
	</TD>
</TR>
	
<%/section%>

</TABLE>


<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" width="100%" background="">
<TR ALIGN="CENTER" STYLE="font-size:13px; font-weight:bold;font-family:Verdana;background-color:#FFAD5B"   >
        <TD Align="center">Header</TD>
</TR>

<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="left">
	<TD>
		<TEXTAREA name="result[header]" style="width:400; height:100;"><%$customs.header%></TEXTAREA>
	</TD>
</TR>


</TABLE>

<TABLE ALIGN="CENTER" BORDER="0" CELLPADDING="5" CELLSPACING="1" width="100%" background="">
<TR ALIGN="CENTER" STYLE="font-size:13px; font-weight:bold;font-family:Verdana;background-color:#FFAD5B"   >
        <TD Align="center">Footer</TD>
</TR>

<TR STYLE="font-size:11px; font-family:Verdana;" ALIGN="left">
	<TD>
		<TEXTAREA name="result[footer]" style="width:400; height:100;"><%$customs.footer%></TEXTAREA>
	</TD>
</TR>

</TABLE>

<TABLE ALIGN="CENTER" BORDER="1" CELLPADDING="5" CELLSPACING="1" WIDTH="100%" background="" bgcolor="#cccccc" bordercolor="#666666">
<TR ALIGN="CENTER">
        <TD Align="center" colspan="5">
				<input type="submit" value="save" name="submitSave" onclick="cmd.value='save'; return true;">
                <input type="submit" value="preview" name="submitPreview" onclick="cmd.value='preview'; return true;">
				<input type="reset" value="reset" >
        </TD>
</TR>

</TABLE>


<INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="100000">

<input type="hidden" name="result[showURL]" value="0" >
<input type="hidden" name="result[showBid]" value="0" >
<input type="hidden" name="result[openLinkInNewWindow]" value="0" >

<input type="hidden" name="mode" value="affiliates" >
<input type="hidden" name="affMode" value="customize" >

<input type="hidden" name="affiliateID" value="<%$member.affiliateID%>" >
<input type="hidden" name="cmd" value="" >

</form>

<%include file="affiliate.footer.php"%>