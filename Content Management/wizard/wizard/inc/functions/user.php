<?php
/*  
    Registration and Login 
    This script were adapted from an article by Tim Perdue on PHP Builder (www.phpbuilder.com)
    Modifications (c) 2005 Philip Shaddock, www.wizardinteractive.com
	This file is part of the Wizard Site Framework.

    This file is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    It is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with the Wizard Site Framework; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/	

//limit the hash to 135 alphanumeric characters or you will produce an error
$hidden_hash_var='ert4aserasdfyurb';


$LOGGED_IN=false;
//clear it out in case someone sets it in the URL or something
unset($LOGGED_IN);


function user_isloggedin() {
    
	global $username,$id_hash,$hidden_hash_var,$LOGGED_IN;
	//have we already run the hash checks? 
	//If so, return the pre-set var
	if (isset($LOGGED_IN)) {
		return $LOGGED_IN;
	}
	$username = $_COOKIE['username']; 
	$id_hash = $_COOKIE['id_hash'];
	if ($username && $id_hash) {
	    
		$hash=md5($username.$hidden_hash_var);
		if ($hash == $id_hash) {
		    $LOGGED_IN=true;
			return true;
		} else {
		    $LOGGED_IN=false;
			return false;
		}
	} else {
		$LOGGED_IN=false;
		return false;
	}
}

function user_login($username,$password,$pageid) {

	global $message;
	if (!$username || !$password) {
		$message .=  USERMISSING; //username or password not entered
		$location = CMS_WWW . "/templates/forms/login.php?message=$message";
		header("Location: $location");
		exit();
		
	} else {
		$username=strtolower($username);
		$password=strtolower($password);
		
		$db = new DB();
		$db->query("SELECT * FROM ". DB_PREPEND . "users WHERE username='$username' AND password='". md5($password) ."'");
		$count = $db->num_rows();
		$result= $db->next_record();
	
		
		if (!$result || $count < 1){
			$message .=  NOTUSERPASS; //username or password not correct or not found
			$location = CMS_WWW . "/templates/forms/login.php?message=$message";
			header("Location: $location");
			exit();
			} else {
			if ($result['is_confirmed'] == '1') {
				user_set_tokens($username);
				$db = new DB();
				
				
				
				
				$db->query("UPDATE ". DB_PREPEND . "users SET last_login=now(), logins=logins+1 WHERE username='$username' ");
				
				if ($pageid == "2") {
				    $location = CMS_WWW . "/admin.php";
					header("Location: $location");
					exit();
				}
				
				
				$location = CMS_WWW . "/index.php?id=$pageid";
				header("Location: $location");
				exit();
			} else {
				$message .=  NOTACTIVATED;
				$location = CMS_WWW . "/templates/forms/login.php?message=$message";
				header("Location: $location");
				exit();
			}
		}
	}
}

function user_logout() {
	setcookie('username','',(time()-2592000),'/','',0);
	setcookie('id_hash','',(time()-2592000),'/','',0);
	$username='';
	    $id  = $_GET['pageid'];
        $location = CMS_WWW . "/index.php?id=$id";
		header("Location: $location");
		exit(); 
}

function user_set_tokens($username_in) {
	global $hidden_hash_var,$username,$id_hash;
	
	if (!$username_in) {
		$message .=  TOKENS; //username missing when setting tokens (cookies)
		return false;
	}
	$username=strtolower($username_in);
	$id_hash= md5($username.$hidden_hash_var);
    
	setcookie('username',$username,(time()+2592000),'/','',0); // one month plus or minus actual server time
	setcookie('id_hash',$id_hash,(time()+2592000),'/','',0);
	
	
}

function user_confirm($hash,$email) {
	
	global $message,$hidden_hash_var;

	//verify that they didn't tamper with the email address
	$new_hash=md5($email.$hidden_hash_var);
	if ($new_hash && ($new_hash==$hash)) {
		//find this record in the db
		$db = new DB();
		$db->query("SELECT uid, confirm_hash, username FROM ". DB_PREPEND . "users WHERE confirm_hash='$hash'");
		$result=$db->next_record();
		$count = $db->num_rows();
		if (!$result || $count < 1) {
			$message .= NOHASH; //confirmation hash was not found
			return false;
		} else {
			
				
			$id = $result['uid'];
			
			//confirm the email and set account to activate unless admin required to approve
			$db2b = new DB();
			$db2b->query("SELECT user_approve FROM ". DB_PREPEND . "config WHERE id=1 "); 
			$regstatus = $db2b->next_record();
			if ($regstatus['user_approve'] == "on") { $isconfirm = 1; $message .= CONFIRMED; }
			else { $isconfirm = 0; $message = "Thank-you. Your registration will be approved soon.";}
			$db2b->close();
			
			$db = new DB();
			$db->query("UPDATE ". DB_PREPEND . "users SET email='$email',is_confirmed='$isconfirm' WHERE confirm_hash='$hash'");
			//this assigns the user to group 3 (registered)
			//comment this line out if you do not want to have the user assigned to the "registered" group
			$db->query("INSERT INTO ". DB_PREPEND . "groupusers (uid,gid) VALUES ('$id','3')");
			
			return true;
		}
	} else {
		$message = CONFIRMFAILED; //failed to confirm
		return false;
	}
}

function user_change_password ($new_password1,$new_password2,$username,$old_password) {

	global $message;
	
	if ($new_password1 == "" || $new_password2 == "" || $username == "" || $old_password == "") {
	    $message = FILLALL;
		$location = CMS_WWW . "/templates/forms/changepass.php?message=$message";
		header("Location: $location");
		exit();
	}
	
		
	//new passwords present and match?
	if ($new_password1 && ($new_password1==$new_password2)) {
		//is this password long enough?
		if (valid_password($new_password1)) {
			//all vars are present?
			if ($username && $old_password) {
				//lower case everything
				$username=strtolower($username);
				$old_password=strtolower($old_password);
				$new_password1=strtolower($new_password1);
				$db = new DB();
				$db->query("SELECT * FROM ". DB_PREPEND . "users WHERE username='$username' AND password='". md5($old_password) ."'");
				$result=$db->next_record();
				$count=$db->num_rows();
				if (!$result || $count < 1) {
					$message .= NOTUSERPASS;
					$location = CMS_WWW . "/templates/forms/changepass.php?message=$message";
					header("Location: $location");
					exit();
				} else {
					$db = new DB();
					$result = $db->query("UPDATE ". DB_PREPEND . "users SET password='". md5($new_password1). "' ".
						"WHERE username='$username' AND password='". md5($old_password). "'");
					$count = $db->affected_rows();
					if (!$result || $count < 1) {
						$message .= NOTUSERPASS . "db ERROR";
						$location = CMS_WWW . "/templates/forms/changepass.php?message=$message";
						header("Location: $location");
						exit();
					} else {
						$message .= CHANGECONFIRMED;
						$location = CMS_WWW . "/templates/forms/changepass.php?message=$message";
						header("Location: $location");
						exit();
					}
				}
			} else {
				$message .= NOTUSERPASS;
				$location = CMS_WWW . "/templates/forms/changepass.php?username=$username&message=$message";
				header("Location: $location");
				exit();
			}
		} else {
			$message .= TRYAGAIN;
			$location = CMS_WWW . "/templates/forms/changepass.php?username=$username&message=$message";
			header("Location: $location");
			exit();
		}
	} else {
		$message .= MATCH; //passwords do not match
		$location = CMS_WWW . "/templates/forms/changepass.php?username=$username&message=$message";
		header("Location: $location");
		exit();
	}
}

function user_lost_password ($email,$username) {
    
	global $message,$hidden_hash_var;
	if ($email && $username) {
		$username=strtolower($username);
		
		$db = new DB();
		$db->query("SELECT * FROM ". DB_PREPEND . "users WHERE username='$username' AND email='$email'");
		$result=$db->next_record();
		$count = $db->num_rows();
		if (!$result || $count < 1) {
			//no matching username or email found found
			$message .= NOMATCH;
			$location = CMS_WWW . "/templates/forms/lostpass.php?message=$message";
			header("Location: $location");
			exit();
		} else {
			//create a secure, new password
			$new_pass=strtolower(substr(md5(time().$username.$hidden_hash_var),1,14));

			//update the database to include the new password
			$db = new DB();
			$db->query("UPDATE ". DB_PREPEND . "users SET password='". md5($new_pass) ."' WHERE username='$username'");
			
			
            $siteaddress = SITE_EMAIL;
			//send a simple email with the new password
			$confirmed = CHANGECONFIRMED; $passwordreset = PASSRESET; $changeitv = CHANGEIT; $fromv = FROM;
			mail ($email, $confirmed , $passwordreset . " ".$new_pass ."\n\n" . $changeitv, $fromv . ": " . $siteaddress ." ");
			$message .= PASSEMAILED; //Your new password was emailed to you.
			$location = CMS_WWW . "/templates/forms/login.php?message=$message";
			header("Location: $location");
			exit();
		}
	} else {
		$message = FILLALL; //missing username and email
		$location = CMS_WWW . "/templates/forms/lostpass.php?message=$message";
		header("Location: $location");
		exit();
	}
}

function user_change_email ($password1,$new_email,$change_user_name) {

    if ($password1 == "" || $new_email == "" || $change_user_name == "" ) {
	    $message = FILLALL;
		$location = CMS_WWW . "/templates/forms/changeemail.php?username=$change_user_name&email=$new_email&message=$message";
		header("Location: $location");
		exit();
	}


	global $message,$hidden_hash_var;
	if (validate_email($new_email)) {
		$hash=md5($new_email.$hidden_hash_var);
		//change the confirm hash in the db but not the email - 
		//send out a new confirm email with a new hash
		$username=strtolower($change_user_name);
		$password1=strtolower($password1);
		
				
		$db = new DB();
		$result=$db->query("UPDATE ". DB_PREPEND . "users SET confirm_hash='$hash' WHERE username='$username' AND password='". md5($password1) ."'");
		$count = $db->affected_rows();
		
		if (!$result || $count < 1) {
			$message .= USERMISSING;
			$location = CMS_WWW . "/templates/forms/changeemail.php?username=$username&email=$new_email&message=$message";
			header("Location: $location");
		} else {
			$message .= SUCCESS;
			user_send_confirm_email($new_email,$hash);
			$location = CMS_WWW . "/templates/forms/changeemail.php?message=$message";
			header("Location: $location");
		}
	} else {
		$message .= EMAILBAD;
		$location = CMS_WWW . "/templates/forms/changeemail.php?username=$username&email=$new_email&message=$message";
		header("Location: $location");
	}
}

function user_send_confirm_email($email,$hash) {
	/*
		Used in the initial registration function
		as well as the change email address function
	*/
    $siteemail = SITE_EMAIL;
	$sitename = SITE_NAME;
	$siteaddress = CMS_WWW;
	$thanks = THANKSREGISTER;
	$follow = "\n " . FOLLOWTHIS . " \n\n" ;
	$message = $thanks . " " . $sitename . $follow . $siteaddress . "/templates/forms/confirm.php?hash=$hash&email=". urlencode($email);
	mail($email, REGCONFIRM ,$message, "From:" . $sitename);
	
	$hasregisteredat = " " . HASREGISTERED . " ";
	$clickon = CLICK;
	//comment these lines out if you do not want to receive notification of new registrations
	$message = $email . $hasregisteredat . $sitename .":\n\n" .
	"\n " . $clickon . " \n\n" .
	$siteaddress . "/admin.php?id=2&item=27&sub=28&page=1&order=email";
	$email = $siteemail;
	mail($email, NEWUSER, $message, "From:" . $sitename);
}

