<?php

/*
+--------------------------------------------------------------------------
|   Invision Board v1.1
|   ========================================
|   by Matthew Mecham
|   (c) 2001,2002 Invision Power Services
|   http://www.ibforums.com
|   ========================================
|   Web: http://www.ibforums.com
|   Email: phpboards@ibforums.com
|   Licence Info: phpib-licence@ibforums.com
+---------------------------------------------------------------------------
|
|   > IPS Remote Call thingy
|   > Module written by Matt Mecham
|   > Date started: 17th October 2002
|
|	> Module Version Number: 1.0.0
+--------------------------------------------------------------------------
*/


// Ensure we've not accessed this script directly:



$idx = new ad_ips();


class ad_ips {

	var $base_url;
	
	var $colours = array();
	
	var $url = "http://www.invisionboard.com/acp/";
	
	var $version = "1.1";

	function ad_ips() {
		global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
		
		switch($IN['code'])
		{
		
			case 'news':
				$this->news();
				break;
				
			case 'updates':
				$this->updates();
				break;
				
			case 'docs':
				$this->docs();
				break;
				
			case 'support':
				$this->support();
				break;
			
			case 'host':
				$this->host();
				break;
				
			case 'purchase':
				$this->purchase();
				break;
				
			//-------------------------
			default:
				exit();
				break;
		}
		
	}
	


	
	function news()
	{
		global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
	
		@header("Location: ".$this->url."?news");
		exit();
	}
	
	function updates()
	{
		global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
	
		//@header("Location: ".$this->url."?updates&version=".$this->version);
		@header("Location: ".$this->url."?updates");
		exit();
	}
	
	function docs()
	{
		global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
	
		@header("Location: ".$this->url."?docs");
		exit();
	}
	
	function support()
	{
		global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
	
		@header("Location: ".$this->url."?support");
		exit();
	}
	
	function host()
	{
		global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
	
		@header("Location: ".$this->url."?host");
		exit();
	}
	
	function purchase()
	{
		global $IN, $root_path, $INFO, $DB, $SKIN, $ADMIN, $std, $MEMBER, $GROUP;
	
		@header("Location: ".$this->url."?purchase");
		exit();
	}
	
	

	
	
	
	
	
	
}


?>