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


// Identify the user. Can not use cookies because of the 
// 3rd party cookie are blocked by Microsoft. I have relatives that work for
// Microsoft so I can't say anything about how stupid 3rd party block is.

$identity = $REMOTE_ADDR . $HTTP_USER_AGENT;
$referer = $HTTP_REFERER;
$identity = ereg_replace(" ","",$identity);

if($cmd == ""){ $cmd = "getstate"; }

if($cmd == "nameme"){
   $query = "UPDATE livehelp_users set username='$name',isnamed='Y' WHERE identity='$identity'";
   $mydatabase->sql_query($query);
   $mydatabase->close_connect();
   Header("Location: browse.gif"); 
}

//----------------------------------------------------------------
if($cmd == "startedtyping"){      
   $timeof = date("YmdHis"); 
   $array = split("__",$channelsplit);
   if($array[1] != ""){
     $saidto = $array[1];    
     $channel = $array[0];
   } else {
     $channel = $channelsplit;
   }
   $comment = "<font color=AAAAAA>typing message...</font>";   
   $query = "UPDATE livehelp_users set status='chat' WHERE user_id='$fromwho'";
   $mydatabase->sql_query($query);
   $query = "INSERT INTO livehelp_messages (message,channel,timeof,saidfrom,saidto) VALUES ('$comment','$channel','$timeof','$fromwho','$saidto')";	
   $mydatabase->insert($query);
   $mydatabase->close_connect();
   Header("Location: browse.gif"); 
}

//----------------------------------------------------------------
if($cmd == "browse"){
      $mydatabase->close_connect();
      Header("Location: browse.gif"); 
      exit;
}

// userstat: return the control image for this user. 
//----------------------------------------------------------------
if($cmd == "userstat"){
   
   // see if anyone is online.. if no one is then we are not tracking..
   $query = "SELECT * FROM livehelp_users WHERE isonline='Y' AND isoperator='Y' ";
   $data = $mydatabase->select($query);  
   if( count($data) == 0){ $imtracking = 0; } else { $imtracking = 1; }
   
   if($imtracking == 1){
   // see if we already know who this guy is.
   $query = "SELECT * FROM livehelp_users WHERE identity='$identity'";
   $data = $mydatabase->select($query);	
   if (count($data) == 0){

     // we do not know them
     $lastaction = date("YmdHis");
     
     //get a good username..  
     $username = $REMOTE_ADDR;          
     $query = "SELECT * FROM livehelp_users WHERE username='$username'";
     $data_tmp = $mydatabase->select($query);	
     $i = 0;
     while( count($data_tmp) != 0){
     	$i++;
        $username = $REMOTE_ADDR . "_" . $i;          
        $query = "SELECT * FROM livehelp_users WHERE username='$username'";
        $data_tmp = $mydatabase->select($query);	
     }

     $query = "INSERT INTO livehelp_users (onchannel,identity,lastaction,status,username,isoperator,password) VALUES ('-1','$identity','$lastaction','Visiting','$username','N','')";
     $mydatabase->insert($query);
     $query = "SELECT * FROM livehelp_users WHERE identity='$identity'";
     $data = $mydatabase->select($query);	     	
     $visitor = $data[0];
   } else {
     $visitor = $data[0];
   }

   // now..
   $rightnow = date("YmdHis");

   // update the visitors tracks.
   // see if we already have the page they are on.
   
   $query = "SELECT * FROM livehelp_visit_track WHERE id='$visitor[user_id]' AND page='$pageid' ";
   $data_tmp = $mydatabase->select($query);
   $count = count($data_tmp);
   if($visitor[user_id] == 0){ $count = 1; }
   if( $count == 0){
     $thisid = $visitor[user_id];

     $query = "INSERT INTO livehelp_visit_track (id,location,page,title,whendone,referrer) VALUES ('$thisid','$page','$pageid','$title','$rightnow','$referer') ";
     $mydatabase->insert($query);
   }
   
   // update their last action to now..
   $query = "UPDATE livehelp_users set lastaction='$rightnow' WHERE identity='$identity'";
   $mydatabase->sql_query($query);	

    // see if the operator wants anything with them:
    // status = R means request Chat.. 
    if($visitor[status] == "request"){
     $mydatabase->close_connect();
     Header("Location: requestchat.gif");
    } else {
     $mydatabase->close_connect();
     Header("Location: browse.gif");   	
    }
   } else {
     $mydatabase->close_connect();
     Header("Location: browse.gif");    	
    } 
}

//give credit to the programmer .. 
//----------------------------------------------------------------
if($cmd == "getcredit"){
	 	
  $query = "SELECT * FROM livehelp_users WHERE isonline='Y' AND isoperator='Y' ";
  $data = $mydatabase->select($query);  
  if( count($data) != 0){
    // see if they left their computer but did not log off.. 
    $prev = mktime ( date("H"), date("i")-10, date("s"), date("m"), date("d"), date("Y") );
    $oldtime = date("YmdHis",$prev);
    $query = "UPDATE livehelp_users set isonline='N' WHERE isoperator='Y' AND lastaction<'$oldtime'";
    $mydatabase->sql_query($query); 
    $mydatabase->close_connect();
    Header("Location: livehelp.gif");
  } else {
    if($leaveamessage == "YES"){
    $mydatabase->close_connect();
    Header("Location: livehelp.gif");     
   } else {	
	
      $mydatabase->close_connect();
      Header("Location: offline.gif");  
    }
  }
}

// are we online or not.. 
//----------------------------------------------------------------
if($cmd == "getstate"){
	 	
  $query = "SELECT * FROM livehelp_users WHERE isonline='Y' AND isoperator='Y' ";
  $data = $mydatabase->select($query);  
  if( count($data) != 0){
    // see if they left their computer but did not log off.. 
    $prev = mktime ( date("H"), date("i")-2, date("s"), date("m"), date("d"), date("Y") );
    $oldtime = date("YmdHis",$prev);
    $query = "UPDATE livehelp_users set isonline='N' WHERE isoperator='Y' AND lastaction<'$oldtime'";
    $mydatabase->sql_query($query); 
    $mydatabase->close_connect();
    Header("Location: online.gif");
  } else {
    if($leaveamessage == "YES"){
    $mydatabase->close_connect();
    Header("Location: leavemessage.gif");     
    } else {	
    $mydatabase->close_connect();
    Header("Location: offline.gif");  
    }
  }
}






?>