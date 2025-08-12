<?php
	$path = getcwd();
	chdir('..');
	include("checksession.php"); 		//we will assume this code gives us a secure session in which to work
	include_once "./includes/settings.php";
	chdir($path);
?>
<html>
	<head>
		<title>Create Problem Category</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta name="Designed by Chad Edwards" content="QuickIntranet.com">
		<script language="JavaScript">
		<!--
		function MM_reloadPage(init) {  //reloads the window if Nav4 resized
		  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
		    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
		  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
		}
		MM_reloadPage(true);
		// -->
		</script>
		<link href="style.css" rel="stylesheet" type="text/css">
	</head>
	<body bgcolor="#FFFFFF" text="#000000"  link="#0000FF" alink="#FF0000" vlink="#0000FF">
<?php
	$error = false;
	if (isset($_POST['submit'])) {
		//we do the insertion work here (or update - the later in most cases)
		if (count($_POST['step']) != 3) {
			$error = true;
			$msg = "Invalid Number of Options Selected";
		}
		
		//Parse Out the Values - series fo switches
		switch ($_POST['step'][1])
		{
			case 'a': $hdemail_create = 0; break;	
			case 'b': $hdemail_create = 1; break;
			case 'c': $hdemail_create = 2; break;
			case 'd': $hdemail_create = 3; break;
		}
		
		switch ($_POST['step'][2])
		{
			case 'a': $hdemail_update = 0; break;
			case 'b': $hdemail_update = 1; break;
			case 'c': $hdemail_update = 2; break;
			case 'd': $hdemail_update = 3; break;
			case 'e': $hdemail_update = 4; break;
			case 'f': $hdemail_update = 5; break;
			case 'g': $hdemail_update = 6; break;
			case 'h': $hdemail_update = 7; break;
		}
		
		switch ($_POST['step'][3])
		{
			case 'a': $hdemail_close = 0; break;
			case 'b': $hdemail_close = 1; break;
			case 'c': $hdemail_close = 2; break;
			case 'd': $hdemail_close = 3; break;
			case 'e': $hdemail_close = 4; break;
			case 'f': $hdemail_close = 5; break;
			case 'g': $hdemail_close = 6; break;
			case 'h': $hdemail_close = 7; break;	
		}
		
		$OBJ->set('hdemail_up', $hdemail_update);
		$OBJ->set('hdemail_create', $hdemail_create);
		$OBJ->set('hdemail_close', $hdemail_close);
	}
	
	if (isset($_POST['submit']) && !$error) {
		$OBJ->commit();
		$_SESSION['obj'] = serialize($OBJ);
		$msg = '';  //surpress superflous error
		echo '<div align="center" style="font-weight:bold; color:blue">Successfully Updated Settings</div>' . chr(10);   //char 10 is ascii code for a new line character
	}
	
	//standard form
	//fetch the data
	$hdemail_create = translate($OBJ->get('hdemail_create', 'intval'));
	$hdemail_update = translate($OBJ->get('hdemail_up', 'intval'));
	$hdemail_close  = translate($OBJ->get('hdemail_close', 'intval'));
