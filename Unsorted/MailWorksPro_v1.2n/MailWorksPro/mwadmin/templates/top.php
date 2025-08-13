<?php
///////////////////////////////////////////////////////////////////////////////
//                                                                           //
//   Program Name         : MailWorks Professional                           //
//   Release Version      : 1.2                                              //
//   Program Author       : SiteCubed Pty. Ltd.                              //
//   Supplied by          : CyKuH [WTN]                                      //
//   Packaged by          : WTN Team                                         //
//   Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                           //
//                       WTN Team `2000 - `2002                              //
///////////////////////////////////////////////////////////////////////////////
	require_once("conf.php");
	require_once("includes/functions.php");
	
	// Has the config script been executed?
	if($isSetup == 0 && !is_numeric(strpos($_SERVER["SCRIPT_NAME"], "config.php")))
	{
	?>
		<html>
			<head>
				<meta http-equiv="refresh" content="0; url=config.php">
			</head>
		</html>
	<?php
		die();
	}

	if(($isSetup == 1 && !is_numeric(strpos($_SERVER["SCRIPT_NAME"], "config.php"))) && isLoggedIn() == false && !is_numeric(strpos($_SERVER["SCRIPT_NAME"], "login.php")))
	{
	?>
		<html>
			<head>
				<meta http-equiv="refresh" content="0; url=login.php">
			</head>
		</html>
	<?php
		die();
	}
			
	ob_start();
	
?>
<html>
<head>
	<title>:: MailWorksPro Administration Area ::</title>
	<style type="text/css">
	
	  a
	  {
		font-family: Verdana;
		font-size: 8pt;
		color: #183863;
	  }
	  
	  .MenuText
	  {
		font-family: Verdana;
		font-size: 8pt;
	  }
	  .MainHeading
	  {
		font-family: Arial;
		font-size: 14pt;
		font-weight: normal;
		color: #1e3a66;
	  }

	  .Info
	  {
		font-family: Verdana;
		font-size: 8pt;
	  }

	  .BodyText
	  {
		font-family: Verdana;
		font-size: 8pt;
	  }
	  
	  .MenuHeading
	  {
	    font-family: Verdana;
	    font-size: 8pt;
	    padding-left: 5pt;
	    color: #ffffff;
	    font-weight: bold;
	  }

	  .TableCell
	  {
	    font-family: Verdana;
	    font-size: 8pt;
	    padding-left: 5pt;
	    color: #000000;
	  }
	
	</style>
	
	<script language="JavaScript">
	
	  function handleError()
	  {
	    return true;
	  }
	
	  function ConfirmCancel(CancelURL)
	  {
	    if(confirm('WARNING: Are you sure you want to cancel what you are doing? Click OK to proceed.'))
		  document.location.href = CancelURL;
	  }
	  
	  function ConfirmStats()
	  {
		if(confirm('WARNING: These stats are generated in real-time from the database, and as such may take anywhere from 5 seconds to 1 minute to generate, depending on the number of subscribers currently in your database.\r\n\r\nClick the OK button to continue.'))
			document.location.href = 'stats.php';
	  }
	  
	  window.onerror = handleError;
	
	</script>
	
</head>
<body bgcolor="#eaeaea" onBeforeUnload="doValueUpdate1()">
  <table width="770" align="center" border="0" cellspacing="0" cellpadding="0">
    <tr>
	  <td width="199" valign="top">
	  <img src="images/menuheader.gif"><table width="100%" background="images/menusides.gif" bgcolor="#ffffff" align="center" border="0"><tr><td bgcolor="#ffffff">
         <p style="margin-left:5">
		 <span class="MenuText">
		 <br>
		 <img src="images/arrow.gif"> <b>Main Menu</b><br>
		  <?php if(@$_COOKIE["auth"] != true) { ?>
		    &nbsp;&nbsp;&nbsp; <a href="login.php">Login</a><br>
		  <?php } else { ?>
		    &nbsp;&nbsp;&nbsp; <a href="login.php?what=logout">Logout</a><br>
		  <?php } ?>
		  &nbsp;&nbsp;&nbsp; <a href="config.php">Update Configuration</a><br>
		  &nbsp;&nbsp;&nbsp; <a href="config.php?what=builddb">Build Database</a><br>
		  <br>
		  <img src="images/arrow.gif"> <a href="template.php"><b>Templates</b></a><br>
		  &nbsp;&nbsp;&nbsp; <a href="template.php?what=new">Create New</a>
		  <br><br>
		  <img src="images/arrow.gif"> <a href="newsletter.php"><b>Newsletters</b></a><br>
		  &nbsp;&nbsp;&nbsp; <a href="newsletter.php?what=new">Create New</a><br>
		  &nbsp;&nbsp;&nbsp; <a href="sendnewsletter.php">Send Newsletter</a>
		  <br><br>
		  <img src="images/arrow.gif"> <a href="topic.php"><b>Topics</b></a><br>
		  &nbsp;&nbsp;&nbsp; <a href="topic.php?what=new">Create New</a>
		  <br><br>
		  <img src="images/arrow.gif"> <a href="subscriber.php"><b>Subscribers</b></a><br>
		  &nbsp;&nbsp;&nbsp; <a href="subscriber.php?what=import">Import</a><br>
		  &nbsp;&nbsp;&nbsp; <a href="subscriber.php?what=export">Export</a>
		  <br><br>
		  <img src="images/arrow.gif"> <a href="javascript:ConfirmStats()"><b>Stats</b></a><br>
		</td></tr></table><img src="images/menubottom.gif"></td>
	  <td width="10">
	    &nbsp;
	  </td>
	  <td width="561" valign="top" bgcolor="#ffffff" height="500">
