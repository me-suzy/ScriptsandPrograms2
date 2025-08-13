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
  echo( "<br><b>UPGRADING EZUPLOAD 1.x TO 2.0</b><br><br>" );

  $handle = opendir( $filesdir );
  
  while( $subdir = readdir($handle) )
  {
    if( !is_dir("$filesdir/$subdir") || $subdir=="." || $subdir==".." ) continue;
	
	echo( "Converting files and info on $subdir... " );

	$uploadid = $UPLOAD->addrow();
	$UPLOAD->setval( "$subdir/", "subdir", $uploadid );
	$UPLOAD->setval( filemtime("$filesdir/$subdir"), "uploaded", $uploadid );
	
	$handle2 = opendir( "$filesdir/$subdir" );
	  
	while( $file = readdir($handle2) )
	{
	  if( $file=="info.txt" || is_dir("$filesdir/$subdir/$file") || $file=="." || $file==".." ) continue;
	  
	  $fileid = $FILE->addrow();
	  $FILE->setval( $uploadid, "upload", $fileid );
	  $FILE->setval( $file, "name", $fileid );
	  $FILE->setval( filesize("$filesdir/$subdir/$file"), "size", $fileid );
	  $FILE->setval( "Unknown", "type", $fileid );
	}
	
	$infofile = "$filesdir/$subdir/info.txt";
	
	if( file_exists($infofile) )
	{
	  if( $fp = @fopen($infofile, "r") )
	  {
	    $comment = fread( $fp, filesize($infofile) );
		fclose( $fp );
		
		adduploadinfo( $uploadid, "Informations", trim($comment) )
		
	    @unlink( $infofile );
	  }
	}
	  
	closedir( $handle2 );
	
	test( true );
  }
  
  closedir( $handle );
	
	
  ////////////////////////////////////////////
  // CONVERT SETTINGS FROM EZUPLOAD 1.0
  ////////////////////////////////////////////

  if( $_POST['from']=="10" )
  {
    echo( "Converting settings from ezUpload 1.0... " );
  
	include( $path."settings.php" );
	  
	if( $extrule=="not" ) $extrule = "except";
	  
	$CONF->setval( $extrule, "extmode" );
	$CONF->setval( $extensions, "extensions" );
	$CONF->setval( $minsize, "sizemin" );
	$CONF->setval( $maxsize, "sizemax" );
	$CONF->setval( $thanktext, "thankyoumsg" );
	$CONF->setval( $adminemail, "adminemail" );
	if( $adminpass!="" ) $CONF->setval( md5($adminpass), "adminpass" );
	  
	test( true );
  }
	
	
  ////////////////////////////////////////////
  // CONVERT SETTINGS FROM EZUPLOAD 1.1
  ////////////////////////////////////////////
	
  if( $_POST['from']=="11" )
  {
    echo( "Converting settings from ezUpload 1.1... " );
  
	include( $path."var_gensettings.php" );
	  
	if( $extrule=="not" ) $extrule = "except";
	  
	$CONF->setval( $extrule, "extmode" );
	$CONF->setval( $extensions, "extensions" );
	$CONF->setval( $minsize, "sizemin" );
	$CONF->setval( $maxsize, "sizemax" );
	$CONF->setval( $thanktext, "thankyoumsg" );
	$CONF->setval( $redirurl, "redirecturl" );
	$CONF->setval( $adminemail, "adminemail" );
	if( $adminpass!="" ) $CONF->setval( md5($adminpass), "adminpass" );
	  
	test( true );
  }

?>