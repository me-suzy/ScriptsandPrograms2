<?php

/*  
   Edit Page Properties
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

require ("inc/functions/validate.input.form.php");

$item = $_GET[item];
$sub = $_GET[sub];
$sec = $_GET[sec];
$parentId = $_GET[parentId];

$pageid = $_GET[pageid];
if (!$pageid) {
  $pageid = $_GET[id];
}

if ($pageid == "2") {$pageid = "0";}

?>
<div id="pagelinks">
<table border="1"  bgcolor="#f8f8ff" cellpadding="3" style="border-collapse: collapse"   bordercolor="#E5E5E5" width="100%">
<tr><td>
<table border="0"  bgcolor="#f8f8ff" cellpadding="0" style="border-collapse: collapse"   bordercolor="#E5E5E5" width="100%">
                      
   
	<tr>
  		<td colspan="5"><span class="message"><b>
		
	<?php      $message = $_GET['message'];
		   if ($message) {  
		   echo "<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img alt=\"result\" src=\"admin/images/error.gif\" height=\"16\" width=\"16\" />&nbsp;&nbsp;Result: ";
		   echo $message; 
		   echo "<br />"; } ?>
		   </b><br /></span></td>
	</tr>
    


	

	<tr><td>&nbsp;</td></tr>
  
	<tr><td>
	<table style="border-collapse: collapse" cellpadding="2" border="1" border-color="c0c0c0" width="100%">
	<tr class="normalText" bgcolor="#f0f0f0">
	   <td style="border: 1px solid #c0c0c0" align="right" background="admin/images/bluebar.gif" ><img alt="error" src="admin/images/blue_ball.gif" height="16" width="16" /></td><td border="0" width="100%" align="left" class="smallText" background="admin/images/bluebar.gif" ><b><a style="color: #000080; font-weight:bold;" href="admin.php?id=2&item=1&sub=9&sec=1&pageid=<?php echo $pageid; ?>">&nbsp;Select Page</b></a></td>
    </tr>
	</table>
	</td></tr>
	
	<?php 
	$sec = $_GET['sec'];
	if (!$sec) { $sec = "1"; }
	
	
if ($id>0) {

	
	//Page Selector
	echo "<tr><td>&nbsp;</td></tr>";
    echo "<tr><td class=\"message\">Use the menu to select a page to edit:&nbsp;<br />&nbsp;</td></tr>";
	echo "<tr><td height=\"20px\">";
	
	//links modified 
	$section = "/admin.php?id=2&item=1&sub=9&sec=2&pageid="; //used to build links
	include ("inc/functions/adminMenu.php");
	
		echo "</td></tr>";
    echo "<tr><td>&nbsp;</td></tr>";

	
}	//if section 1
?>
<tr><td>
	<table style="border-collapse: collapse" cellpadding="2" border="1" border-color="c0c0c0" width="100%">
	<tr class="normalText" bgcolor="#f0f0f0">
	   <td style="border: 1px solid #c0c0c0" align="right" background="admin/images/bluebar.gif" ><img alt="error" src="admin/images/blue_ball.gif" height="16" width="16" /></td><td border="0" width="100%" align="left" class="smallText" background="admin/images/bluebar.gif" ><b><a style="color: #000080; font-weight:bold;" href="admin.php?id=2&item=1&sub=9&sec=2&pageid=<? echo $pageid; ?>">&nbsp;Edit Properties of the Selected Page</b></a></td>
    </tr>
	</table>
</td></tr>
<?php 
if ($sec==2 || $id>0) {

	
    if (!$pageid) {
	    echo "<tr><td colspan=>";
		echo "<p>&nbsp;&nbsp;&nbsp;&nbsp;<img alt=\"error\" src=\"admin/images/error.gif\" height=\"16\" width=\"16\" />&nbsp;&nbsp;Please Select a Page for editing.</p></td></tr>";
    }  
	else
	{
	
	

	    echo "<form enctype='multipart/form-data' action='admin/page/pageEditPro.php' method='post'>";
	
    $db = new DB();
	$db->query("SELECT * FROM ". DB_PREPEND . "pages where id='$pageid' ");
		$page = $db->next_record();	
		$test = $db->num_rows();
		if  (!$test)
		{echo "<tr><td class=\"pageTitle\"><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Page was not found in the database.<br /><br /></td></tr>"; exit;}
		
// Position, Title and FileName Table		
	echo "<tr><td>&nbsp;</td></tr>";
    echo "<tr><td class=\"normalText\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Title:&nbsp;&nbsp;</strong> <span class=\"pageTitle\">".$page[title]."</span>&nbsp;&nbsp;&nbsp;Page id: <span class=\"pageTitle\">".$page['id']."</span>&nbsp;<br />&nbsp;</td></tr>";
	echo "<tr><td><center><table bgcolor =\"#e7ecfa\" style=\"border-collapse: collapse\" style=\"border: 1px #c0c0c0;\" cellpadding=\"5\" border=\"1\" border-color=\"#c0c0c0\" width=\"90%\">";
	
		echo "<tr><td  align=\"left\" class=\"normalText\"><form enctype='multipart/form-data' action='admin/help/edit_pagePro2.php' method='post'><b>Position</b></td><td align=\"left\"  class=\"normalText\" ><b>Title</b></td><td align=\"left\"  class=\"normalText\" ><b>File Name</b> <span class=\"message\">must have file extension: .php</span></td></tr>";
	    echo "<tr>";
		echo"<td width=\"5%\"><input type=\"text\" name=\"newPosition\" value=\"$page[position]\" size=\"4\" ></td><td align=\"left\" width=\"20%\">";
		echo "<input type=\"text\" name=\"pageName\" value=\"$page[title]\" size=\"60\" >"; 
	    echo "<td align=\"left\" >";
		echo "<input type=\"text\" name=\"fileName\" value=\"$page[filename]\" size=\"40\" > <span class=\"message\" ></span></td>";
		
		echo "</tr>";
		echo "<tr><td align=\"left\" colspan=\"3\" class=\"message\">Siblings of this Page:</td></tr>";
	
		//find siblings
		$parentId = $page[parentId];
	 	$db2 = new DB();
		$db2->query("SELECT * FROM ". DB_PREPEND . "pages WHERE parentId='$parentId' AND admin = '0' ORDER BY 'position' ");
		$others = $db2->num_rows();
		while($other = $db2->next_record()){
		       
				
					// if there are no other pages
				if ($others < 2) {
				    echo "<tr><td align=\"left\" colspan=\"3\">";
					echo "<span class=\"tinyText\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This page has no siblings.</span>"; 
	    			echo "</td></tr>";
				}
				elseif ($other['id'] == $page['id'] ) {
					//skip this if it is the current page
				} 
				else {
				    
	    			echo "<tr><td align=\"center\" width=\"5%\">$other[position]</td><td align=\"left\" >
					      
						  <input type=\"hidden\" name=\"position[]\" value=\"$other[id]\" >";
					echo "<a class=\"smallText\" href=\"admin.php?id=2&item=1&sub=9&sec=2&pageid=$other[id]\">$other[title]</a>"; 
	    			echo "</td>";
					echo "<td class=\"smallText\" align=\"left\" >  " . CMS_WWW . "/pages/"  .$other['filename'];
					
	    			echo "</td></tr>";
		        } // else
		} // while
		
		echo "</table></center></td></tr>";
		
	    echo "<tr><td>&nbsp;</td></tr>";


// Show in Menu section



echo "<tr><td>&nbsp;</td></tr>";
    echo "<tr><td class=\"normalText\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Show in Menu / Sitemap?</strong>&nbsp;<br />&nbsp;</td></tr>";
	echo "<tr><td><center><table bgcolor =\"#e7ecfa\" style=\"border-collapse: collapse\" style=\"border: 1px #c0c0c0;\" cellpadding=\"5\" border=\"1\" border-color=\"#c0c0c0\" width=\"90%\">";
	
		
	    			echo "<tr><td align=\"left\" width=\"80%\">
					      
						  <input type=\"checkbox\" name=\"menu\" ";
						  
						  if ($page['menu'] == "on")
						  { echo "checked=\"checked\"";}
						  
						  
					echo	  ">"; 
	    			echo " <span class=\"message\">Children of this page will also be hidden.</span></td></tr>";
		        
		
		echo "</table></center></td></tr>";

//end Show in Menu section			

// Delete Page



echo "<tr><td>&nbsp;</td></tr>";
    echo "<tr><td class=\"normalText\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Delete Page?</strong>&nbsp;<br />&nbsp;</td></tr>";
	echo "<tr><td><center><table bgcolor =\"#e7ecfa\" style=\"border-collapse: collapse\" style=\"border: 1px #c0c0c0;\" cellpadding=\"5\" border=\"1\" border-color=\"#c0c0c0\" width=\"90%\">";
	
		
	    			echo "<tr><td align=\"right\" width=\"5%\"><a href=\"" . CMS_WWW . "/admin.php?id=2&item=1&sub=85&sec=2&pageid=$pageid&edit=1&parentId=$parentId\"><img src=\"admin/images/del.gif\" alt=\"Delete Button\" width=\"11\" height=\"11\" /></a></td><td align=\"left\" width=\"80%\">";
					      
					echo  "<a href=\"" . CMS_WWW . "/admin.php?id=2&item=1&sub=85&sec=2&pageid=$pageid&edit=1&parentId=$parentId\">    Delete this page.</a>"; 
	    			echo "</td></tr>";
		        
		
		echo "</table></center></td></tr>";

//end Delete Page


// Parent section

echo "<tr><td>&nbsp;</td></tr>";
    echo "<tr><td class=\"normalText\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Page Parent</strong>&nbsp;<br />&nbsp;</td></tr>";
	echo "<tr><td><center><table bgcolor =\"#e7ecfa\" style=\"border-collapse: collapse\" style=\"border: 1px #c0c0c0;\" cellpadding=\"5\" border=\"1\" border-color=\"#c0c0c0\" width=\"90%\">";
	echo "<tr><td align=\"left\" width=\"5%\"><b>Title</b></td><td align=\"left\" width=\"80%\"><b>Parent id</b><span class=\"tinyText\"></span></td></tr>";
	
	$parent = $page[parentId];
	
	 	$db3 = new DB();
		$db3->query("SELECT title FROM ". DB_PREPEND . "pages WHERE parentId='$parent' AND admin = '0' ORDER BY 'position' ");
		$momma = $db3->next_record();
	    			echo "<tr><td align=\"left\" width=\"20%\">".$page['title'] ."</td><td align=\"left\" width=\"80%\">
					      
						  <input type=\"text\" name=\"pageparent\" value=".$page['parentId']." size=\"6\" >"; 
	    			echo " <span class=\"tinyText\">Changing parent id <b>moves</b> the page.</span></td></tr>";
		        
		
		echo "</table></center></td></tr>";

//end Parent section			
		
		
		
// Permissions section

echo "<tr><td>&nbsp;</td></tr>";
    echo "<tr><td class=\"normalText\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Who can see this page?</strong>&nbsp;<br />&nbsp;</td></tr>";
	echo "<tr><td><center><table bgcolor =\"#e7ecfa\" style=\"border-collapse: collapse\" style=\"border: 1px #c0c0c0;\" cellpadding=\"5\" border=\"1\" border-color=\"#c0c0c0\" width=\"90%\">";
	
		
	    			echo "<tr><td align=\"left\" width=\"5%\">Group:</td><td align=\"left\" >
					      
						  <input type=\"text\" name=\"permission\" value=".$page['permit']." size=\"4\" >"; 
	    			echo " <span class=\"tinyText\">Default is everybody (Everybody = 4, Registered = 3, Administrator = 2, Webmaster = 1)</span></td></tr>";
		        
		
		echo "</table></center></td></tr>";

//end Permissions section	

// Meta Description section

echo "<tr><td>&nbsp;</td></tr>";
    echo "<tr><td class=\"normalText\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Meta Description</strong> <span class=\"smallText\">for Google etc. </span>&nbsp;<br />&nbsp;</td></tr>";
	echo "<tr><td><center><table bgcolor =\"#e7ecfa\" style=\"border-collapse: collapse\" style=\"border: 1px #c0c0c0;\" cellpadding=\"5\" border=\"1\" border-color=\"#c0c0c0\" width=\"90%\">";
	
		
	    			echo "<td align=\"left\" >     
		
						  <input type=\"text\" maxlength=\"1024\" size=\"130\" name=\"metadescription\" value=\"".$page['description']."\">"; 
	    			echo "</td></tr>";
		        
		
		echo "</table></center></td></tr>";

//end Metadescription section	

// Keyword section

echo "<tr><td>&nbsp;</td></tr>";
    echo "<tr><td class=\"normalText\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Keywords</strong><span class=\"message\"> Necessary for search function! Use words separated by commas.</span>&nbsp;<br />&nbsp;</td></tr>";
	echo "<tr><td><center><table bgcolor =\"#e7ecfa\" style=\"border-collapse: collapse\" style=\"border: 1px #c0c0c0;\" cellpadding=\"5\" border=\"1\" border-color=\"#c0c0c0\" width=\"90%\">";
	
		
	    			     
				echo "<td ><input type=\"text\" maxlength=\"1024\" size=\"130\" name=\"keywords\" value=\"".$page['keywords']."\"></td></tr>";
		        
		
		echo "</table></center></td></tr>";

//end keywords section	

// Robots section

echo "<tr><td>&nbsp;</td></tr>";
    echo "<tr><td class=\"normalText\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Search Robots Meta Tag</strong>&nbsp;<br />&nbsp;</td></tr>";
	echo "<tr><td><center><table bgcolor =\"#e7ecfa\" style=\"border-collapse: collapse\" style=\"border: 1px #c0c0c0;\" cellpadding=\"5\" border=\"1\" border-color=\"#c0c0c0\" width=\"90%\">";
	
		
	    			echo "<tr><td align=\"left\" >
					      
						  <input type=\"text\" name=\"robots\" value=".$page['robots']." size=\"15\" >"; 
	    			echo " <span class=\"tinyText\">ALL = index page and follow links, NONE = do not, NOINDEX = do not index, NOFOLLOW = do not follow links on page.</span></td></tr>";
		        
		
		echo "</table></center></td></tr>";

//end Robots section	

		echo "<tr><td>&nbsp;</td></tr>";
		
		echo "<tr class=\"normalText\" bgcolor=\"#f0f0f0\">";
      	echo "<td background=\"admin/images/bluebarBg.gif\" >
		<input type=\"hidden\" name=\"parentId\" value=\"$parentId\" />
		<input type=\"hidden\" name=\"pageid\" value=\"$pageid\" />
		<center><input type=\"submit\" name=\"Submit\" value=\"Save\"></center></td></tr>"; 
        echo "<tr><td>&nbsp;</form></td></tr>";
	
    } // if pageid
	
	

	
	
} // if sec 2
?>








</table>
</td></tr>
</table>
</div>

