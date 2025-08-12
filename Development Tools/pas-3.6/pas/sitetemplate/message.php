<?php 
// Copyright 2001 - 2004 SQLFusion LLC           info@sqlfusion.com
  /****
   * Message display page
   * Display the content of $message
   * - param string $message contains the message to display
   *
   * @package PASSiteTemplate
   * @author Philippe Lewicki  <phil@sqlfusion.com>
   * @copyright  SQLFusion LLC 2001-2004
   * @version 3.0
   */


  include_once("config.php") ;
  $pageTitle = "Message" ;
  include("includes/header.inc.php") ;

  echo htmlentities(stripslashes($message)) ;

  include("includes/footer.inc.php") ;
?>
