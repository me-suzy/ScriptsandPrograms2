<?php
////////////////////////////////////////////////
// Sending mass mail
if ($home_action=="send_mass_mail") {
	// Removes HTML from the mail
	$subject = htmlspecialchars($subject);
	$message = htmlspecialchars($message);

	// Making sure the form is not blank
	if ($subject==false || $message==false)
	{
		echo "<html><body><script language=javascript1.1>alert('Please fill out the required fields'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
		exit;
	}
	
	$subject = "Message from: $library_name - $subject"; 
	$message = "$message\n\n\nThis message was sent from: $library_name by the user: $PHPLibrary[username]";
	$extra = "From: $library_email\r\nReply-To: $library_email\r\n";
	
	// Getting all the students email address and sending
	$sqlMassMail = mysql_query("SELECT `email` FROM $mysql_pre$mysql_students"); 
	if ($resultMassMail = mysql_fetch_array($sqlMassMail)) {
		$students_count = 0;
		echo "<b>Email Progress:</b><br><br>";
		do {
				// Getting the number of students to email
				$sqlCountStudents = "SELECT * FROM $mysql_pre$mysql_students";
				$resultCountStudents = mysql_query($sqlCountStudents); 
				$CountStudents = mysql_num_rows($resultCountStudents); 
			$students_count++;
			$to = $resultMassMail[email];
			mail ($to, $subject, $message, $extra);
			echo "$students_count of $CountStudents: <b>$to</b><br>";
		} while ($resultMassMail = mysql_fetch_array($sqlMassMail));
	}
	echo "<html><body><script language=javascript1.1>alert('Your message has been sent to $students_count students'); window.location='$PHP_SELF?module=$module';</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
}
////////////////////////////////////////////////
// Displaying mass mail form 
if ($home_action=="mass_mail") {
?>
<center>
Mass Mail all Students<br><br>
<a href="<?php echo "$PHP_SELF?module=$module"; ?>"><img src="images/back.gif" boarder=0> Back</a><br>
<form name="form1" method="post" action="<?php echo "$PHP_SELF?module=$module"; ?>&header_footer=no&home_action=send_mass_mail">
  <table cellpadding=2 cellspacing=0 border=1 bordercolor=#FFFFCC align=center>
    <tr> 
      <td class=color3>Subject:</td>
      <td class=color2><input name="subject" type="text" class="Input" size="25" maxlength="30"></td>
    </tr>
    <tr>
      <td class=color3>Message:</td>
      <td class=color2><textarea name="message" cols="25" rows="5" class="Input"></textarea></td>
    </tr>
    <tr> 
      <td class=color3>&nbsp;</td>
      <td class=color3><input class="Input" type="submit" name="Submit" value="Ok"> </td>
    </tr>
  </table>
</form>
</center>
<?
}
////////////////////////////////////////////////
// Displaying home page
if ($home_action==false) {
?>
	<div align="center"><font size="3"><strong>Welcome to the <?php echo $library_name; ?></strong></font><br>
	  <br>
	  <table width="22%" border="1" cellspacing="10" bordercolor="#000000">
		<tr> 
		  <td> <div align="center"> 
			  <p><b>Database Stats</b><br>
				<a href="<?php echo $PHP_SELF; ?>?module=items"><b> 
				<?php 
		$sqlCountItems = "SELECT * FROM $mysql_pre$mysql_items";
		$resultCountItems = mysql_query($sqlCountItems); 
		$CountItems = mysql_num_rows($resultCountItems); 
		echo "$CountItems"; 
		?>
				</b> items at a total cost of £<?php 
		$sqlCostItems = mysql_query("SELECT `price` FROM `$mysql_pre$mysql_items`"); 
		
		if ($resultCostItems = mysql_fetch_array($sqlCostItems)) {
		do {
				$total_cost = ($total_cost + $resultCostItems["price"]);
			} while ($resultCostItems = mysql_fetch_array($sqlCostItems)); 
		} 
		else
		{
		$total_cost = 0;
		}
		echo $total_cost; ?></a><br>
			   
		
				<a href="<?php echo $PHP_SELF; ?>?module=students"><b>
				<?php 
		$sqlCountStudents = "SELECT * FROM $mysql_pre$mysql_students";
		$resultCountStudents = mysql_query($sqlCountStudents); 
		$CountStudents = mysql_num_rows($resultCountStudents); 
		echo "$CountStudents"; 
		?>
				</b> students</a><br>
				<a href="<?php echo $PHP_SELF; ?>?module=loans"<b> 
					<?php 
		$sqlCountItemsOut = "SELECT * FROM $mysql_pre$mysql_loans WHERE `status`='Out'";
		$resultCountItemsOut = mysql_query($sqlCountItemsOut); 
		$CountItemsOut = mysql_num_rows($resultCountItemsOut); 
		echo "$CountItemsOut"; 
		?>
				</b> items being borrowed</a><br>
				<a href="<?php echo $PHP_SELF; ?>?module=loans&show_status=Both"<b> 
				<?php 
		$sqlCountLoans = "SELECT * FROM $mysql_pre$mysql_loans";
		$resultCountLoans = mysql_query($sqlCountLoans); 
		$CountLoans = mysql_num_rows($resultCountLoans); 
		echo "$CountLoans"; 
		?>
				</b> loans have been made in total</a><br>
				
				<a href="<?php echo $PHP_SELF; ?>?module=overdue"><b> 
				<?php 
		// Selecting all the items that are set as out
		$sqlOverdueEmail = mysql_query("SELECT * FROM `$mysql_pre$mysql_loans` WHERE 1 AND `status` LIKE 'Out' ORDER BY `id` ASC",$db);
		
		// Displaying the data
		if ($resultOverdueEmail = mysql_fetch_array($sqlOverdueEmail)) {
			// Checking how many items are overdue
			$email_count = 0;
			$overdue_count = "0";
			do {
				
				// Checking input year as it should not be less that this year
				if ($resultOverdueEmail[date_in_year] < $current_year)
				{
					$email_count = true;
				}
				
				// Checking input month as it should not be less that this month
				if ($resultOverdueEmail[date_in_month] < $current_month)
				{
					$email_count = true;
				}
				
				// Checking input day as it should be greater than todays day
				if ($resultOverdueEmail[date_in_day] < $current_day)
				{
					if ($resultOverdueEmail[date_in_month] <= $current_month)
					{
						$email_count = true;
					}
				}
				
				if ($email_count==true) {
					$overdue_count++;
				}
			} while ($resultOverdueEmail = mysql_fetch_array($sqlOverdueEmail));
		}
		
		if ($overdue_count>=1) {
			echo "<font color=#FF9900>$overdue_count";
		}
		else
		{
			echo "0 ";
		}
				
		?>
				</b> overdue items</font></a><br>
				
				<a href="<?php echo $PHP_SELF; ?>?module=accounts"><b> 
				<?php 
		$sqlCountAdmin = "SELECT * FROM $mysql_pre$mysql_admin";
		$resultCountAdmin = mysql_query($sqlCountAdmin); 
		$CountAdmin = mysql_num_rows($resultCountAdmin); 
		echo "$CountAdmin"; 
		?>
				</b> admin accounts</a></p>
			</div></td>
		</tr>
	  </table>
	</div>
	<br><br>
	<center>
	<a href="<?php echo "$PHP_SELF?module=$module&home_action=mass_mail"; ?>"><img src="images/email.gif"> Mass Mail all Students</a>
	</center>
<?php
}
?>