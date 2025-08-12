<?PHP
/**
* DO NOT MODIFY THIS FILE
*/
	function getMailFromSTDIN()
	{
		$return = '';

		if ($fp = fopen('php://stdin', 'rb')) {
			while ($line = fread($fp, 1024)) {
				$return .= $line;
			}
			
			fclose($fp);
			
			return array($return);
		}
		
		return false;
	}
	
/**
* Reads mail from POP3
*/
	function getMailFromPOP3($user, $pass, $host = 'localhost', $port = 110)
	{
		require_once(dirname(__FILE__) . '/POP3.php');
		
		$pop3 = new Net_POP3();
		$pop3->connect($host, $port);
		$result = $pop3->login($user, $pass, false);

		// Logged in, now retrieve mail
		if ($result === true) {
			$numMsg = $pop3->numMsg();

			for ($i=1; $i<=$numMsg; $i++) {
				$return[] = $pop3->getMsg($i);
				$pop3->deleteMsg($i);
			}
			
			$pop3->disconnect();
			
			return $return;
		}
		
		return false;
	}

/**
* Function parse one or more emails for bounce addresses
*
* @param  string $method   The method to use to retrieve mail
* @param  string $pop3User POP3 Username
* @param  string $pop3Pass POP3 Password
* @param  string $pop3Host POP3 Hostname
* @param  string $pop3Port POP3 Port
* @return array            An array of email addresses that bounced
*/
	function getBounceAddresses($method = 'stdin', $pop3User = '', $pop3Pass = '', $pop3Host = '', $pop3Port = 110)
	{
		$return = array();
		$messages = ( $method == 'stdin' ? getMailFromSTDIN() : getMailFromPOP3($pop3User, $pop3Pass, $pop3Host, $pop3Port) );
		// Go through messages looking for a To: line matching
		// the bounce address pattern
		foreach ($messages as $msg) {
			if (preg_match('/^To: bounce\+(.*)@domainx\.com\s*$/im', $msg, $matches)) {
				$return[] = str_replace('=', '@', $matches[1]);
			}
		}
		return $return;
	}
?>