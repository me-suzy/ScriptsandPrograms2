<?php
	//Revised by Jason Farrell
	//Revised on July 23, 2005
	//Revision Number 2
	
	//Rev 2: Functionality added to allow User class to facilitate all interaction of Interface to Database for User information

	class User
	{
		var $id;
		var $user;
		var $pass;
		var $FirstName;
		var $LastName;
		var $ComputerName;
		var $HelpDeskAddress = '';
		var $securityLevel;
		var $email_addr;
		var $phoneNumber;
		var $phoneExt;
		
		function User($id = false)
		{
			$this->id = $id;
			if ($id) {
				$this->fetch();	
			}
		}
		
		function fetch()
		{
			$q = "select * from " . DB_PREFIX . "accounts where id = $this->id LIMIT 1";
			$s = mysql_query($q) or die(mysql_error());
			$r = mysql_fetch_assoc($s);
			
			$this->user = $r['User'];
			$this->pass = $r['Pass'];
			$this->FirstName = $r['FirstName'];
			$this->LastName = $r['LastName'];
			$this->ComputerName = $r['ComputerName'];
			$this->HelpDeskAddress = $r['HelpDeskAddress'];
			$this->securityLevel = $r['securityLevel'];
			$this->email_addr = $r['email_addr'];	
			$this->phoneNumber = $r['phoneNumber'];
			$this->phoneExt = $r['phoneExt'];
		}
		
		function get($name, $callback = null)
		{
			switch ($name)
			{
				case 'phoneNumber':
					$val = preg_replace('/[^\d]/', '', $this->$name); break;
				default: $val = $this->$name; break;
			}
			
			if (is_null($callback))
				return $val;
			else 
				return $callback($val);	
		}
		
		function set($name, $value, $applier = null)
		{
			switch ($name)
			{
				case 'phoneNumber': $value = $this->parseDigits($value); break;
				case 'phoneExt': $value = $this->phoneExt; break;
				default: break;
			}
			
			if (is_null($applier)) {
				$this->$name = $value;
			}
			else {
				$this->$name = $applier($value);
			}
		}
		
		/*
			We use the applicator model for our set function so that we can pull the escaping out of commit and keep it clean
			make sure you validate all the data you set, this prevents SQL injection errors - the two best ways are as follows:
				Strings:	mysql_real_escape_string
				Integers:	intval
		*/
		function commit()
		{
			if ($this->id) {
				//update
				$cmd  = "update " . DB_PREFIX . "accounts set ";
				$cmd .= "email_addr = '$this->email_addr', ";
				$cmd .= "FirstName = '$this->FirstName', ";
				$cmd .= "LastName = '$this->LastName', ";
				$cmd .= "phoneExt = $this->phoneExt, ";
				$cmd .= "phoneNumber = $this->phoneNumber, ";
				$cmd .= "securityLevel = $this->securityLevel ";
				$cmd .= "where id = $this->id";
                
                mysql_query($cmd) or die(mysql_error());
			}
			else {
				//insert
				$cmd  = "insert into " . DB_PREFIX . "accounts(email_addr, FirstName, LastName, phoneExt, phoneNumber, securityLevel, pass, user) ";
				$cmd .= "values('$this->email_addr', '$this->FirstName', '$this->LastName', '$this->phoneExt', '$this->phoneNumber', $this->securityLevel, '" . md5($this->pass) . "', '$this->user')";
                
                mysql_query($cmd) or die(mysql_error());
                $this->id = mysql_insert_id();
			}
		}
		
		//these are meant to be private functions
		function parseDigits($value)
		{
			return preg_replace('/[^\d]/', '', $value);
		}
		
		function delete()
		{
			$cmd = "delete from " . DB_PREFIX . "accounts where id = $this->id";
			mysql_query($cmd) or die(mysql_error());
			
			$cmd = "update " . DB_PREFIX . "data set regUser = 0 where regUser = $this->id";
			mysql_query($cmd) or die(mysql_error());
		}
		
		function passwd($pass)	//begin phasing this function out
		{
			$cmd  = "update " . DB_PREFIX . "accounts set ";
			$cmd .= "pass = '" . md5($pass) . "' ";
			$cmd .= "where id = $this->id";
			
			mysql_query($cmd) or die(mysql_error());
		}
		
		function promote()
		{
			$cmd  = "update " . DB_PREFIX . "accounts set ";
			$cmd .= "securityLevel = securityLevel + 1 ";
			$cmd .= "where id = $this->id and securityLevel <> " . ADMIN_SECURITY_LEVEL;
			
			mysql_query($cmd) or die(mysql_error());
		}
		
		function demote()
		{
			$cmd  = "update " . DB_PREFIX . "accounts set ";
			$cmd .= "securityLevel = securityLevel - 1 ";
			$cmd .= "where id = $this->id and securityLevel <> " . ENDUSER_SECURITY_LEVEL;
			
			mysql_query($cmd) or die(mysql_error());
		}
		
		//custom get functions
		function getTextSecurityLevel()
		{
			$array = array(
				"End User", "Technician", "Administrator"
			);
			
			return $array[$this->securityLevel];	
		}
		
		function psswd($old, $new)
		{
			if (md5($old) != $this->pass) {
				return false;
			}
			
			if (empty($old)) {
				return false;	
			}
			
			
			$cmd  = "update " . DB_PREFIX . "accounts set ";
			$cmd .= "pass = '" . md5($new) . "' ";
			$cmd .= "where id = $this->id";
			
			mysql_query($cmd) or die(mysql_error());
			$this->pass = md5($new);
			return true;
		}
	}
?>