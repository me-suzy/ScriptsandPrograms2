<?  ##############################################
   ### MySource ------------------------------###
  ##- Site Creator object ----- PHP4 ---------##
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
## File: xtras/site/design_areas/login/login_form.inc
## Desc: prints a custom login form, ie it had editable images
## $Source: /home/cvsroot/xtras/site/design_areas/login/login_custom_form_section.inc,v $
## $Revision: 2.7 $
## $Author: bvial $
## $Date: 2004/01/16 01:18:46 $
#######################################################################
#---------------------------------------------------------------------#

class Site_Design_Area_Login_Custom_Form_Section extends Site_Design_Area {

	var $contents;

	function Site_Design_Area_Login_Custom_Form_Section(&$_owner) {
		$this->Site_Design_Base($_owner);
		$this->customisable = true;

		$this->contents = Array();

		$this->_set_var('link_to', '', 'text', '');
		$this->_set_var('class',   '', 'text', '');
		$this->_set_var('submit_text', 'submit', 'text', '');
		$this->_set_var('submit_image', '',    'text', '');
		$this->_set_var('submit_type',  'button',  'set', '', Array('button' => 'Button', 'image' => 'Image', 'text' => 'Text'));


	}#end Site_Design_Area_Login_Custom_Form_Section()

	function create(&$tag) {

		 ########################################
		# Set up any variables
		$this->_set_variables($tag, true);
		$this->_set_nested_areas($tag, 'login');

		 ############################################
		# process the contents of the element
		foreach($tag[contents] as $index => $element) {
			# if we are dealing with a tag
			switch ($element[_type]) {
			
				case 'TAG'         : # should only be a print tag, we'll deal with it later
				case 'DESIGN_AREA' : # dealt with above so just add to contents
				case 'HTML'        : # nothing to do to this yet, so just add to contents array

					$this->contents[] = $element;

				break;

				default :
					$this->_set_error("_type '$element[_type]' unknown", __FILE__, __LINE__);
					unset($this);
					return;

			}#end switch

		}#end foreach

		# for backwards compatibility, if there is no submit_type then derive it
		if ($img = $this->get_val('submit_image')) {
			$this->set_var('submit_type', 'image');
		}#end if


	}#end create()
 
	function paint() {

		# Now look through all our contents
		foreach($this->contents as $element) {
			switch($element[_type]) {
				# plain html element just echo its contents
				case 'HTML' :
					echo $element[contents];
				break;

				# nested design area so print/paint it
				case 'DESIGN_AREA' :
					$this->_print_nested_area($element);
				break;

				# taged element ... should really only be a print element
				case 'TAG' :
					switch($element[operation])	{
						case 'print':
							$this->print_val($element);
						break;
					}

				break;

				default :
					$this->_set_error("_type '$element[_type]' unknown", __FILE__, __LINE__);
					unset($this);
					return;

			}#end switch

		}#end foreach contents

	}#end paint()

	  ##########################################################
	 # Prints the backend for the site administrator to allow 
	# them to customise this object 
	function print_user_backend($prefix_name='') {
		$web_system = &get_web_system();

		$backend = &$web_system->get_backend();
		$this->print_vars_backend($backend, $prefix_name, array('link_to'));
		$button_name = $this->get_val('button_name');

		$var_name = $prefix_name.'_pageid';

		if(isset($_REQUEST[$var_name])) {
			$pageid = $_REQUEST[$var_name];
			$this->set_custom_var('link_to', $pageid);	
			$changes_made = true;
		}

		$backend->open_field('Link To');

		$pages = $web_system->page_array_with_sticks($form_vars[siteid]);
		$pages = array('0' => '[ NONE ]') + $pages;

		?> 
		<table border="0">
			<tr>
				<td align="right">
					Page :
				</td>
				<td>
					<?= combo_box($var_name, $pages, $this->get_val('link_to')); ?> 
				</td>
			</tr> 
		</table>
		<?

		return $changes_made;

	}#end print_user_backend()

