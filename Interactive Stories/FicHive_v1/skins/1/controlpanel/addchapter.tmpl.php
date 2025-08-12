<script language='javascript' src='js.js'></script>

<script language='javascript'>
var maxl = <%MAX%>;
</script>

<table border='0' width='100%'>
<tr><td class='frame' colspan='2'><select name='chsid'><%STORYLIST%></select></td></tr>
<tr><td class='fframe'><%CHAPTERTITLE%></td><td><input type='text' name='chtitle'></td></tr>
<tr><td class='fframe' valign='top'><%CHAPTERTEXT%><br><span id='wordcount'>0</span> / <%MAX%></td>
<td><%WYSIWYG%><br><textarea name='chchapter' class='large' onKeyUp='countit(this.value,"wordcount",2);' onKeyPress='countit(this.value,"wordcount",2);'></textarea></td></tr>
<tr><td colspan='2' class='frame'><div id='preview'></div></td></tr>
<tr><td class='fframe'><%CHAPUPLOADA%></td><td><%CHAPUPLOADB%></td></tr>
<tr><td class='frame' colspan='2'><input type='submit' value='<%GO%>' name='addchapter'></td></tr>
</table>