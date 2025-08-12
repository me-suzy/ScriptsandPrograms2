<?
/*
DtI18Resource.php
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
	* The goal during parsing is to minimize memory usage, that's why we don't parse the whole thing into a structure or anything like that, these resource files can get huge...
	* When we can't find a resource in the requested language/dialect context, we look for a match ni the following order:
		* The requested language's , 	'default_dialect'
		* The DTI18_DEFAULTLANG , 		DTI18_DEFAULTDIALECT
		* The DTI18_DEFAULTLANG , 		'default_dialect' 
TODO:
	* Resource get should accept a single arrray or strings..
	* And return a single string or an array.
*/
define('DtI18Resource.php', true);

if (!defined('DTI18_DEFAULTLANG'))
	define('DTI18_DEFAULTLANG',		'en');

if (!defined('DTI18_DEFAULTDIALECT'))
	define('DTI18_DEFAULTDIALECT',	'us');

if (!defined('DTI18_DEFAULTCHARSET'))
	define('DTI18_DEFAULTCHARSET',	'UTF-8');

/*************************************************************/
/* Includes                                                  */
/*************************************************************/
if (!defined('DtErrHandler.php')) require(dirname (__FILE__).'/DtErrHandler.php');

/*************************************************************/
/* Constants                                                 */
/*************************************************************/

// These are used on the $a_ParseCur*'s element index as a mechanism for understanding the given parse state inside _parsexmlCharacterData() at any given time.
define('DTI18_EI_NAME',					1); // <-- Either a resource name, language name or dialect name - depending.
define('DTI18_EI_LANGDIALECTDEF',	2); // <-- the default language dialect,
define('DTI18_EI_RESOURCETYPE',		3); // <-- the type of the resource , currently we only support 'text'
	define('DTI18_RESOURCETYPE_TEXT',	1);

/*************************************************************/
/* Error Codes                                               */
/*************************************************************/
dterr_class('DtI18Resource');

dterr_error('DTI18_RESOURCEGET_BADPARM',				5); // A non-string was passed to resourceGet()
dterr_error('DTI18_RESOURCEGET_NOTARGETS',			6); // No valid targets were passed to resourceGet()
dterr_error('DTI18_RESOURCEGET_PARSERCREATEFAIL',	7);
dterr_error('DTI18_RESOURCEGET_SETOBJECTFAIL',		8);
dterr_error('DTI18_RESOURCEGET_SETELHANDLERFAIL',	9);
dterr_error('DTI18_RESOURCEGET_SETDATAHANDLERFAIL', 10);
dterr_error('DTI18_RESOURCEGET_XMLPARSEFAIL', 		11);
dterr_error('DTI18_RESOURCEGET_PARSERFREEFAIL', 	12);

dterr_error('DTI18_XMLPARSE_UNRECOGNIZEDELEMENT', 	13);
dterr_error('DTI18_XMLPARSE_DIALECTNOTINLANG', 		14);
dterr_error('DTI18_XMLPARSE_RESOURCENOTINDIALECT', 15);
dterr_error('DTI18_XMLPARSE_LANGNOTATROOT', 			16);
dterr_error('DTI18_XMLPARSE_RESTYPENUSUPPORTED', 	17);

/*************************************************************/
/* Code                                                      */
/*************************************************************/

class DtI18Resource extends DtErrHandler {
	
	/* Private: ***************************************/
	
	var $strLang 			= NULL;
	var $strDialect 		= NULL;
	var $strCharset 		= NULL;
	
	var $strPathXmlRes 	= NULL;
	
	/*
		These member variables exist so that the parse functions can communicate amongst themselves during the process,
		Their lifespan only exists during the parse stage and will be expunged aupon completion.		
	*/
	var $a_ParseResTargets 	= array();
	
	var $a_ParseCurResource	= array();
	var $a_ParseCurLanguage	= array();
	var $a_ParseCurDialect	= array();
	
	
	// When this is set to true, we've just parsed a resource, used for duplicate resource entry detection.
	var $bParseResourceIsFresh = false;
	
	// This is where the matches go:
	var $a_ParseTargetFailsafe 			= array(); // This is the almost worst-case scenario match, usually pulled from DTI18_DEFAULTLANG & DTI18_DEFAULTDIALECT
	var $a_ParseTargetFailsafeDefault 	= array(); // This is the worse cast scenario match, if there's no DTI18_DEFAULTDIALECT found under DTI18_DEFAULTLANG, then we take DTI18_DEFAULTLANG's default_dialect and stick it here.
	var $a_ParseTargetLangDefault			= array(); // This is where the match goes if its in the requested language, albeit not the requested dialect.
	var $a_ParseTargetRequested			= array(); // The exact match, a best case sceanrio!


