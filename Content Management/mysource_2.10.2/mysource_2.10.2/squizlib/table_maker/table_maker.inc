<?  ##############################################
   ### SQUIZLIB ------------------------------###
  ##- Generic Include Files -- PHP4 ----------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This file is subject to version 1.0 of the
## MySource License, that is bundled with
## this package in the file LICENSE, and is
## available at through the world-wide-web at
## http://mysource.squiz.net/
## If you did not receive a copy of the MySource
## license and are unable to obtain it through
## the world-wide-web, please contact us at
## mysource@squiz.net so we can mail you a copy
## immediately.
##
## Desc: Object to be used when the need to print either a plain text or HTML table with the same contents
## $Source: /home/cvsroot/squizlib/table_maker/table_maker.inc,v $
## $Revision: 1.7 $
## $Author: brobertson $
## $Date: 2004/03/16 12:14:16 $
#######################################################################

#---------------------------------------------------------------------#

class table_maker extends Object {

	var $attr				= Array();
	var $rows				= Array();
	var $_plain_text_rows	= Array();

	var $_current_row  = 0;
	var $_cell_open    = false;
	var $_current_cell = 0;

	 #######################################
	# chars to use when in plain text mode
	var $corner_char = '+';
	var $col_char    = '|';
	var $row_char    = '-';


	 ##############################
	# Constructor
	function table_maker($attr=Array()) {
		$this->attr       = $attr;
	}# end constructor

	 ##############################
	# clears the tables contents
	function clear() {
		$this->attr				= Array();
		$this->rows				= Array();
		$this->_plain_text_rows = Array();
	}# end constructor


	 ##################################
	# open a table row
	function open_row($plain_text_border=false, $attr=Array()) {
		if ($this->_row_open) $this->close_row();

		$this->_current_row = count($this->rows);
		$this->rows[$this->_current_row] = Array('attr' => $attr, 
												'plain_text_border' => $plain_text_border, 
												'cells' => Array());
		$this->_row_open = true;

	}#end open_row()

	 ##################################
	# open a table cell
	function close_row() {
		if ($this->_cell_open) $this->close_cell();
		$this->_row_open = false;
	}#end close_row();

	 ##################################
	# open a table cell
	function open_cell($attr=Array()) {
		if ($this->_cell_open) $this->close_cell();

		$this->_current_cell = count($this->rows[$this->_current_row]['cells']);
		$this->rows[$this->_current_row]['cells'][$this->_current_cell] = Array('attr'  => $attr, 'contents' => '');
		$this->_cell_open = true;
		ob_start();	

	}#end open_cell()

	 ##################################
	# close a table cell
	function close_cell() {
		$this->rows[$this->_current_row]['cells'][$this->_current_cell]['contents'] = ob_get_contents();
		ob_end_clean();
		$this->_cell_open = false;
	}#end close_cell()

	function close_table() {
		if ($this->_row_open) $this->close_row();
	}

	 ##################
	# print the table
	function paint($html = true, $echo_pre_tags=true) {
		if ($this->_row_open) $this->close_row();

		if($html) {
			$this->_paint_html();
		} else {
			if ($echo_pre_tags) echo "<pre>\n";
			$this->_paint_plain_text();
			if ($echo_pre_tags) echo "</pre>\n";
		}

	}#end paint()

	 #############################################
	# returns a strings of the table contents
	function get_as_string($html = true) {
		if ($this->_row_open) $this->close_row();

		ob_start();	
		$this->paint($html, false);
		$str = ob_get_contents();
		ob_end_clean();
		return $str;
	}#end get_as_string()

	 #########################################
	# paint the html version of the tables
	function _paint_html() {

		echo "<table".$this->_html_attr_string($this->attr).">\n";
		for($r = 0; $r < count($this->rows); $r++) {
			$row = &$this->rows[$r];
			echo "\t<tr".$this->_html_attr_string($row['attr']).">\n";
			for($c = 0; $c < count($row['cells']); $c++) {
				$cell = &$row['cells'][$c];
				echo "\t\t<td".$this->_html_attr_string($cell['attr']).">".$cell['contents']."</td>\n";
			}#end for cells
			echo "\t</tr>\n";
		}#end for rows
		echo "</table>\n";

	}# end _paint_html();

