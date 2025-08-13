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
if($isadminsetting != "Y"){
 print "sorry only admin users have access to this... ";
$mydatabase->close_connect();
 exit;	
}
?>
<body bgcolor=FFFFEE><center>

<table bgcolor=DDDDDD width=600><tr><td>
<b>Tabs:</b> 
</b></td></tr></table>
<table width=600>
<tr bgcolor=FFFFFF><td><b>Tabs Name</b></td><td><b>path</b></td><td><b>Options:</b></td></tr>
<?
if($updatemod != ""){
  $query = "UPDATE livehelp_modules SET name='$name',path='$path',adminpath='$adminpath',query_string='$query_string' WHERE id='$updatemod'";
  $mydatabase->sql_query($query);  
}
if($newmodinsert != ""){
  $query = "INSERT INTO livehelp_modules (name,path,adminpath,query_string) VALUES ('$name','$path','$adminpath','$query_string')";
  $mydatabase->insert($query); 	
}

if($delmod != ""){
  $query = "DELETE FROM livehelp_modules WHERE id=$delmod ";
  $mydatabase->sql_query($query); 	
  $query = "DELETE FROM livehelp_modules_dep WHERE modid=$delmod ";
  $mydatabase->sql_query($query); 
}

$query = "SELECT * FROM livehelp_modules ";
$data = $mydatabase->select($query);
for($i=0;$i< count($data); $i++){
 $row = $data[$i];
 if($bgcolor=="EFEF9D"){ $bgcolor="FFFFC0"; } else { $bgcolor="EFEF9D"; }
 print "<tr bgcolor=$bgcolor><td>$row[name]</b></td><td>$row[path]</td><td><b><a href=modules.php?edmod=$row[id]>Edit</a>";
 if($row[id] != 1){ print " <a href=modules.php?delmod=$row[id] ><font color=990000>Delete</font></a>"; }
 if($row[adminpath] != ""){  print "  <a href=$row[adminpath] ><font color=009999>ADMIN</font></a>"; } 
 print "</b></td></tr>\n";
}
 ?>
</table>
<? if ($newmod == ""){ ?> 
<a href=modules.php?newmod=1>+ Add A Tab</a>
<? } 
if ($edmod != ""){
	
$query = "SELECT * FROM livehelp_modules WHERE id='$edmod'";
$data = $mydatabase->select($query);
$modulerow = $data[0];

}
if ( ($newmod != "") || ($edmod != "")){
 ?>
<table width=600><tr><td>
<h2>ADD/EDIT Tab:</h2>
  <form action=modules.php method=post> 
<? if ($edmod != ""){ ?>
  <input type=hidden name=updatemod value=<?= $modulerow[id] ?>>
<? } else { ?>
  <input type=hidden name=newmodinsert value=yes>
<? } ?>
<table width=100% bgcolor=FFFFCC><tr><td>
<b>Name of the Tab:</b>
</td></tr></table>
The name of the tab is what appears on the pop-up window for the user. This should 
be something short like "Live Help" or "Message Board" or "Contact"<br>
<b>Name:</b> <input type=text name=name  MAXLENGTH=20 size=20 value="<?= $modulerow[name] ?>"><br>
<table width=100% bgcolor=FFFFCC><tr><td>
<b>User side URL :</b> (path to the application)
</td></tr></table>
This is the url to the application. do not include a query string (for example
do not enter <i>http://www.mywebsite.com/myapplication.php<font color=990000><s>?somthing=somethingelse</s></font></i>
This should be a simple url to the application such as http://www.mywebsite.com/myapplication.php a query
string with informaion from the user will be generated. <br>
<b>User URL Path:</b><input type=text name=path size=45 value="<?= $modulerow[path] ?>" ><br>
<table width=100% bgcolor=FFFFCC><tr><td>
<b>Admin side URL :</b> (Optional administration path to the application)
</td></tr></table>
This is the url to the administration side of the application. <br>
<b>Admin URL Path:</b><input type=text name=adminpath size=45 value="<?= $modulerow[adminpath] ?>" ><br>
<table width=100% bgcolor=FFFFCC><tr><td>
<b>Query Sting to user side URL :</b> (optional)
</td></tr></table>
This is an optional query string of variables to pass to the front END of the application.
this should be a string such as <b>myvar=thisvalue&anotheritem=thisvalue</b>
<b>query string:</b><input type=text name=query_string size=45 value="<?= $modulerow[query_string] ?>" ><br>
<br>
<input type=submit name=CREATE>
  </form>  
</td></tr></table>
<? } ?>
<br><br>
<pre>


</pre>
<font size=-2>
<!-- Note if you remove this line you will be violating the license even if you have modified the program -->
Crafty Syntax Live Help © 2003 by <a href=http://www.craftysyntax.com/>Eric Gerdes</a>  
<br>
CSLH is  Software released 
under the <a href=http://www.gnu.org/copyleft/gpl.html target=_blank>GNU/GPL license</a> [<a href=rules.php>more info</a>]
</font>