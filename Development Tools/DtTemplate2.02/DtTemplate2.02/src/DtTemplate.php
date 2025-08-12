<?
/*
DtTemplate.php
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
	* A function that starts with an underscore is a private function
	
	Parse Notes:
	* We are escaping the values of properties in the DtElement tags. Meaning PROPERTY="Testing \"an\" Escape" gets stored as PROPERTY=>'Testing "an" Escape'
	* Any DtElement property value whose first character is a '/' and which is identified as a preg according to the '/adasd/xxx' pattern will be matched against the options as a preg. There is no way to escape this condition and have the value be interpreted literally.
	* Due to the relative complexity and cost/benefit prohibitiveness we don't support the following (fringe) constructs:
		* <% TAGNAME1 PARAMETER="<?echo 'funky code here';? >" %>
		* <% TAGNAME2 PARAMETER="<%funkyparameter here%>" %>
	* All the tag names and property names (THOUGH NOT propery values!) inside a <% %> element treated case-insensitively
	* No such thing as an encapsulation 'type' , so to speak.
	* THERE MAY NOT BE BOTH A STATIC AND COLLECTION DEFINED WITH THE SAME NAME IN A GIVEN CONTEXT. (This is pretty obvious really, but needs to be mentioned)
	* Note: Duplicate properties are allowed for any given on/collection element ! (because of != and ~ operator cases, more flexibility here...) And duplicate flags are harmless anyway (if not just a little weird)
	* Quickmerge is not able to execute php code in templates
	* Template-embedded php code will not be executed unless DtTemplate::bSupportsExecute is true
	* THERE IS BE NO CASE SENSITIVITY ON ELEMENT NAMES AND THEIR PROPERTY NAMES ! (Though property-value comparisons are case sensitive)
	* This seems obvious now but, The following template won't work as expected, if at all: <%INVALID%><%INVALID%><%/INVALID%> the outer 'INVALID' elements don't pair up, instead the right two 'INVALID' elements pair as an encapsulation, with the leftmost one being parsed as a static element (7-6-04)

CHANGELOG:
	(7-6-04)
		* There was a bug in the way that ON elements' preg Conditions were being tested, this was due to the use of $j instead of $j ni the condition preg. Now Fixed.
		* Created the testcases/encapsulation_bug_test.php to verify the above Note on encapsulation's (Its postfixed by this date)
		* Started with the <%ELEMENT !FLAG %> Code
			* Added a DTTEMPL_CONDTYPE_FLAGNOT define, I think this is better than a FLAGTYPE construction (similar to the PAIR's)
			* Added code to the _templateL1Parse that tests the first character of all flags for a '!', and if found, sets the FLAG type appropro
			* Added an entry for the DTTEMPL_CONDTYPE_FLAGNOT case in the highlight() condition map
			* Added DTTEMPL_HIGHLIGHT_CODE <%ON%> Cases that appropriately display a FLAGNOT's rendering with the prefix'ed '!' character
			* Noticed while in the highlight code that I had a stupid bug that deal with the $a_SequencesCur not being reset in-between iterations in the main chain switch statement. This caused some really weird output in the DTTEMPL_HIGHLIGHT_PARSE template under some circumstances
			* Adjusted the hightlight() CODE template so that operators are displayed in black
			* And lastly, I went ahead and added support for FLAGNOT inside the merge() function
	(7-7-04)
		* Created the DTTEMPL_SUPPORTS_EXECUTE directive, which tells a DtTemplate object whther or not to support executions. Defaults to false.
		* Incremented the official version to "2.02"
		* I threw dt_var_dump() inside this file, 
	(8-16-04)
		* Added the LGPL header and lesser.txt for publishing
		
TODO: 		

	(Long-term) :	
	
	* When Accession 1.4 comes out:
		* mergeQuick 's default behavior should not allow for loading from files, or execution.
	
	* A smarty-esque compile caching system. 
		* We should have a DTTEMPL_CACHE directory in which all templates get cached in a file according to the template's md5(), md5_file () checksum . A nice benefit to this approach is that we can thus compile templates without a strict source template file. (i.e. a string'd template passed to quickMerge)
			* We'll need to figure out how to prune this directory... and I think the best way would be to encourage cron jobs that purge entiries based on old atime(s)
		* We should also try to support the merge between a template and a dataset
			* Meaning, if a subsequent merge request is issued for a given template to the same dataset we can just stream back the cached merge. This can easily end up causing more performance issues than benefit though so we'll need to document this possibility thouroughly , and leave this option disabled by default.
		
	* A nice feature might be something like:
		[%FIELDS/FIELD NAME="sqlfieldname"%]
		instead of
		[%FIELDS NAME="sqlfieldname"%][%VALUE%][%/FIELDS%]		
		Though there might be better looking syntax here that'd be appropriate
		
		* 	The way to do this correctly is for COLLECTIONNAME/STATICNAME to reference the first element in the collection (which address the single-element collection possibility) 
			Alternatively , one could COLLECTIONNAME:2/STATICNAME which takes the second element's static from the collection (provided it existed) likewise :-1 should take the last
			You could even do a [%COLLECTIONNAME/STATICNAME OnRecord=2%] in the above case. But here's where it gets tricky. What if there is more than one match? I guess we just take the first...
			We're close here, but think about this more... how can we incorporate a '#' into this? are encapsulations supported in this manner? ...
			
		
	* Maybe we should use the  _stringCharToCitation() in L1? ...
	
	* Preg the supplied preg's to make sure the syntax is roughly correct
	
	* in the _merge , under the preg_matching, Prefix the preg_ with an '@', and see if we can figure out a good way of intercepting its output, and including it in our error message
		* If we have to overide the default error handler than just forget it. - but be sure to throw an error than communicates the subject and preg in its output
	
	* On the preg_split('/(<%.+?%>)/s'), adjust the preg so that we don't end up capturing the %> in <%TAG PROPETY="<%VALUE%>" %>(seeing that its enclosed...) though we should capture tag%> .	
	* templateCompiledGet()
		* We should be storing md5() sums in the return along with the data, this way we can verify the contents w/o going through the whole array
	* We could 'cache' the compiled files alongside the uncompiled counterparts somehow, so that when an already compiled file has been specififed we can check the timestamps and if its not what we had already cached, we can just recompile it and save the result.
		* I.e. 'Definition.xml' being stored as Definition.xml.compiled.... (THis is a big security risk though I'd think, particularly with the <? ? > type stuff having to be web-user writable...)
		* We should be storing md5() sums in the file along with the data, this way we can verify the contents w/o going through the whole array
	* Process the Num type's PARENT= in the compilation phase
		* We're not doing this now b/c I haven't fully determined its syntax.
		* We can probably splitit into two parts, collection_target_names & depth/level
	* Come up with a prettier highlight templates
		* Pretty- up the compilation template so that DTTEMPL_PI_ELSEQUENCES & DTTEMPL_PI_ELCONDCHAIN s don't use <UL> , but instead, maybe a (White) table ...
		* Perhaps we could use a better DTTEMPL_HIGHLIGHT_CODE template as the one we've got mangle's collections a tad (since the whitespace between collection directives is not recorded, we cannot render them in the highlight)
			* though I don't know how much trouble its worth to 'fix' this... I guess we could render it after a level 1 parse, but that's not really cool. the better alternative is to mirror the whitespace in front of the first entry in the collection, but this too gets complicated and is not really accurate.
	*(I'm Not sure that I want to do this after all) : Definition Parsing:
		* Complications arise when an application wants to add a property that wasn't defined in an xml file. we would need to add a function to append to the definition somehow. but I don't think this is even worth doing...
		* It would be good to allow for multiple definitions to exist in a single xml file. figure out how we can attach a name to the given DtTemplate that is used when pulling up the definition from the xml file
		* Remember to throw an error if we run into Reserved defined properties while parsing the definition
			*  by the name of "ONRECORD" or "ONEVERY" when parsing the definition file. These two are reserved properties.
			* Same goes for a tag name defined as '#'
		* Check for duplicate defined entities with the same name (element, static, etc)
		* We also need to acount for the 'alias' property of collections and static values. This is important. (probably a reference pointer or some such thing)
		* Constructor:
			* We should allow for an optional template 'name', whose only purpose really serves to load itself from the provided xml file..
			* We should allow for an optional definition to be passed in the contructor ! (And no-where else!) this should be an xml file only
		* A function &_definitionGenerate ($strTemplateFile) might be nice..
			This wouldn't be too hard to do , and could probably be quite useful ...
	* See what kind of slow-down (if any) we receive if all references '&' were removed
	* Perhaps we should be adding support for OnEvery and OnRecord in the <%ON%> Tags (throwing an error of course if this exhibited in the root node, this would only be valid inside a collection)
*/
define('DtTemplate.php',true);

if (!defined('DTTEMPL_SUPPORTS_EXECUTE')) define('DTTEMPL_SUPPORTS_EXECUTE',false);

/*************************************************************/
/* Includes                                                  */
/*************************************************************/
if (!defined('DtErrHandler.php')) require(dirname (__FILE__).'/DtErrHandler.php');

/*************************************************************/
/* Error Codes                                               */
/*************************************************************/

dterr_class('DtTemplate');

// These are phase 1 (parse) errors:
dterr_error('DTERR_TEMPL_PARSEFAILED', 				10);
dterr_error('DTERR_TEMPL_INVALIDPREDICATE', 			11);
dterr_error('DTERR_TEMPL_INVALIDCLOSEINDICATOR', 	12);
dterr_error('DTERR_TEMPL_INVALIDTAGFLAGS',			13);
dterr_error('DTERR_TEMPL_EXTRANEOUSONTAG', 			14);
dterr_error('DTERR_TEMPL_EXTRANEOUSNUMTAG', 			15);
dterr_error('DTERR_TEMPL_EXTRANEOUSCLOSERTAG', 		18);

// These are phase 2 (hierarchy) errors:
dterr_error('DTERR_TEMPL_UNMATCHEDONOPENER', 			20); // THis means that there's no </ON> for a detected on...
dterr_error('DTERR_TEMPL_ENCAPSULATIONMISSINGOPENER',	21);
dterr_error('DTERR_TEMPL_STATICTAGWITHCONDITIONS', 	22); // These are not allowed! Chances are that a collection attempted creation, but there was no </closer> for it below it

// Quick Merge errors:
dterr_error('DTERR_QMERGE_INVALIDDATASET', 				25);

// General 
dterr_error('DTERR_TEMPL_TEMPLATENOTYETDEFINED',	 	30); // A member function requires an object template , this template can't find one attached.
dterr_error('DTERR_PLOAD_NOGETCONTENTS',	 				31);


dterr_error('DTERR_MERGE_TYPEMISMATCHELSTATIC', 		40);
dterr_error('DTERR_MERGE_TYPEMISMATCHELCOLLECTION', 	41);
dterr_error('DTERR_MERGE_INVALIDNUMPARENT', 				42);
dterr_error('DTERR_MERGE_TYPEMISMATCHELNUM', 			43);

dterr_error('DTERR_EVAL_OBFATAL', 							50);
dterr_error('DTERR_EVAL_PARSERFAIL',						51);

// Highlight Errors:
dterr_error('DTERR_HIGHLIGHT_UNSUPPORTEDTEMPLATEPARAMETER',		60);

/*************************************************************/
/* Constants                                                 */
/*************************************************************/

// These are used as parameters to the highlight() function
define('DTTEMPL_HIGHLIGHT_CODE', 	1); // The default highlight mode - 'code' oriented
define('DTTEMPL_HIGHLIGHT_PARSE', 	2); // The parsed-structure highlight mode 

// These are parse level 1 constructs: 
define('DTTEMPL_PI_ELTYPE',		1);
	define('DTTEMPL_ELTYPE_RAW',				1);
	define('DTTEMPL_ELTYPE_ELEMENT',			2); // L1 Construct Only: Unsorted <% % > element type (could be either static or collection, but is of yet unknown)
	define('DTTEMPL_ELTYPE_ELSTATIC',		3);
	define('DTTEMPL_ELTYPE_ELCOLLECTION',	4);
	define('DTTEMPL_ELTYPE_ON',				5);
	define('DTTEMPL_ELTYPE_NUM',				6);
