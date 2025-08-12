<?php

/*  
   Delete Page Script
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
                      
    <tr>
  		<td colspan="5"><span class="message"><b>
		
<?php      $message = $_GET['message'];
		   if ($message) {  
		   echo "<br /><img alt=\"result\" src=\"admin/images/error.gif\" height=\"16\" width=\"16\" />&nbsp;&nbsp;Result: ";
		   echo $message; 
		   echo "<br />"; } ?>
		   </b><br /></span></td>
	</tr>


	<tr><td>
	<table style="border-collapse: collapse" cellpadding="2" border="1" border-color="c0c0c0" width="100%">
	<tr class="normalText" bgcolor="#f0f0f0">
	   <td style="border: 1px solid #c0c0c0" align="right" background="admin/images/bluebar.gif" ><img alt="error" src="admin/images/blue_ball.gif" height="16" width="16" /></td><td border="0" width="100%" align="left" class="smallText" background="admin/images/bluebar.gif" ><b>&nbsp;<a style="color: #000080; font-weight:bold;" href="admin.php?id=2&item=1&sub=85&sec=1&pageid=<?php $pageid = $_GET['pageid']; echo $pageid; ?>">Choose Page</b></a></td>
	    
	</tr>
	</table>
	</td></tr>
	
	<?php 
	$sec = $_GET['sec'];
	if (!$sec) { $sec = "1"; }
	
if ($sec==1) {

    $pageid = $_GET['pageid'];
    if (!$pageid) { $pageid = "1"; }
    ?>
	
	
	<?php 
	
	//Page Selector
	echo "<tr><td>&nbsp;</td></tr>";
    echo "<tr><td class=\"smallText\">Select a page to delete:&nbsp;<br />&nbsp;</td></tr>";
	echo "<tr><td height=\"20px\">";
	
	//links modified 
	$section = "/admin.php?id=2&item=1&sub=85&sec=2&pageid="; //used to build links
	include ("inc/functions/adminMenu.php");
	
		echo "</td></tr>";
		echo "<tr><td>&nbsp;</td></tr>";
		
	
	
	
	echo "</form>";
	
}	//if section 1
?>
<tr><td>
	<table style="border-collapse: collapse" cellpadding="2" border="1" border-color="c0c0c0" width="100%">
	<tr class="normalText" bgcolor="#f0f0f0">
	   <td style="border: 1px solid #c0c0c0" align="right" background="admin/images/bluebar.gif" ><img alt="error" src="admin/images/blue_ball.gif" height="16" width="16" /></td><td border="0" width="100%" align="left" class="smallText" background="admin/images/bluebar.gif" ><b>&nbsp;<a style="color: #000080; font-weight:bold;" href="admin.php?id=2&item=1&sub=85&sec=2">Page Delete Confirmation</b></a></td>
    </tr>
	</table>
	</td></tr>
<?php 
if ($sec==2) {
    $edit = $_GET['edit'];
	$pageid = $_GET['pageid'];
	$parentId = $_GET['parentId'];
	
		
	$db = new DB();
	$db->query("SELECT * FROM ". DB_PREPEND . "pages WHERE parentId='$pageid' AND admin = '0' ORDER BY 'position' ");
	$others = $db->num_rows();
	if ($others >= 1){
	echo "<tr><td>&nbsp;</td></tr>";
	echo "<tr><td>";
	echo "<table style=\"border-collapse: collapse\" cellpadding=\"8\" border=\"1\" border-color=\"c0c0c0\" width=\"100%\">";
	echo "<tr bgcolor=\"#f0f0f0\"><td class=\"message\" >";
	echo "Error: This item has children that must be moved or deleted. Please choose again.";
	echo "</td></tr></table></td></tr>";
	echo "<tr><td>&nbsp;</td></tr>";
	}
	else {
	$db = new DB();
	$db->query("SELECT title FROM ". DB_PREPEND . "pages WHERE id='$pageid' AND admin = '0' ORDER BY 'position' ");
    $i = $db->next_record();
	$title = $i['title'];
	echo "<tr><td>&nbsp;</td></tr>";
	echo "<tr><td><center>";
	echo "<table style=\"border-collapse: collapse\" bgcolor=\"#f0f0f0\" cellpadding=\"8\" border=\"1\" border-color=\"c0c0c0\" width=\"90%\">";
	echo "<tr class=\"message\" >";
	echo "<td>Please confirm that you want to delete $title </td></tr>";
	echo "<tr><td align=\"center\"><a href=\"admin/page/pageDeletePro.php?pageid=$pageid&edit=$edit&parentId=$parentId\">Confirm</a></td></tr>";
	echo "</table></center></td></tr>";
	echo "<tr><td>&nbsp;</td></tr>";
	}
	
    
} // if sec 2
?>


</table>
</td></tr>
</table>
</div>

