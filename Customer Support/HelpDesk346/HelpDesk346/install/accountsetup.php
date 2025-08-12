<?php
	include_once "../includes/constants.php";
	if (isset($_POST['submit'])) {
		include_once "./accountsinject.php";
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Help Desk Accounts Setup</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../style.css" rel="stylesheet" type="text/css">
</head>
<script language="JavaScript">
function validate()
{
if(document.form1.UserAccount.value=="")
     {
             alert("Please Enter User Name");
             document.form1.UserAccount.focus();
             return false;
     }
	
 if(document.form1.password.value=="")
	 {
	 	alert("Please Enter Password");
		document.form1.password.focus();
		return false;
	 }
   if(document.form1.pathToHelpDesk.value=="")
	{
		alert("Please Enter Path To HelpDesk");
		document.form1.pathToHelpDesk.focus();
		return false;
	}
	

   }
return true;

</script>
<body>   <form name="form1" method="post" action=""  onSubmit="return validate()">
  <table width="100%" border="0">
  <form method="post" action="">
    <tr> 
      <td colspan="3"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr> 
            <td height=""> <p align="center"><strong>Help Desk User Account Creation. 
                &quot;Accounts used to login to the help desk&quot;.</strong><br>
                <br>
                You will need to create accounts for every staff member that requires 
                access to the help desk admin Interface. At this point of the 
                setup we give you the opportunity to enter 1 to 5 accounts, you 
                can alwatys insert new accounts later.</p>
              <p align="center">Press &quot;Create Accounts&quot; button at the 
                bottom of the page when you have entered your desired accounts.</p>
            </td>
           </tr>
           <tr><td colspan="4" align="center" class="error">
           <?php echo isset($error_msg) ? $error_msg : ''; ?>	
           </td></tr>
           <tr><td valign="top">
            <?php
              	for ($i=0; $i<DEFAULT_INSTALL_USERS; $i++)
              	{
            ?>
              <span style="font-weight:bold">Account <?php echo ($i+1); ?></span>
              <table border="0" width="70%" cellspacing="0" cellpadding="0">
                <tr> 
                  <td>First Name:</td>
                  <td><input name="accounts[<?php echo $i; ?>][fname]" type="text" value="<?php echo isset($_POST['accounts'][$i]['fname']) ? $_POST['accounts'][$i]['fname'] : ''; ?>" /></td>
                  <td>Example: Ted</td>
                </tr>
                <tr> 
                  <td>Last Name:</td>
                  <td><input name="accounts[<?php echo $i; ?>][lname]" type="text" value="<?php echo isset($_POST['accounts'][$i]['lname']) ? $_POST['accounts'][$i]['lname'] : ''; ?>" /></td>
                  <td>Example: Sloth</td>
                </tr>
                <tr> 
                  <td>User Account:</td> 
                  <td><input name="accounts[<?php echo $i; ?>][uname]" type="text" value="<?php echo isset($_POST['accounts'][$i]['uname']) ? $_POST['accounts'][$i]['uname'] : ''; ?>" /></td>
                  <td>Example TSloth</td>
                </tr>
                <tr> 
                  <td>Password:</td>
                  <td><input name="accounts[<?php echo $i; ?>][psswd]" type="password" value="<?php echo isset($_POST['accounts'][$i]['psswd']) ? $_POST['accounts'][$i]['psswd'] : ''; ?>"/></td>
                  <td width="13%">Example: superglobal</td>
                </tr>
                <tr> 
                  <td>Email Address:</td>
                  <td><input name="accounts[<?php echo $i; ?>][email]" type="text" value="<?php echo isset($_POST['accounts'][$i]['email']) ? $_POST['accounts'][$i]['email'] : ''; ?>"/></td>
                  <td width="13%">Example: superglobal@somedomain.com</td>
                </tr>
                <tr>
                	<td>Phone Number:</td>
                	<td><input name="accounts[<?php echo $i; ?>][phoneNum]" type="text" value="<?php echo isset($_POST['accounts'][$i]['phoneNum']) ? $_POST['accounts'][$i]['phoneNum'] : ''; ?>" /></td>
                	<td>Example: (123) 456-7890</td>
                </tr>
                <tr>
                	<td>Phone Extension: (Optional)</td>
                	<td><input name="accounts[<?php echo $i; ?>][phoneExt]" type="text" value="<?php echo isset($_POST['accounts'][$i]['phoneExt']) ? $_POST['accounts'][$i]['phoneExt'] : ''; ?>" /></td>
                	<td>Example: 1234</td>
                </tr>
                <tr>
                	<td valign="top">User Type:</td>
                	<td valign="top" colspan="2">
                		<input type="radio" name="accounts[<?php echo $i; ?>][userType]" value="0"<?php echo isset($_POST['submit']) ? (($_POST['accounts'][$i]['userType'] == 0) ? ' checked="checked"' : '') : ''; ?> />Registered End User&nbsp;
                		<input type="radio" name="accounts[<?php echo $i; ?>][userType]" value="1"<?php echo isset($_POST['submit']) ? (($_POST['accounts'][$i]['userType'] == 1) ? ' checked="checked"' : '') : ''; ?> />Technician<br/>
                		<input type="radio" name="accounts[<?php echo $i; ?>][userType]" value="2"<?php echo isset($_POST['submit']) ? (($_POST['accounts'][$i]['userType'] == 2) ? ' checked="checked"' : '') : ' checked="checked"'; ?> />Administrator&nbsp;
                	</td>
                </tr>
                <tr><td colspan="3" class="error">
               	<?php echo isset($_POST['accounts'][$i]['error']) ? $_POST['accounts'][$i]['error'] : ''; ?>
               	</td></tr>
              </table>
              <hr/>
              <?php
              	}
              ?>
              </td></tr>
              <tr><td colspan="4" align="center" class="error">
              <?php echo isset($error_msg) ? $error_msg : ''; ?>	
              </td></tr>
                <tr><th align="left">Initial Help Desk Settings</th></tr>
                <tr> 
                  <td height="25" colspan="5">
                  	<table cellpadding="0" cellspacing="0" border="0">
                  		<tr>
                  			<td>
                  				Navigation Type:&nbsp;<br/>
                     			<input name="navigation" type="radio" value="B" checked>Banner Navigation<br/>
                     			<input type="radio" name="navigation" value="T">Text Navigation
                     		</td>
                     		<td width="40"></td>
                  			<td>
                  				Security:&nbsp;<br/>
                  				<input name="helpdesk" type="radio" value="S" checked>Secure Help Desk<br/>
                    			<input name="helpdesk" type="radio" value="O">Open Help Desk
                    		</td>
                    		<td width="40"></td>
                    		<td>
                    			Results per Page:&nbsp;
                  				<select name="result_page" size="1">
                  				<?php
                  					for ($i=5; $i<=30; $i+=5)
                  						echo '<option value="' . $i . '">' . $i . '</option>' . chr(10);
                  				?>
                  				</select><br/>
                  				Email Type:&nbsp;
                  				<select name="email_type" size="1">
                  					<option value="0">Plain Text</option>
                  					<option value="1" selected="selected">HTML</option>
                  				</select>
                    		</td>
                    	</tr>
                    	<tr><td height="10"></td></tr>
                    	
                    	<tr>
                    		<td>
                    			Public KnowledgeBase:&nbsp;<br/>
                    			<input type="radio" name="show_kb" value="1" />Yes<br/>
                    			<input type="radio" name="show_kb" value="0" checked="checked" />No
                    		</td>
                    		<td width="40"></td>
                    		<td>
                    			Public Ticket Lookup:&nbsp;<br/>
                    			<input type="radio" name="ticketAccessModify" value="1" checked="checked" />Yes<br/>
                    			<input type="radio" name="ticketAccessModify" value="0">No
                    		</td>
                    		<td width="40"></td>
	                    	<td>
	                    		Public Priority Definition:<br/>
	                    		<input type="radio" name="user_defined_priorities" value="1" />Yes<br/>
	                    		<input type="radio" name="user_defined_priorities" value="0" checked="checked" />
	                    	</td>
                    	</tr>
                    	<tr>
                    		<td>
                    			Helpdesk From Address:&nbsp;<br/>
                    			<input type="text" name="hd_from" size="20" maxlength="50" />
                    		</td>
                    	</tr>
                    	<tr><td colspan="="3" align="center" style="color:red">
                    	<?php echo isset($setting_data_error) ? $setting_data_error : ''; ?>
                    	</td></tr>
                    </table>
                  </td>
                </tr>
              </table>
              <!--<hr noshade> -->
            </td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td width="32%">&nbsp;</td>
      <td width="68%" colspan="2">&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="3"><div align="center"> 
          <input type="submit" name="submit" value="Create Accounts" class="button">
        </div></td>
    </tr>
    <tr> 
      <td colspan="3">&nbsp;</td>
    </tr>
  </form>
  </table>
</form>
<div align="center"><br>
  <a href="http://www.helpdeskreloaded.com"><img src="http://www.helpdeskreloaded.com/reload/help-desk-copyright.jpg" alt="http://www.helpdeskreloaded.com Help Desk Software By  HelpDeskReloaded &quot;Help Desk Reloaded&quot;" border="0"></a> 
</div>
</body>
</html>
