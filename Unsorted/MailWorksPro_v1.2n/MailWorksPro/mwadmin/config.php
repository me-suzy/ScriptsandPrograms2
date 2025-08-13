<?php ob_start(); ?>
<?php include("templates/top.php"); ?>
<?php include_once("includes/functions.php"); ?>
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
  if(!isLoggedIn() && $isSetup == 1)
  {
    header("location: login.php");
    die();
  }

  $what = @$_POST["what"];
  
  if($what == "")
  $what = @$_GET["what"];
  
  switch($what)
  {
    case "save":
      SaveDetails();
	  break;
	case "builddb":
	  BuildDatabase();
	  break;
	default:
	  ShowConfigForm();
  }
  
  function ShowConfigForm()
  {
	// Show the configuration details from config.php
	global $siteName;
	global $siteURL;
	global $dbServer;
	global $dbUser;
	global $dbPass;
	global $dbName;
	global $adminUser;
	global $adminPass;
	global $useTemplates;
	global $topTemplate;
	global $bottomTemplate;
	global $privacyPolicyStmt;
	
	if(@$_SERVER["SERVER_NAME"] == "")
		$server = "http://www.yoursite.com";
	else
		$server = "http://" . $_SERVER["SERVER_NAME"];
  ?>
	    <script language="JavaScript">

			function toggleP()
			{
				if(document.all.pMenu.style.display == 'inline')
				{
					document.all.pMenu.style.display = 'none';
					document.all.pText.innerHTML = 'Subscription Page Templates »';
				}
				else
				{
					document.all.pMenu.style.display = 'inline';
					document.all.pText.innerHTML = '« Subscription Page Tempaltes';
				}
			}
			
			function EnableTemplates()
			{
				document.frmConfig.topTemplate.disabled = false;
				document.frmConfig.bottomTemplate.disabled = false;
			}
			
			function DisableTemplates()
			{
				document.frmConfig.topTemplate.disabled = true;
				document.frmConfig.bottomTemplate.disabled = true;
			}

			function switchToWYSIWYG()
			{
				if(viewMode1 == 2)
				{
					alert('You must change back to WYSIWYG editing mode first.');
					return false;
				}
						
				return true;
			}

	    </script>
	    
	    <table width="98%" align="center" border="0">
		  <tr><td height="30">
		    <span class="MainHeading">Update Configuration</span>
		  </td></tr>
		  <tr><td background="images/yellowbg.gif">
		    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
			<span class="Info">
              <br>Please complete the form below to update your configuration file. You can change database details, your admin username and password, etc.
              Click on the "Save Changes" button when you're done. <a href="config.php?what=builddb">Click here to build your MySQL database</a>.
              <br><br>
				<a style="cursor:hand" onClick="toggleP()"><u><span id="pText">Subscription Page Templates »</span></u></a><br><br>
				<table style="display:none" width="95%" align="center" id="pMenu"><tr><td>
					<span class="Info">
						When a user visits <?php echo $server; ?>/mwsubscribe they can subscribe to your newsletter through the
						MailWorksPro subscription manager page. By default this page has a plain white background, however if
						you have site templates and want to make this page look like the rest of your site then enter the paths to
						your top and bottom templates in the "Subscription Page Templates" fields.
						<br><br>
						You must specify the full URL to your site templates like this:
						<ul>
							<li>Top Template: http://www.mysite.com/templates/mytop.php</li>
							<li>Bottom Template: http://www.mysite.com/templates/mybottom.php</li>
						</ul>
					</span>
				</td></tr></table>
				</span>
		  </td></tr>
		  <tr><td background="images/yellowbg1.gif">		  
		  </td></tr>
		  <tr><td>
		    <form onSubmit="return switchToWYSIWYG()" name="frmConfig" action="config.php" method="post">
			  <input type="hidden" name="what" value="save">
			  <table width="95%" align="center" border="0">
			    <tr>
				  <td valign="top">
				    <span class="BodyText">
				      <br><b><img src="images/arrow.gif"> Site Details:</b>
					  <br><br>
				      &nbsp;&nbsp;&nbsp;Site Name: (ex: My Web Site)<br>
					  &nbsp;&nbsp;&nbsp;<input type="text" name="siteName" size="40" value="<?php echo $siteName; ?>">
					  <br><br>
				      &nbsp;&nbsp;&nbsp;Site URL: (ex: http://www.mysite.com -- do not include a trailing forward slash)<br>
					  &nbsp;&nbsp;&nbsp;<input type="text" name="siteURL" size="40" value="<?php echo $siteURL; ?>">
				      <br><br>
				      <b><img src="images/arrow.gif"> MySQL Database Details:</b>
					  <br><br>
				      &nbsp;&nbsp;&nbsp;Database Server: (ex: localhost)<br>
					  &nbsp;&nbsp;&nbsp;<input type="text" name="dbServer" size="40" value="<?php echo $dbServer; ?>">
					  <br><br>
				      &nbsp;&nbsp;&nbsp;Database User: (ex: myuser)<br>
					  &nbsp;&nbsp;&nbsp;<input type="text" name="dbUser" size="40" value="<?php echo $dbUser; ?>">
					  <br><br>
				      &nbsp;&nbsp;&nbsp;Database Password: (ex: mypassword)<br>
					  &nbsp;&nbsp;&nbsp;<input type="password" name="dbPass" size="40" value="<?php echo $dbPass; ?>">
					  <br><br>
				      &nbsp;&nbsp;&nbsp;Database Name: (ex: mailworksdb)<br>
					  &nbsp;&nbsp;&nbsp;<input type="text" name="dbName" size="40" value="<?php echo $dbName; ?>">
					  <br><br>
					  <b><img src="images/arrow.gif"> MailWorksPro Login Details:</b>
					  <br><br>
				      &nbsp;&nbsp;&nbsp;Admin Username:<br>
					  &nbsp;&nbsp;&nbsp;<input type="text" name="adminUser" size="40" value="<?php echo $adminUser; ?>">
					  <br><br>
				      &nbsp;&nbsp;&nbsp;Admin Password:<br>
					  &nbsp;&nbsp;&nbsp;<input type="password" name="adminPass" size="40" value="<?php echo $adminPass; ?>">
					  <br><br>
					  <b><img src="images/arrow.gif"> Subscription Page Templates:</b>
					  <br><br>
				      &nbsp;&nbsp;&nbsp;Would You Like To Specify Templates For Your MailWorksPro Subscription Page?<br>
					  &nbsp;&nbsp;&nbsp;<input onClick="EnableTemplates()" type="radio" name="useTemplates" value="1" <?php if($useTemplates == true) { echo " CHECKED "; } ?>> Yes
					  <input onClick="DisableTemplates()" type="radio" name="useTemplates" value="0" <?php if($useTemplates == false) { echo " CHECKED "; } ?>> No
					  <br><br>
				      &nbsp;&nbsp;&nbsp;Top Template: (ex: http://www.mysite.com/templates/mytop.php)<br>
					  &nbsp;&nbsp;&nbsp;<input type="text" name="topTemplate" size="40" value="<?php echo $topTemplate; ?>" <?php if($useTemplates == false) { echo " DISABLED "; } ?>>
					  <br><br>
				      &nbsp;&nbsp;&nbsp;Bottom Template: (ex: http://www.mysite.com/templates/mybottom.php)<br>
					  &nbsp;&nbsp;&nbsp;<input type="text" name="bottomTemplate" size="40" value="<?php echo $bottomTemplate; ?>" <?php if($useTemplates == false) { echo " DISABLED "; } ?>>
					  <br><br>
					  <b><img src="images/arrow.gif"> Privacy Policy: </b>[ <a href="http://www.the-dma.org/library/privacy/creating.shtml" target="_blank">Create One Online</a> ]
					  <br>
					  <p style="margin-left:15">
					  <?php
					  
						// Show an EWP control
						require_once("class.ewp.php");
						$myEWP = new EWP;
						$myEWP->HideTableButton();
						$myEWP->SetValue($privacyPolicyStmt);
						$myEWP->ShowControl(510, 200, "ewp_images");
					  ?>
					  <br>
					  <input type="button" value="« Cancel" onClick="ConfirmCancel('config.php')">
					  <input type="submit" name="submit" value="Save Changes »">
					  <br><br>
				</td>
			  </tr>
			</table>
  <?php
  }
  
  function SaveDetails()
  {
	// Get the config details from the form and save them to conf.php
	require_once("class.ewp.php");
	
	global $isSetup;
	
	$siteName = @$_POST["siteName"];
	$siteURL = @$_POST["siteURL"];
	$dbServer = @$_POST["dbServer"];
	$dbUser = @$_POST["dbUser"];
	$dbPass = @$_POST["dbPass"];
	$dbName = @$_POST["dbName"];
	$adminUser = @$_POST["adminUser"];
	$adminPass = @$_POST["adminPass"];
	$useTemplates = @$_POST["useTemplates"] == "0" ? 0 : 1;
	$topTemplate = @$_POST["topTemplate"];
	$bottomTemplate = @$_POST["bottomTemplate"];
	
	$myEWP = new EWP;
	$privacyPolicyStmt =$myEWP->GetValue();
	
	$err = "";
	
	// Start the error checking
	if($siteName == "")
		$err .= "<li>You forgot to enter your web sites name</li>";
	
	if(!ereg("^http://", $siteURL))
		$err .= "<li>You forgot to enter your web sites URL</li>";
	else
		$siteURL = ereg_replace("/$", "", $siteURL);
	
	if($dbServer == "")
		$err .= "<li>You forgot to enter the IP/host name of your MySQL server</li>";
		
	if($dbUser == "")
		$err .= "<li>You forgot to enter a valid username for your MySQL database</li>";
	
	if($dbName == "")
		$err .= "<li>You forgot to enter a valid MySQL database name</li>";

	if($adminUser == "")
		$err .= "<li>You forgot to enter a valid MailWorksPro username</li>";

	if($adminUser == "")
		$err .= "<li>You forgot to enter a valid MailWorksPro password</li>";

	if($useTemplates == true)
	{
		if($topTemplate == "")
			$err .= "<li>You forgot to enter a path to your top template file</li>";
		
		if($bottomTemplate == "")
			$err .= "<li>You forgot to enter a path to your bottom template file</li>";
	}
	
	if($privacyPolicyStmt == "")
		$err .= "<li>You forgot to enter a privacy statement</li>";
	
	if($err != "")
	{
		?>
		<table width="98%" align="center" border="0">
		  <tr><td height="30">
		    <span class="MainHeading">Update Configuration</span>
		  </td></tr>
		  <tr><td background="images/yellowbg.gif">
		    <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
			<span class="Info">
			  <br>The form that you've just submitted is incomplete. Please review the errors below and then go back and
			  correct them:
			  <ul><?php echo $err; ?></ul>
			  <p style="margin-left:5pt">
			  <a href="javascript:history.go(-1)"><< Go Back</a><br>&nbsp;
  			</span>
		    </td></tr>
		    <tr><td background="images/yellowbg1.gif">
		    </td></tr>
		    <tr><td>
  		    </td></tr>
		  </table>
		<?php
		die();
	}
	else
	{
		// Update the configuration file
		$configFile = "<?php

// MailWorksPro Configuration File
\$isSetup = 1;

\$siteName = \"$siteName\";
\$siteURL = \"$siteURL\";

\$dbServer = \"$dbServer\";
\$dbUser = \"$dbUser\";
\$dbPass = \"$dbPass\";
\$dbName = \"$dbName\";

\$useTemplates = $useTemplates;
\$topTemplate = \"$topTemplate\";
\$bottomTemplate = \"$bottomTemplate\";

\$privacyPolicyStmt = '$privacyPolicyStmt';

\$adminUser = \"$adminUser\";
\$adminPass = \"$adminPass\";

?>
";

		// Open the configuration file and save it
		if($fp = @fopen("conf.php", "w"))
		{
			if(@fputs($fp, $configFile))
			{
				// Config file updated OK
				?>
					<table width="98%" align="center" border="0">
						<tr><td height="30">
						  <span class="MainHeading">Update Configuration</span>
						</td></tr>
						<tr><td background="images/yellowbg.gif">
						  <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
							<span class="Info">
							<?php if($isSetup == true) { ?>
							  <br>Your MailWorksPro configuration file has been updated successfully.
							  <br><br>
							  <a href="config.php">Continue >></a><br>&nbsp;
							<?php } else { ?>
							  <br>Your MailWorksPro configuration file has been updated successfully. Because this
							  is your first time running MailWorksPro, you will now need to login and then build your database.
							  Please click on the link below to login and then click on the "Build Database" link from the menu
							  on the left.
							  <br><br>
							  <a href="login.php">Login >></a><br>&nbsp;
							<?php } ?>
  							</span>
						  </td></tr>
						  <tr><td background="images/yellowbg1.gif">
						  </td></tr>
						  <tr><td>
  						  </td></tr>
					</table>
				<?php
			}
			else
			{
				// Couldnt write to the conf.php file
				?>
					<table width="98%" align="center" border="0">
						<tr><td height="30">
						  <span class="MainHeading">Update Configuration</span>
						</td></tr>
						<tr><td background="images/yellowbg.gif">
						  <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
							<span class="Info">
							  <br>An error occured while trying to write to admin/conf.php. Make sure this file is
							  has write permissions (CHMOD 755) and try again.
							  <br><br>
							  <a href="javascript:document.location.reload()">Try Again</a><br>&nbsp;
  							</span>
						  </td></tr>
						  <tr><td background="images/yellowbg1.gif">
						  </td></tr>
						  <tr><td>
  						  </td></tr>
					</table>
				<?php
			}
			@fclose($fp);
		}
		else
		{
			// Coudlnt open the conf.php file
			?>
				<table width="98%" align="center" border="0">
					<tr><td height="30">
					  <span class="MainHeading">Update Configuration</span>
					</td></tr>
					<tr><td background="images/yellowbg.gif">
					  <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
						<span class="Info">
						  <br>An error occured while trying to open admin/conf.php for writing. Please make sure that
						  the file exists and that it has write permissions (CHMOD 755).
						  <br><br>
						  <a href="javascript:document.location.reload()">Try Again</a><br>&nbsp;
  						</span>
					  </td></tr>
					  <tr><td background="images/yellowbg1.gif">
					  </td></tr>
					  <tr><td>
  					  </td></tr>
				</table>
			<?php
		}
	}
  }
  
  function BuildDatabase()
  {
	// Connect to the database and build it
	doDbConnect();
	
	@mysql_query("drop table if exists topics;");
	@mysql_query("drop table if exists newsletters;");
	@mysql_query("drop table if exists templates;");
	@mysql_query("drop table if exists subscribedUsers;");
	@mysql_query("drop table if exists subscriptions;");
	
	@mysql_query("create table topics
	(
	  pk_tId int auto_increment not null,
	  tName varchar(50),
	  primary key(pk_tId),
	  unique id(pk_tId)
	);");
	
	@mysql_query("create table newsletters
	(
	  pk_nId int auto_increment not null,
	  nName varchar(50),
	  nTitle varchar(100),
	  nContent text,
	  nTemplateId int,
	  nStatus enum('pending', 'sent'),
	  primary key(pk_nId),
	  unique id(pk_nId)
	);");
	
	@mysql_query("create table templates
	(
	  pk_nId int auto_increment not null,
	  nName varchar(50),
	  nDesc text,
	  nTopicId int,
	  nFromEmail varchar(250),
	  nReplyToEmail varchar(250),
	  nFrequency1 int,
	  nFrequency2 int,
	  nFormat enum('text', 'html'),
	  primary key(pk_nId),
	  unique id(pk_nId)
	);");
	
	@mysql_query("create table subscribedUsers
	(
	  pk_suId int auto_increment not null,
	  suFName varchar(30),
	  suLName varchar(30),
	  suEmail varchar(250),
	  suPassword varchar(70),
	  suStatus enum('pending', 'subscribed'),
	  suDateSubscribed timestamp,
	  primary key(pk_suId),
	  unique id(pk_suId),
	  fulltext(suFName, suLName, suEmail)
	);");
	
	@mysql_query("create table subscriptions
	(
	  pk_sId int auto_increment not null,
	  sNewsletterId int,
	  sSubscriberId int,
	  primary key(pk_sId),
	  unique id(pk_sId)
	);");

	if(mysql_errno() == 0)
	{
		// Tables were created successfully
		?>
			<table width="98%" align="center" border="0">
				<tr><td height="30">
				  <span class="MainHeading">Build Database</span>
				</td></tr>
				<tr><td background="images/yellowbg.gif">
				  <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
					<span class="Info">
					  <br>Your MailWorksPro database has been setup successfully. You may now create and send newsletters,
					  manage, import and export subscribers, etc.
					  <br><br>
					  <a href="index.php">Continue</a><br>&nbsp;
  					</span>
				  </td></tr>
				  <tr><td background="images/yellowbg1.gif">
				  </td></tr>
				  <tr><td>
  				  </td></tr>
			  </table>
		<?php
	}
	else
	{
		// Table creation failed
		?>
			<table width="98%" align="center" border="0">
				<tr><td height="30">
				  <span class="MainHeading">Build Database</span>
				</td></tr>
				<tr><td background="images/yellowbg.gif">
				  <p style="margin-top:5; margin-bottom:3; margin-left: 5; margin-right:5">
					<span class="Info">
					  <br>A MySQL error occured while trying to build your MailWorksPro database: ERROR #<?php echo mysql_errno(); ?>: "<?php echo mysql_error(); ?>".
					  Please click on the link below to try again.
					  <br><br>
					  <a href="javascript:document.location.reload()">Try Again</a><br>&nbsp;
  					</span>
				  </td></tr>
				  <tr><td background="images/yellowbg1.gif">
				  </td></tr>
				  <tr><td>
  				  </td></tr>
			  </table>
		<?php
	}
  }
  
?>
