<?
/**
 * First line of defense script.
 *
 * This first version helps battle referer spam,
 * comment-spam and trackback-spam.
 *
 */

/**
 * Block referer spam. Returns true if checks were succesfull,
 * false if not, dies if spam is detected.
 *
 * @return boolean
 *
 */
function block_refererspam() {
	global $blockArray;

	if (file_exists(dirname(__FILE__)."/db/ignored_domains.txt.php") && (isset($_SERVER["HTTP_REFERER"])))  {
		$blockArray = file(dirname(__FILE__)."/db/ignored_domains.txt.php");

		// Prevent tampering with the URL.
		$refererparts = parse_url(strtolower($_SERVER["HTTP_REFERER"]));
		$referer = $refererparts['host'].$refererparts['path'];

		if ($_SERVER['HTTP_HOST']==$refererparts['host']) {
			// if the current host is the same as the refering one, we can skip the checks.
			return true;
		} else {
			// else we check it against the blocked phrases
			foreach($blockArray as $blockphrase)  {
				$blockphrase = trim(str_replace("*", "", $blockphrase));
				if(strpos($referer, $blockphrase) != false)  {
					echo "Spam is not appreciated.";
					die();
				}
			}
			return true;
		}
	} else {
		return false;
	}
}


/**
 * Block 'posted' spam: In either comments or trackbacks. Returns true
 * if checks were succesfull, false if not, dies if spam is detected.
 *
 * @return boolean
 */
function block_postedspam() {
	global $blockArray;

	// load blockarray, if needed.
	if (!isset($blockArray) && (file_exists(dirname(__FILE__)."/db/ignored_domains.txt.php")))  {
		$blockArray = file(dirname(__FILE__)."/db/ignored_domains.txt.php");
	}

	if (isset($blockArray)) {

		// ignore the $_GET['p']..
		unset($_GET['p']);

		$postedData = strtolower(implode(" ", array_merge($_GET, $_POST)));

		if (strlen($postedData)<3) {
			// if there's no posted data, we can skip the checks.
			return true;
		} else {
			// else run the checks.
			foreach($blockArray as $blockPhrase)  {
				if(strstr($blockPhrase, "*") == false)  {
					if(strpos($postedData, trim($blockPhrase)) != false)  {
						echo "Spam is not appreciated.";
						die();
					}
				}
			}
			return true;
		}
	} else {
		return false;
	}

}


?>
