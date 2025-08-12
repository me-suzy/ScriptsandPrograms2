<?php
	class Ticket
	{
		//We set the default as shown in the database
		//PHP Class Attribute Default Values:
		//String : ''
		//Int : 0
		//We Know this and dont need to set all variables - save yourself some work
		
		var $id 		= null;
		var $FirstName;
		var $LastName;
		var $EMail;
		var $PCatagory = null;
		var $descrip;
		var $resolution = array();
		var $status = null;
		var $staff;
		var $mainDate;
		var $priority = null;
		var $platform;
		var $os;
		var $ipaddress;
		var $browser;
		var $bversion;
		var $uastring;
		var $partNo 	= '';
		var $phoneNumber;
		var $phoneExt;
		var $ticketVisi	= 1;
		var $pageView;
		var $regUser = 0;
		
		var $fileList = array();
		var $results = false;
		
		function Ticket($id = false)
		{
			$this->id = $id;
			if ($id) {
				$this->fetch();	
			}	
		}
		
		function fetch()
		{
			$q = "select * from " . DB_PREFIX . "data where id = $this->id LIMIT 1";
			$s = mysql_query($q) or die(mysql_error());
			$r = mysql_fetch_assoc($s);
			
			if (mysql_num_rows($s)) $this->results = true;
			
			$this->id = $r['ID'];
			$this->FirstName = $r['FirstName'];
			$this->LastName = $r['LastName'];
			$this->EMail = $r['EMail'];
			$this->PCatagory = new Category($r['category']);
			$this->descrip = $r['descrip'];
			$this->status = new Status($r['status']);
			$this->staff = new User($r['staff']);
			$this->mainDate = $r['mainDate'];
			$this->priority = new Priority($r['priority']);
			$this->platform = $r['platform'];
			$this->os = $r['os'];
			$this->ipaddress = $r['ipaddress'];
			$this->browser = $r['browser'];
			$this->bversion = $r['bversion'];
			$this->uastring = $r['uastring'];
			$this->partNo = $r['partNo'];
			$this->phoneNumber = $r['phoneNumber'];
			$this->phoneExt = $r['phoneExt'];
			$this->ticketVisi = $r['ticketVisi'];
			$this->pageView = $r['pageView'];
			$this->regUser = $r['regUser'];
			
			$this->fetchFileList();
			$this->fetchResolutionList();
		}
		
		function fetchFileList()
		{
			$q = "select name from " . DB_PREFIX . "files where id = $this->id";
			$s = mysql_query($q) or die(mysql_error());
			
			while ($r = mysql_fetch_assoc($s))
				$this->fileList[] = $r['name'];	
		}
		
		function fetchResolutionList()
		{
			$q = "select * from " . DB_PREFIX . "resolution where id = $this->id";
			$s = mysql_query($q) or die(mysql_error());
			
			while ($r = mysql_fetch_assoc($s))
			{
				$arr['id'] = $r['resid'];
				$arr['resolution'] = $r['solution'];
				$arr['date'] = $r['resdate'];
				$this->resolution[] = $arr;
			}
		}
		
		function get($name, $callback=null)
		{
			switch ($name)
			{
				case 'priority':
					$o = $this->priority;
					return $this->resolveValue($o); break;
				case 'PCatagory':
					$o = $this->PCatagory;
					return $this->resolveValue($o); break;
				case 'status':
					$o = $this->status;
					return $this->resolveValue($o); break;
				default: break;
			}
			
			//return result
			if (is_null($callback)) {
				return $this->$name;
			}
			else {
				return $callback($this->$name);	
			}
		}
		
		function set($name, $value, $func=null)
		{			
			if (is_null($func)) {
				$this->$name = $value;
			}
			else {
				$this->$name = $func($value);
			}
		}
		
		/*
			Note: this function updates or inserts everything as we expect the programmer to set the data.  Therefore similar
			functions would be redundant if written in the subclass, defeating the purpose of inhearitance
		*/
		function commit()
		{
			//Note it is the programmers responsibility to pass escaping and forced evauation functions as callbacks when
			//setting data, this function will not, and should not, support this.  If you think otherwise, learn how to program.
			//This functions job is to commit and store the data, not make sure it is in the proper format
			$priority = $this->priority;
			$category = $this->PCatagory;
			$status	  = $this->status;
			$staff 	  = $this->staff;
			
			if ($this->id) {
				$cmd  = "update " . DB_PREFIX . "data set ";
				$cmd .= "FirstName = '$this->FirstName', ";
				$cmd .= "EMail = '$this->EMail', ";
				$cmd .= "LastName = '$this->LastName', ";
				$cmd .= "category = '" . $category->get('id') . "', ";
				$cmd .= "descrip = '" . mysql_real_escape_string($this->descrip) . "', ";
				$cmd .= "status = '" . $status->get('id') . "', ";
				$cmd .= "staff = " . $staff->get('id') . ", ";
				$cmd .= "mainDate = '$this->mainDate', ";
				$cmd .= "priority = '" . $priority->get('pid') . "', ";
				$cmd .= "platform = '$this->platform', ";
				$cmd .= "os = '$this->os', ";
				$cmd .= "ipaddress = '$this->ipaddress', ";
				$cmd .= "browser = '$this->browser', ";	
				$cmd .= "bversion = '$this->bversion', ";
				$cmd .= "uastring = '$this->uastring', ";
				$cmd .= "partNo = $this->partNo, ";
				$cmd .= "phoneNumber = '$this->phoneNumber', ";
				$cmd .= "phoneExt = '$this->phoneExt', ";
				$cmd .= "ticketVisi = $this->ticketVisi, ";
				$cmd .= "pageView = $this->pageView, ";
				$cmd .= "regUser = $this->regUser ";
				$cmd .= "where id = $this->id";
				
				mysql_query($cmd) or die(mysql_error() . "1");
			}
			else {
				$cmd  = "insert into " . DB_PREFIX . "data(FirstName, LastName, EMail, category, descrip, status, staff, mainDate, priority, platform, os, ipaddress, browser, bversion, uastring, partNo, phoneNumber, phoneExt, ticketVisi, regUser) ";
				$cmd .= "values('$this->FirstName', '$this->LastName', '$this->EMail', '" . $category->get('id') . "', '$this->descrip', ";
				$cmd .= "'" . $status->get('id') . "', '$this->staff', '$this->mainDate', '" . $priority->get('pid') . "', '$this->platform', ";
				$cmd .= "'$this->os', '$this->ipaddress', '$this->browser', '$this->bversion', '$this->uastring', '$this->partNo', '$this->phoneNumber', ";
				$cmd .= "'$this->phoneExt', $this->ticketVisi, $this->regUser)";
				
				mysql_query($cmd) or die(mysql_error());
				$this->id = mysql_insert_id();
			} 	
		}
		
		function resolveValue($obj)
		{
			if (empty($obj)) return "Unknown";
			else return $obj;
		}
		
		function delete()
		{
			$cmd = "delete from " . DB_PREFIX . "data where id = $this->id";
			mysql_query($cmd) or die(mysql_error());	
		}
	}
?>