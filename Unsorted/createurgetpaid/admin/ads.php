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
	
	$tml->RegisterVar("TITLE", "Advertising Manager");

	if(!$user->IsOperator() || !$user->IsLoggedIn())
		exit($error->Report("Advertising Manager", "You can not access this page."));
	
	if($_GET["action"] == "packages")
	{
		if($_GET["sub"] == "delete")
		{
			$db->Query("SELECT id FROM ad_packages WHERE id='" . $_GET["pid"] . "'");
			
			if($db->NumRows() == 0)
				exit($error->Report("Advertising Packages", "This package doesn't exist."));
			
			$db->Query("DELETE FROM ad_packages WHERE id='" . $_GET["pid"] . "'");
			
			$main->printText("<B>Advertising Packages</B><BR><BR>Advertising Package Deleted.", 1);
		}
		elseif($_GET["sub"] == "edit")
		{
			$db->Query("SELECT id FROM ad_packages WHERE id='" . $_GET["pid"] . "'");
			
			if($db->NumRows() == 0)
				exit($error->Report("Advertising Packages", "This package doesn't exist."));
			
			if($_SERVER["REQUEST_METHOD"] == "POST")
			{
				$db->Query("UPDATE ad_packages SET title='" . $_POST["title"] . "', price='" . $_POST["price"] . "', type='" . $_POST["type"] . "' WHERE id='" . $_GET["pid"] . "'");
				
				$main->printText("<B>Advertising Packages</B><BR><BR>Advertising Package Edited.", 1);
			}
			else
			{
				$data	= $main->Trim($db->Fetch("SELECT * FROM ad_packages WHERE id='" . $_GET["pid"] . "'"));
				$types	= Array("ads", "emails", "clicks", "signups", "leads", "sales", "other", "account");
				
				$text	.= "<FORM ACTION=\"" . _ADMIN_URL . "/ads.php?sid=" . $session->ID . "&action=packages&sub=edit&pid=" . $_GET["pid"] . "\" METHOD=\"POST\">\n"
						 ."<TABLE WIDTH=\"100%\">\n"
						 ."<TR><TD COLSPAN=\"2\"><B>Edit an Advertising Package</B></TD></TR>"
						 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
						 ."<TR><TD>Title</TD><TD><INPUT TYPE=\"text\" NAME=\"title\" VALUE=\"" . $data["title"] . "\"></TD></TR>"
						 ."<TR><TD>Price</TD><TD><INPUT TYPE=\"text\" NAME=\"price\" VALUE=\"" . $data["price"] . "\"></TD></TR>"
						 ."<TR><TD>Type</TD><TD><SELECT NAME=\"type\" SIZE=\"1\">";
				
				foreach($types AS $type)
				{
					$text	.= "<OPTION VALUE=\"$type\"" . ($type == $data["type"] ? "selected" : "") . ">$type</OPTION>\n";
				}
				
				$text	.= "</SELECT></TD></TR>\n"
						  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
						  ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"submit\" value=\"Edit Package\"></TD></TR>\n"
						  ."</TABLE></FORM>";
				
				$main->printText($text);
			}
		}
		elseif($_GET["sub"] == "add")
		{
			if($_SERVER["REQUEST_METHOD"] == "POST")
			{
				$db->Query("INSERT INTO ad_packages (title, price, type) VALUES ('" . $_POST["title"] . "', '" . $_POST["price"] . "', '" . $_POST["type"] . "');");
				
				$main->printText("<B>Advertising Packages</B><BR><BR>Advertising Package Added.", 1);
			}
			else
			{
				$types	= Array("ads", "emails", "clicks", "signups", "leads", "sales", "other", "account");
				
				$text	.= "<FORM ACTION=\"" . _ADMIN_URL . "/ads.php?sid=" . $session->ID . "&action=packages&sub=add\" METHOD=\"POST\">\n"
						 ."<TABLE WIDTH=\"100%\">\n"
						 ."<TR><TD COLSPAN=\"2\"><B>Add an Advertising Package</B></TD></TR>"
						 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
						 ."<TR><TD>Title</TD><TD><INPUT TYPE=\"text\" NAME=\"title\"></TD></TR>"
						 ."<TR><TD>Price</TD><TD><INPUT TYPE=\"text\" NAME=\"price\"></TD></TR>"
						 ."<TR><TD>Type</TD><TD><SELECT NAME=\"type\" SIZE=\"1\">";
				
				foreach($types AS $type)
				{
					$text	.= "<OPTION VALUE=\"$type\">$type</OPTION>\n";
				}
				
				$text	.= "</SELECT></TD></TR>\n"
						  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
						  ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"submit\" value=\"Add Package\"></TD></TR>\n"
						  ."</TABLE></FORM>";
				
				$main->printText($text);
			}
		}
		else
		{
			$start	= (isset($_GET["start"])) ? intval($_GET["start"]) : 0;
			
			$text	.= "<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\">\n"
					 ."<TR BGCOLOR=\"#D3D3D3\">\n<TD>Package Title</TD><TD>Price</TD><TD>Type</TD><TD>Action</TD></TR>\n";
			
			$db->Query("SELECT id, title, price, type FROM ad_packages ORDER BY type ASC LIMIT $start, 30");
			
			while($row = $db->NextRow())
			{
				$text	.= "<TR BGCOLOR=\"#EAEAEA\">\n"
						  ."<TD>" . $row["title"] . "</TD><TD>" . _ADMIN_CURRENCY . " " . number_format($row["price"], 2) . "</TD><TD>" . $row["type"] . "</TD>"
						  ."<TD><A HREF=\"" . _ADMIN_URL . "/ads.php?sid=" . $session->ID . "&action=packages&sub=delete&pid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/del.gif\" ALT=\"Delete\" BORDER=\"0\"></A> "
						  ."<A HREF=\"" . _ADMIN_URL . "/ads.php?sid=" . $session->ID . "&action=packages&sub=edit&pid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/edit.gif\" ALT=\"Edit/View\" BORDER=\"0\"></A></TD></TR>\n";
			}
			
			$text	.= "</TABLE><BR>\n";
			
			$db->Query("SELECT id FROM ad_packages");
			
			$text	.= "<TABLE WIDTH=\"100%\"><TR><TD>" . $main->GeneratePages(_ADMIN_URL . "/ads.php?sid=" . $session->ID . "&action=packages", $db->NumRows(), 30, $start) . "</TD></TR></TABLE>"
					  ."<TABLE WIDTH=\"100%\"><TR><TD><A HREF=\"" . _ADMIN_URL . "/ads.php?sid=" . $session->ID . "&action=packages&sub=add\">Add Advertising Package</A></TD></TR></TABLE>\n";
			
			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "orders")
	{
		if($_GET["sub"] == "view")
		{
			$db->Query("SELECT id FROM ad_orders WHERE id='" . $_GET["oid"] . "'");
			
			if($db->NumRows() == 0)
				exit($error->Report("Orders", "This order doesn't exist."));
			
			if($_SERVER["REQUEST_METHOD"] == "POST")
			{
				$db->Query("UPDATE ad_orders SET fullname='" . $_POST["fullname"] . "', address='" . $_POST["address"] . "', zipcode='" . $_POST["zipcode"] . "', city='" . $_POST["city"] . "', country='" . $_POST["country"] . "', email='" . $_POST["email"] . "', endtotal='" . $_POST["endtotal"] . "', payment_date='" . ($_POST["payment_date"] == 0 ? 0 : $main->date2Stamp($_POST["payment_date"])) . "' WHERE id='" . $_GET["oid"] . "'");
				
				$main->printText("<B>Orders</B><BR><BR>Order changed.", 1);
			}
			else
			{
				$order_data	= $main->Trim($db->Fetch("SELECT package, method, endtotal, fullname, address, zipcode, city, country, email, ad_url, ad_title, ad_text, comments, referer, billdate, payment_date, payment_id, payment_acct FROM ad_orders WHERE id='" . $_GET["oid"] . "'"));
				
				if($order_data["method"] == "account")
				{
					$package_data	= $db->Fetch("SELECT item, type FROM redempts WHERE id='" . $order_data["package"] . "'");
					$package_title	= $package_data["item"];
				}
				else
				{
					$package_data	= $db->Fetch("SELECT title, type FROM ad_packages WHERE id='" . $order_data["package"] . "'");
					$package_title	= $package_data["title"];
				}
				
				$ref			= $db->Fetch("SELECT email FROM users WHERE id='" . $order_data["referer"] . "'");
				$referer		= $ref != "" ? "<A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=edit&uid=" . $order_data["referer"] . "\">" . $ref . "</A>" : "no referer";
				
				$payment_data	= $order_data["payment_date"] == 0 ? 0 : date("m-d-Y", $order_data["payment_date"]);
				$payment_id		= $order_data["payment_id"] == "" ? "-" : $order_data["payment_id"];
				$payment_acct	= $order_data["payment_acct"] == "" ? "-" : $order_data["payment_acct"];
				
				$text	= "<FORM ACTION=\"" . _ADMIN_URL . "/ads.php?sid=" . $session->ID . "&action=orders&sub=view&oid=" . $_GET["oid"] . "\" METHOD=\"post\">\n"
						 ."<TABLE WIDTH=\"100%\">\n"
						 ."<TR><TD COLSPAN=\"2\"><B>View order #" . $_GET["oid"] . "</B></TD></TR>\n"
						 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
						 ."<TR><TD>Package:</TD><TD>$package_title</TD></TR>\n"
						 ."<TR><TD>Full Name:</TD><TD><INPUT TYPE=\"text\" NAME=\"fullname\" VALUE=\"" . $order_data["fullname"] . "\"></TD></TR>\n"
						 ."<TR><TD>Address:</TD><TD><INPUT TYPE=\"text\" NAME=\"address\" VALUE=\"" . $order_data["address"] . "\"></TD></TR>\n"
						 ."<TR><TD>Zipcode:</TD><TD><INPUT TYPE=\"text\" NAME=\"zipcode\" VALUE=\"" . $order_data["zipcode"] . "\"></TD></TR>\n"
						 ."<TR><TD>City:</TD><TD><INPUT TYPE=\"text\" NAME=\"city\" VALUE=\"" . $order_data["city"] . "\"></TD></TR>\n"
						 ."<TR><TD>Country:</TD><TD><INPUT TYPE=\"text\" NAME=\"country\" VALUE=\"" . $order_data["country"] . "\"></TD></TR>\n"
						 ."<TR><TD>E-Mail:</TD><TD><INPUT TYPE=\"text\" NAME=\"email\" VALUE=\"" . $order_data["email"] . "\"></TD></TR>\n"
						 ."<TR><TD>Referer:</TD><TD>$referer</TD></TR>\n"
						 ."<TR><TD>Total costs:</TD><TD><INPUT TYPE=\"text\" NAME=\"endtotal\" VALUE=\"" . number_format($order_data["endtotal"], 2) . "\"></TD></TR>\n"
						 ."<TR><TD>Billdate:</TD><TD>" . date("m-d-Y", $order_data["billdate"]) . "</TD></TR>\n"
						 ."<TR><TD>Payment Date<BR><FONT SIZE=\"1\">\"mm-dd-yyyy\", 0 if unpaid</FONT></TD><TD><INPUT TYPE=\"text\" NAME=\"payment_date\" VALUE=\"" . $payment_data . "\"></TD></TR>\n"
						 ."<TR><TD>Payment Method:</TD><TD>";
				
				if($order_data["method"] == "account")
					$text	.= "Account";
				elseif(_ADDON_AP == 1 && _AP_ADS == 1)
					$text	.= $apayment->PaymentMethod($order_data["method"]);
				else
					$text	.= "Unknown";
				
				$text	.= "</TD></TR>\n";
				
				if(_ADDON_AP == 1 && _AP_ADS == 1)
				{
					$text	.= "<TR><TD>Payment Account:</TD><TD>" . $payment_acct . "</TD></TR>\n"
							  ."<TR><TD>Payment ID:</TD><TD>" . $payment_id . "</TD></TR>\n";
				}
				
				$text	.= "<TR><TD>Ad URL:</TD><TD>" . $order_data["ad_url"] . "</TD></TR>\n"
						  ."<TR><TD>Ad Title/Subject:</TD><TD>" . $order_data["ad_title"] . "</TD></TR>\n"
						  ."<TR><TD>Ad Type:</TD><TD>" . $package_data["type"] . "</TD></TR>\n"
						  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
						  ."<TR><TD VALIGN=\"top\">Ad Text:</TD><TD><BLOCKQUOTE><FONT SIZE=\"1\">" . nl2br(htmlentities($order_data["ad_text"])) . "</FONT></BLOCKQUOTE></TD></TR>\n"
						  ."<TR><TD VALIGN=\"top\">Comments:</TD><TD><BLOCKQUOTE><FONT SIZE=\"1\">" . nl2br($order_data["comments"]) . "</FONT></BLOCKQUOTE></TD></TR>\n"
						  ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"submit\" VALUE=\"Save Changes\"></TD></TR>\n"
						  ."</TABLE></FORM>\n";
				
				$main->PrintText($text);
			}
		}
		elseif($_GET["sub"] == "add")
		{
			$db->Query("SELECT id FROM ad_orders WHERE id='" . $_GET["oid"] . "'");
			
			if($db->NumRows() == 0)
				exit($error->Report("Orders", "This order doesn't exist."));
			
			$order_data	= $db->Fetch("SELECT package, method, email FROM ad_orders WHERE id='" . $_GET["oid"] . "'");
			
			if($order_data["method"] == "account")
			{
				$type	= $db->Fetch("SELECT type FROM redempts WHERE id='" . $order_data["package"] . "'");
			}
			else
			{
				$type	= $db->Fetch("SELECT type FROM ad_packages WHERE id='" . $order_data["package"] . "'");
			}
			
			$db->Query("SELECT id FROM users WHERE email='" . $order_data["email"] . "'");
			
			$aid	= $db->NumRows() == 1 ? $db->Fetch("SELECT id FROM users WHERE email='" . $order_data["email"] . "'") : $user->Get("id");
			
			if($type == "emails")
				header("Location: " . _ADMIN_URL . "/paidmails.php?sid=" . $session->ID . "&action=add&oid=" . $_GET["oid"] . "&aid=$aid");
			elseif($type == "clicks")
				header("Location: " . _ADMIN_URL . "/ptc.php?sid=" . $session->ID . "&action=add&oid=" . $_GET["oid"] . "&aid=$aid");
			elseif($type == "ads")
				header("Location: " . _ADMIN_URL . "/ads.php?sid=" . $session->ID . "&action=add&oid=" . $_GET["oid"] . "&aid=$aid");
			elseif($type == "signups")
				header("Location: " . _ADMIN_URL . "/paidsignups.php?sid=" . $session->ID . "&action=add&oid=" . $_GET["oid"] . "&aid=$aid");
			elseif($type == "leads")
				header("Location: " . _ADMIN_URL . "/leads.php?sid=" . $session->ID . "&action=add&oid=" . $_GET["oid"] . "&aid=$aid");
			elseif($type == "sales")
				header("Location: " . _ADMIN_URL . "/sales.php?sid=" . $session->ID . "&action=add&oid=" . $_GET["oid"] . "&aid=$aid");
			else
				exit($error->Report("Orders", "Package type unknown, cannot start campaign."));
		}
		elseif($_GET["sub"] == "delete")
		{
			$db->Query("SELECT id FROM ad_orders WHERE id='" . $_GET["oid"] . "'");
			
			if($db->NumRows() == 0)
				exit($error->Report("Orders", "This order doesn't exist."));
			
			$db->Query("DELETE FROM ad_orders WHERE id='" . $_GET["oid"] . "'");
			
			$main->printText("<B>Orders</B><BR><BR>Order Deleted.", 1);
		}
		else
		{
			$start	= (isset($_GET["start"])) ? intval($_GET["start"]) : 0;
			
			$text	.= "<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\">\n"
					 ."<TR BGCOLOR=\"#D3D3D3\">\n<TD>Full Name</TD><TD>Package</TD><TD>Total Costs</TD><TD>Payment Method</TD><TD>Bill-date</TD><TD>Paid</TD><TD>Action</TD></TR>\n";
	
			$db->Query("SELECT id, package, method, endtotal, fullname, email, billdate, payment_date FROM ad_orders ORDER BY payment_date DESC, billdate DESC LIMIT $start, 30");
			
			while($row = $db->NextRow())
			{
				if($row["method"] == "account")
				{
					$title	= $db->Fetch("SELECT item FROM redempts WHERE id='" . $row["package"] . "'", 2);
				}
				else
				{
					$title	= $db->Fetch("SELECT title FROM ad_packages WHERE id='" . $row["package"] . "'", 2);
				}
				
				$paid	= $row["payment_date"] >= 1 ? "yes" : "no";
				
				$text	.= "<TR BGCOLOR=\"#EAEAEA\">\n"
						  ."<TD><A HREF=\"" . _ADMIN_URL . "/mailer.php?sid=" . $session->ID . "&to=" . $row["email"] . "&from=" . _EMAIL_ADVERTISE . "\">" . $row["fullname"] . "</A></TD><TD>$title</TD>\n"
						  ."<TD>" . _ADMIN_CURRENCY . number_format($row["endtotal"], 2) . "</TD><TD>";
				
				if($row["method"] == "account")
				{
					$text	.= "Account";
				}
				elseif(_ADDON_AP == 1 && _AP_ADS == 1)
				{
					$text	.= $apayment->PaymentMethod($row["method"]);
				}
				else
				{
					$text	.= "Unknown";
				}
					
				$text	.= "</TD><TD>" . date(_SITE_DATESTAMP, $row["billdate"]) . "</TD><TD>" . $paid . "</TD>\n"
						  ."<TD><A HREF=\"" . _ADMIN_URL . "/ads.php?sid=" . $session->ID . "&action=orders&sub=view&oid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/edit.gif\" ALT=\"View\" BORDER=\"0\"></A> "
						  ."<A HREF=\"" . _ADMIN_URL . "/ads.php?sid=" . $session->ID . "&action=orders&sub=add&oid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/add.gif\" ALT=\"Add\" BORDER=\"0\"></A> "
						  ."<A HREF=\"" . _ADMIN_URL . "/ads.php?sid=" . $session->ID . "&action=orders&sub=delete&oid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/del.gif\" ALT=\"Delete\" BORDER=\"0\"></A></TD></TR>\n";
			}
			
			$text	.= "</TABLE><BR>\n";
			
			$db->Query("SELECT id FROM ad_orders");
			
			$text	.= "<TABLE WIDTH=\"100%\"><TR><TD>" . $main->GeneratePages(_ADMIN_URL . "/ads.php?sid=" . $session->ID . "&action=orders", $db->NumRows(), 30, $start) . "</TD></TR></TABLE>";
			
			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "delete")
	{
		$db->Query("SELECT id FROM ads WHERE id='" . $_GET["bid"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report("Rotating Banners", "This banner doesn't exist."));
		
		$db->Query("DELETE FROM ads WHERE id='" . $_GET["bid"] . "'");
		
		$main->printText("<B>Rotating Banners</B><BR><BR>Banner Deleted.", 1);
	}
	elseif($_GET["action"] == "add")
	{
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$db->Query("SELECT id FROM users WHERE id='" . $_POST["advertiser"] ."' AND advertiser='yes'");
			
			if($db->NumRows() == 0)
				exit($error->Report("Rotating Banners", "Advertiser account not found, please check account."));
			
			if($_POST["b_type"] == "js")
				$_POST["type"]	= "views";
			
			$db->Query("INSERT INTO ads (aid, name, path, url, alt, type, quantity, b_type, jscode, active) VALUES ('" . $_POST["advertiser"] ."', '" . $_POST["name"] . "', '" . $_POST["path"] . "', '" . $_POST["url"] . "', '" . $_POST["alt"] . "', '" . $_POST["type"] . "', '" . $_POST["quantity"] . "', '" . $_POST["b_type"] . "', '" . $_POST["jscode"] . "', '" . $_POST["active"] . "');");
			
			$main->printText("<B>Rotating Banners</B><BR><BR>Banner Added.", 1);
		}
		else
		{
			$order_data	= $db->Fetch("SELECT ad_url, ad_title, ad_text FROM ad_orders WHERE id='" . $_GET["oid"] . "'");
			
			$text		.= "<FORM NAME=\"addad\" ACTION=\"" . _ADMIN_URL . "/ads.php?sid=" . $session->ID . "&action=add\" METHOD=\"POST\">\n"
						 ."<SCRIPT LANGUAGE=\"javascript\" TYPE=\"text/javascript\">\n"
						 ."function f_adtype(option){\n\n"
						 ."        form = document.addad;\n"
						 ."        if(option == 'img')\n"
						 ."        {\n"
						 ."           T1.style.display = '';\n"
						 ."           T2.style.display = '';\n"
						 ."           T3.style.display = '';\n"
						 ."           T4.style.display = '';\n"
						 ."           T5.style.display = '';\n"
						 ."           T6.style.display = '';\n"
						 ."           T7.style.display = '';\n"
						 ."           T8.style.display = '';\n"
						 ."           T9.style.display = 'none';\n"
						 ."           T10.style.display = 'none';\n"
						 ."           T11.style.display = '';\n"
						 ."           T12.style.display = '';\n"
						 ."        }\n"
						 ."        else\n"
						 ."        if(option == 'js')\n"
						 ."        {\n"
						 ."           T1.style.display = 'none';\n"
						 ."           T2.style.display = 'none';\n"
						 ."           T3.style.display = 'none';\n"
						 ."           T4.style.display = 'none';\n"
						 ."           T5.style.display = 'none';\n"
						 ."           T6.style.display = 'none';\n"
						 ."           T7.style.display = 'none';\n"
						 ."           T8.style.display = 'none';\n"
						 ."           T9.style.display = '';\n"
						 ."           T10.style.display = '';\n"
						 ."           T11.style.display = 'none';\n"
						 ."           T12.style.display = 'none';\n"
						 ."        }\n"
						 ."}\n"
						 ."</SCRIPT>\n"
						 ."<TABLE WIDTH=\"100%\">\n"
						 ."<TR><TD COLSPAN=\"2\"><B>Add Banner</B></TD></TR>"
						 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
						 ."<TR><TD>Name:</TD><TD><INPUT TYPE=\"text\" NAME=\"name\" SIZE=\"30\" VALUE=\"" . $order_data["ad_title"] . "\"></TD></TR>\n"
						 ."<TR><TD>Bannertype:</TD><TD><SELECT NAME=\"b_type\" SIZE=\"1\" ONCHANGE=\"f_adtype(this.value)\"><OPTION VALUE=\"img\">Normal</OPTION><OPTION VALUE=\"js\">Javascript</OPTION></SELECT></TD></TR>\n"
						 ."<TR><TD><DIV ID=\"T9\" STYLE=\"DISPLAY: none\">Javascript Code:</DIV></TD><TD><DIV ID=\"T10\" STYLE=\"DISPLAY: none\"><TEXTAREA NAME=\"jscode\" COLS=\"25\" rows=\"5\"></TEXTAREA></DIV></TD></TR>\n"
						 ."<TR><TD><DIV ID=\"T1\" STYLE=\"DISPLAY: \">Path to Banner:</DIV></TD><TD><DIV ID=\"T2\" STYLE=\"DISPLAY: \"><INPUT TYPE=\"text\" NAME=\"path\" SIZE=\"30\"></DIV></TD></TR>\n"
						 ."<TR><TD><DIV ID=\"T3\" STYLE=\"DISPLAY: \">Url:</DIV></TD><TD><DIV ID=\"T4\" STYLE=\"DISPLAY: \"><INPUT TYPE=\"text\" NAME=\"url\" SIZE=\"30\" VALUE=\"" . $order_data["ad_url"] . "\"></DIV></TD></TR>\n"
						 ."<TR><TD><DIV ID=\"T5\" STYLE=\"DISPLAY: \">Alt:</DIV></TD><TD><DIV ID=\"T6\" STYLE=\"DISPLAY: \"><INPUT TYPE=\"text\" NAME=\"alt\" SIZE=\"30\"></DIV></TD></TR>\n"
						 ."<TR><TD><DIV ID=\"T7\" STYLE=\"DISPLAY: \">Paid for:</DIV></TD><TD><DIV ID=\"T8\" STYLE=\"DISPLAY: \"><SELECT NAME=\"type\" SIZE=\"1\"><OPTION VALUE=\"clicks\" selected>Clicks</OPTION><OPTION VALUE=\"views\">Views</OPTION></SELECT></DIV></TD></TR>\n"
						 ."<TR><TD><DIV ID=\"T11\" STYLE=\"DISPLAY: \">Quantity:</DIV<</TD><TD><DIV ID=\"T12\" STYLE=\"DISPLAY: \"><INPUT TYPE=\"text\" NAME=\"quantity\" SIZE=\"10\"></DIV></TD></TR>\n"
						 ."<TR><TD>Advertiser:<BR><FONT SIZE=\"1\">account e-mail</FONT></TD>\n"
						 ."<TD><SELECT NAME=\"advertiser\" SIZE=\"1\">\n";
			
			$db->Query("SELECT id, email FROM users WHERE active='yes' AND advertiser='yes'");
			
			while($row = $db->NextRow())
				$text		.= "<OPTION VALUE=\"" . $row["id"] . "\" " . ($row["id"] == $_GET["aid"] ? "selected" : "") . ">" . $row["email"] . "</OPTION>\n";
			
			$text		.= "</SELECT></TD></TR>\n"
						  ."<TR><TD>Active:</TD><TD><SELECT NAME=\"active\" SIZE=\"1\"><OPTION VALUE=\"yes\" selected>Yes</OPTION><OPTION VALUE=\"no\">No</OPTION></SELECT></TD></TR>\n"
						  ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"submit\" value=\"Add Banner\"></TD></TR>\n"
						  ."</TABLE></FORM>";
			
			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "edit")
	{
		$db->Query("SELECT id FROM ads WHERE id='" . $_GET["bid"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report("Rotating Banners", "An error has occured."));
		
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			$db->Query("SELECT id FROM users WHERE id='" . $_POST["advertiser"] . "' AND advertiser='yes'");
			
			if($db->NumRows() == 0)
				exit($error->Report("Rotating Banners", "Advertiser account not found, please check account."));
			
			$db->Query("UPDATE ads SET aid='" . $_POST["advertiser"] . "', name='" . $_POST["name"] . "', path='" . $_POST["path"] . "', url='" . $_POST["url"] . "', alt='" . $_POST["alt"] . "', clicks='" . $_POST["clicks"] . "', views='" . $_POST["views"] . "', type='" . ($_POST["b_type"] == "js" ? "views" : $_POST["type"]) . "', quantity='" . $_POST["quantity"] . "', jscode='" . $_POST["jscode"] . "', b_type='" . $_POST["b_type"] . "', active='" . $_POST["active"] . "' WHERE id='" . $_GET["bid"] . "'");
			
			$main->printText("<B>Rotating Banners</B><BR><BR>Banner Edited.", 1);
		}
		else
		{
			$data	= $main->Trim($db->Fetch("SELECT * FROM ads WHERE id='" . $_GET["bid"] . "'"));
			
			$text	.= "<FORM NAME=\"editad\" ACTION=\"" . _ADMIN_URL . "/ads.php?sid=" . $session->ID . "&action=edit&bid=" . $_GET["bid"] . "\" METHOD=\"POST\">\n"
					  ."<SCRIPT LANGUAGE=\"javascript\" TYPE=\"text/javascript\">\n"
					  ."function f_adtype(option){\n\n"
					  ."        form = document.editad;\n"
					  ."        if(option == 'img')\n"
					  ."        {\n"
					  ."           T15.style.display = 'none';\n"
					  ."           T16.style.display = 'none';\n"
					  ."           T1.style.display = '';\n"
					  ."           T2.style.display = '';\n"
					  ."           T3.style.display = '';\n"
					  ."           T4.style.display = '';\n"
					  ."           T5.style.display = '';\n"
					  ."           T6.style.display = '';\n"
					  ."           T7.style.display = '';\n"
					  ."           T8.style.display = '';\n"
					  ."           T9.style.display = '';\n"
					  ."           T10.style.display = '';\n"
					  ."           T11.style.display = '';\n"
					  ."           T12.style.display = '';\n"
					  ."           T13.style.display = '';\n"
					  ."           T14.style.display = '';\n"
					  ."        }\n"
					  ."        else\n"
					  ."        if(option == 'js')\n"
					  ."        {\n"
					  ."           T15.style.display = '';\n"
					  ."           T16.style.display = '';\n"
					  ."           T1.style.display = 'none';\n"
					  ."           T2.style.display = 'none';\n"
					  ."           T3.style.display = 'none';\n"
					  ."           T4.style.display = 'none';\n"
					  ."           T5.style.display = 'none';\n"
					  ."           T6.style.display = 'none';\n"
					  ."           T7.style.display = 'none';\n"
					  ."           T8.style.display = 'none';\n"
					  ."           T9.style.display = 'none';\n"
					  ."           T10.style.display = 'none';\n"
					  ."           T11.style.display = 'none';\n"
					  ."           T12.style.display = 'none';\n"
					  ."           T13.style.display = 'none';\n"
					  ."           T14.style.display = 'none';\n"
					  ."        }\n"
					  ."}\n"
					  ."</SCRIPT>\n"
					  ."<TABLE WIDTH=\"80%\">\n"
					  ."<TR><TD COLSPAN=\"2\"><B>Edit Banner \"" . $data["name"] . "\"</B></TD></TR>\n"
					  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
					  ."<TR><TD>Name:</TD><TD><INPUT TYPE=\"text\" NAME=\"name\" VALUE=\"" . $data["name"] . "\" SIZE=\"30\"></TD></TR>\n";
			
			if($data["b_type"] == "img")
			{
				$tmp2	= "none";
				
				$text	.= "<TR><TD>Bannertype:</TD><TD><SELECT NAME=\"b_type\" SIZE=\"1\" ONCHANGE=\"f_adtype(this.value)\"><OPTION VALUE=\"img\" selected>Normal</OPTION><OPTION VALUE=\"js\">Javascript</OPTION></SELECT></TD></TR>\n";
			}
			else
			{
				$tmp1	= "none";
				
				$text	.= "<TR><TD>Bannertype:</TD><TD><SELECT NAME=\"b_type\" SIZE=\"1\" ONCHANGE=\"f_adtype(this.value)\"><OPTION VALUE=\"img\">Normal</OPTION><OPTION VALUE=\"js\" selected>Javascript</OPTION></SELECT></TD></TR>\n";
			}
			
			$text	.= "<TR><TD><DIV ID=\"T15\" STYLE=\"DISPLAY: $tmp2\">Javascript Code:</DIV></TD><TD><DIV ID=\"T16\" STYLE=\"DISPLAY: $tmp2\"><TEXTAREA NAME=\"jscode\" COLS=\"25\" rows=\"5\">" . $data["jscode"] . "</TEXTAREA></DIV></TD></TR>\n"
					  ."<TR><TD><DIV ID=\"T1\" STYLE=\"DISPLAY: $tmp1\">Path to Banner:</DIV></TD><TD><DIV ID=\"T2\" STYLE=\"DISPLAY: $tmp1\"><INPUT TYPE=\"text\" NAME=\"path\" VALUE=\"" . $data["path"] . "\" SIZE=\"30\"></DIV></TD></TR>\n"
					  ."<TR><TD><DIV ID=\"T3\" STYLE=\"DISPLAY: $tmp1\">Url:</DIV></TD><TD><DIV ID=\"T4\" STYLE=\"DISPLAY: $tmp1\"><INPUT TYPE=\"text\" NAME=\"url\" VALUE=\"" . $data["url"] . "\" SIZE=\"30\"></DIV></TD></TR>\n"
					  ."<TR><TD><DIV ID=\"T5\" STYLE=\"DISPLAY: $tmp1\">Alt:</DIV></TD><TD><DIV ID=\"T6\" STYLE=\"DISPLAY: $tmp1\"><INPUT TYPE=\"text\" NAME=\"alt\" VALUE=\"" . $data["alt"] . "\" SIZE=\"30\"></DIV></TD></TR>\n"
					  ."<TR><TD><DIV ID=\"T7\" STYLE=\"DISPLAY: $tmp1\">Clicks:</DIV></TD><TD><DIV ID=\"T8\" STYLE=\"DISPLAY: $tmp1\"><INPUT TYPE=\"text\" NAME=\"clicks\" VALUE=\"" . $data["clicks"] . "\" SIZE=\"30\"></DIV></TD></TR>\n"
					  ."<TR><TD><DIV ID=\"T9\" STYLE=\"DISPLAY: $tmp1\">Views:</DIV></TD><TD><DIV ID=\"T10\" STYLE=\"DISPLAY: $tmp1\"><INPUT TYPE=\"text\" NAME=\"views\" VALUE=\"" . $data["views"] . "\" SIZE=\"30\"></DIV></TD></TR>\n";
			
			if($data["type"] == "clicks")
				$text	.= "<TR><TD><DIV ID=\"T11\" STYLE=\"DISPLAY: $tmp1\">Paid for:</DIV></TD><TD><DIV ID=\"T12\" STYLE=\"DISPLAY: $tmp1\"><SELECT NAME=\"type\" SIZE=\"1\"><OPTION VALUE=\"clicks\" selected>Clicks</OPTION><OPTION VALUE=\"views\">Views</OPTION></SELECT></DIV></TD></TR>\n";
			else
				$text	.= "<TR><TD><DIV ID=\"T11\" STYLE=\"DISPLAY: $tmp1\">Paid for:</DIV></TD><TD><DIV ID=\"T12\" STYLE=\"DISPLAY: $tmp1\"><SELECT NAME=\"type\" SIZE=\"1\"><OPTION VALUE=\"clicks\">Clicks</OPTION><OPTION VALUE=\"views\" selected>Views</OPTION></SELECT></DIV></TD></TR>\n";
			
			$text	.= "<TR><TD><DIV ID=\"T13\" STYLE=\"DISPLAY: $tmp1\">Quantity:</DIV></TD><TD><DIV ID=\"T14\" STYLE=\"DISPLAY: $tmp1\"><INPUT TYPE=\"text\" NAME=\"quantity\" VALUE=\"" . $data["quantity"] . "\" SIZE=\"30\"></DIV></TD></TR>\n"
					  ."<TR><TD>Advertiser:</TD><TD><SELECT NAME=\"advertiser\" SIZE=\"1\">\n";
			
			$db->Query("SELECT id, email FROM users WHERE active='yes' AND advertiser='yes'");
			
			while($row = $db->NextRow())
			{
				$text	.= "<OPTION VALUE=\"" . $row["id"] . "\"" . ($row["id"] == $data["aid"] ? " selected" : "") . ">" . $row["email"] . "</OPTION>\n";
			}
			
			$text	.= "</SELECT></TD></TR>\n";
			
			$text	.= "<TR><TD>Active:</TD><TD><SELECT NAME=\"active\" SIZE=\"1\"><OPTION VALUE=\"yes\"" . ($data["active"] == "yes" ? " selected" : "") . ">Yes</OPTION><OPTION VALUE=\"no\"" . ($data["active"] == "no" ? " selected" : "") . ">No</OPTION></SELECT></TD></TR>\n"
					  ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"submit\" VALUE=\"Edit Banner\"></TD></TR>\n"
					  ."</TABLE></FORM>";
			
			$main->printText($text);
		}
	}
	else
	{
		$start	= (isset($_GET["start"])) ? intval($_GET["start"]) : 0;
		
		$text	.= "<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\">\n"
				 ."<TR BGCOLOR=\"#D3D3D3\">\n<TD>Name</TD><TD>Clicks</TD><TD>Views</TD><TD>Clickratio</TD><TD>Active</TD><TD>Action</TD></TR>\n";

		$db->Query("SELECT id, name, clicks, views, active FROM ads ORDER BY name LIMIT $start, 30");
		
		while($row = $db->NextRow())
		{
			$row	= $main->Trim($row);
			$ratio	= @round(($row["clicks"] / $row["views"]) * 100,3);
			
			$text	.= "<TR BGCOLOR=\"#EAEAEA\">\n"
					  ."<TD>" . $row["name"] . "</TD><TD>" . $row["clicks"] . "</TD>"
					  ."<TD>" . $row["views"] . "</TD><TD>" . $ratio . "%</TD><TD>" . $row["active"] . "</TD>\n"
					  ."<TD><A HREF=\"" . _ADMIN_URL . "/ads.php?sid=" . $session->ID . "&action=delete&bid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/del.gif\" ALT=\"Delete\" BORDER=\"0\"></A> "
					  ."<A HREF=\"" . _ADMIN_URL . "/ads.php?sid=" . $session->ID . "&action=edit&bid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/edit.gif\" ALT=\"Edit/View\" BORDER=\"0\"></A></TD></TR>\n";
		}
		
		$text	.= "</TABLE><BR>\n";

		$db->Query("SELECT id FROM ads");
		
		$text	.= "<TABLE WIDTH=\"100%\"><TR><TD>" . $main->GeneratePages(_ADMIN_URL . "/ads.php?sid=" . $session->ID, $db->NumRows(), 30, $start) . "</TD></TR></TABLE>"
				  ."<TABLE WIDTH=\"100%\"><TR><TD><A HREF=\"" . _ADMIN_URL . "/ads.php?sid=" . $session->ID . "&action=add\">Add Banner</A></TD></TR></TABLE>\n";
		
		$main->printText($text);
	}

?>                              