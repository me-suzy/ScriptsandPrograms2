<?php

class MiniTemplate {

	var $template_html = NULL; // declare vars
	var $template_vars = array ();

	function MiniTemplate () { // class constructor

	}

	function add_file ($file) { // adds file to html var

		global $user;

		if (!$this->template_html .= @implode (@file (SYSTEM_PATH . "templates/{$user["theme"]}/$file"))) {
			$page->set_error ("page_file_error_$file", "The file ($file) could not be read into a variable. Make sure it is the correct path, and has the correct permissions.", 1);
			return FALSE;
		} else {
			return TRUE;
		}

	}

	function get_file ($file) { // get file contents

		global $user;

		if (file_exists (SYSTEM_PATH . "templates/{$user["theme"]}/$file")) {
			return @implode (@file ( (SYSTEM_PATH . "templates/{$user["theme"]}/$file")));
		} else {
			error ("get_file_$file", "The file \"$file\" is invalid, or has the wrong permissions.", 1);
			return FALSE;
		}

	}

	function set_template ($id, $value) { // function to set a var to be replaced with <{}> in the template files

		global $user;

		$this->template_vars[$id] = $value;

		return TRUE;

	}

	function return_html () { // outputs the final HTML

		$final_html = $this->template_html;

		if (count ($this->template_vars)) {
			foreach ($this->template_vars AS $key => $value) {
				$final_html = str_replace ("<\{$key}>", $value, $final_html);
			}
		}

		return $final_html;

	}

}

?>