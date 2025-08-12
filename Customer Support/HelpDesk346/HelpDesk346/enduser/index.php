<?php
	session_start();
	include_once "../config.php";
	
	mysql_connect(DB_HOST, DB_UNAME, DB_PASS);
	mysql_select_db(DB_DBNAME);
	
	$p = getcwd();
	chdir("../");
	include_once "./includes/settings.php";
	chdir($p);
	
	$error = false;
	if (!$OBJ->get('allow_enduser_reg')) header("Location: ../index.php");
	
	if (isset($_POST['command'])) include_once "./includes/validate.php";
?>
<html>
	<head>
		<title>End User Registration</title>
		<link rel="stylesheet" type="text/css" href="../style.css" />
		<style type="text/css">
			.formtext {
				 font-weight: bold;
				 font-size: 14px;
				 color: blue;
			}
			
			.error {
				font-family: Arial;
				font-size: 12px;
				color: red;
				padding-left: 5px;
			}
		</style>
	</head>
	
	<body bgcolor="#FFFFFF" text="#000000" " link="#0000FF" alink="#FF0000" vlink="#0000FF" onLoad="document.registerForm.uname.focus()">
<table height="392" width="540" border="0" align="left" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="111" colspan="3" valign="top"><img src="../images/help-desk-main.jpg" alt="Help Desk Software Main Page" width="540" height="150"></td>
  </tr>
  <tr> 
    <td height="10" colspan="3" align="center" style="padding-bottom:3px">
          <strong>Welcome to the Information Technology Help Desk End User Registration Page.</strong>
    </td>
  </tr>
  <tr> 
    <td width="100%" valign="top">
    <?php
	    if (isset($_POST['command']) && !$error) {
			include_once "./success.php";
		}
		else {
	?>
    	<table cellpadding="0" cellspacing="0" align="center" style="border:1px solid red; padding:5px">
    	<form name="registerForm" action="" method="post">
    	<input type="hidden" name="userType" value="3" />
			<tr><th colspan="2" style="font-size:16px">
				Please Fill in All Fields to Register
			</th></td>
			<tr><td height="5"></td></tr>
			
			<tr>
				<td class="formtext">Username:&nbsp;</td>
				<td><input type="text" name="uname" size="30" maxlength="50" value="<?php echo isset($_POST['uname']) ? $_POST['uname'] : ''; ?>" /></td>
				<td class="error"><?php echo isset($uname_error) ? $uname_error : ''; ?></td>
			</tr>
			<tr><td height="5"></td></tr>
			
			<tr>
				<td class="formtext">Password:&nbsp;</td>
				<td><input type="password" name="pass1" size="20" maxlength="30" /></td>
				<td></td>
			</tr>
			<tr><td height="5"></td></tr>
			
			<tr>
				<td class="formtext">Confirm Password:&nbsp;</td>
				<td><input type="password" name="pass2" size="20" maxlength="30" /></td>
				<td class="error"><?php echo isset($pass_error) ? $pass_error : ''; ?></td>
			</tr>
			<tr><td height="5"></td></tr>
			
			<tr>
				<td class="formtext">Email Address:&nbsp;</td>
				<td><input type="text" name="email_addr" size="20" maxlength="30" value="<?php echo isset($_POST['email_addr']) ? $_POST['email_addr'] : ''; ?>" /></td>
				<td class="error"><?php echo isset($email_error) ? $email_error : ''; ?></td>
			</tr>
			<tr><td height="5"></td></tr>
			
			<tr>
				<td class="formtext">First Name:&nbsp;</td>
				<td><input type="text" name="fname" size="20" maxlength="30" value="<?php echo isset($_POST['fname']) ? $_POST['fname'] : ''; ?>" /></td>
				<td class="error"><?php echo isset($fname_error) ? $fname_error : ''; ?></td>
			</tr>
			<tr><td height="5"></td></tr>
			
			<tr>
				<td class="formtext">Last Name:&nbsp;</td>
				<td><input type="text" name="lname" size="20" maxlength="30"  value="<?php echo isset($_POST['lname']) ? $_POST['lname'] : ''; ?>"/></td>
				<td class="error"><?php echo isset($lname_error) ? $lname_error : ''; ?></td>
			</tr>
			<tr><td height="5"></td></tr>
			
			<tr>
				<td class="formtext">Phone Number:</td>
				<td><input type="text" name="phoneNum" size="15" maxlength="15" value="<?php echo isset($_POST['phoneNum']) ? $_POST['phoneNum'] : ''; ?>" /></td>
				<td class="error"><?php echo isset($phoneNum_error) ? $phoneNum_error : ''; ?></td>
			</tr>
			<tr><td height="5"></td></tr>
			
			<tr>
				<td class="formtext">Phone Extension (Optional):</td>	
				<td><input type="text" name="phoneExt" size="15" maxlength="15" value="<?php echo isset($_POST['phoneExt']) ? $_POST['phoneExt'] : ''; ?>" /></td>
				<td class="error"><?php echo isset($phoneExt_error) ? $phoneExt_error : ''; ?></td>
			</tr>
			<tr><td height="5"></td></tr>
			
			<tr><td colspan="3" align="center">
				<input type="submit" name="command" value="Register Username" />
			</td></tr>
		</table>
	<?php
		}
	?>
	</td>
   </tr>
   <tr>
    <td width="7%" valign="top">
      <p align="center"><font size="2" face="Times New Roman, Times, serif">CopyRight 
        2005 Help Desk Reloaded<br>
        <a href="www.helpdeskreloaded.com">Today's Help Desk Software for Tomorrows 
        Problem.</a></font></p>
      <p align="center"><br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <img src="http://www.helpdeskreloaded.com/reload/help-desk-copyright.jpg" alt="http://www.helpdeskreloaded.com Help Desk Software By  HelpDeskReloaded &quot;Help Desk Reloaded&quot;"></p></td>
  </tr>
</table>
<p>&nbsp;</p>
<p> 
  <script Language="JavaScript">
<!-- hide// Navigation - Stop
var timerID = null;
var timerRunning = false;
function stopclock (){
        if(timerRunning)
                clearTimeout(timerID);
        timerRunning = false;
}
function showtime () {
        var now = new Date();
        var hours = now.getHours();
        var minutes = now.getMinutes();
        var seconds = now.getSeconds()
        var timeValue = "" + ((hours >12) ? hours -12 :hours)
        timeValue += ((minutes < 10) ? ":0" : ":") + minutes
        timeValue += ((seconds < 10) ? ":0" : ":") + seconds
        timeValue += (hours >= 12) ? " P.M." : " A.M."
        //document.clock.face.value = timeValue;
        // you could replace the above with this
        // and have a clock on the status bar:
        window.status = timeValue;
        timerID = setTimeout("showtime()",1000);
        timerRunning = true;
}
function startclock () {
        // Make sure the clock is stopped
        stopclock();
        showtime();
}
// un hide --->
</script>
</p>
<p>&nbsp;</p>
<p align="center"><font size="5" face="Arial, Helvetica, sans-serif">



<SCRIPT LANGUAGE="JavaScript">
<!--
startclock()
//-->
</SCRIPT>


</font></p>
<p>&nbsp;</p>
<p>&nbsp;</p>

</body>
</html>