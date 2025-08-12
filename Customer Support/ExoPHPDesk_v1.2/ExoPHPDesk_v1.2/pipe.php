#!/usr/bin/php -q
<?php

// << -------------------------------------------------------------------- >>
// >> EXO Helpdesk Mail Piping Module
// >>
// >> PIPE . PHP - Mail Piping Module
// >> Started : January 07, 2004
// >> Edited  : November 19, 2004
// << -------------------------------------------------------------------- >>

// Include Required Files
include_once('conf.php');
include_once($lang_file);

// GET THE DATA SENT
$MAIL = file ( "php://stdin" );

// LOOP TO GET THE HEADERS
while (list($NUM, $DATA) = each($MAIL))
{
	// UNSET THE HEADER LINE
	unset ($MAIL[$NUM]);
	
	if (preg_match('#boundary="(.+)"#i', $DATA, $boundary)) {
		$bound = $boundary[1];
	}
			
	// FIX(nov 19, 04) - this message looks like 
	// this will contain html and plain text as well..
	if (stristr($DATA, 'multipart/alternative;')) {
		$plain_only = 1;
	}
	
	// If The First Char Is A Space
	if ($DATA[0] == ' ')
	{
		$Headers[$NAME] .= ' ' . $DATA;
	}
	else
	{
		// If Empty Line, Then Body Starts Here So Break
		if (trim($DATA) == NULL) {
			break;
		}
		else
		{
			// Trim The Data
			$DATA  =  trim ($DATA);
			// Split The Header Line
			$SPLIT =  explode (": ", $DATA, 2);
			// Get Header Type
			$TYPE  =  $SPLIT[0];
			// Unset Type
			unset ($SPLIT[0]);
			// Get Header Value
			$HEAD  =  implode (": ", $SPLIT);
			// Get Header Name
			$NAME  =  strtolower ($TYPE);
			// Set Header Name And Value
			$Headers[$NAME]  =  $HEAD;
		}
	}

} 
// END - HEADER DATA LOOP

// split the boundarys and get 'em!
function split_bounds ($data, $bound)
{
	while (preg_match("#([-]+|)$bound(.+?)([-]+)$bound#is", $data, $strip))
	{
		$strips[] = $strip[2];
		$data = str_replace($strip[0], $bound, $data);
	}
	
	return $strips;
}

// strip out the sub-headers!
function strip_headers ($data, $rev = 0)
{
	//echo $data;
	$data = explode("\n", $data);
	while (list($num, $line) = each($data))
	{
		$x++;
		
		if( $rev == 1 ) {
			$collect .= $line . "\n";
		}
		
		$line = trim($line);
		if( $line == "" AND $x != 1 ) {
			break;
		}
		elseif( $rev != 1 ) {
			unset($data[$num]);
		}
	}

	if( $rev == 1 )
		$data = $collect;
	else
		$data = implode("\n", $data);

	return $data;
}

function handle_split($data, $bound)
{
	// globalize some required variables!
	global $BODY, $attachment, $filedata, $base64, $plain_only;
	
	// split using boundaries!
	$strips = split_bounds($data, $bound);
	foreach ($strips as $content)
	{

		// this message has multiple boundaries, lets move on!
		if (preg_match('#boundary="(.+)"#i', $content, $boundary))
		{
			// FIX(nov 19, 04) - I really hate OUTLOOK! Here it will create a 
			// multipart/alternative header in a subpart while it should have 
			// done it in the main header only..
			if (stristr($content, 'multipart/alternative;')) {
				$plain_only = 1;
			}
			
			$boundary = $boundary[1];
			handle_split( $content, $boundary );
			continue;
		}
		
		// FIX(nov 19, 04) - convert quoted-printable text to 8bit, if required..
		if (stristr($content, 'quoted-printable')) {
			$content = quoted_printable_decode($content);
		}

		if (preg_match('#content-disposition(.+?)attachment#i', $content))
		{
			$filedata = strip_headers( $content );
			$headers  = strip_headers( $content, 1 );
			
			// FIX(nov 22, 04) - decode content if required
			if (stristr($headers, 'Content-transfer-encoding: base64')) {
				$filedata = base64_decode($filedata);
			}
			
			// lets find some name or filename in header
			if (preg_match( '#(name|filename)="(.+?)"#', $headers, $file)) {
				$attachment = $file[2];
			}
		}
		elseif (preg_match('#Content-Type(.+?)text/(plain|html)#i', $content, $type))
		{
			// FIX(nov 19, 04) - we need plain one only!
			if ($plain_only == 1 AND $type[2] == 'html') {
				continue;
			}
			
			$headers  = strip_headers($content, 1);
			$body = strip_headers($content);
			
			// FIX(nov 22, 04) - decode content if required
			if (stristr($headers, 'Content-transfer-encoding: base64')) {
				$body = base64_decode($body);
			}
			
			// add to $BODY
			$BODY .= $body;
			
		}
	}
}