	function _resetParseMembers ( ) {
		// This reduces the waste of resident variables being in memory that don't need to be between parse runs, as well as initializing the communications values before a fresh parse run.
		
		$this->a_ParseResTargets 			= array();
			
		$this->a_ParseCurResource			= array();
		$this->a_ParseCurLanguage			= array();
		$this->a_ParseCurDialect			= array();

		$this->bParseResourceIsFresh = false;	
		
		$this->a_ParseTargetFailsafe 			= array();
		$this->a_ParseTargetFailsafeDefault	= array();
		$this->a_ParseTargetLangDefault 		= array();
		$this->a_ParseTargetRequested 		= array();				
	}	
	
	function _parsexmlStartElement ( $res , $strName, $a_Attribs = NULL ) {
				
		switch ($strName) {
			case 'RESOURCE':
				if (
					($a_Attribs['TYPE']) && 
					(strcasecmp($a_Attribs['TYPE'],'TEXT'))
				) 
					return $this->_errorThrow ( 
						'DtI18Resource', 
						DTI18_XMLPARSE_RESTYPENUSUPPORTED,
						array(
							'LINE' => xml_get_current_line_number($res)
						)
					);

				if ( (!$this->a_ParseCurDialect) || ($this->a_ParseCurResource) )
					return $this->_errorThrow ( 
						'DtI18Resource', 
						DTI18_XMLPARSE_RESOURCENOTINDIALECT,
						array(
							'LINE' => xml_get_current_line_number($res)
						)
					);

				/*
					this Sets a flag that tells us we've just matched a resource.
				 	this will prevent issues in the case of a duplicate named resource match under the same context ...
				 	(For this case, we use the most recent duplicate)
				*/
				$this->bParseResourceIsFresh = true;
				
				$this->a_ParseCurResource = array (
					DTI18_EI_RESOURCETYPE 	=> DTI18_RESOURCETYPE_TEXT,
					DTI18_EI_NAME				=> $a_Attribs['NAME']
				);
				break;
			case 'LANGUAGE':
				if ($this->a_ParseCurLanguage)
					return $this->_errorThrow ( 
						'DtI18Resource', 
						DTI18_XMLPARSE_LANGNOTATROOT,
						array(
							'LINE' => xml_get_current_line_number($res)
						)
					);

				$this->a_ParseCurLanguage = array (
					DTI18_EI_NAME				=> $a_Attribs['NAME'],
					DTI18_EI_LANGDIALECTDEF	=> $a_Attribs['DEFAULT_DIALECT']
				);
				break;
			case 'DIALECT':
				if ( (!$this->a_ParseCurLanguage) || ($this->a_ParseCurDialect) )
					return $this->_errorThrow ( 
						'DtI18Resource', 
						DTI18_XMLPARSE_DIALECTNOTINLANG,
						array(
							'LINE' => xml_get_current_line_number($res)
						)
					);

				$this->a_ParseCurDialect = array (
					DTI18_EI_NAME		=> $a_Attribs['NAME']
				);			
				break;
			case 'IRESOURCES':
				// This isn't too important to us...
				break;				
			default:
				return $this->_errorThrow ( 
					'DtI18Resource', 
					DTI18_XMLPARSE_UNRECOGNIZEDELEMENT,
					array(
						'ELEMENT_NAME' => $strName,
						'LINE' => xml_get_current_line_number($res)
					)
				);
				break;
		}
		
		
	}
	
	function _parsexmlEndElement ( $res , $strName ) {
		
		switch ($strName) {
			case 'RESOURCE':
				$this->a_ParseCurResource = NULL;
				break;
			case 'LANGUAGE':
				$this->a_ParseCurLanguage = NULL;
				break;
			case 'DIALECT':
				$this->a_ParseCurDialect = NULL;
				break;
			case 'IRESOURCES':
				// This isn't too important to us...
				break;				
			default:
				return $this->_errorThrow ( 
					'DtI18Resource', 
					DTI18_XMLPARSE_UNRECOGNIZEDELEMENT,
					array(
						'ELEMENT_NAME' => $strName,
						'LINE' => xml_get_current_line_number($res)
					)
				);
				break;
		}
	}

