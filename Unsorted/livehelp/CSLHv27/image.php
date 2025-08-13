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
 setcookie ("identity", $identity,time()+3600, "/");
}

include("config.php");

function gettrans($channel){
  global $mydatabase;
  $trans = "";
  $query = "SELECT * FROM livehelp_messages WHERE channel='$channel' ORDER by timeof ";	
  $messages = $mydatabase->select($query);
  if ( count($messages) == 0) 
    return "";
  for($i=0;$i<count($messages);$i++){
    $row = $messages[$i];
    $message = $row[message];
    $timeof = $row[timeof];
    $id_num = $row[id_num];
    $saidfrom = $row[saidfrom];
    $saidto = $row[saidto];
    // this is in a seprate query because left join queries take too long..
    $query = "SELECT * FROM livehelp_users WHERE user_id='$saidfrom'";
    $username_s = $mydatabase->select($query);
    $username_a = $username_s[0];
    $username = $username_a[username];
    $abort_counter = 0;
    $trans .= " $username : $message <br>";		
    }
  return $trans;
}

// get department information...
if( ($department!=0) && ($department!="")){ $where = " WHERE recno='$department' "; }
$query = "SELECT * FROM livehelp_departments $where ";
$data_d = $mydatabase->select($query);  
$department_a = $data_d[0];
$department = $department_a[recno];
$creditline  = $department_a[creditline];

/// make sure the department is right.
  $query = "UPDATE livehelp_users set department='$department' WHERE identity='$identity'";	
  $mydatabase->sql_query($query);
  
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
   // i am typing messages.. 
   $query = "DELETE FROM livehelp_messages WHERE message='<font color=AAAAAA>typing message...</font>'";
   $mydatabase->sql_query($query); 
      
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
    
// lets do a little cleanning ..  
   $prev = mktime ( date("H"), date("i")-5, date("s"), date("m"), date("d"), date("Y") );
   $oldtime = date("YmdHis",$prev);
   $prev = mktime ( date("H"), date("i")-50, date("s"), date("m"), date("d"), date("Y") );
   $reallyoldtime = date("YmdHis",$prev);
   $query = "SELECT * FROM livehelp_users WHERE isoperator='N' AND lastaction<$oldtime";
   $old_people = $mydatabase->select($query);   

   for($i=0;$i< count($old_people); $i++){
     $old_user = $old_people[$i];
     $trans = gettrans($old_user[onchannel]);

     // if we talked to them add them to the transcripts. 
     if( ($trans != "") && ($old_user[isnamed] == "Y")){
       $trans = ereg_replace("'","",$trans);
       $query = "INSERT INTO livehelp_transcripts (who,daytime,transcript) VALUES ('$old_user[username]','$old_user[lastaction]','$trans')";
       $mydatabase->insert($query);
     }
     // get rid of old messages.
     $query = "DELETE FROM livehelp_messages WHERE channel='$old_user[onchannel]'";
     $mydatabase->sql_query($query);      
          
     $dayof = date("Ymd");
     
     // move all old data into referer tracking database.. 
     $camefrom = ereg_replace("'","",$old_user[camefrom]);
     $query = "SELECT * FROM livehelp_referers WHERE camefrom='$camefrom' AND dayof='$dayof'";
     $count_a = $mydatabase->select($query);
     if(count($count_a) == 0){     	
       // update the total.
       $query = "SELECT * FROM livehelp_referers_total WHERE camefrom='$camefrom'";
       $tmp = $mydatabase->select($query);
       if( count($tmp) == 0){
       	  $query = "INSERT INTO livehelp_referers_total (camefrom,ctotal) VALUES ('$camefrom','1') ";      
          $mydatabase->insert($query);
       } else {
         $tmp = $tmp[0];
         $ctotal = $tmp[ctotal] + 1;
         $query = "UPDATE livehelp_referers_total set ctotal='$ctotal' WHERE camefrom='$camefrom' ";      
         $mydatabase->insert($query);
       }
       $query = "INSERT INTO livehelp_referers (camefrom,dayof,uniquevisits) VALUES ('$camefrom','$dayof','1') ";      
       $mydatabase->insert($query);
     } else {
      $count_d = $count_a[0];
      $uniquevisits = $count_d[uniquevisits] + 1;  
      $query = "SELECT * FROM livehelp_referers_total WHERE camefrom='$camefrom'";
      $tmp = $mydatabase->select($query); 
      $tmp = $tmp[0];    
      $ctotal = $tmp[ctotal] + 1;     
      $query = "UPDATE livehelp_referers set uniquevisits='$uniquevisits' WHERE camefrom='$camefrom' AND dayof='$dayof' ";      
      $mydatabase->sql_query($query); 
      $query = "UPDATE livehelp_referers_total set ctotal='$ctotal' WHERE camefrom='$camefrom'";      
      $mydatabase->sql_query($query);                    	
     }

     // move all old data into visit tracking database.. 
     if($dbtype == "txt-db-api.php"){
       $query = "SELECT location FROM livehelp_visit_track WHERE id='$old_user[user_id]'";
     } else {
       $query = "SELECT DISTINCT location FROM livehelp_visit_track WHERE id='$old_user[user_id]'";
     }
     $footsteps = $mydatabase->select($query);
     for($j=0;$j<count($footsteps); $j++){
     	$foot = $footsteps[$j];
     	$pageurl = $foot[location];
     	$pathstuff = split("\?",$pageurl);
        $pageurl = $pathstuff[0];

        $query = "SELECT * FROM livehelp_visits WHERE pageurl='$pageurl' AND dayof='$dayof'";
        $count_a = $mydatabase->select($query);
        if(count($count_a) == 0){     	
          // update the total.
          $query = "SELECT * FROM livehelp_visits_total WHERE pageurl='$pageurl'";
          $tmp = $mydatabase->select($query);
          if( count($tmp) == 0){
       	     $query = "INSERT INTO livehelp_visits_total (pageurl,ctotal) VALUES ('$pageurl','1') ";      
             $mydatabase->insert($query);
          } else {
             $tmp = $tmp[0];
             $ctotal = $tmp[ctotal] + 1;
             $query = "UPDATE livehelp_visits_total set ctotal='$ctotal' WHERE pageurl='$pageurl' ";      
             $mydatabase->insert($query);
          }
       $query = "INSERT INTO livehelp_visits (pageurl,dayof,uniquevisits) VALUES ('$pageurl','$dayof','1') ";      
       $mydatabase->insert($query);
     } else {
      $count_d = $count_a[0];
      $uniquevisits = $count_d[uniquevisits] + 1;  
      $query = "SELECT * FROM livehelp_visits_total WHERE pageurl='$pageurl'";
      $tmp = $mydatabase->select($query); 
      $tmp = $tmp[0];    
      $ctotal = $tmp[ctotal] + 1;     
      $query = "UPDATE livehelp_visits set uniquevisits='$uniquevisits' WHERE pageurl='$pageurl' AND dayof='$dayof' ";      
      $mydatabase->sql_query($query); 
      $query = "UPDATE livehelp_visits_total set ctotal='$ctotal' WHERE pageurl='$pageurl'";      
      $mydatabase->sql_query($query);                    	
     }
     }
     
     // let get rid of the temp data..
     $query = "DELETE FROM livehelp_visit_track WHERE id='$old_user[user_id]' ";
     $mydatabase->sql_query($query); 
     $query = "DELETE FROM livehelp_users WHERE user_id='$old_user[user_id]' ";
     $mydatabase->sql_query($query);  
     $query = "DELETE FROM livehelp_channels WHERE user_id='$old_user[user_id]' ";
     $mydatabase->sql_query($query);        
     $query = "DELETE FROM livehelp_messages WHERE timeof<'$reallyoldtime'";
     $mydatabase->sql_query($query);      
   }
       
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

     $query = "INSERT INTO livehelp_users (onchannel,identity,lastaction,status,username,isoperator,password,department,camefrom) VALUES ('-1','$identity','$lastaction','Visiting','$username','N','','$department','$referrer')";
     $mydatabase->insert($query);
     //print $query;
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
}

