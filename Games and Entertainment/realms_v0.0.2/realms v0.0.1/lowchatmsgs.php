<HEAD>
<?php
include("mysql.php");
$p=chat;

$username=$_COOKIE['username'];
$pass=$_COOKIE['pass'];
if($md5pass==1){
$pass=md5($pass);
}
$stat=mysql_fetch_array(mysql_query("select * from users where username='$username' and password='$pass'"));
if($stat[id]<1){
$stat=mysql_fetch_array(mysql_query("select * from users where username='guest' and password='guest'"));
}

$ctime = time();






//if($dark!=1){
include("style.php");
//}else{
//include("dstyle.php");
//}

if($stat[world]==jail){
print"You can sort of hear chatter, but there are no visitors for you";
die();
}

?>
</HEAD>
<BODY>
<?php

if ($action == chat) {
        if ($msg) {

        if($stat[rank]=="Admin"&&$msg=="/heal"){

        $psel = mysql_query("select * from users where page='chat'");
$ctime = time();

while ($pl = mysql_fetch_array($psel)) {
        $span = ($ctime - $pl[lpv]);
        if ($span <= 600) {
mysql_query("update users set hp=max_hp where id='$pl[id]'");
}
 }
 $hour = strftime("%H");
 $minute = strftime("%M");
 $stamp = "$hour:$minute";
          mysql_query("insert into chat (user, chat, timem, stamp) values('<font class=admin>Event</font>', 'All users in chat have been healed </b> </u> </i> </s> </font> </a>', '$ctime', '$stamp')");

         }else{
                if ($stat[rank] == Admin) {
                        $starter = "$stat[user]";
                } else {
                        $starter = "$stat[user]";
                }


$msg = str_replace("'","&#39;",$msg);
if($stat[rank]!=Admin){
$msg = str_replace("<","&#139;",$msg);
$msg = str_replace(">","&#155;",$msg);
}
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

   $hour = strftime("%H");
 $minute = strftime("%M");
 $stamp = "$hour:$minute";
                mysql_query("insert into chat (user, chat, timem, stamp) values('$starter', '$msg </b> </u> </i> </s> </font> </a>', '$ctime', '$stamp')");
        }
//exit;

}
print"<META HTTP-EQUIV=Refresh CONTENT=\"0;url=lowchatmsgs.php\">";

}else{

$backtime=$ctime-600;

$csel = mysql_query("select * from chat where timem>$backtime order by timem desc limit 5");

while ($chat = mysql_fetch_array($csel)) {

                if ($stat[rank] == Admin) {
                        $starter = "$stat[user]";
                } else {
                        $starter = "$stat[user]";
                }

if($chat[user]==$starter){
$go=1;
}
}


print"<META HTTP-EQUIV=Refresh CONTENT=\"25;url=lowchatmsgs.php\">";



}


$tfh=24*60*60;
$backtime=$ctime-$tfh;

$gbacktime=$ctime-600;
$csel = mysql_query("select * from chat where timem>$backtime order by timem desc limit 5");

while ($chat = mysql_fetch_array($csel)) {
       if($chat[timem]>$gbacktime){
            print "[$chat[stamp]] <a href=\"$PHP_SELF?p=view&view=2\" target=_parent><b>$chat[user]</b></a>: $chat[chat]<br>";
        }else{
           print "[$chat[stamp]] <a href=\"$PHP_SELF?p=view&view=2\" target=_parent><i>$chat[user]</a>: $chat[chat]</i><br>";
        }
}



$tfh=24*60*60;
$dbacktime=$ctime-$tfh;

$delcsel = mysql_query("select * from chat where timem<$dbacktime order by timem desc");

while ($dchat = mysql_fetch_array($delcsel)) {
mysql_query("DELETE FROM `chat` WHERE `id` = '$dchat[id]'");
}




?>

</body>
</html>