	function _html_attr_string(&$attr) {
		$attributes = '';
		if (count($attr)) {
			for(reset($attr); NULL !== ($k = key($attr)); next($attr)) {
				$attributes .= ' '.$k.'="'.htmlspecialchars($attr[$k]).'"';
			}
		}
		return $attributes;
	}#end _html_attr_string()

	 #########################################
	# paint the plain text version of the tables
	function _paint_plain_text() {

		list($col_widths, $row_heights) = $this->_plain_text_prep_rows();
		$border_on = (isset($this->attr['border']) && (int) $this->attr['border'] > 0);

//		pre_echo("----------------------------------");
//		pre_echo("COL WIDTHS");
//		pre_echo($col_widths);
//		pre_echo("ROW HEIGHTS");
//		pre_echo($row_heights);

//		echo "<pre>\n"; //pre_echo

		$this->_paint_plain_text_row_line($col_widths, 0);
		$row_base_row_line_printed = true;
		$row_num = 0;
		for($r = 0; $r < count($this->_plain_text_rows); $r++) {
			$row = &$this->_plain_text_rows[$r];
			# if this is the table has a border or the row wants a border then print the row line
			# assuming the row above hasn't just print a line
			if (($border_on || $row['plain_text_border']) && !$row_base_row_line_printed) {
				$this->_paint_plain_text_row_line($col_widths, $r);
				$row_base_row_line_printed = true;
			} else {
				$row_base_row_line_printed = false;
			}
			# cycle through each line of the row
			for($line_num = 0; $line_num < $row_heights[$row_num]; $line_num++){
				echo $this->col_char;
				$col_num = 0;
				for($c = 0; $c < count($row['cells']); $c++) {
					$cell = &$row['cells'][$c];
					switch(strtolower($cell['attr']['align'])){
						case 'right' :
							$align = STR_PAD_LEFT;
						break;

						case 'center' :
							$align = STR_PAD_BOTH;
						break;

						default :
							$align = STR_PAD_RIGHT;

					}#end swith
			
					if ($cell['attr']['colspan'] <= 1) {
						$num_chars = $col_widths[$col_num];
					} else {
						$num_chars = 0;
						for($j = 0; $j < $cell['attr']['colspan']; $j++) {
							$num_chars += $col_widths[$col_num + $j];
						}
						$num_chars += ($cell['attr']['colspan'] - 1) * (2 + strlen($this->col_char));
					}#end if
		
					echo ' '.str_pad(array_shift($cell['contents']), $num_chars, ' ', $align).' '.$this->col_char;
					$col_num += $cell['attr']['colspan'];
				}#end for cells

				# print out any other cols
				for($j = $col_num; $j < count($col_widths);$j++) {
					echo str_repeat(' ', $col_widths[$j] + 2).$this->col_char;
				}#end for
				echo "\n";

			}#end for each row line
			$row_num++;
			# if this is the table has a border or the row wants a border then print the row line
			if ($border_on || $row['plain_text_border']) {
				$this->_paint_plain_text_row_line($col_widths, $r);
				$row_base_row_line_printed = true;
			} else {
				$row_base_row_line_printed = false;
			}
		}#end for rows

		# print the bottom of the table if the last row didn't do it for us
		if (!$row_base_row_line_printed) {
			$this->_paint_plain_text_row_line($col_widths, (count($this->_plain_text_rows) - 1));
		}


//		echo "</pre>"; //pre_echo

	}#end _paint_plain_text()

