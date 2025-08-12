<html>
<head>
<link rel="stylesheet" href="main.css">
</head>
<body>

<?
/////////////////////////////////////////////////////////////
// Program Name         : Autolinks Professional            
// Program Version      : 2.0                               
// Program Author       : ScriptsCenter                     
// Supplied by          : CyKuH [WTN] , Stive [WTN]         
// Nullified by         : CyKuH [WTN]                       
// Distribution         : via WebForum and Forums File Dumps
//                   (c) WTN Team `2002
/////////////////////////////////////////////////////////////

  include( "initialize.php" );

  $success = false;
  
  if( $submitted=="convert" )
  {
    //////////////////////////////////////////////
    // set the default autolinks url
    //////////////////////////////////////////////
  
    echo( "Updating the al_site variables... " );
  
    $res_site = mysql_query( "SELECT * FROM al_site" );
  
    while( $site = mysql_fetch_array($res_site) )
    {
      $url = checkurl( $site[url] );
	  $alurl = $url . "autolinks/";
	
	  mysql_query( "UPDATE al_site SET url='$url', alurl='$alurl' WHERE login='{$site[login]}'" );
    }
  
    echo( "OK<br>" );
  

    //////////////////////////////////////////////
    // set the $fromsite to an active site
    //////////////////////////////////////////////
  
    echo( "Updating the al_ref variables... " );
  
    $res_site = mysql_query( "SELECT * FROM al_site WHERE status=1 LIMIT 1" );
  
    if( mysql_num_rows($res_site)>0 )
    {
      $site = mysql_fetch_array( $res_site );
      mysql_query( "UPDATE al_ref SET fromsite='{$site[login]}'" );
    }
    else
    {
      // no active site, use a random one
      $res_site = mysql_query( "SELECT * FROM al_site LIMIT 1" );
	  $site = mysql_fetch_array( $res_site );
	  mysql_query( "UPDATE al_ref SET fromsite='{$site[login]}'" );
    }  

    echo( "OK<br>" );
  
  
    /////////////////////////////////////////////////////  
    // update all the images according to the path given
    /////////////////////////////////////////////////////
  
    $res_oldimg = mysql_query( "SELECT * FROM al_oldimg" );
  
	$path = checkurl( $path );  
  
    $numerrors = 0;
  
    while( $oldimg = mysql_fetch_array($res_oldimg) )
    {
      $filename = $path . $oldimg[id] . "." . $oldimg[extension];
  
      echo( "Copying $filename in database... " );
  
      $fp = @fopen( $filename, "rb" );  
	  if( !$fp )
	  {
	    echo( "Failed!<br>" );
	    $numerrors++;
	    continue;
	  }
	
	  $rawdata = fread( $fp, 9999999 );
	  $rawdata = addslashes( $rawdata );
	
      fclose( $fp );
	
	  mysql_query( "INSERT INTO al_img SET
	
				  	type='referrer',
				  	login='{$oldimg[aff]}',
				  	format='{$oldimg[type]}',
				  	extension='{$oldimg[extension]}',
					updated=NOW(),
				  	rawdata='{$rawdata}'" );
		
	  @mysql_query( "DELETE FROM al_oldimg WHERE id='{$oldimg[id]}'" );
		
	  if( $oldimg[type]=="thumb" )
	  {		  
	    mysql_query( "UPDATE al_ref SET thumb='{$oldimg[description]}' WHERE login='{$oldimg[aff]}' LIMIT 1" );
	  }
	  else
	  {
	    mysql_query( "UPDATE al_ref SET description='{$oldimg[description]}' WHERE login='{$oldimg[aff]}' LIMIT 1" );
	  }
	
	  echo( "OK<br>" );
    }
  
    if( $numerrors==0 )
    {
      @mysql_query( "DROP TABLE al_oldimg" );
      echo( "<br>All images have been successfully copied to the database. You may delete the images directory if you want.<br><br>" );
      $success = true;
	}
    else
    {
      echo( "<br>Warning! One or more images could not be copied. It may be that the images were deleted. Please check the image URL was correct. If you want to ignore these images and continue, <a href='upgrade_finish.php'>click here</a>.<br><br>" );
    }
  }
  
  if( !$success ):
?>

On version 2.0, all images are stored in the database. The script will copy the current images in the database (so you don't lose any data) but it needs to know the place where all the images were located in the previous version. So please enter either the URL (http://) or the relative path (for example, ../images) of the image directory for the previous installation.
<form method="post" action="<?=$PHP_SELF?>">
<input type="hidden" name="submitted" value="convert">
<table border="0" cellspacing="0" cellpadding="3" width="100">
  <tr>
    <td>
      <input type="text" name="path" size="50">
    </td>
  </tr>
  <tr>
    <td align="right">
      <input type="submit" name="Submit" value="Submit">
    </td>
  </tr>
</table>
</form>

<? else: ?>

<form method="post" action="upgrade_finish.php">
<input type="submit" name="continue" value="Continue to Next Step">
</form>

<? endif; ?>

</body>
</html>