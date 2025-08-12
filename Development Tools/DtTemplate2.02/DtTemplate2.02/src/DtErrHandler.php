<?
/*
DtErrHandler.php
Copyright (c) 2004 DeRose Technologies, Inc.

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 2.1 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public
License along with this library; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

To contact the original author of this library:
	www: 		http://www.derosetechnologies.com 
	email:	info@derosetechnologies.com
	

NOTES:
	* ITS EXTREMELY IMPORTANT that the DTERR_ERROROFLASTRESORT compiles correctly, otherwise you stand a good chance of looping infinitely while compile error-after-error trigger's a compile error
	* Error constants 0-10 are reserved, use a number higher than ten for your code's errors unless you want problems down the line.
	* DTERR_OK should be universal, its ok for your code to use (i.e. return ...) this.
	  Alternatively , you can return bool(false) on success , which does the same thing.
	  If this is tough for you to digest, just remember - there are a large number of ways ( represented by integers greater than 0) a function can fail , but there's only one way it can succeed. (integer 0)

TODO: 
		
	LONG-TERM:
	* Maybe we should accept error numbers in addition to the error name in the xml file...
	
	* Let's make sure the thrown errors that occur during an ERRORLAST work...
		* Should Error last always return a string? I think so...
		* Something that we have to keep in mind is that there's a definate possibility for a for a fatal type-mismatch to occur in the template merge .
			if this is the case, then that would cause a subsequent unknown error string merge to fail as well (probably). so just plan around this possibility
*/

define('DtErrHandler.php',true);

if (!defined('DTERR_ERROROFLASTRESORT'))
	define('DTERR_ERROROFLASTRESORT', 'An unknown error [%ON ERR_NAME%]"[%ERR_NAME%]"[%/ON%] ([%ERR_NO%]) has occured in class "[%ERR_CLASS%]". [%ON ERR_DEF%]The error provider furnished the following, context-specific details: [%ERR_DEF OnRecord="1"%][%DEF_KEY%] = "[%DEF_VAL%]"[%/ERR_DEF%][%ERR_DEF OnRecord="-1"%], and [%DEF_KEY%] = "[%DEF_VAL%]"[%/ERR_DEF%][%ERR_DEF%], [%DEF_KEY%] = "[%DEF_VAL%]"[%/ERR_DEF%].[%/ON%]');

/*************************************************************/
/* Includes                                                  */
/*************************************************************/
if (!defined('DtI18Resource.php')) require(dirname (__FILE__).'/DtI18Resource.php');
if (!defined('DtTemplate.php')) require(dirname (__FILE__).'/DtTemplate.php');

/*************************************************************/
/* Error Codes                                               */
/*************************************************************/
dterr_class('DtErrHandler');

dterr_error('DTERR_OK', 		0); // This is a success 'error'
dterr_error('DTERR_FAIL', 		1); // This is a generic failure 'error'. don't use this , please. 
dterr_error('DTERR_UNKNOWN',	2); // This is a generic failure 'error'. for lazy developers who don't undersdtand why its important to DOCUMENT!

dterr_error('DTERR_ERRORLAST_COMPILEFAILED',	5); 
dterr_error('DTERR_ERRORLAST_MERGEFAILED',	6); 


/*************************************************************/
/* Constants                                                 */
/*************************************************************/
define('DTERRHANDLER_EI_NUM' , 	1);
define('DTERRHANDLER_EI_DEF' , 	2);
define('DTERRHANDLER_EI_CLASS',	3);

/*************************************************************/
/* Globals                                                   */
/*************************************************************/
if (!$_a_DtErrCodeNameMap)
	$_a_DtErrCodeNameMap = array();

if (!$_DtErrCurClass)
	$_DtErrCurClass = NULL;

/*
	We make these short and simple so that they're fast and can't fail..
*/

function dterr_class($strClass) { $GLOBALS['_DtErrCurClass'] = &$strClass; }