	function _parsexmlCharacterData ( $res , $strData ) {
		if (!$this->a_ParseCurResource) return;
			
		$strTarget = NULL; // This is the destination location for the current $strData, it changes depending on our current context:
		
		if (in_array($this->a_ParseCurResource[DTI18_EI_NAME],$this->a_ParseResTargets,true)) {
			// Now we check to see what language and dialect we're dealing with, and store the match in the appropriate array:
			
			if (! strcasecmp($this->a_ParseCurLanguage[DTI18_EI_NAME] , $this->strLang ) ) {
				if ( ! strcasecmp($this->a_ParseCurDialect[DTI18_EI_NAME] , $this->strDialect) ) 
					// An exact match, yes!
					$strTarget = &$this->a_ParseTargetRequested[$this->a_ParseCurResource[DTI18_EI_NAME]];
				
				else if (! strcasecmp($this->a_ParseCurLanguage[DTI18_EI_LANGDIALECTDEF] , $this->a_ParseCurDialect[DTI18_EI_NAME]) )
					// This means we have Lang Default Match :
					$strTarget = &$this->a_ParseTargetLangDefault[$this->a_ParseCurResource[DTI18_EI_NAME]];
				else 
					return; // We don't care about this resource. b/c its not viable for the needed language/dialect.
			}
			else if (! strcasecmp($this->a_ParseCurLanguage[DTI18_EI_NAME] , DTI18_DEFAULTLANG ) ) {
			
				if (! strcasecmp($this->a_ParseCurDialect[DTI18_EI_NAME] , DTI18_DEFAULTDIALECT) ) 
					// This means we have Failsafe Match
					$strTarget = &$this->a_ParseTargetFailsafe[$this->a_ParseCurResource[DTI18_EI_NAME]];
				else if (! strcasecmp($this->a_ParseCurDialect[DTI18_EI_NAME] , $this->a_ParseCurLanguage[DTI18_EI_LANGDIALECTDEF]) ) 
					// This means we have the failsafe language's default_dialect match:
					$strTarget = &$this->a_ParseTargetFailsafeDefault[$this->a_ParseCurResource[DTI18_EI_NAME]];
				else 
					return; // We don't care about this resource. b/c its not viable for the needed language/dialect.
			}
			else
				return; // We don't care about this resource. b/c its not viable for the needed language/dialect.

			if ($this->bParseResourceIsFresh) $strTarget = '';
				
			$strTarget .= $strData;
			
			$this->bParseResourceIsFresh = false;				
		}
		
		return;
	}	
	
	
	/* Public: ****************************************/
	
	function DtI18Resource ( $strPathXmlRes , $strPhpLang = NULL ) { 
		// NOTE: "PhpLang" 's are in the form 'en_us.UTF-8', $strLang is just the 'us' part
		
		$a_PhpLangParts = array();

		if (!file_exists($strPathXmlRes)) { 
			// Fatal Error!
			$this = NULL;
			return;
		}

		$this->strPathXmlRes = &$strPathXmlRes;

		if (is_null($strPhpLang))
			$strPhpLang = &$_ENV["LANG"] ;
		
		preg_match('/^([^_]+)(?:_([^\\.]+))?(?:\\.(.+))?$/',$strPhpLang, &$a_PhpLangParts);

		$this->strLang 	= ($a_PhpLangParts[1]) ? $a_PhpLangParts[1] : DTI18_DEFAULTLANG;
		$this->strDialect	= ($a_PhpLangParts[2]) ? $a_PhpLangParts[2] : DTI18_DEFAULTDIALECT;
		$this->strCharset	= ($a_PhpLangParts[3]) ? $a_PhpLangParts[3] : DTI18_DEFAULTCHARSET;
	
		$this->DtErrHandler();
		
		$this->_errorClassAttach ( 'DtI18Resource', dirname(__FILE__).'/DtI18Resource-IRes.xml' );
		
		return ;
	}
	
