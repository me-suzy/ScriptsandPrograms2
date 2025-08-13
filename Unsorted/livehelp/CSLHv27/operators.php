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
$lastaction = date("Ymdhis");
$startdate =  date("Ymd");

checkuser();
$pass = $username;

$query = "SELECT * FROM livehelp_users WHERE username='$username'";
$data = $mydatabase->select($query);
$row = $data[0];
$isadminsetting = $row[isadmin];
$myid = $row[user_id];

if($action == "addnew"){
 $query = "INSERT INTO livehelp_users (username,password,isoperator,isadmin,isnamed,email,user_alert,show_arrival) VALUES ('$newuser','$newpass','Y','$isadmin','Y','$email','$user_alert','$show_arrival')";	
 $user_id = $mydatabase->insert($query);

 $query = "UPDATE livehelp_users set onchannel=user_id where isadmin='Y'";	
 $mydatabase->sql_query($query);
  
 $query = "SELECT * FROM livehelp_users ORDER BY user_id DESC LIMIT 1";
 $channel_a = $mydatabase->select($query);
 $channel_a = $channel_a[0];
 $user_id = $channel_a[user_id];

  $query = "SELECT * FROM livehelp_departments";
  $data = $mydatabase->select($query);
  for($i=0;$i< count($data); $i++){
    $row = $data[$i];
    $varname = "mydepartment_" . $row[recno]; 
    if($$varname != "") {  
        $query = "INSERT INTO livehelp_operator_departments (user_id,department) VALUES ('$user_id','$row[recno]')";	
        $mydatabase->insert($query);
    }	
  }	

}
if($remove != ""){
  $query = "DELETE FROM livehelp_users WHERE user_id='$remove'";	
  $mydatabase->sql_query($query);
  $query = "DELETE FROM livehelp_operator_departments WHERE user_id='$remove'";	
  $mydatabase->sql_query($query);
}

