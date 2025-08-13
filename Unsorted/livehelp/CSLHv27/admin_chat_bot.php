<?
//****************************************************************************************/
//  Crafty Syntax Live Help (CSLH)  by Eric Gerdes (http://craftysyntax.com )
//======================================================================================
// NOTICE: Do NOT remove the copyright and/or license information from this file. 
//         doing so will automatically terminate your rights to use this program.
// ------------------------------------------------------------------------------------
// ORIGINAL CODE: 
// ---------------------------------------------------------
// Crafty Syntax Live Help (CSLH) http://www.craftysyntax.com/livehelp/
// Copyright (C) 2003  Eric Gerdes 
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program in a file named LICENSE.txt .
// if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
// ---------------------------------------------------------  
// MODIFICATIONS: 
// ---------------------------------------------------------  
// [ Programmers who change this code should cause the  ]
// [ modified changes here and the date of any change.  ]
//======================================================================================
//****************************************************************************************/

require("globals.php");
include("config.php");
include("user_access.php");

$timeof = date("YmdHis");

checkuser();

if($myid == ""){
  // get the if of this user.. 
  $query = "SELECT * FROM livehelp_users WHERE username='$username'";	
  $people = $mydatabase->select($query);
  $people = $people[0];
  $myid = $people[user_id];
  $channel = $people[onchannel];
}

$array = split("__",$channelsplit);
$saidto = $array[1]; 
$channel = $array[0];  
if($saidto == ""){ $channel = -1; }

// alternate what for keystroke return 
if($alt_what != ""){ $what = $alt_what;}

if($what == "save"){      
      $comment = addslashes($comment);
      $comment = ereg_replace("\r\n","",$comment);
      $comment = ereg_replace("\n","",$comment);  
   if($editid != ""){ 
      $query = "UPDATE livehelp_quick set name='$notename',message='$comment' WHERE id='$editid'";	
      $messages = $mydatabase->sql_query($query);	
   } else {
      $query = "INSERT INTO livehelp_quick (name,message) VALUES ('$notename','$comment')";	
      $mydatabase->insert($query);
   }
   $what = "";
   $quicknote = "";
}

if($what == "send"){
  
   // check to see if they are active is a chat session. 
   $query = "SELECT * FROM livehelp_users WHERE user_id='$saidto'";
   $check_s = $mydatabase->select($query);
   $check_s = $check_s[0];
   if($check_s[status] != "chat"){
    $query = "UPDATE livehelp_users set status='request' WHERE user_id='$saidto' ";
    $mydatabase->sql_query($query);   	   	
   }
   
    $query = "INSERT INTO livehelp_messages (message,channel,timeof,saidfrom,saidto) VALUES ('$comment','$channel','$timeof','$myid','$saidto')";	
    $mydatabase->insert($query);
    $quicknote ="";
}

?>
<script language="JavaScript1.2">
<!--

function openwindow(url){ 
 window.open(url, 'chat54057', 'width=572,height=320,menubar=no,scrollbars=1,resizable=1');
}
 
function netscapeKeyPress(e) {
     if (e.which == 13)
         returnsend();
}

function microsoftKeyPress() {
  if(ie4){
    if (window.event.keyCode == 13)
         returnsend();
  }
}

if (navigator.appName == 'Netscape') {
    window.captureEvents(Event.KEYPRESS);
    window.onKeyPress = netscapeKeyPress;
}
//--></script>

<SCRIPT>
ns4 = (document.layers)? true:false;
ie4 = (document.all)? true:false;
cscontrol= new Image;
var flag_imtyping = false;

function shouldifocus(){
   if(flag_imtyping == false){
<? if($use_flush != "no"){ ?>
     window.parent.focus();
      window.focus();
     <? if($channelsplit != ""){ ?>    
     setTimeout("document.chatter.comment.focus()",1000);
     <? } ?>

<? } ?>
   }
}