?>
	<table align="left" width="85%" style="padding-left: 5px">
	<form method="post" action="">	<!-- Empty Action Means Send to Self -->
		<tr><td align="left">
			<h2>Step 1</h2>
		</td></tr>
		<tr>
			<td valign="top" style="border-bottom: 1px solid black">
				<h4>What Do You Want to do when a new ticket is created by a user?</h4>
				<input type="radio" name="step[1]" value="a"<?php echo ($hdemail_create == 'a') ? "checked=\"checked\"" : ''; ?>/>Do Not Send Email Notification<br/>
				<input type="radio" name="step[1]" value="b"<?php echo ($hdemail_create == 'b') ? "checked=\"checked\"" : ''; ?>/>Send Email to All Technicians<br/>
				<input type="radio" name="step[1]" value="c"<?php echo ($hdemail_create == 'c') ? "checked=\"checked\"" : ''; ?>/>Send Email to All Techs and Admins<br/>
				<input type="radio" name="step[1]" value="d"<?php echo ($hdemail_create == 'd') ? "checked=\"checked\"" : ''; ?>/>Send Email to Admins Only<br/>
			</td>
		</tr>
		<tr><td height="5"></td></tr>
		<tr><td align="left">
			<h2>Step 2</h2>
		</td></tr>
		<tr>
			<td valign="top" style="border-bottom: 1px solid black">
				<h4>What Do You Want to do if a Help Desk Ticket is Modified?</h4>
				<input type="radio" name="step[2]" value="a"<?php echo ($hdemail_update == 'a') ? "checked=\"checked\"" : ''; ?>/>Do Not Send Email Notification<br/>
				<input type="radio" name="step[2]" value="b"<?php echo ($hdemail_update == 'b') ? "checked=\"checked\"" : ''; ?>/>Send Email to Creator of Help Desk Ticket<br/>
				<input type="radio" name="step[2]" value="c"<?php echo ($hdemail_update == 'c') ? "checked=\"checked\"" : ''; ?>/>Send Email to Creator and Tech Assigned to the Ticket<br/>
				<input type="radio" name="step[2]" value="d"<?php echo ($hdemail_update == 'd') ? "checked=\"checked\"" : ''; ?>/>Send Email to Creator of Ticket and All Techs<br/>
				<input type="radio" name="step[2]" value="e"<?php echo ($hdemail_update == 'e') ? "checked=\"checked\"" : ''; ?>/>Send Email to Creator of Ticket, All Techs, and All Admins<br/>
				<input type="radio" name="step[2]" value="f"<?php echo ($hdemail_update == 'f') ? "checked=\"checked\"" : ''; ?>/>Send Email to All Techs and Admins<br/>
				<input type="radio" name="step[2]" value="g"<?php echo ($hdemail_update == 'g') ? "checked=\"checked\"" : ''; ?>/>Send Email to All Techs<br/>
				<input type="radio" name="step[2]" value="h"<?php echo ($hdemail_update == 'h') ? "checked=\"checked\"" : ''; ?>/>Send Email to All Admins<br/>
			</td>
		</tr>
		<tr><td height="5"></td></tr>
		<tr><td align="left">
			<h2>Step 3</h2>
		</td></tr>
		<tr>
			<td valign="top" style="border-bottom: 1px solid black">
				<h4>What do you want to do when a trouble ticket is closed?</h4>
				<input type="radio" name="step[3]" value="a"<?php echo ($hdemail_close == 'a') ? "checked=\"checked\"" : ''; ?>/>Do Not Send Email Notification<br/>
				<input type="radio" name="step[3]" value="b"<?php echo ($hdemail_close == 'b') ? "checked=\"checked\"" : ''; ?>/>Send Email to Creator of Help Desk Ticket<br/>
				<input type="radio" name="step[3]" value="c"<?php echo ($hdemail_close == 'c') ? "checked=\"checked\"" : ''; ?>/>Send Email to Creator and Tech Assigned to the Ticket<br/>
				<input type="radio" name="step[3]" value="d"<?php echo ($hdemail_close == 'd') ? "checked=\"checked\"" : ''; ?>/>Send Email to Creator of Ticket and All Techs<br/>
				<input type="radio" name="step[3]" value="e"<?php echo ($hdemail_close == 'e') ? "checked=\"checked\"" : ''; ?>/>Send Email to Creator of Ticket, All Techs, and All Admins<br/>
				<input type="radio" name="step[3]" value="f"<?php echo ($hdemail_close == 'f') ? "checked=\"checked\"" : ''; ?>/>Send Email to All Techs and Admins<br/>
				<input type="radio" name="step[3]" value="g"<?php echo ($hdemail_close == 'g') ? "checked=\"checked\"" : ''; ?>/>Send Email to All Techs<br/>
				<input type="radio" name="step[3]" value="h"<?php echo ($hdemail_close == 'h') ? "checked=\"checked\"" : ''; ?>/>Send Email to All Admins<br/>
			</td>
		</tr>
		<tr><td height="5"></td></tr>
		<tr><td align="center" colspan="3">
			<input type="submit" name="submit" value="Submit" />
			<span style="color:red; font-weight:bold"><?php echo isset($_POST['submit']) ? $msg : ''; ?></span>
		</td></tr>
		</form>
		<tr><td colspan="3">
			<p><a href="settings.php">Return to Settings</a><br/>
        Note: You may have to revisit the setting page and return to this page 
        to see your changes. </p>
      <p align="center"><font size="2" face="Times New Roman, Times, serif">CopyRight 
        2005 Help Desk Reloaded<br>
        <a href="http://www.helpdeskreloaded.com">Today's Help Desk Software for 
        Tomorrows Problem.</a></font></p>
      <p>&nbsp; </p></td></tr>
	</table>
	</body>
</html>
<?php
	///
	///<summary>Translates Number Entries into their related letters used in rule making</summary>
	///
	///<param name="numeral" type="int">Numeral to be Translated</param>
	///<return type="char">Char representing the selected option</return>
	function translate($numeral)
	{
		switch ($numeral)
		{
			case 0: return 'a';
			case 1: return 'b';
			case 2: return 'c';
			case 3: return 'd';
			case 4: return 'e';
			case 5: return 'f';
			case 6: return 'g';	
			case 7: return 'h';
		}	
	}
?>