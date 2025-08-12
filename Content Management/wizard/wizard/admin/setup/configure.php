<?php
/*  
   	Configure script
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

// Database configuration data is called at top of index.php script

$message = $_GET['message'];
?>


 

<!-- Inner table -->
<table border="1"  bgcolor="#f8f8ff" cellpadding="5" style="border-collapse: collapse"   bordercolor="#E5E5E5" width="100%">
<tr><td>
<table border="0"  bgcolor="#f8f8ff" cellpadding="5" style="border-collapse: collapse"   bordercolor="#E5E5E5" width="100%">
              
<form enctype='multipart/form-data' action='admin/setup/configurePro.php' method='post'>


    <tr>
  		<td colspan="4"><span class="message"><?php echo $message; unset($message); ?><br /></span></td>
	</tr>
    

<!--- SITE Description  -->
    <tr><td colspan="4">
	<table style="border-collapse: collapse" cellpadding="2" border="0" border-color="c0c0c0" width="100%">
	<tr class="normalText" bgcolor="#f0f0f0">
	   <td style="border: 1px solid #c0c0c0" align="right" background="admin/images/bluebar.gif" ><img alt="error" src="admin/images/blue_ball.gif" height="16" width="16" /></td><td border="0" width="100%" align="left" class="smallText" background="admin/images/bluebar.gif" ><b>&nbsp;Site Description</b></a></td>
    </tr>
	</table>
	</td></tr>
	
	<tr class="normalText"> 
	  <td width="29%">&nbsp;</td>
      <td align="left" width="24%" ><?php echo "Site Name"; ?></td>
      <td align="left" width="20%" > <input type="text" name="name" value="<?php echo $config[name] ?>" size="50" maxlength="290"> 
      </td>
      <td align="left" width="27%">&nbsp;</td>
    </tr>
    
    
	
	<tr class="normalText"> 
	  <td width="29%">&nbsp;</td>
      <td align="left" width="24%" >Copyright</td>
      <td align="left" width="20%" > <input type="text" name="copyright" value="<?php echo $config[copyright] ?>" size="50" maxlength="290"></td>
      <td align="left" width="27%">&nbsp;</td>
    </tr>
	
	
	
	
    
	
<!--- Company Coordinates  -->
    <tr><td colspan="4">
	<table style="border-collapse: collapse" cellpadding="2" border="1" border-color="c0c0c0" width="100%">
	<tr class="normalText" bgcolor="#f0f0f0">
	   <td style="border: 1px solid #c0c0c0" align="right" background="admin/images/bluebar.gif" ><img alt="error" src="admin/images/blue_ball.gif" height="16" width="16" /></td><td border="0" width="100%" align="left" class="smallText" background="admin/images/bluebar.gif" ><b>&nbsp;Coordinates</b></a></td>
    </tr>
	</table>
	</td></tr>
	
	<tr>
	  <td width="29%">&nbsp;</td>
      <td class="normalText" align="left" width="24%" >Company</td>
      <td align="left" width="20%" ><input type="text" name="company" value="<? echo $config[company]; ?>" size="50"></td>
      <td align="left" width="27%">&nbsp;</td>
    </tr>
	
	<tr>
	  <td width="29%">&nbsp;</td>
      <td class="normalText" align="left" width="24%" >Contact</td>
      <td align="left" width="20%" ><input type="text" name="siteAdmin" value="<? echo $config[siteAdmin]; ?>" size="50"></td>
      <td align="left" width="27%">&nbsp;</td>
    </tr>
	
	<tr>
	  <td width="29%">&nbsp;</td>
      <td class="normalText" align="left" width="24%" >Email:</td>
      <td align="left" width="20%" ><input type="text" name="email" value="<? echo $config[email]; ?>" size="50"></td>
      <td class="normalText"></td>
    </tr>
	
	
    <tr>
	  <td width="29%">&nbsp;</td>
      <td class="normalText" align="left" width="24%" >Address:</td>
      <td align="left" width="20%" ><input type="text" name="address" value="<? echo $config[address]; ?>" size="50"></td>
      <td align="left" width="27%">&nbsp;</td>
    </tr>
	
	<tr>
	  <td width="29%">&nbsp;</td>
      <td class="normalText" align="left" width="24%" >City:</td>
      <td align="left" width="20%" ><input type="text" name="city" value="<? echo $config[city]; ?>" size="50"></td>
      <td align="left" width="27%">&nbsp;</td>
    </tr>
	
	<tr>
	  <td width="29%">&nbsp;</td>
      <td class="normalText" align="left" width="24%" >State / Province:</td>
      <td align="left" width="20%" ><input type="text" name="state" value="<? echo $config[state]; ?>" size="50"></td>
      <td align="left" width="27%">&nbsp;</td>
    </tr>
	
	<tr>
	  <td width="29%">&nbsp;</td>
      <td class="normalText" align="left" width="24%" >Country:</td>
      <td align="left" width="20%" ><input type="text" name="country" value="<? echo $config[country]; ?>" size="50"></td>
      <td align="left" width="27%">&nbsp;</td>
    </tr>
	
	<tr>
	  <td width="29%">&nbsp;</td>
      <td class="normalText" align="left" width="24%" >Postal:</td>
      <td align="left" width="20%" ><input type="text" name="postal" value="<? echo $config[postal]; ?>" size="50"></td>
      <td align="left" width="27%">&nbsp;</td>
    </tr>
	
	<tr>
	  <td width="29%">&nbsp;</td>
      <td class="normalText" align="left" width="24%" >Phone:</td>
      <td align="left" width="20%" ><input type="text" name="phone" value="<? echo $config[phone]; ?>" size="50"></td>
      <td align="left" width="27%">&nbsp;</td>
    </tr>
	
	<tr>
	  <td width="29%">&nbsp;</td>
      <td class="normalText" align="left" width="24%" >Fax:</td>
      <td align="left" width="20%" ><input type="text" name="fax" value="<? echo $config[fax]; ?>" size="50"></td>
      <td align="left" width="27%">&nbsp;</td>
    </tr>
    
	<tr>
	  <td width="29%">&nbsp;</td>
      <td align="left" width="24%" >&nbsp;</td>
      <td align="left" width="20%" >&nbsp;</td>
      <td align="left" width="27%">&nbsp;</td>
    </tr>
	
		
    <tr bgcolor="#f0f0f0">
       <td background="admin/images/bluebarBg.gif" class="normalText" colspan="4"><center><input type="submit" name="Submit" value="Save"></center></td>
    </tr>
	</form>
  </table>
  </td></tr>
  </table>






