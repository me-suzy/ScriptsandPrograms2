<?php
/////////////////////////////////////////////////////////////
// Program Name         : EzUpload Pro                       
// Program Version      : 2.20                                
// Program Author       : ScriptsCenter.com                  
// Supplied by          : CyKuH [WTN]                        
// Nullified and tested : CyKuH [WTN]                        
// Distribution         : via WebForum and Forums File Dumps 
//                    WTN Team `2004
/////////////////////////////////////////////////////////////
  function checkfile( $filename, $mode )
  {
    if( $mode=="read" )
	{
	  echo( "Checking if {$filename} is readable... " );
	  test( file_exists( $filename ) );
    }
	elseif( $mode=="write" )
	{
	  echo( "Checking if {$filename} is writable... " );
	  test( is_writable($filename) );
	}
  }

  echo( "<b>CHECKING FILES PERMISSIONS</b><br><br>" );

  if( $_POST['from']=="10" )
  {
	checkfile( $path."settings.php", "read" );
  }
  elseif( $_POST['from']=="11" )
  {
	checkfile( $path."var_gensettings.php", "read" );
  }
  
  checkfile( $path."files", "write" );
  checkfile( $path."var_settings.php", "write" );
  
  if( $_POST['storage_method']=="file" )
  {
    checkfile( $path."var_fields.php", "write" );
	checkfile( $path."var_files.php", "write" );
	checkfile( $path."var_options.php", "write" );
	checkfile( $path."var_uploads.php", "write" );
	checkfile( $path."var_uploadinfos.php", "write" );
	checkfile( $path."var_users.php", "write" );
  }
  else
  {
    if( $_POST['from']=="20" || $_POST['from']=="21" )
	{
      checkfile( $path."var_fields.php", "read" );
	  checkfile( $path."var_files.php", "read" );
	  checkfile( $path."var_options.php", "read" );
	  checkfile( $path."var_uploads.php", "read" );
	  checkfile( $path."var_uploadinfos.php", "read" );
	  checkfile( $path."var_users.php", "read" );
    }
  }

?>