	  ###############################################################
	 # this function returns values for the special variables
	# if it is not a predefined variable it uses the value of the variable in this object
	function get_val($name) {
		global $SESSION;
		switch(strtolower($name)) {
            case 'beginform' :
			# Do we want to redirect
			if($this->get_val('link_to')) $page = &$this->get_page($this->get_val('link_to'));
			else $page = &$this->get_page();
			if ($SESSION->logged_in()) {
					$str  = '<form action="'.$page->get_url().'?'.$_SERVER['QUERY_STRING'].'" name="logout_form" method="POST">';
					$str .= '<input type="hidden" name="mysource_session_action" value="logout">';
				} else {
					global $QUERY_STRING;
					$str  = '<form action="'.$page->get_url(false,(($this->_owner->_owner->get_val('require_ssl'))?true:'')).'" name="login_prompt" method="POST">';
					$str .= '<input type=hidden name="mysource_login_key" value="'.$SESSION->new_login_key().'">';
					$str .= '<input type=hidden name="pageid" value="'.$this->get_val('link_to').'">';
					$str .= '<input type=hidden name="mysource_session_action" value="login">';
				}#end if

				return $str;

			break;

			case 'endform' :
				return '</form>';
			break;

			case 'username_input' :
				global $l;
				if ($class = $this->get_val('class')) {
					$class = ' class="'.$class.'"';
				}
				if ($size = $this->get_val('username_size')) {
					$size = ' size="'.$size.'"';
				}
				return '<input type=text name="mysource_login" value="'.$l.'" maxlength=64'.$size.$class.'>';

			break;

			case 'password_input' :

				if ($class = $this->get_val('class')) {
					$class = ' class="'.$class.'"';
				}
				if ($size = $this->get_val('password_size')) {
					$size = ' size="'.$size.'"';
				}
				return '<input type=password name="mysource_password" value="'.$l.'" maxlength=64'.$size.$class.'>';

			break;

			case 'submit_button' :

				# if we are texting the output
				$texting = ($this->get_val('submit_type') == 'text');

				# OK let's generate slightly different onclick strings depending on whether we are
				# printing a text href or not
				$onclick = ' onClick="javascript: ';
				if (!$SESSION->logged_in()) {
					$onclick .= 'if (document.login_prompt.mysource_login.value.length > 0 && document.login_prompt.mysource_password.value.length > 1) { ';
					$onclick .= ($texting) ? 'document.login_prompt.submit();' : 'return true; } else { return false;';
					$onclick .= '}';
				} else {
					$onclick .= ($texting) ? 'document.logout_form.submit();' : 'return true;';
				}

				if ($texting) $onclick .= ' return false;';
				$onclick .= '"';

				if ($class = $this->get_val('class')) {
					$class = ' class="'.$class.'"';
				}

				switch($this->get_val('submit_type')) {
					case 'text' :
						return '<a href="#"  '.$class.$onclick.'>'.$this->get_val('submit_text').'</a>';
					break;

					case 'image' :
						return '<input type="image" style="border: none" src="'.$this->get_val('IMAGE_PREFIX').$this->get_val('submit_image').'"'.$class.$onclick.'>';
					break;

					# default to button
					default:
						return '<input type="submit" value="'.$this->get_val('submit_text').'"'.$class.$onclick.'>';

				}#end switch

			break;

			case 'name' : 
				return $SESSION->user->name();
			break;

			case 'firstname' : 
				return $SESSION->user->firstname;
			break;

			case 'surname' : 
				return $SESSION->user->surname;
			break;

			case 'login' : 
				return $SESSION->user->login;
			break;

			# lets hope they declared it themselves
			default :
				return Site_Design_Area::get_val($name);
		}#end switch

	}#end get_val()

	 
}#end class Site_Design_Area_Login_Custom_Form_Section

?>