<?php
/*  
 * BlockDocument.php	
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
import("org.active-link.xml.XMLDocument");
import("moebius2.template.TemplateBlock");

/**
  * Class represents a block document, for creating and parsing Block document files.
  *
  * @class		TemplateBlock
  * @package	moebius2.template
  * @author		Alejandro Espinoza <aespinoza@structum.com.mx>
  * @version	1.3
  * @extends	XMLDocument
  * @requires	ObjectManager, XMLDocument
  * @see		XMLDocument
  */
class BlockDocument extends XMLDocument
{
	/* --- Attributes --- */
	var $objectManager;
	var $block;

	/* --- Methods --- */	
	/**
	  * Constructor, initializes and opens the block document if provided.
	  * @method		BlockDocument
	  * @param		optional string blockFile
	  * @param		optional string openMode
	  * @returns	none
	  */
	function BlockDocument($blockFile = "", $openMode = "r")
	{
		parent::XMLDocument($blockFile, $openMode);
		$this->objectManager =& new ObjectManager("moebius2.template", "BlockDocument");

		$this->block =& new TemplateBlock($this->xml);
	}   

	/**
	  * Sets the variables and values to the block document.
	  *
	  * @method		SetVars
	  * @param		array variables
	  * @returns	none.
	  */		
	function SetVars(&$variables)
	{
		$this->block->variables =& $variables;
	}
	
	/**
	  * Assigns a value to a variable in the block document.
	  *
	  * @method		AssignValue
	  * @param		string var
	  * @param		string value
	  * @returns	none.
	  */		
	function Assign($variable, $value)
	{
		$this->block->variables[$variable] = $value;
	}
	
	/**
	  * Replaces the variables in the template, for their corresponding value, and generates the actual xhtml document.
	  *
	  * @method		Generate
	  * @returns	true if success, false otherwise.
	  */		
	function Generate()
	{
		$success = false;

		$this->block->Generate();
		
		return $success;
	}

	/**
	  * Returns the xhtml code; it can also build the Xhtml code for the document if autoGenerate is set to true (Default).
	  * @method		GetXhtml
	  * @param		optional bool autoGenerate 
	  * @returns	object of type XMLBranch containing the xhtml document.
	  */	
	function GetXhtml($autoGenerate=true)
	{
		return $this->block->GetXhtml($autoGenerate);
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
		return $this->block->GetStringXhtml($autoGenerate, $formatString);
	}	
}

?>
