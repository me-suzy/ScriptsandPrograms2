<?php 
//===========================================================================
//* --    ~~                Crafty Syntax Live Help                ~~    -- *
//===========================================================================
//           URL:   http://www.craftysyntax.com/    EMAIL: ericg@craftysyntax.com
//         Copyright (C) 2003-2005 Eric Gerdes   (http://www.craftysyntax.com )
// --------------------------------------------------------------------------
// $              CVS will be released with version 3.1.0                   $
// $    Please check http://www.craftysyntax.com/ or REGISTER your program for updates  $
// --------------------------------------------------------------------------
// NOTICE: Do NOT remove the copyright and/or license information any files. 
//         doing so will automatically terminate your rights to use program.
//         If you change the program you MUST clause your changes and note
//         that the original program is Crafty Syntax Live help or you will 
//         also be terminating your rights to use program and any segment 
//         of it.        
// --------------------------------------------------------------------------
// LICENSE:
//     This program is free software; you can redistribute it and/or
//     modify it under the terms of the GNU General Public License
//     as published by the Free Software Foundation; 
//     This program is distributed in the hope that it will be useful,
//     but WITHOUT ANY WARRANTY; without even the implied warranty of
//     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//     GNU General Public License for more details.
//
//     You should have received a copy of the GNU General Public License
//     along with this program in a file named LICENSE.txt .
// --------------------------------------------------------------------------
// FILE NOTES:
//     This file controls the list of active users on the site. 
//===========================================================================
require_once("security.php");

// if this is an operator op is set.   
if(!(empty($UNTRUSTED['op']))){
  require_once("admin_common.php");
  validate_session($identity);
} else {
  require_once("visitor_common.php");
}

  $sqlquery = "SELECT user_id,onchannel,isnamed,username,jsrn FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
  $people = $mydatabase->query($sqlquery);
  $people = $people->fetchRow(DB_FETCHMODE_ORDERED);
  $myid = $people[0];
  $channel = $people[1];
  $isnamed = $people[2];
  $username = $people[3];
  $jsrn = $people[4];
  $see = "";
  $hide = "";
  
if(empty($UNTRUSTED['whattodo']))
  $whattodo = "";
  
if($UNTRUSTED['whattodo'] == "ping"){	  
 echo 'OK';
 exit;
}

if(!(empty($UNTRUSTED['see'])))
  $see = $UNTRUSTED['see'];
  
if(!(empty($UNTRUSTED['externalchats'])))
  $hide = $UNTRUSTED['externalchats'];  
  
if(ereg("cslhVISITOR",$identity['IDENTITY']))
  $see = $channel;


if($UNTRUSTED['whattodo'] == "wantstochat"){

	 //update last action:
   $mytimeof = date("YmdHis");
   $sqlquery = "UPDATE livehelp_users set lastaction='$mytimeof' WHERE sessionid='".$identity['SESSIONID']."'";	
   $mydatabase->query($sqlquery);
  
   // if they have timed out:
   if($UNTRUSTED['waitTimeout'] < $mytimeof){
     print "TIMEOUT";
     exit;	
  }

   // see if someone is talking to this user on this channel if so send to 
   // chat:
   $sqlquery = "SELECT channel FROM livehelp_operator_channels WHERE channel=" . intval($channel);
   $counting = $mydatabase->query($sqlquery);
   if( $counting->numrows() != 0)
     print "CONNECTED";
   else
     print "LIGHTS-ARE-ON-BUT-NOBODY-IS-HOME";  
  
  exit;
  
}

if($UNTRUSTED['whattodo'] == "messages"){	 
	
	// if noone is talking to this user then send exit layer:
	if (empty($UNTRUSTED['op'])){
   $sqlquery = "SELECT user_id FROM livehelp_users WHERE user_id=".intval($myid)." AND status='chat'";
   $alive = $mydatabase->query($sqlquery);
   if($alive->numrows() == 0){ 
   	   $aftertime = date("YmdHis");
       $string = "messages[0] = new Array(); messages[0][0]=$aftertime; messages[0][1]=$jsrn; messages[0][2]=\"EXIT\"; messages[0][3]=\"\"; messages[0][4]=\"\";"; 
       print $string;
       exit;
   }
	}
	$omitself = true;
	if(!(empty($UNTRUSTED['includeself'])))
	  $omitself = false;
	print showmessages($myid,"",$UNTRUSTED['HTML'],$see,$hide,true,$omitself);
	print showmessages($myid,"writediv",$UNTRUSTED['LAYER'],$see,$hide,true,$omitself);		
}

