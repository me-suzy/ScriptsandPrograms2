

<?php
if (!$view && !$read) {
$view=inbox;
}

if ($view == inbox) {
        print "<table>";
        print "<tr><td width=75><b><u>From</td><td width=100><b><u>Subject</td><td width=50><b><u>Options</td></tr>";
        $msel = mysql_query("select * from mail where owner=$user[id] order by id desc");
        while ($mail = mysql_fetch_array($msel)) {
                print "<tr><td><a href=$GAME_SELF?p=view&view=$mail[senderid]>$mail[sender]</a></td><td>$mail[subject]</td><td>- <a href=$GAME_SELF?p=mail&read=$mail[id]>Read</a>";
        }
        print "</table>";
        print "<br>[<a href=$GAME_SELF?p=mail&view=inbox&step=clear>Clear Inbox</a>][<a href=$GAME_SELF?p=mail&view=write>Compose</a>]";

        if ($step == clear) {
                print "<br>Mail cleared. (<a href=$GAME_SELF?p=mail&view=inbox>refresh</a>)";
                mysql_query("delete from mail where owner=$user[id]");
        }
}

if ($view == write) {
        print "[<a href=$GAME_SELF?p=mail&view=inbox>Inbox</a>]<br><br>";
        print "<table>";
        print "<form method=post action=$GAME_SELF?p=mail&view=write&step=send>";
print"<tr><td>To (Choose):</td><td><select name=playa>";
                $tsel = mysql_query("select * from users");
                while ($ts = mysql_fetch_array($tsel)) {
                        if ($ts[id] == $to) {
                                print "
<option selected value=$ts[id]>$ts[username]($ts[id])</option> ";
                        } else {
                                print "
<option value=$ts[id]>$ts[id]-$ts[username]</option> ";
                        }
                }
                print "</select></td></tr>";
        print "<tr><td><b>OR</b> To (ID Number):</td><td><input type=text maxlength=50 name=to value=\"$to\"></td></tr>";
        print "<tr><td>Subject:</td><td><input type=text name=subject maxlength=50 value=\"$subject\"></td></tr>";
        print "<tr><td valign=top>Body:</td><td><textarea name=body rows=5 cols=19></textarea></td></tr>";
        print "<tr><td colspan=2 align=center><input type=submit value=Send></td></tr>";
        print "</form></table>";

        if ($step == send) {
if(!$to){
$to=$playa;
}
                if (empty ($to) || empty ($body)) {
                        print "Please fill out all fields.";
                        include("gamefooter.php");
                        exit;
                }
                if (empty ($subject)) {
                        $subject = "None";
                }
                $rec = mysql_fetch_array(mysql_query("select * from users where id=$to"));
                if (empty ($rec[id])) {
                        print "No such player.";
                        include("gamefooter.php");
                        exit;
                }

$body = str_replace("<","&#139;",$body);
$body = str_replace(">","&#155;",$body);

$body = str_replace("'","&#39;",$body);
$subject = str_replace("'","&#39;",$subject);
$body = str_replace("[back]" , "<img src=img/back.gif>" , $body);
$body = str_replace("[bigsmile]" , "<img src=img/bigsmile.gif>" , $body);
$body = str_replace("[cry]" , "<img src=img/cry.gif>" , $body);
$body = str_replace("[forward]" , "<img src=img/forward.gif>" , $body);
$body = str_replace("[frown]" , "<img src=img/frown.gif>" , $body);
$body = str_replace("[frustrated]" , "<img src=img/frustrated.gif>" , $body);
$body = str_replace("[mad]" , "<img src=img/mad.gif>" , $body);
$body = str_replace("[pause]" , "<img src=img/pause.gif>" , $body);
$body = str_replace("[play]" , "<img src=img/play.gif>" , $body);
$body = str_replace("[smile]" , "<img src=img/smile.gif>" , $body);
$body = str_replace("[stop]" , "<img src=img/stop.gif>" , $body);
$body = str_replace("[suprised]" , "<img src=img/suprised.gif>" , $body);
$body = str_replace("[tongue]" , "<img src=img/tongue.gif>" , $body);
$body = str_replace("[b]" , "<b>" , $body);
$body = str_replace("[u]" , "<u>" , $body);
$body = str_replace("[i]" , "<i>" , $body);
$body = str_replace("[s]" , "<s>" , $body);
$body = str_replace("[/b]" , "</b>" , $body);
$body = str_replace("[/u]" , "</u>" , $body);
$body = str_replace("[/i]" , "</i>" , $body);
$body = str_replace("[/s]" , "</s>" , $body);
$body = str_replace("[hl]" , "<font face=\"courier new\" color=darkblue style=\"background:white\">" , $body);
$body = str_replace("[/hl]" , "</font>" , $body);
$subject = str_replace("[back]" , "<img src=img/back.gif>" , $subject);
$subject = str_replace("[bigsmile]" , "<img src=img/bigsmile.gif>" , $subject);
$subject = str_replace("[cry]" , "<img src=img/cry.gif>" , $subject);
$subject = str_replace("[forward]" , "<img src=img/forward.gif>" , $subject);
$subject = str_replace("[frown]" , "<img src=img/frown.gif>" , $subject);
$subject = str_replace("[frustrated]" , "<img src=img/frustrated.gif>" , $subject);
$subject = str_replace("[mad]" , "<img src=img/mad.gif>" , $subject);
$subject = str_replace("[pause]" , "<img src=img/pause.gif>" , $subject);
$subject = str_replace("[play]" , "<img src=img/play.gif>" , $subject);
$subject = str_replace("[smile]" , "<img src=img/smile.gif>" , $subject);
$subject = str_replace("[stop]" , "<img src=img/stop.gif>" , $subject);
$subject = str_replace("[suprised]" , "<img src=img/suprised.gif>" , $subject);
$subject = str_replace("[tongue]" , "<img src=img/tongue.gif>" , $subject);
$subject = str_replace("[b]" , "<b>" , $subject);
$subject = str_replace("[u]" , "<u>" , $subject);
$subject = str_replace("[i]" , "<i>" , $subject);
$subject = str_replace("[s]" , "<s>" , $subject);
$subject = str_replace("[/b]" , "</b>" , $subject);
$subject = str_replace("[/u]" , "</u>" , $subject);
$subject = str_replace("[/i]" , "</i>" , $subject);
$subject = str_replace("[/s]" , "</s>" , $subject);
$subject = str_replace("[hl]" , "<font face=\"courier new\" color=darkblue style=\"background:white\">" , $subject);
$subject = str_replace("[/hl]" , "</font>" , $subject);



$body = str_replace("'","&#39;",$body);

$body = str_replace("(C)","&#169;",$body);
$body = str_replace("(c)","&#169;",$body);
$body = str_replace("  "," &nbsp;",$body);

$body = str_replace("
","<BR>",$body);




                mysql_query("insert into mail (sender,senderid,owner,subject,body) values('$user[username]','$user[id]',$to,'$subject','$body')") or print("<br>Could not send mail.");
                mysql_query("insert into log (owner, log) values($to, '<b>$user[username]</b> has sent you a message.')") or print("<br>Could not add to log.");
                print "You sent mail to $rec[username].";
        }
}
if ($view==delete&&$id) {
        $mail = mysql_fetch_array(mysql_query("select * from mail where id=$id"));
        if (empty ($mail[id])) {
                print "No such mail.";
                include("gamefooter.php");
                exit;
        }
        if ($mail[owner] != $user[id]) {
                print "That's not your mail.";
                include("gamefooter.php");
                exit;
        }
        mysql_query("delete from mail where owner=$user[id] and id=$id");
print"mail deleted";
print"<meta http-equiv='refresh' content='0; url=$GAME_SELF?p=mail'>";

}

if ($read) {
        $mail = mysql_fetch_array(mysql_query("select * from mail where id=$read"));
        if (empty ($mail[id])) {
                print "No such mail.";
                include("gamefooter.php");
                exit;
        }
        if ($mail[owner] != $user[id]) {
                print "That's not your mail.";
                include("gamefooter.php");
                exit;
        }
        mysql_query("update mail set unread='T' where id=$mail[id]");
$remail = str_replace("<img src=img/back.gif>" , "[back]" , $mail[subject]);
$remail = str_replace("<img src=img/bigsmile.gif>" , "[bigsmile]" , $remail);
$remail = str_replace("<img src=img/cry.gif>" , "[cry]" , $remail);
$remail = str_replace("<img src=img/forward.gif>" , "[forward]" , $remail);
$remail = str_replace("<img src=img/frown.gif>" , "[frown]" , $remail);
$remail = str_replace("<img src=img/frustrated.gif>" , "[frustrated]" , $remail);
$remail = str_replace("<img src=img/mad.gif>" , "[mad]" , $remail);
$remail = str_replace("<img src=img/pause.gif>" , "[pause]" , $remail);
$remail = str_replace("<img src=img/play.gif>" , "[play]" , $remail);
$remail = str_replace("<img src=img/smile.gif>" , "[smile]" , $remail);
$remail = str_replace("<img src=img/stop.gif>" , "[stop]" , $remail);
$remail = str_replace("<img src=img/suprised.gif>" , "[suprised]" , $remail);
$remail = str_replace("<img src=img/tongue.gif>" , "[tongue]" , $remail);
$remail = str_replace("<font face=\"courier new\" color=darkblue style=\"background:white\">" , "[hl]" , $remail);
$remail = str_replace("&#39;","'",$remail);
$remail = str_replace(" " , "&nbsp;" , $remail);
$to=$mail[senderid];
        print "$remail:<br><b><a href=$GAME_SELF?p=view&view=$mail[senderid]>$mail[sender]</a></b> says: ...  \"$mail[body]\".<br><br>[<a href=$GAME_SELF?p=mail&view=write&to=$to&subject=Re:$remail>Reply</a>] - [<a href=$GAME_SELF?p=mail&view=delete&id=$read>Delete</a>] - [<a href=$GAME_SELF?p=mail&view=inbox>Inbox</a>] - [<a href=$GAME_SELF?p=mail&view=write>Compose</a>]";
}
?>