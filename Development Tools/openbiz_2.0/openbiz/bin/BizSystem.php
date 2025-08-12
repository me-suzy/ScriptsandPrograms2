<?PHP

/**
 * BizSystem class - BizSystem is initialized for each request, it provides infrastructure objects and utility methods
 * which are used in whole request.
 * 
 * @package BizSystem
 * @author rocky swen 
 * @copyright Copyright (c) 2005
 * @access public 
 */
class BizSystem
{
   private $m_ObjectFactory = null;
   private $m_SessionContext = null;
   private $m_Confgiuration = null;
   private $m_ClientProxy = null;
   private $m_TypeManager = null;
   private $m_CurrentViewName = "";
   private $m_DBConnection = array();
   private $m_ServiceList = array();
   private $m_UserProfile = null;
   
   /**
    * BizSystem::__construct() - initialize SessionContext and retieve object session variables
    * 
    * @return void 
    */
   public function __construct()
   {
      BizSystem::LoadCoreLib("SessionContext");
      $this->m_SessionContext = new SessionContext(); 
      // retrieve object session vars
      $this->m_SessionContext->RetrieveSessionObjects();
   }
   
   /**
    * BizSystem::__destruct() - save object session variables
    * 
    * @return void 
    */
   public function __destruct()
   {
      // save object session vars
      $this->m_SessionContext->SaveSessionObjects();
      //echo "<br>destruct bizSystem";
   }
   
   /**
    * BizSystem::GetObjectFactory() - get the ObjectFactory object
    * 
    * @return ObjectFactory 
    */
   public function GetObjectFactory()
   {
      if (!$this->m_ObjectFactory) {
         BizSystem::LoadCoreLib("ObjectFactory");
         $this->m_ObjectFactory = new ObjectFactory();
      }
      return $this->m_ObjectFactory;
   }
   
   /**
    * BizSystem::GetSessionContext() - get the SessionContext object
    * 
    * @return SessionContext 
    */
   public function GetSessionContext()
   {
      return $this->m_SessionContext;
   }
   
   /**
    * BizSystem::GetConfiguration() - get the Configuration object
    * 
    * @return Configuration 
    */
   public function GetConfiguration()
   {
      if (!$this->m_Confgiuration) {
         BizSystem::LoadCoreLib("Configuration");
         $this->m_Confgiuration = new Configuration();
      }
      return $this->m_Confgiuration;
   }
   
   /**
    * BizSystem::GetClientProxy() - get the ClientProxy object
    * 
    * @return ClientProxy 
    */
   public function GetClientProxy()
   {
      if (!$this->m_ClientProxy) {
         BizSystem::LoadCoreLib("ClientProxy");
         $this->m_ClientProxy = new ClientProxy();
      }
      return $this->m_ClientProxy;
   }
   
   /**
    * BizSystem::GetTypeManager() - get the TypeManager object
    * 
    * @return TypeManager 
    */
   public function GetTypeManager()
   {
      if (!$this->m_TypeManager) {
         BizSystem::LoadCoreLib("TypeManager");
         $this->m_TypeManager = new TypeManager();
      }
      return $this->m_TypeManager;
   }
   
   public function GetService($service)
   {
      /*
      if (key_exists($service, $this->m_ServiceList))
         return $this->m_ServiceList[$service];
      
      $class = $service;
      $classFile= $this->GetLibFileWithPath($class, "service");
      if (!$classFile) {
         $errmsg = $this->GetMessage("ERROR", "SYS_ERROR_CLASSNOTFOUND",array($class));
         trigger_error($errmsg, E_USER_ERROR);
      }
      else {
         include_once($classFile);
         $obj = new $class();
         $this->m_ServiceList[$service] = $obj;
         return $obj;
      }
      return null;
      */
      $default_package = "service";
      $svc_name = $service;
      if (strpos($service, ".") === false)
         $svc_name = $default_package.".".$service;
      return $this->GetObjectFactory()->GetObject($svc_name);
   }
   
   public function InitUserProfile($userId)
   {
      $svcobj = $this->GetService("profileService");
      $profile = $svcobj->GetProfile($userId);
      if ($profile)
         $this->GetSessionContext()->SetVar("USR_PRFL", $profile);
      return $profile;
   }
   
