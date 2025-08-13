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
	error_reporting(0);

	require_once("../mwadmin/conf.php");
	require_once("../mwadmin/includes/functions.php");
	
	// Which page are we displaying?
	$what = @$_GET["what"];
	
	switch($what)
	{
		case "subscribe":
		{
			TopTemplate();
			ShowNewUserScreen();
			BottomTemplate();
			break;
		}
		case "unsubscribe":
		{
			TopTemplate();
			ShowUnsubscribeScreen();
			BottomTemplate();
			break;
		}
		case "doUnsubscribe":
		{
			ProcessUnsubscribe();
			break;
		}
		case "processNew":
		{
			TopTemplate();
			ProcessSubscription();
			BottomTemplate();
			break;
		}
		case "privacy":
		{
			TopTemplate();
			ShowPrivacyPolicy();
			BottomTemplate();
			break;
		}
		case "confirm":
		{
			TopTemplate();
			ConfirmSubscription();
			BottomTemplate();
			break;
		}
		case "login":
		{
			TopTemplate();
			ShowLoginScreen();
			BottomTemplate();
			break;
		}
		case "processLogin":
		{
			ProcessLogin();
			break;
		}
		case "update":
		{
			TopTemplate();
			ShowUpdateForm();
			BottomTemplate();
			break;
		}
		case "updateDetails":
		{
			ProcessUpdate();
			break;
		}
		case "getPass":
		{
			TopTemplate();
			ShowGetPasswordScreen();
			BottomTemplate();
			break;
		}
		case "sendPass":
		{
			TopTemplate();
			SendPassword();
			BottomTemplate();
			break;
		}
		case "logout":
		{
			setcookie("mwAuth", true, time() - 10000);
			setcookie("mwEmail", true, time() - 10000);
			TopTemplate();
			ShowLogoutScreen();
			BottomTemplate();
			break;
		}
		default:
		{
			TopTemplate();
			ShowMainScreen();
			BottomTemplate();
		}
	}
	
	function ShowLogoutScreen()
	{
		// Let the user unsubscribe from his account
		global $siteName;
		global $siteURL;
		global $useTemplates;
		
		$domain = "/mwsubscribe/index.php";
		$auth = @$_COOKIE["mwAuth"] == "" ? false : true;

		if(!$useTemplates)
			echo "<table width='770' align='center' border='0' cellspacing='0' cellpadding='0'>";
		else
			echo "<table width='95%' align='center' border='0' cellspacing='0' cellpadding='0'>";
			
		?>
			<tr>
				<td>
					<font face="Verdana" size="5" color="#00000"><b>Logout Successful</b></font>
					<font face="Verdana" size="2" color="#000000">
						<br><br>
						You have successfully logged out of our newsletter subscription system.
						<br><br>
						<a href="<?php echo $domain; ?>">Continue</a>
						<br>&nbsp;
					</font>
				</td>
			</tr>
		<?php
		
		echo "</table>";
	}
	
	function ProcessUnsubscribe()
	{
		// Check if the user exists in the database and remove him
		global $siteName;
		global $siteURL;
		global $useTemplates;
		
		$domain = "/mwsubscribe/index.php";
		$email = @$_POST["email"];
		$password = @$_POST["password"];
		
		doDbConnect();
		
		$result = mysql_query("select * from subscribedUsers where suEmail='$email' and suPassword=password('$password')");
		
		if($row = mysql_fetch_array($result))
		{
			// User exists in the database, remove him and his subscription details
			$suId = $row["pk_suId"];
			$query1 = "delete from subscribedUsers where pk_suId = $suId";
			$query2 = "delete from subscriptions where sSubscriberId = $suId";
			
			if(@mysql_query($query1) && @mysql_query($query2))
			{
				// User was removed successfully
				setcookie("mwAuth", true, time() - 10000);
				setcookie("mwEmail", true, time() - 10000);

				TopTemplate();

				if(!$useTemplates)
					echo "<table width='770' align='center' border='0' cellspacing='0' cellpadding='0'>";
				else
					echo "<table width='95%' align='center' border='0' cellspacing='0' cellpadding='0'>";

				?>
					<tr>
						<td>
							<font face="Verdana" size="5" color="#00000"><b>Subscription Cancelled</b></font>
							<font face="Verdana" size="2" color="#000000">
								<br><br>
								You have been removed from our subscription database successfully.
								<br><br>
								<a href="<?php echo $domain; ?>">Continue</a>
								<br>&nbsp;
							</font>
						</td>
					</tr>
				<?php
				
				BottomTemplate();
			}
			else
			{
				// An error occured while removing this user
				TopTemplate();

				if(!$useTemplates)
					echo "<table width='770' align='center' border='0' cellspacing='0' cellpadding='0'>";
				else
					echo "<table width='95%' align='center' border='0' cellspacing='0' cellpadding='0'>";

				?>
					<tr>
						<td>
							<font face="Verdana" size="5" color="#00000"><b>An Error Occured</b></font>
							<font face="Verdana" size="2" color="#000000">
								<br><br>
								A database error occured while trying to remove you from our subscription database.
								Please click on the link below to try again.
								<br><br>
								<a href="javascript:document.location.reload()">Continue</a>
								<br>&nbsp;
							</font>
						</td>
					</tr>
				<?php
				
				BottomTemplate();
			}
		}
		else
		{
			// User doesn't exist in the database
			TopTemplate();

			if(!$useTemplates)
				echo "<table width='770' align='center' border='0' cellspacing='0' cellpadding='0'>";
			else
				echo "<table width='95%' align='center' border='0' cellspacing='0' cellpadding='0'>";

			?>
				<tr>
					<td>
						<font face="Verdana" size="5" color="#00000"><b>User Not Found</b></font>
						<font face="Verdana" size="2" color="#000000">
							<br><br>
							A user with this email address and password combination does not exist in our subscription database.
							Please click on the link below to go back and try again.
							<br><br>
							<a href="javascript:history.go(-1)"><< Go Back</a>
							<br>&nbsp;
						</font>
					</td>
				</tr>
			<?php
				
			BottomTemplate();
		}
		
		echo "</table>";
	}
	
	function ShowUnsubscribeScreen()
	{
		// Let the user unsubscribe from his account
		global $siteName;
		global $siteURL;
		global $useTemplates;
		
		$domain = "/mwsubscribe/index.php";
		$auth = @$_COOKIE["mwAuth"] == "" ? false : true;

		if(!$useTemplates)
			echo "<table width='770' align='center' border='0' cellspacing='0' cellpadding='0'>";
		else
			echo "<table width='95%' align='center' border='0' cellspacing='0' cellpadding='0'>";
			
		?>
			<script language="JavaScript">

				function ConfirmCancel(CancelURL)
				{
				  if(confirm('WARNING: Are you sure you want to cancel what you are doing? Click OK to proceed.'))
					  document.location.href = CancelURL;
				}
					
			</script>

			<form action="<?php echo $domain; ?>?what=doUnsubscribe" method="post">
			<tr>
				<td>
					<font face="Verdana" size="5" color="#00000"><b>Unsubscribe</b></font>
					<font face="Verdana" size="2" color="#000000">
						<br><br>
						Please enter your email address and password in the fields below and then click on the "unsubscribe" button.
						You will be removed from the <?php echo $siteName; ?> newsletter subscription database.
						<a href="<?php echo $domain; ?>?what=getPass">Click here</a> if you have forgotten your password and would like it emailed to you.
						<br><br>
						<table width="100%" border="0" cellspacing="0" cellpadding="1">
							<tr>
								<td width="25%">
									<font face="Verdana" size="2" color="#000000">Email Address:</font>
								</td>
								<td width="75%">
									<input type="text" name="email" size="30">
								</td>
							</tr>
							<tr>
								<td width="25%">
									<font face="Verdana" size="2" color="#000000">Password:</font>
								</td>
								<td width="75%">
									<input type="password" name="password" size="30">
								</td>
							</tr>
							<tr>
								<td width="25%">
									&nbsp;
								</td>
								<td width="75%">
									<input type="button" value="« Cancel" onClick="ConfirmCancel('<?php echo $domain; ?>')">
									<input type="submit" value="Unsubscribe »">
								</td>
							</tr>
						</table>
						<br>&nbsp;
					</font>
				</td>
			</tr>
			</form>
		<?php
		
		echo "</table>";
	}
	
	function ShowMainScreen()
	{
		// This function will display a simple menu that lets the user choose what to do
		global $siteName;
		global $siteURL;
		global $useTemplates;
		
		$domain = "/mwsubscribe/index.php";
		$auth = @$_COOKIE["mwAuth"] == "" ? false : true;

		if(!$useTemplates)
			echo "<table width='770' align='center' border='0' cellspacing='0' cellpadding='0'>";
		else
			echo "<table width='95%' align='center' border='0' cellspacing='0' cellpadding='0'>";
		
		?>
			<tr>
				<td>
					<font face="Verdana" size="5" color="#00000"><b><?php echo $siteName; ?> Newsletter Subscriptions</b></font>
					<font face="Verdana" size="2" color="#000000">
						<br>
						Hi, welcome to the <?php echo $siteName; ?> newsletter subscription page. Please choose an option:
						<br><br>
						<ul>
							<li><a href="<?php echo $domain; ?>?what=privacy">Our Privacy Policy</a></li>
							<li><a href="<?php echo $domain; ?>?what=subscribe">Subscribe</a></li>
							<li><a href="<?php echo $domain; ?>?what=unsubscribe">Unsubscribe</a></li>
							<li><a href="<?php echo $domain; ?>?what=update">Update Preferences</a></li>
							<li><a href="<?php echo $domain; ?>?what=getPass">Retrieve Password</a></li>
							<?php if($auth == true) { ?>
								<li><a href="<?php echo $domain; ?>?what=logout">Logout</a></li>
							<?php } ?>
						</ul>
						<br>&nbsp;
					</font>
				</td>
			</tr>
		<?php
		
		echo "</table>";
	}
	
	function SendPassword()
	{
		// This function will email the user a new password and save it to the database
		global $siteName;
		global $siteURL;
		global $useTemplates;

		$domain = "/mwsubscribe/index.php";
		$email = @$_POST["email"];
		
		doDbConnect();

		if(!$useTemplates)
			echo "<table width='770' align='center' border='0' cellspacing='0' cellpadding='0'>";
		else
			echo "<table width='95%' align='center' border='0' cellspacing='0' cellpadding='0'>";
		
		// Does a user with this email address exist in the database?
		$result = mysql_query("select * from subscribedUsers where suEmail = '$email'");
		
		if($row = mysql_fetch_array($result))
		{
			// User exists in the database, set him a new password
			$randPass = GenerateRandomPassword();
			
			if(@mysql_query("update subscribedUsers set suPassword = password('" . $randPass . "') where suEmail = '$email'"))
			{
				// Users password was updated OK, email the user
				$mailMsg = "Hi,\r\nYour password to manage your newsletter subscriptions at $siteName has been changed and is shown below:\r\n\r\nEmail: $email\r\nNew Password: $randPass\r\n\r\nPlease visit $siteURL/mwsubscribe/index.php?what=login to login to your account and update your subscription preferences.\r\n\r\nThanks,\r\nThe $siteName Team\r\n$siteURL";
				
				if(@mail($email, "Password For $siteName Newsletter Subscriptions", $mailMsg))
				{
					// The email was sent OK
					?>
						<tr>
							<td>
								<font face="Verdana" size="5" color="#00000"><b>Password Sent</b></font>
								<font face="Verdana" size="2" color="#000000">
									<br><br>
									A new password has been sent to your email address. Please check your email for this new password and
									your link to login and update your subscription preferences.
									<br><br>
									<a href="<?php echo $domain; ?>?what=login">Login</a>
									<br>&nbsp;
								</font>
							</td>
						</tr>
					<?php
				}
				else
				{
				// Couldnt send the email
				?>
					<tr>
						<td>
							<font face="Verdana" size="5" color="#00000"><b>Password Not Sent</b></font>
							<font face="Verdana" size="2" color="#000000">
								<br><br>
								An error occured while trying to send your password to your email account.
								<br><br>
								<a href="javascript:document.location.reload()">Try Again</a>
								<br>&nbsp;
							</font>
						</td>
					</tr>
				<?php
				}
			}
			else
			{
				// Couldnt update the users password
				?>
					<tr>
						<td>
							<font face="Verdana" size="5" color="#00000"><b>Password Not Sent</b></font>
							<font face="Verdana" size="2" color="#000000">
								<br><br>
								An error occured while trying to send your password to your email account.
								<br><br>
								<a href="javascript:document.location.reload()">Try Again</a>
								<br>&nbsp;
							</font>
						</td>
					</tr>
				<?php
			}
		}
		else
		{
			// User doesn't exist in the database
			?>
				<tr>
					<td>
						<font face="Verdana" size="5" color="#00000"><b>User Not Found</b></font>
						<font face="Verdana" size="2" color="#000000">
							<br><br>
							A subscriber account with this email address was not found in our database. Please use
							the link below to try again.
							<br><br>
							<a href="<?php echo $domain; ?>?what=getPass">Try Again</a>
							<br>&nbsp;
						</font>
					</td>
				</tr>
			<?php
		}
		
		echo "</table>";
	}
	
	function ShowGetPasswordScreen()
	{
		// Send the user their password via email
		global $useTemplates;
		$domain = "/mwsubscribe/index.php";

		if(!$useTemplates)
			echo "<table width='770' align='center' border='0' cellspacing='0' cellpadding='0'>";
		else
			echo "<table width='95%' align='center' border='0' cellspacing='0' cellpadding='0'>";

		?>

			<script language="JavaScript">

				function ConfirmCancel(CancelURL)
				{
				  if(confirm('WARNING: Are you sure you want to cancel what you are doing? Click OK to proceed.'))
					  document.location.href = CancelURL;
				}
					
			</script>

			<form action="<?php echo $domain; ?>?what=sendPass" method="post">
			<tr>
				<td>
					<font face="Verdana" size="5" color="#00000"><b>Password Retrieval</b></font>
					<font face="Verdana" size="2" color="#000000">
						<br><br>
						Please enter your email address in the field below and then click on the "retrieve password" button.
						A new password will be emailed to you within 2-3 minutes.
						<br><br>
						<table width="100%" border="0" cellspacing="0" cellpadding="1">
							<tr>
								<td width="25%">
									<font face="Verdana" size="2" color="#000000">Email Address:</font>
								</td>
								<td width="75%">
									<input type="text" name="email" size="30">
								</td>
							</tr>
							<tr>
								<td width="25%">
									&nbsp;
								</td>
								<td width="75%">
									<input type="button" value="« Cancel" onClick="ConfirmCancel('<?php echo $domain; ?>')">
									<input type="submit" value="Retrieve Password »">
								</td>
							</tr>
						</table>
						<br>&nbsp;
					</font>
				</td>
			</tr>
			</form>
		<?php
		
		echo "</table>";
	}
	
	function ProcessUpdate()
	{
		// Update the users details and subscription preferences
		global $siteName;
		global $siteURL;
		global $fName;
		global $email;
		global $useTemplates;
		
		$cEmail = @$_COOKIE["mwEmail"];
		$fName = @$_POST["fName"];
		$lName = @$_POST["lName"];
		$email = @$_POST["email"];
		$pass1 = @$_POST["pass1"];
		$tIds = @$_POST["templateId"];
		$updatePass = false;
		$result1 = false;
		$result2 = false;
		$sOK = true;
		$domain = "/mwsubscribe/index.php";
		
		doDbConnect();
		
		$err = "";
		
		if($fName == "")
			$err .= "<li>You forgot to enter your first name</li>";
		
		if($lName == "")
			$err .= "<li>You forgot to enter your last name</li>";
			
		if(!is_numeric(strpos($email, "@")) || !is_numeric(strpos($email, ".")))
		{
			$err .= "<li>You didn't enter a valid email address</li>";
		}
		else
		{
			// Make sure the user doesn't already exist in the database
			$numEmails = mysql_result(mysql_query("select count(pk_suId) from subscribedUsers where suEmail = '$email'"), 0, 0);
			
			if($cEmail != $email && $numEmails > 0)
				$exists = true;

			if($exists == true)
				$err .= "<li>The selected email address is taken</li>";
		}

		if(strlen($pass1) > 0)
			$updatePass = true;
		
		if(!is_array($tIds))
			$err .= "<li>You forgot to select at least one newsletter to subscribe to</li>";
		
		// Is there an error?
		if($err != "")
		{
			TopTemplate();
			
			if(!$useTemplates)
				echo "<table width='770' align='center' border='0' cellspacing='0' cellpadding='0'>";
			else
				echo "<table width='95%' align='center' border='0' cellspacing='0' cellpadding='0'>";
		
		?>
			<tr>
				<td>
					<font face="Verdana" size="5" color="#00000"><b>Newsletter Subscription Failed</b></font>
					<font face="Verdana" size="2" color="#000000">
						<br><br>
						Your subscription form appears to be incomplete/invalid. Please review the list of errors
						shown below and then click on the link below to go back and correct them:
						<ul><?php echo $err; ?></ul>
						<p style="margin-left:5">
						<a href="javascript:history.go(-1)"><< Go Back</a>
						<br>&nbsp;
					</font>
				</td>
			</tr>
		<?php
			BottomTemplate();
		}
		else
		{
			// No error, update the users details and preferences
			// Get the users ID
			$sResult = mysql_query("select pk_suId from subscribedUsers where suEmail = '$cEmail'");
			
			if($sRow = mysql_fetch_row($sResult))
				$suId = $sRow[0];
			else
				$suId = -1;
			
			if($updatePass == false)
				$query1 = "update subscribedUsers set suFName = '$fName', suLName = '$lName', suEmail = '$email' where pk_suId = $suId";
			else
				$query1 = "update subscribedUsers set suFName = '$fName', suLName = '$lName', suEmail = '$email', suPassword = password('$pass1') where pk_suId = $suId";
			
			if(@mysql_query($query1))
				$result1 = true;
			else
				$result1 = false;
				
			// Build the query for the subscriptions
			@mysql_query("delete from subscriptions where sSubscriberId = $suId");

			for($i = 0; $i < sizeof($tIds); $i++)
			{
				if(!mysql_query("insert into subscriptions values(0, {$tIds[$i]}, $suId)"))
					$sOK = false;
			}
			
			if($result1 == true && $sOK == true)
			{
				// Everything was updated OK, change the cookie
				setcookie("mwEmail", $email);
				
				TopTemplate();
				
				if(!$useTemplates)
					echo "<table width='770' align='center' border='0' cellspacing='0' cellpadding='0'>";
				else
					echo "<table width='95%' align='center' border='0' cellspacing='0' cellpadding='0'>";

				?>
					<tr>
						<td>
							<font face="Verdana" size="5" color="#00000"><b>Subscription Details Updated!</b></font>
							<font face="Verdana" size="2" color="#000000">
								<br><br>
								Your subscription details have been updated successfully.
								<br><br>
								<a href="<?php echo $domain; ?>?what=logout">Logout</a> |
								<a href="<?php echo $domain; ?>">Continue</a>
								<br>&nbsp;
							</font>
						</td>
					</tr>
				<?php
				
				BottomTemplate();
			}
			else
			{
				// An error occured while trying to update the details
				TopTemplate();
				
				if(!$useTemplates)
					echo "<table width='770' align='center' border='0' cellspacing='0' cellpadding='0'>";
				else
					echo "<table width='95%' align='center' border='0' cellspacing='0' cellpadding='0'>";
				?>
					<tr>
						<td>
							<font face="Verdana" size="5" color="#00000"><b>Subscription Details Not Updated</b></font>
							<font face="Verdana" size="2" color="#000000">
								<br><br>
								An error occured while trying to update your subscription details.
								<br><br>
								<a href="javascript:document.location.reload()">Try Again</a>
								<br>&nbsp;
							</font>
						</td>
					</tr>
				<?php
				
				BottomTemplate();
			}
		}
		
		echo "</table>";
	}
	
	function ShowUpdateForm()
	{
		// Show the form to update the users subscription details
		global $useTemplates;
		global $topTemplate;
		global $bottomTemplate;
		
		$auth = @$_COOKIE["mwAuth"];
		$email = @$_COOKIE["mwEmail"];
		$domain = "/mwsubscribe/index.php";

		// Display the form listing all newsletters, etc
		if(!$useTemplates)
			echo "<table width='770' align='center' border='0' cellspacing='0' cellpadding='0'>";
		else
			echo "<table width='95%' align='center' border='0' cellspacing='0' cellpadding='0'>";
			
		if($auth == true)
		{
			// User is logged in, show their subscription preferences
			doDbConnect();
			
			$result1 = mysql_query("select * from subscribedUsers where suEmail = '$email'");
			
			if($row1 = mysql_fetch_array($result1))
			{
				// Get the users subscription preferences
				$sId = $row1["pk_suId"];
				$fName = $row1["suFName"];
				$lName = $row1["suLName"];
				$email = $row1["suEmail"];
				$password = $row1["suPassword"];
				$arrTIds = array();
				$domain = "/mwsubscribe/index.php";
				
				$result2 = mysql_query("select * from subscriptions where sSubscriberId = $sId");
				
				while($row2 = mysql_fetch_row($result2))
					$arrTIds[] = $row2[1];
					
				?>
				
					<script language="JavaScript">

						function ConfirmCancel(CancelURL)
						{
						  if(confirm('WARNING: Are you sure you want to cancel what you are doing? Click OK to proceed.'))
							  document.location.href = CancelURL;
						}
					
					</script>
					
					<form action="<?php echo $domain; ?>?what=updateDetails" method="post">
						<tr>
							<td>
								<font face="Verdana" size="4" color="#00000"><b>Your Subscription Details</b></font>
								<font face="Verdana" size="2" color="#000000">
								<br><br>
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td width="25%">
											<font face="Verdana" size="2" color="#000000">First Name:</font>
										</td>
										<td width="75%">
											<input type="text" name="fName" size="30" value="<?php echo $fName; ?>">
										</td>
									</tr>
									<tr>
										<td width="25%">
											<font face="Verdana" size="2" color="#000000">Last Name:</font>
										</td>
										<td width="75%">
											<input type="text" name="lName" size="30" value="<?php echo $lName; ?>">
										</td>
									</tr>
									<tr>
										<td width="25%">
											<font face="Verdana" size="2" color="#000000">Email Address:</font>
										</td>
										<td width="75%">
											<input type="text" name="email" size="30" value="<?php echo $email; ?>">
										</td>
									</tr>
									<tr>
										<td width="25%">
											&nbsp;
										</td>
										<td width="75%">
											&nbsp;
										</td>
									</tr>
									<tr>
										<td width="25%">
											<font face="Verdana" size="2" color="#000000">New Password:</font>
										</td>
										<td width="75%">
											<input type="password" name="pass1" size="30"><br>
											<font face="Verdana" size="1" color="#000000"><i>[Leave blank to keep current password]</i></font>
										</td>
									</tr>
								</table>
								<br>
								<font face="Verdana" size="4" color="#00000"><b>Newsletter Subscriptions</b></font>
								<br><br>
								<?php
								
									$result = mysql_query("select * from topics order by tName asc");
							
									while($row = mysql_fetch_row($result))
									{
										$nResult = mysql_query("select pk_nId, nName, nDesc, nFrequency1, nFrequency2, nFormat from templates where nTopicId = " . $row[0]);
										
										if(mysql_num_rows($nResult) > 0)
										{
											echo "<b>&nbsp;&nbsp;&nbsp;<img src='/mwsubscribe/images/arrow.gif'> " . $row[1] . "</b><br><br>";
											echo "<p style='margin-left:25'>";
											
											while($nRow = mysql_fetch_row($nResult))
											{
											?>
												<input type="checkbox" <?php if(in_array($nRow[0], $arrTIds)) { echo " CHECKED "; } ?> name="templateId[]" value="<?php echo $nRow[0]; ?>">
												<font color="#183863"><b><i><?php echo $nRow[1]; ?>:</i></b><br></font>
												<font face="Verdana" size="1" color="#808080">
													<b>Format:</b> <?php echo $nRow[5]; ?> | <b>Frequency:</b> Every <?php echo $nRow[3]; ?>
													<?php
													
														switch($nRow[4])
														{
															case 1:
																echo "Day(s)";
																break;
															case 2:
																echo "Week(s)";
																break;
															case 3:
																echo "Month(s)";
																break;
														}
													?>
												</font>
												<br><br>
												<?php echo $nRow[2]; ?>
												<br><br>
											<?php
											}
											echo "</p>";
										}
									}
								?>
								<input type="button" value="« Cancel" onClick="ConfirmCancel('<?php echo $domain; ?>')">
								<input type="submit" name="submit" value="Update Subscription Details »">
							</font>
							<br>&nbsp;
						</td>
					</tr>
				</form>
				</table>
				<?php
			}
			else
			{
				// User no longer exists in the database
				?>
					<tr>
						<td>
							<font face="Verdana" size="5" color="#00000"><b>Subscription Details Not Found</b></font>
							<font face="Verdana" size="2" color="#000000">
								<br><br>
								Your subscription details were not found in our database.
								<br><br>
								<a href="<?php echo $domain; ?>?what=login">Login</a>
								<br>&nbsp;
							</font>
						</td>
					</tr>
				<?php
			}
		}
		else
		{
			// User hasn't logged in yet
			?>
				<tr>
					<td>
						<font face="Verdana" size="5" color="#00000"><b>Not Logged In</b></font>
						<font face="Verdana" size="2" color="#000000">
							<br><br>
							You must login before you can modify your newsletter subscription details. Please click on the link below to login.
							<br><br>
							<a href="<?php echo $domain; ?>?what=login">Login</a>
							<br>&nbsp;
						</font>
					</td>
				</tr>
			<?php
		}
			
		echo "</table>";
	}
	
	function ProcessLogin()
	{
		// This function will check if the user exists in the database and if so it will
		// set cookies for the users details and allow him to update his subscriptions
		
		global $useTemplates;
		global $topTemplate;
		global $bottomTemplate;

		$email = @$_POST["email"];
		$password = @$_POST["password"];
		$domain = "/mwsubscribe/index.php";

		doDbConnect();
		
		$result = mysql_query("select * from subscribedUsers where suEmail='$email' and suPassword = password('$password')");
		
		if($row = mysql_fetch_array($result))
		{
			if($row["suStatus"] == "subscribed")
			{
				// User exists, get his details and save them as cookies
				$fName = $row["suFName"];
				$email = $row["suEmail"];
			
				setcookie("mwAuth", true);
				setcookie("mwEmail", $email);
			
				TopTemplate();

				// Display the form listing all newsletters, etc
				if(!$useTemplates)
					echo "<table width='770' align='center' border='0' cellspacing='0' cellpadding='0'>";
				else
					echo "<table width='95%' align='center' border='0' cellspacing='0' cellpadding='0'>";
		
				?>
					<tr>
						<td>
							<font face="Verdana" size="5" color="#00000"><b>Login Successful</b></font>
							<font face="Verdana" size="2" color="#000000">
								<br><br>
								Thank you for logging in <?php echo $fName; ?>. Please click on the link below to manage your newsletter subscription details.
								<br><br>
								<a href="<?php echo $domain; ?>?what=update">Manage Subscription Details</a>
								<br>&nbsp;
							</font>
						</td>
					</tr>
				<?php
			
				BottomTemplate();
			}
			else
			{
				// This user hasn't clicked on the link in his confirmation email yet
				TopTemplate();

				// Display the form listing all newsletters, etc
				if(!$useTemplates)
					echo "<table width='770' align='center' border='0' cellspacing='0' cellpadding='0'>";
				else
					echo "<table width='95%' align='center' border='0' cellspacing='0' cellpadding='0'>";
				?>
					<tr>
						<td>
							<font face="Verdana" size="5" color="#00000"><b>Subscription Not Confirmed</b></font>
							<font face="Verdana" size="2" color="#000000">
								<br><br>
								You were sent a confirmation email for your newsletter subscription when you registered. You must read this email and
								click on the link in that email to confirm your subscription before you can login.
								<br><br>
								<a href="<?php echo $domain; ?>?what=login">Login</a> |
								<a href="<?php echo $domain; ?>">Signup Again</a>
								<br>&nbsp;
							</font>
						</td>
					</tr>
				<?php
				
				BottomTemplate();
			}
		}
		else
		{
			// User doesn't exist in the database
			TopTemplate();
			
			// Display the form listing all newsletters, etc
			if(!$useTemplates)
				echo "<table width='770' align='center' border='0' cellspacing='0' cellpadding='0'>";
			else
				echo "<table width='95%' align='center' border='0' cellspacing='0' cellpadding='0'>";
			?>
				<tr>
					<td>
						<font face="Verdana" size="5" color="#00000"><b>Login Failed</b></font>
						<font face="Verdana" size="2" color="#000000">
							<br><br>
							Your details were not found in our subscription database.
							<br><br>
							<a href="<?php echo $domain; ?>?what=subscribe">Become A Subscriber</a> |
							<a href="<?php echo $domain; ?>?what=getPass">Retrieve Password</a> |
							<a href="<?php echo $domain; ?>?what=login">Try Again</a>
							<br>&nbsp;
						</font>
					</td>
				</tr>
			<?php
		}
		echo "</table>";
		
		BottomTemplate();
	}

	function TopTemplate()
	{
		global $useTemplates;
		global $topTemplate;
		
		if($useTemplates == true)
		{
			if($fp = @fopen($topTemplate, "rb"))
			{
				while(!@feof($fp))
				{
					$tData .= fgets($fp, 4096);
				}
					
				@fclose($fp);
				echo $tData . "<br>";
			}
		}
	}
	
	function ShowLoginScreen()
	{
		// Show the login screen to let users manage their subscription preferences
		global $useTemplates;
		$domain = "/mwsubscribe/index.php";

		// Display the form listing all newsletters, etc
		if(!$useTemplates)
			echo "<table width='770' align='center' border='0' cellspacing='0' cellpadding='0'>";
		else
			echo "<table width='95%' align='center' border='0' cellspacing='0' cellpadding='0'>";
		
		doDbConnect();

		?>

			<script language="JavaScript">

				function ConfirmCancel(CancelURL)
				{
				  if(confirm('WARNING: Are you sure you want to cancel what you are doing? Click OK to proceed.'))
					  document.location.href = CancelURL;
				}
					
			</script>
					
			<form action="<?php echo $domain; ?>?what=processLogin" method="post">
			<tr>
				<td>
					<font face="Verdana" size="5" color="#00000"><b>Newsletter Login</b></font>
					<font face="Verdana" size="2" color="#000000">
						<br><br>
						Please enter your email address and password in the fields below to login and update your newsletter
						subscription information. If you have forgotten your password please <a href="<?php echo $domain;?>?what=getPass">click here</a>.
						<br><br>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="25%">
									<font face="Verdana" size="2" color="#000000">Email Address:</font>
								</td>
								<td width="75%">
									<input type="text" name="email" size="30">
								</td>
							</tr>
							<tr>
								<td width="25%">
									<font face="Verdana" size="2" color="#000000">Password:</font>
								</td>
								<td width="75%">
									<input type="password" name="password" size="30">
								</td>
							</tr>
							<tr>
								<td width="25%">
									&nbsp;
								</td>
								<td width="75%">
									<input type="button" value="« Cancel" onClick="ConfirmCancel('<?php echo $domain; ?>')">
									<input type="submit" name="submit" value="Login »">
								</td>
							</tr>
						</table>
					</font>
				</td>
			</tr>
		</table>
		</form>
		<?php
	}
	
	function ConfirmSubscription()
	{
		global $useTemplates;
		$domain = "/mwsubscribe/index.php";

		// Display the form listing all newsletters, etc
		if(!$useTemplates)
			echo "<table width='770' align='center' border='0' cellspacing='0' cellpadding='0'>";
		else
			echo "<table width='95%' align='center' border='0' cellspacing='0' cellpadding='0'>";
		
		$suId = @$_GET["suId"];
		
		doDbConnect();
		
		if(is_numeric($suId))
		{
			// Update the subscribedUsers table
			if(@mysql_query("update subscribedUsers set suStatus = 'subscribed' where pk_suId = $suId"))
			{
				// Update was successful
				?>
					<tr>
						<td>
							<font face="Verdana" size="5" color="#00000"><b>Subscription Updated Successful</b></font>
							<font face="Verdana" size="2" color="#000000">
								<br><br>
								Thank you for confirming your subscription to our newsletter. You will now receive your chosen newsletter(s)
								when appropriate. You can manage your subscription details and preferences by logging into your account using
								the link below:
								<br><br>
								<a href="<?php echo $domain; ?>?what=login">Login To Manage Subscription Details</a>
								<br>&nbsp;
							</font>
						</td>
					</tr>
				<?php
			}
			else
			{
				// Update failed
				?>
					<tr>
						<td>
							<font face="Verdana" size="5" color="#00000"><b>Subscription Updated Failed</b></font>
							<font face="Verdana" size="2" color="#000000">
								<br><br>
								An error occured while trying to confirm your subscription status. Please click on the
								link below to tryin again.
								<br><br>
								<a href="javascript:document.location.reload()">Try Again</a>
								<br>&nbsp;
							</font>
						</td>
					</tr>
				<?php
			}
		}
		else
		{
			// Invalid subscribed ID
			?>
				<tr>
					<td>
						<font face="Verdana" size="5" color="#00000"><b>Invalid Subscriber ID</b></font>
						<font face="Verdana" size="2" color="#000000">
							<br><br>
							The selected subscribed ID is invalid.
							<br>&nbsp;
						</font>
					</td>
				</tr>
			<?php
		}
		echo "</table>";
	}

	function ShowPrivacyPolicy()
	{
		global $useTemplates;
		global $privacyPolicyStmt;

		// Display the form listing all newsletters, etc
		if(!$useTemplates)
			echo "<table width='770' align='center' border='0' cellspacing='0' cellpadding='0'>";
		else
			echo "<table width='95%' align='center' border='0' cellspacing='0' cellpadding='0'>";
			
		?>
				<tr>
					<td>
						<font face="Verdana" size="5" color="#00000"><b>Our Privacy Policy</b></font>
						<br><br>
						<font face="Verdana" size="2" color="#000000">
							<?php echo $privacyPolicyStmt; ?>
							<br><br>
							<a href="javascript:history.go(-1)"><< Go Back</a>
							<br>&nbsp;
						</font>
					</td>
				</tr>
			</table>
		<?php
	}
	
	function ShowNewUserScreen()
	{
		// Display the form listing all newsletters, etc
		global $useTemplates;

		doDbConnect();
		$domain = "/mwsubscribe/index.php";
		
		if(!$useTemplates)
			echo "<table width='770' align='center' border='0' cellspacing='0' cellpadding='0'>";
		else
			echo "<table width='95%' align='center' border='0' cellspacing='0' cellpadding='0'>";
			
		?>

			<script language="JavaScript">

				function ConfirmCancel(CancelURL)
				{
				  if(confirm('WARNING: Are you sure you want to cancel what you are doing? Click OK to proceed.'))
					  document.location.href = CancelURL;
				}
					
			</script>

			<form action="<?php echo $domain; ?>?what=processNew" method="post">
				<tr>
					<td>
						<font face="Verdana" size="5" color="#00000"><b>Newsletter Subscriptions</b></font>
						<font face="Verdana" size="2" color="#000000">
							<br><br>
							To subscribe to one/more of our newsletters, please complete the form below, choosing which
							newsletter(s) you'd like to subscribe to. Once you've completed the form, click on the
							"Subscribe Now >>" button. You will receive an emailing containing a confirmation link. Simply
							click this link and you will be automatically subscribed to your selected newsletter(s).
							<br><br>
							<font face="Verdana" size="4" color="#00000"><b>Your Subscription Details</b></font>
							<br><br>
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="25%">
										<font face="Verdana" size="2" color="#000000">First Name:</font>
									</td>
									<td width="75%">
										<input type="text" name="fName" size="30">
									</td>
								</tr>
								<tr>
									<td width="25%">
										<font face="Verdana" size="2" color="#000000">Last Name:</font>
									</td>
									<td width="75%">
										<input type="text" name="lName" size="30">
									</td>
								</tr>
								<tr>
									<td width="25%">
										<font face="Verdana" size="2" color="#000000">Email Address:</font>
									</td>
									<td width="75%">
										<input type="text" name="email" size="30">
									</td>
								</tr>
								<tr>
									<td width="25%">
										&nbsp;
									</td>
									<td width="75%">
										&nbsp;
									</td>
								</tr>
								<tr>
									<td width="25%">
										<font face="Verdana" size="2" color="#000000">Password:</font>
									</td>
									<td width="75%">
										<input type="password" name="pass1" size="30">
									</td>
								</tr>
								<tr>
									<td width="25%">
										<font face="Verdana" size="2" color="#000000">Confirm Password:</font>
									</td>
									<td width="75%">
										<input type="password" name="pass2" size="30">
									</td>
								</tr>
							</table>
							<br>
							<font face="Verdana" size="4" color="#00000"><b>Newsletter Subscriptions</b></font>
							<br><br>
							<?php
							
								$result = mysql_query("select * from topics order by tName asc");
						
								while($row = mysql_fetch_row($result))
								{
									$nResult = mysql_query("select pk_nId, nName, nDesc, nFrequency1, nFrequency2, nFormat from templates where nTopicId = " . $row[0]);
									
									if(mysql_num_rows($nResult) > 0)
									{
										echo "<b>&nbsp;&nbsp;&nbsp;<img src='/mwsubscribe/images/arrow.gif'> " . $row[1] . "</b><br><br>";
										echo "<p style='margin-left:25'>";
										
										while($nRow = mysql_fetch_row($nResult))
										{
										?>
											<input type="checkbox" CHECKED name="templateId[]" value="<?php echo $nRow[0]; ?>">
											<font color="#183863"><b><i><?php echo $nRow[1]; ?>:</i></b><br></font>
											<font face="Verdana" size="1" color="#808080">
												<b>Format:</b> <?php echo $nRow[5]; ?> | <b>Frequency:</b> Every <?php echo $nRow[3]; ?>
												<?php
												
													switch($nRow[4])
													{
														case 1:
															echo "Day(s)";
															break;
														case 2:
															echo "Week(s)";
															break;
														case 3:
															echo "Month(s)";
															break;
													}
												?>
											</font>
											<br><br>
											<?php echo $nRow[2]; ?>
											<br><br>
										<?php
										}
										echo "</p>";
									}
								}
							?>
							<input type="button" value="« Cancel" onClick="ConfirmCancel('<?php echo $domain; ?>')">
							<input type="submit" name="submit" value="Subscribe Now »">
						</font>
						<br>&nbsp;
					</td>
				</tr>
			</table>
		</form>
		<?php
	}

	function ProcessSubscription()
	{
		// Take the users details, validate them, and add them to the database
		global $siteName;
		global $siteURL;
		global $fName;
		global $email;
		global $useTemplates;
		
		$fName = @$_POST["fName"];
		$lName = @$_POST["lName"];
		$email = @$_POST["email"];
		$pass1 = @$_POST["pass1"];
		$pass2 = @$_POST["pass2"];
		$tIds = @$_POST["templateId"];
		$domain = "/mwsubscribe/index.php";
		
		doDbConnect();
		
		$err = "";
		
		if($fName == "")
			$err .= "<li>You forgot to enter your first name</li>";
		
		if($lName == "")
			$err .= "<li>You forgot to enter your last name</li>";
			
		if(!is_numeric(strpos($email, "@")) || !is_numeric(strpos($email, ".")))
		{
			$err .= "<li>You didn't enter a valid email address</li>";
		}
		else
		{
			// Make sure the user doesn't already exist in the database
			$exists = mysql_result(mysql_query("select count(pk_suId) from subscribedUsers where suEmail = '$email'"), 0, 0) > 0 ? true : false;
			
			if($exists == true)
				$err .= "<li>You are already subscribed to our newsletter(s)</li>";
		}
		
		if(strlen($pass1) < 5)
		{
			$err .= "<li>You must enter a password of at least 5 characters</li>";
		}
		else
		{
			if($pass1 != $pass2)
				$err .= "<li>Your passwords didn't match</li>";
		}
		
		if(!is_array($tIds))
			$err .= "<li>You forgot to select at least one newsletter to subscribe to</li>";
		
		if(!$useTemplates)
			echo "<table width='770' align='center' border='0' cellspacing='0' cellpadding='0'>";
		else
			echo "<table width='95%' align='center' border='0' cellspacing='0' cellpadding='0'>";
		
		// Is there an error?
		if($err != "")
		{
		?>
			<tr>
				<td>
					<font face="Verdana" size="5" color="#00000"><b>Newsletter Subscription Failed</b></font>
					<font face="Verdana" size="2" color="#000000">
						<br><br>
						Your subscription form appears to be incomplete/invalid. Please review the list of errors
						shown below and then click on the link below to go back and correct them:
						<ul><?php echo $err; ?></ul>
						<p style="margin-left:5">
						<a href="javascript:history.go(-1)"><< Go Back</a>
						<br>&nbsp;
					</font>
				</td>
			</tr>
		<?php
		}
		else
		{
			// No error, add the user and his subscription preferences to the database
			if(@mysql_query("insert into subscribedUsers(suFName, suLName, suEmail, suPassword, suStatus) values('$fName', '$lName', '$email', password('$pass1'), 'pending')"))
			{
				$suId = mysql_insert_id();
				$failed = false;
				
				// Add the users subscription preferences to the subscriptions table
				for($i = 0; $i < sizeof($tIds); $i++)
				{
					if(!@mysql_query("insert into subscriptions values(0, {$tIds[$i]}, $suId)"))
						$failed = true;
				}
				
				if($failed == true)
				{
				?>
					<tr>
						<td>
							<font face="Verdana" size="5" color="#00000"><b>Newsletter Subscription Failed</b></font>
							<font face="Verdana" size="2" color="#000000">
								<br><br>
								An error occured while trying to add you to our subscription list. Please click on the
								link below to try again:
								<br><br>
								<a href="javascript:document.location.reload()">Try Again</a>
								<br>&nbsp;
							</font>
						</td>
					</tr>
				<?php
				}
				
				// Everything is OK, email the user their confirmation opt-in link
				$mailMsg = "Hi $fName,\r\nYou have been sent this email to confirm your subscription to the $siteName newsletter(s). You MUST click on the link below to confirm your subscription:\r\n\r\n$siteURL/mwsubscribe/index.php?what=confirm&suId=$suId&cfn=" . md5($suId) . "\r\n\r\nThanks,\r\nThe $siteName Team\r\n$siteURL\r\n\r\n";
				
				if(@mail($email, "$siteName Subscription Confirmation", $mailMsg))
				{
					// Email sent OK
					?>
						<tr>
							<td>
								<font face="Verdana" size="5" color="#00000"><b>Newsletter Subscription Successful!</b></font>
								<font face="Verdana" size="2" color="#000000">
									<br><br>
									Thank you for subscribing to our newsletter(s) <?php echo $fName; ?>. We have sent a confirmation email to <?php echo $email; ?>.
									You MUST click the link inside this email to confirm your subscription status, so please check your email in 2-3 minutes.
									<br><br>
									<a href="<?php echo $domain; ?>">Continue</a>
									<br>&nbsp;
								</font>
							</td>
						</tr>
					<?php
				}
				else
				{
					// Couldn't send email
					?>
					<tr>
						<td>
							<font face="Verdana" size="5" color="#00000"><b>Newsletter Subscription Failed</b></font>
							<font face="Verdana" size="2" color="#000000">
								<br><br>
								An error occured while trying to add you to our subscription list. Please click on the
								link below to try again:
								<br><br>
								<a href="javascript:document.location.reload()">Try Again</a>
								<br>&nbsp;
							</font>
						</td>
					</tr>
					<?php
				}
			}
			else
			{
				// An error occured while trying to subscribe this user
				?>
					<tr>
						<td>
							<font face="Verdana" size="5" color="#00000"><b>Newsletter Subscription Failed</b></font>
							<font face="Verdana" size="2" color="#000000">
								<br><br>
								An error occured while trying to add you to our subscription list. Please click on the
								link below to try again:
								<br><br>
								<a href="javascript:document.location.reload()">Try Again</a>
								<br>&nbsp;
							</font>
						</td>
					</tr>
				<?php
			}
		}
		
		echo "</table>";
	}
	
	function BottomTemplate()
	{	
		global $useTemplates;
		global $topTemplate;

		if($useTemplates == true)
		{
			if(@$fp = @fopen($bottomTemplate, "rb"))
			{
				while(!@feof($fp))
					$tData .= fgets($fp, 4096);
					
				@fclose($fp);
				echo $tData;
			}
		}
	}
	
?>