define('DTTEMPL_PI_DATA',			2);
define('DTTEMPL_PI_ELNAME',		3);

define('DTTEMPL_PI_PARENT',		4);
define('DTTEMPL_PI_CLOSER',		5); // Boolean value

define('DTTEMPL_PI_CONDITIONS',	6);
	define('DTTEMPL_CONDITION_TYPE',			1);
		define('DTTEMPL_CONDTYPE_ONEVERY',		1);
		define('DTTEMPL_CONDTYPE_ONRECORD',		2);
		define('DTTEMPL_CONDTYPE_PAIR',			3);
		define('DTTEMPL_CONDTYPE_FLAG',			4);
		define('DTTEMPL_CONDTYPE_FLAGNOT',		5);
	define('DTTEMPL_CONDITION_ON',			2);
	define('DTTEMPL_CONDITION_FLAGNAME',	3);
	define('DTTEMPL_CONDITION_PAIRTYPE',	4);
		define('DTTEMPL_CONDPTYPE_EQUALS',		1);
		define('DTTEMPL_CONDPTYPE_EQUALSNOT',	2);
		define('DTTEMPL_CONDPTYPE_PREG',			3);
		define('DTTEMPL_CONDPTYPE_PREGNOT',		4);
	define('DTTEMPL_CONDITION_PAIRNAME',	5);
	define('DTTEMPL_CONDITION_PAIRVAL',		6);

define('DTTEMPL_PI_NUMTYPE',		7);
	define('DTTEMPL_NUMTYPE_COUNT',			1);
	define('DTTEMPL_NUMTYPE_CUR',				2);

// These are parse level 2 constructs: 
define('DTTEMPL_PI_ONCHILDREN',	8);
define('DTTEMPL_PI_ELCONDCHAIN',	9);
define('DTTEMPL_PI_ELSEQUENCES',	10);


/*************************************************************/
/* Globals                                                   */
/*************************************************************/
function &dt_phpeval_sandbox ($strPage, &$strOutput) {
	// This function will execute code ($strPage), to the best of its ability , in a sandbox that will resemble a normal page context

	// This will load the global variable space into this function's variable space..
	extract($GLOBALS, EXTR_SKIP | EXTR_REFS);
	
	if (!ob_start ())
		return DTERR_EVAL_OBFATAL;
	
	$retEval = eval ( '?>'.$strPage.'<?' );
	
	$strOutput = ob_get_contents();
	
	if ($strOutput === false )
		return DTERR_EVAL_OBFATAL;
	
	if (!ob_end_clean ())
		return DTERR_EVAL_OBFATAL;
	
	if ($retEval === false) 
		return DTERR_EVAL_PARSERFAIL;
		
	return DTERR_OK;	
}

function &dt_var_dump($sVar) {
	/*
	This really isn't referenced anywhere below. But since I use it so regularly , I put it in here.
	Really its a great tool in general , and this seemed like the best place to keep it. Especially since its so small anyways...
	*/
	$strTemplate = <<<EOD
<%TYPE%>
<%ON TYPE~"/(string)|(array)/"%>(<%SIZE%>)<%/ON%>
<%ON TYPE!~"/(string)|(array)/"%><%ON VALUE%>(<%VALUE%>)<%/ON%><%/ON%>
<%ON TYPE="string"%>"<%VALUE%>"<%/ON%>
<%ON CONTENTS%>{<UL STYLE="margin-top: 0pt; margin-bottom: 0pt;"><%CONTENTS%><LI>[<%KEY%>] => <%VALUE%></LI><%/CONTENTS%></UL>}<%/ON%><BR>
EOD;
	
	$a_Ret = array();
	
	$a_Ret['TYPE'] = gettype($sVar);

	switch ($a_Ret['TYPE']) {
		case "boolean":
			$a_Ret['VALUE'] = ($sVar) ? 'true' : 'false';
			break;
		case "integer":
		case "double":			
			$a_Ret['VALUE'] = $sVar;
			break;
		case "string":
			$a_Ret['SIZE'] = strlen($sVar);
			$a_Ret['VALUE'] = htmlentities($sVar);
			break;
		case "array":
			$a_Ret['SIZE'] = sizeof($sVar);
			foreach (array_keys($sVar) as $strKey)
				$a_Ret['CONTENTS'][] = array(
					'KEY' => htmlentities($strKey),
					'VALUE' => dt_var_dump($sVar[$strKey])
				);
			break;
		case "object":
		case "unknown type":
		case "resource":
		case "NULL":
		case "user function":
		default:			
			// Unhandled... (TODO?)
			break;
	}	
	
	return DtTemplate::mergeQuick(&$strTemplate,$a_Ret);
	
}

/*************************************************************/
/* Code                                                      */
/*************************************************************/
class DtTemplate extends DtErrHandler {
	/* Public: ****************************************/
	var $bSupportsExecute = DTTEMPL_SUPPORTS_EXECUTE; // This controls whether or not we will support php execution for our _merge() output's contents
	
	/* Private: ***************************************/
	var $a_Template;

	/* Private: ***************************************/
	function &_parameterLoad ( $strFile ) {
		// In here we'll decide whether or not the parameter is a file, and if it is we'll fopen it and return the contents.
		// Maybe we should check to see if its an array and if so just return that...
		// TODO: If we are dealing with files, this is the place to handle cache(s) if and when they exist

		if (@file_exists($strFile)) {
			$strRet = @file_get_contents($strFile,false);
			
			if ($strRet === false)
				return DtErrHandler::_errorThrow ( 'DtTemplate', DTERR_PLOAD_NOGETCONTENTS, array( 'FILE' => $strFile ));
			
			return $strRet;
		}
		
		return $strFile;
	}

	function _stringCharToCitation( $strSource , $nCharacter ) {
		$a_Match = array();
		
		if ( preg_match ( '/^[<\\[]%.*%[>\\]]/' , substr ( $strSource, $nCharacter ), &$a_Match) );
			return $a_Match[0];
			
		return NULL;
	}
		
	function _stringCharToLineNumber ( $strSource , $nCharacter ) {
		// This will return the number of end-lines present before the $nCharacter
		return (preg_match_all ( '/[\\r]?\\n/s', substr ( $strSource, 0, $nCharacter),$a_Junk )+1);
	}
	
	function _stringCharToLineOffset ( $strSource , $nCharacter ) {
		// This will return the characters in between the $nCharacter and its 'leftmost' EOL 
		
		return (strlen(strrchr ( substr ( $strSource, 0, $nCharacter ), "\n" ))-1); // The \n here will match the EOL for both dos and UNIX templates
	}

