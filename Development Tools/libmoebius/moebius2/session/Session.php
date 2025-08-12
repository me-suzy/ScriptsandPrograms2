<?php
/*  
 * Session.php	
 * Copyright (C) 2003-2004, Alejandro Espinoza Esparza.
 *
 * Description :
 *   This class manages all the session variables.
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
  * This class manages all session variables.
  * NOTE: The design of the class is based on the avoidance of cookies, since cookies are commonly considered harmful;
  * because PHP functions allow the use of other forms of session management, cookies were not used, instead Encripted URL IDs were used.
  *
  * @class		Session
  * @package	moebius2.session
  * @author		Alejandro Espinoza &lt; <a href="mailto:aespinoza@structum.com.mx">aespinoza@structum.com.mx</a> &gt;
  * @version	1.2
  * @extends	ObjectManager
  * @requires	ObjectManager
  */
class Session extends ObjectManager
{
	/* --- Attributes --- */
	
	/* --- Methods --- */
	/**
	  * Constructor, initializes the default options and starts the session is set.
	  * @method		Session
	  * @param 		optional bool startSession
	  * @returns	none.
	  */	
	function Session($startSession=true)
	{
		ObjectManager::ObjectManager("moebius2.session", "Session");

		if($startSession) {
			$this->Start();
		}
	}

	/**
	  * Starts the session, and the counter if set.
	  * @method		Start
	  * @param 		optional bool startCounter
	  * @returns	none.
	  */	
	function Start($startCounter=false)
	{
		session_start();
		header("Cache-control: private"); //IE 6 Fix
		
		if($startCounter) {
			$this->SetCounter();
		}
	}

	/**
	  * Destroys the session object and unsets all variables.
	  * @method		Destroy
	  * @returns	none.
	  */	
	function Destroy()
	{
		$this->DeleteAllVars();
		session_destroy();
	}

	/**
	  * Inserts a new variable.
	  * @method		InsertData
	  * @param 		mixed variable
	  * @param		mixed value
	  * @returns	none.
	  */	
	function InsertData($variable, $value)
	{
		if(!isset($_SESSION[$variable])) {
			$this->CreateVar($variable);
		}
		   
		$_SESSION[$variable] = $value;
	}

	/**
	  * Returns a variable's value.
	  * @method		GetData
	  * @param 		mixed variable
	  * @returns	mixed value of the session var selected.
	  */	
	function GetData($variable)
	{
		return $_SESSION[$variable];
	}

	/**
	  * Creates a new session variable.
	  * @method		CreateVar
	  * @param 		mixed variable
	  * @returns	none.
	  */
	function CreateVar($variable)
	{
		session_register($variable); 
	}

	/**
	  * Deletes a session variable.
	  * @method		DeleteVar
	  * @param 		mixed variable
	  * @param		optional bool unsetVar
	  * @returns	none.
	  */	
	function DeleteVar($variable, $unsetVar=true)
	{
		session_unregister($variable);
		
		if($unsetVar) {
			unset($variable);
		}
	}

	/**
	  * Deletes all session variables, thus unseting the session array.
	  * @method		DeleteAllVars
	  * @returns	none.
	  */	
	function DeleteAllVars()
	{
		session_unset();
	}

	/**
	  * Returns true if a variable exists in the session array.
	  * @method		IsVarCreated
	  * @param 		mixed variable
	  * @returns	true if variable is found, false otherwise.
	  */
	function IsVarCreated($variable)
	{
		$set=false;

		if(isset($_SESSION[$variable])) {
			$set=TRUE;
		}

		return $set;
	}

	/**
	  * Returns true if a variable has a value in the session array.
	  * @method		IsVarEmpty
	  * @param 		mixed variable
	  * @returns	true if variable has value, false otherwise.
	  */	
	function IsVarEmpty($variable)
	{
		$empty=true;

		if($_SESSION[$variable]!="EMPTY" && isset($_SESSION[$variable])) {
			$empty=false;
		}

		return $empty;
	}

	/**
	  * Returns the number of variables in the session array.
	  * @method		CountSessionVars
	  * @returns	integer representing the number of session variables.
	  */	
	function CountSessionVars()
	{
		return count($_SESSION);
	}

	/**
	  * Returns the content of the session array.
	  * @method		DumpSession
	  * @returns	array containing the session variables.
	  */	
	function &DumpSession()
	{
		return $_SESSION;
	}

	/**
	  * Returns true if the session is active.
	  * @method		IsSessionActive
	  * @returns	true if session is active, false otherwise.
	  */	
	function IsSessionActive()
	{
		$active=false;
		
		if(count($_SESSION)>0) {
			$active=true;
		}

		return $active;
	}

	/**
	  * Returns the session id.
	  * @method		GetSessionId
	  * @returns	string representing the id of the session.
	  */	
	function GetSessionId()
	{
		return session_id();		
	}

	/**
	  * Creates a session  counter.
	  * @method		CreateCounter
	  * @returns	none.
	  */	
	function CreateCounter()
	{
		$this->CreateVar("SESCOUNT");
		$this->InsertData("SESCOUNT", 0);
	}

	/**
	  * Changes the value of the counter to a new number.
	  * @method		SetCounter
	  * @param 		int num
	  * @returns	none.
	  */	
	function SetCounter($num)
	{
		if(!isset($_SESSION["SESCOUNT"])) {
			$this->CreateCounter();
		}
		
		$this->InsertData("SESCOUNT", $num);
	}

	/**
	  * Add a number to the counter.
	  * @method		AddToCounter
	  * @param 		optional int addNum
	  * @returns	none.
	  */	
	function AddToCounter($addNum=1)
	{
		if(!isset($_SESSION["SESCOUNT"])) {
			$this->CreateCounter();
		}		

		$count = $_SESSION["SESCOUNT"]+$addNum;
		$this->InsertData("SESCOUNT", $count);
	}

	/**
	  * Returns the counter value.
	  * @method		GetCounter
	  * @returns	integer representing the counter value.
	  */	
	function GetCounter()
	{
		return $_SESSION["SESCOUNT"];
	}

}

?>