<?php
/*  
 * MenuItem.php	
 * Copyright (C) 2003-2004, Alejandro Espinoza Esparza.
 *
 * Description :
 *   This class manages the items of a menu.
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

/**
  * This class manages the items of a menu.
  * @class		MenuItem
  * @package	moebius2.navigation
  * @author		Alejandro Espinoza &lt; <a href="mailto:aespinoza@structum.com.mx">aespinoza@structum.com.mx</a> &gt;
  * @version	1.3
  * @extends	ObjectManager
  * @requires	ObjectManager
  */
class MenuItem extends ObjectManager
{
	/* --- Attributes --- */	
	var $name;
	var $title;
	var $desc;
	var $icon;
	var $cbManager;

	/* --- Methods  ---- */
	/**
	  * Constructor, initializes the  menu item.
	  * @method		MenuItem
	  * @param		string name
	  * @param		string title
	  * @param 		string description
	  * @param		optional string cbManager
	  * @param		optional string icon
	  * @returns	none
	  */	
	function MenuItem($name, $title, $description, $cbManager=null, $icon=null)
	{
		ObjectManager::ObjectManager("moebius2.navigation", "MenuItem");		
		$this->name = $name;
		$this->title = $title;
		$this->description = $description;
		$this->cbManager = $cbManager;
		$this->icon = $icon;
	}

	/**
	  * Changes the item's name or identifier.
	  * @method		SetName
	  * @param		string name
	  * @returns	none
	  */			
	function SetName($name)
	{
		$this->name = $name;
	}

	/**
	  * Returns the item's name or identifier.
	  * @method		GetName
	  * @returns	string containing the item's name
	  */	
	function GetName()
	{
		return $this->name;
	}

	/**
	  * Changes the item's title.
	  * @method		SetTitle
	  * @param		string title
	  * @returns	none
	  */			
	function SetTitle($title)
	{
		$this->title = $title;
	}

	/**
	  * Returns the item's title.
	  * @method		GetTitle
	  * @returns	string containing the item's title.
	  */	
	function GetTitle()
	{
		return $this->title;
	}

	/**
	  * Changes the item's description.
	  * @method		SetDescription
	  * @param		string desc
	  * @returns	none
	  */			
	function SetDescription($desc)
	{
		$this->desc = $desc;
	}

	/**
	  * Returns the item's description.
	  * @method		GetDescription
	  * @returns	string containing the item's description.
	  */	
	function GetDescription()
	{
		return $this->desc;
	}

	/**
	  * Changes the item's callback Manager; which is in charge of menu events.
	  * @method		SetCbManager
	  * @param		string cbManager
	  * @returns	none
	  */			
	function SetCbManager($cbManager)
	{
		$this->cbManager = $cbManager;
	}

	/**
	  * Returns the item's callback manager; which is in charge of menu events.
	  * @method		GetCbManager
	  * @returns	string containing the item's cbManager.
	  */	
	function GetCbManager()
	{
		return $this->cbManager;
	}

	/**
	  * Changes the item's icon.
	  * @method		SetIcon
	  * @param		string icon
	  * @returns	none
	  */			
	function SetIcon($icon)
	{
		$this->icon = $icon;
	}

	/**
	  * Returns the item's icon.
	  * @method		GetIcon
	  * @returns	string containing the item's icon.
	  */	
	function GetIcon()
	{
		return $this->icon;
	}

	/**
	  * Returns the item data as a string.
	  * @method		ToString
	  * @param		optional string format	  
	  * @returns	string containing the item data.
	  */	
	function ToString($format="\t[%n] -> %t (%d) :: %c\n")
	{
		$str = preg_replace("/%n/i", $this->name, $format);
		$str = preg_replace("/%t/i", $this->title, $str);
		$str = preg_replace("/%d/i", $this->description, $str);
		$str = preg_replace("/%c/i", $this->cbManager, $str);
		
		return $str;
	}	
}


?>