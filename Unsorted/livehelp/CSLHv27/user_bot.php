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
$timeof = date("YmdHis");

if($identity == ""){
 if($REMOTE_ADDR == ""){
    $REMOTE_ADDR = $HTTP_SERVER_VARS["REMOTE_ADDR"]; 
 }
 if($HTTP_USER_AGENT == ""){
    $HTTP_USER_AGENT = $HTTP_SERVER_VARS["HTTP_USER_AGENT"]; 
 }
 $identity = $REMOTE_ADDR . $HTTP_USER_AGENT . $rand_id;
 $referer = $HTTP_REFERER;
 $identity = ereg_replace(" ","",$identity);
}

  $query = "SELECT * FROM livehelp_users WHERE identity='$identity'";	
  $people = $mydatabase->select($query);
  $people = $people[0];
  $myid = $people[user_id];
  $channel = $people[onchannel];
  $isnamed = $people[isnamed];


if($comment != ""){
   $comment = substr($comment,0,10000);
   $query = "INSERT INTO livehelp_messages (message,channel,timeof,saidfrom,saidto) VALUES ('$comment','$channel','$timeof','$myid','$saidto')";	
   $mydatabase->insert($query);
   $query = "UPDATE livehelp_users set lastaction='$timeof' WHERE user_id='$myid' ";
   $mydatabase->sql_query($query);
} 
?>
<html>
<SCRIPT>
function expandit() {
  window.parent.resizeTo(window.screen.availWidth - 50,      
  window.screen.availHeight - 50); 
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
ns4 = (document.layers)? true:false;
ie4 = (document.all)? true:false;
cscontrol= new Image;
var flag_imtyping = false;

function imtyping(){
  if (document.chatter.comment.value.length > 2){
  if(flag_imtyping == false){
  flag_imtyping = true;
  document.chatter.typing.value="yes";  
  <? if ($show_typing != "N") { ?>
  var u = '<?= $webpath ?>image.php?' + 
					'cmd=startedtyping' + 
					'&channelsplit=' + escape(document.chatter.channel.value) + 
					'&user=' + escape('user') +
					'&fromwho=' + escape(document.chatter.myid.value);
  cscontrol.src = u;  
  <? } ?>
 }
 }
}

</SCRIPT>
<body marginheight=0 marginwidth=0 leftmargin=0 topmargin=0 bgcolor=E5ECF4 onload=init()>
<table cellpadding=0 cellspacing=0 border=0 width=100%>
<tr>
<td width=1% valign=top><img src=images/qmark.gif width=74 height=21 border=0></td>
<td width=99% background=images/bot_bg.gif>
<img src=images/blank.gif width=20 height=2 border=0><br>
<?
// get department information...
if($department!=""){ $where = " WHERE recno=$department "; }
$query = "SELECT * FROM livehelp_departments $where ";
$data_d = $mydatabase->select($query);  
$department_a = $data_d[0];

if( ($department_a[requirename] == "Y") && ($isnamed != "Y")){
?>
<SCRIPT>
function init(){
  // nothing	
}
</SCRIPT><br>
<table width=100% cellpadding=0 cellspacing=0 border=0 background=images/blank.gif><tr><td background=images/blank.gif><font color=999999><?= $lang_notchat ?></font></td></tr></table>
<?
} else {
?>
<FORM action=user_bot.php  name=chatter METHOD=POST name=chatter>
<input type=hidden name=channel value=<?= $channel ?> >
<input type=hidden name=myid value=<?= $myid ?> >
<input type=hidden name=typing value="no">
<b><?= $lang_ask ?></b><input type=text size=40 name=comment ONKEYDOWN="return imtyping()"><input type=submit value=SAY>
</FORM>
<SCRIPT>
function init(){
 if(flag_imtyping == false){
  document.chatter.comment.focus();	
 }
}
</SCRIPT>
<? } ?>
</td>
</tr>
</table>
</body>
</html>
<?
$mydatabase->close_connect();
?>