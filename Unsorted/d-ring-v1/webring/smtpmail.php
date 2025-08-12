<?

// These functions may be used as a replacement for PHP's
// built-in mail() function, which doesn't work in all servers.
// Include this file, then replace PHP's mail(); function with:

//	$subject = ""; 		/* The message subject	*/
//	$message = "";		/* The message 		*/

//	$FromUser = "";		/* Your email address	*/
//	$ToUser = "";		/* Recipent's email	*/
//	$SMTPServer = "";	/* Your SMTP server	*/

//	$mail = smtp_open($SMTPServer, 25);
//	smtp_helo($mail);
//	smtp_mail_from($mail, $FromUser);
//	smtp_rcpt_to($mail, $ToUser);

//	smtp_data($mail, $subject, $message);
//	smtp_quit($mail);

function smtp_open($server, $port)
{
     global $SMTP_GLOBAL_STATUS;

     $smtp = fsockopen($server, $port);
     if ($smtp < 0) return 0;

     $line = fgets($smtp, 1024);

     $SMTP_GLOBAL_STATUS[$smtp]["LASTRESULT"] = substr($line, 0, 1);
     $SMTP_GLOBAL_STATUS[$smtp]["LASTRESULTTXT"] = substr($line, 0, 1024);

     if ($SMTP_GLOBAL_STATUS[$smtp]["LASTRESULT"] <> "2") return 0;

     return $smtp;
}

 
function smtp_helo($smtp)
{
     global $SMTP_GLOBAL_STATUS;

     /* 'localhost' always works */
     fputs($smtp, "helo localhost\r\n");
     $line = fgets($smtp, 1024);
     $SMTP_GLOBAL_STATUS[$smtp]["LASTRESULT"] = substr($line, 0, 1);
     $SMTP_GLOBAL_STATUS[$smtp]["LASTRESULTTXT"] = substr($line, 0, 1024);

     if ($SMTP_GLOBAL_STATUS[$smtp]["LASTRESULT"] <> "2") return 0;

     return 1;
}
 
function smtp_mail_from($smtp, $from)
{
     global $SMTP_GLOBAL_STATUS;

     fputs($smtp, "MAIL FROM: <$from>\r\n");
     $line = fgets($smtp, 1024);
     $SMTP_GLOBAL_STATUS[$smtp]["LASTRESULT"] = substr($line, 0, 1);
     $SMTP_GLOBAL_STATUS[$smtp]["LASTRESULTTXT"] = substr($line, 0, 1024);

     if ($SMTP_GLOBAL_STATUS[$smtp]["LASTRESULT"] <> "2") return 0;

     return 1;
}
 
function smtp_rcpt_to($smtp, $to)
{
     global $SMTP_GLOBAL_STATUS;

     fputs($smtp, "RCPT TO: <$to>\r\n");
     $line = fgets($smtp, 1024);
     $SMTP_GLOBAL_STATUS[$smtp]["LASTRESULT"] = substr($line, 0, 1);
     $SMTP_GLOBAL_STATUS[$smtp]["LASTRESULTTXT"] = substr($line, 0, 1024);

     if ($SMTP_GLOBAL_STATUS[$smtp]["LASTRESULT"] <> "2") return 0;


     return 1;
}

function smtp_data($smtp, $subject, $data)
{
     global $SMTP_GLOBAL_STATUS;

     fputs($smtp, "DATA\r\n");
     $line = fgets($smtp, 1024);
     $SMTP_GLOBAL_STATUS[$smtp]["LASTRESULT"] = substr($line, 0, 1);
     $SMTP_GLOBAL_STATUS[$smtp]["LASTRESULTTXT"] = substr($line, 0, 1024);

     if ($SMTP_GLOBAL_STATUS[$smtp]["LASTRESULT"] <> "3") return 0;

     fputs($smtp, "Mime-Version: 1.0\r\n");
     fputs($smtp, "Subject: $subject\r\n");
     fputs($smtp, "$data\r\n\r\n");
     fputs($smtp, ".\r\n");
     $line = fgets($smtp, 1024);
     if (substr($line, 0, 1) <> "2")
          return 0; 

     return 1;
}
 
function smtp_quit($smtp)
{
     global $SMTP_GLOBAL_STATUS;

     fputs($smtp, "QUIT\r\n");
     $line = fgets($smtp, 1024);
     $SMTP_GLOBAL_STATUS[$smtp]["LASTRESULT"] = substr($line, 0, 1);
     $SMTP_GLOBAL_STATUS[$smtp]["LASTRESULTTXT"] = substr($line, 0, 1024);

     if ($SMTP_GLOBAL_STATUS[$smtp]["LASTRESULT"] <> "2") return 0;

     return 1;
}

?>