function imtyping(){
  microsoftKeyPress();
  if (document.chatter.comment.value.length > 2){
  if(flag_imtyping == false){
  flag_imtyping = true;
  document.chatter.typing.value="yes";  
  <? if ($show_typing != "N") { ?>
  var u = '<?= $webpath ?>image.php?' + 
					'cmd=startedtyping' + 
					'&channelsplit=' + escape(document.chatter.channelsplit.value) + 
					'&user=' + escape('<?=$username?>') +
					'&fromwho=' + escape(document.chatter.user_id.value);
  cscontrol.src = u; 
   <? } ?>
  }
  } 
}
function forcerefreshit(){
 document.chatter.typing.value="no";
 refreshit();
}
function refreshit(){
 if(document.chatter.typing.value=="no"){
   window.parent.connection.location="admin_chat.php?starttimeof=<?= $starttimeof ?>";
   setTimeout("window.location='admin_chat_bot.php'",200);	
 }
}
function returnsend(){
  document.chatter.alt_what.value= "send";
  document.chatter.submit();	
}
function expandit() {
  window.parent.resizeTo(window.screen.availWidth,      
  window.screen.availHeight); 
  if(IE4){
    // everything should be ok.. 
  } else {    
    setTimeout('refreshit()',900);
  }
}
</SCRIPT>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<body bgcolor=E0E8F0 <? if($channelsplit != ""){ print "onload=document.chatter.comment.focus();"; } ?> >

<!-- Tabs of current Chatting users.-->
<table border="0" cellspacing="0" cellpadding="3" width="100%" class="tabs">
    <tr>
        <td width="8">&nbsp;</td>
<? 
$query = "SELECT livehelp_operator_channels.bgcolor,livehelp_operator_channels.userid,livehelp_users.username,livehelp_users.onchannel,livehelp_users.lastaction FROM livehelp_operator_channels,livehelp_users where livehelp_operator_channels.userid=livehelp_users.user_id AND livehelp_operator_channels.user_id='$myid' ";
$mychannels = $mydatabase->select($query);	
for($i=0; $i<count($mychannels); $i++){ 
 $channel_a = $mychannels[$i];
 $prev = mktime ( date("H"), date("i")-4, date("s"), date("m"), date("d"), date("Y") );
 $oldtime = date("YmdHis",$prev);
 if($channel_a[lastaction] < $oldtime){
   $query = "DELETE FROM livehelp_operator_channels WHERE userid='$channel_a[userid]' ";	
   $mydatabase->sql_query($query);
 }
 ?>
   <? 
    $thischannel = $channel_a[onchannel] . "__" . $channel_a[userid]; 
    if ($channelsplit == $thischannel){ $usercolor = $channel_a[bgcolor]; $myuser = $channel_a[username];$bgcolor = "FFFFFF"; } else { $bgcolor = "DDDDDD"; }
   
   $dakineuser = substr($channel_a[username],0,15);
   ?>
 <td  bgcolor="#<?= $bgcolor ?>" align="center" width="64" nowrap="nowrap" class="tab"><a href="admin_chat_bot.php?channelsplit=<?= $channel_a[onchannel] ?>__<?= $channel_a[userid] ?>"><b><?= ereg_replace(" ","&nbsp;",$dakineuser) ?></b></a></td>
 <td  bgcolor="#<?= $bgcolor ?>" align="right" width="44" nowrap="nowrap" class="tab"><a href=admin_chat.php?offset=2&see=<?= $channel_a[onchannel] ?> target=connection><img src=images/makvis.gif width=33 height=13 border=0></a></td>
 <td width="8">&nbsp;</td>
<? } ?>
    <td width="150" NOWRAP bgcolor=FFFFC0><a href=javascript:expandit()><img src=images/max.gif width=25 height=25 border=0></a>&nbsp;&nbsp;<a href=javascript:forcerefreshit()><img src=images/refresh.gif width=25 height=25 border=0></a>&nbsp;&nbsp;<a href=admin_chat.php?clear=now target=connection><img src=images/clear.gif width=25 height=25 border=0></a>&nbsp;&nbsp;</td>
    </tr>
