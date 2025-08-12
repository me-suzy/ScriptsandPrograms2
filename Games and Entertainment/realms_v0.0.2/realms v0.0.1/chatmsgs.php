<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<title>chat page thing</title>
<?php



include("mysql.php");
$p=chat;

$username=$_COOKIE['username'];
$pass=$_COOKIE['pass'];
if($md5pass==1){
$pass=md5($pass);
}
$user = mysql_fetch_array(mysql_query("select * from users where username='$username' and password='$pass'"));
if($user[id]<1){
$user = mysql_fetch_array(mysql_query("select * from users where username='guest' and password='guest'"));
}
if($user[position]!="Admin"){
ini_set(display_errors,off);
}

print"<style type=\"text/css\">";
if($user[template]!="darkrealmsie"){
?>
body {
        background: #fff;
        margin: 0;
        padding: 0;
        border: 0;
        font-family: "Verdana", sans-serif;
        font-size: 11px;
        color: #36454B;
        }




a:link {
        color: #287A8A;
        text-decoration: underline;
                font-weight: bold;
        }

a:visited {
        color: #5C858A;
        text-decoration: underline;
                font-weight: bold;
        }

a:hover {
        text-decoration: none;
                font-weight: bold;
        }

a:active {
        color: #5C858A;
        text-decoration: none;
        font-weight: bold;
        }
        <?
}else{
?>
body {
        background: #000;
        margin: 0;
        padding: 0;
        border: 0;
        font-family: "Verdana", sans-serif;
        font-size: 11px;
        color: #6A55A1;
        }



p {
        padding: 3px 0px;
        margin: 8px 0px 0px 0px;
        }

a:link {
        color: #6A55A1;
        text-decoration: underline;
                font-weight: bold;
        }

a:visited {
        color: #9A3581;
        text-decoration: underline;
                font-weight: bold;
        }

a:hover {
        text-decoration: none;
                font-weight: bold;
        }

a:active {
        color: #9A85F1;
        text-decoration: none;
        font-weight: bold;
        }
<?
}

print"</style>\n";


$stat = mysql_fetch_array(mysql_query("select * from characters where id='$user[activechar]'"));

$ctime = time();


if($user[position]=="Admin"||$user[position]=="Staff"||$user[position]=="Moderator"){
      $staff="yes";
      ini_set(display_errors,on);
}else{
      $staff="HELL NO FOOL";
}


if($version!="low"){
mysql_query("update users set lastseen=$ctime where id=$stat[id]");
mysql_query("update characters set lastseen=$ctime where id=$stat[id]");
$ip = "$HTTP_SERVER_VARS[REMOTE_ADDR]";
mysql_query("update users set ip='$ip' where id=$stat[id]");
mysql_query("update users set page='chat' where id=$stat[id]");
}

if($action==chat){
        print"<META HTTP-EQUIV=Refresh CONTENT=\"0;url=chatmsgs.php?version=$version\">";
        }else{

$backtime=$ctime-600;

$csel = mysql_query("select * from chat where timem>$backtime order by timem desc");

while ($chat = mysql_fetch_array($csel)) {

                if ($user[position] == Admin) {
                        $starter = "$user[username]";
                } else {
                        $starter = "$user[username]";
                }

if($chat[user]==$starter){
$go=1;
}
}

if($go==1){
print"<META HTTP-EQUIV=Refresh CONTENT=\"10;url=chatmsgs.php?version=$version\">";
}else{
$trek=rand(1,10);
if($trek==1&&$staff!="yes"){
print"anti-bot action, please say something<BR>";
}else{
print"<META HTTP-EQUIV=Refresh CONTENT=\"15;url=chatmsgs.php?version=$version\">";
}
}


}


?>
</HEAD>
<BODY>



<?php


