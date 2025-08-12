<?php
Class dump {
	/******************************************************************
	* Class array_dump: generate a viewble indented tree from an array
	*
	* Author: Andrea Giammarchi [ www.3site.it ] [ andrea@3site.it ]
	* Date: 23/09/2003
	*
	*
	* Example:
	*
	* // Create and use it with 1 or more arrays
	* $arraydump = new dump();
	* echo $arraydump->dump($anArray);
	* // You can specify the array's name too
	* echo $arraydump->dump($my_array_2, "my_array_2");
	*
	* // Create directly with an array as constructor's parameter
	* // and print them
	* $arraydump = new dump($my_array_1, "my_array_1");
	* echo $arraydump->dump();
	******************************************************************/
	var $direct_show, $direct_show_name, $show_array_function_records, $show_array_function_level, $show_array_function_remember, $show_array_function_testo, $show_array_max_length;
	var $show_array_function_start, $show_array_function_b, $show_array_function_r, $show_array_function_g, $show_array_function_i, $show_array_function_f, $show_array_function_FIND;
	function array_dump($ar = "", $ar_name = "") {
	        if($ar!='') {
	        	$this->direct_show = $ar;
	        	$this->direct_show_name = $ar_name;
	        }
	        $this->max_length(50);
	}
	function max_length($how_much) {
		$this->show_array_max_length = $how_much;
		return true;
	}
	function dump($it = "", $ar_name = "") {
	        if(!isSet($this->direct_show_name)) {
	        	$this->direct_show_name = $ar_name;
	        }
		$mydump = $it == "" ? $this->return_dump($this->direct_show, $this->direct_show_name) : $this->return_dump($it, $this->direct_show_name);
	        unset($this->show_array_function_b);
	        unset($this->direct_show_name);
	        $this->max_length(50);
	        $this->show_array_function_testo = "";
	        return($mydump);
	}
	function time_elapsed_capture() {
		list($u, $s) = explode(" ",microtime());
		return ((float)$u + (float)$s);
	}
	function time_elapsed_print($item1, $item2, $rd=6) {
		return round(($item2-$item1), $rd);
	}
	function return_dump($item, $name) {
		if(!isSet($this->show_array_function_b)) {
			$this->show_array_function_b = "<span style=\"background-color: #FFFFFF; font-family: arial, Helvetica, sans-serif; font-size: 8pt; color: #000000; font-weight: bold;\">";
			$this->show_array_function_r = "<span style=\"background-color: #FFFFFF; font-family: arial, Helvetica, sans-serif; font-size: 8pt; color: #454545; font-weight: bold;\">";
			$this->show_array_function_f = "<span style=\"background-color: #FFFFFF; font-family: arial, Helvetica, sans-serif; font-size: 8pt; color: #000088; font-weight: bold;\">";
			$this->show_array_function_g = "<span style=\"background-color: #FFFFFF; font-family: arial, Helvetica, sans-serif; font-size: 8pt; color: #0000FF; font-weight: bold;\">";
			$this->show_array_function_i = "<span style=\"background-color: #FFFFFF; font-family: arial, Helvetica, sans-serif; font-size: 8pt; color: #676767; font-weight: bold;\">";
			$this->show_array_function_FIND = "<span style=\"background-color: #FFFFFF; font-family: arial, Helvetica, sans-serif; font-size: 8pt; color: #00C000; font-weight: bold;\">";
			$this->show_array_function_start = $this->time_elapsed_capture();
			$this->show_array_function_level = 0;
			if(!is_Array($item) || !isSet($item) || empty($item)) {
				$dump_result = "<br />".$this->show_array_function_r."[</span>".$this->show_array_function_b."The value </span>".$this->show_array_function_r;
				$dump_result .= "IS NOT</span> ".$this->show_array_function_b."an Array()</span>".$this->show_array_function_r."]</span><br />";
				return $dump_result;
			}
		}
		while (list($k) = each ($item)) {
			if(is_Array($item[$k])) {
				$showed = $this->show_array_function_FIND.$item[$k];
				$done_check = count($item[$k])>0 ? true : false;
			}
			else {
			     	$now_value = is_String($item[$k]) ? htmlspecialchars($item[$k]) : $item[$k];
			     	if(strlen($now_value)>$this->show_array_max_length && is_String($now_value)) {
			     		$now_value = substr($now_value, 0, $this->show_array_max_length)."...";
				}
				$showed = is_String($item[$k]) ? $this->show_array_function_g.$now_value : $this->show_array_function_i.$now_value;
				$done_check = false;
			}
			$k_col = is_String($k) ? $this->show_array_function_f.$k."</span>" : $this->show_array_function_r.$k."</span>";
			$test_control = $this->show_array_function_b."Key [</span>".$k_col.$this->show_array_function_b."] => Value [</span> ".$showed."</span> ".$this->show_array_function_b."]</span><br />";
			$total_string = strlen($this->show_array_function_remember) > 0 ? $this->show_array_function_remember."&#8735; ".$test_control : $test_control;
			$this->show_array_function_testo .= $total_string."\r";
			if($done_check) {
				$this->show_array_function_level++;
				$this->show_array_function_records[$this->show_array_function_level] = count($item[$k]);
				$this->show_array_function_remember = "";
				for($i=0; $i<$this->show_array_function_level; $i++) {
					$this->show_array_function_remember .= "&nbsp; &nbsp; &nbsp; ";
				}
				$this->return_dump($item[$k], $name);
			}
			if(isSet($this->show_array_function_records[$this->show_array_function_level]) && $this->show_array_function_records[$this->show_array_function_level] > 0) {
				$this->show_array_function_records[$this->show_array_function_level]--;
				if($this->show_array_function_records[$this->show_array_function_level]==0) {
					$this->show_array_function_level--;
					$this->show_array_function_remember = "";
					for($i=0; $i<$this->show_array_function_level; $i++) {
						$this->show_array_function_remember .= "&nbsp; &nbsp; &nbsp; ";
					}
				}
			}
		}
		$for_who = $name != '' ? $name : "ARRAY DUMP";
		$dump_result = "<br /><span style=\"background-color: #FFFFFF; font-family: arial, Helvetica, sans-serif; font-size: 9pt; color:#002255;\"><b>[ {$for_who} ]</b></span>   ";
		$dump_result .= $this->show_array_function_FIND."[ARRAY]</span>   ".$this->show_array_function_g."[STRING]</span>   ".$this->show_array_function_i."[NUMBER]</span><br />";
		$dump_result .= $this->show_array_function_b." ______________________________________</span><br />".$this->show_array_function_testo;
		$dump_result .= $this->show_array_function_b." ______________________________________</span><br />";
		$dump_result .= "<span style=\"background-color: #FFFFFF; font-family: arial, Helvetica, sans-serif; font-size: 8pt; color:#9A9A9A;\"><b>";
		$real_time = $this->time_elapsed_print($this->show_array_function_start, $this->time_elapsed_capture());
		$dump_result .= "[ dump generated in ".$real_time." seconds ]</b></span><br />";
		return "<div style=\"background-color: #FFFFFF; color: #000000;\">".$dump_result."</div>";
	}
}
?>
