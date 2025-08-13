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
  // GET THE ID FOR THIS UPLOAD
  ///////////////////////////////////////
  
  if( isset($HTTP_SESSION_VARS['uploadid']) && $UPLOAD->exists($HTTP_SESSION_VARS['uploadid']) )
  {
    $newupload = false;
  
    $uploadid = $HTTP_SESSION_VARS['uploadid'];
  }
  else
  {
    $newupload = true;
  
    // new upload - add a new row
    $uploadid = $UPLOAD->addrow();

	$UPLOAD->setval( time(), "uploaded", $uploadid );
	
	if( $CONF->getval("takeip")==1 ) 
	{
	  $ip = getipaddress();
	  adduploadinfo( $uploadid, "IP Address", $ip );
    }
	elseif( $CONF->getval("takeip")==2 ) 
	{
	  $host = @gethostbyaddr( getipaddress() );
	  adduploadinfo( $uploadid, "Host Name", $host );
    }
  }
  
  
  ///////////////////////////////////////
  // CHECK IF THE UPLOADED IS BANNED
  ///////////////////////////////////////

  if( $CONF->getval("banned_ips") )
  {
	$baniphosts = explode( "\n", $CONF->getval("banned_ips") );

	foreach( $baniphosts AS $baniphost )
	{
	   $baniphost = trim( $baniphost );
	
	   if( $baniphost==$ip ) exit( "You have been banned from uploading files." );
	   
	   if( isset($host) )
	   {
	     if( stristr($host, $baniphost) ) exit( "You have been banned from uploading files." );
	   }
	}
  }
  

  ///////////////////////////////////////
  // FIND AND CREATE THE SUBDIR
  ///////////////////////////////////////
  
  if( !$newupload )
  {
    $subdir = $UPLOAD->getval( "subdir", $uploadid );
  }
  elseif( $CONF->getval("subdir")=="select" )
  {
    // if the list was not displayed (no subdirectory)
	// then it will be uploaded on the /files directory
	// since the subdirlist variable will not be set
    $subdir = $_POST['subdirlist'];
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
    $subdir = date("Y-m-d");
	
	// check if we need to add a number
	while( is_dir( $filesdir.$subdir ) )
	{
	  $num++;
	  $subdir = date("Y-m-d") . "_" . $num;
	}
	
	$subdir .= "/";
  }
  elseif( $CONF->getval("subdir")=="field" ) // the subdir is a field
  {
	$fieldid = $CONF->getval("subdir_field");
	
	if( $fieldid != -1 )
	  $subdir = makedirname( $_POST["f".$fieldid] ) . "/";
	else
	  $subdir = sprintf( "%03d", $uploadid ) . "/";
  }
  elseif( $CONF->getval("subdir")=="user" )
  {
    $userfield = $CONF->getval( "subdir_user" );
	$subdir = makedirname( $USER->getval($userfield,$HTTP_SESSION_VARS['userid']) ) . "/";
  }
  
  $uploaddir = $path . $filesdir . $subdir;
  
  if( !@is_dir( $uploaddir ) )
  {
    $oldumask = @umask( 0 );
	
    if( !@mkdir( $uploaddir, 0777 ) )
	{
	  echo( $_LANG['dir_creation_error'] );
	  exit;
	}
	
	@umask( $oldumask );
  }
  
  $UPLOAD->setval( $subdir, "subdir", $uploadid );
  
  
  ///////////////////////////////////////
  // FIND THE UPLOAD NAME
  ///////////////////////////////////////

  if( $CONF->getval("namefield") == -1 )
  {
    // use the upload ID for the name
    $uploadname = "Upload #$uploadid";
  }
  else
  {
    $uploadname = $_POST['f'.$CONF->getval("namefield")];
  }
  
  $UPLOAD->setval( $uploadname, "name", $uploadid );
  
  
  ///////////////////////////////////////
  // FIND THE USER (IF ANY)
  ///////////////////////////////////////
  
  if( $CONF->getval("formprotect")=="user" )
  {
    $UPLOAD->setval( $HTTP_SESSION_VARS['userid'], "user", $uploadid );
	
    // if no email field, take the user's email
    if( $CONF->getval("emailfield")=="-1" )
    {
	  $useremail = $USER->getval( "email", $userid );
	  $UPLOAD->setval( $useremail, "email", $uploadid );
    }
  }
  else
  {
    $UPLOAD->setval( 0, "user", $uploadid );
  }
  
  
  ///////////////////////////////////////
  // LOOPS THROUGH THE ACTIVE FIELDS
  ///////////////////////////////////////
  
  $FIELD->sortdata( "order", "asc" );
  
  $fields = $FIELD->get();
  $errors = array();
  $totalsize = 0;
  
  foreach( $fields AS $field )
  {
    $fname = "f" . $field['id'];
    
	
	///////////////////////////////////////
	// THE FIELD IS A FILE
	///////////////////////////////////////
	
	if( $field['type']=="file" )
	{
	  ///////////////////////////////////////
	  // CHECK IF THE FILE WAS UPLOADED
	  ///////////////////////////////////////
	  
	  if( checkversion("4.2.0") ) // can we use the error code?
	  {
	    // don't use the error constants since they exist only since PHP 4.3
	  
		if( $_FILES[$fname]['error']==1 ) // The file exceeds the upload_max_filesize directive
		{
		  $errors[$field['id']] = $_LANG['maxsize_settings'];
	      continue;
		}
		elseif( $_FILES[$fname]['error']==2 ) // The file exceeds the MAX_FILE_SIZE directive 
		{
		  $errors[$field['id']] = $_LANG['maxsize_form'];
	      continue;
		}
		elseif( $_FILES[$fname]['error']==3 ) // The file was only partially uploaded
		{
		  $errors[$field['id']] = $_LANG['partial_upload'];
	      continue;
		}
		elseif( $_FILES[$fname]['error']==4 ) // No file was uploaded
		{
		  // only put an error if the field was required
		  if( $field['required'] ) $errors[$field['id']] = $_LANG['required_missing'];
	      continue;
		}
		elseif( $_FILES[$fname]['error']==5 ) // The user typed the filename
		{
		  $errors[$field['id']] = $_LANG['invalid_file'];
	      continue;
		}
	  }
	  else
	  {
	    $isuploaded = false;
	  
	    if( $safemode )
	    {
	      $isuploaded = is_uploaded_file( $_FILES[$fname]['tmp_name'] );
	    }
	    else
	    {
	      if( $_FILES[$fname]['name']!="" && $_FILES[$fname]['name']!="none" && $_FILES[$fname]['tmp_name']!="none" && $_FILES[$fname]['tmp_name']!="" ) $isuploaded = true;
	    }
		
		if( !$isuploaded )
		{
		  if( $field['required'] ) $errors[$field['id']] = $_LANG['required_missing'];
	      continue;
		}
	  }
	  
	  ///////////////////////////////////////
	  // CHECK FILE EXTENSION
	  ///////////////////////////////////////
	  
	  if( $CONF->getval("extmode")!="all" )
	  {
		$fileext =  strtolower( substr( strrchr( $_FILES[$fname]['name'], "." ), 1 ) );
		$extarray = explode( " ", $CONF->getval("extensions") );
		  
		if( $CONF->getval("extmode")=="except" && in_array($fileext,$extarray) )
		{
		  $errors[$field['id']] = $_LANG['ext_not_accepted'] . " (.$fileext)";
		  continue;
		}
		elseif( $CONF->getval("extmode")=="only" && !in_array($fileext,$extarray) )
		{
		  $errors[$field['id']] = $_LANG['ext_not_accepted'] . " (.$fileext)";
		  continue;
		}
	  }

	  // block .htaccess files
	  if( $_FILES[$fname]['name']==".htaccess" )
	  {
		$errors[$field['id']] = $_LANG['ext_not_accepted'];
		continue;
	  }

		
	  //////////////////////////////////////
	  // CHECK FILE SIZE
	  //////////////////////////////////////
		
	  if( $CONF->getval("limitsize") )
      {
        $filesize = (int) round( $_FILES[$fname]['size'] / 1024 );

        if( $filesize > $CONF->getval("sizemax") )
        {
          $errors[$field['id']] = $_LANG['file_too_large'] . $CONF->getval("sizemax") . "Kb)";
	      continue;
        }
        elseif( $filesize < $CONF->getval("sizemin") )
        {
          $errors[$field['id']] = $_LANG['file_too_small'] . $CONF->getval("sizemin") . "Kb)";
          continue;
        }
	  }
		
		
	  //////////////////////////////////////
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
		
		
	  //////////////////////////////////////
	  // COPY THE FILE TO THE FOLDER
	  ///////////////////////////////////////
		
	  if( $safemode )
	  {
		move_uploaded_file( $_FILES[$fname]['tmp_name'], $uploaddir.$filename );
	  }
	  else
	  {
		copy( $_FILES[$fname]['tmp_name'], $uploaddir.$filename );
	  }
		
	  
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
			  $errors[$field['id']] = $_LANG['image_width'] . $CONF->getval("widthmin") . " " . $_LANG['pixels'];
			else
			  $errors[$field['id']] = $_LANG['image_width'] . $CONF->getval("widthmin") . "-" . $CONF->getval("widthmax") . " " . $_LANG['pixels'];

			// invalid file, delete it
			@unlink( $uploaddir.$filename );
			continue;
		  }
		  elseif( $height<$CONF->getval("heightmin") || $height>$CONF->getval("heightmax") )
		  {
			// single height allowed
			if( $CONF->getval("heightmin")==$CONF->getval("heightmax"))
			  $errors[$field['id']] = $_LANG['image_height'] . $CONF->getval("heightmin") . $_LANG['pixels'];
			else
			  $errors[$field['id']] = $_LANG['image_height'] . $CONF->getval("heightmin") . "-" . $CONF->getval("heightmax") . $_LANG['pixels'];

			// invalid file, delete it
			@unlink( $uploaddir.$filename );
			continue;
		  }
		}
	  }
	  
	  
	  ///////////////////////////////////////
	  // SAVE THE FILE INFORMATION
	  ///////////////////////////////////////
	  
	  $fileid = $FILE->addrow();
	  $FILE->setval( $uploadid, "upload", $fileid );
	  $FILE->setval( $filename, "name", $fileid );
	  $FILE->setval( $_FILES[$fname]['type'], "type", $fileid );
	  $FILE->setval( $_FILES[$fname]['size'], "size", $fileid );
		
		
	  ///////////////////////////////////////
	  // CALCULATE FILES LIST AND SIZE
	  ///////////////////////////////////////
		
	  $filesize = (int) round( $FILE->getval("size",$fileid) / 1024 );

	  $fileurl = "$ezuploadurl/$filesdir" . rawurlencode( $UPLOAD->getval("subdir",$uploadid) . $filename );
	  $fileurl = str_replace( "%2F", "/", $fileurl );
	  
	  $links_html .= "<BR><A HREF=\"$fileurl\">$filename</a> - {$filesize}Kb - ".$FILE->getval("type",$fileid)."\n";
      $links_text .= "\n$fileurl ({$filesize}Kb)";

	  $totalsize += $filesize;
		
	  // generate file list to find again which files were uploaded
	  // this upload (this prevents files to be attached several times)
	  $currentfiles[] = $fileid;
	}
	
	
	///////////////////////////////////////
	// THE FIELD IS AN USER INFO
	///////////////////////////////////////
	
	else
	{
	  if( empty($_POST[$fname]) || !isset($_POST[$fname]) )
	  {
	    // forget the session data
	    session_unregister( $fname );
		
		if( $field['required'] )
		{
		  if( $field['type']=="checkbox" || $field['type']=="radio" )
		  {
		    $errors[$field['id']] = $_LANG['no_option_checked'];
		  }
		  else
		  {
		    $errors[$field['id']] = $_LANG['required_missing'];
		  }
		}
	    
		continue;
	  }
	  
	  
	  ///////////////////////////////////////
	  // CHECK FIELD LENGTH
	  ///////////////////////////////////////
	  
	  if( $field['type']!="dropbox" && $field['type']!="checkbox" && $field['type']!="radio" )
	  {
	    if( strlen($_POST[$fname])<$field['minchars'] && $field['required'] )
		{
		  $errors[$field['id']] = $_LANG['value_too_short'] . $field['minchars'] . ")";
		}
		elseif( strlen($_POST[$fname]) > $field['maxchars'] )
		{
		  $errors[$field['id']] = $_LANG['value_too_long'] . $field['maxchars'] . ")";
		}
	  }
	
	
	  ///////////////////////////////////////
	  // CHECK EMAIL VALIDITY
	  ///////////////////////////////////////
	
	  if( $field['id']==$CONF->getval("emailfield") )
	  {
	    if( !isemail($_POST[$fname]) && !empty($_POST[$fname]) )
		{
		  $errors[$field['id']] = $_LANG['invalid_address'];
		}
		
		// All email address are saved under the same field
		$field['name'] = "email";
	  }
	
	
	  ///////////////////////////////////////
	  // FORMAT CHECKBOX DATA
	  ///////////////////////////////////////
	  
	  if( $field['type']=="checkbox" )
	  {
	    if( isset($_POST[$fname]) )
		{
		  $_POST[$fname] = implode( "\n", $_POST[$fname] );
		}
	  }
	
	
	  ///////////////////////////////////////
	  // PUT VALUE IN ARRAY AND SESSION
	  ///////////////////////////////////////
	  
	  if( $field['name']=="email" )
	    $UPLOAD->setval( $_POST[$fname], "email", $uploadid );
	  else
	    adduploadinfo( $uploadid, $field['name'], $_POST[$fname] );

  	  // sessions will be used if the "try again" button is pressed
	  // it will make it possible to keep the data submitted...
	  // use $HTTP_SESSION_VARS for bug in PHP 4.0.6
      $HTTP_SESSION_VARS[$fname] = $_POST[$fname];
	}
  }
  
 
  ///////////////////////////////////////
  // IF THERE IS NO ERROR...
  ///////////////////////////////////////
  
  if( count($errors)==0 )
  {
    // only save data if no fatal error
    $UPLOAD->savedata();
	$UPLOADINFO->savedata();
    $FILE->savedata();
  
	// use this for PHP 4.0.6 bug
    $HTTP_SESSION_VARS['uploadid'] = $uploadid;
 
    // send email to user and admin
	include( $path . "form_notify.php" );
  }
  
  
  ////////////////////////////////////////
  // DISPLAY THE RESULTS
  ////////////////////////////////////////
  
  include( $path . "form_results.php" );
  
?>