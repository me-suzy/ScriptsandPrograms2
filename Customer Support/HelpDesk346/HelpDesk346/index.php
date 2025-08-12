<?php
	//Revised On May 08, 2005
	//Revised By Jason Farrell
	//Revision Number 2
	session_start();
	include_once "./config.php";
	include_once "./includes/constants.php";
	
	//log into the database - this is for looking at the settings
	mysql_connect(DB_HOST, DB_UNAME, DB_PASS) or die("Technical Problems Preventing Data Connection - Terminating");
	mysql_select_db(DB_DBNAME) or die("Invalid : " . mysql_error());
	include_once "./includes/settings.php";
	include_once "./includes/classes.php";
	
	if (isset($_POST['loginSubmit'])) {
		include_once "./includes/login.process.php";
	}
	elseif (isset($_POST['logout'])) {
		header("Location: logout.php");	
	}
?>
<html>
	<head>
		<title>Help Desk Main Page</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta name="Designed by Chad Edwards" content="QuickIntranet.com HelpDesk">
	</head>

	<body>
		<table border="0" align="left" cellpadding="0" cellspacing="0">
			<tr><td colspan="3" align="center">
				<img src="images/help-desk-main.jpg" alt="Help Desk Software Main Page" width="540" height="150" /><br/>
				<strong>Welcome to the Helpdesk</strong>
			</td></tr>
			<tr>
				<td align="center"">
					<a href="reportproblem.php" title="Create a Ticket for a Problem">
						<img src="images/request-customer-support.jpg" alt="Customer Support Center" width="174" height="136" border="0" />
					</a>
				</td>
				<td width="50"></td>
				<form method="post" action="">
				<td align="center" style="color:white; background-color: #004184; padding-left:5px">
				<?php
					if (isset($_SESSION['loggedIn'])) {
						$user = unserialize($_SESSION['enduser']);
				?>
					<h4 align="center">Logged Into the Help Desk</h4>
					<b>Username</b>:&nbsp;<?php echo $user->get('user'); ?><br/>
					<div align="center"><input type="submit" name="logout" value="Logout" />
				<?php
					}
					else {
				?>
					<h4 align="center">Login to Helpdesk</h4>
					<b>Username</b>:&nbsp;<input type="text" name="uname" size="20" maxlength="30" /><br/>
					<b>Password</b>:&nbsp;<input type="password" name="passwd" size="20" maxlength="30" /><br/>
					<span style="color:red"><?php echo isset($error_msg) ? $error_msg : ''; ?></span>
					<div align="center"><input type="submit" name="loginSubmit" value="Login" />
				<?php
					}
				?>
				</td>
				</form>
			</tr>
			<td><td height="20"></td></tr>
			<tr><td colspan="3" align="center">
				<strong>Accessible Modules</strong><br/>
				<?php
					if (isset($_SESSION['enduser'])) {
						$u = unserialize($_SESSION['enduser']);
						if ($user->get('securityLevel') > ENDUSER_SECURITY_LEVEL) {
				?>
				<a href="DataAccess.php">Helpdesk Admin Page</a>&nbsp;&nbsp;&nbsp;
				<?php	
						}	
					}
					
					if (isset($_SESSION['enduser']) || $OBJ->get('ticket_lookup')) {
				?>
					<a href="ticketLookup/">Look Up Tickets</a>&nbsp;&nbsp;&nbsp;			
				<?php
					}
					
					if ($OBJ->get('show_kb')) {
				?>
					<a href="kb/">Access Helpdesk Knowledge Base</a>&nbsp;&nbsp;&nbsp;
				<?php
					}
					
					if ($OBJ->get('allow_enduser_reg')) {
				?>
					<a href="enduser/">Register as an End User</a>&nbsp;&nbsp;&nbsp;
				<?php
					}
				?>
			</td></tr>
		</table>
	</body>
</html>
