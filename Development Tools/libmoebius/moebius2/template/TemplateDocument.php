<?php
/*  
 * TemplateDocument.php	
 * Copyright (C) 2004, Alejandro Espinoza Esparza.
 *
 * Description :
 *   This class represents a template document, for parsing Template document files.
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
import("moebius2.template.BlockDocument");
import("org.active-link.xml.XMLDocument");

/**
  * Class represents a template document, for creating and parsing Template document files.
  * Template Document files are only structure definition, they don't actually contain any variables;
  * The variables are contained in the template blocks. In other words, Template Documents files contain
  * only document blocks.
  *
  * @class		TemplateDocument
  * @package	moebius2.template
  * @author		Alejandro Espinoza <aespinoza@structum.com.mx>
  * @version	1.3
  * @extends	XMLDocument
  * @requires	ObjectManager, XMLDocument
  * @see		XMLDocument
  */
class TemplateDocument extends XMLDocument
{
	/* --- Attributes --- */
	var $objectManager;
	var $variables;
	var $blocksPath;

	var $name;
	var $title;
	var $author;
	var $desc;

	/* --- Methods --- */	
	/**
	  * Constructor, initializes and opens the template document if provided.
	  * @method		TemplateManager
	  * @param		optional string templateFile
	  * @param		optional string openMode
	  * @returns	none
	  */
	function TemplateDocument($templateFile = "", $openMode = "r")
	{
		XMLDocument::XMLDocument($templateFile, $openMode);
		$objectManager =& new ObjectManager("moebius2.template", "TemplateDocument");		

		// Load the default values for the template document.
		$this->name   =& $this->xml->getTagAttribute("name");
		$this->title  =& $this->xml->getTagAttribute("title");
		$this->desc   =& $this->xml->getTagAttribute("description");
		$this->author =& $this->xml->getTagAttribute("author");
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
	  * Changes the document's author.
	  * @method		SetAuthor
	  * @param		string author
	  * @returns	none
	  */			
	function SetAuthor($uathor)
	{
		$this->author = $author;
	}

	/**
	  * Returns the document's author.
	  * @method		GetAuthor
	  * @returns	string containing the document's author.
	  */	
	function GetAuthor()
	{
		return $this->author;
	}

	/**
	  * Changes the document's name or identifier.
	  * @method		SetName
	  * @param		string name
	  * @returns	none
	  */			
	function SetName($name)
	{
		$this->name = $name;
	}

	/**
	  * Returns the document's name or identifier.
	  * @method		GetName
	  * @returns	string containing the document's name
	  */	
	function GetName()
	{
		return $this->name;
	}

	/**
	  * Changes the document's description.
	  * @method		SetDescription
	  * @param		string desc
	  * @returns	none
	  */			
	function SetDescription($desc)
	{
		$this->desc = $desc;
	}

	/**
	  * Returns the document's description.
	  * @method		GetDescription
	  * @returns	string containing the document's description.
	  */	
	function GetDescription()
	{
		return $this->desc;
	}

	/**
	  * Sets the blocks' path.
	  *
	  * @method		SetBlocksPath
	  * @param		string path
	  * @returns	none.
	  */		
	function SetBlocksPath($path)
	{
		$this->blocksPath = $path;
	}

	/**
	  * Returns the blocks' path.
	  *
	  * @method		GetBlocksPath
	  * @returns	string containing the blocks'  path.
	  */		
	function GetBlocksPath()
	{
		return $this->blocksPath;
	}		
	
	/**
	  * Assigns a value to a variable in the template document.
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
	  * Replaces the blocks in the template, for their corresponding code, and generates the actual xhtml document.
	  *
	  * @method		Generate
	  * @returns	true if success, false otherwise.
	  */		
	function Generate()
	{
		$success = false;
		$doc = $this->xml->getXMLString(0);

		$blocksArray =& $this->xml->getBranches("template:document", "template:block");
		$count = (empty($blocksArray) ? 0 : count($blocksArray));

		for($i = 0; $i < $count; $i++) {
			$block =& $blocksArray[$i];
	
			if(is_object($block) && get_class($block)=="xmlbranch") {

				// Check if it is a link to a block document or an embedded block.
				$src =& $block->getTagAttribute("src");

				if(!empty($src)) { // It is a link to a block document.
					$blockDoc =& new BlockDocument($this->GetBlocksPath().$src, "r");				

					$blockDoc->SetVars($this->variables);

					$doc = preg_replace("'<template:block[^>]*?name=\"".$block->getTagAttribute("name")."\".*?/>'si", $blockDoc->GetStringXhtml(), $doc);					
				} else { // It is an embedded block.

					$tagContent = $block->getTagContent();
						
					if(!empty($tagContent)) {
						$templateBlock =& new TemplateBlock($block);
						$templateBlock->SetVars($this->variables);						
						
						$doc = preg_replace("'<template:block[^>]*?name=\"".$block->getTagAttribute("name")."\".*?>.*?</template:block>'si", $templateBlock->GetStringXhtml(), $doc);
						$templateBlock = null;
					}
				}
				
				$blockDoc = null;
			}
		}

		$this->xml =& new XML();

		if($this->xml->parseFromString($doc)) {
			$success = true;
		}

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
		if($autoGenerate==true) {
			$this->Generate();
		}
		
		return $this->xml->getXML();
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

		// Clean string from template especific tags.
		$xhtml = $this->xml->getXMLString($formatString);
		$xhtml = preg_replace("'<template[^>]*?>'si", "",  $xhtml);
		$xhtml = preg_replace("'</template[^>]*?>'si", "",  $xhtml);		
		
		return $xhtml;
	}

	/**
	  * Saves the document to a supplied filename.
	  *
	  * @method		Save
	  * @param		string filename 	  
	  * @returns	true if succesful, false otherwise.
	  */	
	function Save($filename)
	{
		return parent::save($filename);
	}
}

?>