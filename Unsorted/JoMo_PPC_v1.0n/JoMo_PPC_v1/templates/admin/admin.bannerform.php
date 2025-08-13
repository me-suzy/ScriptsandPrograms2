
<table border="1" width="400" align="center" cellpadding="5" cellspacing="1" bgcolor="#E0E0E0" bordercolor="black">
 <tr valign="top">
  <td align="center" colspan="2"><b> 	<%$statusMsg%> </b></td>
 </tr>
 <tr valign="top">
  <td align="right" width="100">image:</td>
  <td>
	  <div align="center">
                <IMG src="<%$item.path%>"><br>
				<%$item.name%>	
        </div>
            
  </td>
 </tr>
 <tr valign="top">
  <td align="right">created:</td>
  <td>
            <%$item.creationDate%>
  </td>
 </tr>
 <tr valign="top">
  <td align="right">modified:</td>
  <td>
            <%$item.modificationDate%>
  </td>
 </tr> 
 <tr valign="top">
  <td align="right">member:</td>
  <td>
            <%$item.membername%><br>
			<a href="#">view member account</a>
  </td>
 </tr>
 <tr valign="top">
  <td align="right">url:</td>
  <td>
            <%$item.url%><br>
			<a href="#">view items of URL</a>
  </td>
 </tr>

 <tr valign="top">
  <td align="right">keyword:</td>
  <td>
            <%$item.keywords%>
  </td>
  
 </tr>
  <tr valign="top">
  <td align="right">catch all:</td>
  <td>
	<input type="checkbox" name="isCatchAll" <%if $item.isCatchAll != 0%>CHECKED<%/if%>> catch all
  </td>
 </tr>

 <tr valign="top">
  <td align="right">Bid:</td>
  <td><%$item.bid%>
  </td>
 </tr>
 <tr valign="top">
  <td align="right">status:</td>
  <td>
    	<%if $item.status != 0%>activated by member<%else%>deactivated by member<%/if%>
  </td>

 </tr>

 <tr>
  <td colspan="2" align="center">
        <input type="hidden" name = "mode" value="banners">

        <input type="hidden" name = "cmd" value="">

        <input type="submit" value="OK" style="width:80;">
  </td>

 </tr>
</table>

