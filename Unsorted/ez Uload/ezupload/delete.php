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
  include( "initialize.php" );
  
  checklogged();

  if( $demomode ) confirm( "No upload, file or field can be deleted on the demo mode" );
  
  
  ////////////////////////////////////////
  // THE USER WANTS TO DELETE A FILE
  ////////////////////////////////////////
  
  if( $_GET['type']=="file" )
  {
    if( !$FILE->exists($_GET['id']) ) confirm( "No file found with this ID" );
  
    $isdeleted = true;
  
    $fileid = $_GET['id'];
    $uploadid = $FILE->getval( "upload", $fileid );
	$filename = $filesdir . $UPLOAD->getval( "subdir", $uploadid ) . $FILE->getval( "name", $fileid );
	
	$FILE->deleterow( $fileid );
	$FILE->savedata();
	
	if( @file_exists($filename) )
	{
	  if( !@unlink( $filename ) ) $isdeleted = false;
	}
	
	// now the file is deleted, see if we need to delete an empty directory
	cleardirs();
	clearinfos();
	
	// do the upload information still exist?
	if( $UPLOAD->exists( $uploadid ) )
	{
	  if( $isdeleted )
	    confirm( "File successfully deleted", "viewfiles.php?id=$uploadid" );
	  else
	    confirm( "The file could not be removed from the server (Permission denied)<br>You'll have to do this through FTP.", "viewfiles.php?id=$uploadid" );
	}
	else
	{
	  if( $isdeleted )
	    confirm( "File and information successfully deleted", "browser.php" );
      else
	    confirm( "The file could not be removed from the server (Permission denied)<br>You'll have to do this through FTP.", "browser.php" );
	}
  }	
  
  
  ////////////////////////////////////////
  // THE USER WANTS TO DELETE AN UPLOAD
  ////////////////////////////////////////
  
  elseif( $_GET['type']=="upload" )
  {
    if( !$UPLOAD->exists($_GET['id']) ) confirm( "No upload found with this ID" );
  
    $numerrors = 0;
    $uploadid = $_GET['id'];
	$dirname = $filesdir . $UPLOAD->getval( "subdir", $uploadid );
	
	
	////////////////////////////////////////////
	// DELETE ALL THE FILES ONE BY ONE
	////////////////////////////////////////////
	
	$files = $FILE->queryrows( $uploadid, "upload" );
	
	foreach( $files AS $file )
	{
	  $filename = $dirname . $file['name'];
	
	  if( @file_exists($filename) )
	  {
		if( !@unlink($filename) ) $numerrors++;
	  }
	  
	  $FILE->deleterow( $file['id'] );
	}
	
	
	////////////////////////////////////////////
	// DELETE ALL UPLOAD INFORMATION
	////////////////////////////////////////////
	
	$infos = $UPLOADINFO->queryrows( $uploadid, "upload" );
	
	foreach( $infos AS $info )
	{
	  $UPLOADINFO->deleterow( $info['id'] );
	}
	
	
	////////////////////////////////////////////
	// CLEAR EMPTY DIR AND SAVE DATA
	////////////////////////////////////////////

	cleardirs();
	
	$UPLOAD->deleterow( $uploadid );
	
	$UPLOAD->savedata();
	$UPLOADINFO->savedata();
	$FILE->savedata();
	
	if( $numerrors > 0 )
	{
	  confirm( "One or more files could not be removed from the server<br>(Permission denied) You'll have to do this through FTP.", "browser.php" );
	}
	else
	{
	  confirm( "Upload information and files successfully removed", "browser.php" );
	}
  }	
  
  
  ////////////////////////////////////////
  // THE USER WANTS TO DELETE A FIELD
  ////////////////////////////////////////
  
  elseif( $_GET['type']=="field" )
  {
	// set email field to none if we deleted the selected field
	if( $CONF->getval("emailfield")==$_GET['id'] )
	{
	  $CONF->setval( -1, "emailfield" );
	}
	  
	// set name field to none if we deleted the selected field
	if( $CONF->getval("namefield")==$_GET['id'] )
	{
	  $CONF->setval( -1, "namefield" );
	}
	  
	// change subdir if we deleted the selected field
	if( $CONF->getval("subdir_field")==$_GET['id'] )
	{
	  $CONF->setval( $_GET['id'], "subdir_field" );
	}
	  
	$fieldtype = $FIELD->getval( "type", $_GET['id'] ); 
	  
	// if dropbox or checkbox, delete all options
	if( $fieldtype=="dropbox" || $fieldtype=="checkbox" || $fieldtype=="radio" )
	{
	  $OPTION->deleterows( $_GET['id'], "field" );
	}
	
    $FIELD->deleterow( $_GET['id'] );
	
    $FIELD->savedata();
	$CONF->savedata();
	$OPTION->savedata();
	
	confirm( "Field successfully deleted", "fields.php" );
  }
  
  ////////////////////////////////////////
  // THE USER WANTS TO DELETE AN USER
  ////////////////////////////////////////
  
  elseif( $_GET['type']=="user" )
  {
    // set user=0 for all associated uploads
    $uploads = $UPLOAD->queryrows( $_GET['id'], "user" );
	
	foreach( $uploads AS $upload )
	{
	  $UPLOAD->setval( 0, "user", $upload['id'] );
	}
	
	// delete user
	$USER->deleterow( $_GET['id'] );
	
	$UPLOAD->savedata();
	$USER->savedata();
	
	confirm( "User successfully deleted", "access.php" );
  }
  
?>