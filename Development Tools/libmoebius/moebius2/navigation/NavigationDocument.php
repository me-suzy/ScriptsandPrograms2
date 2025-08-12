<?php
/*  
 * NavigationDocument.php	
 * Copyright (C) 2004, Alejandro Espinoza Esparza.
 *
 * Description :
 *   This class manages the navigation document parsing menus and items of an application.
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
import("moebius2.navigation.Menu");

/**
  * This class manages the navigation document parsing menus and items of an application.
  * @class		NavigationDocument
  * @package	moebius.navigation
  * @author		Alejandro Espinoza &lt; <a href="mailto:aespinoza@structum.com.mx">aespinoza@structum.com.mx</a> &gt;
  * @version	1.5
  * @extends	ObjectManager
  * @requires	ObjectManager
  */
class NavigationDocument extends ObjectManager
{
	/* --- Attributes --- */	
	var $navDoc;

	var $menu;

	/* --- Methods  ---- */
	/**
	  * Constructor, initializes the default options and loads the navigation document if set autoParse.
	  * @method		NavigationDocument
	  * @param		string navDocument
	  * @param		bool autoParse
	  * @returns	none
	  */	
	function NavigationDocument($navDocument, $autoParse=true)
	{
		ObjectManager::ObjectManager("moebius2.navigation", "NavigationDocument");		
		$this->navDoc = $navDocument;
		if($autoParse) {
			$this->ParseNav();
		}
	}

	/**
	  * Parses the XML navigation document into the object.
	  * @method		ParseNav
	  * @returns	none
	  */	
	function ParseNav() 
	{
		$xmlDoc =& new XMLDocument($this->navDoc, "r");
		$xml =& $xmlDoc->getXML();

		$this->menu =& $this->ParseMenu($xml);		
	}

	/**
	  * Parses the XML menu into the object; this function is recursive for all submenus.
	  * @method		ParseMenu
	  * @returns	object containing the menu parsed.
	  */	
	function ParseMenu($xml)
	{		
		global $CFG_DIR, $CFG_SYS;

		if(get_class($xml)=="xml" || get_class($xml)=="xmlbranch") {
			
			// Determine if it is a link, or an embedded menu.
			$src = $xml->getTagAttribute("src");
			if (!empty($src)) {
				if(file_exists($CFG_DIR['templates']."/".$CFG_SYS['template']."/".$CFG_DIR['nav']."/".$src)) {
					$xmlDoc =& new XMLDocument($CFG_DIR['templates']."/".$CFG_SYS['template']."/".$CFG_DIR['nav']."/".$src);
					$xml =& $xmlDoc->getXML();
				} else {
					$this->SendErrorMessage("ParseMenu", "File doesn't exist :".$CFG_DIR['templates']."/".$CFG_SYS['template']."/".$CFG_DIR['nav']."/".$src);
				}
			}

			// Load the menu object and load the menu header.
			$menu =& new Menu($xml->getTagAttribute("name"),
							  $xml->getTagAttribute("title"), 							  
							  $xml->getTagAttribute("description"),
							  $xml->getTagAttribute("cbManager"),
							  $xml->getTagAttribute("icon"));

			/* First parse the menu properties if found */
			$arrProps =& $xml->getBranches("navigation:menu", "navigation:property");			
			$count = count($arrProps);

			for($i = 0; $i < $count; $i++) {
				$prop =& $arrProps[$i];

				if(get_class($prop)=="xmlbranch") {
					$menu->AddProperty($prop->getTagAttribute("name"), $prop->getTagContent());
				}
			}

			/*
			 * The first idea for parsing the items, was to use an index as an attribute 
			 * to preseve item order; whether it was another menu or an item. 
			 * But the idea was dropped to avoid the overhead, since the xml method getBranches
			 * returns an ordered array which actually preserves the order of the items.
			 */
			$arrMenu =& $xml->getBranches("navigation:menu");			

			$count = count($arrMenu);
			
			for($i = 0; $i < $count; $i++) {
				// Get the first item.
				$item =& $arrMenu[$i];

				if(get_class($item)=="xmlbranch") {

					// Determine whether the item is another menu or not.
					if (preg_match("/navigation:menu/i", $item->getXMLString())) {
						$menu->AddMenuObject($this->ParseMenu($item));
					} else {
						if(preg_match("/navigation:item/i", $item->getXMLString())) {
							$menu->AddItem($item->getTagAttribute("name"), 
										   $item->getTagContent(), 
										   $item->getTagAttribute("description"), 
										   $item->getTagAttribute("cbManager"), 
										   $item->getTagAttribute("icon"));
						}
					}
				}
			}
		} else {
			$this->SendErrorMessage("ParseMenu", "The parameter 'xml' is not a valid xmlBranch class\n Class : ".get_class($xml));
		}
		
		return $menu;
	}

	/**
	  * Returns the navigation data as a string.
	  * @method		ToString
	  * @returns	string containing the navigation's data.
	  */	
	function ToString()
	{				
		return $this->menu->ToString();
	}	
}

?>