<?php
$onlinenow=usersonline(10,on);
print"<br><CENTER>
<table class=\"ontop\" width=\"100%\"border=0 cellpadding=0 cellspacing=0><tr>
<td colspan=3><hr>Users online : &nbsp; $onlinenow<hr></td></tr><tr>
<td width=150><CENTER><a href=\"$GAME_SELF?p=rules\">Game Rules</a></CENTER></td>
<td width=150><CENTER><a href=\"$GAME_SELF?p=morestats\">More Stats</a></CENTER></td>
<td width=150><CENTER><a href=\"$GAME_SELF?p=privacy\">Privacy Statement</a></CENTER></TD>
</TR><TR>
<TD colspan=3><CENTER><a href=\"$GAME_SELF?p=source&amp;file=$p\">View PHP source</a></CENTER></td>
</tr></TABLE></CENTER><br>";