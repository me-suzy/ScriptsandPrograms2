<?php
/**************************************************************************
    FILENAME        :   authorization.php
    PURPOSE OF FILE :   Class for user authorization
    LAST UPDATED    :   08 June 2005
    COPYRIGHT       :   © 2005 CMScout Group
    WWW             :   www.cmscout.za.org
    LICENSE         :   GPL vs2.0
    
    

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
**************************************************************************/
?>
<?php
if (!defined('SCOUT_NUKE'))
    die("You have accessed this page illegally, please go use the main menu");
//require_once ('../config.php');
class auth
{
	// CHANGE THESE VALUES TO REFLECT YOUR SERVER'S SETTINGS
	var $dbhost;	// Change this to the proper DB HOST
	var $dbusername;	// Change this to the proper DB USERNAME
	var $dbpassword ;	// Change this to the proper DB USER PASSWORD
	var $dbname;	// Change this to the proper DB NAME
    var $dbprefix;

	function set_cookie($username) 
    {
		global $config;
        
		$uids = "";
 		$cookiename = $config['cookiename'];
		setcookie($cookiename, "", 0);
        
		$uids = md5($_SERVER['REMOTE_ADDR'] . $username . time());
		$cookieinfo = @serialize(array('uname' => $username, 'uid' => $uids, 'ip' => $_SERVER['REMOTE_ADDR']));
        
		setcookie ($cookiename, $cookieinfo, time() + $config['session_length']);
        
		return $uids;
	}
	
	function reset_cookie($username, $uid) 
    {
		global $config;
        
 		$cookiename = $config['cookiename'];
		$cookieinfo = @serialize(array('uname' => $username, 'uid' => $uid, 'ip' => $_SERVER['REMOTE_ADDR']));
        
		setcookie ($cookiename, $cookieinfo, time() + $config['session_length']);
        
		return true;
	}
	
	function read_cookies() 
    {
		global $config;
        
 		$info = "";
		$cookiename = $config['cookiename'];
        
        if(isset($_COOKIE[$cookiename]))
        {
            $cookieval = $_COOKIE[$cookiename];
            $cookieval = stripslashes($cookieval);
            $info = @unserialize($cookieval);
     		return $info;
        }
	}//read_cookies
	
	function get_active() 
    {
		global $config, $data;
        
        $sql = $data->select_query("onlineusers");
		$onlineuser = array();
		$onlineuser[0] = 0;
		$onlineuser[1] = 0;
        while ($temp = $data->fetch_array($sql))
        {      
            $timediff = time() - $temp['lastupdate'];
            if ($timediff >= $config['session_length']) 
			{
				$id = $temp['uid'];
				$data->delete_query("onlineusers", "uid='$id'", "", "", false);                
			} 
            elseif ($timediff >= $config['activetime']) 
			{
				$id = $temp['uid'];
				$data->update_query("onlineusers", "isactive=0", "uid='$id'", "", "", false);
			} 
            elseif ($timediff <= $config['activetime']) 
			{
		        if ($temp['uname'] != 'Guest')
                {
                    $onlineuser[0]++;
	    			$onlineuser[] = $temp;
                }
                else
                {
                    $onlineuser[1]++;
                }
			} 
        }
		return $onlineuser;
	}
	
	function logout() 
    {
		global $config, $data;
        
 		$cookiename = $config['cookiename'];
		$info = $this->read_cookies();
		$uid = $info['uid'];
        $ip = $_SERVER['REMOTE_ADDR'];
        
		$data->delete_query("onlineusers", "uid='$uid' AND ip='$ip'", "", "", false);
		$data->update_query("authuser", "uid = ''", "uid='$uid'", "", "", false);
        
		setcookie ($cookiename, "", 0);
		return false;
	}
    
    function auth() 
    {
        return true;
	} //database
    
