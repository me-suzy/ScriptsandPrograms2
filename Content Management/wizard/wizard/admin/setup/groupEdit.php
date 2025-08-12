<?php
/*  
   Group Edit
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

if ($config['admin_groups'] == "off" && !is_memberof(1)) {
    $message = "Sorry, \"Settings\" allow only the Webmaster to edit Groups.";
	$location = CMS_WWW . "/admin.php?id=2&item=24&sub30&message=$message";
	header("Location: $location");
}


$colname = $_GET['colname'];
$colvalue = $_GET["$colname"];

if (!$colname || !$colvalue) {
    $message = "Select the Group Id number.";
    $location = CMS_WWW . "/admin.php?id=2&item=24&sub=30&message=$message";
	header("Location: $location");
	exit;
}


$db = new DB(); 
$db->query("SELECT * FROM ". DB_PREPEND . "groups WHERE $colname='$colvalue'");
$group = $db->next_record();


$message = $_GET['message'];
?>


 

<!-- Inner table -->
<div id="pagelinks">
<table border="1"  bgcolor="#f8f8ff" cellpadding="5" style="border-collapse: collapse"   bordercolor="#E5E5E5" width="100%">
<tr><td>
<table border="0"  bgcolor="#f8f8ff" cellpadding="5" style="border-collapse: collapse"   bordercolor="#E5E5E5" width="100%">
                 
<form enctype='multipart/form-data' action='admin/setup/groupeditPro.php' method='post'>

  
    <tr>
  		<td colspan="3"><span class="message"><?php echo $message; unset($message); ?><br /></span></td>
	</tr>
    


    <tr><td colspan="3">
	<table style="border-collapse: collapse" cellpadding="2" border="0" border-color="c0c0c0" width="100%">
	<tr class="normalText" bgcolor="#f0f0f0">
	   <td colspan="3" style="border: 1px solid #c0c0c0" align="right" background="admin/images/bluebar.gif" ><img alt="error" src="admin/images/blue_ball.gif" height="16" width="16" /></td><td border="0" width="100%" align="left" class="smallText" background="admin/images/bluebar.gif" ><b>&nbsp;Group Edit</b></a></td>
    </tr>
	</table>
	</td></tr>
	
	<tr class="normalText"> 
      <td align="left" width="20%" >Group Id</td>
      <td align="left" width="80%" >
	  <?php 
	  if ($group[gid] != "1" && $group[gid] != "2"  ) {
	      echo "<input type=\"text\" name=\"gid\" value=\"$group[gid]\" size=\"5\" maxlength=\"4\">";
	  }
	  else
	  {
	  echo $group[gid];
	  }
	  ?> 
      </td>
      <td align="left" width="1%"  class="normalText"></td>
    </tr>
	
	<tr class="normalText"> 
      <td align="left" width="20%" >Group Name</td>
      <td align="left" width="80%" >
	  <?php 
	  if ($group[gid] != "1" && $group[gid] != "2"  ) {
	      echo "<input type=\"text\" name=\"name\" value=\"$group[name]\" size=\"20\" maxlength=\"50\">";
	  }
	  else
	  {
	  echo $group[name];
	  }
	  ?> 
      </td>
      <td align="left" width="1%"  class="normalText"></td>
    </tr>
	
	
	<tr class="normalText"> 
      <td align="left" width="20%" >Description</td>
      <td align="left" width="80%" >
	  <?
	      echo "<input type=\"text\" name=\"description\" value=\"$group[dsc]\" size=\"70\" maxlength=\"200\">";
	  
	  ?> 
      </td>
      <td align="left" width="1%"  class="normalText"></td>
    </tr>
	
	
	<?php
	if ($group[gid] == "1" || $group[gid] == "2"  ) {
	//can't delete Webmaster or Administrator
	} else {
	echo "<tr>";
	   echo "<td align=\"left\" width=\"20%\" >Delete this Group?</td>";
      echo "<td align=\"left\" width=\"80%\" ><a href=\"admin/setup/groupEditDelete.php?gid=" . $group['gid'] . "\"><img alt=\"Delete Group\" border=\"0\" src=\"admin/images/del.gif\" height=\"11\" width=\"11\">&nbsp; Delete</a></td>";
      echo "<td width=\"1%\" class=\"normalText\">&nbsp;</td>";
	echo "</tr>";
	}
	?>
	<tr>
      <td align="left" width="20%" >&nbsp;</td>
      <td align="left" width="80%" >&nbsp;</td>
      <td width="1%" class="normalText">&nbsp;</td>
    </tr>
		
    <tr bgcolor="#f0f0f0">
	   <input type="hidden" name="recordId" value="<?php echo $group[gid]; ?>">
	   <?php 
	   	if ($group[gid] == "1" || $group[gid] == "2"  ) {
	    	echo "<input type=\"hidden\" name=\"gid\" value=\"$group[gid]\">";
			echo "<input type=\"hidden\" name=\"name\" value=\"$group[name]\">";
	  	}
	   ?>
	   
       <td background="admin/images/bluebarBg.gif" class="normalText" colspan="3"><center><input type="submit" name="Submit" value="Save"></center></td>
    </tr>
	</form>
  </table>
  </td></tr>
  </table>
</div>