if($action == "updateuser"){
    $query = "UPDATE livehelp_users SET user_alert='$user_alert',show_arrival='$show_arrival',username='$newuser',password='$newpass',isadmin='$isadmin',email='$email' WHERE user_id='$who' ";
    $mydatabase->sql_query($query);
    $query = "DELETE FROM livehelp_operator_departments WHERE user_id='$who'";
    $mydatabase->sql_query($query);

  $query = "SELECT * FROM livehelp_departments";
  $data = $mydatabase->select($query);
  for($i=0;$i< count($data); $i++){
    $row = $data[$i];
    $varname = "mydepartment_" . $row[recno]; 
    if($$varname != "") {  
        $query = "INSERT INTO livehelp_operator_departments (user_id,department) VALUES ('$who','$row[recno]')";	
        $mydatabase->insert($query);
    }	
  }


    print "<font color=007700 size=+2>DATABASE UPDATED.. </font>";  
$mydatabase->close_connect();
    exit;  
}
?>
<body bgcolor=FFFFEE><center>
<? if ( ($addmenu != "Y" ) && ($editit == "") ){ ?>
<table border=0>
<tr bgcolor=FFFFFF><td><b>Username</b></td><td><b>Department(s)</b></td><td><b>Admin</b></td><td><b>e-mail</b></td><td><b>Actions</b></td></tr>
<? 
$query = "SELECT * FROM livehelp_users WHERE isoperator='Y' ";
$data = $mydatabase->select($query);
for($i=0;$i< count($data); $i++){
 $row = $data[$i];
   if($bgcolor=="EFEF9D"){ $bgcolor="FFFFC0"; } else { $bgcolor="EFEF9D"; }
 print "<tr bgcolor=$bgcolor><td>$row[username]</td><td>";
 $query = "SELECT * FROM livehelp_users,livehelp_operator_departments WHERE livehelp_users.user_id=livehelp_operator_departments.user_id AND livehelp_users.user_id='$row[user_id]' ";
 $data2 = $mydatabase->select($query);
 for($j=0;$j< count($data2); $j++){
  $dept = $data2[$j];	
  $query = "SELECT * FROM livehelp_departments WHERE recno='$dept[department]' ";
  $data3 = $mydatabase->select($query);
  $data3 =  $data3[0];   
  print "$data3[nameof]<br>";
 }
 print "</td><td>$row[isadmin]</td><td>$row[email]</td><td>";
 if( ($isadminsetting == "Y") || ($myid == $row[user_id]) ) { print "<a href=operators.php?editit=$row[user_id]>Edit</a> "; }
 if($isadminsetting == "Y"){ print "<a href=operators.php?remove=$row[user_id]><font color=990000>remove</font>"; }
 print "</a></td></tr>\n"; 	
}
?>
</table>
<? 
if($isadminsetting == "Y"){	
  print "<a href=operators.php?addmenu=Y>ADD A NEW OPERATOR</a>";
 }
}
if( ($isadminsetting == "Y") && ( ($addmenu == "Y" ) || ($editit != "") ) ){ 
?>
<br><table width=600><tr><td>
<form action=operators.php method=post>
<? 
if($editit != ""){
 $query = "SELECT * FROM livehelp_users where user_id='$editit' "; 
 $data = $mydatabase->select($query);
 $userinfo = $data[0];	
?>
<input type=hidden name=action value=updateuser>
<input type=hidden name=who value=<?= $editit ?> >
<table bgcolor=FFFFC0>
<tr><td bgcolor=FFFFFF colspan=2>UPDATE operator:</td></tr>
<? } else { ?>
<input type=hidden name=action value=addnew>
<table bgcolor=FFFFC0>
<tr><td bgcolor=FFFFFF colspan=2>ADD a new operator:</td></tr>
<? } ?>
<tr><td>username:</td><td><input type=text name=newuser size=30 value="<?= $userinfo[username] ?>"></td></tr>
<tr><td>password:</td><td><input type=text name=newpass size=30 value="<?= $userinfo[password] ?>"></td></tr>
<tr><td>email:</td><td><input type=text name=email size=30 value="<?= $userinfo[email] ?>"></td></tr>
<?
if ($userinfo[show_arrival] == "N"){ $show_arrival_n = " CHECKED "; } else { $show_arrival_y = " CHECKED "; }
if ($userinfo[user_alert] == "Y"){ $user_alert_y = " CHECKED "; }
if ($userinfo[user_alert] == "N"){ $user_alert_n = " CHECKED "; }
if ($userinfo[user_alert] == "E"){ $user_alert_e = " CHECKED "; }

if ($userinfo[isadmin] == "Y"){ $isadmin_y = " CHECKED "; }
?>
<tr><td>Alert of visitors:</td><td><input type=radio name=show_arrival value=Y <?= $show_arrival_y ?> >YES <input type=radio name=show_arrival value=N <?= $show_arrival_n ?>>NO</td></tr>
<tr><td>Sound:</td><td><input type=radio name=user_alert value=Y <?= $user_alert_y ?> > JS alert <input type=radio name=user_alert value=N <?= $user_alert_n ?> > EMBED wav sound</td></tr>
<tr><td>Admin access:</td><td><input type=checkbox name=isadmin value=Y <?= $isadmin_y ?> ></td></tr>

<tr><td colspan=2>Departments:<ul>
<?
$query = "SELECT * FROM livehelp_departments";
$data = $mydatabase->select($query);
for($i=0;$i< count($data); $i++){
  $dept = $data[$i];
  $query = "SELECT * FROM livehelp_operator_departments WHERE user_id='$userinfo[user_id]' and department='$dept[recno]' ";
  $check = $mydatabase->select($query);
  if(count($check) == 0){ $checked =""; } else { $checked= " CHECKED "; }
  print "<input type=checkbox name=mydepartment_$dept[recno] value=$dept[recno] $checked><b> $dept[nameof] </b><br>";
}


?>
</td></tr>
</table><br>
<input type=submit value=ADD/UPDATE><br>
</td></tr></table><br>
</form>
<?
}

$mydatabase->close_connect();
?>
<pre>


</pre>
<font size=-2>
<!-- Note if you remove this line you will be violating the license even if you have modified the program -->
Crafty Syntax Live Help © 2003 by <a href=http://www.craftysyntax.com/>Eric Gerdes</a>  
<br>
CSLH is  Software released 
under the <a href=http://www.gnu.org/copyleft/gpl.html target=_blank>GNU/GPL license</a> [<a href=rules.php>more info</a>]
</font>