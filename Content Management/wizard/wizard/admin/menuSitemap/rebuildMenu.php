<?php

/*  
   Rebuild Menu Script
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
<div id="pagelinks">
<table border="1"  bgcolor="#f8f8ff" cellpadding="3" style="border-collapse: collapse"   bordercolor="#E5E5E5" width="100%">
<tr><td>
<table border="0"  bgcolor="#f8f8ff" cellpadding="0" style="border-collapse: collapse"   bordercolor="#E5E5E5" width="100%">
                      

	<tr><td>
	<table style="border-collapse: collapse" cellpadding="2" border="1" border-color="c0c0c0" width="100%">
	<tr class="normalText" bgcolor="#f0f0f0">
	   <td style="border: 1px solid #c0c0c0" align="right" background="admin/images/bluebar.gif" ><img alt="error" src="admin/images/blue_ball.gif" height="16" width="16" /></td><td border="0" width="100%" align="left" class="smallText" background="admin/images/bluebar.gif" ><b>&nbsp;Rebuild Menus</b></a></td>
	    
	</tr>
	</table>
	</td></tr>
	
	<tr>
  		<td align="center" colspan="5"><span class="message"><b>
		
<?php      $message = $_GET['message'];
		   if ($message) {  
		   echo "<br /><img alt=\"result\" src=\"admin/images/error.gif\" height=\"16\" width=\"16\" />&nbsp;&nbsp;";
		   echo $message; } 

?>
		   </b></span></td>
	</tr>

	
<?
	
	//Page Selector
	
	echo "<tr><td>&nbsp;</td></tr>";
	echo "<tr><td  align=\"center\" >";
	echo "<a class=\"pageTitle\" href=\"". CMS_WWW ."/admin/menuSitemap/rebuildMenuPro.php\">Rebuild Menu</a>";
	echo "</td></tr>";
	echo "<tr><td>&nbsp;</td></tr>";
	echo "<tr><td>&nbsp;</td></tr>";
	echo "<tr><td height=\"20px\">";
	$section = "/admin.php?id=2&item=19&sub=20&pageid="; //used to build links
	include ("inc/functions/adminMenu.php");
	
		echo "</td></tr>";
		echo "<tr><td>&nbsp;</td></tr>";
		
	
	

?>


</table>
</td></tr>
</table>
</div>

