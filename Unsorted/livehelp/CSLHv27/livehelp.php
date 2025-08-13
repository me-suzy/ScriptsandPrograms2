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
 $identity = ereg_replace(" ","",$identity);
}


$query = "SELECT * FROM livehelp_users WHERE identity='$identity'";
$person_a = $mydatabase->select($query);  
$person = $person_a[0];

// get department information...
   if($department!=""){ $where = " WHERE recno='$department' "; }
   $query = "SELECT * FROM livehelp_departments $where ";
   $data_d = $mydatabase->select($query);  
   $department_a = $data_d[0];
   $department = $department_a[recno];
   $qa_enabled = $department_a[qa_enabled];

// see if anyone is online . if not send them to the leave a message page..
$query = "SELECT * FROM livehelp_users,livehelp_operator_departments WHERE livehelp_users.user_id=livehelp_operator_departments.user_id AND livehelp_users.isonline='Y' AND livehelp_users.isoperator='Y' AND livehelp_operator_departments.department='$department' ";
$data = $mydatabase->select($query);  

if( count($data) == 0){

 // if they clicked on the live help tab.
 if(($tab == "") || ($tab == 1) ){
  $doubleframe = "yes";
  $page = "offline.php";
 } 
 
 // else go to default tab id.. 
if($tab == ""){
   $doubleframe = "yes";  
   $query = "SELECT * FROM livehelp_modules_dep,livehelp_modules WHERE livehelp_modules_dep.modid=livehelp_modules.id AND defaultset='Y' AND departmentid='$department'";	
   $data = $mydatabase->select($query);
   if(count($data) == 0){
     $doubleframe = "yes";
     $page = "offline.php";
     $tab = 1;
   } else {
    $row = $data[0];
    $page = $row[path];
    $page .= "?department=$department&referer=$person[camefrom]&" . $row[query_string];
    $tab = $row[id];
   }
 } else {
    $query = "SELECT * FROM livehelp_modules WHERE id='$tab'";	
    $data = $mydatabase->select($query);
    $row = $data[0];
    $page .= "?department=$department&referer=$person[camefrom]&" . $row[query_string]; 	
 }
}
if($tab == ""){ $tab = 1;}
if($tab != 1){
  $doubleframe = "yes";
}

if( count($data) != 0){
  
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

// if the user was invited. change status to invited.
if($status == "request"){
  $query = "UPDATE livehelp_users set status='invited' WHERE identity='$identity'";	
  $mydatabase->sql_query($query);
}

/// make sure the department is right.
  $query = "UPDATE livehelp_users set department='$department' WHERE identity='$identity'";	
  $mydatabase->sql_query($query);

// if the user requested an exit..
if($action == "leave"){
  if($visiting ==""){
    $query = "UPDATE livehelp_users set status='stopped' WHERE identity='$identity'";	
  } else {
    $query = "UPDATE livehelp_users set status='wentaway' WHERE identity='$identity'";	
  }
  $mydatabase->sql_query($query);
  $mydatabase->close_connect();
  print "<br><br><br><br><center><font color=990000 size=+2>Session CLOSED.</font><br><br><a href=javascript:window.close()>Close this window</a></center>"; 
  if($autoclose != ""){
    print "<SCRIPT> window.close(); </SCRIPT>";
  }
  exit;
}

// skip ask for name if that is set.
if($department_a[requirename] != "Y"){
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
}

if($doubleframe == "yes"){
?>
<SCRIPT>
function exitchat(){
 <? if ($isnamed == "Y"){ ?>
  window.open('wentaway.php?action=leave&autoclose=Y&visiting=Y&department=<?= $department ?>', 'ch54050872', 'width=40,height=90,menubar=no,scrollbars=0,resizable=1');
 <? } ?>
}
</SCRIPT>
<frameset rows="36,*" border="0" frameborder="0" framespacing="0" spacing="0" onunload="exitchat()">
<frame src="user_top.php?department=<?= $department ?>&tab=<?= $tab?>" name="1a" scrolling="no" border="0" marginheight="0" marginwidth="0" NORESIZE>
<frame src="<?= $page ?>" name="1b" scrolling="AUTO" border="0" marginheight="0" marginwidth="0" NORESIZE>
</frameset>
<?
} else {
?><SCRIPT>
function exitchat(){
 <? if ($isnamed == "Y"){ ?>
  window.open('livehelp.php?action=leave&autoclose=Y&department=<?= $department ?>', 'ch54050872', 'width=540,height=390,menubar=no,scrollbars=0,resizable=1');
 <? } ?>
}
</SCRIPT>
<frameset rows="36,*,55" border="0" frameborder="0" framespacing="0" spacing="0" onunload="exitchat()">
<frame src="user_top.php?department=<?= $department ?>&channel=<?= $channel ?>&t=<?= $lastaction ?>&myid=<?= $myid ?>&tab=<?= $tab ?>" name="1a" scrolling="no" border="0" marginheight="0" marginwidth="0" NORESIZE>
<frame src="user_chat.php?department=<?= $department ?>&channel=<?= $channel ?>&t=<?= $lastaction ?>&myid=<?= $myid ?>" name="1b" scrolling="AUTO" border="0" marginheight="0" marginwidth="0" NORESIZE>
<frame src="user_bot.php?department=<?= $department ?>&channel=<?= $channel ?>&t=<?= $lastaction ?>&myid=<?= $myid ?>" name="1c" scrolling="no" border="0" marginheight="0" marginwidth="0" NORESIZE>
</frameset>
<? } ?>