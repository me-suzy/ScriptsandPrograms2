<?php 
// Copyright 2001 - 2004 SQLFusion LLC           info@sqlfusion.com
  /**
   * Event
   * This file contains the Event and RecordEvent class.
   *
   * @see Event, RecordEvent
   * @package PASClass
   */
  /**
   * Event Class
   * Used to manage event calls true forms or links. Built the url to call an event.
   * Insert the hidden field in a form to call and proceed and event.
   * Based on display it can use persistance thrue sessions
   * By default the Event object send one event but you can add sub events
   * using the addEvent method, The differents events will then share the
   * sames parameters.
   * @package PASClass
   * @author Philippe Lewicki  <phil@sqlfusion.com>
   * @copyright  SQLFusion LLC 2001-2004   
   * @version 3.0.0
   * @access public
   */
class Event extends Display {

  /**  name of the event
   * @var String $name
   */
  var $name ;

  /**  action for this event
   * complexe events sometime uses an action parameter to manage differents states of the event.
   * When action is not required its better to use sub events.
   * @var String $action
   */
  var $action ;

   /**  level of the event
   * When multiples events in a request are sent at the same time to the
    * eventcontroler there execution will be ordered by there level from 1->10000.
   * @var int $level
   */
  var $level = "";

  /**  URL of an event controler that will execute the event
   * @var String $tbl_event
   */
  var $eventcontroler = "eventcontroler.php" ;

  /**  file flag. true if there is a file field in the form.
   * @var boolean $file
   */
  var $file = false ;

  /**  list of variable that will be saved with the instance of the object in the session
   * @var array $paramtosave
   */
  var $paramstosave ;

  /**  Set the event to send is parameters in a more secure way. It take more processing time, but it will protect
   *    your site from people guessing process by looking at variables in GET and POST.
   * @var bool $secure
   */
  var $secure = true;

  /**  It is the key send as parameter to reload the parameters associates to it.
   *   It is originaly emtpy and generated on its first call
   * @var string $securekey
   */
  var $securekey = "";

   /**  This is an array with the list of variable not to hide when secure mode is on.
   * @var array $do_not_hide
   */ 
  var $do_not_hide;
  
    /**  This is a string with the name of the target window or frame.
   * @var string $target  used to set target with getformheader. Doesnt apply to getlink
   */  
  var $target = "";
  
  /**
   * Constructor, create the event object with name and action
   * parameters.
   * The goto param is preset to the location where the event is created.
   * The goto param is used in the events to define the url to call
   * after executing the event.
   * @param String $name name of the event
   * @param String $action action for this event
   * @global $PHP_SELF, $QUERY_STRING
   * @constant MYDB_EVENT_SECURE to set the event to secure or none secure mode
   * @access public
   */
  function Event($name="", $action="") {
    global $PHP_SELF, $QUERY_STRING ;
    $this->name = $name ;
    $this->action = $action ;
    $this->level = 100 ;    
    if (defined("MYDB_EVENT_SECURE")) {
        $this->setSecure(MYDB_EVENT_SECURE);
    }
    if (defined("PAS_EVENT_CONTROLER")) {
        $this->setEventControler(PAS_EVENT_CONTROLER);
    }    
    /* incompatilbe with the mydb.callDisplay   which call the displayreport page only if goto is not define
        if (strlen($QUERY_STRING) > 0) {
            $this->params["goto"] = $PHP_SELF."?".$QUERY_STRING ;
        }   else {
            $this->params["goto"] = $PHP_SELF ;
        }
    */
  }