// load chatterHTML and VisitorHTML into DIVs.. 
// string returned needs to be :
// numchaters=[number]&chattersHTML=[chatterHTML]&numvisitors=[number]&visitorsHTML=[visitorHTML]&doorbell=[Y/N]&alert=[Y/N]
if( (!(empty($UNTRUSTED['op']))) && ($UNTRUSTED['whattodo'] == "userslist")){	
  $onload = "";
  $DIVS = "";
  $timeof = date("YmdHis");

  // update operators timestamp .
  $sql = "UPDATE livehelp_users set lastaction='$timeof' WHERE sessionid='".$identity['SESSIONID']."' ";
  $mydatabase->query($sql);

  $chattersHTML = "<table width=100%>";

  $prev = mktime ( date("H"), date("i")-3, date("s"), date("m"), date("d"), date("Y") );
  $oldtime = date("YmdHis",$prev);

  $sqlquery = "SELECT * FROM livehelp_users WHERE status='chat'";
  $chatcheck = $mydatabase->query($sqlquery);
  $numchaters = $chatcheck->numrows();
  if($chatcheck->numrows() == 0){
   	$chattersHTML .= "<tr bgcolor=FFFFFF><td>".$lang['no_chat']."</td></tr>";
  }
  
  while($visitor = $chatcheck->fetchRow(DB_FETCHMODE_ASSOC)){
    $actionlink = "";
    $chatting ="";
 
    // see if this is NOT an operator.
    if($visitor['isoperator'] == "N"){    	
      // see if we are in the same department as this user..
      $sqlquery = "SELECT * FROM livehelp_operator_departments WHERE user_id='$myid' AND department='" . $visitor['department'] . "' ";
      $data_check = $mydatabase->query($sqlquery); 
      if($data_check->numrows() == 0){ 
        // see if we are chatting with them
        /*
        $sqlquery = "SELECT * FROM livehelp_operator_channels WHERE user_id='$myid' And channel='" . $visitor['onchannel'] . "'";
        $counting = $mydatabase->query($sqlquery);
          if($counting->numrows() == 0){   
             $chatting = "<img src=images/noton.gif width=19 height=18 border=0>";
             $actionlink = "[<a href=admin_users.php?ignorelist=$ignorelist&operators=$operators&action=activiate&who=" . $visitor['user_id'] . "&whatchannel=" . $visitor['onchannel'] . "&hidevisitors=$UNTRUSTED['hidevisitors']>Conference</a>] ";
             $actionlink .= " [<a href=admin_users.php?ignorelist=$ignorelist&operators=$operators&action=activiate&who=" . $visitor['user_id'] . "&whatchannel=" . $visitor['onchannel'] . "&hidevisitors=$UNTRUSTED['hidevisitors']><font color=007700>" . $lang['chat'] . "</font></a>]";
          } else {
             $chatting = "<img src=images/active.gif width=19 height=18 border=0>";
             $actionlink = "";
             $sqlquery = "SELECT * FROM livehelp_operator_channels WHERE channel='" . $visitor['onchannel'] . "'";
             $counting = $mydatabase->query($sqlquery);
             if($counting->numrows() >1){
                $actionlink = "[<a href=admin_users.php?action=ignorelist=$ignorelist&operators=$operators&leave&who=" . $visitor['user_id'] . "&whatchannel=" . $visitor['onchannel'] . "&hidevisitors=$UNTRUSTED['hidevisitors']>Un-Conference</a>] ";
             }
             $actionlink .= " [<a href=admin_users.php?ignorelist=$ignorelist&operators=$operators&action=stop&who=" . $visitor['user_id'] . "&whatchannel=" . $visitor['onchannel'] . "&hidevisitors=$UNTRUSTED['hidevisitors']><font color=990000>" . $lang['stop'] . "</font></a>]"; 
          }
         */
       } else {
         // see if anyone is chatting with this person. 
         $sqlquery = "SELECT * FROM livehelp_operator_channels WHERE channel='" . $visitor['onchannel'] . "'";
         $counting = $mydatabase->query($sqlquery);
         if( ($counting->numrows() == 0) && (!(in_array($visitor['user_id'],$ignorelist_array))) ){
            $updated_ignore = $ignorelist . "," . $visitor['user_id'];
            $chatting = "<img src=images/needaction.gif width=21 height=20 border=0>";  
            $actionlink = "[<a href=admin_users.php?ignorelist=$ignorelist&operators=$operators&action=activiate&who=" . $visitor['user_id'] . "&whatchannel=" . $visitor['onchannel'] . "&hidevisitors=".$UNTRUSTED['hidevisitors']."&needaction=1>Activate</a>] ";
            
            $actionlink .= "[<a href=admin_users.php?ignorelist=$updated_ignore&operators=$operators&hidevisitors=".$UNTRUSTED['hidevisitors'].">ignore</a>]";
            $onload = " setTimeout(\"tellme();\",500); ";
            if( ($user_alert == "N") || ($user_alert == "")){ 
              $stuffatbottom .= "<EMBED NAME=\"Bach\" SRC=\"sound.wav\" LOOP=FALSE AUTOSTART=TRUE HIDDEN=TRUE MASTERSOUND><noembed><BGSOUND SRC=sound.wav></noembed>";
            }      
         } else {
            // see if we are chatting with them
           $sqlquery = "SELECT * FROM livehelp_operator_channels WHERE user_id='$myid' And channel='" . $visitor['onchannel'] . "'";
           $counting = $mydatabase->query($sqlquery);
           if($counting->numrows() == 0){
             $chatting = "<img src=images/noton.gif width=19 height=18 border=0>";
             $actionlink = "[<a href=admin_users.php?ignorelist=$ignorelist&operators=$operators&action=activiate&who=" . $visitor['user_id'] . "&whatchannel=" . $visitor['onchannel'] . "&hidevisitors=".$UNTRUSTED['hidevisitors']."><font color=007700>" . $lang['begin'] . "</font></a>]";
           } else {
            $chatting = "<img src=images/active.gif width=19 height=18 border=0>";
            $actionlink = "";
            $sqlquery = "SELECT * FROM livehelp_operator_channels WHERE channel='" . $visitor['onchannel'] . "'";
            $counting = $mydatabase->query($sqlquery);
            if($counting->numrows() >1){
              $actionlink = "[<a href=admin_users.php?ignorelist=$ignorelist&operators=$operators&action=leave&who=" . $visitor['user_id'] . "&whatchannel=" . $visitor['onchannel'] . "&hidevisitors=".$UNTRUSTED['hidevisitors'].">Un-Conference</a>] ";
            }
            $actionlink .= "[<a href=admin_users.php?ignorelist=$ignorelist&operators=$operators&action=stop&who=" . $visitor['user_id'] . "&whatchannel=" . $visitor['onchannel'] . "&hidevisitors=".$UNTRUSTED['hidevisitors']."><font color=990000>" . $lang['stop'] . "</font></a>]"; 
           }
        } 
        // see if anyone is chatting with this person. 
       $sqlquery = "SELECT * FROM livehelp_operator_channels WHERE channel='" . $visitor['onchannel'] . "'";
       $counting = $mydatabase->query($sqlquery);
       if( ($counting->numrows() == 0) && (!(in_array($visitor['user_id'],$ignorelist_array))) ){
         $updated_ignore = $ignorelist . "," . $visitor['user_id'];
         $chatting = "<img src=images/needaction.gif width=19 height=18 border=0>";  
              $actionlink = "[<a href=admin_users.php?ignorelist=$ignorelist&operators=$operators&action=activiate&who=" . $visitor['user_id'] . "&whatchannel=" . $visitor['onchannel'] . "&hidevisitors=".$UNTRUSTED['hidevisitors']."&needaction=1>Activate</a>] ";
            
         $actionlink .= " [<a href=admin_users.php?ignorelist=$updated_ignore&operators=$operators&hidevisitors=".$UNTRUSTED['hidevisitors'].">ignore</a>]";
       }
       $chattersHTML .= "<tr bgcolor=FFFFFF><td width=10><input type=checkbox name=session__".$visitor['user_id']." value=".$visitor['sessionid']."></td><td>$chatting <a href=javascript:seepages(" . $visitor['user_id'] . ") onMouseOver=\"hide('chattersdiv');show(event, 'info-" . $visitor['username'] . "'); return true;\" onMouseOut=\"showit('chattersdiv');hide('info-" . $visitor['username'] . "'); return true;\">" . $visitor['username'] . "</a>  $actionlink </td></tr>";
      }    
    } else {      
      // Operators can be on multiple channels so see if one of the channels they are on is one we are on.      
      $foundcommonchannel = 0;
      $sqlquery = "SELECT * FROM livehelp_operator_channels WHERE user_id='".$visitor['user_id']."'";
      $mychannels = $mydatabase->query($sqlquery);
      while($rowof = $mychannels->fetchRow(DB_FETCHMODE_ASSOC)){
         $sqlquery = "SELECT * FROM livehelp_operator_channels WHERE user_id='$myid' And channel='".$rowof['channel']."'";
         $counting = $mydatabase->query($sqlquery);        
         if($counting->numrows() != 0){ 
             $foundcommonchannel = $rowof['channel'];
         }
       }
       if($foundcommonchannel == 0){
         $chatting = "<img src=images/operator.gif width=21 height=20 border=0><img src=images/noton.gif width=19 height=18 border=0>";
         if($myid != $visitor['user_id'])
           $actionlink = "[<a href=admin_users.php?ignorelist=$ignorelist&operators=$operators&action=activiate&who=" . $visitor['user_id'] . "&whatchannel=" . $visitor['onchannel'] . "&conferencein=yes&hidevisitors=".$UNTRUSTED['hidevisitors']."><font color=007700>" . $lang['begin'] . "</font></a>]";
         else
           $actionlink = "";             
        } else {
          $chatting = "<img src=images/operator.gif width=21 height=20 border=0><img src=images/active.gif width=19 height=18 border=0>";
        if($visitor['user_id'] != $myid)
           $actionlink = "[<a href=admin_users.php?ignorelist=$ignorelist&operators=$operators&action=leave&who=" . $visitor['user_id'] . "&whatchannel=$foundcommonchannel&clearchannel=Y&hidevisitors=".$UNTRUSTED['hidevisitors'].">".$lang['Hide']."</a>]"; 
        }
      $chattersHTML .= "<tr bgcolor=FFFFFF><td>&nbsp;</td><td>$chatting <a href=javascript:seepages(" . $visitor['user_id'] . ") onMouseOver=\"hide('chattersdiv');show(event, 'info-" . $visitor['username'] . "'); return true;\" onMouseOut=\"showit('chattersdiv');hide('info-" . $visitor['username'] . "'); return true;\">" . $visitor['username'] . "</a>  $actionlink </td></tr>";
      }

$sqlquery = "SELECT * from livehelp_users WHERE sessionid='" . $visitor['sessionid'] . "'";

$user_info = $mydatabase->query($sqlquery);
$user_info = $user_info->fetchRow(DB_FETCHMODE_ASSOC);

$sqlquery = "SELECT * from livehelp_visit_track WHERE sessionid='".$visitor['sessionid']."' Order by whendone DESC";
$page_trail = $mydatabase->query($sqlquery);
$page = $page_trail->fetchRow(DB_FETCHMODE_ASSOC);
  
$sqlquery = "SELECT * from livehelp_departments WHERE recno='".$visitor['department']."'";
$tmp = $mydatabase->query($sqlquery);
$nameof = $tmp->fetchRow(DB_FETCHMODE_ASSOC);
$nameof = $nameof['nameof'];
  
  
 $DIVS .= "<DIV ID=\"info-" . $visitor['username'] . "\" STYLE=\"position: absolute; z-index: 20; visibility: hidden; top: 0px; left: 0px;\">
<TABLE BORDER=\"0\" WIDTH=\"300\"><TR BGCOLOR=\"#000000\"><TD> 
<TABLE BORDER=\"0\" WIDTH=\"100%\" CELLPADDING=0 CELLSPACING=0 BORDER=0><TR><TD width=1 BGCOLOR=#D4DCF2><img src=images/blank.gif width=7 height=120></TD><TD BGCOLOR=\"#D4DCF2\" valign=top>
<FONT COLOR=\"#000000\">
<b>Referer:</b><br>" . $user_info['camefrom'] . "<br>
<b>Department:</b><br>$nameof<br>
<b>Currently at:</b><br><a href=" . $page['location'] . "  target=_blank>" . $page['location'] . "</a><br>";
$now = date("YmdHis");
if(($user_info['lastaction'] =="") || ($user_info['lastaction'] < 20002020202020) )
   $user_info['lastaction'] = $now;
$thediff = $now - $user_info['lastaction'];
 $DIVS .= "<b>Last Action:</b><br>$thediff Seconds ago<br>
 </FONT></TD></TR></TABLE></TD></TR></TABLE></DIV>"; 

  } 
 $chattersHTML .= "</table>";
 $chattersHTML .= "<DIV ID=\"chattersdiv\" STYLE=\"z-index: 2;\">"; 	
 if($chatcheck->numrows() > 1){
  $chattersHTML .= "<table>";
  $chattersHTML .= "<tr><td><img src=images/arrow_ltr.png width=38 height=22><select name=whattodochat onchange=updatepeople(1)>";
  $chattersHTML .= "<option value=\"\">with selected:</option>";
  $chattersHTML .= "<option value=stop>Stop Chat</option>";
  $chattersHTML .= "<option value=transfer>transfer Chat</option>";  
  $chattersHTML .= "</select> <a href=javascript:updatepeople(1)><img src=images/go.gif border=0 width=20 height=20></a></td></tr>";
  $chattersHTML .= "</table>";
 }
  $chattersHTML .= "</DIV>";
 
  // get the count of active visitors in the system right now.
 $timeof = date("YmdHis");
 $prev = mktime ( date("H"), date("i")-2, date("s"), date("m"), date("d"), date("Y") );
 $oldtime = date("YmdHis",$prev);
 $sqlquery = "SELECT * FROM livehelp_users WHERE lastaction>'$oldtime' AND status!='chat' AND isoperator!='Y' ORDER by lastaction DESC";
 $visitors = $mydatabase->query($sqlquery);
 $numvisitors = $onlinenow = $visitors->numrows();
 $html .= "</td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr> <tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=278 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr><tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
<td><table width=100% cellpadding=0 cellspacing=0 bgcolor=D4DCF2><tr><td>";
 
  if(empty($UNTRUSTED['hidevisitors'])) { 
     $html .= "<a href=admin_users.php?ignorelist=$ignorelist&operators=".$UNTRUSTED['operators']."&hidevisitors=1><img src=images/minus.gif border=0></a>";
  } else { 
     $html .= "<a href=admin_users.php?ignorelist=$ignorelist&operators=".$UNTRUSTED['operators']."><img src=images/plus.gif border=0></a>";  
  } 
 
 if(empty($UNTRUSTED['hidevisitors'])){
 $html .= "</td><td><b>" .  $lang['visit'] . " <font color=007700>$onlinenow Online </td></tr></table></td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr> <tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
<td>"; 
 $html .= "<table width=100%><tr bgcolor=$color_background><td>&nbsp;</td><td><b>ID</b></td><td><b>".$lang['status'].":</b></td><td><b>#</b></td></tr>";

while($visitor = $visitors->fetchRow(DB_FETCHMODE_ASSOC)){
   $chatting = "";
    // see if we are in the same department as this user..
   $sqlquery = "SELECT * FROM livehelp_operator_departments WHERE user_id='$myid' AND department='".$visitor['department']."' ";
   $data_check = $mydatabase->query($sqlquery); 
   if( $data_check->numrows() == 0){ 

   } else {
  if(empty($visitor['showedup'])) $visitor['showedup'] = 0; 
  if($visitor['showedup'] != 1){
      $onload = " setTimeout(\"doorbell();\",700); ";
      if( ($user_alert == "N") || ($user_alert == "") ){ 
        if ($show_arrival != "N"){
          $stuffatbottom .= "<EMBED NAME=\"Bach\" SRC=\"insite.wav\" LOOP=FALSE AUTOSTART=TRUE HIDDEN=TRUE MASTERSOUND><noembed><BGSOUND SRC=insite.wav></noembed>";
        }      
      } 
      $sql = "UPDATE livehelp_users SET showedup='1' WHERE user_id='" . $visitor['user_id'] . "' ";
      $mydatabase->query($sql);
  }
  $sqlquery = "SELECT * from livehelp_visit_track WHERE sessionid='" . $visitor['sessionid'] . "'";
  $my_count = $mydatabase->query($sqlquery);
  $my_count = $my_count->numrows();
  $html .= "<tr bgcolor=FFFFFF><td width=10><input type=checkbox name=session__".$visitor['user_id']." value=".$visitor['sessionid']."></td><td>";
    
 $html .= "<a href=javascript:seepages(" . $visitor['user_id'] . ") onMouseOver=\"hide('visitorsdiv');show(event, 'info-" . $visitor['username'] . "'); return true;\" onMouseOut=\"showit('visitorsdiv');hide('info-" . $visitor['username'] . "'); return true;\">" . $visitor['username'] . "</a>";
 
$sqlquery = "SELECT * from livehelp_users WHERE user_id='" . $visitor['user_id'] . "'";
$user_info = $mydatabase->query($sqlquery);
$user_info = $user_info->fetchRow(DB_FETCHMODE_ASSOC); 

$sqlquery = "SELECT * from livehelp_visit_track WHERE sessionid='" . $visitor['sessionid'] . "' Order by whendone DESC LIMIT 1";
$page_trail = $mydatabase->query($sqlquery);
$page = $page_trail->fetchRow(DB_FETCHMODE_ASSOC);

$sqlquery = "SELECT * from livehelp_departments WHERE recno='" . $visitor['department'] . "'";
$tmp = $mydatabase->query($sqlquery);
$nameof = $tmp->fetchRow(DB_FETCHMODE_ASSOC);
$nameof = $nameof['nameof'];
  
  
 $DIVS .= "<DIV ID=\"info-" . $visitor['username'] . "\" STYLE=\"position: absolute; z-index: 20; visibility: hidden; top: 0px; left: 0px;\">
<TABLE BORDER=\"0\" WIDTH=\"300\"><TR BGCOLOR=\"#000000\"><TD> 
<TABLE BORDER=\"0\" WIDTH=\"100%\" CELLPADDING=0 CELLSPACING=0 BORDER=0><TR><TD width=1 BGCOLOR=#D4DCF2><img src=images/blank.gif width=7 height=120></TD><TD BGCOLOR=\"#D4DCF2\" valign=top>
<FONT COLOR=\"#000000\">
<b>Referer:</b><br>" . $user_info['camefrom'] . "<br>
<b>Department:</b><br>$nameof<br>
<b>Currently at:</b><br>" . $page['location'] . "<br>";
$now = date("YmdHis");
$thediff = $now - $user_info['lastaction'];
 $DIVS .= "<b>Last Action:</b><br>$thediff Seconds ago<br>
 </FONT></TD></TR></TABLE></TD></TR></TABLE></DIV>"; 
 
 $html .= "</td>";
  switch($visitor['status']){
    case("DHTML"):
       $html .= "<td>L:<img src=images/invited.gif> ";
       break;
    case("request"):
       $html .= "<td>P:<img src=images/invited.gif> ";
       break;
    case("invited"):
       $html .= "<td><img src=images/invited2.gif>  ";
       break;
     case("qna"):
       $html .= "<td><img src=images/qna.gif>";
       break; 
    case("stopped"):
       $html .= "<td><img src=images/stopped.gif> ";
       break;    
    case("message"):
       $html .= "<td><img src=images/message.gif> ";
       break;            
    default:
      $html .= "<td><a href=invite.php?selectedwho=" . $visitor['user_id'] . " target=_blank>pop-up</a> <a href=layer.php?selectedwho=" . $visitor['user_id'] . " target=_blank>Layer</a> ";
      break;
    }
  $html .= "</td>";
  $html .= "<td>$my_count</td></tr>";
  }}
 $html .= "</table>";
 $html .= "<DIV ID=\"visitorsdiv\" STYLE=\"z-index: 2;\">";
 if($visitors->numrows() > 0){
  $html .= "<table>";
  $html .= "<tr><td><img src=images/arrow_ltr.png width=38 height=22><select name=whattodo onchange=updatepeople(2)>";
  $html .= "<option value=\"\">with selected:</option>";
  $html .= "<option value=DHTML>Invite: Layer</option>";
  $html .= "<option value=pop>Invite: Pop-up</option>";
  $html .= "</select> <a href=javascript:updatepeople(2)><img src=images/go.gif border=0 width=20 height=20></a></td></tr>";
  $html .= "</table>";
 }
  $html .= "</DIV>"; 

 } else {
	  $html .= "</td><td><b>" .  $lang['visit'] . "  </td></tr></table></td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr> <tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
<td>"; 

 }
$html = $html . $DIVS;
print $html;
}
?> 