function dterr_error($strName, $nNum, $strClass = NULL) {
	
	$GLOBALS['_a_DtErrCodeNameMap'][ ($strClass) ? $strClass : $GLOBALS['_DtErrCurClass'] ][$nNum] = $strName;
	
	define($strName, $nNum);
}


/*************************************************************/
/* Code                                                      */
/*************************************************************/

class DtErrHandler {
	
	/* Private: ***************************************/
	
	var $_DTE_a_ResPaths	= array(); 	// Class name to resource file mappings
	var $_DTE_a_Errors 	= array(); 	// Our Error Stack, Indexed by occurance (int), its arrays are indexed by the above DTERRHANDLER_EI_* constants
	
	function _errorClassAttach ($strClassName, $strClassResFile) {
		// This is to be called inside a class constructor, and sets-up that class from its inheritence

		$this->_DTE_a_ResPaths[$strClassName] = $strClassResFile;
		
		return DTERR_OK;
	}

	function _errorFlush() {
		if (!$this) return ;
		
		$this->_DTE_a_Errors = array();
		
		return DTERR_OK;
	}


	function _errorThrow ($strClass, $nErr, $a_ErrDef = array() ) {
		
		if (!$this) return $nErr; // Just incase we're being called by a class in a :: mechansim, we'll return the error number - that's about all that can be done.
		
		$this->_DTE_a_Errors[] = array(
			DTERRHANDLER_EI_CLASS 	=> &$strClass,
			DTERRHANDLER_EI_NUM		=> $nErr,
			DTERRHANDLER_EI_DEF 		=> &$a_ErrDef
		);
		
		// We return our called error so that if the caller is returning our value (due to it being fatal), *its* caller knows the error condition why.
		return $nErr; 
	}

	function _errorMessageCompose ( $nErr, $strTemplate ) {
		// In here, we'll compose the error and if $strTemplate is null or otherwise invalid, we'll go ahead and generate a generic error.
		
		$strRet = NULL;

		// We'll declare this static , just in case we end up with multiple errors in need of an Unknown template - this will act as the "DTERR_UNKNOWN" template 'cache'. 
		static $strTemplateUnknown = NULL;

		$strErrClass 	= &$this->_DTE_a_Errors[$nErr][DTERRHANDLER_EI_CLASS];
		$nErrNum			= &$this->_DTE_a_Errors[$nErr][DTERRHANDLER_EI_NUM];
	
		if ($strTemplate)
			// nice, all is well, so let's set up the error's definition:
			$a_Def = &$this->_DTE_a_Errors[$nErr][DTERRHANDLER_EI_DEF];
		else {
			// Since we couldn't find the error we wanted, let's load up the generic Unknown Error message instead:
			
			if (!$strTemplateUnknown) {
				$irError = new DtI18Resource( $this->_DTE_a_ResPaths['DtErrHandler'] );
				
				if ($irError) {
					$a_Resources = &$irError->resourceGet('DTERR_UNKNOWN');
					
					$strTemplateUnknown = $a_Resources['DTERR_UNKNOWN'];
				}
				else
					$strTemplateUnknown = DTERR_ERROROFLASTRESORT;
			}

			$strTemplate = &$strTemplateUnknown;
			
			// Now that we're here, Let's modify the definition so that these generic errors can accomodate the error specificities better:
			$a_Def = array( 
				'DEF' => array()
			);
			
			foreach ($this->_DTE_a_Errors[$nErr][DTERRHANDLER_EI_DEF] as $strDefKey => $sDefVal)
				$a_Def['ERR_DEF'][] = array( 'DEF_KEY'=>$strDefKey, 'DEF_VAL'=>$sDefVal );
		}

		$tmplMerger = new DtTemplate();
				
		if ($ret = $tmplMerger->templateCompile(&$strTemplate))
			return $this->_errorThrow(
				'DtErrHandler',
				DTERR_ERRORLAST_COMPILEFAILED,
				array(
					'REQUESTED_ERRNO' => $nErrNum,
					'REQUESTED_CLASS' => $strErrClass,
					'TMPL_ERRSTRING'	=> $tmplMerger->errorLast(),
					'TMPL_ERRNUM'		=> $ret,
				)
			);

		$ret = $tmplMerger->merge(
			array_merge(
				array( 
					'ERR_NO' 	=> $nErrNum,
					'ERR_CLASS' => $strErrClass,
					'ERR_NAME' 	=> &$GLOBALS['_a_DtErrCodeNameMap'][$strErrClass][$nErrNum],
				),
				&$a_Def
			)
		);
		
		if (is_int($ret))
			return $this->_errorThrow(
				'DtErrHandler',
				DTERR_ERRORLAST_MERGEFAILED,
				array(
					'REQUESTED_ERRNO' => $nErrNum,
					'REQUESTED_CLASS' => $strErrClass,
					'TMPL_ERRSTRING'	=> $tmplMerger->errorLast(),
					'TMPL_ERRNUM'		=> $ret
				)
			);		
		
		return $ret;
	}
	
