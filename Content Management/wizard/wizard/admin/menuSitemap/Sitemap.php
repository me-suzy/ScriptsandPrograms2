<?php
/*  
   	Sitemap Properties
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
    include_once ("admin/admin_auth.php");

// Database configuratin table loaded at top of index.php script
$message = $_GET['message'];

?>


 

<!-- Inner table -->
<table border="1"  bgcolor="#f8f8ff" cellpadding="5" style="border-collapse: collapse"   bordercolor="#E5E5E5" width="100%">
<tr><td>
<table border="0"  bgcolor="#f8f8ff" cellpadding="5" style="border-collapse: collapse"   bordercolor="#E5E5E5" width="100%">
              
<form enctype='multipart/form-data' action='admin/menuSitemap/SitemapPro.php' method='post'>


  
    <tr>
  		<td colspan="4"><span class="message"><?php echo $message; unset($message); ?><br /></span></td>
	</tr>
    

<!--- Group Memberships  -->
    <tr><td colspan="4">
	<table cellpadding="2" border="0" width="100%">
	<tr class="normalText" bgcolor="#f0f0f0">
	   <td style="border: 1px solid #c0c0c0" align="right" background="admin/images/bluebar.gif" ><img alt="error" src="admin/images/blue_ball.gif" height="16" width="16" /></td>
	   <td border="0" width="100%" align="left" class="smallText" background="admin/images/bluebar.gif" ><b>&nbsp;Set Sitemap Depth</b></a></td>
    </tr>
	</table>
	</td></tr>
	

	
	
<!--Set Top Menu Levels-->
	<tr class="normalText"> 
		<td width="50px">&nbsp;</td>
      <td align="left" width="270px" >How many levels deep?</td>
      <td align="left" width="5px" ><input type="text" name="sitemapDepth" size="5" value="<?php echo $config['sitemapDepth']; ?>">
	  </td>
      <td align="left" width="300px"  class="tinyText">Must be at least 1.</td>
    </tr>

	
		
    <tr bgcolor="#f0f0f0">
       <td background="admin/images/bluebarBg.gif" class="normalText" colspan="4"><center><input type="submit" name="Submit" value="Save"></center></td>
    </tr>
	</form>
  </table>
  </td></tr>
  </table>






