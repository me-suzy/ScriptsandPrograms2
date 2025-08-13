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

if($installed==false){
 Header("Location: setup.php");	
} else {
 $mydatabase->close_connect();
}
?>
<h2>CS Live Help Version <?= $version ?> Options:</h2>
<ul>
<li><a href=admin.php>Log into Admin to monitor channels</a>
<li><a href=example.php> Example how can I help you page and HTML CODE </a>
<li><a href=http://www.craftysyntax.com/support/?c=3>Online Support Form</a>
<li><a href=http://www.craftysyntax.com/livehelp/>Upgrades and Downloads Page</a>
<li><a href=http://www.craftysyntax.com/livehelp/donations.php>About the program and donations.</a>
</ul>