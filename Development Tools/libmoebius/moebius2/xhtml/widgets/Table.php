<?php
/*  
 * Table.php	
 * Copyright (C) 2004-2005, Alejandro Espinoza Esparza.
 *
 * Description :
 *   This class manages a table widget for standarized used in XHTML forms.
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
import("moebius2.xhtml.widgets.TableColumn");

/**
  * Class manages a table widget for standarized used in XHTML forms.
  *
  * @class		Table
  * @package	moebius2.xhtml.widgets
  * @author		Alejandro Espinoza <aespinoza@structum.com.mx>
  * @version	1.0
  * @extends	ObjectManager
  * @requires	TableColumn, ObjectManager, XML
  * @see		TableColumn, ObjectManager, XML
  */ 
class Table extends ObjectManager
{
	/* --- Attributes --- */
	var $cols;
	var $width;

	var $xhtml;

	/* --- Methods --- */
	/**
	  * Constructor, initializes the widget.
	  * @method		Table
	  * @param		string width
	  * @returns	none.
	  */	
	function Table($width)
	{
		ObjectManager::ObjectManager("moebius2.xhtml.widgets", "Table");
		$this->xhtml =& new XML();		

		$this->width = $width;
	}

	/**
	  * Adds a new column to the table.
	  *
	  * @method 	AddCol
	  * @param		string col
	  * @returns	none.
	  */
	function AddCol($col)
	{
		$this->cols[count($this->cols)] =& new TableColumn($col);
	}

	/**
	  * Adds a new row to the selected column.
	  *
	  * @method 	AddRow
	  * @param 		string col
	  * @param		string row
	  * @returns	none.
	  */
	function AddRowTo($col, $row)
	{
		$index = -1;

		for($i = 0; $i < count($this->cols); $i++) {
			if($this->cols[$i]->GetName() == $col) {
				$index = $i;
				break;
			}			
		}

		if($index >= 0) {
			$this->cols[$index]->AddRow($row);
		}
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

        $widget  = "<table width=\"".$this->width."\">";
        $widget .= "<tr>";

		// Swipe columns
		for($i = 0; $i < count($this->cols); $i++) {
			$widget .= "<td>".$this->cols[$i]->GetName()."</td>";
		}
		$widget .= "</tr>";		

		// TODO: Find a better solution for getting actual Row count.
		$rowCount = count($this->cols[0]->rows);

		// Swipe each row per column.
		for($i = 0; $i < $rowCount; $i++) {
			for($y = 0; $y < count($this->cols); $y++) {
				// Avoid malformation because of missing rows on columns.
				$content = $this->cols[$y]->GetRowAt($i);
				if(empty($content)) {
					$content = "&nbsp;";
				}

				$widget .= "<td>".$content."</td>";
			}
			$widget .= "</tr>";
		}

        $widget .= "</table>";		
		
		if($this->xhtml->parseFromString($widget)) {
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