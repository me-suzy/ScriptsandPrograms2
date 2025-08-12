<?php 
// Copyright 2001 - 2004 SQLFusion LLC           info@sqlfusion.com

  /**
   * Event Controler Object
   * @see EventControler
   * @package PASClass
   */

 /**
  * Check incoming events and distribute them
  *
  * This object listen to incoming events and based on the mydb_events. all the events names are stored in an array,
  * the array is passed to the lisenevent method that will include the file based on the event name.
  * 
  * @package PASClass
  * @author Philippe Lewicki  <phil@sqlfusion.com>
  * @copyright  SQLFusion LLC 2001-2004  
  * @version 3.0.0
  * @access public
  */
class EventControler extends BaseObject {

  /**  Name of the table to store the events
   * @var String $tbl_event
   */
  var $tbl_event = "mydb_event" ;

  /**  object for instances, database connexion
   * @var object sqlConnect $dbc
   */
  var $dbc ;

  /**  directory where all the eveent include files are
   */
  var $eventdir = "events/" ;

  /**  path to mydb application for default envents.
   */
  var $mydbpath = "../mydb/" ;

  /**  array with all the parameters, params[varname, varvalue]
   * @var array $params
   */
  var $params ;

  /**  array with all the session variables, params[varname, varvalue]
   * @var array $params
   */
  var $sessionparams ;

  /** default url to go after executing the events
   */
  var $urlNext ;

  /** default url to go after executing the events
   */
  var $dispNext ;

  /** name of the page that display messages
  */
  var $messagepage="message.php" ;

  /** display the global vars in the url
  */
  var $urlglobalvars = true ;

  /** Execute events request coming from the same domain has eventcontroler
  */
  var $checkreferer = true ;

  /** List all the
  */
  var $mydb_events;
  
  /**
   * Constructor, create a new instance of an event controler
   * @param object sqlConnect $dbc
   * @access public
   */
  function EventControler($dbc=0) {
      $this->dbc = $dbc ;
      $this->setLogRun(false);
      $this->setLog("\n\n Instanciate Event Controler Object ".date("Y/m/d H:i:s"));
  }

  /**
   * Execute an event with the associate parameters
   *
   * To execute an event it will look for a file based on the name of the event by adding ",inc.php" at the end of the name.
   * The method will look inside the default directory events from mydb directory and local application directory.
   * If it founds no methodes it will set the nexturl to the message page with an error message.
   *
   * @param string $eventname
   * @param array $params
   * @access private
   */
  function Execute($eventname, $params=0) {
    if (is_array($params)) {
      $this->params = $params;
    }
    reset($this->params) ;
    while (list($key, $val) = each($this->params)) {
      $$key = $val ;
    }
    if (!empty($eventname)) {
        if (file_exists($this->mydbpath.$this->eventdir.$eventname.".inc.php")) {
          include($this->mydbpath.$this->eventdir.$eventname.".inc.php") ;
        } elseif (file_exists($this->eventdir.$eventname.".inc.php")) {
          include($this->eventdir.$eventname.".inc.php") ;
        } else {
          $event_errormessage = "Event ".$eventname." not found in :".$this->mydbpath.$this->eventdir.", ".$this->eventdir ;
        }
    } else {
      $event_errormessage = "An event must be specified" ;
    }
    if (!empty($event_errormessage)) {
      $this->setError($event_errormessage);
      $this->setUrlNext($this->getMessagePage()."?message=".urlencode($event_errormessage)) ;
    }
  }

  /**
   * check the incomming events and send them to the Execute method
   *
   * Get the mydb_event array, order them on there execution order, delete duplicate and send them to the Execute method
   *
   * @param array $mydb_events
   * @access public
   */
   function lisenEvents($mydb_events="")  {
     $this->listenEvents($mydb_events) ;
   }
   function listenEvents($mydb_events="") {
        global $mydb_key, $cfg_notrefererequestkey ;
        $mydb_paramkeys=$_SESSION['mydb_paramkeys']; 
        $mydb_eventkey=$_GET['mydb_eventkey']; 
        if (is_array($mydb_paramkeys) && !empty($mydb_eventkey)) {
            if (is_array($mydb_paramkeys[$mydb_eventkey])) {
                 $mydb_key = $mydb_paramkeys[$mydb_eventkey]['mydb_key'];
            }
        }
        if ($mydb_events=="") {  global $mydb_events; }
        $this->mydb_events = $mydb_events ;
        if ($this->getCheckReferer()) {
            if (($this->getReferer() == $this->getURI()) || ($mydb_key == $cfg_notrefererequestkey))  {
                if (is_array($this->mydb_events)) {
                    $mydb_events = array_unique($this->mydb_events) ;
                    ksort($this->mydb_events) ;
                    if (is_array($this->mydb_events)) {
                        foreach($this->mydb_events as $eventname) {
                            $this->setLog("\n\n Executing Event : ".$eventname) ;
                            $this->Execute($eventname);
                        }
                    }
                }
            } else {
                $this->setError("EventControler Error : URI didn't match REFERER or Wrong mydb_key has been sent") ;
            }
        } else {
            if (is_array($this->mydb_events)) {
                $mydb_events = array_unique($this->mydb_events) ;
                ksort($this->mydb_events) ;
                if (is_array($this->mydb_events)) {
                    foreach($this->mydb_events as $eventname) {
                        $this->setLog("\n\n Executing Event : ".$eventname) ;
                        $this->Execute($eventname);
                    }
                }
            }
        }
    }

