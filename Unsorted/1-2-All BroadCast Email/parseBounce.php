<?PHP
/**
* DO NOT MODIFY THIS FILE
*/
	require_once("engine.inc.php");

	$popfinder = mysql_query ("SELECT * FROM Backend
                         WHERE valid LIKE '1'
						 limit 1
                       ");
	$popfind = mysql_fetch_array($popfinder);
	$pop_ho = $popfind["pop_ho"];
	$pop_us = $popfind["pop_us"];
	$pop_pa = base64_decode($popfind["pop_pa"]);
	$pop_po = $popfind["pop_po"];
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
	function getMailFromPOP3()
	{
		require_once(dirname(__FILE__) . '/POP3.php');
		$pop3 = new Net_POP3();
		$pop3->connect($pop_ho, $pop_po);
		$result = $pop3->login($pop_us, $pop_pa, false) OR DIE ("Invalid POP account information.  Unable to connect.  Check your POP account information in the bounced email configuration.");
		if ($result === true) {
			$numMsg = $pop3->numMsg();
			for ($i=1; $i<=$numMsg; $i++) {
			if ($i <= 125){
				$activdelete = $pop3->getMsg($i);
			if (preg_match('/^X-mid: (.*)\s*$/im', $activdelete, $matchesD)) {
				$return[] = $pop3->getMsg($i);
				$pop3->deleteMsg($i);
			}
			}
			}
			$pop3->disconnect();
			return $return;
		}
		return false;
	}
	
	function getBounceAddresses($method = 'pop3')
	{
		$return = array();
		$messages = ( $method == 'stdin' ? getMailFromSTDIN() : getMailFromPOP3() );
		if ($messages != ""){
		foreach ($messages as $msg) {
			if (preg_match('/^X-mid: (.*)\s*$/im', $msg, $matches)) {
				$return[] = $matches[1];
			}
		}
		}
		return $return;
	}
?>