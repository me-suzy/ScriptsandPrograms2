<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

require_once('../classes/generalFunctions.php');
//register the Site class
$GLOBALS['PntIncludedClasses']['site'] = 'Site';
includeClass('PntSite', 'pnt/web');

/** Objects of this class are the single entrypoint for handling http requests.
* Site connects to the database as specified in scriptMakeSettings.php and
* sets the ErrorHandler, the debugMode, specifies application folder and domain folder, 
* supplies StringConverters,  baseUrl and takes care of sessions.
*
* This concrete subclass is here to keep de application developers
* code (including localization overrides) separated from the framework code.
* @see http://www.phppeanuts.org/site/index_php/Menu/178
* Framework code is in superclass. 
*/
class Site extends PntSite {

	//var $debugMode = 'verbose'; //options: '', 'short', 'verbose'

	function setErrorHandler()
	{
		//Activate the code below if you do not want to see notifications but you do want to log them.
		//also set the correct hostname in classErrorHandler.php
//		includeClass('ErrorHandler');
//		$this->errorHandler =& new ErrorHandler('../classes/pntErrorLog.txt', $this->getShopEmail());
//		$this->errorHandler->loggingLevel = E_ALL;
//		$this->errorHandler->startHandling();
	}

	function getShopEmail() {
		return "youraddress@yourdomain";
	}

}

?>