function user_getid() {
	global $G_USER_RESULT;
	global $countresult;
	//see if we have already fetched this user from the db, if not, fetch it
	if (!$G_USER_RESULT) {
	    $db = new DB();
		$db->query("SELECT * FROM ". DB_PREPEND . "users WHERE username='" . user_getname() . "'");
		$G_USER_RESULT= $db->next_record();
		$countresult = $db->num_rows();
	}
	if ($G_USER_RESULT && $countresult > 0) {
		return $G_USER_RESULT['uid'];
	} else {
		return false;
	}
}

function user_getrealname() {
	global $G_USER_RESULT;
	global $countresult;
	//see if we have already fetched this user from the db, if not, fetch it
	if (!$G_USER_RESULT) {
		$db = new DB();
		$db->query("SELECT * FROM ". DB_PREPEND . "users WHERE username='" . user_getname() . "'");
		$G_USER_RESULT= $db->next_record();
		$countresult = $db->num_rows();
	}
	if ($G_USER_RESULT && $countresult > 0) {
	    $realname = $G_USER_RESULT['first_name'] . " " . $G_USER_RESULT['last_name'];
		return $realname;
	} else {
		return false;
	}
}

function user_getemail() {
	global $G_USER_RESULT;
	//see if we have already fetched this user from the db, if not, fetch it
	if (!$G_USER_RESULT) {
		$G_USER_RESULT=db_query("SELECT * FROM ". DB_PREPEND . "users WHERE username='" . user_getname() . "'");
	}
	if ($G_USER_RESULT && db_numrows($G_USER_RESULT) > 0) {
		return db_result($G_USER_RESULT,0,'email');
	} else {
		return false;
	}
}