	function &_templateL1Parse ( $strString, &$a_ParseRet, &$a_RetCharNums ) {
		/*
		This return value will look like:
		array (
			[0] => array (
				[DTTEMPL_PI_ELTYPE] 		=> DTTEMPL_ELTYPE_RAW
				[DTTEMPL_PI_DATA]		=> 'Raw Data Here' 
			),
			[1] => array (
				[DTTEMPL_PI_ELTYPE] 		=> DTTEMPL_ELTYPE_ON
				[DTTEMPL_PI_CLOSER] 	=> true|false
				[DTTEMPL_PI_CONDITIONS]		=> array( 
					0 => array(
						DTTEMPL_CONDITION_TYPE 		=> DTTEMPL_CONDTYPE_ONEVERY|DTTEMPL_CONDTYPE_ONRECORD|DTTEMPL_CONDTYPE_PAIR|DTTEMPL_CONDTYPE_FLAG
						DTTEMPL_CONDITION_ON			=> [int] // Only used if type is onevery|onrecord 
						DTTEMPL_CONDITION_FLAGNAME => [string] // Only used if type is flag
						DTTEMPL_CONDITION_PAIRTYPE	=> DTTEMPL_CONDPTYPE_EQUALS | DTTEMPL_CONDPTYPE_EQUALSNOT | DTTEMPL_CONDPTYPE_PREG | DTTEMPL_CONDPTYPE_PREGNOT						
						DTTEMPL_CONDITION_PAIRNAME => 'pairname',
						DTTEMPL_CONDITION_PAIRVAL	=> 'value'
					),
					[...] => array(...)
				)
			),
			[2] => array (
				[DTTEMPL_PI_ELTYPE] 		=> DTTEMPL_ELTYPE_NUM
				[DTTEMPL_PI_DATA]		=> 'Raw Data Here' 

				[DTTEMPL_PI_NUMTYPE]	=> DTTEMPL_NUMTYPE_COUNT|DTTEMPL_NUMTYPE_CUR
				[DTTEMPL_PI_PARENT] => 'Parent name here'
			),
			[3] => array (
				[DTTEMPL_PI_ELTYPE] 		=> DTTEMPL_ELTYPE_ELEMENT
				[DTTEMPL_PI_ELNAME]		=> 'ElementName'
				[DTTEMPL_PI_CLOSER] 	=> true|false
				[DTTEMPL_PI_CONDITIONS]		=> array( 
					0 => array(
						DTTEMPL_CONDITION_TYPE 		=> DTTEMPL_CONDTYPE_ONEVERY|DTTEMPL_CONDTYPE_ONRECORD|DTTEMPL_CONDTYPE_PAIR|DTTEMPL_CONDTYPE_FLAG
						DTTEMPL_CONDITION_ON			=> [int] // Only used if type is onevery|onrecord 
						DTTEMPL_CONDITION_FLAGNAME => [string] // Only used if type is flag
						DTTEMPL_CONDITION_PAIRTYPE	=> DTTEMPL_CONDPTYPE_EQUALS | DTTEMPL_CONDPTYPE_EQUALSNOT | DTTEMPL_CONDPTYPE_PREG | DTTEMPL_CONDPTYPE_PREGNOT						
						DTTEMPL_CONDITION_PAIRNAME => 'pairname',
						DTTEMPL_CONDITION_PAIRVAL	=> 'value'
					),
					[...] => array(...)
				)
								
			),
			[...] => array(...)
		)
		*/
		
		$a_ParseRet = array();
		$nParseRet 	= 0; // Theoretically, this should better ensure that $a_ParseRet and $a_RetCharNums are in sync towards the bottom, as well as run relatively fast...
		
		static $a_CondOpMap = array(
			// This is an old school Static-value look up map, we might use this below when processing element conditionals. Basically, it should be a fast way of doing thnigs
			'='	=> DTTEMPL_CONDPTYPE_EQUALS,
			'!='	=> DTTEMPL_CONDPTYPE_EQUALSNOT,
			'~'	=> DTTEMPL_CONDPTYPE_PREG,
			'!~'	=> DTTEMPL_CONDPTYPE_PREGNOT
		); 
		
		// This will split all the elements in the template, and capture the element (for later use) if found.
		$a_Elements = @preg_split (
			'/((?:<%.+?%>)|(?:\\[%.+?%\\]))/s', 
			&$strString, 
			-1 ,
			PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY | PREG_SPLIT_OFFSET_CAPTURE
		);
		
		if (!is_array($a_Elements)) 
			return DtErrHandler::_errorThrow ( 'DtTemplate', DTERR_TEMPL_PARSEFAILED );
		
		// Now we'll walk over the array and start to organize it a little better
		for ($i=0;$i<sizeof($a_Elements);$i++) {
			/*
			This is expected to retain the parsed result of whatever tag it is that
			we're currently operating on . This array will become attached to the main 
			$a_ParseRet above.
			*/
			$a_CurElementRet = array();

			$a_ElementCur = array();
			if ( preg_match(
				// This matches the tag. And if present: its closer (/), its OnRecord/Onevery Type & predicate, and a property/flag block 
				'/^'.( ($a_Elements[$i][0][0]=='<') ? '<' : '[' ).'%[\\s]*([\\/]?)[\\s]*([^=\\s|:]+)([|:]?)([\\d]?)[\\s]*'.
				'(.*)'.
				'%'.( ($a_Elements[$i][0][0]=='<') ? '>' : ']' ).'$/s',
				&$a_Elements[$i][0],
				$a_ElementCur
				)
			) {
				/* Match found! this means we hit a non-'RAW' Type. */

				// Let's make it a little easier to work with our regex parts:
				$bIsCloser 					= ($a_ElementCur[1]) ? true : false;
				$strElementName 			= strtoupper(&$a_ElementCur[2]);
				$chrCollectionIndicator = &$a_ElementCur[3];
				$chrCollectionPredicate = &$a_ElementCur[4];
				$strPropertyValuePairs 	= &$a_ElementCur[5];
				
				$a_Props = array();
				$nElementProperties=0;
				if ( (!$bIsCloser) && ($strPropertyValuePairs) )
					$nElementProperties = preg_match_all(
						// THis one does everything we need, including the removal of enclosing ' " 's on property values.
						// The drawback is that the returned array is a little goofy, and longer than I'd prefer . 
						// But whatever - This is the fastest algorithm yet!
						
						// Here's the property-name:
						'/[\\s]*([^\\s][^=\!\~\\s]+)'.
						// Here's the operator (Its Optional):
						'(?:[\\s]*([!]?[=~])[\\s]*'.
						// Here's the value (Its Optional):
						'(?:'.
							// This will capture : ="\"escaped\" values" :
							'(?:"((?:(?<=\\\\)"|(?:[^"]*))+)")'.'|'.
							// This will capture : ='\'escaped\' values' :
							"(?:'((?:(?<=\\\\)'|(?:[^']*))+)')".'|'.
							// This will capture : =unenclosedvalues
							'([^\\s]+)'.
						')?'.
						')?'.
						'[\\s]*/s',
						&$strPropertyValuePairs,
						&$a_Props
					);				
				
				// We may or may not use this below:
				$a_CurConditionsRet = array();
				
				switch ($strElementName) {
					case 'ON':
						if ($chrCollectionIndicator) 
							return DtErrHandler::_errorThrow ( 'DtTemplate', DTERR_TEMPL_EXTRANEOUSONTAG, array( 'LINE'=> DtTemplate::_stringCharToLineNumber(&$strString, $a_Elements[$i][1]) , 'CHARACTER' => $a_Elements[$i][1], 'OFFSET' => DtTemplate::_stringCharToLineOffset(&$strString, $a_Elements[$i][1]) , 'CITATION' => $a_ElementCur[0] ));
							
						if (!$bIsCloser) {

							for ($j=0;$j<$nElementProperties;$j++) {
	
								$strProperty 	= strtoupper(&$a_Props[1][$j]);
								$strOp 			= &$a_Props[2][$j];
								// This is a very strange construct, I know. But its fast, and works. Basically, it takes whatever value matched out of the three locations and stripslashes any match between ="" or ='' (but not =val)
								$strValue = ($a_Props[3][$j]) ? stripslashes($a_Props[3][$j]) : ( ($a_Props[4][$j]) ? stripslashes($a_Props[4][$j]) : ( ($a_Props[5][$j]) ? $a_Props[5][$j] : NULL) ) ;
								
								if ( $strOp ) {
									$a_CurConditionsRet[] = array(
										DTTEMPL_CONDITION_TYPE		=> DTTEMPL_CONDTYPE_PAIR,
										DTTEMPL_CONDITION_PAIRNAME => $strProperty,
										DTTEMPL_CONDITION_PAIRVAL 	=> $strValue, // References fuck this up.
										DTTEMPL_CONDITION_PAIRTYPE	=> $a_CondOpMap[$strOp] 
									);
								} 
								else {
									// This means our directive is a boolean flag (i.e. html's CHECKED flag on a RADIOGROUP tag)
									
									if ($strProperty[0] == '!')
										$a_CurConditionsRet[] = array(
											DTTEMPL_CONDITION_TYPE		=> DTTEMPL_CONDTYPE_FLAGNOT,
											DTTEMPL_CONDITION_FLAGNAME	=> substr($strProperty, 1) // This substr is everything to the right of the '!'
										);									
									else
										$a_CurConditionsRet[] = array(
											DTTEMPL_CONDITION_TYPE		=> DTTEMPL_CONDTYPE_FLAG,
											DTTEMPL_CONDITION_FLAGNAME	=> $strProperty
										);
								}
							}
							
						} 
						else if ($strPropertyValuePairs)
							return DtErrHandler::_errorThrow ( 'DtTemplate', DTERR_TEMPL_EXTRANEOUSCLOSERTAG, array( 'LINE'=> DtTemplate::_stringCharToLineNumber(&$strString, $a_Elements[$i][1]) , 'CHARACTER' => $a_Elements[$i][1], 'OFFSET' => DtTemplate::_stringCharToLineOffset(&$strString, $a_Elements[$i][1]) , 'CITATION' => $a_ElementCur[0] ));

						$a_CurElementRet[DTTEMPL_PI_ELTYPE] = DTTEMPL_ELTYPE_ON;
						
						$a_CurElementRet[DTTEMPL_PI_CLOSER] = $bIsCloser;

						if (sizeof(&$a_CurConditionsRet))
							$a_CurElementRet[DTTEMPL_PI_CONDITIONS] = $a_CurConditionsRet; // References fuck this up.
						
						break;
					case '#':
						if ($chrCollectionIndicator) 
							return DtErrHandler::_errorThrow ( 'DtTemplate', DTERR_TEMPL_EXTRANEOUSNUMTAG, array( 'LINE'=> DtTemplate::_stringCharToLineNumber(&$strString, $a_Elements[$i][1]) , 'CHARACTER' => $a_Elements[$i][1], 'OFFSET' => DtTemplate::_stringCharToLineOffset(&$strString, $a_Elements[$i][1]) , 'CITATION' => $a_ElementCur[0] ));
							
						if ($bIsCloser)
							return DtErrHandler::_errorThrow ( 'DtTemplate', DTERR_TEMPL_INVALIDCLOSEINDICATOR, array( 'LINE'=> DtTemplate::_stringCharToLineNumber(&$strString, $a_Elements[$i][1]) , 'OFFSET' => DtTemplate::_stringCharToLineOffset(&$strString, $a_Elements[$i][1]) , 'CHARACTER' => $a_Elements[$i][1],'CITATION'=>$a_ElementCur[0] ));
				
						for ($j=0;$j<$nElementProperties;$j++) {

							$strProperty 	= strtoupper(&$a_Props[1][$j]);
							$strOp 			= &$a_Props[2][$j];
							// This is a very strange construct, I know. But its fast, and works. Basically, it takes whatever value matched out of the three locations and stripslashes any match between ="" or ='' (but not =val)
							$strValue = ($a_Props[3][$j]) ? stripslashes($a_Props[3][$j]) : ( ($a_Props[4][$j]) ? stripslashes($a_Props[4][$j]) : ( ($a_Props[5][$j]) ? $a_Props[5][$j] : NULL) ) ;
							
							if ( !$strOp ) 
								return DtErrHandler::_errorThrow ( 'DtTemplate', DTERR_TEMPL_INVALIDTAGFLAGS, array( 'LINE'=> DtTemplate::_stringCharToLineNumber(&$strString, $a_Elements[$i][1]) , 'OFFSET' => DtTemplate::_stringCharToLineOffset(&$strString, $a_Elements[$i][1]) , 'CHARACTER' => $a_Elements[$i][1], 'CITATION' => $strProperty )); 

							switch ($strProperty) {
								case 'TYPE':
									if (isset($a_CurElementRet[DTTEMPL_PI_NUMTYPE])) 
										return DtErrHandler::_errorThrow ( 'DtTemplate', DTERR_TEMPL_EXTRANEOUSNUMTAG, array( 'LINE' => DtTemplate::_stringCharToLineNumber(&$strString, $a_Elements[$i][1]) , 'OFFSET' => DtTemplate::_stringCharToLineOffset(&$strString, $a_Elements[$i][1]) , 'CHARACTER'=> $a_Elements[$i][1], 'CITATION' => $strProperty ));
										
									switch(strtoupper($strValue)) {
										case 'COUNT':
											$a_CurElementRet[DTTEMPL_PI_NUMTYPE] = DTTEMPL_NUMTYPE_COUNT;
											break;
										case 'CUR':
											$a_CurElementRet[DTTEMPL_PI_NUMTYPE] = DTTEMPL_NUMTYPE_CUR;
											break;
										default:
											if ($strValue)
												// If its null, we just ignore this paramter, which will default to DTTEMPL_NUMTYPE_CUR inside _merge()
												return DtErrHandler::_errorThrow ( 'DtTemplate', DTERR_TEMPL_EXTRANEOUSNUMTAG, array( 'LINE' => DtTemplate::_stringCharToLineNumber(&$strString, $a_Elements[$i][1]) , 'OFFSET' => DtTemplate::_stringCharToLineOffset(&$strString, $a_Elements[$i][1]) , 'CHARACTER' => $a_Elements[$i][1], 'CITATION' => $strProperty ));
											break;	
									}
									break;
								case 'PARENT' :
									if (isset($a_CurElementRet[DTTEMPL_PI_PARENT])) 
										return DtErrHandler::_errorThrow ( 'DtTemplate', DTERR_TEMPL_EXTRANEOUSNUMTAG, array( 'LINE' => DtTemplate::_stringCharToLineNumber(&$strString, $a_Elements[$i][1]) , 'OFFSET' => DtTemplate::_stringCharToLineOffset(&$strString, $a_Elements[$i][1]) , 'CHARACTER' => $a_Elements[$i][1], 'CITATION' => $strProperty ));
									
									// We should be parsing this thing a little better... let's separate the level here, and place it into two parts, a level and a name
									
									$a_CurElementRet[DTTEMPL_PI_PARENT] = $strValue;
									break;
								default:
									// <%#% > Tag types don't support anything but parent='' and type=''
									return DtErrHandler::_errorThrow ( 'DtTemplate', DTERR_TEMPL_EXTRANEOUSNUMTAG, array( 'LINE' => DtTemplate::_stringCharToLineNumber(&$strString, $a_Elements[$i][1]) , 'OFFSET' => DtTemplate::_stringCharToLineOffset(&$strString, $a_Elements[$i][1]) , 'CHARACTER' => $a_Elements[$i][1], 'CITATION' => $strProperty ));;
									break;
							}
						}
						
						$a_CurElementRet[DTTEMPL_PI_ELTYPE] = DTTEMPL_ELTYPE_NUM;		
						break;
					default:
						/*	This means we're either encapsulating or static. */
						
						if (!$bIsCloser) {
							// Do we have a TAGNAME:# or TAGNAME|# ?
							if ($chrCollectionIndicator) {
								if (!$nOn = intval(&$chrCollectionPredicate)) 
									return DtErrHandler::_errorThrow ( 'DtTemplate', DTERR_TEMPL_INVALIDPREDICATE, array( 'LINE' => DtTemplate::_stringCharToLineNumber(&$strString, $a_Elements[$i][1]) , 'OFFSET' => DtTemplate::_stringCharToLineOffset(&$strString, $a_Elements[$i][1]) , 'CHARACTER' => $a_Elements[$i][1], 'CITATION' => $strElementName ));
								
								$a_CurConditionsRet[] = array(
									DTTEMPL_CONDITION_TYPE	=> ($chrCollectionIndicator == '|') ? DTTEMPL_CONDTYPE_ONEVERY : DTTEMPL_CONDTYPE_ONRECORD,
									DTTEMPL_CONDITION_ON 	=> $nOn 
								);
							}
				
							for ($j=0;$j<$nElementProperties;$j++) {
	
								$strProperty 	= strtoupper(&$a_Props[1][$j]);
								$strOp 			= &$a_Props[2][$j];
								// This is a very strange construct, I know. But its fast, and works. Basically, it takes whatever value matched out of the three locations and stripslashes any match between ="" or ='' (but not =val)
								$strValue = ($a_Props[3][$j]) ? stripslashes($a_Props[3][$j]) : ( ($a_Props[4][$j]) ? stripslashes($a_Props[4][$j]) : ( ($a_Props[5][$j]) ? $a_Props[5][$j] : NULL) ) ;
								
								switch ($strProperty) {
									case 'ONEVERY':
										if (!$nOn = intval(&$strValue)) 
											return DtErrHandler::_errorThrow ( 'DtTemplate', DTERR_TEMPL_INVALIDPREDICATE, array( 'LINE' => DtTemplate::_stringCharToLineNumber(&$strString, $a_Elements[$i][1]) , 'OFFSET' => DtTemplate::_stringCharToLineOffset(&$strString, $a_Elements[$i][1]) , 'CHARACTER' => $a_Elements[$i][1], 'CITATION' => $strElementName ));
								
										$a_CurConditionsRet[] = array(
											DTTEMPL_CONDITION_TYPE	=> DTTEMPL_CONDTYPE_ONEVERY,
											DTTEMPL_CONDITION_ON 	=> $nOn 
										);
										break;
									case 'ONRECORD' :
										if (!$nOn = intval(&$strValue))
											return DtErrHandler::_errorThrow ( 'DtTemplate', DTERR_TEMPL_INVALIDPREDICATE, array( 'LINE' => DtTemplate::_stringCharToLineNumber(&$strString, $a_Elements[$i][1]) , 'OFFSET' => DtTemplate::_stringCharToLineOffset(&$strString, $a_Elements[$i][1]) , 'CHARACTER'=>$a_Elements[$i][1], 'CITATION' => $strElementName ));
								
										$a_CurConditionsRet[] = array(
											DTTEMPL_CONDITION_TYPE	=> DTTEMPL_CONDTYPE_ONRECORD,
											DTTEMPL_CONDITION_ON 	=> $nOn 
										);
										break;
									default:
										if ( !$strOp ) {
											if ($strProperty[0] == '!')
												$a_CurConditionsRet[] = array(
													DTTEMPL_CONDITION_TYPE		=> DTTEMPL_CONDTYPE_FLAGNOT,
													DTTEMPL_CONDITION_FLAGNAME	=> substr($strProperty, 1) // This substr is everything to the right of the '!'
												);									
											else
												$a_CurConditionsRet[] = array(
													DTTEMPL_CONDITION_TYPE		=> DTTEMPL_CONDTYPE_FLAG,
													DTTEMPL_CONDITION_FLAGNAME	=> $strProperty
												);
										}
										else
											$a_CurConditionsRet[] = array(
												DTTEMPL_CONDITION_TYPE		=> DTTEMPL_CONDTYPE_PAIR,
												DTTEMPL_CONDITION_PAIRNAME => $strProperty,
												DTTEMPL_CONDITION_PAIRVAL 	=> $strValue,
												DTTEMPL_CONDITION_PAIRTYPE	=> $a_CondOpMap[$strOp] 
											);
										break;
								}
							}
						}
						else if ($strPropertyValuePairs)
							return DtErrHandler::_errorThrow ( 'DtTemplate', DTERR_TEMPL_EXTRANEOUSCLOSERTAG, array( 'LINE'=> DtTemplate::_stringCharToLineNumber(&$strString, $a_Elements[$i][1]) , 'CHARACTER' => $a_Elements[$i][1], 'OFFSET' => DtTemplate::_stringCharToLineOffset(&$strString, $a_Elements[$i][1]) , 'CITATION' => $a_ElementCur[0] ));
							
						$a_CurElementRet[DTTEMPL_PI_ELTYPE] = DTTEMPL_ELTYPE_ELEMENT;
						$a_CurElementRet[DTTEMPL_PI_ELNAME] = $strElementName;
						
						$a_CurElementRet[DTTEMPL_PI_CLOSER] = $bIsCloser;

						if (sizeof(&$a_CurConditionsRet))
							$a_CurElementRet[DTTEMPL_PI_CONDITIONS] = $a_CurConditionsRet;  // References fuck this up.
						break;
				}				
			}
			else 
				// This is just Raw data, not much for us to do here.
				$a_CurElementRet = array(
					DTTEMPL_PI_ELTYPE => DTTEMPL_ELTYPE_RAW, 
					DTTEMPL_PI_DATA => &$a_Elements[$i][0]
				);
			
			$a_ParseRet[$nParseRet] = $a_CurElementRet;
			$a_RetCharNums[$nParseRet++] = $a_Elements[$i][1];
		}

		return DTERR_OK;
	}	
	
