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
$mydatabase->close_connect();
?>

<script language="javascript" src="<?= $webpath ?>/livehelp.js"></script>
<br>
<b>HOW TO ADD LIVE HELP ICON TO YOU PAGES:</b>
<br>
All that you have to do is cut and paste the following HTML code
into all of the webpages that you wish to have the live help .
copy all of the HTML that is in the yellow box below.<br>
<table width=700 bgcolor=FFFFEE border=1>
<tr><td NOWRAP><br><br>
<b>
&lt;!-- BEGIN CS LIVE HELP HTML CODE --&gt;<br>
&lt;script language="javascript" src="<?= $webpath ?>livehelp.js"&gt;&lt;/script&gt;<br>
&lt;!-- END CS LIVE HELP HTML CODE --&gt;<br>
</b><br><br>
</td></tr></table>