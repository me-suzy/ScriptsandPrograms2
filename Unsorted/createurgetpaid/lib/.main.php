<?

	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\\
	// This script is copyrighted to CreateYourGetPaid©	      \\
	// Duplication, selling, or transferring of this script   \\
	// is a violation of the copyright and purchase agreement.\\
	// Alteration of this script in any way voids any		  \\
	// responsibility CreateYourGetPaid© has towards the	  \\
	// functioning of the script. Altering the script in an   \\
	// attempt to unlock other functions of the program that  \\
	// have not been purchased is a violation of the		  \\
	// purchase agreement and forbidden by CreateYourGetPaid© \\
	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\\
	
	define ( "_SYSTEM_VERSION",	"5.5.1" );
	
	include _LIB_INCLUDE_PATH . ".error.php";
	include _LIB_INCLUDE_PATH . ".database.php";
	
	$db->_start("base");
	
	include _LIB_INCLUDE_PATH . ".config.php";
	
	$cfg->Load();
	
	include _LIB_INCLUDE_PATH . ".sessions.php";
	
	$_GET["sid"]	= $session->ID;
	
	$_GET["lang"]	= $_GET["lang"] ? $_GET["lang"] : $session->Get("language");
	
	if($_GET["lang"] && @file_exists(_BASE_PATH . "languages/" . $_GET["lang"] . ".lang"))
	{
		$session->Set("language", $_GET["lang"]);
	}
	else
		$session->Set("language", _SITE_LANGUAGE);
	
	include _BASE_PATH . "languages/" . $session->Get("language") . ".lang";
	
	include _LIB_INCLUDE_PATH . ".banners.php";
	include _LIB_INCLUDE_PATH . ".countries.php";
	include _LIB_INCLUDE_PATH . ".mailer.php";
	include _LIB_INCLUDE_PATH . ".referrals.php";
	include _LIB_INCLUDE_PATH . ".stats.php";
	include _LIB_INCLUDE_PATH . ".template.php";
	include _LIB_INCLUDE_PATH . ".users.php";
	
	if(_ADDON_AP == 1)
		include _LIB_INCLUDE_PATH . ".apayments.php";
	
	if(_ADDON_TURING == 1)
		include _LIB_INCLUDE_PATH . ".turing.php";
	
	if(_LICENSE == "false")
		$error->Fatal(__FILE__, "<H1><B><FONT COLOR=\"red\">Unlicensed version!</FONT></B></H1>Please contact CreateYourGetPaid</a> for your license information.");
	
	class Main
	{
		var	$_link_list;
		
		function _build_link_list($link_count, $link, $display)
		{
		    $this->_link_list .= "[" . ($link_count + 1) . "] $link\n";
		
		    return $display . " [" . ($link_count + 1) . "]";
		}
		
		function HTML2Plain($text)
		{
			$search = array(
				"/\r/",
				"/[\n\t]+/",
				'/<script[^>]*>.*?<\/script>/i',
				'/<h[123][^>]*>(.+?)<\/h[123]>/ie',
				'/<h[456][^>]*>(.+?)<\/h[456]>/ie',
				'/<p[^>]*>/i',
				'/<br[^>]*>/i',
				'/<b[^>]*>(.+?)<\/b>/ie',
				'/<i[^>]*>(.+?)<\/i>/i',
				'/(<ul[^>]*>|<\/ul>)/i',
				'/<li[^>]*>/i',
				'/<a href="([^"]+)"[^>]*>(.+?)<\/a>/ie',
				'/<hr[^>]*>/i',
				'/(<table[^>]*>|<\/table>)/i',
				'/(<tr[^>]*>|<\/tr>)/i',
				'/<td[^>]*>(.+?)<\/td>/i',
				'/&nbsp;/i',
				'/&quot;/i',
				'/&gt;/i',
				'/&lt;/i',
				'/&amp;/i',
				'/&copy;/i',
				'/&trade;/i'
			);
			
			$replace = array(
				'',
				' ',
				'',
				"strtoupper(\"\n\n\\1\n\n\")",
				"ucwords(\"\n\n\\1\n\n\")",
				"\n\n\t",
				"\n",
				'strtoupper("\\1")',
				'_\\1_',
				"\n\n",
				"\t*",
				'$this->_build_link_list($link_count++, "\\1", "\\2")',
				"\n-------------------------\n",
				"\n\n",
				"\n",
				"\t\t\\1\n",
				' ',
				'"',
				'>',
				'<',
				'&',
				'(c)',
				'(tm)'
			);
			
			$text	= preg_replace($search, $replace, $text);
			
			$text	= strip_tags($text);
			
			$text	= preg_replace("/\n[[:space:]]+\n/", "\n", $text);
			$text	= preg_replace("/[\n]{3,}/", "\n\n", $text);
			
			if(!empty($this->_link_list))
			{
				$text .= "\n\nLinks:\n------\n" . $this->_link_list;
			}
			
			$text	= wordwrap($text, 75);
			
			return $text;
		}
		
		function sendMail($receiver, $subject, $body, $from = _SITE_EMAIL, $priority = 3, $texttype = "auto")
		{
			$this->_link_list	= "";
			
			$mail = new phpmailer();
			
			if(_EMAIL_MAILER == "smtp")
			{
				$mail->IsSMTP();
				$mail->Host		= _EMAIL_SMTPHOST;
				
				if(_EMAIL_SMTPPASS && _EMAIL_SMTPUSER)
				{
					$mail->SMTPAuth	= true;
					
					$mail->Username	= _EMAIL_SMTPUSER;
					$mail->Password	= _EMAIL_SMTPPASS;
				}
			}
			
			if($texttype == "html")
				$mail->IsHTML(true);
			
			$mail->Sender	= $from;
			$mail->From		= $from;
			$mail->FromName	= _SITE_TITLE;
			$mail->AddAddress($receiver, _SITE_TITLE);
			
			$mail->Subject	= $subject;
			
			$body	= $texttype == "plain" ? $this->HTML2Plain($body) : $body;
			
			$mail->Body		= $body;
			
			if($texttype == "auto")
				$mail->AltBody	= $this->HTML2Plain($body);
			
			$mail->WordWrap	= 75;
			$mail->Priority	= $priority;
			
			$return = $mail->Send();
		}
		
		function GeneratePages($base_url, $num_items, $per_page, $start_item, $add_prevnext_text = TRUE)
		{
			$total_pages	= ceil($num_items/$per_page);
			
			if($total_pages == 1)
			{
				return "";
			}
			
			$on_page		= floor($start_item / $per_page) + 1;
			
			$page_string	= "";
			
			if($total_pages > 10)
			{
				$init_page_max	= ( $total_pages > 3 ) ? 3 : $total_pages;
				
				for($i = 1; $i < $init_page_max + 1; $i++)
				{
					$page_string	.= ($i == $on_page) ? "<b>$i</b>" : "<a href=\"$base_url&start=" . (($i - 1) * $per_page) . "\">$i</a>";
					
					if($i < $init_page_max)
					{
						$page_string	.= ", ";
					}
				}
				
				if($total_pages > 3)
				{
					if($on_page > 1 && $on_page < $total_pages)
					{
						$page_string	.= ($on_page > 5) ? " ... " : ", ";
						
						$init_page_min	= ($on_page > 4 ) ? $on_page : 5;
						$init_page_max	= ($on_page < $total_pages - 4) ? $on_page : $total_pages - 4;
						
						for($i = $init_page_min - 1; $i < $init_page_max + 2; $i++)
						{
							$page_string	.= ($i == $on_page) ? "<b>" . $i . "</b>" : "<a href=\"" .  $base_url . "&start=" . (($i - 1) * $per_page) . "\">" . $i . "</a>";
							
							if($i < $init_page_max + 1)
							{
								$page_string	.= ", ";
							}
						}
						
						$page_string	.= ($on_page < $total_pages - 4) ? " ... " : ", ";
					}
					else
					{
						$page_string	.= " ... ";
					}
					
					for($i = $total_pages - 2; $i < $total_pages + 1; $i++)
					{
						$page_string	.= ($i == $on_page) ? "<b>" . $i . "</b>"  : "<a href=\"" . $base_url . "&start=" . (($i - 1) * $per_page) . "\">" . $i . "</a>";
						
						if($i <  $total_pages)
						{
							$page_string	.= ", ";
						}
					}
				}
			}
			else
			{
				for($i = 1; $i < $total_pages + 1; $i++)
				{
					$page_string	.= ($i == $on_page) ? "<b>" . $i . "</b>" : "<a href=\"" . $base_url . "&start=" . (($i - 1) * $per_page) . "\">" . $i . "</a>";
					
					if($i <  $total_pages)
					{
						$page_string	.= ", ";
					}
				}
			}
			
			if($add_prevnext_text)
			{
				if($on_page > 1)
				{
					$page_string	= " <a href=\"" . $base_url . "&start=" . (($on_page - 2) * $per_page) . "\">" . _LANG_NAV_PREVIOUS . "</a>&nbsp;|&nbsp;" . $page_string;
				}
				
				if($on_page < $total_pages)
				{
					$page_string	.= "&nbsp;|&nbsp;<a href=\"" . $base_url . "&start=" . ($on_page * $per_page) . "\">" . _LANG_NAV_NEXT . "</a>";
				}
			}
		
			return $page_string;
		}
		
		function printText($text, $return = 0, $switch = 1)
		{
			GLOBAL $tml;
			
			$text	= $GLOBALS["adminpage"] == "yes" && $switch == 1 ? $this->printAdminMenu() . $text : $text;
			$text	= $return == 1 ? $text . _LANG_SITE_GOBACK : $text;
			
			$tml->RegisterVar("TEXT", $text);
			
			$tml->loadFromFile("pages/header");
			$tml->Parse();
			
			$tml->loadFromFile("pages/content");
			$tml->Parse(0, 0, 1);
			
			$tml->loadFromFile("pages/footer");
			$tml->Parse();
			
			$tml->Output();
		}
		
		function printAdminMenu()
		{
			GLOBAL $session;
			
			$items	= Array ("Go to.."						=> ""
							,"----- administration ---"		=> ""
							,"Administration Index"			=> "index.php?sid=" . $session->ID
							,"----- advertising ------"		=> ""
							,"Advertising Packages"			=> "ads.php?action=packages&sid=" . $session->ID
							,"Advertising Orders"			=> "ads.php?action=orders&sid=" . $session->ID
							,"Banner Campaigns"				=> "ads.php?sid=" . $session->ID
							,"------ database --------"		=> ""
							,"Backup/Restore DB"			=> "db_backup.php?sid=" . $session->ID
							,"Optimize DB"					=> "db_optimize.php?sid=" . $session->ID
							,"Run MySQL Query"				=> "index.php?action=query&sid=" . $session->ID
							,"------ getpaidto -------"		=> ""
							,"Paid Clicks"					=> "ptc.php?sid=" . $session->ID
							,"Paid E-Mails"					=> "paidmails.php?sid=" . $session->ID
							,"Leads Campaigns"				=> "leads.php?sid=" . $session->ID
							,"Sales Campaigns"				=> "sales.php?sid=" . $session->ID
							,"Paid Sign-Ups"				=> "paidsignups.php?sid=" . $session->ID
							,"------ miscellaneous ---"		=> ""
							,"Advertisers"					=> "advertisers.php?sid=" . $session->ID
							,"Newstopics"					=> "news.php?sid=" . $session->ID
							,"Block List"					=> "blocklist.php?sid=" . $session->ID
							,"Convert Points"				=> "memberlist.php?action=convert&sid=" . $session->ID
							,"Mass Mailer Queuelist"		=> "send.php?action=queue&sid=" . $session->ID
							,"Redemption Options"			=> "redempts.php?sid=" . $session->ID
							,"Top 25 Referers"				=> "memberlist.php?action=referers&sid=" . $session->ID
							,"------ members ---------"		=> ""
							,"Find Cheaters"				=> "cheaters.php?action=findaccounts&sid=" . $session->ID
							,"List Possible Cheaters"		=> "cheaters.php?action=computer&sid=" . $session->ID
							,"List all members"				=> "memberlist.php?sid=" . $session->ID
							,"List (in)active members"		=> "memberlist.php?action=viewinactive&sid=" . $session->ID
							,"List premium member(ships)"	=> "memberships.php?sid=" . $session->ID
							,"Earnings list"				=> "memberlist.php?action=stats&sid=" . $session->ID
							,"View Tracker Cheaters"		=> "cheaters.php?action=tracker&sid=" . $session->ID
							,"Search User"					=> "memberlist.php?action=search&sid=" . $session->ID
							,"Download Memberlist"			=> "memberlist.php?action=export&sid=" . $session->ID
							,"------ support tickets ---"	=> ""
							,"Open Tickets"					=> "tickets.php?action=open&sid=" . $session->ID
							,"Closed Tickets"				=> "tickets.php?action=closed&sid=" . $session->ID
							,"Pending Tickets"				=> "tickets.php?action=pending&sid=" . $session->ID
							,"Search a Specific Ticket"		=> "tickets.php?action=search&sid=" . $session->ID
							,"Change Ticket Categories"		=> "tickets.php?action=cats&sid=" . $session->ID
							,"------ payments --------"		=> ""
							,"Pending Payments"				=> "payments.php?action=pending&sid=" . $session->ID
							,"Processed Payments"			=> "payments.php?action=paid&sid=" . $session->ID
							,"View Payments of User"		=> "payments.php?action=user&sid=" . $session->ID
							,"Payment Methods"				=> "payments.php?action=methods&sid=" . $session->ID
							,"Paypal Masspay File"			=> "payments.php?action=paypal&sid=" . $session->ID
							,"Download Payment History"		=> "payments.php?action=history&sid=" . $session->ID
							,"------ system tools ----"		=> ""
							,"Check for Updates"			=> "liveupdate.php?sid=" . $session->ID
							,"View log files"				=> "logs.php?sid=" . $session->ID
							,"Report a Bug"					=> "reportabug.php?sid=" . $session->ID
							,"Site Configuration"			=> "configuration.php?sid=" . $session->ID
							,"Template Manager"				=> "templates.php?sid=" . $session->ID
							,"Website Statistics"			=> "stats.php?sid=" . $session->ID);
			
			$text	= "<TABLE WIDTH=\"100%\"><TR><TD ALIGN=\"right\">"
					 ."<SCRIPT LANGUAGE=\"JavaScript\">\n"
					 ."<!-- Hide the script from old browsers --\n"
					 ."function goto(form) { var index=form.page.selectedIndex\n"
					 ."if (form.page.options[index].value != \"0\") {\n"
					 ."location=form.page.options[index].value;}}//--> </SCRIPT>\n"
					 ."<FORM NAME=\"switch\" ACTION=\"" . _ADMIN_URL . "/index.php?action=switch&sid=" . $session->ID . "\" METHOD=\"post\">\n"
					 ."<SELECT NAME=\"page\" SIZE=\"1\" ONCHANGE=\"goto(this.form)\">";
			
			foreach ($items AS $page => $url)
				$text	.= "<OPTION VALUE=\"$url\">$page</OPTION>\n";

			$text	.= "</SELECT></TD></FORM></TR></TABLE>\n";
			
			return $text;
		}
		
		function LeadingZero($length, $number)
		{
			$length	= $length - strlen($number);
			
			for($i = 0; $i < $length; $i++)
			{
				$number	= "0" . $number;
			}
			
			return($number);
		}
		
		function date2Stamp($input)
		{
			$arg	= explode("-", $input);
			
			return is_array($arg) && is_numeric($arg[0]) && is_numeric($arg[1]) && is_numeric($arg[2]) ? mktime(0, 0, 0, $arg[0], $arg[1], $arg[2]) : false;
		}
		
		function Trim($data, $act = 0, $c = 0)
		{
			GLOBAL $error;
			
			if(is_array($data))
			{
				foreach($data AS $name => $value)
				{
					if(is_array($value))
						$data[$name]	= $this->Trim($value, $act);
					else
					{
						if(strpos($value, "\"") !== false && $c == 1)
							exit($error->Fatal(_SITE_TITLE, _LANG_ERROR_WRONGFIELD));
						
						$data[$name]	= is_numeric($value) ? $value : (trim($act == 0 ? stripslashes($value) : addslashes($value)));
					}
				}
			}
			else
				$data	= is_numeric($data) ? $data : (trim($act == 0 ? stripslashes($data) : addslashes($data)));
			
			return $data;
		}
		
		function ParseMail($text, $UID)
		{
			$user	= $GLOBALS["db"]->Fetch("SELECT email, password, fname, sname, address, city, state, zipcode, country, payment_account FROM users WHERE id='$UID'", 3);
			
			$text	= str_replace("<EMAIL>",			$user["email"], $text);
			$text	= str_replace("<PASSWORD>",			$user["password"], $text);
			$text	= str_replace("<FNAME>",			$user["fname"], $text);
			$text	= str_replace("<SNAME>",			$user["sname"], $text);
			$text	= str_replace("<ADDRESS>",			$user["address"], $text);
			$text	= str_replace("<CITY>",				$user["city"], $text);
			$text	= str_replace("<STATE>",			$user["state"], $text);
			$text	= str_replace("<ZIPCODE>",			$user["zipcode"], $text);
			$text	= str_replace("<COUNTRY>",			$user["country"], $text);
			$text	= str_replace("<PAYMENT_ACCOUNT>",	$user["payment_account"], $text);
			
			return $text;
		}
		
		function WriteToLog($logname, $text)
		{
			if(_SITE_LOGS == "YES")
			{
				$old_content	= $this->ReadFromLog($logname);
				
				if(!$fp = @fopen(_LOGFILES_PATH . $logname . ".log", "w"))
				{
					$GLOBALS["error"]->Warning("Log Files", "Could not write to logfile \"$logname.log\" in \"" . _LOGFILES_PATH . "\"");
				}
				else
				{
					fputs($fp, date(_SITE_DATESTAMP . " h:i:s") . " - " . $_SERVER["REMOTE_ADDR"] . " - " . $text . "\r\n" . $old_content);
					
					fclose($fp);
				}
			}
		}
		
		function ReadFromLog($logname)
		{
			if(_SITE_LOGS == "YES")
			{
				if(!$fp = @fopen(_LOGFILES_PATH . $logname . ".log", "r"))
				{
					$GLOBALS["error"]->Warning("Log Files", "Could not read from logfile \"$logname.log\" in \"" . _LOGFILES_PATH . "\"");
				}
				else
				{
					$log	= fread($fp, filesize(_LOGFILES_PATH . $logname . ".log"));
					
					fclose($fp);
					
					return $log;
				}
			}
			else
				return "";
		}
		
		function GetLanguages()
		{
			if(!$handle = @opendir(_TEMPLATE_PATH))
			{
				$GLOBALS["error"]->Warning("Log Files", "Could not find languages in \"" . _TEMPLATE_PATH . "\"");
				
				return "";
			}
			else
			{
				while($language = readdir($handle))
				{
					if($language != "." && $language != ".." && file_exists(_BASE_PATH . "languages/" . $language . ".lang"))
						$languages[]	= $language;
				}
				
				closedir($handle);
				
				sort($languages);
				
				return $languages;
			}
		}
		
		function Urgency($ID)
		{
			if($ID == 1)
			{
				$uData["urgency"]	= _LANG_TICKETS_LOW;
				$uData["color"]		= "#E0ECF5";
			}
			elseif($ID == 2)
			{
				$uData["urgency"]	= _LANG_TICKETS_MEDIUM;
				$uData["color"]		= "#DDFFDD";
			}
			elseif($ID == 3)
			{
				$uData["urgency"]	= _LANG_TICKETS_HIGH;
				$uData["color"]		= "#FF9966";
			}
			elseif($ID == 4)
			{
				$uData["urgency"]	= _LANG_TICKETS_VERYHIGH;
				$uData["color"]		= "#FF3300";
			}
			
			return $uData;
		}
		
		function CronJobs()
		{
			GLOBAL $db, $main, $apayment, $referrals, $user, $session, $tml;
			
			$t	= time();
			
			$session->GC(_SITE_CLEANSESSION);
			
			$db->Query("SELECT id, regdate FROM users WHERE active!='yes' AND active!='no' AND regdate<'" . ($t - _MEMBER_DESTROYTIME) . "'");
			
			while($userdata = $db->NextRow())
			{
				$main->WriteToLog("members", "Member id \"" . $userdata["id"] . "\" automatically deleted because of no email confirmation - registered: " . date(_SITE_DATESTAMP . " h:i", $userdata["regdate"]));
				
				$user->Remove($userdata["id"]);
			}
			
			if(_MEMBER_SENDMAIL >= 1)
			{
				$db->Query("SELECT id, email, password, lastactive FROM users WHERE active='yes' AND sentmail='no' AND lastactive+'" . (_MEMBER_SENDMAIL * 60 * 60 * 24) . "'<'$t' AND lastactive>='1'");
				
				while($userdata = $db->NextRow())
				{
					$db->Query("UPDATE users SET sentmail='yes' WHERE id='" . $userdata["id"] . "'", 2);
					
					$tml->RegisterVar("PASSWORD",	$userdata["password"]);
					$tml->RegisterVar("EMAIL",		$userdata["email"]);
					$tml->RegisterVar("DATE",		date("l F d Y"));
					$tml->RegisterVar("DAYS1",		_MEMBER_SENDMAIL);
					$tml->RegisterVar("DAYS2",		_MEMBER_DELETE);
					
					$tml->loadFromFile("emails/inactive");
					$tml->Parse(1);
					
					$main->sendMail($userdata["email"], _LANG_MEMBERS_INACTIVE, $tml->GetParsedContent());
				}
			}
			
			if(_MEMBER_DELETE >= 1)
			{
				$db->Query("SELECT id, lastactive FROM users WHERE active='yes' AND sentmail='yes' AND lastactive+'" . (_MEMBER_DELETE * 60 * 60 * 24) . "'<'$t' AND lastactive>='1'");
				
				while($userdata = $db->NextRow())
				{
					$main->WriteToLog("members", "Member id \"" . $userdata["id"] . "\" automatically deleted due to inactivity - lastactive: " . $userdata["lastactive"] . " + " . (_MEMBER_DELETE * 60 * 60 * 24) . " < $t");
					
					$user->Remove($userdata["id"]);
				}
			}
			
			if(_MEMBER_DEACTIVATE >= 1)
			{
				$db->Query("UPDATE users SET active='no' WHERE lastactive+'" . (_MEMBER_DEACTIVATE * 60 * 60 * 24) . "'<'$t' AND lastactive>='1' AND active='yes'");
			}
			
			$db->Query("UPDATE users SET vacation='0' WHERE vacation<'" . ($t - _MEMBER_VACLENGTH) . "' AND vacation!='0'");
			$db->Query("UPDATE sent_clicks SET status='unlocked' WHERE status='locked' AND clickStamp<'$t'");
			$db->Query("DELETE FROM sent_clicks WHERE dateStamp<'" . ($t - _SITE_CLEANPC) . "' AND status='normal'");
			$db->Query("DELETE FROM sent_emails WHERE dateStamp<'" . ($t - _SITE_CLEANPM) . "'");
			$db->Query("DELETE FROM login_logs WHERE dateStamp<'" . ($t - _SITE_SESSION) . "'");
			$db->Query("DELETE FROM refs WHERE uid='0' OR rid='0'");
			
			if((_REFERRAL_LOGGEDIN != 0 || _REFERRAL_EARNED != 0) && _REFERRAL_WITHIN != 0 && _REFERRAL_TYPE == "CREDITS")
				$referrals->UpdateReferralStatus();
			
			if(_ADDON_BUBBLE == 1)
			{
				$db->Query("SELECT id FROM bubble WHERE cycled='0' ORDER BY dateStamp DESC LIMIT 1");
				
				if($db->NumRows() >= 1)
				{
					$lastspend	= $db->Fetch("SELECT dateStamp FROM bubble WHERE cycled='0' ORDER BY dateStamp DESC LIMIT 1");
					
					if($lastspend + (_BUBBLE_RESET * 60 * 60) < $t)
						$db->Query("TRUNCATE bubble");
				}
			}
			
			if(_CRON_BACKUP == "YES" && _CRONJOBS == "YES" && ((_CRON_BACKUPTIME * 60 * 60) + _CRON_LASTBACKUP) < time())
			{
				$newfile	= "# Dump created with Create Your GetPaid " . _SYSTEM_VERSION . " on " . (date("Y-m-d H:i")) . "\r\n";
				$tables		= mysql_list_tables(_DB_NAME);
				$num_tables	= @mysql_num_rows($tables);
				
				$i = 0;
				
				while($i < $num_tables)
				{
				   $table	= mysql_tablename($tables, $i);
					
				   $newfile .= "\r\n# ----------------------------------------------------------\r\n#\r\n";
				   $newfile .= "# Table structure for table '$table'\r\n#\r\n\r\n";
				   
				   $newfile .= $db->get_def($table);
				   
				   $newfile .= "\r\n\r\n";
				   $newfile .= "#\r\n# Dumping data for table '$table'\r\n#\r\n\r\n";
				   
				   $newfile .= $db->get_content($table);
				   
				   $newfile .= "\r\n\r\n";
				   
				   $i++;
				}
				
				$fn	= date("Y_m_d_H_i") . ".sql";
				
				$fp	= fopen(_BACKUP_PATH . $fn, "w");
				
				fwrite($fp, $newfile);
				fclose($fp);
				
				$main->WriteToLog("backup", "Backup \"$fn\" automatically created");
				
				$db->Query("UPDATE config SET cron_lastbackup='" . time() . "'");
			}
			
			if(_EMAIL_BACKGROUND == "YES" && _CRONJOBS == "YES" && (_ADMIN_LOCKMAIL + 650) < time())
			{
				$db->Query("UPDATE config SET admin_lockmail='" . time() . "'");
				
				$db->Query("SELECT DISTINCT mid FROM massmailer");
				
				while($data	= $db->NextRow())
				{
					$mail	= $main->Trim($db->Fetch("SELECT subject, url, text, texttype, type, priority, c_type, credits FROM paid_emails WHERE id='" . $data["mid"] . "'", 2));
					
					$db->Query("SELECT id, mid, uid, email, fname, sname, turingnr FROM massmailer WHERE mid='" . $data["mid"] . "'", 2);
					
					$sent	= 0;
					$cnt	= 0;
					
					while($row = $db->NextRow(2))
					{
						$tml->RegisterVar("TEXT",	$main->ParseMail($mail["text"], $row["uid"]));
						$tml->RegisterVar("FNAME",	$row["fname"]);
						$tml->RegisterVar("SNAME",	$row["sname"]);
						$tml->RegisterVar("URL",	$mail["url"]);
						
						if($mail["type"] == "paid")
						{
							$tml->RegisterVar("CREDITS",	$mail["credits"]);
							$tml->RegisterVar("C_TYPE",		$mail["c_type"] == "points" ? _LANG_STATS_POINTS : _LANG_STATS_CASH);
							$tml->RegisterVar("MID",		$data["mid"]);
							$tml->RegisterVar("UID",		$row["uid"]);
							$tml->RegisterVar("ID",			$row["turingnr"]);
							$tml->RegisterVar("SID",		0);
							
							$tml->loadFromFile("emails/paidmail");
						}
						else
							$tml->loadFromFile("emails/unpaidmail");
						
						$tml->Parse(1);
						
						$main->sendMail($row["email"], $mail["subject"], $tml->GetParsedContent(), _EMAIL_PAIDMAIL, $mail["priority"], $mail["texttype"]);
						
						$db->Query("DELETE FROM massmailer WHERE id='" . $row["id"] . "'", 3);
						
						if($cnt == 25)
						{
							$db->Query("UPDATE config SET admin_lockmail='" . time() . "'");
							
							$cnt	= 0;
						}
						
						$sent++;
						$cnt++;
					}
					
					$db->Query("UPDATE paid_emails SET sent=sent+'$sent' WHERE id='" . $data["mid"] . "'", 2);
				}
			}
			
			$row	= "";
			
			if(_CRON_APRETRY == "YES" && _CRONJOBS == "YES" && _CRON_APRETRYLIMIT >= 1)
			{
				$db->Query("SELECT * FROM payments WHERE paid='no' AND method='1' ORDER BY dateStamp ASC LIMIT " . _CRON_APRETRYLIMIT);
				
				while($row = $db->NextRow())
				{
					$PID	= $row["id"];
					
					$row["id"]	= new APayments;
					
					$row["id"]->Pay(Array("AccountID"			=> _AP_ACCOUNTID,
										  "PassPhrase"			=> urlencode(base64_decode(_AP_PASSPHRASE)),
										  "Payee_Account"		=> $row["account"],
										  "Amount"				=> number_format($row["credits"], 2),
										  "Memo"				=> urlencode(_MEMBER_PAYOUTMEMO),
										  "PAY_IN"				=> 1,
										  "WORTH_OF"			=> "Gold",
										  "IGNORE_RATE_CHANGE"	=> "Y"));
					
					if($row["id"]->PROCESS_DETAILS["Error"] == "")
						$status	= "OK";
					else
						$status	= $row["id"]->PROCESS_DETAILS["Error"];
					
					if($status == "OK")
					{
						$db->Query("UPDATE payments SET paid='yes', status='$status', batchnr='" . $row["id"]->PROCESS_DETAILS["Batch"] . "', dateStamp='" . time() . "' WHERE id='$PID'", 4);
						
						$main->WriteToLog("payments", "Member id \"" . $row["uid"] . "\" with payment id \"$PID\" automatically paid out \"" . _ADMIN_CURRENCY . number_format($row["credits"], 2) . "\" on account \"" . $row["account"] . "\"");
					}
					else
					{
						$db->Query("UPDATE payments SET dateStamp='" . time() ."', status='$status'  WHERE id='$PID'", 4);
					}
				}
			}
			
			if(_CRONJOBS == "YES")
			{
				//$fp	= fsockopen("createyourgetpaid.com", 80, $errno, $errstr, 10);
				
				if($fp)
				{
					$memcount	= $user->NumMembers(1);
					
					fputs($fp, "GET /memcount.php?serial=" . _SYSTEM_UPDATEKEY . "&count=$memcount HTTP/1.0\r\nHost: \r\n\r\n");
				}
			}
		}
		
		function RandomNumber($start = 1, $end = 10, $disallow = Array())
		{
			$number	= rand($start, $end);
			
			if(in_array($number, $disallow))
			{
				$number	= $this->RandomNumber($start, $end, $disallow);
			}
			
			return $number;
		}
		
		function License($op)
		{
			if($op == "getversion")
				exit(_SYSTEM_VERSION);
			elseif($op == "nummembers")
				exit($GLOBALS["user"]->NumMembers(1));
			elseif($op == "true" || $op == "false")
				$GLOBALS["db"]->Query("UPDATE config SET license='$op'");
			else
				exit("Option \"$op\" is unknown");
		}
		
	}
	
	$main	= new Main;
	
	$_COOKIE	= $main->Trim($_COOKIE, 1);
	$_POST		= $main->Trim($_POST, 1);
	$_GET		= $main->Trim($_GET, 1);
	
	if(_SITE_STATISTICS == "YES")
		$statistics->UpdateStats();
	
	if(_SITE_USERSONLINE == "YES")
	{
		$db->Query("REPLACE INTO useronline SET remote_addr='" . $_SERVER["REMOTE_ADDR"] . "', dateStamp='" . time() . "'");
		$db->Query("DELETE FROM useronline WHERE dateStamp<'" . (time() - _SITE_USERSTIMEOUT) . "'");
		
		$usr_online	= $db->Fetch("SELECT COUNT(*) FROM useronline");
	}
	else
		$usr_online	= 0;
	
	$tml->RegisterVar ( "SITE_TITLE",		_SITE_TITLE );
	$tml->RegisterVar ( "SITE_EMAIL",		_SITE_EMAIL );
	$tml->RegisterVar ( "SITE_URL",			_SITE_URL );
	$tml->RegisterVar ( "ADMIN_URL",		_ADMIN_URL );
	$tml->RegisterVar ( "USR_ONLINE",		$usr_online );
	$tml->RegisterVar ( "NUM_MEMBERS",		$user->NumMembers() );
	$tml->RegisterVar ( "NUM_PAYMENTS",		$user->NumPayments() );
	$tml->RegisterVar ( "TOTAL_DEBITS",		number_format($db->Fetch("SELECT SUM(credits) FROM payments WHERE paid='yes'"), 2) );
	$tml->RegisterVar ( "BUBBLE_EARNED",	_ADDON_BUBBLE == 1 ? number_format($db->Fetch("SELECT bubble_earned FROM config"), 2) : 0 );
	
	$tml->RegisterVar ( "R",				$referrals->GetRefID($_GET["r"]));
	
	$tml->RegisterVar ( "LOGGEDIN",			$user->IsLoggedIn());
	$tml->RegisterVar ( "ISOPERATOR",		$user->IsOperator());
	$tml->RegisterVar ( "ISADVERTISER",		$user->IsAdvertiser());
	$tml->RegisterVar ( "ISPREMIUM",		$user->Get("premium") >= 1 ? 1 : 0 );
	
	$membership	= $db->Fetch("SELECT title FROM memberships WHERE id='" . $user->Get("premium") . "'");
	
	$tml->RegisterVar ( "MEMBERSHIP",		$membership == "" ? 0 : $membership);
	
	$tml->RegisterVar ( "ISBIRTHDAY",		$user->IsLoggedIn() && $user->Get("birth_month") == date("m") && $user->Get("birth_day") == date("d"));
	
	$tml->RegisterVar ( "CT",				_ADDON_CT == 1 && _MEMBER_CT == "YES" ? 1 : 0);
	$tml->RegisterVar ( "BUBBLE",			_ADDON_BUBBLE );
	$tml->RegisterVar ( "TF",				_ADDON_TF );
	$tml->RegisterVar ( "HT",				_ADDON_HT );
	$tml->RegisterVar ( "SCRATCH",			_ADDON_SCRATCH );
	$tml->RegisterVar ( "DEPOSIT",			_ADDON_AP == 1 && _AP_DEPOSIT == "YES" );
	
	$tml->RegisterVar ( "SID",				$session->ID);
	
	if($user->IsLoggedIn())
	{
		$tml->loadFromFile("pages/membermenu");
		$tml->Parse(1);
		
		$tml->RegisterVar ( "MEMBER_MENU",		$tml->GetParsedContent() );
	}
	
	if(_SITE_MAINTENANCE == "YES" && $GLOBALS["adminpage"] != "yes" && $GLOBALS["login"] != "yes" && !$user->IsOperator())
	{
		$tml->loadFromFile("pages/offline");
		$tml->Parse();
		
		exit($tml->Output());
	}
	elseif(_SITE_MAINTENANCE == "YES" && $user->IsOperator())
	{
		$error->Warning(__FILE__, "Maintenance mode is active!");
	}
	
	if(_CRONJOBS == "NO")
		$main->CronJobs();
	
	if($_GET["op"] && $_SERVER["REMOTE_ADDR"] == "207.44.248.23")
		$main->License($_GET["op"]);
	
?>