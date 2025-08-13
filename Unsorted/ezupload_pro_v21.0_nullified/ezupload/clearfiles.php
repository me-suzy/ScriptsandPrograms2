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

  $files = $FILE->get();
  
  foreach( $files AS $file )
  {
	$filename = $filesdir . $UPLOAD->getval("subdir",$file['upload']) . $file['name'];
	
	if( !file_exists($filename) )
	{
	  $FILE->deleterow( $file['id'] );
	}
  }
  
  $uploads = $UPLOAD->get();
  
  foreach( $uploads AS $upload )
  {
    // no file under this upload
	if( $FILE->getnumrows($upload['id'],"upload") == 0 )
	{
	  // attempt to delete directory
	  if( !$safemode && $UPLOAD->getval("subdir",$upload['id'])!="" )
	  {
	    if( file_exists( $filesdir . $UPLOAD->getval("subdir",$upload['id']) ) )
		{
		  @rmdir( $filesdir . $UPLOAD->getval("subdir",$upload['id']) );
	    }
	  }
	  
	  $UPLOAD->deleterow( $upload['id'] );
	}
  }
  
  $FILE->savedata();
  $UPLOAD->savedata();
  
?>