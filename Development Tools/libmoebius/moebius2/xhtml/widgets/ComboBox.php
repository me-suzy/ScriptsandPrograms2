<?php
/*  
 * ComboBox.php	
 * Copyright (C) 2004, Alejandro Espinoza Esparza.
 *
 * Description :
 *   This class manages a combobox widget for standarized used in XHTML forms.
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
import("org.active-link.xml.XML");

/* --- Constants --- */	
define("COMBO_ITEM_VAL",       0);
define("COMBO_ITEM_LINK",      1);
define("COMBO_ITEM_DESC",      2);
define("COMBO_ITEM_ARR",       3);

/**
  * Class that manages a combobox widget for standarized used in XHTML forms.
  *
  *  The array structure for the Combo Item array is the following:
  *  array[index][0] = value
  *	 array[index][1] = link (optional, usually used for EventOnChange. This goes into the value field)
  *  array[index][2] = description
  *	
  *  The position of the values of the array usually end like this:
  *	  	<option value="array[index][1].array[index][0]"> array[index][2] </option>
  *
  * @class		ComboBox
  * @package	moebius2.xhtml.widgets
  * @author		Alejandro Espinoza <aespinoza@structum.com.mx>
  * @version	1.5
  * @extends	ObjectManager
  * @requires	ObjectManager, XML
  * @see		ObjectManager, XML
  */ 
class ComboBox extends ObjectManager
{
	/* --- Attributes --- */
	var $name;
	var $indexSelected;
	var $valueSelected;
	var $eventOnChange;	

	var $itemsArray;
	var $itemCount;	
	
	var $dummyName;
	var $dummyVal;       

	var $setEventOnChange;
	var $enabled;
	var $includeDummy;

	var $xhtml;

	var $typeSel;
	
	/* --- Methods --- */
	/**
	  * Constructor, initializes the combo widget.
	  * @method		ComboBox
	  * @param		string name
	  * @param		array valsArray
	  * @param		bool setEventOnChange
	  * @param		bool includeDummy
	  * @returns	none.
	  */	
	function ComboBox($name, $valsArray, $setEventOnChange=false, $includeDummy=false)
	{
		ObjectManager::ObjectManager("moebius2.xhtml.widgets", "ComboBox");
		$this->xhtml =& new XML("select");
		
		$this->name = $name;

		$this->itemCount = 0;
		$this->indexSelected=-1;

		$this->SetItems($valsArray);
		
		$this->SetEnable();
		
		$this->SetEventOnChange("if(options[selectedIndex].value) window.location.href=(options[selectedIndex].value)", $setEventOnChange);
		$this->SetIncludeDummy($includeDummy);
	}

	/**
	  * Enables or disables the widget.
	  *
	  * @method		SetEnable
	  * @param		optional bool enabled
	  * @returns	none.
	  */	
	function SetEnable($enabled=true)
	{
		$success = false;

		if(is_bool($enabled)) {
			$this->enabled = $enabled;
			$success = true;
		} else {
			$this->SendErrorMessage("SetEnable", "Variable not Boolean");
		}
		
		return $success;
	}

	/**
	  * Sets the selected combo item by index.
	  *
	  * @method		SetSelectedIndex
	  * @param		optional int item
	  * @returns	true if successful, false otherwise.
	  */	
	function SetSelectedIndex($index=0)
	{
		$success  = false;
		if($index>=0 && $index<=$this->itemCount) {
			$this->indexSelected=$index;
			$this->typeSel=0;
			$success = true;
		} else {
			$this->SendErrorMessage("SetSelectedItem", "Index out of range", "- Ini = 0"."<br>- Index =".$i."<br>- End = ".$this->itemCount);
		}

		return $success;
	}

	/**
	  * Sets the selected combo item by value.
	  *
	  * @method		SetSelectedValue
	  * @param		mixed value
	  * @returns	true if successful, false otherwise.
	  */	
	function SetSelectedValue($value)
	{
		$success = false;

		if(!empty($value))
		{
			$this->valueSelected = $value;
			$this->typeSel=1;			
			$success = true;
		}
		else
			$this->SendErrorMessage("SetSelectedText", "Variable Empty");

		return $success;
	}

	/**
	  * Inserts or removes the dummy from the item list.
	  *
	  * @method		SetIncludeDummy
	  * @param		optional bool include
	  * @returns	true if successful, false otherwise.
	  */	
	function SetIncludeDummy($include=true)
	{
		$success = false;

		if(is_bool($include)) {
			$this->includeDummy = $include;
			$success = true;
		} else {
			$this->SendErrorMessage("SetIncludeDummy", "Variable not Boolean");
		}
			
		return $success;
	}

