<?php
//=============================================================================
//
//     CMS Master :: Content management system
//     File: /cms-libs/servervars.php
//     Version: 1.0
//     Created date: 28 September 2003
//     Modification date: 28 September 2003
//
//============================================================================
                                                                                     
class ServerVars {

    var $REMOTE_ADDR;
    var $COOKIE;
    var $POST;
    var $GET;
    var $FILES;
    var $REQUEST_URI;
    var $DOCUMENT_ROOT;
    
    function ServerVars() {
      $this->REMOTE_ADDR = $_SERVER["REMOTE_ADDR"];
      $this->COOKIE = $_COOKIE;
      $this->POST = $_POST;
      $this->GET = $_GET;
      $this->FILES = $_FILES;
      $this->REQUEST_URI = $_SERVER["REQUEST_URI"];
      $this->DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
    }
    
}

?>