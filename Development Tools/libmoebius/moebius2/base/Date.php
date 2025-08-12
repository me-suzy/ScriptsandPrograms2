<?php
/*  
 * Date.php	
 * Copyright (C) 2003-2004, Alejandro Espinoza Esparza.
 *
 * Description :
 *   This class manages all the date services.
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

/* --- Constants --- */
// Language types
define("LANG_EN", 0);
define("LANG_SP", 1);

/**
  * This class manages all the date services.
  * @class		Date
  * @package	moebius2.base
  * @author		Alejandro Espinoza &lt; <a href="mailto:aespinoza@structum.com.mx">aespinoza@structum.com.mx</a> &gt;
  * @version	1.2
  * @extends	Object
  * @requires	Object
  */
class Date extends Object
{
	/* --- Attributes --- */	
	var $day;
	var $month;
	var $year;

	var $monthDays;
	var $isLeapYear;

	var $langNum;
	
	var $monthNames;
	var $dayNames;

	/* --- Methods  ---- */
	/**
	  * Constructor, initializes the default options and sets the object to the current date.
	  * @method		Date
	  * @returns	none
	  */	
	function Date()
	{
		Object::Object("moebiud.base","Date");
		
		// Default Values.
		$this->SetLang(LANG_EN);
		$this->isLeapYear = false;

		$this->LoadArrays();		

		$this->SetCurrentDate();
	}

	/**
	  * Loads the predefined array data.
	  * @method		LoadArrays
	  * @returns	none
	  */	
	function LoadArrays()
	{
		$this->monthDays = array(   0,  31,  59,  90, 120, 151, 181, 212, 243, 273, 304, 334 );
		
		//English (en)
		$this->monthNames[0] [0]  = "January";
		$this->monthNames[0] [1]  = "February";
		$this->monthNames[0] [2]  = "March";
		$this->monthNames[0] [3]  = "April";
		$this->monthNames[0] [4]  = "May";
		$this->monthNames[0] [5]  = "June";
		$this->monthNames[0] [6]  = "July";
		$this->monthNames[0] [7]  = "August";
		$this->monthNames[0] [8]  = "September";
		$this->monthNames[0] [9]  = "October";
		$this->monthNames[0] [10] = "November";
		$this->monthNames[0] [11] = "December";

		$this->dayNames[0] [0] = "Monday";
		$this->dayNames[0] [1] = "Tuesday";
		$this->dayNames[0] [2] = "Wednesday";
		$this->dayNames[0] [3] = "Thursday";
		$this->dayNames[0] [4] = "Friday";
		$this->dayNames[0] [5] = "Saturday";
		$this->dayNames[0] [6] = "Sunday";

		//Spanish (sp)
		$this->monthNames[1] [0]  = "Enero";
		$this->monthNames[1] [1]  = "Febero";
		$this->monthNames[1] [2]  = "Marzo";
		$this->monthNames[1] [3]  = "Abril";
		$this->monthNames[1] [4]  = "Mayo";
		$this->monthNames[1] [5]  = "Junio";
		$this->monthNames[1] [6]  = "Julio";
		$this->monthNames[1] [7]  = "Agosto";
		$this->monthNames[1] [8]  = "Septiembre";
		$this->monthNames[1] [9]  = "Octubre";
		$this->monthNames[1] [10] = "Noviembre";
		$this->monthNames[1] [11] = "Diciembre";

		$this->dayNames[1] [0] = "Lunes";
		$this->dayNames[1] [1] = "Martes";
		$this->dayNames[1] [2] = "Miercoles";
		$this->dayNames[1] [3] = "Jueves";
		$this->dayNames[1] [4] = "Viernes";
		$this->dayNames[1] [5] = "Sabado";
		$this->dayNames[1] [6] = "Domingo";
	}

