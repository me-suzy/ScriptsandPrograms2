<?php
/*  
 * Head.php	
 * Copyright (C) 2004, Alejandro Espinoza Esparza.
 *
 * Description :
 *   This class represents the header for an html document.
 *
 * Author(s):
 *   Alejandro Espinoza <aespinoza@structum.com.mx>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation; either version 2.1 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307, USA.
 *
 */

import("moebius2.base.ObjectManager");
import("org.active-link.xml.XML");

/**
  * Class that represents the header for an html document. The idea behind this whole class is to manage  all the document
  * header's data internally and then, when needed, parse and generate the actual XHTML code. This class manages only
  * the HTML Header.
  * 
  * This class is designed to only manage XHTML code, since it uses an XML parser for HTML code generation.
  * If you embed any other version of HTML code things should work fine, but won't match W3C standards,
  * and won't pass the validation test.
  *
  * NOTE: Changing the Document type to another HTML document type other than XHTML, won't change the fact,
  * that this class is designed for XHTML.
  *
  * Glossary :
  * - Document Header : The document header represents a set of multimedia content that goes in the top of a document.
  * - HTML Header : A set of HTML tags that define the body of the document.
  *
  * @class		Head
  * @package	moebius2.xhtml.base
  * @author		Alejandro Espinoza <aespinoza@structum.com.mx>
  * @version	0.1
  * @extends	ObjectManager
  * @requires	ObjectManager, XML
  * @see		XML
  */
class Head extends ObjectManager
{
	/* --- Attributes --- */
	// Components
	var $htmlHead;
	var $customHtmlHead;

	var $xhtml;	

	var $title;
	var $metadata;
	var $styles;
	var $scripts;

	/* --- Methods --- */	
	/**
	  * Constructor, initializes the html header.
	  * @method		Head
	  * @param		object htmlDoc
	  * @returns	none
	  */
	function Head()
	{
		ObjectManager::ObjectManager("moebius2.xhtml.base", "Head");
		
		$this->xhtml =& new XML("head");
	}

	/**
	  * Changes the document's title.
	  * @method		SetTitle
	  * @param		string title
	  * @returns	none
	  */			
	function SetTitle($title)
	{
		$this->title = $title;        
	}

	/**
	  * Returns the document's title.
	  * @method		GetTitle
	  * @returns	string containing the document's title
	  */	
	function GetTitle()
	{
		return $this->title;
	}
	
	/**
	  * Adds a metadata (&lt;meta&gt;) text to the html header.
	  * Attribute's types:
	  * META_HTTP_EQUIV
	  * META_NAME
	  * @method		AddMetadata
	  * @param		string content
	  * @param		string attribContent
	  * @param		optional int attribType	  
	  * @returns	none
	  */	
	function AddMetadata($attribContent, $content, $attribType=META_HTTP_EQUIV)
	{
		if($attribType == META_HTTP_EQUIV || $attribType == META_NAME) {
			$count = count($this->metadata);	   			
			$this->metadata[$count][0] = $attribType;
			$this->metadata[$count][1] = $attribContent;
			$this->metadata[$count][2] = $content;
		} else {
			$this->SendErrorMessage("AddMetadata", "Argument 'attribType' has to be either META_HTTP_EQUIV or META_NAME");
		}		
	}

