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

checkuser();
?>
<HEAD>
<META http-equiv="pragma" CONTENT="no-cache"> 
<META HTTP-EQUIV="REFRESH" content="10;URL=admin_users.php">
<META HTTP-EQUIV="EXPIRES" CONTENT="Sat, 01 Jan 2001 00:00:00 GMT">
<link title="new" rel="stylesheet" href="style.css" type="text/css">
</HEAD>
<?
if($myid == ""){
  // get the if of this user.. 
  $query = "SELECT * FROM livehelp_users WHERE username='$username'";	
  $people = $mydatabase->select($query);
  $people = $people[0];
  $myid = $people[user_id];
  $channel = $people[onchannel];
}

// invite
if($action == "invite"){
  $query = "SELECT * FROM livehelp_operator_channels ORDER BY id DESC LIMIT 1";	
  $color = $mydatabase->select($query);    
  $color = $color[0];
  switch($color[bgcolor]){
  	case "": 
  	   $bgcolor = "000000";
  	break;
  	case "000000": 
  	   $bgcolor = "6A0C00";
  	break;
  	case "6A0C00": 
  	   $bgcolor = "64006A";
  	break;
  	case "64006A": 
  	   $bgcolor = "00256A";
  	break;
  	case "00256A": 
  	   $bgcolor = "006A52";
  	break;
  	case "006A52": 
  	   $bgcolor = "026A00";
  	break;  
  	case "026A00": 
  	   $bgcolor = "6A4800";
  	break;  
  	case "6A4800": 
  	   $bgcolor = "000000";
  	break;  
       default:
         $bgcolor = "000000";
        break;	  	  		  	  	  	  	  	
  }
  $query = "INSERT INTO livehelp_channels (user_id,statusof,startdate) VALUES ('$saidto','P','$timeof')";
  $whatchannel = $mydatabase->insert($query);

if($dbtype == "txt-db-api.php"){
 $query = "SELECT * FROM livehelp_channels ORDER BY id DESC LIMIT 1";
 $channel_a = $mydatabase->select($query);
 $channel_a = $channel_a[0];
 $whatchannel = $channel_a[id];
}

  $query = "UPDATE livehelp_users set onchannel='$whatchannel',isnamed='Y' WHERE user_id='$saidto' ";
  $mydatabase->sql_query($query);
  $query = "DELETE FROM livehelp_operator_channels WHERE user_id='$myid' AND userid='$saidto'";	
  $mydatabase->sql_query($query);
  $query = "INSERT INTO livehelp_operator_channels (user_id,channel,userid,bgcolor) VALUES ('$myid','$whatchannel','$saidto','$bgcolor')";	
  $mydatabase->sql_query($query);
  $timeof = date("YmdHis");
  ?>
  <SCRIPT>
  function invite(){
    window.parent.bottomof.flag_imtyping = true;
    url = 'admin_chat_bot.php?selectedwho=' + <?= $saidto ?>;
    window.open(url, 'chat5405087', 'width=472,height=320,menubar=no,scrollbars=0,resizable=1');
  }
  setTimeout('invite()', 99);
  </SCRIPT>
  <?
}

?>
<SCRIPT>
function seepages(id){
  url = 'details.php?id=' + id;
  window.open(url, 'chat54050872', 'width=472,height=320,menubar=no,scrollbars=0,resizable=1');
}
</SCRIPT>
<?
// activiate chatting with the user.. 
if($action == "activiate"){
  $query = "SELECT * FROM livehelp_operator_channels ORDER BY id DESC LIMIT 1";	
  $color = $mydatabase->select($query);    
  $color = $color[0];
  switch($color[bgcolor]){
  	case "": 
  	   $bgcolor = "000000";
  	break;
  	case "000000": 
  	   $bgcolor = "6A0C00";
  	break;
  	case "6A0C00": 
  	   $bgcolor = "64006A";
  	break;
  	case "64006A": 
  	   $bgcolor = "00256A";
  	break;
  	case "00256A": 
  	   $bgcolor = "006A52";
  	break;
  	case "006A52": 
  	   $bgcolor = "026A00";
  	break;  
  	case "026A00": 
  	   $bgcolor = "6A4800";
  	break;  
  	case "6A4800": 
  	   $bgcolor = "000000";
  	break;  
       default:
         $bgcolor = "000000";
        break;	  	  		  	  	  	  	  	
  }
           
  $query = "DELETE FROM livehelp_operator_channels WHERE user_id='$myid' AND userid='$who'";	
  $mydatabase->sql_query($query);  
  $query = "INSERT INTO livehelp_operator_channels (user_id,channel,userid,bgcolor) VALUES ('$myid','$whatchannel','$who','$bgcolor')";	
  $mydatabase->sql_query($query);
  $timeof = date("YmdHis");
  $query = "INSERT INTO livehelp_messages (saidto,saidfrom,message,channel,timeof) VALUES ('$who','$myid','<font color=007700>Enters Chat</font>','$whatchannel','$timeof')";	
  $mydatabase->sql_query($query);
}

