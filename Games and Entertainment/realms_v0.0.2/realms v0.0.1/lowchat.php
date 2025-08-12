<SCRIPT LANGUAGE="JavaScript "type="text/javascript"><!--
function mySubmit() {
    setTimeout('document.chat.submit()',100);
    setTimeout('document.chat.reset()',500);
    return false;
}
//--></SCRIPT>


<?php
print"<form NAME=chat method=post target=ifr action=\"chatmsgs.php?action=chat&amp;version=low\" onSubmit=\"return mySubmit()\">
[<a href=\"$GAME_SELF?p=chat\">Go full</a>] <input type=text name=msg size=40 maxlength=100 ONCHANGE=\"focus()\">
<input type=submit value=say>
</form>



<iframe src=\"chatmsgs.php?version=low\" width=\"100%\" height=\"100\" id=ifr name=ifr frameborder=0></iframe>


";