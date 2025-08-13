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

?>
<body bgcolor=D3DBF1>
<center>
<table border=0 cellpadding=0 cellspacing=0 width=590>
<?
if($goforit){
if (($regnum < 10000) || (($regnum % 7)!= 0) ){
  print "<b>Invaild Registration number";
} else {
  $query = "UPDATE livehelp_config set membernum='$regnum'";
  $mydatabase->sql_query($query);
  print "<br><br><br><table width=600 bgcolor=FFFFFF><tr><td><br><br><font color=007700 size=+6>Thank you</font><br><br></td></tr></table>";
}
} else {
if($membernum >10000){
print "<tr><td bgcolor=FFFFC0><b>Your program has already been registered.</td></tr>";
} else {
print "<center><a href=admin.php target=_top><img src=images/back_s.gif border=0>Click Here to return to menu.</a><br></center>";
?>
<tr><td bgcolor=EFEF9D><b>CSLH Program Registration</b></td></tr>
<tr><td bgcolor=FFFFC0><ul>
In order to activate your registration, enter in your registration ID 
provided in the email sent to you after you submitted the registration.
<form action=registerit.php method=post>
<input type=hidden value=now name=goforit>
<b>Registration Number:</b><br><input type=text name=regnum size=15><input type=submit value=activate>
</form>
</ul>
</td></tr>
<tr><td bgcolor=EFEF9D><b>How much is a Registration?</b></td></tr>
<tr><td bgcolor=FFFFC0><ul>
IT IS FREE !! However donations to help support this program and 
keep it moving are needed and accepted.. <br>
</td></tr>
<tr><td bgcolor=EFEF9D><b><a name=why>How to I register this program?</a></b></td></tr>
<tr><td bgcolor=FFFFC0>
<?
$sql = "SELECT * FROM livehelp_users WHERE isadmin='Y' order by user_id";
$data = $mydatabase->select($sql);
$row = $data[0];
?>
<a href=http://www.craftysyntax.com/livehelp/registration.php?v=<?= $version ?>&e=<?= $row[email] ?>&db=<?= $dbtype ?>><font size=+3>CLICK HERE TO BEGIN REGISTRATION</font></a> 
</td></tr> 
<?	
}}
?>
</table>
<br><br>
<?
 $mydatabase->close_connect();
?>