   public function GetUserProfile($attribute=null)
   {
      if (!$this->m_UserProfile) {
         $this->m_UserProfile = $this->GetSessionContext()->GetVar("USR_PRFL");   // USR_PRFL stands for user profile
      }
      if ($attribute && key_exists($attribute, $this->m_UserProfile))
         return $this->m_UserProfile[$attribute];
      else if ($attribute) {
         $svcobj = $this->GetService("profileService");
         return $svcobj->GetAttribute($this->m_UserProfile["USERID"],$attribute);
      }
      return $this->m_UserProfile; 
   }
   
   /**
    * BizSystem::GetCurrentViewName() - get the current view name
    * 
    * @return string 
    */
   public function GetCurrentViewName() 
   { 
      if ($this->m_CurrentViewName == "")
         $this->m_CurrentViewName = $this->GetSessionContext()->GetVar("CVN");   // CVN stands for CurrentViewName
      return $this->m_CurrentViewName; 
   }
   /**
    * BizSystem::SetCurrentViewName() - set the current view name
    * 
    * @param string $viewname
    * @return string 
    */
   public function SetCurrentViewName($viewname) 
   { 
      $this->m_CurrentViewName = $viewname;
      $this->GetSessionContext()->SetVar("CVN", $this->m_CurrentViewName);   // CVN stands for CurrentViewName
   }
   
   /**
    * BizSystem::GetDBConnection() - get the database connection object
    * 
    * @param string $dbname, database name declared in config.xml
    * @return NewADOConnection 
    */
   public function GetDBConnection($dbname=null)
   {
      $rDBName = (!$dbname) ? "Default" : $dbname;
      if (isset($this->m_DBConnection[$rDBName]))
         return $this->m_DBConnection[$rDBName];
         
      $dbinfo = $this->GetConfiguration()->GetDatabaseInfo($rDBName);
      
      include_once(ADODB_DIR."/adodb.inc.php");
      $this->m_DbConnection[$rDBName] = NewADOConnection($dbinfo["Driver"]);
      if (!$this->m_DbConnection[$rDBName])
         trigger_error("Unable to create a db connection on driver '".$dbinfo["Driver"]."'", E_USER_ERROR);
      $this->m_DbConnection[$rDBName]->debug = false;
      if (!$this->m_DbConnection[$rDBName]->Connect($dbinfo["Server"], $dbinfo["User"], $dbinfo["Password"], $dbinfo["DBName"])) {
         $errmsg = BizSystem::GetMessage("ERROR", "BDO_ERROR_DBCONN",array($rDBName));
         trigger_error($errmsg, E_USER_ERROR);
      }
      
      $this->m_DbConnection[$rDBName]->SetFetchMode(ADODB_FETCH_NUM);
      
      if ($dbinfo["Driver"] == "mysql")
         mysql_query("SET NAMES 'utf8'"); 
      
      return $this->m_DbConnection[$rDBName];
   }
   
   /**
    * BizSystem::GetMacroValue() - evaluate macro, this method can only be used to get profile in 2.0
    * For example, @macro_var:macro_key. i.e. @profile:ROLE
    * 
    * @param string $var, macro name
    * @param string $key, macro key
    * @return string
    */
   public function GetMacroValue($var, $key)
   {
      if ($var == "profile") {
         return $this->GetUserProfile($key);
      }
      return null;
   }
   
   public static function GetSmartyTemplate()
   {
      include_once(SMARTY_DIR."Smarty.class.php");
      $smarty = new Smarty;
      if (defined('SMARTY_TPL_PATH'))
         $smarty->template_dir = SMARTY_TPL_PATH;
      if (defined('SMARTY_CPL_PATH'))
         $smarty->compile_dir = SMARTY_CPL_PATH;
      if (defined('SMARTY_CFG_PATH'))
         $smarty->config_dir = SMARTY_CFG_PATH;
      return $smarty;
   }
   
