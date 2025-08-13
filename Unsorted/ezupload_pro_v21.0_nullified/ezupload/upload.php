<? 
/////////////////////////////////////////////////////////////
// Program Name         : EzUpload Pro                       
// Program Version      : 2.0                                
// Program Author       : ScriptsCenter.com                  
// Supplied by          : Stive [WTN]                        
// Nullified and tested : CyKuH [WTN]                        
// Distribution         : via WebForum and Forums File Dumps 
//                    WTN Team `2002
/////////////////////////////////////////////////////////////
  include( "initialize.php" );
  
  
  ///////////////////////////////////////
  // FIND THE ROW FOR THIS UPLOAD
  ///////////////////////////////////////
  
  if( isset($_POST['id']) )
  {
    $uploadid = $_POST['id'];
  }
  else
  {
    // new upload - add a new row
    $uploadid = $UPLOAD->addrow();

	$UPLOAD->setval( time(), "uploaded", $uploadid );
	if( $CONF->getval("takeip") )$UPLOAD->setval( getipaddress(), "IP Address", $uploadid );
  }
  
  
  ///////////////////////////////////////
  // FIND AND CREATE THE SUBDIR
  ///////////////////////////////////////
  
  if( isset($_POST['id']) )
  {
    $subdir = $UPLOAD->getval( "subdir", $uploadid );
  }
  elseif( $CONF->getval("subdir")=="none" || $safemode )
  {
    // we don' setup any subdir
	$subdir = "";
  }
  elseif( $CONF->getval("subdir")=="random" )
  {
    $subdir = rand_string(8) . "/";
  }
  elseif( $CONF->getval("subdir")=="date" )
  {
    $num = 1;
    $subdir = date("m-d-y");
	
	while( is_dir( $filesdir.$subdir ) )
	{
	  $num++;
	  $subdir = date("m-d-y") . "_" . $num;
	}
	
	$subdir .= "/";
  }
  else // the subdir is a field
  {
	$fieldid = $CONF->getval("subdir");
	
	$subdir = $_POST["f$fieldid"] . "/";
  }
  
  $uploaddir = $filesdir . $subdir;
  
  if( !is_dir( $uploaddir ) )
  {
    $oldumask = @umask( 0 );
	
    if( !@mkdir( $uploaddir, 0777 ) )
	{
	  echo( "Error! Could not create directory! (Permission denied)" );
	  exit;
	}
	
	@umask( $oldumask );
  }
  
  $UPLOAD->setval( $subdir, "subdir", $uploadid );

  
  
  ///////////////////////////////////////
  // LOOPS THROUGH THE ACTIVE FIELDS
  ///////////////////////////////////////
  
  $FIELD->sortdata( "order", "asc" );
  
  $fields = $FIELD->get();
  $errors = array();
  
  foreach( $fields AS $field )
  {
    $fname = "f" . $field['id'];
    
	
	///////////////////////////////////////
	// THE FIELD IS A FILE
	///////////////////////////////////////
	
	if( $field['type']=="file" )
	{
	  if( is_uploaded_file($_FILES[$fname]['tmp_name']) )
	  {
	  	///////////////////////////////////////
	  	// CHECK FILE EXTENSION
		///////////////////////////////////////
	  
	    if( $CONF->getval("extmode")!="all" )
		{
		  $fileext =  strtolower( substr( strrchr( $_FILES[$fname]['name'], "." ), 1 ) );
		  $extarray = explode( " ", $CONF->getval("extensions") );
		  
		  if( $CONF->getval("extmode")=="except" && in_array($fileext,$extarray) )
		  {
		    $errors[$field['id']] = "Extension not accepted (.$fileext)";
		    continue;
		  }
		  elseif( $CONF->getval("extmode")=="only" && !in_array($fileext,$extarray) )
		  {
		    $errors[$field['id']] .= "Extension not accepted (.$fileext)";
		    continue;
		  }
		}

		
	  	///////////////////////////////////////
	  	// CHECK FILE SIZE
		///////////////////////////////////////
		
	    if( $CONF->getval("limitsize") )
        {
          $filesize = (int) round( $_FILES[$fname]['size'] / 1024 );

          if( $filesize > $CONF->getval("sizemax") )
          {
            $errors[$field['id']] = "File is too large (Max. " . $CONF->getval("sizemax") . "Kb)";
	        continue;
          }
          elseif( $filesize < $CONF->getval("sizemin") )
          {
            $errors[$field['id']] = "File is too small (Min. " . $CONF->getval("sizemin") . "Kb)";
            continue;
          }
		}
		
		
	  	///////////////////////////////////////
	  	// CHECK IF FILE ALREADY EXISTS
		///////////////////////////////////////
		
		$filename = $_FILES[$fname]['name'];
		
		if( file_exists( $uploaddir.$filename ) )
		{
		  if( $CONF->getval("existing")=="skip" )
		  {
		    continue;
		  }
		  elseif( $CONF->getval("existing")=="addnumber" || $safemode )
		  {
		    $namenoext = substr( $filename, 0, (strrpos($filename,".")) );
			
			$num = 2;
			while( file_exists( $uploaddir.$namenoext.$num.".".$fileext ) ) { $num++; }
			$filename = $namenoext.$num.".".$fileext;
		  }
		  elseif( $CONF->getval("existing")=="overwrite" )
		  {
		    $files = $FILE->queryrows( $filename, "name" );
			
			foreach( $files AS $file )
			{
			  // delete file vars with same name if located on same subdir
			  if( $subdir == $UPLOAD->getval( "subdir", $file['upload'] ) )
			  {
			    $FILE->deleterow( $file['id'] );
			  }
			}
		  }
		}
		
		move_uploaded_file( $_FILES[$fname]['tmp_name'], $uploaddir.$filename );
	  
	  
	  	///////////////////////////////////////
	  	// CHECK IMAGE DIMENSION
		///////////////////////////////////////
	  
	    if( $CONF->getval("limitdim") )
        {
		  $imageinfo = @getimagesize( $uploaddir.$filename ); 
		  
		  // is the file an image?
		  if( $imageinfo )
		  {
		    $width = $imageinfo[0];
			$height = $imageinfo[1];
			
			if( $width<$CONF->getval("widthmin") || $width>$CONF->getval("widthmax") )
			{
			  // single height allowed
			  if( $CONF->getval("widthmin")==$CONF->getval("widthmax"))
			    $errors[$field['id']] = "Image width must be " . $CONF->getval("widthmin") . " pixels";
			  else
			    $errors[$field['id']] = "Image width must be between " . $CONF->getval("widthmin") . " and " . $CONF->getval("widthmax");

			  @unlink( $uploaddir.$filename );
			  continue;
			}
			elseif( $height<$CONF->getval("heightmin") || $height>$CONF->getval("heightmax") )
			{
			  // single height allowed
			  if( $CONF->getval("heightmin")==$CONF->getval("heightmax"))
			    $errors[$field['id']] = "Image height must be " . $CONF->getval("heightmin") . " pixels";
			  else
			    $errors[$field['id']] = "Image height must be between " . $CONF->getval("heightmin") . " and " . $CONF->getval("heightmax");

			  @unlink( $uploaddir.$filename );
			  continue;
			}
		  }
		}
	  
	    // save the file
	    $fileid = $FILE->addrow();
	    $FILE->setval( $uploadid, "upload", $fileid );
		$FILE->setval( $filename, "name", $fileid );
		$FILE->setval( $_FILES[$fname]['type'], "type", $fileid );
		$FILE->setval( $_FILES[$fname]['size'], "size", $fileid );
	  }
	  else
	  {
	    if( $field['required'] ) $errors[$field['id']] = "Required field is missing!";
	    continue;
	  }
	}
	
	
	///////////////////////////////////////
	// THE FIELD IS AN USER INFO
	///////////////////////////////////////
	
	else
	{
	  if( empty($_POST[$fname]) )
	  {
	    if( $field['required'] ) $errors[$field['id']] = "Required field is missing!";
	    continue;
	  }
	  
	  
	  ///////////////////////////////////////
	  // CHECK FIELD LENGTH
	  ///////////////////////////////////////
	  
	  if( $field['required'] && $field['type']!="dropbox" )
	  {
	    if( strlen($_POST[$fname]) < $field['minchars'] )
		{
		  $errors[$field['id']] = "Value is too short (Min. " . $field['minchars'] . ")";
		}
		elseif( strlen($_POST[$fname]) > $field['maxchars'] )
		{
		  $errors[$field['id']] = "Value is too long (Max. " . $field['maxchars'] . ")";
		}
	  }
	
	  if( $field['id'] == $CONF->getval("emailfield") )
	  {
	    $UPLOAD->setval( $_POST[$fname], "email", $uploadid );
	  }
	  else
	  {
	    $UPLOAD->setval( $_POST[$fname], $field['name'], $uploadid );
	  }
	}
  }
  
  $UPLOAD->savedata();
  $FILE->savedata();
  
  
  ///////////////////////////////////////
  // COUNT THE NUMBER OF FATAL ERRORS
  ///////////////////////////////////////
  
  $numfatalerrors = 0;
  
  while( list($fieldid, $message) = each($errors) )
  {
    if( $FIELD->getval("required",$fieldid) ) $numfatalerrors++;
  }
  
  reset( $errors );
  
  
  if( $numfatalerrors==0 ):
  
  ///////////////////////////////////////
  // SEND A CONFIRMATION TO THE USER
  ///////////////////////////////////////
  
  if( $CONF->getval("sendconfirmation") && $UPLOAD->getval("email",$uploadid) )
  {
    sendmessage( $UPLOAD->getval("email",$uploadid), $CONF->getval("confirmtitle"), $CONF->getval("confirmmsg"), $CONF->getval("adminname"), $CONF->getval("adminemail") );
  }
  
  
  ///////////////////////////////////////
  // SEND A NOTIFICATION TO THE ADMIN
  ///////////////////////////////////////
  
  if( $CONF->getval("notification")!="none" )
  {
    $uploads = $UPLOAD->getrow( $uploadid );
	
	
	///////////////////////////////////////
	// GENERATE THE UPLOAD INFORMATION
	///////////////////////////////////////
	
    while( list($field,$value) = each($uploads) )
    {
      if( !$value || $field=="id" || $field=="subdir" ) 
	  {
	    continue;
	  }
	  elseif( $field=="email" )
	  {
	    $field = "Email Address";
		if( $CONF->getval("notification")=="html" ) $value = "<a href='mailto:$value'>$value</a>"; 
	  }
      elseif( $field=="uploaded" )
	  {
	    $field = "Uploaded On";
	    $value = date( "m/d/y @ H:i", $value );
	  }
	  
	  if( $CONF->getval("notification")=="html" )
	    $message .= "<B>$field:</B> $value<BR>\n";
	  else
	    $message .= "$field: $value\n";
    }
	
	
	///////////////////////////////////////
	// GENERATE THE FILES INFORMATION
	///////////////////////////////////////
	
    $files = $FILE->queryrows( $uploadid, "upload" );
	$ezuploadurl = "http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']);

    foreach( $files AS $file )
    {
	  $filesize = (int) round( $file['size'] / 1024 );
	  $fileurl = "$ezuploadurl/$filesdir" . rawurlencode( $UPLOAD->getval("subdir",$uploadid) . $file['name'] );

	  if( $CONF->getval("notification")=="html" )
	    $message .= "<BR><A HREF=\"$fileurl\">{$file['name']}</a> - {$filesize}Kb - {$file['type']}\n";
	  else
	    $message .= "\n$fileurl ({$filesize}Kb)";
		
	  $totalsize += $filesize;
    }
	
	
	////////////////////////////////////////
	// ADD A LINK TO THE CONTROL PANEL
	////////////////////////////////////////
	
	if( $CONF->getval("notification")=="html" )
	  $message .= "<BR><BR><A HREF=\"$ezuploadurl/viewfiles.php?id=$uploadid\">$ezuploadurl/viewfiles.php?id=$uploadid</A>\n";
	else
	  $message .= "\n\n$ezuploadurl/viewfiles.php?id=$uploadid";
	

	////////////////////////////////////////
	// SEND THE EMAIL WITH ATTACHMENT
	////////////////////////////////////////
	
	if( $CONF->getval("attachments")=="always" || ( $CONF->getval("attachments")=="only" && $totalsize<$CONF->getval("attachmaxsize") ) )
	{
	  if( $CONF->getval("notification")=="html" )
	    $contenttype = "text/html";
	  else
	    $contenttype = "text/plain";
	
      $boundary = "----=_NextPart_ezUpload_000_0052_01C1AD1D.55C33D80";

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
	  
      $header = "From: EzUpload <" . $CONF->getval("adminemail") . ">\n"
              . "Reply-To: " . $CONF->getval("adminemail") . "\n"
              . "Mime-Version: 1.0\n"
              . "Content-Type: multipart/mixed;\n"
              . "\tboundary=\"$boundary\"\n";
	  
	  mail( $CONF->getval("adminemail"), "Upload Notification", $body, $header );
	}
	
	
	////////////////////////////////////////
	// SEND THE EMAIL WITHOUT ATTACHMENT
	////////////////////////////////////////
	
	else
	{
      $header = "From: EzUpload <" . $CONF->getval("adminemail") . ">\n"
              . "Reply-To: " . $CONF->getval("adminemail") . "\n";
	  
	  if( $CONF->getval("notification")=="html" )
	  {
        $header .= "Mime-Version: 1.0\n"
                 . "Content-Type: text/html;\n";
	  }
	  
	  mail( $CONF->getval("adminemail"), "Upload Notification", $message, $header );
	}
  }
  
  endif; // if( $numfatalerrors==0 )
  
  
  ///////////////////////////////////////
  // DISPLAY THE REPORT PAGE
  ///////////////////////////////////////
  
  if( count($errors)>0 )
  {
    if( $numfatalerrors>0 )
	{
	  $result = "Your upload was not successful. See below for the details "
	  		  . "and click on Try Again to try to submit the information again. ";
			  
	  $prevtext = "Try Again";
	}
	else
	{
	  $result = $CONF->getval( "thankyoumsg" ) . " Unfortunately, some information "
	          . "not required you entered were not correct. You may correct them by "
			  . "clicking on the Try Again link below. ";
				
	  $prevtext = "Try Again";
	}
  }
  else
  {
    $result = $CONF->getval( "thankyoumsg" );
	
	$prevtext = "Upload More";
  }
  
  $result = wordwrap( $result, 60, "<br>" );
  
  $shownext = false;
  $showprev = false;
  
  if( $numfatalerrors==0 && $CONF->getval("redirecturl")!="http://" && $CONF->getval("redirecturl")!="" )
  {
    $shownext = true;
  }
  
  if( count($errors)>0 || $CONF->getval("moreuploads") )
  {
    $showprev = true;
  }
  
