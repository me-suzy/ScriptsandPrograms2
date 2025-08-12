<?php 
// Copyright 2001 - 2004 SQLFusion LLC           info@sqlfusion.com
  /**
   * Main event Controler
   * This is an instance of the Event controler that will be managing the execution of the events and set the next url
   * @see EventControler
   * @package PASSiteTemplate
   * @author Philippe Lewicki  <phil@sqlfusion.com>
   * @copyright  SQLFusion LLC 2001-2004
   * @version 3.0
   */

   include_once("config.php") ;

  //  "start event control" ;
  $eventControler = new EventControler($conx) ;
  $eventControler->setMyDbPath($cfg_local_mydbdir) ;
  $eventControler->addparam("dbc", $conx) ;
  $eventControler->addparam("doSave", "yes") ;
  $eventControler->setMessagePage("message.php");
//  If you are under ssl or have complaining about infinit redirect of page time out
//  uncomment the line below.  
//  $eventControler->setCheckReferer(false);
  $eventControler->addallvars(); 
  $eventControler->listenEvents($_REQUEST['mydb_events']) ;

  $eventControler->doForward() ;


?>