	function &_templateL2Parse ( $a_ParseData, $a_ParseChars, $strTemplate, /*&*/ $i = NULL ) {
		/*
			NOTES:
			* 	This function is recursive, So stand back!		
			* $a_ParseChars and $strTemplate ae only used to report compilation errors, this ugly hack was needed to properly report line numbers, offsets, etc...
			* OK, we'll be doing this bottom to top!
				* THis is so that we get notice of the '/' tags , and can appropriate handle the hierarchial elements from the static ones. (Pretty smart no?)
			* Take note that the $i is expected to be the position in the $a_ParseData array to 'Start' from (remember that we're going from bottom to top though..)
				It should be a reference to $i from the main for loop, and this way as the array is traversed - We can be sure we've covered every element in the main array.
		*/

		/*
		This return value will look like:
		array (
			[0] => array (
				[DTTEMPL_PI_ELTYPE] 		=> DTTEMPL_ELTYPE_RAW
				[DTTEMPL_PI_DATA]		=> 'Raw Data Here' 
			),
			[1] => array (
				[DTTEMPL_PI_ELTYPE] 		=> DTTEMPL_ELTYPE_ON
				[DTTEMPL_PI_CONDITIONS]		=> array( 
					0 => array(
						DTTEMPL_CONDITION_TYPE 		=> DTTEMPL_CONDTYPE_ONEVERY|DTTEMPL_CONDTYPE_ONRECORD|DTTEMPL_CONDTYPE_PAIR|DTTEMPL_CONDTYPE_FLAG
						DTTEMPL_CONDITION_ON			=> [int] // Only used if type is onevery|onrecord 
						DTTEMPL_CONDITION_FLAGNAME => [string] // Only used if type is flag
						DTTEMPL_CONDITION_PAIRTYPE	=> DTTEMPL_CONDPTYPE_EQUALS | DTTEMPL_CONDPTYPE_EQUALSNOT | DTTEMPL_CONDPTYPE_PREG | DTTEMPL_CONDPTYPE_PREGNOT						
						DTTEMPL_CONDITION_PAIRNAME => 'pairname',
						DTTEMPL_CONDITION_PAIRVAL	=> 'value'
					),
					[...] => array(...)
				)
***			[DTTEMPL_PI_ONCHILDREN] => array( /* Root Node(s) sequences repeat here.* /)
			),
			[2] => array (
				[DTTEMPL_PI_ELTYPE] 		=> DTTEMPL_ELTYPE_NUM
				[DTTEMPL_PI_DATA]		=> 'Raw Data Here' 

				[DTTEMPL_PI_NUMTYPE]	=> DTTEMPL_NUMTYPE_COUNT|DTTEMPL_NUMTYPE_CUR
				[DTTEMPL_PI_PARENT] => 'Parent name here'
			),
			[3] => array (
				[DTTEMPL_PI_ELTYPE] 		=> DTTEMPL_ELTYPE_ELEMENT
				[DTTEMPL_PI_ELNAME]		=> 'ElementName'
***			[DTTEMPL_PI_ELCONDCHAIN] => array(
***				0 => array(
***					DTTEMPL_CONDITION_TYPE 		=> DTTEMPL_CONDTYPE_ONEVERY|DTTEMPL_CONDTYPE_ONRECORD|DTTEMPL_CONDTYPE_PAIR|DTTEMPL_CONDTYPE_FLAG
***					DTTEMPL_CONDITION_ON			=> [int] // Only used if type is onevery|onrecord 
***					DTTEMPL_CONDITION_FLAGNAME => [string] // Only used if type is flag
***					DTTEMPL_CONDITION_PAIRTYPE	=> DTTEMPL_CONDPTYPE_EQUALS | DTTEMPL_CONDPTYPE_EQUALSNOT | DTTEMPL_CONDPTYPE_PREG | DTTEMPL_CONDPTYPE_PREGNOT						
***					DTTEMPL_CONDITION_PAIRNAME => 'pairname',
***					DTTEMPL_CONDITION_PAIRVAL	=> 'value'
***				),
***				[...] => array(...)
				),
***			[DTTEMPL_PI_ELSEQUENCES] => array(
***				[0] => array(/* Root Node(s) sequences repeat here.* /)
***			)
			),
			[...] => array(...)
		)

		*/
		
		$nCurParent = NULL;
		$a_Ret = array();
		
		// If we don't do this, then $i will be reset every time we recurse into this function
		if (is_null($i)) $i=(sizeof($a_ParseData)-1);
		else {
			// This means, we've arrived via a recursion, so let's set a handy reference to our parent just in case its needed.
			$nCurParent = $i;
			// Furthermore, we should (advance) $i even if this is a recursed entrypoint, otherwise $i will retain its exiting value upon the loop's first iteration
			$i--;
		}
		
		while ($i>=0) {
			$a_RetCur = array();
						
			switch($a_ParseData[$i][DTTEMPL_PI_ELTYPE]) {
				case DTTEMPL_ELTYPE_RAW:
					// These are easy enough ...
					$a_RetCur = &$a_ParseData[$i];
					$i--;
					break;
				case DTTEMPL_ELTYPE_ELEMENT:
					/*
						This is where it gets really hairy, just pay close attention and follow the comments...
					*/
					if (!$a_ParseData[$i][DTTEMPL_PI_CLOSER]) {
						
						if ( $a_ParseData[$nCurParent][DTTEMPL_PI_ELNAME] === $a_ParseData[$i][DTTEMPL_PI_ELNAME] ) 
							// We have hit our parent's complimentary opener tag, time to return.
							return $a_Ret;
						else {
							// Its a static-value  tag :

							// Let's make sure that there are no conditions defined here. This is not allowed for static types:
							// This will incidently catch collection openers that have no closing tag
							if (sizeof($a_ParseData[$i][DTTEMPL_PI_CONDITIONS])) 
								if ($a_ParseData[$nCurParent][DTTEMPL_PI_ELTYPE] === DTTEMPL_ELTYPE_ON)
									return DtErrHandler::_errorThrow ( 'DtTemplate', DTERR_TEMPL_ENCAPSULATIONMISSINGOPENER, array( 'LINE' => DtTemplate::_stringCharToLineNumber(&$strTemplate, $a_ParseChars[$i]) , 'OFFSET' => DtTemplate::_stringCharToLineOffset(&$strTemplate, $a_ParseChars[$i]) , 'CHARACTER'=>$a_ParseChars[$i], 'CITATION' => DtTemplate::_stringCharToCitation(&$strTemplate, $a_ParseChars[$i]) ) );
								else
									// Either a tag was detected w/o a closer, or static tag had conditions
									return DtErrHandler::_errorThrow ( 'DtTemplate', DTERR_TEMPL_STATICTAGWITHCONDITIONS, array( 'LINE' => DtTemplate::_stringCharToLineNumber(&$strTemplate, $a_ParseChars[$i]) , 'OFFSET' => DtTemplate::_stringCharToLineOffset(&$strTemplate, $a_ParseChars[$i]) , 'CHARACTER'=>$a_ParseChars[$i], 'CITATION' => DtTemplate::_stringCharToCitation(&$strTemplate, $a_ParseChars[$i]) ) );
							
							// Easy enough, just add to the collection:
							$a_RetCur = array (
								DTTEMPL_PI_ELTYPE => DTTEMPL_ELTYPE_ELSTATIC,
								DTTEMPL_PI_ELNAME => &$a_ParseData[$i][DTTEMPL_PI_ELNAME]								
							);
						}
						$i--;
					} 
					else {					
						// Since we're a closer, its time to recurse!:
						
						// These hold our captures, before their added to $a_RetCur
						$a_CurSequences	= array();
						$a_ConditionChain	= array();
						
						$strSequenceName = &$a_ParseData[$i][DTTEMPL_PI_ELNAME]; // The first encountered sequence
						$j = 0; // This is the currently 'active' sequence number , used for maintaing $a_CurSequences & $a_ConditionChain
						
						/**********************/
						/*
							This is how we attach sequences of Collection types together ... very tricky indeed
						*/
						do {
							$a_CurSequences[$j] = &DtTemplate::_templateL2Parse(&$a_ParseData, &$a_ParseChars, &$strTemplate, &$i);

							if (!is_array($a_CurSequences[$j])) return $a_CurSequences[$j];
							
							$a_ConditionChain[$j] = $a_ParseData[$i][DTTEMPL_PI_CONDITIONS];
							
							$j++;
							
							// We have to make sure we're not advancing the pointer below '0'
							if ($i>=0) $i--; else break;
						}
						while (
							(
								// Either this next element is part of the collection
								($a_ParseData[$i][DTTEMPL_PI_ELTYPE] === DTTEMPL_ELTYPE_ELEMENT) &&
								($a_ParseData[$i][DTTEMPL_PI_ELNAME] === $strSequenceName)
							) ||
							(
								// Or the next element, after whitespace, is part of the collection
								($a_ParseData[($i-1)][DTTEMPL_PI_ELTYPE] === DTTEMPL_ELTYPE_ELEMENT) && 
								($a_ParseData[($i-1)][DTTEMPL_PI_ELNAME]  === $strSequenceName ) &&
								($a_ParseData[$i][DTTEMPL_PI_ELTYPE] === DTTEMPL_ELTYPE_RAW) &&
								( preg_match('/^[\\s]+$/s',$a_ParseData[$i][DTTEMPL_PI_DATA]) ) &&
								($i--) // This is a cheap trick, but it'll advance $i provided the above 4 conditions are satisfied. 
							)
						) ;
						
						/**********************/

						$a_RetCur = array(
							DTTEMPL_PI_ELTYPE => DTTEMPL_ELTYPE_ELCOLLECTION,
							DTTEMPL_PI_ELNAME => &$strSequenceName, 
							DTTEMPL_PI_ELCONDCHAIN => $a_ConditionChain,  // References fuck this up.
							DTTEMPL_PI_ELSEQUENCES => $a_CurSequences 	 // References fuck this up.
						);	
					}
					break;					
				case DTTEMPL_ELTYPE_NUM:
					// Same as the DATA type - no problems really.
					$a_RetCur = &$a_ParseData[$i];
					$i--;
					break;
				case DTTEMPL_ELTYPE_ON:
					
					// If we're not a closer, we'll want to return to our 'parent'
					if (!$a_ParseData[$i][DTTEMPL_PI_CLOSER]) {

						// Let's make sure we're not at the root level:
						if (is_null($a_ParseData[$nCurParent]))
							return DtErrHandler::_errorThrow ( 'DtTemplate', DTERR_TEMPL_UNMATCHEDONOPENER , array( 'LINE' => DtTemplate::_stringCharToLineNumber(&$strTemplate, $a_ParseChars[$i]) , 'OFFSET' => DtTemplate::_stringCharToLineOffset(&$strTemplate, $a_ParseChars[$i]) , 'CHARACTER'=>$a_ParseChars[$i], 'CITATION' => DtTemplate::_stringCharToCitation(&$strTemplate, $a_ParseChars[$i]) ));

						// Let's make sure that the parent is in fact an on tag:
						if ($a_ParseData[$nCurParent][DTTEMPL_PI_ELTYPE] !== DTTEMPL_ELTYPE_ON)
							return DtErrHandler::_errorThrow ( 'DtTemplate', DTERR_TEMPL_UNMATCHEDONOPENER , array( 'LINE' => DtTemplate::_stringCharToLineNumber(&$strTemplate, $a_ParseChars[$i]) , 'OFFSET' => DtTemplate::_stringCharToLineOffset(&$strTemplate, $a_ParseChars[$i]) , 'CHARACTER'=>$a_ParseChars[$i], 'CITATION' => DtTemplate::_stringCharToCitation(&$strTemplate, $a_ParseChars[$i]) ));
							
						return $a_Ret;
					}
					
					// Since we're a closer, we'll recurse and find our children:
					$a_RetChildren = &DtTemplate::_templateL2Parse(&$a_ParseData, &$a_ParseChars, &$strTemplate, &$i);
					if (!is_array($a_RetChildren)) return $a_RetChildren;

					/*
						* $a_ParseData[$i] is the opening tag that corresponds with the orignal closer, before we recursed above...
					 	* What we're actually doing here is 'disreagarding' the closer, and instead setting up the opener,
					 	  whose children are all the tags that were found in between the opener and closer.
					*/
					
					$a_RetCur = array(
						DTTEMPL_PI_ELTYPE 		=> DTTEMPL_ELTYPE_ON,
						DTTEMPL_PI_CONDITIONS 	=> &$a_ParseData[$i][DTTEMPL_PI_CONDITIONS],
						DTTEMPL_PI_ONCHILDREN	=> &$a_RetChildren
					);
	
					$i--;
					break;
				default:
					// We should never end up in here. *But* in case we do.... we don't want this function to loop infinitely...
					$i--;
					break;
			}
			
			$a_Ret[] = $a_RetCur;
			
		}
		
		if ( ($i<0) && !is_null($a_ParseData[$nCurParent]) )
			// This will make sure we fail if a </closing> tag was detected and we never found its corresponding 'opener'
			return DtErrHandler::_errorThrow ( 'DtTemplate', DTERR_TEMPL_ENCAPSULATIONMISSINGOPENER , array( 'LINE' => DtTemplate::_stringCharToLineNumber(&$strTemplate, $a_ParseChars[$nCurParent]) , 'OFFSET' => DtTemplate::_stringCharToLineOffset(&$strTemplate, $a_ParseChars[$nCurParent]) , 'CHARACTER'=>$a_ParseChars[$nCurParent], 'CITATION' => DtTemplate::_stringCharToCitation(&$strTemplate, $a_ParseChars[$nCurParent]) ) );
		
		return $a_Ret;
	}

