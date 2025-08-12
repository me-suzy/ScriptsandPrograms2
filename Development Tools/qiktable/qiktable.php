<?php

class QikTable
{
	/**
	QikTable Class
	Version: 2.3
	Last Update: September 8, 2002
	Author: Dennis Shearin
	Copyright Dennis Shearin, 2001, 2002

	This class can be used to quickly and easily create an HTML table. Many of the tables parameters (border, alignment, color, etc.) can be set for the table as a whole or for individual rows, columns or cells. Several methods are provided to aid in importing data from various types of databases. There is also support for CSS and templates, which can be used to give tables a consistent look.

	See <a href="http://www.qiktable.com/license.txt">license.txt</a> for terms of use.
	*/

	/*
	V. 2.3 Notes:
	addParameters() - takes an array and adds it to the $this->parameters array. If arg is not an array, make it an array before adding.
	setParameter() - sets the value of a single parameter, existing values are overwritten or new key/value pair is added.
	getParameter() - gets the value of a single parameter.
	*/

	// Properties

	// Data
	var $contents; 				// 2-D numeric array
	var $headings;				// Numeric array
	var $comment;				// Printed as HTML comment before the table
	var $parameters = array();	// Parameters to be used by template (assoc array of key/value pairs)
	var $data;					// Raw data to be processed by template

	// Table properties
	var $tableAttribs = array();
	var $rowPriority = FALSE;

	// Heading Properties
	var $headingAttribs = array();

	// Content Properties
	var $contentAttribs = array();

	// Cell Properties
	var $cellAttribs = array();
	var $headingCellAttribs = array();

	// Column properties
	var $colAttribs = array();
	var $colCount = 0;

	// Row Properties
	var $rowAttribs = array();

	/**
	@section Methods
	*/

	// Constructor
	/**
	@subsection Constructor
	*/

	function QikTable($contents = NULL, $headings = NULL, $comment = NULL, $parameters = NULL, $data = NULL)
	{
		/**
		The constructor takes five optional parameters.
		The first, $contents,  must be a two dimensional indexed array representing the row and column data for the table.
		The second, $headings, is a one dimensional indexed array containing the text for the headings. Various methods are provided to set, reset or clear these parameters after instantiation.
		The third, $comment, is a string that will be output as an HTML comment immediately above the table.

		The last two, $parameters and $data, are not used by QikTable or any of its methods. Their purpose is to provide a way of passing parameters to templates - eliminating the need for using global variables. While there are no restrictions on the type or content of these variables, $parameters is intended to be an associative array containing the name/values pairs of parameters to be used by the template. $data can be used for storing raw data to be processed by the template.

		Nothing is output at the time the QikTable object is instantiated. Methods are provided to print the table once all parameters have been set.
		*/

		$this->contents = $contents;
		$this->headings = $headings;
		$this->comment = $comment;
		$this->data = $data;

		if (isset($parameters))
		{
			if (is_array($parameters))
			{
				$this->parameters = $parameters;
			}
			else
			{
				$this->parameters = array($parameters);
			}
		}
	}

	// Using Template
	/** @subsection Templates
	*/

	function includeTemplate($templatefile)
	{
		/**
		This method allows you to apply preconfigured templates to QikTable objects, making it easy to create tables with a consistent look. This method can be used alone or in conjuction with other QikTable methods. QikTable does not check for conflicts between the parameters in the template and the other methods called. It is the job of the browser to reconcile these conflicts and the results will depend, in part, on the order in which the methods were called.

		Template files are simply standard PHP files. Any valid PHP command can be included and all these commands must be contained within a PHP script tag. QikTable methods can be invoked by preceding the method call with the keyword "$this->" (i.e., $this->setBorder(2);). Anything that appears outside of PHP tags will be interpreted as HTML. This makes it possible to add titles or captions to your tables using templates.

		6/2/02 - This method will pass the return value of a template back to the calling program.
		*/
		$returnValue = include($templatefile);
		return $returnValue;
	}

	function useTemplate($templatefile)
	{
		/**
		This is an alias for includeTemplate which reflects its more active role (i.e. macro capability). This method allows you to apply preconfigured templates to QikTable objects, making it easy to create tables with a consistent look. This method can be used alone or in conjuction with other QikTable methods. QikTable does not check for conflicts between the parameters in the template and the other methods called. It is the job of the browser to reconcile these conflicts and the results will depend, in part, on the order in which the methods were called.

		Template files are simply standard PHP files. Any valid PHP command can be included and all these commands must be contained within a PHP script tag. QikTable methods can be invoked by preceding the method call with the keyword "$this->" (i.e., $this->setBorder(2);). Anything that appears outside of PHP tags will be interpreted as HTML. This makes it possible to add titles or captions to your tables using templates.
		*/
		$returnValue = include($templatefile);
		return $returnValue;
	}

	// QikTable Properties
	/** @subsection QikTable Properties
	*/

	function setComment($comment)
	{
		/**
		This method sets the comment to be printed before the QikTable. A string is expected.
		*/

		$this->comment = $comment;
	}

	function addComment($comment)
	{
		/**
		This method appends text to the comment to be printed before the QikTable. A string is expected.
		*/

		$this->comment .= $comment;
	}

	function getComment()
	{
		/**
		This method returns the comment to be printed before the QikTable.
		*/

		return $this->comment;
	}

	function setParameters($parameters)
	{
		/**
		This method sets the contents of the Parameters variable. It is expected to be an associative array containing name/value pairs of parameters to be used in a template.

		V.2/25: If $parameters is not an array, it will be converted to an array. If the $parameters argument is not supplied, then the $paramters array will be reset to an empty array.
		*/

		if (isset($parameters))
		{
			if (is_array($parameters))
			{
				$this->parameters = $parameters;
			}
			else
			{
				$this->parameters = array($parameters);
			}
		}
		else
		{
			$this->parameters = NULL;
		}
	}

	function getParameters()
	{
		/**
		This method returns the contents of the Parameters variable.
		*/

		return $this->parameters;
	}

	function addParameters($parameters)
	{
		/**
		This method adds values to the Parameters array. If the $parameters argument is not an array, it will be converted to an array before merging it with the existing parameters.

		Values for any matching key will be overwritten with the new value.
		*/
		if (isset($parameters))
		{
			if (is_array($parameters))
			{
				$this->parameters = array_merge($this->parameters, $parameters);
			}
			else
			{
				$this->parameters = array_merge($this->parameters, array($parameters));
			}
		}
	}

	function setParameter($value, $key = NULL)
	{
		/**
		This method sets the value of a single parameter. If the parameter already exists, its value is overwritten. Otherwise, a new parameter is added.

		Note the order of the arguments - value is first, followed by the key. While this might not seem intuitive, there is a reason for this order. It enables you to pass values without keys. Those values then have numerical indexes in the parameters array.
		*/
		if (isset($key))
		{
			$parameter = array($key => $value);
			$this->parameters = array_merge($this->parameters, $parameter);
		}
		else
		{
			$parameter = array($value);
			$this->parameters = array_merge($this->parameters, $parameter);
		}
	}

	function getParameter($key)
	{
		/**
		This method returns the value of a single parameter.
		*/

		return $this->parameters[$key];
	}

	function setData($data)
	{
		/**
		This method sets the contents of the Data section. This data storage area is intended for raw data to be processed by a template and can be in any format.
		*/

		$this->data = $data;
	}

	function getData()
	{
		/**
		This method returns the comment to be printed before the QikTable.
		*/

		return $this->data;
	}


	// Building Model
	/** @subsection Add Data
	*/