if($stat[world]==jail){
print"You can sort of hear chatter, but there are no visitors for you";
die();
}


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
 $yeses="yes";



          mysql_query("insert into chat (user, chat, timem, stamp) values('<font class=admin>Event</font>', 'All users in chat have been healed', '$ctime', '$stamp')");

         }elseif($stat[rank]=="Admin"&&$msg=="/clear"){
          mysql_query("update `chat` set `show`='no'");
         }else{
                if ($user[position] == Admin) {
                        $starter = "$user[username]";
                } else {
                        $starter = "$user[username]";
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
$msg = "&nbsp; $msg";
   $hour = strftime("%H");
 $minute = strftime("%M");
 $stamp = "$hour:$minute";
 $yeses="yes";
$endemall="";

$bendtest = strpos("$msg", '<b>');
$bendtestb = strpos("$msg", '</b>');
if($bendtest&&!$bendtestb){
$endemall.="</b>";
}

$iendtest = strpos("$msg", '<i>');
$iendtestb = strpos("$msg", '</i>');
if($iendtest&&!$iendtestb){
$endemall.="</i>";
}

$uendtest = strpos("$msg", '<u>');
$uendtestb = strpos("$msg", '</u>');
if($uendtest&&!$uendtestb){
$endemall.="</u>";
}

$sendtest = strpos("$msg", '<s>');
$sendtestb = strpos("$msg", '</s>');
if($sendtest&&!$sendtestb){
$endemall.="</s>";
}

$aendtest = strpos("$msg", '<a');
$aendtestb = strpos("$msg", '</a>');
if($aendtest&&!$aendtestb){
$endemall.="</a>";
}

$fendtest = strpos("$msg", '<font');
$fendtestb = strpos("$msg", '</font>');
if($fendtest&&!$fendtestb){
$endemall.="</font>";
}

                mysql_query("insert into chat (user, chat, timem, stamp) values('$starter', '$msg $endemall', '$ctime', '$stamp')");
        }

exit;

}

}


$tfh=24*60*60;
$backtime=$ctime-$tfh;

$gbacktime=$ctime-600;
if($version=="low"){
$limit=4;
}else{
$limit=50;
}
$csel = mysql_query("select * from `chat` where `timem`>'$backtime' and `show`='yes' order by timem desc limit $limit");

while ($chat = mysql_fetch_array($csel)) {
          $poster=mysql_fetch_array(mysql_query("select * from users where username='$chat[user]'"));
		   if($chat[timem]>$gbacktime){
            print "[$chat[stamp]] <a href=\"index.php?p=view&view=$poster[id]\" target=_parent><b>$chat[user]</b></a>: $chat[chat]<br>
            ";
        }else{
           print "[$chat[stamp]] <a href=\"index.php?p=view&view=$poster[id]\" target=_parent><font style=\"font: oblique lighter 10px Arial, Helvetica, Sans-Serif;\"><i>$chat[user]</a>: $chat[chat]</i></font><br>
           ";
        }
}



$tfh=24*60*60;
$dbacktime=$ctime-$tfh;

$delcsel = mysql_query("select * from `chat` where `timem`<'$dbacktime' and `show`='yes' order by timem desc");

while ($dchat = mysql_fetch_array($delcsel)) {
mysql_query("update `chat` set `show`='no' where `id` = '$dchat[id]'");
}





print"<center><br><br>";

   $hour = strftime("%H");
 $minute = strftime("%M");
 $stamp = "$hour:$minute";

 print"The current server time is $stamp  - ";

$psel = mysql_query("select * from users where page='chat'");
$ctime = time();

while ($pl = mysql_fetch_array($psel)) {
        $span = ($ctime - $pl[lpv]);
        if ($span <= 600) {
                if ($pl[rank] == Admin) {
                        $on = "$on [<font class=admin><A href=\"index.php?p=view&amp;view=$pl[id]\" target=_blank>$pl[user]</a> ($pl[id])] </font>";
                } else {
                        $on = "$on [<A href=\"index.php?p=view&amp;view=$pl[id]\" target=_blank>$pl[user]</a> ($pl[id])] ";
$chatrand=rand(1,100);
if($chatrand==1){
$ratchance=rand(1,2);
if($ratchance==2){
                        $on = "$on <b><u>[+50 credits]</b></u> ";
mysql_query("update characters set cash=cash+50 where id='$pl[id]'");
mysql_query("insert into log (owner, log) values($pl[id],'$stat[name] won you 50 credits in the chatroom lucky draw')");
}
}

if($chatrand==42&&$pl[id]!=$stat[id]){
$ratchance=rand(1,10);
if($ratchance==2){
                        $on = "$on <b><u>[+1000 credits]</b></u> ";
mysql_query("update characters set cash=cash+1000 where id='$pl[id]'");
mysql_query("insert into log (owner, log) values($pl[id],'$stat[name] won you 1000 credits in the chatroom lucky draw')");
}
}


if($chatrand==69&&$pl[id]!=$stat[id]){
$ratchance=rand(1,5);
if($ratchance==2){
                        $on = "$on <b><u>[+500 credits]</b></u> ";
mysql_query("update characters set cash=cash+500 where id='$pl[id]'");
mysql_query("insert into log (owner, log) values($pl[id],'$stat[name] won you 500 credits in the chatroom lucky draw')");
}
}

                }
        }
}
print "$on";



?>


</center>
</body>
</html>