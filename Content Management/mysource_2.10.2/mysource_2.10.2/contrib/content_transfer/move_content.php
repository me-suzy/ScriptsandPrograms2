<?

// this script looks at all files in a directory,
// takes the file (which is a raw html table), parses it,
// and creates a new MySource page and bodycopy based on the
// table html in the file

// Known Good Points
// - will work with nested tables in rows with more than one column (this was hard)
// - <table> and </table> tags do not have to be on their own line
// - <tr> and </tr> tags do not have to be on their own line
// - <td> and </td> tags do not have to be on their own line
// - will set attributes for the table/row/cell from the raw html
// - wont die if it finds blank lines
// - will create MySource links for pages on the same level listed in the todo_list directory
// - will work with uppercase and lowercase tags

// Know Bad Points
// - will not work with colspans and rowspans
// - will not work with nested tables in rows with one column (will need to make a new table)
// - it is not recursive
// - will not change all links to the correct MySource link (ie. some links dead - no images)


// Initialise
include_once("../init.php");
include_once("../../include/webobject.inc");
include_once("../../squizlib/bodycopy/bodycopy.inc");

$web = &get_web_system();
$page = new Page();
$files = array();
$start_pageid = -1;
$testing = false;	// set to false to create pages
					// set to true to just view bodycopy's in browser

if ($testing == true) { echo "<b>RUNNING IN TEST MODE<br><br></b>"; }

// load all the file names into an array
// the index will be used to determine their pageid
if ($dir = @opendir("todo_list")) {
	while (($filename = readdir($dir)) !== false) {
		// skip . and .. directories
		if ($filename == "." || $filename == "..") { continue; }
		$files[] = $filename;
	}  
	closedir($dir);
}


foreach ($files as $filename) {
		
	echo "starting page: $filename<br>";
	
	$pagename = $filename;
	$pagetemplate = "standard";
	$siteid = 2;
	$parentid = 0;

	// create a new page
	if ($testing === false) { 
		$page->create($pagename,$pagetemplate,$siteid,$parentid); 
		#$page = &$web->get_page(8);
		echo "Created a new page<br>";
	}
	if ($testing == true) { 
		echo "Using page 8 for testing<br>";
		$page = &$web->get_page(6); 
	}
	
	if ($start_pageid == -1) { $start_pageid = $page->id; }
	echo "start pageid is $start_pageid<br>";
	echo "this page has been given a pageid of $page->id<br>";

	$template = &$page->get_template();
	$bodycopy_string = &$template->bodycopy;
	$bodycopy = new BodyCopy($bodycopy_string,'bodycopy');
	echo "bodycopy created<br>";

	// clear the contents of this bodycopy
	$bodycopy->delete();
	echo "bodycopy cleared<br>";
	if ($testing == true) { pre_echo($bodycopy); }

	$file = array();	// array to store the file
	$linecount = 0;		// number of lines in file

	// load the file into an array for ease of use
	// change links to MySource links as we go
	// NB. don't use functions such as 'file' here because
	// the links will never be changed
	$fp = fopen("todo_list/$filename", "r");
	while (!feof ($fp)) {
		$buffer = fgets($fp, 1024);
		$buffer = create_mysource_links($buffer);
		$file[] = $buffer;
		$linecount++;
	}
	fclose($fp);

	$i = 0;
	$intable = false;	// are we printing a table?
	$inrow = false;		// are we printing a row?
	$printed_cells = false;

	$tablecount = 0;	// current table being printed
	$rowcount = 0;		// current row being printed
	$cellcount = 0;		// current cell being printed

	while ($i < $linecount) {
		$buffer = $file[$i];
		if ($testing == true) { echo "intable: $intable<br>inrow: $inrow<br>"; }
		if ($testing == true) { echo "$i checking:"; }
		if ($testing == true) { pre_echo($buffer); }

		// skip blank lines
		if (trim($buffer) == "") { 
			$i++;
			continue; 
		}

		if ($intable == false) {
			// no table yet, so look for one
			if (look_for_table($buffer, $i)) {
				// found a table and created it
				$intable = true;
			}
		}
		else {
			if ($inrow == false){
				// in a table, but not in a row
				if (look_for_row($buffer)) {
					// found a row and created it
					$inrow = true;
				}
				else if ($intable == true && $inrow == false) { 
					if (look_for_end_table($buffer)) {
						$intable = false;
					}
				}
			}
			else {
				// we are in a table and a row
				if ($printed_cells == false) {
					look_for_cells($buffer);
					// found all cells in row and printed them
					$printed_cells = true;
					if ($testing == true) { echo "ended cell<br>"; }
				}
				else if (look_for_end_row($buffer)) {
					$inrow = false;
					$printed_cells = false;
					if ($testing == true) { echo "ended row<br>"; }
				}
		
			}
		}
		$i++;
	}


	// save the bodycopy
	if ($testing === false) { echo $template->set_bodycopy($bodycopy->pack()); }
	echo "<br>";

	echo "the bodycopy for this page is include below:<br><br>";
	pre_echo($bodycopy);
	echo "<br><br><br><br>";

}