	function addRow($newrow)
	{
		/**
		This method adds one row of data to the end of the table. The parameter is expected to be an indexed array.

		This method is ideal for building an HTML table from an SQL table. The output of each mysql_fetch_row statement can be used as the argument to the addRow method.
		*/

		$this->contents[] = $newrow;
	}

	function addArray($inArray)
	{
		/**
		This method also takes an array as input and adds one row of data to the end of the table. Unlike addRow, this method will also process associative arrays, as well as non-sequential or non-zero-based indexed arrays. It can be used in conjunction with the mysql_fetch_assoc function.
		*/

		$i = 0;
		while (list($key, $value) = each($inArray))
		{
			$newrow[$i++] = $value;
		}
		$this->addRow($newrow);
	}

	function addLine($line, $sep = "\t")
	{
		/**
		Like the above methods, this adds one row of data to the end of the table, but it extracts that data from a string rather than an array. The second parameter is an optional separator character which is used to break the string into array elements. If the separator is not specified, then the default character (tab) is used.

		This method is ideal for building an HTML table from CSV (comma separated values) or tab-delimited flat file databases.
		*/

		$rowArray = explode($sep, $line);
		$this->addRow($rowArray);
	}

	function addItem($item, $newRow = FALSE)
	{
		/**
		This method adds one data item the end of a table row. If the second optional parameter evaluates to TRUE, then a new row is started before the item is added.
		*/

		if ($newRow)
		{
			$this->addRow(array($item));
		}
		else
		{
			// This may get a little more elaborate if/when the wrap function is implemented
			$currRow = ($this->getRowCount() - 1);
			$this->contents[$currRow][] = $item;
		}
	}

	function setItem($item, $row, $col)
	{
		/**
		This method sets the contents of a specific cell to the value passed in $item. If that cell does not exist in the table, it is added. Otherwise, the current contents are overwritten.
		*/

		$this->contents[$row][$col] = $item;
	}

	function getItem($row, $col)
	{
		/**
		This method gets the contents of a specific cell.
		*/

		return $this->contents[$row][$col];
	}

	function setContents($contents)
	{
		/**
		This method fills the Contents array. The argument passed must be a two-dimensional indexed array containing the data that will fill the rows and columns of the table's body. The previous contents of the table are overwritten.
		*/

		$this->contents = $contents;
	}

	function addContents($contents)
	{
		/**
		This method is exactly like the method above except that it appends the new contents to the end of any data that has already been added to the table.
		*/

		$this->contents = array_merge($this->contents, $contents);
	}

	function clearContents()
	{
		/**
		This method clears all data in the body of the table.
		*/

		$this->setContents(NULL);
	}

	function getContents()
	{
		/**
		This method can be used to retrieve the current contents of the QikTable. It returns a two-dimensional array representing the rows and columns of the table, or NULL if the contents have been cleared or not set.
		*/

		return $this->contents;
	}


	function setHeadings($headings)
	{
		/**
		This method can be used to set the headings for the table. It accepts an array containing the headings. The previous contents of the headings array are overwritten.
		*/

		$this->headings = $headings;
	}

	function clearHeadings()
	{
		/**
		This method clears the headings for the table.
		*/

		$this->setHeadings(NULL);
	}

	function setHeadingsFromKeys($assocArray)
	{
		/**
		This is a qik and easy way to set up the headings from the keys in an associative array. The keys are extracted and the first letter of each key is capitalized. The result from the mysql_fetch_assoc function can be used as an argument to this function.
		*/

		$arraySize = count($assocArray);
		reset($assocArray);
		for ($i = 0; $i < $arraySize; $i++)
		{
			$this->headings[$i] = ucfirst(key($assocArray));
			next($assocArray);
		}
	}

	function getHeadings()
	{
		/**
		This method can be used to retrieve the current headings for the QikTable. It returns a one-dimensional indexed array containing the column headings, or NULL if the headings have been cleared or not set.
		*/

		return $this->headings;
	}


	/** @subsection Set Number of Columns
	*/

	function setColCount($numOfCols)
	{
		/**
		This method can be used to set the number of columns to be displayed in the table. If the number of columns is not set by calling one of these methods, then each row of the table will contain the number of entries in that row of the array. No checking is done to make sure that the number of cells is the same in each row of the table. The major browsers don't seem to have a problem formatting uneven rows, but it's not good form.

		This method can also be used to limit the number of columns printed. For example, if your array contains five elements per row, but you are only interested in the first two, setting the Column Count to 2 will hide the last three columns.

		Do NOT set the number of columns if you are planning on using the rowspan or colspan attributes.
		*/

		$this->colCount = $numOfCols;
	}

	function setColCountFromHeadings()
	{
		/**
		This method sets the number of columns to be displayed based on the number of entries in the Headings array. To qikly set up a table from a MySQL table, first take the results of the mysql_fetch_assoc function and call setHeadingsFromKeys(), followed by setColCountFromHeadings().
		*/

		$this->colCount = count($this->headings);
	}

	function setColCountFromContents()
	{
		/**
		This method sets the number of columns to be displayed based on the longest row in the Contents array. This is the least effective way to set the Column Count. If the Contents array is large, this could be time consuming. Also, if every row in the array contains at least one NULL value, then the results will not be accurate, as NULL values are not counted.

		This method will only set the Column Count if the result it gets is greater than the current value of the Column Count. For example, if every row of the Contents array has three elements and the Column Count has been manually set to 4, a blank column will be output at the end of the table. When using this method, it's best to explicitly set the Column Count to zero first.
		*/

		for ($i = 0; $i < count($this->contents); $i++)
		{
			if (count($this->contents[$i]) > $this->colCount) $this->colCount = count($this->contents[$i]);
		}
	}

	function getColCount()
	{
		/**
		This method gets the number of columns currently in the table. It does this by counting the number of entries in the largest row of the Contents array. It ignores the Headings array.

		This method can be used in conditional statements in a QikTable template file.
		*/

		$numOfCols = 0;
		$numOfRows = $this->getRowCount();
		for ($i = 0; $i < $numOfRows; $i++)
		{
			if (count($this->contents[$i]) > $numOfCols) $numOfCols = count($this->contents[$i]);
		}
		return $numOfCols;
	}

	function getRowCount()
	{
		/**
		This method gets the number of rows currently in the table. It does this by counting the number of entries in the Contents array. It does not count the Headings array.

		This method can be used in conditional statements in a QikTable template file.
		*/

		return count($this->contents);
	}


	// Outputting the table
	/** @subsection Output Table
	*/

	function startTable($toBrowser = TRUE)
	{
		/**
		This method returns the initial TABLE tag with the parameters that have been set. If the optional parameter is omitted or evaluates to TRUE, then the output will also be sent to the browser.
		*/
		ob_start();

		echo "<!-- Table generated by QikTable - Copyright Dennis Shearin, 2001, 2002 \n  -->";
		echo "<!-- QikTable home page: http://www.qiktable.com  \n  -->";
		if ($this->comment) echo "<!-- " . $this->comment . "\n  -->";

		echo "<table ";

		if (isset($this->tableAttribs))
		{
			reset($this->tableAttribs);

			while ($tableAttrib = each($this->tableAttribs))
			{
				echo " " . $tableAttrib["key"] . "=\"" . $tableAttrib["value"] . "\"";
			}
		}

		echo ">\n";

		$code = ob_get_contents();

		if ($toBrowser)
		{
			ob_end_flush();
		}
		else
		{
			ob_end_clean();
		}

		return $code;
	}

	function endTable($toBrowser = TRUE)
	{
		/**
		This method simply returns the closing TABLE tag and sends it to the browser if the optional parameter is omitted or evaluates to TRUE.
		*/
		ob_start();

		echo "</table>";

		$code = ob_get_contents();

		if ($toBrowser)
		{
			ob_end_flush();
		}
		else
		{
			ob_end_clean();
		}

		return $code;
	}

