<?php
/*  
 * ReportTable.php	
 * Copyright (C) 2004-2005, Alejandro Espinoza Esparza.
 *
 * Description :
 *   This class manages a table widget for reports.
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

import("moebius2.xhtml.widgets.Table");

/**
  * Class manages a table widget for reports. This table is based heavily on CSS.
  * These styles should be in a separate file.
  *
  * Proposed Styles for CSS:
  * table.reportTable {
  *  margin: 0px;
  *  border: 1px #808080 solid;
  *  padding: 0px;
  *  }
  *
  * table.reportTable td {
  *   padding: 4px;
  *   margin: 0px;
  * }
  *    
  * tr.reportTableHead td {
  *   margin: 0px;
  *   padding: 4px;
  *   background-color: #AAAAAA;
  *   color: white;
  *   width: 50%; 
  * }
  * 
  * table.reportTable tr.reportTableEvenRow {
  *   background-color: #e0e0e0;
  * }  
  *   
  * @class		ReportTable
  * @package	moebius2.xhtml.widgets
  * @author		Alejandro Espinoza <aespinoza@structum.com.mx>
  * @version	1.0
  * @extends	Table
  * @requires	Table, ObjectManager, XML
  * @see		Table, ObjectManager, XML
  */ 
class ReportTable extends Table
{
	/* --- Attributes --- */

	/* --- Methods --- */
	/**
	  * Constructor, initializes the widget.
	  * @method		ReportTable
	  * @param		string width
	  * @returns	none.
	  */	
	function ReportTable($width)
	{
		parent::Table($width);
		ObjectManager::ObjectManager("moebius2.xhtml.widgets", "ReportTable");
		$this->xhtml =& new XML();		
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

        $widget  = "<table width=\"".$this->width."\" class=\"reportTable\" cellspacing=\"0\">";
        $widget .= "<tr class=\"reportTableHead\">";

		// Swipe columns
		for($i = 0; $i < count($this->cols); $i++) {
			$widget .= "<td>".$this->cols[$i]->GetName()."</td>";
		}
		$widget .= "</tr>";

		// TODO: Find a better solution for getting actual Row count for each column.
		$rowCount = count($this->cols[0]->rows);

		// Swipe each row per column
		for($i = 0; $i < $rowCount; $i++) {

			// If it is even, use change the style.
			if( (($i+1) % 2) == 0 ) {
				$widget .= "<tr class=\"reportTableEvenRow\">";
			} else {
				$widget .= "<tr>";				
			}

			for($y = 0; $y < count($this->cols); $y++) {
				// Avoid malformation because of missing rows on columns.
				if(get_class($this->cols[$y])=="tablecolumn") {
					$content = $this->cols[$y]->GetRowAt($i);
				}

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
}

?>