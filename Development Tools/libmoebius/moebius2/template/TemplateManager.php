<?php
/*  
 * TemplateManager.php	
 * Copyright (C) 2004, Alejandro Espinoza Esparza.
 *
 * Description :
 *   This class represents a template manager, for creating and parsing Template files.
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
import("moebius2.template.TemplateDocument");

/* --- Constants --- */
// Entity types
define("ENTITY_TEMPLATE", 0);
define("ENTITY_BLOCK", 1);

/**
  * Class represents a template manager, for parsing Template files. 
  * 
  *
  * @class		TemplateManager
  * @package	moebius2.template
  * @author		Alejandro Espinoza <aespinoza@structum.com.mx>
  * @version	1.2
  * @extends	ObjectManager
  * @requires	ObjectManager, XMLDocument
  * @see		XMLDocument
  */
class TemplateManager extends ObjectManager
{
	/* --- Attributes --- */
	var $templateDoc;
	var $paths;
	
	/* --- Methods --- */	
	/**
	  * Constructor, initializes the template manager.
	  * @method		TemplateManager
	  * @returns	none
	  */
	function TemplateManager()
	{
		ObjectManager::ObjectManager("moebius2.template", "TemplateManager");
	}   

	/**
	  * Sets the path for each template entity. i.e. entity = templates, path = templates/
	  *
	  * Entities :
	  * - ENTITY_TEMPLATE
	  * - ENTITY_BLOCK
	  *
	  * @method		SetPath
	  * @param		string entity
	  * @param		string path
	  * @returns	none.
	  */		
	function SetPath($entity, $path)
	{
		$this->paths[$entity] = $path;
	}

	/**
	  * Returns the selected entity's path.
	  *
	  * Entities :
	  * - ENTITY_TEMPLATE
	  * - ENTITY_BLOCK
	  *
	  * @method		SetPath
	  * @param		string entity
	  * @returns	string containing the entities' path.
	  */		
	function GetPath($entity)
	{
		return $this->paths[$entity];
	}	
	
	/**
	  * Changes the templateFile.
	  *
	  * @method		SetTemplateFile
	  * @param		string templateFile
	  * @returns	true if success, false otherwise.
	  */		
	function SetTemplateFile($templateFile)
	{
		$success = false;

		if(file_exists($this->GetPath(ENTITY_TEMPLATE).$templateFile))
		{
			$this->templateDoc =& new TemplateDocument($this->GetPath(ENTITY_TEMPLATE).$templateFile);
			$this->templateDoc->SetBlocksPath($this->GetPath(ENTITY_BLOCK));
			$success = true;
		} 
		
		return $success;
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
		$this->templateDoc->Assign($variable, $value);
	}

	/**
	  * Compiles the document.
	  *
	  * @method		Generate
	  * @returns	true if success, false otherwise.
	  */		
	function Generate()
	{
		return $this->templateDoc->Generate();
	}

	/**
	  * Returns the xhtml code; it can also build the Xhtml code for the document if autoGenerate is set to true (Default).
	  * @method		GetXhtml
	  * @param		optional bool autoGenerate 
	  * @returns	object of type XMLBranch containing the xhtml document.
	  */	
	function GetXhtml($autoGenerate=true)
	{		
		return $this->templateDoc->GetXhtml($autogenerate);
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
		return $this->templateDoc->GetStringXhtml($autoGenerate, $formatString);
	}	

	/**
	  * Saves the document to a supplied filename.
	  *
	  * @method		Save
	  * @param		string filename 	  
	  * @returns	true if succesful, false otherwise.
	  */	
	function SaveDocument($filename)
	{
		return $this->templateDoc->Save($filename);
	}	
}

?>