</table>
<? if($channelsplit == ""){ 

if (count($mychannels) == 0){
?>
<table bgcolor=FFFFFF width=450><tr><td>
<?= $lang_noone_online ?>
</td></tr></table>
<?
} else {
?>
<table bgcolor=FFFFFF width=450><tr><td>
<b><?= $lang_choose ?></b></td></tr></table>

<? } ?>
<form action=admin_chat_bot.php name=chatter method=post>
<input type=hidden name=typing value="no">
<input type=hidden name=user_id value="<?= $myid ?>">
<input type=hidden name=comment value=1 size=1>
</form>
<?
  
 } else { ?>
<table width=100% cellpadding=0 cellspacing=0 border=0><tr><td width=50%>
<table width=100% cellpadding=0 cellspacing=0 border=0 bgcolor=FFFFC0><tr><td><b><?= $lang_message_to ?> <font color=<?= $usercolor ?> size=+1><?= $myuser ?></font>:</b></td></tr></table>
<form action=admin_chat_bot.php name=chatter method=post>
<textarea cols=40 rows=3 name=comment ONKEYDOWN="return imtyping()">
</textarea><input type=submit name=what value=send>
<SCRIPT>
function autofill(num){
   if(num == 0){ document.chatter.comment.value=''; }
<? 
  $query = "SELECT * FROM livehelp_quick Where typeof!='URL' ORDER by typeof,name ";
  $result = $mydatabase->select($query);
  for($j=0;$j<count($result);$j++){
   $row = $result[$j];
   $in= $j + 1;
   if($row[typeof] == "IMAGE"){
   print "if(num == $in){ document.chatter.comment.value='<img src=$row[message] >'; document.chatter.editid.value='$row[id]';  }\n";	    
   } else {
   print "if(num == $in){ document.chatter.comment.value='$row[message] '; document.chatter.editid.value='$row[id]';  }\n";	
   }
 } ?>
}
function autofill_url(num){
   if(num == 0){ document.chatter.comment.value=''; }
<? 
  $query = "SELECT * FROM livehelp_quick Where typeof='URL' ORDER by name ";
  $result = $mydatabase->select($query);
  for($j=0;$j<count($result);$j++){
   $row = $result[$j];
   $in= $j + 1;
   print "if(num == $in){ document.chatter.comment.value='[SCRIPT]openwindow(\'$row[message]\',\'window$row[id]\');[/SCRIPT]';  }\n";	
 } ?>
}
</SCRIPT>
<input type=hidden name=typing value="no">
<input type=hidden name=channelsplit value="<?= $channelsplit ?>" > 
<input type=hidden name=user_id value="<?= $myid ?>">
<input type=hidden name=alt_what value="">
<input type=hidden name=timeof value=<?= $timeof ?> >
<input type=hidden name=editid value="">
</form>
</td>
<td width=50% valign=top>
<table width=100%>
<tr><td colspan=3>
<table width=100% bgcolor=FFFFC0><tr><td><b><?= $lang_addtional ?>:</b></td></tr></table>
<img src=images/blank.gif width=300 height=1><br>
</td></tr>
<tr><td><b>Push Url</b>:</td><td>
<select name=url  onchange=autofill_url(this.selectedIndex)><option value=-1 ><?= $lang_pick ?>:</option>
<? 
  $query = "SELECT * FROM livehelp_quick Where typeof='URL' ORDER by name ";
  $result = $mydatabase->select($query);
  for($j=0;$j<count($result);$j++){
   $row = $result[$j];
   $in= $j + 1;
   print "<option value=$row[id]>$row[name]</option>\n";	
} ?>
</select>
</td><td><a href=javascript:openwindow('edit_quick.php?typeof=URL')>Edit URLS</a></td></tr>
<tr><td colspan=3><b>Images/Quick Note(s):</b><br>
<select name=quicknote onchange=autofill(this.selectedIndex)>
<option value=-1 >pick something:</option>
<? 
  $query = "SELECT * FROM livehelp_quick Where typeof!='URL' ORDER by  typeof,name ";
  $result = $mydatabase->select($query);
  for($j=0;$j<count($result);$j++){
   $row = $result[$j];
   $in= $j + 1;
   print "<option value=$row[id]>$row[name]</option>\n";	
} ?>
</select> <a href=javascript:openwindow('edit_quick.php')>Edit Quick Notes</a> <a href=javascript:openwindow('edit_quick.php?typeof=IMAGE')>Edit Images</a><br>
</td>
</tr></table>
<? } ?><br>
<?
if( ($membernum > 1) && ($membernum < 10000)){
?>
<font color=990000><b>This program has not been Registered yet.</b><a href=registerit.php target=_blank>Click here to register</a><br><br>
<?} ?>
</body>
<?
$mydatabase->close_connect();
?>