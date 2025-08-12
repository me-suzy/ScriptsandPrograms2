<?php if(isset($color_changing)&&$color_changing==1){
print "<img align=\"right\" border=\"0\" vspace=\"2\" hspace=\"0\" src=\"pics/clr.gif\" width=\"109\" height=\"10\" usemap=\"#clr\" alt=\"$lang[5]\" />";
print "<map name=\"clr\" id=\"clr\">";
print "<area shape=\"rect\" onclick=\"change_color($f,0);return false\" coords=\"0,1,10,10\" href=\"#\" alt=\"$lang[5]\" />";
print "<area shape=\"rect\" onclick=\"change_color($f,1);return false\" coords=\"11,1,21,10\" href=\"#\" alt=\"$lang[5]\" />";
print "<area shape=\"rect\" onclick=\"change_color($f,2);return false\" coords=\"22,1,32,10\" href=\"#\" alt=\"$lang[5]\" />";
print "<area shape=\"rect\" onclick=\"change_color($f,3);return false\" coords=\"33,1,43,10\" href=\"#\" alt=\"$lang[5]\" />";
print "<area shape=\"rect\" onclick=\"change_color($f,4);return false\" coords=\"44,1,54,10\" href=\"#\" alt=\"$lang[5]\" />";
print "<area shape=\"rect\" onclick=\"change_color($f,5);return false\" coords=\"55,1,65,10\" href=\"#\" alt=\"$lang[5]\" />";
print "<area shape=\"rect\" onclick=\"change_color($f,6);return false\" coords=\"66,1,76,10\" href=\"#\" alt=\"$lang[5]\" />";
print "<area shape=\"rect\" onclick=\"change_color($f,7);return false\" coords=\"77,1,87,10\" href=\"#\" alt=\"$lang[5]\" />";
print "<area shape=\"rect\" onclick=\"change_color($f,8);return false\" coords=\"88,1,98,10\" href=\"#\" alt=\"$lang[5]\" />";
print "<area shape=\"rect\" onclick=\"change_color($f,9);return false\" coords=\"99,1,109,10\" href=\"#\" alt=\"$lang[5]\" />";
print "</map>";}?> 
<a href="info.php" onclick="uri=escape(window.location.toString());uri='info.php?uri='+uri;window.open(uri,'inf','width=250,height=125');return false" title="about EasyForum!"><img src="pics/ef.gif" width="14" height="12" vspace="0" hspace="1" border="0" align="left" alt="about EasyForum!" /></a>
<span class="f"><?php $end_time=time_to_run();$total_time=substr(($end_time-$start_time),0,5);print "$total_time&nbsp;$lang[31]";?></span>