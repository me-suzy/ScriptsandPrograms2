<?php
	//Created on May 08, 2005
	//Created by Jason Farrell
	//Version 1.0
	
	session_start();
	$path = getcwd();
	chdir('..');
	include_once "./config.php";
	include_once "./includes/constants.php";
	
	//log into the database - this is for looking at the settings
	mysql_connect(DB_HOST, DB_UNAME, DB_PASS) or die("Technical Problems Preventing Data Connection - Terminating");
	mysql_select_db(DB_DBNAME) or die("Invalid : " . mysql_error());
	include_once "./includes/settings.php";
	include_once "./includes/classes.php";
	
	chdir($path);
	
	//validation for login attempt
	if (isset($_POST['command']) && $_POST['command'] == 'Login') {
	#	die("Point Reached");
		$q = "select id from " . DB_PREFIX . "accounts where user = '" . mysql_real_escape_string($_POST['uname']) . "' and pass = '" . mysql_real_escape_string($_POST['upass']) . "' LIMIT 1";
		$s = mysql_query($q) or die(mysql_error());
		if (mysql_num_rows($s)) {
			$r = mysql_fetch_assoc($s);
			$_SESSION['loggedIn'] = true;
			$_SESSION['enduser'] = serialize(new User($r['id']));
		}
		else {
			$error_msg = "Login Invalid";
		}
	}
?>
<html>
<head>
<!-- #BeginEditable "doctitle" --> 
<title>Help Desk :: TicketLookup</title>
 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="Designed by Chad Edwards" content="QuickIntranet.com HelpDesk">
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
</head>

<body bgcolor="#FFFFFF" text="#000000" " link="#0000FF" alink="#FF0000" vlink="#0000FF">
<table width="75%" height="392" border="0" align="left" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="7%" rowspan="4" valign="top" bgcolor="#000000"> 
      <div align="center"></div>
      <blockquote> 
        <p>&nbsp;</p>
      </blockquote>
      <div align="center"></div>
    </td>
    <td height="111" colspan="3" valign="top"> <img src="../images/help-desk-default-page-logo.jpg" width="905" height="106"><br>
      <br>
      Click Here to Return to the <a href="../">Main Help Desk Page</a></td>
  </tr>
  <tr> 
    <td height="10" colspan="2" valign="top">
    </td>
  </tr>
  <tr><td colspan="2" valign="top">
  <?php
  		//perform a check to make sure ticket viewing is allowed
  		if ($OBJ->get('ticket_lookup') || isset($_SESSION['enduser'])) {
			include_once "./ticketLookup_display.php";
		}
		elseif (!$OBJ->get('ticketAccessModify')) {
			include_once "./ticketLookup_login.php";
		}
  ?>
  </td></tr>
  <tr>
    <td width="18%" valign="top" colspan="2">
      <p align="center"><img src="http://www.helpdeskreloaded.com/reload/help-desk-copyright.jpg" alt="http://www.helpdeskreloaded.com Help Desk Software By  HelpDeskReloaded &quot;Help Desk Reloaded&quot;"></p>
    </td>
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
