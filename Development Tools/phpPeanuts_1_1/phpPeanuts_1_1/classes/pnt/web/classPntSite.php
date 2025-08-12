<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntPage', 'pnt/web/pages');

/** Objects of this class are the single entrypoint for handling http requests.
* Site connects to the database as specified in scriptMakeSettings.php and
* sets the ErrorHandler, the debugMode, specifies application folder and domain folder, 
* supplies StringConverters,  baseUrl and takes care of sessions. 
* 
* This abstract superclass provides behavior for the concrete
* subclass StringConverter in the root classFolder or in the application classFolder. 
* To keep de application developers code (including localization overrides) 
* separated from the framework code override methods in the 
* concrete subclass rather then modify them here.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* @package pnt/web
*/
class PntSite extends PntPage {
// subclassed from Pnt Page instead of PntRequestHandler becuase of depricated support

	// to be set from scriptMakeSettings.php
	var $dbUser;
	var $dbPwd;
	var $dbAddress;
	var $dbPort;
	var $dbName;

	// may be set from scriptMakeSettings.php
	var $baseUrl; 
	var $debugMode = 'short'; //options: '', 'short', 'verbose'
	var $funkyUrls = false;

	var $dbc; //see initDatabaseConnection
	var $dir; //set from constructor parameter
	var $domainDir; //may be set from the applications index.php
	
	// private
	var $requestStartTime;
	var $filters;
	var $converter;

	//depricated support
	var $isSiteRunning=true;
	var $name="site"; 
	var $os="linux";
	 
	function PntSite($dir="beheer") {
		$timeArr = explode(" ",microtime());
		$this->requestStartTime = $timeArr[1].substr($timeArr[0],1,3);

		$this->PntRequestHandler($null, $_REQUEST);
		$this->setErrorHandler();
		$this->setDir($dir);
		$this->setDomainDir($dir);

		header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
		header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
                                                      // always modified
		header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
		header ("Pragma: no-cache");
		
		$this->loadSettings();
		$this->importLibraries();
		$this->createObjects();
	}
	
	function isWindows() {
		if ($this->os=="windows") {
			return true;
		} else {
			return false;
		}
	}
	
	function setErrorHandler()
	{
		includeClass('ErrorHandler');
		$this->errorHandler =& new ErrorHandler();
		$this->errorHandler->startHandling();	
	}
	
	/** WARNING: the active error handler is a copy of the one this method returns.
	* after changing properties of this error handler a new copy of it 
	* must be activated through startHandling()
	* @result ErrorHandler
	*/
	function &getErrorHandler()
	{
		return $this->errorHandler;
	}
	
	function loadSettings() {
		require('../classes/scriptMakeSettings.php');
	}
	
	function importLibraries() {
		// require_once("../classes/generalFunctions.php"); already included by classSite.php
		// require_once("../classes/dateFunctions.php");	no longer supported
	}
	
	function createObjects() {
		includeClass("DatabaseConnection");
		$this->useClass("QueryHandler", $this->getDir());
		$this->useClass("ValueValidator", $this->getDir());
		$this->useClass("StringConverter", $this->getDir());
		
		$this->initConverter();
	}
	
	function initDatabaseConnection()
	{
		$this->dbc = new DatabaseConnection();
		$this->dbc->setUserName($this->dbUser);
		$this->dbc->setPassword($this->dbPwd);
		$this->dbc->setHost($this->dbAddress);
		$this->dbc->setPort($this->dbPort);
		$this->dbc->setDatabaseName($this->dbName);
		$this->dbc->makeConnection();		
	}
	
	function initConverter() {
		$this->converter = new StringConverter();
	}

	function setDir($value){
		$this->dir = ($value && substr($value, -1) != '/')
			? $value.'/'
			: $value;
	}
	
	function getDir() {
		return $this->dir;
	}

	function setDomainDir($value) {
		$this->domainDir = ($value && substr($value, -1) != '/')
			? $value.'/'
			: $value;
	}
	
	function getDomainDir() {
		return $this->domainDir;
	}