	/**
	  * Sets the contents of the dummy item.
	  *
	  * @method		SetDummy
	  * @param		string name
	  * @param		mixed value
	  * @param		optional bool autoInclude
	  * @returns	true if successful, false otherwise.
	  */	
	function SetDummy($name, $value, $autoInclude=true)
	{
		$success = true;

		if(!empty($name)) {
			$this->dummyVal = $value;
			$this->dummyName = $name;
			
			if($bAutoInc) {
				$this->includeDummy = true;
			}
			
			$success = true;			
		} else {
			$this->SendErrorMessage("SetDummy", "Empty values in input", "- Name: ".$name."<br>- Value: ".$value."<br>- AutoInclude: ".($autoInclude ? "true" : "false"));
		}
			
		return $success;
	}

	/**
	  * Enables or disables event on change.
	  *
	  * @method		SetEnableEventOnChange
	  * @param		optional bool enable
	  * @returns	true if successful, false otherwise.
	  */	
	function SetEnableEventOnChange($enable=true)
	{
		$success = false;

		if(is_bool($enable)) {		
			$this->setEventOnChange = $enable;
			$success = true;
		} else {
			$this->SendErrorMessage("SetEnableEventOnChange", "Variable not Boolean");
		}			

		return $success;
	}

	/**
	  * Sets the event on change.
	  *
	  * @method		SetEventOnChange
	  * @param		string event
	  * @param		optional bool autoEnable
	  * @returns	true if successful, false otherwise.
	  */	
	function SetEventOnChange($event, $autoEnable=true)
	{
		$success = false;		

		if(!empty($event))
		{
			$this->eventOnChange = "onchange=\"".$event."\"";

			if($autoEnable){
				$this->SetEnableEventOnChange();
			}
			
			$success = true;
		} else {
			$this->SendErrorMessage("SetEventOnChange", "Empty values in input", "- Event: ".$event."<br>- AutoEnable: ".($autoEnable ? "true" : "false"));
		}

		return $success;
	}

	/**
	  * Sets the items for the combo box.
	  *
	  * @method		SetItems
	  * @param		array items
	  * @returns	true if successful, false otherwise.
	  */	
	function SetItems($items)
	{
		$success = false;
		
		$this->itemsArray = $items;			
		$this->itemCount = count($items);
		
		$success;

		return $success;
	}

	/**
	  * Adds an item to the item list.
	  *
	  * @method		AddItem
	  * @param		mixed value
	  * @param		string description
	  * @param		optional string link
	  * @returns	true if successful, false otherwise.
	  */	
	function AddItem($value, $description, $link="")
	{
		$success = false;
		
		if(!empty($value) && !empty($description)) {
			$id = $this->itemCount;
			$this->itemsArray[$iId][0] = $value;
			$this->itemsArray[$iId][1] = $link;
			$this->itemsArray[$iId][2] = $description;

			$this->itemCount++;
			
			$success = true;
		} else {
			$this->SendErrorMessage("AddItem", "Empty values in input", "- Value: ".$value."<br>- Desc: ".$description."<br>- Link: ".$link);
		}

		return $success;
	}

	/**
	  * Returns the name of the combo box.
	  *
	  * @method		GetName
	  * @returns	string containing the combobox's name.
	  */
	function GetName()
	{
		return $this->name;
	}

	/**
	  * Returns the selected item's index.
	  *
	  * @method		GetSelectedIndex
	  * @returns	integer containing the index of the selected item.
	  */	
	function GetSelectedIndex()
	{
		return $this->indexSelected;
	}

	/**
	  * Returns the selected item's value.
	  *
	  * @method		GetSelectedValue
	  * @returns	mixed containing the selected item's value.
	  */	
	function GetSelectedValue()
	{
		return $this->valueSelected;
	}

	/**
	  * Returns the event on change set.
	  *
	  * @method		GetEventOnChange
	  * @returns	string containing the event on change.
	  */	
	function GetEventOnChange()
	{
		return $this->eventOnChange;
	}

	/**
	  * Returns an item data from the combobox's item list.
	  *
	  * @method		GetItem
	  * @param		int index
	  * @param		optional int data
	  * @returns	mixed containing the data from an item.
	  */	
	function GetItem($index, $data=COMBO_ITEM_VAL)
	{
		if($i>=0 && $i<=$this->GetItemCount()) {
			switch($data)
			{
			case COMBO_ITEM_VAL:
				$var = $this->itemsArray[$index][0];
				break;
			case COMBO_ITEM_DESC:
				$var = $this->itemsArray[$index][2];				
				break;
			case COMBO_ITEM_LINK:
				$var = $this->itemsArray[$index][3];
				break;				
			case COMBO_ITEM_ARR:
				$var[0] = $this->itemsArray[$index][0];
				$var[1] = $this->itemsArray[$index][1];
				$var[2] = $this->itemsArray[$index][2];				
				break;
			}
		} else {
			$this->SendErrorMessage("GetItem", "Index out of range", "- Ini = 0"."<br>- Index =".$i."<br>- End = ".$this->itemCount);
		}
				     
		return $var;
	}

