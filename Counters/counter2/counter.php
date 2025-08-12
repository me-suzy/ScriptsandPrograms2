<?php
//Counter by des.
//Counts using flatfile and cookies.

//Get the absolute location of this file
$absolute = dirname(__FILE__);

//Read the ips from counter
$ips = file( $absolute."/log.txt" );

//Get the users ip, and put in shorter string
$ip = $_SERVER['REMOTE_ADDR'];


if( ( $_COOKIE['unique_cookie'] ) or ( array_search( $ip, $ips ) ) ) {
  //===================================
  //If the user has already been logged
  //===================================

  //Make sure cookie is set
  if(!$_COOKIE['unique_cookie']) {
    setcookie( "unique_cookie", true );
  }

  //Make sure user is in the database
  if( !array_search( $ip, $ips ) ) {
    //Get the ips from the ip log
    $cv = fopen( $absolute . "/log.txt", r);
    $pre = fread( $cv, filesize( $absolute . "/log.txt" ) );
    fclose( $cv);

    //Add an ip to the bottom
    $cf = fopen( $absolute . "/log.txt", w);
    $new = $pre . "\n" . $_SERVER['REMOTE_ADDR'];
    fwrite( $cf, $new );
    fclose( $cf );
  }

} else {
  //===================================
  //If user is thought to be unique
  //===================================  

  //Add the IP to the IP database
  $cv = fopen( $absolute . "/log.txt", r);
  $pre = fread( $cv, filesize( $absolute . "/log.txt" ) );
  fclose( $cv);

  //Add an ip to the bottom
  $cf = fopen( $absolute . "/log.txt", w);
  $new = $pre . "\n" . $_SERVER['REMOTE_ADDR'];
  fwrite( $cf, $new );
  fclose( $cf );

  //Set the cookie
  setcookie( "unique_cookie", true );

  //Get the value of the hit counter
  $cv = fopen( $absolute . "/counter.dat", r);
  $pre = fread( $cv, 100 );
  fclose( $cv);

  //Add one to the hit counter.
  $cf = fopen( $absolute . "/counter.dat", w);
  $new = $pre + 1;
  fwrite( $cf, $new );
  fclose( $cf );
}
?>