	function &_merge ( $a_Template = NULL, $a_Dataset = NULL, $nDepth = 0 ) {
		
		$strRet = '';
		
		// This will be used by recursions 
		static $a_ParentStack = NULL;
		
		// The stack of current collections being considered. Keep in mind that we'll have one of these for every $nDepth 
		static $a_CollectionSizesStack = NULL;
		
		// The $a_CollectionStack 's I's stack. this is needed by the num type , and will hold the current I level for any given level in the collection stack
		static $a_CollectionIsStack = NULL;
				
		if ($a_Dataset) {
			// This is a root-node _merge() call, so let's setup the stack(s): 
			
			/*
			Strictly speaking, we don't need $nDepth - but it does speed the process up a bit so I use it to indicate the current position in the stack
			*/
			$a_ParentStack 			= array();
			$a_ParentStack[$nDepth] = array_change_key_case ( &$a_Dataset, CASE_UPPER ) ;

			// The root stack has no size or i, so we do this.
			$a_CollectionSizesStack				= array();
			$a_CollectionSizesStack[$nDepth]	= NULL;
			
			$a_CollectionIsStack 			= array();
			$a_CollectionIsStack[$nDepth]	= NULL;
		}
		
		for ($i=(sizeof($a_Template)-1);$i>=0;$i--) 
			switch ($a_Template[$i][DTTEMPL_PI_ELTYPE]) {
				case DTTEMPL_ELTYPE_RAW:
					$strRet .= $a_Template[$i][DTTEMPL_PI_DATA];
					break;
				case DTTEMPL_ELTYPE_ELSTATIC:
					// This little block will grab our value from the current dataset - or the parent stack if its not found at the current level.
					// The reason that this one is a little different than the rest is b/c it has to deal with scalar values, any of which may potentially be int(0).
					// If we use the alternative syntax here, the condition of 0 won't be a qualifying exit point for the loop , thus resistingits legitimate rendering.
					for ($j=$nDepth;$j>=0;$j--) {
						$strVal = &$a_ParentStack[$j][$a_Template[$i][DTTEMPL_PI_ELNAME]];
						if (!is_null($strVal)) break;
					}
						
					// If nothing was found in the dataset:
					if (is_null($strVal)) break; 
					
					// And make sure we don't have an array or something weird here:
					if (!is_scalar($strVal)) {
						// Just a warning here..
						DtErrHandler::_errorThrow ( 'DtTemplate', DTERR_MERGE_TYPEMISMATCHELSTATIC, array('ELNAME' => $a_Template[$i][DTTEMPL_PI_ELNAME] ) );
						break;
					}
					
					$strRet .= $strVal;
					break;
				case DTTEMPL_ELTYPE_ELCOLLECTION:
					// This little block will grab our value from the current dataset - or the parent stack if its not found at the current level.
					for ($j=$nDepth;$j>=0;$j--)
						if ($a_Val = &$a_ParentStack[$j][$a_Template[$i][DTTEMPL_PI_ELNAME]]) break;

					if ( ( !is_array($a_Val) ) ||	( !sizeof($a_Val) ) )
						// No series by this collection name was found in the provided dataset
						break;
					
					$nCollectionDepth = ($nDepth+1); // This is just a shortcut to keep us from having to use ($nDepth+1) multiple times below.
					$a_CollectionSizesStack[$nCollectionDepth] = sizeof($a_Val);
					$a_CollectionIsStack[$nCollectionDepth] = 0;
					
					for ($j=&$a_CollectionIsStack[$nCollectionDepth];$j<$a_CollectionSizesStack[$nCollectionDepth];$j++) {
						
						// Unfortunately, we'll need to do this here (and slow this loop down a bit) b/c of the condition testing that will be done inside the $l loop below:
						// Mostly this happens b/c of an erroneous dataset being passed to the collection.
						if (!is_array($a_Val[$j])) {
							DtErrHandler::_errorThrow ( 'DtTemplate', DTERR_MERGE_TYPEMISMATCHELCOLLECTION, array('ELNAME' => $a_Template[$i][DTTEMPL_PI_ELNAME] ) );
							
							break 2;
						}
						
						$a_ParentStack[$nCollectionDepth] = array_change_key_case ( &$a_Val[$j], CASE_UPPER );
						
						// Keep in mind that $k is stored 'backwards' , though not $j or $l
						for ($k=(sizeof($a_Template[$i][DTTEMPL_PI_ELCONDCHAIN])-1); $k>=0; $k--) {
							
							$a_ConditionsCur = &$a_Template[$i][DTTEMPL_PI_ELCONDCHAIN][$k];

							for ( $l=0;$l<sizeof($a_ConditionsCur);$l++ ) 
								switch($a_ConditionsCur[$l][DTTEMPL_CONDITION_TYPE]) {
									case DTTEMPL_CONDTYPE_ONEVERY:
									
										if ( ($j+1) % $a_ConditionsCur[$l][DTTEMPL_CONDITION_ON] ) 
											continue 3;
										
										break;
									case DTTEMPL_CONDTYPE_ONRECORD:
										if ( 
											($a_ConditionsCur[$l][DTTEMPL_CONDITION_ON] != ($j+1)) && 
											($a_ConditionsCur[$l][DTTEMPL_CONDITION_ON] != (-$a_CollectionSizesStack[$nCollectionDepth] + $j) )
										)
											continue 3;
										break;
									case DTTEMPL_CONDTYPE_PAIR:
										// This one is a little different than the others b/c we'll be testing values inside this collection entry before checking for the values inside the 'current' level.
										for ($m=$nCollectionDepth;$m>=0;$m--) if ($sPairVal = &$a_ParentStack[$m][$a_ConditionsCur[$l][DTTEMPL_CONDITION_PAIRNAME]]) break;

										switch ($a_ConditionsCur[$l][DTTEMPL_CONDITION_PAIRTYPE]) {
											case DTTEMPL_CONDPTYPE_EQUALS:
												if ( $sPairVal != $a_ConditionsCur[$l][DTTEMPL_CONDITION_PAIRVAL])
													continue 4;
												break;
											case DTTEMPL_CONDPTYPE_EQUALSNOT:
												if ( $sPairVal == $a_ConditionsCur[$l][DTTEMPL_CONDITION_PAIRVAL])
													continue 4;
												break;
											case DTTEMPL_CONDPTYPE_PREG:
												if ( @preg_match($a_ConditionsCur[$l][DTTEMPL_CONDITION_PAIRVAL], $sPairVal ) !== 1 )
													continue 4;
												break;
											case DTTEMPL_CONDPTYPE_PREGNOT:
												if ( @preg_match($a_ConditionsCur[$l][DTTEMPL_CONDITION_PAIRVAL], $sPairVal ) === 1 )
													continue 4;
												break;
											default:
												// This should never happen:
												continue 4;
												break;
										}
										break;						
									case DTTEMPL_CONDTYPE_FLAG :
										// Let's make sure we have our flag target loaded in $sFlagVal, or NULL if not found:
										for ($m=$nCollectionDepth;$m>=0;$m--) if ($sFlagVal = &$a_ParentStack[$m][$a_ConditionsCur[$l][DTTEMPL_CONDITION_FLAGNAME]]) break;
										
										if (is_array($sFlagVal)) {
											// It's an array , and its size is 0:
											if ( !sizeof($sFlagVal) ) continue 3;
										}
										// Its not an array, and its value is null , not set , or empty string
										else if (is_null($sFlagVal)) continue 3;
								
										break;
									case DTTEMPL_CONDTYPE_FLAGNOT :
										// Let's make sure we have our flag target loaded in $sFlagVal, or NULL if not found:
										for ($m=$nCollectionDepth;$m>=0;$m--) if ($sFlagVal = &$a_ParentStack[$m][$a_ConditionsCur[$l][DTTEMPL_CONDITION_FLAGNAME]]) break;
										
										if (is_array($sFlagVal)) {
											// It's an array , and its size is > 0:
											if ( sizeof($sFlagVal) ) continue 3;	
										}
										// Its not an array, and its value is !null , set , or non-empty string
										else if (!is_null($sFlagVal)) continue 3;	

										break;									
									default:
										// This should never happen:
										continue 3;
										break;
								}
								
							/*
								If we're here, than all the above conditions were satisfied, 
								and this route is the first (potentially 'only') that is valid
								We'll branch here, and break from this collection series.
							*/
							
							$strRecursion = &DtTemplate::_merge(
								&$a_Template[$i][DTTEMPL_PI_ELSEQUENCES][$k],
								NULL,
								$nCollectionDepth
							); 
							
							if (!is_string($strRecursion)) return $strRecursion;
					
							$strRet .= $strRecursion;
							
							break; // This exits the current $k loop and returns to the next element in the series, from the $j 
						}
					}
					
					break;
				case DTTEMPL_ELTYPE_ON:
					// Let's verify the conditions are adequate:
					$a_ConditionsCur = &$a_Template[$i][DTTEMPL_PI_CONDITIONS];
					for ($j=0;$j<sizeof($a_ConditionsCur);$j++) 
						switch($a_ConditionsCur[$j][DTTEMPL_CONDITION_TYPE]) {
							case DTTEMPL_CONDTYPE_PAIR :
								
								// This will put the pair target in $sPairVal , this is needed b/c $sPairVal could be something up the chain
								for ($k=$nDepth;$k>=0;$k--) if ($sPairVal = &$a_ParentStack[$k][$a_ConditionsCur[$j][DTTEMPL_CONDITION_PAIRNAME]]) break;

								switch ($a_ConditionsCur[$j][DTTEMPL_CONDITION_PAIRTYPE]) {
									case DTTEMPL_CONDPTYPE_EQUALS:
										if ($sPairVal != $a_ConditionsCur[$j][DTTEMPL_CONDITION_PAIRVAL])
											break 4;
										break;
									case DTTEMPL_CONDPTYPE_EQUALSNOT:
										if ($sPairVal == $a_ConditionsCur[$j][DTTEMPL_CONDITION_PAIRVAL])
											break 4;
										break;
									case DTTEMPL_CONDPTYPE_PREG:
										if ( @preg_match($a_ConditionsCur[$j][DTTEMPL_CONDITION_PAIRVAL], $sPairVal ) !== 1 )
											break 4;
										break;
									case DTTEMPL_CONDPTYPE_PREGNOT:
										if ( @preg_match($a_ConditionsCur[$j][DTTEMPL_CONDITION_PAIRVAL], $sPairVal ) === 1 )
											break 4;
										break;
									default:
										// This should never happen:
										break 4;		
										break;
								}
								break;
							case DTTEMPL_CONDTYPE_FLAG :
								// Let's make sure we have our flag target loaded in $sFlagVal, or NULL if not found:
								for ($k=$nDepth;$k>=0;$k--) if ($sFlagVal = &$a_ParentStack[$k][$a_ConditionsCur[$j][DTTEMPL_CONDITION_FLAGNAME]]) break;

									
								if (is_array($sFlagVal)) {
									// It's an array , and its size is 0:
									if ( !sizeof($sFlagVal) ) break 3;
								}
								// Its not an array, and its value is null , not set , or empty string
								else if (is_null($sFlagVal)) break 3;
									
								break;
							case DTTEMPL_CONDTYPE_FLAGNOT :
								// Let's make sure we have our flag target loaded in $sFlagVal, or NULL if not found:
								for ($k=$nDepth;$k>=0;$k--) if ($sFlagVal = &$a_ParentStack[$k][$a_ConditionsCur[$j][DTTEMPL_CONDITION_FLAGNAME]]) break;

								if (is_array($sFlagVal)) {
									// It's an array , and its size is > 0:
									if ( sizeof($sFlagVal) ) break 3;	
								}
								// Its not an array, and its value is !null , set , or non-empty string
								else if (!is_null($sFlagVal)) break 3;								
								
								break;								
							default:
								// This should never happen:
								break 3;
								break;
						}
					
				
					$strRecursion = &DtTemplate::_merge(
						&$a_Template[$i][DTTEMPL_PI_ONCHILDREN], 
						NULL,
						$nDepth
					);
					
					if (!is_string($strRecursion)) return $strRecursion;
					
					$strRet .= $strRecursion;
					break;
				case DTTEMPL_ELTYPE_NUM:
					/*
					 This is a little obfuscated, mostly because I want to leave its potential open at this stage so that I can more easily experiment
					 with how the 'parent' option gets parsed in a numtype..
					*/
					
					$strBase 		= NULL;
					$nNumDepthCur 	= $nDepth;
					
					if ($a_Template[$i][DTTEMPL_PI_PARENT]) {
						// This means that we have a parent directive to recognize

						$a_ParentParts = array();
						
						if (!@preg_match ( '/^((?:\\/)|(?:..\\/)+)?(.*)$/', $a_Template[$i][DTTEMPL_PI_PARENT] , &$a_ParentParts )) {
							DtErrHandler::_errorThrow ( 'DtTemplate', DTERR_MERGE_INVALIDNUMPARENT, array('PARENT' => $a_Template[$i][DTTEMPL_PI_PARENT]) );
							break;
						}
											
						$strBase = &$a_ParentParts[2];
				
						$nNumDepthCur = ($nDepth - strlen($a_ParentParts[1])/3); // [1] is the ((?:\\/)|(?:..\\/)+) capture
					}
					
					if ($a_Template[$i][DTTEMPL_PI_NUMTYPE] == DTTEMPL_NUMTYPE_COUNT) {
					
						if ($strBase) {
							if (is_array($a_ParentStack[$nNumDepthCur][$strBase]))
								$strRet .= sizeof($a_ParentStack[$nNumDepthCur][$strBase]);
							else 
								DtErrHandler::_errorThrow ( 'DtTemplate', DTERR_MERGE_TYPEMISMATCHELNUM, array('PARENT' => $a_Template[$i][DTTEMPL_PI_PARENT]) );
						}
						else
							if ( $nNumDepthCur > 0 ) 
								$strRet .= $a_CollectionSizesStack[$nNumDepthCur];
					}
					else
						// Currently, this means we're of type DTTEMPL_NUMTYPE_CUR
						if ( (!$strBase) && ( $nNumDepthCur > 0 ) ) {
							// This ensures that nothing happens if we try to to pull a CUR at the root level.
							$strRet .= ($a_CollectionIsStack[$nNumDepthCur]+1);
						}
						// else: An invalid parent tag was passed.
						// * parent="/" is inacceptable, b/c root is not a collection and thus has no possible numtype value.
						// * We're not supporting count for non-'active' collections. Really, this isn't even possible to do. We could return their count here, but even that's not quite appropriate.
					break;
			}

		return $strRet;
	}


