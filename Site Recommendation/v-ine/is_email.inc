<?

// validate the e-mail address
function is_email($string)
{
	$boolValue = ereg(
		// username includes alphanumeric characters, underscore, period, or hyphen
		'^[a-z0-9_\.-]+'.

		// address includes at-sign
		'@'.

		// domain name includes alphanumeric characters, hyphen, or period (in case of subdomains)
		'([a-z0-9-]+\.)+'.
		
		// domain includes 2-4 alphabetical characters
		'[a-z]{2,4}$',
		$string);

	return($boolValue);
}

?>