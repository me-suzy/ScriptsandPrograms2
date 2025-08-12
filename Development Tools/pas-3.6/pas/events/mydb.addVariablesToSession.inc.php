<?php 
// Copyright 2001 - 2004 SQLFusion LLC           info@sqlfusion.com

    /**
     * mydb.addVAriablesToSession
     * This is event set variables in the session
     * <br>
     * <br>- param mixte a_add_to_session can be a string or an array and contain the 
     *                          variable(s) that you want to set in the session
     * <br>- param string goto name of the page to display next.    
     *
     * @package PASEvents  
     * @author Philippe Lewicki  <phil@sqlfusion.com>
     * @copyright  SQLFusion LLC 2001-2004
     * @version 3.0
     */

     $a_add_to_session = $this->getParam("a_add_to_session");
     $this->setLogRun(false);
     $this->setLog("\nCount:".count($a_add_to_session));
     if (is_array($a_add_to_session)) {
        foreach($a_add_to_session as $varname => $value) {
            global $$varname;
            $$varname = $value;
            $this->setLog("\n".$varname." = ".$value);
            session_register($varname);
        }
     } else {
         global $$a_add_to_session;
         $$a_add_to_session = $this->getParam($a_add_to_session);
         $this->setLog("add session to session : ".$a_add_to_session." => ".$this->getParam($a_add_to_session));
         session_register($a_add_to_session);
     }

     if (strlen($goto)>0) {
        $disp = new Display($goto);
        $this->setDisplayNext($disp);
     }
?>