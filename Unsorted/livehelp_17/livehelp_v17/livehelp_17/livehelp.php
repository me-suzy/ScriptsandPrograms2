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
$lastaction = date("YmdHis");
$timeof = date("YmdHis");
$startdate =  date("Ymd");

$identity = $REMOTE_ADDR . $HTTP_USER_AGENT;
$identity = ereg_replace(" ","",$identity);


// see if anyone is online . if not send them to the leave a message page..
$query = "SELECT * FROM livehelp_users WHERE isonline='Y' AND isoperator='Y' ";
$data = $mydatabase->select($query);  
if( count($data) == 0){
$mydatabase->close_connect();
Header("Location: leavemessage.php");
exit;
}

// get the userid, channel and isnamed fields.
if($myid  == ""){
  $identity = $REMOTE_ADDR . $HTTP_USER_AGENT;
  $identity = ereg_replace(" ","",$identity);
  $query = "SELECT * FROM livehelp_users WHERE identity='$identity'";	
  $people = $mydatabase->select($query);
  // if user does not exist create them.. 
  if( count($people) == 0){
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

     $query = "INSERT INTO livehelp_users (onchannel,identity,lastaction,status,username) VALUES ('-1','$identity','$lastaction','Visiting','$username')";
     $mydatabase->insert($query);
     $query = "SELECT * FROM livehelp_users WHERE identity='$identity'";
     $data = $mydatabase->select($query);	     	
   $people = $data[0];
   $myid = $people[user_id];
   $channel = $people[onchannel];
   $isnamed = $people[isnamed];
  } else {
   $people = $people[0];
   $myid = $people[user_id];
   $channel = $people[onchannel];
   $isnamed = $people[isnamed];
  }
}

// if the user requested an exit..
if($action == "leave"){
  $query = "UPDATE livehelp_users set status='Visiting' WHERE identity='$identity'";	
  $mydatabase->sql_query($query);
  $mydatabase->close_connect();
  print "<br><br><br><br><center><font color=990000 size=+2>Session CLOSED.</font><br><br><a href=javascript:window.close()>Close this window</a></center>";
  exit;
}

// skip ask for name if that is set.
if($needname == "no"){
  $newusername = $REMOTE_ADDR;
  $makenamed = "Y";
}

// isnamed is set to yes when the user enters in their name. 
if($makenamed == "Y"){
  
  // make sure the username that we have is unique:
  $countnum = 0;
  $count = 1;
  $username_s = $newusername; 
  if($newusername == ""){ $newusername = "no name"; }
  while($count != 0){
    $query = "SELECT * FROM livehelp_users WHERE username='$newusername' "; 
    $count_a = $mydatabase->select($query);
    $count = count($count_a);  
    if($count != 0){ $newusername = $username_s . "_" . $countnum; }
    $countnum++;
  }      
  $query = "UPDATE livehelp_users Set isnamed='Y',username='$newusername' WHERE identity='$identity'";	
  $mydatabase->sql_query($query);	
  
 
  $query = "SELECT * FROM livehelp_users WHERE identity='$identity'";	
  $people = $mydatabase->select($query);
  $people = $people[0];
  $myid = $people[user_id];
  $channel = $people[onchannel];
  $isnamed = $people[isnamed];
  
  $isnamed = "Y";
}


// create the channel
if(( ($channel == -1) || ($channel == "")) && ($isnamed == "Y") ){
$query = "INSERT INTO livehelp_channels (user_id,statusof,startdate) VALUES ('$myid','P','$startdate')";
$channel = $mydatabase->insert($query);
if($dbtype == "txt-db-api.php"){
 $query = "SELECT * FROM livehelp_channels ORDER BY id DESC LIMIT 1";
 $channel_a = $mydatabase->select($query);
 $channel_a = $channel_a[0];
 $channel = $channel_a[id];
}
$query = "UPDATE livehelp_users set onchannel='$channel' WHERE user_id='$myid' ";
$mydatabase->sql_query($query);
$query = "UPDATE livehelp_users set status='chat' WHERE user_id='$myid' ";
$mydatabase->sql_query($query);
}

// change status to chat 
if($isnamed == "Y"){
  $query = "UPDATE livehelp_users set status='chat' WHERE user_id='$myid' ";
  $mydatabase->sql_query($query); 
}
$mydatabase->close_connect();
?>
<frameset rows="36,*,55" border="0" frameborder="0" framespacing="0" spacing="0">
<frame src="user_top.php?channel=<?= $channel ?>&t=<?= $lastaction ?>&myid=<?= $myid ?>" name="1a" scrolling="no" border="0" marginheight="0" marginwidth="0" NORESIZE>
<frame src="user_chat.php?channel=<?= $channel ?>&t=<?= $lastaction ?>&myid=<?= $myid ?>" name="1b" scrolling="AUTO" border="0" marginheight="0" marginwidth="0" NORESIZE>
<frame src="user_bot.php?channel=<?= $channel ?>&t=<?= $lastaction ?>&myid=<?= $myid ?>" name="1c" scrolling="no" border="0" marginheight="0" marginwidth="0" NORESIZE>
</frameset>