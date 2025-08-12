<?php
	class PublishedTicket extends Ticket
	{
		var $query;
		
		function PublishedTicket($id = false, $regUser = false)
		{
			$this->id = $id;
			if ($id) {
				$this->regUser = $regUser;
				$this->buildQuery();
				$this->fetch();
			}
		}
		
		function buildQuery()
		{
			if ($this->regUser) {
				$this->query = "select * from " . DB_PREFIX . "data where ticketVisi = 1 and ID = $this->id and regUser = $this->regUser";
			}
			else {
				$this->query = "select * from " . DB_PREFIX . "data where ticketVisi = 1 and ID = $this->id";
			}
		}
		
		function fetch()
		{
			$s = mysql_query($this->query) or die(mysql_error());
			if (mysql_num_rows($s)) {
				$this->results = true;
				$r = mysql_fetch_assoc($s);
				
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
			else {
				$this->results = false;	
			}
		}
	}
?>