// if we have a boundary!
if( $bound != null )
{
	$data = implode("", $MAIL);

	//
	// Created a function that is able to handle multiple boundaries messages
	// sometimes Outlook generates messages with multiple boundaries, although
	// this shouldnt happen!
	// Fixed..
	handle_split( $data, $bound );
}
else
{
	// GET THE BODY
	$BODY  =  implode ( "", $MAIL );
	
	// is the content decoded?
	if ($Headers['Content-Transfer-Encoding'] == 'base64' OR $Headers['content-transfer-encoding'] == 'base64') {
		$BODY = base64_decode($BODY);
	}
}

// if we have an attachment with the email!
if( $attachment != null )
{
	// set it to valid now..
	$valid = 1;

	// if its base64 encoded, then decode it!
	if( $base64 == 1 )
		$filedata = base64_decode( $filedata );
	
	// guess size of the file using strlen() and
	// make sure it doesn't exceeds size limit!
	$ap_size = strlen( $filedata );
	if( $ap_size > $Max_Upload )
		$valid = 0;
		
	// Find out whether this type of attachment is allowed or not!
	$Allowed = explode(",", $Allowed_Ext);
	while (list(,$Type) = each($Allowed))
	{
		$Type = preg_replace('/\s/', NULL, $Type);
		if (preg_match('/(.+?)'. $Type .'$/i', $attachment))
		{
			$valid = 1;
			break;
		}
		else
			$valid = 0;
	}
 	
	// start file existance check, we need to add a prefix if the
	// file already exist!
	if (file_exists($Attach_dir . $attachment))
	{
		$st = 0;
		while ($st != 1)
		{
			$x++;
			if (!file_exists($Attach_dir . $Attach_pre . $x . $attachment))
			{
				$st = 1;
				$Attachment = $Attach_pre . $x . $attachment;
			}
		}
	} // end file existance check!
	else
	{
		$Attachment = $attachment;
	}
	
	// Okay, we have a valid file. Write it to a file..	
	if ($valid == 1)
	{
		$fp = @fopen($Attach_dir . $Attachment, 'w');
		@fwrite($fp, $filedata);
		@fclose($fp);
		
		// chmod the file to 0666 if supported by the system..
		@chmod($Attach_dir . $Attachment, 0666);
		
		$valid_attach = 1;
	}
}

// PREPARE SOME HEADERS VARIABLES
$TIME    =  ( empty ( $Headers['date'] ) ) ? time() : strtotime ( $Headers['date'] );
$EMAIL   =  preg_replace ( '/(.*)<(.*)>/', "\\2", $Headers['from'] );
$NAME    =  preg_replace ( '/(.*)<(.*)>/', "\\1", $Headers['from'] );
$SUBJECT =  trim ( $Headers['subject'] );

// CHECK TO SEE IF A TICKET ID EXISTS, IN SUBJECT
if( preg_match("/(\(\[(.*)Ticket(\s)ID:(.*)\]\))/", $SUBJECT, $MATCHES) )
{
	// Get Ticket ID From The Subject
	$TICKETID = trim(str_replace('([','',str_replace('])','',str_replace('Ticket ID:','',$MATCHES[0]))));

	// Replace Ticket ID At Subject With NULL
	$SUBJECT  = preg_replace("/(\(\[(.*)Ticket(\s)ID:(.*)\]\))/", '', $SUBJECT);

	// Replace Ticket ID At Body With NULL	
	$BODY     = preg_replace("/(\(\[(.*)Ticket(\s)ID:(.*)\]\))/", '', $BODY);
}

