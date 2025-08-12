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

  // search all images in image table which haven't been updated
  $res_img = mysql_query( "SELECT * FROM al_img WHERE updated>='$prevupdate'" );

  while( $img = mysql_fetch_array($res_img) )
  {
    // check the directory exists
	if( !is_dir( "{$alpath}images/{$img[type]}/{$img[format]}" ) )
	{
	  $oldumask = umask(0);
	  @mkdir( "{$alpath}images", 0777 );
	  @mkdir( "{$alpath}images/{$img[type]}", 0777 );
	  @mkdir( "{$alpath}images/{$img[type]}/{$img[format]}", 0777 );
	  umask($oldumask);
	}
  
    $fp = @fopen( "{$alpath}images/{$img[type]}/{$img[format]}/{$img[login]}.{$img[extension]}", "wb" );

    if( $fp )
    {
      fwrite( $fp, $img[rawdata] );
      fclose( $fp );
    }
  }
  
?>