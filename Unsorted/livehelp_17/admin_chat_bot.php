<?
//****************************************************************************************/
//  Crafty Syntax Live Help (CS Live Help)  by Eric Gerdes (http://craftysyntax.com )
//======================================================================================
// NOTICE: Do NOT remove the copyright and/or license information from this file. 
//         doing so will automatically terminate your rights to use this program.
// ------------------------------------------------------------------------------------
// ORIGINAL CODE: 
// ---------------------------------------------------------
// CS LIVE HELP http://www.craftysyntax.com/livehelp/
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


include("config.php");
include("user_access.php");

$timeof = date("YmdHis");

$username = $username;
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
      $notename = ereg_replace("'","",$notename);  
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
//create links out of urls:
$array_string = split(" ",$comment);   
$comment = "";
for($i=0; $i< count($array_string); $i++){
   if (eregi("http://",$array_string[$i])){
   	$comment .= "<a href=$array_string[$i] target=_blank>$array_string[$i]</a> ";
   } else {
   	$comment .= " $array_string[$i] ";
   }
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

if($what == "remove"){
   $query = "DELETE FROM livehelp_quick WHERE id='$quicknote' ";
   $mydatabase->sql_query($query);
}

if( $closewindow == "yes"){
	print "message sent.. <a href=javascript:window.close()>Click here to close this</a>";
print "<script>window.close();</script>";
$mydatabase->close_connect();
exit;	
}
if($what == "Clear"){
?>
<SCRIPT>
window.parent.connection.location.replace("admin_connect.php?urlof=admin_chat.php");
</SCRIPT>
<?	
}

?>
<script language="JavaScript1.2">
<!--
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
     window.focus();
     document.chatter.comment.focus();
<? } ?>
   }
}

function imtyping(){
  microsoftKeyPress();
  if (document.chatter.comment.value.length > 2){
  if(flag_imtyping == false){
  flag_imtyping = true;
  document.chatter.typing.value="yes";  
  var u = '<?= $webpath ?>/image.php?' + 
					'cmd=startedtyping' + 
					'&channelsplit=' + escape(document.chatter.channelsplit.value) + 
					'&user=' + escape('<?=$username?>') +
					'&fromwho=' + escape(document.chatter.user_id.value);
  cscontrol.src = u;  
  }
  } 
}
function forcerefreshit(){
 document.chatter.typing.value="no";
 refreshit();
}
function refreshit(){
 if(document.chatter.typing.value=="no"){
   window.location="admin_chat_bot.php";	
 }
}
function returnsend(){
  document.chatter.alt_what.value= "send";
  document.chatter.submit();	
}
</SCRIPT>

<body bgcolor=E0E8F0 onload=document.chatter.comment.focus();>
<form action=admin_chat_bot.php name=chatter method=post>
<? if($selectedwho != ""){ ?>
<input type=hidden name=closewindow value="yes">
<? } ?>
<input type=hidden name=typing value="no">
<input type=hidden name=user_id value="<?= $myid ?>">
<input type=hidden name=alt_what value="">
<b>Send to:</b> <select name=channelsplit>
<? 
$expires = date("YmdHis");
$expires = $expires - 20000;

$query = "SELECT livehelp_operator_channels.userid,livehelp_users.username,livehelp_users.onchannel,livehelp_users.lastaction FROM livehelp_operator_channels,livehelp_users where livehelp_operator_channels.userid=livehelp_users.user_id AND livehelp_operator_channels.user_id='$myid' ";
$mychannels = $mydatabase->select($query);	
for($i=0; $i<count($mychannels); $i++){ 
 $channel_a = $mychannels[$i];
 $prev = mktime ( date("H"), date("i")-5, date("s"), date("m"), date("d"), date("Y") );
 $oldtime = date("YmdHis",$prev);
 if($channel_a[lastaction] < $oldtime){
   $query = "DELETE FROM livehelp_operator_channels WHERE userid='$channel_a[userid]' ";	
   $mydatabase->sql_query($query);
 }
 ?>
 <option value=<?= $channel_a[onchannel] ?>__<?= $channel_a[userid] ?>
 <? if ($selectedwho == $channel_a[userid]){ print " SELECTED "; } ?>
 <? if ($saidto == $channel_a[userid]){ print " SELECTED "; } ?>
 ><?= $channel_a[username] ?></option>
<? } ?>
</select>
<a href=javascript:forcerefreshit()>refresh</a>
<input type=submit name=what value=send>&nbsp;&nbsp;&nbsp;<input type=submit name=what value=Clear>
<SCRIPT>
function autofill(num){
   if(num == 0){ document.chatter.notename.value=''; document.chatter.comment.value=''; document.chatter.editid.value=''; }
<? 
  $query = "SELECT * FROM livehelp_quick ORDER by name ";
  $result = $mydatabase->select($query);
  for($j=0;$j<count($result);$j++){
   $row = $result[$j];
   $in= $j + 1;
   print "if(num == $in){ document.chatter.notename.value='$row[name]'; document.chatter.comment.value='$row[message]'; document.chatter.editid.value='$row[id]';  }\n";	
 } ?>
}
</SCRIPT><br>
<input type=hidden name=timeof value=<?= $timeof ?> >
<input type=hidden name=editid value="">
<textarea cols=60 rows=2 name=comment ONKEYDOWN="return imtyping()">
</textarea>
<br><b>Quick Note</b>:</td><td><select name=quicknote onchange=autofill(this.selectedIndex)>
<option value=-1 >pick something:</option>
<? 
  $query = "SELECT * FROM livehelp_quick ORDER by name ";
  $result = $mydatabase->select($query);
  for($j=0;$j<count($result);$j++){
   $row = $result[$j];
   $in= $j + 1;
   print "<option value=$row[id]>$row[name]</option>\n";	
} ?>
</select><br>
<b>Note Title:</B><input type=text size=30 name=notename><input type=submit name=what value=save><input type=submit name=what value=remove></td>
</form>
</body>
<?
$mydatabase->close_connect();
?>