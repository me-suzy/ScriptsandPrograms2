<html>
<head>
<title>EzUpload Pro Control Panel</title>
<link rel="stylesheet" href="main.css" type="text/css">
</head>
<body>

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
    
  if( $_POST['action']=="upgrade" ):
  
	////////////////////////////////////////////
	// CONVERT UPLOADED FILES AND INFO.TXT
	////////////////////////////////////////////
  
    if( !file_exists("files") )
	{
	  echo( "Unable to find \"files/\" directory!<br>" );
	  echo( "Make sure you renamed the \"uploads/\" directory!" );
      exit();
	}
	
	$handle = opendir( "files" );
  
    while( $subdir = readdir($handle) )
    {
      if( !is_dir("files/$subdir") || $subdir=="." || $subdir==".." ) continue;
	
	  echo( "Exploring $subdir ...<br>" );

	  $uploadid = $UPLOAD->addrow();
	  $UPLOAD->setval( "$subdir/", "subdir", $uploadid );
	  $UPLOAD->setval( filemtime("files/$subdir"), "uploaded", $uploadid );
	
	  $handle2 = opendir( "files/$subdir" );
	  
	  while( $file = readdir($handle2) )
	  {
	    if( $file=="info.txt" || is_dir("files/$subdir/$file") || $file=="." || $file==".." ) continue;
	  
	    $fileid = $FILE->addrow();
	    $FILE->setval( $uploadid, "upload", $fileid );
	    $FILE->setval( $file, "name", $fileid );
	    $FILE->setval( filesize("files/$subdir/$file"), "size", $fileid );
	    $FILE->setval( "Unknown", "type", $fileid );
	  }
	
	  $infofile = "files/$subdir/info.txt";
	
	  if( file_exists($infofile) )
	  {
	    if( $fp = @fopen($infofile, "r") )
	    {
	      $comment = fread( $fp, filesize($infofile) );
		  fclose( $fp );
		
		  $UPLOAD->setval( trim($comment), "Information", $uploadid );  
		
		  @unlink( $infofile );
	    }
	  }
	  
	  closedir( $handle2 );
    }
  
    closedir( $handle );
  
    $UPLOAD->savedata();
    $FILE->savedata();
	
	
	////////////////////////////////////////////
	// CONVERT THE SETTINGS FROM EZUPLOAD 1.0
	////////////////////////////////////////////

	if( $_POST['from']=="10" )
	{
	  if( !file_exists("settings.php") )
	  { 
	    echo( "<br>Unable to find \"settings.php\" file!<br>" );
	  }
	  else
	  {
	    include( "settings.php" );
	  
	    if( $extrule=="not" ) $extrule = "except";
	  
	    $CONF->setval( $extrule, "extmode" );
	    $CONF->setval( $extensions, "extensions" );
	    $CONF->setval( $minsize, "sizemin" );
	    $CONF->setval( $maxsize, "sizemax" );
	    $CONF->setval( $thanktext, "thankyoumsg" );
	    $CONF->setval( $adminemail, "adminemail" );
	    if( $adminpass!="" )$CONF->setval( md5($adminpass), "adminpass" );
	  
	    $CONF->savedata();
	  
	    @unlink( "settings.php" );
	  
	    echo( "<br>Settings converted from ezUpload 1.0<br>" );
	  }
	}
	
	
	////////////////////////////////////////////
	// CONVERT THE SETTINGS FROM EZUPLOAD 1.1
	////////////////////////////////////////////
	
	elseif( $_POST['from']=="11" )
	{
	  if( !file_exists("var_gensettings.php") )
	  {
	    echo( "<br>Unable to find \"var_gensettings.php\" file!<br>" );
	  }
	  else
	  {
	    include( "var_gensettings.php" );
	  
	    if( $extrule=="not" ) $extrule = "except";
	  
	    $CONF->setval( $extrule, "extmode" );
	    $CONF->setval( $extensions, "extensions" );
	    $CONF->setval( $minsize, "sizemin" );
	    $CONF->setval( $maxsize, "sizemax" );
	    $CONF->setval( $thanktext, "thankyoumsg" );
	    $CONF->setval( $redirurl, "redirecturl" );
	    $CONF->setval( $adminemail, "adminemail" );
	    if( $adminpass!="" )$CONF->setval( md5($adminpass), "adminpass" );
	  
	    $CONF->savedata();
		
		@unlink( "var_gensettings.php" );
	  
	    echo( "<br>Settings converted from ezUpload 1.1<br>" );
	  }
	}
	
?>

<br>
Upgrade finished, you may now continue to the <a href="cpanel.php">control panel</a>.<br>
For your security, please delete the "upgrade.php" file from your server immediately.

<? else: ?>

This will upgrade your older version of ezUpload to version 2.0. Before clicking<br>
below, please make sure that you have moved all the directories with the files on<br>
a directory called "files/" (the upload directory on previous version was called by<br>
default "uploads/"). The upgrade will convert all your files, settings as well as the<br>
information provided by the users.<br>
<br>
<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
<input type="hidden" name="action" value="upgrade">
<input type="radio" name="from" value="none"> Don't upgrade settings (only convert files)<br>
<input type="radio" name="from" value="10"> Upgrade settings from ezUpload 1.0 to 2.0 (required settings.php file)<br>
<input type="radio" name="from" value="11"> Upgrade settings from ezUpload 1.1 to 2.0 (require var_gensettings.php file)<br><br>
<input type="submit" name="upgrade" value="Upgrade Now!">
</form>

<? endif; ?>

</body>
</html>