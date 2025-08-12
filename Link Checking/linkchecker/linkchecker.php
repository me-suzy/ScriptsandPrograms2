<?php
error_reporting(E_ALL);

// Gets urls
function GetUrls( $html, $url ) {
  // Gets url ready to use
  $info  = @parse_url( $url );

  // Splits the headers from the html
  $pieces = explode( "\r\n\r\n", $html,2 );
  $html   =  $pieces[1];
  unset( $pieces );

  // Finds any links in the HTML
  $matches = array();
  preg_match_all("|href\=\"?'?`?([[:alnum:]:?=&@/;._-]+)\"?'?`?|i", $html, & $matches);
  $links = array();
  $ret = $matches[1];
  for($i=0;isset($ret[$i]);$i++) {
     if(preg_match("|^http://(.*)|i",$ret[ $i])) {
         $links[] = $ret[$i];
     } elseif(preg_match("|^/(.*)|i",$ret[$i])) {
         $links[] = "http://".$info["host"]."". $ret[$i];
     }
  }
  return $links;
}

// Gets Unique Urls
function GetUniqueUrls( $html, $url ) {

  if ( !$html ){
    return false;
  }

  // Gets the list of urls
  $urls = GetUrls ( $html, $url );
  $uurls = array();
  for( $i=0;isset($urls[$i]);$i++ ) {

    // Checks if the url is in the array
    if(!in_array($urls[$i], $uurls )) {

      // If it's not it adds it
      $uurls[] = $urls[$i];
    }
  }
  return $uurls;
}

// Gets everything headers and HTML
function geteverything($url) {
  // Gets url ready to use
  $info  = @parse_url( $url );

  // Opens socket
  $fp    = @fsockopen( $info["host"], 80, $errno, $errstr, 10 );

  // Makes sure the socket is open or returns false
  if ( !$fp ) {
     return false ;
  } else {

     // Checks the path is not empty
     if( empty( $info["path"] ) ) {

        // If it is empty it fills it
        $info["path"] = "/";
     }
     $query = "";

     // Checks if there is a query string in the url
     if( isset( $info["query"] ) ) {

          // If there is a query string it adds a ? to the front of it
          $query = "?".$info["query"]."";
     }

     // Sets the headers to send
     $out  = "GET ".$info["path"]."".$query." HTTP/1.0\r\n";
     $out .= "Host: ".$info["host"]."\r\n";
     $out .= "Connection: close \r\n";
      $out .= "User-Agent: link_checker/1.1\r\n\r\n";

     // writes the headers out
     fwrite( $fp, $out );
     $html = '';

     // Reads what gets sent back
     while ( !feof( $fp ) ) {
          $html .= fread( $fp, 8192 );
     }

     // Closes socket
     fclose( $fp );
  }
  return $html;
}

// Gets everything headers
function gethead($url) {
  // Gets url ready to use
   $info  = @parse_url( $url );

  // Opens socket
  $fp    = @fsockopen( $info["host"], 80, $errno, $errstr, 10 );

  // Makes sure the socket is open or returns false
  if ( !$fp ) {
     return false;
  } else {

     // Checks the path is not empty
     if( empty( $info["path"] ) ) {

        // If it is empty it fills it
        $info["path"] = "/";
     }
     $query = ""; 

     // Checks if there is a query string in the url
     if( isset( $info["query"] ) ) {

          // If there is a query string it adds a ? to the front of it
          $query = "?".$info["query"]."";
     }

     // Sets the headers to send
      $out  = "HEAD ".$info["path"]."".$query." HTTP/1.0\r\n";
     $out .= "Host: ".$info["host"]."\r\n";
     $out .= "Connection: close \r\n" ;
     $out .= "User-Agent: link_checker/1.1\r\n\r\n";

     // writes the headers out
     fwrite( $fp, $out );
     $html = '';

     // Reads what gets sent back
     while ( !feof( $fp ) ) {
          $html .= fread( $fp, 8192 );
     }

     // Closes socket
     fclose( $fp );
  }
  return $html;
}

// Gets status code
function getstatuscode( $header ) {

  // Splits the headers into an array
  $headers = explode( "\r\n", $header );
  unset( $header );
  for( $i=0;isset( $headers[$i] );$i++ ) {

    // Checks if the header is the status header
    if( preg_match( "/HTTP\/[0-9A-Za-z +]/i" ,$headers[$i] ) ) {

      // If it is save the status
      $status = preg_replace( "/http\/[0-9]\.[0-9]/i","",$headers[$i] );
    }
  }
  return $status;
}

if(isset($_GET['url'])) {

   // Print the url were checking
   print "Checking link: <a href=\"".$_GET['url']."\">".$_GET['url']."</a> ...<br />\n";
   ob_flush();
   flush();

   // Check it
   $html = geteverything ( $_GET['url'] );
   if(!$html) {
     print "Sorry that link appears not to exist.<br /><br />\n";
   } else {

      // Get status code
      $done = getstatuscode( $html );

      // Print results
      print "". $done."<br /><br />\n";

      // Get urls from main page
      $urls = GetUniqueUrls( $html, $_GET['url'] );
      for($i=0;isset($urls[$i]);$i++) {

          // Print the link were checking
         print "Checking link: <a href=\"".$urls[$i]."\">".$urls[$i]."</a> ...<br />\n";
         ob_flush();
         flush();

         // Get everything
          $html = gethead($urls[$i]);

         // Check it
         if(!$html) {
            $done = "Sorry that link appears not to exist.<br /><br />\n";
         } else {
            // Get status code
             $done = getstatuscode( $html );
         }
         // Show the results
         print "".$done."<br /><br />\n";

         ob_flush();
         flush();

         // Sleep so we don't send requests to the same server to fast
         sleep(3);
      }
   }

} else {

    print "PHP link checker to help get rid of any dead links on your web pages.<br />\n";
    print "To use this free online like checker, Just type in the box below the page\n";
    print "uri you want to check then click the \"Check links\" button. Then the link\n";
    print "checker will crawl your web page and get the links out of it and check them.\n";
    print "It will return a list of links it has checked and tell you the status i.e.\n";
    print "404 NOT FOUND, 200 OK, ect.</p>\n";
    print "<form action=\"linkchecker.php\" method=\"get\">\n";
    print "<p><input type=\"text\" name=\"url\" value=\"http://\" size=\"40\" />\n";
    print "<input type=\"submit\" value=\"Check links\" />\n";
    print "</p></form><p>\n";
    print "<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />\n";
    print "<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />\n";

}
?>