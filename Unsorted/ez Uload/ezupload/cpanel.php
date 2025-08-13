<?
///////////////////////////////////////////////////////////////////////////////
//                                                                           //
//   Program Name         : EzUpload Pro                                     //
//   Program Version      : 2.20                                             //
//   Supplied by          : CyKuH [WTN]                                      //
//   Nullified by         : CyKuH [WTN]                                      //
//   Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                           //
///////////////////////////////////////////////////////////////////////////////

  include( "initialize.php" );

  $UPLOAD->sortdata( "uploaded", "desc" );

  $uploads = $UPLOAD->get();
  
  if( count($uploads)>0 ):
  
    header( "Location: browser.php?$SID" );
	
  else:
  
    header( "Location: settings.php?$SID" );
	
  endif;
  
?>