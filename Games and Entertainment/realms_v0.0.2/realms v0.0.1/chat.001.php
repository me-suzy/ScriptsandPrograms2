<SCRIPT LANGUAGE="JavaScript" type="text/javascript">
function mySubmit() {
    setTimeout('document.chat.submit()',100);
    setTimeout('document.chat.reset()',500);
    return false;
}
</SCRIPT>


<?php
print"<table width=\"100%\">
<tr><td colspan=2 align=center>
<form NAME=chat method=post target=ifr action=\"chatmsgs.php?action=chat\" onSubmit=\"return mySubmit()\">
[<a href=\"$GAME_SELF?p=chat\">refresh</a>] <input type=text name=msg size=55 maxlength=200 ONCHANGE=\"focus()\">
<input type=submit value=say>
</form>
</td></tr>
<tr><td width=\"100%\" valign=top>
<u><b>Chat</b></u><br><br>

<iframe src=chatmsgs.php width=\"100%\" height=\"400\" id=ifr name=ifr frameborder=0></iframe>

</td><td width=100 valign=top>
&nbsp;</td></tr>
<tr><td colspan=2 align=center>&nbsp;
</td></tr>
</table>

";