	/* Public: ****************************************/
	function DtTemplate() {
		
		$this->_errorClassAttach('DtTemplate', dirname(__FILE__).'/DtTemplate-IRes.xml');
		
		return $this->DtErrHandler();
	}
	
	function &highlight ( $sOutputTemplate = DTTEMPL_HIGHLIGHT_CODE, $a_Template = NULL ) {
		// This is meant as a debugging aid and will highlight a template file for such purposes
		$this->_errorFlush();
	
		if (is_null($a_Template))
			if (is_null($this->a_Template))
				return $this->_errorThrow ( 'DtTemplate', DTERR_TEMPL_TEMPLATENOTYETDEFINED, array( 'DEPENDENTMETHOD' => 'highlight' ) );
			else
				$a_Template = &$this->a_Template;

		// These are convinient shortcuts for use below...
		static $a_MapConditionType = array(
			DTTEMPL_CONDTYPE_ONEVERY	=> 'DTTEMPL_CONDTYPE_ONEVERY',
			DTTEMPL_CONDTYPE_ONRECORD	=> 'DTTEMPL_CONDTYPE_ONRECORD',
			DTTEMPL_CONDTYPE_PAIR		=> 'DTTEMPL_CONDTYPE_PAIR',
			DTTEMPL_CONDTYPE_FLAG		=> 'DTTEMPL_CONDTYPE_FLAG',
			DTTEMPL_CONDTYPE_FLAGNOT	=> 'DTTEMPL_CONDTYPE_FLAGNOT'
		);

		static $a_MapConditionPairType = array(
			DTTEMPL_CONDPTYPE_EQUALS		=> 'DTTEMPL_CONDPTYPE_EQUALS',
			DTTEMPL_CONDPTYPE_EQUALSNOT	=> 'DTTEMPL_CONDPTYPE_EQUALSNOT',
			DTTEMPL_CONDPTYPE_PREG			=> 'DTTEMPL_CONDPTYPE_PREG',
			DTTEMPL_CONDPTYPE_PREGNOT		=> 'DTTEMPL_CONDPTYPE_PREGNOT',
		);
		
		static $a_MapNumType = array(
			DTTEMPL_NUMTYPE_COUNT	=> 'DTTEMPL_NUMTYPE_COUNT',
			DTTEMPL_NUMTYPE_CUR		=> 'DTTEMPL_NUMTYPE_CUR'
		);
		
		switch($sOutputTemplate) {
			case DTTEMPL_HIGHLIGHT_PARSE:
				$sOutputTemplate = <<<EOD
<TABLE CELLPADDING="4" BGCOLOR="#000000" CELLSPACING="1" BORDER="0" WIDTH="100%">
<%ELEMENTS%>
<TR>
<TD VALIGN="TOP" WIDTH="6" BGCOLOR="#FFFFFF"><FONT SIZE="2"><B><%NUMBER%></B></FONT></TD>
<TD VALIGN="TOP" BGCOLOR="#FFFFFF">
<TABLE CELLPADDING="0" CELLSPACING="0" BORDER="0" WIDTH="100%">
<%ON DTTEMPL_PI_ELTYPE="DTTEMPL_ELTYPE_RAW"%>
	<TR><TD BGCOLOR="#DDDDDD">
		<UL>
		<LI><B>DTTEMPL_PI_ELTYPE</B>: <%DTTEMPL_PI_ELTYPE%></LI>
		<LI><B>DTTEMPL_PI_DATA</B>:<BR> <%DTTEMPL_PI_DATA%></LI>
		</UL>
	</TD></TR>
<%/ON%>
<%ON DTTEMPL_PI_ELTYPE="DTTEMPL_ELTYPE_ELSTATIC"%>
	<TR><TD BGCOLOR="#EE8888">
		<UL>
		<LI><B>DTTEMPL_PI_ELTYPE</B>: <%DTTEMPL_PI_ELTYPE%></LI>
		<LI><B>DTTEMPL_PI_ELNAME</B>: <%DTTEMPL_PI_ELNAME%></LI>
		</UL>		
	</TD></TR>
<%/ON%>
<%ON DTTEMPL_PI_ELTYPE="DTTEMPL_ELTYPE_NUM"%>
	<TR><TD BGCOLOR="#FFBB00">
		<UL>
		<LI><B>DTTEMPL_PI_ELTYPE</B>: <%DTTEMPL_PI_ELTYPE%></LI>
		<LI><B>DTTEMPL_PI_NUMTYPE</B>: <%DTTEMPL_PI_NUMTYPE%></LI>
		<LI><B>DTTEMPL_PI_PARENT</B>: <%DTTEMPL_PI_PARENT%></LI>
		</UL>
	</TD></TR>
<%/ON%>
<%ON DTTEMPL_PI_ELTYPE="DTTEMPL_ELTYPE_ON"%> 
	<TR><TD BGCOLOR="#AAEEAA">
		<UL>
		<LI><B>DTTEMPL_PI_ELTYPE</B>: <%DTTEMPL_PI_ELTYPE%></LI>
		<LI><B>DTTEMPL_PI_CONDITIONS</B>:
		<UL>
		<%DTTEMPL_PI_CONDITIONS%>
			<%ON DTTEMPL_CONDITION_TYPE="DTTEMPL_CONDTYPE_ONEVERY"%>
				<LI><B><%DTTEMPL_CONDITION_TYPE%></B>: <%DTTEMPL_CONDITION_ON%></LI>
			<%/ON%>
			<%ON DTTEMPL_CONDITION_TYPE="DTTEMPL_CONDTYPE_ONRECORD"%>
				<LI><B><%DTTEMPL_CONDITION_TYPE%></B>: <%DTTEMPL_CONDITION_ON%></LI>
			<%/ON%>		
			<%ON DTTEMPL_CONDITION_TYPE="DTTEMPL_CONDTYPE_PAIR"%>
				<LI><B><%DTTEMPL_CONDITION_TYPE%></B>: "<%DTTEMPL_CONDITION_PAIRNAME%>" <%DTTEMPL_CONDITION_PAIRTYPE%> "<%DTTEMPL_CONDITION_PAIRVAL%>"</LI>
			<%/ON%>		
			<%ON DTTEMPL_CONDITION_TYPE="DTTEMPL_CONDTYPE_FLAG"%>
				<LI><B><%DTTEMPL_CONDITION_TYPE%></B>: <%DTTEMPL_CONDITION_FLAGNAME%></LI>
			<%/ON%>
			<%ON DTTEMPL_CONDITION_TYPE="DTTEMPL_CONDTYPE_FLAGNOT"%>
				<LI><B><%DTTEMPL_CONDITION_TYPE%></B>: <%DTTEMPL_CONDITION_FLAGNAME%></LI>
			<%/ON%>			
		<%/DTTEMPL_PI_CONDITIONS%>
		</UL>		
		</LI>
		<LI><B>DTTEMPL_PI_ONCHILDREN</B>:<BR><%DTTEMPL_PI_ONCHILDREN%></LI>
		</UL>		
	</TD></TR>
<%/ON%>
<%ON DTTEMPL_PI_ELTYPE="DTTEMPL_ELTYPE_ELCOLLECTION"%>
	<TR><TD BGCOLOR="#AAAAEE">
		<UL>
		<LI><B>DTTEMPL_PI_ELTYPE</B>: <%DTTEMPL_PI_ELTYPE%></LI>
		<LI><B>DTTEMPL_PI_ELNAME</B>: <%DTTEMPL_PI_ELNAME%></LI>
		<LI><B>DTTEMPL_PI_ELCONDCHAIN</B>:
		<UL>
		<%DTTEMPL_PI_ELCONDCHAIN%>
			<LI><%NUMBER%>
				<UL>
				<%CHAIN%>
					<%ON DTTEMPL_CONDITION_TYPE="DTTEMPL_CONDTYPE_ONEVERY"%>
						<LI><B><%DTTEMPL_CONDITION_TYPE%></B>: <%DTTEMPL_CONDITION_ON%></LI>
					<%/ON%>
					<%ON DTTEMPL_CONDITION_TYPE="DTTEMPL_CONDTYPE_ONRECORD"%>
						<LI><B><%DTTEMPL_CONDITION_TYPE%></B>: <%DTTEMPL_CONDITION_ON%></LI>
					<%/ON%>		
					<%ON DTTEMPL_CONDITION_TYPE="DTTEMPL_CONDTYPE_PAIR"%>
						<LI><B><%DTTEMPL_CONDITION_TYPE%></B>: "<%DTTEMPL_CONDITION_PAIRNAME%>" <%DTTEMPL_CONDITION_PAIRTYPE%> "<%DTTEMPL_CONDITION_PAIRVAL%>"</LI>
					<%/ON%>		
					<%ON DTTEMPL_CONDITION_TYPE="DTTEMPL_CONDTYPE_FLAG"%>
						<LI><B><%DTTEMPL_CONDITION_TYPE%></B>: <%DTTEMPL_CONDITION_FLAGNAME%></LI>
					<%/ON%>
					<%ON DTTEMPL_CONDITION_TYPE="DTTEMPL_CONDTYPE_FLAGNOT"%>
						<LI><B><%DTTEMPL_CONDITION_TYPE%></B>: <%DTTEMPL_CONDITION_FLAGNAME%></LI>
					<%/ON%>					
				<%/CHAIN%>
				</UL>
			</LI>
		<%/DTTEMPL_PI_ELCONDCHAIN%>
		</UL>
		</LI>
		<LI><B>DTTEMPL_PI_ELSEQUENCES</B>:<BR>
			<UL>
				<%DTTEMPL_PI_ELSEQUENCES%><LI><%NUMBER%><%SEQUENCE%><%/DTTEMPL_PI_ELSEQUENCES%></LI>
			</UL>
		</LI>
		</UL>		
	</TD></TR>
<%/ON%>	
	</TABLE>
</TD></TR>
<%/ELEMENTS%>
</TABLE>
EOD;
				break;
			case DTTEMPL_HIGHLIGHT_CODE:
				$sOutputTemplate = <<<EOD
<CODE>
<%ELEMENTS%>
<%ON DTTEMPL_PI_ELTYPE="DTTEMPL_ELTYPE_RAW"%>
	<FONT COLOR="#777777"><%DTTEMPL_PI_DATA%></FONT>
<%/ON%>
<%ON DTTEMPL_PI_ELTYPE="DTTEMPL_ELTYPE_ELSTATIC"%>
	<FONT COLOR="#EE2222">&lt;%<%DTTEMPL_PI_ELNAME%>%&gt;</FONT>
<%/ON%>
<%ON DTTEMPL_PI_ELTYPE="DTTEMPL_ELTYPE_NUM"%>
	<FONT COLOR="#FF6600">&lt;%#<%ON DTTEMPL_PI_NUMTYPE="DTTEMPL_NUMTYPE_COUNT"%> TYPE="COUNT"<%/ON%><%ON DTTEMPL_PI_NUMTYPE="DTTEMPL_NUMTYPE_CUR"%> TYPE="CUR"<%/ON%><%ON DTTEMPL_PI_PARENT%> PARENT="<%DTTEMPL_PI_PARENT%>"<%/ON%>%&gt;</FONT>
<%/ON%>
<%ON DTTEMPL_PI_ELTYPE="DTTEMPL_ELTYPE_ON"%> 
	<FONT COLOR="#008800">
	&lt;%&nbsp;ON
	<%DTTEMPL_PI_CONDITIONS%>
		<%ON DTTEMPL_CONDITION_TYPE="DTTEMPL_CONDTYPE_ONEVERY"%>
			&nbsp;ONEVERY="<%DTTEMPL_CONDITION_ON%>"
		<%/ON%>
		<%ON DTTEMPL_CONDITION_TYPE="DTTEMPL_CONDTYPE_ONRECORD"%>
			&nbsp;ONRECORD="<%DTTEMPL_CONDITION_ON%>"
		<%/ON%>
		<%ON DTTEMPL_CONDITION_TYPE="DTTEMPL_CONDTYPE_PAIR"%>
			&nbsp;<%DTTEMPL_CONDITION_PAIRNAME%>
			<%ON DTTEMPL_CONDITION_PAIRTYPE="DTTEMPL_CONDPTYPE_EQUALS"%><FONT COLOR="#000000">=</FONT><%/ON%>
			<%ON DTTEMPL_CONDITION_PAIRTYPE="DTTEMPL_CONDPTYPE_EQUALSNOT"%><FONT COLOR="#000000">!=</FONT><%/ON%>
			<%ON DTTEMPL_CONDITION_PAIRTYPE="DTTEMPL_CONDPTYPE_PREG"%><FONT COLOR="#000000">~</FONT><%/ON%>
			<%ON DTTEMPL_CONDITION_PAIRTYPE="DTTEMPL_CONDPTYPE_PREGNOT"%><FONT COLOR="#000000">!~</FONT><%/ON%>	
			"<%DTTEMPL_CONDITION_PAIRVAL%>"
		<%/ON%>
		<%ON DTTEMPL_CONDITION_TYPE="DTTEMPL_CONDTYPE_FLAG"%>
			&nbsp;<%DTTEMPL_CONDITION_FLAGNAME%>
		<%/ON%>
		<%ON DTTEMPL_CONDITION_TYPE="DTTEMPL_CONDTYPE_FLAGNOT"%>
			&nbsp;<FONT COLOR="#000000">!</FONT><%DTTEMPL_CONDITION_FLAGNAME%>
		<%/ON%>		
	<%/DTTEMPL_PI_CONDITIONS%>
	%&gt;
	<%DTTEMPL_PI_ONCHILDREN%>
	&lt;%/ON%&gt;
	</FONT>
<%/ON%>
<%ON DTTEMPL_PI_ELTYPE="DTTEMPL_ELTYPE_ELCOLLECTION"%>
	<%DTTEMPL_PI_ELCONDCHAIN%>
		<FONT COLOR="#000088">
		&lt;%&nbsp;<%DTTEMPL_PI_ELNAME%>
		<%CHAIN%>
			<%ON DTTEMPL_CONDITION_TYPE="DTTEMPL_CONDTYPE_ONEVERY"%>
				&nbsp;ONEVERY="<%DTTEMPL_CONDITION_ON%>"
			<%/ON%>
			<%ON DTTEMPL_CONDITION_TYPE="DTTEMPL_CONDTYPE_ONRECORD"%>
				&nbsp;ONRECORD="<%DTTEMPL_CONDITION_ON%>"
			<%/ON%>
			<%ON DTTEMPL_CONDITION_TYPE="DTTEMPL_CONDTYPE_PAIR"%>
				&nbsp;<%DTTEMPL_CONDITION_PAIRNAME%>
				<%ON DTTEMPL_CONDITION_PAIRTYPE="DTTEMPL_CONDPTYPE_EQUALS"%><FONT COLOR="#000000">=</FONT><%/ON%>
				<%ON DTTEMPL_CONDITION_PAIRTYPE="DTTEMPL_CONDPTYPE_EQUALSNOT"%><FONT COLOR="#000000">!=</FONT><%/ON%>
				<%ON DTTEMPL_CONDITION_PAIRTYPE="DTTEMPL_CONDPTYPE_PREG"%><FONT COLOR="#000000">~</FONT><%/ON%>
				<%ON DTTEMPL_CONDITION_PAIRTYPE="DTTEMPL_CONDPTYPE_PREGNOT"%><FONT COLOR="#000000">!~</FONT><%/ON%>	
				"<%DTTEMPL_CONDITION_PAIRVAL%>"
			<%/ON%>
			<%ON DTTEMPL_CONDITION_TYPE="DTTEMPL_CONDTYPE_FLAG"%>
				&nbsp;<%DTTEMPL_CONDITION_FLAGNAME%>
			<%/ON%>
			<%ON DTTEMPL_CONDITION_TYPE="DTTEMPL_CONDTYPE_FLAGNOT"%>
				&nbsp;<FONT COLOR="#000000">!</FONT><%DTTEMPL_CONDITION_FLAGNAME%>
			<%/ON%>
		<%/CHAIN%>
	%&gt;
	<%SEQUENCE%>
	&lt;%/<%DTTEMPL_PI_ELNAME%>%&gt;
	</FONT>
	<%/DTTEMPL_PI_ELCONDCHAIN%>
<%/ON%>	
<%/ELEMENTS%>
</CODE>
EOD;
				break;
			default:
				if (!is_string($sOutputTemplate))
					return $this->_errorThrow ( 'DtTemplate', DTERR_HIGHLIGHT_UNSUPPORTEDTEMPLATEPARAMETER );
				break;
		}

		$a_Dataset = array();
		$a_Dataset['ELEMENTS'] = array();

		for ($i=(sizeof($a_Template)-1);$i>=0;$i--)
			switch ($a_Template[$i][DTTEMPL_PI_ELTYPE]) {
				case DTTEMPL_ELTYPE_RAW:
					$a_Dataset['ELEMENTS'][] = array (
						'NUMBER' 				=> ($i),
						'DTTEMPL_PI_ELTYPE' 	=> 'DTTEMPL_ELTYPE_RAW',
						'DTTEMPL_PI_DATA' 	=> str_replace("\t", "&nbsp;&nbsp;&nbsp;",nl2br(htmlentities($a_Template[$i][DTTEMPL_PI_DATA])) )
					);
					break;
				case DTTEMPL_ELTYPE_ELSTATIC:
					$a_Dataset['ELEMENTS'][] = array (
						'NUMBER' 				=> ($i),
						'DTTEMPL_PI_ELTYPE' 	=> 'DTTEMPL_ELTYPE_ELSTATIC',
						'DTTEMPL_PI_ELNAME' 	=> nl2br(htmlentities($a_Template[$i][DTTEMPL_PI_ELNAME]))
					);
					break;
				case DTTEMPL_ELTYPE_ELCOLLECTION:
					$a_ConditionChain = array();
					
					for ($j=(sizeof($a_Template[$i][DTTEMPL_PI_ELCONDCHAIN])-1); $j>=0; $j--) {
						
						$a_ConditionsCur	= array();
						$a_SequencesCur	= array();

						for ( $k=0;$k<sizeof($a_Template[$i][DTTEMPL_PI_ELCONDCHAIN][$j]);$k++ ) 
							$a_ConditionsCur[] = array (
							'DTTEMPL_CONDITION_TYPE' 		=> $a_MapConditionType[$a_Template[$i][DTTEMPL_PI_ELCONDCHAIN][$j][$k][DTTEMPL_CONDITION_TYPE]],
							'DTTEMPL_CONDITION_ON'			=> $a_Template[$i][DTTEMPL_PI_ELCONDCHAIN][$j][$k][DTTEMPL_CONDITION_ON],
							'DTTEMPL_CONDITION_PAIRTYPE' 	=> $a_MapConditionPairType[$a_Template[$i][DTTEMPL_PI_ELCONDCHAIN][$j][$k][DTTEMPL_CONDITION_PAIRTYPE]],
							'DTTEMPL_CONDITION_PAIRNAME' 	=> $a_Template[$i][DTTEMPL_PI_ELCONDCHAIN][$j][$k][DTTEMPL_CONDITION_PAIRNAME],
							'DTTEMPL_CONDITION_PAIRVAL' 	=> $a_Template[$i][DTTEMPL_PI_ELCONDCHAIN][$j][$k][DTTEMPL_CONDITION_PAIRVAL],
							'DTTEMPL_CONDITION_FLAGNAME' 	=> $a_Template[$i][DTTEMPL_PI_ELCONDCHAIN][$j][$k][DTTEMPL_CONDITION_FLAGNAME]
							);

						
						$strSequenceCur = $this->highlight ( &$sOutputTemplate, &$a_Template[$i][DTTEMPL_PI_ELSEQUENCES][$j] );
						
						if (!is_string($strSequenceCur))
							return $strSequenceCur;
						
						$a_SequencesCur[] = array(
							'NUMBER'		=> $j,
							'SEQUENCE' 	=> $strSequenceCur,
							'CHAIN'		=> $a_ConditionsCur,
						);							

						$a_ConditionChain[] = array(
							'NUMBER'		=> $j,
							'CHAIN'		=> &$a_SequencesCur[(sizeof($a_SequencesCur)-1)]['CHAIN'],
							'SEQUENCE'	=> &$a_SequencesCur[(sizeof($a_SequencesCur)-1)]['SEQUENCE']
						);
					}
					
					$a_Dataset['ELEMENTS'][] = array( 
						'NUMBER' => ($i),
						'DTTEMPL_PI_ELTYPE' 			=> 'DTTEMPL_ELTYPE_ELCOLLECTION',
						'DTTEMPL_PI_ELNAME' 			=> $a_Template[$i][DTTEMPL_PI_ELNAME],
						'DTTEMPL_PI_ELCONDCHAIN' 	=> $a_ConditionChain,
						'DTTEMPL_PI_ELSEQUENCES' 	=> $a_SequencesCur
					);
					
					break;
				case DTTEMPL_ELTYPE_ON:
					$a_Conditions = array();
					
					for ($j=0;$j<sizeof($a_Template[$i][DTTEMPL_PI_CONDITIONS]);$j++) 
						$a_Conditions[] = array (
							'DTTEMPL_CONDITION_TYPE' 		=> $a_MapConditionType[$a_Template[$i][DTTEMPL_PI_CONDITIONS][$j][DTTEMPL_CONDITION_TYPE]],
							'DTTEMPL_CONDITION_ON'			=> $a_Template[$i][DTTEMPL_PI_CONDITIONS][$j][DTTEMPL_CONDITION_ON],
							'DTTEMPL_CONDITION_PAIRTYPE' 	=> $a_MapConditionPairType[$a_Template[$i][DTTEMPL_PI_CONDITIONS][$j][DTTEMPL_CONDITION_PAIRTYPE]],
							'DTTEMPL_CONDITION_PAIRNAME' 	=> $a_Template[$i][DTTEMPL_PI_CONDITIONS][$j][DTTEMPL_CONDITION_PAIRNAME],
							'DTTEMPL_CONDITION_PAIRVAL'	=> $a_Template[$i][DTTEMPL_PI_CONDITIONS][$j][DTTEMPL_CONDITION_PAIRVAL],
							'DTTEMPL_CONDITION_FLAGNAME' 	=> $a_Template[$i][DTTEMPL_PI_CONDITIONS][$j][DTTEMPL_CONDITION_FLAGNAME]
						);
					
					$strChildren = $this->highlight ( &$sOutputTemplate, &$a_Template[$i][DTTEMPL_PI_ONCHILDREN] );
						
					if (!is_string($strChildren))
						return $strChildren;
						
					$a_Dataset['ELEMENTS'][] = array( 
						'NUMBER' 					=> ($i),
						'DTTEMPL_PI_ELTYPE' 		=> 'DTTEMPL_ELTYPE_ON',
						'DTTEMPL_PI_CONDITIONS' => $a_Conditions,
						'DTTEMPL_PI_ONCHILDREN' => $strChildren
					);
					break;
				case DTTEMPL_ELTYPE_NUM:
					$a_Dataset['ELEMENTS'][] = array( 
						'NUMBER' 					=> ($i),
						'DTTEMPL_PI_ELTYPE' 		=> 'DTTEMPL_ELTYPE_NUM',
						'DTTEMPL_PI_NUMTYPE' 	=> $a_MapNumType[$a_Template[$i][DTTEMPL_PI_NUMTYPE]],
						'DTTEMPL_PI_PARENT'	=> $a_Template[$i][DTTEMPL_PI_PARENT]
					);
					break;
			}
		
		return $this->mergeQuick(&$sOutputTemplate,&$a_Dataset);
	}	
	

