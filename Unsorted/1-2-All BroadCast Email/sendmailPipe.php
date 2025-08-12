#!/usr/local/bin/php -q
<?PHP
/**
* A script to use with sendmail
* to retrieve bounce addresses.
*/
	$methnow = "stdin";
	set_time_limit(0); 
	ignore_user_abort();
	require_once("parseBounce2.php");
	require_once("engine.inc.php");
	
/**
* Set the max # of times an e-mail address may bounce.
* This is default 3.  Thus, if an e-mail address were to bounce
* three times, the script will automatically remove the address.
* You may manually remove batches of addresses that bounce X amt's 
* of times via the control panel at any time.  This is the max # allowed.
*/

	$b_max = "3";


/**
* Call the function to get the addresses. It returns
* an array of addresses, though if used in this fashion
* (ie with sendmail pipe instead of POP3) it should only
* ever return 1 address.
*/
	@$addresses = getBounceAddresses();
	$count = 0;
	$count_f = 0;
	$count_d = 0;
	foreach ($addresses as $something) 
	{
	$b_find = mysql_query ("SELECT * FROM ListMembers
							WHERE email LIKE '$something'
							limit 1
							");
							$b_found = mysql_fetch_array($b_find);
	$nb = $b_found["bounced"];
	$nb = $nb + 1;
	if ($nb != 3){
	mysql_query("UPDATE ListMembers SET bounced='$nb' WHERE (email='$something')");
	$count_f = $count_f + 1;
	}
	else {
	mysql_query ("DELETE FROM ListMembers
                                WHERE email LIKE '$something'
								");
	$count_d = $count_d + 1;
	}
	$count = $count + 1;
	}
?>