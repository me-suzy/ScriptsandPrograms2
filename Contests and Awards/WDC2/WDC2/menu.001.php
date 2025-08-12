<?php




if($p==contestlist){
print"<b>";
}
print"[<a href=\"$GAME_SELF?p=contestlist\" onmouseover=\"return escape('contest list')\">Contests</a>] ";
if($p==contestlist){
print"</b>";
}


if($p==updates){
print"<b>";
}
print"[<a href=\"$GAME_SELF?p=updates\" onmouseover=\"return escape('Updates')\">Updates</a>] ";
if($p==updates){
print"</b>";
}

$lognum = mysql_num_rows(mysql_query("select * from `log` where `read`='F' and `owner`='$user[id]'"));
$lognumb = mysql_num_rows(mysql_query("select * from `log` where `read`='T' and `owner`='$user[id]'"));
$lognumc = $lognum+$lognumb;
if($lognum>0){
print"<b>";
print"[<a href=\"$GAME_SELF?p=log\" onmouseover=\"return escape('Event Log $lognumc logs, $lognum unread')\">log</a>[$lognumc]] ";
print"</b>";
}elseif($lognumb>0){
if($p==log){
print"<b>";
}
print"[<a href=\"$GAME_SELF?p=log\" onmouseover=\"return escape('Event Log $lognumc logs')\">log</a>[$lognumc]] ";
if($p==log){
print"</b>";
}
}

$ttime=time();
$span=$ttime-600;

$chatnum = mysql_num_rows(mysql_query("select * from users where page='chat' and lastseen >= '$span'"));

if($p==chat){
print"<b>";
}
print"[<a href=\"$GAME_SELF?p=chat\" onmouseover=\"return escape('Chatroom $chatnum chatters')\">Chatroom</a>[$chatnum]] ";
if($p==chat){
print"</b>";
}



$nummail = mysql_num_rows(mysql_query("select * from mail where unread='F' and owner=$user[id]"));
$nummailb = mysql_num_rows(mysql_query("select * from mail where unread='T' and owner=$user[id]"));
$nummailc = $nummailb+$nummail;
if($nummail>0){
print"<b>";
print"[<a href=\"$GAME_SELF?p=mail\" onmouseover=\"return escape('Mail $nummailc messages, $nummail unread')\">Mail</a>[$nummailc]] ";
print"</b>";
}elseif($nummailb>0){
if($p==mail){
print"<b>";
}
print"[<a href=\"$GAME_SELF?p=mail\" onmouseover=\"return escape('Mail $nummailc messages')\">Mail</a>[$nummailc]] ";
if($p==mail){
print"</b>";
}
}






// If you host forums somewhere else use a link like the following, else use the built in forums
print"[<a href=\"http://www.ponju.net/index.php?showforum=43\"  onmouseover=\"return escape('Forum')\" target=_blank>Forums</a>] ";








if($p==options){
print"<b>";
}
print"[<a href=\"$GAME_SELF?p=options\" onmouseover=\"this.T_STICKY=true;this.T_STATIC=true;return escape('";
include("options.001.php");
print"')\">Options</a>] ";
if($p==options){
print"</b>";
}



if($user[username]=="guest"){

print"<BR><B>[<a href=\"http://www.tcgames.net/index.php?p=login\" onmouseover=\"return escape('Login')\">LOGIN</a>] ";

print"[<a href=\"http://www.tcgames.net/index.php?p=login&amp;thing=register\" onmouseover=\"return escape('Register')\">REGISTER</a>]</B> ";

}else{

print"<BR><B>[<a href=\"$GAME_SELF?p=logout\" onmouseover=\"return escape('Logout')\">LOGOUT</a>] ";

}
?>