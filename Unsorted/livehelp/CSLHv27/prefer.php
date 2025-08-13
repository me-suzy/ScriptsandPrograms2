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

$username = $username;
checkuser();
    $pass = $username;
if($action == "update"){
  if($mypassword == $mypassword2){
    $query = "UPDATE livehelp_users SET email='$email',username='$myusername',password='$mypassword' WHERE username='$username'";
    $mydatabase->sql_query($query);
    print "<font color=007700 size=+2>DATABASE UPDATED.. </font>";
    $username = $myusername;
    $pass = $mypassword;
    
  }
}
      $query = "SELECT * FROM livehelp_users WHERE username='$username' ";
      $data = $mydatabase->select($query);
      $data = $data[0];
      $email = $data[email];
      $pass = $data[password];
?>
<body bgcolor=FFFFEE>
<b>User Settings:</b>
<br><hr>
<form action=prefer.php method=post>
<input type=hidden name=action value=update>
<table>
<tr><td><b>Username:</b></td><td><input type=text name=myusername value="<?= $username ?>" ></td></tr>
<tr><td><b>email:</b></td><td><input type=text size=50 name=email value="<?= $email ?>" ></td></tr>
<tr><td><b>password:</b></td><td><input type=password name=mypassword value="<?= $pass ?>" ></td></tr>
<tr><td><b>password (again):</b></td><td><input type=password name=mypassword2 value="<?= $pass ?>" ></td></tr>
</table>
<input type=submit value=UPDATE>
</body>
<?
$mydatabase->close_connect();
?>