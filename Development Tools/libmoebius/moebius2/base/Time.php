<?php
/*  
 * Time.php	
 * Copyright (C) 2003-2004, Alejandro Espinoza Esparza.
 *
 * Description :
 *   This class manages all the time services.
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

import("moebius2.base.Object");

/**
  * This class manages all the time services.
  * @class		Time
  * @package	moebius2.base
  * @author		Alejandro Espinoza &lt; <a href="mailto:aespinoza@structum.com.mx">aespinoza@structum.com.mx</a> &gt;
  * @version	1.2
  * @extends	Object
  * @requires	Object
  */
class Time extends Object
{
	/* --- Attributes --- */
	var $hour;
	var $minutes;
	var $seconds;

	/* --- Methods ---- */
	/**
	  * Constructor, initializes the default options and sets the object to the current time.
	  * @method		Time
	  * @returns	none
	  */	
	function Time()
	{
		Object::Object("moebius2.base","Time");	   

		$this->SetCurrentTime();
	}

	/**
	  * Changes the object's time value to the current time.
	  * @method		SetCurrentTime
	  * @returns	none
	  */	
	function SetCurrentTime()
	{
		$this->hour = intval(date("G"));
		$this->minutes  = intval(date("i"));
		$this->seconds  = intval(date("s"));
	}

	/**
	  * Changed the hour.
	  * @method		SetHour
	  * @param		int hour
	  * @returns	true if it was changed, false otherwise.
	  */	
	function SetHour($hour)
	{
		$success = FALSE;
		
		if($hour < 24) {
			$this->hour = $hour;
			$success = TRUE;
		}

		return $success;
	}

	/**
	  * Returns the hour set in the object.
	  * @method		GetHour
	  * @returns	number representing the hour
	  */	
	function GetHour()
	{
		return $this->hour;
	}

	/**
	  * Changed the minutes.
	  * @method		SetMin
	  * @param		int minutes
	  * @returns	true if it was changed, false otherwise.
	  */	
	function SetMin($minutes)
	{
		$success = TRUE;

		if($minutes < 60) {
			$this->minutes = $minutes;
			$success = TRUE;
		}
		
		return $success;
	}

	/**
	  * Returns the minutes set in the object.
	  * @method		GetMin
	  * @returns	number representing the minutes
	  */	
	function GetMin()
	{
		return $this->minutes;
	}

	/**
	  * Changed the seconds.
	  * @method		SetSec
	  * @param		int seconds
	  * @returns	true if it was changed, false otherwise.
	  */	
	function SetSec($seconds)
	{
		$success = TRUE;

		if($seconds < 60) {
			$this->seconds = $seconds;
			$success = TRUE;
		}
		
		return $success;
	}

	/**
	  * Returns the seconds set in the object.
	  * @method		GetSec
	  * @returns	number representing the seconds
	  */	
	function GetSec()
	{
		return $this->seconds;
	}	

	/**
	  * Returns the time in a defined format.
	  * Format varibales :
	  * - %H = 24 Hour Format (Int).
	  * - %h = 12 Hour Format (Int).
	  * - %i = Minutes (Int).
	  * - %s = Seconds (Int).
	  * - %x = Suffix "AM" or "PM" (Str).
	  * @method		GetFormatTime
	  * @param		optional string format
	  * @returns	string containing the time formated.
	  */	
	function GetFormatTime($format = "%h:%i:%s %x")
	{
		$formatedTime = "";
		$temp = "";
		$len = strlen($format);

		/* * * Parse format * * *
		 * If % found, then get next char.
		 * Then identify type. (Hour, Min or Sec)
		 * Identify property of type. 
		 * - %H = 24 Hour Format (Int).
		 * - %h = 12 Hour Format (Int).
		 * - %i = Minutes (Int).
		 * - %s = Seconds (Int).
		 * - %x = Suffix "AM" or "PM" (Str).
		 * Finally replace two characters % and char for the selected item.
 		 */

		//swipe the string
		for($i = 0; $i < $len; $i++)
		{
			//get first char
			$char = substr($format, $i, 1);
			
			//Check if it is a token...
			if($char == "%")
			{
				$i++;
				//Get Next char
				$char = substr($format, $i, 1);

				switch($char)
				{
				case "h":
					$temp = sprintf("%02d", $this->GetHour());
					break;
					
				case "H":
					$temp = sprintf("%02d", $this->Get12HourFormat($this->hour));
					break;
					
				case "i":
					$temp =  sprintf("%02d", $this->GetMin());
					break;
					
				case "s":
					$temp =  sprintf("%02d", $this->GetSec());
					break;

				case "x":
					$temp = $this->GetSuffix();
					break;
					
				default:
					$temp = "";
					break;
				}
				$formatedTime = $formatedTime . $temp;
			}
			else
				$formatedTime = $formatedTime . $char;
		}
		return $formatedTime;
	}

	/**
	  * Returns the hour in a 12hour format.
	  * @method		Get12HourFormat
	  * @returns	number representing the hour in a 12 hour format.
	  */	
	function Get12HourFormat($hour)
	{
		$resHour = 0;

		if($hour <= 12) {
			$resHour = $hour;
		} else {
			$resHour = $hour - 12;
		}

		return $resHour;
	}

	/**
	  * Returns the suffix for the given time. (Am or Pm)
	  * @method		GetSuffix
	  * @returns	string representing the suffix
	  */	
	function GetSuffix()
	{
		if($this->hour <= 11) {
			$suffix = "Am";
		} else {
			$suffix = "Pm";
		}

		return $suffix;
	}
			
}

?>