    function getReferer($url="") {
        //global $HTTP_SERVER_VARS ;
        if (empty($url)) {
            $url = $_SERVER["HTTP_REFERER"];
        }
        list ($url, $querystring) = explode("?", $url) ;
        $urlreferer = explode("/", $url) ;
        $num = count($urlreferer) ;
        for ($i=0; $i<$num-1; $i++) {
            $referer .= $urlreferer[$i]."/" ;
        }
        return $referer ;
    }

    function getURI($url="") {
     //   global $HTTP_SERVER_VARS ;
        if (empty($url)) {
            $url =  "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"] ;
        }
        list ($url, $querystring) = explode("?", $url) ;
        $urluri = explode("/", $url) ;
        $num = count($urluri) ;
        for ($i=0; $i<$num-1; $i++) {
            $uri .= $urluri[$i]."/" ;
        }
        return $uri ;
    }

  /**
   * Process to the next page based on the $urlNext
   * @access public
   */
  function doForward() {
    global $globalevents  ;
    if ($this->urlglobalvars && (is_array($globalevents))) {
      $url = "" ;
      $baseurl = $this->getUrlNext() ;
      list($requestpage, $query)  = explode("?", basename($this->getUrlNext())) ;
      reset($globalevents) ;
      while (list($key, $value)= each($globalevents)) {
        global $$key ;
        if ($value) {
            if((eregi($value, $requestpage))  && (is_object($$key))) {
                $params = $$key->getParams() ;
                if (is_array($params)) {
                    while(list($name, $value) = each($params)) {
                        $$name = $value ;
                        if (is_array($$name)) {
                            foreach ($$name as $nkey => $nvalue) {
                                $url .= "&".$name."[".$nkey."]=".urlencode($nvalue) ;
                            }
                        } else {
                            $url .="&".$name."=".urlencode($value) ;
                        }
                    }
                }
            }
        }
      }
      if (ereg("\?", $baseurl)) {
        $this->urlNext = $baseurl.$url ;
      } else {
        $this->urlNext = $baseurl."?uniqid=".uniqid(rand()).$url ;
      }
    } else {
      if (ereg("uniqid", $this->getUrlNext())) {
        $this->urlNext = ereg_replace("uniqid=.*", "uniqid=".uniqid(rand()), $this->getUrlNext()) ;
      } elseif (ereg("\?", $this->getUrlNext())) {
        $this->urlNext .= "&uniqid=".uniqid(rand()) ;
      }  else {
        $this->urlNext .= "?uniqid=".uniqid(rand()) ;
      }
    }
    $this->setLog("\n Forward to URL:".$this->getUrlNext());
    header("Location: ".$this->getUrlNext()) ;
    exit ;
  }

  /**
   * Add a parameter to the event execution
   *
   * The var name must be the variable name inside a string. This methode is used on the initialisation of the event controler
   * or inside an event to save a variable that will be used by other events.
   * The varvalue is optional
   *
   * @param string $varname
   * @param string $varvalue
   * @access public
   * @see updateParam(), addallvars()
   */
  function addParam($varname, $varvalue="" ) {
    if(!empty($varvalue)) {
      $this->params[$varname] = $varvalue ;
    } else {
      global $$varname ;
      $this->params[$varname] = $$varname ;
    }
  }

  /**
   * Add a session var to the event execution
   *
   * The var name must be the variable name inside a string. This methode is used on the initialisation of the event controler
   * or inside an event to save a variable that will be used by other events.
   * The varvalue is optional
   *
   * @param string $varname
   * @param string $varvalue
   * @access public
   * @see addParam(), addallvars()
   */

 function addSession($varname, $varvalue="" ) {
  // global $HTTP_SESSION_VARS;
    if(!empty($varvalue)) {
      $this->sessionparams[$varname] = $varvalue ;
    } else {
      $varvalue = $_SESSION[$varname] ;
      $this->sessionparams[$varname] = $varvalue ;
    }
  }
  /**
   *  Update a parameter
   *
   * This methode is mainly used inside events to modify the value of a parameter
   * that is sent to other events.
   * All parameters are required for now because the global on $varname doesn't work well.
   *
   * @param string $varname
   * @param string $varnewvalue
   * @access public
   * @see addParam(), addallvars()
   */
  function updateParam($varname, $varnewvalue="") {
    if(!empty($varnewvalue)) {
      $this->params[$varname] = $varnewvalue ;
    } else {
      global $$varname ;
      $this->params[$varname] = $$varname ;
    }
  }
  function editParam($varname, $varnewvalue="") {
    $this->updateParam($varname, $varnewvalue="") ;
  }

  function updateSession($varname, $varnewvalue="") {
    $this->addSession($varname, $varnewvalue);
  }

  function editSession($varname, $varnewvalue="") {
    $this->addSession($varname, $varnewvalue);
  }

  function getParam($varname) {
    return $this->params["$varname"] ;
  }

  function getSession($varname) {
    return $this->sessionparams["$varname"] ;
  }
  function getIsParam($varname) {
    if(!empty($varname) && is_array($this->params)) {
        if(array_key_exists($varname, $this->params)) {
            return true;
        } else {
            return false;
        }
    }
  }
  function getIsSession($varname) {
      if(!empty($varname) && is_array($this->sessionparams)) {
   //   echo "<br>".$varname."->".$this->sessionparams[$varname];
        if(array_key_exists($varname, $this->sessionparams)) {
            return true;
        } else {
            return false;
        }
      }
  }
  /**
   *  Insert all the PHP environement variables in the $params
   * @access private
   * @see addParam(), updateParam()
   */
  function addallvars() {
    //global $HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_ENV_VARS, $HTTP_COOKIE_VARS, $HTTP_SESSION_VARS ;
    while (list($name, $value) = each($_POST)) {
       if (!$this->getIsParam($name)) {
         $this->addParam($name, $value) ;
       }
    }
    while (list($name, $value) = each($_GET)) {
      if (!$this->getIsParam($name)) {
        $this->addParam($name, $value) ;
      }
    }
    if (is_array($_SESSION)) {
      while (list($name, $value) = each($_SESSION)) {
        if (!$this->getIsSession($name)) {
          $this->addSession($name, $value);
        } 
      }
    }
    if (is_array($_COOKIE)) {
      while (list($name, $value) = each($_COOKIE)) {
        if (!$this->getIsParam($name)) {
          $this->addParam($name, $value);
        }
      }
    }
  }

  /** Set the path to MyDB
   * @param string $path
   */
  function setMydbPath($path) {
    $this->mydbpath = $path ;
  }

  /**  Return the path of MyDB
   * @return string
   */
  function getMydbPath() {
    return $this->mydbpath ;
  }

  /** Set the Local Event Directory
   * @param string $path
   */
  function setEventDir($path) {
    $this->eventdir = $path ;
  }
  
  /** Return the local envent directory
   * @return string $eventdir
   */
  function getEventDir() {
    return $this->eventdir ;
  }
  
  /** Set the next url to go after events execution
   * @param string $url
   */
  function setUrlNext($url) {
    $this->urlNext = $url ;
  }
  
  /** Return the next url
   * @return string $urlNext
   */
  function getUrlNext() {
    if (is_object($this->dispNext)) {
      if (strtolower(get_class($this->dispNext)) == "display" || strtolower(get_class($this->dispNext)) == "event") {
         $this->urlNext = $this->dispNext->getUrl() ;
      }
    }
    return $this->urlNext ;
  }

  /** Set the next url to go after events execution
   * @param string $url
   */
  function setDisplayNext($disp) {
    if (strtolower(get_class($disp)) == "display" || strtolower(get_class($disp)) == "event") {
       $this->dispNext = $disp ;
    } else {
      $this->setError("<b>Event Controler Error</b> The display object assign to setDisplayNext is not a Display or Event object") ;
    }
  }
  
  /** Return the next url
   * @return string $urlNext
   */
  function getDisplayNext() {
    return $this->dispNext ;
  }
  
  /** Set the message Page, default page that display messages
   * @param string $page
   * @return boolean true
   */
  function setMessagePage($page) {
    $this->messagepage = $page ;
    return true ;
  }
  
  /** Set the display of global vars of the request page in the URL
   *  @param boolean $bool
   */
   function setUrlGolbalvars($bool) {
     $this->urlglobalvars = $bool ;
   }

  /** Set the check refer that will refuse all event request not comming from local host
   *  unless the key =  cfg_notrefererequestkey
   *  @param boolean $bool
   */
    function setCheckReferer($bool) {
        $this->checkreferer = $bool ;
    }
    
    function getCheckReferer() {
        return $this->checkreferer  ;
    }
    
  /** Return the page to display message, used in events to built urlNext
   * @return string $messagepage
   */
  function getMessagePage() {
    return $this->messagepage ;
  }

  /** Return Database connexion object
   * @return object sqlConnect $dbc
   */
  function getDbCon() {
    return $this->dbc ;
  }
}
?>
