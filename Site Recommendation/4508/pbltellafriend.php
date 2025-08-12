<?php

/* DEDICATION / INSPIRATION ----->
Sanity is a full time job, in a world that is always changing.
Sanity is the state of mind, that you believe in sanity.
*/

$thisprog = "pbltellafriend.php";
$template = "template.html"; // must be valid!
$people = "10"; // number of people user can recommend page to at a time
$fontface = "Verdana"; // font style
$maxlength = "50"; // maximum length of string displayed url will be before adding "..." before it
$messagefile = "tellafriend_message.txt"; // does not need to be modified if file exists in same directory
$mes_footer_file = "tellafriend_mes_footer.txt"; // does not need to be modified if file exists in same directory
$subject = "You were recommended!"; // change this to your default subject
$changesub = "n"; // allow users to change the "subject" of the email
$requirerefer = "n"; // y or n
$validservers = "pixelatedbylev.com,www.pixelatedbylev.com,taintedthoughts.com,www.taintedthoughts.com";
$uselog = "y"; // want to enable log recording so that you may view a log of recommendations? ***REQUIRES mySQL!***

// IF YOU WANTED TO ENABLE LOG RECORDING THEM THE FOLLOWING PARAMETERS ALSO MUST BE SET

$truepassword = "pbl"; // password you will use in url query string to access log data

// MYSQL REQUIRED PARAMETERS
$GLOBALS['sqlhost'] = 'localhost';
$GLOBALS['sqluser'] = 'username';
$GLOBALS['sqlpass'] = 'password';
$GLOBALS['sqldbnm'] = 'database_name';


/*

Written by Lev. Visit www.pixelatedbylev.com for more free programs & info on ordering
custom code writing services.

Do not remove footer unless script has been modified by at least 10%! Consult:
http://www.pixelatedbylev.com/agreement.php

*/


// NOTHING BELOW THIS LINE NEEDS TO BE MODIFIED
///////////////////////////////////////////////


$validemail = "/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/";
$program = 'Tell A Friend v 1.02'; // do not change
$programnoversion = preg_replace("/^(.*?) v (.*?)$/","\\1",$program);
$data .= "<FONT FACE=\"$fontface\"><BR><BR>\n<BLOCKQUOTE><BIG><B>" . $programnoversion . " »</B></BIG> \$title<P>\n\n";

$footer = get_footer();

if ($_GET['action'] == 'auto-update')
	{
	list ($tempprog,$version) = split (" v ",$program);
	$tempprog = str_replace(" ","_",$tempprog);
	header ("Location: http://www.pixelatedbylev.com/au.php?p=$tempprog&v=$version");
	exit;
	}

if ($_GET['action'] == 'log' && $_GET['password'] == $truepassword)
	{
	$title = "Log";
	mysql_connect ($GLOBALS[sqlhost], $GLOBALS[sqluser], $GLOBALS[sqlpass]);
	mysql_select_db ($GLOBALS[sqldbnm]);
	$data .= "attempting to read from mySQL database...<P>\n";
	$query = "SELECT * FROM `recommend_log` ORDER BY `time` DESC";
	if ($result = mysql_query($query))
		{
		$data .= "<CENTER><TABLE CELLSPACING=0 CELLPADDING=3 BORDER=1>";
		$total = mysql_num_rows($result);
		while ($logrow = mysql_fetch_array($result))
			{
			$logrow['message'] = str_replace("\n","<BR>",$logrow['message']);
			$newtime = date("g:ia d M. Y",$logrow['time']);
			$data .= "<TR><TD><FONT SIZE=1><B>From:</B><BR>" . $logrow['name'] . "\n<BR>\n" . $logrow['email'];
			$data .= "\n<BR>\n" . $logrow['ip'] . "<P>\n<B>To:</B>\n<BR>\n" . $logrow['to'] . "<P>\n";
			$data .= "<B>Time:</B>\n<BR>\n" . $newtime . "</FONT></TD><TD>";
			$data .= "<FONT SIZE=1><B>" . $logrow['subject'] . "</B><P>" . $logrow['message'] . "</TD>";
			}
		$data .= "</TABLE></CENTER><P>SUCCESSFULLY READ LOG DATA (<B>$total</B> records!)!<P>\n";
		}
	else
		{
		$createq = "CREATE TABLE `recommend_log` (
		`time` INT( 15 ) NOT NULL ,
		`name` TEXT NOT NULL ,
		`email` TEXT NOT NULL ,
		`subject` TEXT NOT NULL ,
		`message` MEDIUMTEXT NOT NULL ,
		`to` TEXT NOT NULL ,
		`ip` TEXT NOT NULL 
		);";
		if ($createres = mysql_query($createq))
			{
			$data .= "FAILED TO READ DATA; SUCCESSFULLY CREATED NEW TABLE IN DATABASE!<P>\n";
			}
		else
			{
			$data .= "FAILED TO READ OR CREATE FROM mySQL DATABASE!<P>\n";
			}
		}
	}