	/**
	  * Returns the list of metadata added to the html header; depending on the 'returnType' selected, this method can
	  * return an array, an XMLBranch Object array or even an string.
	  *
	  * Return types :
	  * RET_ARRAY : returns an array. (Default)
	  * RET_ARRAY_XML : returns an Array of XMLBranch references. 
	  * RET_TEXT : returns a string with the html code.
	  *	  
	  * The structure of the returning array is :
	  *
	  *	 array[index][0] = metadata attrib type (META_HTTP_EQUIV or META_NAME)
	  *	 array[index][1] = metadata attribute content
	  *	 array[index][2] = metadata content	  
	  *	  	  
	  * @method		GetMetadata
	  * @returns	list of metadata; depending on the returnType selected : array, XMLBranch  array or string.
	  */	
	function GetMetadata($returnType=RET_ARRAY)
	{
		switch($returnType)
		{
		case RET_ARRAY:
			return $this->metadata;
			break;
		case RET_ARRAY_XML:
			$xml = $this->GetMetadataXML();			
			return $xml->getBranches("metadata");
			break;
		case RET_TEXT:
			$tempArray = $this->GetMetasXML();
			$count = count($tempArr);
			$str = "";
			
			for($i = 0; $i < $count; $i++) {
				$xmlBranch =& $tempArr[$i];
				$str .= $xmlBranch->getStringXML(0);
			}
			return $str;
			break;			
		default:
			return $this->metadata;
			break;
		}		
	}

	
	/**
	  * Returns an XMLBranch object array with references to the list of metadata in XML format.
	  *
	  * @method		GetMetadataXML
	  *	@returns	array of XMLBranch objects
	  */
	function GetMetadataXML()
	{
		$meta = $this->metadata;
		$count = count($meta);
		
		$xmlMeta =& new XMLBranch("metadata");
				
		for($i = 0; $i < $count; $i++) {
			$xml =& new XMLBranch("meta");
			
			if($meta[$i][0] == META_HTTP_EQUIV) {
				$xml->setTagAttribute("http-equiv", $meta[$i][1], "meta");
			} else {
				$xml->setTagAttribute("name", $meta[$i][1], "meta");
			}
			
			$xml->setTagAttribute("content", $meta[$i][2], "meta");

			$xmlMeta->addXMLBranch($xml);
		}

		// Add Author if added
		if(!empty($this->author)) {
			$xml =& new XMLBranch("meta");
			$xml->setTagAttribute("name", "author", "meta");			
			$xml->setTagAttribute("content", $this->author, "meta");
			$xmlMeta->addXMLBranch($xml);			
		}

		// Add description if added
		if(!empty($this->description)) {
			$xml =& new XMLBranch("meta");
			$xml->setTagAttribute("name", "description", "meta");			
			$xml->setTagAttribute("content", $this->description, "meta");
			$xmlMeta->addXMLBranch($xml);			
		}
		
		return $xmlMeta;
	}	
	
	/**
	  * Adds a CSS Style Sheet link or text to the header depending on the selected type.
	  * Style Types :
	  * TAG_LINK - Style link. i.e. &lt;link rel="stylesheet" type="text/css"  href="style.css"&gt; (Default)
	  *	TAG_TEXT - Style text. i.e. &lt;style type="text/css"&gt;body {background-color: white;}&lt;/style&gt;
	  * @method		AddStyle
	  *	@param		string style
	  *	@param		optional int type
	  * @returns	none
	  */	
	function AddStyle($style, $type=TAG_LINK)
	{
		if($type == TAG_LINK || $type == TAG_TEXT) {
			$count = count($this->styles);	   			
			$this->styles[$count][0] = $type;
			$this->styles[$count][1] = $style;
		} else {
			$this->SendErrorMessage("AddStyle", "Argument 'type' has to be either TAG_LINK or TAG_TEXT");
		}
		
	}