// CHECK TO SEE IF A TICKET ID EXISTS, IN BODY
if( empty($TICKETID) && preg_match("/(\(\[(.*)Ticket(\s)ID:(.*)\]\))/", $BODY, $MATCHES) )
{
	// Get Ticket ID From Body
	$TICKETID = trim(str_replace('([','',str_replace('])','',str_replace('Ticket ID:','',$MATCHES[0]))));

	// Replace Ticket ID With NULL	
	$BODY     = preg_replace("/(\(\[(.*)Ticket(\s)ID:(.*)\]\))/", '', $BODY);
}

// IF ID FOUND THEN CONFIRM IF IT EXISTS, USING AN SQL QUERY
$TID_CHECK    = $db->query( $sel_ticket . " WHERE `id`='".$TICKETID."'" );
$FETCH_TID    = $db->fetch( $TID_CHECK );

// ADDSLASHES TO BODY AND SUBJECT
$BODY = addslashes( $BODY );
$SUBJECT = addslashes( $SUBJECT );

// find out a user with the incoming email address!
$UQ      = $db->query ( $sel_mem . " WHERE `email` = '" . $EMAIL . "'" );
$UF      = $db->fetch ( $UQ );
// set the ticket owner ..
$T_OWNER = ( $db->num( $UQ ) ) ? $UF['username'] : 'Guest';

// CHECK IF TICKET BELONGS TO THE EMAIL IT CAME FROM				
if( $db->num($TID_CHECK) )
{
	$EMAIL_C  = $db->query($sel_ticket." WHERE `id`='".$TICKETID."' AND `admin_email`='".$EMAIL."'");
}

// IF EXISTING TID AND EMAIL
if( $db->num($TID_CHECK) && $db->num($EMAIL_C) )
{
	// Add A Response
	response ( $TICKETID, $T_OWNER, 'Subject: '.$SUBJECT."\n".$BODY, 1, 0, 0, 0 );
}
else
{
	// IF SUBJECT IS EMPTY
	if( empty($SUBJECT) )
	{
		// Set Subject
		$SUBJECT = 'NO SUBJECT';
	}
	
	// owner id!
	$OID     = ( $db->num( $UQ ) ) ? $UF['id'] : '0';
	
	if ($valid_attach == 1 AND $Attachment != null)
	{
		$attach = $Attachment;
	}
	
	// which group we need to pipe this ticket to (suggested by AplosMedia on forums)
	if ((isset($_SERVER['argv'][2])) && ($_SERVER['argv'][2] != '')) {
	    $pipeto = $_SERVER['argv'][2];
	} else {
    	$pipeto = 'EMAIL';
	}

	// PREPARE SQL
	$SQL = "'". $OID ."', '". $T_OWNER ."', '".$EMAIL."', '".$TIME."', '".$SUBJECT."', '{$pipeto}', '".$BODY."', '2', '".$TIME."', 'Open', 'Staff', '', '', '$attach'";
	$INSTICKET = str_replace('`admin_user`,', '`admin_user`,`admin_email`,', $ins_ticket);
	
	// Notify All Staff About New Ticket
	mail_all_staff($pipeto, $T_OWNER, $BODY, $SUBJECT);
					
	// EXECUTE mySQL QUERY				
	$db->query( str_replace('^sql^', $SQL, $INSTICKET ) );
	
	// update the total and open tickets counter..
	$db->query("UPDATE `phpdesk_groups` SET open_tickets = open_tickets + 1, 
					total_tickets = total_tickets + 1 WHERE `name` = '{$pipeto}'");
	
	// select max id
	$q = $db->query("SELECT MAX(id) as max_id FROM `phpdesk_tickets`");
	$f = $db->fetch($q);
	
	// Mail back the user to inform him or her about the ticket creation!
	mail_it('pipe_tcreate', $EMAIL, $general['pipe_tcreate'], '', '', $f['max_id'], $BODY, $SUBJECT);
}

?>