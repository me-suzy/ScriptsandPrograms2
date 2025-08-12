<?
extract($HTTP_POST_VARS); 
extract($HTTP_GET_VARS); 
extract($HTTP_COOKIE_VARS); 

// A quick function first off to deal with any edited lines
function functest($matches) {
global $edituser;
global $edittime;
global $totedits;
$edituser = $matches[1];
$edittime = $matches[2];
$totedits = $matches[3];
}

function replacestuff($chktxt, $sigset, $currentuser) {
global $zone;
global $edituser;
global $edittime;
global $totedits;
include "converttime.php";
include "settings.php";
include "languages/$language.php";

// First, ensure there's no functioning HTML code and then insert linebreaks
$chktxt = str_replace("<", "&lt;", $chktxt);
$chktxt = str_replace(">", "&gt;", $chktxt);
$chktxt = str_replace("\n", "<br/>", $chktxt);
$chktxt = str_replace("\r", " ", $chktxt);

// This one sets up the basic quotes; while statement catches nested quotes
$findstring = "/\[quote\] <br\/>(.*?)\[\/quote\] <br\/>/";
while(preg_match($findstring, $chktxt)) {
$chktxt = preg_replace($findstring,"<center><table width=\"90%\"><tr><td valign=\"top\"><font class=\"emph\">$txt_quote:</font><div class=\"quote\">\\1</div></td></tr></table></center>",$chktxt); 
}
$findstring = "/\[QUOTE\] <br\/>(.*?)\[\/QUOTE\] <br\/>/";
while(preg_match($findstring, $chktxt)) {
$chktxt = preg_replace($findstring,"<center><table width=\"90%\"><tr><td valign=\"top\"><font class=\"emph\">$txt_quote:</font><div class=\"quote\">\\1</div></td></tr></table></center>",$chktxt); 
}

// This one sets up quotes where a particular user, or indeed a site, is quoted; while statement catches nested quotes
$findstring = "/\[quote=\"?([^\"\]]+)\"?\] <br\/>(.*?)\[\/quote\] <br\/>/";
while(preg_match($findstring, $chktxt)) {
$chktxt = preg_replace($findstring,"<center><table width=\"90%\"><tr><td valign=\"top\"><font class=\"emph\">\\1 $txt_said:</font><div class=\"quote\">\\2</div></td></tr></table></center>",$chktxt);
}
$findstring = "/\[QUOTE=\"?([^\"\]]+)\"?\] <br\/>(.*?)\[\/QUOTE\] <br\/>/";
while(preg_match($findstring, $chktxt)) {
$chktxt = preg_replace($findstring,"<center><table width=\"90%\"><tr><td valign=\"top\"><font class=\"emph\">\\1 $txt_said:</font><div class=\"quote\">\\2</div></td></tr></table></center>",$chktxt);
}

// This writes the edited time text
$findstring = "/\[EDITCODE ([^\s]+)\s([^\s]+)\s([^\]]+)\]/";
$edits = preg_replace_callback($findstring, functest, $chktxt);
$edittime = date($timeform, $edittime + (3600 * $zone));
$editline = "[i]$txt_editedby [b]${edituser}[/b] $txt_at $edittime. Total edits: ${totedits}[/i]";
$chktxt = preg_replace($findstring, "$editline", $chktxt);

// First parse any unmarked URLs within the text (this only won't function if the URL is the very first character in the post)
$chktxt = preg_replace( "/([^\/=\"\]])((http|ftp)+(s)?:\/\/[^<>\s]+)/i", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>",  $chktxt);
$chktxt = preg_replace('/([^\/=\"\]])(www\.)(\S+)/', '\\1<a href="http://\\2\\3" target="_blank">\\2\\3</a>', $chktxt);

// Parse URLs which have link text assigned
$chktxt = preg_replace("/\[url=\"?((http)+(s)?:(\/\/))?([^\"\]]+)\"?\](.*?)\[\/url\]/","<a href=\"http://\\5\" target=\"blank\">\\6</a>",$chktxt);
$chktxt = preg_replace("/\[URL=\"?((http)+(s)?:(\/\/))?([^\"\]]+)\"?\](.*?)\[\/URL\]/","<a href=\"http://\\5\" target=\"blank\">\\6</a>",$chktxt);

// Next, parse URLs which have been marked as such
$chktxt = preg_replace("/\[url]((http)+(s)?:(\/\/))?(.*?)\[\/url\]/","<a href=\"http\\3://\\5\" target=\"blank\">\\5</a>",$chktxt);
$chktxt = preg_replace("/\[URL]((http)+(s)?:(\/\/))?(.*?)\[\/URL\]/","<a href=\"http\\3://\\5\" target=\"blank\">\\5</a>",$chktxt);

// Now we'll insert images, checking if the registered user doesn't want to see them if this is a signature
if ($sigset == "1" && isset($currentuser)) {
$sigimsoff = mysql_query("SELECT * FROM users WHERE userid='$currentuser'");
$sigimsoff = mysql_result($sigimsoff, 0, "imgsig");
}
if($sigimsoff == 0 && isset($sigimsoff)) $chktxt = preg_replace("/\[img]((http)+(s)?:(\/\/))?(.*?)\[\/img\]/","",$chktxt);
else {
$chktxt = preg_replace("/\[img]((http)+(s)?:(\/\/))?(.*?)\[\/img\]/","<img src=\"http\\3://\\5\" alt=\"$txt_userposted\"/>",$chktxt);
$chktxt = preg_replace("/\[IMG]((http)+(s)?:(\/\/))?(.*?)\[\/IMG\]/","<img src=\"http\\3://\\5\" alt=\"$txt_userposted\"/>",$chktxt);
}

// Finally the (relatively!) simple job of replacing bold, italics etc and inserting smileys
$chktxt = preg_replace('/\[([biuBIU])\]/i', '<\\1>', $chktxt);
$chktxt = preg_replace('/\[\/([biuBIU])\]/i', '</\\1>', $chktxt);
$chktxt = eregi_replace("\[list\]", "[li]", $chktxt);
$chktxt = ereg_replace("\[LI\]", "[li]", $chktxt);

// And then we have to make sure there's unordered list tags at the start and end to validate
if(strstr($chktxt, "[li]")) {
unset($occur);
while(strstr($chktxt, "[li]")) {
$loc = strpos($chktxt, "[li]");
$occur[] = $loc;
$chktxt = substr($chktxt, 0, $loc)."[lo]".substr($chktxt, $loc + 4);
}
$chktxt = str_replace("[lo]", "[li]", $chktxt);
$chktxt = substr($chktxt, 0, $occur[0])."<ul>".substr($chktxt, $occur[0]);
end($occur);
$last = key($occur);
$last = $occur[$last];
$origend = substr($chktxt, $last + 4); // to allow for adding the opening tag
$findstring = "/\[li\](.*?)<br\/>/";
$newend = preg_replace($findstring, "[li]\\1</li></ul>", $origend);
$chktxt = str_replace($origend, $newend, $chktxt);
}
$chktxt = eregi_replace("\[li\]", "<li>", $chktxt);
$chktxt = str_replace("<br/><li>", "</li><li>", $chktxt);

$chktxt = str_replace(":)", "<img src=\"gfx/smileys/smile.gif\" alt=\":)\"/>", $chktxt);
$chktxt = str_replace(":-)", "<img src=\"gfx/smileys/smile.gif\" alt=\":)\"/>", $chktxt);
$chktxt = str_replace(":(", "<img src=\"gfx/smileys/sad.gif\" alt=\":(\"/>", $chktxt);
$chktxt = str_replace(":-(", "<img src=\"gfx/smileys/sad.gif\" alt=\":(\"/>", $chktxt);
$chktxt = str_replace(":@", "<img src=\"gfx/smileys/angry.gif\" alt=\":@\"/>", $chktxt);
$chktxt = str_replace(":-@", "<img src=\"gfx/smileys/angry.gif\" alt=\":@\"/>", $chktxt);
$chktxt = str_replace(":$", "<img src=\"gfx/smileys/blush.gif\" alt=\":$\"/>", $chktxt);
$chktxt = str_replace(":-$", "<img src=\"gfx/smileys/blush.gif\" alt=\":$\"/>", $chktxt);
$chktxt = str_replace(":S", "<img src=\"gfx/smileys/confused.gif\" alt=\":S\"/>", $chktxt);
$chktxt = str_replace(":-S", "<img src=\"gfx/smileys/confused.gif\" alt=\":S\"/>", $chktxt);
$chktxt = str_replace("8)", "<img src=\"gfx/smileys/cool.gif\" alt=\"8)\"/>", $chktxt);
$chktxt = str_replace("8-)", "<img src=\"gfx/smileys/cool.gif\" alt=\"8)\"/>", $chktxt);
$chktxt = str_replace(":'(", "<img src=\"gfx/smileys/cry.gif\" alt=\":'(\"/>", $chktxt);
$chktxt = str_replace(":'-(", "<img src=\"gfx/smileys/cry.gif\" alt=\":'(\"/>", $chktxt);
$chktxt = str_replace(":D", "<img src=\"gfx/smileys/grin.gif\" alt=\":D\"/>", $chktxt);
$chktxt = str_replace(":-D", "<img src=\"gfx/smileys/grin.gif\" alt=\":D\"/>", $chktxt);
$chktxt = str_replace(":|", "<img src=\"gfx/smileys/line.gif\" alt=\":|\"/>", $chktxt);
$chktxt = str_replace(":-|", "<img src=\"gfx/smileys/line.gif\" alt=\":|\"/>", $chktxt);
$chktxt = str_replace(":P", "<img src=\"gfx/smileys/tongue.gif\" alt=\":P\"/>", $chktxt);
$chktxt = str_replace(":-P", "<img src=\"gfx/smileys/tongue.gif\" alt=\":P\"/>", $chktxt);
$chktxt = str_replace(";)", "<img src=\"gfx/smileys/wink.gif\" alt=\";)\"/>", $chktxt);
$chktxt = str_replace(";-)", "<img src=\"gfx/smileys/wink.gif\" alt=\";)\"/>", $chktxt);
$chktxt = str_replace(":O", "<img src=\"gfx/smileys/shock.gif\" alt=\":O\"/>", $chktxt);
$chktxt = str_replace(":-O", "<img src=\"gfx/smileys/shock.gif\" alt=\":O\"/>", $chktxt);

echo $chktxt;
return;
}

?>