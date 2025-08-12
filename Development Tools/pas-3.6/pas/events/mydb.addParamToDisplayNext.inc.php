<?php 
// Copyright 2001 - 2004 SQLFusion LLC           info@sqlfusion.com
   /**
    * mydb.addParamToDisplayNext.inc.php
    *
    * This event take the current display next and add
    * All the param to it.
    * A display object need to have already been created by a previous object
    * @package PASEvents   
    * @author Philippe Lewicki  <phil@sqlfusion.com>
    * @copyright  SQLFusion LLC 2001-2004
    * @version 3.0
    */

    $this->setLogRun(false);
    $this->setLog("\n\nEvent mydb.addParamToDisplayNext start :".date("Y-m-d H:i:s"));
    $d_cur = $this->getDisplayNext();
    if (is_object($d_cur)) {
      foreach($this->params as $key=>$value) {
        if ($d_cur->getParam($key) == "") {
          if (!empty($value) && !empty($key)) { 
            if ($key != "mydb_events"
             && $key != "globalevents"
             && $key != "garbagevents"
             && !is_object($value)) {
              $d_cur->addParam($key, $value);
              $this->setLog("\nParamtodisplay : $key = $value");
            }
          }
        }
      }
      $this->setDisplayNext($d_cur);
    } else {
      $this->setLog("\nEvent ParamToDisplay : no display object found");
    }
    $this->setLog("\nEvent mydb.addParamToDisplayNext stop");
    $this->setLogRun(false);
?>