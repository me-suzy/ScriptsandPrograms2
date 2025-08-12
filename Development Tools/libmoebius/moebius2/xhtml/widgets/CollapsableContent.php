<?php
/*  
 * CollapsableContent.php	
 * Copyright (C) 2004-2005, Alejandro Espinoza Esparza.
 *
 * Description :
 *   This class manages a collapsable content table that can be used to save space.
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
  * Class manages a collapsable content table that can be used to save space.
  *
  *
  * @class		CollapsableContent
  * @package	moebius2.xhtml.widgets
  * @author		Alejandro Espinoza <aespinoza@structum.com.mx>
  * @version	1.0
  * @extends	ObjectManager
  * @requires	ObjectManager, XML
  * @see		ObjectManager, XML
  */ 
class CollapsableContent extends ObjectManager
{
	/* --- Attributes --- */
	var $name;
	var $title;
	var $content;

	var $xhtml;

	/* --- Methods --- */
	/**
	  * Constructor, initializes the widget. The name param should not have spaces.
	  * @method		CollapsableContent
	  * @param		string name
	  * @param		string title
	  * @param		string content
	  * @returns	none.
	  */	
	function CollapsableContent($name, $title, $content)
	{
		ObjectManager::ObjectManager("moebius2.xhtml.widgets", "CollapsableContent");
		$this->xhtml =& new XML("div");

		$this->SetName($name);
		$this->SetTitle($title);
		$this->SetContent($content);		
	}

	/**
	  * Enables or disables the widget.
	  *
	  * @method		SetEnable
	  * @param		optional bool enabled
	  * @returns	none.
	  */	
	function SetEnable($enabled=true)
	{
		$success = false;

		if(is_bool($enabled)) {
			$this->enabled = $enabled;
			$success = true;
		} else {
			$this->SendErrorMessage("SetEnable", "Variable not Boolean");
		}
		
		return $success;
	}

	/**
	  * Sets the name of the collapsable table.
	  *
	  * @method		SetName
	  * @param		string name
	  * @returns	none.
	  */	
	function SetName($name)
	{
		$this->name = $name;
	}

	/**
	  * Returns the name of the collapsable table
	  *
	  * @method		GetName
	  * @returns	string containing the collapsable table's name.
	  */
	function GetName()
	{
		return $this->name;
	}

	/**
	  * Sets the title of the collapsable table.
	  *
	  * @method		SetTitle
	  * @param		string title
	  * @returns	none.
	  */	
	function SetTitle($title)
	{
		$this->title = $title;
	}

	/**
	  * Returns the title of the collapsable table
	  *
	  * @method		GetTitle
	  * @returns	string containing the collapsable table's title.
	  */
	function GetTitle()
	{
		return $this->title;
	}

	/**
	  * Sets the content of the collapsable table.
	  *
	  * @method		SetContent
	  * @param		string content
	  * @returns	none.
	  */	
	function SetContent($content)
	{
		$this->content = $content;
	}

	/**
	  * Returns the content of the collapsable table
	  *
	  * @method		GetTitle
	  * @returns	string containing the collapsable table's content.
	  */
	function GetContent()
	{
		return $this->content;
	}

	/**
	  * Generates the actual widget, with the options selected before.
	  *
	  * @method		Generate
	  * @returns	true if successful, false otherwise.
	  */	
	function Generate()
	{
		global $CFG_DIR;

		$success = false;
		
		$widget = "<div class=\"collapseTable\">\n";

		$widget .= "<script type=\"text/javascript\" src=\"".$CFG_DIR['scripts']."/collapse.js\"></script>";

		$widget .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"2\" width=\"100%\" class=\"collapseTitle\">\n";
		$widget .= "<tr><td>".$this->GetTitle()."</td>\n";
		$widget .= "<td align=\"right\">\n";
		$widget .= "<a title=\"muestra/esconde\" id=\"".$this->GetName()."_link\" href=\"javascript: void(0);\" ";
		$widget .= " onclick=\"toggle(this, '".$this->GetName()."');\"  class=\"collapseToggle\">-</a>&nbsp;\n";
		$widget .= "</td></tr></table>\n";
		$widget .= "<div id=\"".$this->GetName()."\" class=\"collapseContent\">".$this->GetContent()."</div>\n";
		$widget .= "</div>";

		$widget .= "</div>\n";
		
		if($this->xhtml->parseFromString($widget)) {
			$success = true;
		}
		
		return $success;
	}

	
	/**
	  * Returns the xhtml code; it can also build the Xhtml code for the widget if autoGenerate is set to true (Default).
	  * @method		GetXhtml
	  * @param		optional bool autoGenerate 
	  * @returns	object of type XMLBranch containing the xhtml document.
	  */	
	function GetXhtml($autoGenerate=true)
	{		
		if($autoGenerate) {
			$this->Generate();
		}

		return $this->xhtml;
	}

	/**
	  * Returns the xhtml code in a string; it can also build the Xhtml code for the widget if autoGenerate is set to true (Default).
	  * It can also format the string, depending on if 'formatString' is set to true.
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
		
		return $this->xhtml->getXMLString($formatString);
	}	
}

?>