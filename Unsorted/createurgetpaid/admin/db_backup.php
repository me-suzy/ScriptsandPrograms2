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
	
	$tml->RegisterVar("TITLE", "Database Backup");
	
	if(!$user->IsOperator() || !$user->IsLoggedIn())
		exit($error->Report("Database Backup", "You can not access this page."));
	
	if($_GET["action"] == "download")
	{
		if(!$_GET["file"])
			exit($error->Report("Database Backup", "An error has occured."));
		
		header("Content-Type: application/octet-stream");
		header("Content-Disposition: attachment; filename=backup.sql");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: public");
		
		$main->WriteToLog("backup", "Backup \"" . $_GET["file"] . "\" downloaded");
		
		readfile(_BACKUP_PATH . $_GET["file"]);
	}
	else
	{
		if($_GET["action"] == "backup")
		{
			$newfile	= "# Dump created with Create Your GetPaid " . _SYSTEM_VERSION . " on " . (date("Y-m-d H:i")) . "\r\n";
			$tables		= mysql_list_tables(_DB_NAME);
			$num_tables	= @mysql_num_rows($tables);
			$time0		= time();
			
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
				
				$time1	= time();
				
				if($time1 >= $time0 + 30)
				{
					$time0	= $time1;
					
					header('X-pmaPing: Pong');
				}
			}
			
			$fn	= date("Y_m_d_H_i") . ".sql";
			
			$fp	= fopen(_BACKUP_PATH . $fn, "w");
			
			fwrite($fp, $newfile);
			fclose($fp);
			
			$main->WriteToLog("backup", "Backup \"$fn\" manually created");

			$main->printText("<B>Database Backup</B><BR><BR>Succesfully created database backup.", 1);
		}
		elseif($_GET["action"] == "restore")
		{
			if($_GET["file"] == "")
				exit($error->Report("Database Backup","An error has occured."));
			
			if($_GET["confirm"] == "yes")
			{
				@set_time_limit(0);
				
				$file			= fread(fopen(_BACKUP_PATH . $_GET["file"], "r"), filesize(_BACKUP_PATH . $_GET["file"]));
				$query			= explode(";#%%\r\n", $file);
				$errorCount		= 0;
				
				for($i = 0; $i < count($query) - 1; $i++)
				{
					@mysql_query($query[$i]) or ($errorCount++);
					
					if($errorCount == 10)
						exit($error->Report("Database Backup", "<BR><BR><B><FONT COLOR=\"red\">" . $_GET["file"] . " couldn't be restored -> too many errors!</FONT></B>"));
				}
				
				$main->WriteToLog("backup", "Backup \"" . $_GET["file"] . "\" restored");
				
				$main->printText("<B>" . $_GET["file"] . " successfully restored!</B><BR><BR>$errorCount errors occured.", 1);
			}
			else
			{
				$main->printText("<B>All previous settings will be replaced!</B><BR>Are you sure you want to restore the database?"
								."<BR><BR><A HREF=\""._ADMIN_URL."/db_backup.php?sid=" . $session->ID . "&action=restore&file=".$_GET["file"]."&confirm=yes\">Yes</A> - "
								."<A HREF=\""._ADMIN_URL."/db_backup.php?sid=" . $session->ID . "\">No</A><BR><BR>");
			}
		}
		else
		{
			if($_GET["delete"] != "")
			{
				$main->WriteToLog("backup", "Backup \"" . $_GET["delete"] . "\" deleted");
				
				$text	.= !@unlink(_BACKUP_PATH . $_GET["delete"]) ? "<B><FONT COLOR=\"red\">" . $_GET["delete"] . " couldn't be deleted!</FONT></B><BR><BR>" : "<B>" . $_GET["delete"] . " successfully deleted!</B><BR><BR>";
			}

			$text	.= "<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\">\n"
					  ."<TR BGCOLOR=\"#D3D3D3\">"
					  ."<TD>File</TD><TD>Size</TD><TD>Date</TD><TD>Action</TD></TR>\n";
			
			if(!$dir = @opendir(_BACKUP_PATH))
				exit($error->Report("Database Backup", "Failed to open backup directory, please create it and CHMOD it to 777."));
			
			$total	= 1;
			
			while($file = readdir($dir))
			{
				if($file != "." && $file != ".." && eregi("\.sql", $file))
				{
					$fileid	= explode(".", $file);
					
					$text	.= "<TR BGCOLOR=\"#EAEAEA\"><TD>backup$total.sql&nbsp;</TD><TD>&nbsp;" . number_format(filesize(_BACKUP_PATH . $file) / 1024, 0) . " kB&nbsp;</TD>\n"
							  ."<TD>&nbsp;" . date(_SITE_DATESTAMP . " H:i", filemtime(_BACKUP_PATH . $file)) . "</TD>\n"
							  ."<TD>&nbsp;<A HREF=\""._ADMIN_URL."/db_backup.php?sid=" . $session->ID . "&action=restore&file=$file\"><B>Restore</B></A> "
							  ."<A HREF=\""._ADMIN_URL."/db_backup.php?sid=" . $session->ID . "&action=download&file=$file\"><B>Download</B></A> "
							  ."<A HREF=\""._ADMIN_URL."/db_backup.php?sid=" . $session->ID . "&delete=$file\"><B>Delete</B></A></TD></TR>\n";
					
					$total	+= 1;
			    }
			}

			closedir($dir);

			if($total == 1)
				$text	.= "<TR BGCOLOR=\"#EAEAEA\"><TD COLSPAN=\"4\">You haven't made any backup yet.</TD></TR>\n";

			$text	.= "</TABLE><TABLE WIDTH=\"100%\"><TR><TD COLSPAN=\"4\">&nbsp;</TD></TR>\n"
					  ."<TR><TD COLSPAN=\"4\"><A HREF=\""._ADMIN_URL."/db_backup.php?sid=" . $session->ID . "&action=backup\">Click here to create a backup</A></TD></TR></TABLE>\n";

			$main->printText($text);
		}
	}

?>