?>

<html>
<head>
<title>Upload Results</title>
</head>
<body bgcolor="#FFFFFF">

<table border="0" cellspacing="0" cellpadding="0" align="center" height="100%">
  <tr>
    <td>
      <table border="0" cellspacing="1" cellpadding="10" bgcolor="#000000">
        <tr bgcolor="#FFFFFF"> 
          <td> 
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td colspan="2"> 
				  <font face="Verdana, Arial, Helvetica, sans-serif" size="2"><? echo($result); ?></font>
          		</td>
        	  </tr>

<? if( count($errors)>0 ): ?>

              <tr> 
                <td colspan="2" height="20">&nbsp;</td>
        	  </tr>
        	  <tr>
          		<td align="center" colspan="2">
				  <table border="0" cellspacing="0" cellpadding="7" align="center" bgcolor="#E9E9E9">
 					<tr>
				      <td>
					    <font face="Verdana, Arial, Helvetica, sans-serif" size="2">
	
<?						  
  while( list($fieldid, $message) = each($errors) )
  {
    echo( "<b>" . $FIELD->getval("name",$fieldid) . ":</b> $message<br>" );
  }
?>
							
				        </font>
					  </td>
					</tr>
				  </table>
                </td>
              </tr>

<?
  endif; 
  
  if( $showprev || $shownext ): 
?>

              <tr> 
                <td colspan="2" height="20">&nbsp;</td>
        	  </tr>
              <tr>
                <td align="left">
		          <b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">

<?
  if( $showprev )
  {
    echo( "&lt; <a href='{$_POST['formurl']}?id=$uploadid&date=" . $UPLOAD->getval("uploaded",$uploadid) . "'>$prevtext</a>" );
  }
  else
  {
    echo( "&nbsp;" );
  }

?>

			      </font></b>
			    </td>
			    <td align="right">
			      <b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">

<?
  if( $shownext )
  {
    echo( "<a href='" . $CONF->getval("redirecturl") . "'>Continue</a> &gt;" );
  }
  else
  {
    echo( "&nbsp;" );
  }
?>

                  </font></b>
		        </td>
              </tr>
					
<? endif; ?>
					
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

</body>