<?php
/**
* MyObjects
*
* Copyright (c) 2004 R. Erdinc Yilmazel <erdinc@yilmazel.com>
*
* http://www.myobjects.org
*
* @version $Id: index.php,v 1.6 2004/11/15 22:29:36 erdincyilmazel Exp $
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsWebClient
*/
ini_set('magic_quotes_gpc', 'off');
define('MYOBJECTS_VERSION', 'RC2');

$start = microtime(true);

define('MYOBJECTS_ROOT', dirname($_SERVER['SCRIPT_FILENAME']));

require_once(MYOBJECTS_ROOT . '/webclient/Menu.php');
require_once(MYOBJECTS_ROOT . '/webclient/WebClient.php');

/**
* A Simple Template Engine
*
* This class defines several methods that template engines generally provide
* It is not truely a template engine though it ony includes the template file.
*
* @version 1.0
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsWebClient
*/
class Template {
    
    /**
    * @var $templateDir The path of the directory that holds the template files
    */
    protected $templateDir;
    
    /**
    * @var array $variables Array of assigned variables
    */
    protected $variables;
    
    /**
    * Creates a new Template instance
    */
    function __construct() {
        $this->templateDir = MYOBJECTS_ROOT . '/webclient/templates';
        $this->variables = array();
    }
    
    /**
    * Registers the given value with the Template
    *
    * @param string $variable The name of the variable that will be registered
    * @param string $value The value for the variable
    * @return void
    */
    public function assign($variable, $value) {
        $this->variables[$variable] = $value;
    }
    
    /**
    * Displays the template
    *
    * @param string $template Name of the template file
    * @return void
    */
    public function display($template) {
        include($this->templateDir . '/' . $template);
    }
    
    /**
    * Returns the requested object property
    *
    * @param string $property The requested property
    * @return mixed The value of the requested property
    */
    function __get($property) {
        return isset($this->variables[$property]) ? $this->variables[$property] : '';
    }
    
    /**
    * Sets the value for the specified object property
    *
    * @param string $property Name of object property
    * @param string $value Value of object property
    * @return void
    */
    function __set($property, $value) {
        $this->variables[$property] = $value;
    }
}

// Create a new WebClient instance
$client = new WebClient();

// Display the elapsed time as an Html comment
$elapsedTime = microtime(true) - $start;
echo "<!-- Elapsed Time: " . $elapsedTime . " -->";
?>