function user_getname() {

	if (user_isloggedin()) {
		return  $_COOKIE['username'];
		
	} else {
		//look up the user some day when we need it
		return GUEST;
	}
}

function valid_username($username) {
	global $message;
	// no spaces
	if (strrpos($username,' ') > 0) {
		$message .= SPACES;
		return false;
	}

	// must have at least one character
	if (strspn($username,"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZßäÄüÜöÖ") == 0) {
		$message .= ALPHA;
		return false;
	}

	// must contain all legal characters
	if (strspn($username,"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_ßäÄüÜöÖ")
		!= strlen($username)) {
		$message .= ILLEGALCHAR;
		return false;
	}

	// min and max length
	if (strlen($username) < 5) {
		$message .= "Username is too short.";
		return false;
	}
	if (strlen($username) > 15) {
		$message .= USERLONG;
		return false;
	}

	// illegal names
	if (eregi("^((root)|(bin)|(daemon)|(adm)|(lp)|(sync)|(shutdown)|(halt)|(mail)|(news)"
		. "|(uucp)|(operator)|(games)|(mysql)|(httpd)|(nobody)|(dummy)"
		. "|(insert)|(select)|(update)|(delete)|(truncate)|(replace)|(handler)"
		. "|(www)|(cvs)|(shell)|(ftp)|(irc)|(debian)|(ns)|(download))$",$name)) {
		$message .= SYSTEMUSE; //Name reserved for system use.
		return 0;
	}
	
	return true;
}

