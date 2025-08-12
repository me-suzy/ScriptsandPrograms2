<?php
/*  
 * Menu.php	
 * Copyright (C) 2003-2004, Alejandro Espinoza Esparza.
 *
 * Description :
 *   This class manages the  menus and ocrresponding items of an application.
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
import("moebius2.navigation.MenuItem");

/**
  * This class manages the  menus and corresponding items of an application.
  * @class		Menu
  * @package	moebius.navigation
  * @author		Alejandro Espinoza &lt; <a href="mailto:aespinoza@structum.com.mx">aespinoza@structum.com.mx</a> &gt;
  * @version	0.8
  * @extends	ObjectManager
  * @requires	ObjectManager
  */
class Menu extends ObjectManager
{
	/* --- Attributes --- */	
	var $name;
	var $title;
	var $desc;	
	var $properties;
	var $items;


	var $cbManager;
	var $icon;

	var $pointer;

	/* --- Methods  ---- */
	/**
	  * Constructor, initializes the default options of the menu.
	  * @method		Menu
	  * @param		string name
	  * @param		string description
	  * @param		optional string cbManager
	  * @param		optional string icon
	  * @returns	none
	  */	
	function Menu($name, $title, $description, $cbManager=null, $icon=null)
	{
		ObjectManager::ObjectManager("moebius2.navigation", "Menu");				
		$this->name = $name;
		$this->title = $title;
		$this->description = $description;
		$this->cbManager = $cbManager;
		$this->icon = $icon;
		
		$this->pointer = 0;
	}

	/**
	  * Changes the menu's name or identifier.
	  * @method		SetName
	  * @param		string name
	  * @returns	none
	  */			
	function SetName($name)
	{
		$this->name = $name;
	}

	/**
	  * Returns the menu's name or identifier.
	  * @method		GetName
	  * @returns	string containing the menu's name
	  */	
	function GetName()
	{
		return $this->name;
	}

	/**
	  * Changes the menu's title..
	  * @method		SetTitle
	  * @param		string title
	  * @returns	none
	  */			
	function SeTitle($title)
	{
		$this->title = $title;
	}

	/**
	  * Returns the menu's title.
	  * @method		GetTitle
	  * @returns	string containing the menu's title
	  */	
	function GetTitle()
	{
		return $this->title;
	}	

	/**
	  * Changes the menu's description.
	  * @method		SetDescription
	  * @param		string desc
	  * @returns	none
	  */			
	function SetDescription($desc)
	{
		$this->desc = $desc;
	}

	/**
	  * Returns the menu's description.
	  * @method		GetDescription
	  * @returns	string containing the menu's description.
	  */	
	function GetDescription()
	{
		return $this->desc;
	}

	/**
	  * Changes the menu's callback Manager; which is in charge of menu events.
	  * @method		SetCbManager
	  * @param		string cbManager
	  * @returns	none
	  */			
	function SetCbManager($cbManager)
	{
		$this->cbManager = $cbManager;
	}

	/**
	  * Returns the menu's callback manager; which is in charge of menu events.
	  * @method		GetCbManager
	  * @returns	string containing the menu's cbManager.
	  */	
	function GetCbManager()
	{
		return $this->cbManager;
	}

	/**
	  * Changes the menu's icon.
	  * @method		SetIcon
	  * @param		string icon
	  * @returns	none
	  */			
	function SetIcon($icon)
	{
		$this->icon = $icon;
	}

	/**
	  * Returns the menu's icon.
	  * @method		GetIcon
	  * @returns	string containing the menu's icon.
	  */	
	function GetIcon()
	{
		return $this->icon;
	}

	/**
	  * Adds an item to the menu.
	  * @method		AddItem
	  * @param		string itemName
	  * @param		string itemTitle
	  * @param		string itemDesc
	  * @param		string itemCbManager
	  * @param		string itemIcon
	  * @returns	none
	  */			
	function AddItem($itemName, $itemTitle, $itemDesc, $itemCbManager, $itemIcon)
	{
		$count = count($this->items);
		$this->items[$count] =& new MenuItem($itemName, $itemTitle, $itemDesc, $itemCbManager, $itemIcon);
	}

	/**
	  * Creates and Adds an menu to the menu as an item.
	  * @method		AddMenu
	  * @param		string menuName
	  * @param		string menuDesc
	  * @param		string menuCbManager
	  * @param		string menuIcon
	  * @param		optional array menuItems
	  * @returns	none
	  */			
	function AddMenu($menuName, $menuTitle, $menuDesc, $menuCbManager, $menuIcon, $menuItems=null)
	{
		$count = count($this->items);
		$menu =& new Menu($itemName, $itemDesc, $itemCbManager, $itemIcon);

		$this->AddMenuObject($menu, $menuItems);
	}