	/* Public: ****************************************/

	function DtErrHandler ( ) {
		
		$this->_errorClassAttach('DtErrHandler', dirname(__FILE__).'/DtErrHandler-IRes.xml');

		return;
	}


	function &errorAll() {
		// Return a merged template, of the entire array of errors. 
		
		$a_Ret = array();
		
		if (!sizeof($this->_DTE_a_Errors))
			return DTERR_OK;
		
		$a_i18Resources = array();
		$a_ErrorStackNames = array(); // Just a cache to make the stack-index to error name translation a little easier down the line.
		
		// We'll want to call DtI18Resource as few times as possible, which means only once per 'class', so before we go much further, let's separate the error stack into ordered arrays by class:
		for ($i=0;$i<sizeof($this->_DTE_a_Errors); $i++) {
			$a_ErrorStackNames[$i] = &$GLOBALS['_a_DtErrCodeNameMap'][$this->_DTE_a_Errors[$i][DTERRHANDLER_EI_CLASS]][$this->_DTE_a_Errors[$i][DTERRHANDLER_EI_NUM]];
			$a_i18Resources[$this->_DTE_a_Errors[$i][DTERRHANDLER_EI_CLASS]][] = $a_ErrorStackNames[$i];
		}

		foreach (array_keys($a_i18Resources) as $strClass ) {
			
			$irError = new DtI18Resource( $this->_DTE_a_ResPaths[$strClass] );

			if ($irError)
				$a_i18Resources[$strClass] = &$irError->resourceGet($a_i18Resources[$strClass]);
			else
				// Apparently there was a an error during the resource grab...
				$a_i18Resources[$strClass] = array();
		}
		
		for ($i=0;$i<sizeof($this->_DTE_a_Errors); $i++)
			$a_Ret[] = $this->_errorMessageCompose(
				$i,
				$a_i18Resources[$this->_DTE_a_Errors[$i][DTERRHANDLER_EI_CLASS]][$a_ErrorStackNames[$i]]
			);
		
		
		return $a_Ret;
	}
	

	function errorLast () {
		// If our language is not set and/or the resource file cannot be loaded, then we'll just have to return sprintf() formatted error numbers
	
		if (!sizeof($this->_DTE_a_Errors))
			return DTERR_OK;

		$nErrLast = (sizeof($this->_DTE_a_Errors)-1);

		$strErrorMessage	= NULL; // This is where our i18 adjusted mergeable error will go. if we find it...
		$strErrClass	= &$this->_DTE_a_Errors[$nErrLast][DTERRHANDLER_EI_CLASS];
		$strErrName 	= &$GLOBALS['_a_DtErrCodeNameMap'][$strErrClass][$this->_DTE_a_Errors[$nErrLast][DTERRHANDLER_EI_NUM]];

		if ($strErrName) {
			$irError = new DtI18Resource( $this->_DTE_a_ResPaths[$strErrClass] );
			
			if ($irError) {
				$a_Resources = &$irError->resourceGet($strErrName);
	
				$strErrorMessage = &$a_Resources[$strErrName];
			}
		}
		
		return $this->_errorMessageCompose($nErrLast, $strErrorMessage);

	}	
}
?>