<table border='0' width='100%'>
<tr><td class='fframe'><%IP%></td><td><%IP_VAL%></td></tr>
<tr><td class='fframe'><%REGISTERED%></td><td><%REGISTERED_VAL%></td></tr>
<tr><td class='fframe'><%GROUP%></td><td><%GROUP_VAL%></td></tr>
<tr><td class='fframe'><%PENNAME%></td><td><input type='text' name='uname' value='<%PENNAME_VAL%>'></td></tr>
<tr><td class='fframe'><%PASSWORD%></td><td><input type='text' name='upass'></td></tr>
<tr><td class='fframe'><%EMAIL%></td><td><input type='text' name='uemail' value='<%EMAIL_VAL%>'></td></tr>
<tr><td class='fframe'><%LANGUAGE%></td><td><select name='ulang'><%LANGUAGE_VAL%></select></td></tr>
<tr><td class='fframe'><%SKIN%></td><td><select name='uskin'><%SKIN_VAL%></select></td></tr>
<tr><td class='fframe'><%AVATAR%></td><td><select name='uavatara' onChange="img.src ='<%IMGP%>'+ this.value"><%AVATAR_VAL%></select><p>
<input type='file' name='uavatarb'></td></tr>
<tr><td class='fframe'><%BIO%></td><td><textarea name='ubio'><%BIO_VAL%></textarea></td></tr>
<tr><td class='frame' colspan='2'><input type='submit' value='<%GO%>'></td></tr>
</table>