	/**
	  * Adds a previously created menu object to the menu as an item.
	  * @method		AddMenuObject
	  * @param		object menu
	  * @param		optional array menuItems
	  * @returns	none
	  */			
	function AddMenuObject($menu, $menuItems=null)
	{
		if(get_class($menu)=="menu") {

			$count = count($this->items);			
			
			if(!is_null($menuItems)) {
				$menu->SetItems($menuItems);
			}
			
			$this->items[$count] = $menu;
		} else {
			$this->SendErrorMessage("AddMenuObject", "The parameter is not a valid menu class\n Class : ".get_class($menu));
		}
	}

	/**
	  * Changes the items array.
	  * @method		SetItems
	  * @param		array items
	  * @return		none
	  */	
	function SetItems($items)
	{
		return $this->items =& $items;
	}
	
	/**
	  * Returns the menu's items.
	  * @method		GetItems
	  * @returns	array containing the menu's items.
	  */	
	function GetItems()
	{
		return $this->items;
	}

	/**
	  * Returns the item at the position selected by the pointer; or the one set as parameter.
	  * If the parameter is not set, the item pointed is used.
	  * @method		GetItem
	  * @param		integer item
	  * @returns	object containing the menu item pointed.
	  */	
	function GetItem($item=null)
	{
		if(is_null($item)) {
			$item = $this->pointer;
		}
		
		return $this->items[$item];
	}	

	/**
	  * Adds a property to the menu.
	  * @method		AddProperty
	  * @param		string name
	  * @param		string value
	  * @returns	none.
	  */			
	function AddProperty($name, $value)
	{
		$this->properties[$name] = $value;
	}

	/**
	  * Returns the selected menu property.
	  * @method		GetProperty
	  * @param		string name
	  * @returns	variable containing the value of the property.
	  */	
	function GetProperty($name)
	{	  
		return $this->properties[$name];
	}	

	/**
	  * Returns the menu properties.
	  * @method		GetProperty
	  * @returns	array containing the menus properties.
	  */	
	function GetProperties()
	{	  
		return $this->property;
	}	


	/**
	  * Returns the menu data as a string.
	  * @method		ToString
	  * @param		optional string format
	  * @returns	string containing the menu's  data.
	  */	
	function ToString($format="[%n] -> %t (%d) :: %c\n")
	{
		$str = preg_replace("/%n/i", $this->name, $format);
		$str = preg_replace("/%t/i", $this->title, $str);
		$str = preg_replace("/%d/i", $this->description, $str);
		$str = preg_replace("/%c/i", $this->cbManager, $str);			   
	   
		// Parse items and print them.
		$count = count($this->items);

		for($i = 0; $i < $count; $i++) {
			$str .= "\t".$this->items[$i]->ToString()."\n";
		}
		
		return $str;
	}

	/**
	  * Returns the menu's item count.
	  * @method		GetItemCount
	  * @returns	integer containing the number of items in the menu.
	  */	
	function GetItemCount()
	{
		return count($this->items);
	}


	/**
	  * Sets the pointer to the start of the item's array.
	  * @method		Start
	  * @returns	none.
	  */	
	function Start()
	{
		$this->pointer = 0;
	}	
	
	/**
	  * Sets the pointer to the next item in the array.
	  * @method		Next
	  * @returns	none.
	  */	
	function Next()
	{
		$this->pointer++;
	}

	/**
	  * Sets the pointer to the end of the array.
	  * @method		End
	  * @returns	none.
	  */	
	function End()
	{
		$count = $this->GetItemCount();
		$this->pointer = $count--;
	}		
	
	/**
	  * TODO: Seeks for a certain item in the array set the pointer to that item's position.
	  * If the item was not found, the pointer is set to the start of the array.
	  * @method		Seek
	  * @returns	true if found, false otherwise.
	  */	
	function Seek()
	{
		return false;
	}

	/**
	  * Returns true if the pointed item is another menu, false other wise.
	  * @method		End
	  * @returns	true if the item is a menu object, false otherwise.
	  */	
	function ItemIsMenu()
	{
		$isMenu = false;

		if(get_class($this->GetItem())=="menu") {
			$isMenu = true;
		}
		
		return $isMenu;
	}		
}


?>