	function templateCompile( $strTemplateIn ) {
		$this->_errorFlush();
		
		$strTemplate = &$this->_parameterLoad (&$strTemplateIn);
		
		if (is_int($strTemplate))
			return $strTemplate;
		
		$a_Templ = array();
		$a_RetChars = array();
		
		$nRet = &$this->_templateL1Parse( &$strTemplate, &$a_Templ, &$a_RetChars );
		
		if ($nRet !== DTERR_OK) return $nRet;
		
		$a_Templ = &$this->_templateL2Parse(&$a_Templ, &$a_RetChars, &$strTemplate );
 
		if (!is_array($a_Templ)) return $a_Templ; 
	
		$this->a_Template = &$a_Templ;
		
		return DTERR_OK;
	}
	
	function &merge ( $a_Data ) {
		$this->_errorFlush();

		if (is_null($this->a_Template))
			return $this->_errorThrow ( 'DtTemplate', DTERR_TEMPL_TEMPLATENOTYETDEFINED, array( 'DEPENDENTMETHOD' => 'merge' ) );

		$strRet = &$this->_merge(&$this->a_Template , &$a_Data );
		
		if (!is_string($strRet))
			return $strRet;
		
		if ($this->bSupportsExecute)
			if (preg_match ( '/<'.'?([^?][^>])*?'.'>/m', $strRet )) {
				$nRet = dt_phpeval_sandbox(&$strRet,&$strRet);

				if ($nRet)
					return $this->_errorThrow ( 'DtTemplate', $nRet, array('CITATION' => $strRet));
			}
				
		return $strRet;

	}
	
