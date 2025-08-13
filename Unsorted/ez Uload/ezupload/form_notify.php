<? 
/////////////////////////////////////////////////////////////
// Program Name         : EzUpload Pro                       
// Program Version      : 2.20                                
// Program Author       : ScriptsCenter.com                  
// Supplied by          : CyKuH [WTN]                        
// Nullified and tested : CyKuH [WTN]                        
// Distribution         : via WebForum and Forums File Dumps 
//                    WTN Team `2004
/////////////////////////////////////////////////////////////
  ///////////////////////////////////////
  // SEND A CONFIRMATION TO THE USER
  ///////////////////////////////////////
  
  if( $CONF->getval("sendconfirmation") && $UPLOAD->getval("email",$uploadid) )
  {
    $confirmmsg = str_replace( "[links]", trim($links_text), $CONF->getval("confirmmsg") );
  
    sendemail( $UPLOAD->getval("email",$uploadid), $CONF->getval("adminemail"), $CONF->getval("adminname"), $CONF->getval("confirmtitle"), $confirmmsg );
  }
  
  
  ///////////////////////////////////////
  // SEND A NOTIFICATION TO THE ADMIN
  ///////////////////////////////////////
  
  if( $CONF->getval("notification")!="none" )
  {
    // set time limit in case large files must be attached
    // this does not work if php is under safe mode
    @set_time_limit( 600 );

	
	///////////////////////////////////////
	// GENERATE THE UPLOAD INFORMATION
	///////////////////////////////////////
	
	$upload = $UPLOAD->getrow( $uploadid );
	
    // get user name, if any
    if( $upload['user'] )
    {
      $userid = $UPLOAD->getval( "user", $uploadid );
      $username = $USER->getval( "name", $userid );
	  
	  if( $CONF->getval("notification")=="html" )
        $infoarray['User Name'] = "<a href='$ezuploadurl/edituser.php?id=$userid'>$username</a>";
	  else
	    $infoarray['User Name'] = $username;
    }

    // get date
	$infoarray['Uploaded On'] = userdate( $upload['uploaded'] );
	
	// get email
    if( $upload['email'] )
	{
	  if( $CONF->getval("notification")=="html" )
	    $infoarray['Email Address'] = "<a href='mailto:{$upload['email']}'>{$upload['email']}</a>"; 
	  else
	    $infoarray['Email Address'] = $upload['email'];
    }
   
    // get other user info from UPLOADINFO table
    $infos = $UPLOADINFO->queryrows( $uploadid, "upload" );
    foreach( $infos AS $info )
    {
      $infoarray[$info['name']] = $info['value'];
    }

    while( list($field,$value) = each($infoarray) )
    {
	  if( $CONF->getval("notification")=="html" )
	    $message .= "<B>$field:</B> $value<BR>\n";
	  else
	    $message .= "$field: $value\n";
    }
	
	
	///////////////////////////////////////
	// INCLUDE THE FILES INFORMATION
	///////////////////////////////////////
	
	if( $CONF->getval("includelinks") )
	{
	  if( $CONF->getval("notification")=="html" )
	  {
	    $message .= $links_html;
	  }
	  else
	  {
	    $message .= $links_text;
	  }
	}
	
	
	////////////////////////////////////////
	// ADD A LINK TO THE CONTROL PANEL
	////////////////////////////////////////
	
	if( $CONF->getval("notification")=="html" )
	  $message .= "<BR><BR><A HREF=\"$ezuploadurl/viewfiles.php?id=$uploadid\">$ezuploadurl/viewfiles.php?id=$uploadid</A>\n";
	else
	  $message .= "\n\n$ezuploadurl/viewfiles.php?id=$uploadid";
	

	////////////////////////////////////////
	// CREATE EMAIL WITH ATTACHMENT
	////////////////////////////////////////
	
	if( $totalsize>0 && ( $CONF->getval("attachments")=="always" || ( $CONF->getval("attachments")=="only" && $totalsize<$CONF->getval("attachmaxsize") ) ) )
	{
	  if( $CONF->getval("notification")=="html" )
	    $contenttype = "text/html";
	  else
	    $contenttype = "text/plain";
	
      $boundary = "----=_NextPart_ezUpload_000_" . rand_string(15);

	  $body = "This is a multi-part message in MIME format.\n"
	        . "\n--$boundary\n"
	        . "Content-Type: $contenttype;\n"
	        . "\tcharset=\"iso-8859-1\"\n"
	        . "Content-Transfer-Encoding: 7bit\n\n"
	        . $message;
	  
      $files = $FILE->queryrows( $uploadid, "upload" );
	  $dirpath = $filesdir . $UPLOAD->getval( "subdir", $uploadid );
	
      foreach( $files AS $file )
      {
	    // if file not uploaded this upload, ignore it
	    if( !in_array($file['id'], $currentfiles) ) continue;

  		// read the image and encode it (for MIME)
		$fp = @fopen( $dirpath.$file['name'], "rb" );
  		$imageraw = @fread( $fp, filesize($dirpath.$file['name']) );
  		$imageraw = @base64_encode( $imageraw );
  		@fclose( $fp );
		
	    $body .= "\n--$boundary\n"
	           . "Content-Type: {$file['type']};\n"
	           . "\tname=\"{$file['name']}\"\n"
	           . "Content-Transfer-Encoding: base64\n"
	           . "Content-Disposition: attachment;\n"
	           . "\tfilename=\"{$file['name']}\"\n\n"
	           . $imageraw;
	  }
	  
	  $body .= "\n\n--$boundary--";
	  
      $header = "Mime-Version: 1.0\n"
              . "Content-Type: multipart/mixed;\n"
              . "\tboundary=\"$boundary\"\n";
	}
	
	
	////////////////////////////////////////
	// CREATE EMAIL WITHOUT ATTACHMENT
	////////////////////////////////////////
	
	else
	{
	  if( $CONF->getval("notification")=="html" )
	  {
        $header .= "Mime-Version: 1.0\n"
                 . "Content-Type: text/html;\n";
	  }
	  
	  $body = $message;
	}
	
	
	///////////////////////////////////////
	// SEND NOTIFICATION EMAIL(S)
	///////////////////////////////////////
	
	$emailarray = array();
	
	if( $CONF->getval("notifyemails")!="" )
	{
	  $emailarray = explode( ",", $CONF->getval("notifyemails") );
	}
	
	$emailarray[] = $CONF->getval("adminemail");
	
	foreach( $emailarray AS $notify_email )
	{
	  // remove any space (especially from additional emails)
	  $notify_email = trim( $notify_email );
	
	  // check that the email address is valid
	  if( !isemail($notify_email) ) continue;
	  
	  sendemail( $notify_email, $CONF->getval("adminemail"), "ezUpload", "Upload Notification", $body, $header );
	}
  }
  
?>