	function getBaseUrl() {
		
		if (isSet($this->baseUrl)) return $this->baseUrl;	

		//set $this->baseUrl from scriptMakeSettings if the following does not work correctly
		
		//$_SERVER['HTTPS'] may only be available with apache. 
		if ( isSet($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ) {
			$protocol = 'https' ; $port = 443;
		} else {
			$protocol = 'http'; $port = 80;
		}
		$server = isSet($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
		
		$portPiece = (isSet($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != $port)
			? (':'.$_SERVER['SERVER_PORT']) : '';
		
		$slashPos = strrpos( $_SERVER['REQUEST_URI'], '/');
		$slashPos = strrpos( subStr($_SERVER['REQUEST_URI'], 0, $slashPos), '/'); 
		$path = subStr($_SERVER['REQUEST_URI'], 0, $slashPos+1);
		
		$this->baseUrl = "$protocol://$server$portPiece$path";
		return $this->baseUrl;
	}
	
	/** returns funkyUrls setting
	* funkyUrls are search engine and user friendly urls
	* if Funky Urls are used, $this->baseUrl must be set from scriptMakeSettings
	* questionmarks and ampersands become forward slashes
	*/
	function isFunkyUrls() {
		return $this->funkyUrls;	
	}
	
	
	// answer a copy so field values won't get mixed up
	function getConverter() {
		return $this->converter;
	}

	/** if set to equal false, no debug comments are included by printPart
	* if set to 'verbose' printPart includes comments that show all its options.
	* otherwise printPart inlcudes short comments at start and end of each part
	*/
	function getDebugMode()
	{
		return $this->debugMode;
	}

	function handleRequest() {
		$this->forwardRequest($_REQUEST);
	}

	function forwardRequest(&$requestData, $information=null) {
		if ($this->dbc === null)
			$this->initDatabaseConnection();
		$handler =& $this->getRequestHandler($requestData);
		if ($information)
			$handler->setInformation($information);
		$handler->handleRequest();
	}

	function startSession()
	{
		//must be done before any output is written. 
		//do not register objects in $_SESSION, or serialize them first
		session_start();
		$this->sessionStarted = true;
	}
	
	// make sure to include eventual additional filter classes before calling this method
	function &getGlobalFilters()
	{
		if ($this->filters === null) {
			includeClass('PntSqlFilter', 'pnt/db/query');
			
			$this->filters = array();
			if (isSet($_SESSION['pntGlobalFilters'])) {
				$filterArrays = $_SESSION['pntGlobalFilters'];
				while (list($key) = each($filterArrays)) {
					$this->filters[$key] =& PntSqlFilter::instanceFromPersistArray($filterArrays[$key]);
				}
			}
		}
		return $this->filters;		
	}
	
	function setGlobalFilters(&$filters)
	{
		$this->filters =& $filters;

		$filterArrays = array();
		reset($filters);
		while (list($key) = each($filters)) {
			$filterArrays[$key] = $filters[$key]->getPersistArray();
		}
		$_SESSION['pntGlobalFilters'] = $filterArrays;
	}

	/** Get requestdata from funky url. 
	* all components from '/$this->getDir()/$alias' up to one slash before the ? are interpreted
	* as pntType/id/key/value/key/value etc.  
	* For normal urls this method returns the script name as parameter key, 
	* so one should not use the script name as the name of a parameter in the query string
	* result with POST is unknown
	* if Funky Urls are used, $this->baseUrl must be set from scriptMakeSettings
	*/
	function getFunkyRequestData($alias) 
	{
		$beforeFunky = '/'.$this->getDir().$alias;
		$uri = $_SERVER["REQUEST_URI"];
		$pAndQ = explode('?', $uri);

		//funky url mixes up $_REQUEST, redo parsing of the querystring here
		$requestData = array_merge( parse_str($pAndQ[1]), $_REQUEST);

		$pos = strpos ($pAndQ[0], $beforeFunky);	
		if ($pos === false) return $requestData; //can not find the start of the funky piece 
	
		$funkyPiece = substr($pAndQ[0],$pos+strLen($beforeFunky));
		if (strLen($funkyPiece) == 0) return $requestData; // funky piece is empty
		
		$kvArr = explode("/",$funkyPiece);
		$requestData['pntType'] = ucfirst($kvArr[0]);
		$requestData['id'] = $kvArr[1];

		if (count($kvArr) > 2)
			for ($i=2; $i < count($kvArr)-1; $i += 2)
				$requestData[$kvArr[$i]] = $kvArr[$i+1];

		return $requestData;
	}

	/** Override this to modify default class loading */
	function getTryUseClassTryParams($className, $dir)
	{
		$params = array();
		$params[] = array($className, $dir);
		$params[] = array($className, '');
		return $params;
	}

	/** Override this to modify default skin inclusion */
	function getIncludesDir()
	{
		return 'includes';
	}

} //end of class def
	
?>
