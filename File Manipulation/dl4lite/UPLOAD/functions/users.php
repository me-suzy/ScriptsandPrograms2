<?php

/*********************************************************
 * Name: users.php
 * Author: Dave Conley
 * Contact: realworld@blazefans.com
 * Description: Base class declaration for user database
 * Version: 4.00
 * Last edited: 7th March, 2005
 *********************************************************/

class user
{
	var $isAdmin,
		$userid,
		$userlevel;
	
	function adminLogin($session = "")
	{
		global $DB;
		if ( $session == "" )
			return false;

		$s1 = $DB->query("SELECT * FROM dl_sessions WHERE sID = '$session'");
		$row1 = $DB->fetch_row($s1);
		$uid = $row1["id"];

		$s2 = $DB->query("SELECT * FROM dl_users WHERE id = $uid"); 
		$row2 = $DB->fetch_row($s2);
		return $this->auto_login($row2['id'], $row2['password']);

	}

	function auto_login($id = -1, $pass="password")
	{
		global $DB, $IN, $CONFIG, $std;

		if ( $id == -1 )
		{
			$std->error("Your login cookie is invalid. Please re-login to update your cookies");
			return false;
		}
		
		$DB->query("SELECT * FROM dl_users WHERE `id`='{$id}' AND password = '{$pass}'");
		$myrow = array();
		if (!($myrow = $DB->fetch_row()) )
			return false;
		if ($myrow['group'] == '1')
			$this->isAdmin = 1;
		else
			$this->isAdmin = 0;
		$this->userlevel = $myrow['group'];
		$this->userid = $myrow['id'];
		return $this;
	}
	
	function do_login()
	{
		global $IN, $DB;
		
		$password = md5($IN['userpw']);
		$DB->query("SELECT * FROM dl_users WHERE `username`='{$IN['username']}' AND password = '{$password}'");
		$myrow = array();
		if (!($myrow = $DB->fetch_row()) )
			return false;
		if ($myrow['group'] == '1')
			$this->isAdmin = 1;
		else
			$this->isAdmin = 0;
		$this->userlevel = $myrow['group'];
		$this->userid = $myrow['id'];

		return true;
	}
}
	
?>