	/**
	  * Returns the list of styles added to the html header; depending on the 'returnType' selected, this method can
	  * return an array, an XMLBranch Object array or even an string.
	  *
	  * Return types :
	  * RET_ARRAY : returns an array. (Default)
	  * RET_ARRAY_XML : returns an Array of XMLBranch references. 
	  * RET_TEXT : returns a string with the html code.
	  *	  
	  * The structure of the returning array is :
	  *
	  * array[index][0] = style type. (TAG_LINK or TAG_TEXT)
	  * array[index][1] = actual style (either text or link)	  
	  *	
	  * @method		GetStyles
	  * @param		int returnType
	  * @returns	list of styles; depending on the returnType selected : array, XMLBranch array or string.
	  */	
	function GetStyles($returnType=RET_ARRAY)
	{
		switch($returnType)
		{
		case RET_ARRAY:
			return $this->styles;
			break;
		case RET_ARRAY_XML:
			$xml = $this->GetStylesXML();
			
			return $xml->getBranches("styles");
			break;
		case RET_TEXT:
			$tempArray = $this->GetStylesXML();
			$count = count($tempArr);
			$str = "";
			
			for($i = 0; $i < $count; $i++) {
				$xmlBranch =& $tempArr[$i];
				$str .= $xmlBranch->getStringXML(0);
			}
			return $str;
			break;			
		default:
			return $this->styles;
			break;
		}
	}

	/**
	  * Returns an XMLBranch object array with references to the list of styles in XML format.
	  *
	  * @method		GetStylesXML
	  *	@returns	array of XMLBranch objects
	  */
	function GetStylesXML()
	{
		$styles = $this->styles;
		$count = count($styles);

		$xmlStyles =& new XMLBranch("styles");
		
		for($i = 0; $i < $count; $i++) {			
			if($styles[$i][0] == TAG_LINK) {
				$xml =& new XMLBranch("link");
				$xml->setTagAttribute("rel", "stylesheet", "link");
				$xml->setTagAttribute("type", "text/css", "link");
				$xml->setTagAttribute("href", $styles[$i][1], "link");				
			} else {
				$xml =& new XMLBranch("style");
				$xml->setTagAttribute("type", "text/css", "style");
				$xml->setTagContent($styles[$i][1], "style");
			}

			$xmlStyles->addXMLBranch($xml);			
		}

		return $xmlStyles;
	}

	/**
	  * Adds a script to the html header; either text or link depending on the selected type.
	  * Style Types :
	  * TAG_LINK - Script link. i.e. &lt;script type='text/javascript' src='script.js'&gt; &lt;/script&gt; (Default)
	  * TAG_TEXT - Script text. i.e. &lt;script type='text/javascript'&gt; document.write("hello world")lt;/script&gt;	  
	  * @method		AddScript
	  * @param		string src
	  * @param		optional int  type
	  * @returns	none
	  */	
	function AddScript($script, $type=TAG_LINK)
	{
		if($type == TAG_LINK || $type == TAG_TEXT) {
			$count = count($this->scripts);
			$this->scripts[$count][0] = $type;
			$this->scripts[$count][1] = $script;			
			
		} else {
			$this->SendErrorMessage("AddScript", "Argument 'type' has to be either TAG_LINK or TAG_TEXT");
		}		
	}

	/**
	  * Returns the list of scripts added to the html header; depending on the 'returnType' selected, this method can
	  * return an array, an XMLBranch Object array or even an string.
	  *
	  * Return types :
	  * RET_ARRAY : returns an array. (Default)
	  * RET_ARRAY_XML : returns an Array of XMLBranch references. 
	  * RET_TEXT : returns a string with the html code.
	  *
	  * The structure of the returning array is (if chosen) :
	  *
	  *	 array[index][0] = script type (TAG_LINK or TAG_TEXT)
	  *	 array[index][1] = actual script (either text or link)	  
	  *	  
	  * @method		GetScripts
	  * @param		optional int returnType
	  * @returns	list of scripts; depending on the returnType selected : array, XMLBranch array or string.
	  */	
	function GetScripts($returnType=RET_ARRAY)
	{
		switch($returnType)
		{
		case RET_ARRAY:
			return $this->scripts;
			break;
		case RET_ARRAY_XML:
			$xml = $this->GetScriptsXML();
			
			return $xml->getBranches("scripts");
			break;
		case RET_TEXT:
			$tempArray = $this->GetScriptsXML();
			$count = count($tempArr);
			$str = "";
			
			for($i = 0; $i < $count; $i++) {
				$xmlBranch =& $tempArr[$i];
				$str .= $xmlBranch->getStringXML(0);
			}
			return $str;
			break;			
		default:
			return $this->scripts;
			break;
		}
	   
	}

