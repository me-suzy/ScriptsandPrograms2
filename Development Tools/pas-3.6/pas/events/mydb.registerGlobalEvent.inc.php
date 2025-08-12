<?php 
// Copyright 2001 - 2004 SQLFusion LLC           info@sqlfusion.com

  /**
   *  Event Registration in globalEvent
   *
   *  It just transfert the Event object from garbage to global.
   *  This event is always executed with other events so he doesn't have
   *  setUrlNext().
   *
   * <br>- param String $requestSaveObject[] name of the objects to set in globalevents.
   * <br>- global $globalevents, $garbagevents
   *
   * @package PASEvents
   * @author Philippe Lewicki  <phil@sqlfusion.com>
   * @copyright  SQLFusion LLC 2001-2004
   * @version 3.0   
   */

  global $globalevents, $garbagevents ;
  
  $this->setLogRun(false);
  $this->setLog("\n Start event mydb.registryGlobalEvent ".date("Y-M-d H:i:s"));
  
  $requestSaveObject = $this->getParam("requestSaveObject"); 
  if (is_array($requestSaveObject)) {
      reset($requestSaveObject) ;
      while (list($key, $lobjectname) = each($requestSaveObject)) {
        $this->setLog("\n Object to save: ".$lobjectname);
        //$$lobjectname = new Event($this->getParam("requestSaveEventName")) ;
        $$lobjectname = new Display() ;
        $globalevents[$lobjectname] = $key;
        $garbagevents[$lobjectname] = 0 ;
        if (is_array($paramstosave)) { 
        reset($paramstosave) ;
            while (list($key, $varname) = each($paramstosave)) {
                $this->setLog("\n Param: ".$varname." = ".$$varname);
                if (is_array($$varname)) { 
                  $$lobjectname->editParam($varname, $$varname) ;
                  $this->setLog(" is an array"); 
                } else {
                   $$lobjectname->editParam($varname, stripslashes($$varname)) ;
                }                
            }
        } else { 
            $this->setError(" No Param to be saved for object ".$lobjectname." add a ->addParam before calling ->requestSave"); 
        }
        $this->setLog("\n Do Save for object ".$lobjectname." die on page ".$globalevents[$lobjectname]);
        $$lobjectname->save($lobjectname, $globalevents[$lobjectname]) ;
      }
      session_register("globalevents") ;
      session_register("garbagevents") ;
  } else {
      $this->setError("requestSavedObject is not an Array") ;
  }
?>