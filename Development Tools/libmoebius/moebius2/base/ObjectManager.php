<?php
/*  
 * ObjectManager.php	
 * Copyright (C) 2004, Alejandro Espinoza Esparza.
 *
 * Description :
 *   This file represents a simple base class for all other objects on the
 *   Framework, so that all objects are treated the same. For example an
 *   unified error manager for all classes in an application.
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

import("moebius2.base.ErrorManager");

/**
  * This file represents a simple base class for all other objects on the
  * Framework, so that all objects are treated the same. For example an
  * unified error manager for all classes in an application.
  * @class		ObjectManager
  * @package	moebius2.base
  * @author		Alejandro Espinoza <aespinoza@structum.com.mx>
  * @version	0.6
  * @extends	Object
  * @requires	ErrorManager, Object
  * @see		ErrorManager
  */
class ObjectManager extends Object
{
	/* --- Attibutes --- */
	var $errorManager;
	var $startTime;
	
	/* --- Methods ---- */
	/**
	  * Constructor, initializes the object's default attributes like the error manager, class name and package name.
	  * @method		Object
	  * @param		string pkgName	  
	  * @param		string className
	  * @returns	none
	  */	
	function ObjectManager($pkgName, $className)
	{
		Object::Object($pkgName, $className);
		
		$this->errorManager =& new ErrorManager($pkgName, $className);
	}

	/**
	  * Changes the error manager.
	  * @method		SetErrorManager
	  * @param		ErrorManager errorManager	  
	  * @returns	none.
	  */
	function SetErrorManager($errorManager)
	{
		if(is_object($argument) && get_class($argument) == "errormanager") {			
			$this->errorManager = $errorManager;
		} else {
			$this->SendErrorMessage("SetErrorManager", "Argument is not a valid ErrorManager Object.");
		}			
	}

	/**
	  * Sends a Message to the error manager.
	  * @method		SendErrorMessage
	  * @param		string methodName
	  * @param		string message
	  * @param		optional const int priority
	  * @returns	none
	  */	
	function SendErrorMessage($methodName, $message, $priority=null)
	{
		if(get_class($this->errorManager) == "errormanager") {
			$this->errorManager->SendErrorMessage($methodName, $message, $priority);
		} else {
			echo $message;		   
		}
	}

	/**
	  * Sets the start time for an object to load.
	  * @method		SetStartTime
	  * @returns	none.
	  */	
	function SetStartTime()
	{
		$time = microtime();
		$time = explode(" ",$time);
		$time = $time[1] + $time[0];

		$this->startTime = $time; 
	}

	/**
	  * Returns the time elapsed between the start time set, and the moment GetLoadTime is called.
	  * @method		GetLoadTime
	  * @returns	none.
	  */	
	function GetLoadTime()
	{
		$time = microtime();
		$time = explode(" ",$time);
		$time = $time[1] + $time[0];
		$endTime = $time;

		return ($endTime - $this->startTime);

	}
}
?>