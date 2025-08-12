<?php

/**
* Model Class to derive your models from
*
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      EasyFramework
*/

/**
* Easy Framework: Model Superclass
*
* Inherit from this class to write model classes using easy framework.
* Have a look at the examples (testApps) to find out how this model class
* can be used.
*
* @version      $Id: easy_model.class.php,v 1.4 2005/08/06 06:57:08 carsten Exp $
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      EasyFramework
*/
class easy_model {

	/**
	* The command variable contains the action which is about to be executed.
	*
    * The command is the basic directive to what the controller is supposed to execute. When
    * a formular is submitted, easy framework looks for a parameter called <i>command</i> which is passed
    * to the controller.
    *
    * @var string $command
    */
    var $command       = null;

	/**
    * The model ID. deprecated.
    *
    * @todo get rid of _model_id
    * @var string $_model_id
    */
	//var $_model_id     = null;

	/**
    * Next token identifier. deprecated
    *
    * @todo get rid _next_token
    * @var string $_next_token
    */
	//var $_next_token   = null;

	/**
    * Documentation missing.
    * @todo check for _fields variable in code
    *
    * @var array $_fields
    */
    //var $_fields       = array ();

    /**
    * Documentation missing.
    * @todo think over template engines again
    *
    * @var array $_fields
    */
    //var $smarty        = null;

    /**
    * Constructor
    *
    * Creates Model ID and next Token
    *
    * @todo get rid of smarty here
    * @access public
    */
    function easy_model () {

        //$this->create_model_id();
        //$this->create_next_token();
        //$this->smarty = &$smarty_instance;
    }

    //function get_model_id ()   { return $this->_model_id; }
    //function get_next_token () { return $this->_next_token; }

    // === Helper Functions ======================================

	/**
    * Creates Model ID
    *
    * @todo validate usefullness
    * @access private
    */
    /*function create_model_id () {
    	//echo "Creating new model id";
        if ($this->_model_id <> NULL) die ("Doublicate Model ID");
        list($usec, $sec) = explode(' ', microtime());
        mt_srand((float) $sec + ((float) $usec * 100000));
        $rand_string = md5(uniqid(mt_rand(), true));
        // !!! Model IDs und serializing erst mal deaktivieren
        // weil die Session oft unnötigerweise zu sehr anwächst
        //$this->_model_id = $rand_string;
        // !!! Wieso werden so viele IDs erzeugt? Im Normalfall gibt
        // es nur eine ID pro Seite...
        //$this->_model_id = "999";
    }*/

    /**
    * 
    *
    * @todo validate usefullness
    * @access private
    */
    /*function create_next_token () {
    	//if ($this->_model_id == NULL) die ("Model not identified");
        $next_token = (base_convert ($this->_model_id, 26, 10) % 1234567) + 1;
        $this->_next_token = $next_token;
    }*/

    /**
    * Creates next token
    *
    * @todo validate usefullness
    * @access private
    */
    function addParams2Model (&$container, $params,
        $omit = array ( "submit",      "command")) {

        $omitted = array ();
        foreach ($params AS $param => $value) {
            if (!in_array($param, $omit)) {
                if (isset($container[$param])) {
                    //echo $param." => ".$value."<br>";
                    $container[$param]->set ($value);
                }
                else
                    $omitted[] = $param;
            }
        }
        return array ($container, $omitted);
    }

    /**
    *
    *
    * @todo validate usefullness
    * @access private
    */
    /*function addParams2Smarty ($params, $omit = array ("submit", "command")) {
        foreach ($params AS $param => $value) {
            if (!in_array($param, $omit)) {
                //echo $param."=>".$params[$param]."<br>";
                //$this->smarty->assign ('owner', $params['owner']);
                $this->smarty->assign ($param, $params[$param]);
            }
        }
    }*/
}

?>