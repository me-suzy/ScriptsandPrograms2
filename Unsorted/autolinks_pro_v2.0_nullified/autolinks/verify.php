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

  include( "ra_initialize.php" );

  
  ////////////////////////////////////////////////
  // DO SOME WORK BEFORE THE DISPLAY
  ////////////////////////////////////////////////
  
  $res_ref = mysql_query( "SELECT * FROM al_ref WHERE login='$login' AND code='$code' LIMIT 1" );
  
  if( mysql_num_rows($res_ref)==0 )
  {
    $notice = "Invalid activation code or login! <a href='mailto:{$CONF[admin_email]}' class='highlight'>Email us</a> if you feel there's a problem.";
  }
  else
  {
    $ref = mysql_fetch_array( $res_ref );
  
    // check the referrer hasn't already been verified
	if( $ref[status]==3 )
	{
	  // send notificaton to admin
	  if( $CONF[notify_new] ) email_templ( "new_ref", $ref[login] );
	
	  if( $CONF[moderate_new] )
	  {
	    // referrer must wait moderation
	    mysql_query( "UPDATE al_ref SET status=2 WHERE login='$login' LIMIT 1" );
	  }
	  else
	  {
	    // referrer is now fully active
	    mysql_query( "UPDATE al_ref SET status=1 WHERE login='$login' LIMIT 1" );
		
		// send referrer a confirmation
		if( $CONF[confirm_new] ) email_templ( "confirm", $ref[login] );
	  }
	}
  
    // log the user
	session_register( "sesslogin" );
	session_register( "sesspass" );
	$sesslogin = $ref[login];
	$sesspass = $ref[password];
  
    // redirect to the final registration page
    header( "Location: register3.php?verified=1&PHPSESSID=$PHPSESSID" );
  }
  

  ////////////////////////////////////////////////
  // START DISPLAYING THE PAGE
  ////////////////////////////////////////////////
  
  showheader();
  
  shownotice( $notice );
  
  showfooter();
  
?>