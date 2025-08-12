<?php
/*  
 * TemplateBlock.php	
 * Copyright (C) 2004, Alejandro Espinoza Esparza.
 *
 * Description :
 *   This class represents a template block, for parsing block document files.
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
  * Class represents a block document, for creating and parsing Block document files.
  *
  * @class		TemplateBlock
  * @package	moebius2.template
  * @author		Alejandro Espinoza <aespinoza@structum.com.mx>
  * @version	1.0
  * @extends	XMLDocument
  * @requires	ObjectManager, XMLDocument
  * @see		XMLDocument
  */
class TemplateBlock extends XML
{
	/* --- Attributes --- */
	var $variables;
	var $objectManager;

	/* --- Methods --- */	
	/**
	  * Constructor, initializes the template block.
	  * @method		TemplateManager
	  * @param		variant blockContent
	  * @returns	none
	  */
	function TemplateBlock($blockContent=null)
	{
		parent::XML();
		$this->objectManager =& new ObjectManager("moebius2.template", "TemplateBlock");
		
		if(!is_null($blockContent)) {
			$this->SetBlockContent($blockContent);
		}
	}

	/**
	  * Sets the the content for the block. The contents can only be of type xml, xmlbranch or string.
	  *
	  * @method		SetBlockContent
	  * @param		variant content
	  * @returns	none.
	  */		
	function SetBlockContent($content)
	{
		if(get_class($content)=="xml" || get_class($content)=="xmlbranch" ) {
			$content = $content->getXMLString();
		}

		$this->parseFromString($content);
	}	

	/**
	  * Sets the variables and values to the block.
	  *
	  * @method		SetVars
	  * @param		array variables
	  * @returns	none.
	  */		
	function SetVars(&$variables)
	{
		$this->variables =& $variables;
	}
	
	/**
	  * Assigns a value to a variable in the block.
	  *
	  * @method		AssignValue
	  * @param		string var
	  * @param		string value
	  * @returns	none.
	  */		
	function Assign($variable, $value)
	{
		$this->variables[$variable] = $value;
	}
	
	/**
	  * Replaces the variables in the template, for their corresponding value, and generates the actual xhtml content.
	  *
	  * @method		Generate
	  * @returns	true if success, false otherwise.
	  */		
	function Generate()
	{
		$success = false;
		$xml = $this->getXMLString(0);

		// Call the constructor to clean the contents of the XML block.
		parent::XML();

		// If it has a Default tag.
		$xml = preg_replace("'<.*?xml[^>]*?>'si", "", $xml);		

		if(is_array($this->variables)) {
			foreach ($this->variables as $var=>$val) {			
				$xml = preg_replace("/{%".$var."}/", $val, $xml);
			}
		}		
		
		if($this->parseFromString($xml)) {
			$success = true;
		}
		
		return $success;
	}

	/**
	  * Returns the xhtml code; it can also build the Xhtml code if autoGenerate is set to true (Default).
	  * @method		GetXhtml
	  * @param		optional bool autoGenerate 
	  * @returns	object of type XMLBranch containing the xhtml code.
	  */	
	function GetXhtml($autoGenerate=true)
	{
		if($autoGenerate==true) {
			$this->Generate();
		}
		
		return $this->getXML();
	}
	
	/**
	  * Returns the xhtml code in a string; it can also build the Xhtml code for the document if autoGenerate is set to true (Default).
	  * It can also format the string, depending on if 'formatString' is set to true.
	  * This method also adds the document type.
	  *
	  * @method		GetStringXhtml
	  * @param		optional bool autoGenerate
	  * @param		optional bool formatString 	  
	  * @returns	string containing the xhtml document.
	  */	
	function GetStringXhtml($autoGenerate=true, $formatString=true)
	{
		if($autoGenerate==true) {
			$this->Generate();
		}
		
		return $this->getXMLString(0);
	}	
}

?>
