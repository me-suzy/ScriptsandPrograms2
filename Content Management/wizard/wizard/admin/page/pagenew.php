<?php

/*  
   New Page 
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
  		<td colspan="5"><span class="colorNormalText"><b>
		
<?php      $message = $_GET['message'];
		   if ($message) {  
		   echo "<br />&nbsp;";
		   echo $message; 
		   echo "<br />"; } ?>
		   </b><br /></span></td>
	</tr>


  
	<tr><td>
	<table style="border-collapse: collapse" cellpadding="2" border="1" border-color="c0c0c0" align="left" width="100%">
	<tr class="normalText" bgcolor="#f0f0f0">
	   <td style="border: 1px solid #c0c0c0" align="right" background="admin/images/bluebar.gif" ><img alt="error" src="admin/images/blue_ball.gif" height="16" width="16" /></td><td border="0" width="100%" align="left" class="smallText" background="admin/images/bluebar.gif" ><b><a style="color: #000080; font-weight:bold;" href="admin.php?id=2&item=1&sub=2&sec=1&pageid=<?php $pageid = $_GET['pageid']; echo $pageid; ?>">&nbsp;Choose Parent Page</b></a></td>
	    
	</tr>
	</table>
	</td></tr>
	
	<?php 
	$sec = $_GET['sec'];
	if (!$sec) { $sec = "1"; }
	
if ($sec==1) {

    $pageid = $_GET['pageid'];
	$fileName = $_GET['fileName'];
	$pageName = $_GET['pageName'];
    ?>
	
	
	<?php 
	
	//Page Selector
	echo "<tr><td>&nbsp;</td></tr>";
    echo "<tr><td class=\"message\">&nbsp;&nbsp;&nbsp;Select the new page's parent. (Use Home for top level.)&nbsp;<br /></td></tr>";
	echo "<tr><td height=\"20px\">";
	
	//links modified 
	$section = "/admin.php?id=2&item=1&sub=2&sec=2&pageid="; //used to build links
	include ("inc/functions/adminMenu.php");
	
		echo "</td></tr>";
		echo "<tr><td>&nbsp;</td></tr>";
		
	
	
	
	echo "</form>";
	
}	//if section 1
?>
<tr><td>
	<table style="border-collapse: collapse" cellpadding="2" border="1" border-color="c0c0c0" width="100%">
	<tr class="normalText" bgcolor="#f0f0f0">
	   <td style="border: 1px solid #c0c0c0" align="right" background="admin/images/bluebar.gif" ><img alt="error" src="admin/images/blue_ball.gif" height="16" width="16" /></td><td border="0" width="100%" align="left" class="smallText" background="admin/images/bluebar.gif" ><b><a style="color: #000080; font-weight:bold;" href="admin.php?id=2&item=1&sub=2&sec=2">&nbsp;Page Properties</b></a></td>
    </tr>
	</table>
	</td></tr>
<?php 
    echo "<tr><td>&nbsp;</td></tr>";
	

if ($sec==2) {

	
      
	  $pageid = $_GET['pageid'];
	  
	
 echo "<form enctype='multipart/form-data' action='admin/page/pagenewPro.php' method='post'>";
		// convert Home page id to top level id
		
		//find if this page has any siblings
		$db->query("SELECT * FROM ". DB_PREPEND . "pages WHERE id='$pageid' AND admin = '0' ORDER BY position ");
		$pagedata = $db->next_record();

   

	echo "<tr><td class=\"normalText\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Page Parent:<span class=\"colorNormalText\"> ".$pagedata['title']." </span>&nbsp;&nbsp;&nbsp;Id:<span class=\"colorNormalText\"> ".$pagedata['id']."</span></td></tr>";
	
	   
		

// Page Title and Position section				
		echo "<tr><td>&nbsp;</td></tr>";
		echo "<tr><td><table bgcolor =\"#e7ecfa\" align=\"center\" style=\"border-collapse: collapse\" style=\"border: 1px #eeeeee;\" cellpadding=\"5\" border=\"1\" border-color=\"eeeeee\" width=\"90%\">";
		echo "<tr><td align=\"center\" width=\"50\"><b>Position</b></td><td align=\"left\" ><b>&nbsp;&nbsp;Page Title</b></td><td align=\"left\" >&nbsp;&nbsp;<b>File Name</b> (extension must be .php)</td>";
		echo "</tr>";
		if (!$count) {
		    echo "<tr><td align=\"center\" ><input type=\"text\" name=\"newPosition\" size=\"5\" value=\"$newPosition\"></td><td class=\"message\" align=\"left\" ><input type=\"text\" name=\"pageName\" size=\"40\" value=\"$pageName\"></td><td class=\"message\" align=\"left\" ><input type=\"text\" name=\"fileName\" size=\"40\" value=\"$fileName\"></td>";
		} else {
		echo "<tr><td align=\"left\" ><input type=\"text\" name=\"newPosition\" size=\"5\" value=\"$newPosition\"></td><td class=\"message\" align=\"left\" ><input type=\"text\" name=\"pageName\" size=\"40\" value=\"$pageName\"></td><td class=\"message\" align=\"left\" >".CMS_WWW."/pages/<input maxlength=\"37\" type=\"text\" name=\"fileName\" size=\"40\" value=\"$fileName\">(e.g. newpage.php)</td>";
		}
		echo "</tr>";
		
		
		
//Order of Siblings
    	
		$newPosition = $_GET['newPosition'];
		$pageName = $_GET['pageName']; 
		
		// convert Home page id to top level id
		if ($pageid == "1") { $pageid = "0"; }
		//find if this page has any siblings
		$db->query("SELECT * FROM ". DB_PREPEND . "pages WHERE parentId='$pageid' AND admin = '0' ORDER BY position ");
		$count = $db->num_rows();
		
		
		echo "<tr><td class=\"normalText\" colspan=\"3\"><b>Siblings of this Page</b></td>";
		
		//find if this page has any siblings
		$db->query("SELECT * FROM ". DB_PREPEND . "pages WHERE parentId='$pageid' AND admin = '0' ORDER BY position ");
		$count = $db->num_rows();
	   
	    if (!$count) {
		echo "<tr><td class=\"message\" colspan=\"3\">This is the only page under this menu item.</td></tr>";
		echo "<tr><td colspan=\"3\">&nbsp;</td></tr>";
		echo "</table></td></tr>";
	    } // not count
		else {
			    
		$num = 1;
		while($sibling = $db->next_record()){
	    	echo "<tr>";
			echo "<td align=\"center\" ><input type=\"hidden\" name=\"position[]\" value=\"$sibling[id]\" size=\"4\">" . $num++ ."</td><td align=\"left\" >$sibling[title]</td><td class=\"normalText\" align=\"left\" >".CMS_WWW."/pages/".$sibling[filename]." </td>";
			echo "</tr>";
	    } // while$sibling = $db->next_record();
	
		echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>";
		echo "</table></td></tr>";
		}
		
//beginning of templates section		
		

echo "<tr><td>&nbsp;</td></tr>";
    echo "<tr><td class=\"normalText\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Choose Template for this Page</strong>&nbsp;<br />&nbsp;</td></tr>";
	echo "<tr><td><center><table bgcolor =\"#e7ecfa\" style=\"border-collapse: collapse\" style=\"border: 1px #c0c0c0;\" cellpadding=\"5\" border=\"1\" border-color=\"#c0c0c0\" width=\"90%\">";
	
		
	    			echo "<tr><td align=\"left\" width=\"5%\">Template:</td><td align=\"left\" width=\"80%\">";
					      echo "<select name=\"template\">";				  
							$this_dir = dir('./templates');
							while ($file = $this_dir->read()) {
						if (preg_match ("/tmpl.php/i", $file)) {
							$filec = STR_REPLACE(".tmpl.php","",$file);
							if ($template == $file) {
								echo "<option value = '$file' selected> $filec </option>";
							} else {
								echo "<option value = '$file'> $filec </option>";
							} 
						}
					}  
					echo "</select>";	
					echo " <span class=\"smallText\">&nbsp;&nbsp;&nbsp;Page templates are found in www.yoursite.com/templates/.</span></td></tr>";
		        
		
		echo "</table></center></td></tr>";

echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td  align=\"center\" class=\"message\" width=\"90%\">Click on the template name to preview its layout:</td></tr>";
//Allow user to click on the templates to see their layout
echo "<tr><td  align=\"center\" width=\"90%\">";
            $idnum = 19;
			$this_dir = dir('./templates');
			while ($file = $this_dir->read()) {
				if (preg_match ("/tmpl.php/i", $file)) {
				echo "<a href=\"" . CMS_WWW . "/templates/".$file."?id=".$idnum++."\" target =\"_blank\">".$file."</a><br />&nbsp;";	
				}
			} //while 
							
echo "</td></tr>";



		
//end of templates section		
	    

        echo "<tr><td>&nbsp;</td></tr>";
	    echo "<tr class=\"normalText\" bgcolor=\"#f0f0f0\">";
		echo "<input type=\"hidden\" name=\"parentId\" value=\"$pageid\" />";
		$uid = user_getid();
		echo "<input type=\"hidden\" name=\"uid\" value=\"$uid\" />";
      	echo "<td background=\"admin/images/bluebarBg.gif\" ><center><input type=\"submit\" name=\"Submit\" value=\"Save New Page\"></center></td></tr>"; 

	
	
	   echo "</form>";
	
   
} // if sec 2
?>


</table>
</td></tr>
</table>
</div>

