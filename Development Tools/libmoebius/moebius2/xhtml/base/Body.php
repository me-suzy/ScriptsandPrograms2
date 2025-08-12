<?php
/*  
 * Body.php	
 * Copyright (C) 2004, Alejandro Espinoza Esparza.
 *
 * Description :
 *   This class represents the body for an html document.
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
  * Class that reprensents the html body of an html document.
  *
  * @class		Body
  * @package	moebius2.xhtml.base
  * @author		Alejandro Espinoza <aespinoza@structum.com.mx>
  * @version	1.1
  * @extends	ObjectManager
  * @requires	ObjectManager, XML
  * @see		XML
  */
class Body extends ObjectManager
{
	/* --- Attributes --- */
	// Components
	var $attribs;
	var $content;

	var $xhtml;

	/* --- Methods --- */	
	/**
	  * Constructor, initializes the html body.
	  * @method		Body
	  * @returns	none
	  */
	function Body()
	{
		ObjectManager::ObjectManager("moebius2.xhtml.base", "Body");

		$this->xhtml =& new XML("body");
	}   
	
	/**
	  * Adds 1 or several attributes to the document's body. 
	  * @method		AddAttrib
	  * @param		string attribName
	  * @param		string attribVal
	  * @returns	none
	  */		
	function AddAttribute($attribName, $attribVal)
	{
		$count = count($this->attribs);	   			
		$this->attribs[$count][0] = $attribName;
		$this->attribs[$count][1] = $attribVal;		
	}

	/**
	  * Returns the attributes added to the body tag; depending on the 'returnType' selected, this method can
	  * return an array, an XMLBranch Object array or even an string.
	  *
	  * Return types :
	  * RET_ARRAY : returns an array. (Default)
	  * RET_XML : returns an XMLBranch object.
	  * RET_TEXT : returns a string with the html code.
	  *	  
	  * The structure of the returning array is :
	  *
	  * array[index][0] = attribute name. 
	  * array[index][1] = attribute value.
	  *	
	  * @method		GetAttrib
	  * @param		int returnType
	  * @returns	body tag; depending on the returnType selected : array, XMLBranch or string.	  
	  */			
	function GetAttrib($returnType=RET_ARRAY)
	{
		switch($returnType)
		{
		case RET_ARRAY:
			return $this->attribs;
			break;
		case RET_XML:
			return $this->GetAttribsXml();
			break;
		case RET_TEXT:
			$xml->GetAttribsXML();
			return  $xml->getStringXML(0);
			break;			
		default:
			return $this->attribs;
			break;
		}	   
	}

	/**
	  * Returns an XMLBranch object referencing to the body and attributes in XML format.
	  *
	  * @method		GetAttribsXml
	  *	@returns	XMLBranch object referencing to the body tag.
	  */	
	function GetAttribsXml()
	{
		$count = count($this->attribs);
		$xml =& new XMLBranch("body");		

		for($i = 0; $i < $count; $i++) {			
			$xml->setTagAttribute($this->attribs[$i][0], $this->attribs[$i][1], "body");
		}

		return $xml;
	}

	/**
	  * Changes the body's content.
	  * @method		SetContent
	  * @param		string content
	  * @returns	none
	  */	
	function SetContent($content)
	{				
		$xml =& new XMLBranch("content");
		if($xml->parseFromString("<content>".$content."</content>")) {						
			$this->content =& $xml;
		} else {
			$this->SendErrorMessage("SetContent", "The 'content' argument does not contain valid xml code; it failed the xml parsing test.");
		}
	}

	/**
	  * Returns the html code for the body's content; either in XMLBranch object or string.
	  * @method		GetContent
	  * @returns	XML object or string containing the body's content
	  */		
	function GetContent($returnType=RET_TEXT)
	{
		switch($returnType)
		{
		case RET_XML:
			return $this->content;
			break;
		case RET_TEXT:
		default:
			$xml =& $this->content->getBranches("content");			
			return $xml->getXMLString(0);
			break;
		}
	}

	/**
	  * Adds an String Branch to the body's content.
	  * @method		AddStringBranch
	  * @param		string stringBranch
	  * @returns	none
	  */	
	function AddStringBranch($stringBranch)
	{
		$xml =& new XMLBranch();

		if($xml->parseFromString($stringBranch)) {						
			$this->AddXmlBranch($xml);
		} else {
			$this->SendErrorMessage("AddStringBranch", "The 'stringBranch' argument does not contain valid xml code; it failed the xml parsing test.");
		}		
	}

	
	/**
	  * Adds an XML Branch to the body's content.
	  * @method		AddXmlBranch
	  * @param		object xmlBranch
	  * @returns	none
	  */	
	function AddXmlBranch($xmlBranch)
	{		
		if(is_object($xmlBranch) && get_class($xmlBranch) == "xmlbranch") {
			
			if( !(is_object($this->content) && get_class($this->content) == "xmlbranch") ) {
				$this->content =& new XMLBranch("content");
			} 

			$this->content->addXMLBranch($xmlBranch);
		} else {
			$this->SendErrorMessage("AddXmlBranch", "The 'xmlBranch' argument is not of type XMLBranch");			
		}
	}
	
	/**
	  * Generates the html code for the body, including the content and attributes.
	  * @method		Generate
	  * @returns	none
	  */	
	function Generate()
	{	
		$body =& $this->GetAttrib(RET_XML);

		if(!empty($this->content)) {

			$branches =& $this->content->getBranches("content");
			$count = (empty($branches) ? 0 : count($branches));

			for($i = 0; $i < $count; $i++) {
				$body->addXMLBranch($branches[$i]);
			}
		}
		
		$this->xhtml =& $body;
	}

	/**
	  * Returns the xhtml code; it can also build the Xhtml code for the body if autoGenerate is set to true (Default).
	  * @method		Generate
	  * @param		optional bool autoGenerate 
	  * @returns	object of type XMLBranch containing the body.
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