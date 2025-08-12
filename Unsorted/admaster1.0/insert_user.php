<?
include "sys/Conf.inc";

$db = new DB ();

$letters = 'qwertyuiopasdfghjklmnbvcxz';
$uletters = 'QWERTYUIOPASDFGHJKLMNBVCXZ';

$success = 0;
for ($i = 0; $i < 100; $i++)
{
	srand ((float) microtime() * 100);
	
	$str   = md5 ($i.rand (-100, 100));
	$name  = substr ($str, 0, 6);
	$fname = eregi_replace ("[0-9]+", "a", substr ($str, 10, 14));
	$lname = eregi_replace ("[0-9]+", "b", substr ($str, 14, 18));
	$email = substr ($str, 18, 25)."@".substr ($str, 25, 32).".ch";
	$pswd  = ''; 
	
	$fchar = substr ($name, 0, 1);
	if (!strrpos ($letters, $fchar) && !strrpos ($uletters, $fchar))
	{
		$fchar = substr ($uletters, $fchar*1, 1);
		$name = $fchar.$name;
	}
	$year = floor (rand (1900, 2005));
	$month = floor (rand (1, 12));
	$day   = floor (rand (1, 31));
	$regDate = "$year-$month-$day";
	if (!$db->execute ("insert into User (UserName, Password, FirstName, Name, Email, RegDate) 
					 values ('$name', '$pswd', '$fname', '$lname', '$email', '$regDate')"))
	{
		continue;
	}
	
	$uid = $db->lastID ();
	
	$db->execute ("insert into BankAccount (UserID) values ($uid)");
	$db->execute ("insert into MoneyTransfer (UserID) values ($uid)");
	
	$numOfSites = floor (rand (1, 10));
	for ($l = 1; $l <= $numOfSites; $l++)
	{
		srand ((float) microtime() * 100);
		$str = md5 ($k.$uid.$pid.rand (-100, 100));
		$siteTitle = substr ($str, 0, 10);
		$siteURL   = "http://www.".substr ($str, 11, 21).".ch";
		$rid = md5 ($uid.$siteURL);
		$db->execute ("insert into UserSite (UserID, Title, URL, RefID) values ($uid, '$siteTitle', '$siteURL', '$rid')");
	}

	$success++;
}
print "OK = $success";
?>