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
  $show_arrival = $people[show_arrival]; 
  $user_alert = $people[user_alert];
  $auto_invite = $people[auto_invite];
} else {

  $sql = "SELECT * FROM livehelp_users WHERE user_id='$myid' ";
  $prefs_1 = $mydatabase->select($sql);
  $prefs = $prefs_1[0];
  $channel = $prefs[onchannel];
  $show_arrival = $prefs[show_arrival]; 
  $user_alert = $prefs[user_alert];  	
	
}

// invite
if($action == "invite"){

  $query = "INSERT INTO livehelp_channels (user_id,statusof,startdate) VALUES ('$saidto','P','$timeof')";
  $whatchannel = $mydatabase->insert($query);

if($dbtype == "txt-db-api.php"){
 $query = "SELECT * FROM livehelp_channels ORDER BY id DESC LIMIT 1";
 $channel_a = $mydatabase->select($query);
 $channel_a = $channel_a[0];
 $whatchannel = $channel_a[id];
}

  $query = "UPDATE livehelp_users set onchannel='$whatchannel' WHERE user_id='$saidto' ";
  $mydatabase->sql_query($query);
  $query = "DELETE FROM livehelp_operator_channels WHERE user_id='$myid' AND userid='$saidto'";	
  $mydatabase->sql_query($query);
  $timeof = date("YmdHis");
  ?>
  <SCRIPT>
  function invite(){
    window.parent.bottomof.flag_imtyping = true;
    url = 'invite.php?selectedwho=' + <?= $saidto ?>;
    window.open(url, 'chat5405087', 'width=572,height=320,menubar=no,scrollbars=0,resizable=1');
  }
  setTimeout('invite()', 99);
  </SCRIPT>
  <?
}