   /*
    * BizSystem::log() 
    * log message to log file
    *  
    * @param integer $priority. it can be one of following value
    *    LOG_EMERG	system is unusable = 1
    *    LOG_ALERT	action must be taken immediately = LOG_EMERG
    *    LOG_CRIT	   critical conditions = LOG_EMERG
    *    LOG_ERR	   error conditions = 4
    *    LOG_WARNING	warning conditions = 5
    *    LOG_NOTICE	normal, but significant, condition = 6
    *    LOG_INFO	   informational message = LOG_NOTICE
    *    LOG_DEBUG	debug-level message = LOG_NOTICE
    *    ### So LOG_EMERG, LOG_ERR, LOG_WARNING and LOG_DEBUG are valid inputs ###
    * @param string $subject. the log subject decided by caller function
    * @param string $message. the message to be logged in log file
    * @return void
   */
   public static function log($priority, $subject, $message)
   {
      global $g_BizSystem;
      $svcobj = $g_BizSystem->GetService("logService");
      $svcobj->log($priority, $subject, $message);
   }
   
   /**
    * $BizSystem::GetNewSYSID()
    * Get a new SYSID from the id_table. You can get SYSID for a table with prefix and base converting
    * 
    * @param ADOConnection $conn
    * @param string $tablename
    * @param boolean $includePrefix
    * @param integer $base
    * @return string
    **/
   public static function GetNewSYSID($conn, $tablename, $includePrefix=false, $base=-1)
   {
      $maxRetry = 3;
      $sql = "SELECT * FROM ob_sysids WHERE ob_sysids.TABLENAME='".$tablename."'";
      $rs = &$conn->Execute($sql);
      if ($rs && !$rs->EOF) {
         if ($rs->fields[2] == null && $rs->fields[1]) // idbody is empty, return prefix
           return $rs->fields[1]."_";
         for ($try=1; $try <= $maxRetry; $try++)
         {
           $idbody = $rs->fields[2]+$try;
           $sql = "UPDATE ob_sysids SET ob_sysids.IDBODY=".$idbody." WHERE ob_sysids.TABLENAME='".$tablename."'";
           $rt = &$conn->Execute($sql);
           if ($rt === false && $try >= $maxRetry) 
              return null;
           else
              break;
         }
         if ($base>=2 && $base<=36)
            $idbody = dec2base($idbody, $base);
         if ($includePrefix)
            return $rs->fields[1]."_".$idbody;
         return $idbody;
      }
      return null;
   }
   
   /**
    * BizSystem::GetXmlFileWithPath()
    * Search the object metedata file as objname+.xml in metedata directories 
    * name convension: demo.BOEvent points to metadata/demo/BOEvent.xml
    * 
    * @param string $xmlobj
    * @return string xml config file path 
    **/
   public static function GetXmlFileWithPath($xmlobj)
   {
      $xmlfile = $xmlobj;
      if (strpos($xmlobj, ".xml")>0)  // remove .xml suffix if any
         $xmlfile = substr($xmlobj, 0, strlen($xmlobj)-4);
      
      // replace "." with "/"
      $xmlfile = str_replace (".", "/", $xmlfile);
      $xmlfile .= ".xml";
   
      if (file_exists($xmlfile)) 
         return $xmlfile;
      $xmlfile = "/".$xmlfile;
      if (file_exists(META_PATH.$xmlfile))
         return META_PATH.$xmlfile;
      if (file_exists(OPENBIZ_META.$xmlfile))
         return OPENBIZ_META.$xmlfile;

      return null;
   }
   
   /**
    * BizSystem::GetLibFileWithPath()
    * Get openbiz library php file path from /bin or /bin/usrlib 
    * 
    * @param string $className
    * @return string php library file path 
    **/
   public static function GetLibFileWithPath($className, $packageName="")
   {
      $classfile = $className.".php";
      // convert package name to path, add it to classfile
      if ($packageName) {
         $path = str_replace(".", "/", $packageName);
         $p_classfile = $path."/".$classfile;
         if (file_exists($p_classfile)) 
            return $p_classfile;
      }
      
      if (file_exists($classfile)) 
         return $classfile;
      if (file_exists(OPENBIZ_BIN.$classfile))
         return OPENBIZ_BIN.$classfile;
      //else if (file_exists(OPENBIZ_BIN."usrlib/".$classfile))
      //   return OPENBIZ_BIN."usrlib/".$classfile;
      return null;
   }
   
   public static function LoadCoreLib($className)
   {
      $classfile = BizSystem::GetLibFileWithPath($className);
      include_once($classfile);
   }

