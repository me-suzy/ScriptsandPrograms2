<?

	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\\
	// This script is copyrighted to CreateYourGetPaid©       \\
	// Duplication, selling, or transferring of this script   \\
	// is a violation of the copyright and purchase agreement.\\
	// Alteration of this script in any way voids any         \\
	// responsibility CreateYourGetPaid© has towards the      \\
	// functioning of the script. Altering the script in an   \\
	// attempt to unlock other functions of the program that  \\
	// have not been purchased is a violation of the          \\
	// purchase agreement and forbidden by CreateYourGetPaid© \\
	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\\
	
	$GLOBALS["adminpage"] = "yes";
	
	include "../lib/.htconfig.php";
	
	$tml->RegisterVar("TITLE", "Site Configuration");
	
	if(!$user->IsOperator() || !$user->IsLoggedIn())
		exit($error->Report("Site Configuration", "You can not access this page."));
	
	if($_GET["action"] == "resetrc" && _ADDON_CT == 1)
	{
		$db->Query("UPDATE refs SET ct='0'");
		
		$main->WriteToLog("global", "Referral contest reset.");
		
		$main->printText("<B>Site Configuration</B><BR><BR>Referral Contest has been resetted.", 1);
	}
	elseif($_GET["action"] == "resetbubble" && _ADDON_BUBBLE == 1)
	{
		$db->Query("TRUNCATE TABLE bubble");
		$db->Query("UPDATE config SET bubble_cash='0'");
		
		$main->WriteToLog("global", "Bubble game reset.");
		
		$main->printText("<B>Site Configuration</B><BR><BR>Bubble game has been reset.", 1);
	}
	elseif($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$cfg->Save($main->Trim($_POST, 1));
		
		$main->WriteToLog("global", "Configuration updated");
		
		$main->printText("<B>Site Configuration</B><BR><BR>Site Configuration succesfully updated.", 1);
	}
	else
	{
		$text	 = "<FORM ACTION=\"" . _ADMIN_URL . "/configuration.php?sid=" . $session->ID . "\" METHOD=\"POST\">\n";
		$text	.= "<TABLE WIDTH=\"100%\">\n";
		$text	.= "<TR><TD COLSPAN=\"2\" ALIGN=\"center\"><B>Site Configuration for " . _SITE_TITLE . ($_GET["section"] ? " - " . $_GET["section"] : "") . "</B></TD></TR>";
		$text	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>";
		
		if($_GET["section"] == "website")
		{
			$text	.= "<TR><TD COLSPAN=\"2\"><U><B>Website Settings</B></U></TD></TR>\n";
			$text	.= "<TR><TD><B>Site Title:</B><BR><FONT SIZE=\"1\">website's title</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"site_title\" VALUE=\"" . _SITE_TITLE . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>Site E-Mail:</B><BR><FONT SIZE=\"1\">website's e-mail address</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"site_email\" VALUE=\"" . _SITE_EMAIL . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>Site Language:</B><BR><FONT SIZE=\"1\">website's default language</FONT></TD><TD><SELECT NAME=\"site_language\" SIZE=\"1\">\n";
			
			$languages	= $main->GetLanguages();
		    
		    for($i = 0; $i < count($languages); $i++)
		    {
			    $text	.= "<OPTION VALUE=\"$languages[$i]\"" . ($languages[$i] == _SITE_LANGUAGE ? " selected" : "") . ">$languages[$i]</OPTION>\n";
		    }
			
			$text	.= "</SELECT></TD></TR>\n";
			$text	.= "<TR><TD><B>Maintenance mode:</B><BR><FONT SIZE=1>This will make the site unavailable to members<BR>Operators are unaffected by this setting</FONT></TD><TD>Yes <INPUT TYPE=\"radio\" NAME=\"site_maintenance\" VALUE=\"YES\"" . (_SITE_MAINTENANCE == "YES" ? " checked" : "") . "> No <INPUT TYPE=\"radio\" NAME=\"site_maintenance\" VALUE=\"NO\"" . (_SITE_MAINTENANCE == "NO" ? " checked" : "") . "></TD></TR>\n";
			$text	.= "<TR><TD><B>Site Statistics:</B></TD><TD>Yes <INPUT TYPE=\"radio\" NAME=\"site_statistics\" VALUE=\"YES\"" . (_SITE_STATISTICS == "YES" ? " checked" : "") . "> No <INPUT TYPE=\"radio\" NAME=\"site_statistics\" VALUE=\"NO\"" . (_SITE_STATISTICS == "NO" ? " checked" : "") . "></TD></TR>\n";
			$text	.= "<TR><TD><B>Users Online:</B><BR><FONT SIZE=1>View how many users are online</FONT></TD><TD>Yes <INPUT TYPE=\"radio\" NAME=\"site_usersonline\" VALUE=\"YES\"" . (_SITE_USERSONLINE == "YES" ? " checked" : "") . "> No <INPUT TYPE=\"radio\" NAME=\"site_usersonline\" VALUE=\"NO\"" . (_SITE_USERSONLINE == "NO" ? " checked" : "") . "></TD></TR>\n";
			$text	.= "<TR><TD><B>Users Online Time-Out:</B><BR><FONT SIZE=1>Timeout value in seconds<BR>\"<B>[SECONDS]</B>\"</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"site_userstimeout\" VALUE=\"" . _SITE_USERSTIMEOUT . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>Date Format:</B><BR><FONT SIZE=1>same as <A HREF=\"http://www.php.net/date\" TARGET=\"_blank\">www.php.net/date</A></FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"site_datestamp\" VALUE=\"" . _SITE_DATESTAMP . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>Clean Paid E-Mails:</B><BR><FONT SIZE=1>delete unclicked emails after X seconds</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"site_cleanpm\" VALUE=\"" . _SITE_CLEANPM . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>Clean Paid Clicks:</B><BR><FONT SIZE=1>delete unclicked clicks after X seconds</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"site_cleanpc\" VALUE=\"" . _SITE_CLEANPC . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>Clean Login Logs:</B><BR><FONT SIZE=1>delete user logs after X seconds</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"site_session\" VALUE=\"" . _SITE_SESSION . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>Clean Sessions:</B><BR><FONT SIZE=1>delete sessions after X seconds unactive</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"site_cleansession\" VALUE=\"" . _SITE_CLEANSESSION . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>Session Generation String Length:</B><BR><FONT SIZE=1>the length of the session generation string</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"site_sessionlength\" VALUE=\"" . _SITE_SESSIONLENGTH . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>Admin Currency:</B><BR><FONT SIZE=1>currency displayed in the admin (e.g. $)</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"admin_currency\" VALUE=\"" . _ADMIN_CURRENCY . "\" SIZE=\"35\"></TD></TR>\n";
		}
		elseif($_GET["section"] == "cronjobs")
		{
			$text	.= "<TR><TD COLSPAN=\"2\"><U><B>Cronjob Settings</B></U></TD></TR>\n";
			$text	.= "<TR><TD><B>Enable Cronjobs:</B><BR><FONT SIZE=1>more information</A></FONT></TD><TD>Yes <INPUT TYPE=\"radio\" NAME=\"cronjobs\" VALUE=\"YES\"" . (_CRONJOBS == "YES" ? " checked" : "") . "> No <INPUT TYPE=\"radio\" NAME=\"cronjobs\" VALUE=\"NO\"" . (_CRONJOBS == "NO" ? " checked" : "") . "></TD></TR>\n";
			$text	.= "<TR><TD><B>Test Cronjobs: *</B><BR><FONT SIZE=1>Send e-mail from cronjob to see if it's running</FONT></TD><TD>Yes <INPUT TYPE=\"radio\" NAME=\"site_crontest\" VALUE=\"YES\"" . (_SITE_CRONTEST == "YES" ? " checked" : "") . "> No <INPUT TYPE=\"radio\" NAME=\"site_crontest\" VALUE=\"NO\"" . (_SITE_CRONTEST == "NO" ? " checked" : "") . "></TD></TR>\n";
			$text	.= "<TR><TD><B>Send E-Mails in Background: *</B><BR><FONT SIZE=1>Let the cronjob do the work for you!</FONT></TD><TD>Yes <INPUT TYPE=\"radio\" NAME=\"email_background\" VALUE=\"YES\"" . (_EMAIL_BACKGROUND == "YES" ? " checked" : "") . "> No <INPUT TYPE=\"radio\" NAME=\"email_background\" VALUE=\"NO\"" . (_EMAIL_BACKGROUND == "NO" ? " checked" : "") . "></TD></TR>\n";
			$text	.= "<TR><TD><B>Enable Automatic Backup: *</B><BR><FONT SIZE=\"1\">should the system make backups automatically</FONT></TD><TD>Yes <INPUT TYPE=\"radio\" NAME=\"cron_backup\" VALUE=\"YES\"" . (_CRON_BACKUP == "YES" ? " checked" : "") . "> No <INPUT TYPE=\"radio\" NAME=\"cron_backup\" VALUE=\"NO\"" . (_CRON_BACKUP == "NO" ? " checked" : "") . "></TD></TR>\n";
			$text	.= "<TR><TD><B>Automatic Backup Time: *</B><BR><FONT SIZE=\"1\">after how many hours make a new backup<BR>[time_in_hours] e.g. 24</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"cron_backuptime\" VALUE=\"" . _CRON_BACKUPTIME . "\" SIZE=\"35\"></TD></TR>\n";
			
			if(_ADDON_AP == 1)
			{
				$text	.= "<TR><TD><B>Retry failed e-Gold payment automatically: *</B></TD><TD>Yes <INPUT TYPE=\"radio\" NAME=\"cron_apretry\" VALUE=\"YES\"" . (_CRON_APRETRY == "YES" ? " checked" : "") . "> No <INPUT TYPE=\"radio\" NAME=\"cron_apretry\" VALUE=\"NO\"" . (_CRON_APRETRY == "NO" ? " checked" : "") . "></TD></TR>\n";
				$text	.= "<TR><TD><B>How many payment retries per session: *</B><BR><FONT SIZE=\"1\">[num_retries] e.g. 50</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"cron_apretrylimit\" VALUE=\"" . _CRON_APRETRYLIMIT . "\" SIZE=\"35\"></TD></TR>\n";
			}
			
			$text	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n";
			$text	.= "<TR><TD COLSPAN=\"2\" ALIGN=\"center\">* cronjobs have to be enabled for this!</TD></TR>\n";
		}
		elseif($_GET["section"] == "members")
		{
			$text	.= "<TR><TD COLSPAN=\"2\"><U><B>Member Settings</B></U></TD></TR>\n";
			$text	.= "<TR><TD><B>Sign-up Bonus:</B><BR><FONT SIZE=\"1\">member signup bonus</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"member_signupbonus\" VALUE=\"" . _MEMBER_SIGNUPBONUS . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>Login Time:</B><BR><FONT SIZE=\"1\">stay logged in for X seconds</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"member_logincookie\" VALUE=\"" . _MEMBER_LOGINCOOKIE . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>Delete Time:</B><BR><FONT SIZE=\"1\">delete member if not confirmed email<BR>within X seconds</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"member_destroytime\" VALUE=\"" . _MEMBER_DESTROYTIME . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>Paid E-Mail Standard Visit Time:</B><BR><FONT SIZE=1>get paid after X seconds in tracker</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"member_emailrefresh\" VALUE=\"" . _MEMBER_EMAILREFRESH . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>Paid Click Standard Visit Time:</B><BR><FONT SIZE=1>get paid after X seconds in tracker</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"member_clickrefresh\" VALUE=\"" . _MEMBER_CLICKREFRESH . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>Member Interests:</B><BR><FONT SIZE=1>seperate with \"<B>|</B>\"</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"member_interests\" VALUE=\"" . _MEMBER_INTERESTS . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>Additional Fields:</B><BR><FONT SIZE=1>seperate with \"<B>|</B>\"</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"member_additional\" VALUE=\"" . _MEMBER_ADDITIONAL . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>Member Vacation Time:</B><BR><FONT SIZE=1>time to be on vacation in seconds</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"member_vaclength\" VALUE=\"" . _MEMBER_VACLENGTH . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>Account History:</B><BR><FONT SIZE=1>how much actions must be logged<br>0 is unlimited</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"member_latestact\" VALUE=\"" . _MEMBER_LATESTACT . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>History Actions per Page:</B><BR><FONT SIZE=1>how many actions per page</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"member_latestactpp\" VALUE=\"" . _MEMBER_LATESTACTPP . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>Paidsignups per Page:</B><BR><FONT SIZE=1>how many paidsignups per page</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"member_signupspp\" VALUE=\"" . _MEMBER_SIGNUPSPP . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>Enable point earnings:</B></TD><TD>Yes <INPUT TYPE=\"radio\" NAME=\"member_points\" VALUE=\"YES\"" . (_MEMBER_POINTS == "YES" ? " checked" : "") . "> No <INPUT TYPE=\"radio\" NAME=\"member_points\" VALUE=\"NO\"" . (_MEMBER_POINTS == "NO" ? " checked" : "") . "></TD></TR>\n";
			$text	.= "<TR><TD><B>Activation:</B><BR><FONT SIZE=1>send activation e-mail on signup</FONT></TD><TD>Yes <INPUT TYPE=\"radio\" NAME=\"member_activation\" VALUE=\"YES\"" . (_MEMBER_ACTIVATION == "YES" ? " checked" : "") . "> No <INPUT TYPE=\"radio\" NAME=\"member_activation\" VALUE=\"NO\"" . (_MEMBER_ACTIVATION == "NO" ? " checked" : "") . "></TD></TR>\n";
			$text	.= "<TR><TD><B>Payout memo:</B><BR><FONT SIZE=1>memo for e-gold/paypal pay-outs<BR>(e.g. " . _SITE_TITLE . " payout!)</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"member_payoutmemo\" VALUE=\"" . _MEMBER_PAYOUTMEMO . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>Earnings Transfer:</B><BR><FONT SIZE=1>Allow members to transfer earnings to other<BR>members accounts<BR>\"<B>YES</B>\" or \"<B>NO</B>\"</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"member_transfer\" VALUE=\"" . _MEMBER_TRANSFER . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>Earnings Transfer Fee:</B><BR><FONT SIZE=1>Fee for an earning transfer<BR>[PERCENTAGE]% e.g. 5</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"member_transferfee\" VALUE=\"" . _MEMBER_TRANSFERFEE . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>Activation Key Length:</B></TD><TD><INPUT TYPE=\"text\" NAME=\"signup_hashlength\" VALUE=\"" . _SIGNUP_HASHLENGTH . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n";
			$text	.= "<TR><TD COLSPAN=\"2\"><U><B>Member Startup Page</B></U></TD></TR>\n";
			$text	.= "<TR><TD><B>Latest Paid Clicks:</B><BR><FONT SIZE=\"1\">how many latest clicks displayed</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"startpage_maxclicks\" VALUE=\"" . _STARTPAGE_MAXCLICKS . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>Latest Paid Emails:</B><BR><FONT SIZE=\"1\">how many latest emails displayed</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"startpage_maxemails\" VALUE=\"" . _STARTPAGE_MAXEMAILS . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n";
			$text	.= "<TR><TD COLSPAN=\"2\"><U><B>Inactive Members</B></U></TD></TR>\n";
			$text	.= "<TR><TD><B>Send e-mail:</B><BR><FONT SIZE=\"1\">send e-mail after X days inactive on site<BR>0 is disabled</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"member_sendmail\" VALUE=\"" . _MEMBER_SENDMAIL . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>Delete:</B><BR><FONT SIZE=\"1\">delete after X days inactive on site<BR>0 is disabled</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"member_delete\" VALUE=\"" . _MEMBER_DELETE . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>Deactivate:</B><BR><FONT SIZE=\"1\">deactivate after X days inactive on site<BR>0 is disabled</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"member_deactivate\" VALUE=\"" . _MEMBER_DEACTIVATE . "\" SIZE=\"35\"></TD></TR>\n";
		}
		elseif($_GET["section"] == "email")
		{
			$var3	= _EMAIL_MAILER == "smtp" ? "" : " checked";
			$var4	= _EMAIL_MAILER == "php" ? "" : " checked";
			
			$text	.= "<TR><TD COLSPAN=\"2\"><U><B>E-Mail Settings</B></U></TD></TR>\n";
			$text	.= "<TR><TD><B>Advertise e-mails:</B><BR><FONT SIZE=\"1\">e.g. advertise@</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"email_advertise\" VALUE=\"" . _EMAIL_ADVERTISE . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>Contact e-mails:</B><BR><FONT SIZE=\"1\">e.g. info@</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"email_contact\" VALUE=\"" . _EMAIL_CONTACT . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>Get Gold e-mails:</B><BR><FONT SIZE=\"1\">e.g. members@</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"email_getgold\" VALUE=\"" . _EMAIL_GETGOLD . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>Paid e-mails:</B><BR><FONT SIZE=\"1\">e.g. noreply@</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"email_paidmail\" VALUE=\"" . _EMAIL_PAIDMAIL . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>Signup process:</B><BR><FONT SIZE=\"1\">e.g. noreply@</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"email_signup\" VALUE=\"" . _EMAIL_SIGNUP . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD VALIGN=\"top\"><B>Mail Sender:</B></TD><TD><TABLE><TR><TD><INPUT TYPE=\"radio\" NAME=\"email_mailer\" VALUE=\"php\"$var3> PHP Mail()</TD><TD><INPUT TYPE=\"radio\" NAME=\"email_mailer\" VALUE=\"smtp\"$var4> SMTP</TD></TR>\n";
			$text	.= "<TR><TD>SMTP Server:</TD><TD><INPUT TYPE=\"text\" NAME=\"email_smtphost\" VALUE=\"" . _EMAIL_SMTPHOST . "\" SIZE=\"18\"></TD></TR>\n";
			$text	.= "<TR><TD>SMTP Username:</TD><TD><INPUT TYPE=\"text\" NAME=\"email_smtpuser\" VALUE=\"" . _EMAIL_SMTPUSER . "\" SIZE=\"18\"></TD></TR>\n";
			$text	.= "<TR><TD>SMTP Password:</TD><TD><INPUT TYPE=\"text\" NAME=\"email_smtppass\" VALUE=\"" . _EMAIL_SMTPPASS . "\" SIZE=\"18\"></TD></TR></TABLE></TD></TR>\n";
		}
		elseif($_GET["section"] == "anticheat")
		{
			$text	.= "<TR><TD COLSPAN=\"2\"><U><B>Anti-Cheat Settings</B></U></TD></TR>\n";
			$text	.= "<TR><TD><B>Block double e-mail addresses (leads):</B></TD><TD>Yes <INPUT TYPE=\"radio\" NAME=\"lead_checkemail\" VALUE=\"YES\"" . (_LEAD_CHECKEMAIL == "YES" ? " checked" : "") . "> No <INPUT TYPE=\"radio\" NAME=\"lead_checkemail\" VALUE=\"NO\"" . (_LEAD_CHECKEMAIL == "NO" ? " checked" : "") . "></TD></TR>\n";
			$text	.= "<TR><TD><B>Block double ip addresses (leads):</B></TD><TD>Yes <INPUT TYPE=\"radio\" NAME=\"lead_checkip\" VALUE=\"YES\"" . (_LEAD_CHECKIP == "YES" ? " checked" : "") . "> No <INPUT TYPE=\"radio\" NAME=\"lead_checkip\" VALUE=\"NO\"" . (_LEAD_CHECKIP == "NO" ? " checked" : "") . "></TD></TR>\n";
			$text	.= "<TR><TD><B>Block double e-mail addresses (signup/info):</B></TD><TD>Yes <INPUT TYPE=\"radio\" NAME=\"signup_checkemail\" VALUE=\"YES\"" . (_SIGNUP_CHECKEMAIL == "YES" ? " checked" : "") . "> No <INPUT TYPE=\"radio\" NAME=\"signup_checkemail\" VALUE=\"NO\"" . (_SIGNUP_CHECKEMAIL == "NO" ? " checked" : "") . "></TD></TR>\n";
			$text	.= "<TR><TD><B>Block double ip addresses (signup):</B></TD><TD>Yes <INPUT TYPE=\"radio\" NAME=\"signup_checkip\" VALUE=\"YES\"" . (_SIGNUP_CHECKIP == "YES" ? " checked" : "") . "> No <INPUT TYPE=\"radio\" NAME=\"signup_checkip\" VALUE=\"NO\"" . (_SIGNUP_CHECKIP == "NO" ? " checked" : "") . "></TD></TR>\n";
			$text	.= "<TR><TD><B>Block double payment accounts (signup/info):</B></TD><TD>Yes <INPUT TYPE=\"radio\" NAME=\"signup_checkbank\" VALUE=\"YES\"" . (_SIGNUP_CHECKBANK == "YES" ? " checked" : "") . "> No <INPUT TYPE=\"radio\" NAME=\"signup_checkbank\" VALUE=\"NO\"" . (_SIGNUP_CHECKBANK == "NO" ? " checked" : "") . "></TD></TR>\n";
			$text	.= "<TR><TD><B>Monitor double ip addresses (signup):</B><BR><FONT SIZE=1>This sends an e-mail for every double ip address on sign-up</FONT></TD><TD>Yes <INPUT TYPE=\"radio\" NAME=\"signup_monitorip\" VALUE=\"YES\"" . (_SIGNUP_MONITORIP == "YES" ? " checked" : "") . "> No <INPUT TYPE=\"radio\" NAME=\"signup_monitorip\" VALUE=\"NO\"" . (_SIGNUP_MONITORIP == "NO" ? " checked" : "") . "></TD></TR>\n";
			$text	.= "<TR><TD><B>Ad-timer cheat protection:</B><BR><FONT SIZE=1>Prevent user from opening more then one timed ad at a time</FONT></TD><TD>Yes <INPUT TYPE=\"radio\" NAME=\"member_trackwait\" VALUE=\"YES\"" . (_MEMBER_TRACKWAIT == "YES" ? " checked" : "") . "> No <INPUT TYPE=\"radio\" NAME=\"member_trackwait\" VALUE=\"NO\"" . (_MEMBER_TRACKWAIT == "NO" ? " checked" : "") . "></TD></TR>\n";
			$text	.= "<TR><TD><B>Track Cheater Protection:</B><BR><FONT SIZE=1>Should the system check for Track Cheaters?</FONT></TD><TD>Yes <INPUT TYPE=\"radio\" NAME=\"member_tcenable\" VALUE=\"YES\"" . (_MEMBER_TCENABLE == "YES" ? " checked" : "") . "> No <INPUT TYPE=\"radio\" NAME=\"member_tcenable\" VALUE=\"NO\"" . (_MEMBER_TCENABLE == "NO" ? " checked" : "") . "></TD></TR>\n";
			$text	.= "<TR><TD><B>Track Cheater Ratio:</B><BR><FONT SIZE=1>What may be the ratio between META-refresh and Timer?<BR>\"<B>PERCENTAGE</B>\"<BR>e.g. \"10\"%</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"member_tcratio\" VALUE=\"" . _MEMBER_TCRATIO . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>Remove Track Cheater:</B><BR><FONT SIZE=1>Should the system remove people that try to cheat<BR>with the tracker?<BR>\"Track Cheater Protection\" must be enabled</FONT></TD><TD>Yes <INPUT TYPE=\"radio\" NAME=\"member_tcdelete\" VALUE=\"YES\"" . (_MEMBER_TCDELETE == "YES" ? " checked" : "") . "> No <INPUT TYPE=\"radio\" NAME=\"member_tcdelete\" VALUE=\"NO\"" . (_MEMBER_TCDELETE == "NO" ? " checked" : "") . "></TD></TR>\n";
			$text	.= "<TR><TD><B>Deactivate Track Cheater:</B><BR><FONT SIZE=1>Should the system deactivate people that try to<BR>cheat with the tracker?<BR>\"Track Cheater Protection\" must be enabled</FONT></TD><TD>Yes <INPUT TYPE=\"radio\" NAME=\"member_tcinactivate\" VALUE=\"YES\"" . (_MEMBER_TCINACTIVATE == "YES" ? " checked" : "") . "> No <INPUT TYPE=\"radio\" NAME=\"member_tcinactivate\" VALUE=\"NO\"" . (_MEMBER_TCINACTIVATE == "NO" ? " checked" : "") . "></TD></TR>\n";
			$text	.= "<TR><TD><B>Debit Track Cheater:</B><BR><FONT SIZE=1>Should the system debit people that try to<BR>cheat with the tracker?<BR>\"Track Cheater Protection\" must be enabled<BR>\"<B>NUM CREDITS</B>\"<BR>0 is disabled<BR>e.g. \"0.05\"</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"member_tcdebit\" VALUE=\"" . _MEMBER_TCDEBIT . "\" SIZE=\"35\"></TD></TR>\n";
		}
		elseif($_GET["section"] == "referral")
		{
			$var1	= _REFERRAL_LOGGEDIN == 0 ? "" : " checked";
			$var2	= _REFERRAL_EARNED == 0 ? "" : " checked";
			
			$text	.= "<TR><TD COLSPAN=\"2\"><U><B>Referral Settings</B></U></TD></TR>\n";
			$text	.= "<TR><TD><B>Type:</B><BR><FONT SIZE=1>\"<B>PERCENTAGE</B>\" or \"<B>CREDITS</B>\"</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"referral_type\" VALUE=\"" . _REFERRAL_TYPE . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>Levels:</B><BR><FONT SIZE=1><B>- percentage</B>: \"NR OF LEVELS|LEVEL1|<BR>LEVEL2\" etc (e.g. 4|20|15|10|4)<BR><B>- credits</B>: \"NR OF LEVELS|LEVEL1|<BR>LEVEL2\" etc (e.g. 4|1|0.75|0.50|0.25)</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"referral_levels\" VALUE=\"" . _REFERRAL_LEVELS . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD><B>Move tier:</B><BR><FONT SIZE=1>move tier up when member unsubscribes<BR>\"<B>YES</B>\" or \"<B>NO</B>\"</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"referral_movetier\" VALUE=\"" . _REFERRAL_MOVETIER . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD VALIGN=\"top\"><B>Accepted when:</B><BR><FONT SIZE=1>only when type is \"credits\"</FONT></TD><TD><INPUT TYPE=\"checkbox\" CLASS=\"radio\" NAME=\"options_loggedin\"$var1> logged in <INPUT TYPE=\"text\" NAME=\"referral_loggedin\" VALUE=\"" . _REFERRAL_LOGGEDIN . "\" SIZE=\"10\"> times<BR>\n";
			$text	.= "<INPUT TYPE=\"checkbox\" CLASS=\"radio\" NAME=\"options_earned\"$var2> earned " . _ADMIN_CURRENCY . " <INPUT TYPE=\"text\" NAME=\"referral_earned\" VALUE=\"" . _REFERRAL_EARNED . "\" SIZE=\"10\"><BR>\n";
			$text	.= "within <INPUT TYPE=\"text\" NAME=\"referral_within\" VALUE=\"" . _REFERRAL_WITHIN . "\" SIZE=\"10\"> days</TD></TR>\n";
		}
		elseif($_GET["section"] == "other")
		{
			$text	.= "<TR><TD COLSPAN=\"2\"><U><B>Security</B></U></TD></TR>\n";
			$text	.= "<TR><TD><B>Generate Log Files:</B></TD><TD>Yes <INPUT TYPE=\"radio\" NAME=\"site_logs\" VALUE=\"YES\"" . (_SITE_LOGS == "YES" ? " checked" : "") . "> No <INPUT TYPE=\"radio\" NAME=\"site_logs\" VALUE=\"NO\"" . (_SITE_LOGS == "NO" ? " checked" : "") . "></TD></TR>\n";
			$text	.= "<TR><TD><B>Log payments higher then x $:</B><BR><FONT SIZE=1>generate log files has to be enabled<BR>[numdollars]</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"logs_apmin\" VALUE=\"" . _LOGS_APMIN . "\" SIZE=\"35\"></TD></TR>\n";
			$text	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n";
			$text	.= "<TR><TD COLSPAN=\"2\"><U><B>Error handling</B></U></TD></TR>\n";
			$text	.= "<TR><TD><B>Show or hide SQL errors:</B></TD><TD>Show <INPUT TYPE=\"radio\" NAME=\"error_handling\" VALUE=\"SHOW\"" . (_ERROR_HANDLING == "SHOW" ? " checked" : "") . "> Hide <INPUT TYPE=\"radio\" NAME=\"error_handling\" VALUE=\"HIDE\"" . (_ERROR_HANDLING == "HIDE" ? " checked" : "") . "></TD></TR>\n";
			$text	.= "<TR><TD><B>Send e-mail on error to:</B><BR><FONT SIZE=1>e.g. \"error@\"<br>leave empty to disable</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"error_email\" VALUE=\"" . _ERROR_EMAIL . "\" SIZE=\"35\"></TD></TR>\n";
		}
		elseif($_GET["section"] == "addons")
		{
			if($_GET["addon"] == "refcontest")
			{
				$text	.= "<TR><TD COLSPAN=\"2\"><U><B>Referral Contest Add-on</B></U></TD></TR>\n";
				$text	.= "<TR><TD><B>Referral Contest:</B></TD><TD>Enabled <INPUT TYPE=\"radio\" NAME=\"member_ct\" VALUE=\"YES\"" . (_MEMBER_CT == "YES" ? " checked" : "") . "> Disabled <INPUT TYPE=\"radio\" NAME=\"member_ct\" VALUE=\"NO\"" . (_MEMBER_CT == "NO" ? " checked" : "") . "></TD></TR>\n";
				$text	.= "<TR><TD><B>Top X:</B><BR><FONT SIZE=1>How far does the Top list go?</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"ct_top\" VALUE=\"" . _CT_TOP . "\" SIZE=\"35\"></TD></TR>\n";
				$text	.= "<TR><TD><B>Exclude from contest:</B><BR><FONT SIZE=1>Exclude users from contest<BR><B>Seperate id's with \"|\"</B><BR><B>e.g.: 1|2|49|392</B></FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"ct_exclude\" VALUE=\"" . _CT_EXCLUDE . "\" SIZE=\"35\"></TD></TR>\n";
				$text	.= "<TR><TD COLSPAN=\"2\">Click <A HREF=\"" . _ADMIN_URL . "/configuration.php?sid=" . $session->ID . "&action=resetrc\">here</A> to reset contest-stats.</TD></TR>\n";
			}
			elseif($_GET["addon"] == "apayment")
			{
				$text	.= "<TR><TD COLSPAN=\"2\"><U><B>Automatic Payments Add-on / automatic pay-outs</B></U></TD></TR>\n";
				$text	.= "<TR><TD><B>Automatic pay-out:</B><BR><FONT SIZE=1>should the system pay a member out<BR>after payment request</FONT></TD><TD>Yes <INPUT TYPE=\"radio\" NAME=\"member_ap\" VALUE=\"YES\"" . (_MEMBER_AP == "YES" ? " checked" : "") . "> No <INPUT TYPE=\"radio\" NAME=\"member_ap\" VALUE=\"NO\"" . (_MEMBER_AP == "NO" ? " checked" : "") . "></TD></TR>\n";
				$text	.= "<TR><TD><B>e-Gold account:</B><BR><FONT SIZE=1>e-gold account for automatic payments</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"ap_accountid\" VALUE=\"" . _AP_ACCOUNTID . "\" SIZE=\"35\"></TD></TR>\n";
				$text	.= "<TR><TD><B>e-Gold password:</B><BR><FONT SIZE=1>password for the entered e-gold account</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"ap_passphrase\" VALUE=\"" . base64_decode(_AP_PASSPHRASE) . "\" SIZE=\"35\"></TD></TR>\n";
				$text	.= "<TR><TD><B>What to do with balance limited accounts?</B></TD><TD><SELECT NAME=\"ap_wtdbalance\"><OPTION VALUE=\"1\"" . (_AP_WTDBALANCE == 1 ? " selected" : "") . ">Refund Payment</OPTION><OPTION VALUE=\"2\"" . (_AP_WTDBALANCE == 2 ? " selected" : "") . ">Delete Payment</OPTION><OPTION VALUE=\"3\"" . (_AP_WTDBALANCE == 3 ? " selected" : "") . ">Delete Payment and Member</OPTION><OPTION VALUE=\"4\"" . (_AP_WTDBALANCE == 4 ? " selected" : "") . ">Place into Pending Payments</OPTION></TD></TR>\n";
				$text	.= "<TR><TD><B>What to do with invalid e-gold accounts?</B></TD><TD><SELECT NAME=\"ap_wtdinvalid\"><OPTION VALUE=\"1\"" . (_AP_WTDINVALID == 1 ? " selected" : "") . ">Refund Payment</OPTION><OPTION VALUE=\"2\"" . (_AP_WTDINVALID == 2 ? " selected" : "") . ">Place into Pending Payments</OPTION></TD></TR>\n";
				$text	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n";
				$text	.= "<TR><TD COLSPAN=\"2\"><U><B>Automatic Payments Add-on / automatic advertising</B></U></TD></TR>\n";
				$text	.= "<TR><TD><B>Automatic advertising:</B><BR><FONT SIZE=1>should the system process payments</FONT></TD><TD>Yes <INPUT TYPE=\"radio\" NAME=\"ap_ads\" VALUE=\"1\"" . (_AP_ADS == 1 ? " checked" : "") . "> No <INPUT TYPE=\"radio\" NAME=\"ap_ads\" VALUE=\"0\"" . (_AP_ADS == 0 ? " checked" : "") . "></TD></TR>\n";
				$text	.= "<TR><TD><B>e-Gold account:</B><BR><FONT SIZE=1>e-gold account to receive e-gold payments</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"ap_acctegold\" VALUE=\"" . _AP_ACCTEGOLD . "\" SIZE=\"35\"></TD></TR>\n";
				$text	.= "<TR><TD><B>Moneybookers account:</B><BR><FONT SIZE=1>Moneybookers account to receive Moneybookers payments</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"ap_acctmoneybookers\" VALUE=\"" . _AP_ACCTMONEYBOOKERS . "\" SIZE=\"35\"></TD></TR>\n";
				$text	.= "<TR><TD><B>Paypal account:</B><BR><FONT SIZE=1>Paypal account to receive Paypal payments</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"ap_acctpaypal\" VALUE=\"" . _AP_ACCTPAYPAL . "\" SIZE=\"35\"></TD></TR>\n";
				$text	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n";
				$text	.= "<TR><TD COLSPAN=\"2\"><U><B>Automatic Payments Add-on / security hashes (advertising)</B></U></TD></TR>\n";
				$text	.= "<TR><TD><B>e-Gold HASH:</B><BR><FONT SIZE=1>click <A HREF=\"https://www.e-gold.com/acct/md5check.html\"><B>here</B></A> to generate</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"ap_hashegold\" VALUE=\"" . _AP_HASHEGOLD . "\" SIZE=\"35\"></TD></TR>\n";
				$text	.= "<TR><TD><B>Moneybookers HASH:</B><BR><FONT SIZE=1>enter the lostpassword answer to question 1</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"ap_hashmoneybookers\" VALUE=\"" . _AP_HASHMONEYBOOKERS . "\" SIZE=\"35\"></TD></TR>\n";
				$text	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n";
				$text	.= "<TR><TD COLSPAN=\"2\"><U><B>Automatic Payments Add-on / automatic deposit</B></U></TD></TR>\n";
				$text	.= "<TR><TD><B>Automatic deposit:</B><BR><FONT SIZE=1>should the system process deposits</FONT></TD><TD>Yes <INPUT TYPE=\"radio\" NAME=\"ap_deposit\" VALUE=\"YES\"" . (_AP_DEPOSIT == "YES" ? " checked" : "") . "> No <INPUT TYPE=\"radio\" NAME=\"ap_deposit\" VALUE=\"NO\"" . (_AP_DEPOSIT == "NO" ? " checked" : "") . "></TD></TR>\n";
				$text	.= "<TR><TD><B>e-Gold account:</B><BR><FONT SIZE=1>e-gold account to receive e-gold payments</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"ap_dacctegold\" VALUE=\"" . _AP_DACCTEGOLD . "\" SIZE=\"35\"></TD></TR>\n";
				$text	.= "<TR><TD><B>Moneybookers account:</B><BR><FONT SIZE=1>Moneybookers account to receive Moneybookers payments</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"ap_dacctmoneybookers\" VALUE=\"" . _AP_DACCTMONEYBOOKERS . "\" SIZE=\"35\"></TD></TR>\n";
				$text	.= "<TR><TD><B>Paypal account:</B><BR><FONT SIZE=1>Paypal account to receive Paypal payments</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"ap_dacctpaypal\" VALUE=\"" . _AP_DACCTPAYPAL . "\" SIZE=\"35\"></TD></TR>\n";
				$text	.= "<TR><TD><B>Deposit minimum:</B><BR><FONT SIZE=1>Minimum amount of cash to deposit</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"ap_dmin\" VALUE=\"" . _AP_DMIN . "\" SIZE=\"35\"></TD></TR>\n";
				$text	.= "<TR><TD><B>Deposit maximum:</B><BR><FONT SIZE=1>Maximum amount of cash to deposit</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"ap_dmax\" VALUE=\"" . _AP_DMAX . "\" SIZE=\"35\"></TD></TR>\n";
				$text	.= "<TR><TD><B>Deposit fee:</B><BR><FONT SIZE=1>Fee for a deposit<BR>[PERCENTAGE]% e.g. 5</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"ap_dfee\" VALUE=\"" . _AP_DFEE . "\" SIZE=\"35\"></TD></TR>\n";
				$text	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n";
				$text	.= "<TR><TD COLSPAN=\"2\"><U><B>Automatic Payments Add-on / security hashes (deposit)</B></U></TD></TR>\n";
				$text	.= "<TR><TD><B>e-Gold HASH:</B><BR><FONT SIZE=1>click <A HREF=\"https://www.e-gold.com/acct/md5check.html\"><B>here</B></A> to generate</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"ap_dhashegold\" VALUE=\"" . _AP_DHASHEGOLD . "\" SIZE=\"35\"></TD></TR>\n";
				$text	.= "<TR><TD><B>Moneybookers HASH:</B><BR><FONT SIZE=1>enter the lostpassword answer to question 1</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"ap_dhashmoneybookers\" VALUE=\"" . _AP_DHASHMONEYBOOKERS . "\" SIZE=\"35\"></TD></TR>\n";
			}
			elseif($_GET["addon"] == "bubble")
			{
				$text	.= "<TR><TD COLSPAN=\"2\"><U><B>Bubble Game Add-on</B></U></TD></TR>\n";
				$text	.= "<TR><TD><B>Bubble pay-out percentage:</B><BR><FONT SIZE=1>for example \"150\"%</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"bubble_percent\" VALUE=\"" . _BUBBLE_PERCENT . "\" SIZE=\"35\"></TD></TR>\n";
				$text	.= "<TR><TD><B>Minimum Spend:</B><BR><FONT SIZE=1>for example \"0.01\"</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"bubble_min\" VALUE=\"" . _BUBBLE_MIN . "\" SIZE=\"35\"></TD></TR>\n";
				$text	.= "<TR><TD><B>Maximum Spend:</B><BR><FONT SIZE=1>for example \"5.00\"</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"bubble_max\" VALUE=\"" . _BUBBLE_MAX . "\" SIZE=\"35\"></TD></TR>\n";
				$text	.= "<TR><TD><B>Reset in X hours:</B><BR><FONT SIZE=1>for example \"72\"</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"bubble_reset\" VALUE=\"" . _BUBBLE_RESET . "\" SIZE=\"35\"></TD></TR>\n";
				$text	.= "<TR><TD><B>Fee percentage:</B><BR><FONT SIZE=1>how many % goes to you?<BR>for example \"5%\"</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"bubble_fee\" VALUE=\"" . _BUBBLE_FEE . "\" SIZE=\"35\"></TD></TR>\n";
				$text	.= "<TR><TD><B>Highlight price:</B><BR><FONT SIZE=1>how many does highlight function costs?<BR>for example \"0.50\"</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"bubble_highlight\" VALUE=\"" . _BUBBLE_HIGHLIGHT . "\" SIZE=\"35\"></TD></TR>\n";
				$text	.= "<TR><TD><B>Totally Earned with Bubble:</B></TD><TD>" . number_format(_BUBBLE_EARNED, 2) . "</TD></TR>\n";
				$text	.= "<TR><TD COLSPAN=\"2\">Click <A HREF=\"" . _ADMIN_URL . "/configuration.php?sid=" . $session->ID . "&action=resetbubble\">here</A> to reset bubble game.</TD></TR>\n";
			}
			elseif($_GET["addon"] == "headstails")
			{
				$text	.= "<TR><TD COLSPAN=\"2\"><U><B>Heads or Tails Add-on</B></U></TD></TR>\n";
				$text	.= "<TR><TD><B>Win pay-out percentage:</B><BR><FONT SIZE=1>for example \"200\"%</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"ht_percent\" VALUE=\"" . _HT_PERCENT . "\" SIZE=\"35\"></TD></TR>\n";
				$text	.= "<TR><TD><B>Win chance percentage:</B><BR><FONT SIZE=1>for example \"50\"% (recommended)</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"ht_win\" VALUE=\"" . _HT_WIN . "\" SIZE=\"35\"></TD></TR>\n";
				$text	.= "<TR><TD><B>Minimum Bet:</B><BR><FONT SIZE=1>for example \"0.01\"</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"ht_min\" VALUE=\"" . _HT_MIN . "\" SIZE=\"35\"></TD></TR>\n";
				$text	.= "<TR><TD><B>Maximum Bet:</B><BR><FONT SIZE=1>for example \"5.00\"</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"ht_max\" VALUE=\"" . _HT_MAX . "\" SIZE=\"35\"></TD></TR>\n";
				$text	.= "<TR><TD><B>Fee percentage:</B><BR><FONT SIZE=1>how many % goes to the admin?<BR>for example \"5%\"</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"ht_fee\" VALUE=\"" . _HT_FEE . "\" SIZE=\"35\"></TD></TR>\n";
				$text	.= "<TR><TD VALIGN=\"top\"><B>Statistics:</B></TD><TD>Total wins: <B>" . _HT_WINS . "</B> - Total losses: <B>" . _HT_LOSSES . "</B><BR>Total money won: <B>" . _ADMIN_CURRENCY . " "  . number_format(_HT_MWINS, 2) . "</B> - Total money lost: <B>" . _ADMIN_CURRENCY . " "  . number_format(_HT_MLOSSES, 2) . "</B></TD></TR>\n";
			}
			elseif($_GET["addon"] == "scratch")
			{
				$losses	= _SCRATCH_PLAYS - _SCRATCH_WINS;
				
				$text	.= "<TR><TD COLSPAN=\"2\"><U><B>Scratch Game Add-on</B></U></TD></TR>\n";
				$text	.= "<TR><TD><B>Ticket buy price:</B><BR><FONT SIZE=1>for example \"1.00\"%</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"scratch_price\" VALUE=\"" . _SCRATCH_PRICE . "\" SIZE=\"35\"></TD></TR>\n";
				$text	.= "<TR><TD><B>Ticket win prize:</B><BR><FONT SIZE=1>for example \"1.25\"</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"scratch_prize\" VALUE=\"" . _SCRATCH_PRIZE . "\" SIZE=\"35\"></TD></TR>\n";
				$text	.= "<TR><TD><B>Largest ticket number:</B><BR><FONT SIZE=1>for example \"10\"</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"scratch_largest\" VALUE=\"" . _SCRATCH_LARGEST . "\" SIZE=\"35\"></TD></TR>\n";
				$text	.= "<TR><TD><B>Fee percentage:</B><BR><FONT SIZE=1>how many % goes to the admin?<BR>for example \"5%\"</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"scratch_fee\" VALUE=\"" . _SCRATCH_FEE . "\" SIZE=\"35\"></TD></TR>\n";
				$text	.= "<TR><TD VALIGN=\"top\"><B>Statistics:</B></TD><TD>Total wins: <B>" . _SCRATCH_WINS . "</B> - Total losses: <B>$losses</B></TD></TR>\n";
			}
			elseif($_GET["addon"] == "turing")
			{
				$text	.= "<TR><TD COLSPAN=\"2\"><U><B>Turing Add-on</B></U></TD></TR>\n";
				$text	.= "<TR><TD><B>Enable Turing Add-On:</B></FONT></TD><TD>Yes <INPUT TYPE=\"radio\" NAME=\"turing_enabled\" VALUE=\"YES\"" . (_TURING_ENABLED == "YES" ? " checked" : "") . "> No <INPUT TYPE=\"radio\" NAME=\"turing_enabled\" VALUE=\"NO\"" . (_TURING_ENABLED == "NO" ? " checked" : "") . "></TD></TR>\n";
				$text	.= "<TR><TD><B>How many numbers:</B><BR><FONT SIZE=1>for example \"4\"</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"turing_numbers\" VALUE=\"" . _TURING_NUMBERS . "\" SIZE=\"35\"></TD></TR>\n";
				$text	.= "<TR><TD><B>Draw random pixels:</B></TD><TD>Yes <INPUT TYPE=\"radio\" NAME=\"turing_pixels\" VALUE=\"YES\"" . (_TURING_PIXELS == "YES" ? " checked" : "") . "> No <INPUT TYPE=\"radio\" NAME=\"turing_pixels\" VALUE=\"NO\"" . (_TURING_PIXELS == "NO" ? " checked" : "") . "></TD></TR>\n";
				$text	.= "<TR><TD><B>Blur the image:</B></TD><TD>Yes <INPUT TYPE=\"radio\" NAME=\"turing_blur\" VALUE=\"YES\"" . (_TURING_BLUR == "YES" ? " checked" : "") . "> No <INPUT TYPE=\"radio\" NAME=\"turing_blur\" VALUE=\"NO\"" . (_TURING_BLUR == "NO" ? " checked" : "") . "></TD></TR>\n";
				$text	.= "<TR><TD VALIGN=\"top\"><B>Font type:</B><BR><FONT SIZE=1></FONT></TD><TD><TEXTAREA NAME=\"turing_font\" COLS=\"27\" ROWS=\"4\">" . _TURING_FONT . "</TEXTAREA></TD></TR>\n";
				$text	.= "<TR><TD VALIGN=\"top\"><B>Example:</B></TD><TD><IMG SRC=\"" . _SITE_URL . "/signup.php?action=turing\" BORDER=\"0\" ALT=\"Turing Number\"></TD></TR>\n";
			}
			else
			{
				$text	.= "<TR><TD ALIGN=\"center\">Please select an add-on:</TD></TR>\n";
				$text	.= "<TR><TD>&nbsp;</TD></TR>";
				
				$total	= 0;
				
				if(_ADDON_CT == 1)
				{
					$text	.= "<TR><TD ALIGN=\"center\"><A HREF=\"" . _ADMIN_URL . "/configuration.php?sid=" . $session->ID . "&section=addons&addon=refcontest\">Referral Contest</A></TD></TR>";
					
					$total++;
				}
				
				if(_ADDON_AP == 1)
				{
					$text	.= "<TR><TD ALIGN=\"center\"><A HREF=\"" . _ADMIN_URL . "/configuration.php?sid=" . $session->ID . "&section=addons&addon=apayment\">Automatic Payments</A></TD></TR>";
					
					$total++;
				}
				
				if(_ADDON_BUBBLE == 1)
				{
					$text	.= "<TR><TD ALIGN=\"center\"><A HREF=\"" . _ADMIN_URL . "/configuration.php?sid=" . $session->ID . "&section=addons&addon=bubble\">Bubble Game</A></TD></TR>";
					
					$total++;
				}
				
				if(_ADDON_HT == 1)
				{
					$text	.= "<TR><TD ALIGN=\"center\"><A HREF=\"" . _ADMIN_URL . "/configuration.php?sid=" . $session->ID . "&section=addons&addon=headstails\">Heads or Tails</A></TD></TR>";
					
					$total++;
				}
				
				if(_ADDON_SCRATCH == 1)
				{
					$text	.= "<TR><TD ALIGN=\"center\"><A HREF=\"" . _ADMIN_URL . "/configuration.php?sid=" . $session->ID . "&section=addons&addon=scratch\">Scratch Game</A></TD></TR>";
					
					$total++;
				}
				
				if(_ADDON_TURING == 1)
				{
					$text	.= "<TR><TD ALIGN=\"center\"><A HREF=\"" . _ADMIN_URL . "/configuration.php?sid=" . $session->ID . "&section=addons&addon=turing\">Turing Add-On</A></TD></TR>";
					
					$total++;
				}
				
				if($total == 0 && _ADDON_TF == 0)
				{
					$text	.= "<TR><TD ALIGN=\"center\">You have no add-ons installed at this moment. You can go to the Create Your GetPaid add-ons page by clicking .</TD></TR>";
				}
				elseif($total == 0 && _ADDON_TF == 1)
				{
					$text	.= "<TR><TD ALIGN=\"center\">There is no configuration for the installed add-ons. If you want to have more information about Create Your GetPaid's add-ons, you can go to the Create Your GetPaid add-ons page by clicking here.</TD></TR>";
				}
			}
		}
		else
		{
			$text	.= "<TR><TD COLSPAN=\"2\" ALIGN=\"center\">Please select a section:</TD></TR>\n";
			$text	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>";
			$text	.= "<TR><TD ALIGN=\"center\"><A HREF=\"" . _ADMIN_URL . "/configuration.php?sid=" . $session->ID . "&section=website\">Website Settings</A></TD><TD ALIGN=\"center\"><A HREF=\"" . _ADMIN_URL . "/configuration.php?sid=" . $session->ID . "&section=cronjobs\">Cron Job Settings</A></TD></TR>";
			$text	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>";
			$text	.= "<TR><TD ALIGN=\"center\"><A HREF=\"" . _ADMIN_URL . "/configuration.php?sid=" . $session->ID . "&section=members\">Member Settings</A></TD><TD ALIGN=\"center\"><A HREF=\"" . _ADMIN_URL . "/configuration.php?sid=" . $session->ID . "&section=email\">E-Mail Settings</A></TD></TR>";
			$text	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>";
			$text	.= "<TR><TD ALIGN=\"center\"><A HREF=\"" . _ADMIN_URL . "/configuration.php?sid=" . $session->ID . "&section=anticheat\">Anti-Cheat Settings</A></TD><TD ALIGN=\"center\"><A HREF=\"" . _ADMIN_URL . "/configuration.php?sid=" . $session->ID . "&section=referral\">Referral Settings</A></TD></TR>";
			$text	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>";
			$text	.= "<TR><TD ALIGN=\"center\"><A HREF=\"" . _ADMIN_URL . "/configuration.php?sid=" . $session->ID . "&section=other\">Other Settings</A></TD><TD ALIGN=\"center\"><A HREF=\"" . _ADMIN_URL . "/configuration.php?sid=" . $session->ID . "&section=addons\">Add-on Settings</A></TD></TR>";
		}
		
		if($_GET["section"] && !($_GET["section"] == "addons" && !$_GET["addon"]))
		{
			$text	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>";
			$text	.= "<TR><TD COLSPAN=\"2\" ALIGN=\"center\"><INPUT TYPE=\"submit\" VALUE=\"Save Settings\"></TD></TR>";
		}
		
		if($_GET["section"])
		{
			$text	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>";
			$text	.= "<TR><TD COLSPAN=\"2\" ALIGN=\"center\"><INPUT TYPE=\"button\" ONCLICK=\"history.go(-1)\" VALUE=\"Go Back\"></TD></TR>";
		}
		
		$text	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>";
		$text	.= "</TABLE>";
		$text	.= "</FORM>";
		
		$main->printText($text);
	}

?>                                                                                                                                                                                                        