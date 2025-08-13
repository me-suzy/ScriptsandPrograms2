<script>
 function openKeywordBox(){
 	window.open('keywordbox.php?memberID=<%$banner.memberID%>&keyword=<%$banner.keywords%>&listingType=banner', 'keyword', 'width=300,height=310px,location=no,resizable=yes,directories=no,menubar=no,scrollbars=no,status=yes,titlebar=no,toolbar=no'); 
 }
    
</script> 

        <input type="hidden" name = "result[memberID]" value="<%$banner.memberID%>">
        
        <input type="hidden" name = "result[bannerID]" value="<%$banner.bannerID%>">
        <input type="hidden" name = "bannerID" value="<%$banner.bannerID%>">
        
        <input type="hidden" name = "bannerName" value="<%$banner.name%>">
        
        <input type="hidden" name = "result[status]" value="<%$banner.status%>">
        <input type="hidden" name = "result[adminStatus]" value="<%$banner.adminStatus%>">        
        <input type="hidden" name = "result[isCatchAll]" value="<%$banner.isCatchAll%>">
        <input type="hidden" name = "result[isPerImpression]" value="<%$banner.isPerImpression%>">

        <input type="hidden" name = "cmd" value=<%if $cmd == 'edit'%>"signupedit"<%else%>"signupcreate"<%/if%>>


<%if $cmd == "edit" %>
<TABLE ALIGN="CENTER" BORDER="1" CELLPADDING="5" CELLSPACING="0" WIDTH="100%" background="" bgcolor="#cccccc" bordercolor="#999999">
<TR ALIGN="CENTER" STYLE="font-size:13px; font-weight:bold;font-family:Verdana;background-color:'#ccffcc'"   >
        <TD Align="center" colspan="5">
        	banner "<%$banner.name%>".
	</TD>
</TR>        	
<TR ALIGN="CENTER" STYLE="font-size:13px; font-weight:bold;font-family:Verdana;background-color:'white'"   >
        <TD Align="center" colspan="5">
        <div id="divbanners" align="center">
                <IMG src="<%$banner.path%>"><br>
        </div>
        </TD>
</TR>
</TABLE>
<%/if%>

		
<table border="0" align="center" cellpadding="5" cellspacing="0" bgcolor="#E0E0E0">
<tr valign="top">
  <td align="right">created:</td>
  <td>
            <%$banner.creationDate%>
  </td>
 </tr>
 <tr valign="top">
  <td align="right">modified:</td>
  <td>
            <%$banner.modificationDate%>
  </td>
 </tr> 
 
 <%if $loginMode == "admin"%>
    <%if $banner.memberID != 0%>
 <tr valign="top">
  <td align="right">member status:</td>
  <td>
    	<%if $banner.status != 0%>activated by member<%else%>deactivated by member<%/if%>
  </td>
 </tr>                        
    <%/if%>

 <tr valign="top">
  <td align="right">admin status:</td>
  <td>
    	<input type="checkbox" name="adminStatus" <%if $banner.adminStatus != 0%>CHECKED<%/if%>>enabled<br>
  </td>
 </tr>
 
 <%else%>
 <tr valign="top">
  <td align="right">admin status:</td>
  <td>
    	<%if $banner.adminStatus != 0%>enabled by admin<%else%>disabled by admin. (banner becomes active after admin approves it)<%/if%>
  </td>
 </tr>

 <tr valign="top">
  <td align="right">status:</td>
  <td>
    	<input type="checkbox" name="status" <%if $banner.status != 0%>CHECKED<%/if%>>active<br>
    	<!--
 	  	<%$statusMsg%>
 	  	-->
  </td>
 </tr>
<%/if%>

<%if $loginMode == "admin"%>
 <tr valign="top">
  <td align="right">member:</td>
  <td>
            <%$banner.membername%><br>
  </td>
 </tr>
<%/if%>
  
 <tr valign="top">
  <td align="right">per impression:</td>
  <td>
    	<input type="checkbox" name="isPerImpression" <%if $banner.isPerImpression != 0%>CHECKED<%/if%>>per impression<br>
    	"set price" bid=<%$bannerImpressionBid%>
		<br>
		option "Per Impression" is <%if $bannerPerImpression == 1%>ON<%else%>OFF - per impression banners won't be 
		displayed in search results. <%/if%>
  </td>
 </tr>
 <tr valign="top">
  <td align="right">image:</td>
  <td>	
  			<b><%$banner.name%></b> &nbsp 
  			<br>
            <input type="file" name="userfile"><br>
            <%if $cmd == "edit"%>leave blank if not changed<%/if%>
            <!--
            <input type="submit" name="submitUpload" value="upload">
            -->
            <INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="100000">
  </td>
 </tr>
 <tr valign="top">
  <td align="right">url:</td>
  <td>
            <INPUT type="text" name="result[url]" STYLE="width:400" value=<%$banner.url%>>
  </td>
 </tr>
 <tr valign="top">
  <td align="right">keywords<br>(each keyword in <br>separated by comma):</td>
  <td>
    <textarea name="result[keywords]" rows="5" cols="40"><%$banner.keywords%></textarea><br>
    <input type="checkbox" name="isCatchAll" <%if $banner.isCatchAll != 0%>CHECKED<%/if%>> catch all<br>
    <a href="#" onclick="openKeywordBox(); return false;">view top keyword bids</a>
  </td>
 </tr>
 <tr valign="top">
  <td align="right">Bid:</td>
  <td>
	  <%if $banner.isPerImpression%>
	  	<INPUT TYPE="TEXT" NAME="result[bid]" VALUE="<%$bannerImpressionBid%>" size="30" maxlength="40" >
	  <%else%>
	  	<INPUT TYPE="TEXT" NAME="result[bid]" VALUE="<%$banner.bid%>" size="30" maxlength="40">
	  <%/if%>
  </td>
 </tr>

 <tr>
  <td colspan="2" align="center">

        <input type="submit" value="<%if $cmd == 'edit'%>update<%else%>create<%/if%> banner" name="submitBanner">
        <input type="reset" value="reset">
        <input type="button" value="cancel" onclick="bannerForm.cmd.value='cancel'; bannerForm.submit(); ">

  </td>

 </tr>
</table>