	function &mergeQuick ($strTemplateIn, $a_Dataset) {
		/*
		This is useful both for backwards compatability with the version 1 library, as well as with those fast and dirty one-timers.
			* The only drawbacks to using this function are speed & the lack of in-template php execution 
		*/
		DtErrHandler::_errorFlush();
		
		if (!is_array($a_Dataset)) return DtErrHandler::_errorThrow ( 'DtTemplate', DTERR_QMERGE_INVALIDDATASET );
		
		$strTemplate = &DtTemplate::_parameterLoad (&$strTemplateIn);
		
		if (is_int($strTemplate))
			return $strTemplate;
		
		if (!is_string($strTemplate)) return $strTemplate;
		
		$a_Templ = array();
		$a_RetChars = array();
		
		$nRet = &DtTemplate::_templateL1Parse(&$strTemplate,&$a_Templ,&$a_RetChars);

		if ($nRet !== DTERR_OK) return $nRet; 
		
		// The nice thing here, is that we're both destroying the last array and retaining the new one at the same time.
		$a_Templ = &DtTemplate::_templateL2Parse(&$a_Templ, &$a_RetChars, &$strTemplate);

		if (!is_array($a_Templ)) return $a_Templ;
		
		// Perform the quick merge code here.
		$strRet = DtTemplate::_merge(&$a_Templ, &$a_Dataset); 
		
		if (!is_string($strRet))
			return $strRet;

		if (DTTEMPL_SUPPORTS_EXECUTE)
			if (preg_match ( '/<'.'?([^?][^>])*?'.'>/m', $strRet )) {
				$nRet = dt_phpeval_sandbox(&$strRet,&$strRet);

				if ($nRet) return $nRet;
			}
		
		return $strRet;
	}

}
?>