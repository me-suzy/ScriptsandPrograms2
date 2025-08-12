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
	
	$tml->RegisterVar("TITLE", "News");

	if(!$user->IsOperator() || !$user->IsLoggedIn())
		exit($error->Report("News", "You can not access this page."));
	
	if($_GET["action"] == "delete")
	{
		$db->Query("SELECT id FROM news WHERE id='" . $_GET["nid"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report("News", "An error has occured."));
		
		$db->Query("DELETE FROM news WHERE id='" . $_GET["nid"] . "'");
			
		$main->printText("<B>News</B><BR><BR>Newstopic deleted.", 1);
	}
	elseif($_GET["action"] == "add")
	{
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$db->Query("INSERT INTO news (dateStamp, title, text) VALUES ('" . time() . "', '" . $_POST["title"]."', '" . $_POST["text"] . "');");
			
			$main->printText("<B>News</B><BR><BR>Newstopic Added.", 1);
		}
		else
		{
			$text		.= "<FORM ACTION=\"" . _ADMIN_URL . "/news.php?sid=" . $session->ID . "&action=add\" METHOD=\"POST\">\n"
						 ."<DIV ALIGN=\"center\"><CENTER>\n"
						 ."<TABLE WIDTH=\"80%\">\n"
						 ."<TR><TD COLSPAN=\"2\"><B>Add Newstopic</B></TD></TR>"
						 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
						 ."<TR><TD>Title:</TD><TD ALIGN=\"right\"><INPUT TYPE=\"text\" NAME=\"title\" SIZE=\"30\"></TD></TR>\n"
						 ."<TR><TD>Text:</TD><TD ALIGN=\"right\"><TEXTAREA NAME=\"text\" COLS=\"40\" ROWS=\"10\"></TEXTAREA></TD></TR>\n"
						 ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"submit\" value=\"Add Newstopic\"></TD></TR>\n"
						 ."</DIV></CENTER></TABLE></FORM>";
					
			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "edit")
	{
		$db->Query("SELECT id FROM news WHERE id='" . $_GET["nid"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report("News", "An error has occured."));
		
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$db->Query("UPDATE news SET title='" . $_POST["title"] . "', text='" . $_POST["text"] . "' WHERE id='" . $_GET["nid"] . "'");
			
			$main->printText("<B>News</B><BR><BR>Newstopic Edited.", 1);
		}
		else
		{
			$data	= $main->Trim($db->Fetch("SELECT * FROM news WHERE id='" . $_GET["nid"] . "'"));
			
			$text	.= "<FORM ACTION=\"" . _ADMIN_URL . "/news.php?sid=" . $session->ID . "&action=edit&nid=" . $_GET["nid"] . "\" METHOD=\"POST\">\n"
					  ."<DIV ALIGN=\"center\"><CENTER>\n"
					  ."<TABLE WIDTH=\"80%\">\n"
					  ."<TR><TD COLSPAN=\"2\"><B>Edit Newstopic \"" . $data["title"] . "\"</B></TD></TR>"
					  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
					  ."<TR><TD>Title:</TD><TD ALIGN=\"right\"><INPUT TYPE=\"text\" NAME=\"title\" VALUE=\"" . $data["title"] . "\" SIZE=\"30\"></TD></TR>\n"
					  ."<TR><TD VALIGN=\"top\">Text:</TD><TD ALIGN=\"right\"><TEXTAREA NAME=\"text\" COLS=\"40\" ROWS=\"10\">" . $data["text"] . "</TEXTAREA></TD></TR>\n"
					  ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"submit\" value=\"Edit Newstopic\"></TD></TR>\n"
					  ."</CENTER></DIV></TABLE></FORM>";
			
			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "delete_reply")
	{
		$db->Query("SELECT id FROM replies WHERE id='" . $_GET["reply"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report("News", "An error has occured."));
		
		$db->Query("DELETE FROM replies WHERE id='" . $_GET["reply"] . "'");
		
		$main->printText("<B>News</B><BR><BR>Reply deleted.", 1);
	}
	elseif($_GET["action"] == "edit_reply")
	{
		$db->Query("SELECT id FROM replies WHERE id='" . $_GET["reply"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report(_LANG_NEWS_TITLE, _LANG_ERROR_ERROROCCURED));
		
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$db->Query("UPDATE replies SET author='" . $_POST["author"] . "', text='" . $_POST["text"] . "' WHERE id='" . $_GET["reply"] . "'");
			
			$main->printText("<B>News</B><BR><BR>Reply edited.", 1);
		}
		else
		{
			$data	= $main->Trim($db->Fetch("SELECT author, text FROM replies WHERE id='" . $_GET["reply"] . "'"));
			
			$text	= "<FORM ACTION=\"" . _ADMIN_URL . "/news.php?sid=" . $session->ID . "&action=edit_reply&reply=" . $_GET["reply"] . "\" METHOD=\"post\">\n"
					 ."<DIV ALIGN=\"center\"><CENTER>\n"
					 ."<TABLE WIDTH=\"80%\">\n"
					 ."<TR><TD COLSPAN=\"2\"><B>Edit reply</B></TD></TR>"
					 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
					 ."<TR><TD>Author:</TD><TD ALIGN=\"right\"><INPUT TYPE=\"text\" NAME=\"author\" VALUE=\"" . $data["author"] . "\" SIZE=\"30\"></TD></TR>\n"
					 ."<TR><TD VALIGN=\"top\">Text:</TD><TD ALIGN=\"right\"><TEXTAREA NAME=\"text\" COLS=\"40\" ROWS=\"10\">" . $data["text"] . "</TEXTAREA></TD></TR>\n"
					 ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"submit\" value=\"Edit Reply\"></TD></TR>\n"
					 ."</CENTER></DIV></TABLE></FORM>";
			
			$main->printText($text);
		}
	}
	else
	{
		$start	= (isset($_GET["start"])) ? intval($_GET["start"]) : 0;
		
		$text	.= "<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\">\n"
				 ."<TR BGCOLOR=\"#D3D3D3\">\n<TD CLASS=\"small_header\">Newstopic</TD><TD CLASS=\"small_header\">Date</TD><TD CLASS=\"small_header\">Action</TD></TR>\n";

		$db->Query("SELECT id, dateStamp, title FROM news ORDER BY dateStamp DESC LIMIT $start, 30");
		
		while($row = $db->NextRow())
		{
			$row	= $main->Trim($row);
			
			$text	.= "<TR BGCOLOR=\"#EAEAEA\"><TD CLASS=\"small_body\">" . $row["title"] . "</TD><TD CLASS=\"small_body\">" . date(_SITE_DATESTAMP, $row["dateStamp"]) . "</TD>\n";
			$text	.= "<TD CLASS=\"small_body\"><A HREF=\"" . _ADMIN_URL . "/news.php?sid=" . $session->ID . "&action=delete&nid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/del.gif\" ALT=\"Delete\" BORDER=\"0\"></A>\n";
			$text	.= "<A HREF=\"" . _ADMIN_URL . "/news.php?sid=" . $session->ID . "&action=edit&nid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/edit.gif\" ALT=\"Edit\" BORDER=\"0\"></A></TD></TR>\n";
		}
		
		$text	.= "</TABLE><BR>\n";

		$db->Query("SELECT id FROM news");
		
		$text	.= "<TABLE WIDTH=\"100%\"><TR><TD>" . $main->GeneratePages(_ADMIN_URL . "/news.php?sid=" . $session->ID, $db->NumRows(), 30, $start) . "</TD></TR></TABLE>";
		$text	.= "<TABLE WIDTH=\"100%\"><TR><TD><A HREF=\"" . _ADMIN_URL . "/news.php?sid=" . $session->ID . "&action=add\">Add new newstopic</A></TD></TR></TABLE>";
		
		$main->printText($text);
		
	}

?>