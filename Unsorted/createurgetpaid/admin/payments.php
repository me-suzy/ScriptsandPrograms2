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
	
	$tml->RegisterVar("TITLE", "Payment Manager");

	if(!$user->IsOperator() || !$user->IsLoggedIn())
		exit($error->Report("Payment Manager", "You can not access this page."));
	
	if(_ADDON_AP == 1)
	{
		$word1	= "<TD>Batchnr</TD>";
		$word2	= "8";
	}
	else
	{
		$word1	= "";
		$word2	= "7";
	}
	
	if($_GET["action"] == "pending")
	{
		if($_GET["id"] >= 1)
		{
			$db->Query("SELECT id FROM payments WHERE id='" . $_GET["id"] . "' AND paid='no'");
			
			if($db->NumRows() == 0)
				exit($error->Report("Payment Manager", "The payment doesn't exist or already is paid."));
			
			$UID	= $db->Fetch("SELECT uid FROM payments WHERE id='" . $_GET["id"] . "'");
			
			$db->Query("SELECT id FROM users WHERE id='$UID'");
			
			if($db->NumRows() == 0)
				$error->Warning("Payment Manager", "The member doesn't exists.");
			
			$db->Query("UPDATE payments SET paid='yes' WHERE id='" . $_GET["id"] . "'");
			
			$main->printText("Payment is set to \"paid\".<BR><BR><A HREF=\"" . _ADMIN_URL . "/payments.php?sid=" . $session->ID . "&action=pending\">Click here to go back</A>.");
		}
		elseif($_GET["did"] >= 1)
		{
			$db->Query("SELECT id FROM payments WHERE id='" . $_GET["did"] . "'");
			
			if($db->NumRows() == 0)
				exit($error->Report("Payment Manager", "The payment doesn't exist."));
			
			$db->Query("DELETE FROM payments WHERE id='" . $_GET["did"] . "'");
			
			$main->printText("Payment is deleted.<BR><BR><A HREF=\"" . _ADMIN_URL . "/payments.php?sid=" . $session->ID . "&action=pending\">Click here to go back</A>.");
		}
		elseif($_GET["ret"] >= 1)
		{
			$db->Query("SELECT id FROM payments WHERE id='" . $_GET["ret"] . "'");
			
			if($db->NumRows() == 0)
				exit($error->Report("Payment Manager", "The payment doesn't exist."));
			
			$payment	= $db->Fetch("SELECT uid, credits FROM payments WHERE id='" . $_GET["ret"] . "'");
			
			$db->Query("UPDATE users SET credits=credits+'" . $payment["credits"] . "' WHERE id='" . $payment["uid"] . "'");
			
			$user->Add2Actions($payment["uid"], 0, "refund", $payment["credits"]);
			
			$db->Query("DELETE FROM payments WHERE id='" . $_GET["ret"] . "'");
			
			$main->printText("Payment is refunded.<BR><BR><A HREF=\"" . _ADMIN_URL . "/payments.php?sid=" . $session->ID . "&action=pending\">Click here to go back</A>.");
		}
		else
		{
			$db->Query("SELECT id FROM payments WHERE paid='no'");
			
			$count	= $db->NumRows();
			
			$start	= (isset($_GET["start"])) ? intval($_GET["start"]) : 0;
			
			$text	= "<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\">\n"
					 ."<TR BGCOLOR=\"#D3D3D3\">\n"
					 ."<TD>Username</TD><TD>Payment Account</TD><TD>Payment Method</TD><TD>Credits</TD><TD>Request Date</TD><TD>Action</TD><TD>FP</TD></TR>\n";
			
			if($count == 0)
				$text	.= "<TR BGCOLOR=\"#EAEAEA\"><TD COLSPAN=\"7\">There are no pending payments.</TD></TR>\n";
			else
			{
				$db->Query("SELECT id, uid, credits, method, account, dateStamp, status FROM payments WHERE paid='no' ORDER BY dateStamp DESC LIMIT $start, 100");
				
				while($row = $db->NextRow())
				{
					$userdata	= $db->Fetch("SELECT id, email FROM users WHERE id='" . $row["uid"] . "'", 2);
					$method		= $db->Fetch("SELECT method FROM payment_methods WHERE id='" . $row["method"] . "'", 2);
					
					$email	= $userdata["email"] == "" ? "unknown" : "<A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=edit&uid=" . $userdata["id"] . "\">" . $userdata["email"] . "</A>";
					
					if(_ADDON_AP == 1 && $row["method"] == 1 && $row["status"])
					{
						$word4	= " <A HREF=\"" . _ADMIN_URL . "/payments.php?sid=" . $session->ID . "&action=autopay&pid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/eap.gif\" ALT=\"Automatic E-Gold pay-out\" BORDER=\"0\"></A>\n";
						$word5	= "<TR BGCOLOR=\"#EAEAEA\"><TD COLSPAN=\"7\"><B>Status</B>: " . $row["status"] . "</TD></TR>\n";
					}
					else
					{
						$word4	= "";
						$word5	= "";
					}
					
					$text	.= "<TR BGCOLOR=\"#EAEAEA\">\n"
							  ."<TD>$email</TD><TD>" . $row["account"] . "</TD>"
							  ."<TD>$method</TD><TD>" . _ADMIN_CURRENCY . " " . number_format($row["credits"], 2) . "</TD>\n"
							  ."<TD>" . date(_SITE_DATESTAMP, $row["dateStamp"]) . "</TD>"
							  ."<TD><A HREF=\"" . _ADMIN_URL . "/payments.php?sid=" . $session->ID . "&action=pending&id=" . $row["id"] . "\">\n"
							  ."<IMG SRC=\"" . _SITE_URL . "/inc/img/dollar.gif\" ALT=\"Set As Paid\" BORDER=\"0\"></A>\n"
							  ."<A HREF=\"" . _ADMIN_URL . "/payments.php?sid=" . $session->ID . "&action=pending&ret=" . $row["id"] . "\">"
							  ."<IMG SRC=\"" . _SITE_URL . "/inc/img/ret.gif\" ALT=\"Refund\" BORDER=\"0\"></A>\n"
							  ."<A HREF=\"" . _ADMIN_URL . "/payments.php?sid=" . $session->ID . "&action=pending&did=" . $row["id"] . "\">"
							  ."<IMG SRC=\"" . _SITE_URL . "/inc/img/del.gif\" ALT=\"Delete\" BORDER=\"0\"></A>$word4</TD>\n";
					
					if($row["method"] == 1)
					{
						$text	.= "<FORM ACTION=\"https://www.e-gold.com/sci_asp/payments.asp\" METHOD=\"post\" TARGET=\"_top\">";
						$text	.= "<INPUT TYPE=\"hidden\" NAME=\"PAYEE_ACCOUNT\" VALUE=\"" . $row["account"] . "\">\n";
						$text	.= "<INPUT TYPE=\"hidden\" NAME=\"PAYEE_NAME\" VALUE=\"e-gold SCI\">\n";
						$text	.= "<INPUT TYPE=\"hidden\" NAME=\"PAYMENT_AMOUNT\" VALUE=\"" . number_format($row["credits"], 2) . "\">\n";
						$text	.= "<INPUT TYPE=\"hidden\" NAME=\"PAYMENT_UNITS\" VALUE=\"1\">\n";
						$text	.= "<INPUT TYPE=\"hidden\" NAME=\"PAYMENT_METAL_ID\" VALUE=\"1\">\n";
						$text	.= "<INPUT TYPE=\"hidden\" NAME=\"NOPAYMENT_URL\" VALUE=\"" . _ADMIN_URL . "/payments.php?sid=" . $session->ID . "&action=pending\">\n";
						$text	.= "<INPUT TYPE=\"hidden\" NAME=\"NOPAYMENT_URL_METHOD\" VALUE=\"LINK\">\n";
						$text	.= "<INPUT TYPE=\"hidden\" NAME=\"PAYMENT_URL\" VALUE=\"" . _ADMIN_URL . "/payments.php?sid=" . $session->ID . "&action=pending&id=" . $row["id"] . "\">\n";
						$text	.= "<INPUT TYPE=\"hidden\" NAME=\"PAYMENT_URL_METHOD\" VALUE=\"LINK\">\n";
						$text	.= "<INPUT TYPE=\"hidden\" NAME=\"BAGGAGE_FIELDS\" VALUE=\"\">\n";
						$text	.= "<INPUT TYPE=\"hidden\" NAME=\"SUGGESTED_MEMO\" VALUE=\"" . _MEMBER_PAYOUTMEMO . "\">\n";
						$text	.= "<TD>";
						$text	.= "<INPUT TYPE=\"image\" SRC=\"" . _SITE_URL . "/inc/img/fastpay.gif\" ALT=\"Fast Pay\" BORDER=\"0\">\n";
						$text	.= "</TD>";
						$text	.= "</FORM>";
					}
					elseif($row["method"] == 2)
					{
						$text	.= "<FORM ACTION=\"https://www.paypal.com/cgi-bin/webscr\" METHOD=\"post\" TARGET=\"_top\">";
						$text	.= "<INPUT TYPE=\"hidden\" NAME=\"cmd\" VALUE=\"_ext-enter\">\n";
						$text	.= "<INPUT TYPE=\"hidden\" NAME=\"redirect_cmd\" VALUE=\"_xclick\">\n";
						$text	.= "<INPUT TYPE=\"hidden\" NAME=\"business\" VALUE=\"" . $row["account"] . "\">\n";
						$text	.= "<INPUT TYPE=\"hidden\" NAME=\"item_name\" VALUE=\"" . _MEMBER_PAYOUTMEMO . "\">\n";
						$text	.= "<INPUT TYPE=\"hidden\" NAME=\"amount\" VALUE=\"" . number_format($row["credits"], 2) . "\">\n";
						$text	.= "<INPUT TYPE=\"hidden\" NAME=\"currency_code\" VALUE=\"USD\">\n";
						$text	.= "<INPUT TYPE=\"hidden\" NAME=\"notify_url\" VALUE=\"\">\n";
						$text	.= "<INPUT TYPE=\"hidden\" NAME=\"return\" VALUE=\"" . _ADMIN_URL . "/payments.php?sid=" . $session->ID . "&action=pending&id=" . $row["id"] . "\">\n";
						$text	.= "<INPUT TYPE=\"hidden\" NAME=\"cancel_return\" VALUE=\"" . _ADMIN_URL . "/payments.php?sid=" . $session->ID . "&action=pending\">\n";
						$text	.= "<INPUT TYPE=\"hidden\" NAME=\"no_note\" VALUE=\"1\">\n";
						$text	.= "<INPUT TYPE=\"hidden\" NAME=\"no_shipping\" VALUE=\"1\">\n";
						$text	.= "<INPUT TYPE=\"hidden\" NAME=\"image_url\" VALUE=\"\">\n";
						$text	.= "<INPUT TYPE=\"hidden\" NAME=\"custom\" VALUE=\"\">\n";
						$text	.= "<TD>";
						$text	.= "<INPUT TYPE=\"image\" SRC=\"" . _SITE_URL . "/inc/img/fastpay.gif\" ALT=\"Fast Pay\" BORDER=\"0\">\n";
						$text	.= "</TD>";
						$text	.= "</FORM>";
					}
					else
						$text	.= "<TD><IMG SRC=\"" . _SITE_URL . "/inc/img/fastpay.gif\" ALT=\"Fast Pay\" BORDER=\"0\"></TD>\n";
					
					$text	.= "</TR>$word5\n";
				}
				
				$text	.= "</TABLE><BR>\n";	
			}
			
			$text	.= "<TABLE WIDTH=\"100%\"><TR><TD>" . $main->GeneratePages(_ADMIN_URL . "/payments.php?sid=" . $session->ID . "&action=pending&", $count, 100, $start) . "</TD></TR></TABLE>";
			
			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "paid")
	{
		$db->Query("SELECT id FROM payments WHERE paid='yes'");
		
		$count	= $db->NumRows();
		
		$start	= (isset($_GET["start"])) ? intval($_GET["start"]) : 0;
		
		$text	= "<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\">\n"
				 ."<TR BGCOLOR=\"#D3D3D3\">\n\n"
				 ."<TD>Username</TD><TD>Payment Account</TD><TD>Payment Method</TD><TD>Credits</TD><TD>Request Date</TD>$word1</TR>\n";
		
		if($count == 0)
			$text	.= "<TR BGCOLOR=\"#EAEAEA\"><TD COLSPAN=\"$word2\">There are no paid payments.</TD></TR>\n";
		else
		{
			$db->Query("SELECT id, uid, credits, method, account, dateStamp, status FROM payments WHERE paid='yes' ORDER BY dateStamp DESC LIMIT $start, 100");
			
			while($row = $db->NextRow())
			{
				$email	= $db->Fetch("SELECT email FROM users WHERE id='" . $row["uid"] . "'", 2);
				$method	= $db->Fetch("SELECT method FROM payment_methods WHERE id='" . $row["method"] . "'", 2);
				
				$email	= $email == "" ? "unknown" : "<A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=edit&uid=" . $row["uid"] . "\">$email</A>";
				
				if(_ADDON_AP == 1)
				{
					$word4	= "<TD>" . $row["batchnr"] . "</TD>";
					
					if($row["method"] == 1)
						$word5	= "<TR BGCOLOR=\"#EAEAEA\"><TD COLSPAN=\"6\"><B>Status</B>: " . $row["status"] . "</TD></TR>\n";
				}
				else
				{
					$word4	= "";
					$word5	= "";
				}
				
				$text		.= "<TR BGCOLOR=\"#EAEAEA\">\n"
							  ."<TD>$email</A></TD><TD>" . $row["account"] . "</TD><TD>$method</TD>\n"
							  ."<TD>" . _ADMIN_CURRENCY . " " . number_format($row["credits"], 2) . "</TD>"
							  ."<TD>" . date(_SITE_DATESTAMP, $row["dateStamp"]) . "</TD>\n"
							  ."$word4</TR>\n$word5";
			}
			
			$text	.= "</TABLE><BR>\n";	
		}
		
		$text	.= "<TABLE WIDTH=\"100%\"><TR><TD>" . $main->GeneratePages(_ADMIN_URL . "/payments.php?sid=" . $session->ID . "&action=paid", $count, 100, $start) . "</TD></TR></TABLE>";
		
		$main->printText($text);
	}
	elseif($_GET["action"] == "user")
	{
		if($_GET["uid"])
		{
			$db->Query("SELECT id FROM payments WHERE uid='" . $_GET["uid"] . "'");
			
			$count	= $db->NumRows();
			
			$start	= (isset($_GET["start"])) ? intval($_GET["start"]) : 0;
			
			$text	= "<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\">\n"
					 ."<TR BGCOLOR=\"#D3D3D3\">\n"
					 ."<TD>Username</TD><TD>Payment Account</TD><TD>Payment Method</TD><TD>Credits</TD><TD>Request Date</TD>$word1<TD>Status</TD></TR>\n";
			
			if($count == 0)
				$text	.= "<TR BGCOLOR=\"#EAEAEA\"><TD COLSPAN=\"$word2\">There are no payments for this user.</TD></TR>\n";
			else
			{
				$db->Query("SELECT id, uid, credits, method, account, dateStamp, paid, batchnr, status FROM payments WHERE uid='" . $_GET["uid"] . "' ORDER BY dateStamp DESC LIMIT $start, 100");
				
				while($row = $db->NextRow())
				{
					$email	= $db->Fetch("SELECT email FROM users WHERE id='" . $row["uid"] . "'", 2);
					$method	= $db->Fetch("SELECT method FROM payment_methods WHERE id='" . $row["method"] . "'", 2);
					
					$email	= $email == "" ? "unknown" : "<A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=edit&uid=" . $row["uid"] . "\">$email</A>";
					$word4	= _ADDON_AP == 1 ? "<TD>" . $row["batchnr"] . "</TD>" : "";
					$status	= $row["paid"] == "yes" ? "Paid" : "Pending";
					
					$text		.= "<TR BGCOLOR=\"#EAEAEA\">\n"
								  ."<TD>$email</TD><TD>" . $row["account"] . "</TD><TD>$method<TD>"
								  ._ADMIN_CURRENCY . " " . number_format($row["credits"], 2) . "</TD>"
								  ."<TD>" . date(_SITE_DATESTAMP, $row["dateStamp"]) . "</TD>\n"
								  ."$word4<TD>$status</TD></TR>\n";
				}
				
				$text	.= "</TABLE><BR>\n";	
			}
			
			$text	.= "<TABLE WIDTH=\"100%\"><TR><TD>" . $main->GeneratePages(_ADMIN_URL . "/payments.php?sid=" . $session->ID . "&action=user&uid=" . $_GET["uid"], $count, 100, $start) . "</TD></TR></TABLE>";
			
			$main->printText($text);
		}
		else
		{
			$text	= "<FORM ACTION=\"" . _ADMIN_URL . "/payments.php\" METHOD=\"get\">\n"
					 ."<INPUT TYPE=\"hidden\" NAME=\"sid\" VALUE=\"" . $_GET["sid"] . "\">\n"
					 ."<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"user\">\n"
					 ."<DIV ALIGN=\"center\"><CENTER>\n"
					 ."<TABLE WIDTH=\"80%\"><TR><TD COLSPAN=\"2\">View payments of a specific user</TD></TR>\n"
					 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
					 ."<TR><TD>User ID:</TD><TD><INPUT TYPE=\"text\" NAME=\"uid\"></TD></TR>\n"
					 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
					 ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" VALUE=\"Submit\"></TD></TR>\n"
					 ."</TABLE>\n"
					 ."</CENTER></DIV>\n"
					 ."</FORM>";
			
			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "autopay")
	{
		if(!isset($_GET["pid"]) || $_GET["pid"] == 0)
			exit($error->Report("Payment Manager", "The payment id is incorrect."));
		
		$db->Query("SELECT id FROM payments WHERE id='" . $_GET["pid"] . "' AND paid='no' AND method='1'");
		
		if($db->NumRows() == 0)
			exit($error->Report("Payment Manager", "The payment does not exists, has already been paid or isn't an e-Gold payment."));
		
		$paymentdata	= $db->Fetch("SELECT uid, account, credits FROM payments WHERE id='" . $_GET["pid"] . "'");
		
		$data			= Array(
						"AccountID"				=> _AP_ACCOUNTID,
						"PassPhrase"			=> urlencode(base64_decode(_AP_PASSPHRASE)),
						"Payee_Account"			=> $paymentdata["account"],
						"Amount"				=> number_format($paymentdata["credits"], 2),
						"Memo"					=> urlencode(_MEMBER_PAYOUTMEMO),
						"PAY_IN"				=> 1,
						"WORTH_OF"				=> "Gold",
						"IGNORE_RATE_CHANGE"	=> "Y",
						);
		
		$apayment->Pay($data);
		
		$status	= $apayment->PROCESS_DETAILS["Error"] == "" ? "OK" : $apayment->PROCESS_DETAILS["Error"];
		
		if($status == "OK")
		{
			$db->Query("UPDATE payments SET paid='yes', status='$status', batchnr='" . $apayment->PROCESS_DETAILS["Batch"] . "', dateStamp='" . time() . "' WHERE id='" . $_GET["pid"] . "'");
			
			$main->WriteToLog("payments", "Member id \"" . $paymentdata["uid"] . "\" with payment id \"" . $_GET["pid"] . "\" paid out \"" . _ADMIN_CURRENCY . number_format($payment_data["credits"], 2) . "\" on account \"" . $paymentdata["account"] . "\"");
			
			$main->printText("Member is paid out and payment is set to \"paid\"<BR><BR><A HREF=\"" . _ADMIN_URL . "/payments.php?sid=" . $session->ID . "&action=pending\">Click here to go back</A>.");
		}
		else
		{
			$db->Query("UPDATE payments SET dateStamp='" . time() ."', status='" . $status . "'  WHERE id='" . $_GET["pid"] . "'");
			
			$error->Report("Payment Manager", "Member could not be paid out, updated payment has been placed back in pending payment.");
		}
	}
	elseif($_GET["action"] == "methods")
	{
		if($_GET["op"] == "delete")
		{
			$db->Query("SELECT id FROM payment_methods WHERE id='" . $_GET["mid"] . "'");
			
			if($db->NumRows() == 0)
				exit($error->Report("Payment Manager", "The payment method does not exists."));
			
			if($_GET["mid"] == 1 || $_GET["mid"] == 2)
				exit($error->Report("Payment Manager", "Paypal and e-Gold cannot be deleted. If you don't want to use these payment methods, please set them on inactive"));
			
			$db->Query("DELETE FROM payment_methods WHERE id='" . $_GET["mid"] . "'");
			
			$main->printText("<B>Payment Manager</B><BR><BR>Payment method is deleted.", 1);
		}
		elseif($_GET["op"] == "edit")
		{
			$db->Query("SELECT id FROM payment_methods WHERE id='" . $_GET["mid"] . "'");
			
			if($db->NumRows() == 0)
				exit($error->Report("Payment Manager", "The payment method doesn't exists."));
			
			if($_SERVER["REQUEST_METHOD"] == "POST")
			{
				$db->Query("UPDATE payment_methods SET method='" . $_POST["method"] . "', fee='" . $_POST["fee"] . "', minimum='" . $_POST["minimum"] . "', active='" . $_POST["active"] . "' WHERE id='" . $_GET["mid"] . "'");
				
				$main->printText("<B>Payment Manager</B><BR><BR>Payment method is edited.", 1);
			}
			else
			{
				$data	= $db->Fetch("SELECT method, fee, minimum, active FROM payment_methods WHERE id='" . $_GET["mid"] . "'");
				
				$text	.= "<FORM ACTION=\"" . _ADMIN_URL . "/payments.php?sid=" . $session->ID . "&action=methods&op=edit&mid=" . $_GET["mid"] . "\" METHOD=\"POST\">\n";
				
				if($_GET["mid"] == 1 || $_GET["mid"] == 2)
				{
					$text	.= "<INPUT TYPE=\"hidden\" NAME=\"method\" VALUE=\"" . $data["method"] . "\">\n";
					$xvar	= $data["method"];
				}
				else
					$xvar	= "<INPUT TYPE=\"text\" NAME=\"method\" SIZE=\"30\" VALUE=\"" . $data["method"] . "\">";
				
				$text	.= "<TABLE WIDTH=\"100%\">\n"
						  ."<TR><TD COLSPAN=\"2\"><B>Edit Payment Method \"" . $data["method"] . "\"</B></TD></TR>"
						  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
						  ."<TR><TD>Payment Method:</TD><TD>$xvar</TD></TR>\n"
						  ."<TR><TD>Fee:</TD><TD><INPUT TYPE=\"text\" NAME=\"fee\" SIZE=\"30\" VALUE=\"" . $data["fee"] . "\"></TD></TR>\n"
						  ."<TR><TD>Minimum:</TD><TD><INPUT TYPE=\"text\" NAME=\"minimum\" SIZE=\"30\" VALUE=\"" . $data["minimum"] . "\"></TD></TR>\n";
				
				if($data["active"] == "yes")
					$text	.= "<TR><TD>Active:</TD><TD><SELECT NAME=\"active\" SIZE=\"1\"><OPTION VALUE=\"no\">No</OPTION><OPTION VALUE=\"yes\" selected>Yes</OPTION></SELECT></TD></TR>\n";
				else
					$text	.= "<TR><TD>Active:</TD><TD><SELECT NAME=\"active\" SIZE=\"1\"><OPTION VALUE=\"no\" selected>No</OPTION><OPTION VALUE=\"yes\">Yes</OPTION></SELECT></TD></TR>\n";
				
				$text	.= "<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
						  ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"submit\" value=\"Edit Payment Method\"></TD></TR>\n"
						  ."</TABLE></FORM>";
				
				$main->printText($text);
			}
		}
		elseif($_GET["op"] == "add")
		{
			if($_SERVER["REQUEST_METHOD"] == "POST")
			{
				$db->Query("INSERT INTO payment_methods (method, fee, minimum, active) VALUES ('" . $_POST["method"] . "', '" . $_POST["fee"] . "', '" . $_POST["minimum"] . "', '" . $_POST["active"] . "');");
				
				$main->printText("<B>Payment Manager</B><BR><BR>Payment method is added.", 1);
			}
			else
			{
				$text	.= "<FORM ACTION=\"" . _ADMIN_URL . "/payments.php?sid=" . $session->ID . "&action=methods&op=add\" METHOD=\"POST\">\n"
						  ."<TABLE WIDTH=\"100%\">\n"
						  ."<TR><TD COLSPAN=\"2\"><B>Add Payment Method</B></TD></TR>"
						  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
						  ."<TR><TD>Payment Method:</TD><TD><INPUT TYPE=\"text\" NAME=\"method\" SIZE=\"30\"></TD></TR>\n"
						  ."<TR><TD>Fee:</TD><TD><INPUT TYPE=\"text\" NAME=\"fee\" SIZE=\"30\"></TD></TR>\n"
						  ."<TR><TD>Minimum:</TD><TD><INPUT TYPE=\"text\" NAME=\"minimum\" SIZE=\"30\"></TD></TR>\n"
						  ."<TR><TD>Active:</TD><TD><SELECT NAME=\"active\" SIZE=\"1\"><OPTION VALUE=\"no\">No</OPTION><OPTION VALUE=\"yes\" selected>Yes</OPTION></SELECT></TD></TR>\n"
						  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>\n"
						  ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"submit\" value=\"Add Payment Method\"></TD></TR>\n"
						  ."</TABLE></FORM>";
				
				$main->printText($text);
			}
		}
		else
		{
			$text	= "<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\">\n"
					 ."<TR BGCOLOR=\"#D3D3D3\">\n"
					 ."<TD>Payment Method</TD><TD>Fee</TD><TD>Minimum</TD><TD>Active</TD><TD>Action</TD></TR>\n";
			
			$db->Query("SELECT id, method, fee, minimum, active FROM payment_methods ORDER BY method ASC");
			
			while($row = $db->NextRow())
			{
				$text	.= "<TR BGCOLOR=\"#EAEAEA\">\n"
						  ."<TD>" . $row["method"] . "</TD><TD>" . _ADMIN_CURRENCY . " " . $row["fee"] . "</TD><TD>" . _ADMIN_CURRENCY . $row["minimum"] . "</TD><TD>" . $row["active"] . "</TD>\n"
						  ."<TD><A HREF=\"" . _ADMIN_URL . "/payments.php?sid=" . $session->ID . "&action=methods&op=delete&mid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/del.gif\" ALT=\"Delete\" BORDER=\"0\"></A>\n"
						  ."<A HREF=\"" . _ADMIN_URL . "/payments.php?sid=" . $session->ID . "&action=methods&op=edit&mid=" . $row["id"] . "\"><IMG SRC=\"" . _SITE_URL . "/inc/img/edit.gif\" ALT=\"Edit/View\" BORDER=\"0\"></A></TD></TR>\n";
			}
			
			$text	.= "</TABLE><BR><TABLE WIDTH=\"100%\"><TR><TD><A HREF=\"" . _ADMIN_URL . "/payments.php?sid=" . $session->ID . "&action=methods&op=add\">Add payment method</A></TD></TR></TABLE>\n";
			
			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "history")
	{
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			if(!isset($_POST["fields"]) || !isset($_POST["filetype"]))
				exit($error->Report("Payment Manager", "You have to fill out the form."));
			
			$db->Query("SELECT id FROM payments WHERE method='" . $_POST["method"] . "' AND paid='yes'");
			
			if($db->NumRows() == 0)
			{
				$method	= $db->Fetch("SELECT method FROM payment_methods WHERE id='" . $_POST["method"] . "'");
				
				exit($error->Report("Payment Manager", "There are no paid payments with the method \"$method\" yet."));
			}
			
			header("Content-Type: application/octet-stream");
			header("Content-Disposition: attachment; filename=history." . $_POST["filetype"]);
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Pragma: public");
			
			if($_POST["filetype"] == "csv")
			{
				$sep1	= ",";
				$sep2	= ",";
			}
			else
			{
				$sep1	= "\t ";
				$sep2	= "\t";
			}
			
			$content	= "";
			$sql		= "";
			$fields		= $_POST["fields"];
			$total		= count($fields);
			$i			= 1;
			
			foreach($fields AS $field => $value)
			{
				$content	.= "$field";
				$sql		.= "$field";
				
				if($content && $total != $i)
				{
					$content	.= $sep1;
					$sql		.= ",";
				}
				
				$i++;
			}
			
			$content	.= "\r\n";
			
			$db->Query("SELECT $sql FROM payments WHERE method='" . $_POST["method"] . "' AND paid='yes' ORDER BY dateStamp " . $_POST["direction"]);
			
			while($row = $db->NextRow())
			{
				$j	= 1;
				
				foreach($row AS $name => $value)
				{
					if(!is_numeric($name))
					{
						if($fields[$name])
						{
							if($name == "dateStamp")
								$value	= date(_SITE_DATESTAMP . " h:i:s", $value);
							
							$content	.= "\"$value\"";
							
							if($content && $total != $j)
								$content	.= $sep2;
							
							$j++;
						}
					}
				}
				
				$content	.= "\r\n";
			}
			
			echo $content;
		}
		else
		{
			$text		= "<FORM ACTION=\"" . _ADMIN_URL . "/payments.php?sid=" . $session->ID . "&action=history\" METHOD=\"post\">\n"
						 ."<TABLE WIDTH=\"100%\">\n"
						 ."<TR><TD COLSPAN=\"2\" ALIGN=\"center\"><B>Download Payments History</B></TD></TR>"
						 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
						 ."<TR><TD COLSPAN=\"2\">Check the boxes next to the fields you want to download. All checked fields will be included in your downloadable log.</TD></TR>\n"
						 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
						 ."<TR><TD VALIGN=\"top\" WIDTH=\"50%\"><TABLE WIDTH=\"100%\">"
						 ."<TR><TD><B>Fields</B></TD></TR>"
						 ."<TR><TD WIDTH=\"100%\"><INPUT TYPE=\"checkbox\" NAME=\"fields[account]\" checked> Account</TD></TR>\n"
						 ."<TR><TD WIDTH=\"100%\"><INPUT TYPE=\"checkbox\" NAME=\"fields[batchnr]\" checked> Batch Number</TD></TR>\n"
						 ."<TR><TD WIDTH=\"100%\"><INPUT TYPE=\"checkbox\" NAME=\"fields[credits]\" checked> Credits</TD></TR>\n"
						 ."<TR><TD WIDTH=\"100%\"><INPUT TYPE=\"checkbox\" NAME=\"fields[dateStamp]\" checked> Date/Time</TD></TR>\n"
						 ."<TR><TD WIDTH=\"100%\"><INPUT TYPE=\"checkbox\" NAME=\"fields[uid]\" checked> User ID</TD></TR>\n"
						 ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
						 ."<TR><TD><B>Direction</B></TD></TR>"
						 ."<TR><TD><INPUT TYPE=\"radio\" NAME=\"direction\" VALUE=\"asc\" checked> Ascending</TD></TR>"
						 ."<TR><TD><INPUT TYPE=\"radio\" NAME=\"direction\" VALUE=\"desc\"> Descending</TD></TR>"
						 ."</TABLE></TD><TD VALIGN=\"top\" WIDTH=\"50%\"><TABLE WIDTH=\"100%\">\n"
						 ."<TR><TD><B>Payment Method</B></TD></TR>"
						 ."<TR><TD><SELECT NAME=\"method\" SIZE=\"1\">\n";
			
			$db->Query("SELECT id, method FROM payment_methods WHERE active='yes'");
			
			while($row = $db->NextRow())
			{
				$text		.= "<OPTION VALUE=\"" . $row["id"] . "\">" . $row["method"] . "</OPTION>\n";
			}
			
			$text		.= "</SELECT></TD></TR></TD><TD>"
						  ."<TR><TD>&nbsp;</TD></TR>"
						  ."<TR><TD><B>Download File Types</B></TD></TR>"
						  ."<TR><TD><INPUT TYPE=\"radio\" NAME=\"filetype\" VALUE=\"csv\" checked> Comma delimited file (for use in any spreadsheet application)</TD></TR>"
						  ."<TR><TD><INPUT TYPE=\"radio\" NAME=\"filetype\" VALUE=\"txt\"> Tab delimited file</TD></TR>"
						  ."</TABLE></TD></TR>\n"
						  ."<TR><TD COLSPAN=\"2\">&nbsp;</TD></TR>"
						  ."<TR><TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" NAME=\"submit\" VALUE=\"Download\"></TD></TR>\n"
						  ."</TABLE></FORM>";
			
			$main->printText($text);
		}
	}
	elseif($_GET["action"] == "paypal")
	{
		$db->Query("SELECT id FROM payments WHERE paid='no' AND method='2'");
		
		if($db->NumRows() == 0)
			exit($error->Report("Payment Manager", "There are no unpaid payments with the method \"Paypal\" yet."));
		
		header("Content-Type: application/octet-stream");
		header("Content-Disposition: attachment; filename=masspay.txt");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: public");
		
		$db->Query("SELECT account, credits, uid FROM payments WHERE paid='no' AND method='2'");
		
		while($row = $db->NextRow())
		{
			$content	.= $row["account"] . "\t" . _ADMIN_CURRENCY . number_format($row["credits"], 2) . "\r\n";
		}
		
		echo $content;
	}
	elseif($_GET["action"] == "transfers")
	{
		$db->Query("SELECT id FROM actions WHERE type='transfer_to' OR type='ptransfer_to'");
		
		$count	= $db->NumRows();
		
		$start	= (isset($_GET["start"])) ? intval($_GET["start"]) : 0;
		
		$text	= "<TABLE WIDTH=\"100%\" STYLE=\"border: 1 solid #EAEAEA;\" BGCOLOR=\"#F0F0F0\">\n"
				 ."<TR BGCOLOR=\"#D3D3D3\">\n\n"
				 ."<TD>Transfer From</TD><TD>Transfer To</TD><TD>Credit Type</TD><TD>Credits</TD><TD>Transfer Date</TD></TR>\n";
		
		if($count == 0)
			$text	.= "<TR BGCOLOR=\"#EAEAEA\"><TD COLSPAN=\"5\">There are no transfers.</TD></TR>\n";
		else
		{
			$db->Query("SELECT id, uid, aid, c_type, credits, dateStamp FROM actions WHERE type='transfer_to' OR type='ptransfer_to' ORDER BY dateStamp DESC LIMIT $start, 30");
			
			while($row = $db->NextRow())
			{
				$email1	= $db->Fetch("SELECT email FROM users WHERE id='" . $row["uid"] . "'", 2);
				$email2	= $db->Fetch("SELECT email FROM users WHERE id='" . $row["aid"] . "'", 2);
				
				$email1	= $email1 == "" ? "unknown" : "<A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=edit&uid=" . $row["uid"] . "\">$email1</A>";
				$email2	= $email2 == "" ? "unknown" : "<A HREF=\"" . _ADMIN_URL . "/memberlist.php?sid=" . $session->ID . "&action=edit&uid=" . $row["aid"] . "\">$email2</A>";
				
				$dec	= $row["c_type"] == "points" ? 2 : 4;
				
				$text	.= "<TR BGCOLOR=\"#EAEAEA\">\n"
						  ."<TD>$email1</A></TD><TD>$email2</TD><TD>" . $row["c_type"] . "</TD>\n"
						  ."<TD>" . number_format($row["credits"], $dec) . "</TD>"
						  ."<TD>" . date(_SITE_DATESTAMP, $row["dateStamp"]) . "</TD></TR>\n";
			}
			
			$text	.= "</TABLE><BR>\n";	
		}
		
		$text	.= "<TABLE WIDTH=\"100%\"><TR><TD>" . $main->GeneratePages(_ADMIN_URL . "/payments.php?sid=" . $session->ID . "&action=transfers", $count, 30, $start) . "</TD></TR></TABLE>";
		
		$main->printText($text);
	}
	else
		header("Location: " . _ADMIN_URL . "/payments.php?sid=" . $session->ID . "&action=methods");

?>