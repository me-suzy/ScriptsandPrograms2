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

if($what == "send"){
$array = split("__",$channelsplit);
$saidto = $array[1]; 
$channel = $array[0];  
if($saidto == ""){ $channel = -1; }
    $query = "UPDATE livehelp_users set status='request' WHERE user_id='$saidto' ";
    $mydatabase->sql_query($query);   	   	    
    $query = "INSERT INTO livehelp_messages (message,channel,timeof,saidfrom,saidto) VALUES ('$comment','$channel','$timeof','$myid','$saidto')";	
    $mydatabase->insert($query);    
    if($isnamed == "Y"){
        $query = "UPDATE livehelp_users set isnamed='Y' WHERE user_id='$saidto' ";
      $mydatabase->sql_query($query); 	
    }
print "message sent.. <a href=javascript:window.close()>Click here to close this</a>";
print "<script>window.close();</script>";
$mydatabase->close_connect();
exit;    
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

function returnsend(){
  document.chatter.alt_what.value= "send";
  document.chatter.submit();	
}
</SCRIPT>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<body bgcolor=E0E8F0>
<? 
$expires = date("YmdHis");
$expires = $expires - 20000;
$query = "SELECT * FROM livehelp_users where user_id='$selectedwho' ";
$mychannels = $mydatabase->select($query);	
$channel_a = $mychannels[0];
$thischannel = $channel_a[onchannel] . "__" . $selectedwho; 
$myuser = $channel_a[username];
$bgcolor = "FFFFFF";
$channelsplit = $thischannel;
?>
<table width=100% bgcolor=FFFFC0><tr><td><b>Invite <font color=000000 size=+1><?= $myuser ?></font> for Chat :</b></td></tr></table>
<form action=invite.php name=chatter method=post>
<input type=hidden name=typing value="no">
<input type=hidden name=channelsplit value="<?= $channelsplit ?>" > 
<b>Message:</b>
<input type=hidden name=user_id value="<?= $myid ?>">
<input type=hidden name=alt_what value="">
<SCRIPT>
function autofill(num){
   if(num == 0){ document.chatter.comment.value=''; }
<? 
  $query = "SELECT * FROM livehelp_quick Where typeof!='URL' AND typeof!='IMAGE' ORDER by name ";
  $result = $mydatabase->select($query);
  for($j=0;$j<count($result);$j++){
   $row = $result[$j];
   $in= $j + 1;
   print "if(num == $in){ document.chatter.comment.value='$row[message]'; document.chatter.editid.value='$row[id]';  }\n";	
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

function openwindow(url){ 
 window.open(url, 'chat540', 'width=572,height=320,menubar=no,scrollbars=0,resizable=1');
}

function autofill_image(num){
   if(num == 0){ document.chatter.comment.value=''; }
<? 
  $query = "SELECT * FROM livehelp_quick Where typeof='IMAGE' ORDER by name ";
  $result = $mydatabase->select($query);
  for($j=0;$j<count($result);$j++){
   $row = $result[$j];
   $in= $j + 1;
   print "if(num == $in){ document.chatter.comment.value='<img src=$row[message]>';  }\n";	
 } ?>
}

</SCRIPT><br>
<input type=hidden name=timeof value=<?= $timeof ?> >
<input type=hidden name=editid value="">
<textarea cols=40 rows=3 name=comment >
</textarea><input type=submit name=what value=send>
<br>
<input type=checkbox name=isnamed value=Y>Skip Name, intro message, and start questions for this user
and start chat right away...
</form>
<br>
<table width=100%>
<tr><td colspan=3>
<table width=100% bgcolor=FFFFC0><tr><td><b>Additional Options and Actions:</b></td></tr></table>
<img src=images/blank.gif width=300 height=1><br>
</td></tr>
<tr><td><b>Push Url</b>:</td><td>
<select name=url  onchange=autofill_url(this.selectedIndex)><option value=-1 >pick something:</option>
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
<tr><td><b>Push Image:</b></td><td>
<select name=image onchange=autofill_image(this.selectedIndex)><option value=-1 >pick something:</option>
<? 
  $query = "SELECT * FROM livehelp_quick Where typeof='IMAGE' ORDER by name ";
  $result = $mydatabase->select($query);
  for($j=0;$j<count($result);$j++){
   $row = $result[$j];
   $in= $j + 1;
   print "<option value=$row[id]>$row[name]</option>\n";	
} ?>
</select>
</td><td><a href=javascript:openwindow('edit_quick.php?typeof=IMAGE')>Edit Images</a></td></tr>
<tr><td colspan=3><b>Load Quick Note:</b><br>
<select name=quicknote onchange=autofill(this.selectedIndex)>
<option value=-1 >pick something:</option>
<? 
  $query = "SELECT * FROM livehelp_quick Where typeof!='URL' AND typeof!='IMAGE' ORDER by name ";
  $result = $mydatabase->select($query);
  for($j=0;$j<count($result);$j++){
   $row = $result[$j];
   $in= $j + 1;
   print "<option value=$row[id]>$row[name]</option>\n";	
} ?>
</select> <a href=javascript:openwindow('edit_quick.php')>Edit Quick Notes</a><br>
</td>
</tr></table>
<br>
</body>
<?
$mydatabase->close_connect();
?>