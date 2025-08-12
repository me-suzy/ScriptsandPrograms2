<?php
/*  
   Add User
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

if ($config['user_add'] == "off" && !is_memberof(1)) {
    $message = "Sorry, Wizard is set to allow only the Webmaster to add users.";
	$location = CMS_WWW . "/admin.php?id=2&item=27&message=$message";
	header("Location: $location");
}



$message = $_GET['message'];

$username = $_GET['username'];
$first_name = $_GET['first_name'];
$last_name = $_GET['last_name'];
$organization = $_GET['organization'];
$email = $_GET['email'];
$phone = $_GET['phone'];
$fax = $_GET['fax'];
$address = $_GET['address'];
$address2 = $_GET['address2'];
$city = $_GET['city'];
$state = $_GET['state'];
$country = $_GET['country'];
$postal = $_GET['postal'];
$subscribe = $_GET['subscribe'];
$is_confirmed = $_GET['is_confirmed'];
$comment = $_GET['comment'];

?>


 

<!-- Inner table -->
<div id="pagelinks">
<table border="1"  bgcolor="#f8f8ff" cellpadding="5" style="border-collapse: collapse"   bordercolor="#E5E5E5" width="100%">
<tr><td>
<table border="0"  bgcolor="#f8f8ff" cellpadding="5" style="border-collapse: collapse"   bordercolor="#E5E5E5" width="100%">
                 
<form enctype='multipart/form-data' action='admin/user/useraddPro.php' method='post'>

  
    <tr>
  		<td colspan="4"><span class="message"><?php echo $message; unset($message); ?><br /></span></td>
	</tr>
    

<!--- User Identidy  -->
    <tr><td colspan="4">
	<table style="border-collapse: collapse" cellpadding="2" border="0" border-color="c0c0c0" width="100%">
	<tr class="normalText" bgcolor="#f0f0f0">
	   <td colspan="4" style="border: 1px solid #c0c0c0" align="right" background="admin/images/bluebar.gif" ><img alt="error" src="admin/images/blue_ball.gif" height="16" width="16" /></td><td border="0" width="100%" align="left" class="smallText" background="admin/images/bluebar.gif" ><b>&nbsp;Identidy</b></a></td>
    </tr>
	</table>
	</td></tr>
	
	
	<tr class="normalText"> 
	  <td width="24%" >&nbsp;</td>
      <td align="left" width="22%" >Activate?</td>
      <td align="left" width="27%" > <input type="text" name="is_confirmed" value="<?php echo $is_confirmed; ?>" size="2" maxlength="1"> 
      &nbsp;&nbsp;"1" = activated,  "0" = de-activate</td>
      <td align="left" width="32%" ></td>
    </tr>
	
	<tr class="normalText"> 
	  <td width="24%" >&nbsp;</td>
      <td align="left" width="22%" >Username</td>
      <td align="left" width="27%" ><input type="text" name="username" value="<?php echo $username; ?>" size="16" maxlength="16"> 
      </td>
      <td align="left" width="32%">&nbsp;</td>
    </tr>
	
	<tr class="normalText"> 
	  <td width="24%" >&nbsp;</td>
      <td align="left" width="22%" >Password</td>
      <td align="left" width="27%" > <input type="text" name="password" value="<?php echo $password ?>" size="16" maxlength="16"> 
      </td>
      <td align="left" width="32%">&nbsp;</td>
    </tr>
	
	<tr class="normalText"> 
	  <td width="24%" >&nbsp;</td>
      <td align="left" width="22%" >Password again</td>
      <td align="left" width="27%" > <input type="text" name="password2" value="<?php echo $password2; ?>" size="16" maxlength="16"> 
      </td>
      <td align="left" width="32%">&nbsp;</td>
    </tr>
    
    <tr class="normalText"> 
	  <td width="24%" >&nbsp;</td>
      <td align="left" width="22%" >First Name</td>
      <td align="left" width="27%" > <input type="text" name="first_name" value="<?php echo $first_name; ?>" size="16" maxlength="16"> 
      </td>
      <td align="left" width="32%">&nbsp;</td>
    </tr>
	
	<tr class="normalText"> 
	  <td width="24%" >&nbsp;</td>
      <td align="left" width="22%" >Last Name</td>
      <td align="left" width="27%" > <input type="text" name="last_name" value="<?php echo $last_name; ?>" size="16" maxlength="16"> 
      </td>
      <td align="left" width="32%">&nbsp;</td>
    </tr>
	
	<tr class="normalText"> 
	  <td width="24%" >&nbsp;</td>
      <td align="left" width="22%" >Organization</td>
      <td align="left" width="27%" > <input type="text" name="organization" value="<?php echo $organization; ?>" size="30" maxlength="50"> 
      </td>
      <td align="left" width="32%">&nbsp;</td>
    </tr>

<!--- Group Memberships  -->
    <tr><td colspan="4">
	<table style="border-collapse: collapse" cellpadding="2" border="0" border-color="c0c0c0" width="100%">
	<tr class="normalText" bgcolor="#f0f0f0">
	   <td colspan="4" style="border: 1px solid #c0c0c0" align="right" background="admin/images/bluebar.gif" ><img alt="error" src="admin/images/blue_ball.gif" height="16" width="16" /></td><td border="0" width="100%" align="left" class="smallText" background="admin/images/bluebar.gif" ><b>&nbsp;Group Membership</b></a></td>
    </tr>
	</table>
	</td></tr>
	
	 
	 <tr class="normalText"> 
	  <td width="24%" >&nbsp;</td>
      <td valign="top" align="left" width="22%" >Add to Group</td>
      <td valign="top" align="left" width="27%" >
	  
	 <?  
	    	 
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

				  	
	 		echo "<select name=\"gid[]\" multiple=\"multiple\" >";
			while($j = $db->next_record()){
			$gid = $j['gid'];
			$name = $j['name'];
			echo "<option value=\"$gid\">$name</option>";
	   		
	    	
			} // while$j = $db->next_record(); 
		echo "</select>";
	 
	   	} //skip
	 
	?>
      </td>
      <td align="left" width="32%">&nbsp;</td>
    </tr>
	
	
<!--- Coordinates  -->
    <tr><td colspan="4">
	<table style="border-collapse: collapse" cellpadding="2" border="1" border-color="c0c0c0" width="100%">
	<tr class="normalText" bgcolor="#f0f0f0">
		   <td colspan="4" style="border: 1px solid #c0c0c0" align="right" background="admin/images/bluebar.gif" ><img alt="error" src="admin/images/blue_ball.gif" height="16" width="16" /></td><td border="0" width="100%" align="left" class="smallText" background="admin/images/bluebar.gif" ><b>&nbsp;Coordinates</b></a></td>
	    </tr>
		</table>
		</td></tr>
		
		<tr>
	  <td width="24%" >&nbsp;</td>
      <td class="normalText" align="left" width="22%" >Email:</td>
      <td align="left" width="27%" ><input type="text" name="email" value="<? echo $email; ?>" size="50"></td>
      <td align="left" width="32%">&nbsp;</td>
    </tr>
	
	
    <tr>
	  <td width="24%" >&nbsp;</td>
      <td class="normalText" align="left" width="22%" >Address:</td>
      <td align="left" width="27%" ><input type="text" name="address" value="<? echo $address; ?>" size="50"></td>
      <td align="left" width="32%">&nbsp;</td>
    </tr>
	
	<tr>
	  <td width="24%" >&nbsp;</td>
      <td class="normalText" align="left" width="22%" >Address:</td>
      <td align="left" width="27%" ><input type="text" name="address2" value="<? echo $address2; ?>" size="50"></td>
      <td align="left" width="32%">&nbsp;</td>
    </tr>
	
	<tr>
	  <td width="24%" >&nbsp;</td>
      <td class="normalText" align="left" width="22%" >City:</td>
      <td align="left" width="27%" ><input type="text" name="city" value="<? echo $city; ?>" size="50"></td>
      <td align="left" width="32%">&nbsp;</td>
    </tr>
	
	<tr>
	  <td width="24%" >&nbsp;</td>
      <td class="normalText" align="left" width="22%" >State / Province:</td>
      <td align="left" width="27%" ><input type="text" name="state" value="<? echo $state; ?>" size="50"></td>
      <td align="left" width="32%">&nbsp;</td>
    </tr>
	
	<tr>
	  <td width="24%" >&nbsp;</td>
      <td class="normalText" align="left" width="22%" >Country:</td>
      <td align="left" width="27%" ><input type="text" name="country" value="<? echo $country; ?>" size="50"></td>
      <td align="left" width="32%">&nbsp;</td>
    </tr>
	
	<tr>
	  <td width="24%" >&nbsp;</td>
      <td class="normalText" align="left" width="22%" >Postal:</td>
      <td align="left" width="27%" ><input type="text" name="postal" value="<? echo $postal; ?>" size="50"></td>
      <td align="left" width="32%">&nbsp;</td>
    </tr>
	
	<tr>
	  <td width="24%" >&nbsp;</td>
      <td class="normalText" align="left" width="22%" >Phone:</td>
      <td align="left" width="27%" ><input type="text" name="phone" value="<? echo $phone; ?>" size="50"></td>
      <td align="left" width="32%">&nbsp;</td>
    </tr>
	
	<tr>
	  <td width="24%" >&nbsp;</td>
      <td class="normalText" align="left" width="22%" >Fax:</td>
      <td align="left" width="27%" ><input type="text" name="fax" value="<? echo $fax; ?>" size="50"></td>
      <td align="left" width="32%">&nbsp;</td>
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
	  <td width="24%" >&nbsp;</td>
      <td valign="top" class="normalText" align="left" width="22%" >Notes on this user:</td>
      <td align="left" width="27%" ><textarea name='comment' rows=10 cols=60><? echo $comment; ?></textarea></td>
      <td align="left" width="32%">&nbsp;</td>
    </tr>
    
	<tr>
	  <td width="24%" >&nbsp;</td>
      <td align="left" width="22%" >&nbsp;</td>
      <td align="left" width="27%" >&nbsp;</td>
      <td class="normalText">&nbsp;</td>
    </tr>
	
		
    <tr bgcolor="#f0f0f0">
	   <td background="admin/images/bluebarBg.gif" class="normalText" colspan="4"><center><input type="submit" name="Submit" value="Save"></center></td>
    </tr>
	</form>
  </table>
  </td></tr>
  </table>
</div>




