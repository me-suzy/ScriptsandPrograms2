<?php
/*  
 * Object.php	
 * Copyright (C) 2003-2004, Alejandro Espinoza Esparza.
 *
 * Description :
 *   This class holds all the information regarding an object. This is an abstract class, it is not supposed
 *   to be used as an object, it is intended only for extension.
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

/**
  * This class holds all the information regarding an object. This is an abstract class, it is not supposed
  * to be used as an object, it is intended only for extension.
  * @class		Object
  * @package	moebius2.base
  * @author		Alejandro Espinoza &lt; <a href="mailto:aespinoza@structum.com.mx">aespinoza@structum.com.mx</a> &gt;
  * @version	1.2
  */
class Object
{
	/* --- Attributes --- */
	var $pkgName;
	var $className;
	
	/* --- Methods ---- */
	/**
	  * Constructor, initializes the object's default attributes like class name and package name.
	  * @method		Object
	  * @param		string pkgName	  
	  * @param		string className
	  * @returns	none
	  */	
	function Object($pkgName, $className)
	{
		$this->SetPackageName($pkgName);
		$this->SetClassName($className);	   
	}

	/**
	  * Changes the package name.
	  * @method		SetPackageName
	  * @param		string pkgName	  
	  * @returns	none
	  */	
	function SetPackageName($pkgName)
	{
		$this->pkgName = $pkgName;
	}

	/**
	 * Returns the package name.
	 * @method		GetPackageName
	 * @returns		package name
	 */	
	function GetPackageName()
	{
		return $this->pkgName;
	}

	/**
	  * Changes the class name.
	  * @method		SetClassName
	  * @param		string className	  
	  * @returns	none
	  */	
	function SetClassName($className)
	{
		$this->className = $className;
	}

	/**
	 * Returns the class name.
	 * @method		GetClassName
	 * @returns		class name
	 */
	function GetClassName()
	{
		return $this->className;
	}

	/**
	  * Generates a debug message.
	  * @method		DebugWriteLine
	  * @param		string message
	  * @returns	none
	 */	
	function DebugWriteLine($message)
	{
		if(DEBUG === true) {
			//TODO: Write to debug.html instead of to screen.
			print($message."<br>");
		}
	}

	function DebugConsole()
	{
		$console  = "<script language=\"JavaScript\">";
		$console .= "function debug_console() {";
		$console .= "window.open('debug.html', 'popup', 'width=200, height=200, menubar=no, scrollbars=yes, toolbar=no, location=no, resizable=yes, top=50, left=50');";
		$console .= "}";
		$console .= "</script>";

		return $console;
	}
}
?>