if ($footer == '' || !ereg("www.pixelatedbylev.com",$footer) || !ereg("$program",$footer) || !ereg("Tell A Friend",$footer))
	{
	header("Location: http://www.pixelatedbylev.com/footer.php");
	exit;
	}



if ($_POST['process'] && $_POST['url'] && $_POST['name'] != '' && preg_match($validemail,$_POST['email']))
	{
	$title = "Recommend Site";
	if ($requirerefer == 'y')
		{
		$validarray = explode(",",$validservers);
		foreach ($validarray as $valid)
			{
			if (ereg($valid,$_POST['url']))
				{
				$serverisvalid = 'y';
				}
			}
		}
	if ($serverisvalid != 'y' && $requirerefer == 'y') {$data .= "THIS SERVER IS NOT PERMITTED TO BE RECOMMENDED!";}
	elseif ($serverisvalid == 'y' || $serverisvalid == '')
		{
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "From: " . $_POST['name'] . " <" . $_POST['email'] . ">\r\n";
		$headers .= "Reply-to: " . $_POST['name'] . " <" . $_POST['email'] . ">\r\n";
		if (file_exists($mes_footer_file))
			{
			$eml_footer_data = file_get_contents($mes_footer_file);
			}
		if ($_POST['subject'] != '')
			{
			$subjecttouse = $_POST['subject'];
			}
		else
			{
			$subjecttouse = $GLOBALS['subject'];
			}
		$emailedusers = '0';
		for ($i=1;$i <= $people;$i++)
			{
			if (preg_match($validemail,$_POST["email$i"]))
				{
				$emailedusers++;
				stripslashes($_POST['message']);
				if ($eml_footer_data != '')
					{
					$newmessage = $_POST['message'] . "\n\n" . $eml_footer_data;
					}
				else
					{
					$newmessage = $_POST['message'];
					}
				mail ($_POST["email$i"],$subjecttouse,$newmessage,$headers);
				if ($uselog == 'y') {$query = log_to_db ($_POST["email$i"]);}
				$data .= "recommended <I>" . $_POST["email$i"] . "</I> successfully!\n<BR>\n";
				}
			}
		if ($emailedusers == '0') {$data .= "you need to enter one or more email addresses to recommend!\n<BR>\n";}
		}
	$data .= "<P>";
	}


