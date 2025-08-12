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

  $ref = checklogin( false );
  
  
  ////////////////////////////////////////////////
  // DO SOME WORK BEFORE THE DISPLAY
  ////////////////////////////////////////////////

  if( $verified )
  {
    $info = "Thank you! Your email has been successfully verified. ";
  }
  else
  {
    $info = "Thank you! Your account has been successfully setup. ";
  }
  
  if( $ref[status]==1 )
  {
    if( $CONF[confirm_new] ) $info .= "A confirmation email has been sent with your login and password. ";
    $info .= "You have now access to your account and you can <a href='getcode.php?PHPSESSID=$PHPSESSID'>get the code</a> to link our site(s). If you start sending us hits right now, your link might be on our site(s) in a few minutes!";
  }
  elseif( $ref[status]==2 )
  {
    $info .= "This account is now waiting for our approvation. Please give us 24 hours to review your site and you'll be notified of the results at the email address you provided.";
  }
  elseif( $ref[status]==3 )
  {
    $info .= "We have sent an email at the address you provided to verify its validity. Please check your email and click on the link inside to continue. <a href='mailto:{$CONF[admin_email]}'>Contact us</a> if you don't receive an email within 24 hours.";
  }
  
  
  ////////////////////////////////////////////////
  // START DISPLAYING THE PAGE
  ////////////////////////////////////////////////
  
  showheader();
  
  showinfo( $info );

  showfooter();
  
?>