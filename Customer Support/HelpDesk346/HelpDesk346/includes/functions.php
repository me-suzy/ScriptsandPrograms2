<?php
	//function for determining size of file upload max setting
	function DetermineSize($string, $error = '')
	{	
		if (empty($string)) {
			//there is no value - assume 2 Meg - send an error back
			$retVal = (((1024) * 1024) * 2);
		}
		else if (preg_match('/\d*KB?/i', $string)) {
			return ((1024) * intval($string));	
		}
		else if (preg_match('/\d*MB?/i', $string)) {
			return (((1024) * 1024) * intval($string));
		}
		else if (preg_match('/\d*GB?/i', $string)) {
			return ((((1024) * 1024) * 1024) * intval($string));	
		}
		else if (preg_match('/\d*TB?/i', $string)) {
			return ((((1024) * 1024) * 1024) * intval($string) * 1024);	
		}
		else if (preg_match('/\d+/', $string)) {
			return intval($string);	
		}
		
		$error = "Unable to Determine PHP Internal Limiter - Guessing 2MB";
		return $retVal;
	}
	
	function validateFirstName($string)
	{
		if (!strlen($string)) return false;
		return true;
	}
	
	function validateLastName($string)
	{
		if (!strlen($string)) return false;
		return true;
	}
	
	function validateEmail($string)
	{
		if (strlen($string)<7) return false;
		if ((substr_count($string, '@')-1)) return false;
		if (preg_match('/@\./', $string) || preg_match('/\.@/', $string)) return false;
		if (substr_count($string, ' ')) return false;
		return true;
	}
	
	function validatePassword($string, $_string = null)
	{
		if (strlen($string) < 4) return false;
		if (!is_null($_string))
			if (strcmp($string, $_string)) return false;	
		return true;	
	}
	
	function validateUsername($string)
	{
		if (!mysql_ping()) die("Cannot Access MySQL Database");
		if (strlen($string) < 4) return false;
		if (substr_count($string, ' ')) return false;
		if (mysql_num_rows(mysql_query("select id from " . DB_PREFIX . "accounts where user = '" . mysql_real_escape_string($string) . "'"))) return false;
		return true;	
	}
	
	function validatePhoneNumber($string)
	{
		$string = preg_replace('/[^\d]/', '', $string);
		//if (strlen($string) != 10) return false;
		return true;
	}
	
	function validatePhoneExt($string)
	{
		if (preg_match('/[^\d]/', $string)) return false;
		return true;
	}
	
	function validateUserType($int)
	{
		if (intval($int) < 0 || intval($int) > 2) return false;	
		return true;
	}
?>