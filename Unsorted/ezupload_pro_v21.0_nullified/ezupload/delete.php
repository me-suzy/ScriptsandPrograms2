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
  
  checklogged();

  if( $_GET['type']=="file" )
  {
    if( $safemode ) confirm( "Deleting a file is not possible in safe mode.<br>Please do this manually using a FTP client." );
  
    $fileid = $_GET['id'];
    $uploadid = $FILE->getval( "upload", $fileid );
	$filename = $filesdir . $UPLOAD->getval( "subdir", $uploadid ) . $FILE->getval( "name", $fileid );
	
	$FILE->deleterow( $fileid );
	$FILE->savedata();
	
	if( file_exists($filename) )
	{
	  $result = @unlink( $filename );
	
	  if( !$result )
	  {
	    confirm( "The file could not be physically removed from the server<br>(Permission denied) You'll have to do this through FTP.", "viewfiles.php?id=$uploadid" );
	  }
	}
	
	confirm( "File successfully deleted", "viewfiles.php?id=$uploadid" );
  }	
  
  if( $_GET['type']=="upload" )
  {
    $errors = 0;
    $uploadid = $_GET['id'];
	$dirname = $filesdir . $UPLOAD->getval( "subdir", $uploadid );
	
	////////////////////////////////////////////
	// DELETE ALL THE FILES ONE BY ONE
	////////////////////////////////////////////
	
	$files = $FILE->queryrows( $uploadid, "upload" );
	
	foreach( $files AS $file )
	{
	  $filename = $dirname . $file['name'];
	
	  $FILE->deleterow( $file['id'] );
	
	  if( !$safemode )
	  {
	    if( file_exists($filename) )
	    {
	      $result = @unlink( $filename );
		  if( !$result ) $errors++;
	    }
	  }
	}
	

	////////////////////////////////////////////
	// ATTEMPT TO DELETE THE DIRECTORY
	////////////////////////////////////////////
	
	if( !$safemode )
	{
	  if( $UPLOAD->getval( "subdir", $uploadid )!="" )
	  {
	    if( file_exists($dirname) )
	    {
	      $result = @rmdir( $dirname );
	  
	      if( !$result && $errors==0 )
	      {
	        $message = "The upload directory could not be deleted (Permission denied)";
	      }
	    }
	  }
	
	  if( !isset($message) )
	  {
	    if( $errors>0 )
	    {
	      $message = "$errors could not be physically removed from the server<br>(Permission denied) You'll have to do this through FTP.";
	    }
	    else
	    {
	      $message = "Upload information and files successfully deleted";
	    }
	  }
	}
	else
	{
	  $message = "Upload information successfully deleted";
	}
	
	$UPLOAD->deleterow( $uploadid );
	
	$UPLOAD->savedata();
	$FILE->savedata();
	
	confirm( $message, "browser.php" );
  }	
  
?>