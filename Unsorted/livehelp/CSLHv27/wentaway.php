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
$lastaction = date("YmdHis");
$timeof = date("YmdHis");
$startdate =  date("Ymd");

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

// get department information...
   if($department!=""){ 
   	$mydatabase->close_connect();
   	  print "<SCRIPT> window.close(); </SCRIPT>";
   	  exit;
   }
 
// get the userid, channel and isnamed fields.
if($myid  == ""){
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

     $query = "INSERT INTO livehelp_users (onchannel,identity,lastaction,department,status,username) VALUES ('-1','$identity','$lastaction','$department','Visiting','$username')";
     $mydatabase->insert($query);
     $query = "SELECT * FROM livehelp_users WHERE identity='$identity'";
     $data = $mydatabase->select($query);	     	
   $people = $data[0];
   $myid = $people[user_id];
   $channel = $people[onchannel];
   $isnamed = $people[isnamed];
   $status = $people[status];
  } else {
   $people = $people[0];
   $myid = $people[user_id];
   $channel = $people[onchannel];
   $isnamed = $people[isnamed];
   $status = $people[status];
  }
}

if($visiting ==""){
    $query = "UPDATE livehelp_users set status='stopped' WHERE identity='$identity'";	
  } else {
    $query = "UPDATE livehelp_users set status='visiting' WHERE identity='$identity'";	
  }
  $mydatabase->sql_query($query);

  print "<br><br><br><br><center><font color=990000 size=+2>Session CLOSED.</font><br><br><a href=javascript:window.close()>Close this window</a></center>"; 
  print "<SCRIPT> window.close(); </SCRIPT>";
 
$mydatabase->close_connect();
?>