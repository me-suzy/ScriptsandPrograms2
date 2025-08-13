<?
require("globals.php");
include("config.php");
include("user_access.php");

if($use_flush == "YES"){
?>
<META HTTP-EQUIV="refresh" content="7;URL=<?= $HTTP_GET_VARS['urlof'] ?>">
<font color=007700 size=+4><b>Connecting...please wait...</b></font>
<br><br>
<blockquote>
<b>This connection screen is here for two reasons:</b><br>
<p>
1) Some browsers will not load
other frames if some of the frames have not 
finished loading so this delay is to give the 
other frames in the frameset a chance to load and 
load this frame last because it will never stop loading.. 
<p>
2) 
Some Servers do not honor the flush() buffer 
statment in php so if you are still reading this 
a minute from now or all you ever see is the 
words <b>Refreshing...</b>.. Then then your server does not
honor the flush statment <a href=http://www.php.net/flush>READ ABOUT IT</a>. 
do not worry.. There is 
still hope for you :-). To Fix this Click on the 
<b>Settings</b> tab and select <b>refresh</b> as the type of
chat and then click save. This will solve the problem. 
do it now!! because if you are reading this line then it 
is not working and you need to change it to Refresh mode....
</blockquote>
<a href=<?= $HTTP_GET_VARS['urlof'] ?> > try clicking here</a>
<?
} else {
?>
<META HTTP-EQUIV="refresh" content="4;URL=<?= $HTTP_GET_VARS['urlof'] ?>">
<b>Connecting...please wait...</b>
<br><br>
<blockquote>
You are using the refresh method.. 
<b>This connection screen is here because Some browsers will not load
other frames if some of the frames have not 
finished loading so this delay is to give the 
other frames in the frameset a chance to load and 
load this frame last because it will never stop loading.. 
</blockquote>
<a href=<?= $HTTP_GET_VARS['urlof'] ?> > try clicking here</a>
<? } ?>