if($action == "stop"){
  $query = "UPDATE livehelp_users set status='stopped' WHERE user_id='$who'";	
  $mydatabase->sql_query($query);
  $action = "leave";
}

// leave the channel or user..
if($action == "leave"){
  $query = "DELETE FROM livehelp_operator_channels WHERE user_id='$myid' AND channel='$whatchannel' AND userid='$who' ";	
  $mydatabase->sql_query($query);
}

$html_head = "
<SCRIPT>
function refreshit(){
 window.location.replace(\"admin_users.php\");
}	
  setTimeout(\"refreshit();\",99920);

 
";

if($action == "activiate"){
  $html_head .= " window.parent.bottomof.location.replace(\"admin_chat_bot.php\");";
}

$html_head .= "

function tellme(){
  window.parent.rooms.focus();
  window.parent.rooms.firstSound.play(); 
}

function seepages(id){
  url = 'details.php?id=' + id;
  window.open(url, 'chat54050872', 'width=472,height=320,menubar=no,scrollbars=1,resizable=1');
}
</SCRIPT>
";

print $html_head;

?>
<script language="JavaScript">
<!--

ns4 = (document.layers)? true:false
ie4 = (document.all)? true:false

bluecount = 0
redcount = 0

function layerWrite(id,nestref,text) {
	if (ns4) {
		var lyr = (nestref)? eval('document.'+nestref+'.document.'+id+'.document') : document.layers[id].document
		lyr.open()
		lyr.write(text)
		lyr.close()
	}
	else if (ie4) document.all[id].innerHTML = text
}

//-->
</SCRIPT>
</HEAD>

<BODY BGCOLOR="#FFFFEE">
<center>
<table bgcolor=FFFFEE cellpadding=0 cellspacing=0 border=0 width=280>
<tr>
<td bgcolor=000000><img src=im/blank.gif width=1 height=1></td>
<td bgcolor=000000><img src=im/blank.gif width=278 height=1></td>
<td bgcolor=000000><img src=im/blank.gif width=1 height=1></td>
</tr>
<tr>
<td bgcolor=000000><img src=im/blank.gif width=1 height=1></td>
<td>

<table bgcolor=EEEEEE width=100%><tr><td  bgcolor=EEEEEE align=center>
<?
$query = "SELECT * FROM livehelp_config";
$data = $mydatabase->select($query);
$data = $data[0];
$offset = $data[offset];
if($offset == ""){ $offset = 0; }
$when = mktime ( date("H")+$offset, date("i"), date("s"), date("m") , date("d"), date("Y") );
?>
<?= date("F j, Y, g:i a",$when); ?>
</td></tr></table>
</td>
<td bgcolor=000000><img src=im/blank.gif width=1 height=1></td>
</tr>
<tr>
<td bgcolor=000000><img src=im/blank.gif width=1 height=1></td>
<td bgcolor=000000><img src=im/blank.gif width=278 height=1></td>
<td bgcolor=000000><img src=im/blank.gif width=1 height=1></td>
</tr>
<tr>
<td bgcolor=000000><img src=im/blank.gif width=1 height=1></td>
<td>
<?
//while(1 == 1){
flush();
$html = gethtml();
print $html;	
?>


</td>
<td bgcolor=000000><img src=im/blank.gif width=1 height=1></td>
</tr>
<tr>
<td bgcolor=000000><img src=im/blank.gif width=1 height=1></td>
<td bgcolor=000000><img src=im/blank.gif width=278 height=1></td>
<td bgcolor=000000><img src=im/blank.gif width=1 height=1></td>
</tr>
</table>
</center>
<br><b>Online Operators:</b><br>
  <?
  $query = "SELECT * FROM livehelp_users WHERE isonline='Y' and isoperator='Y'";
  $Operators = $mydatabase->select($query);
  for($i=0;$i< count($Operators); $i++){
     $Operators_row = $Operators[$i];
     print "<img src=images/operator.gif width=21 height=20>$Operators_row[username] ";	
  }
?>

<?
$mydatabase->close_connect();
?>
<SCRIPT>
 <?= $onload ?>
</SCRIPT>
<?
//}
function gethtml(){
  global $mydatabase,$username,$onload,$myid;
  
  $onload = "";
  
  $timeof = date("YmdHis");
  // update operators timestamp .
  $sql = "UPDATE livehelp_users set lastaction='$timeof' WHERE username='$username' ";
  $mydatabase->sql_query($sql);
  $html = "<table width=100% bgcolor=FFFFCC><tr><td><b>Chatting Users:</b></td></tr></table>";
  $html .= "</td><td bgcolor=000000><img src=im/blank.gif width=1 height=1></td></tr> <tr><td bgcolor=000000><img src=im/blank.gif width=1 height=1></td><td>";
  $html .= "<table width=100%>";

  $prev = mktime ( date("H"), date("i")-3, date("s"), date("m"), date("d"), date("Y") );
  $oldtime = date("YmdHis",$prev);

  $query = "SELECT * FROM livehelp_users WHERE status='chat' and isoperator!='Y'";
  $visitors = $mydatabase->select($query);
  if(count($visitors) == 0){
   	$html .= "<tr bgcolor=FFFFFF><td>no one is chatting...</td></tr>";
  }
  for($i=0;$i< count($visitors); $i++){
    $visitor = $visitors[$i];
    // see if this guy has left the building.. 
    if($visitor[lastaction] < $oldtime){
      $query = "UPDATE livehelp_users set status='Stopped' WHERE user_id='$visitor[user_id]'";
      $mydatabase->sql_query($query);	
    }
    // see if anyone is chatting with this person. 
    $query = "SELECT * FROM livehelp_operator_channels WHERE channel='$visitor[onchannel]'";
    $counting = $mydatabase->select($query);
    if(count($counting) == 0){
     $chatting = "<img src=images/needaction.gif width=21 height=20 border=0>";  
     $actionlink = "[<a href=admin_users.php?action=activiate&who=$visitor[user_id]&whatchannel=$visitor[onchannel]>Activate</a>]";
     $onload = " setTimeout(\"tellme();\",500); ";
    } else {
      // see if we are chatting with them
      $query = "SELECT * FROM livehelp_operator_channels WHERE user_id='$myid' And channel='$visitor[onchannel]'";
      $counting = $mydatabase->select($query);
      if(count($counting) == 0){
         $chatting = "<img src=images/noton.gif width=19 height=18 border=0>";
         $actionlink = "[<a href=admin_users.php?action=activiate&who=$visitor[user_id]&whatchannel=$visitor[onchannel]><font color=007700>Start Chatting</font></a>]";
      } else {
        $chatting = "<img src=images/active.gif width=19 height=18 border=0>";
        $actionlink = "[<a href=admin_users.php?action=leave&who=$visitor[user_id]&whatchannel=$visitor[onchannel]>Leave</a>] [<a href=admin_users.php?action=stop&who=$visitor[user_id]&whatchannel=$visitor[onchannel]><font color=990000>STOP</font></a>]"; 
      }
    } 
  // see if anyone is chatting with this person. 
  $query = "SELECT * FROM livehelp_operator_channels WHERE channel='$visitor[onchannel]'";
  $counting = $mydatabase->select($query);
  if(count($counting) == 0){
    $chatting = "<img src=images/needaction.gif width=19 height=18 border=0>";  
    $actionlink = "[<a href=admin_users.php?action=activiate&who=$visitor[user_id]&whatchannel=$visitor[onchannel]>Answer Call</a>]";
  }
  
 $html .= "<tr bgcolor=FFFFFF><td>$chatting $visitor[username] </a>  $actionlink  | [<a href=javascript:seepages($visitor[user_id])>Details</a>]</td></tr>";
  } 
 $html .= "</table>";
 
 
  // get the count of active visitors in the system right now.
 $timeof = date("YmdHis");
 $prev = mktime ( date("H"), date("i")-2, date("s"), date("m"), date("d"), date("Y") );
 $oldtime = date("YmdHis",$prev);
 $query = "SELECT * FROM livehelp_users WHERE lastaction>'$oldtime' AND status!='chat' AND status!='operator' ORDER by lastaction DESC";
 $visitors = $mydatabase->select($query);
 $onlinenow = count($visitors);
 $html .= "</td>
<td bgcolor=000000><img src=im/blank.gif width=1 height=1></td>
</tr> <tr>
<td bgcolor=000000><img src=im/blank.gif width=1 height=1></td>
<td bgcolor=000000><img src=im/blank.gif width=278 height=1></td>
<td bgcolor=000000><img src=im/blank.gif width=1 height=1></td>
</tr><tr>
<td bgcolor=000000><img src=im/blank.gif width=1 height=1></td>
<td><table width=100% bgcolor=FFFFCC><tr><td><b>Current Visitors: <font color=007700> $onlinenow Online Now</font></b></td></tr></table></td>
<td bgcolor=000000><img src=im/blank.gif width=1 height=1></td>
</tr> <tr>
<td bgcolor=000000><img src=im/blank.gif width=1 height=1></td>
<td>"; 
 $html .= "<table width=100%><tr bgcolor=FFFFEE><td><b>ID</b></td><td colspan=2><b>Options and #pages:</b></td></tr>";
 for($i=0;$i< count($visitors); $i++){
  $visitor = $visitors[$i];
  $query = "SELECT * from livehelp_visit_track WHERE id='$visitor[user_id]'";
  $my_count = $mydatabase->select($query);
  $my_count = count($my_count);
  $html .= "<tr bgcolor=FFFFFF><td><a href=javascript:seepages($visitor[user_id])>$visitor[username]</a></td>";
 if($visitor[status] == "request"){ 
  $html .= "<td><img src=images/invited.gif></td>";
 }  else { 
  $html .= "<td><a href=admin_users.php?action=invite&saidto=$visitor[user_id]>Invite</a></td>";
 } 
  $html .= "<td>$my_count</td></tr>";
 }  
  $html .= "</table>";

return $html;
}
?>
