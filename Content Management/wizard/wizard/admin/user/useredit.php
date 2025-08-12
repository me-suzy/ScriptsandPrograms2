<?php
/*  
   User profile edit
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

if ($config['user_view'] == "off" && !is_memberof(1)) {
    $message = "Sorry, Wizard is set to only allow the Webmaster to view user profiles.";
	$location = CMS_WWW . "/admin.php?id=2&item=27&message=$message";
	header("Location: $location");
	exit;
}


$colname = $_GET['colname'];
$colvalue = $_GET[$colname];

if (!$colname || !$colvalue) {
    $message = "Please select a Username for editing.";
    $location = CMS_WWW . "/admin.php?id=2&item=27&sub=28&message=$message";
	header("Location: $location");
	exit;
}


$db = new DB(); 
$db->query("SELECT * FROM ". DB_PREPEND . "users WHERE username='$colvalue'");
$user = $db->next_record();

//check the person coming to this page
$webeditor = user_getid();

if ($config['user_edit'] == "off" && !is_memberof(1)) { 
    $message = "Sorry, Settings - Permissions is set to only allow the Webmaster to edit user profiles.";
	$location = CMS_WWW . "/admin.php?id=2&item=27&message=$message";
	header("Location: $location");
	exit;
}


$message = $_GET['message'];
?>


 

<!-- Inner table -->
<div id="pagelinks">
<table border="1"  cellpadding="0" style="border-collapse: collapse"   bordercolor="#E5E5E5" width="100%">
<tr><td>
<table border="0"  bgcolor="#f8f8ff" cellpadding="5" style="border-collapse: collapse"   bordercolor="#E5E5E5" width="100%">
           
<form enctype='multipart/form-data' action='admin/user/usereditPro.php' method='post'>

 <?php
 if ($message) {   
	echo "<tr>";
  		echo "<td colspan=\"4\"><span class=\"message\">$message</span></td>";
	echo "</tr>";
 }
 ?>  

 	<?php
	//only the webmaster can delete users
	if (is_memberof(1)) {
	 
		if ($user[uid] == "1" ) {
		//can't delete primary Webmaster 
		} else {
			echo "<tr>";
			        echo "<td colspan=\"4\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Delete user?&nbsp;&nbsp;<a href=\"admin/user/userEditDelete.php?uid=" . $user['uid'] . "\"><img alt=\"Delete User\" border=\"0\" src=\"admin/images/del.gif\" height=\"11\" width=\"11\">&nbsp; Delete</a></td>";
			echo "</tr>";
		}
	
	} //if webmaster
	
	
	?>
 
 
<!--- User Identidy  -->
    <tr><td colspan="4">
	<table style="border-collapse: collapse" cellpadding="2" border="0" border-color="c0c0c0" width="100%">
	<tr class="normalText" bgcolor="#f0f0f0">
	   <td colspan="4" style="border: 1px solid #c0c0c0" align="right" background="admin/images/bluebar.gif" ><img alt="error" src="admin/images/blue_ball.gif" height="16" width="16" /></td><td border="0" width="100%" align="left" class="smallText" background="admin/images/bluebar.gif" ><b>&nbsp;Identidy</b></a></td>
    </tr>
	</table>
	</td></tr>
	
	<tr class="normalText"> 
	  <td width="24%">&nbsp;</td>
      <td align="left" width="22%" >User Id</td>
      <td align="left" width="27%" ><?php echo $user[uid] ?> 
      </td>
      <td width="32%">&nbsp;</td>
    </tr>
	
	<tr class="normalText"> 
	  <td width="24%">&nbsp;</td>
      <td align="left" width="22%" >Activated?</td>
      <td align="left" width="27%" > <input type="text" name="is_confirmed" value="<?php echo $user[is_confirmed] ?>" size="2" maxlength="1"> 
      &nbsp;&nbsp;"1" = activated,  "0" = de-activate</td>
      <td width="32%">&nbsp;</td>
    </tr>
	
	<tr class="normalText"> 
	  <td width="24%">&nbsp;</td>
      <td align="left" width="22%" >Username</td>
      <td align="left" width="27%" ><?php echo $user[username] ?> 
      </td>
      <td width="32%">&nbsp;</td>
    </tr>
	
	
	
	<tr class="normalText"> 
	  <td width="24%">&nbsp;</td>
      <td align="left" width="22%" >Registration IP</td>
      <td align="left" width="27%" ><?php echo $user[remote_addr] ?> 
      </td>
      <td width="32%">&nbsp;</td>
    </tr>
    
    <tr class="normalText"> 
	  <td width="24%">&nbsp;</td>
      <td align="left" width="22%" >First Name</td>
      <td align="left" width="27%" > <input type="text" name="first_name" value="<?php echo $user[first_name] ?>" size="16" maxlength="16"> 
      </td>
      <td width="32%">&nbsp;</td>
    </tr>
	
	<tr class="normalText"> 
	  <td width="24%">&nbsp;</td>
      <td align="left" width="22%" >Last Name</td>
      <td align="left" width="27%" > <input type="text" name="last_name" value="<?php echo $user[last_name] ?>" size="16" maxlength="16"> 
      </td>
      <td width="32%">&nbsp;</td>
    </tr>
	
	<tr class="normalText"> 
	  <td width="24%">&nbsp;</td>
      <td align="left" width="22%" >Organization</td>
      <td align="left" width="27%" > <input type="text" name="organization" value="<?php echo $user[organization] ?>" size="30" maxlength="50"> 
      </td>
      <td width="32%">&nbsp;</td>
    </tr>

<!--- Group Memberships  -->
<?php 
// if group editing is turned on for administrators
if ($config[group_edit] == "off" && !is_memberof(1) ) {
  //option to add or remove users from groups is turned off for administrators
}
else {
    echo "<tr><td colspan=\"4\">";
	echo "<table style=\"border-collapse: collapse\" cellpadding=\"2\" border=\"0\" border-color=\"c0c0c0\" width=\"100%\">";
	
	echo "<tr class=\"normalText\" bgcolor=\"#f0f0f0\">";
	   echo "<td colspan=\"4\" style=\"border: 1px solid #c0c0c0\" align=\"right\" background=\"admin/images/bluebar.gif\" ><img alt=\"error\" src=\"admin/images/blue_ball.gif\" height=\"16\" width=\"16\" /></td><td border=\"0\" width=\"100%\" align=\"left\" class=\"smallText\" background=\"admin/images/bluebar.gif\" ><b>&nbsp;Group Membership</b></a></td>";
    echo "</tr></table></td></tr>";
	
	echo "<tr class=\"normalText\">"; 
	  echo "<td width=\"24%\">&nbsp;</td>";
      echo "<td valign=\"top\" align=\"left\" width=\"22%\" >Memberships</td>";
      echo "<td align=\"left\" width=\"27%\" >";
	  
	  //function call to admin/user/user.php to get list of names of current usergroups
	    $groupNames = group_names();
	     
	    //find group numbers (gids) this user belongs to (user.php function)
		$uid = $user['uid'];
		$list = groups($uid); 
		
		if (!$list) {
          echo "<b>User is not assigned to any groups.</b>";		    
		}
		else {
		    
			$db = new DB();
			foreach ($list as $value )
			{
			    
				$gid = $value['gid'];
				$db->query("SELECT name FROM ". DB_PREPEND . "groups where gid='$gid' ");
				$j = $db->next_record();
				$name = $j['name'];		
				//option to delete user from groups		
				echo "<a href=\"". CMS_WWW ."/admin/user/userDelFromGroup.php?gid=$gid&uid=$uid&colname=$colname&colvalue=$colvalue\">$name<span class=\"tinyText\"><font color=\"Red\">&nbsp;&nbsp;&nbsp;&nbsp;Remove</font></span></a><br>";
                $memberships[] = $name; // array of groups this user belongs to
			}
			//check to see if this user belongs to all groups
			
		}// else
		
		echo "</td>";
      	echo "<td width=\"32%\"  >&nbsp;</td>";
    	echo "</tr>";

//Check to see if there is any groups this user might join

		//can webmasters add other webmasters?
		if ($config['reg_webmaster'] == "on") {
		$db = new DB();
		$db->query("SELECT gid,name FROM ". DB_PREPEND . "groups ");		
		}
		else {
		$db = new DB();
		$db->query("SELECT gid,name FROM ". DB_PREPEND . "groups WHERE gid<>'1' ");	
		}

		$groupcount = $db->num_rows();
		if (!$groupcount) { 
		   //do not show select box
		}
		else {

			echo "<tr class=\"normalText\">"; 
			echo "<td width=\"24%\">&nbsp;</td>";
      		echo "<td valign=\"top\" align=\"left\" width=\"22%\" >Add to Group</td>";
      		echo "<td align=\"left\" width=\"27%\" >";
	  	
	 		echo "<select name=\"gid[]\" multiple=\"multiple\" >";
			while($j = $db->next_record()){
			$gid = $j['gid'];
			$name = $j['name'];
			//do not show those the user is already a member of
			if (!in_array($name, $memberships) ) {
	    		echo "<option value=\"$gid\">$name</option>";
	   		} // in array
	    	
			} // while$j = $db->next_record(); 
		echo "</select>";
	 
	   	} //skip
	
      	echo "</td>";
      	echo "<td width=\"32%\" >&nbsp;</td>";
    	echo "</tr>";
    
	} // do not display 

	?>
	
	
<!--- Coordinates  -->
    <tr><td colspan="4">
	<table style="border-collapse: collapse" cellpadding="2" border="1" border-color="c0c0c0" width="100%">
	<tr class="normalText" bgcolor="#f0f0f0">
	   <td colspan="4" style="border: 1px solid #c0c0c0" align="right" background="admin/images/bluebar.gif" ><img alt="error" src="admin/images/blue_ball.gif" height="16" width="16" /></td><td border="0" width="100%" align="left" class="smallText" background="admin/images/bluebar.gif" ><b>&nbsp;Coordinates</b></a></td>
    </tr>
	</table>
	</td></tr>
	
	<tr>
	  <td width="24%">&nbsp;</td>
      <td class="normalText" align="left" width="22%" >Email:</td>
      <td align="left" width="27%" ><input type="text" name="email" value="<? echo $user[email]; ?>" size="50"></td>
      <td width="32%">&nbsp;</td>
    </tr>
	
	
    <tr>
	  <td width="24%">&nbsp;</td>
      <td class="normalText" align="left" width="22%" >Address:</td>
      <td align="left" width="27%" ><input type="text" name="address" value="<? echo $user[address]; ?>" size="50"></td>
      <td width="32%">&nbsp;</td>
    </tr>
	
	<tr> 
	  <td width="24%">&nbsp;</td>
      <td class="normalText" align="left" width="22%" >Address:</td>
      <td align="left" width="27%" ><input type="text" name="address2" value="<? echo $user[address2]; ?>" size="50"></td>
      <td width="32%">&nbsp;</td>
    </tr>
	
	<tr>
	  <td width="24%">&nbsp;</td>
      <td class="normalText" align="left" width="22%" >City:</td>
      <td align="left" width="27%" ><input type="text" name="city" value="<? echo $user[city]; ?>" size="50"></td>
      <td width="32%">&nbsp;</td>
    </tr>
	
	<tr>
	  <td width="24%">&nbsp;</td>
      <td class="normalText" align="left" width="22%" >State / Province:</td>
      <td align="left" width="27%" ><input type="text" name="state" value="<? echo $user[state]; ?>" size="50"></td>
      <td width="32%">&nbsp;</td>
    </tr>
	
	<tr>
	  <td width="24%">&nbsp;</td>
      <td class="normalText" align="left" width="22%" >Country:</td>
      <td align="left" width="27%" ><input type="text" name="country" value="<? echo $user[country]; ?>" size="50"></td>
      <td width="32%">&nbsp;</td>
    </tr>
	
	<tr>
	  <td width="24%">&nbsp;</td>
      <td class="normalText" align="left" width="22%" >Postal:</td>
      <td align="left" width="27%" ><input type="text" name="postal" value="<? echo $user[postal]; ?>" size="50"></td>
      <td width="32%">&nbsp;</td>
    </tr>
	
	<tr>
	  <td width="24%">&nbsp;</td>
      <td class="normalText" align="left" width="22%" >Phone:</td>
      <td align="left" width="27%" ><input type="text" name="phone" value="<? echo $user[phone]; ?>" size="50"></td>
      <td width="32%">&nbsp;</td>
    </tr>
	
	<tr>
	  <td width="24%">&nbsp;</td>
      <td class="normalText" align="left" width="22%" >Fax:</td>
      <td align="left" width="27%" ><input type="text" name="fax" value="<? echo $user[fax]; ?>" size="50"></td>
      <td width="32%">&nbsp;</td>
    </tr>
	
	<!--- Comment  -->
    <tr><td colspan="4">
	<table style="border-collapse: collapse" cellpadding="2" border="1" border-color="c0c0c0" width="100%">
	<tr class="normalText" bgcolor="#f0f0f0">
	   <td colspan="4" style="border: 1px solid #c0c0c0" align="right" background="admin/images/bluebar.gif" ><img alt="error" src="admin/images/blue_ball.gif" height="16" width="16" /></td><td border="0" width="100%" align="left" class="smallText" background="admin/images/bluebar.gif" ><b>&nbsp;Notes</b></a></td>
    </tr>
	</table>
	</td></tr>
	
	<tr>
	  <td width="24%">&nbsp;</td>
      <td class="normalText" valign="top" align="left" width="22%" >Notes on this user:</td>
      <td align="left" width="27%" ><textarea name='comment' rows=10 cols=50><? echo $user[comment]; ?></textarea></td>
      <td width="32%">&nbsp;</td>
    </tr>
    
	<tr>
	  <td width="24%">&nbsp;</td>
      <td align="left" width="22%" >&nbsp;</td>
      <td align="left" width="27%" >&nbsp;</td>
      <td width="32%">&nbsp;</td>
    </tr>
	
		
    <tr bgcolor="#f0f0f0">
	   <input type="hidden" name="uid" value="<?php echo $user[uid]; ?>">
       <td background="admin/images/bluebarBg.gif" class="normalText" colspan="4"><center><input type="submit" name="Submit" value="Save"></center></td>
    </tr>
	</form>
  </table>
  </td></tr>
  </table>
</div>