	// AUTHENTICATE
	function authenticate($username, $password) 
    {
        global $data;
        
		$uid = "";
		$info = $this->read_cookies();
		$olduid = $info['uid'];
        $data->delete_query("onlineusers", "uid='$olduid'", "", "", false);

        $result = $data->select_query("authuser", "WHERE uname='$username' AND passwd='$password' AND status <> 'inactive'");
		
        $numrows = $data->num_rows($result);
		$row = $data->fetch_array($result);
        
		$ip  = $_SERVER['REMOTE_ADDR'];
		$nuid = $this->set_cookie($username);
		$ntime = time();
        
		$info = $this->read_cookies();
		$uid = $info['uid'];
        
		$result2 = $data->select_query("onlineusers", "WHERE uname='$username'"); 
		
		$numrows2 = $data->num_rows($result2);
		if ($numrows2) {
			$data->delete_query("onlineusers", "uname='$username'", "", "", false);
		}
        
		// CHECK IF THERE ARE RESULTS
		// Logic: If the number of rows of the resulting recordset is 0, that means that no
		// match was found. Meaning, wrong username-password combination.
		if ($numrows == 0) 
        {
			return $this->addguest();
		}
		else 
        {
            $data->update_query("authuser", "uid = '$nuid', prevlogin = lastlogin, lastlogin = $ntime, logincount = logincount + 1", "uname='$username'", "", "", false);
            $data->insert_query("onlineusers", "'$nuid', '$username', $ntime, $ntime, '$ip', 1, 0, '', ''", "", "", false);
			return $row;
		}
	} // End: function authenticate

    function addguest()
    {
        global $data;
        
        $username = "Guest";
		$ip  = $_SERVER['REMOTE_ADDR'];
        $nuid = $this->set_cookie($username);
		$ntime = time();

        $data->insert_query("onlineusers", "'$nuid', '$username', '$ntime', '$ntime', '$ip', 1, 0, '', ''", "", "", false);

        $check['uname'] = "Guest";
        $check['level'] = -1;
        $check['team'] = "Guest";
        $check['uid'] = $nuid;
        return $check;
    }

	// PAGE CHECK
	// This function is the one used for every page that is to be secured. This is not the same one
	// used in the initial login screen
	function page_check() 
    {
        global $data;
    
		$cookiestuff = $this->read_cookies();
		
		$username = $cookiestuff['uname'];
		$ip = $cookiestuff['ip'];
		$uid = $cookiestuff['uid'];
		$ok = false;
		$error = 0;
    
        if(isset($uid))
        {
            $result = $data->select_query("onlineusers", "WHERE uid='$uid' AND uname='$username' AND ip='$ip'");;

            $numrows = $data->num_rows($result);
            $row = $data->fetch_array($result);

            if ($numrows != 0) 
            { 
                $ok = true;
            }
            else
            {
                $ok = false;
                $error = 1;
            }            

            // CHECK IF THERE ARE RESULTS
            // Logic: If the number of rows of the resulting recordset is 0, that means that no
            // match was found. Meaning, wrong username-password combination.
            if (!$ok) 
            {
                $data->delete_query("onlineusers", "uid='$uid'", "", "", false);
                return $this->addguest();
            }
            elseif ($ok && $username != "Guest")
            {
                $ntime = time();
                $data->update_query("onlineusers", "lastupdate = '$ntime', isactive = 1, pages = pages + 1", "uid = '$uid'", "", "", false);
                $bla = $this->reset_cookie($username, $uid);
                $sql = $data->select_query("authuser", "WHERE uid='$uid'");
                return $data->fetch_array($sql);
            }
            else
            {
                $ntime = time();
                $data->update_query("onlineusers", "lastupdate = '$ntime', isactive = 1, pages = pages + 1", "uid = '$uid'", "", "", false);
                
                $bla = $this->reset_cookie($username, $uid);
                $check['uname'] = "Guest";
                $check['level'] = -1;
                $check['team'] = "Guest";
                $check['uid'] = $uid;
                return $check;
            }
        }
        else
        {
            return $this->addguest();
        }
	} // End: function page_check
	
	// MODIFY USERS
	function modify_user($username, $password, $team, $level, $status, $zone) 
    {
        
        global $data;
        // If $password is blank, make no changes to the current password
		if (trim($level)=="") 
        {
			return "blank level";
		}
		elseif (($username=="admin" AND $status=="inactive")) 
        {
			return "admin cannot be inactivated";
		}
		else 
        {
            if (trim($password == ''))
            {
                $data->update_query("authuser", "team='$team', level='$level', status='$status', timezone='$zone'", "uname='$username'", "", "", false);
            }
            else
            {
                $data->update_query("authuser", "passwd=MD5('$password'), team='$team', level='$level', status='$status'", "uname='$username'", "", "", false);
            }
			return 1;
		}	
	} // End: function modify_user
	
	// DELETE USERS
	function delete_user($username) 
    {
		$qDelete = "DELETE FROM {$this->dbprefix}authuser WHERE uname=$username";	

		if ($username == "sa") {
			return "User sa cannot be deleted.";
		}
		elseif ($username == "admin") {
			return "User admin cannot be deleted.";
		}

		$result = mysql_query($qDelete); 
	
		return mysql_error();
		
	} // End: function delete_user
	