	function printHeadings($toBrowser = TRUE)
	{
		/**
		This method returns the headings for the table. They are also sent to the browser if the optional parameter is omitted or evaluates to TRUE.
		*/

		if ($this->headings)
		{
			ob_start();

			echo "<tr>\n";

			for ($i = 0; $i < ( $this->colCount == 0 ? count($this->headings) : $this->colCount); $i++)
			{
				$headingCellKeys = array();	// Clear array
				$headingCellStyle = NULL;

				echo "<th ";

				if (isset($this->headingCellAttribs[$i]))
				{
					reset($this->headingCellAttribs[$i]);

					while ($headingCellAttrib = each($this->headingCellAttribs[$i]))
					{
						if ($headingCellAttrib["key"] != "style")
						{
							echo " " . $headingCellAttrib["key"] . "=\"" . $headingCellAttrib["value"] . "\"";
							$headingCellKeys[] = $headingCellAttrib["key"];
						}
						else
						{
							$headingCellStyle = $headingCellAttrib["value"];
						}
					}
				}


				if (isset($this->headingAttribs))
				{
					reset($this->headingAttribs);
					while ($headingAttrib = each($this->headingAttribs))
					{
						if ((!in_array($headingAttrib["key"], $headingCellKeys))) echo " " . $headingAttrib["key"] . "=\"" . $headingAttrib["value"] . "\"";
					}
				}

				echo ">";
				echo $this->headings[$i];
				echo "</th>\n";
			}

			echo "</tr>\n";

			$code = ob_get_contents();

			if ($toBrowser)
			{
				ob_end_flush();
			}
			else
			{
				ob_end_clean();
			}

			return $code;
		}
	}

	function printContents($toBrowser = TRUE)
	{
		/**
		This method returns the complete contents of the table as defined in the Contents array.  They are also sent to the browser if the optional parameter is omitted or evaluates to TRUE.
		*/

		ob_start();

		for ($i = 0; $i < count($this->contents); $i++)
		{
			$rowKeys = array();	// Set to empty array so array functions don't crash!
			$rowStyle = NULL;

			echo "<tr>\n";
			for ($j = 0; $j < ( $this->colCount == 0 ? count($this->contents[$i]) : $this->colCount) ; $j++)
			{
				$attribs = array();
				$cellStyle = NULL;
				$colStyle = NULL;
				$contentStyle = NULL;

				$colKeys = array();		// Clear array
				$cellKeys = array();		// Clear array

				echo "<td ";


				if (isset($this->cellAttribs[$i][$j]))
				{
					reset($this->cellAttribs[$i][$j]);

					while ($cellAttrib = each($this->cellAttribs[$i][$j]))
					{
						if ($cellAttrib["key"] != "style")
						{
							$attribs[$cellAttrib["key"]] = $cellAttrib["value"];
							$cellKeys[] = $cellAttrib["key"];
						}
						else
						{
							$cellStyle = $cellAttrib["value"];
						}
					}
				}

				if (isset($this->colAttribs[$j]))
				{
					reset($this->colAttribs[$j]);

					while ($colAttrib = each($this->colAttribs[$j]))
					{
						if ($colAttrib["key"] != "style")
						{
							if (!in_array($colAttrib["key"], $cellKeys))
							{
								$attribs[$colAttrib["key"]] = $colAttrib["value"];
								$colKeys[] = $colAttrib["key"];
							}
						}
						else
						{
							$colStyle = $colAttrib["value"];
						}
					}
				}

				// Row Attributes
				if (isset($this->rowAttribs[$i]))
				{
					reset($this->rowAttribs[$i]);

					while ($rowAttrib = each($this->rowAttribs[$i]))
					{
						if ($rowAttrib["key"] != "style")
						{
							if (!in_array($rowAttrib["key"], $cellKeys) && (($this->rowPriority) || !in_array($rowAttrib["key"], $colKeys)))
							{
								$attribs[$rowAttrib["key"]] = $rowAttrib["value"];
								$rowKeys[] = $rowAttrib["key"];
							}
						}
						else
						{
							$rowStyle = $rowAttrib["value"];
						}
					}
				}

				if (isset($this->contentAttribs))	// Any attributes set?
				{
					reset($this->contentAttribs);
					while ($contentAttrib = each($this->contentAttribs))
					{
						if ($contentAttrib["key"] != "style")
						{
							if ((!in_array($contentAttrib["key"], $cellKeys)) &&  (!in_array($contentAttrib["key"], $colKeys)) &&  (!in_array($contentAttrib["key"], $rowKeys))) $attribs[$contentAttrib["key"]] = $contentAttrib["value"];
						}
						else
						{
							$contentStyle = $contentAttrib["value"];
						}
					}
				}

				if (!$this->rowPriority)
				{
					$style = $contentStyle . ($contentStyle && substr(trim($contentStyle), -1) != ";" ? "; " : "") . $rowStyle . ($rowStyle && substr(trim($rowStyle), -1) != ";" ? "; " : "") . $colStyle . ($colStyle && substr(trim($colStyle), -1) != ";" ? "; " : "") . $cellStyle . ($cellStyle && substr(trim($cellStyle), -1) != ";" ? "; " : "");
				}
				else
				{
					$style = $contentStyle . ($contentStyle && substr(trim($contentStyle), -1) != ";" ? "; " : "") . $colStyle . ($colStyle && substr(trim($colStyle), -1) != ";" ? "; " : "") . $rowStyle . ($rowStyle && substr(trim($rowStyle), -1) != ";" ? "; " : "") .  $cellStyle . ($cellStyle && substr(trim($cellStyle), -1) != ";" ? "; " : "");
				}

				while ($nextAttrib = each($attribs))
				{
					echo " " . $nextAttrib["key"] . "=\"" . $nextAttrib["value"] . "\"";
				}

				if ($style) echo " style=\"$style\"";

				echo ">";
				echo $this->contents[$i][$j];
				echo "</td>\n";
			}
			echo "</tr>\n";
		}

		$code = ob_get_contents();

		if ($toBrowser)
		{
			ob_end_flush();
		}
		else
		{
			ob_end_clean();
		}

		return $code;
	}

	function printTable($toBrowser = TRUE)
	{
		/**
		This method calls each of the above methods to return the complete table.  The table is also sent to the browser if the optional parameter is omitted or evaluates to TRUE.
		*/

		$code = $this->StartTable($toBrowser);
		$code .= $this->PrintHeadings($toBrowser);
		$code .= $this->PrintContents($toBrowser);
		$code .= $this->EndTable($toBrowser);

		return $code;
	}

	function getTable()
	{
		/**
		This method returns the complete table as a string. This can be useful for nesting QikTables or saving a dynamically generated table as a static HTML file. This is an alias for printTable(FALSE).
		*/

		// ob_start();						// No longer needed

		$code = $this->printTable(FALSE);

		// $table = ob_get_contents();		// No longer needed
		// ob_end_clean();					// No longer needed

		return $code;
	}


	// Setting parameters (mutators)

	/** @subsection Set Table Attributes
	*/

	function setTableAttrib($attribute, $value)
	{

		/**
		This method can be used to set any attribute for the table - for example setTableAttrib("border", 3). The first parameter is a string containing the name of the HTML Table attribute to be set. The second parameter is the value associated with that attribute, which can vary in type.
		*/

		$attribute = strtolower($attribute);
		$this->tableAttribs[$attribute] = $value;
	}

	function setBorder($border)
	{
		/**
		This method sets the border parameter of the table. An integer parameter is expected and behavior is undefined for any other argument type.
		*/

		$this->setTableAttrib("border", $border);
	}