   /**
    * BizSystem::GetXmlArray()
    * Get Xml Array. If xml file has been compiled (has .cmp), load the cmp file as array; otherwise, compile the .xml to .cmp first
    * 
    * @param string $xmlFile
    * @return array 
    **/
   public static function &GetXmlArray($xmlFile)
   {
      $objXmlFileName = $xmlFile;
      $objCmpFileName = str_replace(".xml", ".cmp", $objXmlFileName);
      
      include_once("xmltoarray.php");
      
      if(file_exists($objCmpFileName) 
         && (filemtime($objCmpFileName)>filemtime($objXmlFileName)) )
      {
         $content_array = file($objCmpFileName);
         $xmlArr = unserialize(implode("", $content_array));
      }
      else {
         $parser = new XMLParser($objXmlFileName, 'file', 1);
         $xmlArr = $parser->getTree();
         
         $xmlArrStr = serialize($xmlArr);
         $cmp_file = fopen($objCmpFileName, 'w') or die("can't open cmp file to write");
         fwrite($cmp_file, $xmlArrStr) or die("can't write to the cmp file");
         fclose($cmp_file);
      }
      return $xmlArr;
   }

   /**
    * BizSystem::GetMessage()
    * Get message resource
    * 
    * @param string $rscType, resource type (ERROR|...|...)
    * @param string $resId, resource id
    * @param array $paramArr, parameter key-value array
    * @return string 
    **/
   public static function GetMessage($rscType, $rscId, $paramArr=null)
   {
      // get locale
      // get the resource according to the locale
      global $Rsc_ErrorMessages;
      if ($rscType == "ERROR") {
         $msg = $Rsc_ErrorMessages[$rscId];
      }
      if ($paramArr) {
         $i=0; $pos=0;
         foreach($paramArr as $param) {
            $pos = strpos($msg,"%".$i."%",$pos);
            if ($pos===false) break;
            $msg = str_replace("%".$i."%",$param,$msg);
            $i++;
         }
      }
      return $msg;
   }
   
   /**
    * BizSystem::ErrorHandler() - user error handler
    **/
   public static function ErrorHandler($errno, $errmsg, $filename, $linenum, $vars)
   {
       // timestamp for the error entry
       $dt = date("Y-m-d H:i:s (T)");
       
       // set of errors for which a var trace will be saved
       $user_errors = array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE);

       if ($errno == E_NOTICE || $errno == E_STRICT) return;  // ignore notice error
       //if ($errno == E_STRICT) return;  // ignore notice error
   
       $err = "<div style='font-size: 12px; color: blue; font-family:Arial; font-weight:bold;'>";
       $err .= "[$dt] An exception occurred while executing this script:<br>";
       $err .= "Error message: <font color=maroon> #$errno, $errmsg</font><br>"; 
       $err .= "Script name and line number of error: <font color=maroon>$filename:$linenum</font><br>"; 
       //$err .= "Variable state when error occurred: $vars<br>"; 
       $err .= "<hr>";
       $err .= "Please ask system administrator for help...</div>";
       
       //echo $err;
       global $g_BizSystem;
       echo $g_BizSystem->GetClientProxy()->ShowErrorPopup($err);
   
       if ($errno == E_USER_ERROR || $errno == E_ERROR)
          exit;
   }
   
   public static function ErrorBacktrace($print=true)
   {
       $debug_array = debug_backtrace();
       $counter = count($debug_array);
       for($tmp_counter = 0; $tmp_counter != $counter; ++$tmp_counter)
       {
          echo "<br><b>function:</b> ";   
          echo $debug_array[$tmp_counter]["function"] . " ( ";
          //count how many args a there
          $args_counter = count($debug_array[$tmp_counter]["args"]);
          //print them
          for($tmp_args_counter = 0; $tmp_args_counter != $args_counter; ++$tmp_args_counter)
          {
             echo($debug_array[$tmp_counter]["args"][$tmp_args_counter]);
             if(($tmp_args_counter + 1) != $args_counter)
               echo(", ");
             else
               echo(" ");
          }
          echo ") @ ";
          echo($debug_array[$tmp_counter]["file"]." "); 
          echo($debug_array[$tmp_counter]["line"]);
          if(($tmp_counter + 1) != $counter)
             echo "\n";
       }
       //exit();
   }

}

?>