	/**
	  * Returns an XMLBranch object array with references to the list of scripts in XML format.
	  *
	  * @method		GetScriptsXML
	  *	@returns	array of XMLBranch objects
	  */	
	function GetScriptsXML()
	{
		$xmlScripts = new XMLBranch("scripts");
		
		$scripts = $this->scripts;
		$count = count($scripts);
		for($i = 0; $i < $count; $i++) {			
			if($scripts[$i][0] == TAG_LINK) {
				$xml =& new XMLBranch("script");
				$xml->setTagAttribute("type", "text/javascript", "script");
				$xml->setTagAttribute("src", $scripts[$i][1], "script");
				
				//FIXME: Hack to work around the fucking script link.
// 				$xml->setTagContent("document.write('');", "script");				
			} else {
				$xml =& new XMLBranch("script");
				$xml->setTagAttribute("type", "text/javascript", "script");
				$xml->setTagContent($scripts[$i][1], "script");
			}

			$xmlScripts->addXMLBranch($xml);
		}

		return $xmlScripts;
	}	

	/**
	  * Sets a custom html header into the document, replacing the html head generated.
	  * @method		SetCustomHtmlHead
	  * @param		string customHtmlHead
	  * @returns	none
	  */	
	function SetCustomHtmlHead($customHtmlHead)
	{
		$xml =& new XMLBranch("customHead");
		if($xml->parseFromString($customHtmlHead)) {
			$this->customHtmlHead = $xml;		   
		} else {
			$this->SendErrorMessage("SetCustomHtmlHead", "The 'customHtmlHead' argument does not contain valid xml code; it failed the xml parsing test.");
		}		
	}

	/**
	  * Returns the custom html code previously set.
	  * @method		GetCustomHtmlHead
	  * @returns	object of type XMLBranch containing the custom html head.
	  */	
	function GetCustomHtmlHead()
	{
		return $this->customHtmlHead;
	}
	
	/**
	  * Generates the xhtml code for the  head.
	  * @method		Generate
	  * @returns	none
	  */	
	function Generate()
	{	
		if(!empty($this->customHtmlHead)) {
			$this->xhtml = $this->htmlDoc->addXMLBranch($this->GetCustomHtmlHead());
		} else {		

			$head =& $this->xhtml;
			
			$head->setTagContent($this->GetTitle(), "head/title");			   	 	   	

			// Meta
			$branchesArr =& $this->GetMetadata(RET_ARRAY_XML);
			$count = (empty($branchesArr) ? 0 : count($branchesArr));
		
			for($i = 0; $i < $count; $i++) {
				$branch =& $branchesArr[$i];
				$head->addXMLBranch($branch);
			}

			// Styles
			$branchesArr =& $this->GetStyles(RET_ARRAY_XML);
			$count = (empty($branchesArr) ? 0 : count($branchesArr));
		
			for($i = 0; $i < $count; $i++) {
				$branch =& $branchesArr[$i];
				$head->addXMLBranch($branch);
			}		

			// Scripts
			$branchesArr =& $this->GetScripts(RET_ARRAY_XML);
			$count = (empty($branchesArr) ? 0 : count($branchesArr));
		
			for($i = 0; $i < $count; $i++) {
				$branch =& $branchesArr[$i];
				$head->addXMLBranch($branch);
			}	   		
		}
	}

	/**
	  * Returns the xhtml code; it can also build the Xhtml code for the head if autoGenerate is set to true (Default).
	  * @method		Generate
	  * @param		optional bool autoGenerate 
	  * @returns	object of type XMLBranch containing the head.
	  */	
	function GetXhtml($autoGenerate=true)
	{
		if($autoGenerate==true) {
			$this->Generate();
		}
		
		return $this->xhtml;
	}   
}

?>