?>
<SCRIPT>
function seepages(id){
  url = 'details.php?id=' + id;
  window.open(url, 'chat54050872', 'width=555,height=320,menubar=no,scrollbars=1,resizable=1');
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

  if ($conferencein != ""){
    $query = "INSERT INTO livehelp_operator_channels (user_id,channel,userid,bgcolor) VALUES ('$who','$whatchannel','$myid','$bgcolor')";	
    $mydatabase->sql_query($query);    
  }
  $timeof = date("YmdHis");
  $query = "INSERT INTO livehelp_messages (saidto,saidfrom,message,channel,timeof) VALUES ('$who','$myid','<font color=007700>Enters Chat</font>','$whatchannel','$timeof')";	
  $mydatabase->sql_query($query);
  $channelsplit = $whatchannel . "__" . $who;
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
  setTimeout(\"refreshit();\",19920);

 
";

if($action == "activiate"){
  $html_head .= " window.parent.bottomof.location.replace(\"admin_chat_bot.php?channelsplit=$channelsplit\");";
}

$html_head .= "

function tellme(){
  window.parent.bottomof.shouldifocus();
";

if($user_alert == "Y"){ 
$html_head .= "  alert(\"User is requesting Chat\"); ";
} 

$html_head .= " }

function doorbell(){   
";
if($show_arrival != "N"){
  $html_head .= " window.parent.bottomof.shouldifocus(); ";
  if($user_alert == "Y"){ 
     $html_head .= "  alert(\"New User is in site.\"); ";	
  }
}
$html_head .= "
}

function seepages(id){
  url = 'details.php?id=' + id;
  window.open(url, 'chat54050872', 'width=590,height=350,menubar=no,scrollbars=1,resizable=1');
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

// The following code is used to support the small popups 

function mouseMove(e) {
        // Set x and y to current pos of mouse
        xmouse = (ns4)? e.pageX : event.x;
        ymouse = (ns4)? e.pageY : event.y+document.body.scrollTop;

        return true;
}

ns4 = (document.layers)? true:false;
ie4 = (document.all)? true:false;
readyone = ready = false; // ready for onmouse overs (are the layers known yet)
document.onmousemove = mouseMove;
if (ns4) document.captureEvents(Event.MOUSEMOVE);
ready = true;

NS4 = (document.layers) ? 1 : 0;
IE4 = (document.all) ? 1 : 0;
W3C = (document.getElementById) ? 1 : 0;	
// W3C stands for the W3C standard, implemented in Mozilla (and Netscape 6) and IE5

// Function show(evt, name)
//	evt is a pointer to the Event object passed when the event occurs
//	name is the ID attribute of the element to show
function show ( evt, name ) {
  if (IE4) {
    evt = window.event;  //is it necessary?
  }

  var currentX,		//mouse position on X axis
      currentY,		//mouse position on X axis
      x,		//layer target position on X axis
      y,		//layer target position on Y axis
      docWidth,		//width of current frame
      docHeight,	//height of current frame
      layerWidth,	//width of popup layer
      layerHeight,	//height of popup layer
      ele;		//points to the popup element

  // First let's initialize our variables
  if ( W3C ) {
    ele = document.getElementById(name);
    currentX = evt.clientX,
    currentY = evt.clientY;
    docWidth = document.width;
    docHeight = document.height;
    layerWidth = ele.style.width;
    layerHeight = ele.style.height;

  } else if ( NS4 ) {
    ele = document.layers[name];
    currentX = evt.pageX,
    currentY = evt.pageY;
    docWidth = document.width;
    docHeight = document.height;
    layerWidth = ele.clip.width;
    layerHeight = ele.clip.height;

  } else {	// meant for IE4
    ele = document.all[name];
    currentX = evt.clientX,
    currentY = evt.clientY;
    docHeight = document.offsetHeight;
    docWidth = document.offsetWidth;
    //var layerWidth = document.all[name].offsetWidth;
    // for some reason, this doesn't seem to work... so set it to 200
    layerWidth = 300;
   // layerHeight = name.offsetHeight;    
  }
//  if(layerHeight < 10){ layerHeight = 100; }

  // Then we calculate the popup element's new position
  if ( ( currentX + 300 ) > 600 ) {
    x = ( currentX - 300 );
  }
  else {
    x = currentX;
  }
  if ( ( currentY + 100 ) >= 400 ) {
     y = ( currentY - 100 - 20 );
  }
  else {
    y = currentY + 20;
  }

  y = ymouse - 140;
  yscroll = (NS4)? window.pageYOffset : document.body.scrollTop;
  diff = ymouse - yscroll;
  if (diff < 140){
  y = ymouse + 20;     
  }

// (for debugging purpose) 
// alert("ymouse " + ymouse + ", currentY " + currentY + "\nlayerWidth " + layerWidth + ", layerHeight " + layerHeight + "\ncurrentX " + currentX + ", currentY " + currentY + "\nx " + x + ", y " + y);
x = 0
  // Finally, we set its position and visibility
  if ( NS4 ) {
    //ele.xpos = parseInt ( x );
    ele.left = parseInt ( x );
    //ele.ypos = parseInt ( y );
    ele.top = parseInt ( y );
    ele.visibility = "show";
  } else {  // IE4 & W3C
    ele.style.left = parseInt ( x );
    ele.style.top = parseInt ( y );
    ele.style.visibility = "visible";
  }
}

function hide ( name ) {
  if (W3C) {
    document.getElementById(name).style.visibility = "hidden";
  } else if (NS4) {
    document.layers[name].visibility = "hide";
  } else {
// [note by Benoit : I think the following isn't useful, since any sub-element should rather
// inherit its attributes from their parent. So i commented it out.]
//    if ( document.all[name].length ) {
//      for ( i = 0; i < document.all[name].length; i++ ) {
//        document.all[name][i].style.visibility = "hidden";
//      }
//    } else {
      document.all[name].style.visibility = "hidden";
//    }
  }
}

//-->

</SCRIPT>
</HEAD>

<BODY BGCOLOR="#FFFFEE">
<center>
<table bgcolor=FFFFEE cellpadding=0 cellspacing=0 border=0 width=280>
<tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=278 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr>
<tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
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
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr>
<tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=278 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr>
<tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
<td>
<?
//while(1 == 1){
flush();
$html = gethtml();
print $html;	
?>


</td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr>
<tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=278 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr>
</table>
</center>
<SCRIPT>
 <?= $onload ?>
</SCRIPT>
 <?= $stuffatbottom ?>
<?
//}
function gethtml(){
  global $show_arrival,$mydatabase,$username,$onload,$myid,$stuffatbottom,$user_alert;
  global $lang_chat_text,$lang_no_chat,$lang_diff,$lang_chat,$lang_stop,$lang_begin,$lang_answer,$lang_details,$lang_visit;
  $onload = "";
  
  $timeof = date("YmdHis");
  // update operators timestamp .
  $sql = "UPDATE livehelp_users set lastaction='$timeof' WHERE username='$username' ";
  $mydatabase->sql_query($sql);
  $html = "<table width=100% bgcolor=FFFFCC><tr><td><b> $lang_chat_text </b></td></tr></table>";
  $html .= "</td><td bgcolor=000000><img src=images/blank.gif width=1 height=1></td></tr> <tr><td bgcolor=000000><img src=images/blank.gif width=1 height=1></td><td>";
  $html .= "<table width=100%>";

  $prev = mktime ( date("H"), date("i")-3, date("s"), date("m"), date("d"), date("Y") );
  $oldtime = date("YmdHis",$prev);

  $query = "SELECT * FROM livehelp_users WHERE status='chat'";
  $visitors = $mydatabase->select($query);
  if(count($visitors) == 0){
   	$html .= "<tr bgcolor=FFFFFF><td>$lang_no_chat</td></tr>";
  }
  for($i=0;$i< count($visitors); $i++){
    $visitor = $visitors[$i];
    // see if this guy has left the building.. 
    if($visitor[lastaction] < $oldtime){
      $query = "UPDATE livehelp_users set status='Stopped' WHERE user_id='$visitor[user_id]'";
      $mydatabase->sql_query($query);	
    }

   // see if this is an operator.
  if($visitor[isoperator] == "N"){
    // see if we are in the same department as this user..
   $query = "SELECT * FROM livehelp_operator_departments WHERE user_id='$myid' AND department='$visitor[department]' ";
   $data_check = $mydatabase->select($query); 
   if(count($data_check) == 0){ 
    // see if we are chatting with them
    $query = "SELECT * FROM livehelp_operator_channels WHERE user_id='$myid' And channel='$visitor[onchannel]'";
        $counting = $mydatabase->select($query);
      if(count($counting) == 0){   
      $chatting = "<img src=images/noton.gif width=19 height=18 border=0>";
      $actionlink = "[<a href=admin_users.php?action=activiate&who=$visitor[user_id]&whatchannel=$visitor[onchannel]>Conference</a>] [<a href=admin_users.php?action=activiate&who=$visitor[user_id]&whatchannel=$visitor[onchannel]><font color=007700>$lang_chat</font></a>]";
   } else {
        $chatting = "<img src=images/active.gif width=19 height=18 border=0>";
        $actionlink = "";
            $query = "SELECT * FROM livehelp_operator_channels WHERE channel='$visitor[onchannel]'";
    $counting = $mydatabase->select($query);
        if(count($counting) >1){
        $actionlink = "[<a href=admin_users.php?action=leave&who=$visitor[user_id]&whatchannel=$visitor[onchannel]>Un-Conference</a>] ";
        }
        $actionlink .= " [<a href=admin_users.php?action=stop&who=$visitor[user_id]&whatchannel=$visitor[onchannel]><font color=990000>$lang_stop</font></a>]"; 
      }
   } else {
    // see if anyone is chatting with this person. 
    $query = "SELECT * FROM livehelp_operator_channels WHERE channel='$visitor[onchannel]'";
    $counting = $mydatabase->select($query);
    if(count($counting) == 0){
     $chatting = "<img src=images/needaction.gif width=21 height=20 border=0>";  
     $actionlink = "[<a href=admin_users.php?action=activiate&who=$visitor[user_id]&whatchannel=$visitor[onchannel]>Activate</a>]";
     $onload = " setTimeout(\"tellme();\",500); ";
     if( ($user_alert == "N") || ($user_alert == "")){ 
       $stuffatbottom = "<EMBED NAME=\"Bach\" SRC=\"sound.wav\" LOOP=FALSE AUTOSTART=TRUE HIDDEN=TRUE MASTERSOUND>";
      }      
    } else {
      // see if we are chatting with them
      $query = "SELECT * FROM livehelp_operator_channels WHERE user_id='$myid' And channel='$visitor[onchannel]'";
      $counting = $mydatabase->select($query);
      if(count($counting) == 0){
         $chatting = "<img src=images/noton.gif width=19 height=18 border=0>";
         $actionlink = "[<a href=admin_users.php?action=activiate&who=$visitor[user_id]&whatchannel=$visitor[onchannel]><font color=007700>$lang_begin</font></a>]";
      } else {
        $chatting = "<img src=images/active.gif width=19 height=18 border=0>";
        $actionlink = "";
        $query = "SELECT * FROM livehelp_operator_channels WHERE channel='$visitor[onchannel]'";
        $counting = $mydatabase->select($query);
        if(count($counting) >1){
        $actionlink = "[<a href=admin_users.php?action=leave&who=$visitor[user_id]&whatchannel=$visitor[onchannel]>Un-Conference</a>] ";
        }
        $actionlink .= "[<a href=admin_users.php?action=stop&who=$visitor[user_id]&whatchannel=$visitor[onchannel]><font color=990000>$lang_stop</font></a>]"; 
      }
    } 
    // see if anyone is chatting with this person. 
    $query = "SELECT * FROM livehelp_operator_channels WHERE channel='$visitor[onchannel]'";
    $counting = $mydatabase->select($query);
    if(count($counting) == 0){
      $chatting = "<img src=images/needaction.gif width=19 height=18 border=0>";  
      $actionlink = "[<a href=admin_users.php?action=activiate&who=$visitor[user_id]&whatchannel=$visitor[onchannel]>$lang_answer</a>]";
    }
   } 
 } else { 
     // see if we are chatting with them
      $query = "SELECT * FROM livehelp_operator_channels WHERE user_id='$myid' And channel='$visitor[onchannel]'";
      $counting = $mydatabase->select($query);
      if(count($counting) == 0){
         $chatting = "<img src=images/operator.gif width=21 height=20 border=0><img src=images/noton.gif width=19 height=18 border=0>";
         $actionlink = "[<a href=admin_users.php?action=activiate&who=$visitor[user_id]&whatchannel=$visitor[onchannel]&conferencein=yes><font color=007700>$lang_begin</font></a>]";
      } else {
        $chatting = "<img src=images/operator.gif width=21 height=20 border=0><img src=images/active.gif width=19 height=18 border=0>";
        $actionlink = "[<a href=admin_users.php?action=leave&who=$visitor[user_id]&whatchannel=$visitor[onchannel]>Hide</a>]"; 
      }
 }
 $html .= "<tr bgcolor=FFFFFF><td>$chatting <a href=javascript:seepages($visitor[user_id]) onMouseOver=\"show(event, 'info-" . $visitor[username] . "'); return true;\" onMouseOut=\"hide('info-" . $visitor[username] . "'); return true;\">$visitor[username]</a>  $actionlink </td></tr>";
$query = "SELECT * from livehelp_users WHERE user_id='$visitor[user_id]'";
$user_info = $mydatabase->select($query);
$user_info = $user_info[0]; 

$query = "SELECT * from livehelp_visit_track WHERE id='$visitor[user_id]' Order by whendone DESC";
$page_trail = $mydatabase->select($query);
$page = $page_trail[0];
  
$query = "SELECT * from livehelp_departments WHERE recno='$user_info[department]'";
$tmp = $mydatabase->select($query);
$nameof = $tmp[0];
$nameof = $nameof[nameof];
  
  
 $DIVS .= "<DIV ID=\"info-" . $visitor[username] . "\" STYLE=\"position: absolute; z-index: 20; visibility: hidden; top: 0px; left: 0px;\">
<TABLE BORDER=\"0\" WIDTH=\"300\"><TR BGCOLOR=\"#000000\"><TD> 
<TABLE BORDER=\"0\" WIDTH=\"100%\" CELLPADDING=0 CELLSPACING=0 BORDER=0><TR><TD width=1 BGCOLOR=#FFFFCC><img src=/images/blank.gif width=7 height=120></TD><TD BGCOLOR=\"#FFFFCC\" valign=top>
<FONT COLOR=\"#000000\">
<b>Referer:</b><br>$user_info[camefrom]<br>
<b>Department:</b><br>$nameof<br>
<b>Currently at:</b><br><a href=$page[location]  target=_blank>$page[location]</a><br>";
$now = date("YmdHis");
$thediff = $now - $user_info[lastaction];
 $DIVS .= "<b>Last Action:</b><br>$thediff Seconds ago<br>
 </FONT></TD></TR></TABLE></TD></TR></TABLE></DIV>"; 

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
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr> <tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=278 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr><tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
<td><table width=100% bgcolor=FFFFCC><tr><td><b> $lang_visit <font color=007700>$onlinenow Online </td></tr></table></td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr> <tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
<td>"; 
 $html .= "<table width=100%><tr bgcolor=FFFFEE><td><b>ID</b></td><td colspan=2><b><?= $lang_options_num ?></b></td></tr>";
 for($i=0;$i< count($visitors); $i++){
  $visitor = $visitors[$i];

    // see if we are in the same department as this user..
   $query = "SELECT * FROM livehelp_operator_departments WHERE user_id='$myid' AND department='$visitor[department]' ";
   $data_check = $mydatabase->select($query); 
   if(count($data_check) == 0){ 

  } else {

  if($visitor[showedup] != 1){
      $onload = " setTimeout(\"doorbell();\",700); ";
      if( ($user_alert == "N") || ($user_alert == "") ){ 
        if ($show_arrival != "N"){
          $stuffatbottom = "<EMBED NAME=\"Bach\" SRC=\"insite.wav\" LOOP=FALSE AUTOSTART=TRUE HIDDEN=TRUE MASTERSOUND>";
        }      
      } 
      $sql = "UPDATE livehelp_users SET showedup='1' WHERE user_id='$visitor[user_id]' ";
      $mydatabase->sql_query($sql);
  }
  $query = "SELECT * from livehelp_visit_track WHERE id='$visitor[user_id]'";
  $my_count = $mydatabase->select($query);
  $my_count = count($my_count);
  $html .= "<tr bgcolor=FFFFFF><td>";
    
 $html .= "<a href=javascript:seepages($visitor[user_id]) onMouseOver=\"show(event, 'info-" . $visitor[username] . "'); return true;\" onMouseOut=\"hide('info-" . $visitor[username] . "'); return true;\">$visitor[username]</a>";
 
$query = "SELECT * from livehelp_users WHERE user_id='$visitor[user_id]'";
$user_info = $mydatabase->select($query);
$user_info = $user_info[0]; 

$query = "SELECT * from livehelp_visit_track WHERE id='$visitor[user_id]' Order by whendone DESC";
$page_trail = $mydatabase->select($query);
$page = $page_trail[0];

$query = "SELECT * from livehelp_departments WHERE recno='$user_info[department]'";
$tmp = $mydatabase->select($query);
$nameof = $tmp[0];
$nameof = $nameof[nameof];
  
  
 $DIVS .= "<DIV ID=\"info-" . $visitor[username] . "\" STYLE=\"position: absolute; z-index: 20; visibility: hidden; top: 0px; left: 0px;\">
<TABLE BORDER=\"0\" WIDTH=\"300\"><TR BGCOLOR=\"#000000\"><TD> 
<TABLE BORDER=\"0\" WIDTH=\"100%\" CELLPADDING=0 CELLSPACING=0 BORDER=0><TR><TD width=1 BGCOLOR=#FFFFCC><img src=/images/blank.gif width=7 height=120></TD><TD BGCOLOR=\"#FFFFCC\" valign=top>
<FONT COLOR=\"#000000\">
<b>Referer:</b><br>$user_info[camefrom]<br>
<b>Department:</b><br>$nameof<br>
<b>Currently at:</b><br>$page[location]<br>";
$now = date("YmdHis");
$thediff = $now - $user_info[lastaction];
 $DIVS .= "<b>Last Action:</b><br>$thediff Seconds ago<br>
 </FONT></TD></TR></TABLE></TD></TR></TABLE></DIV>"; 
 
 $html .= "</td>";
  switch($visitor[status]){
    case("request"):
       $html .= "<td><img src=images/invited.gif>";
       break;
    case("invited"):
       $html .= "<td><img src=images/invited2.gif>";
       break;
     case("qna"):
       $html .= "<td><img src=images/qna.gif>";
       break; 
    case("stopped"):
       $html .= "<td><img src=images/stopped.gif>";
       break;    
    case("message"):
       $html .= "<td><img src=images/message.gif>";
       break;            
    default:
      $html .= "<td><a href=admin_users.php?action=invite&saidto=$visitor[user_id]>Invite</a>";
      break;
    }
  $html .= "</td>";
  $html .= "<td>$my_count</td></tr>";
  }}
  $html .= "</table>";
  
$html = $html . $DIVS;
return $html;
}
// autoinvite.
if($auto_invite =="Y"){
  print "<font color=007700>Auto invite on </font> (<a href=autoinvite.php target=_blank>Edit monitors</a>)";
  // get the count of active visitors in the system right now.
 $timeof = date("YmdHis");
 $prev = mktime ( date("H"), date("i")-2, date("s"), date("m"), date("d"), date("Y") );
 $oldtime = date("YmdHis",$prev);
 $query = "SELECT * FROM livehelp_users WHERE lastaction>'$oldtime' AND status!='invited' AND status!='wentaway' AND status!='chat' AND status!='operator' AND status!='stopped' AND status!='Request' ORDER by lastaction DESC";
 $visitors = $mydatabase->select($query);
 for($i=0;$i<count($visitors);$i++){
   $visitor = $visitors[$i];  
   $query = "SELECT * from livehelp_visit_track WHERE id='$visitor[user_id]' ORDER BY whendone DESC";
   $footprints = $mydatabase->select($query); 
   $visits = count($footprints) - 1;
   $foot = $footprints[0];
   $pathstuff = split("\?",$foot[location]);
   $pageurl = $pathstuff[0];
   $pageurl = ereg_replace("http://","",$pageurl);

   $pathstuff = split("\?",$foot[camefrom]);
   $camefrom = $pathstuff[0];
   $camefrom = ereg_replace("http://","",$camefrom);
   
   $saidto = $visitor[user_id];   

 if ($dbtype != "txt-db-api.php"){
   $query = "SELECT * FROM livehelp_autoinvite WHERE (department='$visitor[department]' OR department='0') ";
   if($camefrom !=""){
     $query .= "AND (referer like '%$camefrom%' OR referer='') ";
   } else {
     $query .= "AND referer='' ";   
   }
   $query .= "AND (visits='0' OR visits<$visits) ";
   if($pageurl !=""){
     $query .= "AND (page='' OR page like '%$pageurl%')";
   } else {
     $query .= "AND page='' ";   
   }
 } else {
   $query = "SELECT * FROM livehelp_autoinvite WHERE (department='$visitor[department]' OR department='' OR department='0') ";
   if($camefrom !=""){
     $query .= "AND (referer='$camefrom' OR referer='') ";
   } else {
     $query .= "AND referer='' ";   
   }
   $query .= "AND (visits='' OR visits<$visits) ";
   if($pageurl !=""){
     $query .= "AND (page='' OR page='$pageurl')";
   } else {
     $query .= "AND page='' ";   
   } 	
 }  
  // print $query;
   $data = $mydatabase->select($query);
   if( count($data) != 0){ 
     $row = $data[0];
     $comment = $row[message];
     $query = "INSERT INTO livehelp_channels (user_id,statusof,startdate) VALUES ('$saidto','P','$timeof')";
     $whatchannel = $mydatabase->insert($query);
     if($dbtype == "txt-db-api.php"){
      $query = "SELECT * FROM livehelp_channels ORDER BY id DESC LIMIT 1";
      $channel_a = $mydatabase->select($query);
      $channel_a = $channel_a[0];
      $whatchannel = $channel_a[id];
     }
     $query = "UPDATE livehelp_users set onchannel='$whatchannel' WHERE user_id='$saidto' ";
     $mydatabase->sql_query($query);
     $query = "DELETE FROM livehelp_operator_channels WHERE user_id='$myid' AND userid='$saidto'";	
     $mydatabase->sql_query($query);
     $timeof = date("YmdHis");
     $channel = whatchannel;  
     if($saidto == ""){ $channel = -1; }
     $query = "UPDATE livehelp_users set status='request' WHERE user_id='$saidto' ";
     $mydatabase->sql_query($query);   	   	    
     $query = "INSERT INTO livehelp_messages (message,channel,timeof,saidfrom,saidto) VALUES ('$comment','$channel','$timeof','$myid','$saidto')";	
     $mydatabase->insert($query);    
    // if($isnamed == "Y"){
    //   $query = "UPDATE livehelp_users set isnamed='Y' WHERE user_id='$saidto' ";
    //  $mydatabase->sql_query($query); 	
    // }
   } // end of if auto found.
 } // for loop
} // END of auto invite.
$mydatabase->close_connect();
?>