	function setCellSpacing($cellspacing)
	{
		/**
		This method sets the cellspacing parameter of the table (the amount of whitespace between adjacent table cells). An integer parameter is expected and behaviour is undefined for any other argument type.
		*/

		$this->setTableAttrib("cellspacing", $cellspacing);
	}

	function setCellPadding($cellpadding)
	{
		/**
		This method sets the cellpadding parameter of the table (the amount of whitespace surrounding the contents of a table cell). An integer parameter is expected and behaviour is undefined for any other argument type.
		*/

		$this->setTableAttrib("cellpadding", $cellpadding);
	}

	function setTableWidth($width)
	{
		/**
		This method can be used to set the width of the table. Values can be entered in pixels or percentages. Obviously, there is the potential for a conflict between this setting and the value passed to the setColumnWidth method. QikTable does not check for these conflicts, but leaves that to the user and browser to resolve.
		*/

		$this->setTableAttrib("width", $width);
	}

	function setTableAlign($tableAlign)
	{
		/**
		This determines how the table will be aligned with respect to the page (i.e., "left", "right", "center").
		*/

		$this->setTableAttrib("align", $tableAlign);
	}


	function setTableColor($color)
	{
		/**
		This method can be used to set the background color for the table. Standard color names can be used or the colors can be entered in hex notation (i.e., #FFFFFF, #9900FF, etc.). If not called or called with a NULL argument, the table will be the same color as the page.
		*/

		$this->setTableAttrib("bgcolor", $color);
	}

	function setTableBackground($background)
	{
		/**
		This method can be used to specify the URL of an image file to be used as a background for the table. Typically, this takes precedence over the background color.
		*/

		$this->setTableAttrib("background", $background);
	}

	function setTableClass($className)
	{
		/**
		This method can be used to specify the CSS class that applies to the table as a whole. All the characteristics of the table can be set in that class. The appearance of the table can be changed by simply redefining the class or linking to a different stylesheet.
		*/

		$this->setTableAttrib("class", $className);
	}

	function setTableID($ID)
	{
		/**
		This method allows you to uniquely identify the table with a CSS ID. This can be combined with a stylesheet to set attributes for this table or it can make it possible to dynamically manipulate the table via DHTML.
		*/

		$this->setTableAttrib("id", $ID);
	}

	function setTableStyle($style)
	{
		/**
		This method can be used to directly enter the contents of a STYLE attribute that applies to the table as a whole. No stylesheet or class definition is necessary (or this can be used to override some attributes of a class). The argument passed to this method is in the same form as the value of a STYLE attribute - for example, setTableStyle("background-color: blue; font-family: sans-serif").

		In a CSS enabled browser, styles typically take precedence over HTML attributes. This may be true even if the HTML attribute has a higher precedence in the QikTable scheme. For example, Setting a Cell color to "blue" may not work if the Table color has been set to "red" using setTableStyle.
		*/

		$this->setTableAttrib("style", $style);
	}

	function addTableStyle($style)
	{
		/**
		The method is the same as setTableStyle except that the new style information is appended to the end of the current style definition, rather than overwriting it.
		*/

		$this->tableAttribs["style"] .= ($this->tableAttribs["style"] && substr(trim($this->tableAttribs["style"]), -1) != ";" ? "; " : "") . $style . (substr(trim($style), -1) != ";" ? "; " : "");
	}

	// Set cell properties
	/** @subsection Set Cell Attributes
	Cell attributes apply only to the cells in the content area of the table (not in the heading area). Cell attributes take precedence over all other attributes (Table, Row, Column).

	The Cell is uniquely identified by the last 2 parameters - $rowNum and $colNum. These are the zero-based indices for the row and column, so the cell in the upper left-hand corner would be 0,0.
	*/

	function setCellAttrib($attribute, $value, $rowNum, $colNum)
	{
		/**
		This method can be used to set any HTML attribute for a cell - for example setCellAttrib("bgcolor", "blue", 0, 2). The first parameter is a string containing the name of the HTML Table attribute to be set. The second parameter is the value associated with that attribute, which can vary in type.
		*/

		$attribute = strtolower($attribute);
		$this->cellAttribs[$rowNum][$colNum][$attribute] = $value;
	}

	function setCellAlign($cellAlign, $rowNum, $colNum)
	{
		/**
		This methods sets the alignment of the contents within the cell indicated by $rowNum and $colNum. The first parameter should be a string representing the alignment (i.e., "right", "left", "center").
		*/

		$this->setCellAttrib("align", $cellAlign, $rowNum, $colNum);
	}

	function setCellVAlign($cellVAlign, $rowNum, $colNum)
	{
		/**
		This methods sets the vertical alignment of the contents within the cell indicated by $rowNum and $colNum. The first parameter should be a string representing the vertical alignment (i.e., "top", "bottom", "middle").
		*/

		$this->setCellAttrib("valign", $cellVAlign, $rowNum, $colNum);
	}

	function setCellColor($color, $rowNum, $colNum)
	{
		/**
		Sets the background color for the indicated cell. $color can be any named color (i.e., "red", "blue", etc.) or in hex notation (i.e., "#663399","#99CCFF", etc.). This setting will take precedence over the settings for the row and column that the cell appears in.
		*/

		$this->setCellAttrib("bgcolor", $color, $rowNum, $colNum);
	}

	function setCellBackground($background, $rowNum, $colNum)
	{
		/**
		Sets the background image for the cell. The first parameter should be the URL of an image file. Support for this function varies between browsers.
		*/

		$this->setCellAttrib("background", $background, $rowNum, $colNum);
	}

	function setCellClass($className, $rowNum, $colNum)
	{
		/**
		This method sets the CSS class for the cell.
		*/

		$this->setCellAttrib("class", $className, $rowNum, $colNum);
	}

	function setCellStyle($style, $rowNum, $colNum)
	{
		/**
		Sets the CSS style for this cell. The cell inherits all style attributes that are defined in lower level components (i.e., Table, Row, Column) and overrides any attributes that are set by this method.
		*/

		$this->setCellAttrib("style", $style, $rowNum, $colNum);
	}

	function addCellStyle($style, $rowNum, $colNum)
	{
		/**
		This method is the same as setCellStyle except that it appends the new style information to the end of the current Cell Style definition, rather than overwriting it.
		*/

		$this->cellAttribs[$rowNum][$colNum]["style"] .= ($this->cellAttribs["style"] && substr(trim($this->cellAttribs["style"]), -1) != ";" ? "; " : "") . $style . (substr(trim($style), -1) != ";" ? "; " : "");
	}

	function setCellID($id, $rowNum, $colNum)
	{
		/**
		This method allows you to uniquely identify the cell with a CSS ID. This can be combined with a stylesheet to set attributes for the cell or it can make it possible to dynamically manipulate the cell via DHTML.
		*/

		$this->setCellAttrib("id", $id, $rowNum, $colNum);
	}


	// Set Heading Cell Properties
	/** @subsection Set Heading Cell Attributes
	*/

	/**
	Since headings are printed out one line at a time, heading cells are uniquely identified by the zero-based index of the column they appear in. Heading cell settings take precedence over heading attribute settings.
	*/


	function setHeadingCellAttrib($attribute, $value, $colNum)
	{
		/**
		This method can be used to set any HTML attribute for a heading cell - for example setHeadingCellAttrib("bgcolor", "blue", 2). The first parameter is a string containing the name of the HTML Table attribute to be set. The second parameter is the value associated with that attribute, which can vary in type.
		*/

		$attribute = strtolower($attribute);
		$this->headingCellAttribs[$colNum][$attribute] = $value;
	}

	function setHeadingCellAlign($headingCellAlign, $colNum)
	{
		/**
		This methods sets the alignment of the contents within the heading cell indicated by $colNum. The first parameter should be a string representing the alignment (i.e., "right", "left", "center").
		*/


		$this->setHeadingCellAttrib("align", $headingCellAlign, $colNum);
	}

