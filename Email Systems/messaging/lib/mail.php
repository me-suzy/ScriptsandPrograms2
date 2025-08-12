<?php
class CMail {

	/**
	* description
	*
	* @param
	*
	* @return
	*
	* @access
	*/

	var $server;
	var $port;
	var $protocol;
	var $connection;
	var $user;
	var $pass;

	/**
	* description connection resource to mail server
	*
	* @var type
	*
	* @access type
	*/
	var $conn;
	

	function CMail() {

		$this->name = "mail";
	}

	/**
	* description
	*
	* @param
	*
	* @return
	*
	* @access
	*/
	function Login ($server , $port , $protocol , $conn = "", $user , $pass) {

		//saving the variables
		$this->server = $server;
		$this->port = $port; //imap = 142 ; pop3 = 110
		$this->protocol = $protocol ; //imap = /imap   pop3 = /pop3
		$this->connection = $conn; //ssl = /ssl/novalidate-cert

		$this->s_conn = "{" . $server . ($port ? ":" . $port : "" ) . $protocol . $conn . "}INBOX";
		
		//user data
		$this->user = $user;
		$this->pass = $pass;

		//initializing connection to server
		$this->conn = imap_open( $this->s_conn, $this->user , $this->pass);

		//checking if the connection succeded
		if (!is_resource($this->conn))
			return "0";

		
		return "1";
	}
	

	/**
	* description
	*
	* @param
	*
	* @return
	*
	* @access
	*/
	function GetMails() {

		//checking if the connection is still acctive
		if (@imap_ping($this->conn)) {
				
			//get number of messages 
			$messages = imap_num_msg($this->conn); 
			
			if ($messages > 0) {
				//reading the messages
				for ($i = 1; $i <= $messages; $i++) {
					//reading messages headers
					$mails[$i] = imap_headerinfo ( $this->conn, $i);
					//reading the email body buggy , not yet
					$mails[$i]->body = imap_body ($this->conn , $i);
				}
				
				return $mails;

			} else 
				return NULL;

		} else
			return NULL;
	}

	/**
	* description
	*
	* @param
	*
	* @return
	*
	* @access
	*/
	function DeleteEmails() {
		//checking if the connection is still acctive
		if (@imap_ping($this->conn))
				
			//get number of messages 
			$messages = imap_num_msg($this->conn); 
			
			if ($messages > 0) {
				//reading the messages
				for ($i = 1; $i <= $messages; $i++) {
					//reading messages headers
					imap_delete ( $this->conn, $i);
				}

				imap_expunge($this->conn);
				return 1;
			} 

		return 0;
	}

	/**
	* description
	*
	* @param
	*
	* @return
	*
	* @access
	*/
	function DeleteEmail($id) {
		//checking if the connection is still acctive
		if (@imap_ping($this->conn)) {
			imap_delete ($this->conn , $id);

			return 1;
		} else 
			return 0;
	}
	

	/**
	* description
	*
	* @param
	*
	* @return
	*
	* @access
	*/
	function Close() {
		//checking if the connection is still acctive
		if (@imap_ping($this->conn)) {
	
			imap_close($this->conn);
			return 1;

		} else
			return 0;
	}		
}
?>