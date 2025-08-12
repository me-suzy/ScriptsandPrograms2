<?php
// Copyright 2001 - 2004 SQLFusion LLC           info@sqlfusion.com
  /**
   * Display Object
   * @see Display
   * @package PASClass
   */
  /**
   * Display Class
   *
   * It manage parameters from an internal array
   * and built a full url with the getUrl method.
   *
   * @package PASClass   
   * @author Philippe Lewicki  <phil@sqlfusion.com>
   * @copyright  SQLFusion LLC 2001-2004   
   * @version 3.0.0
   * @access public
   */

class Display extends BaseObject {
    
  /**  Array that stores all the parameters, the index is the parameter name and
   *    the value is the value of the parameter.
   * @var Array $params
   * @access private
   */
  var $params ;

  /**  String with the full URL of the page that will recieve the parameters
   * @var String $page
   * @access private
   */
  var $page ;

  /**   Flag to tel if the object should be freeed
   * @var bool $free
   * @access private
   */
  var $free = false;

  /**
   * Constructor, create a new instance of an Display
   * @param String $page its the full URL of the page where the parameters need to be sent.
   * @access public
   */
  function Display($page="") {
    $this->page = $page ;
  }

  /**
   * getUrl return a welformed URL in a string with parameter page
   * and all the parameters ready to be send has get.
   * @access public
   * @return string with partial URL
   * @see getLink()
   */
  function getUrl() {
    if (ereg("\?", $this->page)) {
      list($this->page, $query) = explode("?", $this->page) ;
    }
    $url = $this->page."?x=1" ;
    if (is_array($this->params)) {
      reset($this->params) ;
      while(list($varname, $varvalue) = each($this->params)) {
        $$varname = $varvalue;
        if (is_array($$varname)) {
            foreach ($$varname as $key => $value) {
                if (is_array($value)) {
                    foreach($value as $key2 => $value2) {
                        if(!is_array($value2)) {
                        $url .= "&".$varname."[".$key."][".$key2."]=".urlencode($value2);
                        } else {
                            $this->setError("The param is an array with more than 2 dimentions. \$arrayname[][][] or more. Make sure its only 2 dimenssions max.");
                        }
                    }
                } else {
                    $url .= "&".$varname."[".$key."]=".urlencode($value) ;
                }
            }
        } else {
            $url .="&".$varname."=".urlencode($varvalue) ;
        }
      }
    }

    return $url ;
  }
  /**
   * getLink return an html link for that display.
   *
   * @param string $linklabel label to display for that link
   * @param string class name of the style for that link
   * @access public
   * @return string with an HTML link
   * @see getUrl()
   */
  function getLink($linklabel, $properties="") {
     $link = "<a href=\"".$this->getUrl()."\"".$properties.">".$linklabel."</a>";
     return $link;
  }
   
  /** 
   * setPage set the page name of the display
   * 
   * @param string $pagename name of the page.
   */
  function setPage($pagename) {
    $this->page = $pagename ;
  }
  
  function getPage() {
    return $this->page ;
  }
  
  /**
   * addParam to the display
   * The varname and its value will be added to the params property array.
   * They will be added to the url on the getUrl()
   * @param string $varname name of the variable
   * @param string $varvalue value or content of the variable
   * @see getUrl(), getParam()
   */
  function addParam($varname, $varvalue) {
    $this->params[$varname] = $varvalue ;
  }
  
  /**
   * get on Param from the display
   * Return the value of the requested param.
   * @param string $varname variable name.
   * @return string param value.
   * @see addParam()
   */
  function getParam($varname) {
    return $this->params[$varname] ;
  }
  
  /**
   * Get all params for the display
   * Return an array with all the params previously set in that display
   * @return array with all the params with the variable name as key.
   * @see addParam(), getParam()
   */
  function getParams() {
    return $this->params ;
  }
  
  /**
   * edit param to modify a param of the display
   * Work exactly like addParam, currently redondant.
   * Was set in the original design interface.
   * @param string $varname name for the variable
   * @param string $varvalue value of the variable.
   * @see addParam(), getParam(), getParams()
   */
  function editParam($varname, $varvalue) {
    $this->params[$varname] = $varvalue ;
  }

  /**
   * save give persistance feature to the object by
   * saving it in the session. It also use a destination string
   * to tel when this object must die.
   * This feature is use to create a Display object and keep it alive
   * to use it somewhere else later.
   * The destination tels when the object will release his parameters
   * as global vars and when he will die.
   *
   * @param String $objectname  name of the object in the session.
   * @param String $destination string used for globalevents
   * @access public
   * @see Event
   */
  function save($objectname, $destination="") {
    global $$objectname, $globalevents ;
    if ($destination == "") {
      $destination = basename($this->getPage());
      list($destination, $querystring) = explode("?", $destination) ;
    }
    $$objectname = $this ;
    //echo $$objectname->getParam("goto") ;
    global $$objectname, $globalevents ;
    session_register($objectname) ;
    $globalevents[$objectname] = $destination ;
  }

  /**
   * Free the object from the current session and take it out
   * of the globalevents
   *
   * @param String $objectname  name of the object in the session.
   * @access public
   * @see Event
   */
  function free($objectname) {
    global $$objectname, $globalevents, $garbagevents;
    session_unregister($objectname) ;
    $globalevents[$objectname] = 0 ;
    $garbagevents[$objectname] = 0 ;
  }
  
  /**
   *  Set to free the object on the next page.
   *  This is require so the variables of the event are still
   *  load when reload the page.
   *
   * @access public
   * @see Event, isFree()
   */
  function setFree() {
    $this->free = true ;
  }

  /**
   * Check if the object is ready to be freed.
   *
   * @return bool $free with the status of the object.
   * @access public
   * @see Event, setFree()
   */
  function isFree() {
    return $this->free ;
  }
}



?>