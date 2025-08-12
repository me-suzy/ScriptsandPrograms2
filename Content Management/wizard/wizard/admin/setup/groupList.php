<?php

/*  
   List Groups
   (c) 2005 Philip Shaddock, www.wizardinteractive.com
	This file is part of the Wizard Site Framework.

    This file is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    It is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with the Wizard Site Framework; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>
<div id="pagelinks" >
<table bgcolor="#f8f8ff" style="border-collapse: collapse" cellpadding="2" border="1" border-color="c0c0c0" width="100%">
<tr><td>
<table bgcolor="#f8f8ff" style="border-collapse: collapse" cellpadding="0" border="0" border-color="c0c0c0" width="100%">
		<tr><td>&nbsp;</td></tr>
	
	<tr><td>
		<table style="border-collapse: collapse" cellpadding="2" border="1" border-color="c0c0c0" width="100%">
			<tr class="normalText" bgcolor="#f0f0f0">
	   			<td style="border: 1px solid #c0c0c0" align="right" background="admin/images/bluebar.gif" ><img alt="error" src="admin/images/blue_ball.gif" height="16" width="16" /></td><td border="0" width="100%" align="left" class="smallText" background="admin/images/bluebar.gif" ><b>List of Groups</b></a></td>
			</tr>
    	</table>
	</td></tr>
	
	
	<tr><td><?php $message = $_GET[message]; echo "<span class='message'>"; echo $message;  echo "</span>"; ?>
	
	
	<br />
<?php	
include "inc/functions/pager.php";

$pageRoot = CMS_WWW . "/admin.php?id=2&item=24&sub=30"; //id of the page that is being displayed
$linkable = 1; // make first column items link to editPage? 1=yes 0=no
$editPage = CMS_WWW . "/admin.php?id=2&item=24&sub=12"; // record that is displayed 
$tableName = DB_PREPEND . "groups"; 
$default_sort = 'gid';
$rank = "ASC"; //DESC or ASC
$tableWidth = "100%";
$columns = array('gid', 'name', 'dsc'); //database columns that will be displayed
$col_alias = array('Id', 'Name', 'Description'); //Column names as they will appear
$col_widths = array ('10px', '110px','490px'); //set relative width of columns
$col_align = array ('center','left','left'); 
$limit = 25; //number of rows to display per page
$bgcolor = "#F2F3FF"; // background color of row
$altbgcolor = "#f8f8ff"; // background color of alternate row
$headrow = "#c0c0c0"; //column headings background color
$tblHeadBg = "admin/images/bluebarBg.gif"; //cell background image for column headings
$selectClause="*"; 
$whereClause="";
pager($selectClause, $whereClause, $pageRoot,$linkable,$editPage,$tableName,$default_sort, $rank, $tableWidth,$columns,$col_alias,$col_widths,$col_align,$limit,$bgcolor,$altbgcolor,$headrow,$tblHeadBg,$sorting);

?>
&nbsp;<br /></td></tr>
</table>
<br />
</td></tr>
</table>

</div>