  /**
   * getUrl return a welformed URL in a string with eventcontroler url
   * and all the parameters ready to be sent to the eventcontroler.
   * @access public
   */
  function getUrl() {
    $url = $this->eventcontroler."?mydb_events[".$this->level."]=".urlencode($this->name) ;
    if (!empty($this->action)) {
      $url .="&eventaction=".urlencode($this->action) ;
    }
    if ($this->getSecure()) {
        global $mydb_paramkeys;
        $key = $this->getSecureKey() ;
        if (is_array($this->params)) {
            foreach($this->params as $varname=>$varvalue) {
                if (eregi("mydb_events", $varname) || $this->do_not_hide[$varname]){
                    $url .= "&".$varname."=".urlencode($varvalue) ;
                } else {
                    $newparams[$varname] = $varvalue;
                }
            }
        }
        $mydb_paramkeys[$key] = $newparams ;
        session_register("mydb_paramkeys") ;
        $url .= "&mydb_events[0]=mydb.loadParamsFromSession&mydb_eventkey=".urlencode($key) ;
    } else {
        if (is_array($this->params)) {
           reset($this->params) ;
            while(list($varname, $varvalue) = each($this->params)) {
                $$varname = $varvalue;
                if (is_array($$varname)) {
                    foreach($$varname as $key => $value) {
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
        /*
        if (is_array($this->paramstosave)) {
            reset($this->paramstosave) ;
            while(list($key, $varname) = each($this->paramstosave)) {
                $url .="&paramstosave[]=".urlencode($varname) ;
            }
        }
        */
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
     if (strlen($this->getTarget()) > 0) {
        $target = " target=\"".$this->getTarget()."\"";
     } else { $target = ""; }
     $link = "<a href=\"".$this->getUrl()."\"".$properties.$target.">".$linklabel."</a>";
     return $link;
  }
  /**
   * getFornHeader return a string with the header of the form for that event.
   *
   * @access public
   */
  function getFormHeader() {
    $target = "";
    if ($this->getTarget() != "") { $target = " target=\"".$this->getTarget()."\""; }
    
    if ($this->file) {
      $out = "\n<form name=\"".str_replace(" ","", str_replace(".","_", $this->name))."\" action=\"".$this->eventcontroler."\" method=\"post\" enctype=\"multipart/form-data\"".$target.">" ;
 //         $out .= "<INPUT TYPE=\"hidden\" NAME=\"MAX_FILE_SIZE\" VALUE = \"1000000\">";
    } else {
      $out = "\n<form name=\"".str_replace(".","_", $this->name)."\" action=\"".$this->eventcontroler."\" method=\"post\"".$target.">" ;
    }
    return $out ;
  }

  /**
   * getFornHeader return a string with the events hidden fields required
   * for the eventcontroler and all the parameters of the event or events.
   *
   * @access public
   */
  function getFormEvent() {
    $out = "\n  <input type=\"hidden\" name=\"mydb_events[".$this->level."]\" value=\"".$this->name."\"/>" ;
    if (!empty($this->action)) {
        $out .= "\n  <input type=\"hidden\" name=\"eventaction\" value=\"".$this->action."\"/>" ;
    }
    if ($this->getSecure()) {
        global $mydb_paramkeys;
        $key = $this->getSecureKey() ;
        if (is_array($this->params)) {
            foreach($this->params as $varname=>$varvalue) {
                if (eregi("mydb_events", $varname) || $this->do_not_hide[$varname]){
                    if (is_array($varvalue)) {
                        foreach($varvalue as $key => $value) {
                            $out .="\n  <input type=\"hidden\" name=\"".$varname."[".$key."]\" value=\"".$value."\">" ;                       
                        }
                    } else {
                        $out .=  "\n <input type=\"hidden\" name=\"".$varname."\" value=\"".$varvalue."\">" ;
                    }
                } else {
                    $newparams[$varname] = $varvalue;
                }
            }
        }
        $mydb_paramkeys[$key] = $newparams ;
        session_register("mydb_paramkeys") ;
        $out .= "\n <input type=\"hidden\" name=\"mydb_events[0]\" value=\"mydb.loadParamsFromSession\"/>" ;
        $out .="\n  <input type=\"hidden\" name=\"mydb_eventkey\" value=\"".$key."\"/>" ;
    } else {
        if (is_array($this->params)) {
            reset($this->params) ;
            while(list($varname, $varvalue) = each($this->params)) {
                $$varname = $varvalue;
                if (is_array($$varname)) {
                    foreach($$varname as $key => $value) {
                        $out .="\n  <input type=\"hidden\" name=\"".$varname."[".$key."]\" value=\"".$value."\"/>" ;
                    }
                } else {
                   $out .="\n  <input type=\"hidden\" name=\"".$varname."\" value=\"".$varvalue."\"/>" ;
                }

            }
        }
        /*
        if (is_array($this->paramstosave)) {
            reset($this->paramstosave) ;
            while(list($key, $varname) = each($this->paramstosave)) {
                $out .="\n  <INPUT TYPE=\"HIDDEN\" NAME=\"paramstosave[]\" VALUE=\"".$varname."\">" ;
            }
        }
        */
    }
    return $out ;
  }

  /**
   * getFornFooter return a string with the footer of the form for that event.
   *
   * @access public
   */
  function getFormFooter($submitvalue="") {
    $out = "";
    if (!empty($submitvalue)) {
   //   $submitvalue = "Submit" ;
    $out .= "\n  <input type=\"submit\" name=\"submitaction\" value=\"".$submitvalue."\"/>" ;
    }
    $out .= "\n</form>" ;
    return $out ;
  }

  /**
   * Set an action variable
   * this was used to separet one eventAction in multiple events.
   * @deprecate Its better to set params to interact with event actions.
   * @param string $action name of the action to execute. 
   */
  
  function setAction($action) {
    $this->action = $action ;
  }
  
  /**
   * Set the name of the Main Event action
   * @param string Event name
   * @param level Event level of execution. 
   */
  function setName($name, $level=0) {
    $this->name = $name;
    if ($level) {
      $this->level = $level;
    }
  }
  
  function getName() {
    return $this->name ;
  }
  
  /**
   * Save the global variable of the current display in the event param.
   * The display offen requires global vars from event object.
   * If the curent event want to call the exact same display it must save
   * current global vars used.
   *
   * @param String $objectname  name of the object in the session.
   * @param String $destination string used for globalevents
   * @access public
   * @see Event
   */
  function addPageVars($pglobalevents="") {
    if ($pglobalevents == "") {
      global $globalevents ;
    }  else {
      $globalevents = $pglobalevents ;
    }
    while (list($key, $value)= each($globalevents)) {
    //echo $key ;
    global $$key ;
      if (is_object($$key) && ($$key->isFree())) {
        $tmp_params = $$key->getParams() ;
        while(list($name, $value) = each($tmp_params)) {
          if (!eregi("mydb_events", $name)) {
            $this->params[$name] = $value ;
          }
        }
      }
    }
  }
  
   /**
   * Request a persistance
   * This create the object but doesn't register it in the globalevents array
   * but in garbagevents instead.
   * It will also add the webide.registerGlobalEvent event that will get the object
   * name and assign it to globalevent, then the object will leave until it reach its target.
   * Before calling requestSave you need to addParamToSave or just addParam for all the params
   * you want that event to keep persistant.
   *
   * @param String $objectname  name of the object in the session.
   * @param String $destination string used for globalevents
   * @param integer $level level of the execution for the requestSaveEventName
   * @access public
   * @see Event
   */
  function requestSave($objectname, $destination="", $level=20) {
    if (is_array($this->params)) {
      reset($this->params) ;
      while(list($varname, $varvalue) = each($this->params)) {
        $this->paramstosave[] = $varname ;
      }
    }
    $this->addParam("paramstosave", $this->paramstosave) ;
    $this->addEvent("mydb.registerGlobalEvent", $level) ;
    $valueSaveObject[$destination] = $objectname;
    $this->addParam("requestSaveObject", $valueSaveObject) ;
    //$this->addParam("requestSaveEventName", $this->getName()); 
  }
  
  /**
   * addEvent add event action to this events.
   * The sub events should be ordered by level for the execution order
   * they will share the same parameters has the other events.
   * Sub event can't have actions .
   *
   * @param String $name name of the event
   * @param String $level for this event
   */

  function addEvent($name, $level=0) {
    if ($level) {
      $varname = "mydb_events[".$level."]" ;
    } else {
      $varname = "mydb_events[10]" ;
    }
      $this->params[$varname] = $name ;
  }  

  function addEventAction($name, $level=0) {
    $this->addEvent($name, $level);
  }

  /**
   * setGotFile to tail the event that there is a file field in the form
   * @access public
   */
  function setGotFile() {
    $this->file = true ;
  }
  
  /**
   * addParam Overwrite the default addParam from display to add the options
   * The first option is "no_secure_hidden" to show the param even if event 
   * secure mode is active.
   *
   * @param String $varname name of the param to add, it will be the name of the variable.
   * @param String $varvalue value of that param and future variable.
   * @param String $option default "" name of the option to apply to that param.
   */
  function addParam($varname, $varvalue, $option="") {
    $this->params[$varname] = $varvalue ;
    if ($option=="no_secure_hidden") {
        $this->do_not_hide[$varname] = 1;
    }
  }
  
  
  /**
   * add a param to be saved.
   * When the getURL() or getFormEvent() are run a list of param are set to be saved in the
   * session when a requestSave as been issues.
   * Thie method allow you to add variable that will be saved without having to preset them with
   * addParam(). This method is required if you are in secure mode for param set after the requestSave 
   * call or comming from user input.
   *
   * @access private
   * @param string $varname name of the variable to be saved with the event object.
   * @see requestSave()
   */
  function addParamToSave($varname) {
    $this->paramstosave[] = $varname ;
  }
  function getEventControler() {
    return $this->eventcontroler ;
  }
  
  /** Set execution level of the main Event action
   * @param integer $level level of execution.
   */
  function setLevel($level=10) {
    $this->level = $level ;
  }
  
  function getLevel() {
    return $this->level ;
  }
  
  function setEventControler($url) {
    $this->eventcontroler = $url;
  }
  
  /**
   * check is the event is in secure mod or not.
   * @return boolean
   */
  function getSecure() {
    return $this->secure;
  }
  
  /**
   * Set the event in secure or unsecure more.
   * The event in secure mode will not display its params in URLs or Forms hidden fields
   * instead it will save in the session all the params and they will be retrieved by 
   * the event controler when the event is executed.
   * @param boolean $bool false or true
   */
  function setSecure($bool) {
    $this->secure = $bool ;
  }
  
  /**
   * Generate a random secure key to register the events params in the session
   * @access private
   * @return string secure md5 rand key.
   */
  function getSecureKey() {
    $this->securekey = md5(uniqid("MYDBPARAMKEY")) ;
    return $this->securekey;
  }
  
    
  function setTarget($target) {
      $this->target = $target;
  }
  function getTarget() {
      return $this->target;
  }
}

 /**
  * Class RecordEvent  simplify the event creation for basic records management.
  * pressets the values for creating event calls when edit, add, delete data from a table
  * With this class you can in a few line generate links to manage the content of a table.
  * For exemple to add a record in a table called "employee":
  * <code>
  * <?php 
  *   $e_ManageRecord = new RecordEvent(“employes”) ;
  * ?>
  * <A href=”<?php=$e_ManageRecord->getUrlAdd();?>Add an employe</A>
  * </code>
  * Record event assume that the primary key of your table is id<tablename>, if the primary key is different you will need to set the primary key manually :
  * <code>
  * $e_ManageRecord->setPrimaryKeyVar(“keyemploye_id”);
  * </code>
  * @author Philippe Lewicki  <phil@sqlfusion.com>
  * @copyright  SQLFusion LLC 2001-2004     
  * @version 3.0
  * @package PASClass
  * @access public
  */
class RecordEvent extends Event {
  var $table ;
  var $formpage = "formrecordedit.php" ;
  var $primarykeyvar ="" ;

  
  /**
   * Constructor 
   * @param string $table name of the table that needs modification events.
   */
  function RecordEvent($table) {
    $this->table = $table ;
    $this->level = 100;
    $this->addParam("table", "");
    $this->addParam("goto", "");
    $this->addParam("formpage", "");
    $this->addParam("id".$table, "");
    $this->addParam("primarykey", "");
    parent::Event("mydb.manageRecord");
  }

  /**
   * getUrlAdd url to trigger events that will display an empty form to add a record in that table.
   * @return string partial url
   */
  function getUrlAdd() {
    global $PHP_SELF, $QUERY_STRING ;
    $this->setAction("Add") ;
    if ($this->getParam("goto") == "") {
      $this->addParam("goto", $PHP_SELF) ;
    }
    $this->addParam("table", $this->table) ;
    $this->addParam("formpage", $this->formpage) ;
    $url = $this->getUrl() ;
    return $url ;
  }

  /**
   * getUrlEdit returns an URL that will triggers events to display a form to edit the values of a record in that table.
   * @param mixed $primaryvalue value from the primary key collomn of the record to be edited.
   * @return string partial url
   */
  function getUrlEdit($primaryvalue) {
    global $PHP_SELF, $QUERY_STRING ;
    $this->setAction("Edit") ;
    if ($this->getParam("goto") == "") {
      $this->addParam("goto", $PHP_SELF) ;
    }
    $this->addParam("table", $this->table) ;
    $this->addParam("formpage", $this->formpage) ;
    if (strlen($this->getPrimarykeyVar()) > 0) {
        $primarykey = $this->getPrimarykeyVar()."='".$primaryvalue."'";
        $this->addParam($this->getPrimarykeyVar(), $primaryvalue) ;
    } else {
        $primarykey = "id".$this->table."='".$primaryvalue."'";
        $this->addParam("id".$this->table, $primaryvalue) ; 
    }
    $this->addParam("primarykey", $primarykey) ;
    $url = $this->getUrl() ;
    return $url ;
  }
  
  /**
   * getUrlDelete returns an URL that will triggers events to delete the record in that table.
   * @param mixed $primaryvalue value from the primary key collomn of the record to be deleted.
   * @return string partial url
   */
  function getUrlDelete($primaryvalue) {
    global $PHP_SELF, $QUERY_STRING;
    $this->setAction("Delete") ;
    if ($this->getParam("goto") == "") {
        $this->addParam("goto", $PHP_SELF) ;
    }
    $this->addParam("table", $this->table) ;
    $this->addParam("formpage", $this->formpage) ;
    if (strlen($this->getPrimarykeyVar()) > 0) {
        $primarykey = $this->getPrimarykeyVar()."='".$primaryvalue."'";
        $this->addParam($this->getPrimarykeyVar(), $primaryvalue) ;
    } else {
        $primarykey = "id".$this->table."='".$primaryvalue."'";
        $this->addParam("id".$this->table, $primaryvalue) ; 
    }
    $this->addParam("primarykey", $primarykey) ;
    $url = $this->getUrl() ;
    return $url ;
  }
  
  /**
   * To generate the default forms to add and edit the records, the events uses the formrecordedit.php page.
   * The method allow you to call an other page to display the auto generated forms.
   * @param string $formpage name of the with code to auto generate the forms.
   */
  
  function setFormPage($formpage) {
    $this->formpage = $formpage ;
  }
  
  /**
   * Set the primary key of that table
   * By default the RecordEvent is going to assume that the primary of your table is id<yourtablename> 
   * If you want to use an other primary key to manager your records set it here.
   * @param string $varname name for the primary key 
   */
  function setPrimaryKeyVar($varname) {
    $this->primarykeyvar = $varname ;
    $this->addParam($varname, "");
  }
  function getPrimaryKeyVar() {
    return $this->primarykeyvar;
  }

}

?>