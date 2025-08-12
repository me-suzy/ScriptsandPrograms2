
<?php
print "$_COOKIE[username]";
print "<br>Cash: $stat[cash]";
print "<br>class:";
include("charclass.001.php");
print "<br>job:";
include("charjob.001.php");
print "<br>realm:";
include("realms.001.php");



print "<br>
<ul>
<li><a href=\"$GAME_SELF?p=pophealth\" onmouseover=\"this.T_STICKY=true;this.T_STATIC=true;return escape('";
include("pophealth.001.php");
print"')\">Health</a>
<li><a href=\"$GAME_SELF?p=popinv\" onmouseover=\"this.T_STICKY=true;this.T_STATIC=true;return escape('";
include("popinv.001.php");
print"')\">Inventory</a>
<li><a href=\"$GAME_SELF?p=popminigames\" onmouseover=\"this.T_STICKY=true;this.T_STATIC=true;return escape('";
include("popminigames.001.php");
print"')\">Minigames</a>
<li><a href=\"$GAME_SELF?p=1p\">Fight</a>
</ul>
";




?>