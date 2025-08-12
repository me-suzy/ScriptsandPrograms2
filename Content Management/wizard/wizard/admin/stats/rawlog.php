<?php

/*  
   Raw Log
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
	   			<td style="border: 1px solid #c0c0c0" align="right" background="admin/images/bluebar.gif" ><img alt="error" src="admin/images/blue_ball.gif" height="16" width="16" /></td><td border="0" width="100%" align="left" class="smallText" background="admin/images/bluebar.gif" ><b>Raw Visitors Log</b></a></td>
			</tr>
    	</table>
	</td></tr>
	
	
	<tr><td>
	
	
	<br />
<?

        include "inc/functions/pager.php";

		$pageRoot = CMS_WWW . "/admin.php?id=2&item=70&sub=71"; 
		$linkable = 0; 
		$editPage = CMS_WWW . "/admin.php?id=2&item=27&sub=32";  
		$tableName = DB_PREPEND . 'hits'; 
		$default_sort = 'Date';
		$rank = "DESC"; //DESC or ASC
		$tableWidth = "98%";
		$columns = array('Member', 'Title','Date','Host'); 
		$col_alias = array('Member', 'Page Visited','Date','Host'); 
		$col_widths = array ('10%', '20%','15%','55%'); 
		$col_align = array ('left','left','left','left'); 
		$limit = 30; 
		$bgcolor = "#F2F3FF"; 
		$altbgcolor = "#f8f8ff"; 
		$headrow = "#c0c0c0"; 
		$tblHeadBg = "admin/images/bluebarBg.gif"; 
		$sorting = "1";
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