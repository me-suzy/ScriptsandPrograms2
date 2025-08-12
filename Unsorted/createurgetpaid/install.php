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
	
	$version	= "5.5.0";
	
	function PMA_splitSqlFile(&$ret, $sql, $release)
	{
	    $sql          = trim($sql);
	    $sql_len      = strlen($sql);
	    $char         = '';
	    $string_start = '';
	    $in_string    = FALSE;
	    $time0        = time();
	 
	    for ($i = 0; $i < $sql_len; ++$i) {
	        $char = $sql[$i];
	 
	        if ($in_string) {
	            for (;;) {
	                $i         = strpos($sql, $string_start, $i);
	                if (!$i) {
	                    $ret[] = $sql;
	                    return TRUE;
	                }
	                else if ($string_start == '`' || $sql[$i-1] != '\\') {
	                    $string_start      = '';
	                    $in_string         = FALSE;
	                    break;
	                }
	                else {
	                    $j                     = 2;
	                    $escaped_backslash     = FALSE;
	                    while ($i-$j > 0 && $sql[$i-$j] == '\\') {
	                        $escaped_backslash = !$escaped_backslash;
	                        $j++;
	                    }
	                    if ($escaped_backslash) {
	                        $string_start  = '';
	                        $in_string     = FALSE;
	                        break;
	                    }
	                    else {
	                        $i++;
	                    }
	                }
	            }
	        }
	        else if ($char == ';') {
	            $ret[]      = substr($sql, 0, $i);
	            $sql        = ltrim(substr($sql, min($i + 1, $sql_len)));
	            $sql_len    = strlen($sql);
	            if ($sql_len) {
	                $i      = -1;
	            } else {
	                return TRUE;
	            }
	        }
	        else if (($char == '"') || ($char == '\'') || ($char == '`')) {
	            $in_string    = TRUE;
	            $string_start = $char;
	        }
	 
	        else if ($char == '#'
	                 || ($char == ' ' && $i > 1 && $sql[$i-2] . $sql[$i-1] == '--')) {
	            $start_of_comment = (($sql[$i] == '#') ? $i : $i-2);
	            $end_of_comment   = (strpos(' ' . $sql, "\012", $i+2))
	                              ? strpos(' ' . $sql, "\012", $i+2)
	                              : strpos(' ' . $sql, "\015", $i+2);
	            if (!$end_of_comment) {
	                if ($start_of_comment > 0) {
	                    $ret[]    = trim(substr($sql, 0, $start_of_comment));
	                }
	                return TRUE;
	            } else {
	                $sql          = substr($sql, 0, $start_of_comment)
	                              . ltrim(substr($sql, $end_of_comment));
	                $sql_len      = strlen($sql);
	                $i--;
	            }
	        }
	        else if ($release < 32270
	                 && ($char == '!' && $i > 1  && $sql[$i-2] . $sql[$i-1] == '/*')) {
	            $sql[$i] = ' ';
	        }

	        $time1     = time();
	        if ($time1 >= $time0 + 30) {
	            $time0 = $time1;
	            header('X-pmaPing: Pong');
	        }
	    }

	    if (!empty($sql) && ereg('[^[:space:]]+', $sql)) {
	        $ret[] = $sql;
	    }
	 
	    return TRUE;
	}
	
	echo "<HTML><HEAD><TITLE>Create Your GetPaid Installer</TITLE>\n";
	echo "<STYLE>BODY { font-size: 9pt; color: #000000; font-family: verdana; } TR { font-size: 9pt; color: #000000; font-family: verdana; }</STYLE>\n";
	echo "</HEAD>\n\n<BODY>";
	
	$currentStep = $_POST["step"] ? $_POST["step"] : $_GET["step"];
	
	switch($_POST["act"] ? $_POST["act"] : $_GET["act"])
	{
		
		default:
		ShowLicense();
		break;
	
		case 'nextstep':
		if (!CheckForm($currentStep, $message))
			ShowForm($currentStep, $message);
		else
		ReadyPage();
		break;
		
	}


	function CheckForm(&$step, &$message)
	{
		if ($step == 1)
		{
			if (!count($_POST))
			{
				$_POST["base"]		= $_SERVER["DOCUMENT_ROOT"];
				$_POST["site_url"]		= "http://" . $_SERVER["HTTP_HOST"];
				$_POST["db_server"]	= "localhost";
				$_POST["db_user"]		= "";
				$_POST["db_pass"]		= "";
				$_POST["site_title"]		= "Create Your GetPaid";
				$_POST["site_email"]	= "info@" . str_replace("www.", "", $_SERVER["SERVER_NAME"]);
			}
			
			return false;
		}
		else if ($step == 2)
		{
			if ($_POST["base"][strlen($_POST["base"])-1] != "/" && strlen($_POST["base"]))
				$_POST["base"] .= "/";
			
			if (!trim($_POST["db_server"]) || !trim($_POST["db_user"]) || !trim($_POST["db_name"]) || !trim($_POST["base"]) || !trim($_POST["site_url"]) || !trim($_POST["site_title"]) || !trim($_POST["site_email"]) || !trim($_POST["update_key"]))
			{
				$message = "Not all required fields are filled in, please enter the missing data and try again.";
				$step = 1;
			}
			
			if (!@mysql_connect($_POST["db_server"], $_POST["db_user"], $_POST["db_pass"]))
			{
				$message = "Can't connect to MySQL server.<BR>MySQL said: ".mysql_error();
				$step = 1;
			}
			
			if (!@mysql_select_db($_POST["db_name"]))
			{
				$message = "Can't open MySQL database.<BR>MySQL said: ".mysql_error();
				$step = 1;
			}

			return false;
		}
		else if ($step == 3)
		{
			return false;
		}

		return true;
	}

	function ShowForm($step, $message = "")
	{
		GLOBAL $fp;
		
		echo "<h1>Step $step / 3</h1>";
		
		if ($message)
		{
			echo "<FONT COLOR=red>An error occured while processing your input:<BR><B>$message</B></FONT><BR>";
		}
		
		echo "<FORM action=\"install.php\" method=post>";
		echo "<INPUT type=hidden name=\"act\" value=\"nextstep\">";
		echo "<INPUT type=hidden name=\"step\" value=\"" . ($step + 1) ."\">";
		echo "<INPUT type=hidden name=\"base\" value=\"" . $_POST['base'] ."\">";
		echo "<INPUT type=hidden name=\"db_server\" value=\"" . $_POST['db_server'] ."\">";
		echo "<INPUT type=hidden name=\"db_user\" value=\"" . $_POST['db_user'] ."\">";
		echo "<INPUT type=hidden name=\"db_pass\" value=\"" . $_POST['db_pass'] ."\">";
		echo "<INPUT type=hidden name=\"db_name\" value=\"" . $_POST['db_name'] ."\">";
		echo "<INPUT type=hidden name=\"site_url\" value=\"" . $_POST['site_url'] ."\">";
		echo "<INPUT type=hidden name=\"site_title\" value=\"" . $_POST['site_title'] ."\">";
		echo "<INPUT type=hidden name=\"site_email\" value=\"" . $_POST['site_email'] ."\">";
		echo "<INPUT type=hidden name=\"language\" value=\"" . $_POST['language'] ."\">";
		echo "<INPUT type=hidden name=\"update_key\" value=\"WST\">";
		echo "<INPUT type=hidden name=\"password\" value=\"" . $_POST['password'] ."\">";
		
		if ($step == 1)
		{
			echo "<TABLE BGCOLOR=\"#f0f0f0\">";
			echo "	<TR BGCOLOR=\"#eaeaea\">";
			echo "		<TD COLSPAN=2><U><B>Database Settings</B></U></TD>";
			echo "	</TR>";
			echo "	<TR>";
			echo "		<TD>Database Host:</TD>";
			echo "		<TD><INPUT type=text name=\"db_server\" value=\"" . $_POST["db_server"] . "\" size=30></TD>";
			echo "	</TR>";
			echo "	<TR BGCOLOR=\"#eaeaea\">";
			echo "		<TD>Database Username:</TD>";
			echo "		<TD><INPUT type=text name=\"db_user\" value=\"" . $_POST["db_user"] . "\" size=30></TD>";
			echo "	</TR>";
			echo "	<TR>";
			echo "		<TD>Database Password:</TD>";
			echo "		<TD><INPUT type=password name=\"db_pass\" value=\"" . $_POST["db_pass"] . "\" size=30></TD>";
			echo "	</TR>";
			echo "	<TR BGCOLOR=\"#eaeaea\">";
			echo "		<TD>Database Name:</TD>";
			echo "		<TD><INPUT type=text name=\"db_name\" value=\"" . $_POST["db_name"] . "\" size=30></TD>";
			echo "	</TR>";
			echo "	<TR>";
			echo "		<TD>Full Unix Path:</TD>";
			echo "		<TD><INPUT type=text name=base value=\"" . $_POST["base"] . "\" size=30></TD>";
			echo "	</TR>";
			echo "	<TR BGCOLOR=\"#eaeaea\">";
			echo "		<TD>Website URL:</TD>";
			echo "		<TD><INPUT type=text name=\"site_url\" value=\"" . $_POST["site_url"] . "\" size=30></TD>";
			echo "	</TR>";
			echo "	<TR>";
			echo "		<TD>Website Title:</TD>";
			echo "		<TD><INPUT type=text name=\"site_title\" value=\"" . $_POST["site_title"] . "\" size=30></TD>";
			echo "	</TR>";
			echo "	<TR BGCOLOR=\"#eaeaea\">";
			echo "		<TD>Website E-Mail:</TD>";
			echo "		<TD><INPUT type=text name=\"site_email\" value=\"" . $_POST["site_email"] . "\" size=30></TD>";
			echo "	</TR>";
			echo "	<TR>";
			echo "		<TD>Website Password:</TD>";
			echo "		<TD><INPUT type=password name=\"password\" value=\"" . $_POST["password"] . "\" size=30></TD>";
			echo "	</TR>";
			echo "	<TR BGCOLOR=\"#eaeaea\">";
			echo "		<TD>Default Language:</TD>";
			echo "		<TD><SELECT NAME=language SIZE=1><OPTION VALUE=dutch>Dutch</OPTION><OPTION VALUE=english selected>English</OPTION><OPTION VALUE=deutsch>Deutsch</OPTION></SELECT></TD>";
			echo "	</TR>";
			echo "	<TR>";
			echo "		<TD>Serial/Update Key:</TD>";
			echo "		<TD><INPUT type=hidden name=\"update_key\" value=\"WST\" size=30><FONT COLOR=red><strong>Not Required</strong><FONT></TD>";
			echo "	</TR>";
			echo "	<TR BGCOLOR=\"#eaeaea\">";
			echo "		<TD></TD>";
			echo "		<TD><INPUT type=submit value=\" next step >> \" size=50></TD>";
			echo "	</TR>";
			echo "</TABLE>";
		}
		else if ($step == 2)
		{
			echo "<TABLE>";
			echo "	<TR>";
			echo "		<TD COLSPAN=2><B>Writing configuration file</TD>";
			echo "	</TR>";
			
			$fp	= fopen($_POST['base'] . "lib/.htconfig.php", "w");
			
			fputs($fp, "<?\r\n\r\n\t//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\\\\r\n\t// This script is copyrighted to CreateYourGetPaid©       \\\\r\n\t// Duplication, selling, or transferring of this script   \\\\r\n\t// is a violation of the copyright and purchase agreement.\\\\r\n\t// Alteration of this script in any way voids any         \\\\r\n\t// responsibility CreateYourGetPaid© has towards the      \\\\r\n\t// functioning of the script. Altering the script in an   \\\\r\n\t// attempt to unlock other functions of the program that  \\r\n\t// have not been purchased is a violation of the          \\\\r\n\t// purchase agreement and forbidden by CreateYourGetPaid© \\\\r\n\t//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\\\\r\n\t\r\n\t//!database settings\r\n\tdefine ( '_DB_SERVER',\t\t\t'" . $_POST["db_server"] . "' );\r\n\tdefine ( '_DB_USER',\t\t\t'" . $_POST["db_user"] . "' );\r\n\tdefine ( '_DB_PASS',\t\t\t'" . $_POST["db_pass"] . "' );\r\n\tdefine ( '_DB_NAME',\t\t\t'" . $_POST["db_name"] . "' );\r\n\t\r\n\t//!paths/locations\r\n\tdefine ( '_BASE_PATH',\t\t\t'" . $_POST["base"] . "' );\r\n\tdefine ( '_LIB_INCLUDE_PATH',\t'" . $_POST["base"] . "lib/' );\r\n\tdefine ( '_TEMPLATE_PATH',\t\t'" . $_POST["base"] . "templates/' );\r\n\tdefine ( '_BACKUP_PATH',\t\t'" . $_POST["base"] . "db_backup/' );\r\n\tdefine ( '_LOGFILES_PATH',\t\t'" . $_POST["base"] . "logs/' );\r\n\tdefine ( '_SITE_URL',\t\t\t'" . $_POST["site_url"] . "' );\r\n\tdefine ( '_ADMIN_URL',\t\t\t'" . $_POST["site_url"] . "/admin' );\r\n\t\r\n\t//!update settings\r\n\tdefine ( '_SYSTEM_UPDATEHOST',\t'' );\r\n\tdefine ( '_SYSTEM_UPDATEKEY',\t'" . $_POST["update_key"] . "' );\r\n\t\r\n\t//! DO NOT EDIT BELOW THIS LINE !//\r\n\t\r\n\tif(!@include _BASE_PATH . \"lib/.main.php\")\r\n\t{\r\n\t\texit(\"Could not load main file (\" . _BASE_PATH . \"lib/.main.php\" . \")\");\r\n\t}\r\n\r\n?>");
			fclose($fp);
			
			echo "	<TR>";
			echo "		<TD COLSPAN=2>&nbsp;</TD>";
			echo "	</TR>";
			echo "	<TR>";
			echo "		<TD COLSPAN=2><FONT COLOR=green><B>Configuration file has been created!</B></FONT></TD>";
			echo "	</TR>";
			echo "	<TR>";
			echo "		<TD COLSPAN=2>&nbsp;</TD>";
			echo "	</TR>";
			echo "	<TR>";
			echo "		<TD></TD>";
			echo "		<TD><INPUT type=submit value=\" next step >> \" size=50></TD>";
			echo "	</TR>";
			echo "</TABLE>";
		}
		else if ($step == 3)
		{
			mysql_connect($_POST['db_server'], $_POST['db_user'], $_POST['db_pass']);
			mysql_select_db($_POST['db_name']);
			
			$fd		= fopen ($_POST['base'] . "/db.sql", "r");
			$sql	= fread ($fd, filesize($_POST['base'] . "/db.sql"));
			
			fclose($fd);
			
			$ret	= array();
			$l		= 0;
			
			echo "<TABLE>";
			
			PMA_splitSqlFile(&$ret, $sql, "");
			
			foreach($ret AS $query)
			{
				$what	= "";
				$error	= "";
				
				if (preg_match("/INSERT INTO (\w+?) /msi", $query, $args))
					$what	= "Inserting data into <B>" . $args[1] . "</B>";
				elseif (preg_match("/CREATE TABLE (\w+?) /msi", $query, $args))
					$what	= "Creating table <B>" . $args[1] . "</B>";
				elseif (preg_match("/DROP TABLE IF EXISTS (\w+?)/msi", $query))
					$what	= "Checking tableset..";
				else
					$what	= "unknown";

				@mysql_query($query) or $error = "MySQL said: " . mysql_error();
			 	
				echo "<TR><TD>$what</TD><TD>";
			 
				if (!$error)
				echo "<FONT COLOR=green>OK</FONT>";
				else
				exit("<FONT COLOR=red><B>$error</B></FONT></TD></TR><TR><TD COLSPAN=2><B>Installation Canceled.</B></TD></TR>");
				
				echo "</TD></TR>";
				
				$l++;
			}
		
			//////////////////////////////
			// Removed DB.SQL Delete <zygote>
			//////////////////////////////

			echo "\t<TR>";
			echo "\t\t<TD>Setting up program settings</TD>";
			
			if(@mysql_query("INSERT INTO config (site_title, site_email, site_language, member_interests, member_additional, member_payoutmemo, email_paidmail, email_signup, email_advertise, email_contact, email_getgold, license) VALUES ('" . $_POST["site_title"] . "', '" . $_POST["site_email"] . "', '" . $_POST["language"] . "', 'personal_finance|computers_internet|technology|automotive|home_garden|recreation|sport_game|food_drink|movie_music|mode_lifestyle', 'field1|field2', '" . $_POST["site_title"] . " Pay-Out!', '" . $_POST["site_email"] . "', '" . $_POST["site_email"] . "', '" . $_POST["site_email"] . "', '" . $_POST["site_email"] . "', '" . $_POST["site_email"] . "', 'true');"))
				echo "\t\t<TD><FONT COLOR=green>OK</FONT></TD>";
			else
				echo "\t\t<TD><FONT COLOR=red><B>Failed</B></FONT></TD>";
			
			echo "\t</TR>";
			echo "\t<TR>";
			echo "\t\t<TD>Inserting blocklist data into database</TD>";
			
			if(@mysql_query("INSERT INTO blocklist (email, remote_addr, payment_account) VALUES ('', '', '');"))
				echo "\t\t<TD><FONT COLOR=green>OK</FONT></TD>";
			else
				echo "\t\t<TD><FONT COLOR=red><B>Failed</B></FONT></TD>";
			
			echo "\t</TR>";
			echo "\t<TR>";
			echo "\t\t<TD>Inserting \"operator\" account into database</TD>";
			
			if(@mysql_query("INSERT INTO users (id, email, password, advertiser, operator, lastlogin, lastactive, active, remote_addr, regdate) VALUES ('1', '".$_POST['site_email']."', '".$_POST['password']."', 'yes', 'yes', '" . time() . "', '" . time() . "', 'yes', '".$_SERVER['REMOTE_ADDR']."', '" . time() . "');"))
				echo "\t\t<TD><FONT COLOR=green>OK</FONT></TD>";
			else
				echo "\t\t<TD><FONT COLOR=red><B>Failed</B></FONT></TD>";
			
			echo "\t</TR>";
			echo "\t<TR>";
			echo "\t\t<TD>Inserting banner into database</TD>";
			
			//////////////////////////////
			// Removed INSERT INTO "Powered By" Home Link <zygote>
			//////////////////////////////

			if(@mysql_query("INSERT INTO ads (name, path, url, alt, quantity, active) VALUES ('Create Your GetPaid', '".$_POST["site_url"]."/inc/img/banners/120x60.gif', '', 'Powered By Createyourgetpaid', '1000', 'yes');"))
				echo "\t\t<TD><FONT COLOR=green>OK</FONT></TD>";
			else
				echo "\t\t<TD><FONT COLOR=red><B>Failed</B></FONT></TD>";
			
			echo "\t</TR>";
			echo "\t<TR>";
			echo "\t\t<TD>Inserting newstopic into database</TD>";
			
			if(@mysql_query("INSERT INTO news (id, dateStamp, title, text) VALUES ('1', '".time()."', 'Welcome to Create Your GetPaid!', 'This is an example post in your Create Your GetPaid installation. You may delete this post if you like since everything seems to be working!');"))
				echo "\t\t<TD><FONT COLOR=green>OK</FONT></TD>";
			else
				echo "\t\t<TD><FONT COLOR=red><B>Failed</B></FONT></TD>";
			
			echo "\t</TR>";
			echo "\t<TR>";
			echo "\t\t<TD>Inserting turing font into database</TD>";
			
			if(@mysql_query("UPDATE config SET turing_font='02200878072009780520077806200878082004780120057801200c780420047808200a7805200b7803200a7802200378042006780320047801200478042003780220077801200d78042004780320037802200a780520047808200c7802200e7801200f780720047802200478022004780320037805200578092005780320037805200378022004780a20087805200478042005780120037807200478012003780420077808200578072004780820057807200a7802200d7801200a78032004780220067809200578022004780520047803200578022004780120027807200578072004780620057809200a7803200b7803200b7801200478042005780720057804200c7804200878032002780620067806200578052006780320037809200478082006780a20047801200478062003780520047806200578032006780720047804200378032006780620077802200e78032009780a20057802200c7803200c7803200578062003780520067805200578072009780620097801200d7802200a780a20067802200b78052009780320057809200c7804200678'"))
				echo "\t\t<TD><FONT COLOR=green>OK</FONT></TD>";
			else
				echo "\t\t<TD><FONT COLOR=red><B>Failed</B></FONT></TD>";
			
			echo "\t</TR>";
			echo "\t<TR>";
			echo "\t\t<TD>Inserting payment method (e-Gold) into database</TD>";
			
			if(@mysql_query("INSERT INTO payment_methods (id, method, fee, active) VALUES ('1', 'e-Gold', '0', 'yes');"))
				echo "\t\t<TD><FONT COLOR=green>OK</FONT></TD>";
			else
				echo "\t\t<TD><FONT COLOR=red><B>Failed</B></FONT></TD>";
			
			echo "\t</TR>";
			echo "\t<TR>";
			echo "\t\t<TD>Inserting payment method (PayPal) into database</TD>";
			
			if(@mysql_query("INSERT INTO payment_methods (id, method, fee, active) VALUES ('2', 'PayPal', '0', 'yes');"))
				echo "\t\t<TD><FONT COLOR=green>OK</FONT></TD>";
			else
				echo "\t\t<TD><FONT COLOR=red><B>Failed</B></FONT></TD>";
			
			echo "\t</TR>";
			echo "\t<TR>";
			echo "\t\t<TD>Inserting payment method (Moneybookers) into database</TD>";
			
			if(@mysql_query("INSERT INTO payment_methods (id, method, fee, active) VALUES ('3', 'Moneybookers', '0', 'no');"))
				echo "\t\t<TD><FONT COLOR=green>OK</FONT></TD>";
			else
				echo "\t\t<TD><FONT COLOR=red><B>Failed</B></FONT></TD>";
			
			echo "\t</TR>";
			echo "\t<TR>";

			echo "\t\t<TD></TD>";
			echo "\t\t<TD><INPUT type=submit value=\" Finish >> \" size=50></TD>";
			echo "\t</TR>";
			echo "</TABLE>";
		}

		echo "</FORM>";
	}

	function ShowLicense()
	{
		echo "<H1>Terms and Conditions</H1>";
		echo "You have to agree the following Terms and Conditions and the purchase agreement to install.\n";
		echo "<OL><LI>This script is copyrighted to <a href=\"http://www.tsteneker.nl\" target=\"_blank\">T. Steneker Internetservices</a>.</LI>\n";
		echo "<LI>Duplication, selling, or transferring of this script\n";
		echo "is a violation of the copyright and purchase agreement.</LI>\n";
		echo "<LI>Altering the script is in any way a violation of the purchase agreement\n";
		echo "and forbidden by T. Steneker Internetservices.</LI><LI>The use of this script is at your\n";
		echo "own risk and T. Steneker Internetservices can't be held reliable for any\n";
		echo "damage caused by the use or misuse of this script.</LI></OL>\n";
		echo "<A href=\"install.php?act=nextstep&step=1\">I Agree to the Terms and Conditions</A>";
	}
	
	function ReadyPage()
	{
		GLOBAL $version;
		
		//////////////////////////////
		// Removed mail registration and the update key. <zygote>
		//////////////////////////////
	
		echo "<H1>Installation Completed!</H1>";
		echo "Here's what you've just accomplished:<BR><BR>";
		echo "- create .htconfig.php file with settings<BR>- create all tables in database<BR>";
		echo "- insert program configuration, banner, newstopic, turing font, operator and payment methods in database<BR><BR>\n";
		echo "<FONT COLOR=red><B>Please delete the \"install.php\" and if exists \"db.sql\" files from your server.</B></FONT><BR><BR>\n";
		echo "<B>Notice</B> - Please CHMOD <B>all</B> files and folders in the \"/templates\" folder to 777.<BR><BR>\n";
		echo "You can change the \"/lib/.htconfig.php\" file to change mysql settings etc. All templates are\n";
		echo "in the \"/templates\" folder and the language files can be found in the \"/languages\" folder.<BR><BR>\n";
		echo "<B>IMPORTANT</B> - Please create a directory called \"db_backup\". If possible create the directory in a folder\n";
		echo "that can't be reached on the web (eg. /web as root, /db_backup as backup folder), the path can be changed in \"/lib/.htconfig.php\"<BR><BR>\n";
		echo "<B>IMPORTANT</B> - Please create a directory called \"logs\". If possible create the directory in a folder\n";
		echo "that can't be reached on the web (eg. /web as root, /logs as log folder), the path can be changed in \"/lib/.htconfig.php\"<BR><BR>\n";
		echo "You can login with email \"".$_POST['site_email']."\" and password \"".$_POST['password']."\"<BR><BR>\n";
		echo "To go directly to your website <A HREF=\"" . $_POST['site_url'] . "/index.php\" TARGET=\"_blank\">click here</A>!";
	}
	
	echo "\n</BODY></HTML>";

?>