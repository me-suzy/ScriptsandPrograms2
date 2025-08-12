<?php

require_once(dirname(__FILE__).'/function.getmxrr.php');
require_once(dirname(__FILE__).'/function.checkdnsrr.php');


class ValidateEmail extends Validator{
    var $email;
		var $debug;
    function ValidateEmail($email){
		    $this->debug = 0;
        $this->email = $email;
        Validator::Validator();
    }
		
    function validate(){
		    $check = trim($this->email);
		    if(!empty($check)){
            if(!$this->check_name()){
                $this->setError('Invalid email address');
            }
            if(strlen($this->email)>100){
                $this->setError('Email Address is too long');
            }
						
						$result = array();
						$result = $this->check_exists($this->email,$this->debug);
						if(!$result[0]){
						    $this->setError('Email Address is not exists');
						}
				}
    }
		
		function check_name(){
		    $result = True;
		    $pattern= "/^([a-zA-Z0-9])+([.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-]+)+/";
        if(!preg_match($pattern,$this->email)){
            $result = False;
        }
				return $result;
		}
		
		function check_exists($Email = '',$Debug = false){
		    $Email = !isset($Email)?$this->email:$Email;
        global $HTTP_HOST;
				$HTTP_HOST = !isset($HTTP_HOST)?'haltebis.com':$HTTP_HOST;
        $Return =array();  
        // Variable for return.
        // $Return[0] : [true|false]
        // $Return[1] : Processing result save.

    
        // E-Mail @ by 2 by standard divide. if it is $Email this "lsm@ebeecomm.com"..
        // $Username : lsm
        // $Domain : ebeecomm.com
        // list function reference : http://www.php.net/manual/en/function.list.php
        // split function reference : http://www.php.net/manual/en/function.split.php
        list($Username,$Domain) = split("@",$Email);

        // That MX(mail exchanger) record exists in domain check .
        // checkdnsrr function reference : http://www.php.net/manual/en/function.checkdnsrr.php
        if(checkdnsrr($Domain,"MX")){
            if($Debug) echo "Confirmation : MX record about {$Domain} exists.<br>";
            // If MX record exists, save MX record address.
            // getmxrr function reference : http://www.php.net/manual/en/function.getmxrr.php
            if(getmxrr($Domain,$MXHost)){
                if($Debug){
                    echo "Confirmation : Is confirming address by MX LOOKUP.<br>";
                    for($i=0,$j=1;$i<count($MXHost);$i++,$j++){
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Result($j) - $MXHost[$i]<BR>";  
                    }
                }
            }
            // Getmxrr function does to store MX record address about $Domain in arrangement form to $MXHost.
            // $ConnectAddress socket connection address.
            $ConnectAddress = $MXHost[0];
        }else{
            // If there is no MX record simply @ to next time address socket connection do .
            $ConnectAddress = $Domain;         
            if($Debug) echo "Confirmation : MX record about {$Domain} does not exist.<br>";
        }

        // fsockopen function reference : http://www.php.net/manual/en/function.fsockopen.php
        $Connect = @fsockopen($ConnectAddress,25);

        // Success in socket connection
        if($Connect){	
							
            if($Debug) echo "Connection succeeded to {$ConnectAddress} SMTP.<br>";
            // Judgment is that service is preparing though begin by 220 getting string after connection .
            // fgets function reference : http://www.php.net/manual/en/function.fgets.php
            if(ereg("^220",$Out=fgets($Connect,1024))){
                // Inform client's reaching to server who connect.
                fputs($Connect,"HELO $HTTP_HOST\r\n");
                if($Debug) echo "Run : HELO $HTTP_HOST<br>";
                $Out = fgets($Connect,1024); // Receive server's answering cord.

                // Inform sender's address to server.
                fputs($Connect,"MAIL FROM: <{$Email}>\r\n");
                if($Debug) echo "Run : MAIL FROM: &lt;{$Email}&gt;<br>";
                $From = fgets($Connect,1024); // Receive server's answering cord.

                // Inform listener's address to server.
                fputs($Connect,"RCPT TO: <{$Email}>\r\n" );
                if($Debug) echo "Run : RCPT TO: &lt;{$Email}&gt;<br>";
                $To = fgets($Connect,1024); // Receive server's answering cord.

                // Finish connection.
                fputs ( $Connect, "QUIT\r\n");
                if($Debug) echo "Run : QUIT<br>";

                fclose($Connect);

                // Server's answering cord about MAIL and TO command checks.
                // Server about listener's address reacts to 550 codes if there does not exist  
                // checking that mailbox is in own E-Mail account.
                if(!ereg("^250",$From) || !ereg("^250",$To)){
                    $Return[0] = false;
                    $Return[1] = "${Email} is address done not admit in E-Mail server.";
                    if ($Debug) echo "{$Email} is address done not admit in E-Mail server.<br>";
                    return $Return;
                }
            } 
						
        }else{
				
		        // Failure in socket connection
            $Return[0] = false;
            $Return[1] = "Can not connect E-Mail server ({$ConnectAddress}).";
            if($Debug) echo "Can not connect E-Mail server ({$ConnectAddress}).<br>";
            return $Return;
						 
        }
				
        $Return[0] = true;
        $Return[1] = "{$Email} is E-Mail address that there is no any problem.";
        return $Return;				
				
    }
		
}
?>
