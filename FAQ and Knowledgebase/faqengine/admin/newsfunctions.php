<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
function postnews($server, $newsgroup, $from, $subject, $body, $domain, $errortext)
{
	$fp=fsockopen($server, 119, &$error, &$description, 5);
    if(!$fp)
	{
		$errortext = "Can't connect to server <i>$server</i> ($error, $description)";
        return false;
	}
	if (fgets($fp, 1024) != 200)
	{
		$errortext = "Connection to news server <i>$server</i> failed ($description)";
		return false;
	}

	fputs($fp, "GROUP $newsgroup\n");
	$x=fgets($fp, 1024);
	if (substr($x, 0,3) != 211)
	{
		$errortext = "Failed to change to NewsGroup <i>$newsgroup</i><br>server reported <i>$x</i>";
		return false;
	}

	fputs($fp, "POST\n");
	$x=fgets($fp, 1024);
	if (substr($x, 0,3) != 340)
	{
		$errortext = "Can't post to newsgroup <i>$newsgroup</i><br>server reported <i>$x</i>";
		return false;
	}
	mt_srand ((double) microtime() * 10000000 );
	$int_id = "<".mt_rand(10000000,99999999)."@$domain>";
	$Today = date("l, j M y G:i:s") . " GMT";
	$id = "<".$int_id."@$domain>";
	$message = "Subject: $subject\nFrom: $from\nNewsgroups: $newsgroup\nMessage-ID: $int_id\nDate: $Today\n\n$body\n.\n";
	fputs($fp, $message);
	$x=fgets($fp, 1024);
	if (substr($x, 0,3) != 240)
	{
		$errortext = "Posting to newsgroup failed<br>server reported: <i>$x</i>";
        return false;
	}
	fclose($fp);
	return true;
}
?>
