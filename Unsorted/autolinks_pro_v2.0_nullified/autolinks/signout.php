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
  
  session_unset();

  setcookie( "sesslogin", "" );
  setcookie( "sesspass", "" );
  
  $info = "Thank you, you have been successfully signed out. You may now <a href='signin.php'>sign in</a> under another account or <a href='register.php'>register</a> a new account. Or you can go back to the <a href='{$site[url]}'>main page</a>.";
  

  ////////////////////////////////////////////////
  // START DISPLAYING THE PAGE
  ////////////////////////////////////////////////
  
  showheader();
  
  showinfo( $info );

  showfooter();
  
?>