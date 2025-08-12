<?

if($user[email] != "admin")
	lib_redirect("Access Denied!", "ad.php",3);

	// Delete a user
	if($f[c] == "delete" && $f[id])
	{
		// Totally expunge this user
		lib_getsql("DELETE from account WHERE clientid='$f[id]'");
		lib_getsql("DELETE from ads WHERE client='$f[id]'");
		lib_getsql("DELETE from admap WHERE client='$f[id]'");	
		lib_getsql("DELETE from clients WHERE id='$f[id]'");

		lib_redirect("The customer was deleted.", "ad.php?c=admin", 2);
		exit;
	}

	// Manually adjust customer's balance
	if($f[c] == "balance" && $f[id])
	{
		$f[amount] = $f[amount] * 1;

		// Add to the accounts table
		if($f[amount] <> 0)
		{
			$i = array();
			$i[date] = time();
			$i[clientid] = $f[id];
			$i[amount] = $f[amount];
			$i[ip] = $REMOTE_ADDR;
			lib_insert("account", $i);
		}

		// Get the balance...
		$bal = lib_getsql("select sum(amount) as balance FROM account WHERE clientid='$f[id]'");
		$balance = iif(!$bal[0][balance],0,$bal[0][balance]);
		
		// Update the client's balance
		if($balance)
			lib_getsql("UPDATE clients SET balance=$balance WHERE id='$f[id]'");
		
		lib_redirect("The customer's balance was updated.", "ad.php?c=admin&cust=$f[id]",3);
		exit;
	}

	// Reset a customer's stats and account
	if($f[c] == "reset" && $f[id])
	{
		lib_getsql("UPDATE admap SET clicks=0, views=0 WHERE client='$f[id]'");

		lib_redirect("The clients click and view totals were reset.", "ad.php?c=admin&cust=$f[id]",3);
		exit;
	}

	// Suspend-Reinstate a customer
	if(($f[c] == "suspend" || $f[c] == "activate") && $f[id])
	{
		$status = iif($f[c]=="activate", 1, 0);
		lib_getsql("UPDATE clients SET status='$status' WHERE id='$f[id]'");

		$title  = iif($f[c] == "activate", "actvated", "suspended");

		lib_redirect("The client was $title", "ad.php?c=admin&cust=$f[id]",3);
		exit;
	}

	// Edit a user
	if($f[c] == "update" && $f[id] && $f[email] && $f[password])
	{
		$id = $f[id];
		unset($f[c]);
		unset($f[id]);

		lib_update("clients", "id", $id, $f);
		
		lib_redirect("The client was successfully updated", "ad.php?c=admin&cust=$id",3);
		exit;
	}


	// Show the control panel for a customer
	if($cust)
	{
		// Get the customer
		$client = lib_getsql("SELECT * FROM clients WHERE id='$cust'");
		$f = $client[0];

		if($client[0][status] == 0)
		{
			$status = "activate";
			$stitle = "<font color=red>Activate Account</font>";
		}
		else
		{
			$status = "suspend";
			$stitle = "Suspend Account";
		}

		$out  = "<font size=+1>Edit Customer</font><br>";

		// Customer menu
		$out .= "<font size=1>\n";
		$out .= "<a href=ad.php?c=admin><b>Customer List</b></a> | \n";
		$out .= "<a href=ad.php?c=admin&cust=$cust&f[c]=delete&f[id]=$cust>Delete</a> | \n";
		$out .= "<a href=ad.php?c=admin&cust=$cust&f[c]=reset&f[id]=$cust>Reset Stats</a> | \n";
		$out .= "<a href=ad.php?c=admin&cust=$cust&f[c]=$status&f[id]=$cust>$stitle</a> | \n";
		$out .= "<a href=#balance>Adjust Balance</a>\n";
		$out .= "</font><p>&nbsp;\n";

		$out .= "<table width=100% border=0 cellspacing=0 cellpadding=3>\n";
		$out .= "<form method=post>\n";
		$out .= "<tr>\n";
		$out .= "<td width=1><font size=2><b>Email:</b></td>\n";
		$out .= "<td><input type=text name=f[email] value=\"$f[email]\" size=30></td>\n";
		$out .= "</tr>\n";
		$out .= "<tr>\n";
		$out .= "<td width=1><font size=2><b>Password:</b></td>\n";
		$out .= "<td><input type=text name=f[password] value=\"$f[password]\" size=20></td>\n";
		$out .= "</tr>\n";
		$out .= "<tr>\n";
		$out .= "<td width=1><font size=2><b>Name:</b></td>\n";
		$out .= "<td><input type=text name=f[name] value=\"$f[name]\" size=40></td>\n";
		$out .= "</tr>\n";
		$out .= "<tr>\n";
		$out .= "<td width=1><font size=2><b>Organization:</b></td>\n";
		$out .= "<td><input type=text name=f[org] value=\"$f[org]\" size=30></td>\n";
		$out .= "</tr>\n";
		$out .= "<tr>\n";
		$out .= "<td width=1><font size=2><b>URL:</b></td>\n";
		$out .= "<td><input type=text name=f[url] value=\"$f[url]\" size=50></td>\n";
		$out .= "</tr>\n";
		$out .= "<tr><td colspan=2>&nbsp;</td></tr>\n";
		$out .= "<tr>\n";
		$out .= "<td width=1 valign=top><font size=2><b>Address:</b></td>\n";
		$out .= "<td><textarea name=f[address] cols=50 rows=3 wrap=virtual>$f[address]</textarea></td>\n";
		$out .= "</tr>\n";
		$out .= "<tr>\n";
		$out .= "<td width=1><font size=2><b>City:</b></td>\n";
		$out .= "<td><input type=text name=f[city] value=\"$f[city]\" size=30></td>\n";
		$out .= "</tr>\n";
		$out .= "<tr>\n";
		$out .= "<td width=1><font size=2><b>State:</b></td>\n";
		$out .= "<td><input type=text name=f[state] value=\"$f[state]\" size=20></td>\n";
		$out .= "</tr>\n";
		$out .= "<tr>\n";
		$out .= "<td width=1><font size=2><b>Zip:</b></td>\n";
		$out .= "<td><input type=text name=f[zip] value=\"$f[zip]\" size=10></td>\n";
		$out .= "</tr>\n";
		$out .= "<tr>\n";
		$out .= "<td width=1><font size=2><b>Phone:</b></td>\n";
		$out .= "<td><input type=text name=f[phone] value=\"$f[phone]\" size=20></td>\n";
		$out .= "</tr>\n";
		$out .= "<tr>\n";
		$out .= "<td width=1>&nbsp;</td>\n";
		$out .= "<td><input type=submit value=\"Update Customer\"></td>\n";
		$out .= "</tr>\n";
		$out .= "<input type=hidden name=f[id] value=\"$cust\">\n";
		$out .= "<input type=hidden name=cust value=\"$cust\">\n";
		$out .= "<input type=hidden name=f[c] value=\"update\">\n";
		$out .= "<input type=hidden name=c value=admin>\n";
		$out .= "</form>\n";
		$out .= "</table>";
		$out .= "<hr size=1>\n";

		$balance = number_format($f[balance],2);
		$out .= "<form method=get>";	
		$out .= "<a name=balance>\n";
		$out .= "<font size=3><b>Update Balance: \$$balance</b></font><br>\n";
		$out .= "<font size=1>To add to the customer's balance, enter a positive amount.<br>To reduce 
					the customer's balance, enter a negavive amount.</font><br>&nbsp;<br>\n";
		$out .= "<font size=2><b>Amount:</b></font>\n";
		$out .= "<input type=text name=f[amount] size=5 value=\"0.00\">\n";
		$out .= "<input type=submit value=\"Adjust\">\n";
		$out .= "<input type=hidden name=f[id] value=\"$cust\">\n";
		$out .= "<input type=hidden name=cust value=\"$cust\">\n";
		$out .= "<input type=hidden name=f[c] value=\"balance\">\n";
		$out .= "<input type=hidden name=c value=admin>\n";
		$out .= "</form>\n";

		lib_main($out, "Edit Customer: $f[name]");
		exit;
	}

	if($f[order] == "balance")
		$f[order] = "balance DESC";
	$order = iif($f[order],$f[order],"name");

	// List the users
	$clients = lib_getsql("SELECT * FROM clients ORDER BY $order");

	$out  = "<font size=+1><b>Manage Clients</b></font><br>&nbsp;\n";
	$out .= "<table width=100% border=0 cellspacing=0 cellpadding=0>\n";
	$out .= "<tr><td bgcolor=#CCCCCC>\n";
	$out .= "<table width=100% border=0 cellspacing=1 cellpadding=3>\n";
	$out .= "<tr>\n";
	$out .= "<td bgcolor=#EEEEEE><font size=2><b><a href=ad.php?c=admin&f[order]=name>Name</a></b></font></td>\n";
	$out .= "<td bgcolor=#EEEEEE><font size=2><b><a href=ad.php?c=admin&f[order]=email>E-mail</a></b></font></td>\n";
	$out .= "<td bgcolor=#EEEEEE><font size=2><b><a href=ad.php?c=admin&f[order]=org>Organization</a></b></font></td>\n";
	$out .= "<td bgcolor=#EEEEEE><font size=2><b>Status</b></font></td>\n";
	$out .= "<td bgcolor=#EEEEEE align=right><font size=2><b><a href=ad.php?c=admin&f[order]=balance>Balance</a></b></font></td>\n";
	$out .= "<td bgcolor=#EEEEEE>&nbsp;</td>\n";
	$out .= "</tr>\n";	

	if(count($clients))
	{
		foreach($clients as $rec)
		{
			$balance = number_format($rec[balance],2);
			$url = "<a href=ad.php?c=admin&cust=$rec[id]>$rec[name]</a>";	
			$rec[status] = iif($rec[status] == 1, "Active", "<font color=red>Suspended</font>");
			$balance = iif($rec[balance] <=0,"<font color=red>$balance</font>",$balance);
			$bgcolor = iif($bgcolor == "FFFFFF", "FFFFEE", "FFFFFF");
			
			$out .= "<tr>\n";
			$out .= "<td bgcolor=#$bgcolor title=\"Click to manage\"><font size=1>$url</font></td>\n";
			$out .= "<td bgcolor=#$bgcolor><a href=mailto:$rec[email]><font size=1>$rec[email]</font></a></td>\n";
			$out .= "<td bgcolor=#$bgcolor><font size=1>$rec[org]</font></td>\n";
			$out .= "<td bgcolor=#$bgcolor><font size=1>$rec[status]</font></td>\n";
			$out .= "<td bgcolor=#$bgcolor align=right><font size=1>$balance</font></td>\n";
			$out .= "<td bgcolor=#$bgcolor align=center><font size=1><a href=ad.php?c=login&f[password]=$rec[password]&f[login]=$rec[email]>Login</a></font></td>\n";
			$out .= "</tr>\n";	
		}
	}

	$out .= "</table>\n</td>\n</tr>\n</table>\n";

	lib_main($out, "Manage Clients");
	exit;
?>
