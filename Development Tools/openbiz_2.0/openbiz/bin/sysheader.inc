<?php
/****************************************************************************
   openbiz core path
 ****************************************************************************/
//define('OPENBIZ_HOME', 'absolute_dir/Openbiz');
if(!defined('OPENBIZ_HOME')) define('OPENBIZ_HOME',dirname(dirname(__FILE__)));
if(!defined('OPENBIZ_BIN'))  define('OPENBIZ_BIN',OPENBIZ_HOME."/bin/");
if(!defined('OPENBIZ_META')) define('OPENBIZ_META',OPENBIZ_HOME."/metadata/");

/****************************************************************************
   third party library path
 ****************************************************************************/
// Smarty package
//define('SMARTY_DIR', 'absolute_dir/Smarty');
if(!defined('SMARTY_DIR')) define('SMARTY_DIR',OPENBIZ_HOME."/others/Smarty/libs/");

// ADODB package
//define('ADODB_DIR', 'absolute_dir/adodb');
if(!defined('ADODB_DIR')) define('ADODB_DIR',OPENBIZ_HOME."/others/adodb");

// dompdf package
include_once(OPENBIZ_HOME."/others/dompdf/dompdf_config.inc.php");

/****************************************************************************
   application services
 ****************************************************************************/
if(!defined('AUTH_SERVICE'))     define('AUTH_SERVICE', "authService");
if(!defined('ACCESS_SERVICE'))   define('ACCESS_SERVICE', "accessService");
if(!defined('PROFILE_SERVICE'))  define('PROFILE_SERVICE', "profileService");
if(!defined('LOG_SERVICE'))      define('LOG_SERVICE', "logService");
if(!defined('EXCEL_SERVICE'))    define('EXCEL_SERVICE', "excelService");
if(!defined('PDF_SERVICE'))      define('PDF_SERVICE', "pdfService");

/* whether print debug infomation or not */
if(!defined('DEBUG')) define("DEBUG", 0);

/* check whether user logged in */
if(!defined('CHECKUSER')) define("CHECKUSER", "N");
/* session timeout seconds */
if(!defined('TIMEOUT')) define("TIMEOUT", -1);  // -1 means never timeout.

include_once("sysresource.inc");

/**
 * MetaObject class - MetaObject is the base class of all derived metadata-driven classes
 * 
 * @package BizSystem
 */
abstract class MetaObject
{
   public $m_Name, $m_Class, $m_Package, $m_Description;
   function __construct(&$xmlArr) {}
   //function __destruct() {}
   protected function ReadMetaData() {}
   protected function ReadMetaCollection(&$xmlArr, &$metaList) {
      if (!$xmlArr) { $metaList = null; return; }
      if ($xmlArr["ATTRIBUTES"])  $metaList[] = $xmlArr;
      else $metaList = $xmlArr;
   }
   protected function PrefixPackage($name) {
      if ($name && !strpos($name, ".") && ($this->m_Package)) // no package prefix as package.object, add it
         $name = $this->m_Package.".".$name;
      
      return $name;
   }
}
/**
 * MetaIterator class - MetaIterator is the base class of all derived metadata-driven classes who support iteration
 * 
 * @package BizSystem
 */
class MetaIterator implements Iterator
{
   protected $m_prtObj = null;
   protected $m_var = array();
   public function __construct(&$xmlArr, $childClassName, $prtObj=null) { 
      //if (is_array($array)) $this->var = $array; 
      $this->m_prtObj = $prtObj;
      if (!$xmlArr) return;
      if ($xmlArr["ATTRIBUTES"]) {
         $className = $xmlArr["ATTRIBUTES"]['CLASS'] ? $xmlArr["ATTRIBUTES"]['CLASS'] : $childClassName;
         $obj = new $className($xmlArr, $prtObj);
         $this->m_var[$obj->m_Name] = $obj;
      }
      else {
         foreach ($xmlArr as $child) {
            $className = $child["ATTRIBUTES"]['CLASS'] ? $child["ATTRIBUTES"]['CLASS'] : $childClassName;
            //$this->m_var[$child["ATTRIBUTES"]['NAME']] = new $className($child, $prtObj);
            $obj = new $className($child, $prtObj);
            $this->m_var[$obj->m_Name] = $obj;
         }
      }
   }
   public function merge($anotherMIObj) { 
      foreach($anotherMIObj as $key=>$value) {
         if (!key_exists($key,$this->m_var)) $this->m_var[$key] = $value;
      }
   }
   public function get($key) { return $this->m_var[$key]; }
   public function set($key, $val) { $this->m_var[$key] = $val; }
   public function rewind() { reset($this->m_var);  }
   public function current() { return current($this->m_var); }
   public function key() { return key($this->m_var); }
   public function next() { return next($this->m_var); }
   public function valid() { return $this->current() !== false; }
}

/**
 * iSessionObject interface - stateful metadata-driven classed need to implement SetSessionVars and GetSessionVars
 * 
 * @package BizSystem
 */
interface iSessionObject
{
   public function SetSessionVars($sessCtxt);
   public function GetSessionVars($sessCtxt);
}

/**
 * iUIControl interface - all UI classes need to implement Render method
 * 
 * @package BizSystem
 */
interface iUIControl
{
   public function Render();
}

//class BDOException extends Exception {}
//class BFMException extends Exception {}

/*
   global variables
*/
include_once("BizSystem.php");
$g_BizSystem = new BizSystem();

/**
 * __auload - search for the php file required to load the class
 * 
 * @package BizSystem
 */

function __autoload($class_name) {
   $filepath = BizSystem::GetLibFileWithPath($class_name);
//   echo $class_name.",".$filepath;
   if ($filepath) {
      include_once($filepath);
      return;
   }
   DOMPDF_autoload($class_name);
}

set_error_handler("userErrorHandler");

/**
 * userErrorHandler - user error handler function
 * 
 * @package BizSystem
 */
function userErrorHandler ($errno, $errmsg, $filename, $linenum, $vars) 
{
   BizSystem::ErrorHandler($errno, $errmsg, $filename, $linenum, $vars);
}

?>