<table border='0' width='100%'>
<tr><td class='fframe'><%CATNAME%></td><td><input type='text' name='cname'></td></tr>
<tr><td class='fframe'><%CATDESCRIPTION%></td><td><input type='text' name='cdesc'></td></tr>
<tr><td class='fframe'><%CATPARENT%></td><td><select name='cparent'><%CATPARENT_VAL%></select></td></tr>
<tr><td class='fframe'><%CATACTIVE%></td><td><%CATACTIVE_VAL%></td></tr>
<tr><td class='fframe'><%CATAPPROVAL%></td><td><%CATAPPROVAL_VAL%></td></tr>
<tr><td class='fframe'><div class='imgholder'><%IMG%></div></td>
<td><select name='cimga' onChange="img.src ='<%IMGP%>'+ this.value"><%CATIMGA_VAL%></select><p>
<input type='file' name='cimgb'></td></tr>
<tr><td class='fframe'><%CATCHARACTERS%></td><td><textarea name='cchars'></textarea></td></tr>
<tr><td class='fframe'><%CATPERMISSIONS%></td><td><%CATPERMISSIONS_VAL%></td></tr>
<tr><td class='fframe'><%CATMODERATORS%></td><td><select name='cmod[]' size='5' multiple><%CATMODERATORS_VAL%></select></td></tr>
<tr><td colspan='2' class='frame'><input type='submit' value='<%GO%>'></td></tr>
</table>