	 ##################################################
	# prep the rows for plain text printing
	function _plain_text_prep_rows() {

		# we'll play with a copy of the rows, to allow painting multiple times
		$this->_plain_text_rows = $this->rows;

		$col_widths  = Array();
		$row_heights = Array();
		for($r = 0; $r < count($this->_plain_text_rows); $r++) {
			$row = &$this->_plain_text_rows[$r];
			$col_widths[$r]  = Array();
			$row_heights[$r]  = Array();
			for($c = 0; $c < count($row['cells']); $c++) {
				$cell = &$row['cells'][$c];
				# if we have no start row then that means that we aren't a reference, so set one up
				if (!isset($cell['start_row'])) {
					$cell['start_row'] = $r;
				}
				if (isset($cell['attr']['colspan'])) {
					$colspan = (int) $cell['attr']['colspan'];
					if ($colspan <= 0) $colspan = 1;
				} else {
					$colspan = 1;
				}#end if
				if (isset($cell['attr']['rowspan'])) {
					$rowspan = (int) $cell['attr']['rowspan'];
					if ($rowspan <= 0) $rowspan = 1;
					# OK now the tricky part of moving the cells below us so that we actually span the row
					if ($rowspan > 1 && $cell['start_row'] == $r) {
						
						for($i = 1; $i < $rowspan; $i++) {
							if (!isset($this->_plain_text_rows[$r + $i])) break;
							$next_row = &$this->_plain_text_rows[$r + $i];
							# move all the cells to the right one space
							for($j = count($next_row['cells']); $j > $c; $j--){
//								pre_echo("SET $i -> ".($r + $i)." : $j = & ".($j - 1));
								$next_row['cells'][$j] = &$next_row['cells'][$j - 1];
							}#end for
							$next_row['cells'][$c] = &$cell;
						}#end for

					}#end if

				} else {
					$rowspan = 1;
				}#end if
				$cell['attr']['colspan'] = $colspan;
				$cell['attr']['rowspan'] = $rowspan;
				$cell['contents'] = $this->_plain_text_array($cell['contents']);

				# we only want to be setting this on our first run through
				if ($cell['start_row'] == $r) {
					$row_heights[$c][$r] = Array('rowspan' => $rowspan, 'height' => count($cell['contents']));
				}

				$col_width = 0;
				foreach($cell['contents'] as $line) $col_width = max($col_width, strlen($line));
				$col_widths[$r][$c] = Array('colspan' => $colspan, 'len' => $col_width);

			}#end for cells
		}#end for rows

//		pre_echo("col_widths\n------------");
//		pre_echo($col_widths);
//		pre_echo("---------------\nrow_heights\n------------");
//		pre_echo($row_heights);

		# firstly we will get the max size for each col of the single col cells
		$max_col_widths = Array();
		for(reset($col_widths); NULL !== ($r = key($col_widths)); next($col_widths)) {
			$i = 0;
			for(reset($col_widths[$r]); NULL !== ($c = key($col_widths[$r])); next($col_widths[$r])) {
				$cell = &$col_widths[$r][$c];
				if (!$max_col_widths[$i]) $max_col_widths[$i] = 0;
				if ($cell['colspan'] == 1) {
					$max_col_widths[$i] = max($max_col_widths[$i], $cell['len']);
				}
				$i += $cell['colspan'];
			}#end for
		}#end for

		# OK now we need to check the cells that span cols to make sure that they 
		# don't use more chars than the sum of the max of the single cells they span
		for(reset($col_widths); NULL !== ($r = key($col_widths)); next($col_widths)) {
			$i = 0;
			for(reset($col_widths[$r]); NULL !== ($c = key($col_widths[$r])); next($col_widths[$r])) {
				$cell = &$col_widths[$r][$c];
				if ($cell['colspan'] > 1) {
					do {
						$sum_max = 0;
						for($j = 0; $j < $cell['colspan']; $j++) {
							# if we are not the first col then include space after the col char
							$sum_max += $max_col_widths[$i + $j];
						}
						$sum_max += ($cell['colspan'] - 1) * (2 + strlen($this->col_char));

						$test_again = false;	
						# bugger, we have to adjust the max values so that this cell fits
						if ($sum_max < $cell['len']) {
							for($j = 0; $j < $cell['colspan']; $j++) $max_col_widths[$i + $j]++;
							$test_again = true;	
						}
					} while ($test_again);
				}
				$i += $cell['colspan'];
			}#end foreach
		}#end foreach

		# get the max number of lines for each of the single rowspan cells
		$max_row_heights = Array();
		for(reset($row_heights); NULL !== ($c = key($row_heights)); next($row_heights)) {
			$i = 0;
//			pre_echo("--");
			for(reset($row_heights[$c]); NULL !== ($r = key($row_heights[$c])); next($row_heights[$c])) {
//				pre_echo("i : $i, c : $c, r : $r");
				$cell = &$row_heights[$c][$r];
				if (!$max_row_heights[$i]) $max_row_heights[$i] = 0;
				if ($cell['rowspan'] == 1) {
					$max_row_heights[$i] = max($max_row_heights[$i], $cell['height']);
				}
				$i += $cell['rowspan'];
			}#end for
		}#end for
		ksort($max_row_heights);
//		pre_echo("---------- MAX_ROW_HEIGHTS ---------------");
//		pre_echo($max_row_heights);

		# OK now we need to check the cells that span rows to make sure that they 
		# don't use more lines than the sum of the max of the single cells they span
		for(reset($row_heights); NULL !== ($c = key($row_heights)); next($row_heights)) {
			$i = 0;
			for(reset($row_heights[$c]); NULL !== ($r = key($row_heights[$c])); next($row_heights[$c])) {
				$cell = &$row_heights[$c][$r];
				if ($cell['rowspan'] > 1) {
//					pre_echo($cell);
					do {
						$sum_max = 0;
						for($j = 0; $j < $cell['rowspan']; $j++) {
							$sum_max += $max_row_heights[$i + $j];
						}
						$sum_max += ($cell['rowspan'] - 1) * strlen($this->row_char);

						$test_again = false;	
						# bugger, we have to adjust the max values so that this cell fits
//						pre_echo("$sum_max < $cell[height]");
						if ($sum_max < $cell['height']) {
							for($j = 0; $j < $cell['rowspan']; $j++) $max_row_heights[$i + $j]++;
							$test_again = true;	
						}
					} while ($test_again);
				}
				$i += $cell['rowspan'];
			}#end foreach
		}#end foreach

//		pre_echo("---------------");
//		pre_echo("MAX_ROW_HEIGHTS");
//		pre_echo($max_row_heights);

		ksort($max_col_widths);
		ksort($max_row_heights);

		return Array($max_col_widths, $max_row_heights);

	}#end _plain_text_prep_rows()

