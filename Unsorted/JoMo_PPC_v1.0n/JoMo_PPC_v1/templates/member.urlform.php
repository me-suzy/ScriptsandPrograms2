
<table border="0" align="center" cellpadding="5" cellspacing="0" bgcolor="#E0E0E0">

 <tr valign="top">
  <td align="right">created:</td>
  <td><%$url.creationDate%></td>
 </tr> 

 <tr valign="top">
  <td align="right">modified:</td>
  <td><%$url.modificationDate%></td>
 </tr>

 <tr valign="top">
  <td align="right">url:</td>
  <td><input name="result[url]" value="<%if $url.url == ''%>http://<%else%><%$url.url%><%/if%>" type="text" size="30" maxlength="30"></td>
 </tr>

 <tr valign="top">
  <td align="right">Title:</td>
  <td><INPUT TYPE="TEXT" NAME="result[title]" VALUE="<%$url.title%>" size="30" maxlength="40">
  </td>
 </tr>

 <tr valign="top">
  <td align="right">description:</td>
  <td><textarea name="result[description]" rows="10" cols="50"><%$url.description%></textarea></td>
 </tr>

 <tr>
  <td colspan="2" align="center">

        <input type="hidden" name = "result[memberID]" value="<%$url.memberID%>">
        <input type="hidden" name = "result[urlID]" value="<%$url.urlID%>">

        <input type="hidden" name = "cmd" value=<%if $cmd eq 'create' %>"signupcreate"<%else%>"signupedit"<%/if%>>

        <input type="submit" value="<%if $cmd eq 'create'%>create<%else%>update<%/if%>">
        <input type="reset" value="reset">
        <input type="button" value="cancel" onclick="urlForm.cmd.value='cancel'; urlForm.submit();">

  </td>

 </tr>
</table>