	function setHeadingCellVAlign($headingCellVAlign, $colNum)
	{
		/**
		This methods sets the vertical alignment of the contents within the cell indicated by $colNum. The first parameter should be a string representing the vertical alignment (i.e., "top", "bottom", "middle").
		*/

		$this->setHeadingCellAttrib("valign", $headingCellVAlign, $colNum);
	}

	function setHeadingCellColor($color, $colNum)
	{
		/**
		Sets the background color for the indicated cell. $color can be any named color (i.e., "red", "blue", etc.) or in hex notation (i.e., "#663399","#99CCFF", etc.).
		*/

		$this->setHeadingCellAttrib("bgcolor", $color, $colNum);
	}

	function setHeadingCellBackground($background, $colNum)
	{
		/**
		Sets the background image for the heading cell. The first parameter should be the URL of an image file. Support for this function varies between browsers.
		*/

		$this->setHeadingCellAttrib("background", $background, $colNum);
	}

	function setHeadingCellClass($className, $colNum)
	{
		/**
		This method sets the CSS class for this heading cell.
		*/

		$this->setHeadingCellAttrib("class", $className, $colNum);
	}

	function setHeadingCellStyle($style, $colNum)
	{
		/**
		Sets the CSS style for this heading cell. The cell inherits all style attributes that are defined in the Table component and overrides any attributes that are set by this method.
		*/

		$this->setHeadingCellAttrib("style", $style, $colNum);
	}

	function addHeadingCellStyle($style, $colNum)
	{
		/**
		This method is the same as setHeadingCellStyle except that it appends the new style information to the end of the current HeadingCell Style definition, rather than overwriting it.
		*/

		$this->headingCellAttribs[$colNum]["style"] .= ($this->headingCellAttribs["style"] && substr(trim($this->headingCellAttribs["style"]), -1) != ";" ? "; " : "") . $style . (substr(trim($style), -1) != ";" ? "; " : "");
	}

	function setHeadingCellID($id, $colNum)
	{
		/**
		Sets the CSS ID for the heading cell. This can be used to uniquely identify the cell for the purpose of applying SCC attributes or manipulating the cell via DHTML.
		*/

		$this->setHeadingCellAttrib("id", $id, $colNum);
	}

	// Row/Column Priority
	/** @subsection Set Row/Column Priority
	*/

	function setRowPriority($rowPriority = TRUE)
	{
		/**
		By default, if an attribute is set for a row and a column, the cell where they intersect will take on the column attribute. This method can be used to change the Row/Column precedence.

		It takes one optional boolean argument. If the argument is omitted or if it equals TRUE, then Row attributes will have priority over Column attributes. If it is set to FALSE, then the opposite will be true.
		*/

		$this->rowPriority = $rowPriority;
	}

	function setColPriority($colPriority = TRUE)
	{
		/**
		By default, Column attributes thake precedence over Row attributes. If that default behaviour has been changed, this method can be used to restore it.

		It takes one optional boolean argument. If the argument is omitted or if it equals TRUE, then Column attributes will have priority over Row attributes. If it is set to FALSE, then the opposite will be true. Note that setColPriority(FALSE) is equivalent to setRowPriority().
		*/

		$this->rowPriority = !$colPriority;
	}

	// Set Column properties
	/** @subsection Set Column Attributes
	*/

	function setColAttrib($attribute, $value, $colNum)
	{
		/**
		This method can be used to set any attribute for a column - for example setColumnAttrib("width", 80, 2). The first parameter is a string containing the name of the HTML Table attribute to be set. The second parameter is the value associated with that attribute, which can vary in type. The last parameter is the zero-based index of the column.

		By default, columns take precedence over rows. This means that if an attribute is set for a row and a column, the cell where they intersect will take on the attribute setting of the column - unless rowPriority has been set or the attribute is also set for the Cell (which always takes precedence of rows and columns).
		*/

		$attribute = strtolower($attribute);
		$this->colAttribs[$colNum][$attribute] = $value;
	}

	function setColWidth($colWidth, $colNum = NULL)
	{
		/**
		This method can be used to set the width of the column indicated by the $colNum parameter. Column width can be given in pixels or percentages.

		If the second parameter is omitted, then the width given by $colWidth is applied to all of the columns. QikTable does no checking to prevent invalid values (i.e., applying a width of 50% each of the 3 columns in a table).
		*/

		if (is_null($colNum))
		{
			$this->setContentAttrib("width", $colWidth);
		}
		else
		{
			$this->setColAttrib("width", $colWidth, $colNum);
		}
	}

	function setColAlign($align, $colNum = NULL)
	{
		/**
		This method can be used to set the alignment of the contents of the column indicated by the $colNum parameter. The first parameter should be a string indicating the alignment (i.e., "left", "right", "center").

		If the second parameter is omitted, then the alignment given by the first parameter is applied to all of the columns.
		*/

		if (is_null($colNum))
		{
			$this->setContentAttrib("align", $align);
		}
		else
		{
			$this->setColAttrib("align", $align, $colNum);
		}
	}

	function setColVAlign($align, $colNum = NULL)
	{
		/**
		This method can be used to set the vertical alignment of the contents of the column indicated by the $colNum parameter. The first parameter should be a string indicating the vertical alignment (i.e., "top", "bottom", "middle").

		If the second parameter is omitted, then the vertical alignment given by the first parameter is applied to all columns.
		*/

		if (is_null($colNum))
		{
			$this->setContentAttrib("valign", $align);
		}
		else
		{
			$this->setColAttrib("valign", $align, $colNum);
		}
	}


	function setColColor($color, $colNum = NULL)
	{
		/**
		Sets the background color for the indicated column. $color can be any named color (i.e., "red", "blue", etc.) or in hex notation (i.e., "#663399","#99CCFF", etc.).

		If the second parameter is omitted, then the background color given by the first parameter is applied to all columns.
		*/

		if (is_null($colNum))
		{
			$this->setContentAttrib("bgcolor", $color);
		}
		else
		{
			$this->setColAttrib("bgcolor", $color, $colNum);
		}
	}

	function setColBackground($background, $colNum = NULL)
	{
		/**
		Sets the background image for the column. The first parameter should be the URL of an image file. Support for this function varies between browsers.

		If the second parameter is omitted, then the background given by the first parameter is applied to all columns.
		*/

		if (is_null($colNum))
		{
			$this->setContentAttrib("background", $background);
		}
		else
		{
			$this->setColAttrib("background", $background, $colNum);
		}
	}

	function setColClass($class, $colNum = NULL)
	{
		/**
		This method sets the CSS class for the column.

		If the second parameter is omitted, then the CSS class is applied to all columns.
		*/

		if (is_null($colNum))
		{
			$this->setContentAttrib("class", $class);
		}
		else
		{
			$this->setColAttrib("class", $class, $colNum);
		}
	}

	function setColStyle($style, $colNum = NULL)
	{
		/**
		Sets the CSS style for this column. The column inherits all style attributes that are defined in lower level components (i.e., Table, Contents, and Row - if rowPriority is FALSE) and overrides any attributes that are set by this method.

		If the second parameter is omitted, then the style given by the first parameter is applied to all columns.
		*/

		if (is_null($colNum))
		{
			$this->setContentAttrib("style", $style);
		}
		else
		{
			$this->setColAttrib("style", $style, $colNum);
		}
	}

