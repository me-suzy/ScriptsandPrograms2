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
$lastaction = date("Ymdhis");
$startdate =  date("Ymd");

$username = $username;
checkuser();

$pass = $username;

$query = "SELECT * FROM livehelp_users WHERE username='$username'";
$data = $mydatabase->select($query);
$row = $data[0];
$isadminsetting = $row[isadmin];
if($isadminsetting != "Y"){
 print "sorry you do not have access to this... ";
$mydatabase->close_connect();
 exit;	
}

if($action == "addnew"){
 $query = "INSERT INTO livehelp_users (username,password,isoperator,isadmin,isnamed,email) VALUES ('$newuser','$newpass','Y','$isadmin','Y','$email')";	
 $mydatabase->sql_query($query);	
}
if($remove != ""){
  $query = "DELETE FROM livehelp_users WHERE user_id='$remove'";	
  $mydatabase->sql_query($query);
}

if($action == "update"){
    $query = "UPDATE livehelp_config SET offset='$offset',use_flush='$newuse_flush',leaveamessage='$newleaveamessage',messageemail='$newmessageemail',opening='$newopening',needname='$newneedname'";
    $mydatabase->sql_query($query);
    print "<font color=007700 size=+2>DATABASE UPDATED.. </font>";  
$mydatabase->close_connect();
    exit;  
}

if($clearall == "YES"){
 $query = "DELETE FROM livehelp_operator_channels";
 $mydatabase->sql_query($query);  
 $query = "DELETE FROM livehelp_users WHERE isoperator='N'";
 $mydatabase->sql_query($query);  
 $query = "DELETE FROM livehelp_messages";
 $mydatabase->sql_query($query); 
 $query = "DELETE FROM livehelp_visit_track";
 $mydatabase->sql_query($query); 
 $query = "DELETE FROM livehelp_channels";
 $mydatabase->sql_query($query);  
 print "<font color=007700 size=+2>ALL DATA CLEARED...</font>";
}

?>
<body bgcolor=FFFFEE><center>
<table bgcolor=DDDDDD width=600><tr><td>
<b>Clean Up:</b></td></tr></table>
<a href=mastersettings.php?clearall=YES>CLICK HERE TO CLEAR ALL OLD MESSAGES and USERS..</a>
<br><br><table bgcolor=DDDDDD width=600><tr><td>
<b>Operators:</b>
</b></td></tr></table>
<table border=1>
<tr><td><b>username</b></td><td><b>timestamp</b></td><td><b>admin</b></td><td>delete</td></tr>
<? 
$query = "SELECT * FROM livehelp_users WHERE isoperator='Y' ";
$data = $mydatabase->select($query);
for($i=0;$i< count($data); $i++){
 $row = $data[$i];
 print "<tr><td>$row[username]</td><td>$row[lastaction]</td><td>$row[isadmin]</td><td><a href=mastersettings.php?remove=$row[user_id]>";
 if( count($data) != 1){
   print "<font color=990000>remove</font>";
 }
 print "</a></td></tr>\n"; 	
}


$query = "SELECT * FROM livehelp_config";
$data = $mydatabase->select($query);
$data = $data[0];
$offset = $data[offset];

if($data[needname] == "no"){
 $needname_s_y  = ""; 
 $needname_s_n  = " CHECKED ";  
} else {
 $needname_s_y  = " CHECKED "; 
 $needname_s_n  = "";  	
}
if($data[use_flush] == "YES"){
$continuous = " SELECTED ";
$refresh = "";
} else {
$continuous = "";
$refresh = " SELECTED ";	
}

if($data[leaveamessage] == "no"){
$leaveamessage_s_n = " CHECKED ";
$leaveamessage_s_y = "";
} else {
$leaveamessage_s_n = "";
$leaveamessage_s_y = " CHECKED ";	
}

?>
</table>
<br><table width=600><tr><td>
<form action=mastersettings.php method=post>
<input type=hidden name=action value=addnew>
ADD a new operator: <br>
username:<input type=text name=newuser size=30><br>
password:<input type=text name=newpass size=30><br>
email:<input type=text name=email size=30><br>
Admin access:<input type=checkbox name=isadmin value=Y><br><input type=submit value=ADD><br>
</td></tr></table><br>
</form><table width=600<tr bgcolor=DDDDDD><td>
<b>Config settings:</b></td></tr>
<tr bgcolor=FFFFFF><td>
<form action=mastersettings.php method=post>
<input type=hidden name=action value=update>
<b>Chat Type:</b><select name=newuse_flush>
<option value=no <?= $refresh ?> > Refresh</option>
<option value=YES <?= $continuous ?> > Continuous </option>
</select><br>
<b>Time Offset:</b><select name=offset>
<option value=<?= $offset ?>><?= $offset ?></option>
<option value=<?= $offset ?>>---</option>
<? for($i=-12;$i<13; $i++){ 
print "<option value=$i>$i</option>\n";
} 
?>
</select><br>
<b>Live help title:</b><input type=text name=newsite_title value="<?= $site_title ?>"><br>
<b>Ask for name on load:</b>Yes <input type=radio name=newneedname value=YES <?= $needname_s_y ?> > no <input type=radio name=newneedname value=no  <?= $needname_s_n ?>> <br>
<b>Leave a message if not online:</b> Yes <input type=radio name=newleaveamessage value=YES <?= $leaveamessage_s_y ?> > no <input type=radio name=newleaveamessage value=no  <?= $leaveamessage_s_n ?>> <br>
<b>message email:</b><input type=text size=25 name=newmessageemail value="<?= $messageemail ?>"><br>
opening message if ask for name is set to Yes:<br>
 <textarea name=newopening rows=7 cols=40>
 <?= $opening ?>
 </textarea><br>
<input type=submit value=UPDATE></td></tr></table>
</body>
<?
$mydatabase->close_connect();
?>