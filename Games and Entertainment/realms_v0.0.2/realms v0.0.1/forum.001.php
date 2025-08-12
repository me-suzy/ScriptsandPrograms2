<center><?php
function Truncate ($str, $length=60, $trailing='...'){
      // take off chars for the trailing
      $length-=strlen($trailing);
      if (strlen($str) > $length){
         // string exceeded length, truncate and add trailing dots
         return substr($str,0,$length).$trailing;
      }else{
         // string was already short enough, return the string
         $res = $str;
      }
      return $res;
} //http://php.snippetdb.com/view.php?ID=59

$time=time();
$mins=60;
print "<div align=left><table width=\"100%\" border=0 cellspacing=2 cellpadding=0><tr><td>";
$topic2sel=mysql_query("select * from forumtopics");
while($topic2=mysql_fetch_array($topic2sel)){
        $lastpost=mysql_fetch_array(mysql_query("select * from forumposts where topic='$topic2[id]' and board='$topic2[board]' order by id desc limit 1"));
        mysql_query("update forumtopics set lastpost='$lastpost[id]' where id='$topic2[id]'");
        mysql_query("update forumtopics set lastpostid='$lastpost[id]' where id='$topic2[id]'");
}
if($thisboard){
        $board=mysql_fetch_array(mysql_query("select * from forumboards where id='$thisboard'"));
        if(empty($board[name])){
                print "Missing things";
                include("gamefooter.php");
                exit;
        }
}
if($t){
        $topic=mysql_fetch_array(mysql_query("select * from forumtopics where id='$t'"));
        if(empty($topic[name])){
                print "Missing things";
                include("gamefooter.php");
                exit;
        }
}
if($reply==yes){
        $msg=$_POST['messagebody'];
                $msg = str_replace("'","&#39;",$msg);
                                $msg=ucfirst($msg);
        if(empty($topic[name]) || empty($board[name]) || empty($msg)){
                print "Missing things";
                include("gamefooter.php");
                exit;
        }
        $type=Reply;
        mysql_query("delete from forumread where post='$topic[id]'");





        mysql_query("INSERT INTO `forumposts` (`poster` , `body` , `topic`, `board` , `type` , `time` , `timenum`) VALUES ('$user[username]' , '$msg' , '$topic[id]' , '$board[id]' , '$type' , '$time' , '$timenum')");

        $numposts=mysql_num_rows(mysql_query("select * from forumposts where poster='$user[username]'"));
        mysql_query("update users set forumposts=$numposts where id='$user[id]'");
        mysql_query("update forumtopics set lastpost='$lastpost[id]' where id='$topic[id]'");
        $lastposttime=time();
        mysql_query("update forumtopics set lastposttime='$lastposttime' where id='$topic[id]'");
        mysql_query("update forumboards set lastpost='$lastpost[id]' where id='$board[id]'");
        mysql_query("update users set forump=forump+1 where id='$user[id]'");
}
if($act==newtopic){
        print "<b>Posting a New Topic</b><br><form method=post action=$GAME_SELF?p=forum&thisboard=$board[id]&act=newtopic&doit=yes>";
        print "Topic Name: <INPUT TYPE=text NAME=topicname maxlength=70><br>Message Body: <TEXTAREA NAME=messagebody ROWS=20 COLS=60></TEXTAREA>";
        print "<br><INPUT TYPE=submit value=\"Start Topic\"></form>";
        if($doit==yes){
                $topicname=$_POST['topicname'];
                                $topicname=strip_tags($topicname);
                $topicname = str_replace("
" , "" , $topicname);
                $topicname = str_replace("<br>" , "" , $topicname);
                $topicname = str_replace("'" , "&#39;" , $topicname);
                $topicname = str_replace("#" , "" , $topicname);
                                $topicname = ucfirst($topicname);

                $msg=$_POST['messagebody'];
                                $msg = str_replace("'","&#39;",$msg);
                                                                $msg=ucfirst($msg);
                if(empty($board[name])||empty($topicname) || empty($msg)){
                        print "Missing things";
                        include("gamefooter.php");
                        exit;
                }
                $type=Normal;
                $secret=rand(1,100000);
                $lastposttime=time();
                mysql_query("INSERT INTO `forumtopics` (`name` , `type` , `author`, `board`, `lastposttime`) VALUES ('$topicname<!--$secret-->' , '$type' , '$user[username]' , '$board[id]' , '$lastposttime')");

                $type=Post;
                    $topicsd=mysql_fetch_array(mysql_query("select * from forumtopics where name='$topicname<!--$secret-->'"));

                mysql_query("INSERT INTO `forumposts` (`poster` , `body` , `topic`, `board` , `type` , `time` , `timenum`) VALUES ('$user[username]' , '$msg' , '$topicsd[id]' , '$board[id]' , '$type' , '$time' , '$timenum')");


                mysql_query("update forumboards set lastpost='$lastpost[time]' where id='$board[id]'");
                mysql_query("update users set forump=forump+2 where id='$user[id]'");
                print "<br>done<meta http-equiv='refresh' content='0; url=$GAME_SELF?p=forum&thisboard=$board[id]&t=$topicsd[id]'>";
        }
}

if($act==edit&&rank!=guest){
        $message2=mysql_fetch_array(mysql_query("select * from forumposts where id='$m'"));
        if($message2[poster]!=$user[username]&&$staff!="yes"){
                print "Missing things";
        }else{
                $message=mysql_fetch_array(mysql_query("select * from forumposts where id='$m'"));
                $board=mysql_fetch_array(mysql_query("select * from forumboards where id='$message[board]'"));
                $topic=mysql_fetch_array(mysql_query("select * from forumtopics where id='$message[topic]'"));
                if(!$done){
                        print "<form method=post action=$GAME_SELF?p=forum&act=edit&m=$message[id]&done=yes>";
                        print "<b>Edit your post:</b><br><TEXTAREA NAME=messagebody ROWS=15 COLS=70>$message[body]</TEXTAREA><br>This post is in <b>$message[board] -> $message[topic]</b><br><INPUT TYPE=submit value=\"Edit Post\"></form>";
                }
                if($done==yes){
                        $msg=$_POST['messagebody'];
                                                $msg = str_replace("'","&#39;",$msg);
                        mysql_query("update forumposts set body='$msg' where id='$message[id]'");
                        print "<br><meta http-equiv='refresh' content='0; url=$GAME_SELF?p=forum&thisboard=$board[id]&t=$topic[id]'>";
                }
        }
}

if(!$act){
        if(!$thisboard){
                print "<table width=\"90%\" border=1 cellspacing=0 cellpadding=1><tr><td><center><font size=1>Board</font></center></td><td><center><font size=1>Topics</font></center></td><td><center><font size=1>Replies</font></center></td><td><center><font size=1>Last post</font></center></td></tr>";

                if($staff=="yes"){
                $boardsel=mysql_query("select * from forumboards order by id");
                }else{
                $boardsel=mysql_query("select * from forumboards where admin='0' order by id");
                }





                while ($board=mysql_fetch_array($boardsel)){

                                if($stat[clan]>0){
                                $clancheck = mysql_fetch_array(mysql_query("select * from forumboards where clan='$stat[clan]' limit 1"));
                                if(!$clancheck){
                                $claned=mysql_fetch_array(mysql_query("select * from clans where id=$stat[clan]"));
                                mysql_query("INSERT INTO `forumboards` (`name` , `descript` , `clan` ) VALUES ('$claned[name]', 'talk about stuff related to $claned[name]', '$claned[id]')");
                               // print "$claned[name]'s forum made<BR>";
                                }
                                }

                                if($board[clan]>0&&$stat[clan]==$board[clan]){
                                $clanboard="yes";
                                }else{
                                $clanboard="no";
                                }

                                if($staff=="yes"){
                                $clanboard="yes";
                                }


                         if($board[clan]==0||$clanboard=="yes"){

                        $numoftopics=mysql_num_rows(mysql_query("select * from forumtopics where board='$board[id]'"));
                        $numofmessages=mysql_num_rows(mysql_query("select * from forumposts where board='$board[id]'"));
                        $numofmessages=$numofmessages-$numoftopics;

                        $lastpostb=mysql_fetch_array(mysql_query("select * from forumposts where `board`='$board[id]' order by time desc limit 1"));
$lastposted=mysql_fetch_array(mysql_query("select * from forumtopics where `id`='$lastpostb[topic]'"));

$newreadcheck=mysql_num_rows(mysql_query("select * from `forumread` where `user`='$user[id]' and `post`='$lastposted[id]'"));


                        print "<tr><td width=\"60%\"><a href=\"$GAME_SELF?p=forum&thisboard=$board[id]\"><b><font size=3>";
$oldchecktime = time();
$oldcheckdays = 10*24*60*60;
$oldcheck = $oldchecktime - $oldcheckdays;
if($newreadcheck<1 && $lastposted[lastposttime] > $oldcheck){
print "<img src=\"img/new.png\">";
}
if($board[admin]==1){
        print "<b><font class=stats>Staff:</font></b> ";
}

if($board[clan]>0){
        print "<b><font class=stats>Clan:</font></b> ";
}


                        print "$board[name]</font></b></a><br><font size=1>$board[descript]</font></div></td><td width=\"5%\"><font size=3>$numoftopics</font></td><td width=\"5%\"><font size=3>$numofmessages</font></td><td width=\"30%\"><font size=1>";


print "by $lastpostb[poster] in <a href=\"$GAME_SELF?p=forum&thisboard=$board[id]&t=$lastposted[id]\">$lastposted[name]</a> ";
$yayb="0";
$yaya="0";
$yayd="0";
$ctime = time();
$yay = $ctime-$lastpostb[time];
$yayb = $yay/$mins;
$yayb = floor($yayb);

while($yayb>=60){
$yayb=$yayb-60;
$yaya=$yaya+1;
}

while($yaya>=24){
$yaya=$yaya-24;
$yayd=$yayd+1;
}


if($yayd>0){
print "$yayd days and $yaya hours ago.<br>";
}elseif($yaya>0){
print "$yaya hours and $yayb minutes ago.<br>";
}elseif($yayb>0){
print "$yayb minutes ago.<br>";
}else{
print "Less than a minute ago.<br>";
}

                        print "</font></td></tr>";
                $clanboard="no";

                }

}
                print "</table>";
        }


        if($thisboard){
                $board=mysql_fetch_array(mysql_query("select * from forumboards where id='$thisboard'"));
                if(empty($board[name])){
                        print "missing things";
                        include("gamefooter.php");
                        exit;
                }
                if($board[admin]==1&&$staff!="yes"){
                        print "admins only";
                        include("gamefooter.php");
                        exit;
                }
                if($board[clan]>0&&$stat[clan]!=$board[clan]&&$staff!="yes"){
                        print "not your clan";
                        include("gamefooter.php");
                        exit;
                }
                                print "<div align=left>";
                print "<a href=\"$GAME_SELF?p=forum\"><b><font size=2>Forum Home</font></b></a> -> <a href=\"$GAME_SELF?p=forum&thisboard=$board[id]\"><b><font size=2>$board[name]</font></b></a>";
                if($t){
                        print " -> <a href=\"$GAME_SELF?p=forum&thisboard=$board[id]&t=$topic[id]\"><b><font size=2>$topic[name]</font></b></a>";
                }
                print "<br>";
                print "<a href=\"$GAME_SELF?p=forum&thisboard=$board[id]&act=newtopic\">New Topic</a> <br>";
                if(!$t&&!$act){
                        print "<table width=\"90%\" border=1 cellspacing=0 cellpadding=2><tr><td><font size=1>topic</font></td><td><font size=1>replies</font></td><td><font size=1>starter</font></td><td><font size=1>last post</font></td></tr>";

                        $topicsel=mysql_query("select * from forumtopics where board='$board[id]' order by lastpostid desc");
                        while($topic=mysql_fetch_array($topicsel)){
                                $topic2=mysql_fetch_array(mysql_query("select * from forumtopics where board='$board[id]' and id='$topic[id]'"));
                                $numofreplies=mysql_num_rows(mysql_query("select * from forumposts where topic='$topic2[id]' and board='$topic2[board]' and type='Reply'"));
                                                                $firstmessage=mysql_fetch_array(mysql_query("select * from forumposts where topic='$topic[id]' and board='$topic[board]' order by id asc limit 1"));
                                                                $firstmsg=Truncate($firstmessage[body]);

                                 $postere = mysql_fetch_array(mysql_query("select * from users where username='$topic[author]'"));

                                print "<tr><td><div align=left><a href=\"$GAME_SELF?p=forum&thisboard=$board[id]&t=$topic[id]\"><b><font size=3>";


                                $newreadcheck=mysql_num_rows(mysql_query("select * from `forumread` where `user`='$user[id]' and `post`='$topic[id]'"));
$oldchecktime = time();
$oldcheckdays = 10*24*60*60;
$oldcheck = $oldchecktime - $oldcheckdays;
if($newreadcheck<1 && $topic[lastposttime] > $oldcheck){
print "<img src=\"img/new.png\">";
}

                                print "$topic[name]</font></b></a><br><font size=1>$firstmsg</font></div></td><td><font size=3>$numofreplies</font></td><td><a href=\"$GAME_SELF?p=view&view=$postere[id]\">$topic[author]</a></td><td width=\"18%\"><font size=2>";

$lastpostt=mysql_fetch_array(mysql_query("select * from forumposts where topic='$topic[id]' order by time desc limit 1"));



print "by $lastpostt[poster] ";
$yayb="0";
$yaya="0";
$yayd="0";
$ctime = time();
$yay = $ctime-$lastpostt[time];
$yayb = $yay/$mins;
$yayb = floor($yayb);

while($yayb>=60){
$yayb=$yayb-60;
$yaya=$yaya+1;
}

while($yaya>=24){
$yaya=$yaya-24;
$yayd=$yayd+1;
}


if($yayd>0){
print "$yayd days and $yaya hours ago.<br>";
}elseif($yaya>0){
print "$yaya hours and $yayb minutes ago.<br>";
}elseif($yayb>0){
print "$yayb minutes ago.<br>";
}else{
print "Less than a minute ago.<br>";
}


                                print "</font></td></tr>";

                        }
                        print "</table>";
                }
                if($t){
                        $topic=mysql_fetch_array(mysql_query("select * from forumtopics where id='$t'"));
                        if(empty($topic[name])){
                                print "missing things";
                                include("gamefooter.php");
                                exit;
                        }

                        $messagesel=mysql_query("select * from forumposts where topic='$topic[id]' and board='$topic[board]' order by id asc");
                        while($message=mysql_fetch_array($messagesel)){
                                $poster=mysql_fetch_array(mysql_query("select * from users where username='$message[poster]'"));
                                $msg=$message[body];
if($poster[position]!="Admin"&&$poster[rank]!="Staff"&&$poster[rank]!="Moderator"){
$msg = str_replace("<","&#139;",$msg);
$msg = str_replace(">","&#155;",$msg);
}
                                $msg = str_replace("
" , "<br>" , $msg);

$msg = str_replace("'","&#39;",$msg);


$msg = str_replace("&nbsp;","]No Non Breaking Spaces Please.[",$msg);

$msg = str_replace("(C)","&#169;",$msg);
$msg = str_replace("(c)","&#169;",$msg);
$msg = str_replace("  "," &nbsp;",$msg);
$msg = str_replace("[back]" , "<img border=0 src=img/back.gif>" , $msg);
$msg = str_replace("[bigsmile]" , "<img border=0 src=img/bigsmile.gif>" , $msg);
$msg = str_replace("[cry]" , "<img border=0 src=img/cry.gif>" , $msg);
$msg = str_replace("[forward]" , "<img border=0 src=img/forward.gif>" , $msg);
$msg = str_replace("[frown]" , "<img border=0 src=img/frown.gif>" , $msg);
$msg = str_replace("[frustrated]" , "<img border=0 src=img/frustrated.gif>" , $msg);
$msg = str_replace("[mad]" , "<img border=0 src=img/mad.gif>" , $msg);
$msg = str_replace("[pause]" , "<img border=0 src=img/pause.gif>" , $msg);
$msg = str_replace("[play]" , "<img border=0 src=img/play.gif>" , $msg);
$msg = str_replace("[smile]" , "<img border=0 src=img/smile.gif>" , $msg);
$msg = str_replace("[stop]" , "<img border=0 src=img/stop.gif>" , $msg);
$msg = str_replace("[suprised]" , "<img border=0 src=img/suprised.gif>" , $msg);
$msg = str_replace("[tongue]" , "<img border=0 src=img/tongue.gif>" , $msg);
$msg = str_replace("[b]" , "<b>" , $msg);
$msg = str_replace("[u]" , "<u>" , $msg);
$msg = str_replace("[i]" , "<i>" , $msg);
$msg = str_replace("[s]" , "<s>" , $msg);
$msg = str_replace("[/b]" , "</b>" , $msg);
$msg = str_replace("[/u]" , "</u>" , $msg);
$msg = str_replace("[/i]" , "</i>" , $msg);
$msg = str_replace("[/s]" , "</s>" , $msg);
$msg = str_replace("[hl]" , "<font face=\"courier new\" color=darkblue style=\"background:white\">" , $msg);
$msg = str_replace("[/hl]" , "</font>" , $msg);
$msg = str_replace("[drakahn]" , "<font class=admin>Drakahn</font>" , $msg);
if(strtoupper($msg)==$msg){
        $msg=strtolower($msg);
}
$msg=ucfirst($msg);




 $postered = mysql_fetch_array(mysql_query("select * from users where username='$message[poster]'"));

                                print "<br><table width=\"98%\" border=1 cellspacing=0 cellpadding=1><tr height=1><td height=1 valign=top rowspan=2 width=100 valign=top><a href=\"$GAME_SELF?p=view&view=$postered[id]\"><img src=forumimage.php?id=$poster[id] border=0></a></td><td height=5><div align=right><font size=1>posted ";

$yayb="0";
$yaya="0";
$yayd="0";
$yay = $time-$message[time];
while($yay>=60){
$yay=$yay-60;
$yayb=$yayb+1;
}



while($yayb>=60){
$yayb=$yayb-60;
$yaya=$yaya+1;
}

while($yaya>=24){
$yaya=$yaya-24;
$yayd=$yayd+1;
}
if($yayd>0){
print "$yayd days, $yaya hours and $yayb minutes ago.<br>";
}elseif($yaya>0){
print "$yaya hours and $yayb minutes ago.<br>";
}elseif($yayb>0){
print "$yayb minutes ago.<br>";
}else{
print "Less than a minute ago.<br>";
}
                                print "</font>";
                                if($poster[id]==$user[id]&&$user[username]!=guest){
                                        print " <a href=\"$GAME_SELF?p=forum&act=edit&m=$message[id]\">Edit Post</a>";
                                        $nodbl="yesyo";
                                }
                                if($nodbl!="yesyo"&&$staff=="yes"){
                                        print " <a href=\"$GAME_SELF?p=forum&act=edit&m=$message[id]\">Edit Post</a>";
                                }
                                autolink($msg);
print "</div></td><tr><td valign=top height=210>$msg";
$readcheck=mysql_num_rows(mysql_query("select * from `forumread` where `user`='$user[id]' and `post`='$message[topic]'"));
if($readcheck == "0"){
$rtime=time();
mysql_query("INSERT INTO `forumread` (`post` , `user`, `time`) VALUES ('$message[topic]', '$user[id]', '$rtime')");
}
print "</td></tr></table>";

                        }
                        $lastpost=mysql_fetch_array(mysql_query("select * from forumposts where topic='$topic[id]' and board='$board[id]' order by id desc limit 1"));

                                print "<br><center>Post a reply:<br><form method=post action=$GAME_SELF?p=forum&thisboard=$thisboard&t=$t&reply=yes>";
                                print "<TEXTAREA NAME=messagebody ROWS=10 COLS=70></TEXTAREA><br><INPUT TYPE=submit value=\"Post Reply\"></form>";

                }
        }

        print "<br>";
}
$topicsel=mysql_query("select * from forumtopics");
while($topic=mysql_fetch_array($topicsel)){
        $lastpost=mysql_fetch_array(mysql_query("select * from forumposts where topic='$topic[id]' order by id desc limit 1"));
        mysql_query("update forumtopics set lastpostid='$lastpost[id]' where id='$topic[id]'");
}



print "</tr></td></table></center></div>";