<?php
/*  
 * NavigationManager.php	
 * Copyright (C) 2004, Alejandro Espinoza Esparza.
 *
 * Description :
 *   This class manages the generation of the menus.
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
import("moebius2.navigation.NavigationDocument");

/**
  * This class manages the generation of the menus.
  * @class		NavigationManager
  * @package	moebius.navigation
  * @author		Alejandro Espinoza &lt; <a href="mailto:aespinoza@structum.com.mx">aespinoza@structum.com.mx</a> &gt;
  * @version	0.8
  * @extends	ObjectManager
  * @requires	ObjectManager
  */
class NavigationManager extends ObjectManager
{
	/* --- Attributes --- */	
	var $navDoc;
	var $menuGenerator;

	/* --- Methods  ---- */
	/**
	  * Constructor, initializes the default options and loads the navigation document.
	  * @method		NavigationManager
	  * @param		bool autoParse
	  * @returns	none
	  */	
	function NavigationManager($autoParse=true)
	{
		global $CFG_DIR, $CFG_SYS;

		ObjectManager::ObjectManager("moebius2.navigation", "NavigationManager");
		$this->navDoc =& new NavigationDocument($CFG_DIR['templates']."/".$CFG_SYS['template']."/".$CFG_DIR['nav']."/default.xml");
		$this->menuGenerator = "HVMenu";

		if($autoParse) {
			$this->navDoc->ParseNav();
		}
	}

	/**
	  * Changes the menu generator for the navigation manager. 
	  * @method		SetMenuGenerator
	  * @param		string menuGenerator
	  * @return		none.
	  */
	function SetMenuGenerator($menuGenerator)
	{
		$this->menuGenerator = $menuGenerator;
	}

	/**
	  * Returns the menu script used the generator set.
	  * @method		GetMenuScript
	  * @return		string containing the menu script.
	  */
	function GetMenuScript()
	{
		global $CFG_DIR, $CFG_SYS;
		/* 
		 * The idea behind this script generator, is to load the generator class 
		 * dynamically, avoiding the loading of all generator classes that exist.
		 */
		import("moebius2.navigation.generators.".$this->menuGenerator);		

		$menuGen =& new $this->menuGenerator ($CFG_DIR['templates']."/".$CFG_SYS['template']."/".$this->menuGenerator."_template.js");
		return $menuGen->GetScript($this->navDoc->menu);
	}
}


?>