	function addColStyle($style, $colNum)
	{
		/**
		This method is the same as setColStyle except that it appends the new style information to the end of the current Column Style definition, rather than overwriting it.

		The second parameter, $colNum, is required. This method cannot be used to add style information to all columns (use addContentStyle instead).
		*/

		$this->colAttribs[$colNum]["style"] .= ($this->colAttribs["style"] && substr(trim($this->colAttribs[$colNum]["style"]), -1) != ";" ? "; " : "") . $style . (substr(trim($style), -1) != ";" ? "; " : "");
	}

	// Set Row properties
	/** @subsection Set Row Attributes
	*/

	function setRowAttrib($attribute, $value, $rowNum)
	{
		/**
		This method can be used to set any attribute for a row - for example setRowAttrib("align", "left", 2). The first parameter is a string containing the name of the HTML Table attribute to be set. The second parameter is the value associated with that attribute, which can vary in type. The last parameter is the zero-based index of the row.
		*/

		$attribute = strtolower($attribute);
		$this->rowAttribs[$rowNum][$attribute] = $value;
	}


	function setRowAlign($align, $rowNum = NULL)
	{
		/**
		This method can be used to set the alignment of the contents of the row indicated by the $rowNum parameter. The first parameter should be a string indicating the alignment (i.e., "left", "right", "center").

		If the second parameter is omitted, then the alignment given by the first parameter is applied to all rows.
		*/


		if (is_null($rowNum))
		{
			$this->setContentAttrib("align", $align);
		}
		else
		{
			$this->setRowAttrib("align", $align, $rowNum);
		}
	}

	function setRowVAlign($align, $rowNum = NULL)
	{
		/**
		This method can be used to set the vertical alignment of the contents of the row indicated by the $rowNum parameter. The first parameter should be a string indicating the alignment (i.e., "top", "bottom", "middle").

		If the second parameter is omitted, then the alignment given by the first parameter is applied to all rows.
		*/

		if (is_null($rowNum))
		{
			$this->setContentAttrib("valign", $align);
		}
		else
		{
			$this->setRowAttrib("valign", $align, $rowNum);
		}
	}


	function setRowColor($color, $rowNum = NULL)
	{
		/**
		Sets the background color for the indicated row. $color can be any named color (i.e., "red", "blue", etc.) or in hex notation (i.e., "#663399","#99CCFF", etc.).

		If the second parameter is omitted, then the background color given by the first parameter is applied to all rows.
		*/

		if (is_null($rowNum))
		{
			$this->setContentAttrib("bgcolor", $color);
		}
		else
		{
			$this->setRowAttrib("bgcolor", $color, $rowNum);
		}
	}

	function setRowBackground($background, $rowNum = NULL)
	{
		/**
		Sets the background image for the row. The first parameter should be the URL of an image file. Support for this function varies between browsers.

		If the second parameter is omitted, then the background given by the first parameter is applied to all rows.
		*/

		if (is_null($rowNum))
		{
			$this->setContentAttrib("background", $background);
		}
		else
		{
			$this->setRowAttrib("background", $background, $rowNum);
		}
	}

	function setRowClass($class, $rowNum = NULL)
	{
		/**
		This method sets the CSS class for the row.

		If the second parameter is omitted, then the CSS class is applied to all rows.
		*/

		if (is_null($rowNum))
		{
			$this->setContentAttrib("class", $class);
		}
		else
		{
			$this->setRowAttrib("class", $class, $rowNum);
		}
	}

	function setRowStyle($style, $rowNum = NULL)
	{
		/**
		Sets the CSS style for this row. The row inherits all style attributes that are defined in lower level components (i.e., Table, Contents, and Column - if rowPriority is TRUE) and overrides any attributes that are set by this method.

		If the second parameter is omitted, then the style given by the first parameter is applied to all rows.
		*/

		if (is_null($rowNum))
		{
			$this->setContentAttrib("style", $style);
		}
		else
		{
			$this->setRowAttrib("style", $style, $rowNum);
		}
	}

	function addRowStyle($style, $rowNum)
	{
		/**
		This method is the same as setRowStyle except that it appends the new style information to the end of the current style definition for the row, rather than overwriting it.

		The second parameter, $rowNum, is required. This method cannot be used to add style information to all rows (use addContentStyle instead).
		*/

		$this->rowAttribs[$rowNum]["style"] .= ($this->rowAttribs["style"] && substr(trim($this->rowAttribs[$rowNum]["style"]), -1) != ";" ? "; " : "") . $style . (substr(trim($style), -1) != ";" ? "; " : "");
	}


	// Set heading properties
	/** @subsection Set Heading Attributes
	*/


	function setHeadingAttrib($attribute, $value)
	{
		/**
		This method can be used to set any attribute for the Heading section of the table - for example setHeadingAttrib("bgcolor", "white"). The first parameter is a string containing the name of the HTML Table attribute to be set. The second parameter is the value associated with that attribute, which can vary in type.
		*/

		$attribute = strtolower($attribute);
		$this->headingAttribs[$attribute] = $value;
	}

	function setHeadingAlign($headingAlign)
	{
		/**
		This determines how the headings will be aligned with respect to the columns of the table (i.e., "left", "right", "center").
		*/

		$this->setHeadingAttrib("align", $headingAlign);
	}

	function setHeadingVAlign($headingVAlign)
	{
		/**
		This determines how the headings will be vertically aligned within the cells of the heading row (i.e., "top", "bottom", "middle").
		*/

		$this->setHeadingAttrib("valign", $headingVAlign);
	}

	function setHeadingColor($color)
	{
		/**
		This method can be used to set the background color for the headings. Standard color names can be used or the colors can be entered in hex notation (i.e., #FFFFFF, #9900FF, etc.).When not called or called with a NULL argument, the background color of the headings will be the same as the underlying table.
		*/

		$this->setHeadingAttrib("bgcolor", $color);
	}

	function setHeadingBackground($background)
	{
		/**
		This method can be used to specify the URL of an image file to be used as a background for the headings. Typically, this takes precedence over the background color. Support for this varies between browsers.
		*/

		$this->setHeadingAttrib("background", $background);
	}

	function setHeadingClass($className)
	{
		/**
		This method sets the CSS class for the headings.
		*/

		$this->setHeadingAttrib("class", $className);
	}

	function setHeadingStyle($style)
	{
		/**
		Sets the CSS style for this Heading area. The headings inherit all style attributes that are assigned to the Table and overrides any attributes that are set by this method.
		*/

		$this->setHeadingAttrib("style", $style);
	}

	function addHeadingStyle($style)
	{
		/**
		This method is the same as setHeadingStyle except that it appends the new style information to the end of the current Heading Style definition, rather than overwriting it.
		*/

		$this->headingAttribs["style"] .= ($this->headingAttribs["style"] && substr(trim($this->headingAttribs["style"]), -1) != ";" ? "; " : "") . $style . (substr(trim($style), -1) != ";" ? "; " : "");
	}

	// Set content properties
	/** @subsection Set Content Attributes
	*/

	function setContentAttrib($attribute, $value)
	{
		/**
		This method can be used to set any attribute for a content area of the table - for example setContentAttrib("align", "right"). The first parameter is a string containing the name of the HTML Table attribute to be set. The second parameter is the value associated with that attribute, which can vary in type.
		*/

		$attribute = strtolower($attribute);
		$this->contentAttribs[$attribute] = $value;
	}

	function setContentAlign($align)
	{
		/**
		This determines how the content in the body of the table will be aligned with respect to the table's columns (i.e., "left", "right", "center").
		*/
		$this->setContentAttrib("align", $align);
	}

	function setContentVAlign($align)
	{
		/**
		This determines how the contents will be vertically aligned within the cells of the table body (i.e., "top", "bottom", "middle").
		*/

		$this->setContentAttrib("valign", $align);
	}