	/**
	  * Changes language for date formatting.
	  * Lang Types :
	  * 0 - LANG_EN : English
	  * 1 - LANG_SP : Spanish
	  * @method		SetLang
	  * @param		int langNum
	  * @returns	none
	  */	
	function SetLang($langNum)
	{
		switch($langNum)
		{
		     case LANG_EN:
			    $this->langNum = 0;
			    break;
		     case LANG_SP:
				$this->langNum = 1;
				break;
		     default:
			    $this->langNum = 0;
			    break;
		}
	}    

	/**
	  * Changes the object's date value to the current date.
	  * @method		SetCurrentDate
	  * @returns	none
	  */	
	function SetCurrentDate()
	{
		$this->year  = intval(date("Y"));
		$this->month = intval(date("m"));
		$this->day   = intval(date("d"));
	}

	/**
	  * Changes the date to a defined value, in the followinf format: %d/%m/%Y
	  * @method		SetDate
	  * @param		string date
	  * @returns	true if the date wasa successfully changed, false otherwise.
	  */	
	// FIXME: It only works with the predefined format.
	function SetDate($date)
	{		
		$success = false;
		$format="%d/%m/%Y";
		$len = strlen($format);
		$delimeter = "/";

		$arr = split($delimeter, $strDate);
		$count = count($arr);
		
		//swipe the string
		for($i = 0, $y = 0; $i < $len; $i++) {
			
			//get first char
			$char = substr($format, $i, 1);
			
			//Check if it is a token...
			if($char == "%") {
				$i++;
				//Get Next char
				$char = substr($format, $i, 1);

				switch($char)
				{
				case "d":
					$this->SetDay(intval($arr[$y]));
					$y++;
					break;					
				case "m":
					$this->SetMonth(intval($arr[$y]));
					$y++;
					break;					
				case "Y":
					$this->SetYear(intval($arr[$y]));
					$y++;
					break;
				default:
					break;
				}
			}
		}

		if($y == $count) {
			$success = true;
		}

		return $success;
	}

	/**
	  * Returns the date in a defined format.
	  * Format varibales :
	  * - %W = Day of Week (Str).
	  * - %d = Day of Month (Int).
	  * - %M = Month of Year (Str).
	  * - %m = Month of Year (Int).
	  * - %y = Year two digits (Int).
	  * - %Y = Year four digits (Int).	  
	  * @method		GetFormatDate
	  * @param		optional string format
	  * @returns	string containing the date formated.
	  */	
	function GetFormatDate($format = "%d/%m/%Y")
	{
		$formatedDate = "";
		$temp = "";
		$len = strlen($format);

		/* * * Parse format * * *
		 * If % found, then get next char.
		 * Then identify type. (Day, Month or Year)
		 * Identify property of type. 
		 * - %W = Day of Week (Str).
		 * - %d = Day of Month (Int).
		 * - %M = Month of Year (Str).
		 * - %N = Month of Year three chars. (Str) 
		 * - %m = Month of Year (Int).
		 * - %y = Year two digits (Int).
		 * - %Y = Year four digits (Int).
		 * Finally replace two characters % and char for the selected item.
 		 */

		//swipe the string
		for($i = 0; $i < $len; $i++) {
			//get first char
			$char = substr($format, $i, 1);
			
			//Check if it is a token...
			if($char == "%") {
				$i++;
				//Get Next char
				$char = substr($format, $i, 1);

				switch($char)
				{
				case "W":
					$temp = $this->GetDayOfWeek();
					break;					
				case "d":
					$temp = strval($this->GetDay());
					break;					
				case "M":
					$temp = $this->monthNames[$this->langNum][$this->GetMonth()-1];
					break;
				case "N":
					$temp = $this->monthNames[$this->langNum][$this->GetMonth()-1];
					$temp = substr($temp, 0, 3);
					break;					
				case "m":
					$temp = $this->GetMonth();
					break;					
				case "y":
					$temp = substr($this->GetYear(), 2, 2);
					break;					
				case "Y":
					$temp = $this->GetYear();
					break;
				default:
					$temp = "";
					break;
				}
				$formatedDate .= $temp;
			} else {
				$formatedDate .= $char;
			}
		}
		
		return $formatedDate;
	}

