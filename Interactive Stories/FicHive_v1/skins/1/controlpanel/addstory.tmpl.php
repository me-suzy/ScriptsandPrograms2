<script language='javascript' src='js.js'></script>

<script language='javascript'>
var maxl = <%MAX%>;
</script>

<script language='javascript'>
function setOptions(chosen) {
var selbox = document.form1.scharacter;
	selbox.options.length = 0;
	<%OPTIONS%>
}
</script>

<table border='0' width='100%'>
<tr><td class='fframe'><%STORYTITLE%></td><td><input type='text' name='stitle'></td></tr>
<tr><td class='fframe'><%DESCRIPTION%></td><td><input type='text' name='sdesc'></td></tr>
<tr><td class='fframe'><%CATEGORY%></td><td><select name='scid' onchange="setOptions(document.form1.scid.options[document.form1.scid.selectedIndex].value);"><%CATEGORYLIST%></select></td></tr>
<tr><td class='fframe'><%RATING%></td><td><select name='srating'><%RATING_VAL%></select></td></tr>
<tr><td class='fframe'><%WIP%></td><td><select name='swip'><%WIP_VAL%></select></td></tr>
<tr><td class='fframe'><%PRIMARYGENRE%></td><td><select name='sgenre1'><%PRIMARYGENRE_VAL%></select></td></tr>
<tr><td class='fframe'><%SECONDARYGENRE%></td><td><select name='sgenre2'><%SECONDARYGENRE_VAL%></select></td></tr>
<tr><td class='fframe'><%MAINCHARACTER%></td><td><select name='scharacter'><%MAINCHARACTER_VAL%></select></td></tr>
<tr><td class='fframe'><%CHAPTERTITLE%></td><td><input type='text' name='chtitle'></td></tr>
<tr><td class='fframe' valign='top'><%CHAPTERTEXT%><br><span id='wordcount'>0</span> / <%MAX%></td>
<td><%WYSIWYG%><br><textarea name='chchapter' class='large' onKeyUp='countit(this.value,"wordcount",1);' onKeyPress='countit(this.value,"wordcount",1);'></textarea></td></tr>
<tr><td colspan='2' class='frame'><div id='preview'></div></td></tr>
<tr><td class='fframe'><%CHAPUPLOADA%></td><td><%CHAPUPLOADB%></td></tr>
<tr><td colspan='2' class='frame'><input type='submit' value='<%GO%>' name='addstory'></td></tr>
</table>