	function setContentColor($color)
	{
		/**
		This method can be used to set the background color for the body content. Standard color names can be used or the colors can be entered in hex notation (i.e., #FFFFFF, #9900FF, etc.).If not called or called with a NULL argument, the background color of the content will be the same as the underlying table.
		*/

		$this->setContentAttrib("bgcolor", $color);
	}

	function setContentBackground($background)
	{
		/**
		This method can be used to specify the URL of an image file to be used as a background for the table's content. Typically, this takes precedence over the background color. Support for this varies between browsers.
		*/

		$this->setContentAttrib("background", $background);
	}

	function setContentClass($className)
	{
		/**
		This method sets the CSS class for the content area of the table.
		*/

		$this->setContentAttrib("class", $className);
	}

	function setContentStyle($style)
	{
		/**
		Sets the CSS style for the table's contents. All style attributes defined in the Table are inherited, then this method overrides any of the same elements.
		*/

		$this->setContentAttrib("style", $style);
	}

	function addContentStyle($style)
	{
		/**
		This method is the same as setContentStyle except that it appends the new style information to the end of the current Content Style definition, rather than overwriting it.
		*/

		$this->contentAttribs["style"] .= ($this->contentAttribs["style"] && substr(trim($this->contentAttribs["style"]), -1) != ";" ? "; " : "") . $style . (substr(trim($style), -1) != ";" ? "; " : "");
	}

	////////////// Clear properties
	/** @subsection Clear Attributes
	*/
	/**
	Any property that has been set using any of the above methods can be cleared using one of the clear methods. The clear methods remove all reference to the attribute, rather than just setting it to NULL, 0 or an empty string.

	The clear methods only work on attributes that have been set at the same level as the clear method. For example, if the method setTableColor("blue") is called, every cell in the table will have a blue background. calling clearCellColor() for a specific cell will not remove the blue background. It will only remove a background that has been set with setCellColor().
	*/

	// Clear Table properties

	function clearTableAttribs()
	{
		/**
		This method clears ALL the attributes that have been set for the table as a whole. It has no effect on attributes that have been set for rows, columns, cells, etc.
		*/

		$this->tableAttribs = array();
	}

	function clearTableAttrib($attribute)
	{
		/**
		This method clears the specified attribute that has been set for the table as a whole.
		*/

		$attribute = strtolower($attribute);
		unset($this->tableAttribs[$attribute]);
	}

	function clearBorder()
	{
		/**
		This method clears the border attribute of the table.
		*/

		$this->clearTableAttrib("border");
	}

	function clearCellSpacing()
	{
		/**
		This method clears the cell spacing attribute of the table.
		*/

		$this->clearTableAttrib("cellspacing");
	}

	function clearCellPadding()
	{
		/**
		This method clears the cell padding attribute of the table.
		*/

		$this->clearTableAttrib("cellpadding");
	}

	function clearTableWidth()
	{
		/**
		This method clears the width attribute of the table.
		*/

		$this->clearTableAttrib("width");
	}

	function clearTableAlign()
	{
		/**
		This method clears the alignment attribute of the table.
		*/

		$this->clearTableAttrib("align");
	}

	function clearTableColor()
	{
		/**
		This method clears the background color of the table. The color of the table will be the same as the color of the page it is on.
		*/

		$this->clearTableAttrib("bgcolor");
	}

	function clearTableBackground()
	{
		/**
		This method clears the background image of the table.
		*/

		$this->clearTableAttrib("background");
	}

	function clearTableClass()
	{
		/**
		This method clears the CSS class associated with the table.
		*/

		$this->clearTableAttrib("class");
	}

	function clearTableID()
	{
		/**
		This method clears the CSS ID associated with the table.
		*/

		$this->clearTableAttrib("id");
	}

	function clearTableStyle()
	{
		/**
		This method clears the CSS style associated with the table.
		*/

		$this->clearTableAttrib("style");
	}

	// Clear cell properties

	function clearCellAttribs($rowNum, $colNum)
	{
		/**
		This method clears ALL the attributes that have been set for the cell indicated by $rowNum, $colNum. It has no effect on attributes that have been set for the table, rows, columns, etc.
		*/

		$this->cellAttribs[$rowNum][$colNum] = array();
	}

	function clearCellAttrib($attribute, $rowNum, $colNum)
	{
		/**
		This method clears the specified attribute that has been set for the cell indicated by $rowNum, $colNum. It has no effect on an attribute that has been set for the table, row, column, etc.
		*/

		$attribute = strtolower($attribute);
		unset($this->cellAttribs[$rowNum][$colNum][$attribute]);
	}

	function clearCellAlign($rowNum, $colNum)
	{
		/**
		This method clears the alignment attribute that was set with setCellAlign($cellAlign, $rowNum, $colNum).
		*/

		$this->clearCellAttrib("align", $rowNum, $colNum);
	}

	function clearCellVAlign($rowNum, $colNum)
	{
		/**
		This method clears the vertical alignment attribute that was set with setCellVAlign($cellVAlign, $rowNum, $colNum).
		*/

		$this->clearCellAttrib("valign", $rowNum, $colNum);
	}

	function clearCellColor($rowNum, $colNum)
	{
		/**
		This method clears the background color that was set with setCellColor($color, $rowNum, $colNum).
		*/

		$this->clearCellAttrib("bgcolor", $rowNum, $colNum);
	}

	function clearCellBackground($rowNum, $colNum)
	{
		/**
		This method clears the background image that was set with setCellBackground($background, $rowNum, $colNum).
		*/

		$this->clearCellAttrib("background", $rowNum, $colNum);
	}

	function clearCellClass($rowNum, $colNum)
	{
		/**
		This method clears the CSS class that was set with setCellClass($className, $rowNum, $colNum).
		*/

		$this->clearCellAttrib("class", $rowNum, $colNum);
	}

	function clearCellStyle($rowNum, $colNum)
	{
		/**
		This method clears the CSS style that was set with setCellStyle($style, $rowNum, $colNum).
		*/

		$this->clearCellAttrib("style", $rowNum, $colNum);
	}

	function clearCellID($rowNum, $colNum)
	{
		/**
		This method clears the CSS ID that was set with setCellID($id, $rowNum, $colNum).
		*/

		$this->clearCellAttrib("id", $rowNum, $colNum);
	}


	// Clear Heading Cell Properties

	function clearHeadingCellAttribs($colNum)
	{
		/**
		This method clears ALL the attributes that have been set for the heading cell indicated by $colNum. It has no effect on attributes that have been set for the heading area as a whole.
		*/

		$this->headingCellAttribs[$colNum] = array();
	}

	function clearHeadingCellAttrib($attribute, $colNum)
	{
		/**
		This method clears a specific attribute that has been set for the heading cell indicated by $colNum. It has no effect on an attribute that has been set for the heading area as a whole.
		*/

		$attribute = strtolower($attribute);
		unset($this->headingCellAttribs[$colNum][$attribute]);
	}

	function clearHeadingCellAlign($colNum)
	{
		/**
		This method clears the alignment attribute that was set with setHeadingCellAlign($cellAlign, $colNum).
		*/

		$this->clearHeadingCellAttrib("align", $colNum);
	}

	function clearHeadingCellVAlign($colNum)
	{
		/**
		This method clears the vertical alignment attribute that was set with setHeadingCellVAlign($cellVAlign, $colNum).
		*/

		$this->clearHeadingCellAttrib("valign", $colNum);
	}

	function clearHeadingCellColor($colNum)
	{
		/**
		This method clears the background color that was set with setHeadingCellColor($color, $colNum).
		*/

		$this->clearHeadingCellAttrib("bgcolor", $colNum);
	}

	function clearHeadingCellBackground($colNum)
	{
		/**
		This method clears the background image that was set with setHeadingCellBackground($background, $colNum).
		*/

		$this->clearHeadingCellAttrib("background", $colNum);
	}

