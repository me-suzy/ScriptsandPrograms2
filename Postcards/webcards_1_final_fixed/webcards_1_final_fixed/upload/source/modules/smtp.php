<?php

class smtp_client { 
    var $connection; 
    var $server; 
    var $port;
    var $smtp_user;
    var $smtp_pass;     

    // default constructor 
    function smtp_client($server='', $port=25, $smtp_user='', $smtp_pass='') { 
        if (!$server) $this->server="localhost";
        else $this->server=$server; 

        if (!$port) $this->port=25;
        else $this->port=$port; 
         
        $this->connection = @fsockopen($this->server, $this->port, &$errno, &$errstr, 100); 
        if ($this->connection <= 0) error($errstr . "(" . $errno. ")"); 

        fputs($this->connection,"HELO " . $HTTP_SERVER_VARS['SERVER_NAME'] . "\r\n");

			if ($this->smtp_user and $this->smtp_pass)
			{
				fputs($this->connection,"AUTH LOGIN");
				
				fputs($this->connection, base64_encode($this->smtp_user));
					
				fputs($this->connection, base64_encode($this->smtp_pass));
					
			}


        } 

    function email($from_mail, $to_mail, $to_name, $header, $subject, $body) { 
        if ($this->connection <= 0) error($errstr . "(" . $errno. ")"); 
     
        fputs($this->connection,"MAIL FROM:$from_mail\r\n");

        fputs($this->connection, "RCPT TO:$to_mail\r\n");

        fputs($this->connection, "DATA\r\n");

        fputs($this->connection,"Subject: $subject\r\n"); 
        fputs($this->connection,"To: $to_name\r\n"); 

        if ($header) {
            fputs($this->connection, "$header\r\n"); 
            } 

        fputs($this->connection,"\r\n"); 
        fputs($this->connection,"$body \r\n"); 
        fputs($this->connection,".\r\n");

        return 1; 
        } 


    function send() { 
        if ($this->connection) { 
            fputs($this->connection, "QUIT\r\n"); 
            fclose($this->connection); 
            $this->connection=0; 
            } 
        } 

    function close() { $this->send(); } 

}

?> 