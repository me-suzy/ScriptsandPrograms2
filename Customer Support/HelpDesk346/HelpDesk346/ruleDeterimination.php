<?php
	//Revised on August 21, 2005
	//Revised by JF
	//Revision Number 4
	
	/*
		@name		PerformUpdateAction
		@descrip	Execute the Rule assoicated with updating a ticket
		@param		intval		Int representing the rule to execute
		@param		id			Int representing the ID of the Ticket Updated
	*/
	function PerformUpdateAction($intval, $id, $type, $staff, $describe, $mainDate, $res, $FirstName)
	{
		//build message and headers
		global $OBJ;
		$t = new Ticket($id);
		$describe = ($OBJ->get('email_type', 'intval')) ? $t->get('descrip', 'stripslashes') : stripslashes($t->get('descrip', 'nl2br'));
		$mainDate = $t->get('mainDate');
		switch ($OBJ->get('email_type', 'intval'))
		{
			case 0:
				  $message= "<table width=100%><tr><td align=left><strong>Solution :</strong></td></tr>";
				  $message.= "<tr><td align=left>$res</td></tr></table>";
			      $message ="<font face='Arial, Helvetica, sans-serif' size='2'><strong>Dear " . $t->get('FirstName', 'stripslashes') . ",</strong><br><br>";
				  $message.="New Solution is added to your help desk call<br><br></font>";
			      $message.="<tr><td><table width=100%>";
				  $message.="<tr><td width=50%><font face='Arial, Helvetica, sans-serif' size='2'>Ticket Number&nbsp;&nbsp;</font></td><td><font face='Arial, Helvetica, sans-serif' size='2'>	<strong>" . $t->get('id', 'intval') . "</strong></font></td></tr>";   
				  $u = $t->get('staff');
				  $staff = (intval($u->get('id')) > 0) ? $u->get('user') : 'None';
				  $message.="<tr><td width=50%><font face='Arial, Helvetica, sans-serif' size='2'>Technical Staff Assigned to call &nbsp;&nbsp;</font></td><td><font face='Arial, Helvetica, sans-serif' size='2'>	<strong>$staff</strong></font></td></tr>";   
			      $message.="<tr><td width=50%><font face='Arial, Helvetica, sans-serif' size='2'>Description of Call&nbsp;&nbsp;</font></td><td><font face='Arial, Helvetica, sans-serif' size='2'>	<strong>$describe</strong></font></td></tr>";   
				  $message.="<tr><td width=50%><font face='Arial, Helvetica, sans-serif' size='2'>Date&nbsp;&nbsp;</font></td><td><font face='Arial, Helvetica, sans-serif' size='2'>	<strong>$mainDate</strong></font></td></tr>";   
				  $message.="</table></td></tr></font></table>";
				  $message.="<br><br><font face='Arial, Helvetica, sans-serif' size='2'>Thanks<br><strong>Technical Team</strong><br>";
				  
				  $headers  = "MIME-Version: 1.0\r\r";
				  $headers .= "Content-type: text/html; charset=iso-8859-1\n\n";
				  $headers .= "From:Help Desk Call\n";
				  break;
			case 1:
				  $message =  "Dear $FirstName\n";
				  $message .= "Ticket Number: $id\n";
				  $message .= "Technical Staff Assigned to call: $staff\n";
				  $message .= "Description of Call: $describe\n";
				  $message .= "Date: $mainDate\n\n";
				  $message .= "Thanks,\nTechnical Team\n";
				  
				  $headers = "From: " . $OBJ->get('hd_from', 'stripslashes') . "\r\n";
				  break;
		}
		
		switch ($OBJ->get('hdemail_up', 'intval'))
		{
			case 1:
				$q = "select Email from " . DB_PREFIX . "data where ID=$id";
				$s = mysql_query($q) or die(mysql_error());
				mail(mysql_result($s, 0, 'Email'), "Help Desk Ticket Updated", $message, $headers);
				break;
			case 2:
				//not sure here
				$q = "select Email from " . DB_PREFIX . "data where ID=$id";
				$s = mysql_query($q) or die(mysql_error());
				mail(mysql_result($s, 0, 'Email'), "Help Desk Ticket Updated", $message, $headers);
				
				$s = mysql_query("select email_addr from " . DB_PREFIX . "accounts where User = '$staff' LIMIT 1");
				if (mysql_num_rows($s)) mail(mysql_result($s, 0), "Help Desk Ticket Updated", $message, $headers);
			case 3:
				$q = "select Email from " . DB_PREFIX . "data where ID=$id";
				$s = mysql_query($q) or die(mysql_error());
				mail(mysql_result($s, 0, 'Email'), "Help Desk Ticket Updated", $message, $headers);
				
				$q = "select email_addr from " . DB_PREFIX . "accounts where securityLevel = 2";
				$s = mysql_query($q) or die(mysql_error());
				while ($r = mysql_fetch_assoc($s))
					mail($r['email_addr'], "Help Desk Ticket Updated", $message, $headers);
				break;
			case 4:
				$q = "select Email from " . DB_PREFIX . "data where ID=$id";
				$s = mysql_query($q) or die(mysql_error());
				mail(mysql_result($s, 0, 'Email'), "Help Desk Ticket Updated", $message, $headers);
				
				$q = "select email_addr from " . DB_PREFIX . "accounts";
				$s = mysql_query($q) or die(mysql_error());
				while ($r = mysql_fetch_assoc($s))
					mail($r['email_addr'], "Help Desk Ticket Updated", $message, $headers);
				break;
			case 5:
				$q = "select email_addr from " . DB_PREFIX . "accounts";
				$s = mysql_query($q) or die(mysql_error());
				while ($r = mysql_fetch_assoc($s))
					mail($r['email_addr'], "Help Desk Ticket Updated", $message, $headers);
				break;
			case 6:
				$q = "select email_addr from " . DB_PREFIX . "accounts where securityLevel = 2";
				$s = mysql_query($q) or die(mysql_error());
				while ($r = mysql_fetch_assoc($s))
					mail($r['email_addr'], "Help Desk Ticket Updated", $message, $headers);
				break;
			case 7:
				$q = "select email_addr from " . DB_PREFIX . "accounts where securityLevel = 1";
				$s = mysql_query($q) or die(mysql_error());
				while ($r = mysql_fetch_assoc($s))
					mail($r['email_addr'], "Help Desk Ticket Updated", $message, $headers);
				break;
		}
	}
	
	/*
		@name		PerformCloseAction
		@descrip	Execute the Rule associated with closing a ticket
		@param		intval		Int representing the rule to execute
		@param		id			Int representing the ID of the Ticket Updated
		@param		message		Message to be sent
		@param		header		Header Information
		@notes		Message is passed in as part of the parameter list - the type is determined in the parent
	*/
	function PerformCloseAction($intval, $type, $id)
	{
		global $OBJ;
		$t = new Ticket($id);
		$ID = $t->get('id');
		$u = $t->get('staff');
		$staff = (is_object($u) && intval($u->get('id')) > 0) ? $u->get('user') : 'None';
		switch ($OBJ->get('email_type', 'intval'))
		{
			case 0:
				  $message= "<table width=100%><tr><td align=left><strong>Solution:</strong></td></tr>";
				  $message.= "<tr><td align=left>$res</td></tr></table>";
			      $message ="<font face='Arial, Helvetica, sans-serif' size='2'><strong>Dear " . $t->get('FirstName', 'stripslashes') . ",</strong><br><br>";
				  $message.="The Following Ticket has Been Removed from the Database<br><br></font>";
			      $message.="<tr><td><table width=100%>";
				  $message.="<tr><td width=50%><font face='Arial, Helvetica, sans-serif' size='2'>Ticket Number&nbsp;&nbsp;</font></td><td><font face='Arial, Helvetica, sans-serif' size='2'>	<strong>$ID</strong></font></td></tr>";   
				  $message.="<tr><td width=50%><font face='Arial, Helvetica, sans-serif' size='2'>Technical Staff Assigned to call &nbsp;&nbsp;</font></td><td><font face='Arial, Helvetica, sans-serif' size='2'>	<strong>$staff</strong></font></td></tr>";   
				  $message.="</table></td></tr></font></table>";
				  $message.="<br><br><font face='Arial, Helvetica, sans-serif' size='2'>Thanks<br><strong>Technical Team</strong><br>";
				  
				  $headers  = "MIME-Version: 1.0\r\r";
				  $headers .= "Content-type: text/html; charset=iso-8859-1\n\n";
				  $headers .= "From:Help Desk Call\n";
				  break;
			case 1:
				  $message  = "Dear " . $t->get('FirstName', 'stripslashes') . "\n";
				  $message .= "The Ticket with the Following Information Has Been Closed\n";
				  $message .= "Ticket Number: " . $t->get('id', 'intval') . "id\n";
				  $message .= "Technical Staff Assigned to call: $staff\n";
				  $message .= "Description of Call: " . $t->get('descrip', 'stripslashes') . "\n";
				  $message .= "Date: " . $t->get('mainDate') . "\n\n";
				  $message .= "Thanks,\nTechnical Team\n";
				  
				  $headers  = "From: " . $OBJ->get('hd_from', 'stripslashes') . "\r\n";
				  break;
		}
		
		switch ($OBJ->get('hdemail_close', 'intval'))
		{
			case 1:
				mail($t->get('EMail'), "Help Desk Ticket Closed", $message, $headers);
				break;
			case 2:
				//first obtain the techs name and join it with a subselect of the account
				$s = mysql_query("select email_addr from " . DB_PREFIX . "accounts where User = '$staff' and email_addr <> '' LIMIT 1");
				if (mysql_num_rows($s))
					mail(mysql_result($s, 0, 'email_addr'), "Help Desk Ticket Closed", $message, $header)  or die("Email Send Failed - Problem With Server");
			case 3:
				mail($EMail, "Help Desk Ticket Closed", $message, $headers)  or die("Email Send Failed - Problem With Server");
				
				$q = "select email_addr from " . DB_PREFIX . "accounts where securityLevel = 2 and email_addr <> ''";
				$s = mysql_query($q) or die(mysql_error());
				if (mysql_num_rows($s))
					while ($r = mysql_fetch_assoc($s))
						mail($r['email_addr'], "Help Desk Ticket Closed", $message, $headers)  or die("Email Send Failed - Problem With Server");
				break;
			case 4:
				$q = "select email_addr from " . DB_PREFIX . "accounts where email_addr <> ''";
				$s = mysql_query($q) or die(mysql_error());
				if (mysql_num_rows($s))
					while ($r = mysql_fetch_assoc($s))
						mail($r['email_addr'], "Help Desk Ticket Closed", $message, $headers) or die("Email Send Failed - Problem With Server");
				break;
			case 5:
				$q = "select email_addr from " . DB_PREFIX . "accounts where email_addr <> ''";
				$s = mysql_query($q) or die(mysql_error());
				if (mysql_num_rows($s))
					while ($r = mysql_fetch_assoc($s))
						mail($r['email_addr'], "Help Desk Ticket Closed", $message, $headers)  or die("Email Send Failed - Problem With Server");
				break;
			case 6:
				$q = "select email_addr from " . DB_PREFIX . "accounts where securityLevel = 2 and email_addr <> ''";
				$s = mysql_query($q) or die(mysql_error());
				if (mysql_num_rows($s))
					while ($r = mysql_fetch_assoc($s))
						mail($r['email_addr'], "Help Desk Ticket Closed", $message, $headers)  or die("Email Send Failed - Problem With Server");
				break;
			case 7:
				$q = "select email_addr from " . DB_PREFIX . "accounts where securityLevel = 1 and email_addr <> ''";
				$s = mysql_query($q) or die(mysql_error());
				if (mysql_num_rows($s))
					while ($r = mysql_fetch_assoc($s))
						mail($r['email_addr'], "Help Desk Ticket Closed", $message, $headers) or die("Email Send Failed - Problem With Server");
				break;
		}	
	}
?>