//give credit to the programmer .. 
//----------------------------------------------------------------
if($cmd == "getcredit"){
	 	
   $query = "SELECT * FROM livehelp_users,livehelp_operator_departments WHERE livehelp_users.user_id=livehelp_operator_departments.user_id AND livehelp_users.isonline='Y' AND livehelp_users.isoperator='Y' AND livehelp_operator_departments.department='$department' ";
   $data = $mydatabase->select($query);  
  if( count($data) != 0){
    // see if they left their computer but did not log off.. 
    $prev = mktime ( date("H"), date("i")-10, date("s"), date("m"), date("d"), date("Y") );
    $oldtime = date("YmdHis",$prev);
    $query = "UPDATE livehelp_users set isonline='N',status='offline' WHERE isoperator='Y' AND lastaction<'$oldtime'";
    $mydatabase->sql_query($query); 
    $mydatabase->close_connect();
        if( ($department_a[creditline] == "L") || ($department_a[creditline] == "")){
       Header("Location: livehelp.gif");     
    } 
    if($department_a[creditline] == "W"){
       Header("Location: livehelp2.gif");   
    }
    if($department_a[creditline] == "N"){
       Header("Location: offline.gif");   
    }  
  } else {  
    if($department_a[leaveamessage] == "YES"){
    $mydatabase->close_connect();
    if( ($department_a[creditline] == "L") || ($department_a[creditline] == "")){
       Header("Location: livehelp.gif");     
    } 
    if($department_a[creditline] == "W"){
       Header("Location: livehelp2.gif");   
    }
    if($department_a[creditline] == "N"){
       Header("Location: offline.gif");   
    }    
   } else {	
	
      $mydatabase->close_connect();
      Header("Location: offline.gif");  
    }
  }
}

// are we online or not.. 
//----------------------------------------------------------------
if($cmd == "getstate"){
	 	
   $query = "SELECT * FROM livehelp_users,livehelp_operator_departments WHERE livehelp_users.user_id=livehelp_operator_departments.user_id AND livehelp_users.isonline='Y' AND livehelp_users.isoperator='Y' AND livehelp_operator_departments.department='$department' ";
   $data = $mydatabase->select($query);     

if($hide == "Y"){
   Header("Location: offline.gif");  
} else {
  if( count($data) != 0){
    // see if they left their computer but did not log off.. 
    $prev = mktime ( date("H"), date("i")-2, date("s"), date("m"), date("d"), date("Y") );
    $oldtime = date("YmdHis",$prev);
    $query = "UPDATE livehelp_users set isonline='N',status='offline' WHERE isoperator='Y' AND lastaction<'$oldtime'";
    $mydatabase->sql_query($query); 
    $mydatabase->close_connect();
    Header("Location: $department_a[onlineimage]");   
  } else {   
      $mydatabase->close_connect();
   if($department_a[leaveamessage] == "YES"){
     if($department_a[qa_enabled] == "Y"){
       Header("Location: $department_a[qaimage]");  
     } else {
       Header("Location: $department_a[offlineimage]");      
     }
   } else {	
     Header("Location: offline.gif");  
   }
  }
 }
}






?>