	function clearHeadingCellClass($colNum)
	{
		/**
		This method clears the CSS class that was set with setHeadingCellClass($className, $colNum).
		*/

		$this->clearHeadingCellAttrib("class", $colNum);
	}

	function clearHeadingCellStyle($colNum)
	{
		/**
		This method clears the CSS style that was set with setHeadingCellStyle($style, $colNum).
		*/

		$this->clearHeadingCellAttrib("style", $colNum);
	}

	function clearHeadingCellID($colNum)
	{
		/**
		This method clears the CSS ID that was set with setHeadingCellID($id, $colNum).
		*/

		$this->clearHeadingCellAttrib("id", $colNum);
	}


	// Clear Column properties

	function clearColAttribs($colNum)
	{
		/**
		This method clears ALL the attributes that have been set for the column indicated by $colNum. It has no effect on attributes that have been set for the table, rows, cells, etc.
		*/

		$this->colAttribs[$colNum] = array();
	}

	function clearColAttrib($attribute, $colNum)
	{
		/**
		This method clears a specific attribute that has been set for the column indicated by $colNum. It has no effect on an attribute that has been set for the table, row, cell, etc.
		*/

		$attribute = strtolower($attribute);
		unset($this->colAttribs[$colNum][$attribute]);
	}

	function clearColWidth($colNum)
	{
		/**
		This method clears the width attribute that was set with setColWidth($colWidth, $colNum).
		*/

		$this->clearColAttrib("width", $colNum);
	}

	function clearColAlign($colNum)
	{
		/**
		This method clears the alignment attribute that was set with setColAlign($align, $colNum).
		*/

		$this->clearColAttrib("align", $colNum);
	}

	function clearColVAlign($colNum)
	{
		/**
		This method clears the vertical alignment attribute that was set with setColVAlign($VAlign, $colNum).
		*/

		$this->clearColAttrib("valign", $colNum);
	}

	function clearColColor($colNum)
	{
		/**
		This method clears the background color that was set with setColColor($color, $colNum).
		*/

		$this->clearColAttrib("bgcolor", $colNum);
	}

	function clearColBackground($colNum)
	{
		/**
		This method clears the background image that was set with setColBackground($background, $colNum).
		*/

		$this->clearColAttrib("background", $colNum);
	}

	function clearColClass($colNum)
	{
		/**
		This method clears the CSS class that was set with setColClass($className, $colNum).
		*/

		$this->clearColAttrib("class", $colNum);
	}

	function clearColStyle($colNum)
	{
		/**
		This method clears the CSS style that was set with setColStyle($style, $colNum).
		*/

		$this->clearColAttrib("style", $colNum);
	}

	// clear Row properties

	function clearRowAttribs($rowNum)
	{
		/**
		This method clears ALL the attributes that have been set for the row indicated by $rowNum. It has no effect on attributes that have been set for the table, columns, cells, etc.
		*/

		$this->rowAttribs[$rowNum] = array();
	}

	function clearRowAttrib($attribute, $rowNum)
	{
		/**
		This method clears a specific attribute that has been set for the row indicated by $rowNum. It has no effect on attributes that have been set for the table, columns, cells, etc.
		*/

		$attribute = strtolower($attribute);
		unset($this->rowAttribs[$rowNum][$attribute]);
	}


	function clearRowAlign($rowNum)
	{
		/**
		This method clears the alignment attribute that was set with setRowAlign($align, $rowNum).
		*/

		$this->clearRowAttrib("align", $rowNum);
	}

	function clearRowVAlign($rowNum)
	{
		/**
		This method clears the vertical alignment attribute that was set with setRowVAlign($VAlign, $rowNum).
		*/

		$this->clearRowAttrib("valign", $rowNum);
	}


	function clearRowColor($rowNum)
	{
		/**
		This method clears the background color that was set with setRowColor($color, $rowNum).
		*/

		$this->clearRowAttrib("bgcolor", $rowNum);
	}

	function clearRowBackground($rowNum)
	{
		/**
		This method clears the background image that was set with setRowBackground($background, $rowNum).
		*/

		$this->clearRowAttrib("background", $rowNum);
	}

	function clearRowClass($rowNum)
	{
		/**
		This method clears the CSS class that was set with setRowClass($className, $rowNum).
		*/

		$this->clearRowAttrib("class", $rowNum);
	}

	function clearRowStyle($rowNum)
	{
		/**
		This method clears the CSS style that was set with setRowStyle($style, $rowNum).
		*/

		$this->clearRowAttrib("style", $style, $rowNum);
	}

	// clear heading properties

	function clearHeadingAttribs()
	{
		/**
		This method clears ALL the attributes that have been set for the heading area. It has no effect on attributes that have been set for a specific heading cell.
		*/

		$this->headingAttribs = array();
	}

	function clearHeadingAttrib($attribute)
	{
		/**
		This method clears a specific attribute that has been set for the heading area. It has no effect on attributes that have been set for a specific heading cell.
		*/

		$attribute = strtolower($attribute);
		unset($this->headingAttribs[$attribute]);
	}

	function clearHeadingAlign()
	{
		/**
		This method clears the alignment attribute that was set with setHeadingAlign($align).
		*/

		$this->clearHeadingAttrib("align");
	}

	function clearHeadingVAlign()
	{
		/**
		This method clears the vertical alignment attribute that was set with setHeadingVAlign($VAlign).
		*/

		$this->clearHeadingAttrib("valign");
	}

	function clearHeadingColor()
	{
		/**
		This method clears the background color that was set with setHeadingColor($color).
		*/

		$this->clearHeadingAttrib("bgcolor");
	}

	function clearHeadingBackground()
	{
		/**
		This method clears the background image that was set with setHeadingBackground($background).
		*/

		$this->clearHeadingAttrib("background");
	}

	function clearHeadingClass()
	{
		/**
		This method clears the CSS class that was set with setHeadingClass($className).
		*/

		$this->clearHeadingAttrib("class");
	}

	function clearHeadingStyle()
	{
		/**
		This method clears the CSS style that was set with setHeadingStyle($style).
		*/


		$this->clearHeadingAttrib("style");
	}


	// clear content properties

	function clearContentAttribs()
	{
		/**
		This method clears ALL the attributes that have been set for the content area of the table. It has no effect on attributes that have been set for the table, rows, cells, etc.
		*/

		$this->contentAttribs = array();
	}

	function clearContentAttrib($attribute)
	{
		/**
		This method clears a specific attribute that has been set for the content area of the table. It has no effect on attributes that have been set for the table, rows, cells, etc.
		*/

		$attribute = strtolower($attribute);
		unset($this->contentAttribs[$attribute]);
	}

	function clearContentAlign()
	{
		/**
		This method clears the alignment attribute that was set with setContentAlign($align).
		*/

		$this->clearContentAttrib("align");
	}

	function clearContentVAlign()
	{
		/**
		This method clears the vertical alignment attribute that was set with setContentVAlign($VAlign).
		*/

		$this->clearContentAttrib("valign");
	}

	function clearContentColor()
	{
		/**
		This method clears the background color that was set with setContentColor($color).
		*/

		$this->clearContentAttrib("bgcolor");
	}

	function clearContentBackground()
	{
		/**
		This method clears the background image that was set with setContentBackground($background).
		*/

		$this->clearContentAttrib("background");
	}

	function clearContentClass()
	{
		/**
		This method clears the CSS class that was set with setContentClass($className).
		*/

		$this->clearContentAttrib("class");
	}

	function clearContentStyle()
	{
		/**
		This method clears the CSS style that was set with setContentStyle($style).
		*/

		$this->clearContentAttrib("style");
	}
}

?>