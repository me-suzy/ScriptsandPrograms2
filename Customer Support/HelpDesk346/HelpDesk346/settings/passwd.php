<?php
	$path = getcwd();
	chdir('..');
	include_once "checksession.php";
	include_once "./includes/classes.php";
	include_once "./includes/settings.php";
	chdir($path);
?>
<html>
	<head>
		<title>Password Change Module</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta name="Designed by Chad Edwards" content="QuickIntranet.com">
		<link href="style.css" rel="stylesheet" type="text/css">
	</head>
	<body bgcolor="#FFFFFF" text="#000000"  link="#0000FF" alink="#FF0000" vlink="#0000FF">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr><td colspan="2">
				<table width="99%" border="0">
				  <tr> 
				    <td height="127" align="center" valign="top" bgcolor="#FFFFFF"> 
				      <div align="center"> <a href="../index.php"></a></div>
				      <table width="99%" border="0" cellpadding="0">
				        <tr> 
				          <td bordercolor="#FFFFFF" bgcolor="#FFFFFF" height="2" valign="top"> 
				          	<?php
				          		$ppath = '../';
				          		if ($OBJ->get('navigation') == 'B') {
				          			include_once "../dataaccessheader.php";
				          		}
				          		else {
				          			include_once "../textnavsystem.php";
				          		}
				          	?><br/>  
				          	<a href="actmgt.php">Back to help desk control panel.</a>
				         </td>
				        </tr>
				       </table>
					</td></tr>
				</table>
			</td></tr>
			<?php
				$error = false;
				if (isset($_POST['submit'])) {
					//validate POSTDATA	
					#include_once "../includes/userManage/passwd_validate.php";
					$user = unserialize($_SESSION['enduser']);
					if ($_POST['newPass1'] != $_POST['newPass2']) $error = true;
					if (!$error && $user->psswd($_POST['oldPass'], $_POST['newPass1'])) {
						$page_error = '<span style="color:blue">Password Changed Successfully</span>';	
					}
					else {
						$page_error = "Password Criteria Not Met - Password Not Change";	
					}
				}
			?>
			<tr><th colspan="2" algin="left">
				Enter the Following Password Information
			</th></tr>
			<form method="post" action="">
			<tr>
				<td>Enter Current Password:&nbsp;</td>
				<td><input type="password" name="oldPass" size="20" maxlength="30" /></td>
			</tr>
			<tr>
				<td>Enter New Password:&nbsp;</td>
				<td><input type="password" name="newPass1" size="20" maxlength="30" /></td>
			</tr>
			<tr>
				<td>Confirm New Password:&nbsp;</td>
				<td><input type="password" name="newPass2" size="20" maxlength="30" /></td>
			</tr>
			<tr><td colspan="2" style="color:red" align="center">
			<?php echo isset($page_error) ? $page_error : ''; ?>
			</td></tr>
			<tr><td colspan="2" align="center">
				<input type="submit" name="submit" value="Change Password" class="button" />
			</td></tr>
			</form>
		</table>
	</body>
</html>