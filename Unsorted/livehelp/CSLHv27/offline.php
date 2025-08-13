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

// get department information...
   if($department!=""){ $where = " WHERE recno=$department "; }
   $query = "SELECT * FROM livehelp_departments $where ";
   $data_d = $mydatabase->select($query);  
   $department_a = $data_d[0];
   $department = $department_a[department];
   
// see if anyone is online..
 $query = "SELECT * FROM livehelp_users,livehelp_operator_departments WHERE livehelp_users.user_id=livehelp_operator_departments.user_id AND livehelp_users.isonline='Y' AND livehelp_users.isoperator='Y' AND livehelp_operator_departments.department='$department' ";
 $data = $mydatabase->select($query); 
if(count($data) != 0){
  $doubleframe = "yes";  
  $query = "SELECT * FROM livehelp_modules_dep,livehelp_modules WHERE livehelp_modules_dep.modid=livehelp_modules.id AND defaultset='Y' AND departmentid='$department'";	
  $data = $mydatabase->select($query);
  if(count($data) == 0){
     $doubleframe = "yes";
     $page = "offline.php?department=$department";
     $tab = 1;
   } else {
    $row = $data[0];
    $page = $row[path];
    $tab = $row[id];
   }
Header("Location: $page");
exit;	
}

?>
<body bgcolor=FFFFC0>
<br><Br><bR>
<blockquote>
<?= $department_a[offline] ?>
</blockquote>
</body>
<?

$mydatabase->close_connect();
?>