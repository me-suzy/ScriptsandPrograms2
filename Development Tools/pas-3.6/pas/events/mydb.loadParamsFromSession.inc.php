<?php 
// Copyright 2001 - 2004 SQLFusion LLC           info@sqlfusion.com

/**   
  * Event Mydb.loadParamsFromSession
  *
  * This restore to the event controler the varibles of events stored 
  * in the session.
  * <br>- param string fields_{fieldsnames}
  *
  * @package PASEvents
  * @author Philippe Lewicki  <phil@sqlfusion.com>
  * @copyright  SQLFusion LLC 2001-2004
  * @version 3.0  
  */

  $disperr = new Display($this->getMessagePage()) ;
  global $mydb_paramkeys;
  
  $mydb_paramkeys = $this->getSession("mydb_paramkeys");
  $mydb_eventkey = $this->getParam("mydb_eventkey");
  $this->setLogRun(false);
  $this->setLog("\n Event Key: ".$mydb_eventkey );
  if (is_array($mydb_paramkeys) && !empty($mydb_eventkey)) {
      if (is_array($mydb_paramkeys[$mydb_eventkey])) {
        foreach($mydb_paramkeys[$mydb_eventkey] as $varname=>$varvalue) {
            global $$varname;
            $$varname = $varvalue;
            $this->addParam($varname, $varvalue) ;
            $this->setLog("\n Restoring vars :".$varname."=".$varvalue);
        }
    }
    $mydb_paramkeys = Array () ;
    session_register("mydb_paramkeys") ;
  } else {
        $disperr->addParam("message", "This event requires mydb_paramkeys from the session and mydb_eventkey as parameter") ;
        $this->setDisplayNext($disperr) ;
  }
?>