	/**
	  * Returns the number of items in the combobox's list.
	  *
	  * @method		GetItemCount
	  * @param		optional bool incDummyIncount
	  * @returns	integer containing the number of items in the combobox.
	  */	
	function GetItemCount($incDummyInCount=true)
	{
		$count = 0;
		
		if($incDummyInCount && $this->IsDummyIncluded()) {
			$count++;
		}

		$count += $this->itemCount;

		return $count;
	}

	/**
	  * Returns the dummy item's data.
	  *
	  * @method		GetDummy
	  * @param		optional int data
	  * @returns	mixed containing the data from the dummy item.
	  */	
	function GetDummy($data=COMBO_ITEM_VAL)
	{
		switch($data)
		{
		case COMBO_ITEM_VAL:
			$var = $this->dummyVal;
			break;
		case COMBO_ITEM_DESC:
			$var = $this->dummyName;				
			break;
		case COMBO_ITEM_ARR:
			$var[0] = $this->dummyVal;
			$var[1] = "";
			$var[2] = $this->dummyName;
			break;
		}
		
		return $var;
	}

	/**
	  * Returns true if combobox is enabled, false otherwise.
	  *
	  * @method		IsEnabled
	  * @returns	true if combobox is enabled, false otherwise.
	  */	
	function IsEnabled()
	{
		return $this->enabled;
	}

	/**
	  * Returns true if event on change is enabled, false otherwise.
	  *
	  * @method		IsEventOnChangeEnabled
	  * @returns	true if it is enabled, false otherwise.
	  */	
	function IsEventOnChangeEnabled()
	{
		return $this->setEventOnChange;
	}

	/**
	  * Returns true if dummy is included, false otherwise.
	  *
	  * @method		IsEnabled
	  * @returns	true if dummy is included, false otherwise.
	  */	
	function IsDummyIncluded()
	{
		return $this->includeDummy;
	}	

	/**
	  * Generates the actual widget, with the options selected before.
	  *
	  * @method		Generate
	  * @returns	true if successful, false otherwise.
	  */	
	function Generate()
	{
		$success = false;
		
		$combo  = "<select name=\"".$this->GetName()."\"";
			
		if($this->IsEventOnChangeEnabled()) {
			$combo .= $this->GetEventOnChange();
		}

		if(!$this->IsEnabled()) {
			$combo .= " disabled ";
		}

		$combo .= " >";
		
		// IncDummy means include first option as a dummy.
		if($this->IsDummyIncluded()) {
			if(($this->typeSel==0 && $this->GetSelectedIndex()==$this->dummyVal)  || ($this->typeSel==1 && $this->GetSelectedValue()==$this->dummyVal)) {
				$extra="selected";
			}
		
			$combo .= "<option value=\"".$this->GetDummy(COMBO_ITEM_VAL)."\" ".$extra.">".$this->GetDummy(COMBO_ITEM_DESC)."</option>\n";
		}

		$extra = "";

		$valuesArray = $this->itemsArray;
		
		for($i = 0; $i < count($valuesArray); $i++) {
			$value = $valuesArray[$i][1].$valuesArray[$i][0];
			
			if(($valuesArray[$i][0]==$this->GetSelectedIndex()) || ($this->GetSelectedValue()==$value)) {
				$extra = "selected=\"selected\"";
			} else {
				$extra = "";
			}

			$combo .= "<option value=\"".$value."\" ".$extra.">";
			$combo .= $valuesArray[$i][2]."</option>\n";
		}
		
		$combo .= "</select>\n";
		
		if($this->xhtml->parseFromString($combo)) {
			$success = true;
		}
		
		return $success;
	}

	
	/**
	  * Returns the xhtml code; it can also build the Xhtml code for the widget if autoGenerate is set to true (Default).
	  * @method		GetXhtml
	  * @param		optional bool autoGenerate 
	  * @returns	object of type XMLBranch containing the xhtml document.
	  */	
	function GetXhtml($autoGenerate=true)
	{		
		if($autoGenerate) {
			$this->Generate();
		}

		return $this->xhtml;
	}

	/**
	  * Returns the xhtml code in a string; it can also build the Xhtml code for the widget if autoGenerate is set to true (Default).
	  * It can also format the string, depending on if 'formatString' is set to true.
	  *
	  * @method		GetStringXhtml
	  * @param		optional bool autoGenerate
	  * @param		optional bool formatString 	  
	  * @returns	string containing the xhtml document.
	  */	
	function GetStringXhtml($autoGenerate=true, $formatString=true)
	{
		if($autoGenerate==true) {
			$this->Generate();
		}
		
		return $this->xhtml->getXMLString($formatString);
	}	
}

?>