	/**
	  * Returns the day of the week of the object's date.
	  * @method		GetDayOfWeek
	  * @returns	string representing the day of the week.
	  */	
	function GetDayOfWeek()
	{
		$dayOfWeek = 0;

		$year  = $this->year;
		$month = $this->month;
		$day   = $this->day;

		if($month > 2) {
			$month -= 2;
		} else {
			$month += 10;
			$year--;
		}

		$day = ( floor((13 * $month - 1) / 5) +
			     $day + ($year % 100) +
			     floor(($year % 100) / 4) +
			     floor(($year / 100) / 4) - 2 *
			     floor($year / 100) + 77);

		$dayOfWeek = (($day - 7 * floor($day / 7)));

		// Work as a Circular List
		if($dayOfWeek == 0) {
			$dayOfWeek = 7;
		}

		return $this->dayNames[$this->langNum][$dayOfWeek - 1];
	}

	/**
	  * Changed the year.
	  * @method		SetYear
	  * @param		int year
	  * @returns	true if it was changed, false otherwise.
	  */	
	function SetYear($year)
	{
		$success = false;
		
		if($year > 0) {
			$this->year = $year;
			$this->isLeapYear = $this->IsLeapYear($year);
			$success = true;
		}

		return $success;
	}

	/**
	  * Returns the year set in the object.
	  * @method		GetYear
	  * @returns	number representing the year
	  */	
	function GetYear()
	{
		return $this->year;
	}

	/**
	  * Changes de month.
	  * @method		SetMonth
	  * @param		int month
	  * @returns	tru if it was changed, false otherwise
	  */	
	function SetMonth($month)
	{
		$success = false;

		if($month > 0 && $month <= 12) {
			$this->month = $month;
			$success = true;
		}
		
		return $success;
	}

	/**
	  * Returns the month number or name.
	  * Type:
	  * m - Number
	  * M - Name
	  * @method		GetMonth
	  * @param		string type
	  * @returns	string or number representing the month
	  */	
	function GetMonth($type = "m")
	{
		if($type == "m") {
			return $this->month;
		} else {
			return $this->monthNames[$this->langNum][$this->month];
		}
	}

	/**
	  * Changes the day.
	  * @method		SetDay
	  * @param		int day
	  * @returns	tru if it was changed, false otherwise.
	  */	
	function SetDay($day)
	{
		$success = false;
		$month = $this->month;

		// Get Max Day Limit for that particular Month.
		if($month <= 11) {
			$iLimMax = $this->monthDays[$month] - $this->monthDays[$month - 1] + (($month >= 2) && $this->isLeapYear ? 1 : 0 );
		} else {
			$iLimMax = 31;
		}
		
		if($day > 0 && $day <= $iLimMax) {
			$this->day = $day;
			$success = true;
		}

		return $success;
	}

	/**
	  * Returns the day.
	  * Type:
	  * d - Number
	  * W - Name
	  * @method		GetDay
	  * @param		string type	  	  
	  * @returns	string or number representing the day.
	  */	
	function GetDay($type="d")
	{
		if($type == "d") {
			return $this->day;
		} else {
			return $this->GetDayOfWeek();
		}		
	}

	/**
	  * Identifies if the year is a leap year.
	  * @method		IsLeapYear
	  * @param		optional int year 	  	  
	  * @returns	true if the year is a leap year, false otherwise.
	  */	
	function IsLeapYear($year = 0)
	{
		if($year == 0) {
			$year = $this->year;
		}
			
		$isLeapYear =  ($year % 4 == 0) && (($year % 100 != 0) ||  ($year % 400 == 0));
		
		return $isLeapYear;
	}

	/**
	  * Returns the representation of the day in the year selected.
	  * @method		GetDay
	  * @returns	number that identifies a certain day ina year.
	  */	
	function DayOfYear()
	{
		return $this->monthDays[$this->month - 1] + $this->day + ($this->month > 2 && IsLeapYear($this->year) ? 1 : 0 );
	}
}


?>