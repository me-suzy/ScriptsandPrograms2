        <input type="hidden" name = "result[linkID]" value="<%$link.linkID%>">
        <input type="hidden" name = "result[status]" value="<%$link.status%>">
        <input type="hidden" name = "result[adminStatus]" value="<%$link.adminStatus%>">        
        
        <input type="hidden" name = "cmd" value=<%if $cmd == 'edit'%>"signupedit"<%else%>"signupcreate"<%/if%>>
        
<table border="0" align="center" cellpadding="5" cellspacing="0" bgcolor="#E0E0E0" bordercolor="#999999">
 <tr valign="top">
  <td align="right">created:</td>
  <td>
            <%$link.creationDate%>&nbsp
  </td>
 </tr>
 <tr valign="top">
  <td align="right">modified:</td>
  <td>
            <%$link.modificationDate%>&nbsp
  </td>
 </tr> 

<%if $loginMode == "admin"%>
 <tr valign="top">
  <td align="right">member status:</td>
  <td>
    	<%if $link.status != 0%>activated by member<%else%>deactivated by member<%/if%>
  </td>
 </tr>
 <tr valign="top">
  <td align="right">admin status:</td>
  <td>
    	<input type="checkbox" name="adminStatus" <%if $link.adminStatus != 0%>CHECKED<%/if%>>enabled<br>
  </td>
 </tr>

<%else%>
 <tr valign="top">
  <td align="right">admin status:</td>
  <td>
      <input type="checkbox" name="adminStatus" <%if $link.adminStatus != 0%>CHECKED<%/if%> DISABLED>
    	<%if $link.adminStatus != 0%>enabled by admin<%else%>disabled by admin. (link becomes active after admin approves it)<%/if%>
  </td>

 </tr>
 <tr valign="top">
  <td align="right">status:</td>
  <td>
    	<input type="checkbox" name="status" <%if $link.status != 0%>CHECKED<%/if%>>active<br>
    	<!--
 	  	<%$statusMsg%>
 	  	-->
  </td>
 </tr>
<%/if%>


<%if $loginMode == "admin"%>
 <tr valign="top">
  <td align="right">url:</td>
  <td>
            <%$link.url%>
  </td>
 </tr>
<%else%> 
 <tr valign="top">
  <td align="right">url:</td>
  <td>
            <SELECT name="result[urlID]" STYLE="width:300">
             <%html_options values=$urlIDs output=$urlTitles selected=$link.urlID%>
            </SELECT>&nbsp
            <%if $loginMode == "member"%>
            <a href="<%$selfURL%>?mode=members&memberMode=url&cmd=create" onclick="">Add new url</a>
            <%/if%>
  </td>
 </tr>
<%/if%> 

 <tr valign="top">
  <td align="right">Link title*:</td>
  <td>
    <INPUT TYPE="TEXT" NAME="result[title]" VALUE="<%$link.title%>" size="30" maxlength="40">
  </td>
 </tr>

 <tr valign="top">
  <td align="right">keyword*:</td>
  <td>
            <input type="text" name="result[keywordName]" STYLE="width:300" value="<%$link.keywordName%>"><br>
			<a href="#" onclick="openKeywordBox(); return false;">view top keyword bid</a>
  </td>
 </tr>

 <tr valign="top">
  <td align="right">Bid*:</td>
  <td><INPUT TYPE="TEXT" NAME="result[bid]" VALUE="<%$link.bid%>" size="30" maxlength="40">
  <br>
  	min bid value: $ <%$minBidValue%>
  </td>
 </tr>
 
 <tr valign="top">
  <td align="right">description:</td>
  <td><textarea name="result[description]" rows="10" cols="50"><%$link.description%></textarea></td>
 </tr>

 <tr>
  <td colspan="2" align="center">
        <input type="submit" value="<%if $cmd eq 'create'%>create<%else%>update<%/if%> link">
        <input type="reset" value="reset">
    <%if $loginMode == "admin"%>        
        <input type="button" value="cancel" onclick="location.href='<%$selfURL%>?mode=link&cmd=cancel'">
    <%else%>
        <input type="button" value="cancel" onclick="location.href='<%$selfURL%>?mode=members&memberMode=link&cmd=cancel'">    
    <%/if%>   
  </td>

 </tr>
</table>

