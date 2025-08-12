<?php
/*  
 * HVMenu.php	
 * Copyright (C) 2004, Alejandro Espinoza Esparza.
 *
 * Description :
 *   This class Serves as a generator for the javascript used in the HVMenu from Dynamic Drive: http://www.dynamicdrive.com
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
import("moebius2.base.FileManager");
import("moebius2.base.ObjectManager");
import("moebius2.navigation.NavigationDocument");

/**
  * This class Serves as a generator for the javascript used in the HVMenu from Dynamic Drive: http://www.dynamicdrive.com
  * @class		HVMenu
  * @package	moebius.navigation.generators
  * @author		Alejandro Espinoza &lt; <a href="mailto:aespinoza@structum.com.mx">aespinoza@structum.com.mx</a> &gt;
  * @version	1.3
  * @extends	ObjectManager
  * @requires	ObjectManager
  */
class HVMenu extends ObjectManager
{
	/* --- Attributes --- */
	var $templateFile;
	var $separator;

	var $parentWidth;
	var $parentHeight;
	var $childWidth;
	var $childHeight;

	/* --- Methods  ---- */
	/**
	  * Constructor, initializes the default options for the class.
	  * @method		HVMenu
	  * @param		string templateFile
	  * @returns	none
	  */	
	function HVMenu($templateFile)
	{
		ObjectManager::ObjectManager("moebius2.navigation.generators", "HVMenu");

		if(file_exists($templateFile)) {
			$this->templateFile = $templateFile;
		} else {
			$this->SendErrorMessage("HVMenu", "File doesn't Exists: ".$templateFile);
		}

		$this->parentWidth = 100;
		$this->parentHeight = 20;

		$this->childWidth = 150;
		$this->childHeight = 20;
	}
	
	/**
	  * Generates and Returns the actual menu script.
	  * @method		Generate
	  * @param		object menu
	  * @returns	string containing the script file for the HVMenu.
	  */	
	function GetScript($menu) 
	{
		global $CFG_DIR;
		
		// Load the template file with replaced variables
		$file =& new FileManager($this->templateFile, "r");		
		$script = preg_replace("/{%imgDir}/", $CFG_DIR["images"], $file->GetContents());

		$script = preg_replace("/{%numMenus}/", $menu->GetItemCount(), $script);		
		$file->Close();	   

		// Load Defined properties.
		$this->parentWidth  = $menu->GetProperty("parentWidth");
		$this->parentHeight = $menu->GetProperty("parentHeight");
		$this->childWidth  = $menu->GetProperty("childWidth");
		$this->childHeight = $menu->GetProperty("childHeight");

		// Generate the Menu Script
		$script .= "\n\n".$this->GenerateStructure($menu);
		
		return $script;
	}

	/**
	  * Generates the actual menu script structure. Since this method is recursive,
	  * the index is used to know the dept of the menu beign processed.
	  * @method		GenerateStructure
	  * @param		object menu
	  * @param		optional integer index
	  * @returns	string containing the menu structure of the script.
	  */	
	function GenerateStructure($menu, $index=0, $parent="") 
	{
		$count = $menu->GetItemCount();

		// Ignore the first menu tag since it is only there to group the menus to use.
		if($index==0) {
			$index = 1;
		} else {

			// Detect the dept of the menu to change the width.
			$width = (substr_count($parent, "_")==0 ? $this->parentWidth : $this->childWidth);
			$height = (substr_count($parent, "_")==0 ? $this->parentHeight : $this->childHeight);

			$script = "Menu".$parent.$index." = new Array(\"".$menu->GetTitle()."\", \"\", \"\", ".$count.", ".$height.", ".$width.");\n";
			$parent .= $index."_";
		}
		
		// Parse Items.
		$menu->Start();
		for($i = 1; $i <= $count; $i++) {
			if($menu->ItemIsMenu()) {
				$script .= "\n";
				$script .= $this->GenerateStructure($menu->GetItem(), $i, $parent);
			} else {
				$item = $menu->GetItem();
				$script .= "\tMenu".$parent.$i." = new Array(\"".$item->GetTitle()."\", \"\", \"\", 0, ".$this->childHeight.", ".$this->childWidth.");\n";
			}
			$menu->Next();
		}
		// FIXME: The separator is no included since it needs to be added to the submenu count.
		//$script .= "\tMenu".$parent.($i)." = new Array(\"".$this->separator."\", \"\", \"\", 0, 20, 200);\n";
		
		return $script;
	}

	/**
	  * Sets the separator after each menu ends.
	  * @method		SetSeparator
	  * @param		string separator
	  * @returns	none.
	  */	
	function SetSeparator($separator="·   ···········································   ·")
	{
		$this->separator = $separator;
	}
}

?>