<?php

class Template {

	var $template_html = NULL; // declare vars
	var $template_vars = array ();
	var $errors = array ();
	var $loop_vars = array ();
	var $loop_id = 0;

	function Template () { } // class constructor

	function get_loop ($name, $string = NULL) { // this function will get the contents of a loop

		if ($string == NULL) {

			preg_match("/<\{loop_$name\}>(.*?)<\{\/loop_$name\}>/s", $this->template_html, $matches);

		} else {

			preg_match("/<\{loop_$name\}>(.*?)<\{\/loop_$name\}>/s", $string, $matches);

		}

		if (isset ($matches[1])) {
			return $matches[1];
		} else {
			return FALSE;
		}

	}

	function end_loop ($name, $string, $old_string = NULL) { // this function will get the contents of a loop, and create a new mini class out of it, then return all the looped through replaced information

		if ($old_string == NULL) {

			$this->template_html = preg_replace ("/<\{loop_$name\}>(.*?)<\{\/loop_$name\}>/s", $string, $this->template_html);

			return TRUE;

		} else {

			$string_final = preg_replace ("/<\{loop_$name\}>(.*?)<\{\/loop_$name\}>/s", $string, $old_string);

			return $string_final;

		}


	}

	function add_file ($file) { // adds file to html var

		global $user;

		if (!$this->template_html .= @implode (@file ("templates/{$user["theme"]}/$file"))) {
			error ("The file ($file) could not be read into a variable. Make sure it is the correct path, and has the correct permissions.", 1);
			return FALSE;
		} else {
			return TRUE;
		}

	}

	function set_template ($id, $value) { // function to set a var to be replaced with <{}> in the template files

		global $user;

		$this->template_vars[$id] = $value;

		return TRUE;

	}

	function get_file ($file) { // get file contents

		global $user;

		if (file_exists (SYSTEM_PATH . "templates/{$user["theme"]}/$file")) {
			return @implode (@file ( "templates/{$user["theme"]}/$file"));
		} else {
			error ("The file \"$file\" is invalid, or has the wrong permissions.", 1);
			return FALSE;
		}

	}

	function end_page () { // outputs the final HTML

		$final_html = $this->template_html;

		if (count ($this->template_vars)) {
			foreach ($this->template_vars AS $key => $value) {
				$final_html = str_replace ("<\{$key}>", $value, $final_html);
			}
		}

		echo "</td></tr></table><p>";

		return $final_html;

	}

}

?>