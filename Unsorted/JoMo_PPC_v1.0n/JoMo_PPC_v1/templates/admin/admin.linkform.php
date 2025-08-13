
<table border="1" align="center" cellpadding="5" cellspacing="0" bgcolor="#E0E0E0" bordercolor="black">
 <tr valign="top">
  <td align="center" colspan="2"><b> 	<%$statusMsg%> </b></td>
 </tr>
  <tr valign="top">
  <td align="right">created:</td>
  <td>
            <%$link.creationDate%>
  </td>
 </tr>
 <tr valign="top">
  <td align="right">modified:</td>
  <td>
            <%$link.modificationDate%>
  </td>
 </tr>
 
 <tr valign="top">
  <td align="right">admin status:</td>
  <td>
    	<input type="checkbox" name="adminStatus" <%if $link.adminStatus != 0%>CHECKED<%/if%>>enable<br>
  </td>
 </tr>
  
 <tr valign="top">
  <td align="right">member:</td>
  <td>
            <%$link.membername%><br>
			<a href="<%$selfURL%>?mode=accountinfo&itemID=<%$memberID%>&accountType=member&cmd=">view member account</a>
  </td>
 </tr>
 <tr valign="top">
  <td align="right">url:</td>
  <td>
            <%$link.url%><br>
            <!--
			<a href="#">view links of URL</a>
			-->
  </td>
 </tr>
 <tr valign="top">
  <td align="right">keyword:</td>
  <td>
            <input type="text" name="result[keywordName]" value="<%$link.keywordName%>">
  </td>
 </tr>
 <tr valign="top">
  <td align="right">Title:</td>
  <td>
  	<input type="text" name="result[title]" value="<%$link.title%>">
  </td>
 </tr>
 <tr valign="top">
  <td align="right">Bid:</td>
  <td>
  	<input type="text" name="result[bid]" value="<%$link.bid%>">
  </td>
 </tr>
 <tr valign="top">
  <td align="right">description:</td>
  <td><textarea name="result[description]" rows="10" cols="50"><%$link.description%></textarea></td>
 </tr>
 <tr valign="top">
  <td align="right">member status:</td>
  <td>
    	<%if $link.status != 0%>activated by member<%else%>deactivated by member<%/if%>
  </td>

 </tr>

 <tr>
  <td colspan="2" align="center">

		<input type="submit" value="<%if $cmd eq 'create'%>create<%else%>update<%/if%> link">
        <input type="reset" value="reset">
        <input type="button" value="cancel" onclick="location.href='<%$selfURL%>?mode=link&cmd=cancel'">
                
  </td>

 </tr>
</table>