elseif ($_GET['action'] == '')
	{
	$title = "Recommend Site";
	if ($_GET['url'] != '')
		{
		$url = $_GET['url'];
		$url = str_replace("~!Q!~","?",$url);
		$url = str_replace("~!AMP!~","&",$url);
		}
	elseif ($_POST['url'] != '')
		{
		$url = $_POST['url'];
		}
	else
		{
		$url = "http://" . $_SERVER['SERVER_NAME'];
		}
	if ($length = strlen($url) > $maxlength)
		{
		$start = strlen($url) - $maxlength;
		$end = strlen($url);
		$url2 = "..." . substr($url,$start,$end);
		}
	else
		{
		$url2 = $url;
		}
	if ($_POST['message'] == '')
		{
		$message = file_get_contents($messagefile);
		$message = str_replace('$url',$url,$message);
		}
	else
		{
		$message = $_POST['message'];
		}
	if ($_POST['name'] == '' || !preg_match($validemail,$_POST['email']))
		{
		$data .= "\n<I>You must enter your name <B>and</B> an email address!</I>\n<P>\n";
		}
	$data .= "<FORM ACTION=$thisprog METHOD=POST>\n";
	$data .= "<TABLE>\n";
	$data .= "<INPUT TYPE=HIDDEN NAME=process VALUE=y><INPUT TYPE=HIDDEN NAME=url VALUE=\"" . $url . "\">\n";
	$data .= "<TR><TD><FONT FACE=\"$fontface\"><B>your name:</B></FONT></TD><TD><INPUT TYPE=TEXT NAME=name SIZE=30 VALUE=\"" . $_POST['name'] . "\"></TD></TR>\n";
	$data .= "<TR><TD><FONT FACE=\"$fontface\"><B>your email:</B></FONT></TD><TD><INPUT TYPE=TEXT NAME=email SIZE=30 VALUE=\"" . $_POST['email'] . "\"></TD></TR>\n";
	$data .= "<TR><TD><FONT FACE=\"$fontface\"><B>url:</B></FONT></TD><TD><FONT SIZE=1><I>$url2</I></FONT></TD></TR>\n";
	if ($changesub == 'y')
		{
		if ($_POST['subject'] != '')
			{
			$subval = $_POST['subject'];
			}
		else
			{
			$subval = $subject;
			}
		$data .= "<TR><TD><FONT FACE=\"$fontface\"><B>subject:</B></FONT></TD><TD><INPUT TYPE=TEXT NAME=subject VALUE=\"$subval\" SIZE=30></TD></TR>\n";
		}
	$data .= "<TR><TD><FONT FACE=\"$fontface\"><B>message:</B></FONT></TD><TD><TEXTAREA ROWS=10 COLS=35 NAME=message>$message</TEXTAREA></TD></TR>\n";
	for ($i=1;$i <= $people;$i++)
		{
		$data .= "<TR><TD><FONT FACE=\"$fontface\"><B>email $i:</B></FONT></TD><TD><INPUT TYPE=TEXT NAME=email$i SIZE=30 VALUE=\"" . $_POST["email$i"] . "\"></TD></TR>\n";
		}
	$data .= "</TABLE>\n<P>\n";
	$data .= "<INPUT TYPE=SUBMIT VALUE=\"recommend to everyone above!\">\n</FORM>\n";
	}

if ($linktopblurl != '' && $linkdata = file_get_contents($linktopblurl))
	{
	if (preg_match("/\<A(.*?)HREF\=(.*?)pixelatedbylev\.com(.*?)\>/i",$linkdata))
		{
		$footer = "";
		}
	}
$data .= "\n<FONT SIZE=1>$footer</FONT></BLOCKQUOTE>\n";
if (!ereg($footer,$data))
	{
	header ("Location: http://www.pixelatedbylev.com/footer.php");
	exit;
	}
$data = str_replace('$title',$title,$data);
printpage($title,$data,"");


function get_footer ()
	{
	$data = "\n\n<P><SMALL>powered by " . $GLOBALS['program'] . "! open source scripting at ";
	$data .= "<A HREF=http://www.pixelatedbylev.com STYLE=\"color:$GLOBALS[fontcolor]\">www.pixelatedbylev.com</A>!</SMALL>";
	return $data;
	}

function log_to_db ($email)
	{
	$time = time();
	if ($_POST['subject'] != '')
		{
		$subjecttouse = $_POST['subject'];
		}
	else
		{
		$subjecttouse = $GLOBALS['subject'];
		}
	mysql_connect ($GLOBALS[sqlhost], $GLOBALS[sqluser], $GLOBALS[sqlpass]);
	mysql_select_db ($GLOBALS[sqldbnm]);
	$query = "INSERT INTO `recommend_log` (`time`,`name`,`email`,`subject`,`message`,`to`,`ip`) VALUES ('$time', '" . $_POST['name'];
	$query .= "', '" . $_POST['email'] . "', '" . $subjecttouse . "', '" . $_POST['message'] . "', '$email', '";
	$query .= $_SERVER['REMOTE_ADDR'] . "');";
	if (mysql_query($query)) {return "SUCCESSFUL TO LOG";} else {return "FAILED TO LOG";}
	}


function printpage ($title,$data,$meta)
	{
	$newtitle = $GLOBALS['program'] . " -> " . $title;
	$template = file_get_contents($GLOBALS['template']);
	$newdata = str_replace('$title',$newtitle,$template);
	$newdata = str_replace('$meta',$meta,$newdata);
	$newdata = str_replace('$data',$data,$newdata);
	echo $newdata;
	}

?>