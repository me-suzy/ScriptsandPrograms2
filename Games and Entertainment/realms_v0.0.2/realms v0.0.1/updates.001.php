<?php

if ($view != all) {
        $usel = mysql_query("select * from updates order by id desc limit 5");
$next=0;
print"<center>";
        while ($upd = mysql_fetch_array($usel)) {


print "<table border=1 width=\"98%\" cellpadding=3 cellspacing=0><tr><td class=eventit><b>$upd[title]</b> by $upd[starter] ...[$upd[stamp]] ";
        print "</td></tr>";
        print "<tr><td class=event>\"$upd[updates]\"</td></tr></table><br>";



        }




print"&nbsp;";


        print "(<a href=\"$GAME_SELF?p=updates&amp;view=all\">click here to view all updates</a>)";
if($staff=="yes"){
print"<table>
<form method=post action=\"$GAME_SELF?p=updates&amp;action=Add\">
<tr><td>Name:</td><td><input type=text name=name value=$stat[user]></td></tr>
<tr><td>Email:</td><td><input type=text name=email value=$stat[email]></td></tr>
<tr><td>Title:</td><td><input type=text name=addtitle></td></tr>
<tr><td valign=top>Update:</td><td><textarea name=addupdate rows=5 cols=19></textarea></td></tr>
<tr><td colspan=2 align=center><input type=submit name=action value=\"Add\"></td></tr>
</form>
</table>";
}


} else {
        $usel = mysql_query("select * from updates order by id desc");
$next=0;
        while ($upd = mysql_fetch_array($usel)) {


print "<table border=1 width=\"98%\" cellpadding=3 cellspacing=0><tr><td class=eventit><b>$upd[title]</b> by $upd[starter]  ";


        print "</td></tr>";
        print "<tr><td class=event>\"$upd[updates]\"</td></tr></table><br>";



        }

}




if ($action == Add) {
        if (empty ($addtitle) || empty ($addupdate)) {
                print "Duh... fill out all fields.";
                include("gamefooter.php");
                exit;
        }


$name = str_replace("'","&#39;",$name);
$email = str_replace("'","&#39;",$email);
$addupdate = str_replace("'","&#39;",$addupdate);
$addtitle = str_replace("'","&#39;",$addtitle);

$addupdate = str_replace("
","<BR>",$addupdate);

$addupdate = str_replace("'","&#39;",$addupdate);

$addupdate = str_replace("(C)","&#169;",$addupdate);
$addupdate = str_replace("(c)","&#169;",$addupdate);
$addupdate = str_replace("  "," &nbsp;",$addupdate);

$today = getdate();
$date="$today[mday].$today[mon].$today[year]";

        mysql_query("insert into updates (`starter`, `title`, `updates`, `stamp`) values('<a href=\"mailto:$email\">$name</a>','$addtitle','$addupdate','$date')") or print("<br>Could not add updates.");
        print "Update added.<meta http-equiv='refresh' content='2; url=$GAME_SELF'>";


}

?>
</center>