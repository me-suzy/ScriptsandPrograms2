<?
include_once("settings.php");

// Redirect an ad
if($r)
{
	$c = "redirect";
}

switch($c)
{
	case "keywords":
		include_once("keywords.php");
		break;

	case "admin":
		include_once("admin.php");
		break;

	case "show":
		display_js($keyword, $limit, $border, $bgcolor);
		break;
		
	case "redirect":
		redirect($r, $m);
		break;
		
	case "login":
		login();
		break;

	case "logout":
		logout();
		break;

	case "forgot":
		forgot();
		break;
	
	case "contact":
		contact();
		break;

	case "register":
		register();
		break;

	case "funds":
		include_once("funds.php");
		break;
		
	case "report_detail":
		report_detail();
		break;

	case "ads":	
	case "main":	
	default:
		include_once("listings.php");
}

/*
print "<hr>";
print "<pre>";
print_r($user);
print $cookie_sid;
print "</pre>";
*/


#------------------------- REDIRECT A USER -----------------------------#
function redirect($ad="", $map=0)
{
	global $P, $S, $REMOTE_ADDR, $sql;
	
	// Do we have an ad?
	$a = lib_getsql("SELECT a.bid, b.url, a.keyword, a.id, a.client FROM admap a, ads b 
						WHERE a.ad=b.id AND a.status=1 AND b.status=1
						AND a.ad='$ad' AND a.id='$map'");
					
	$add = $a[0];
	if(!$add[id] || !$ad[url])
	{
		// Go to our home page if the ad does not exist
		header("Location: $P[url]");
		exit;
	}
	
	// If this is a duplicate click, don't log it
	$delay = time() - $S[dupclicks];
	$dups = lib_getsql("SELECT id FROM account WHERE adid='$ad' AND mapid='$map' 
							AND date > '$delay' AND ip='$REMOTE_ADDR' LIMIT 1");
	
	if($dups[0][id])
	{
		header("Location: $add[url]");
		exit;
	}
	
	// Insert the entry into the accounts
	$i = array();
	$i[date] = time();
	$i[adid] = $ad;
	$i[mapid] = $map;
	$i[clientid] = $add[client];
	$i[amount] = $add[bid] * -1;
	$i[ip] = $REMOTE_ADDR;
	lib_insert("account", $i);
	
	// Update the balance in the client record
	lib_getsql("UPDATE clients SET balance = balance - $add[bid] WHERE id='$ad[client]'");

	// Update the keyword click count
	lib_getsql("UPDATE keywords SET clicks=clicks+1 WHERE id='$add[keyword]'");
	
	// Update the click count in admap
	lib_getsql("UPDATE admap SET clicks=clicks+1 WHERE ad='$ad' AND id='$map'");
	
	// Finally, redirect the User
	header("Location: $add[url]");
	exit;
}



#------------------------- USER ACCOUNTS -------------------------------#
// Logout
function logout()
{
        global $P, $user, $f, $cookie_sid;

        $user = array();
        setcookie("cookie_sid","");
        lib_redirect("You are now logged out.","ad.php");
	return(0);
}


// Retrieve a lost password
function forgot()
{
	global $f, $HTTP_REFERER, $user, $HTTP_COOKIE_VARS, $S;

	#- Send the password to the e-mail address in question
	if($f[email])
	{
		if(lib_checkemail($f[email]))
		{
			$f[email] = strtolower($f[email]);
			$info = lib_getsql("SELECT * FROM users WHERE email='$f[email]'");
			if($info[0][password])
			{
				$login = $info[0][login];
				$password = $info[0][password];
				$email = $f[email];
				$name = $info[0][name];
				$msg  = "Dear $name,\n\n";
				$msg .= "Below is your login information that you so conveniently forgot :-)\n\n";
				$msg .= "Username: $login\n";
				$msg .= "Password: $password\n\n";
				$msg .= "Please keep it in a safe place in the future. If however, you lose it again; ";
				$msg .= "while we may frown upon you, our computer will cheerfully retrieve your login ";
				$msg .= "information and send it to this e-mail address again.\n\n";
				$msg .= "Best regards\nThe $S[org] Support Team";
				mail($f[email], "$S[org] -- Login Information", $msg, "From: $S[paypal_email]");

				lib_redirect("Your login information was sent to the e-mail address you entered. 
						Please check your e-mail to retrieve it.","ad.php?c=login",10);
			}
		}

		lib_redirect("Sorry, we could not verify your e-mail. Please try again or 
				send a message to $S[email]","ad.php?c=forgot",10);
	}

	#- Show the form
	$out = "<table width=100% border=0 cellpadding=0 cellspacing=4>
		<form method=post>
		<font color=red class=contentmedium>$errormsg</font>
		<tr><td colspan=2><font size=+1><b>Retrieve Your Login Information</b></font></td></tr>
		<tr><td width=10%>&nbsp;</td><td>&nbsp;</td></tr>
		<tr><td colspan=2 class=c>Please enter the e-mail you used to register with.</td></tr>
		<tr><td colspan=2 class=c><input type=text name=f[email] width=30></td></tr>
		<tr><td colspan=2><input type=submit value=Retrieve></td></tr>
		<input type=hidden name=c value=forgot>
		</form>
		</table>";
        lib_main($out,"Retrieve your login information");
        exit;
}


#- Login a user
function login()
{
        global $f, $HTTP_REFERER, $user, $HTTP_COOKIE_VARS, $S;

        if(($f[login] && $f[password]) || $HTTP_COOKIE_VARS['cookie_sid'])
        {
                if($f[login] && $f[password])
                {
                        $u = lib_getsql("SELECT * FROM clients WHERE email='$f[login]'");
                        if($u[0][id] && $u[0][password] == $f[password])
                        {
                                $user = $u[0];
                                if($f[cookie])
                                {
                                        setcookie("cookie_sid",$u[0]['sid'],time()+(86400*360),"/","",0);
                                }
				
								lib_redirect("You are now logged in.","ad.php",1);
                        }
			else
			{
				$f[errormsg] = "<li> Invalid username or password";
			}
                }
                elseif($HTTP_COOKIE_VARS['cookie_sid'])
                {
                        $zid = $HTTP_COOKIE_VARS['cookie_sid'];
                        $u = lib_getsql("SELECT * FROM clients WHERE sid='$zid'");
                        if($u[0][id])
                        {
                                $user = $u[0];
				header("Location: ad.php");
                        }
                }
        }

        #- Show the form
		if(!$f[url])
			$f[url] = $HTTP_REFERER;
			
	$out = "<table width=100% border=0 cellpadding=0 cellspacing=4>
		<form method=post>
		<font color=red class=contentmedium>$errormsg</font>
		<tr><td colspan=2><font size=+1><b>Login</b></font></td></tr>
		<tr><td width=10%>&nbsp;</td><td>&nbsp;</td></tr>
		<tr><td class=content><b>E-mail: </b></td><td>
			<input type=text name=f[login] class=content size=20 maxlength=32>
		</td></tr>
		<tr><td class=content valign=top><b>Password: </b></td><td>
			<input type=password name=f[password] class=content size=20 maxlength=32>
			<br>&nbsp;
		</td></tr>
		<tr><td>&nbsp;</td><td><input type=submit value=Login class=contentmedium></td></tr>
		<tr><td class=content colspan=2>
			&nbsp;<br>
			<a href=ad.php?c=register class=content>I want to register as a new user</a><br>
			<a href=ad.php?c=forgot class=content>I forgot my password</a>
			</td></tr>
		</td>
		<input type=hidden name=c value=login>
		<input type=hidden name=f[url] value=\"$f[url]\">
		</form>
		</table>";
        lib_main($out,"Login");
        exit;
}

#- Register a user
function register()
{
        global $f, $HTTP_REFERER, $HTTP_COOKIE_VARS, $user,$P, $sql, $errormsg, $S;

        if($f[password] && $f[confirmpassword] && $f[email] && $f[name])
        {
                $f[login] = strtolower($f[login]);
                $f[email] = strtolower($f[email]);

                if($f[confirmpassword] != $f[password])
                        $f[errormsg] .= "<li> Your passwords did not match";
                if(strlen($f[password]) < 6)
                        $f[errormsg] .= "<li> Your password should be at least 6 letters/numbers long";
                if(!lib_checkemail($f[email]))
                        $f[errormsg] .= "<li> Your e-mail could not be validated. Please try again";
                if(lib_getsql("SELECT id FROM clients WHERE email='$f[email]'"))
                        $f[errormsg] .= "<li> Your e-mail could not be used. Please chose another";
                if(!preg_match("/[a-z|A-Z|0-9]/",$f[password]))
                        $f[errormsg] .= "<li> Your password should contain only letters or numbers";

                if(!$f[errormsg])
                {
                        $i = array();
			$i['sid'] = md5(uniqid(""));
                        $i[password] = addslashes($f[password]);
                        $i[name] = addslashes($f[name]);
                        $i[email] = $f[email];
			$i[status] = 1;
			$i[url] = $f[url];
			$i[org] = $f[org];
			$i[date] = time();
			$i[balance] = $S[freemoney];
                        $id = lib_insert("clients",$i);
						
			if($S[freemoney])
			{
				$j = array();
				$j[date] = time();
				$j[clientid] = $id;
				$j[amount] = $S[freemoney];
				$j[ip] = $REMOTE_ADDR;
				lib_insert("account", $j);
				
				$free = "and \$$S[freemoney].00 was added to your account!";
			}

			mail($S[paypal_email], "adRevenue Registration!", 
				"Hello Mr. Admin Sir!\nAnother adRevenue registration from:\n$i[login]\n$i[name]"); 
                        lib_redirect("You were successfully registered.<br>$free<br>Continue to login!.","ad.php?c=login",60);
                }
        }

        #- Show the form
	$out = "<font color=red class=contentmedium>$f[errormsg]</font>
		<table width=100% border=0 cellpadding=4 cellspacing=0>
		<form method=post>	
		<tr><td colspan=2><font size=+1><b>Registration</b></font></td></tr>
		<tr><td colspan=2 class=content>
		<br>
		Thanks for using us to help drive traffic to your website!<br>
		Please fill out the short and simple registration form below.<p>&nbsp;
		</td></tr>

		<tr><td class=content width=10% nowrap><b>Your Name:</b></td>
			<td><input type=text name=f[name] value=\"$f[name]\" size=30>
		</td></tr>

		<tr><td class=content width=10% nowrap><b>Organization:</b></td>
			<td><input type=text name=f[org] value=\"$f[org]\" size=40>
		</td></tr>

		<tr><td class=content width=10% nowrap><b>Email:</b></td>
			<td><input type=text name=f[email] value=\"$f[email]\" size=20>
		</td></tr>

                <tr><td class=content><b>Password:</b></td>
			<td><input type=password name=f[password] value=\"$f[password]\" size=15>
		</td></tr>

                <tr><td class=content nowrap><b>Confirm Password:</b></td>
			<td><input type=password name=f[confirmpassword] 
			value=\"$f[confirmpassword]\" size=15>
		</td></tr>

		<tr><td>&nbsp;</td><td><input type=submit value=Register! class=contentmedium></td></tr>
		<input type=hidden name=c value=register>
		</form>
		</table>";

        lib_main($out,"Register");
        return(0);
}#-end register


#- Contact us
function contact()
{
	global $f, $user, $S;

	if($f[subject] && $f[message] && $f[email])
	{
		mail($S[paypal_email], stripslashes($f[subject]), stripslashes($f[message]), "From: <$f[email]>");		
		lib_redirect("<b>Thank you!</b><br>Your message was sent","ad.php",2);
		exit;
	}

	$f[email] = $user[email];

	$out .= "
		<font size=+1><b>Contact Us</b></font><br>&nbsp;<br>
		Having a problem?<br>Send us a message and we will get back to you as soon as possible.<p>

		<table width=100% border=0 cellspacing=0 cellpadding=3>
			<form method=post>
			<tr>
				<td width=1><font size=2><b>From: </b></font></td>
				<td><input type=text name=f[email] value=\"$f[email]\" size=20></td>
			</tr>
			<tr>
				<td width=1><font size=2><b>Subject: </b></font></td>
				<td><input type=text name=f[subject] value=\"$f[subject]\" size=40></td>
			</tr>
			<tr>
				<td width=1 valign=top><font size=2><b>Subject: </b></font></td>
				<td><textarea name=f[message] rows=8 cols=45 wrap=virtual>$f[message]</textarea></td>
			</tr>
			<tr>
				<td width=1 valign=top>&nbsp;</td>
				<td valign=top><input type=submit value=Send></td>
			</tr>
			<input type=hidden name=c value=contact>
			</form>
		</table>";

	lib_main($out);
	return(0);
}


?>
