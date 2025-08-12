<?php

/*  
   	Menu Renumber Script
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

$item = $_GET[item];
$sub = $_GET[sub];
$sec = $_GET[sec];
$pageid = $_GET[pageid];
$parentId = $_GET[parentId];
$renumber = $_GET[renumber];



?>
<div id="pagelinks" >
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
	   <td style="border: 1px solid #c0c0c0" align="right" background="admin/images/bluebar.gif" ><img alt="error" src="admin/images/blue_ball.gif" height="16" width="16" /></td><td border="0" width="100%" align="left" class="smallText" background="admin/images/bluebar.gif" ><b><a style="color: #000080; font-weight:bold;" href="admin.php?id=2&item=1&sub=80&sec=1">&nbsp;Change the Page or Menu Order</b></a></td>
    </tr>
	</table>
	</td></tr>
	
	<?php 
	$sec = $_GET['sec'];
	if (!$sec) {$sec = 1;}
	
	
if ($sec==1) {

    echo "<tr><td>&nbsp;</td></tr>";
    echo "<tr><td class=\"message\">Use the menu to select a page to renumber:&nbsp;<br />&nbsp;</td></tr>";
	echo "<tr><td height=\"20px\">";
	
	//links modified 
	$section = "/admin.php?id=2&item=1&sub=80&sec=2&pageid="; //used to build links
	include ("inc/functions/adminMenu.php");
	
    echo "</td></tr><tr><td>&nbsp;</td></tr>";
	

	
}	//if section 1

if ($sec==2) {

	$pageid = $_GET['pageid'];
	
       	
		$db->query("SELECT position,id,title,parentId FROM ". DB_PREPEND . "pages WHERE id='$pageid'");
		$i = $db->next_record();
		

		
//Order of Siblings
    	echo "<tr><td><form enctype='multipart/form-data' action='admin/page/pagerenumberPro.php' method='post'>&nbsp;</td></tr>";
		echo "<tr><td><table align=\"left\" style=\"border-collapse: collapse\" style=\"border: 0\" cellpadding=\"5\" border=\"0\" border-color=\"eeeeee\" width=\"90%\">";
		echo "<tr><td class=\"pageTitle\" align=\"left\">Renumber</td>";
		echo "</tr>";
		echo "</table></td></tr>";
		$parentId = $i[parentId];
		
		$db = new DB();
		$db->query("SELECT * FROM ". DB_PREPEND . "pages WHERE parentId='$parentId' AND admin = '0' ORDER BY position ");
		$count = $db->num_rows();
	    
	    if ($count < 2) {
	    echo "<tr><td>&nbsp;</td></tr>";
		echo "<tr><td><table bgcolor =\"#e7ecfa\" align=\"center\" style=\"border-collapse: collapse\" style=\"border: 1px #eeeeee;\" cellpadding=\"5\" border=\"1\" border-color=\"eeeeee\" width=\"90%\">";
		echo "<tr><td align=\"left\">This is the only page in this menu.</td>";
		echo "</tr>";
		echo "</table></td></tr><tr><td>&nbsp;</td></tr>";
	    } // not count
		else {
		echo "<tr><td>&nbsp;</td></tr>";
		echo "<tr><td><table bgcolor =\"#e7ecfa\" align=\"center\" style=\"border-collapse: collapse\" style=\"border: 1px #eeeeee;\" cellpadding=\"5\" border=\"1\" border-color=\"eeeeee\" width=\"90%\">";
	    
		
		while($sibling = $db->next_record()){
	    	echo "<tr>";
			echo "<td align=\"right\" width=\"10%\"><input type=\"text\" name=\"positionOrder[]\" value=\"$sibling[position]\" size=\"5\"></td><td>$sibling[title]</td>";
			echo "<input type=\"hidden\" name=\"idOrder[]\" value=\"$sibling[id]\" />";
			echo "</tr>";
	    } // while $sibling = $db->next_record();
			
		echo "</table></td></tr><tr><td>&nbsp;</td></tr>";
		
	    }
		
		
	    echo "<tr class=\"normalText\" bgcolor=\"#f0f0f0\">";
		echo "<input type=\"hidden\" name=\"pageid\" value=\"$pageid\" />";
		echo "<input type=\"hidden\" name=\"parentId\" value=\"$parentId\" />";
      	echo "<td background=\"admin/images/bluebarBg.gif\" ><center><input type=\"submit\" name=\"Submit\" value=\"Renumber\"></center></td></tr>"; 

	
	
	   echo "</form>";
	

	

}// if sec 2

?>

</table>
</td></tr>
</table>
</div>