	// ADD USERS
	function add_user($username, $password, $team, $level, $status, $zone) 
    {
		global $data;
        
	
		// Check if all fields are filled up
		if (trim($username) == "") { 
			return "blank username";
		}
		// password check added 09-19-2003
		elseif (trim($password) == "") {
			return "blank password";
		}
		elseif (trim($level) == "") {
			return "blank level";
		}
		
		// Check if user exists
		$user_exists = $data->select_query("authuser", "WHERE uname=$username"); 

		if ($data->num_rows($user_exists) > 0) {
			return "username exists";
		}
		else {
			$result = $data->insert_query("authuser", "'', '', $username, '$password', '$status',  '$level' ,'$team', 0, 0, 0, 0, $zone", "", "", false); 

			return mysql_affected_rows();
		}
	} // End: function add_user


	// *****************************************************************************************
	// ************************************** G R O U P S ************************************** 
	// *****************************************************************************************

	// ADD TEAM
	function add_team($teamname, $teamlead, $status="active") {
		$qGroupExists = "SELECT * FROM {$this->dbprefix}authteam WHERE teamname='$teamname'";
		$qInsertGroup = "INSERT INTO {$this->dbprefix}authteam(teamname, teamlead, status) 
				  			   VALUES ('$teamname', '$teamlead', '$status')";
		
		
		// Check if all fields are filled up
		if (trim($teamname) == "") { 
			return "blank team name";
		}
		
		// Check if group exists
		// OLD CODE - DO NOT REMOVE
		// $group_exists = mysql_db_query($this->DBNAME, $qGroupExists);
		
		// REVISED CODE
		$group_exists = mysql_query($qGroupExists); 

		if (mysql_num_rows($group_exists) > 0) {
			return "group exists";
		}
		else {
			// Add user to DB
			// OLD CODE - DO NOT REMOVE
			// $result = mysql_db_query($this->DBNAME, $qInsertGroup);

			// REVISED CODE
			$SelectedDB = mysql_select_db($this->DBNAME);
			$result = mysql_query($qInsertGroup); 

			return mysql_affected_rows();
		}
	} // End: function add_group
	
	// MODIFY TEAM
	function modify_team($teamname, $teamlead, $status) {
		$qUpdate = "UPDATE {$this->dbprefix}authteam SET teamlead='$teamlead', status='$status'
					WHERE teamname='$teamname'";
		$qUserStatus = "UPDATE authuser SET status='$status' WHERE team='$teamname'";

		if ($teamname == "Admin" AND $status=="inactive") {
			return "Admin team cannot be inactivated.";
		}
		elseif ($teamname == "Ungrouped" AND $status=="inactive") {
			return "Ungrouped team cannot be inactivated.";
		}
		else {		
			
			// UPDATE STATUS IF STATUS OF TEAM IS INACTIVATED
			// OLD CODE - DO NOT REMOVE
			//$userresult = mysql_db_query($this->DBNAME, $qUserStatus);

			// REVISED CODE
			$userresult = mysql_query($qUserStatus); 
	
			// OLD CODE - DO NOT REMOVE
			// $result = mysql_db_query($this->DBNAME, $qUpdate);

			// REVISED CODE
			$result = mysql_query($qUpdate); 
	
			return 1;
		}
		
	} // End: function modify_team

	// DELETE TEAM
	function delete_team($teamname) {
		$qDelete = "DELETE FROM {$this->dbprefix}authteam WHERE teamname='$teamname'";
		$qUpdateUser = "UPDATE {$this->dbprefix}authuser SET team='Ungrouped' WHERE team='$teamname'";	
		
		if ($teamname == "Admin") {
			return "Admin team cannot be deleted.";
		}
		elseif ($teamname == "Ungrouped") {
			return "Ungrouped team cannot be deleted.";
		}
		elseif ($teamname == "Temporary") {
			return "Temporary team cannot be deleted.";
		}

		// OLD CODE - DO NOTE REMOVE
		// $result = mysql_db_query($this->DBNAME, $qUpdateUser);

		// REVISED CODE
		$result = mysql_query($qUpdateUser); 

		// OLD CODE - DO NOT REMOVE
		// $result = mysql_db_query($this->DBNAME, $qDelete);
		
		// REVISED CODE
		$result = mysql_query($qDelete); 

		return mysql_error();
		
	} // End: function delete_team


} // End: class auth
?>