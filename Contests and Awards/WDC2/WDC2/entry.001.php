<?php
$looked = mysql_fetch_array(mysql_query("select * from contest_entries where id=$view"));
if($looked[id]){
$tehartiste = mysql_fetch_array(mysql_query("select * from users where id=$looked[user]"));
print"<br>Artist : <a href=\"$GAME_SELF?p=view&amp;view=$looked[user]\">$tehartiste[username]</a><br><center><img src=\"$looked[filename]\" border=\"0\">";

$tehcomments = mysql_query("select * from contest_comments where entry=$looked[id]");
while($printcom=mysql_fetch_array($tehcomments)){
$tehcommentor = mysql_fetch_array(mysql_query("select * from users where id=$printcom[user]"));
print "<table border=1 width=\"98%\" cellpadding=3 cellspacing=0><tr>
<td class=eventit><b>$printcom[title]</b> by <a href=\"$GAME_SELF?p=view&view=$tehcommentor[id]\">$tehcommentor[username]</a>  ...[$printcom[stamp]]</td>
</tr><tr>
<td class=event>\"$printcom[comment]\"</td>
</tr></table><br>";
}

if($user[position]=="Guest"){
print"Register to leave comments";
}else{
print"<table>
<form method=post action=\"$GAME_SELF?p=entry&amp;view=$view&amp;action=Add\">
<tr><td>Title:</td><td><input type=text name=addtitle></td></tr>
<tr><td valign=top>Comment:</td><td><textarea name=addcomment rows=5 cols=19></textarea></td></tr>
<tr><td colspan=2 align=center><input type=submit name=action value=\"Add\"></td></tr>
</form>
</table>";
}



if ($action == Add) {
if (empty ($addtitle) || empty ($addcomment)|| empty ($view)) {
                print "Duh... fill out all fields.";
                return;
}


$addcomment = str_replace("'","&#39;",$addcomment);
$addtitle = str_replace("'","&#39;",$addtitle);

$addcomment = str_replace("
","<BR>",$addcomment);

$addcomment = str_replace("'","&#39;",$addcomment);

$addcomment = str_replace("(C)","&#169;",$addcomment);
$addcomment = str_replace("(c)","&#169;",$addcomment);
$addcomment = str_replace("  "," &nbsp;",$addcomment);

$today = getdate();
$date="$today[mday].$today[mon].$today[year]";

        mysql_query("insert into contest_comments (`user`, `title`, `comment`, `stamp`, `entry`)
        values ('$user[id]','$addtitle','$addcomment','$date','$view')") or print("<br>Could not add updates.");
        print "<meta http-equiv='refresh' content='2; url=$GAME_SELF?p=entry&amp;view=$view'>";


}



}else{
print"error";
}

?>
</center>