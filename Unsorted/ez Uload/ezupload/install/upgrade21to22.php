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
  echo( "<br><b>UPGRADING FROM EZUPLOAD 2.1 to 2.2</b><br><br>" );


  echo( "Extracting upload informations... " );

  $uploads = $UPLOAD->get();  

  foreach( $uploads AS $upload )
  {
    $uploadid = $upload['id'];
	
	while( list($field,$value) = each($upload) )
	{
	  switch( $field )
	  {
	    case "id":
		case "uploaded":
		case "subdir":
		case "name":
		case "email": 
		  break;
		
		default:
		  
		  adduploadinfo( $uploadid, $field, $value );
		  $UPLOAD->deletevalue( $field, $uploadid );
		  break;
	  }
	}
	
	// add user field to guest
	$UPLOAD->setval( 0, "user", $uploadid );
  }
  
  test( true );


  ///////////////////////////////////////////////////////
  // ADD THE NEW SETTINGS FOR VERSION 2.2
  ///////////////////////////////////////////////////////

  echo( "Adding new settings for version 2.2... " );

  $CONF->setval( "", "banned_ips" );
  $CONF->setval( "name", "subdir_user" );
  $CONF->setval( 0, "reload_info" );
  $CONF->setval( "", "dbhost" );
  $CONF->setval( "", "dbuser" );
  $CONF->setval( "", "dbpass" );
  $CONF->setval( "", "dbname" );

  if( $CONF->getval("formpass")=="" ) 
  	$CONF->setval( "none", "formprotect" );
  else
    $CONF->setval( "pass", "formprotect" );
	
  test( true );
	
?>