function validate_email($email) {
	return (ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'. '@'. '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.' . '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email));
}

function valid_password($password) {
	global $message;
	if (strlen($password) < 5) {
		$message .= PASSINVALID;
		return false;
	}
	return true;
}

function is_memberof($gid) {
	$db = new DB();
		$db->query("SELECT gid FROM ". DB_PREPEND . "groupusers WHERE uid='" . user_getid() . "'");
		
		while($groupUser = $db->next_record()){
			if ($groupUser['gid'] == $gid) {
			    return true;
			}
			
		} // while
	   return false;
}

//returns group ids this member belongs to
function groups($uid) {
	$db = new DB();
		$db->query("SELECT DISTINCT gid FROM ". DB_PREPEND . "groupusers WHERE uid='$uid' ORDER BY gid");
		
		while($group = $db->next_record()){
			$groups[] = $group['gid'];
		} // while
	return $groups;
}

//returns array of group names, use $name = $groupNames[$gid][0]; to retrieve group name accroding to group id number (gid)
function group_names() {
			$db = new DB();
			$db->query("SELECT DISTINCT gid,name FROM ". DB_PREPEND . "groups ORDER BY gid ");
			$count = 0;
			while($i = $db->next_record()){
			    $groupN = $i['gid'];
				$groupNames[$groupN][] = $i['name'];
				$count++;
			} // while
			return $groupNames;
}
?>