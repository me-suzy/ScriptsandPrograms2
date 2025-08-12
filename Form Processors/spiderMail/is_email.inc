<?

// validate the e-mail address
function is_email($string)
{
	$boolValue = ereg(
		'^[a-z0-9_\.-]+'.	// username includes alphanumeric characters,
					// underscore, period, or hyphen
		'@'.			// address includes at-sign
		'([a-z0-9-]+\.)+'.	// domain name includes alphanumeric characters,
					// hyphen, or period (in case of subdomains)
		'[a-z]{2,4}$',		// domain includes 2-4 alphabetical characters
		$string);

	return($boolValue);
}

?>