	function &resourceGet ( /* ... */ ) {
		$this->_errorFlush();
		
		/*
			Let's clean up our object's communication variables a bit before we run the parser,
			probably not needed, but just in case there was a fatal error last time we were called
			this should be run nonetheless:
		*/
		$this->_resetParseMembers();
		
		/*
			First we'll tally and otherwise verify the resources requested. 
			Since this function takes many parameter combinations, this will be a little tricky...
		*/
		$a_Resources = func_get_args();
		
		if ( ( ( sizeof($a_Resources) ) == 1 ) && ( is_array($a_Resources[0]) ) )
			// Basically, if there was one parameter passed, and it was an array - then we assume that array is the list of resources needed.
			$a_Resources = $a_Resources[0];

		for ($i=0;$i<sizeof($a_Resources);$i++)
			if (is_string($a_Resources[$i]))
				$this->a_ParseResTargets[] = $a_Resources[$i];
			else
				$this->_errorThrow ( 
					'DtI18Resource', 
					DTI18_RESOURCEGET_BADPARM, 
					array(
						'TYPE' => gettype($a_Resources[$i]),
						'POSITION' => ($i+1) 
					)
				);
				
		if (!sizeof($this->a_ParseResTargets))
			return $this->_errorThrow ( 'DtI18Resource', DTI18_RESOURCEGET_NOTARGETS );

		// And now let's run the parser:
	   if (!$resXmlParser = @xml_parser_create('ISO-8859-1')) 
	   	return $this->_errorThrow ( 
	   		'DtI18Resource', 
	   		DTI18_RESOURCEGET_PARSERCREATEFAIL
	   	);
	   
      if (!@xml_set_object($resXmlParser, &$this)) 
      	return $this->_errorThrow ( 
	   		'DtI18Resource', 
	   		DTI18_RESOURCEGET_SETOBJECTFAIL,
	   		array( 
	   			'XML_ERRSTRING' 	=> xml_error_string( xml_get_error_code ( $resXmlParser ) ),
	   			'XML_ERRCODE' 		=> xml_get_error_code ( $resXmlParser )
	   		)
	   	);
            	
      if (!@xml_set_element_handler($resXmlParser, "_parsexmlStartElement", "_parsexmlEndElement"))
      	return $this->_errorThrow ( 
	   		'DtI18Resource', 
	   		DTI18_RESOURCEGET_SETELHANDLERFAIL,
	   		array( 
	   			'XML_ERRSTRING' 	=> xml_error_string( xml_get_error_code ( $resXmlParser ) ),
	   			'XML_ERRCODE' 		=> xml_get_error_code ( $resXmlParser )
	   		)
	   	);
      	
      if (!@xml_set_character_data_handler($resXmlParser, "_parsexmlCharacterData"))
      	return $this->_errorThrow ( 
	   		'DtI18Resource', 
	   		DTI18_RESOURCEGET_SETDATAHANDLERFAIL,
	   		array( 
	   			'XML_ERRSTRING' 	=> xml_error_string( xml_get_error_code ( $resXmlParser ) ),
	   			'XML_ERRCODE' 		=> xml_get_error_code ( $resXmlParser )
	   		)
	   	);
		
      if (!@xml_parse(
      	$resXmlParser,
      	@file_get_contents ($this->strPathXmlRes),
      	true
      ))
      	return $this->_errorThrow ( 
	   		'DtI18Resource', 
	   		DTI18_RESOURCEGET_XMLPARSEFAIL,
	   		array( 
	   			'XML_ERRSTRING' 	=> xml_error_string( xml_get_error_code ( $resXmlParser ) ),
	   			'XML_ERRCODE' 		=> xml_get_error_code ( $resXmlParser )
	   		)
	   	);
    
      if (! xml_parser_free ( $resXmlParser ) )
      	return $this->_errorThrow ( 
	   		'DtI18Resource', 
	   		DTI18_RESOURCEGET_PARSERFREEFAIL,
	   		array( 
	   			'XML_ERRSTRING' 	=> xml_error_string( xml_get_error_code ( $resXmlParser ) ),
	   			'XML_ERRCODE' 		=> xml_get_error_code ( $resXmlParser )
	   		)
	   	);
      
      // Now its time to return the matches that we found:
      $a_Ret = array();
      
      for ($i=0;$i<sizeof($this->a_ParseResTargets);$i++) {
      	$strCurTarget = &$this->a_ParseResTargets[$i];
      	
      	// Based on what we found, we'll be assigning either the exact match, the language default match, the failsafe, or the failsafe default - which itself may be null
      	$a_Ret[$strCurTarget] = 
      		($this->a_ParseTargetRequested[$strCurTarget])
      			? $this->a_ParseTargetRequested[$strCurTarget] 
      			: (
      				( $this->a_ParseTargetLangDefault[$strCurTarget] )
      					? $this->a_ParseTargetLangDefault[$strCurTarget] 
      					: (
      						( $this->a_ParseTargetFailsafe[$strCurTarget] )
      							? ( $this->a_ParseTargetFailsafe[$strCurTarget] )
      							: $this->a_ParseTargetFailsafeDefault[$strCurTarget]
      					)
      			);
      }
      
      // Let's free up any resident memory usage:
      $this->_resetParseMembers();
      
      return $a_Ret;
	}	
	
	
}
?>