	 #############################################################
	# convert a bunch of html to plain text, trying to keep 
	# the line breaks in the right spot
	function _plain_text_array($html) {
		# remove all the new lines as we are going to make our own
		$html = ereg_replace("[\r\n]",	'', $html);
		$html = str_replace('<p>',		"\n\n", $html);
		$html = str_replace('<div>',	"\n\n", $html);
		$html = str_replace('<br>',		"\n",   $html);
		$html = str_replace('<br />',	"\n",   $html);
		$html = str_replace('<li>',		"\n -",   $html);
		$html = str_replace(Array('&amp;', '&nbsp;', '&quot;'),  Array('&', ' ', '"'), $html);
		$html = strip_tags($html);
		return explode("\n", $html);
	}#end _plain_text_array()

	function _paint_plain_text_row_line(&$col_widths, $rowid) {
		echo $this->corner_char;
		foreach($col_widths as $i => $width) {
			echo str_repeat($this->row_char, $width + 2).$this->corner_char;
		}#end foreach
		echo "\n";

	}#end _paint_plain_text_row_line()

/*
	function paint_plain_text_row_line(&$col_widths, $rowid) {
		$row = &$this->_plain_text_rows[$rowid];

		echo $this->corner_char;
		foreach($col_widths as $i => $width) {

			(($show_blanks[$i]) ? ' ' : $this->row_char)			



			echo str_repeat($char, $width + 2).$this->corner_char;
		}#end foreach
		echo "\n";

	}#end paint_plain_text_row_line()
*/
}#end class

?>