function create_mysource_images($line) {
	global $testing;
	$output = "";
	if ($testing == true) { pre_echo($line); }
	$pos = strpos($line,"src=");
	while ($pos !== false && $line != "") {
		$output .= substr($line,0, ($pos + 4));
		$line = substr($line, ($pos + 4));
		$pos = strpos($line, " ");
		$pos2 = strpos($line, ">");
		if ($pos === false) { $pos = $pos2; }
		if ($pos > $pos2) { $pos = $pos2; }
}



// check if the first char of the link is a ' or a "" or a &quot;
// if it is one of these, then look for the next one, not a " " (space)
// otherwise, assume no quotes and just look for " " (space) as it currently does
// if it doesnt find an end tag or a space/quote thing, then (we are stuffed) - but also
// we need to get the next line and append that. We need tpo keep doing this
// until we find the end thing. When we do, make sure we dont throw up the
// wrong line (ie change gloabl $i

// also, check that the quote is before the >
// if it is not before it, then use everything up to '>' or the next " " 
// (whichever comes first) as the link
// this does not matter if we are using " " as the sep character

// also, instead of just getting all href's on a line, once one is found
// throw the line back up to the main while loop and let it handle it

// finally, remember to remove the start and end quotes from the link
// before checking the page array for the pageid
function create_mysource_links($line) {
	global $testing;
	$output = "";
	if ($testing == true) { pre_echo($line); }
	$pos = strpos($line, "href=");
	while ($pos !== false && $line != "") {
		$output .= substr($line, 0, ($pos + 5));
		$line = substr($line, ($pos + 5));
		// the first bit of $line to the next space or '>'
		// will be the filename
		$pos = strpos($line, " ");
		$pos2 = strpos($line, ">");
		if ($testing == true) { echo "pos = $pos&nbsp;&nbsp;&nbsp;pos2 = $pos2&nbsp;&nbsp;&nbsp;"; }
		if ($pos === false) { $pos = $pos2; }
		if ($pos > $pos2) { $pos = $pos2; }
		if ($testing == true) { echo "using $pos<br>"; }
		$replace_this = substr($line, 0, $pos);
		$replace_this = trim($replace_this, "\"'");
		$replace_this = str_replace("&quot;", "", $replace_this);

		// check this file to make sure it does not contain a bookmark
		$pos = strpos($replace_this, "#");
		if ($pos !== false) {
			// get the actual page name
			$replace_this = substr($replace_this, 0, $pos);
			// check to make sure there is still a page to link
			if (trim($replace_this) == "") {
				// its all over...
				$pos = strpos($line, "href=");
				echo "found a direct bookmark - no need to replace anything<br>";
				continue;
			}

		}

		// get the pageid for this file
		$pageid = get_page_id($replace_this);
		if ($testing == true) { echo "pageid is $pageid<br>"; }
		if ($pageid >= 0) { 
			$with_this = "./?p=" . $pageid;
			echo "converted $replace_this to MySource link: $with_this<br>";
			$line = str_replace($replace_this, $with_this, $line);
		}
		else {
			// leave the link as it was
			echo "could not convert $replace_this to a MySource link<br>";
			if ($testing == true) { pre_echo($line); }
		}
		$pos = strpos($line, "href=");
	}

	$output .= $line;
	if ($testing == true) { pre_echo($output); }
	return $output;

}



function get_page_id($filename) {
	global $files;
	global $start_pageid;
	global $testing;
	$pageid = -1;
	foreach ($files as $id => $file) {
		if ($file == $filename) { 
			$pageid = $start_pageid + $id; 
			break;
		}
	}
	return $pageid;
}






function look_for_table($buffer, $x) {
	global $linecount;
	global $file;
	global $i;
	global $testing;
	$pos = strpos(strtolower($buffer), "<table");
	if ($pos === false) {
		$found = false;
	}
	else {
		// found a new table
		// check first row for a nested table
		if ($testing == true) { echo "look_for_table found table tag<br>"; }
		$pos = strpos(strtolower($buffer), "<tr");
		// loop until we find the first row
		while ($pos === false) {
			if ($x > $linecount) { 
				#echo "Script died looking for start of row";
				exit; 
			}
			$x++;
			$buffer = $file[$x];
			$pos = strpos(strtolower($buffer), "<tr");
		}
		#pre_echo($buffer);
		// found the first row of the table
		// check it for a nested table
		if (check_row_for_table($x, $linecount) === false) {
			// no nested table
			$buffer = $file[$i];
			start_table($buffer);
			$found = true;
			#echo "no nested table, table created, finsihed looking for table<br>";
			#pre_echo($file[$i+1]);
		}
		else {
			// found a nested table
			echo "nested table alert in look_for_table<br>";
		}
	}
	return $found;
}



function look_for_row($buffer) {
	global $linecount;
	global $file;
	global $i;
	global $testing;
	$pos = strpos(strtolower($buffer), "<tr");
	$pos2 = strpos(strtolower($buffer), "</table>");
	if ($pos === false || $pos2 < $pos && $pos2 !== false) {
		$found = false;
		#echo "didn't find a row";
	}
	else {
		// found a new row
		// check this row for a nested table
		if (check_row_for_table($i, $linecount) === false) {
			// no nested table
			start_row($buffer);
			$found = true;
			#echo "should have created a new row by now<br>";
		}
		else {
			// nested table found
			echo "nested table alert in look_for_row<br>";
		}
	}
	return $found;
}


function look_for_end_row($buffer) {
	global $linecount;
	global $file;
	global $i;
	global $rowcount;
	global $cellcount;
	global $testing;
	$pos = strpos(strtolower($buffer), "</tr>");
	if ($pos === false) {
		$found = false;
	}
	else {
		// found the end of a row
		$found = true;
		$cellcount = 0;
		#echo "finished row $rowcount<br>";
		$rowcount++;
		// get first occurance of a '>' - this _should_ be the
		// end of the '/tr' tag
		$pos = strpos($buffer, ">") + 1;
		$file[$i] = substr($buffer, $pos);
		$i--; // do this line again to get any other tags
	}
	return $found;
}


function look_for_end_table($buffer) {
	global $linecount;
	global $file;
	global $i;
	global $tablecount;
	global $rowcount;
	global $cellcount;
	global $testing;
	$pos = strpos(strtolower($buffer), "</table>");
	if ($pos === false) {
		$found = false;
	}
	else {
		// found the end of a table
		$found = true;
		$rowcount = 0;
		$cellcount = 0;
		$tablecount++;
		// get first occurance of a '>' - this _should_ be the
		// end of the '/table' tag
		$pos = strpos($buffer, ">") + 1;
		$file[$i] = substr($buffer, $pos);
		$i--; // do this line again to get any other tags
	}
	return $found;
}


function look_for_cells($buffer) {
	global $linecount;
	global $file;
	global $i;
	global $testing;
	$cellstring = "";
	$pos = strpos(strtolower($buffer), "</tr>");
	$foundtable = false;

	while ($pos === false || $foundtable == true) {
		if ($testing == true) { echo "pos = $pos<br>"; }
		// havn't found end of row yet
		// check for table tag
		$pos2 = strpos(strtolower($buffer), "<table");
		if ($pos2 !== false) {
			// found a table
			$foundtable = true;
		}
		// check for end table tag
		$pos2 = strpos(strtolower($buffer), "</table>");
		if ($pos2 !== false) {
			// found end table
			$foundtable = false;
		}
		if ($testing == true) { pre_echo($buffer."<br>"); }
		$cellstring .= $buffer;
		if ($testing == true) { pre_echo($cellstring."<br>"); }
		$i++;
		$buffer = $file[$i];
		if ($testing == true) { echo "now im checking:"; }
		if ($testing == true) { pre_echo($buffer); }
		$pos = strpos(strtolower($buffer), "</tr>");
	}

	if ($cellstring == "") { 
		// </tr> found on same line as </td>
		if ($testing == true) { echo "found tr on same line as td<br>"; }
		$cellstring = substr($buffer, 0, $pos);
		$file[$i] = substr($buffer, $pos);
	} else {
		// grab any leftoevers and append them too
		if ($testing == true) { echo "added leftovers<br>"; }
		$pos = strpos(strtolower($buffer), "</tr>");
		$buff = substr($buffer, 0, $pos);
		$cellstring .= $buff;
		$file[$i] = substr($buffer, $pos);
	}

	// ensure that the cellstring has a </td> on the end
	if (substr(trim(strtolower($cellstring)), -5) != "</td>") {
		// need to add a </td> on the end
		if ($testing == true) { echo "need to add a td on the end<br>"; }
		$cellstring .= "</td>";
	}

	if ($testing == true) { echo "cellstring:"; }
	if ($testing == true) { pre_echo($cellstring); }

	// $cellstring now contains all cells in the row
	// cells have already been checked for
	// nested tables in look_for_row and look_for_table
	print_cells($cellstring);
	$i--;
}



function check_row_for_table($x, $lines) {
	global $file;
	global $testing;
	$start = $x;
	#echo "looking for table starting in:";
	#pre_echo($file[$x]);

	$found = false;
	$line = $file[$x];
	$pos = strpos(strtolower($line), "</tr>");
	while ($pos === false && $x < $lines) {
		// no end tr yet
		// check for table tag
		$pos2 = strpos(strtolower($line), "<table");
		if ($pos2 !== false) {
			// found a table
			$found = true;
		}
		$x++;
		$line = $file[$x];
		$pos = strpos(strtolower($line), "<table");
	}
	if ($found === true) {
		// we have found a nested table (maybe)
		$cellsfound = 0;
		$line = $file[$start];

		// first check if it is between the <tr> and </tr> tags
		$pos2 = strpos(strtolower($line), "<tr");	
		$pos3 = strpos($line, "</tr>");
		if ($pos < $pos2 || $pos > $pos3) {
			// the <table> tag is either before a <tr> tag
			// or after a </tr> tag
			// so its not a nested table after all
			#echo "no nested table found<br>";
			return false;
		}
		#echo "$pos:$pos2:$pos3<br>";

		// now check the number of columns
		$pos = strpos(strtolower($line), "</tr>");
		while ($pos === false && $x < $lines) {
			// no end row tag yet
			// check for a cell
			$pos2 = strpos(strtolower($line), "<td");
			if ($pos2 === false) {
				// not found...
			}
			else {
				// found a cell
				$cellsfound++;
				if ($cellsfound > 1) {
					// more than one column, break out
					$found = true;
					$pos = true;
				}
			}
			$x++;
			$line = $file[$x];
			$pos = strpos(strtolower($line), "</tr>");
		}
	}

	if ($cellsfound > 1) { $found = false; }
	#echo "found $cellsfound cells in table row<br>";
	return $found;
}


function start_table($tag) {
	global $tablecount;
	global $bodycopy;
	global $i;
	global $file;
	global $testing;

	// get first occurance of a '>' - this _should_ be the
	// end of the 'table' tag
	$buffer = $tag;
	$pos = strpos($buffer, ">") + 1;
	$tag = substr($buffer, 0, $pos);
	#echo "***<br>";
	#pre_echo($buffer);
	#echo "old";
	#pre_echo($file[$i]);
	$file[$i] = substr($buffer, $pos);
	#echo "new";
	#pre_echo($file[$i]);
	#echo "***<br>";
	$i--; // do this line again to get any other tags

	// create an array of attributes for this table
	$tag = trim($tag);
	$tag = substr($tag, 0, -1);
	$tag = ereg_replace (' +', ' ', $tag);
	$tag = trim($tag);
	$temp = explode(" ", $tag);
	foreach ($temp as $attrib) {
		if (strtolower($attrib) != "<table") {
			$pieces = explode("=", $attrib);
			$pieces[1] = trim($pieces[1], '"');
			$attributes[$pieces[0]] = $pieces[1];
		}
	}
	#print_r($attributes);
	// create a new table
	echo $bodycopy->insert_table($tablecount, 1, 1,"",false, $attributes);
	echo "<br>";
	#pre_echo($file[$i+1]);
	#echo "the start_table function made a new table<br>";
}



function start_row($tag) {
	global $tablecount;
	global $rowcount;
	global $bodycopy;
	global $i;
	global $file;
	global $testing;

	#echo "start row function is checking:";
	#pre_echo($tag);
	// get first occurance of a '>' - this _should_ be the
	// end of the 'tr' tag
	$buffer = $tag;
	$pos = strpos($buffer, ">") + 1;
	$tag = substr($buffer, 0, $pos);
	$file[$i] = substr($buffer, $pos);
	$i--; // do this line again to get any other tags

	if ($rowcount != 0) {
		// create a new row
		echo $bodycopy->tables[$tablecount]->insert_row($rowcount, false);
		echo "<br>";
	}

	// create an array of attributes for this row
	$tag = trim($tag);
	$tag = substr($tag, 0, -1);
	$tag = ereg_replace (' +', ' ', $tag);
	$tag = trim($tag);
	$temp = explode(" ", $tag);
	foreach ($temp as $attrib) {
		if (strtolower($attrib) != "<tr") {
			$pieces = explode("=", $attrib);
			$pieces[1] = trim($pieces[1], '"');
			echo $bodycopy->tables[$tablecount]->rows[$rowcount]->set_attribute($pieces[0], $pieces[1]);
		}
	}
	#print_r($attributes);

	#pre_echo($tag);
	if ($testing == true) { echo "the start_row function made a new row: $rowcount<br>"; }
}





function print_cells($cells) {
	global $tablecount;
	global $rowcount;
	global $cellcount;
	global $bodycopy;
	global $testing;

	$nestedtables = 0;
	$cell = "";
	
	if ($testing == true) { echo "<b>IN THE PRINT_CELLS FUNCTION FOR ROW $rowcount</b><br>"; }
	if ($testing == true) { echo "the cells:"; }
	if ($testing == true) { pre_echo($cells); }

	// fill an array with cells
	$pos = strpos(strtolower($cells), "</td>");

	while ($pos !== false && $cells != "") {
		if ($testing == true) { echo "this is my cell string:"; }
		if ($testing == true) { pre_echo($cells); }
		$pos2 = strpos(strtolower($cells), "<table");

		if ($pos2 === false || $pos < $pos2) {
			if ($testing == true) { echo "using pos = $pos<br>"; }
			$cell .= substr($cells, 0, ($pos + 5));
			if ($testing == true) { echo "insert a cell into array:"; }
			if ($testing == true) { pre_echo($cell); }
			if ($testing == true) { echo "pos = $pos<br>"; }
			$cellarray[] = $cell;
			$cell = "";
			$cells = substr($cells, ($pos + 5));
			if ($testing == true) { echo "leftovers:"; }
			if ($testing == true) { pre_echo($cells); }
			$pos = strpos(strtolower($cells), "</td>");
			if ($testing == true) { echo "newpos = $pos<br>"; }
		}
		else {
			// there is a table in here
			$pos3 = strpos(strtolower($cells), "</table>");
			$cell .= substr($cells, 0, ($pos3 + 8));
			$cells = substr($cells, ($pos3 + 8));
			if ($testing == true) { echo "choped cells"; }
			if ($testing == true) { pre_echo($cells); }
			$pos = strpos(strtolower($cells), "</td>");
			if ($testing == true) { echo "pos = $pos"; }
		}
	}

	foreach ($cellarray as $thecell) {

		if ($testing == true) { echo "thecell:"; }
		if ($testing == true) { pre_echo($thecell); }

		// get first occurance of a '>' - this _should_ be the
		// end of the 'td' tag
		$pos = strpos($thecell, ">") + 1;
		$tag = substr($thecell, 0, $pos);
		$thecell = substr($thecell, $pos, -5);

		if ($cellcount != 0 and $rowcount == 0) {
			if ($testing == true) { echo "start cell $cellcount on row $rowcount in table $tablecount<br>"; }
			echo $bodycopy->tables[$tablecount]->rows[$rowcount]->insert_cell($cellcount, false, "");
			echo "<br>";
		}

		// create an array of attributes for this cell
		$tag = trim($tag);
		$tag = substr($tag, 0, -1);
		$tag = ereg_replace (' +', ' ', $tag);
		$tag = trim($tag);
		$temp = explode(" ", $tag);
		foreach ($temp as $attrib) {
			if (strtolower($attrib) != "<td") {
				$pieces = explode("=", $attrib);
				$pieces[1] = trim($pieces[1], '"');
				echo $bodycopy->tables[$tablecount]->rows[$rowcount]->cells[$cellcount]->set_attribute($pieces[0], $pieces[1]);
				echo "<br>";
			}
		}
		if ($testing == true) { print_r($temp); }
		if ($testing == true) { echo "<br>put html into cell $cellcount on row $rowcount in table $tablecount<br>"; }
		if ($testing == true) { pre_echo($thecell); }
		echo $bodycopy->tables[$tablecount]->rows[$rowcount]->cells[$cellcount]->type->set_html($thecell);
		echo "<br>";
		if ($testing == true) { echo "the print_cell function printed a new cell: $cellcount<br>"; }
		$cellcount++;
	}


}







		#$pos = strpos($thecell, "</td>");
		
		/*
		if ($pos !== false) {
			// found a </td> tag on the same line as the <td> tag
			$buffer = $line;
			$line = substr($buffer, 0, $pos);
			$file[$i] = substr($buffer, $pos + 5);
			$i--; // do this line again to get any other tags
			$htmlstring = $line;
		}
		else {
			while ($pos === false || $nestedtables != 0) {
				// no end cell tag yet
				// check for a table tag
				$pos = strpos($line, "<table");
				if ($pos !== false) {
					$nestedtables++;
					#echo "found a nested table: $nestedtables<br>";
				}
				// check for end table tag
				$pos = strpos($line, "</table>");
				if ($pos !== false) {
					$nestedtables--;
					#echo "found end of a nested table: $nestedtables<br>";
				}

				if ($i > $linecount) { 
					#echo "Script died looking for end of cell";
					exit; 
				}
				// the html for the cell
				$htmlstring .= $line;
				#pre_echo($line);
				$i++;
				$line = $file[$i];
				$pos = strpos($line, "</td>");
			}
		}
		*/
?>
