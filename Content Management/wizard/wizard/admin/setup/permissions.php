<?php
/*  
   Permissions settings
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


//authentication check
    include_once ("admin/webmaster_auth.php");

// Database configuration table loaded at top of admin.php script
$message = $_GET['message'];
?>


 

<!-- Inner table -->
<table border="1"  bgcolor="#f8f8ff" cellpadding="5" style="border-collapse: collapse"   bordercolor="#E5E5E5" width="100%">
<tr><td>
<table border="0"  bgcolor="#f8f8ff" cellpadding="5" style="border-collapse: collapse"   bordercolor="#E5E5E5" width="100%">
              
<form enctype='multipart/form-data' action='admin/setup/permissionsPro.php' method='post'>

  
    <tr>
  		<td colspan="4"><span class="message"><?php echo $message; unset($message); ?><br /></span></td>
	</tr>
    

<!--- Member Registration  -->
    <tr><td colspan="4">
	<table style="border-collapse: collapse" cellpadding="2" border="0" border-color="c0c0c0" width="100%">
	<tr class="normalText" bgcolor="#f0f0f0">
	   <td style="border: 1px solid #c0c0c0" align="right" background="admin/images/bluebar.gif" ><img alt="error" src="admin/images/blue_ball.gif" height="16" width="16" /></td><td border="0" width="100%" align="left" class="smallText" background="admin/images/bluebar.gif" ><b>&nbsp;Member Registration</b></a></td>
    </tr>
	</table>
	</td></tr>
	
	<tr class="normalText"> 
		<td width="31%">&nbsp;</td>
      <td align="left" width="32%" >Allow new users to register?</td>
      <td align="left" width="9%" > 
	  <?php if ($config['register'] == "on") {
	  				echo "<input type=\"checkbox\" name=\"register\" checked=\"checked\" value=\"on\">";
	  			}
	  			else {
	  				echo "<input type=\"checkbox\" name=\"register\" value=\"off\">";
	  			}
	  ?>
	  </td>
      <td align="left" width="28%"  class="normalText"></td>
    </tr>
	
	<tr class="normalText"> 
		<td width="31%">&nbsp;</td>
      <td align="left" width="32%" >Administrator has to approve new user?</td>
      <td align="left" width="9%" > 
	  <?php if ($config['user_approve'] == "on") {
	  				echo "<input type=\"checkbox\" name=\"approve\" checked=\"checked\" value=\"on\">";
	  			}
	  			else {
	  				echo "<input type=\"checkbox\" name=\"approve\" value=\"off\">";
	  			}
	  ?>
	  </td>
      <td align="left" width="28%"  class="normalText"></td>
    </tr>
	

<!--- Group Permissions  -->
    <tr><td colspan="4">
	<table style="border-collapse: collapse" cellpadding="2" border="0" border-color="c0c0c0" width="100%">
	<tr class="normalText" bgcolor="#f0f0f0">
	   <td style="border: 1px solid #c0c0c0" align="right" background="admin/images/bluebar.gif" ><img alt="error" src="admin/images/blue_ball.gif" height="16" width="16" /></td><td border="0" width="100%" align="left" class="smallText" background="admin/images/bluebar.gif" ><b>&nbsp;Group Permissions</b></a></td>
    </tr>
	</table>
	</td></tr>
	
	<tr class="normalText"> 
	  <td width="19%">&nbsp;</td>
      <td align="left" width="24%" ><?php echo "Can add additional webmasters?"; ?></td>
      <td align="left" width="20%" > 
	  <?php if ($config['reg_webmaster'] == "on") {
	  				echo "<input type=\"checkbox\" name=\"reg_webmaster\" checked=\"checked\" value=\"on\">";
	  			}
	  			else {
	  				echo "<input type=\"checkbox\" name=\"reg_webmaster\" value=\"off\">";
	  			}
	  ?>
	  </td>
      <td align="left" width="26%"  class="normalText"></td>
    </tr>
	
	

<!--- User Editing  -->
    <tr><td colspan="4">
	<table style="border-collapse: collapse" cellpadding="2" border="0" border-color="c0c0c0" width="100%">
	<tr class="normalText" bgcolor="#f0f0f0">
	   <td style="border: 1px solid #c0c0c0" align="right" background="admin/images/bluebar.gif" ><img alt="error" src="admin/images/blue_ball.gif" height="16" width="16" /></td><td border="0" width="100%" align="left" class="smallText" background="admin/images/bluebar.gif" ><b>&nbsp;User Editing</b></a></td>
    </tr>
	</table>
	</td></tr>
	
	<tr class="normalText"> 
	  <td width="19%">&nbsp;</td>
      <td align="left" width="32%" ><?php echo "Administrators can add users?"; ?></td>
      <td align="left" width="20%" > 
	  
	  <?php 
	  	 		if ($config['user_add'] == "on") {
	  				echo "<input type=\"checkbox\" name=\"user_add\" checked=\"checked\" value=\"on\">";
	  			}
	  			else {
	  				echo "<input type=\"checkbox\" name=\"user_add\" value=\"off\">";
	  			}
	  ?>
	  </td>
      <td align="left" width="26%"  class="normalText"></td>
    </tr>
	
	<tr class="normalText"> 
	  <td width="19%">&nbsp;</td>
      <td align="left" width="32%" ><?php echo "Administrators can view user profiles?"; ?></td>
      <td align="left" width="20%" > 
	  
	  <?php 
	  	 		if ($config['user_view'] == "on") {
	  				echo "<input type=\"checkbox\" name=\"user_view\" checked=\"checked\" value=\"on\">";
	  			}
	  			else {
	  				echo "<input type=\"checkbox\" name=\"user_view\" value=\"off\">";
	  			}
	  ?>
	  </td>
      <td align="left" width="26%"  class="normalText"></td>
    </tr>
	
    
	<? 
    if ($config['user_view'] == "off") {
        //turn this option off
    }
	else {
	echo "<tr class=\"normalText\">";
	  echo "<td width=\"19%\">&nbsp;</td>";
      echo "<td align=\"left\" width=\"32%\" >Administrators can edit user profiles?</td>";
      echo "<td align=\"left\" width=\"20%\" >";
	  if ($config['user_edit'] == "on") {
	  				echo "<input type=\"checkbox\" name=\"user_edit\" checked=\"checked\" value=\"on\">";
	  			}
	  			else {
	  				echo "<input type=\"checkbox\" name=\"user_edit\" value=\"off\">";
	  			}
	  
	  echo "</td>";
      echo "<td align=\"left\" width=\"26%\"  class=\"normalText\"></td>";
    echo "</tr>";
	} // else
	?>
	
	<!--- Search Restrictions  -->
    <tr><td colspan="4">
	<table style="border-collapse: collapse" cellpadding="2" border="0" border-color="c0c0c0" width="100%">
	<tr class="normalText" bgcolor="#f0f0f0">
	   <td style="border: 1px solid #c0c0c0" align="right" background="admin/images/bluebar.gif" ><img alt="error" src="admin/images/blue_ball.gif" height="16" width="16" /></td><td border="0" width="100%" align="left" class="smallText" background="admin/images/bluebar.gif" ><b>&nbsp;Site Search</b></a></td>
    </tr>
	</table>
	</td></tr>
	
	<tr class="normalText"> 
	  <td width="19%">&nbsp;</td>
      <td align="left" width="24%" ><?php echo "Restricted pages can be searched?"; ?></td>
      <td align="left" width="20%" > 
	  <?php if ($config['searchRestrict'] == "on") {
	  				echo "<input type=\"checkbox\" name=\"searchrestrict\" checked=\"checked\" value=\"on\">";
	  			}
	  			else {
	  				echo "<input type=\"checkbox\" name=\"searchrestrict\" value=\"off\">";
	  			}
	  ?>
	  </td>
      <td align="left" width="26%"  class="normalText"></td>
    </tr>
<!--End Search Restrictions -->
    
	<tr>
	  <td width="19%">&nbsp;</td>
      <td align="left" width="32%" >&nbsp;</td>
      <td align="left" width="20%" >&nbsp;</td>
      <td width="26%" class="normalText">&nbsp;</td>
    </tr>
	
		
    <tr bgcolor="#f0f0f0">
       <td background="admin/images/bluebarBg.gif" class="normalText" colspan="4"><center><input type="submit" name="Submit" value="Save"></center></td>
    </tr>
	</form>
  </table>
  </td></tr>
  </table>






